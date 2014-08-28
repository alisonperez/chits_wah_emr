<?php

//print_r($_SESSION);
if(isset($_SESSION["userid"])):
	db_connect();
	show_connection_details();
	if($_POST["submit_filter"]):
		process_submission();
	endif;
	if($_POST["submit_export"]):
		//print_r($_POST);
		sync_file();

	endif;
else:
	echo "<font color='red'>Unauthorized access to this page. Please log in.</font>";
	echo "<br><a href='$_SERVER[PHP_SELF]'>Try Again</a>";
endif;
 

function db_connect(){
	$db_conn = mysql_connect('localhost',$_SESSION["dbuser"],$_SESSION["dbpass"]) or die("Cannot query 14: ".mysql_error());
	mysql_select_db($_SESSION["dbname"],$db_conn) or die("Cannot query 15: ".mysql_error());
}


function show_connection_details(){
	$q_user = mysql_query("SELECT user_lastname, user_firstname, user_id FROM game_user ORDER by user_lastname ASC, user_firstname ASC");
	$q_brgy = mysql_query("SELECT barangay_id, barangay_name FROM m_lib_barangay") or die("Cannot query 21: ".mysql_error());
	
	echo "<form action='$_SERVER[PHP_SELF]' method='POST'>";
	
	echo "<table width='60%' align='center' style='background-color: #FFFFFF;font-family: verdana,arial;font-size:20;'>";
	echo "<tr><td colspan='2' style='background-color:#0000FF;text-align:center;color:white;'>1. CREATE FILE FOR DATA SYNC</td></tr>";
	echo "<tr style='background-color: #99CCFF; '><td>Current active database </td><td>".$_SESSION["dbname"]."</td></tr>";
	echo "<tr style='background-color: #99CCFF;'><td>Select End User Account to Sync</td>";
	echo "<td><select name='sel_user' style='font-size:18;background-color:#FFFFCC;'>";	
	echo "<option value='all'>All User Accounts</option>";
	while(list($lname,$fname,$uid)=mysql_fetch_array($q_user)){
		echo "<option value='$uid'>$lname, $fname</option>";
	}
	echo "</select></td></tr>";

	echo "<tr style='background-color: #99CCFF;'><td>Select Barangay/s to Sync</td>";
	echo "<td><select name='sel_barangay[]' size='10' multiple='multiple' style='font-size:18;background-color:#FFFFCC ;'>";
	echo "<option value='all'>All Barangays</option>";
	while(list($brgy_id,$brgy_name)=mysql_fetch_array($q_brgy)){
		echo "<option value='$brgy_id'>$brgy_name</option>";
	}
	echo "</select></td></tr>";

	echo "<tr style='background-color: #99CCFF;font-family: verdana,arial;'><td colspan='2'>Note: Only family folders, patients and consultations from the selected barangays will be included in the sync file.</td>";
	echo "<tr align='center'><td colspan='2'><input type='submit' name='submit_filter' value='Create Sync File' style='border: 1px solid #666;font-size:20px;'></td></tr>";	
	echo "</table>";
	echo "</form>";
}

function process_submission(){
	$_SESSION["tmp_directory"] = '../../sql/';
	$_SESSION["file_name"] = 'record_push_'.$_POST["sel_user"].'_'.date('Y-m-d').'.sql';
	$_SESSION["ehr_lite_live"] = 'ehr_lite_live.sql';
	$_SESSION["ehr_lite_import"] = 'ehr_lite_import.sql';

	//print_r($_POST);
	create_tmp_sql_file();

	extract_users($_POST["sel_user"]);		
	extract_patient_folder_consults();
	extract_brgy();

	if(create_ehr_lite_sql() && (isset($_POST["sel_barangay"]))):
		load_import_file();
	endif;	
}


function create_tmp_sql_file(){
	if($handle = fopen($_SESSION["tmp_directory"].$_SESSION["file_name"],'w') or die("Cannot write file 67")): 
		chmod($_SESSION["tmp_directory"].$_SESSION["file_name"],0766);
	endif;	
}

