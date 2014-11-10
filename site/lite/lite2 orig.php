<?php
	if($_POST['sync_user']!=NULL || $_POST['sync_user']!=''){
		$host = $_POST['sync_user'];
		
		lite_connect($host);
		
		//INSERT RECORDS (m_patient)		
		$new_record_count = 0;
		$check_status_sql = "SELECT lite_patient_id, emr_patient_id, lite_specific_id, emr_specific_id, table_name, entry_date, sync_type FROM m_push_status WHERE push_flag ='Y' AND table_name='m_patient' AND sync_type='N'";
		$new_patient_array = get_data_array($check_status_sql);
		
		if($new_patient_array!=NULL || $new_patient_array!=''){
			ehr_connect();
			$array_m_push_update = array();
			foreach($new_patient_array as $p_array => $content){
				$pxid=0;
				
				$new_id = get_new_id($content);	
				$insert_text = insert_values($content, $new_id);
				insert_query($insert_text, $content[4]);
					
				$push_data_array = array($content[0],$new_id, $content[2],$content[3],$content[4],$content[5],'N', $content[6]);
				array_push($array_m_push_update,$push_data_array);
				unset($push_data_array);
				$new_record_count += 1;
			}
			update_m_push($array_m_push_update, 'newpatient');
			unset($array_m_push_update);
			
		}
		
		//INSERT RECORDS (table with primary keys)
		$table_array = array('m_consult',						//consult_id
							'm_patient_mc',						//mc_id
							'm_patient_fp',						//fp_id
							'm_patient_ccdev',					//ccdev_id
							'm_consult_notes',					//notes_id
							'm_patient_fp_method_service',		//fp_servcice_id
							'm_patient_fp_dropout',				//dropout_id
							'm_family',							//family_id
							'm_consult_lab',					//request_id
							'm_patient_ntp',					//ntp_id [2]
							'm_family_cct_member',				//cct_id
							'm_consult_ntp_symptopmatics',		//symptomatic_id
							'm_patient_ntp_report',				//report_id
							'm_consult_reminder',				//reminder_id
							'm_patient_fp_method',				//fp_px_id
							//'m_patient_fp_method_service',		//fp_service_id
							'm_consult_appointments');			//schedule_id
							//list of secondary table with foreign key

		foreach($table_array as $tablename){
			lite_connect($host);
			$check_status_sql = "SELECT lite_patient_id, emr_patient_id, lite_specific_id, emr_specific_id, table_name, entry_date, sync_type FROM m_push_status WHERE push_flag ='Y' AND table_name='$tablename' AND sync_type='N'";
			$new_consult_array = get_data_array($check_status_sql);
			
			if($new_consult_array!=NULL || $new_consult_array!=''){
				ehr_connect();
				$array_m_push_update = array();
				
				foreach($new_consult_array as $c_array => $new_consult){
					//print_r($new_consult);
					//echo "<br />";
					$new_id = 0;
					
					$new_id = get_new_id($new_consult);			
					$insert_text = insert_values($new_consult, $new_id);
					insert_query($insert_text, $new_consult[4]);
					
					$push_data_array = array($new_consult[0], $new_consult[1], $new_consult[2],$new_id,$new_consult[4],$new_consult[5],'N', $new_consult[6]);
					
					array_push($array_m_push_update,$push_data_array);
					unset($push_data_array);
					$new_record_count += 1;
				}
				update_m_push($array_m_push_update, 'new');
				unset($array_m_push_update);
			}
		}
	
		//INSERT RECORDS (all other tables)
		lite_connect($host);
		$check_status_sql = "SELECT lite_patient_id, emr_patient_id, lite_specific_id, emr_specific_id, table_name, entry_date, sync_type FROM m_push_status WHERE push_flag ='Y' AND sync_type='N'";
		$new_entry_array = get_data_array($check_status_sql);
		if($new_entry_array!=NULL || $new_consult_array!=''){
			ehr_connect();
			$array_m_push_update = array();
			print_r($new_entry_array);
			echo "<br />";
			foreach($new_entry_array as $entry_array => $new_entry){
				if($new_entry[4]=='m_patient_fp_pelvic' || $new_entry[4]=='m_patient_fp_pe' || $new_entry[4]=='m_patient_fp_hx' ){
				echo "SUCCESS";
					//DELETE RECORDS
					$delete_record_count = 0;
					lite_connect($host);
					$check_status_sql = "SELECT lite_patient_id, emr_patient_id, lite_specific_id, emr_specific_id, table_name, entry_date, sync_type FROM m_push_status WHERE push_flag ='Y' AND sync_type='D'";
					$delete_array = get_data_array($check_status_sql);
					//print_r($delete_array);
					if($delete_array!=NULL || $delete_array!=''){
						ehr_connect();
						$array_m_push_update = array();
						
						foreach($delete_array as $del_array => $delete_value){
							//echo "<br /><b>1 $delete_value[4]</b><br />";
							//print_r($delete_array);
							$delete_text = where_clause($delete_value);
							
							if($delete_value[4]=='m_patient_fp_obgyn' || $delete_value[4]=='m_patient_fp_hx' || $delete_value[4]=='m_consult_mc_postpartum' || $delete_value[4]=='m_consult_mc_prenatal' || $delete_value[4]=='m_consult_mc_services' || $delete_value[4]=='m_consult_mc_vaccine' || $delete_value[4]=='m_consult_ccdev_vaccine' || $delete_value[4]=='m_consult_ccdev_services' || $delete_value[4]=='m_consult_vaccine' || $delete_value[4]=='m_consult_notes_complaint' || $delete_value[4]=='m_consult_notes_dxclass'){
								//echo "<br /><br />GET ALL VALUE : SELECT * FROM $delete_value[4] WHERE $delete_text <br /><br />";
								$get_all_compare = mysql_query("SELECT * FROM $delete_value[4] WHERE $delete_text") or die("Error 200 : " .mysql_error());
								
								while($get_all_result = mysql_fetch_array($get_all_compare)){
									switch($delete_value[4]):
										case 'm_consult_mc_prenatal':
											$spc_field = 'mc_id';
											$field_to_find = 'visit_sequence';
											$value_to_find = $get_all_result['visit_sequence'];
											break;
										case 'm_consult_mc_postpartum':
											$spc_field = 'mc_id';
											$field_to_find = 'visit_sequence';
											$value_to_find = $get_all_result['visit_sequence'];
											break;
										case 'm_consult_ccdev_vaccine':
											$spc_field = 'ccdev_id';
											$field_to_find = 'vaccine_id';
											$value_to_find = $get_all_result['vaccine_id'];
											break;
										case 'm_consult_mc_vaccine':
											$spc_field = 'mc_id';
											$field_to_find = 'vaccine_id';
											$value_to_find = $get_all_result['vaccine_id'];
											break;
										case 'm_consult_ccdev_services':
											$spc_field = 'ccdev_id';
											$field_to_find = 'service_id';
											$value_to_find = $get_all_result['service_id'];
											break;
										case 'm_consult_mc_services':
											$spc_field = 'mc_id';
											$field_to_find = 'service_id';
											$value_to_find = $get_all_result['service_id'];
											break;
										case 'm_consult_vaccine';
											$spc_field = 'consult_id';
											$field_to_find = 'vaccine_id';
											$value_to_find = $get_all_result['vaccine_id'];
											break;
										case 'm_consult_notes_dxclass':
											$spc_field = 'notes_id';
											$field_to_find = 'class_id';
											$value_to_find = $get_all_result['class_id'];
											break;
										case 'm_consult_notes_complaint':
											$spc_field = 'notes_id';
											$field_to_find = 'complaint_id';
											$value_to_find = $get_all_result['complaint_id'];
											break;
										case 'm_patient_fp_hx':
											$spc_field = 'fp_id';
											$field_to_find = 'history_id';
											$value_to_find = $get_all_result['history_id'];
											break;
										case 'm_patient_fp_obgyn':
											$spc_field = 'fp_id';
											$field_to_find = 'obshx_id';
											$value_to_find = $get_all_result['obshx_id'];
											break;
										default:
											$value_to_find = '';
											break;
									endswitch;
									$missing = find_missing($delete_value[0],$delete_value[2],$field_to_find, $value_to_find, $spc_field, $delete_value[4]);
									//echo "DELETE FROM $delete_value[4] WHERE $delete_text $missing <br /><br /> ";
									if($missing!=NULL || $missing!=''){
										ehr_connect();
										//echo "<b>DELETE QUERY : </b>DELETE FROM $delete_value[4] WHERE $delete_text $missing";
										$delete_query = mysql_query("DELETE FROM $delete_value[4] WHERE $delete_text $missing") or die("Error 227 : ".mysql_error());
									}
								}
								ehr_connect();
							}else{
								if($delete_value[4]=='m_consult_notes'){
									$delete_text = "notes_id = '$delete_value[3]'";
								}
								echo "<br /><br />DELETE FROM $delete_value[4] WHERE $delete_text<br /><br />";
								$delete_query = mysql_query("DELETE FROM $delete_value[4] WHERE $delete_text") or die("Error 132 : " .mysql_error());
							}
							$push_data_array = array($delete_value[0], $delete_value[1], $delete_value[2],$delete_value[3],$delete_value[4],$delete_value[5],'N', $delete_value[6]);
							array_push($array_m_push_update,$push_data_array);
							unset($push_data_array);
							$delete_record_count += 1;
						}
						update_m_push($array_m_push_update, 'update');
						unset($array_m_push_update);
						$array_m_push_update = array();
			
					}
				}//end of delete fp_pe
				
				print_r($new_entry);
				echo "<br />";
				$new_id = '';
				$insert_text = insert_values($new_entry, $new_id);
				if($new_entry[4]=='m_consult_notes_complaint' || $new_entry[4]=='m_consult_notes_dxclass'){
				//echo "TETETETE" .$new_entry[4];
					$where_clause = "notes_id = '$new_entry[7]' AND consult_id = '$new_entry[8]' AND patient_id = '$new_entry[9]'";
					$get_all = mysql_query("SELECT * FROM $new_entry[4] WHERE $where_clause") or die("Error 93 : " .mysql_error());
					
					while(mysql_fetch_array($get_all)){
						switch($new_entry[4]):
							case 'm_consult_notes_complaint':
								$where_clause = $where_clause. " AND complaint_id = '$new_entry[10]'";
								break;
							case 'm_consult_notes_dxclass':
								$where_clause = $where_clause. " AND class_id = '$new_entry[10]'";
								break;
							default:
							
								break;
						endswitch;
						
						$check_consult_exist_sql = mysql_query("SELECT * FROM $new_entry[4] WHERE $where_clause")or die("Error 92 : ".mysql_error());
						if(mysql_num_rows($check_consult_exist_sql)==0){
							insert_query($insert_text, $new_entry[4]);
						}
					}
				}elseif($new_entry[4]=='m_patient_fp_pelvic' || $new_entry[4]=='m_patient_fp_pe' || $new_entry[4]=='m_patient_fp_obgyn' || $new_entry[4]=='m_patient_fp_hx' || $new_entry[4]=='m_consult_mc_prenatal' || $new_entry[4]=='m_consult_mc_postpartum' || $new_entry[4]=='m_consult_mc_services' || $new_entry[4]=='m_consult_ccdev_vaccine' || $new_entry[4]=='m_consult_mc_vaccine' || $new_entry[4]=='m_consult_vaccine'){
					$insert_text = '';
					lite_connect($host);
					echo "<b>new entry :</b><br />";
					print_r($new_entry);
					echo "FUCK";
					switch($new_entry[4]):
						case 'm_consult_ccdev_vaccine':
							$where_field = 'ccdev_id';
							break;
						case 'm_consult_vaccine':
							$where_field = 'consult_id';
							break;
						case 'm_patient_fp_hx':
							$where_field = 'fp_id';
							break;
						case 'm_patient_fp_pe' || 'm_patient_fp_obgyn' || 'm_patient_fp_pelvic':
							$where_field = 'fp_id';
							break;
						default:
							$where_field = 'mc_id';
							break;
					endswitch;
					//echo "SELECT * FROM $new_entry[4] WHERE mc_id = '$new_entry[2]' AND patient_id = '$new_entry[0]'";
					//echo "<br /><br />SELECT * FROM $new_entry[4] WHERE patient_id = '$new_entry[0]' AND $where_field = '$new_entry[7]'<br />";
					if($new_entry[4]=='m_patient_fp_pe' || $new_entry[4]=='m_patient_fp_pelvic'){
						$get_mc_lite_date = mysql_query("SELECT * FROM $new_entry[4] WHERE patient_id = '$new_entry[0]' AND $where_field = '$new_entry[2]'")or die("Error 113 : ".mysql_error());
						//echo "PE SQL: SELECT * FROM $new_entry[4] WHERE patient_id = '$new_entry[0]' AND $where_field = '$new_entry[2]'";
					}else{
						$get_mc_lite_date = mysql_query("SELECT * FROM $new_entry[4] WHERE patient_id = '$new_entry[0]' AND $where_field = '$new_entry[2]'")or die("Error 113 : ".mysql_error());
					}
					
					while($get_mc_lite_result = mysql_fetch_array($get_mc_lite_date)){
						echo "PASOK";
						ehr_connect();
						//echo "<br /><br />SELECT * FROM $new_entry[4] WHERE mc_id = '$new_entry[7]' AND consult_id = '$new_entry[9]' AND patient_id = '$new_entry[10]' AND prenatal_date = '$get_mc_lite_result[prenatal_date]'<br /><br />";
						switch($new_entry[4]):
								case 'm_consult_mc_prenatal':
									$mc_id = $new_entry[7];
									$consult_id = $new_entry[9];
									$patient_id = $new_entry[10];
									$field_to_find = 'visit_sequence';
									break;
								case 'm_consult_mc_postpartum':
									$mc_id = $new_entry[7];
									$consult_id = $new_entry[8];
									$patient_id = $new_entry[9];
									$field_to_find = 'visit_sequence';
									break;
								case 'm_consult_mc_services':	
									$mc_id = $new_entry[7];
									$consult_id = $new_entry[8];
									$patient_id = $new_entry[10];
									$field_to_find = 'service_id';
									break;
								case 'm_consult_mc_vaccine':
									$mc_id = $new_entry[7];
									$consult_id = $new_entry[8];
									$patient_id = $new_entry[9];
									$field_to_find = 'vaccine_id';
									break;
								case 'm_consult_vaccine':
									$spc_field = 'consult_id';
									$consult_id = $new_entry[7];
									$patient_id = $new_entry[8];
									$field_to_find = 'vaccine_id';
									break;
								case 'm_consult_ccdev_vaccine':
									$spc_field = 'ccdev_id';
									$ccdev_id = $new_entry[7];
									$consult_id = $new_entry[8];
									$patient_id = $new_entry[9];
									$field_to_find = 'vaccine_id';
									break;
								case 'm_patient_fp_hx':
									$spc_field = 'fp_id';
									$fp_id = $new_entry[7];
									$consult_id = $new_entry[9];
									$patient_id = $new_entry[8];
									$field_to_find = 'history_id';
									break;
								case 'm_patient_fp_pe':
									$spc_field = 'fp_id';
									$fp_id = $new_entry[7];
									$consult_id = $new_entry[10];
									$patient_id = $new_entry[8];
									$field_to_find = 'pe_id';
									break;
								case 'm_patient_fp_pelvic':
									$spc_field = 'fp_id';
									$fp_id = $new_entry[7];
									$consult_id = $new_entry[9];
									$patient_id = $new_entry[8];
									$field_to_find = 'pelvic_id';
									break;
								case 'm_patient_fp_obgyn':
									$spc_field = 'fp_id';
									$fp_id = $new_entry[7];
									$patient_id = $new_entry[8];
									$field_to_find = 'obshx_id';
									break;
								default:
								
									break;
						endswitch;
						
						if($new_entry[4]=="m_consult_vaccine"){
							$check_mc_date = mysql_query("SELECT * FROM $new_entry[4] WHERE $spc_field = '$consult_id' AND patient_id = '$patient_id' AND $field_to_find = '$get_mc_lite_result[$field_to_find]'")or die("Error 156 : ".mysql_error());
						}elseif($new_entry[4]=="m_consult_ccdev_vaccine"){
							$check_mc_date = mysql_query("SELECT * FROM $new_entry[4] WHERE $spc_field = '$ccdev_id' AND patient_id = '$patient_id' AND $field_to_find = '$get_mc_lite_result[$field_to_find]'")or die("Error 156 : ".mysql_error());
						}elseif($new_entry[4]=="m_patient_fp_hx" || $new_entry[4]=="m_patient_fp_pe" || $new_entry[4]=="m_patient_fp_pelvic" || $new_entry[4]=="m_patient_fp_obgyn"){
							//echo "SELECT * FROM $new_entry[4] WHERE $spc_field = '$fp_id' AND patient_id = '$patient_id' AND $field_to_find = '".$get_mc_lite_result[$field_to_find]."'";
							$check_mc_date = mysql_query("SELECT * FROM $new_entry[4] WHERE $spc_field = '$fp_id' AND patient_id = '$patient_id' AND $field_to_find = '".$get_mc_lite_result[$field_to_find]."'")or die("Error 156 : ".mysql_error());
						}else{
							$check_mc_date = mysql_query("SELECT * FROM $new_entry[4] WHERE mc_id = '$mc_id' AND consult_id = '$consult_id' AND patient_id = '$patient_id' AND $field_to_find = '$get_mc_lite_result[$field_to_find]'")or die("Error 158 : ".mysql_error());
						}
						
						if(mysql_num_rows($check_mc_date)==0){
							$field_count = mysql_num_fields($check_mc_date);
							$insert_text = '';
							for($i=0;$i<$field_count;$i++){
								$field_name = mysql_field_name($check_mc_date, $i);
								if($field_name=='mc_id'){
									if($insert_text=='' || $insert_text==NULL){
										$insert_text = "'".$mc_id."'";
									}else{
										$insert_text = $insert_text. ", '".$mc_id."'";
									}
								}elseif($field_name=='fp_id'){
									if($insert_text=='' || $insert_text==NULL){
										$insert_text = "'".$fp_id."'";
									}else{
										$insert_text = $insert_text. ", '".$fp_id."'";
									}
									
								}elseif($field_name=='consult_id'){
									if($insert_text=='' || $insert_text==NULL){
										$insert_text = "'".$consult_id."'";
									}else{
										$insert_text = $insert_text. ", '".$consult_id."'";
									}
									
								}elseif($field_name=='ccdev_id'){
									if($insert_text=='' || $insert_text==NULL){
										$insert_text = "'".$ccdev_id."'";
									}else{
										$insert_text = $insert_text. ", '".$ccdev_id."'";
									}
									
								}elseif($field_name=='patient_id'){
									if($insert_text=='' || $insert_text==NULL){
										$insert_text = "'".$patient_id."'";
									}else{
										$insert_text = $insert_text. ", '".$patient_id."'";
									}
								}else{
									if($insert_text=='' || $insert_text==NULL){
										$insert_text = "'".$get_mc_lite_result[$field_name]."'";
									}else{
										$insert_text = $insert_text. ", '".$get_mc_lite_result[$field_name]."'";
									}
									
								}
							}
							echo $insert_text;
							insert_query($insert_text, $new_entry[4]);
						}
						lite_connect($host);
					}
				}else{
					ehr_connect();
					insert_query($insert_text, $new_entry[4]);
				}
				
				if($new_entry[4]=='m_family_address' || $new_entry[4]=='m_family_members'){
					lite_connect($host);
					$get_fam_id = mysql_query("SELECT lite_specific_id FROM m_push_status WHERE lite_specific_id = '$new_entry[2]' AND table_name ='m_family' LIMIT 1") or die("Error 66 : ".mysql_error());
					$result_fam_id = mysql_fetch_array($get_fam_id);
						
					$fam_id = $result_fam_id['lite_specific_id'];
					$push_data_array = array($new_entry[0], $new_entry[1], $new_entry[2],$fam_id,$new_entry[4],$new_entry[5],'N', $new_entry[6]);
					ehr_connect();
				}else{
					$push_data_array = array($new_entry[0], $new_entry[1], $new_entry[2],$new_entry[3],$new_entry[4],$new_entry[5],'N', $new_entry[6]);
				}
				
				array_push($array_m_push_update,$push_data_array);
				unset($push_data_array);
				$new_record_count += 1;
			}
			update_m_push($array_m_push_update, 'new');
			unset($array_m_push_update);
			
		}
		
		//UPDATE RECORDS
		$update_record_count = 0;
		lite_connect($host);
		$check_status_sql = "SELECT lite_patient_id, emr_patient_id, lite_specific_id, emr_specific_id, table_name, entry_date, sync_type FROM m_push_status WHERE push_flag ='Y' AND sync_type='U'";
		$update_array = get_data_array($check_status_sql);
		if($update_array!=NULL || $update_array!=''){
			//print_r($update_array);
			ehr_connect();
			$array_m_push_update = array();
			
			foreach($update_array as $up_array => $update_value){
				$update_text = '';
				$update_text = update_values($update_value);
				$where_text = where_clause($update_value);
				
				if($update_value[4]=='m_consult_ccdev_services' || $update_value[4]=='m_consult_vaccine' || $update_value[4]=='m_consult_ccdev_vaccine'){
					lite_connect($host);
					//echo "SELECT * FROM $update_value[4] WHERE ccdev_id =$update_value[7] AND consult_id =$update_value[8]";
					
					switch($update_value[4]):
						case 'm_consult_ccdev_services':
							$where_ccdev = "ccdev_id ='$update_value[2]' AND patient_id ='$update_value[0]'";
							break;
						case 'm_consult_ccdev_vaccine':
							$where_ccdev = "ccdev_id ='$update_value[2]' AND patient_id ='$update_value[0]'";
							break;
						case 'm_consult_vaccine':
							$where_ccdev = "consult_id ='$update_value[2]' AND patient_id ='$update_value[0]'";
							break;
						default:
							
							break;
					endswitch;
					
					//echo "SELECT * FROM $update_value[4] WHERE $where_ccdev";
					$update_ccdev_services_sql = mysql_query("SELECT * FROM $update_value[4] WHERE $where_ccdev") or die("Error 127 :".mysql_error());
					
					while($update_result = mysql_fetch_array($update_ccdev_services_sql)){
						$add_update ='';
						switch($update_value[4]):
							case 'm_consult_ccdev_services':
								$add_update = ", ccdev_timestamp = '$update_result[ccdev_timestamp]',ccdev_service_date = '$update_result[ccdev_service_date]', age_on_service = '$update_result[age_on_service]' ";
								$add_where = " AND service_id = '$update_result[service_id]'";
								break;
							case 'm_consult_ccdev_vaccine':
								$add_update = ", age_on_vaccine= $update_result[age_on_vaccine], vaccine_timestamp = '$update_result[vaccine_timestamp]', actual_vaccine_date = '$update_result[actual_vaccine_date]'";
								$add_where = " AND vaccine_id = '$update_result[vaccine_id]'";
								break;
							case 'm_consult_vaccine':
								$add_update = ", vaccine_timestamp = '$update_result[vaccine_timestamp]', actual_vaccine_date = '$update_result[actual_vaccine_date]'";
								$add_where = " AND vaccine_id = '$update_result[vaccine_id]'";
								break;
							default:
								break;
						endswitch;
						
						//echo "<br /><br />UPDATE $update_value[4] SET $update_text $add_update WHERE $where_text <br /> <br />";
						$update_ccdev_sql = "UPDATE $update_value[4] SET $update_text $add_update WHERE $where_text $add_where";
						update_ccdev($update_ccdev_sql);
					}
					lite_connect($host);
					
				}elseif($update_value[4]=='m_patient_fp_obgyn' || $update_value[4]=='m_patient_fp_hx' || $update_value[4]=='m_patient_fp_pe' || $update_value[4]=='m_patient_fp_pelvic' || $update_value[4]=='m_consult_mc_prenatal' || $update_value[4]=='m_consult_mc_postpartum' || $update_value[4]=='m_consult_mc_services' || $update_value[4]=='m_consult_mc_vaccine'){
					$insert_text = '';
					lite_connect($host);
				
					switch($update_value[4]):
						case 'm_consult_ccdev_vaccine':
							$where_field = 'ccdev_id';
							break;
						case 'm_consult_vaccine':
							$where_field = 'consult_id';
							break;
						case 'm_patient_fp_hx':
							$where_field = 'fp_id';
							break;
						case 'm_patient_fp_pe' || 'm_patient_fp_obgyn' || 'm_patient_fp_pelvic':
							$where_field = 'fp_id';
							break;
						default:
							$where_field = 'mc_id';
							break;
					endswitch;
					//echo "SELECT * FROM $update_value[4] WHERE mc_id = '$update_value[2]' AND patient_id = '$update_value[0]'";
					$get_mc_lite_date = mysql_query("SELECT * FROM $update_value[4] WHERE patient_id = '$update_value[0]' AND $where_field = '$update_value[2]' ")or die("Error 113 : ".mysql_error());

					while($get_mc_lite_result = mysql_fetch_array($get_mc_lite_date)){
						ehr_connect();
						//echo "<br /><br />SELECT * FROM $update_value[4] WHERE mc_id = '$update_value[7]' AND consult_id = '$update_value[9]' AND patient_id = '$update_value[10]' AND prenatal_date = '$get_mc_lite_result[prenatal_date]'<br /><br />";
						switch($update_value[4]):
								case 'm_consult_mc_prenatal':
									$mc_id = $update_value[7];
									$consult_id = $update_value[9];
									$patient_id = $update_value[10];
									$field_to_find = 'visit_sequence';
									$visit_sequence = $update_value[16];
									break;
								case 'm_consult_mc_postpartum':
									$mc_id = $update_value[7];
									$consult_id = $update_value[8];
									$patient_id = $update_value[9];
									$field_to_find = 'visit_sequence';
									$visit_sequence = $update_value[14];
									break;
								case 'm_consult_mc_services':	
									$mc_id = $update_value[7];
									$consult_id = $update_value[8];
									$patient_id = $update_value[10];
									$field_to_find = 'service_id';
									$service_id = $update_value[13];
									break;
								case 'm_patient_fp_hx':
									$mc_id = $update_value[7];
									$consult_id = $update_value[8];
									$patient_id = $update_value[9];
									$field_to_find = 'history_id';
									$vaccine_id = $update_value[14];
									break;
								case 'm_patient_fp_pe':
									$mc_id = $update_value[7];
									$consult_id = $update_value[10];
									$patient_id = $update_value[8];
									$field_to_find = 'pe_id';
									$vaccine_id = $update_value[14];
									break;
								case 'm_patient_fp_pelvic':
									$mc_id = $update_value[7];
									$consult_id = $update_value[9];
									$patient_id = $update_value[8];
									$field_to_find = 'pelvic_id';
									$vaccine_id = $update_value[10];
									break;
								case 'm_patient_fp_obgyn':
									$mc_id = $update_value[7];
									$patient_id = $update_value[8];
									$field_to_find = 'obshx_id';
									$vaccine_id = $update_value[9];
									break;
								default:
								
									break;
						endswitch;
						
						if($update_value[4]=="m_consult_vaccine"){
							$check_mc_date = mysql_query("SELECT * FROM $update_value[4] WHERE $spc_field = '$consult_id' AND patient_id = '$patient_id' AND $field_to_find = '$get_mc_lite_result[$field_to_find]'")or die("Error 156 : ".mysql_error());
						}elseif($update_value[4]=="m_consult_ccdev_vaccine"){
							$check_mc_date = mysql_query("SELECT * FROM $update_value[4] WHERE $spc_field = '$ccdev_id' AND patient_id = '$patient_id' AND $field_to_find = '$get_mc_lite_result[$field_to_find]'")or die("Error 156 : ".mysql_error());
						}elseif($update_value[4]=="m_patient_fp_hx" || $update_value[4]=="m_patient_fp_pe" || $update_value[4]=="m_patient_fp_pelvic" || $update_value[4]=="m_patient_fp_obgyn"){
							$check_mc_date = mysql_query("SELECT * FROM $update_value[4] WHERE $spc_field = '$mc_id' AND patient_id = '$patient_id' AND $field_to_find = '$get_mc_lite_result[$field_to_find]'")or die("Error 156 : ".mysql_error());
						}else{
							$check_mc_date = mysql_query("SELECT * FROM $update_value[4] WHERE mc_id = '$mc_id' AND consult_id = '$consult_id' AND patient_id = '$patient_id' AND $field_to_find = '$get_mc_lite_result[$field_to_find]'")or die("Error 158 : ".mysql_error());
						}
						
						if(mysql_num_rows($check_mc_date)>=1){
							$field_count = mysql_num_fields($check_mc_date);
							$insert_text = '';
							$update_where = '';
							for($i=0;$i<$field_count;$i++){
								$field_name = mysql_field_name($check_mc_date, $i);
								
								if($field_name=='mc_id' || $field_name=='consult_id' || $field_name=='ccdev_id' || $field_name=='patient_id' || $field_name==$field_to_find){
									$value = $$field_name;
									if($update_where=='' || $update_where==NULL){
										$update_where = "$field_name = '$value'";
									}else{
										$update_where = $update_where." AND $field_name = '$value'";
									}
						
								}else{
									if($insert_text=='' || $insert_text==NULL){
										$insert_text = " $field_name='".$get_mc_lite_result[$field_name]."'";
									}else{
										$insert_text = $insert_text. ", $field_name='".$get_mc_lite_result[$field_name]."'";
									}
								}
							}
							
							$update_ccdev_sql = "UPDATE $update_value[4] SET $insert_text WHERE $update_where";
							echo $update_ccdev_sql;
							update_ccdev($update_ccdev_sql);
						}
						lite_connect($host);
					}
				}else{
					//echo "<br />UPDATE $update_value[4] SET $update_text WHERE $where_text <br />";
					$update_query = mysql_query("UPDATE $update_value[4] SET $update_text WHERE $where_text") or die("Error 135 : " .mysql_error());
				}
				
				$push_data_array = array($update_value[0], $update_value[1], $update_value[2],$update_value[3],$update_value[4],$update_value[5],'N', $update_value[6]);
				array_push($array_m_push_update,$push_data_array);
				unset($push_data_array);
				$update_record_count += 1;
				
			}
			update_m_push($array_m_push_update, 'update');
			unset($array_m_push_update);
		
		}
		
		//DELETE RECORDS
		$delete_record_count = 0;
		lite_connect($host);
		$check_status_sql = "SELECT lite_patient_id, emr_patient_id, lite_specific_id, emr_specific_id, table_name, entry_date, sync_type FROM m_push_status WHERE push_flag ='Y' AND sync_type='D'";
		$delete_array = get_data_array($check_status_sql);
		print_r($delete_array);
		if($delete_array!=NULL || $delete_array!=''){
			ehr_connect();
			$array_m_push_update = array();
			
			foreach($delete_array as $del_array => $delete_value){
				//echo "<br /><b>1 $delete_value[4]</b><br />";
				print_r($delete_array);
				echo "<br />";
				$delete_text = where_clause($delete_value);
				
				if($delete_value[4]=='m_patient_fp_obgyn' || $delete_value[4]=='m_patient_fp_hx' || $delete_value[4]=='m_consult_mc_postpartum' || $delete_value[4]=='m_consult_mc_prenatal' || $delete_value[4]=='m_consult_mc_services' || $delete_value[4]=='m_consult_mc_vaccine' || $delete_value[4]=='m_consult_ccdev_vaccine' || $delete_value[4]=='m_consult_ccdev_services' || $delete_value[4]=='m_consult_vaccine' || $delete_value[4]=='m_consult_notes_complaint' || $delete_value[4]=='m_consult_notes_dxclass'){
					//echo "<br /><br />GET ALL VALUE : SELECT * FROM $delete_value[4] WHERE $delete_text <br /><br />";
					$get_all_compare = mysql_query("SELECT * FROM $delete_value[4] WHERE $delete_text") or die("Error 200 : " .mysql_error());
					
					while($get_all_result = mysql_fetch_array($get_all_compare)){
						switch($delete_value[4]):
							case 'm_consult_mc_prenatal':
								$spc_field = 'mc_id';
								$field_to_find = 'visit_sequence';
								$value_to_find = $get_all_result['visit_sequence'];
								break;
							case 'm_consult_mc_postpartum':
								$spc_field = 'mc_id';
								$field_to_find = 'visit_sequence';
								$value_to_find = $get_all_result['visit_sequence'];
								break;
							case 'm_consult_ccdev_vaccine':
								$spc_field = 'ccdev_id';
								$field_to_find = 'vaccine_id';
								$value_to_find = $get_all_result['vaccine_id'];
								break;
							case 'm_consult_mc_vaccine':
								$spc_field = 'mc_id';
								$field_to_find = 'vaccine_id';
								$value_to_find = $get_all_result['vaccine_id'];
								break;
							case 'm_consult_ccdev_services':
								$spc_field = 'ccdev_id';
								$field_to_find = 'service_id';
								$value_to_find = $get_all_result['service_id'];
								break;
							case 'm_consult_mc_services':
								$spc_field = 'mc_id';
								$field_to_find = 'service_id';
								$value_to_find = $get_all_result['service_id'];
								break;
							case 'm_consult_vaccine';
								$spc_field = 'consult_id';
								$field_to_find = 'vaccine_id';
								$value_to_find = $get_all_result['vaccine_id'];
								break;
							case 'm_consult_notes_dxclass':
								$spc_field = 'notes_id';
								$field_to_find = 'class_id';
								$value_to_find = $get_all_result['class_id'];
								break;
							case 'm_consult_notes_complaint':
								$spc_field = 'notes_id';
								$field_to_find = 'complaint_id';
								$value_to_find = $get_all_result['complaint_id'];
								break;
							case 'm_patient_fp_hx':
								$spc_field = 'fp_id';
								$field_to_find = 'history_id';
								$value_to_find = $get_all_result['history_id'];
								break;
							case 'm_patient_fp_obgyn':
								$spc_field = 'fp_id';
								$field_to_find = 'obshx_id';
								$value_to_find = $get_all_result['obshx_id'];
								break;
							default:
								$value_to_find = '';
								break;
						endswitch;
						$missing = find_missing($delete_value[0],$delete_value[2],$field_to_find, $value_to_find, $spc_field, $delete_value[4]);
						//echo "DELETE FROM $delete_value[4] WHERE $delete_text $missing <br /><br /> ";
						if($missing!=NULL || $missing!=''){
							ehr_connect();
							//echo "<b>DELETE QUERY : </b>DELETE FROM $delete_value[4] WHERE $delete_text $missing";
							$delete_query = mysql_query("DELETE FROM $delete_value[4] WHERE $delete_text $missing") or die("Error 227 : ".mysql_error());
						}
					}
					ehr_connect();
				}else{
					if($delete_value[4]=='m_consult_notes'){
						$delete_text = "notes_id = '$delete_value[3]'";
					}
					echo "<br /><br />DELETE FROM $delete_value[4] WHERE $delete_text<br /><br />";
					$delete_query = mysql_query("DELETE FROM $delete_value[4] WHERE $delete_text") or die("Error 132 : " .mysql_error());
				}
				$push_data_array = array($delete_value[0], $delete_value[1], $delete_value[2],$delete_value[3],$delete_value[4],$delete_value[5],'N', $delete_value[6]);
				array_push($array_m_push_update,$push_data_array);
				unset($push_data_array);
				$delete_record_count += 1;
			}
			update_m_push($array_m_push_update, 'update');
			unset($array_m_push_update);
			
		}
		
		echo "You have successfully Synced : <br />";
		echo "<span style='padding: 0 20px 0 20px; width: 120px; display: inline-block;'>New Records </span>= $new_record_count <br />";
		echo "<span style='padding: 0 20px 0 20px; width: 120px; display: inline-block;'>Updated Records </span>= $update_record_count <br />";
		echo "<span style='padding: 0 20px 0 20px; width: 120px; display: inline-block;'>Deleted Records </span>= $delete_record_count";
		
	}else{
		echo "<form name='lite2-sync' method='post' action=''>";
		echo "Enter IP-Address to Sync : ";
		
			echo "<input type='text' name='sync_user' />";
			/*
			echo "<select name='sync_user'>";
				echo "<option value='localhost'>Localhost</option>";
				echo "<option value='sample'>Sample</option>";
			echo "</select>";
			echo "<input type='submit' />";
			*/
		echo "</form>";
	}
	
	function update_ccdev($update_ccdev_sql){
		ehr_connect();
		//echo $update_ccdev_sql. "<br />";
		$update_query = mysql_query($update_ccdev_sql) or die("Error 196 : " .mysql_error());
		
	}
	
	function find_missing($px_id,$spc_id,$field_to_find, $value_to_find, $spc_field, $table){
		$host = $_POST['sync_user'];
		lite_connect($host);
		//echo "<br /> FIND : SELECT * FROM $table WHERE $spc_field = $spc_id AND patient_id = $px_id AND $field_to_find = '$value_to_find' <br />";
		$compare_lite_sql = mysql_query("SELECT * FROM $table WHERE $spc_field = $spc_id AND patient_id = $px_id AND $field_to_find = '$value_to_find'") or die("Error 261 : " .mysql_error());
		
		if(($compare_lite_count = mysql_num_rows($compare_lite_sql))<=0){
			$delete_where = " AND $field_to_find = '$value_to_find'";
			return $delete_where;
		}
		
	}
	
	function insert_query(){
		if (func_num_args()>0) {
			$arg_list = func_get_args();
			$list = $arg_list[0];
			$table_name = $arg_list[1];
		}
		echo "INSERT INTO $table_name VALUES($list)<br /><br />";
		$insert_sql = mysql_query("INSERT INTO $table_name VALUES($list)") or die("Error 21 : ".mysql_error());	
	}
		
	function get_new_id(){
		if (func_num_args()>0) {
			$arg_list = func_get_args();
			$list = $arg_list[0];
		}
			
		$cur_prime_key_sql = mysql_query("SELECT * FROM $list[4]") or die("Error 68 : ".mysql_error());
		$cur_prime_key = mysql_field_name($cur_prime_key_sql,0);
			
		$new_id_sql = mysql_query("SELECT $cur_prime_key AS new_id FROM $list[4] ORDER BY $cur_prime_key DESC LIMIT 0,1") or die("Error 71 : ".mysql_error());
		
		if(mysql_num_rows($new_id_sql)==0){
			$new_id = 1;
		}else{
			$new_id_result = mysql_fetch_array($new_id_sql);	
			$new_id = $new_id_result['new_id'] + 1;
		}

		return $new_id;
	}
		
	function update_m_push(){
		$host = $_POST['sync_user'];
		lite_connect($host);
		if (func_num_args()>0) {
			$arg_list = func_get_args();
			$list = $arg_list[0];
			$type = $arg_list[1];
		}
		//foreach list[0];
		foreach($list as $key => $value){
			switch ($type){
				case 'newpatient':
					$update_patient_id = mysql_query("UPDATE m_push_status SET emr_patient_id=$value[1] WHERE lite_patient_id=$value[0] AND emr_patient_id=0") or die("Error 213 : " .mysql_error());
					$update_sql = "UPDATE m_push_status SET push_flag='N' WHERE lite_patient_id=$value[0] AND lite_specific_id =0";
					//echo $update_sql;
					break;
				case 'update':
					$update_sql = "UPDATE m_push_status SET push_flag='N' WHERE lite_patient_id = '$value[0]' AND emr_patient_id = '$value[1]' AND lite_specific_id='$value[2]' AND table_name='$value[4]' AND entry_date='$value[5]'";
					//echo $update_sql;
					break;
				default:
					$update_sql = "UPDATE m_push_status SET emr_specific_id='$value[3]', push_flag='N' WHERE lite_patient_id = '$value[0]' AND emr_patient_id = '$value[1]' AND lite_specific_id='$value[2]' AND table_name='$value[4]' AND entry_date='$value[5]' AND sync_type='N'";
					break;
			}
			$update_status = mysql_query($update_sql) or die("Error 45 : ".mysql_error());
		}
	}
		
	function get_data_array($check_status_sql){
		
		$check_status_query = mysql_query("$check_status_sql") or die("Error 57: ". mysql_error());
		
		if(mysql_num_rows($check_status_query)>=1){
			
			//create an array of all the entry in ehr_lite when m_push_status = 'Y'
			if(!isset($all_array)){
				$all_array = array();
			}else{
				unset($all_array);
			}
			
			if(!isset($collect_array)){
				$collect_array = array();
			}else{
				unset($collect_array);
			}
			
			
			while(list($lite_px_id, $emr_px_id, $lite_specific_id, $emr_specific_id, $table_name, $entry_date, $sync_type)=mysql_fetch_array($check_status_query)){
				echo $check_status_sql;
				$data_array = array($lite_px_id, $emr_px_id, $lite_specific_id, $emr_specific_id, $table_name, $entry_date, $sync_type);
				echo "<br />";
				print_r($data_array);
				echo "<br />";
				$prime_key_sql = mysql_query("SELECT * FROM $table_name") or die("Error 88 :".mysql_error());
				if($table_name=='m_consult_ptgroup'){
					$prime_key = mysql_field_name($prime_key_sql,1);
				}else{
					$prime_key = mysql_field_name($prime_key_sql,0);
				}
				
				$key_count = mysql_num_fields($prime_key_sql)-1;
				$all_keys = array();
				for($x=0;$x<=$key_count;$x++){
					$keys = mysql_field_name($prime_key_sql,$x);
					array_push($all_keys, $keys); 
				}
				
				//Get all information from the specific table name
				if($table_name=='m_patient'){
					$get_data_sql = mysql_query("SELECT * FROM $table_name WHERE patient_id ='$lite_px_id'") or die("Error 93: ". mysql_error());
				}else{
					if(in_array('patient_id',$all_keys)){
						if($table_name=='m_patient_philhealth'){
							$get_data_sql = mysql_query("SELECT * FROM $table_name WHERE patient_id ='$lite_px_id'") or die("Error 95: ". mysql_error());
							$dup_sql = "SELECT * FROM $table_name WHERE patient_id ='$lite_px_id'";
							echo "A: SELECT * FROM $table_name WHERE patient_id ='$lite_px_id' <br />";
						}else{
							$get_data_sql = mysql_query("SELECT * FROM $table_name WHERE $prime_key ='$lite_specific_id' AND patient_id ='$lite_px_id'") or die("Error 95: ". mysql_error());
							$dup_sql = "SELECT * FROM $table_name WHERE $prime_key ='$lite_specific_id' AND patient_id ='$lite_px_id'";
							echo "B: SELECT * FROM $table_name WHERE $prime_key ='$lite_specific_id' AND patient_id ='$lite_px_id' <br />";
						}
					}else{
						$get_data_sql = mysql_query("SELECT * FROM $table_name WHERE $prime_key ='$lite_specific_id'") or die("Error 98: ". mysql_error());
						$dup_sql = "SELECT * FROM $table_name WHERE $prime_key ='$lite_specific_id'";
						echo "C: SELECT * FROM $table_name WHERE $prime_key ='$lite_specific_id' <br />";
					}
					unset($all_keys);
				}
				
				if(mysql_num_rows($get_data_sql)==0){
					$emr_px_id = $emr_px_id;
				}
				
				$get_data_result = mysql_fetch_array($get_data_sql);
						
				$field_count = mysql_num_fields($get_data_sql);
				
				for($i=0;$i<$field_count;$i++){
					$field_name = mysql_field_name($get_data_sql, $i);
					switch($field_name):
						case 'patient_id':
							if($emr_px_id==0){
								array_push($data_array, 'na');
							}else{
								array_push($data_array, $emr_px_id);
							}
							break;
							
						case 'consult_id':
							if($get_data_result[$field_name]){
								$lite_specific_id = $get_data_result[$field_name];
							}
							
							$emr_id = get_existing_id('m_consult', $field_name, $lite_specific_id);
							array_push($data_array, $emr_id);
							break;
							
						case 'mc_id':
							if($get_data_result[$field_name]){
								$lite_specific_id = $get_data_result[$field_name];
							}
							
							$emr_id = get_existing_id('m_patient_mc', $field_name, $lite_specific_id);
							array_push($data_array, $emr_id);
							break;
							
						case 'fp_id':
							if($get_data_result[$field_name]){
								$lite_specific_id = $get_data_result[$field_name];
							}
							
							$emr_id = get_existing_id('m_patient_fp', $field_name, $lite_specific_id);
							array_push($data_array, $emr_id);
							break;
							
						case 'fp_px_id':
							if($get_data_result[$field_name]){
								$lite_specific_id = $get_data_result[$field_name];
							}
							
							$emr_id = get_existing_id('m_patient_fp_method', $field_name, $lite_specific_id);
							array_push($data_array, $emr_id);
							break;
							
						case 'notes_id':
							if($sync_type=='D'){
								$emr_id = $emr_specific_id;
							}else{
								if($get_data_result[$field_name]){
									$lite_specific_id = $get_data_result[$field_name];
								}
								
								$emr_id = get_existing_id('m_consult_notes', $field_name, $lite_specific_id); 
							}
							array_push($data_array, $emr_id);
							break;
						/*	
						case 'fp_service_id':
							if($get_data_result[$field_name]){
								$lite_specific_id = $get_data_result[$field_name];
							}
							
							$emr_id = get_existing_id('m_patient_fp_method_service', $field_name, $lite_specific_id);
							array_push($data_array, $emr_id);
							break;
					*/
						case 'dropout_id':
							if($get_data_result[$field_name]){
								$lite_specific_id = $get_data_result[$field_name];
							}
							
							$emr_id = get_existing_id('m_patient_fp_dropout', $field_name, $lite_specific_id);
							array_push($data_array, $emr_id);
							break;
							
						case 'family_id':
							if($get_data_result[$field_name]){
								$lite_specific_id = $get_data_result[$field_name];
							}
							
							$emr_id = get_existing_id('m_family', $field_name, $lite_specific_id);
							array_push($data_array, $emr_id);
							break;
							
						case 'cct_id':
							if($get_data_result[$field_name]){
								$lite_specific_id = $get_data_result[$field_name];
							}
							
							$emr_id = get_existing_id('m_family_cct_member', $field_name, $lite_specific_id);
							array_push($data_array, $emr_id);
							break;
						case 'ccdev_id':
							if($get_data_result[$field_name]){
								$lite_specific_id = $get_data_result[$field_name];
							}
							
							$emr_id = get_existing_id('m_patient_ccdev', $field_name, $lite_specific_id);
							array_push($data_array, $emr_id);
							break;
							
						case 'request_id':
							if($get_data_result[$field_name]){
								$lite_specific_id = $get_data_result[$field_name];
							}
							
							$emr_id = get_existing_id('m_consult_lab', $field_name, $lite_specific_id);
							array_push($data_array, $emr_id);
							break;
							
						case 'ntp_id':
							if($get_data_result[$field_name]){
								$lite_specific_id = $get_data_result[$field_name];
							}
							
							$emr_id = get_existing_id('m_patient_ntp', $field_name, $lite_specific_id);
							array_push($data_array, $emr_id);
							break;
							
						case 'symptomatic_id':
							if($get_data_result[$field_name]){
								$lite_specific_id = $get_data_result[$field_name];
							}
							
							$emr_id = get_existing_id('m_consult_ntp_symptopmatics', $field_name, $lite_specific_id);
							array_push($data_array, $emr_id);
							break;
							
						case 'report_id':
							if($get_data_result[$field_name]){
								$lite_specific_id = $get_data_result[$field_name];
							}
							
							$emr_id = get_existing_id('m_patient_ntp_report', $field_name, $lite_specific_id);
							array_push($data_array, $emr_id);
							break;
							
						case 'reminder_id':
							if($get_data_result[$field_name]){
								$lite_specific_id = $get_data_result[$field_name];
							}
							
							$emr_id = get_existing_id('m_consult_reminder', $field_name, $lite_specific_id);
							array_push($data_array, $emr_id);
							break;
						case 'fp_px_id':
							if($get_data_result[$field_name]){
								$lite_specific_id = $get_data_result[$field_name];
							}
							
							$emr_id = get_existing_id('m_patient_fp_method', $field_name, $lite_specific_id);
							array_push($data_array, $emr_id);
							break;
							
						case 'schedule_id':
							if($get_data_result[$field_name]){
								$lite_specific_id = $get_data_result[$field_name];
							}
							
							$emr_id = get_existing_id('m_consult_appointments', $field_name, $lite_specific_id);
							array_push($data_array, $emr_id);
							break;
							
						default:
							array_push($data_array, $get_data_result[$i]);
							break;
							
					endswitch;
				}
					
				//start of checking for duplicate
				$duplicate = 0;
				$inside_array = array($data_array[4],$data_array[7],$data_array[8]);
				array_push($collect_array, $inside_array);
				
				if(in_array($data_array,$all_array)){
					//check for same entry in array
					switch($data_array[4]): //check duplicate entry base on primary key, default will move the cursor to next record
						case 'm_consult_ccdev_services' || 'm_consult_vaccine' || 'm_consult_ccdev_vaccine':
							$inside_array = array($data_array[4],$data_array[7],$data_array[8]);
							if(in_array($inside_array, $collect_array)){
								$$data_array[4] = $$data_array[4] + 1;
								//echo "pumasok";
							}
							break;
							
						case 'm_consult_ccdev_vaccines':
							$inside_array = array($data_array[4],$data_array[7],$data_array[8]);
							if(in_array($inside_array, $collect_array)){
								$$data_array[4] = $$data_array[4] + 1;
							}
								
							break;
							
						default:
							$$data_array[4] = $$data_array[4] + 1;
								
							break;
					endswitch;
						
					$duplicate = $$data_array[4];
					echo $data_array[4]. " id :". $duplicate. "<br />";
					unset($inside_array);
				}
					
				if($duplicate>=1){
					//Go to next row if duplicate was found
					unset($data_array);
					//$data_array = array();
					$data_array = array($lite_px_id, $emr_px_id, $lite_specific_id, $emr_specific_id, $table_name, $entry_date, $sync_type);
					$dup_sql_con = $dup_sql . " LIMIT $duplicate, 1";
					echo $dup_sql_con ."<br />";
					//echo $table_name." table name<br />";
						
					//echo "<br />SQL for now!: ". $dup_sql_con."<br />";
					//echo "table = ". $table_name. " lite_id : " .$lite_specific_id."<br />";
					$dup_query = mysql_query($dup_sql_con);
					$get_ids = mysql_fetch_array($dup_query);
					//$ccdev_id = $get_data_result['ccdev_id'];
					//echo "table = ". $table_name. " lite_id : " .$lite_specific_id."<br /> get_id = " .$ccdev_id."<br />";
					
					$field_count = mysql_num_fields($dup_query);
					
					for($i=0;$i<$field_count;$i++){
						$field_name = mysql_field_name($dup_query, $i);
						switch($field_name):
							case 'patient_id':
								if($emr_px_id==0){
									array_push($data_array, 'na');
								}else{
									array_push($data_array, $emr_px_id);
								}
								break;
								
							case 'consult_id':
								if($get_ids[$field_name]){
									$lite_specific_id = $get_ids[$field_name];
								}
							
								$emr_id = get_existing_id('m_consult', $field_name, $lite_specific_id);
								array_push($data_array, $emr_id);
								break;
					
							case 'mc_id':
								if($get_ids[$field_name]){
									$lite_specific_id = $get_ids[$field_name];
								}
							
								$emr_id = get_existing_id('m_patient_mc', $field_name, $lite_specific_id);
								array_push($data_array, $emr_id);
								break;
								
							case 'fp_id':
								if($get_ids[$field_name]){
									$lite_specific_id = $get_ids[$field_name];
								}
							
								$emr_id = get_existing_id('m_patient_fp', $field_name, $lite_specific_id);
								array_push($data_array, $emr_id);
								break;
							
							case 'fp_px_id':
								if($get_ids[$field_name]){
									$lite_specific_id = $get_ids[$field_name];
								}
							
								$emr_id = get_existing_id('m_patient_fp_method', $field_name, $lite_specific_id);
								array_push($data_array, $emr_id);
							break;
								
							case 'notes_id':
								if($get_ids[$field_name]){
									$lite_specific_id = $get_ids[$field_name];
								}
							
								$emr_id = get_existing_id('m_consult_notes', $field_name, $lite_specific_id); 
								array_push($data_array, $emr_id);
								break;
							/*	
							case 'fp_service_id':
								if($get_ids[$field_name]){
									$lite_specific_id = $get_ids[$field_name];
								}
							
								$emr_id = get_existing_id('m_patient_fp_method_service', $field_name, $lite_specific_id);
								array_push($data_array, $emr_id);
								break;
							*/	
							case 'dropout_id':
								if($get_ids[$field_name]){
									$lite_specific_id = $get_ids[$field_name];
								}
							
								$emr_id = get_existing_id('m_patient_fp_dropout', $field_name, $lite_specific_id);
								array_push($data_array, $emr_id);
								break;
								
							case 'family_id':
								if($get_ids[$field_name]){
									$lite_specific_id = $get_ids[$field_name];
								}
							
								$emr_id = get_existing_id('m_family', $field_name, $lite_specific_id);
								array_push($data_array, $emr_id);
								break;
								
							case 'cct_id':
								if($get_ids[$field_name]){
									$lite_specific_id = $get_ids[$field_name];
								}
							
								$emr_id = get_existing_id('m_family_cct_member', $field_name, $lite_specific_id);
								array_push($data_array, $emr_id);
								break;
								
							case 'ccdev_id':
								if($get_ids['ccdev_id']){
									$lite_specific_id = $get_ids['ccdev_id'];
								}
								
								$emr_id = get_existing_id('m_patient_ccdev', $field_name, $lite_specific_id);
								array_push($data_array, $emr_id);
								break;
								
							case 'request_id':
								if($get_ids[$field_name]){
									$lite_specific_id = $get_ids[$field_name];
								}
							
								$emr_id = get_existing_id('m_consult_lab', $field_name, $lite_specific_id);
								array_push($data_array, $emr_id);
								break;
								
							case 'ntp_id':
								if($get_ids[$field_name]){
									$lite_specific_id = $get_ids[$field_name];
								}
							
								$emr_id = get_existing_id('m_patient_ntp', $field_name, $lite_specific_id);
								array_push($data_array, $emr_id);
								break;
								
							case 'symptomatic_id':
								if($get_ids[$field_name]){
									$lite_specific_id = $get_ids[$field_name];
								}
							
								$emr_id = get_existing_id('m_consult_ntp_symptopmatics', $field_name, $lite_specific_id);
								array_push($data_array, $emr_id);
								break;
								
							case 'report_id':
								if($get_ids[$field_name]){
									$lite_specific_id = $get_ids[$field_name];
								}
							
								$emr_id = get_existing_id('m_patient_ntp_report', $field_name, $lite_specific_id);
								array_push($data_array, $emr_id);
								break;
								
							case 'reminder_id':
								if($get_ids[$field_name]){
									$lite_specific_id = $get_ids[$field_name];
								}
							
								$emr_id = get_existing_id('m_consult_reminder', $field_name, $lite_specific_id);
								array_push($data_array, $emr_id);
								break;
								
							case 'fp_px_id':
								if($get_ids[$field_name]){
									$lite_specific_id = $get_ids[$field_name];
								}
							
								$emr_id = get_existing_id('m_patient_fp_method', $field_name, $lite_specific_id);
								array_push($data_array, $emr_id);
								break;
								
							case 'schedule_id':
								if($get_ids[$field_name]){
									$lite_specific_id = $get_ids[$field_name];
								}
							
								$emr_id = get_existing_id('m_consult_appointments', $field_name, $lite_specific_id);
								array_push($data_array, $emr_id);
								break;
								
							default:
								array_push($data_array, $get_ids[$i]);
								break;
								
						endswitch;
					}
					
					array_push($all_array,$data_array);
				}else{
					//If no duplicate was detected
					array_push($all_array,$data_array);
				}
				
				//print_r($data_array);
				//echo "<br /><br />";
				unset($data_array);
				$data_array = array();
			}//end of while
		}
		//echo "<br /><br /><br /><br />";
		echo "<br />";
		print_r($all_array);
		echo "<br />";
		return $all_array;
	}
	
	function get_existing_id($table_name, $field_id_name, $lite_id){
		$get_id_sql = mysql_query("SELECT emr_specific_id AS id FROM $table_name AS a JOIN m_push_status b ON a.$field_id_name=b.lite_specific_id WHERE table_name='$table_name' AND b.lite_specific_id='$lite_id'") or die("Error 231 : ".mysql_error());
		//echo "get existing id query : SELECT emr_specific_id AS id FROM $table_name AS a JOIN m_push_status b ON a.$field_id_name=b.lite_specific_id WHERE table_name='$table_name' AND b.lite_specific_id='$lite_id' <br />";
		$get_id_result = mysql_fetch_array($get_id_sql);
		$exist_id = $get_id_result['id'];
		
		if($exist_id==0){
			$existing_id = 'na';
		}else{
			$existing_id = $exist_id;
		}
		return($existing_id);
	}
	
	function update_values(){
		if (func_num_args()>0) {
			$arg_list = func_get_args();
			$list = $arg_list[0];
		}
		
		$get_field_name = mysql_query("SELECT * FROM $list[4] LIMIT 1") or die("Error 308 : ". mysql_error());
		$get_field_count = mysql_num_fields($get_field_name);
		
		$update_text = '';
		
		for($count=0;$count<$get_field_count;$count++){
			if($update_text=='' || $update_text==NULL){
				$int_value = $count + 7;
				$field_name = mysql_field_name($get_field_name,$count);
				$update_text = $field_name. " = '".$list[$int_value]."'";
			}else{
				$int_value = $count + 7;
				$field_name = mysql_field_name($get_field_name,$count);
				
				if($list[4]=='m_consult_ccdev_services' || $list[4]=='m_consult_ccdev_vaccine' || $list[4]=='m_consult_vaccine'){
					if($field_name!='service_id' && $field_name!='ccdev_timestamp' && $field_name!='ccdev_service_date' && $field_name!='age_on_service' && $field_name!='vaccine_id' && $field_name!='vaccine_timestamp' && $field_name!='actual_vaccine_date'){
						$update_text = $update_text. ", ".$field_name. " = '".$list[$int_value]."'";
					}
				}else{
					$update_text = $update_text. ", ".$field_name. " = '".$list[$int_value]."'";
				}
			}
		}
		return $update_text;
	}
	
	function where_text($field_name, $value, $where_text){
		if($where_text=='' || $where_text==NULL){
			if($value!='na'){
				$where_text = $field_name." = '".$value."'";
			}
		}else{
			if($value!='na'){
				$where_text = $where_text. " AND " .$field_name." = '".$value."'";
			}
		}
		return $where_text;
	}
	
	function where_clause(){
		if (func_num_args()>0) {
			$arg_list = func_get_args();
			$list = $arg_list[0];
		}
		
		$get_field_name = mysql_query("SELECT * FROM $list[4] LIMIT 1") or die("Error 308 : ". mysql_error());
		
		$get_field_count = mysql_num_fields($get_field_name);
		$where_text = '';
		for($i=0;$i<$get_field_count;$i++){
			$field_name = mysql_field_name($get_field_name, $i);
			switch($field_name):
				case 'patient_id':
					$value = $i + 7;
					$where_text = where_text($field_name, $list[$value], $where_text);
					break;
				case 'consult_id':
					$value = $i + 7;
					$where_text = where_text($field_name, $list[$value], $where_text);
					break;
				case 'mc_id':
					$value = $i + 7;
					$where_text = where_text($field_name, $list[$value], $where_text);
					break;
				case 'fp_id':
					$value = $i + 7;
					$where_text = where_text($field_name, $list[$value], $where_text);
					break;
				case 'fp_px_id':
					$value = $i + 7;
					$where_text = where_text($field_name, $list[$value], $where_text);
					break;
				case 'notes_id':
					$value = $i + 7;
					$where_text = where_text($field_name, $list[$value], $where_text);
					break;
				case 'fp_service_id':
					$value = $i + 7;
					$where_text = where_text($field_name, $list[$value], $where_text);
					break;
				case 'dropout_id':
					$value = $i + 7;
					$where_text = where_text($field_name, $list[$value], $where_text);
					break;
				case 'family_id':
					$value = $i + 7;
					$where_text = where_text($field_name, $list[$value], $where_text);
					break;
				case 'cct_id':
					$value = $i + 7;
					$where_text = where_text($field_name, $list[$value], $where_text);
					break;
				case 'ccdev_id':
					$value = $i + 7;
					$where_text = where_text($field_name, $list[$value], $where_text);
					break;
				case 'request_id':
					$value = $i + 7;
					$where_text = where_text($field_name, $list[$value], $where_text);
					break;
				case 'ntp_id':
					$value = $i + 7;
					$where_text = where_text($field_name, $list[$value], $where_text);
					break;
				case 'symptomatic_id':
					$value = $i + 7;
					$where_text = where_text($field_name, $list[$value], $where_text);
					break;
				case 'obshx_id':
					$value = $i + 7;
					$where_text = where_text($field_name, $list[$value], $where_text);
					break;
				case 'report_id':
					$value = $i + 7;
					$where_text = where_text($field_name, $list[$value], $where_text);
					break;
				case 'reminder_id':
					$value = $i + 7;
					$where_text = where_text($field_name, $list[$value], $where_text);
					break;
				case 'fp_px_id':
					$value = $i + 7;
					$where_text = where_text($field_name, $list[$value], $where_text);
					break;
				case 'schedule_id':
					$value = $i + 7;
					$where_text = where_text($field_name, $list[$value], $where_text);
					break;
				//case 'ccdev_timestamp':
				//	$value = $i + 7;
				//	$where_text = where_text($field_name, $list[$value], $where_text);
				//	break;
				default:
					break;
			endswitch;
		}
		
		return $where_text;
	}
	
	function insert_values(){
		if (func_num_args()>0) {
			$arg_list = func_get_args();
			$list = $arg_list[0];
			$id = $arg_list[1];
			$prenatal_date = $arg_list[2];
			$prenatal_time = $arg_list[3];
		}
			
		$insert_text = '';
		$field_count = count($list);
		
		if($id=='' || $id==NULL){
			for($insert_count=7;$insert_count<$field_count;$insert_count++){
				if($insert_text=='' || $insert_text==NULL){
					$insert_text = "'".$list[$insert_count]."'";
				}else{
					if(!$list[$insert_count]){
						$insert_text = $insert_text.", ''";
					}else{
						$insert_text = $insert_text.", '".$list[$insert_count]."'";
					}
				}
			}
		}else{
			for($insert_count=7;$insert_count<$field_count;$insert_count++){
				if($insert_count==7){
				
					if(!$list[$insert_count]){
						$insert_text = '';
					}else{
						if($list[4]=='m_patient_ntp'){
							$insert_text = "'".$list[1]."'";
						}else{
							if($id=='other'){
								$insert_text = "'".$list[$insert_count]."'";
							}else{
								$insert_text = "'".$id."'";
							}
						}
					}
				}else{
					if($list[4]=='m_patient_ntp'){
						if($insert_count==8){
							$insert_text = $insert_text.", '".$id."'";
						}else{
							$insert_text = $insert_text.", '".$list[$insert_count]."'";
						}
					}else{
						if(!$list[$insert_count]){
							$insert_text = $insert_text.", ''";
						}else{
							$insert_text = $insert_text.", '".$list[$insert_count]."'";
						}
					}
				}
			}
		}
		return $insert_text;
	}
	
	function lite_connect($host){
		$dbconnlite = mysql_connect($host,$_SESSION['dbuser'],$_SESSION['dbpass']) or die(mysql_error());
		mysql_select_db('ehr_lite_live',$dbconnlite);
	}
		
	function ehr_connect(){
		$dbconn = mysql_connect('localhost',$_SESSION['dbuser'],$_SESSION['dbpass']) or die(mysql_error());
		mysql_select_db('server',$dbconn);
	}
	
	
?>
