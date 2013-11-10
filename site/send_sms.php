<?php
	session_start();
   ob_start();
   include('../chits_query/layout/class.widgets.php');

   $widconn = new widgets();


   $dbconn = mysql_connect('localhost',$_SESSION["dbuser"],$_SESSION["dbpass"]) or die("Cannot query 8 ".mysql_error());
   mysql_select_db($_SESSION["dbname"],$dbconn) or die("Cannot select db".mysql_error());

	$q_px = mysql_query("SELECT patient_lastname, patient_firstname, patient_id, patient_cellphone FROM m_patient WHERE patient_id='$_GET[pxid]'") or die("Cannot query 12: ".mysql_error());
	list($lname,$fname,$pxid,$cp) = mysql_fetch_array($q_px);

	if(!(empty($_GET["appt_id"]))):

		$q_appt = mysql_query("SELECT cp_number, appt_code, date_followup_visit, date_sending, sms_message FROM m_lib_sms_appointment WHERE appointment_id='$_GET[appt_id]'") or die("Cannot query 16: ".mysql_error());
		
		if(mysql_num_rows($q_appt)!=0):
			list($cp_number,$appt_code,$date_followup_visit,$date_sending,$sms_message) = mysql_fetch_array($q_appt);
			
			list($ffy,$ffm,$ffd) = explode('-',$date_followup_visit);
			$date_followup_visit = $ffm.'/'.$ffd.'/'.$ffy;

			list($sy,$sm,$sd) = explode('-',$date_sending);
			$date_sending = $sm.'/'.$sd.'/'.$sy;

			//echo $cp_number.'/'.$appt_id.'/'.$date_followup_visit.'/'.$date_sending.'/'.$sms_message;
		endif;
	endif;

	if($_POST["btn_submit"]):
		list($ffm,$ffd,$ffy) = explode('/',$_POST["txt_date_ffup"]);
		list($sm,$sd,$sy) = explode('/',$_POST["txt_date_send"]);

		$ff_date = $ffy.'-'.$ffm.'-'.$ffd;
		$send_date = $sy.'-'.$sm.'-'.$sd;

	switch($_POST["btn_submit"]){
		case "Save":
			$insert_appt = mysql_query("INSERT INTO m_lib_sms_appointment SET patient_id='$_POST[txt_pxid]',cp_number='$_POST[txt_cp]',appt_code='$_POST[sel_alert_type]',date_followup_visit='$ff_date',date_sending='$send_date',sms_message='$_POST[txt_msg]',consult_id='$_POST[txt_consult_id]',user_id='$_SESSION[userid]',sending_status='upcoming',date_recorded=NOW()") or die("Cannot query 41: ".mysql_error());

			if($insert_appt):
				echo "<font color='red'><b>The appointment for SMS sending was successfully been saved.</b></font>";
			endif;

			break;
		case "Send Now":

			break;
		case "Update":
			$update_appt = mysql_query("UPDATE m_lib_sms_appointment SET cp_number='$_POST[txt_cp]',date_followup_visit='$ff_date',date_sending='$send_date',sms_message='$_POST[txt_msg]',appt_code='$_POST[sel_alert_type]',sending_status='upcoming',date_updated=NOW() WHERE appointment_id='$_POST[txt_appt_id]'") or die("Cannot query 38: ".mysql_error());

			if($update_appt):
				header("Location: $_SERVER[PHP_SELF]?pxid=$_GET[pxid]&consult_id=$_GET[consult_id]&action=$_GET[action]&appt_id=$_GET[appt_id]");
			endif;

			break;
		case "Delete":
			
			break;
		default:

			break;
	}

	endif;

	echo "<html>";	
	echo "<head>";

	echo "<script language='javascript' src='../popups.js'></script>";
	echo "<script language='JavaScript' src='../ts_picker4.js'></script>";
	echo "<script language='JavaScript' src='../js/functions.js'></script>";

	echo "</head>";

	echo "<body>";

	echo "<form method='POST' action='$_SERVER[PHP_SELF]?pxid=$_GET[pxid]&consult_id=$_GET[consult_id]&action=$_GET[action]&appt_id=$_GET[appt_id]' name='form_send_sms'>";
	
	echo "<input type='hidden' name='txt_pxid' value='$pxid'></input>";
	echo "<input type='hidden' name='txt_consult_id' value='$_GET[consult_id]'></input>";
	echo "<input type='hidden' name='txt_action' value='$_GET[action]'></input>";
	echo "<input type='hidden' name='txt_appt_id' value=$_GET[appt_id]></input>";

	echo "<table bgcolor='#FFCCFF'>";
	echo "<tr><td class='alert_table_header' colspan='2' align='center'>SMS SCHEDULER</td></tr>";

	echo "<tr>";
	echo "<td>Patient's Name</td>";
	echo "<td>";
	echo "<input type='text' name='txt_px' value='$fname $lname'></input>";
	echo "</td>";
	echo "</tr>";


	$cp_value = (!(empty($cp))?$cp:$cp_number);

	echo "<tr>";
	echo "<td>Cellphone Number</td>";
	echo "<td>";
	echo "<input type='text' name='txt_cp' size='5' value='$cp_value'></input>";
	echo "</td>";
	echo "</tr>";


	echo "<tr>";
	echo "<td>Appointment Type</td>";
	echo "<td>";
	display_appt($appt_code);
	echo "</td>";
	echo "</tr>";

	echo "<tr>";
	echo "<td>Date of Follow-Up Visit</td>";
	echo "<td>";
	echo "<input type='text' name='txt_date_ffup' size='4' value='$date_followup_visit'></input>&nbsp;";
    echo "<a href=\"javascript:show_calendar4('document.form_send_sms.txt_date_ffup', document.form_send_sms.txt_date_ffup.value);\"><img src='../images/cal.gif' width='16' height='16' border='0' alt='Click Here to Pick up the date'></a><br>";

	echo "</td>";
	echo "</tr>";

	echo "<tr>";
	echo "<td>Date of SMS Sending</td>";
	echo "<td>";
	echo "<input type='text' name='txt_date_send' size='4' value='$date_sending'></input>&nbsp;";
    echo "<a href=\"javascript:show_calendar4('document.form_send_sms.txt_date_send', document.form_send_sms.txt_date_send.value);\"><img src='../images/cal.gif' width='16' height='16' border='0' alt='Click Here to Pick up the date'></a><br>";
	echo "</td>";
	echo "</tr>";

	echo "<tr>";
	echo "<td>Message</td>";
	echo "<td>";
	echo "<textarea cols='25' rows='5' name='txt_msg'>$sms_message</textarea>";
	echo "</td>";
	echo "</tr>";


	echo "<tr>";
	echo "<td colspan='2' align='center'>";


	if($_GET["action"]!='update'):
		echo "<input type='submit' name='btn_submit' value='Save'></input>&nbsp;&nbsp;";
		//echo "<input type='submit' name='btn_submit' value='Send Now'></input>&nbsp;&nbsp;";
	else:
		echo "<input type='submit' name='btn_submit' value='Update'></input>&nbsp;&nbsp;";
	endif;

	echo "<input type='button' name='btn_close' value='Close' onclick='window.close()'></input>";
	echo "</td>";
	echo "</tr>";


	echo "</table>";

	echo "</form>";
	echo "</body>";
	echo "</html>";


	function display_appt($appt_code){
		$q_indicator = mysql_query("SELECT alert_indicator_id, main_indicator, sub_indicator FROM m_lib_alert_indicators ORDER by main_indicator ASC, sub_indicator ASC") or die("Cannot query 86: ".mysql_error());


		echo "<select name='sel_alert_type' size='1'>";
		echo "<option value='0'>--- Select Appointment Type ---</option>";		
		if(mysql_num_rows($q_indicator)!=0):
			while(list($alert_id,$main_ind,$sub)=mysql_fetch_array($q_indicator)){
				if($alert_id==$appt_code):
					echo "<option value='$alert_id' SELECTED>$main_ind - $sub</option>";		
				else:
					echo "<option value='$alert_id'>$main_ind - $sub</option>";		
				endif;
			}
		endif;
		echo "</select>";
	}

	
?>