function extract_users($user_id){ 
	if($user_id=='all'):	
		$q_user = mysql_query("SELECT * FROM game_user") or die("Cannot query 59: ".mysql_error());
	else:	//specific user only
		$q_user = mysql_query("SELECT * FROM game_user WHERE user_id='$user_id'") or die("Cannot query 61: ".mysql_error());
	endif;

	if(mysql_num_rows($q_user)!=0): 
			$insert_user = '';
			$handle = fopen($_SESSION["tmp_directory"].'/'.$_SESSION["file_name"],'a') or die("Cannot open file 78");
		while($r_user=mysql_fetch_array($q_user)){ //print_r($r_user);
			if($r_user["user_admin"]=='N'):	//include only the non-admin accounts

			$insert_user = "REPLACE INTO game_user (user_id,user_lastname,user_firstname,user_middle,user_dob,user_gender,user_role,user_admin,user_login,user_password,user_lang,user_email,user_cellular,user_pin,user_active,user_receive_sms) VALUES ('$r_user[user_id]','$r_user[user_lastname]','$r_user[user_firstname]','$r_user[user_middle]','$r_user[user_dob]','$r_user[user_gender]','$r_user[user_role]','$r_user[user_admin]','$r_user[user_login]','$r_user[user_password]','$r_user[user_lang]','$r_user[user_email]','$r_user[user_cellular]','$r_user[user_pin]','$r_user[user_active]','$r_user[user_receive_sms]')".';';			
			
			fwrite($handle,$insert_user."\n"); 

			$clear_user_privilege = "DELETE FROM module_user_location WHERE user_id='$r_user[user_id]';";

			fwrite($handle,$clear_user_privilege."\n");

			$modify_user_privilege = "REPLACE INTO module_user_location (location_id,user_id) VALUES ('ADM','$r_user[user_id]');";

			fwrite($handle,$modify_user_privilege."\n");

			endif;
		}

			fclose($handle);
	else:

	endif;
}

function extract_patient_folder_consults(){
	$tables_for_export = array('consult','patient','dental'); //list the tables of which selection will be based on the barangays. For other tables, Export ALL records
	
	$arr_table = array(); //this shall contain all the tables that passes through the filter
	$patient_arr = array();
	

	$get_tables = mysql_query("SHOW TABLES FROM ".$_SESSION["dbname"]) or die("Cannot query 93: ".mysql_error());

	while(list($table)=mysql_fetch_array($get_tables)){
		$str_array = explode('_',$table);
		if(in_array($str_array[1],$tables_for_export)):
			array_push($arr_table,$table);
		endif;
	}

	$_SESSION["arr_table"] = $arr_table;

	if(isset($_POST["sel_barangay"])):
		$patient_arr = get_family_folders();	//return the patient_ids
		get_patient_records($patient_arr); 
		get_patient_program_enrollment($patient_arr);
	else:
		echo "Please select barangay/s.";
	endif;

	

}

function get_family_folders(){
	$patient_arr = array();	//stores the patient_id's
	$family_arr = array();

	if(in_array('all',$_POST["sel_barangay"])):
		$q_family_address = mysql_query("SELECT * FROM m_family_address");
	else: 
		$str_brgy = "'".implode("','",$_POST["sel_barangay"])."'";

		$q_family_address = mysql_query("SELECT * FROM m_family_address WHERE barangay_id IN ($str_brgy)") or die("Cannot query 119: ".mysql_error());
	endif;

	//insert the m_family_address into the text file
	if(mysql_num_rows($q_family_address)!=0):
	
		$handle = fopen($_SESSION["tmp_directory"].'/'.$_SESSION["file_name"],'a') or die("Cannot open file 124");

		while($r_family=mysql_fetch_array($q_family_address)){
			$r_family['address'] = addslashes($r_family['address']);


			$insert_family_address = "REPLACE INTO m_family_address (family_id,address_year,address,barangay_id) VALUES ('$r_family[family_id]','$r_family[address_year]','$r_family[address]','$r_family[barangay_id]');";
			array_push($family_arr,$r_family["family_id"]);
			fwrite($handle,$insert_family_address."\n"); 
		}

		fclose($handle);

		foreach($family_arr as $key=>$family_id){
			insert_family($family_id);
			insert_family_cct($family_id);
			$patient_arr_fam = insert_family_members($family_id);	//get the patient_id's
			foreach($patient_arr_fam as $key=>$value){
				array_push($patient_arr,$value);
			}
			
		}
	endif;

	return $patient_arr;
}


