<?php
	function insert_to_push($px_id, $sp_id, $tbl_name,$sync_type){
	

		//check if the id's submitted already have an equivalent value in ehr server
		$check_if_exist = mysql_query("SELECT ehr_patient_id, ehr_specific_id FROM m_push_status WHERE lite_patient_id = '$px_id' AND lite_specific_id='$sp_id' AND table_name='$tbl_name' LIMIT 1") or die("Error 86 : " .mysql_error()); 
		
		$check_if_exist_count = mysql_num_rows($check_if_exist);
		
		if($check_if_exist_count>=1){
			$check_result = mysql_fetch_assoc($check_if_exist);
			$ehr_id = $check_result['ehr_patient_id'];
			$spc_id = $check_result['ehr_specific_id'];
		}else{
			$ehr_id = 0;
			$spc_id = 0;
		}
		
		$date = date("Y-m-d");
		$insert_to_push = mysql_query("INSERT INTO m_push_status VALUES ('".$px_id."','".$ehr_id."','".$sp_id."','".$spc_id."','".$tbl_name."','".$date."','Y','".$sync_type."')") or die("Error 100 :".mysql_error());
		//$insert_to_push = mysql_query("INSERT INTO m_push_status VALUES ("'".$px_id."','".$ehr_id."','".$sp_id."','".$spc_id."','".$tbl_name."','".date."','Y','".$sync_type."'")") or die("Error 2 :".mysql_error());
	}
?>