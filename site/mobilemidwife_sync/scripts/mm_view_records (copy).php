<?php		
	session_start();
	ob_start();

  echo "<html>";
  echo "<head>";
  echo "<style type='text/css'>";
  echo ".connect_table { background-color:#6600FF; font-family: arial,sans-serif; color: white; border-radius: 15px; }";
  echo "td.connect_table { background-color:#6666FF; font-family: arial,sans-serif; color: white; font-size: 25px;}";

  echo ".view_tables { background-color:white; font-family: arial,sans-serif; font-size: 20px; color: white;border-radius: 10px; }";
  echo "tr.view_tables { background-color:#336699; font-family: arial,sans-serif; font-size: 20px; color: #FFFF33; text-align: center; font-weight: bold; }";
  echo "a:hover { color:yellow; }";
  echo "a:active { color:yellow; }";
  echo "a { color:white; }";
  echo ".message_info { font-family: arial,sans-serif; font-size: 13px; color: #006600; }";
  echo ".warning { font-family: arial,sans-serif; font-size: 13px; color: #FF0000; }";
  echo ".tr_inner_label { background-color:#6600FF; font-family: arial,sans-serif; font-size: 15px; color: #FFFF33; text-align: center; font-weight: bold; }";
  echo ".tr_inner { background-color:#6666FF; font-family: arial,sans-serif; font-size: 15px; color: white; text-align: center; }";

  echo "</style>";
  echo "</head>";

  echo "<body>";

	$dbconn = mysql_connect('localhost',$_SESSION["dbuser"],$_SESSION["dbpass"]) or die("Please login to access the Mobile Midwife Synchronization interfaces".mysql_error());
	mysql_select_db($_SESSION["dbname"],$dbconn) or die("cannot select db");
	
	$arr_display = array();

	if($_GET["type"]=='noupdate'): 
		$arr_display = $_SESSION["str_not_updated"];
	elseif($_GET["type"]=='inc'):
		$arr_display = $_SESSION["str_not_completed"];
	elseif($_GET["type"]=='newpx'):
		$arr_display = $_SESSION["str_new_px"];
	elseif($_GET["type"]=='oldpx'):
		$arr_display = $_SESSION["str_old_px_id"];
	else:

	endif;


	/*if(!empty($_GET["uid"]) && !empty($_GET["program"]) && $_GET["action"]='sync'):
		check_program($_GET["uid"],$_GET["program"]);
	endif;
	*/
	//print_r($_SESSION);


	if($_POST["submit_patient"]=='Add New Patient'):
		insert_patient($arr_display);
	else:

	endif;

	if($_POST["submit_conflict"]):
			$pxid = $_POST["pxid"];
			$uid = $_POST["uid"];
			foreach($_POST as $key=>$value){
				if($key!='pxid' && $key!='submit_conflict'):
					save_conflict_data($value,$pxid,$uid);
				endif;
			}		
			header("Location:".$_SERVER["PHP_SELF"]."?type=$_GET[type]&title=$_GET[title]&uid=$_GET[uid]&program=$_GET[program]&action=$_GET[action]");
	endif;

	echo "<html>";
	echo "<head>";
	echo "<script>";
?>
	function create_folder(fname,lname,brgy,address,pxid){ 

		if(window.confirm("Create folder for " + fname + " " + lname + " of " + address + ", " + brgy + "?")){
			document.form_patient_details.px_fname.value = fname;
			document.form_patient_details.px_lname.value = lname;
			document.form_patient_details.px_id.value = pxid;
			document.form_patient_details.px_brgy.value = brgy;
			document.form_patient_details.px_address.value = address;

			document.forms["form_patient_details"].submit();
		}		
	}

	function assign_relative(fname,lname,brgy,address,pxid){
		
		window.open("../scripts/check_hh.php?fname="+fname+"&lname="+lname+"&brgy="+brgy+"&address="+address+"&pxid="+pxid);
	}