function insert_family($family_id){
	$q_family = mysql_query("SELECT * FROM m_family WHERE family_id='$family_id'") or die("Cannot query 145: ".mysql_error());

	if(mysql_num_rows($q_family)!=0):
		$handle = fopen($_SESSION["tmp_directory"].'/'.$_SESSION["file_name"],'a') or die("Cannot open file 148");

		while($r_family=mysql_fetch_array($q_family)){
			$insert_family = "REPLACE INTO m_family (family_id,head_patient_id) VALUES ('$r_family[family_id]','$r_family[head_patient_id]');";

			fwrite($handle,$insert_family."\n"); 
		}

		fclose($handle);
	endif;

}

function insert_family_cct($family_id){
	$q_family_cct = mysql_query("SELECT * FROM m_family_cct_member WHERE family_id='$family_id'") or die("Cannot query 145: ".mysql_error());

	if(mysql_num_rows($q_family_cct)!=0){
		$handle = fopen($_SESSION["tmp_directory"].'/'.$_SESSION["file_name"],'a') or die("Cannot open file 165");

		while($r_cct = mysql_fetch_array($q_family_cct)){
			$insert_cct = "REPLACE INTO m_family_cct_member (cct_id,family_id,date_enroll,last_updated) VALUES ('$r_cct[cct_id]','$r_cct[family_id]','$r_cct[date_enroll]','$r_cct[last_updated]');";
			fwrite($handle,$insert_cct."\n");
		}
	
		fclose($handle);
	}
}

function insert_family_members($family_id){
	$patient_arr = array(); 

	$q_family_members = mysql_query("SELECT * FROM m_family_members WHERE family_id='$family_id'") or die("Cannot query 145: ".mysql_error());


	if(mysql_num_rows($q_family_members)!=0):
		$handle = fopen($_SESSION["tmp_directory"].'/'.$_SESSION["file_name"],'a') or die("Cannot open file 186");

		while($r_members = mysql_fetch_array($q_family_members)){
			$insert_members = "REPLACE INTO m_family_members (family_id,family_role,patient_id) VALUES ('$r_members[family_id]','$r_members[family_role]','$r_members[patient_id]');";
			array_push($patient_arr,$r_members["patient_id"]);		
			fwrite($handle,$insert_members."\n");
		}

		fclose($handle);
	else:

	endif;

	return $patient_arr;
}


