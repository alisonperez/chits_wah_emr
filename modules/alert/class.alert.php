<?php

class alert extends module{

	function alert(){
		$this->description = "Automated Reminder and Alert Module / SPASMS";
		$this->version = "0.95-".date('Y-m-d');
		$this->author = "darth_ali";
		$this->module = "alert";
		
		$this->mods = array('mc'=>array("Maternal Care"),'sick'=>array("Sick Children Under 5"),'epi'=>array("Expanded Program for Immunization"),'fp'=>array("Birth Spacing / Family Planning"),'notifiable'=>array("Notifiable Diseases"),'philhealth'=>array("PhilHealth"),'tb'=>array("Tuberculosis"));

		$this->images = array('mc'=>'mc_alert.png','epi'=>'epi_alert.jpeg','fp'=>'fp_alert.jpeg','notifiable'=>'notifiable_alert.jpeg','sick'=>'sick_alert.jpeg','philhealth'=>'philhealth_alert.jpg','tb'=>'tb_alert.jpg');
		$this->year = date('Y');
		$this->morb_wk = $this->get_wk_num();

		$this->arr_dep = array("DPT2"=>array('DPT1','28'),"DPT3"=>array('DPT2','28'),"OPV2"=>array('OPV1','28'),"OPV3"=>array('OPV2','28'),"HEPB2"=>array('HEPB1','42'),"HEPB3"=>array('HEPB2','56'),"PENTA2"=>array('PENTA1','28'),"PENTA3"=>array('PENTA2','28'),"ROTA2"=>array('ROTA','28')); //first argument contains the antigen and second contains the 
	}


	function init_deps(){
		module::set_dep($this->module,"module");
		module::set_dep($this->module, "healthcenter");
        	module::set_dep($this->module, "patient");
        	module::set_dep($this->module, "calendar");
        	module::set_dep($this->module, "ptgroup");
        	module::set_dep($this->module, "family");
        	module::set_dep($this->module, "barangay");
	}

	function init_lang(){

	}

	function init_stats(){

	}

	function init_help(){

	}

	function init_menu(){
		if(func_num_args()>0):
			$arg_list = func_get_args();
		endif;

		module::set_menu($this->module,"Alert Types","LIBRARIES","_alert_type");
		module::set_menu($this->module,"Alerts","CONSULTS","_alert");
		module::set_menu($this->module,"SMS Alerts Configuration","LIBRARIES","_sms_config");
		module::set_menu($this->module,"SMS Patient Enrollment","LIBRARIES","_sms_enroll");
		module::set_menu($this->module,"SMS Alerts","CONSULTS","_sms_alert");
		module::set_detail($this->description,$this->version,$this->author,$this->module);
	
	}

