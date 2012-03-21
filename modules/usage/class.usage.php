<?php

//module class usage diplays statistics on invidual usage of the system. this includes log stats and daa entry stats of each indicator
// stats can be filtered based on date of usage and reported as graph and can be downloaded as pdf file


class usage extends module {

	function usage(){
        	$this->author = "darth_ali";
        	$this->version = "0.1-".date("Y-m-d");
        	$this->module = "usage";
        	$this->description = "CHITS - Usage Stats and Graph";

		$this->arr_usage_indicator = array("User Logins","Patients Registered","Consultations Logged","Consult Notes Recorded");
		$this->arr_period = array('D'=>'Daily','W'=>'Weekly','M'=>'Monthly','Q'=>'Quarterly','A'=>'Annual');
	}

	function init_deps() {
        	module::set_dep($this->module, "module");
        	module::set_dep($this->module, "user");
    	}


	function init_lang(){
		

	}

	function init_menu(){

		module::set_menu($this->module, "Usage Stats and Graph", "REPORTS", "_usage");
        	module::set_detail($this->description, $this->version, $this->author, $this->module);
	}


	function init_help(){

	}


	function init_sql(){


	}


	function drop_tables(){


	}

	// ---- CUSTOM MODULE FUNCTIONS ---- //

	function _usage(){
	
		$this->form_usage();

		if($_POST["btn_usage"]):
			$this->query_stats($_POST["sel_usage"],$_POST["sel_period"]);
		else:
			$this->query_stats('0','D');
		endif;
	}


	function form_usage(){
		$arr_usage = $this->arr_usage_indicator;
		$arr_period = $this->arr_period;

		echo "<form action='$_SERVER[PHP_SELF]?page=$_GET[page]&menu_id=$_GET[menu_id]' name='form_usage' method='POST'>";

		echo "<table bgcolor='FFFF99' style='border: 1px solid #000000'>";
		echo "<thead bgcolor='#FF9900'><td><font color='white'><b>USAGE STATISTICS (select indicator and period)</b></font></td></thead>";
		
		echo "<tr><td>";
		echo "<select size='1' name='sel_usage'>";
		for($i=0;$i<count($arr_usage);$i++){
			echo "<option value='$i'>";
			echo $arr_usage[$i];
			echo "</option>";
		}
		echo "</select>";


		echo "&nbsp;&nbsp;";

		echo "<select size='1' name='sel_period'>";
		foreach($arr_period as $key=>$value){
			echo "<option value='$key' onclick=''>";
			echo $value;
			echo "</option>";
		}		
		echo "</select>";
		
		echo "&nbsp;&nbsp;";

		echo "<input type='submit' name='btn_usage' value='Set'></input>";
		
		echo "</td></tr>";

		echo "</table>";

		echo "</form>";
	
	}
	
	
	function query_stats($usage_ind,$period){
		$arr_dates = $this->get_current_period($period);
		
		$arr_usage = $this->query_usage($usage_ind,$arr_dates[0],$arr_dates[1]);
		
		if(count($arr_usage)!=0):
			$_SESSION["arr_usage"] = $arr_usage;
			$this->plot_table($usage_ind,$arr_dates);
		endif;
	}


	function get_current_period($period){
		$date_today = date('Y-m-d');

		switch($period){
	
			case 'D':
				$arr_dates = array($date_today,$date_today);
				break;

			case 'W':
				$q_weekly_date = mysql_query("SELECT start_date, end_date FROM m_lib_weekly_calendar WHERE '$date_today' BETWEEN start_date AND end_date") or die("Cannot query 131: ".mysql_error());
				if(mysql_num_rows($q_weekly_date)!=0):
					list($sdate,$edate) = mysql_fetch_array($q_weekly_date);
					$arr_date = array($sdate,$edate);
				else:
					echo "<table><tr><td width='350'><font color='red'><b>Warning! Please supply WEEKLY MORBIDITY CALENDAR. (LIBRARIES --> Usage Stats and Graph)</b></font></td></tr></table><br>";
				endif;

				break;

			case 'M':
				$arr_dates = array(date('Y-m-').'01',date('Y-m-').'31');
				break;

			case 'Q':
				$arr_months = $this->get_q_dates(date('m'));
				$arr_dates = array(date('Y').'-'.$arr_months[0].'-01',date('Y').'-'.$arr_months[1].'-31');
				break;

			case 'A':
				$arr_dates = array(date('Y').'-01-01',date('Y').'-12-31');
				break;

			default:

				break;
		}

		return $arr_dates;
	}