function get_patient_records($patient_arr){
	foreach($_SESSION["arr_table"] as $key=>$table_name){ 
		$check_field = mysql_query("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA='$_SESSION[dbname]' AND TABLE_NAME='$table_name' AND COLUMN_NAME='patient_id'") or die("Cannot query 215: ".mysql_error());

		if(mysql_num_rows($check_field)!=0):
			$arr_fields = array();
			
			$get_cols = mysql_query("SHOW COLUMNS FROM $table_name") or die("Cannot query 218: ".mysql_error());
			
			while($arr_table = mysql_fetch_array($get_cols)){ //print_r($arr_table);
				array_push($arr_fields,$arr_table["Field"]);
			}

			//print_r($arr_fields);
			$str_fields = implode(",",$arr_fields);

			foreach($patient_arr as $key=>$patient_id){  
				$get_records = mysql_query("SELECT * FROM $table_name WHERE patient_id='$patient_id'") or die("Cannot query 218: ".mysql_error());

				if(mysql_num_rows($get_records)!=0):
					//get the fields of the table
					while($r_records = mysql_fetch_array($get_records)){ 
						
						$handle = fopen($_SESSION["tmp_directory"].'/'.$_SESSION["file_name"],'a') or die("Cannot open file 236");
						
						$arr_fields_result = array(); 

						$insert_records = "REPLACE INTO $table_name ($str_fields) VALUES (";
						foreach($arr_fields as $key=>$field_name){
							array_push($arr_fields_result,"'".addslashes($r_records[$field_name])."'");
						} 
						$str_fields_results = implode(",",$arr_fields_result);

						$insert_records = $insert_records.$str_fields_results.");";

						fwrite($handle,$insert_records."\n");
					}
					
					fclose($handle);
			
				endif;
			}
		endif;
	}

}

function get_patient_program_enrollment($patient_arr){ //pulling the m_consult_ptgroup
		foreach($patient_arr as $key=>$value){
			$q_consult = mysql_query("SELECT consult_id FROM m_consult WHERE patient_id='$value'") or die("Cannot query 285 :".mysql_error());

			if(mysql_num_rows($q_consult)!=0):
				while(list($consult_id)=mysql_fetch_array($q_consult)){

					$q_ptgroup = mysql_query("SELECT ptgroup_id,consult_id,ptgroup_timestamp,user_id FROM m_consult_ptgroup WHERE consult_id='$consult_id'") or die("Cannot query 289: ".mysql_error());

					if(mysql_num_rows($q_ptgroup)!=0):


						while(list($ptgroup_id,$consult_id,$ptgroup_timestamp,$user_id)=mysql_fetch_array($q_ptgroup)){
							$handle = fopen($_SESSION["tmp_directory"].'/'.$_SESSION["file_name"],'a') or die("Cannot open file 293");
							
							$str_insert = "REPLACE INTO m_consult_ptgroup (ptgroup_id,consult_id,ptgroup_timestamp,user_id) VALUES ("."'".$ptgroup_id."',"."'".$consult_id."','".$ptgroup_timestamp."','".$user_id."');";

							fwrite($handle,$str_insert."\n") or die("Cannot write m_consult_ptgroup");

						}
						fclose($handle);
					else:

					endif;
				}
			endif;
		
		}
}

function extract_brgy(){
	if(in_array('all',$_POST["sel_barangay"])):
		$q_brgy = mysql_query("SELECT * FROM m_lib_barangay") or die("Cannot query 265: ".mysql_error());		

		$q_population = mysql_query("SELECT * FROM m_lib_population") or die("CAnnot query: 267".mysql_error());
	else:
		$str_brgy = "'".implode("','",$_POST["sel_barangay"])."'";
		$q_brgy = mysql_query("SELECT * FROM m_lib_barangay WHERE barangay_id IN ($str_brgy)") or die("Cannot query 268: ".mysql_error());

		$q_population = mysql_query("SELECT * FROM m_lib_population WHERE barangay_id IN ($str_brgy)") or die("Cannot query: 267".mysql_error());

	endif;
	
	if(mysql_num_rows($q_brgy)!=0):
		$handle = fopen($_SESSION["tmp_directory"].'/'.$_SESSION["file_name"],'a') or die("Cannot open file 278");

		while($r_brgy = mysql_fetch_array($q_brgy)){
			$insert_brgy = "INSERT INTO m_lib_barangay (barangay_id,barangay_name,barangay_population,area_code) VALUES ('$r_brgy[barangay_id]','$r_brgy[barangay_name]','$r_brgy[barangay_population]','$r_brgy[area_code]');";

			fwrite($handle,$insert_brgy."\n");
		}

		fclose($handle);
	endif;
	
	if(mysql_num_rows($q_population)!=0):
		while($r_population = mysql_fetch_array($q_population)){
			$handle = fopen($_SESSION["tmp_directory"].'/'.$_SESSION["file_name"],'a') or die("Cannot open file 290");

			$insert_population = "INSERT INTO m_lib_population (population_id,barangay_id,population,population_year) VALUES ('$r_population[population_id]','$r_population[barangay_id]','$r_population[population]','$r_population[population_year]');" ;

			fwrite($handle,$insert_population."\n");
		}
		
		fclose($handle);

	endif;

}