	function init_sql(){
		
		//create m_lib_alert_table. this table will contain user-defined alerts and reminders
		module::execsql("CREATE TABLE IF NOT EXISTS `m_lib_alert_type` (
			`alert_id` int(11) NOT NULL AUTO_INCREMENT,
  			`module_id` varchar(50) NOT NULL, `alert_indicator_id` int(2) NOT NULL,,

  			`date_pre` date NOT NULL,`date_until` date NOT NULL,
  			`alert_message` text NOT NULL,`alert_action` text NOT NULL,
  			`date_basis` varchar(50) NOT NULL,`alert_url_redirect` text NOT NULL,
  			PRIMARY KEY (`alert_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;");
			
		module::execsql("CREATE TABLE IF NOT EXISTS `m_lib_alert_indicators` (
		  	`alert_indicator_id` int(11) NOT NULL AUTO_INCREMENT,`main_indicator` varchar(10) NOT NULL,
		  	`sub_indicator` text NOT NULL,`efhsis_code` varchar(25) NOT NULL,
		         PRIMARY KEY (`alert_indicator_id`)
		        ) ENGINE=MyISAM  DEFAULT CHARSET=latin1 ;");

		module::execsql("CREATE TABLE  IF NOT EXISTS `m_lib_sms_px_enroll` (`enroll_id` INT( 20 ) NOT NULL AUTO_INCREMENT PRIMARY KEY		
			,`patient_id` INT( 20 ) NOT NULL ,`program_id` VARCHAR( 20 ) NOT NULL ,`last_modified` DATETIME NOT NULL ,`modified_by` INT( 5 ) NOT NULL) ENGINE = MYISAM ;");

		module::execsql("CREATE TABLE IF NOT EXISTS `m_lib_sms_config` (`sms_config_id` int(2) NOT NULL AUTO_INCREMENT,`sms_url` text NOT NULL,
  			`sms_port` varchar(5) NOT NULL,`sms_time` time NOT NULL,`sms_contact_info` text NOT NULL,`sms_sending_method` set('auto','manual') NOT NULL,`sms_test_message` text NOT NULL,`sms_test_number` varchar(15) NOT NULL,`sms_last_edited` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,`sms_edited_by` varchar(3) NOT NULL,PRIMARY KEY (`sms_config_id`)) ENGINE=MyISAM;");

		module::execsql("INSERT INTO `m_lib_alert_indicators` (`alert_indicator_id`, `main_indicator`, `sub_indicator`, `efhsis_code`) VALUES
		(1, 'mc', 'Quality Prenatal Visit', ''),(2, 'mc', 'Expected Date of Delivery', ''),(3, 'mc', 'Postpartum Visit', ''),(4, 'mc', 'Tetanus Toxoid Intake (CPAB)', ''),
		(5, 'mc', 'Vitamin A Intake (20,000 unit)', ''),(6, 'mc', 'Iron with Folic Acid Intake', ''),(7, 'epi', 'BCG Immunization', ''),(8, 'epi', 'DPT 1 Immunization', ''),
		(9, 'epi', 'DPT 2 Immunization', ''),(10, 'epi', 'DPT 3 Immunization', ''),(11, 'epi', 'OPV 1 Immunization', ''),(12, 'epi', 'OPV 2 Immunization', ''),
		(13, 'epi', 'OPV 3 Immunization', ''),(14, 'epi', 'Hepa B1 Immunization', ''),(15, 'epi', 'Hepa B2 Immunization', ''),(16, 'epi', 'Hepa B3 Immunization', ''),
		(17, 'epi', 'Measles Immunization', ''),(18, 'epi', 'Fully Immunized Child', ''),(19, 'epi', 'Completely Immunized Child', ''),(20, 'sick', 'Vitamin A Supplementation', ''),
		(21, 'sick', 'Diarrhea Case for 6-11 and 12-72', ''),(22, 'fp', 'Pill Intake Follow-Up', ''),(23, 'fp', 'Condom Replenishment Follow-Up', ''),
		(24, 'fp', 'IUD Follow-Up', ''),(25, 'fp', 'Injectables Follow-Up', ''),(26, 'fp', 'Pills Dropout Alert', ''),
		(27, 'fp', 'Condom Dropout Alert', ''),(28, 'fp', 'IUD Dropout Alert', ''),(29, 'fp', 'Injectables Dropout Alert', ''),
		(30, 'fp', 'Female Sterilization Dropout Alert', ''),(31, 'fp', 'Male Sterilization Dropout Alert', ''),(32, 'fp', 'NFP LAM Dropout Alert', '');");
		
	}

	function drop_tables(){
		module::execsql("DROP TABLE `m_lib_alert_type`;");
		module::execsql("DROP TABLE `m_lib_alert_indicators`;");
	}



	// custom-built functions
	
	function _alert_type(){
		echo "<span class='library'>REMINDER AND ALERT ADMINISTRATION</span>";
		echo "<p align='justify'>The Alert and Reminder administration page will allow the end-user to set necessary messages for the various indicators listed. It also always the user to set the number of days the message will be posted in advance and its duration.</p>";
		
		if($_POST[submit_alert]=='Save Reminder/Alert'):
			$this->verify_form($_POST);
		elseif($_POST[submit_alert]=='Update Reminder/Alert'):
			$this->verify_form($_POST);
		elseif($_POST[submit_alert]=='Delete Reminder/Alert'):
			$this->verify_form($_POST);
		else:
			
		endif;
		
		$vals_update = $this->set_vals_update($_GET);
		
		$main_indicator = (!empty($_POST[sel_mods]))?($_POST[sel_mods]):($vals_update["module_id"]);


		$q_indicator = mysql_query("SELECT alert_indicator_id,main_indicator,sub_indicator FROM m_lib_alert_indicators WHERE main_indicator='$main_indicator' ORDER by sub_indicator ASC") or die("Cannot query: 94 ".mysql_error());

		echo "<form name='form_alert_lib' method='POST' action='$_SERVER[PHP_SELF]?page=$_GET[page]&menu_id=$_GET[menu_id]#alert'>";

		echo "<input type='hidden' name='confirm_delete' value='0'>";

		echo "<a name='alert'></a>";
		
		echo "<table bgcolor='ffccff'>";
		echo "<tr><td width='65%' valign='top'>";
		
		echo "<table bgcolor='#FFCCFF'>";
		echo "<tr class='alert_table_header'><td colspan='2'>REMINDER & ALERT ADMINISTRATION</td></tr>";

		echo "<tr>";
		echo "<td  class='alert_table_row'>Health Program</td>";
		echo "<td>";
		echo "<select name='sel_mods' size='1' onchange=\"autoSubmit_alert();\">";
		
		echo "<option value='0'>---- SELECT PROGRAM ----</option>";
		
		foreach($this->mods as $key=>$value){
			foreach($value as $key2=>$value2){
				if($key==$_POST[sel_mods]):
					echo "<option value='$key' SELECTED>$value2</option>";
				elseif($key==$vals_update["module_id"]):
					echo "<option value='$key' SELECTED>$value2</option>";
				else:
					echo "<option value='$key'>$value2</option>";
				endif;
			}
		}

		echo "</select>";
		echo "</td>";
		echo "</tr>";
		
		echo "<tr>";
		
		echo "<td class='alert_table_row'>Reminder/Alert Event</td>";
		echo "<td>";
				
		echo "<select name='sel_alert_indicators' size='1'>";
		
		if(mysql_num_rows($q_indicator)!=0):
			while(list($ind_id,$main_ind,$sub_ind)=mysql_fetch_array($q_indicator)){
				if($ind_id==$vals_update["alert_indicator_id"]):
					echo "<option value='$ind_id' SELECTED>$sub_ind</option>";				
				else:
					echo "<option value='$ind_id'>$sub_ind</option>";				
				endif;
				
			}
		else:
			echo "<option value='$ind_id' disabled>$sub_ind</option>";
		endif;

		echo "</select>";

		echo "</td>";	
		echo "</tr>";

		echo "<tr>";
		echo "<td valign='top' class='alert_table_row'>Reminder Message (Pre-event)</td>";
		echo "<td>";
		echo "<textarea name='txt_msg' cols='25' rows='3'>$vals_update[alert_message]";
		echo "</textarea>";
		echo "</td>";
		echo "</tr>";


		echo "<tr>";
		echo "<td valign='top' class='alert_table_row'>Message on the actual occurence of event</td>";
		echo "<td>";
		echo "<textarea name='txt_actual_msg' cols='25' rows='3'>$vals_update[alert_actual_message]";
		echo "</textarea>";
		echo "</td>";
		echo "</tr>";

		echo "<tr>";
		echo "<td valign='top' class='alert_table_row'>Alert Message (Post-event)</td>";
		echo "<td>";
		echo "<textarea name='txt_action' cols='25' rows='3'>$vals_update[alert_action]";
		echo "</textarea>";
		echo "</td>";
		echo "</tr>";

		echo "<tr>";
		echo "<td valign='top' class='alert_table_row'>No. of Days Reminder is posted before event date</td>";
		echo "<td>";
		echo "<select name='sel_days_before' size='1'>";
		
		for($i=0;$i<=100;$i++){
			if($i==$vals_update[date_pre]):
				echo "<option value='$i' SELECTED>$i</option>";
			else:
				echo "<option value='$i'>$i</option>";
			endif;
		}
		
		echo "</select>";
		echo "&nbsp;&nbsp;days (setting to 0 means actual date)</td>";
		echo "</tr>";

		echo "<tr>";
		echo "<td class='alert_table_row'>No. of Days Reminder is posted after event date</td>";
		echo "<td>";
		echo "<select name='sel_days_after' size='1'>";
		
		for($i=0;$i<=100;$i++){
			if($i==$vals_update[date_until]):
				echo "<option value='$i' SELECTED>$i</option>";
			else:
				echo "<option value='$i'>$i</option>";
			endif;
		}
		echo "</select>";
		echo "&nbsp;&nbsp;days (setting to 0 means alert will be displayed until record is updated)</td>";
		echo "</tr>";

		echo "<tr>";
		echo "<td></td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td class='alert_table_row'>URL for data entry</td>";
		echo "<td>";
		echo "<input type='text' name='txt_url' size='25' value='$vals_update[alert_url_redirect]'></input>";
		echo "</td>";
		echo "</tr>";

		if($vals_update['alert_flag_activate']=='Y'):
			$y_activate = 'SELECTED';
		elseif($vals_update['alert_flag_activate']=='N'):
			$n_activate = 'SELECTED';
		else: 
		endif;

		echo "<tr>";
		echo "<td class='alert_table_row'>Activate SMS Message?</td>";
		echo "<td>";
		echo "<select name='sel_activate' size='1'>";
		echo "<option value='Y' $y_activate>Yes</option>";
		echo "<option value='N' $n_activate>No</option>";
		echo "</select>";
		echo "</td>";
		echo "</tr>";
		
		echo "<tr align='center'>";
		echo "<td colspan='2'>";

		if(!isset($vals_update)):
			echo "<input type='submit' name='submit_alert' value='Save Reminder/Alert'></input>&nbsp;&nbsp;";
		else:
			echo "<input type='submit' name='submit_alert' value='Update Reminder/Alert'></input>&nbsp;&nbsp;";
			echo "<input type='submit' name='submit_alert' value='Delete Reminder/Alert'></input>&nbsp;&nbsp;";
		endif;
		echo "<input type='reset' name='clear' value='Clear'></input>";
		echo "</td>";
		echo "</tr>";
		
		echo "</table>";

		echo "</td>";

		echo "<td>";
		echo '&nbsp;&nbsp;&nbsp;&nbsp;';
		echo "</td>";


		echo "<td valign='top'>";
		
		echo "<table bgcolor='#FFCCFF'>";
		echo "<tr valign='top'><td colspan='2' class='alert_table_header'>LIST of REMINDERS & ALERTS</td></tr>";
		
		$this->list_alert();

		echo "</table>";
		
		echo "</td>";

		echo "</table>";

		echo "</form>";
	}

	function _alert(){
		//echo "this is the container for the alert and reminder master list";
		echo "<form action='$_SERVER[PHP_SELF]?page=$_GET[page]&menu_id=$_GET[menu_id]#reminder' method='POST'>";
		echo "<a name='reminder'></a>";
		echo "<table bgcolor='#FFCCFF' id='alert_table'>";
		echo "<tr class='alert_table_header'><td colspan='".(count($this->mods)+1)."'>REMINDER and ALERT MONITORING WINDOW</td></tr>";
		echo "<tr>";
		echo "<td colspan='".(count($this->mods)+1)."' class='alert_table_row'>";
		echo $this->show_barangay();
		echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Year";
		echo $this->show_current_yr();
		echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Week ";
		echo $this->show_current_wk();
		echo "</td>";
		echo "</tr>";
		
		echo "<tr class='alert_table_row'>";
		echo "<td>Barangay / Household</td>";
		$this->show_categories();
		echo "</tr>";

		$this->show_brgy_hh();

		echo "</table>";
		echo "</form>";
	}
	
	function _sms_config(){
		if($_POST['submit_alert']=='Save Configuration'): 
			if(!$this->check_sms_field($_POST)):

			else:
				if(!is_numeric($_POST[txt_testnum]) && strlen($_POST[txt_testnum])!=11):
					echo "<script language='javascript'>";
					echo "window.alert('Test cellphone number is not valid number.')";
					echo "</script>";
				else:
					$time_send = $_POST['sel_hr'].':'.str_pad($_POST['sel_min'],2,0).' '.$_POST['sel_day'];

					if($this->test_sms($_POST)):   //if SMS was successfully been sent, store the setup to the database

						$get_sms = mysql_query("SELECT sms_config_id from m_lib_sms_config") or die("Cannot query 312: ".mysql_error());

						if(mysql_num_rows($get_sms)!=0):
							$sms_id = mysql_fetch_array($get_sms);
							$q_sms = mysql_query("UPDATE m_lib_sms_config SET sms_url='$_POST[txt_midserver]',sms_port='$_POST[txt_port]',sms_time='$time_send',sms_contact_info='$_POST[txt_contact]',sms_sending_method='$_POST[sel_method]',sms_test_message='$_POST[txt_testmsg]',sms_test_number='$_POST[txt_testnum]',sms_last_edited=NOW(),sms_edited_by='$_SESSION[userid]'") or die("Cannot query 315: ".mysql_error());
						else:
							$q_sms = mysql_query("INSERT into m_lib_sms_config SET sms_url='$_POST[txt_midserver]', sms_port='$_POST[txt_port]',sms_time='$time_send',sms_contact_info='$_POST[txt_contact]', sms_sending_method='$_POST[sel_method]',sms_test_message='$_POST[txt_testmsg]', sms_test_number='$_POST[txt_testnum]',sms_last_edited=NOW(), sms_edited_by='$_SESSION[userid]'") or die("Cannot query 315: ".mysql_error());
						endif;

						if($q_sms):
							echo "<script language='javascript'>";
							echo "window.alert('The SMS configuration was successfully been saved! You should be able to receive the test messages shortly.')";
							echo "</script>";
						endif;
					else:
						echo "<script language='javascript'>";
						echo "window.alert('Test sending failed. Please supply the correct values.')";
						echo "</script>";
					endif;
				endif;
				
			endif;
		endif;

		$q_sms_info = mysql_query("SELECT * FROM m_lib_sms_config") or die("Cannot query 339 ".mysql_error());
		$sms_info = mysql_fetch_array($q_sms_info);
		$arr_sms_time = explode(':',$sms_info['sms_time']);
		
		echo "<span class='library'>REMINDER AND ALERT ADMINISTRATION</span>";
		echo "<p align='justify'>The SMS Alert and Configuration page will allow the end-user to configure the sending of the SMS messages. To facilitate sending of SMS, set a valid SMS gateway and port. The <b>Time for Batch Sending</b> is a reserved time within the day wherein the SMS messages are going to be sent out. <br><br>The <b>Contact Information Message</b> is a customizable message appended at the end of the SMS and has dynamic values. Append the keywords ".'$midwife for name of midwive, $bhs for name of barangay health station, $source name of health center and $msgcode for message code'."<br><br>To test if the settings are working, to test if the values are correct, enter your mobile number at the <b>Test Number</b> box and a sample message at the <b>Test Message</b> box. You should be able to receive the message in the number you supplied.</p>";
		
		echo "<form action='$_SERVER[PHP_SELF]?page=$_GET[page]&menu_id=$_GET[menu_id]#sms' name='form_sms' method='POST'>";
		echo "<a name='sms'></a>";
		echo "<table width='600' bgcolor='FFCCFF'>";
 		echo "<thead><td class='alert_table_header' colspan='2'>SMS ALERT CONFIGURATION PAGE</td></thead>";
		//echo "<tr><td colspan='2' class='alert_table_row'><b>This is the main configuration page for the SMS Alert System. Supply proper values for the SMS settings. To test if the values are correct, enter your mobile number at the 'Test Number' box and a sample message at the 'Test Message' box. You should be able to receive the message in the number you supplied. </b></td></tr>";
		
		echo "<tr><td class='alert_table_row'>URL of the middle server</td>";
		echo "<td><input type='text' name='txt_midserver' value='$sms_info[sms_url]'></td></tr>";

		echo "<tr><td class='alert_table_row'>Port Number</td>";
		echo "<td><input type='text' name='txt_port' value='$sms_info[sms_port]'></td></tr>";

		echo "<tr><td class='alert_table_row'>Time For Batch Sending</td>";
		echo "<td><select name='sel_hr' value='1'>";
		for($i=1;$i<=23;$i++){
			if($i!=$arr_sms_time[0]):
				echo "<option value='$i'>$i</option>";
			else:
				echo "<option value='$i' SELECTED>$i</option>";
			endif;
		}
		echo "</select>";

		echo "<b>:</b><select name='sel_min'>";
		for($i=0;$i<=59;$i++){
			if($i!=$arr_sms_time[1]):
				echo "<option value='".str_pad($i)."'>".str_pad($i,2,0,STR_PAD_LEFT)."</option>";
			else:
				echo "<option value='".str_pad($i)."' SELECTED>".str_pad($i,2,0,STR_PAD_LEFT)."</option>";
			endif;
		}		
		echo "</select>";
		//echo "&nbsp;<select name='sel_day'>";	
		//echo "<option value='AM'>AM</option>";
		//echo "<option value='PM'>PM</option>";
		//echo "</select>";
		echo "</td></tr>";
		
		echo "<tr valign='top'><td class='alert_table_row'>Contact Information Message<br>(ie. name of midwife, BHS, health center)</td>";
		echo "<td><textarea name='txt_contact' cols='30' rows='5'>$sms_info[sms_contact_info]</textarea></tr>";

		echo "<tr><td class='alert_table_row'>Method of Sending</td>";
		echo "<td><select name='sel_method'>";
		
		$auto_mode = $manual_mode = '';
		if($sms_info['sms_sending_method']=='auto'):
			$auto_mode = 'SELECTED';
		elseif($sms_info['sms_sending_method']=='manual'):
			$manual_mode = 'SELECTED';
		else:
		endif;

		echo "<option value='auto' $auto_mode>Automatic</option>";
		echo "<option value='manual' $manual_mode>Manual</option>";
		echo "</select>";
		echo "</td></tr>";
	
		echo "<tr><td class='alert_table_row'>Test Message<br></td>";
		echo "<td><input type='text' name='txt_testmsg' value=''>";
		echo "</td></tr>";

		echo "<tr><td class='alert_table_row'>Test Number (11 digits)<br></td>";
		echo "<td><input type='text' name='txt_testnum' value=''>";
		echo "</td></tr>";

		echo "<tr><td colspan='2' align='center'>";
		echo "<input type='submit' name='submit_alert' value='Save Configuration' />&nbsp;&nbsp;";
		echo "<input type='reset' name='Clear' />";
		echo "</td></tr>";
		echo "</table>";
		echo "</form>";
	}

	function _sms_enroll(){
		
		$this->check_sms_alert();

		echo "<form action='$_SERVER[PHP_SELF]?page=$_GET[page]&menu_id=$_GET[menu_id]#px' name='form_sms' method='POST'>";
		echo "<input type='hidden' name='pxid' />";
		
		/*echo "<a name='px'></a>";
		echo "<span class='library'>SMS PATIENT ENROLLMENT FORM</span><br><br>";
		echo "<table border='1' width='600'>";
		echo "<thead><td>Search the name of the patient</td></thead>"; */

		echo "<tr>";
		echo "<td>";
		echo "</td>";
		echo "</tr>";

		echo "<tr>";
		echo "</tr>";
		echo "</table>";
	}

	function list_alert(){
		echo "<tr class='alert_table_row'><td>Program</td><td>Indicators</td></tr>";
		foreach($this->mods as $key=>$value){
			$q_mods = mysql_query("SELECT a.alert_id,a.module_id,a.alert_indicator_id,a.date_pre,a.date_until,a.alert_message,a.alert_action,a.date_basis,a.alert_url_redirect,b.sub_indicator FROM m_lib_alert_type a, m_lib_alert_indicators b WHERE a.module_id='$key' AND a.alert_indicator_id=b.alert_indicator_id ORDER by b.sub_indicator ASC") or die("Cannot query 285 ".mysql_error());
			
			$rec_num = mysql_num_rows($q_mods);
			if(mysql_num_rows($q_mods)!=0):
				echo "<tr>";
				echo "<td valign='top' class='alert_table_row'>$value[0]</td>";
				echo "<td>";
				while($r_ind = mysql_fetch_array($q_mods)){
					echo "<a href='$_SERVER[PHP_SELF]?page=$_GET[page]&menu_id=$_GET[menu_id]&indicator_id=$r_ind[alert_indicator_id]&action=update#alert'>$r_ind[sub_indicator]</a><br><br>";
				}
				echo "</td>";
				echo "</tr>";
			endif;
		}
	}

	function _sms_alert(){
		$this->send_basic_stat();
		$this->check_sms_appt();

		if($_POST['submit_alert']=='Send Manually'):
			$arr_config = $this->get_sms_config();
			foreach($_POST['sms'] as $key=>$sms_id){
				$q_sms_message = mysql_query("SELECT sms_number, sms_message from m_lib_sms_alert WHERE sms_id='$sms_id'") or die("Cannot query 496: ".mysql_error()); 
				list($sms_number,$sms_message) = mysql_fetch_array($q_sms_message);
				
				if($this->send_sms($arr_config['sms_url'],$arr_config['sms_port'],$sms_number,$sms_message)):
					$this->update_sms_status($sms_id,'sent');
				else:
					$this->update_sms_status($sms_id,'not_sent');
				endif;
			}

		elseif($_POST['submit_alert']=='Hold Message'):
			foreach($_POST['sms'] as $key=>$sms_id){
				$this->update_sms_status($sms_id,'hold');
			}
		elseif($_POST['submit_alert']=='Terminate Message'):
			foreach($_POST['sms'] as $key=>$sms_id){
				$this->update_sms_status($sms_id,'terminate');
			}

		elseif($_POST['submit_alert']=='Go to Date'):
			
		else:
		endif;

		if(!isset($_POST['date_alert'])):
			$date_today = date('Y-m-d');
			$q_sms_alert = mysql_query("SELECT sms_id,patient_id,barangay_id,program_id,alert_id,alert_date,base_date,sms_status,sms_message,sms_code,sms_number FROM m_lib_sms_alert WHERE alert_date='$date_today'") or die("Cannot query 490: ".mysql_error());
		else: 	
			$arr_date = explode('/',$_POST["date_alert"]);
			$date_today = $arr_date[2].'-'.$arr_date[0].'-'.$arr_date[1];

			$q_sms_alert = mysql_query("SELECT sms_id,patient_id,barangay_id,program_id,alert_id,alert_date,base_date,sms_status,sms_message,sms_code,sms_number FROM m_lib_sms_alert WHERE alert_date='$date_today' ORDER by barangay_id ASC") or die("Cannot query 490: ".mysql_error());
		endif;
		
		echo "<span class='library'>SMS ALERT MESSAGE FOR BROADCASTING</span>";
		echo "<p align='justify'>The SMS Alert Message for Broadcasting page displays the SMS message for sending on the date specified. The messages are automatically being sent out based on the scheduled time set in the configuration page. If the message were unsuccessfully been sent, it could be sent manually by ticking the checkbox next to the record and pressing the <b>Send Manually </b>button. The messages can be suspended by pressing the <b>Hold Message</b> button. To display the message to be sent, click </p>";

		echo "<form action='$_SERVER[PHP_SELF]?page=$_GET[page]&menu_id=$_GET[menu_id]' method='POST' name='sms_form'>";
		echo "<table bgcolor='FFCCFF'>";
		echo "<thead><td colspan='9' class='alert_table_header'>SMS ALERT MESSAGE FOR BROADCASTING ON</td></thead>";
		echo "<tr><td colspan='9' class='alert_table_header'>";
		echo "<input type='text' size='10' maxlength='10' class='textbox' name='date_alert' value='".(isset($_POST["date_alert"])?($_POST["date_alert"]):(date('m/d/Y')))."' style='border: 1px solid #000000'>";
        
        
	        echo "<a href=\"javascript:show_calendar4('document.sms_form.date_alert', document.sms_form.date_alert.value);\"><img src='../images/cal.gif' width='16' height='16' border='0' alt='Click Here to Pick up the date'></a>";
		
		echo "<input type='submit' name='submit_alert' value='Go to Date' />";
		echo "</td></tr>";
		if(mysql_num_rows($q_sms_alert)!=0):

			echo "<tr>";
			echo "<td class='alert_table_row'>&nbsp;</td>";
			echo "<td class='alert_table_row'>&nbsp;SMS Code&nbsp;</td>";
			echo "<td class='alert_table_row'>&nbsp;Name Recipient&nbsp;</td>";
			echo "<td class='alert_table_row'>&nbsp;SMS Number&nbsp;</td>";
			echo "<td class='alert_table_row'>&nbsp;Barangay&nbsp;</td>";
			echo "<td class='alert_table_row'>&nbsp;Program&nbsp;</td>";
			echo "<td class='alert_table_row'>&nbsp;Alert Type&nbsp;</td>";
			echo "<td class='alert_table_row'>&nbsp;Sending Status&nbsp;</td>";
			echo "<td class='alert_table_row'>&nbsp;View Message&nbsp;</td>";
			echo "</tr>";

			while(list($sms_id,$pxid,$brgy_id,$program,$alert,$alert_date,$base_date,$sms_status,$sms_message,$sms_code,$sms_number)=mysql_fetch_array($q_sms_alert)){

				if($program=='user'):
					list($code,$user_id) = explode('-',$pxid);
					$q_user_name = mysql_query("SELECT user_lastname, user_firstname FROM game_user WHERE user_id='$user_id'") or die("Cannot query 565: ".mysql_error());
					list($lname,$fname) = mysql_fetch_array($q_user_name);

					$main_indicator = 'user';
					$sub_indicator = 'basic';
					
					$pre_msg = "Good day! This is an electronically generated SMS from WAH at ".$_SESSION["datanode"]["name"].".Thank you.";

				else:
					$q_px_num  = mysql_query("SELECT patient_lastname, patient_firstname FROM m_patient WHERE patient_id='$pxid'") or die("Cannot query 520: ".mysql_error());
					list($lname,$fname) = mysql_fetch_array($q_px_num);

					$q_brgy = mysql_query("SELECT barangay_name FROM m_lib_barangay WHERE barangay_id='$brgy_id'") or die("Cannot query 523: ".mysql_error());
					list($brgy_name) = mysql_fetch_array($q_brgy);

					$q_program = mysql_query("SELECT main_indicator,sub_indicator FROM m_lib_alert_indicators WHERE alert_indicator_id='$alert'") or die("Cannot query 526 ".mysql_error());
					list($main_indicator,$sub_indicator) = mysql_fetch_array($q_program);
				endif;

				

				echo "<tr align='center'>";
				echo "<td>";
				if($sms_status!='sent'):
					echo "<input type='checkbox' name='sms[]' value='$sms_id'></input>";
				else:
					echo "&nbsp;";
				endif;
				echo "</td>";
				echo "<td>$sms_code</td>";
				echo "<td>$lname, $fname</td>";
				echo "<td>$sms_number</td>";
				echo "<td>$brgy_name</td>";
				echo "<td>$main_indicator</td>";
				echo "<td>$sub_indicator</td>";
				echo "<td>$sms_status</td>";

				if($program=='user'):
					echo "<td><a href='#' onclick=\"window.alert('".$pre_msg.'\n\n'.$sms_message."');return true;\">View</a></td>";
				else:
					echo "<td><a href='#' onclick=\"window.alert('".$sms_message."');return true;\">View</a></td>";
				endif;

				echo "</tr>";
			}

			echo "<tr>";
			echo "<td colspan='9' align='center'>";
			echo "<input type='submit' value='Send Manually' name='submit_alert' />&nbsp;&nbsp;";
			echo "<input type='submit' value='Hold Message' name='submit_alert' />&nbsp;&nbsp;";
			echo "<input type='submit' value='Terminate Message' name='submit_alert' />&nbsp;&nbsp;";
			echo "</td></tr>";

		else: 
			echo "<tr><td>No scheduled SMS messages to be broadcasted.</td></tr>";
		endif;

		echo "</table>";
		echo "</form>";

	}

	function set_vals_update($get_arr){

		if($get_arr["action"]=='update'):
			
			$q_indicator = mysql_query("SELECT a.alert_id,a.module_id,a.alert_indicator_id,a.date_pre,a.date_until,a.alert_message,a.alert_action,a.date_basis,a.alert_url_redirect,b.sub_indicator,a.alert_actual_message,a.alert_flag_activate,a.alert_url_redirect FROM m_lib_alert_type a, m_lib_alert_indicators b WHERE a.alert_indicator_id='$get_arr[indicator_id]' AND a.alert_indicator_id=b.alert_indicator_id") or die("Cannot query 306 ".mysql_error());

			if(mysql_num_rows($q_indicator)!=0):
				$indicator_arr = mysql_fetch_array($q_indicator);
			endif;
		endif;

		return $indicator_arr;
	}

	function verify_form($post_arr){

		$q_alert = mysql_query("SELECT alert_id,alert_indicator_id FROM m_lib_alert_type WHERE alert_indicator_id='$post_arr[sel_alert_indicators]'") or die("Cannot query 74 ".mysql_error());

			if(mysql_num_rows($q_alert)!=0 && $post_arr[submit_alert]=='Save Reminder/Alert'):
				echo "<script language='javascript'>";
				echo "window.alert('There is already a definition for this alert. To update click the alert link on the right side panel.')";
				echo "</script>";

			elseif(empty($post_arr[sel_alert_indicators])):
				echo "<script language='javascript'>";
				echo "window.alert('No indicator was selected. Please select one.')";
				echo "</script>";	
			elseif($post_arr[submit_alert]=='Delete Reminder/Alert'):
				$q_delete = mysql_query("DELETE FROM m_lib_alert_type WHERE alert_indicator_id='$post_arr[sel_alert_indicators]'") or die("Cannot query 327 ".mysql_error());

				if($alert_transact):
					echo "<script language='javascript'>";
					echo "window.alert('Alert was successfully been deleted.')";
					echo "</script>";
				endif;

			else:
				
				/*if(empty($post_arr[txt_msg]) || empty($post_arr[txt_action])):
					
					echo "<script language='javascript'>";
					echo "window.alert('Please supply entry for reminder / alert message or actions.')";
					echo "</script>";
					
				else: */
					if($post_arr[submit_alert]=='Save Reminder/Alert'):
					
					$alert_transact = mysql_query("INSERT INTO m_lib_alert_type SET module_id='$post_arr[sel_mods]',alert_indicator_id='$post_arr[sel_alert_indicators]',date_pre='$post_arr[sel_days_before]',date_until='$post_arr[sel_days_after]',alert_message='$post_arr[txt_msg]',alert_action='$post_arr[txt_action]',alert_actual_message='$post_arr[txt_actual_msg]',alert_flag_activate='$post_arr[sel_activate]'") or die("Cannot query: 107");

					elseif($post_arr[submit_alert]=='Update Reminder/Alert'): 
					$alert_transact = mysql_query("UPDATE m_lib_alert_type SET module_id='$post_arr[sel_mods]',alert_indicator_id='$post_arr[sel_alert_indicators]',date_pre='$post_arr[sel_days_before]',date_until='$post_arr[sel_days_after]',alert_message='$post_arr[txt_msg]',alert_action='$post_arr[txt_action]',alert_actual_message='$post_arr[txt_actual_msg]',alert_flag_activate='$post_arr[sel_activate]' WHERE alert_indicator_id='$post_arr[sel_alert_indicators]'") or die("Cannot query: 341");

					else:
					endif;
					
					if($alert_transact):
						echo "<script language='javascript'>";
						echo "window.alert('Alert was successfully been saved. To edit, click the alert link on the right side panel.')";
						echo "</script>";
					endif;
								
				//endif;
			endif;

	}

	function get_wk_num(){
		$d1 = mktime(0,0,0,1,1,date('Y'));
		$d2 = mktime(0,0,0,date('m'),date('d'),date('Y'));
		
		$wk_num = floor((floor(($d2-$d1)/86400))/7);

		return $wk_num;
	} 

	function show_current_yr(){
		$index = 10;

		echo "<select name='sel_year' size='1'>";
		for($i=(date('Y')-$index);$i<(date('Y')+$index);$i++){			
			if($i==date('Y')):
				echo "<option value='$i' SELECTED>$i</option>";
			else:
				echo "<option value='$i'>$i</option>";
			endif;
		}
		echo "</select>";
	}

	function show_current_wk(){
		echo "<select name='sel_wk' size='1'>";
		for($i=1;$i<=52;$i++){
			if($i==$this->morb_wk):
				echo "<option value='$i' SELECTED>$i</option>";
			else:
				echo "<option value='$i'>$i</option>";
			endif;
		}
		echo "</select>";
	}

	function show_barangay(){
		echo "Barangay ";
		$q_brgy = mysql_query("SELECT barangay_id, barangay_name FROM m_lib_barangay ORDER by barangay_name ASC") or die("Cannot query 422".mysql_error());

		if(mysql_num_rows($q_brgy)!=0):
			echo "<select size='1' name='sel_brgy'>";
			echo "<option value='-'>Select Barangay</option>";
			while($r_brgy = mysql_fetch_array($q_brgy)){
				if($_POST["sel_brgy"]==$r_brgy["barangay_id"]):
					echo "<option value='$r_brgy[barangay_id]' SELECTED>$r_brgy[barangay_name]</option>";
				else:
					echo "<option value='$r_brgy[barangay_id]'>$r_brgy[barangay_name]</option>";
				endif;
			}
			echo "</select>&nbsp;";
			echo "<input type='submit' name='submit_brgy' value='GO'></input>";
		endif;
	}

	function show_categories(){
		foreach($this->mods as $key=>$value){
			echo "<td>$value[0]</td>";
		}
	}

	function show_brgy_hh(){

		//$q_brgy_hh = mysql_query("SELECT a.barangay_id,a.barangay_name,b.family_id,b.patient_id,c.address,d.patient_lastname FROM m_lib_barangay a, m_family_members b, m_family_address c, m_patient d WHERE a.barangay_id=c.barangay_id AND b.family_id=c.family_id AND b.patient_id=d.patient_id GROUP BY a.barangay_name ORDER by a.barangay_name ASC, d.patient_lastname ASC") or die("Cannot query: 426 ".mysql_error()); //select barangay id, household's, houhsehold name

		//$q_brgy_hh = mysql_query("SELECT a.barangay_id,a.barangay_name,b.family_id,b.patient_id,c.address,d.patient_lastname FROM m_lib_barangay a, m_family_members b, m_family_address c, m_patient d WHERE a.barangay_id=c.barangay_id AND b.family_id=c.family_id AND b.patient_id=d.patient_id ORDER by a.barangay_name ASC, d.patient_lastname ASC") or die("Cannot query: 426 ".mysql_error()); //select barangay id, household's, houhsehold name
		
		//while($r_brgy_hh = mysql_fetch_array($q_brgy_hh)){
		//	count($q_brgy_hh);
		//	print_r($r_brgy_hh).'<br>';
		//}

		$q_brgy = mysql_query("SELECT a.barangay_id,a.barangay_name FROM m_lib_barangay a WHERE a.barangay_id='$_POST[sel_brgy]'") or die("Cannot query 460 ".mysql_query());
		
		while($r_brgy = mysql_fetch_array($q_brgy)){
			echo "<tr><td>".$r_brgy["barangay_name"]."</td>";
			
			for($i=0;$i<(count($this->mods));$i++){
				echo "<td>&nbsp;</td>";
			}

			echo "</tr>";

			//$q_hh = mysql_query("SELECT DISTINCT a.family_id,a.patient_id,b.address,c.patient_lastname FROM m_family_members a, m_family_address b, m_patient c WHERE b.barangay_id='$r_brgy[barangay_id]' AND a.family_id=b.family_id AND a.patient_id=c.patient_id ORDER by c.patient_lastname ASC") or die("Cannot query 438 ".mysql_error());
			$q_hh = mysql_query("SELECT DISTINCT a.family_id  FROM m_family_members a, m_family_address b WHERE b.barangay_id='$r_brgy[barangay_id]' AND a.family_id=b.family_id") or die("Cannot query 438 ".mysql_error());

			while(list($fam_id) = mysql_fetch_array($q_hh)){
				$arr_prog = array();
				$arr_res_alert = array();
				$pxid = $px_lastname = '';

				$q_lastname = mysql_query("SELECT a.patient_id,a.patient_lastname FROM m_patient a,m_family_members b WHERE a.patient_id=b.patient_id AND b.family_id='$fam_id' AND b.family_role='head'") or die("Cannot query 449 ".mysql_error());

				if(mysql_num_rows($q_lastname)!=0):
					list($pxid,$px_lastname) = mysql_fetch_array($q_lastname);
				else:
					$q_lastname = mysql_query("SELECT a.patient_id,a.patient_lastname FROM m_patient a,m_family_members b WHERE a.patient_id=b.patient_id AND b.family_id='$fam_id' ORDER by a.patient_lastname ASC LIMIT 1") or die("Cannot query 449 ".mysql_error());

					list($pxid,$px_lastname) = mysql_fetch_array($q_lastname);
				endif;

				if(!empty($pxid)):
				
				echo "<tr bgcolor='#FFFFFF'>";

				if(count($this->determine_alert_hh($fam_id))!=0):
				
					echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;$px_lastname </td>";

				foreach($this->mods as $program_id=>$program_arr){
					$arr_prog = $this->get_indicator_instance($program_id,$fam_id);	
			
					echo "<td align='center'>";
					if(!empty($arr_prog)): 
						$image = $this->images[$program_id];
						$ser_arr = serialize($arr_prog);
						//print_r($arr_prog);
						echo "<a href='../site/show_hh.php?id=$ser_arr&famid=$fam_id' target='_blank'>";
						echo "<img src='../images/$image' width='30' height='30' alt='$program_id' onclick=\"window.open('$_SERVER[PHP_SELF]/site/show_hh.php?id=$ser_arr&famid=$fam_id')\"></img>";
						echo "</a>";
					else:
						echo "&nbsp";
					endif;
					//print_r($arr_prog);
					//print ' '.$program_id;
					echo "</td>";
				} 

				endif;

				endif;
			}
		}

	}


	function get_indicator_instance(){
		//this function accepts the program id (i.e. mc, ccdev) and family id. this should determine if the family, through its individual patients will be able to determine if it is qualified for a reminder for the present week based on the indicator. function should return an array of the indicator with the indicator numbers. 

		if(func_num_args()>0):
			$arr_args = func_get_args();
			$program_id = $arr_args[0];
			$family_id = $arr_args[1];
		endif;
		$arr_case = array();
		$arr_members = $this->get_family_members($family_id); //arr_fam should contain patient_id of the members of family_id
		
		switch($program_id){
			case 'mc':
				$arr_px = $this->mc_alarms($family_id,$arr_members,'mc'); //function call for database query for mc indicators. this should return an array of patient id, indicator, case_id
				break;

			case 'fp':
				$arr_px = $this->fp_alarms($family_id,$arr_members,'fp');
				break;

			case 'epi': 
				$arr_px = $this->epi_alarms($family_id,$arr_members,'epi');
				break;

			case 'philhealth':
				$arr_px = $this->philhealth_alarms($family_id,$arr_members,'philhealth');
				break;
			default:

				break;
		}

		if(!empty($arr_px)):
			array_push($arr_case,$arr_px);
		endif;

		return $arr_case;
	}

	function mc_alarms(){
		if(func_num_args()>0):
			$arr = func_get_args();
			$family_id = $arr[0];
			$members = $arr[1];
			$program_id = $arr[2];
		endif;
		
		$arr_px = array(); //will contain patient id of family_members with any of the cases under indicators
		$arr_fam = array();

		/*the function will accept the family id and family_members
		1).navigate through the mc tables using the patient id of the family. each indicator has its own SQL.
		2). execute on SQL for the indicator, 
		3). pushed the patient_id, indicator id and the consult id to an array back to the calling function (get_indicator_instance)
		4). retrun value is an array of format family_id=>array(patient_id1=>array(indicator_id1=>array(consult_id1,consult_id2,...consult_id[n]),indicator_id2=>array(consult_id1,consult_id2,...,consult_id[n])),patient_id2...);
		*/

		foreach($members as $key=>$patient_id){
			$arr_px = array();
			
			$arr_indicator = array();   //this will contain indicator_id and array of consult_id

			$q_mc_indicators = mysql_query("SELECT alert_indicator_id,sub_indicator FROM m_lib_alert_indicators WHERE main_indicator='$program_id' ORDER by sub_indicator ASC") or die("Cannot query 475: ".mysql_error());


			//$arr_case_id = array(); //this will contain the consult_id and enrollment id's		

			while(list($indicator_id,$sub_indicator) = mysql_fetch_array($q_mc_indicators)){

				$arr_case_id = array(); //this will contain the consult_id and enrollment id's
				$arr_definition = $this->get_alert_definition($indicator_id); //composed of defition id, days before and after. 
				$alert_id = $arr_definition[0];
				$days_before = $arr_definition[1];
				$days_after = $arr_definition[2];
				$date_today = date('Y-m-d');

				//echo $indicator_id.' '.$days_before.'<br>';

				switch($indicator_id){

					case '1':			//indicator id for quality prenatal visit
						$q_mc = mysql_query("SELECT mc_id,trimester1_date,trimester2_date,trimester3_date FROM m_patient_mc WHERE patient_id='$patient_id' AND end_pregnancy_flag='N' AND delivery_date='0000-00-00' AND patient_edc >= NOW()") or die("Cannot query 510 ".mysql_error());
	
						if(mysql_num_rows($q_mc)!=0):
							list($mc_id,$tri1,$tri2,$tri3) = mysql_fetch_array($q_mc);
							$reference_date = (date('Y-m-d')<=$tri1)?$tri1:((date('Y-m-d')<=$tri2)?$tri2:((date('Y-m-d')<=$tri3)?$tri3:''));
							
							$trimester = ($reference_date==$tri1)?1:(($reference_date==$tri2)?2:3);
							//echo $reference_date.'/'.$mc_id.'/'.$trimester;
							
							if($reference_date):
								array_push($arr_case_id,$mc_id,$reference_date); //push if the present date is on or before the reference prenatal visit date
							endif;

						endif;

						break; //end case

					case '2':			//indicator id for EDC
						$q_mc = mysql_query("SELECT mc_id, patient_edc FROM m_patient_mc WHERE patient_id='$patient_id' AND end_pregnancy_flag='N' AND delivery_date='0000-00-00' AND '$date_today' BETWEEN patient_lmp AND patient_edc AND (to_days(patient_edc)-to_days('$date_today')) <= '$days_before'") or die("Cannot query 562 ".mysql_error());

						if(mysql_num_rows($q_mc)!=0):
							list($mc_id,$patient_edc)=mysql_fetch_array($q_mc);
							array_push($arr_case_id,$mc_id,$patient_edc);
						else: 
						endif;
						
						break;
					case '3':			//indicator id for postpartum visit
						//refence date will be the date of delivery. message will appear as long as the duration set in the days_after value (0 - persistent, n - days)

						$q_mc = mysql_query("SELECT mc_id,delivery_date FROM m_patient_mc WHERE patient_id='$patient_id' AND delivery_date!='0000-00-00' AND (to_days('$date_today')-to_days(delivery_date)) >= 0") or die("Cannot query 580 ".mysql_error());

						if(mysql_num_rows($q_mc)!=0):
							list($mc_id,$delivery_date) = mysql_fetch_row($q_mc);
							$q_postpartum = mysql_query("SELECT mc_id FROM m_consult_mc_postpartum WHERE mc_id='$mc_id'") or die("Cannot query 586 ".mysql_error()); //check if the patient has visited during postpartum period

							if(mysql_num_rows($q_postpartum) < 2):  //at least 2 postpartum visits are required. if not satisfied, set 1 to alert flag
								array_push($arr_case_id,$mc_id,$delivery_date);
							endif;
						endif;


						break;
					case '4':			//tetanus toxoid intake (CPAB)
						//determine if the patient has an active pregnancy. 
						$q_mc = mysql_query("SELECT mc_id, patient_edc FROM m_patient_mc WHERE patient_id='$patient_id' AND end_pregnancy_flag='N' AND delivery_date='0000-00-00' AND patient_edc >= NOW()") or die("Cannot query 596 ".mysql_error());
						
						//if it does, determine the status of the tetanus toxoid
						if(mysql_num_rows($q_mc)!=0):
							list($mc_id,$patient_edc) = mysql_fetch_array($q_mc);
							$tt_status = mc::get_tt_status($mc_id,$patient_id,$patient_edc);
							
							if(eregi('not',$tt_status)): // a not substring means that the tt is not active
								array_push($arr_case_id,$mc_id,$patient_edc);
							endif;
						endif;

						break;
					case '5':			//vitamin A intake (200,000 units)
						$q_mc = mysql_query("SELECT mc_id, patient_edc FROM m_patient_mc WHERE patient_id='$patient_id' AND end_pregnancy_flag='N' AND delivery_date='0000-00-00' AND patient_edc >= NOW()") or die("Cannot query 596 ".mysql_error());

						if(mysql_num_rows($q_mc)!=0):
							list($mc_id,$patient_edc) = mysql_fetch_array($q_mc);
							// sql here to determine the vitamin A quantity intake
							$q_vit = mysql_query("SELECT SUM(service_qty) as sum_vita FROM m_consult_mc_services WHERE mc_id='$mc_id' AND service_id='VITA'") or die("Cannot query 615 ".mysql_error());

							list($sum_vita) = mysql_fetch_array($q_vit);
							
							if($sum_vita < 200000): //throw to the arr_case_id if the sum is less than 200000 units of vitamin A
								array_push($arr_case_id,$mc_id,$patient_edc);
							endif;
						endif;

						break;

					case '6':			//iron with folic acid intake
						$q_mc = mysql_query("SELECT mc_id, patient_edc FROM m_patient_mc WHERE patient_id='$patient_id' AND end_pregnancy_flag='N' AND delivery_date='0000-00-00' AND patient_edc >= NOW()") or die("Cannot query 596 ".mysql_error());


						if(mysql_num_rows($q_mc)!=0):
							list($mc_id,$patient_edc) = mysql_fetch_array($q_mc);
							$q_iron = mysql_query("SELECT SUM(service_qty) as sum_iron FROM m_consult_mc_services WHERE mc_id='$mc_id' AND service_id='IRON'") or die("Cannot query 633 ".mysql_error());
							
							list($sum_iron) = mysql_fetch_array($q_iron);
							
							if($sum_iron==0): //push the mc_id to the arr_case_id if no ironintake
								array_push($arr_case_id,$mc_id,$patient_edc);
							endif;
						endif;

						break;

					case '40':   //post trimester alert. patient didn't attended any prenatal visits
						$q_mc = mysql_query("SELECT mc_id,trimester1_date,trimester2_date,trimester3_date FROM m_patient_mc WHERE patient_id='$patient_id' AND end_pregnancy_flag='N' AND delivery_date='0000-00-00' AND patient_edc >= NOW()") or die("Cannot query 510 ".mysql_error());


						if(mysql_num_rows($q_mc)!=0):
							list($mc_id,$tri1,$tri2,$tri3,) = mysql_fetch_array($q_mc);
							$trimester = $this->get_trimester($mc_id,date('Y-m-d'));

							$tri_date = ($trimester=='2')?($tri1):(($trimester=='3')?$tri2:'');
							
							if($tri_date!=''):

								$q_prenatal = mysql_query("SELECT date_until FROM m_lib_alert_type WHERE alert_indicator_id=1") or die("Cannot query 975: ".mysql_error());

								if(mysql_num_rows($q_prenatal)!=0):
									list($date_until) = mysql_fetch_array($q_prenatal);
									if($date_until==$this->get_date_diff_days($tri_date,date('Y-m-d'))):
									
									//echo $trimester.'/'.$tri1.'/'.$tri2.'/'.$tri3.'/'.$tri_date.'/'.$this->get_date_diff_days($tri_date,date('Y-m-d')).'<br>';
									array_push($arr_case_id,$mc_id,date('Y-m-d'));
									endif;
								else:
									
								endif;
								
							endif;
						else:
							
						endif;

						
	
					default:

						break;

				} //end switch for case id's
				
				if(!empty($arr_case_id)):	
					array_push($arr_indicator,array($indicator_id=>$arr_case_id));
				endif;

			} //end while for indicators
			
			if(!empty($arr_indicator)):
				array_push($arr_px,array($patient_id=>$arr_indicator)); 
				array_push($arr_fam,$arr_px);
			endif;
				
		} //end foreach for patient id's

		return $arr_fam;		
	} //end function

	function fp_alarms(){
		
		if(func_num_args()>0):
			$arr = func_get_args();
			$family_id = $arr[0];
			$members = $arr[1];
			$program_id = $arr[2];
		endif;

		$arr_px = array(); //will contain patient id of family_members with any of the cases under indicators
		$arr_fam = array();

		/*the function will accept the family id and family_members
		1).navigate through the mc tables using the patient id of the family. each indicator has its own SQL.
		2). execute on SQL for the indicator, 
		3). pushed the patient_id, indicator id and the consult id to an array back to the calling function (get_indicator_instance)
		4). retrun value is an array of format family_id=>array(patient_id1=>array(indicator_id1=>array(consult_id1,consult_id2,...consult_id[n]),indicator_id2=>array(consult_id1,consult_id2,...,consult_id[n])),patient_id2...);
		*/

		foreach($members as $key=>$patient_id){
			$arr_px = array();	
			$arr_indicator = array();   //this will contain indicator_id and array of consult_id

			$q_fp_indicators = mysql_query("SELECT alert_indicator_id,sub_indicator FROM m_lib_alert_indicators WHERE main_indicator='$program_id' ORDER by sub_indicator ASC") or die("Cannot query 475: ".mysql_error());

			//$arr_case_id = array(); //this will contain the consult_id and enrollment id's

			while(list($indicator_id,$sub_indicator) = mysql_fetch_array($q_fp_indicators)){

				$arr_case_id = array(); //this will contain the consult_id and enrollment id's		
				
				$arr_definition = $this->get_alert_definition($indicator_id); //composed of defition id, days before and after. 
				$alert_id = $arr_definition[0];
				$days_before = $arr_definition[1];
				$days_after = $arr_definition[2];
				$date_today = date('Y-m-d');

				switch($indicator_id){
					case '22': 			//pill intake reminder
						$q_fp = $this->check_active_user($patient_id,'PILLS');

						if(mysql_num_rows($q_fp)!=0): 
							list($fp_px_id,$date_registered) = mysql_fetch_array($q_fp);

							//echo $patient_id.'/'.$date_registered.'/'.$days_before.'/'.$fp_px_id."<br>";
							
							$arr_fp_details = $this->get_fp_pre_reminder($date_today,$fp_px_id,$patient_id,$days_before,'PILLS');							

							$fp_service_id = $arr_fp_details[0];
							$fp_next_service_date = $arr_fp_details[1];					
							
							if($fp_service_id!=0):
								array_push($arr_case_id,$fp_service_id,$fp_next_service_date);
							endif;
						endif;
				
						break;

					case '23':			//condom re-supply reminder
						$q_fp = $this->check_active_user($patient_id,'CONDOM');

						if(mysql_num_rows($q_fp)!=0):
							list($fp_px_id,$date_registered) = mysql_fetch_array($q_fp);
					
							$arr_fp_details = $this->get_fp_pre_reminder($date_today,$fp_px_id,$patient_id,$days_before,'CONDOM');

							
							$fp_service_id = $arr_fp_details[0];
							$fp_next_service_date = $arr_fp_details[1];
							
							if($fp_service_id!=0):
								array_push($arr_case_id,$fp_service_id,$fp_next_service_date);
							endif;
						endif;
							
						break;

					case '24':			//IUD follow-up

						$q_fp = $this->check_active_user($patient_id,'IUD');

						if(mysql_num_rows($q_fp)!=0):
							list($fp_px_id,$date_registered) = mysql_fetch_array($q_fp);
							
							$arr_fp_details = $this->get_fp_pre_reminder($date_today,$fp_px_id,$patient_id,$days_before,'IUD');

							$fp_service_id = $arr_fp_details[0]; 
							$fp_next_service_date = $arr_fp_details[1];					

							if($fp_service_id!=0):
								array_push($arr_case_id,$fp_service_id,$fp_next_service_date);
							endif;

						endif;

						break;

					case '25':		//injectables follow-up reminder
						$q_fp = $this->check_active_user($patient_id,'DMPA');

						if(mysql_num_rows($q_fp)!=0):
							list($fp_px_id,$date_registered) = mysql_fetch_array($q_fp);

							$arr_fp_details = $this->get_fp_pre_reminder($date_today,$fp_px_id,$patient_id,$days_before,'DMPA');

							$fp_service_id = $arr_fp_details[0];
							$fp_next_service_date = $arr_fp_details[1];
							
							if($fp_service_id!=0):
								array_push($arr_case_id,$fp_service_id,$fp_next_service_date);
							endif;
						endif;
						
						break;

					case '26':		//pills drop-out alert
						$q_fp = $this->check_active_user($patient_id,'PILLS'); 
						if(mysql_num_rows($q_fp)!=0): 
							list($fp_px_id,$date_registered) = mysql_fetch_array($q_fp);

							$arr_fp_details = $this->get_post_reminder($fp_px_id,$date_registered,$patient_id,'PILLS');

							$fp_service_id = $arr_fp_details[0];
							$fp_next_service_date = $arr_fp_details[1];					

							if($fp_service_id!=0):
								array_push($arr_case_id,$fp_service_id,$fp_next_service_date);
							endif;

						endif;

						break;
													
					case '27':		//condom dropout alert
						$q_fp = $this->check_active_user($patient_id,'CONDOM');

						if(mysql_num_rows($q_fp)!=0):
							list($fp_px_id,$date_registered) = mysql_fetch_array($q_fp);
							
							
							$fp_service_id = $this->get_post_reminder($fp_px_id,$date_registered,$patient_id,'CONDOM');
							
							if($fp_service_id!=0):
								array_push($arr_case_id,$fp_service_id);
							endif;
						endif;

						break;
					
					case '28':		//IUD dropout alert
						$q_fp = $this->check_active_user($patient_id,'IUD');
						
						if(mysql_num_rows($q_fp)!=0):

							list($fp_px_id,$date_registered) = mysql_fetch_array($q_fp);
							
							$fp_service_id = $this->get_post_reminder($fp_px_id,$date_registered,$patient_id,'IUD');
							
							if($fp_service_id!=0):
								array_push($arr_case_id,$fp_service_id);
							endif;

						endif;


						break;

					case '29':		//DMPA/injectables dropout alert
						$q_fp = $this->check_active_user($patient_id,'DMPA');
						
						if(mysql_num_rows($q_fp)!=0):
							list($fp_px_id,$date_registered) = mysql_fetch_array($q_fp);

							$arr_fp_details = $this->get_post_reminder($fp_px_id,$date_registered,$patient_id,'DMPA');
							
							$fp_service_id = $arr_fp_details[0];
							$fp_next_service_date = $arr_fp_details[1];

							if($fp_service_id!=0):								
								array_push($arr_case_id,$fp_service_id,$fp_next_service_date);
							endif;

						endif;

						break;


					case '30':		//female sterilization dropout
						$q_fp = $this->check_active_user($patient_id,'FSTR/BTL');

						if(mysql_num_rows($q_fp)!=0):
							list($fp_px_id,$date_registered) = mysql_fetch_array($q_fp);
							
							$px_age = $this->get_patient_age($patient_id);
							
							if($px_age >= 50):	//candidate for dropout in BTL is px_age>=50
								array_push($arr_case_id,$fp_px_id);
							endif;
						endif;
						

						break;
					default:

						break;

				} //end switch for case id's
				
				if(!empty($arr_case_id)):
					array_push($arr_indicator,array($indicator_id=>$arr_case_id));
				endif;

			} //end while for indicators
			
			if(!empty($arr_indicator)):
				
				array_push($arr_px,array($patient_id=>$arr_indicator)); 
				array_push($arr_fam,$arr_px);
			endif;
				
		} //end foreach for patient id's
		
		return $arr_fam;				
	}

	function epi_alarms(){
		if(func_num_args()>0):
			$arr = func_get_args();
			$family_id = $arr[0];
			$members = $arr[1];
			$program_id = $arr[2];
		endif;

		$arr_px = array(); //will contain patient id of family_members with any of the cases under indicators
		$arr_fam = array();

		foreach($members as $key=>$patient_id){ 
			$arr_px = array();	
			$arr_indicator = array();   //this will contain indicator_id and array of consult_id

			$q_epi_indicators = mysql_query("SELECT alert_indicator_id,sub_indicator FROM m_lib_alert_indicators WHERE main_indicator='$program_id' ORDER by sub_indicator ASC") or die("Cannot query 475: ".mysql_error());

			$q_epi = $this->check_ccdev_enrollment($patient_id);
			
			if(mysql_num_rows($q_epi)!=0):
			
				list($ccdev_id,$dob) = mysql_fetch_array($q_epi);
				//echo $ccdev_id.' '.$patient_id.' '.$dob.'<br>';

			while(list($indicator_id,$sub_indicator) = mysql_fetch_array($q_epi_indicators)){
				$arr_case_id = array(); //this will contain the consult_id and enrollment id's		
				
				$arr_definition = $this->get_alert_definition($indicator_id); //composed of defition id, days before and after. 
				$alert_id = $arr_definition[0];
				$days_before = $arr_definition[1];
				$days_after = $arr_definition[2];
				$date_today = date('Y-m-d');


				switch($indicator_id){

					case '7':		//BCG immunization
						$eligibility = $this->check_vaccine_eligibility($patient_id,$dob,'BCG');
						$buffer_day = $this->get_vaccine_min_age_eligibility('BCG');
						break;

					case '8':		//DPT1 immunization
						$eligibility = $this->check_vaccine_eligibility($patient_id,$dob,'DPT1');
						$buffer_day = $this->get_vaccine_min_age_eligibility('DPT1');
						break;

					case '9':		//DPT2 immunization
						$eligibility = $this->check_vaccine_eligibility($patient_id,$dob,'DPT2');
						$buffer_day = $this->get_vaccine_min_age_eligibility('DPT2',$patient_id,$dob);
						break;

					case '10':		//DPT3 immunization
						$eligibility = $this->check_vaccine_eligibility($patient_id,$dob,'DPT3');
						$buffer_day = $this->get_vaccine_min_age_eligibility('DPT3',$patient_id,$dob);
						break;
					case '11':
						$eligibility = $this->check_vaccine_eligibility($patient_id,$dob,'OPV1');
						$buffer_day = $this->get_vaccine_min_age_eligibility('OPV1');
						break;
					case '12':
						$eligibility = $this->check_vaccine_eligibility($patient_id,$dob,'OPV2');
						$buffer_day = $this->get_vaccine_min_age_eligibility('OPV2',$patient_id,$dob);
						break;
					case '13':
						$eligibility = $this->check_vaccine_eligibility($patient_id,$dob,'OPV3');
						$buffer_day = $this->get_vaccine_min_age_eligibility('OPV3',$patient_id,$dob);
						break;
					case '14':
						$eligibility = $this->check_vaccine_eligibility($patient_id,$dob,'HEPB1');
						$buffer_day = $this->get_vaccine_min_age_eligibility('HEPB1');
						break;
					case '15':
						$eligibility = $this->check_vaccine_eligibility($patient_id,$dob,'HEPB2');
						$buffer_day = $this->get_vaccine_min_age_eligibility('HEPB2',$patient_id,$dob);
						break;
					case '16':
						$eligibility = $this->check_vaccine_eligibility($patient_id,$dob,'HEPB3');
						$buffer_day = $this->get_vaccine_min_age_eligibility('HEPB3',$patient_id,$dob);
						break;
					case '17':
						$eligibility = $this->check_vaccine_eligibility($patient_id,$dob,'MSL');
						$buffer_day = $this->get_vaccine_min_age_eligibility('MSL');
						break;
					case '18':		//FIC
						//$eligibility = $this->check_vaccine_eligibility($patient_id,$dob,'MSL');
						$eligibility = ((eregi('FIC',ccdev::determine_vacc_status($patient_id))?false:true) && ($this->get_patient_age($patient_id)>0));
						$buffer_day = '365';

						break;

					case '19':		//CIC
						if((eregi('CIC',ccdev::determine_vacc_status($patient_id))==true) && (eregi('FIC',ccdev::determine_vacc_status($patient_id))==false)):
							$buffer_day = '365';
							$eligibility = true;
						else:
							$eligibility = false;
						endif;

						break;
					case '41':		//PENTA1
						$eligibility = $this->check_vaccine_eligibility($patient_id,$dob,'PENTA1');
						$buffer_day = $this->get_vaccine_min_age_eligibility('PENTA1');
						break;
					case '42':		//PENTA2
						$eligibility = $this->check_vaccine_eligibility($patient_id,$dob,'PENTA2');
						$buffer_day = $this->get_vaccine_min_age_eligibility('PENTA2',$patient_id,$dob);		break;
					case '43':		//PENTA3
						$eligibility = $this->check_vaccine_eligibility($patient_id,$dob,'PENTA3');
						$buffer_day = $this->get_vaccine_min_age_eligibility('PENTA3',$patient_id,$dob);

						break;

					case '44':	//MMR
						$eligibility = $this->check_vaccine_eligibility($patient_id,$dob,'MMR');
						$buffer_day = $this->get_vaccine_min_age_eligibility('MMR');
						break;

					case '45':	//ROTA 1
						$eligibility = $this->check_vaccine_eligibility($patient_id,$dob,'ROTA');
						$buffer_day = $this->get_vaccine_min_age_eligibility('ROTA');
						break;
					case '46':	//ROTA 2
						$eligibility = $this->check_vaccine_eligibility($patient_id,$dob,'ROTA2');
						$buffer_day = $this->get_vaccine_min_age_eligibility('ROTA2',$patient_id,$dob);
						break;
					default:
						break;
				}	//end switch
				if($eligibility==true):
					$base_date = date('Y/m/d',(strtotime(date("Y-m-d", strtotime($dob)) . " +".$buffer_day." day")));
					array_push($arr_case_id,$ccdev_id,$base_date); 
				endif;

				if(!empty($arr_case_id)):
					array_push($arr_indicator,array($indicator_id=>$arr_case_id));
				endif;

			}	//end while

				if(!empty($arr_indicator)):
					array_push($arr_px,array($patient_id=>$arr_indicator)); 
					array_push($arr_fam,$arr_px);
				endif;
			endif;

		} //end for each

		return $arr_fam;
	}


	function philhealth_alarms(){
		/*if(func_num_args()>0):
			$arr = func_get_args();
			$family_id = $arr[0];
			$members = $arr[1];
			$program_id = $arr[2];
		endif;
	
		$arr_px = array(); //will contain patient id of family_members with any of the cases under indicators
		$arr_fam = array();

		foreach($members as $key=>$patient_id){	
			$arr_px = array();	
			$arr_indicator = array();   //this will contain indicator_id and array of consult_id

			$q_dob = mysql_query("SELECT patient_dob, round((TO_DAYS(b.consult_date) - TO_DAYS(a.patient_dob))/365 ,2) FROM m_patient WHERE patient_id='$patient_id'") or die("Cannot query: 1517");
			list($dob,$px_age) = mysql_fetch_array($q_dob);
			
	
			$q_philhealth_indicators = mysql_query("SELECT alert_indicator_id,sub_indicator FROM m_lib_alert_indicators WHERE main_indicator='$program_id' ORDER by sub_indicator ASC") or die("Cannot query 1448: ".mysql_error());

			while(list($indicator_id,$sub_indicator)=mysql_fetch_array($q_philhealth_indicators)){
				$arr_case_id = array();

				$arr_definition = $this->get_alert_definition($indicator_id);
				$alert_id = $arr_definition[0];
				$days_before = $arr_definition[1];
				$days_after = $arr_definition[2];
				$date_today = date('Y-m-d');

				switch($indicator_id){
				
					case '30':	//membership eligibility alert (for those who are turning 21 years old
						//if(($px_age=='21)' && (strtotime($date_today)==strtotime($dob))):
							
						break;					
				
					default:

							break;
				
				}


			
			}			
		}
		*/
	}

	function get_family_members($family_id){
		$arr_members = array();

		$q_members = mysql_query("SELECT patient_id FROM m_family_members WHERE family_id='$family_id'") or die("Cannot query 498 ".mysql_error());

		while(list($pxid)=mysql_fetch_array($q_members)){
			array_push($arr_members,$pxid);
		}
		return $arr_members;
	}


	function get_alert_definition($indicator_id){
		$arr_alert = array();
		$q_indicator = mysql_query("SELECT alert_id,date_pre,date_until FROM m_lib_alert_type WHERE alert_indicator_id='$indicator_id'") or die("Cannot query 521 ".mysql_error());

		if(mysql_num_rows($q_indicator)!=0):
			list($alert_id,$before,$until) = mysql_fetch_array($q_indicator);
			array_push($arr_alert,$alert_id,$before,$until);
		else:
			array_push($arr_alert,0,7,0); //0 alert_id indicates that no alert definition. by default, alerts will be shown 7 days before. zero means that alert will be there until the record has been updated
		endif;

		return $arr_alert;
	}

	function compare_date($date_for_test,$date_basis){  //returns true if the first date is after the second date
		if(strtotime($date_for_test) > strtotime($date_basis)):
			return true;
		else:
			return false;
		endif;

	}

	function check_active_user($patient_id,$method_id){		//function will check if patient_id is an active FP client of $method_id
		$q_fp = mysql_query("SELECT fp_px_id,date_registered FROM m_patient_fp_method WHERE patient_id='$patient_id' AND method_id='$method_id' AND drop_out='N' ORDER by date_registered DESC") or die("Cannot query 710 ".mysql_error());

		return $q_fp;
	}

	function get_fp_pre_reminder($date_today,$fp_px_id,$patient_id,$days_before,$method_id){   //performs a query by getting the reference date, compares it with $days_before and returns true if the reference date is within the range of the 0 and $days_before.
	
		$arr_fp_details = array();

		$q_next_service_date = mysql_query("SELECT date_service,next_service_date FROM m_patient_fp_method_service WHERE fp_px_id='$fp_px_id' AND patient_id='$patient_id' ORDER by date_service DESC") or die("Cannot query 877 ".mysql_error());

		if(mysql_num_rows($q_next_service_date)!=0):

			list($service_date, $next_service_date) = mysql_fetch_array($q_next_service_date);

			if($next_service_date!='0000-00-00'): 
				$q_fp_method = mysql_query("SELECT fp_service_id,(to_days(next_service_date)-to_days('$date_today')) as sum_date FROM m_patient_fp_method_service WHERE fp_px_id='$fp_px_id' AND patient_id='$patient_id' AND (to_days(next_service_date)-to_days('$date_today')) BETWEEN 0 AND '$days_before' ORDER by date_service DESC") or die("Cannot query 714 ".mysql_error());	
				
				$proj_next_service_date = $next_service_date;

			else:   //create a projected service date once the 

				$proj_next_service_date = $this->get_proj_service_date($service_date,$method_id,$fp_px_id,$patient_id);
				//echo $method_id.' '.$proj_next_service_date.' '.$days_before.'<br>';

				$q_fp_method = mysql_query("SELECT fp_service_id,(to_days('$proj_next_service_date')-to_days('$date_today')) as sum_date FROM m_patient_fp_method_service WHERE fp_px_id='$fp_px_id' AND patient_id='$patient_id' AND (to_days('$proj_next_service_date')-to_days('$date_today')) BETWEEN 0 AND '$days_before' ORDER by date_service DESC") or die("Cannot query 714 ".mysql_error());
			endif;

			if(mysql_num_rows($q_fp_method)!=0):  
				list($fp_service_id,$sum_date) = mysql_fetch_array($q_fp_method); 

				array_push($arr_fp_details,$fp_service_id,$proj_next_service_date);

				return $arr_fp_details;
			else:
				return 0;
			endif;

		else:
			return 0;			
		endif;
	}

	function get_post_reminder(){
		$arr_fp_details = array();

		if(func_num_args()>0):
			$arr = func_get_args();
			$fp_px_id = $arr[0];
			$date_registered = $arr[1];
			$patient_id = $arr[2];
			$method_id = $arr[3];
		endif;
		

		$q_fp_service = mysql_query("SELECT fp_service_id,date_service,next_service_date FROM m_patient_fp_method_service WHERE fp_px_id='$fp_px_id' AND patient_id='$patient_id' ORDER by date_service ASC") or die("Cannot query 951 ".mysql_error());

		if(mysql_num_rows($q_fp_service)!=0):
			list($fp_service_id,$date_service,$next_service_date) = mysql_fetch_array($q_fp_service);

			if($next_service_date!='0000-00-00'):  
				if(($this->compare_date(date('Y-m-d'),$next_service_date))):

					$next_service_date = date('Y-m-d',strtotime("+1 days",strtotime($next_service_date))); //the post reminder will always be set one day after the date of re-visit for FP service

					array_push($arr_fp_details,$fp_service_id,$next_service_date);
					return $arr_fp_details;
				else:
					return 0;
				endif;
			else:	//the next service date was not set
				$proj_next = $this->get_proj_service_date($date_service,$method_id,$fp_px_id,$patient_id);

				if($this->compare_date(date('Y-m-d'),$proj_next)):

					$proj_next = date('Y-m-d',strtotime("+1 days",strtotime($proj_next))); //the post reminder will always be set one day after the date of re-visit for FP service

					array_push($arr_fp_details,$fp_service_id,$proj_next);
					return $arr_fp_details;
				else:
					return 0;
				endif;
			endif;

		else: return 0; //this will going to return 0
		endif;

	}

	function get_proj_service_date(){
		//set the buffer (unit: days) from the service_date for the method_id
		//returns the end_date after the buffer is added to the service_date
		if(func_num_args()>0):
			$arr = func_get_args();
			$service_date = $arr[0];
			$method_id = $arr[1];
			$fp_px_id = $arr[2];
			$patient_id = $arr[3];
		endif;

		switch($method_id){
			case 'PILLS':
				$buffer = 28;
				break;
			case 'DMPA':
				$buffer = 90;
				break;

			case 'CONDOM':
				$buffer = $this->get_condom_span_days($fp_px_id,$patient_id);
				break;

			case 'IUD':
				$buffer = $this->get_iud_span_days($fp_px_id,$patient_id);
				break;
			default:
				$buffer = 30;
				break;
		}


		return $this->compute_buffer_date($service_date,$buffer);

	}

	function get_iud_span_days($fp_px_id,$patient_id){
		//function will generate number of buffer days before the next visit based on the number of IUD realignments
		//1st: 30, 2nd: 180, >3rd: 365

		$q_iud_visits = mysql_query("SELECT COUNT(fp_service_id) FROM m_patient_fp_method_service WHERE patient_id='$patient_id' AND fp_px_id='$fp_px_id'") or die("Cannot query 935 ".mysql_error());

		if(mysql_num_rows($q_iud_visits)!=0):
			list($visit_count) = mysql_fetch_array($q_iud_visits);
			if($visit_count==1):
				$buffer = 30;
			elseif($visit_count==2):
				$buffer = 180; 
			else:
				$buffer = 365;
			endif;
		else:
			$buffer = 30;		//by default, set 30 days from the date of the latest service date
		endif;

		return $buffer;
	}

	function get_condom_span_days($fp_px_id,$patient_id){
		$q_condom = mysql_query("SELECT date_service,quantity FROM m_patient_fp_method_service WHERE patient_id='$patient_id' and fp_px_id='$fp_px_id' ORDER by date_service DESC") or die("Cannot query 1055 ".mysql_error());

		if(mysql_num_rows($q_condom)!=0):
			list($date_service,$quantity) = mysql_fetch_array($q_condom);
			$buffer = $quantity * 15;
			
		else:
			$buffer = 30;
		endif;

		return $buffer;
	}

	

	function compute_buffer_date($date_to_adjust,$buffer){

		list($y,$m,$d) = explode('-',$date_to_adjust);

		$d = mktime(0,0,0,$m,$d,$y);
		$end_date = date('Y-m-d',strtotime('+'.$buffer.'days',$d));
		
		return $end_date;
	}


	function get_patient_age($patient_id){
		$q_age = mysql_query("SELECT ((TO_DAYS(NOW()) - TO_DAYS(patient_dob))/365) as 'px_age' FROM m_patient WHERE patient_id='$patient_id'") or die("Cannot query: 1113");

		list($px_age) = mysql_fetch_array($q_age);
		
		return $px_age;
		
	}

	function check_ccdev_enrollment($patient_id){
		$q_ccdev = mysql_query("SELECT a.ccdev_id,b.patient_dob b FROM m_patient_ccdev a, m_patient b WHERE a.patient_id='$patient_id' AND a.patient_id=b.patient_id") or die("Cannot query 1123 ".mysql_error());

		return $q_ccdev;
	}



	function check_vaccine_eligibility(){
		/*	to check for the eligbility of patient_id:
			1. check if the patient has achieved the minimum required months and within the maximum amount of time (5 years old?)
			2. check if the patient hasn't received any immunization
			3. find out the target date for the next immunization for the patient then display 
		*/
	
		if(func_num_args()>0):
			$arr = func_get_args();
			$patient_id = $arr[0];
			$dob = $arr[1];
			$vaccine = $arr[2];
		endif;


		//query will determine 3 things: 1. determine if the patient is enrolled in ccdev, 2. determine if the patient has a vaccination record, 3. determine if the patient hasn't been vaccinated with $vaccine yet.

		$q_ccdev = mysql_query("SELECT a.ccdev_id FROM m_patient_ccdev a WHERE a.patient_id='$patient_id' ORDER by a.ccdev_timestamp DESC") or die("Cannot query 1149 ".mysql_error()); 
		$arr_vacc_no_seq = array('BCG','HEPB1','MSL','DPT1','OPV1','PENTA1','MMR','ROTA'); //1st dosages OR vaccine has no series
		$arr_vacc_seq = array('DPT2','OPV2','HEPB2','PENTA2','DPT3','OPV3','HEPB3','PENTA3','ROTA2');

		if(mysql_num_rows($q_ccdev)!=0):
			list($ccdev_id) = mysql_fetch_array($q_ccdev);
			
			
			$q_vaccine = mysql_query("SELECT consult_id FROM m_consult_ccdev_vaccine WHERE ccdev_id='$ccdev_id' AND patient_id='$patient_id' AND vaccine_id='$vaccine'") or die("Cannot query 1158 ".mysql_error());
			
			if(mysql_num_rows($q_vaccine)==0): 
				if($this->get_vaccine_min_age_eligibility($vaccine,$patient_id,$dob)<=round(($this->get_patient_age($patient_id)*12*30.42),0)): //checks if the client is within the minimum age to have the vaccination

					if(in_array($vaccine,$arr_vacc_no_seq)): 
						return true;
					elseif(in_array($vaccine,$arr_vacc_seq)): //check if the prerequisite vaccine was given to the client 
						//this would cover only 'DPT2','OPV2','HEPB2','DPT3','OPV3','HEPB3'
						if($this->check_vaccine_dependency($vaccine,$patient_id)): 
							return true;
						else:
							return false;
						endif;
					else:
						
					endif;
				else:
					return false;
				endif;
			else:
				return false;
			endif;

		else:
			return false;
		endif;
	}

	function get_vaccine_min_age_eligibility(){
		//function returns the minimum number of days

		if(func_num_args()>0):
			$arr = func_get_args();
			$vaccine = $arr[0];
			$patient_id = $arr[1];
			$dob = $arr[2];
		endif;

		if(array_key_exists($vaccine,$this->arr_dep)): 
			$prereq_vacc = $this->arr_dep[$vaccine][0];
			$vacc_allowance = $this->arr_dep[$vaccine][1];
		endif;



		$get_prereq_vacc = mysql_query("SELECT actual_vaccine_date,(TO_DAYS(actual_vaccine_date) - TO_DAYS('$dob')) ,(TO_DAYS(NOW()) - TO_DAYS(actual_vaccine_date)) FROM m_consult_ccdev_vaccine WHERE vaccine_id='$prereq_vacc' AND (TO_DAYS(NOW()) - TO_DAYS(actual_vaccine_date)) >= '$vacc_allowance' AND patient_id='$patient_id'") or die("Cannot query 1656: ".mysql_error());


		if(mysql_num_rows($get_prereq_vacc)!=0): 		
			//(prereq_vacc_date minus DOB) days + allowance 
			list($prereq_vacc_date,$vacc_dob_diff,$now) = mysql_fetch_array($get_prereq_vacc);			
			$vacc_elig_from_dob = $vacc_dob_diff + $vacc_allowance; //from DOB to $vacc_elig_from_dob		
		else:
			$vacc_elig_from_dob = '';
		endif;
	

		switch($vaccine){
	
			case 'BCG':
				$min_age = 0;		//newborn (0 months old) and above
				break;

			case 'DPT1':
				//$min_age = 1.5;		//6 weeks
				$min_age = 42;
				break;

			case 'DPT2':
				//$min_age = 70;		//10 weeks
				$min_age = ($vacc_elig_from_dob=='')?70:$vacc_elig_from_dob;
				break;

			case 'DPT3':
				//$min_age = 98;		//14 weeks
				$min_age = ($vacc_elig_from_dob=='')?98:$vacc_elig_from_dob;
				break;

			case 'OPV1':
				$min_age = 42;		//6 weeks
				break;

			case 'OPV2':
				//$min_age = 70;		//10 weeks
				$min_age = ($vacc_elig_from_dob=='')?70:$vacc_elig_from_dob;
				break;

			case 'OPV3':
				//$min_age = 98;		//14 weeks
				$min_age = ($vacc_elig_from_dob=='')?98:$vacc_elig_from_dob;
				break;

			case 'HEPB1':			//at birth
				$min_age = 0;
				break;

			case 'HEPB2':
				//$min_age = 42; 		//6 weeks
				$min_age = ($vacc_elig_from_dob=='')?42:$vacc_elig_from_dob;
				break;

			case 'HEPB3':
				//$min_age = 98; 		//14 weeks
				$min_age = ($vacc_elig_from_dob=='')?98:$vacc_elig_from_dob;
				break;

			case 'MSL':
				$min_age = 274; 		//9 months
				break;

			case 'PENTA1':
				$min_age = 42;
				break;
			case 'PENTA2':
				$min_age = ($vacc_elig_from_dob=='')?70:$vacc_elig_from_dob;
				break;
			case 'PENTA3': 
				$min_age = ($vacc_elig_from_dob=='')?98:$vacc_elig_from_dob;
				break;
			case 'MMR':
				$min_age = 365;
				break;
			case 'ROTA':
				$min_age = 42;
				break;
			case 'ROTA2': //rota 1 should be recorded. the system will count 28 days from rota vaccine date
				$min_age = $vacc_elig_from_dob; 
				break;
			default:
				
				break; 
		}
		return $min_age; 
	}	


	function check_sms_alert($date_passed){
		$alert = new alert;
		$arr_alert = array();
		$include_wkend = 0;

		if(isset($date_passed)):
			$today = $date_passed;
		else:
			$today = date('Y-m-d');

			if(date('l',strtotime($today))=='Friday'){
				$include_wkend = 1;
			}
		endif;

		//echo $today;
		//echo date('l',strtotime($today));
		for($i=1;$i<=2;$i++){
			$date = strtotime("+$i day", strtotime($today));
			//echo date("Y-m-d", $date)."<br>";
		}

		
		$q_sms_alert = mysql_query("SELECT sms_id FROM m_lib_sms_alert WHERE alert_date='$today'") or die("Cannot query 732: ".mysql_error());

		$q_fam_id = mysql_query("SELECT DISTINCT a.family_id FROM m_family_address a, m_family_members b, m_lib_barangay c WHERE a.family_id=b.family_id AND a.barangay_id=c.barangay_id ORDER by c.barangay_name ASC") or die("Cannot query 1576: ".mysql_error());



		while($r_fam = mysql_fetch_array($q_fam_id)){
			if($alert->check_px_enrolled_sms($r_fam['family_id'])):
				array_push($arr_alert,$alert->determine_alert_hh($r_fam['family_id']));
			endif;
			//echo $r_fam[family_id].' ';
			//print_r($arr_alert);
			//echo "<br><br><br>"; 
		}

		if(mysql_num_rows($q_sms_alert)==0):
			foreach($arr_alert as $key1=>$value1){	
				foreach($value1 as $key2=>$value2){
					foreach($value2 as $key3=>$value3){
						foreach($value3 as $key4=>$value4){
							foreach($value4 as $key5=>$value5){
								foreach($value5 as $key6=>$value6){ //key6 is the patient_id
										$q_name = mysql_query("SELECT patient_lastname,patient_firstname FROM m_patient WHERE patient_id='$key6'") or die("Cannot query 1545: ".mysql_error());
										list($lname,$fname) = mysql_fetch_array($q_name);
										$ngalan = $lname.', '.$fname;
									foreach($value6 as $key7=>$alert_details){
										//echo $key7.'<br>';
										//print_r($value6).'<br><br>';
										foreach($alert_details as $alert_id=>$arr_alert){ //arr_alert[0] is the program_id, arr_alert[1] is the base date
											if($alert->determine_px_enrollment($key6,$alert_id)): 
												$arr_alert_msg = $alert->check_alert_msg($alert_id,$arr_alert[1],$key6);
												if(count($arr_alert_msg)!=0): 
													//print_r($arr_alert_msg);
													//$day_diff = $alert->get_date_diff_days(date('Y-m-d'),$arr_alert[1]);

													$day_diff = $alert->get_date_diff_days($today,$arr_alert[1]);

													//echo $arr_alert[1]; 
													//echo $key6.' '.$arr_alert[1].'  '.$day_diff.'<br>';

													$mensahe = $alert->get_message($day_diff,$arr_alert_msg,$ngalan,$arr_alert[1]); 
//echo $day_diff.'/'.$ngalan.'/'.$mensahe.'/'.$alert_id.'/'.$arr_alert[1].'<br>xxx';
													if($mensahe!=''):
														/* $key: patient_id, $arr_alert[0]: program_id, $arr_alert[1]: base date, $alert_id: id for alert, 'queue': sms_status, $mensahe: alert message
														*/

														$alert->queue_sms($key6,$arr_alert[0],$arr_alert[1],$alert_id,'queue',$mensahe);

														
													endif;
												endif;
											endif;
										}
									//$insert_sms_alert = mysql_query("INSERT INTO m_lib_sms_alert SET alert_date=NOW()") or die("Cannot query 735: ".mysql_error()); 
									}
								}
							}
						}
					}
				}
			}

		else:
			$arr_config = $alert->get_sms_config();
			if(count($arr_config)!=0):
				if($arr_config['sms_time_sched'] <= date('H:i')):
					$date_today = date('Y-m-d');
					
					$q_alert = mysql_query("SELECT sms_id,alert_date,sms_message,sms_number FROM m_lib_sms_alert WHERE alert_date='$date_today' AND sms_status!='sent'") or die("Cannot query 1644: ".mysql_error());
					
					if(mysql_num_rows($q_alert)!=0):
					while($r_alert = mysql_fetch_array($q_alert)){  
						if($alert->send_sms($arr_config['sms_url'],$arr_config['sms_port'],$r_alert['sms_number'],$r_alert['sms_message'])):
							$alert->update_sms_status($r_alert['sms_id'],'sent');
						else: 
							$alert->update_sms_status($r_alert['sms_id'],'not_sent');
						endif;
					}
					endif;
				endif;
			else:
				echo "<font>There is no SMS configuration!</font>";
			endif;
		endif;	

 
	}


	function determine_px_enrollment($pxid,$alert_id){
		$prog_id = $this->determine_program_id($alert_id);
		$q_enrollment = mysql_query("SELECT enroll_id FROM m_lib_sms_px_enroll WHERE patient_id='$pxid' AND program_id='$prog_id'") or die("Cannot query 1545: ".mysql_error());

		if(mysql_num_rows($q_enrollment)!=0):
			return true;
		else:
			return false;
		endif;
	}

	function determine_program_id($alert_id){
		$q_prog_id = mysql_query("SELECT main_indicator FROM m_lib_alert_indicators WHERE alert_indicator_id='$alert_id'") or die("CAnnot query 1547: ".mysql_error());

		list($program_id) = mysql_fetch_array($q_prog_id);
		return $program_id;
	}

	function check_alert_msg(){
		/* use this function to check if the alert message is: 1). activated, 2). present, 3). if it is, then return an array containint the alert message details to the foreach loop*/
		$arr_alert = array();

		if(func_num_args()>0):
			$arr = func_get_args();
			$alert_id = $arr[0];
			$base_date = $arr[1];
			$pxid = $arr[2];
		endif;

		$q_alert_prog_id = mysql_query("SELECT alert_id,date_pre,date_until,alert_message,alert_action,alert_actual_message FROM m_lib_alert_type WHERE alert_indicator_id='$alert_id' AND alert_flag_activate='Y'") or die("Cannot query 1590: ".mysql_error());	


		if(mysql_num_rows($q_alert_prog_id)!=0):
			$arr_alert = mysql_fetch_array($q_alert_prog_id);
		endif;

			return $arr_alert;
	}
	
	function determine_alert_hh(){
		//function determine_alert_hh
		if(func_num_args()>0):
			$arr = func_get_args();
			$fam_id = $arr[0];
		endif;

		$arr_alert = array();
		
		foreach($this->mods as $program_id=>$program_arr){ 
			$arr_prog = array();
			$arr_prog = $this->get_indicator_instance($program_id,$fam_id);	
			if(!empty($arr_prog)):
				array_push($arr_alert,$arr_prog);
			endif;
		} 
		/*echo $fam_id;
		print_r($arr_alert);
		echo "<br><br><br>";*/
		return $arr_alert;
	}

	function test_sms(){   //test if the formulated URL is a valid for sending SMS message
		//print_r($_POST);
		if(func_num_args()>0):
			$arr = func_get_args();
			$post = $arr[0];
		endif;

		$padded_str = str_replace(' ','%20',$post[txt_testmsg]);

		if(exec('nohup curl http://'.$post[txt_midserver].':'.$post[txt_port].'/send/sms/'.$post[txt_testnum].'/'.$padded_str)):
			echo "<font color='red'>Message/s sent!</font><br>";
			return true;
		else:
			echo "<font color='red'>Message/s not sent! Please check the SMS configuration.</font><br>";
			return false;
		
		endif;
	}

	function send_sms(){
		
		if(func_num_args()>0):
			$arr = func_get_args();
			$midserver = $arr[0];
			$port = $arr[1];
			$sms_number = $arr[2];
			$sms_message = $arr[3];
		endif;

		$padded_str = str_replace(' ','%20',$sms_message);
		
		if(exec('nohup curl http://'.$midserver.':'.$port.'/send/sms/'.$sms_number.'/'.$padded_str)):
		//if(exec('nohup curl http://192.168.1.109:8011/send/sms/09224978259/Total Consultations (08-06-2012): 0, Total Overall Consultations: 10,Total Patients (08-06-2012): 0,Total Overall Patients: 54,All-time Top Visiting Barangay: Ginerbra (8) ,Top Visiting Barangay  (08-06-2012): ,')):
			echo "<font color='red'>Message/s sent!</font><br>";
			return true;
		else:
			echo "<font color='red'>Message/s not sent! Please check the SMS configuration.</font><br>";
			return false;
		endif;

	}


	function get_sms_config(){
		$q_sms_config = mysql_query("SELECT sms_url, sms_port, date_format(sms_time,'%H:%i') sms_time_sched  FROM m_lib_sms_config") or die("Cannot query 1722: ".mysql_error());
		$arr_sms_config = mysql_fetch_array($q_sms_config);
		
		return $arr_sms_config;
	}

	function check_sms_field(){
		if(func_num_args()>0):
			$arr = func_get_args();
			$post = $arr[0];
		endif;
		//print_r($post);
		$str = '';

		if(empty($post["txt_midserver"]))
			$str .= '\n\n'.'- URL of the middle server'.'\n';
		if(empty($post["txt_port"]))
			$str .= '- Port number of the server'.'\n';
		if(empty($post["txt_contact"]))
			$str .= '- Contact information of the RHU'.'\n';
		if(empty($post["txt_testmsg"]))
			$str .= '- Test message'.'\n';
		if(empty($post["txt_testnum"]))
			$str .= '- Test number'.'\n';

		if($str==''):
			return true;
		else:
			echo "<script language='javascript'>";
			echo "window.alert('The following items should be supplied: $str')";
			echo "</script>";
			return false;
		endif;
	}

	function get_date_diff_days(){
		/* function returns difference in days*/

		if(func_num_args()>0){
			$arr = func_get_args();
			$sdate = $arr[0];     //base date
			$edate = $arr[1];     //date today
		}

		$diff_days = (strtotime($edate) - strtotime($sdate)) / (60 * 60 * 24);

		return $diff_days;
	}

	function get_message(){ print_r($arr_alert_msg);
		//function checks the number of days from the actual date then selects and return the appropriate message content based in the query. Function will pad the key variable $date in the message with the base date */
		if(func_num_args()>0){
			$arr = func_get_args();
			$day_diff = $arr[0];
			$arr_alert_msg = $arr[1];
			$pxname = $arr[2];
			$base_date = $arr[3];
		}

		//echo $pxname.$arr_alert_msg['date_pre'].$arr_alert_msg['alert_action'].$day_diff.'/'.$base_date.'<br>';

		if($day_diff==0):
			$str_msg = str_replace('$name',$pxname,$arr_alert_msg['alert_actual_message']);
			$str_msg = str_replace('$date',$base_date,$str_msg);
		elseif(($day_diff > 0) && ($day_diff==$arr_alert_msg['date_pre'])):
			$str_msg = str_replace('$name',$pxname,$arr_alert_msg['alert_message']);
			$str_msg = str_replace('$date',$base_date,$str_msg);
		elseif(($day_diff < 0) && (abs($date_diff)==$arr_alert_msg['date_until'])):
			$str_msg = str_replace('$name',$pxname,$arr_alert_msg['alert_action']);
			$str_msg = str_replace('$date',$base_date,$str_msg);
		else: //echo $day_diff.' nada'; 
			
		endif;
		
		$str_msg = str_replace('/','-',$str_msg);
		return $str_msg;
	}

	function queue_sms(){
		if(func_num_args()>0){
			$arr = func_get_args();
			$pxid = $arr[0];
			$prog_id = $arr[1];
			$base_date = $arr[2];
			$alert_id = $arr[3];
			$sms_status = $arr[4];
			$sms_message = $arr[5];

		/* $key6: patient_id, $arr_alert[0]: program_id, $arr_alert[1]: base date, $alert_id: id for alert, 'queue': sms_status, $mensahe: alert message
		$alert->queue_sms($key6,$arr_alert[0],$arr_alert[1],$alert_id,date('Y-m-d'),'queue',$mensahe); */
		}



		$q_px_cp  = mysql_query("SELECT patient_cellphone FROM m_patient WHERE patient_id='$pxid'") or die("Cannot query 520: ".mysql_error());
		list($cp) = mysql_fetch_array($q_px_cp);


		$get_brgy = mysql_query("SELECT a.barangay_id FROM m_family_address a, m_family_members b WHERE b.patient_id='$pxid' AND a.family_id=b.family_id") or die("Cannot query 1740: ".mysql_error());

		list($brgy_id) = mysql_fetch_array($get_brgy);

		$q_program = mysql_query("SELECT main_indicator,sub_indicator FROM m_lib_alert_indicators WHERE alert_indicator_id='$alert_id'") or die("Cannot query 1907 ".mysql_error());
		list($main_indicator,$sub_indicator) = mysql_fetch_array($q_program);

		$get_fac_id = mysql_query("SELECT facility_id FROM m_lib_health_facility_barangay WHERE barangay_id='$brgy_id'") or die("Cannot query 1910: ".mysql_error());
		list($facid) = mysql_fetch_array($get_fac_id);

		$alert_date = date('Y-m-d');

		$insert_sms_alert = mysql_query("INSERT INTO m_lib_sms_alert SET patient_id='$pxid',program_id='$prog_id',alert_id='$alert_id',alert_date='$alert_date',base_date='$base_date',sms_status='$sms_status',last_update=NOW(),barangay_id='$brgy_id',sms_number='$cp',recipient_type='px'") or die("Cannot query 1740: ".mysql_error());

		$sms_id = mysql_insert_id();

		$sms_code = $facid.'-'.$main_indicator.'-'.$sms_id;

		$get_outro = $this->get_outro_msg($brgy_id,$sms_code);
		$sms_message = $sms_message.$get_outro;
		
		$update_sms_alert = mysql_query("UPDATE m_lib_sms_alert SET sms_code='$sms_code',sms_message='$sms_message' WHERE sms_id='$sms_id'") or die("Cannot query 1920: ".mysql_error());
		
		$this->insert_sms_provider($brgy_id, $pxid,$prog_id,$alert_id,$alert_date,$base_date,$sms_status,$sms_code,$sms_message);
	}

	function update_sms_status(){
		if(func_num_args()>0){
			$arr = func_get_args();
			$sms_id = $arr[0];
			$status = $arr[1];
		}
		//echo $sms_id.' '.$status;
		$q_sms_status = mysql_query("UPDATE m_lib_sms_alert SET sms_status='$status' WHERE sms_id='$sms_id'") or die("Cannot query 1879: ".mysql_error());
	}


	function get_outro_msg($brgy_id,$sms_code){
		$q_bhs_midwife = mysql_query("SELECT a.user_lastname,a.user_firstname,b.bhs_name FROM game_user a, m_lib_bhs b,m_lib_bhs_barangay c WHERE b.bhs_id=c.bhs_id AND c.barangay_id='$brgy_id' AND a.user_id=b.user_id") or die("Cannot query 1943: ".mysql_error());
		
		list($lname,$fname,$bhs) = mysql_fetch_array($q_bhs_midwife);
		
		$get_sms_info = mysql_query("SELECT sms_contact_info FROM m_lib_sms_config") or die("Cannot query 1947: ".mysql_error());
		list($sms_info) = mysql_fetch_array($get_sms_info);

		$sms_info = str_replace('$bhs',$bhs,$sms_info);
		$sms_info = str_replace('$midwife',$fname.' '.$lname,$sms_info);
		$sms_info = str_replace('$msgcode',$sms_code,$sms_info);
		$sms_info = str_replace('$source',$_SESSION['datanode']['name'],$sms_info);

		return $sms_info;
	}

	function insert_sms_provider($brgy_id, $pxid,$prog_id,$alert_id,$alert_date,$base_date,$sms_status,$sms_code,$sms_message){

		$q_provider_info = mysql_query("SELECT a.user_lastname,a.user_id,a.user_cellular FROM game_user a,m_lib_bhs b,m_lib_bhs_barangay c WHERE b.bhs_id=c.bhs_id AND c.barangay_id='$brgy_id' AND a.user_id=b.user_id") or die("Cannot query 1984: ".mysql_error());
		

		if(mysql_num_rows($q_provider_info)!=0):

			while(list($user_lname,$user_id,$cp) = mysql_fetch_array($q_provider_info)){			
			
			//echo $user_lname.','.$user_id.','.$cp;

			if(!empty($cp)):
				$q_sms = mysql_query("INSERT INTO m_lib_sms_alert SET patient_id='$pxid',barangay_id='$brgy_id',program_id='$prog_id',alert_id='$alert_id',alert_date='$alert_date',base_date='$base_date',sms_status='$sms_status',sms_message='$sms_message',sms_code='$sms_code',last_update=NOW(),sms_number='$cp',recipient_type='midwife'") or die("Cannot query 1995: ".mysql_error());
			endif;

			}
		endif;
	}

	function check_weekend($date_to_check){
	//check if the date would fall in saturday or sunday.
		
		$dtimestamp = strtotime($date_to_check);
	
		$day = date("D",$dtimestamp);

		if(($day=='Sat') || ($day=='Sun')):
			return true;
		else:
			return false;
		endif;
	}


	function get_trimester() {
		//
		// get_trimester()
		// what trimester is patient in?
		// returns trimester integer
		//
        	if (func_num_args()>0) {
            		$arg_list = func_get_args();
            		$mc_id = $arg_list[0];
            		$consult_date = $arg_list[1];
        	}
        	$sql = "select case when (to_days('$consult_date')<=to_days(trimester1_date)) then 1 ".
               		"when (to_days('$consult_date')<=to_days(trimester2_date) and to_days('$consult_date')>to_days(trimester1_date)) then 2 ".
               		"when to_days('$consult_date')>to_days(trimester2_date) then 3 end ".
               		"from m_patient_mc ".
               		"where mc_id = '$mc_id'";
        	
		if ($result = mysql_query($sql)) {
            		if (mysql_num_rows($result)) {
                		list($trimester) = mysql_fetch_array($result);
		                return $trimester;
            		}
        	}
    	}

	function check_px_enrolled_sms(){
		//function returns true if the family_id has a patient wherein he/she is enrolled on a sms alert, false otherwise
		
		if(func_num_args()>0):
			$arg_list = func_get_args();
			$family_id = $arg_list[0];
		endif;

		$q_enroll = mysql_query("SELECT a.family_id FROM m_family_members a, m_lib_sms_px_enroll b WHERE a.family_id='$family_id' AND a.patient_id=b.patient_id") or die("Cannot query 2105: ".mysql_error());


		if(mysql_num_rows($q_enroll)!=0):
			return true;
		else:
			return false;
		endif;

	}

	function check_vaccine_dependency(){
		//this function would apply only on antigens that are given in dosages and dependent to a prior antigen. all except hepa B2 and B3 has 1 month of allowance in between
		//$arr_dep = array("DPT2"=>array('DPT1','28'),"DPT3"=>array('DPT2','28'),"OPV2"=>array('OPV1','28'),"OPV3"=>array('OPV2','28'),"HEPAB2"=>array('HEPAB1','42'),"HEPAB3"=>array('HEPAB2','56')); //first argument contains the antigen and second contains the distance between the two antigens

		if(func_num_args()>0):
			$arg_list = func_get_args();
			$vaccine = $arg_list[0];
			$patient_id = $arg_list[1];
		endif;

		//$arr_dep[$vaccine][0] -- prereq vaccination
		//$arr_dep[$vaccine][1] -- allowance between prereq vaccination and this vaccine

		$prereq_vacc = $this->arr_dep[$vaccine][0];
		$vacc_allowance = $this->arr_dep[$vaccine][1];
		
		//check first if the actual vaccination has been given 
		$q_vacc = mysql_query("SELECT ccdev_id, consult_id, patient_id, actual_vaccine_date FROM m_consult_ccdev_vaccine WHERE patient_id='$patient_id' AND vaccine_id='$vaccine'") or die("Cannot query 2148: ".mysql_error());

		if(mysql_num_rows($q_vacc)==0): 
			
		//echo $patient_id.' / '.$vaccine.'/ '.$prereq_vacc.'/'.$vacc_allowance.'<br>';			
		
		//check if the client has the prereq vaccination. if it has, check if the date today and its vaccine date is equal or more than the 2nd argument in days. if not, check the prerequisites

			$q_prereq_vacc = mysql_query("SELECT actual_vaccine_date FROM m_consult_ccdev_vaccine WHERE vaccine_id='$prereq_vacc' AND patient_id='$patient_id' AND (TO_DAYS(NOW()) - TO_DAYS(actual_vaccine_date)) >= '$vacc_allowance'") or die("Cannot query 2156: ".mysql_error());

			if(mysql_num_rows($q_prereq_vacc)!=0):
				return true; //a true would mean that the client is OK to be alerted for the vaccination
			else:
				return false; //a false would mean that the client doesn't have the prereq antigen yet and/or given less than allowance date
			endif;

		endif;
	}

	function send_basic_stat(){

		if(!empty($_POST["date_alert"])):
			list($m,$d,$y) = explode('/',$_POST["date_alert"]);
			$date_today = $y.'-'.sprintf("%02s",$m).'-'.sprintf("%02s",$d);
		else:
			$date_today = date('Y-m-d');
		endif;

		$brgy = $_SESSION["datanode"]["code"];
		$q_user = mysql_query("SELECT user_id, user_lastname, user_firstname, user_cellular FROM game_user WHERE user_cellular!='' AND user_receive_sms='Y' AND user_active='Y'") or die("Cannot query: ".mysql_error());

		$q_stats_today = mysql_query("SELECT news_text FROM m_news WHERE DATE(news_timestamp) < '$date_today' AND news_title LIKE '%Stat Updates%' ORDER BY news_timestamp DESC") or die("Cannot query: ".mysql_error());

		$q_insert_today = mysql_query("SELECT sms_code FROM m_lib_sms_alert WHERE alert_date='$date_today' AND alert_id='basic'") or die("Cannot query 2217: ".mysql_error());

		if(mysql_num_rows($q_user)!=0 && mysql_num_rows($q_stats_today)!=0):

			
			list($stat_txt) = mysql_fetch_array($q_stats_today);
			$stat_txt = str_replace('<br><br>',',',$stat_txt);
			$stat_txt = str_replace('<br>',',',$stat_txt);
			$stat_txt = str_replace('/','-',$stat_txt);
			$stat_txt = str_replace('(','- ',$stat_txt);
			$stat_txt = str_replace(')','',$stat_txt);

			while($user = mysql_fetch_array($q_user)){
				
				if((mysql_num_rows($q_insert_today)==0)):

					$insert_sms_alert = mysql_query("INSERT INTO m_lib_sms_alert SET patient_id='u-$user[user_id]',program_id='user',alert_id='basic',alert_date='$date_today',base_date='$date_today',sms_status='queue',last_update=NOW(),barangay_id='$brgy',sms_number='$user[user_cellular]',sms_message='$stat_txt',recipient_type='user'") or die("Cannot query 2216: ".mysql_error());

					$sms_id = mysql_insert_id();

					$update_sms_code = mysql_query("UPDATE m_lib_sms_alert SET sms_code='$brgy-user-$sms_id' WHERE sms_id='$sms_id'") or die("Cannot query: ".mysql_error());

				else:
   
					/*$insert_sms_alert = mysql_query("INSERT INTO m_lib_sms_alert SET patient_id='u-$user[user_id]',program_id='user',alert_id='basic',alert_date='$date_today',base_date='$date_today',sms_status='queue',last_update=NOW(),barangay_id='$brgy',sms_number='$user[user_cellular]',sms_message='$stat_txt',recipient_type='user'") or die("Cannot query 2216: ".mysql_error());

					$sms_id = mysql_insert_id();

					$update_sms_code = mysql_query("UPDATE m_lib_sms_alert SET sms_code='$brgy-user-$sms_id' WHERE sms_id='$sms_id'") or die("Cannot query: ".mysql_error());*/
				endif;




			}
		else:
			echo "<font color='red'>No end-user allowed to receive SMS on basic statistics.</font><br><br><br>";

		endif;
	}


	function check_sms_appt(){
		if(!empty($_POST["date_alert"])):
			list($m,$d,$y) = explode('/',$_POST["date_alert"]);
			$date_today = $y.'-'.sprintf("%02s",$m).'-'.sprintf("%02s",$d);
		else:
			$date_today = date('Y-m-d');
		endif;


		$q_appt = mysql_query("SELECT patient_id,cp_number,appt_code,sms_message FROM m_lib_sms_appointment WHERE date_sending='$date_today'") or die("Cannot query 2418: ".mysql_error());

		while(list($pxid,$cp_number,$appt_code,$sms_message)=mysql_fetch_array($q_appt)){
			$q_brgy = mysql_query("SELECT a.barangay_id FROM m_family_address a, m_family_members b WHERE b.patient_id='$pxid' AND b.family_id=a.family_id") or die("Cannot query 2421: ".mysql_error());
			list($brgy) = mysql_fetch_array($q_brgy);

			$insert_sms_alert = mysql_query("INSERT INTO m_lib_sms_alert SET patient_id='$pxid',program_id='appointment',alert_id='$appt_code',alert_date='$date_today',base_date='$date_today',sms_status='queue',last_update=NOW(),barangay_id='$brgy',sms_number='$cp_number',sms_message='$sms_message',recipient_type='px'") or die("Cannot query 2216: ".mysql_error());

			$sms_id = mysql_insert_id();

			$update_sms_code = mysql_query("UPDATE m_lib_sms_alert SET sms_code='$brgy-appointment-$sms_id' WHERE sms_id='$sms_id'") or die("Cannot query: ".mysql_error());
		}
									
	}



} //end of class
?>