<?php
	//This script will be run in the EHR-SERVER
	$host = "localhost";	
	lite_connect($host);
	
	//1. insert new patient first
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
		}
		update_m_push($array_m_push_update, 'newpatient');
		unset($array_m_push_update);
	}else{
		echo "No new patient <br />";
	}
	
	//2. insert other table with sync_type = 'N'
	lite_connect($host);
	$check_status_sql = "SELECT lite_patient_id, emr_patient_id, lite_specific_id, emr_specific_id, table_name, entry_date, sync_type FROM m_push_status WHERE push_flag ='Y' AND table_name='m_consult' AND sync_type='N'";
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
		}
		update_m_push($array_m_push_update, 'newconsult');
		unset($array_m_push_update);
	}else{
		echo "No new consult";
	}
	
	//other table with consult_id
	$table_array = array('m_patient_mc','m_patient_fp');//list of secondary table related with consult_id

	foreach($table_array as $tablename){
		lite_connect($host);
		$check_status_sql = "SELECT lite_patient_id, emr_patient_id, lite_specific_id, emr_specific_id, table_name, entry_date, sync_type FROM m_push_status WHERE push_flag ='Y' AND table_name='$tablename' AND sync_type='N'";
		$new_entry_array = get_data_array($check_status_sql);
		if($new_entry_array!=NULL || $new_consult_array!=''){
			ehr_connect();
			$array_m_push_update = array();
			
			foreach($new_entry_array as $entry_array => $new_entry){
				$new_id = get_new_id($new_entry);
				$insert_text = insert_values($new_entry, $new_id);
				insert_query($insert_text, $new_entry[4]);
				
				$push_data_array = array($new_entry[0], $new_entry[1], $new_entry[2],$new_id,$new_entry[4],$new_entry[5],'N', $new_entry[6]);
				array_push($array_m_push_update,$push_data_array);
				unset($push_data_array);
			}
			update_m_push($array_m_push_update, 'new');
			unset($array_m_push_update);
		}
	}
	
	function insert_query(){
		if (func_num_args()>0) {
			$arg_list = func_get_args();
			$list = $arg_list[0];
			$table_name = $arg_list[1];
		}
		
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
		$new_id_result = mysql_fetch_array($new_id_sql);
			
		$new_id = $new_id_result['new_id'] + 1;

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
					$update_sql = "UPDATE m_push_status SET emr_patient_id=$value[1], push_flag='N' WHERE lite_patient_id=$value[0] AND emr_patient_id=0";
					break;
				default:
					$update_sql = "UPDATE m_push_status SET emr_specific_id=$value[3], push_flag='N' WHERE lite_patient_id = '$value[0]' AND emr_patient_id = '$value[1]' AND lite_specific_id='$value[2]' AND table_name='$value[4]' AND entry_date='$value[5]'";
					break;
			}
			echo $update_sql."<br />";
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
		
				//get primary key
				$prime_key_sql = mysql_query("SELECT * FROM $table_name") or die("Error 88 :".mysql_error());
				$prime_key = mysql_field_name($prime_key_sql,0);
				
				//Get all information from the specific table name
				if($table_name=='m_patient'){
					$get_data_sql = mysql_query("SELECT * FROM $table_name WHERE patient_id ='$lite_px_id'") or die("Error 93: ". mysql_error());
				}else{
					$get_data_sql = mysql_query("SELECT * FROM $table_name WHERE $prime_key ='$lite_specific_id' AND patient_id ='$lite_px_id'") or die("Error 95: ". mysql_error());
				}
				
				$get_data_result = mysql_fetch_array($get_data_sql);
				
				$field_count = mysql_num_fields($get_data_sql);
				for($i=0;$i<$field_count;$i++){
					$field_name = mysql_field_name($get_data_sql, $i);
					switch($field_name):
						case 'patient_id':
							if($emr_px_id==0){
								array_push($data_array, 'na');
								echo "a <br />";
							}else{
								array_push($data_array, $emr_px_id);
								echo "b <br />";
							}
							break;
						
						case 'consult_id':
							$get_consult_id_sql = mysql_query("SELECT emr_specific_id AS cid FROM $table_name AS a JOIN m_push_status b ON a.consult_id=b.lite_specific_id WHERE table_name='m_consult'") or die("Error 154 : ".mysql_error());
							$get_consult_id_result = mysql_fetch_array($get_consult_id_sql);
							$emr_consult_id = $get_consult_id_result['cid'];
						

							if( $emr_consult_id==0){
								array_push($data_array, 'na');
							}else{
								array_push($data_array, $emr_consult_id);
							}
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
		return $all_array;
	}
	
	function insert_values(){
		if (func_num_args()>0) {
			$arg_list = func_get_args();
			$list = $arg_list[0];
			$id = $arg_list[1];
		}
		
		$insert_text = '';
		$field_count = count($list);
		
		for($insert_count=7;$insert_count<$field_count;$insert_count++){
			if($insert_count==7){
				if(!$list[$insert_count]){
					$insert_text = '';
				}else{
					$insert_text = "'".$id."'";
				}
			}else{
				if(!$list[$insert_count]){
					$insert_text = $insert_text.", ''";
				}else{
					$insert_text = $insert_text.", '".$list[$insert_count]."'";
				}
			}
		}
		return $insert_text;
	}
		
		/*
		for($insert_count=7;$insert_count<$field_count;$insert_count++){
			if($insert_count==7){
				if(!$list[$insert_count]){
					$insert_text = '';
				}else{
					//filter for every primary table ex. m_patient, m_consult
					switch ($list[4]):
						case 'm_patient' || 'm_consult':
							$insert_text = "'".$id."'";
							break;
						default:
							$insert_text = "'".$list[$insert_count]."'";
							break;
					endswitch;
				}
			}else{
				if($insert_count==8){
					if($list[4]=='m_consult'){
						$insert_text = $insert_text. ", '".$list[1]. "'";
					}else{
						if(!$list[$insert_count]){
							$insert_text = $insert_text.", ''";
						}else{
							$insert_text = $insert_text.", '".$list[$insert_count]."'";
						}
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
		*/
		
	
	
	function lite_connect($host){
		$dbconnlite = mysql_connect($host,$_SESSION['dbuser'],$_SESSION['dbpass']) or die(mysql_error());
		mysql_select_db('lite',$dbconnlite);
	}
	
	function ehr_connect(){
		$dbconn = mysql_connect("localhost",$_SESSION['dbuser'],$_SESSION['dbpass']) or die(mysql_error());
		mysql_select_db('chits',$dbconn);
	}
?>