function create_ehr_lite_sql(){

	if(copy('../../db/'.$_SESSION["ehr_lite_live"],$_SESSION["tmp_directory"].$_SESSION["ehr_lite_import"])): 
		$ehr_lite_import = fopen($_SESSION["tmp_directory"].$_SESSION["ehr_lite_import"],'a') or die("Cannot open file 320");

		$replace_insert_file = file($_SESSION["tmp_directory"].'/'.$_SESSION["file_name"]);


		foreach($replace_insert_file as $key=>$value){
			fwrite($ehr_lite_import,$value);
		}
		return 1;
	else:
		echo "<font color='red'>Cannot create SQL files for import to EHR-lite.</font>";
		return 0;
	endif;
}

function load_import_file(){
		echo "<table align='center' width='60%' style='background-color: #FFFFFF;font-family: verdana,arial;font-size:20;'>";
		echo "<tr style='background-color:#0000FF;text-align:center;color:white;'><td>2. SYNC DATA FROM EHR SERVER TO EHR LITE</td></tr>";
		echo "<tr><tr><td style='background-color: #99CCFF; '>";
		//echo "EHR-lite import file is generated and can be downloaded&nbsp;<a href='$_SESSION[tmp_directory]$_SESSION[ehr_lite_import]'>HERE</a>. You can manually upload this import file to the EHR-lite computer.";
		echo "EHR-lite import file is generated and can be downloaded&nbsp;<a href='$_SESSION[tmp_directory]$_SESSION[file_name]'>HERE</a>. You can manually upload this import file to the EHR-lite computer.";
		echo "</td></tr>";
		echo "<tr><td align='center' style='background-color: #99CCFF; '><b>OR</b></td></tr>";

		echo "<tr>";
		echo "<td style='background-color: #99CCFF; '>";
		echo "SYNC the EHR-lite export file from this computer to the EHR-lite computer. Please supply the information below and press the EXPORT EHR-LITE DATA button.";
		echo "</td>";
		echo "</tr>";
		
		
		echo "<form action='$_SERVER[PHP_SELF]' name='form_sync' method='POST'>";
		echo "<tr><td>";
		echo "<table>";
		echo "<tr style='background-color: #99CCFF; '><td >IP Address of the EHR-Lite computer</td>";
		echo "<td><input type='text' name='txt_ip' style='font-size:18;background-color:#FFFFCC ;'></input></td></tr>";
		
		echo "<tr style='background-color: #99CCFF; '><td>Name of Database</td>";
		echo "<td><input type='text' name='txt_db' style='font-size:18;background-color:#FFFFCC ;'></input></td></tr>";

		echo "<tr style='background-color: #99CCFF; '><td>EHR-lite database name</td>";
		echo "<td>";
		echo "<input type='text' name='txt_dbname' style='font-size:18;background-color:#FFFFCC ;'></input>";
		echo "</td></tr>";

		echo "<tr style='background-color: #99CCFF; '><td>EHR-lite database password</td>";
		echo "<td>";
		echo "<input type='password' name='txt_dbpwd' style='font-size:18;background-color:#FFFFCC ;'></input>";
		echo "</td></tr>";

		echo "<tr style='background-color: #99CCFF; '><td>Initials of EHR-lite User (ie. jp (for Jose Rizal))</td>";
		echo "<td>";
		echo "<input type='text' name='txt_initial' style='font-size:18;background-color:#FFFFCC ;'></input>";
		echo "</td></tr>";
				
		echo "</table>";
		echo "</td></tr>";
		
		echo "<tr>";
		echo "<td colspan='2' align='center'><input type='submit' value='EXPORT EHR-LITE DATA' name='submit_export' style='border: 1px solid #666;font-size:20px;'></input></td>";
		echo "</tr>";
		echo "</form>";
		echo "</table>";
}

