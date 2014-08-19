<?php

  class mmsync{
  
    function mmsync(){
      $this->module = "Mobile Midwife Sync";
      $this->author = "darth_ali";
      $this->date = "2012-11-19";
      $this->desc = "Mobile Midwife Sync is the component that resides in the server. Sync, cleans and validates tablets data";

      $this->arr_program_id = array('Expanded Program for Immunization');
	
    }

  function connect_ip_address(){  
    
    $json_str = '';


    $cmd = "curl -X GET 'http://$_POST[txt_ip]:$_POST[txt_port]/coconut/_all_docs'";
    
    //echo $cmd; 
    
    if($json_str = shell_exec($cmd)):	//execute the shell curl command and save the string output to the $json_file variable
	$_SESSION["txt_ip"] = $_POST["txt_ip"];
	$_SESSION["txt_port"] = $_POST["txt_port"];
      echo "<p class='message_info'>Connection success!</p>";
    else:      
      echo "<p class='warning'>Failed to connect</p>";
    endif;
    
    return $json_str;
    
  }
  
  function get_json_docs($json_str){
      $arr_json_id = array();
      $arr_json = json_decode($json_str,true);


      foreach($arr_json["rows"] as $key=>$value){
	foreach($value as $key2=>$value2){
		if($key2=='id'):
			array_push($arr_json_id,$value2);
		endif;
	}

      }
	
        return $arr_json_id;

  }

  function get_json_elements($arr_json_id){


	if(count($arr_json_id)!=0):
	
		$filename = './json_docs/mmdoc_'.date('YmdHi').'.txt';
		$handle = fopen($filename,'w') or die("Cannot open file: ".$filename);


		$arr_json = array();   //array to store the filtered JSON docs based on the question in array arr_program_id. this should include JSON for all programs
		$arr_json_element = array();    //array to store the decoded JSON string
	
		foreach($arr_json_id as $key=>$value){
			$cmd = "curl -H 'Content-type: application/json' -X GET 'http://$_SESSION[txt_ip]:$_SESSION[txt_port]/coconut/$value'";
			$str_json_element = shell_exec($cmd);
			//echo $json_element.'<br><br>';

			$arr_json_element = json_decode($str_json_element,TRUE);

			if(in_array($arr_json_element["question"],$this->arr_program_id)):
				fwrite($handle,$str_json_element);
				//fwrite($handle,",");
				array_push($arr_json,$arr_json_element);
			endif;
		}
		return $arr_json;
	else:
		//echo "Array has no contents (Line: 77)";
	endif;
  }



   function check_mm_content($arr_json){
		$arr_new = array();
		$arr_old = array();
		$arr_old = array();
		$arr_exist_for_validate = array();
		$arr_with_required_fields = array();

		if(count($arr_json)!=0):
			$count_insert = 0;
			$count_old = 0; 

			foreach($arr_json as $key=>$arr_value){
				$q_uid = mysql_query("SELECT sync_uid,last_sync FROM m_lib_mm_sync WHERE sync_uid='$arr_value[_id]'") or die("Cannot query 90: ".mysql_error());

				if(mysql_num_rows($q_uid)==0): 
					
					/* 1. uid is not yet in the lookup table, insert the ID 2
					   2. consider for sync candidate (validateion)
					   3. do not update the last_sync field yet. to be updated once the data sets are entered into the database
					*/

					$insert_uid = mysql_query("INSERT INTO m_lib_mm_sync SET sync_uid='$arr_value[_id]', date_created='$arr_value[createdAt]',date_last_modified='$arr_value[lastModifiedAt]',question_id='$arr_value[question]'") or die("Cannot query 99: ".mysql_error());
					
					if($insert_uid):
						$count_insert++;
					endif;

				else:	
					/*	1. compare the last_sync (db) and lastModifiedAt index (JSON)
						2. if last_sync >= lastModifiedAt, do not sync
						3. if last_sync < lastModifiedAt, consider as sync candidate (validation)
					*/

					//echo $arr_value["_id"]."<br>";
					
					list($sync_id,$last_sync) = mysql_fetch_array($q_uid);

					if(($arr_value["lastModifiedAt"] > $last_sync) && ($last_sync!='0000-00-00 00:00:00')):
						//if the tablet was updated, update the last update time in the database
						$update_sync = mysql_query("UPDATE m_lib_mm_sync SET date_last_modified='$arr_value[lastModifiedAt]' WHERE sync_uid='$sync_id'") or die("Cannot query 99: ".mysql_error());
					endif;
					

					array_push($arr_old,$arr_value);
					$count_old++;
				endif;
			
			}

			//1. purge the array such that only those that lastModified > lastsync will be included
			//$arr_for_validate will contain uid's whose lastSync < lastModified OR lastSync=0000-00-00

			$arr_for_validate = $this->check_sync_upload_dates($arr_old);

			//2. create the array if it has no first, last,  birthday and barangay residence
			//$arr_not_complete will contain uid wherein the fname, lname, birthday and barangay residence is missing

			$arr_not_complete = $this->validate_required_fields($arr_json,$arr_for_validate);
			
			$arr_with_required_fields = $this->get_uid_required_fields($arr_for_validate,$arr_not_complete);
			

			if(count($arr_for_validate)!=0 ): 
				//$arr_old_px = $this->validate_patient_profile($arr_json,$arr_for_validate);
				$arr_old_px = $this->validate_patient_profile($arr_json,$arr_with_required_fields);
			endif;
			

			echo "<script language='Javascript'>";
			echo "window.alert('$count_insert NEW records have been scanned and will be validated. $count_old EXISTING records are to be validated for possible modifications.')";
			echo "</script>";
			
/*
	$arr_json - contains the complete tablet record (_docs_all) 
	$arr_for_validate - will contain tablet records wherein the lastModified > lastSync OR lastSync = 0000-00-00
	$arr_not_complete - will contain tablet records w/o the necessary fields
	$arr_with_required_fields - will contain tablet records with the necessary fields
	$arr_old_px - will contain patients that are in the tablet and at the emr

*/

			$this->build_transaction_table($arr_json,$arr_for_validate,$arr_not_complete,$arr_with_required_fields,$arr_old_px);
			
		else:

		endif;
   }


   function check_sync_upload_dates($arr_old){
		//print_r($arr_old);	
		$arr_include_validation = array();

		foreach($arr_old as $key=>$arr_internal_array){
			$q_get_last_sync = mysql_query("SELECT sync_uid, last_sync, question_id FROM m_lib_mm_sync WHERE sync_uid='$arr_internal_array[_id]'") or die("Cannot query 141: ".mysql_error());


			list($sync_uid,$last_sync,$question_id) = mysql_fetch_array($q_get_last_sync);

			if($last_sync=='0000-00-00 00:00:00'): //exists yet no sync data yet. usual instance are newly addde records
				array_push($arr_include_validation,$arr_internal_array['_id']);
			elseif(strtotime($last_sync) < strtotime($arr_internal_array['lastModifiedAt'])):
				array_push($arr_include_validation,$arr_internal_array['_id']);
			else:

			endif;


		}

		return $arr_include_validation; //should contain uid's wherein lastModified > lastSync, to be included in the validation
   }


	function validate_required_fields($arr_json,$arr_for_validate){
		$arr_not_complete = array();

		foreach($arr_json as $key=>$value){
			$match = 1;

			$get_field_fname = mysql_query("SELECT tablet_field_name FROM m_lib_mm_data_map WHERE emr_field_name='patient_firstname' AND question_id='$value[question]'") or die("Cannot query 176: ".mysql_error());
			list($fname) = mysql_fetch_array($get_field_fname);

		
			$get_field_lname = mysql_query("SELECT tablet_field_name FROM m_lib_mm_data_map WHERE emr_field_name='patient_lastname' AND question_id='$value[question]'") or die("Cannot query 178: ".mysql_error());
			list($lname) = mysql_fetch_array($get_field_lname);

			$get_field_mname = mysql_query("SELECT tablet_field_name FROM m_lib_mm_data_map WHERE emr_field_name='patient_middlename' AND question_id='$value[question]'") or die("Cannot query 181: ".mysql_error());
			list($mname) = mysql_fetch_array($get_field_mname);

			$get_field_bday = mysql_query("SELECT tablet_field_name FROM m_lib_mm_data_map WHERE emr_field_name='patient_dob' AND question_id='$value[question]'") or die("Cannot query 181: ".mysql_error());
			list($bday) = mysql_fetch_array($get_field_bday);

			$get_field_mother = mysql_query("SELECT tablet_field_name FROM m_lib_mm_data_map WHERE emr_field_name='mother_name' AND question_id='$value[question]'") or die("Cannot query 181: ".mysql_error());
			list($mother) = mysql_fetch_array($get_field_mother);									


			$get_field_brgy = mysql_query("SELECT tablet_field_name FROM m_lib_mm_data_map WHERE emr_field_name='barangay_id' AND question_id='$value[question]'") or die("Cannot query 195: ".mysql_error());
			list($brgy) = mysql_fetch_array($get_field_brgy);

			
			$match = preg_match("^\d{1,2}/\d{1,2}/\d{4}^", $value["DateofBirth"]); //requires the date to be mm/dd/yyyy

			if(isset($value[$fname]) && isset($value[$brgy]) && isset($value[$lname]) && isset($value[$bday]) && isset($value["HouseNumberStreetNameSitioPurok"]) && $match==1): //if fname, brgy, lname, address and bday are complete and bday is of regexp mm/dd/yyyy

			else: //at least one in fname, lname and bday is incomplete				
				array_push($arr_not_complete,$value["_id"]);
			endif;

		}	

		return $arr_not_complete;
	}


	function get_uid_required_fields($arr_for_validate,$arr_not_complete){
		$arr_with_required_fields = array();
		
		foreach($arr_for_validate as $key=>$value){
			if(!in_array($value,$arr_not_complete)):
				array_push($arr_with_required_fields,$value);
			endif;
		
		}
		//print_r($arr_with_required_fields);
		return $arr_with_required_fields;
	
	}

   
   
   function validate_patient_profile($arr_json,$arr_for_validate){
			$arr_old_px = array();
			$arr_new_px = array();


		foreach($arr_json as $key=>$value){

			$get_field_fname = mysql_query("SELECT tablet_field_name FROM m_lib_mm_data_map WHERE emr_field_name='patient_firstname' AND question_id='$value[question]'") or die("Cannot query 176: ".mysql_error());
			list($fname) = mysql_fetch_array($get_field_fname);

		
			$get_field_lname = mysql_query("SELECT tablet_field_name FROM m_lib_mm_data_map WHERE emr_field_name='patient_lastname' AND question_id='$value[question]'") or die("Cannot query 178: ".mysql_error());
			list($lname) = mysql_fetch_array($get_field_lname);

			$get_field_mname = mysql_query("SELECT tablet_field_name FROM m_lib_mm_data_map WHERE emr_field_name='patient_middlename' AND question_id='$value[question]'") or die("Cannot query 181: ".mysql_error());
			list($mname) = mysql_fetch_array($get_field_mname);

			$get_field_bday = mysql_query("SELECT tablet_field_name FROM m_lib_mm_data_map WHERE emr_field_name='patient_dob' AND question_id='$value[question]'") or die("Cannot query 181: ".mysql_error());
			list($bday) = mysql_fetch_array($get_field_bday);


			$get_field_mother = mysql_query("SELECT tablet_field_name FROM m_lib_mm_data_map WHERE emr_field_name='mother_name' AND question_id='$value[question]'") or die("Cannot query 181: ".mysql_error());
			list($mother) = mysql_fetch_array($get_field_mother);									

			$get_field_gender = mysql_query("SELECT tablet_field_name FROM m_lib_mm_data_map WHERE emr_field_name='patient_gender' AND question_id='$value[question]'") or die("Cannot query 181: ".mysql_error());
			list($gender) = mysql_fetch_array($get_field_gender);									


			list($m,$d,$y) = explode('/',$value[$bday]);
			$dob = $y.'-'.str_pad($m, 2, "0", STR_PAD_LEFT).'-'.str_pad($d, 2, "0", STR_PAD_LEFT);

			
			$concat_tablet_data = $value[$fname].$value[$mname].$value[$lname].$dob.$value[$gender]; //.'juanita';

			$q_patient = mysql_query("SELECT patient_id,patient_firstname,patient_middle,patient_lastname,patient_dob,patient_gender,patient_mother FROM m_patient ORDER by registration_date ASC") or die("Cannot query 265: ".mysql_error());

			while(list($pxid,$px_fname,$px_mname,$px_lname,$dob,$gender,$mother)=mysql_fetch_array($q_patient)){		
				$perc = 0.00; //saves the percent diffence between the two texts

				$concat_emr_data = $px_fname.$px_mname.$px_lname.$dob.$gender; //.$mother;
			
				$char_diff = similar_text(strtolower($concat_emr_data),strtolower($concat_tablet_data),$perc);

				if($perc>=93):
					$q_value = mysql_query("SELECT sync_uid FROM m_lib_mm_sync WHERE last_sync < date_last_modified AND sync_uid='$value[_id]'") or die("Cannot query 125: ".mysql_error());

					if(mysql_num_rows($q_value)!=0):
						array_push($arr_old_px,array($pxid,$value["_id"]));		
					endif;
				endif;

				/*if($perc>=93): 
					array_push($arr_old_px,array($pxid,$value["_id"]));
				else:
					array_push($arr_new_px,array($pxid,$value["_id"]));
				endif;			*/
			}

			//if the $perc is more than 93%, most likely this is the same person. Store the patient_id. If less than 93% then, add the patient into the m_patient folder						

		}

		return $arr_old_px; //returns a hash array of patient who are both in the EMR and tablet
		   
   }

   function validate_px_family_address(){
   
   
   }


	function build_transaction_table($arr_json,$arr_for_validate,$arr_not_complete,$arr_with_required_fields,$arr_old_px){
		$this->build_table_not_sync($arr_json,$arr_for_validate,$arr_not_complete,$arr_old_px);
		echo "<br><br>";
		$this->build_table_sync($arr_json,$arr_with_required_fields,$arr_old_px);
	}


	function build_table_not_sync($arr_json,$arr_for_validate,$arr_not_complete,$arr_old_px){
		$arr_not_updated = array(); //will contain the whole array for which lastModifed < lastSync
		$arr_not_completed = array();
		$arr_old_px_id = array();

		foreach($arr_old_px as $key=>$value){
			array_push($arr_old_px_id,$value[1]);
		}

		foreach($arr_json as $key=>$value){
			if(!in_array($value["_id"],$arr_for_validate)):
				array_push($arr_not_updated,$value);
			endif;

			if(in_array($value["_id"],$arr_not_complete) && !in_array($value["_id"],$arr_old_px_id)):
				array_push($arr_not_completed,$value);
			endif;
		}

		$_SESSION["str_not_updated"] = $arr_not_updated;
		$_SESSION["str_not_completed"] = $arr_not_completed;

		echo "<table class='view_tables'>";
		echo "<tr class='view_tables'><td colspan='2'>Records NOT for Data Sync</td></tr>";
		echo "<tr><td>No record updates since last sync</td>";
		
		echo "<td><a href='scripts/mm_view_records.php?type=noupdate&title=No record updates since last sync' target='new'>".count($arr_not_updated)."</td></tr>";

		echo "<tr><td>Tablet records with insufficient required fields (i.e. first name, last name, date of birth, barangay)</td>";
		
		echo "<td><a href='scripts/mm_view_records.php?type=inc&title=Tablet records with insufficient required fields' target='new'>".count($arr_not_completed)."</td></tr>";	
		echo "</table>";
	}

	function build_table_sync($arr_json,$arr_with_required_fields,$arr_old_px){
		$_SESSION["str_required_fields"] = $arr_with_required_fields;
		$_SESSION["str_old_px"] = $arr_old_px;
		$arr_old_px_id = array();

		foreach($arr_old_px as $key=>$value){ 
			array_push($arr_old_px_id,$value[1]);
		}

		echo "<table class='view_tables'>";
		echo "<tr class='view_tables'><td colspan='3'>Records ELIGIBLE for Data Sync</td></tr>";

		$this->build_table_new_px($arr_json,$arr_with_required_fields,$arr_old_px);
		$this->build_table_old_px($arr_json,$arr_old_px_id);

		echo "</table>";
	}

	function build_table_new_px($arr_json,$arr_with_required_fields,$arr_old_px){				

		//this loop will segregate the new patients with the all required fields by comparing to the old array
		$arr_new_px = array();
		$arr_old_px_id = array();



		foreach($arr_old_px as $key=>$value){
			/*if(!in_array($value[1],$arr_with_required_fields)):
					array_push($arr_new_px,$value2);
			else:

			endif; */

			array_push($arr_old_px_id,$value[1]);
		}


		foreach($arr_with_required_fields as $key=>$value){
			if(!in_array($value,$arr_old_px_id)):
				array_push($arr_new_px,$value);
			endif;
		}


		if(count($arr_old_px)!=0):
			$_SESSION["str_new_px"] = $this->get_json_array($arr_json,$arr_new_px);
		else:
			$_SESSION["str_new_px"] = $this->get_json_array($arr_json,$arr_with_required_fields);
		endif;

		echo "<tr><td>New patient profiles to be added in the RHU server";
		echo "<td>";
		echo "<a href='scripts/mm_view_records.php?type=newpx&title=New patient profiles to be added in the RHU server' target='new'>".count($_SESSION["str_new_px"])."</href>";
		echo "</td></tr>";
	
	}

	function build_table_old_px($arr_json, $arr_old_px_id){

		$_SESSION["str_old_px_id"] = $this->get_json_array($arr_json,$arr_old_px_id);

		echo "<tr><td>Existing patients present both in the RHU and mobile device";
		echo "<td>";
		echo "<a href='scripts/mm_view_records.php?type=oldpx&title=Existing patients present both in the RHU and mobile device' target='new'>".count($arr_old_px_id)."</href>";
		echo "</td></tr>";			
	}


	function get_json_array($arr_json, $arr_uid){
		$arr_json_id = array();

		foreach($arr_json as $key=>$value){
			if(in_array($value["_id"],$arr_uid)):
				array_push($arr_json_id,$value);
			endif;
		}
	
		return $arr_json_id;
	}
  }

?>