	function get_q_dates($month){
		if($month >= 1 && $month<=3):
			$arr_date = array('01','03','1');
		elseif($month >= 4 && $month<=6):
			$arr_date = array('04','06','2');
		elseif($month >= 7 && $month<=9):
			$arr_date = array('07','09','3');
		elseif($month >= 10 && $month<=12):
			$arr_date = array('10','12','4');
		else:

		endif;
		
		return $arr_date;
	}

	function query_usage($usage_ind,$sdate,$edate){
		//"User Logins","Patients Registered","Consultations Logged","User Registered""User Logins","Patients Registered","Consultations Logged","User Registered"
		$arr_users = $this->get_users();
		$arr_ind_count = array();
		

		switch($usage_ind){
				
			case '0': //

				for($i=0;$i<count($arr_users);$i++){
					$arr_log_count = array();
					$q_users = mysql_query("SELECT COUNT(log_id) FROM user_logs WHERE userid='$arr_users[$i]' AND date_format(login,'%Y-%m-%d') BETWEEN '$sdate' AND '$edate'") or die("Cannot query 174: ".mysql_error());

					list($log_count) = mysql_fetch_array($q_users);
					array_push($arr_log_count,$arr_users[$i],$log_count);
					array_push($arr_ind_count,$arr_log_count);
					//echo $arr_users[$i].' '.$sdate.' '.$edate.' '.$log_count.'<br>';
				}

				break;

			case '1':
				for($i=0;$i<count($arr_users);$i++){
					$arr_log_count = array();
					$q_px = mysql_query("SELECT COUNT(patient_id) FROM m_patient WHERE user_id='$arr_users[$i]' AND date_format(registration_date,'%Y-%m-%d') BETWEEN '$sdate' AND '$edate'") or die("Cannot query 196: ".mysql_error());
					
					list($log_count) = mysql_fetch_array($q_px);
					array_push($arr_log_count,$arr_users[$i],$log_count);
					array_push($arr_ind_count,$arr_log_count);

				}
				break;

			case '2':
				for($i=0;$i<count($arr_users);$i++){
					$arr_log_count = array();

					$q_consult = mysql_query("SELECT COUNT(consult_id) FROM m_consult WHERE user_id='$arr_users[$i]' AND date_format(consult_date,'%Y-%m-%d') BETWEEN '$sdate' AND '$edate'") or die("Cannot query 209: ".mysql_error());

					list($log_count) = mysql_fetch_array($q_consult);
					array_push($arr_log_count,$arr_users[$i],$log_count);
					array_push($arr_ind_count,$arr_log_count);

				}
				break;

			case '3':
				for($i=0;$i<count($arr_users);$i++){
					$arr_log_count = array();
				
					$q_notes = mysql_query("SELECT COUNT(notes_id) FROM m_consult_notes WHERE user_id='$arr_users[$i]' AND date_format(notes_timestamp,'%Y-%m-%d') BETWEEN '$sdate' AND '$edate'") or die("Cannot query 222: ".mysql_error());

					list($log_count) = mysql_fetch_array($q_notes);
					array_push($arr_log_count,$arr_users[$i],$log_count);
					array_push($arr_ind_count,$arr_log_count);
				}
				break;

			default:

				break;

		}

		return $arr_ind_count;
	}

	function get_users(){
		$arr_users = array(); 

		$q_user = mysql_query("SELECT user_id FROM game_user WHERE user_active='Y' ORDER by user_lastname ASC,user_firstname ASC") or die("Cannot query 197: ".mysql_error());

		if(mysql_num_rows($q_user)!=0):
			while(list($user_id) = mysql_fetch_array($q_user)){
				array_push($arr_users,$user_id);
			}
		endif;

		return $arr_users;
	}

	function plot_table($usage_ind,$arr_dates){
		echo "<table bgcolor='FFFF99' style='border: 1px solid #000000'>";
		echo "<tr><td colspan='2' bgcolor='#FF9900'><font color='white'><b>USAGE STATS (".$arr_dates[0]." to ".$arr_dates[1].")</b></td></tr>";
		echo "<tr align='center'><td><b>Name</b></td>";
		echo "<td><b>".$this->arr_usage_indicator[$usage_ind]."</b></td>";
		echo "</tr>";


		foreach($_SESSION["arr_usage"] as $key=>$value){

			echo "<tr>";
			$q_user = mysql_query("SELECT user_firstname, user_lastname FROM game_user WHERE user_id='$value[0]'") or die("Cannot query 238: ".mysql_error());
			list($fname,$lname) = mysql_fetch_array($q_user);	
			echo "<td>".$lname.', '.$fname."</td>";
			echo "<td align='center'>".$value[1]."</td>";
			echo "</tr>";
		}

		echo "</table>";
	}
}
?>