function sync_file(){
	

	if(!isset($_POST["txt_ip"])):
		echo "Cannot process file export. Please supply the IP address of the EHR-lite computer.";
	elseif(!isset($_POST["txt_db"])):
		echo "Cannot process file export. Please supply the name of the database of the EHR-lite";
	else:
		if(!file_exists('/var/www/backup')):
			mkdir('/var/www/backup',0777);
		endif;

		$str_backup = 'mysqldump -h '.$_POST["txt_ip"].' -u '.$_POST["txt_dbname"].' -p'.$_POST["txt_dbpwd"].' '.$_POST["txt_db"].' > /var/www/backup/ehrlite_backup.'.$_POST["txt_initial"].'.'.date('Ymd').'.sql';		
		exec($str_backup);

		if(file_exists('/var/www/backup/ehrlite_backup.'.$_POST["txt_initial"].'.'.date('Ymd').'.sql')):
			if(filesize('/var/www/backup/ehrlite_backup.'.$_POST["txt_initial"].'.'.date('Ymd').'.sql')>0):
					
					//this code block does a timestampped mirror of the database in the ehr-lite
					$str_rename_db = 'mysql -h '.$_POST["txt_ip"].' -u '.$_POST["txt_dbname"].' -p'.$_POST["txt_dbpwd"].' --execute "DROP DATABASE IF EXISTS '.$_POST["txt_db"].'_'.date('Ymd').'; '.'CREATE DATABASE '.$_POST["txt_db"].'_'.date('Ymd').';"';
					//echo $str_rename_db;
					exec($str_rename_db);	
					
					$str_export = 'mysql -h '.$_POST["txt_ip"].' -u '.$_POST["txt_dbname"].' -p'.$_POST["txt_dbpwd"].' '.$_POST["txt_db"].'_'.date('Ymd').' < /var/www/backup/ehrlite_backup.'.$_POST["txt_initial"].'.'.date('Ymd').'.sql';										
					//echo $str_export;
					exec($str_export);


					//this code block drops the ehr_lite_live database, re-creates and dumps the updated database
					$str_drop_db = 'mysql -h '.$_POST["txt_ip"].' -u '.$_POST["txt_dbname"].' -p'.$_POST["txt_dbpwd"].' --execute "DROP DATABASE IF EXISTS '.$_POST["txt_db"].'; '.'CREATE DATABASE '.$_POST["txt_db"].';"';

					$str_export_lite = 'mysql -h '.$_POST["txt_ip"].' -u '.$_POST["txt_dbname"].' -p'.$_POST["txt_dbpwd"].' '.$_POST["txt_db"].' < '.$_SESSION["tmp_directory"].$_SESSION["ehr_lite_import"];
					
					$str_import_m_push = 'mysql -h '.$_POST["txt_ip"].' -u '.$_POST["txt_dbname"].' -p'.$_POST["txt_dbpwd"].' --execute "INSERT '.$_POST["txt_db"].'.m_push_status SELECT * FROM '.$_POST["txt_db"].'_'.date('Ymd').'.m_push_status;"';				

					exec($str_drop_db);
					exec($str_export_lite);
					exec($str_import_m_push);

					echo "<script language='Javascript'>";
					echo "window.alert('Sync and file import completed. Please check the EHR-lite computer if the database was successfuly been loaded.')";
					echo "</script>";
			else:
					echo "<script language='Javascript'>";
					echo "window.alert('Sync and file import not completed. Please check information you entered and re-sync again.')";
					echo "</script>";
	
			endif;
		endif;

	endif;
}
?>