<?php		
	echo "</script>";
	echo "</head>";

	echo "<body>";
	echo "<a href='../index.php?action=reload' style='color:blue;'><< Go Back to Mobile Midwife Sync Main Page</a>";
	if(count($arr_display)!=0):
		create_family_folder($_POST);

		//print_r($_SESSION);
		
		//a receptacle for temporary PX details when the CREATE` folder link is pushed
		echo "<form name='form_patient_details' method='POST'>";
		echo "<input type='hidden' name='px_fname' value=''></input>";
		echo "<input type='hidden' name='px_lname' value=''></input>";
		echo "<input type='hidden' name='px_id' value=''></input>";
		echo "<input type='hidden' name='px_brgy' value=''></input>";
		echo "<input type='hidden' name='px_address' value=''></input>";
		echo "</form>";


		echo "<form name='form_patient_info' method='POST' action='$_SERVER[PHP_SELF]?type=$_GET[type]&title=$_GET[title]'>";
		echo "<table class='view_tables'>";
		echo "<tr class='view_tables'><td colspan='9' align='center'>".$_GET["title"]."</td></tr>";
		echo "<tr class='tr_inner_label'>";
		echo "<td>First Name</td>";
		echo "<td>Last Name</td>";
		echo "<td>Date of Birth</td>";
		echo "<td>Barangay</td>";
		echo "<td>Date Created</td>";
		echo "<td>Date Last Modified</td>";
		echo "<td>Program</td>";
		get_action_title($_GET["type"]);
		echo "</tr>";



		foreach($arr_display as $key=>$value){

			if($_GET["type"]!='noupdate'):
				$q_value = mysql_query("SELECT sync_uid FROM m_lib_mm_sync WHERE last_sync < date_last_modified AND sync_uid='$value[_id]'") or die("Cannot query 125: ".mysql_error());

				$count = mysql_num_rows($q_value);
			else:  //for GET == noupdate
				$count = 1;
			endif;

			if($count!=0):

			$q_fname = mysql_query("SELECT tablet_field_name FROM m_lib_mm_data_map WHERE question_id='$value[question]' AND emr_field_name='patient_firstname'") or die("Cannot query 26: ".mysql_error());
			list($fname) = mysql_fetch_array($q_fname);

			$q_lname = mysql_query("SELECT tablet_field_name FROM m_lib_mm_data_map WHERE question_id='$value[question]' AND emr_field_name='patient_lastname'") or die("Cannot query 26: ".mysql_error());
			list($lname) = mysql_fetch_array($q_lname);

			$q_dob = mysql_query("SELECT tablet_field_name FROM m_lib_mm_data_map WHERE question_id='$value[question]' AND emr_field_name='patient_dob'") or die("Cannot query 26: ".mysql_error());
			list($dob) = mysql_fetch_array($q_dob);

			echo "<tr class='tr_inner'>";
			echo "<td><a href='http://$_SESSION[txt_ip]:$_SESSION[txt_port]/coconut/_design/coconut/index.html#edit/result/$value[_id]' target='_blank'>$value[$fname]</a></td>";
			echo "<td>$value[$lname]</td>";
			echo "<td>$value[$dob]</td>";
			echo "<td>$value[Barangay]</td>";
			echo "<td bgcolor='$yeah'>$value[createdAt]</td>";
			echo "<td>$value[lastModifiedAt]</td>";
			echo "<td>$value[question]</td>";
			get_field_input($_GET["type"],$value["_id"],$value["question"]);
			
			echo "</tr>";

			endif;

		}

		get_table_button($_GET["type"]);
		echo "</table>";
		echo "</form>";
		
		
	else:
		echo 'No records to display';
	endif;


	function get_action_title($type){
		if($type=='newpx'):
			echo "<td>Add Patient?</td>";
		elseif($type=='oldpx'):
			echo "<td>";
			echo "Family";
			echo "</td>";	

			echo "<td>";
			echo "Sync Health Program?";
			echo "</td>";	
		else:
			
		endif;
	}
	
	function get_field_input($type,$uid,$program){
		
		$pxid = get_patient_id($uid);

		list($px_fname,$px_lname,$px_brgy,$px_address) = explode("|",get_pxdetails_couch($uid));

		$px_fname = trim($px_fname);
		$px_lname = trim($px_lname);
		$px_brgy = trim($px_brgy);
		$px_address = trim($px_address);

		//echo $px_lname.'/'.$px_fname.'/'.$px_brgy.'/'.$px_address."<br >";
		//print_r($_SESSION["str_old_px_id"]);

		if($type=='newpx'):
			echo "<td align='center'>";
			echo "<input type='checkbox' name='$uid' CHECKED></input>";
			echo "</td>";

		elseif($type=='oldpx'):
		
			$q_check_family = mysql_query("SELECT family_id FROM m_family_members WHERE patient_id='$pxid'") or die("Cannot query: 100".mysql_error());

			if(mysql_num_rows($q_check_family)!=0):
				echo "<td>Yes</td>";
				echo "<td><a href='$_SERVER[PHP_SELF]?type=$_GET[type]&title=$_GET[title]&uid=$uid&program=$program&action=sync'>Sync Health Data</a>";
		
				if(!empty($_GET["uid"]) && !empty($_GET["program"]) && $_GET["action"]='sync' && $_GET["uid"]==$uid): 
					check_program($uid,$_GET["program"]);
				endif;

				echo "</td>";
			else:
				$px_address = str_replace("'s","",$px_address);				
				$px_address = str_replace("'","",$px_address);				

				echo "<td>";			
				echo "<a onclick=\"create_folder('$px_fname','$px_lname','$px_brgy','$px_address','$pxid')\">Create</a>&nbsp;&nbsp;&nbsp;&nbsp;";
				echo "<a onclick=\"assign_relative('$px_fname','$px_lname','$px_brgy','$px_address','$pxid')\">Assign</a>&nbsp;&nbsp;&nbsp;";
				echo "</td>";	

				echo "<td>Assign to family first</td>";
			endif;
		
			
		else:
		endif;
	}


	function get_table_button($type){  
		if($type=='newpx'): 
			echo "<tr><td align='center' colspan='8'>";
			echo "<input type='submit' name='submit_patient' value='Add New Patient'></input>";
			echo "</td></tr>";
		endif;
	}


	function insert_patient($arr_display){
		$arr_json_id = array();

		foreach($_POST as $key=>$value){
			array_push($arr_json_id,$key);
		}

		foreach($arr_display as $key=>$value){
			if(in_array($value["_id"],$arr_json_id)):

				list($m,$d,$y) = explode("/",$value["DateofBirth"]);

				$dob = $y."-".str_pad($m, 2, "0", STR_PAD_LEFT)."-".str_pad($d, 2, "0", STR_PAD_LEFT);
				$dob = str_replace(' ','',$dob);

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


			$str_insert = "INSERT INTO m_patient (patient_lastname,patient_firstname,patient_middle,patient_dob,patient_mother,registration_date,patient_gender) VALUES ('$value[$lname]','$value[$fname]','$value[$mname]','$dob','$value[$mother]',NOW(),'$value[$gender]')";

			$q_insert = mysql_query($str_insert) or die("Cannot query 143: ".mysql_error());
			
			else:

			endif;


		}

		if($q_insert):
			echo "<script language='Javascript'>";
			echo "window.alert('The patient basic information in the MM tablet/netbook was successfully recorded to to the RHU server. Search and reconnect to the device again to refresh records.');";
			echo "</script>";

			header("Location: ../index.php");
		endif;

	}

	function get_patient_id($uid){
		$pxid = '';
		foreach($_SESSION["str_old_px"] as $key=>$value){
			if($uid==$value[1]):
				$pxid = $value[0];
			endif;
		}

		return $pxid;
		
	}

	function get_pxdetails_couch($uid){
		$str_px_details = '';

		foreach($_SESSION["str_old_px_id"] as $key=>$value){
		
			if($value["_id"]==$uid):

				$get_field_fname = mysql_query("SELECT tablet_field_name FROM m_lib_mm_data_map WHERE emr_field_name='patient_firstname' AND question_id='$value[question]'") or die("Cannot query 176: ".mysql_error());
				list($fname) = mysql_fetch_array($get_field_fname);
		
				$get_field_lname = mysql_query("SELECT tablet_field_name FROM m_lib_mm_data_map WHERE emr_field_name='patient_lastname' AND question_id='$value[question]'") or die("Cannot query 178: ".mysql_error());
				list($lname) = mysql_fetch_array($get_field_lname);

				$get_field_brgy = mysql_query("SELECT tablet_field_name FROM m_lib_mm_data_map WHERE emr_field_name='barangay_id' AND question_id='$value[question]'") or die("Cannot query 195: ".mysql_error());
				list($brgy) = mysql_fetch_array($get_field_brgy);

				$str_px_details = $value[$fname].'|'.$value[$lname].'|'.$value[$brgy].'|'.$value["HouseNumberStreetNameSitioPurok"];
			endif;
		}

		return $str_px_details;

	}

	function create_family_folder($post_data){ 
		//print_r($post_data); 
		if(!empty($post_data["px_id"]) && !empty($post_data["px_brgy"]) && !empty($post_data["px_address"])):
		$year = date('Y');
		//print_r($post_data);
		$brgy_id = '';

		$get_brgy_id = mysql_query("SELECT barangay_id FROM m_lib_barangay WHERE barangay_name='$post_data[px_brgy]'") or die("Cannot query 256: ".mysql_error());
		list($brgy_id) = mysql_fetch_array($get_brgy_id);

		if($brgy_id!=''): 
			$insert_family1 = mysql_query("INSERT INTO m_family SET head_patient_id='0'") or die("Cannot query 265: ".mysql_error());

			$family_id = mysql_insert_id();

			$insert_family = mysql_query("INSERT INTO m_family_address SET family_id='$family_id',barangay_id='$brgy_id',address='$post_data[px_address]',address_year='$year'") or die("Cannot query 269: ".mysql_error());

			$insert_member = mysql_query("INSERT INTO m_family_members SET family_id='$family_id',family_role='member',patient_id='$post_data[px_id]'") or die("Cannot query 271: ".mysql_error());
			
			echo "<script language='Javascript'>";
			echo "window.alert('The family folder was created in the RHU server. The patient was successfully been added to the family folder.')";
			echo "</script>";
		else:
			echo "<script language='Javascript'>";
			echo "window.alert('The barangay in the mobile device does not exist in the RHU server.')";
			echo "</script>";
		endif;

		endif;
	}

	function check_program($uid,$program){ 
		$pxid = get_px_id($uid);
		if($program=='Expanded Program for Immunization'):
			//return $pxid;
			$q_ccdev = mysql_query("SELECT patient_id FROM m_patient_ccdev WHERE patient_id='$pxid'") or die("Cannot query 315: ".mysql_error());

				foreach($_SESSION["str_old_px_id"] as $key=>$value){ 
					if($value["_id"]==$uid):
						$q_bday = mysql_query("SELECT patient_dob FROM m_patient WHERE patient_id='$pxid'") or die("Cannot query 325: ".mysql_error());
						
						list($bday) = mysql_fetch_array($q_bday);
						$del_loc = location_delivery_code($value["LocationofDelivery"]);
						$date_reg = convert_date($value["DateofRegistrationYYYYMMDD"]);
						$bfed6_date = convert_date($value["Dateof6thMonthBreastfeedingifapplicable"]);
						$bfed1 = ($value["BreastfeedingMonth1"]=='Yes')?'Y':'N';
						$bfed2 = ($value["BreastfeedingMonth2"]=='Yes')?'Y':'N';
						$bfed3 = ($value["BreastfeedingMonth3"]=='Yes')?'Y':'N';
						$bfed4 = ($value["BreastfeedingMonth4"]=='Yes')?'Y':'N';
						$bfed5 = ($value["BreastfeedingMonth5"]=='Yes')?'Y':'N';
						$bfed6 = (!empty($bfed6_date))?'Y':'N';
							
					
						if(mysql_num_rows($q_ccdev)==0): //patient does not exist. create a new CCDEV record						
							$insert_ccdev = mysql_query("INSERT INTO m_patient_ccdev SET patient_id='$pxid',mother_name='$value[CompleteNameofMother]',father_name='$value[CompleteNameofFather]',ccdev_timestamp=NOW(),ccdev_dob='$bday',birth_weight='$value[BirthWeight]',delivery_location='$del_loc',date_registered='$date_reg',bfed_month1='$bfed1',bfed_month2='$bfed2',bfed_month3='$bfed3',bfed_month4='$bfed4',bfed_month5='$bfed5',bfed_month6='$bfed6',bfed_month6_date='$bfed6_date',ccdev_remarks='$value[Remarks]'") or die("Cannot query 337: ".mysql_error());

							$ccdev_id = mysql_insert_id();

							update_last_sync_date();

						else: //patient has an existing CCDEV record. update it

							$q_ccdev_id = mysql_query("SELECT ccdev_id FROM m_patient_ccdev WHERE patient_id='$pxid'") or die("Cannot query 358: ".mysql_error());
							list($ccdev_id) = mysql_fetch_array($q_ccdev_id);

							$updated_ccdev = mysql_query("UPDATE m_patient_ccdev SET mother_name='$value[CompleteNameofMother]',father_name='$value[CompleteNameofFather]',ccdev_timestamp=NOW(),ccdev_dob='$bday',birth_weight='$value[BirthWeight]',delivery_location='$del_loc',date_registered='$date_reg',bfed_month1='$bfed1',bfed_month2='$bfed2', bfed_month3='$bfed3',bfed_month4='$bfed4',bfed_month5='$bfed5',bfed_month6='$bfed6',bfed_month6_date='$bfed6_date', ccdev_remarks='$value[Remarks]' WHERE patient_id='$pxid'") or die("Cannot query 340: ".mysql_error());								

							update_last_sync_date();
						endif;

						

						if($insert_ccdev):
							echo "<script language='Javascript'>";
							echo "window.alert('Client was registered for the $_GET[program].')";
							echo "</script>";
						elseif($updated_ccdev):							
							echo "<script language='Javascript'>";
							echo "window.alert('Client's registration record for $_GET[program] was updated.')";
							echo "</script>";
						else:

						endif;
						
						sync_health_details($uid,$program,$pxid,$value,$ccdev_id);
						
						//header("Location:".$_SERVER["PHP_SELF"]."?type=$_GET[type]&title=$_GET[title]&uid=$_GET[uid]&program=$_GET[program]&action=$_GET[action]");

					else:
						

					endif;
				}

		else:
			return "Sync Health Program Data";
		endif;


	}

	function get_px_id($uid){
		foreach($_SESSION["str_old_px"] as $key=>$value){
			if($value[1]==$uid):
				return $value[0];
			endif;
		}
	}

	function location_delivery_code($loc_value){


		switch($loc_value){
			case 'Home':
				return 'HOME';
				break;
			case 'Hospital':
				return 'HOSP';
				break;
			case 'Private Lying-In Clinic':
				return 'LYIN';
				break;
			case 'Health Center':
				return 'HC';
				break;
			case 'Barangay Health Station':
				return 'BHS';
				break;
			case 'Others':
				return 'OTHERS';
				break;
			default:
				return;
				break;
		}	
	}

	function convert_date($date_to_convert){ //converts a date in m/d/yyyy to yyyy-mm-dd

		if(!empty($date_to_convert)):
			list($m,$d,$y) = explode('/',trim($date_to_convert));

			$converted_yr = $y."-".str_pad($m, 2, "0", STR_PAD_LEFT)."-".str_pad($d, 2, "0", STR_PAD_LEFT);
		else:
			$converted_yr = '0000-00-00';
		endif;

		return $converted_yr;	
	}

	function sync_health_details($uid,$program,$pxid,$arr_tablet,$ccdev_id){


		$arr_conflict_data = array();
		
		$q_consult_id = mysql_query("SELECT consult_id FROM m_consult ORDER by consult_id DESC") or die("Cannot query 420: ".mysql_error());

		list($consult_id) = mysql_fetch_array($q_consult_id);
		$consult_id = $consult_id + 1;
		$insert_made = 0;
		$userid = 1;

		switch($program){
			case 'Expanded Program for Immunization':
				$get_value_to_match = mysql_query("SELECT db_table, emr_field_name, tablet_field_name, value_to_match FROM m_lib_mm_data_map WHERE question_id='$program' AND value_to_match!=''") or die("Cannot query 421: ".mysql_error());

				while(list($db_table,$emr_field_name,$tablet_field_name,$value_to_match)=mysql_fetch_array($get_value_to_match)){					

					$field_name_to_compare = ($db_table=='m_consult_ccdev_vaccine')?'vaccine_id':'service_id';
					$field_date = ($db_table=='m_consult_ccdev_vaccine')?'actual_vaccine_date':'ccdev_service_date';


					$q_health_data = mysql_query("SELECT ".$emr_field_name." FROM ".$db_table." WHERE ".$field_name_to_compare."='".$value_to_match."' AND patient_id='".$pxid."'") or die("Cannot query 425: ".mysql_error());
					

					if(mysql_num_rows($q_health_data)==0): //the vaccine_id/service_id not seen, insert the ID
						//echo $db_table.'/'.$emr_field_name.'/'.$tablet_field_name.'/'.$value_to_match."<br>";

						$date_given = convert_date($arr_tablet[$tablet_field_name]); 
					
						if($date_given!="0000-00-00"):					

							$str_sql = "INSERT INTO ".$db_table." SET ".$field_date."='".$date_given."', consult_id='".$consult_id."',user_id='".$userid."',patient_id='".$pxid."', ".$field_name_to_compare."='".$value_to_match."',ccdev_id='".$ccdev_id."'";

							//echo $str_sql."<br><br>";

							$q_insert_health_data = mysql_query($str_sql) or die("Cannot query 440: ".mysql_error());

							$q_insert_vaccine = mysql_query("INSERT INTO m_consult_vaccine SET consult_id='$consult_id',patient_id='$pxid',user_id='$userid',vaccine_timestamp=NOW(),actual_vaccine_date='$date_given',source_module='ccdev',adr_flag='N',vaccine_id='$value_to_match'") or die("Cannot query 529: ".mysql_error());

							$insert_made = 1;
						else: 
							
						endif;
					else:
						list($emr_date_value) = mysql_fetch_array($q_health_data);						

						if($emr_date_value!='0000-00-00'):

						$tablet_date = convert_date($arr_tablet[$tablet_field_name]); 
						if($emr_date_value!=$tablet_date): //conflicting data, allow the user to select				
							array_push($arr_conflict_data,array($db_table,$field_name_to_compare,$emr_date_value,$tablet_date,$value_to_match,$field_date,$tablet_field_name));
						endif;

						endif;
					endif;

				}



				if($insert_made==1 && !empty($_GET["uid"])):
					$healthcenter_id = $_SESSION["datanode"]["code"];

					$q_insert_consult = mysql_query("INSERT INTO m_consult SET consult_id='$consult_id',patient_id='$pxid',user_id='$userid',healthcenter_id='$healthcenter_id',consult_timestamp=NOW(),consult_end=NOW(),consult_date=NOW(),see_doctor_flag='N'");

					echo "<script language='Javascript'>";
					echo "window.alert('The record for $_GET[program] was saved.')";
					echo "</script>";

					header("Location:".$_SERVER["PHP_SELF"]."?type=$_GET[type]&title=$_GET[title]&uid=$_GET[uid]&program=$_GET[program]&action=$_GET[action]");

				else: 
					echo "<script language='Javascript'>";
					echo "window.alert('No update found for this client record for the $_GET[program]')";
					echo "</script>"; 
				endif;
				
				if(count($arr_conflict_data)!=0):
					echo "<script language='Javascript'>";
					echo "window.alert('There was a data conflict identified. Select whether to save the mobile device or the EMR value.')";
					echo "</script>"; 
					display_conflict_date($arr_conflict_data,$uid,$pxid);
				endif;

				break;
			
			default:

				break;
		}		
	}

	function display_conflict_date($arr_conflict_data,$uid,$pxid){

		if(count($arr_conflict_data)!=0 && $_GET["uid"]==$uid):
			echo "<form action='$_SERVER[PHP_SELF]?type=$_GET[type]&title=$_GET[title]&$_GET[uid]&program=$_GET[program]&action=$_GET[action]' method='POST'>";
			
			echo "<input type='hidden' name='pxid' value='$pxid'></input>";
			echo "<input type='hidden' name='uid' value='$uid'></input>";

			echo "<br><br>";

			echo "<table>";
			echo "<tr>";
			echo "<td align='center' colspan='3' class='tr_inner_label'>Data Conflicts</td>";
			echo "</tr>";
			
			echo "<tr align='center' class='tr_inner_label'>";
			echo "<td>Field</td>";
			echo "<td>EMR</td>";
			echo "<td>Tablet</td>";
			echo "</tr>";
			

			foreach($arr_conflict_data as $key=>$value){
				echo "<tr>";

				echo "<td>";
				echo $value[4];
				echo "</td>";

				echo "<td>";
				echo "<input type='radio' name='$value[4]' value='$value[2]*$value[0]*$value[1]*$value[4]*$value[5]*$value[6]*emr'  checked>$value[2]";
				echo "</td>";

				echo "<td>";
				if($value[3]!='0000-00-00'):
					echo "<input type='radio' name='$value[4]' value='$value[3]*$value[0]*$value[1]*$value[4]*$value[5]*$value[6]*tablet'>$value[3]";
				endif;
				echo "</td>";

				echo "</tr>";
			}
			echo "<tr>";
			echo "<td align='center' colspan='3'><input type='submit' name='submit_conflict' value='Save' /></td>";
			echo "</tr>";

			echo "</table>";
			echo "</form>";
		endif;
	}

	function save_conflict_data($post_data,$pxid,$uid){

			list($value_to_insert,$table_name,$field_to_compare,$value_to_compare,$field_to_insert,$tablet_field_value,$record_source) = explode('*',$post_data);

			/*echo $value_to_insert.'/'.$table_name.'/'.$field_to_compare.'/'.$value_to_compare.'/'.$field_to_insert.'/'.$tablet_field_value.'/'.$record_source;
			echo $pxid;
			echo "<br>";*/
			
			if($record_source=='tablet'):	//copy the tablet data set to the EMR
				$str_sql = "UPDATE $table_name SET $field_to_insert='".$value_to_insert."' WHERE $field_to_compare='".$value_to_compare."' AND patient_id='".$pxid."'";

				$str_sql_main_vacc = "UPDATE m_consult_vaccine SET $field_to_insert='".$value_to_insert."' WHERE $field_to_compare='".$value_to_compare."' AND patient_id='".$pxid."'";

				$q_update_record = mysql_query($str_sql) or die("Cannot query 570: ".mysql_error());
				$q_update_record_vacc = mysql_query($str_sql_main_vacc) or die("Cannot query 570: ".mysql_error());
				
				if($q_update_record):
					echo "<script language='Javascript'>";
					echo "window.alert('Record for $_GET[program] was successfully been updated.')";
					echo "</script>"; 
				endif;

			elseif($record_source=='emr'): //copy the EMR data set to the tablet
				list($y,$m,$d) = explode('-',$value_to_insert);
				$value_to_insert = $m.'/'.$d.'/'.$y;


				$str_json = shell_exec("curl -i -H 'Accept: application/json' ". "http://$_SESSION[txt_ip]:$_SESSION[txt_port]/coconut/".$uid);

				//echo $str_json;
				list($first,$second) = explode("{",$str_json);
				$arr_json = explode(",",$second);

				foreach($arr_json as $key=>$value){
					list($key2,$value2) = explode(":",$value);
					if($key2=='"_rev"'):
						$rev_id = trim($value2,'"');
					endif;
				}
				

				//print_r($_SESSION["str_old_px_id"]);

				foreach($_SESSION["str_old_px_id"] as $key=>$value){
					if($value["_id"]==$uid):
					foreach($value as $key2=>$value2){
						if($key2==$tablet_field_value):
							$str_curl = $str_curl.'"'.$key2.'":'.'"'.$value_to_insert.'",';

						else:
							if($value2==''): 
								$value2 = '';
							endif;
							$str_curl = $str_curl.'"'.$key2.'":'.'"'.$value2.'",';
						endif;
					}

					endif;
				}
				echo "<br><br>";
				$str_curl = rtrim($str_curl,",");

				$curl_command = "curl -i -H 'Accept: application/json' -X PUT -d '{".$str_curl."}' http://$_SESSION[txt_ip]:$_SESSION[txt_port]/coconut/".$uid;

				//echo $curl_command;
				
				if(shell_exec($curl_command)):
					echo "<script language='Javascript'>";
					echo "window.alert('The record from the EMR was successfully copied to the mobile device! Please search and connect again to the device to refresh.')";

					echo "</script>"; 

				else:
					echo "<script language='Javascript'>";
					echo "window.alert('The record from the EMR was NOT successfully copied to the mobile device. Please repeat the process.')";
					echo "</script>"; 
				endif;

			else:

			endif;
	}

	function update_last_sync_date(){
		$q_update = mysql_query("UPDATE m_lib_mm_sync SET last_sync=NOW() WHERE sync_uid='$_GET[uid]'") or die("Cannot query 682: ".mysql_error());	
	}

	echo "</body>";
	echo "</html>";
?>