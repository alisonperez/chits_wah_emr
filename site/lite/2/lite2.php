<?php
	if($_POST['sync_user']!=NULL || $_POST['sync_user']!=''){
		$host = '192.168.1.108';
		lite_connect($_POST['sync_user']);
		
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
				
			foreach($new_entry_array as $entry_array => $new_entry){
				$new_id = '';
				$insert_text = insert_values($new_entry, $new_id);
				insert_query($insert_text, $new_entry[4]);
					
				$push_data_array = array($new_entry[0], $new_entry[1], $new_entry[2],$new_id,$new_entry[4],$new_entry[5],'N', $new_entry[6]);
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
			ehr_connect();
			$array_m_push_update = array();
			
			foreach($update_array as $up_array => $update_value){
				$update_text = update_values($update_value);
				$where_text = where_clause($update_value);
				$update_query = mysql_query("UPDATE $update_value[4] SET $update_text WHERE $where_text") or die("Error 110 : " .mysql_error());
				
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
		if($delete_array!=NULL || $delete_array!=''){
			ehr_connect();
			$array_m_push_update = array();
			
			foreach($delete_array as $del_array => $delete_value){
				$delete_text = where_clause($delete_value);
				$delete_query = mysql_query("DELETE FROM $delete_value[4] WHERE $delete_text") or die("Error 132 : " .mysql_error());
				
				if($delete_query){
					echo "SUCCESS";
				}else{
					echo "FAILED";
				}
				$push_data_array = array($delete_value[0], $delete_value[1], $delete_value[2],$delete_value[3],$delete_value[4],$delete_value[5],'N', $delete_value[6]);
				array_push($array_m_push_update,$push_data_array);
				unset($push_data_array);
				$delete_record_count += 1;
			}
			update_m_push($array_m_push_update, 'update');
			unset($array_m_push_update);
			
		}
		
		//if($new_record_count!=0 || $update_record_count!=0 || $delete_record_count!=0){
			echo "You have successfully Synced : <br />";
			echo "<span style='padding: 0 20px 0 20px; width: 120px; display: inline-block;'>New Records </span>= $new_record_count <br />";
			echo "<span style='padding: 0 20px 0 20px; width: 120px; display: inline-block;'>Updated Records </span>= $update_record_count <br />";
			echo "<span style='padding: 0 20px 0 20px; width: 120px; display: inline-block;'>Deleted Records </span>= $delete_record_count";
		//}
	}else{
		echo "<form name='lite2-sync' method='post' action=''>";
		echo "Select user to Sync : ";
			echo "<select name='sync_user'>";
				echo "<option value='localhost'>Localhost</option>";
				echo "<option value='sample'>Sample</option>";
			echo "</select>";
			echo "<input type='submit' />";
		echo "</form>";
	}
	
	function insert_query(){
		if (func_num_args()>0) {
			$arg_list = func_get_args();
			$list = $arg_list[0];
			$table_name = $arg_list[1];
		}
		//$insert_sample = "INSERT INTO $table_name VALUES($list)";
		//echo $insert_sample . "<br />";
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
					echo $update_sql;
					break;
				default:
					$update_sql = "UPDATE m_push_status SET emr_specific_id=$value[3], push_flag='N' WHERE lite_patient_id = '$value[0]' AND emr_patient_id = '$value[1]' AND lite_specific_id='$value[2]' AND table_name='$value[4]' AND entry_date='$value[5]'";
					break;
			}
			$update_status = mysql_query($update_sql) or die("Error 45 : ".mysql_error());
		}
	}
		
	function get_data_array($check_status_sql){
		$check_status_query = mysql_query("$check_status_sql") or die("Error 57: ". mysql_error());
		if(mysql_num_rows($check_status_query)){
			//create an array of all the entry in ehr_lite when m_push_status = 'Y'
			if(!isset($all_array)){
				$all_array = array();
			}else{
				unset($all_array);
			}
				
			while(list($lite_px_id, $emr_px_id, $lite_specific_id, $emr_specific_id, $table_name, $entry_date, $sync_type)=mysql_fetch_array($check_status_query)){
				$data_array = array($lite_px_id, $emr_px_id, $lite_specific_id, $emr_specific_id, $table_name, $entry_date, $sync_type);
				
				$prime_key_sql = mysql_query("SELECT * FROM $table_name") or die("Error 88 :".mysql_error());
				$prime_key = mysql_field_name($prime_key_sql,0);
					
				//Get all information from the specific table name
				if($table_name=='m_patient'){
					$get_data_sql = mysql_query("SELECT * FROM $table_name WHERE patient_id ='$lite_px_id'") or die("Error 93: ". mysql_error());
				}else{
					$get_data_sql = mysql_query("SELECT * FROM $table_name WHERE $prime_key ='$lite_specific_id' AND patient_id ='$lite_px_id'") or die("Error 95: ". mysql_error());
				}
				
				if(mysql_num_rows($get_data_sql)!=0){
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
								$emr_id = get_existing_id('m_consult', $field_name);
								array_push($data_array, $emr_id);
								break;
							case 'mc_id':
								$emr_id = get_existing_id('m_patient_mc', $field_name);
								array_push($data_array, $emr_id);
								break;
							case 'fp_id':
								$emr_id = get_existing_id('m_patient_fp', $field_name);
								array_push($data_array, $emr_id);
								break;
							case 'notes_id':
								$emr_id = get_existing_id('m_consult_fp', $field_name);
								array_push($data_array, $emr_id);
								break;
							case 'fp_service_id':
								$emr_id = get_existing_id('m_patient_fp_method_service', $field_name);
								array_push($data_array, $emr_id);
								break;
							case 'dropout_id':
								$emr_id = get_existing_id('m_patient_fp_dropout', $field_name);
								array_push($data_array, $emr_id);
								break;
							case 'family_id':
								$emr_id = get_existing_id('m_family', $field_name);
								array_push($data_array, $emr_id);
								break;
							case 'cct_id':
								$emr_id = get_existing_id('m_family_cct_members', $field_name);
								array_push($data_array, $emr_id);
								break;
							case 'ccdev_id':
								$emr_id = get_existing_id('m_patient_ccdev', $field_name);
								array_push($data_array, $emr_id);
								break;
							case 'request_id':
								$emr_id = get_existing_id('m_consult_lab', $field_name);
								array_push($data_array, $emr_id);
								break;
							case 'ntp_id':
								$emr_id = get_existing_id('m_patient_ntp', $field_name);
								array_push($data_array, $emr_id);
								break;
							case 'symptomatic_id':
								$emr_id = get_existing_id('m_consult_ntp_symptopmatics', $field_name);
								array_push($data_array, $emr_id);
								break;
							case 'report_id':
								$emr_id = get_existing_id('m_patient_ntp_report', $field_name);
								array_push($data_array, $emr_id);
								break;
							case 'reminder_id':
								$emr_id = get_existing_id('m_consult_reminder', $field_name);
								array_push($data_array, $emr_id);
								break;
							case 'fp_px_id':
								$emr_id = get_existing_id('m_patient_fp_method', $field_name);
								array_push($data_array, $emr_id);
								break;
							case 'schedule_id':
								$emr_id = get_existing_id('m_consult_appointments', $field_name);
								array_push($data_array, $emr_id);
								break;
							default:
								array_push($data_array, $get_data_result[$i]);
								break;
						endswitch;
					}
						
					array_push($all_array,$data_array);
					unset($data_array);
					$data_array = array();
				}
			}
		}
		return $all_array;
	}
	
	function get_existing_id($table_name, $field_id_name){
		$get_id_sql = mysql_query("SELECT emr_specific_id AS id FROM $table_name AS a JOIN m_push_status b ON a.$field_id_name=b.lite_specific_id WHERE table_name='$table_name'") or die("Error 231 : ".mysql_error());
		$get_id_result = mysql_fetch_array($get_id_sql);
		$exist_id = $get_id_result['id'];
		
		if( $exist_id==0){
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
				$update_text = $update_text. ", ".$field_name. " = '".$list[$int_value]."'";
			}
		}
		return $update_text;
	}
	
	function where_text($field_name, $value, $where_text){
		if($where_text=='' || $where_text==NULL){
			$where_text = $field_name." = '".$value."'";
		}else{
			$where_text = $where_text. " AND " .$field_name." = '".$value."'";
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
		}
			
		$insert_text = '';
		$field_count = count($list);
		
		if($id=='' || $id==NULL){
			for($insert_count=7;$insert_count<$field_count;$insert_count++){
				$insert_text = "'".$list[$insert_count]."'";
			
				if(!$list[$insert_count]){
					$insert_text = $insert_text.", ''";
				}else{
					$insert_text = $insert_text.", '".$list[$insert_count]."'";
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
		$dbconnlite = mysql_connect("192.168.1.108","root","root") or die(mysql_error());
		mysql_select_db('lite_test',$dbconnlite);
	}
		
	function ehr_connect(){
		$dbconn = mysql_connect("localhost","root","root") or die(mysql_error());
		mysql_select_db('server',$dbconn);
	}
?>
