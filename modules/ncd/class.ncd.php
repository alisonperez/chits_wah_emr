<?php
class ncd extends module{

	
	function ncd(){
		$this->description = "Non Communicable Diseases Prevention and Control";
		$this->version = "0.1-".date('Y-m-d');
		$this->author = "darth_ali";
		$this->module = "ncd";
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
		module::set_detail($this->description,$this->version,$this->author,$this->module);
	}


	function init_sql(){
		
	}

	function drop_tables(){
	}


	// end of default functions
	
	function _consult_ncd(){
		if($exitinfo = $this->missing_dependencies('ncd')){
			return print($exitinfo);
		}

		if(func_num_args()>0){
		      $menu_id = $arg_list[0];	   //from $_GET
		      $post_vars = $arg_list[1];      //from form submissions
		      $get_vars = $arg_list[2];       //from $_GET
		      $validuser = $arg_list[3];       //from $_SESSION
		      $isadmin = $arg_list[4];	       //from $_SESSION
		}

		$ncd = new ncd;
		$ncd->ncd_menu($_GET["menu_id"],$_POST,$_GET,$_SESSION["validuser"],$_SESSION["isadmin"]);
		
		$ncd->form_ncd();
	}

	
	function form_ncd(){
		if(func_num_args()>0){
		      $menu_id = $arg_list[0];	   //from $_GET
		      $post_vars = $arg_list[1];      //from form submissions
		      $get_vars = $arg_list[2];       //from $_GET
		      $validuser = $arg_list[3];       //from $_SESSION
		      $isadmin = $arg_list[4];	       //from $_SESSION
		}

		
		switch($_GET["ncd"]){
			case 'ARCHIVE':
				$this->form_archives();
				break;
			case 'ASSESSMENT':
				$this->set_high_risk_assessment();
				//print_r($_POST);
				
				if($_POST["submit_risk_assess"]=='Start New NCD High Risk Assessment Form'):
					//print_r($_POST); 
					if($_POST["txt_assess_date"]==''):
						echo "<script language='Javascript'>";
						echo "window.alert('Please indicate the date of the assessment')";
						echo "</script>";
					else:
						$this->form_high_risk_assessment('');
					endif;
				elseif($_POST["submit_risk_assess"]=='Update Risk Assessment Date'):
					if($_POST["txt_assess_date"]==''):
						echo "<script language='Javascript'>";
						echo "window.alert('Please indicate the date of the assessment')";
						echo "</script>";
					else:
						list($m,$d,$y) = explode('/',$_POST["txt_assess_date"]);
						$assess_date = $y.'-'.str_pad($m, 2, "0", STR_PAD_LEFT).'-'.$d; 
						$q_update = mysql_query("UPDATE m_consult_ncd_risk_assessment SET date_assessment='$assess_date' WHERE ncd_id='$_GET[ncd_id]' AND consult_risk_id='$_GET[ncd_consult]'") or die("Cannot query 108: ".mysql_error());

						header("Location: ". "$_SERVER[PHP_SELF]?page=$_GET[page]&menu_id=$_GET[menu_id]&consult_id=$_GET[consult_id]&ptmenu=$_GET[ptmenu]&module=$_GET[module]&ncd=$_GET[ncd]&ncd_id=$_GET[ncd_id]&ncd_consult=$_GET[ncd_consult]#ra");

					endif;
				elseif($_POST["submit_assess"]=='Save Risk Assessment Details'): 
					//$this->check_assess_next();
					$this->form_high_risk_assessment($this->check_assess_next());
				elseif($_POST["submit_assess"]=='Update Risk Assessment Details'):
					$this->update_risk_assessment();
					$this->form_high_risk_assessment('');
				elseif($_POST["submit_assess"]=='Save and Proceed to Risk Screening'):
					$this->save_risk_assessment();
					$this->form_high_risk_assessment('');
				elseif($_GET["ncd_id"]!='' && $_GET["ncd_consult"]!=''):
					$this->form_high_risk_assessment('');
				else:
					
				endif;

				break;

			case "SCREENING":
				if($_POST["submit_risk_screening"]=='Save Presence of Diabetes Details'):
					//print_r($_POST);
					$this->update_presence_diabetes();
				elseif($_POST["submit_risk_screening"]=='Save Blood Glucose Test Details'):
					$this->update_blood_glucose();
				elseif($_POST["submit_risk_screening"]=='Save Blood Lipid Test Details'):
					$this->update_lipid();
				elseif($_POST["submit_risk_screening"]=='Save Urine Test for Protein Details'):
					$this->update_urine_protein();
				elseif($_POST["submit_risk_screening"]=='Save Urine Test for Ketones Details'):
					$this->update_urine_ketones();
				elseif($_POST["submit_risk_screening"]=='Save'):
					$this->update_screening_date();
				else:
				endif;

				$this->form_risk_screening();



				break;
			case "QUESTIONNAIRE":
				if($_POST["submit_interview"]):
					$this->update_question();
				endif;

				$this->form_questionnaire_tia();
				
				break;
			case "REFER":
				$this->form_refer_patient();
				$this->form_return_slip();
				break;
			case "STRATIFICATION":				
				$this->show_risk_stratification();			
				break;

			case "PATIENTRECORD":
				$this->show_patient_record();
				//$this->form_case_management();
				break;
	

			default:
				header("location: $_SERVER[PHP_SELF]?page=$_GET[page]&menu_id=$_GET[menu_id]&consult_id=$_GET[consult_id]&ptmenu=$_GET[ptmenu]&module=$_GET[module]&ncd=ARCHIVE#archive");
				$this->set_high_risk_assessment();
				$this->form_high_risk_assessment();
				break;
				break;

		}
	}

	function _details_ncd(){
		if(func_num_args()>0){
			$menu_id = $arg_list[0];
			$post_vars = $arg_list[1];
			$get_vars = $arg_list[2];
			$validuser = $arg_list[3];
			$isadmin = $arg_list[4];
		}
	}

	function ncd_menu(){
		if(func_num_args()>0){
		      $menu_id = $arg_list[0];	   //from $_GET
		      $post_vars = $arg_list[1];      //from form submissions
		      $get_vars = $arg_list[2];       //from $_GET
		      $validuser = $arg_list[3];       //from $_SESSION
		      $isadmin = $arg_list[4];	       //from $_SESSION
		}

		echo "<table cellpadding='1' cellspacing='1' bgcolor='#33CC33' style='border: 1px solid black'>";
		echo "<tr><td>";		
		
		
		echo "<a href='$_SERVER[PHP_SELF]?page=$_GET[page]&menu_id=$_GET[menu_id]&consult_id=$_GET[consult_id]&ptmenu=$_GET[ptmenu]&module=$_GET[module]&ncd=ARCHIVE#archive' class='fpmenu'>NCD ARCHIVE</a>";

		if(!empty($_GET["ncd_id"]) && !empty($_GET["ncd_consult"])):

			echo "<a href='$_SERVER[PHP_SELF]?page=$_GET[page]&menu_id=$_GET[menu_id]&consult_id=$_GET[consult_id]&ptmenu=$_GET[ptmenu]&module=$_GET[module]&ncd=ASSESSMENT&ncd_id=$_GET[ncd_id]&ncd_consult=$_GET[ncd_consult]#ra' class='fpmenu'>RISK ASSESSMENT</a>";
		
			echo "<a href='$_SERVER[PHP_SELF]?page=$_GET[page]&menu_id=$_GET[menu_id]&consult_id=$_GET[consult_id]&ptmenu=$_GET[ptmenu]&module=$_GET[module]&ncd=SCREENING&ncd_id=$_GET[ncd_id]&ncd_consult=$_GET[ncd_consult]#rs' class='fpmenu'>RISK SCREENING</a>";
		

			echo "<a href='$_SERVER[PHP_SELF]?page=$_GET[page]&menu_id=$_GET[menu_id]&consult_id=$_GET[consult_id]&ptmenu=$_GET[ptmenu]&module=$_GET[module]&ncd=QUESTIONNAIRE&ncd_id=$_GET[ncd_id]&ncd_consult=$_GET[ncd_consult]#ques' class='fpmenu'>QUESTIONNAIRE (NURSE INTERVIEW)</a>";

			echo "<a href='$_SERVER[PHP_SELF]?page=$_GET[page]&menu_id=$_GET[menu_id]&consult_id=$_GET[consult_id]&ptmenu=$_GET[ptmenu]&module=$_GET[module]&ncd=STRATIFICATION&ncd_id=$_GET[ncd_id]&ncd_consult=$_GET[ncd_consult]#ques' class='fpmenu'>RISK STRATIFICATION</a>";

			echo "<a href='$_SERVER[PHP_SELF]?page=$_GET[page]&menu_id=$_GET[menu_id]&consult_id=$_GET[consult_id]&ptmenu=$_GET[ptmenu]&module=$_GET[module]&ncd=PATIENTRECORD&ncd_id=$_GET[ncd_id]&ncd_consult=$_GET[ncd_consult]#ques' class='fpmenu'>PATIENT RECORD</a>";
		
		else:
			echo "<a href='$_SERVER[PHP_SELF]?page=$_GET[page]&menu_id=$_GET[menu_id]&consult_id=$_GET[consult_id]&ptmenu=$_GET[ptmenu]&module=$_GET[module]&ncd=ASSESSMENT#ra' class='fpmenu'>RISK ASSESSMENT</a>";
		endif;

		echo "<a href='$_SERVER[PHP_SELF]?page=$_GET[page]&menu_id=$_GET[menu_id]&consult_id=$_GET[consult_id]&ptmenu=$_GET[ptmenu]&module=$_GET[module]&ncd=REFER#refer' class='fpmenu'>REFER PATIENT</a>";


		echo "</td></tr>";
		echo "<table>";
		
	}

	function menu_highlight(){

	}

	function set_high_risk_assessment(){
		if($_POST["sel_assessment"]=='facility'):
			$facility = 'SELECTED';
		elseif($_POST["sel_assessment"]=='community'):
			$community = 'SELECTED';
		else:
			$facility = $community = '';
		endif;


		if($_GET["ncd_consult"]):
			$q_date = mysql_query("SELECT risk_assessment_type, date_format(date_assessment,'%m/%d/%Y') as date_assess FROM m_consult_ncd_risk_assessment WHERE consult_risk_id='$_GET[ncd_consult]'") or die("Cannot query 219: ".mysql_error());

			list($location,$date_assessment) = mysql_fetch_array($q_date);
			
			if($location=='facility'):
				$facility = 'SELECTED';
			elseif($location=='community'):
				$community = 'SELECTED';
			else:
				$facility = $community = '';
			endif;

			$date_assess = $date_assessment;
		else:
			$date_assess = $_POST["txt_assess_date"];
		endif;

		echo "<form action='$_SERVER[PHP_SELF]?page=$_GET[page]&menu_id=$_GET[menu_id]&consult_id=$_GET[consult_id]&ptmenu=$_GET[ptmenu]&module=$_GET[module]&ncd=ASSESSMENT&ncd_id=$_GET[ncd_id]&ncd_consult=$_GET[ncd_consult]#ra' method='POST' name='form_ncd_set_assessment'>";

		echo "<br>";
		echo "<a name='ra' />";
		echo "<table bgcolor='#66FF66'>";
		echo "<tr><td colspan='5' align='center' bgcolor='#339966' class='whitetext'>NCD HIGH-RISK ASSESSMENT</tr></td></thead>";
		echo "<tr><td>RISK ASSESSMENT LOCATION</td>";
		echo "<td><select name='sel_assessment' size='1'>";
		echo "<option value='community' $community>Community Level</option>";
		echo "<option value='facility' $facility>Health Facility</option>";
		echo "</td>";
		
		echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>";
		echo "<td>DATE OF ASSESSMENT (mm/dd/yyyy)</td>";

		echo "<td>";
		echo "<input type='text' size='11' maxlength='10' name='txt_assess_date' value='$date_assess'>&nbsp;&nbsp;";
		echo "<a href=\"javascript:show_calendar4('document.form_ncd_set_assessment.txt_assess_date', document.form_ncd_set_assessment.txt_assess_date.value);\"><img src='../images/cal.gif' width='16' height='16' border='0' alt='Click Here to Pick up the date'></a>";
		echo "</input></td>";

		if($_GET["ncd_id"]=='' && $_GET["ncd_consult"]==''):
			echo "</tr>";
			echo "<tr align='center'>";
			echo "<td colspan='5'><input type='submit' value='Start New NCD High Risk Assessment Form' name='submit_risk_assess'></input></td>";
			echo "</tr>";
		else:
			echo "</tr>";
			echo "<tr align='center'>";
			echo "<td colspan='5'><input type='submit' value='Update Risk Assessment Date' name='submit_risk_assess'></input></td>";
			echo "</tr>";
		endif;

		echo "</table>";
		echo "<br>";
		echo "</form>";
	}

	function form_high_risk_assessment($next_step){
		$r_assess = array();
		$walkin = $refer = '';

		$q_facility_type = mysql_query("SELECT risk_assessment_type FROM m_consult_ncd_risk_assessment WHERE consult_risk_id='$_GET[ncd_consult]' AND ncd_id='$_GET[ncd_id]'") or die("Cannot query 281: ".mysql_error());
		list($location) = mysql_fetch_array($q_facility_type);
		

		if($_GET["ncd_id"]=='' && $_GET["ncd_consult"]==''):
			echo "<form action='$_SERVER[PHP_SELF]?page=$_GET[page]&menu_id=$_GET[menu_id]&consult_id=$_GET[consult_id]&ptmenu=$_GET[ptmenu]&module=$_GET[module]&ncd=$_GET[ncd]#ra' method='POST' name='form_ncd_assessment'>";
		else:
			echo "<form action='$_SERVER[PHP_SELF]?page=$_GET[page]&menu_id=$_GET[menu_id]&consult_id=$_GET[consult_id]&ptmenu=$_GET[ptmenu]&module=$_GET[module]&ncd=$_GET[ncd]&ncd_id=$_GET[ncd_id]&ncd_consult=$_GET[ncd_consult]#ra' method='POST' name='form_ncd_assessment'>";

			$q_assess = mysql_query("SELECT * FROM m_consult_ncd_risk_assessment WHERE consult_id='$_GET[consult_id]' AND ncd_id='$_GET[ncd_id]' AND consult_risk_id='$_GET[ncd_consult]'") or die("Cannot query 220: ".mysql_error());
	
			if(mysql_num_rows($q_assess)!=0):
				$r_assess = mysql_fetch_array($q_assess);
				//print_r($r_assess);
				$_SESSION["ncd_id"] = $ncd_id;
				$_SESSION["ncd_consult"] = $r_assess["consult_risk_id"];
			endif;
		endif;

		echo "<input type='hidden' name='sel_assessment' value='$_POST[sel_assessment]'></input>";
		echo "<input type='hidden' name='txt_assess_date' value='$_POST[txt_assess_date]'></input>";

		echo "<table bgcolor='#66FF66'>";

		if($_POST["sel_assessment"]=='facility' || $location=='facility'):

		echo "<tr><td>If location is HEALTH FACILITY, select TYPE OF CLIENT</td>";
		echo "<td colspan='2'><select name='sel_facility_type' size='1'>";
		echo "<option value='walkin'>Walk-in clients</option>";
		echo "<option value='refer'>Referred from the community</option>";
		echo "</td>";
		echo "</tr>";
		
		elseif($r_assess["facility_risk_type"]!=''):
			if($r_assess["facility_risk_type"]=='walkin'):
				$walkin = 'SELECTED';
			elseif($r_assess["facility_risk_type"]=='refer'):
				$refer = 'SELECTED';
			else:
			endif;

		echo "<tr><td>If location is HEALTH FACILITY, select TYPE OF CLIENT</td>";
		echo "<td colspan='2'><select name='sel_facility_type' size='1'>";
		echo "<option value='walkin' $walkin>Walk-in clients</option>";
		echo "<option value='refer' $refer>Referred from the community</option>";
		echo "</td>";
		echo "</tr>";

		else:
		
		endif;

		
		$this->display_family_hx($r_assess);
		echo "<tr><td colspan='4'><hr></td></tr>";
		$this->smoking_status($r_assess);
		echo "<tr><td colspan='4'><hr></td></tr>";
		$this->alcohol_intake($r_assess);
		echo "<tr><td colspan='4'><hr></td></tr>";
		$this->high_fat_salt($r_assess);
		echo "<tr><td colspan='4'><hr></td></tr>";
		$this->dietary_fiber($r_assess);
		echo "<tr><td colspan='4'><hr></td></tr>";
		$this->physical_activity($r_assess);
		
		if($_POST["sel_assessment"]=='community' || $location=='community'):
			echo "<tr><td colspan='4'><hr></td></tr>";
			$this->presence_diabetes($r_assess);
		endif;

		echo "<tr><td colspan='4'><hr></td></tr>";
		$this->bp_adiposity($r_assess);
		echo "<tr><td colspan='4'><hr></td></tr>";
		$this->action_taken($r_assess);
		echo "<tr><td colspan='4'><hr></td></tr>";

		if($_GET["ncd_id"]=='' && $_GET["ncd_consult"]==''):	
			echo "<tr><td colspan='4' align='center'><input type='submit' name='submit_assess' value='Save Risk Assessment Details' />&nbsp;&nbsp;";
		else:
			echo "<tr><td colspan='4' align='center'><input type='submit' name='submit_assess' value='Update Risk Assessment Details' />&nbsp;&nbsp;";
		endif;
		
		if($next_step=='screen'):
			echo "<input type='submit' name='submit_assess' value='Save and Proceed to Risk Screening' />&nbsp;&nbsp;";
		elseif($next_step=='healthinfo'):
			echo "<input type='submit' name='submit_assess' value='Save and Provide Health Information' />&nbsp;&nbsp;";
		else:
		endif;

		echo "<input type='reset' name='reset' value='Reset Values' /></td></tr>";

		echo "</table>";

		echo "</form>";
	}

	function display_family_hx($r_assess){ 
		
		$q_fam_hx = mysql_query("SELECT hx_id, hx_label FROM m_lib_ncd_family_hx ORDER by hx_sequence ASC") or die("Cannot query 157: ".mysql_error());

		echo "<tr><td colspan='2' valign='top'>Family History - Does the patient have 1st degree relative with</td>";
		echo "<td>";
		echo "<table>";
		while(list($hx_id,$hx_label) = mysql_fetch_array($q_fam_hx)){ 
			$yes = $no = $na = '';

			$family_hx_tbl = $this->get_field_name_family_hx($hx_id);

			if($_POST[$hx_id]=='Y' || $r_assess[$family_hx_tbl]=='Y'):
				$yes = 'SELECTED';
			elseif($_POST[$hx_label]=='NA' || $r_assess[$family_hx_tbl]=='NA'):
				$na = 'SELECTED';
			else:
				$no = 'SELECTED';
			endif;

			echo "<tr>";
			echo "<td class='boxtitle'>".$hx_label."</td>";
			echo "<td><select name='$hx_id' size='1'>";
			echo "<option value='Y' $yes>Yes</option>";
			echo "<option value='N' $no>No</option>";
			echo "<option value='NA' $na>N/A</option>";
			echo "</select></td>";
			echo "</tr>";
		}
		echo "</table>";
		echo "</td>";
		echo "</tr>";
		
	}

	function smoking_status($r_assess){

		$q_smoking_status = mysql_query("SELECT smoking_id, smoking_status_label FROM m_lib_ncd_smoking_status ORDER by smoking_seq ASC") or die("Cannot query 180: ".mysql_error());

		echo "<tr><td colspan='2' valign='top'>Smoking (Tobacco / Cigarette) </td>";
		echo "<td>";
		echo "<table><tr><td><select name='sel_smoking' size='1'>";
		while(list($smoking_id,$smoking_status)=mysql_fetch_array($q_smoking_status)){
			/*echo "<tr>";
			echo "<td>"; 
			echo "<input type='radio' name='radio_smoking[]' value='$smoking_id'>$smoking_status</>";
			echo "</td>";
			echo "</tr>"; */

			if($_POST["sel_smoking"]==$smoking_id || $r_assess["smoking"]==$smoking_id):
				echo "<option value='$smoking_id' SELECTED>$smoking_status</option>";
			else:
				echo "<option value='$smoking_id'>$smoking_status</option>";
			endif;
		}
		echo "</select></td></tr></table>";
		
		echo "</td>";
		echo "</tr>";
	}

	function alcohol_intake($r_assess){
		$yes = $no = $yes_excess = $no_excess = '';

		if($_POST["sel_alcohol"]=='Y' || $r_assess["alcohol_intake"]=='Y'):
			$yes = 'SELECTED';
		elseif($_POST["sel_alcohol"]=='N' || $r_assess["alcohol_intake"]=='N'):
			$no = 'SELECTED';
		else:
			$no = 'SELECTED';
		
		endif;

		if($_POST["sel_excessive_alcohol"]=='Y' || $r_assess["excessive_alcohol_intake"]=='Y'):
			$yes_excess = 'SELECTED';
		elseif($_POST["sel_excessive_alcohol"]=='N' || $r_assess["excessive_alcohol_intake"]=='N'):
			$no_excess = 'SELECTED';
		else:
			$no_excess = 'SELECTED';
		endif;

		echo "<tr><td colspan='2' valign='top'>Alcohol Intake</td>";
		echo "<td>";
		echo "<select name='sel_alcohol' size='1'>";
		echo "<option value='N' $no>Never consumed</option>";
		echo "<option value='Y' $yes>Yes, drinks alcohol</option>";
		echo "</select>";
		echo "</td>";
		echo "</tr>";

		echo "<tr><td colspan='2' valign='top'>Excessive Alcohol Intake<br>In the past months, had 5 drinks in one occassion</td>";
		echo "<td>";
		echo "<select name='sel_excessive_alcohol' size='1'>";
		echo "<option value='Y' $yes_excess>Yes</option>";
		echo "<option value='N' $no_excess>No</option>";		
		echo "</select>";
		echo "</td>";
		echo "</tr>";
	}

	function high_fat_salt($r_assess){
		$yes = $no = '';

		if($_POST["sel_fat_salt"]=='Y' || $r_asses["high_fat_salt"]=='Y'):
			$yes = 'SELECTED';
		elseif($_POST["sel_fat_salt"]=='N' || $r_asses["high_fat_salt"]=='N'):
			$no = 'SELECTED';
		else:
			$no = 'SELECTED';
		endif;

		echo "<tr><td colspan='2' valign='top'>High Fat / High Salt Intake</td>";
		echo "<td>";
		echo "<select name='sel_fat_salt' size='1'>";		
		echo "<option value='Y' $yes>Yes</option>";
		echo "<option value='N' $no>No</option>";
		echo "</select>";
		echo "</td>";
		echo "</tr>";
	}

	function dietary_fiber($r_assess){
		$yes_dietary = $no_dietary = $yes_fruit = $no_fruit = '';
		
		if($_POST["sel_dietary"]=='Y' || $r_assess["dietary_fiber_vegetables"]=='Y'):
			$yes_dietary = 'SELECTED';
		elseif($_POST["sel_dietary"]=='N' || $r_assess["dietary_fiber_vegetables"]=='N'):
			$no_dietary = 'SELECTED';
		else:
			$no_dietary = 'SELECTED';
		endif;


		if($_POST["sel_fruits"]=='Y' || $r_assess["dietary_fiber_fruits"]=='Y'):
			$yes_fruit = 'SELECTED';
		elseif($_POST["sel_fruits"]=='N' || $r_assess["dietary_fiber_fruits"]=='N'):
			$no_fruit = 'SELECTED';
		else:
			$no_fruit = 'SELECTED';
		endif;


		echo "<tr><td colspan='2' valign='top'>Dietary Fiber Intake<br>3 servings of vegetables daily<br>2-3 servings of fruits daily<br></td>";
		echo "<td><br>";
		echo "<select name='sel_dietary' size='1'>";
		echo "<option value='Y' $yes_dietary>Yes</option>";
		echo "<option value='N' $no_dietary>No</option>";
		echo "</select>";
		
		echo "<br>";
		echo "<select name='sel_fruits' size='1'>";
		echo "<option value='Y' $yes_fruit>Yes</option>";
		echo "<option value='N' $no_fruit>No</option>";
		echo "</select>";
		echo "</td></tr>";
	}


	function physical_activity($r_assess){
		$yes = $no = '';

		if($_POST["sel_physical"]=='Y' || $r_assess["physical_activity"]=='Y'):
			$yes = 'SELECTED';
		elseif($_POST["sel_physical"]=='N' || $r_assess["physical_activity"]=='N'):
			$no = 'SELECTED';
		else:
			$no = 'SELECTED';
		endif;

		echo "<tr><td colspan='2'>Physical Activity<br>Does at least 2 and a half hours a week of moderate-intensity physical activity</td><td>";
		echo "<select name='sel_physical' size='1'>";
		echo "<option value='Y' $yes>Yes</option>";
		echo "<option value='N' $no>No</option>";
		echo "</select>";
		echo "</td>";
		echo "</tr>";
	}

	function set_screen_date(){
		$q_screen = mysql_query("SELECT date_screening FROM m_consult_ncd_risk_assessment WHERE consult_risk_id='$_GET[ncd_consult]' AND ncd_id='$_GET[ncd_id]'") or die("Cannot query 542: ".mysql_error());
		
	
		list($date_screen) = mysql_fetch_array($q_screen);
		
		if($date_screen=='0000-00-00'):
			$date_screen = date('Y-m-d');
		endif;




		echo "<table bgcolor='#66FF66'>";
		echo "<tr><td colspan='2' align='center' bgcolor='#339966' class='whitetext'>&nbsp;&nbsp;&nbsp;Date of Screening (YYYY-MM-DD)&nbsp;&nbsp;&nbsp;</td><td>";
		echo "<input type='text' name='txt_date_screen' size='8' maxlength='10' value='$date_screen'>";
		echo "</input>";
		echo "&nbsp;&nbsp;";
		echo "<input type='submit' name='submit_risk_screening' value='Save' />";
		echo "</td>";
		echo "</tr>";
		echo "</table>";

	}

	function presence_diabetes($r_assess){
		$yes = $no = '';

		if($_POST["sel_diabetes"]=='Y' || $r_assess["presence_diabetes"]=='Y'):
			$yes = 'SELECTED';
		elseif($_POST["sel_diabetes"]=='N' || $r_assess["presence_diabetes"]=='N'):
			$no = 'SELECTED';
		else:
			$no = 'SELECTED';
		endif;

		echo "<tr><td colspan='2'>Presence or absence of diabetes<br>Was patient diagnosed as having diabetes?</td><td>";
		echo "<select name='sel_diabetes' size='1'>";
		echo "<option value='Y'>Yes</option>";
		echo "<option value='N'>No</option>";
		echo "</select>";
		echo "</td>";
		echo "</tr>";
	}

	function bp_adiposity($r_assess){
		$txt_waist = $adiposity = $no = $raised_bp = $diastolic_1st = $systolic_1st = $diastolic_2nd = $systolic_2nd = $ave_bp = '';
		
		$q_pxid = mysql_query("SELECT patient_id FROM m_consult WHERE consult_id='$_GET[consult_id]'") or die("Cannot query 905: ".mysql_error());
		list($patient_id) = mysql_fetch_row($q_pxid);

		$q_gender = mysql_query("SELECT patient_gender FROM m_patient WHERE patient_id='$patient_id'") or die("Cannot query 909: ".mysql_error());

		list($patient_gender) = mysql_fetch_row($q_gender);

		if(!empty($_POST["txt_waist"])):
			$txt_waist = $_POST["txt_waist"];
		elseif(!empty($r_assess["waist_line"])):
			$txt_waist = $r_assess["waist_line"];
		else:
			$txt_waist = '';
		endif;

		if(!empty($_POST["txt_diastolic_1st"])):
			$diastolic_1st = $_POST["txt_diastolic_1st"];
		elseif(!empty($r_assess["diastolic_1st"])):
			$diastolic_1st = $r_assess["diastolic_1st"];
		else:
		endif;

		if(!empty($_POST["txt_systolic_1st"])):
			$systolic_1st = $_POST["txt_systolic_1st"];
		elseif(!empty($r_assess["systolic_1st"])):
			$systolic_1st = $r_assess["systolic_1st"];
		else:
		endif;

		if(!empty($_POST["txt_diastolic_2nd"])):
			$diastolic_2nd = $_POST["txt_diastolic_2nd"];
		elseif(!empty($r_assess["diastolic_2nd"])):
			$diastolic_2nd = $r_assess["diastolic_2nd"];
		endif;

		if(!empty($_POST["txt_systolic_2nd"])):
			$systolic_2nd = $_POST["txt_systolic_2nd"];
		elseif(!empty($r_assess["systolic_2nd"])):
			$systolic_2nd = $r_assess["systolic_2nd"];
		else:
		endif;

		if($diastolic_1st!='' && $systolic_1st!='' && $diastolic_2nd!='' && $systolic_2nd!=''):
			$ave_diastolic = round(($diastolic_1st + $diastolic_2nd)/2,1);
			$ave_systolic = round(($systolic_1st + $systolic_2nd)/2,1);

			if($ave_systolic >= 120 && $ave_diastolic >= 80):
				$raised_bp = 'Yes';
				echo "<input type='hidden' name='sel_raised_bp' value='Y' />";
			else:
				$raised_bp = 'No';
				echo "<input type='hidden' name='sel_raised_bp' value='N' />";
			endif;
			
			$ave_bp = $ave_systolic.' / '.$ave_diastolic;
			echo "<input type='hidden' name='sel_ave_bp' value='$ave_bp' />";
		endif;



		if($txt_waist!=''):
			if(($txt_waist >= '90' && $patient_gender=='M')):
				$adiposity = 'Yes (at risk)';
				echo "<input type='hidden' name='sel_adiposity' value='Y' />";
			elseif($txt_waist >= '80' && $patient_gender=='F'):
				$adiposity = 'Yes (at risk)';
				echo "<input type='hidden' name='sel_adiposity' value='Y' />";
			else:
				$adiposity = 'No  (not at risk)';
				echo "<input type='hidden' name='sel_adiposity' value='N' />";
			endif;
		endif;
		
		//elseif($_POST["sel_fat_salt"]=='N'):
		//	$no = 'SELECTED';
		//else:
		//	$no = 'SELECTED';
		//endif;

		echo "<tr><td colspan='2'>Central Adiposity</td><td>";
		echo $adiposity;
		echo "</select>";
		echo "</td>";
		echo "</tr>";

		
		echo "<tr><td colspan='2'>Waist Circumference (cm)</td><td>";
		echo "<input type='text' name='txt_waist' size='2' maxlength='3' value='$txt_waist'></input>";
		echo "</select>";
		echo "</td>";
		echo "</tr>";

		echo "<tr><td colspan='2'>Raised Blood Pressure (>= 120/80)</td><td>";
		echo $raised_bp;
		echo "</td>";
		echo "</tr>";

		echo "<tr><td colspan='2'>Average Blood Pressure</td><td>";
		echo $ave_bp;
		echo "</td>";
		echo "</tr>";

		echo "<tr><td colspan='2'>Systolic 1st Reading</td><td>";
		echo "<input type='text' name='txt_systolic_1st' size='2' maxlength='3' value='$systolic_1st'></input>";
		echo "</select>";
		echo "</td>";
		echo "</tr>";

		echo "<tr><td colspan='2'>Diastolic 1st Reading</td><td>";
		echo "<input type='text' name='txt_diastolic_1st' size='2' maxlength='3' value='$diastolic_1st'></input>";
		echo "</select>";
		echo "</td>";
		echo "</tr>";

		echo "<tr><td colspan='2'>Systolic 2nd Reading</td><td>";
		echo "<input type='text' name='txt_systolic_2nd' size='2' maxlength='3' value='$systolic_2nd'></input>";
		echo "</select>";
		echo "</td>";
		echo "</tr>";

		echo "<tr><td colspan='2'>Diastolic 2nd Reading</td><td>";
		echo "<input type='text' name='txt_diastolic_2nd' size='2' maxlength='3' value='$diastolic_2nd'></input>";
		echo "</select>";
		echo "</td>";
		echo "</tr>";

		
	}

	function action_taken($r_assess){ //form must be auto-fill based on the previous items
		$action = $refer_date = $assess_by;

		if(!empty($_POST["sel_action_taken"])):
			$action = $_POST["sel_action_taken"];
		elseif(!empty($r_assess["action_taken"])):
			$action = $r_assess["action_taken"];
		else:
		endif;
		
		if($action=='refer'):
			$refer = 'SELECTED';
		elseif($action=='healthinfo'):
			$healthinfo = 'SELECTED';
		else:
			$nada = 'SELECTED';
		endif;

		if(!empty($_POST["txt_refer_date"])):
			$refer_date = $_POST["txt_refer_date"];
		elseif(!empty($r_assess["date_referral"])):
			list($y,$m,$d) = explode('-',$r_assess["date_referral"]);
			$refer_date = $m.'/'.$d.'/'.$y;
		
		else:
		endif;

		if(!empty($_POST["sel_user"])):
			$assess_by = $_POST["sel_user"];
		elseif(!empty($r_assess["referred_by"])):
			$assess_by = $r_assess["referred_by"];
		else:
		endif;

		$q_user = mysql_query("SELECT user_id, user_lastname, user_firstname FROM game_user WHERE user_active='Y'ORDER by user_lastname ASC, user_firstname ASC") or die("Cannot query 350: ".mysql_error());


		echo "<tr><td colspan='2'>Action Taken</td><td>";
		echo "<select name='sel_action_taken' size='1'>";
		echo "<option value='' $nada>----Select Action Taken----</option>";
		echo "<option value='refer' $refer>Referred to the health center</option>";
		echo "<option value='healthinfo' $healthinfo>Given Health Information</option>";
		echo "</select>";

		echo "</td>";
		echo "</tr>";

		echo "<tr><td colspan='2'>If referred, indicate the date and time of referral (mm/dd/yyyy)</td><td>";
		echo "<input type='text' name='txt_refer_date' size='11' maxlength='10' value='$refer_date'>&nbsp;";
		echo "<a href=\"javascript:show_calendar4('document.form_ncd_assessment.txt_refer_date', document.form_ncd_assessment.txt_refer_date.value);\"><img src='../images/cal.gif' width='16' height='16' border='0' alt='Click Here to Pick up the date'></a>";

		echo "</input>";
		echo "</td>";
		echo "</tr>";

		echo "<tr><td colspan='2'>Assessment done by</td><td>";
		echo "<select name='sel_user' size='1'>";
		echo "<option value=''>--- SELECT ASSESSING STAFF ---</option>";
		while($r_user = mysql_fetch_array($q_user)){
			if($assess_by == $r_user["user_id"]):
				echo "<option value='$r_user[user_id]' SELECTED>$r_user[user_lastname], $r_user[user_firstname]</option>";
			else:
				echo "<option value='$r_user[user_id]'>$r_user[user_lastname], $r_user[user_firstname]</option>";
			endif;
		}
		
		echo "</select>";
		echo "</td>";
		echo "</tr>";
	}


	function form_risk_screening(){
		echo "<a name='rs' />";
		echo "<form action='$_SERVER[PHP_SELF]?page=$_GET[page]&menu_id=$_GET[menu_id]&consult_id=$_GET[consult_id]&ptmenu=$_GET[ptmenu]&module=$_GET[module]&ncd=SCREENING&ncd_id=$_GET[ncd_id]&ncd_consult=$_GET[ncd_consult]#rs' method='POST' name='form_ncd_screening'>";

		$this->set_screen_date();
		$this->form_diabetes_presence();
		$this->form_blood_glucose();
		$this->form_blood_lipid();
		$this->form_urine_protein();
		$this->form_urine_ketone();

		echo "</form>";
	}

	function form_diabetes_presence(){
		$q_diabetes = mysql_query("SELECT  * FROM m_consult_ncd_risk_screen_diabetes WHERE consult_risk_id='$_GET[ncd_consult]' AND ncd_id='$_GET[ncd_id]'") or die("Cannot query 737: ".mysql_error());

		$arr_presence_resp = array("Y"=>"Yes","N"=>"No","D"=>"Do not know");
		$arr_med = array(""=>"N/A","med"=>"With medications","nomed"=>"Without medications");
		$arr_yn = array("Y"=>"Yes","N"=>"No"); 
		//consult_risk_id, ncd_id, patient_id, consult_id, presence_diabetes, medications, polyphagia, polydipsia, polyuria, recorded_by, last_updated

		$r_diabetes = mysql_fetch_array($q_diabetes);
		//print_r($r_diabetes);
		

		echo "<br>";
		echo "<table bgcolor='#66FF66'>";
		echo "<tr><td colspan='2' align='center' bgcolor='#339966' class='whitetext'>Presence or absence of diabetes</td></tr>";
		echo "<tr>";
		echo "<td class='boxtitle'>1. Was the patient diagnosed as having diabetes?</td>";
		echo "<td><select size='1' name='sel_presence_diabetes'>";

		foreach($arr_presence_resp as $key=>$value){
			if($r_diabetes["presence_diabetes"]==$key):
				echo "<option value='$key' SELECTED>$value</option>";
			else:
				echo "<option value='$key'>$value</option>";
			endif;
		}
		//echo "<option value='Y'>Yes</option>";
		//echo "<option value='N'>No</option>";
		//echo "<option value='D'>Do not know</option>";
		echo "</select></td>";
		echo "</tr>";
		
		
		echo "<tr>";
		echo "<td align='right' class='boxtitle'>If Yes,</td>";
		echo "<td><select size='1' name='sel_medications'>";

		foreach($arr_med as $key=>$value){
			if($r_diabetes["medications"]==$key):
				echo "<option value='$key' SELECTED>$value</option>";
			else:
				echo "<option value='$key'>$value</option>";
			endif;
		}
		
		//echo "<option value='med'>With medications</option>";
		//echo "<option value='nomed'>Without medications</option>";
		echo "</select></td>";
		echo "</tr>";

		echo "<tr valign='top'>";
		echo "<td class='boxtitle'>2. Does the patient have the following symptoms?</td>";
		
		echo "<td >";
		echo "<table>";
		echo "<tr><td class='boxtitle'>Polyphagia</td><td><select size='1' name='sel_polyphagia'>";
		foreach($arr_yn as $key=>$value){
			if($r_diabetes["polyphagia"]==$key):
				echo "<option value='$key' SELECTED>$value</option>";
			else:
				echo "<option value='$key'>$value</option>";
			endif;
		}

		//echo "<option value='Y'>Yes</option>";
		//echo "<option value='N' SELECTED>No</option>";
		echo "</select></td>";
		echo "</tr>";

		echo "<tr><td class='boxtitle'>Polydipsia</td><td><select size='1' name='sel_polydipsia'>";
		foreach($arr_yn as $key=>$value){
			if($r_diabetes["polydipsia"]==$key):
				echo "<option value='$key' SELECTED>$value</option>";
			else:
				echo "<option value='$key'>$value</option>";
			endif;
		}

		echo "</select></td>";
		echo "</tr>";

		echo "<tr><td class='boxtitle'>Polyuria</td><td><select size='1' name='sel_polyuria'>";
		foreach($arr_yn as $key=>$value){
			if($r_diabetes["polyuria"]==$key):
				echo "<option value='$key' SELECTED>$value</option>";
			else:
				echo "<option value='$key'>$value</option>";
			endif;
		}
		echo "</select></td>";
		echo "</tr>";

		echo "</table>";

		echo "</td></tr>";

		echo "<tr><td colspan='2' align='center'>";
		echo "<input type='submit' name='submit_risk_screening' value='Save Presence of Diabetes Details'></input>";
		echo "</td>";
		echo "</tr>";

		echo "</table>";
	}

	function update_presence_diabetes(){
		$pxid = healthcenter::get_patient_id($_GET["consult_id"]);
		$arr_glucose = array();

		$q_check_diabetes = mysql_query("SELECT consult_risk_id FROM m_consult_ncd_risk_screen_diabetes WHERE consult_risk_id='$_GET[ncd_consult]' AND ncd_id='$_GET[ncd_id]'") or die("Cannot query 794: ".mysql_error());

		if($_POST["sel_presence_diabetes"]=='N' && $_POST["sel_medications"]!=""):
			echo "<script language='Javascript'>";
			echo "window.alert('If the patient is not diagnosed with diabetes, please select N/A option.')";
			echo "</script>";

		elseif($_POST["sel_presence_diabetes"]=='Y' && $_POST["sel_medications"]==""):
			echo "<script language='Javascript'>";
			echo "window.alert('Please indicate if the diabetic patient is having medication or not.')";
			echo "</script>";
		else:

		if(mysql_num_rows($q_check_diabetes)==0):
			$q_insert_diabetes = mysql_query("INSERT INTO m_consult_ncd_risk_screen_diabetes SET consult_risk_id='$_GET[ncd_consult]',ncd_id='$_GET[ncd_id]',patient_id='$pxid',consult_id='$_GET[consult_id]',presence_diabetes='$_POST[sel_presence_diabetes]',medications='$_POST[sel_medications]',polyphagia='$_POST[sel_polyphagia]',polydipsia='$_POST[sel_polydipsia]',polyuria='$_POST[sel_polyuria]',recorded_by='$_SESSION[userid]',last_updated=NOW()") or die("Cannot query 966: ".mysql_error());

		else:
			$q_insert_diabetes = mysql_query("UPDATE m_consult_ncd_risk_screen_diabetes SET presence_diabetes='$_POST[sel_presence_diabetes]',medications='$_POST[sel_medications]',polyphagia='$_POST[sel_polyphagia]',polydipsia='$_POST[sel_polydipsia]',polyuria='$_POST[sel_polyuria]',recorded_by='$_SESSION[userid]',last_updated=NOW() WHERE consult_risk_id='$_GET[ncd_consult]' AND ncd_id='$_GET[ncd_id]'") or die("Cannot query 969: ".mysql_error());
		endif;

		if($q_insert_diabetes): //echo $_POST["sel_polyphagia"];
			array_push($arr_glucose,$_POST["sel_polyphagia"],$_POST["sel_polydipsia"],$_POST["sel_polyuria"]);
			//print_r($arr_glucose);

			$arr_count = array_count_values($arr_glucose);
			

			echo "<script language='Javascript'>";
				if($arr_count["Y"]==2):
					echo "window.alert('The client is advised to undergo blood glucose test due to the presence of 2 or more conditions (polyphagia, polydipsia, polyuria). Information was successfully been saved.')";
				else:
					echo "window.alert('Information was successfully been saved.')";
				endif;
			echo "</script>";
			//echo 'yes';
		else:
			echo 'no';
		endif;


		endif;
	}

	function form_blood_glucose(){
		$q_bgt = mysql_query("SELECT fbs,rbs, raised_blood_glucose, date_format(date_taken, '%m/%d/%Y') as taken_date FROM m_consult_ncd_risk_screen_glucose WHERE consult_id='$_GET[consult_id]' AND consult_risk_id='$_GET[ncd_consult]'") or die("Cannot query 872: ".mysql_error());

		$r_bgt = mysql_fetch_array($q_bgt);

		echo "<br>";
		echo "<table  bgcolor='#66FF66'>";
		echo "<tr><td colspan='2' align='center' bgcolor='#339966' class='whitetext'>Blood Glucose Test</td></tr>";
		
		echo "<tr><td colspan='2' class='boxtitle'>Ask for the client's last meal:</td></tr>";
		echo "<tr>";
		echo "<td class='boxtitle'>If at least 8 hours, record FBS (mg/dl)</td>";
		echo "<td>";
		echo "<input type='text' name='txt_fbs' size='3'  value='$r_bgt[fbs]' /> ";
		echo "</td>";
		echo "</tr>";

		echo "<tr>";
		echo "<td class='boxtitle'>If regardless when was the last meal, record RBS (mg/dl)</td>";
		echo "<td>";
		echo "<input type='text' name='txt_rbs' size='3'  value='$r_bgt[rbs]' />";
		echo "</td>";
		echo "</tr>";

		echo "<tr>";
		echo "<td class='boxtitle'>Raised Blood Glucose<br>(Yes if FBS>=126 mg/dl OR RBS >= 200mg/dl)</td>";
		echo "<td><b>";

		//echo "<select size='1' name='sel_raised_bp'>";
		//echo "<option value='Y'>Yes</option>";
		//echo "<option value='N' SELECTED>No</option>";
		//echo "</select>";

		echo $r_bgt["raised_blood_glucose"];

		echo "</b></td>";
		echo "</tr>";

		echo "<tr>";
		echo "<td class='boxtitle' align='right'>Date Taken</td>";
		echo "<td>";
		echo "<input type='text' name='txt_bg_taken' size='11' maxlength='10' value='$r_bgt[taken_date]'>";
		echo "<a href=\"javascript:show_calendar4('document.form_ncd_screening.txt_bg_taken', document.form_ncd_screening.txt_bg_taken.value);\"><img src='../images/cal.gif' width='16' height='16' border='0' alt='Click Here to Pick up the date'></a>";
		echo "</input>";
		echo "</td>";
		echo "</tr>";
		
		echo "<tr><td colspan='2' align='center'>";
		echo "<input type='submit' name='submit_risk_screening' value='Save Blood Glucose Test Details'></input>";

		echo "</td>";
		echo "</tr>";

		echo "</table>";
	}

	function update_blood_glucose(){
		
		$pxid = healthcenter::get_patient_id($_GET["consult_id"]);

		if(empty($_POST["txt_fbs"]) && empty($_POST["txt_rbs"])):
			echo "<script language='Javascript'>";
			echo "window.alert('Please supply either the FBS or RBS.')";
			echo "</script>";
		elseif(!empty($_POST["txt_fbs"]) && !empty($_POST["txt_rbs"])):
			echo "<script language='Javascript'>";
			echo "window.alert('Please supply either FBS (if the last meal >= 8 hours) or RBS (regardless of when was the last meal taken) and NOT BOTH.')";
			echo "</script>";
		elseif(empty($_POST["txt_bg_taken"])):
			echo "<script language='Javascript'>";
			echo "window.alert('Please supply the supply the where the blood glucose test was taken.')";
			echo "</script>";
		else: // all is complete

			list($m,$d,$y) = explode('/',$_POST["txt_bg_taken"]);
			$date_taken = $y.'-'.$m.'-'.$d;


			if($_POST["txt_fbs"] >= 126 || $_POST["txt_rbs"] >= 200):
				$raised_bgt = 'Y';
			else:
				$raised_bgt = 'N';
			endif;
			$q_bgt = mysql_query("SELECT consult_risk_id FROM m_consult_ncd_risk_screen_glucose WHERE consult_id='$_GET[consult_id]' AND consult_risk_id='$_GET[ncd_consult]'") or die("Cannot query 935: ".mysql_error());

			if(mysql_num_rows($q_bgt)!=0):
				$q_update_bgt = mysql_query("UPDATE m_consult_ncd_risk_screen_glucose SET fbs='$_POST[txt_fbs]',rbs='$_POST[txt_rbs]',raised_blood_glucose='$raised_bgt',date_taken='$date_taken',recorded_by='$_SESSION[userid]' WHERE consult_risk_id='$_GET[ncd_consult]' AND ncd_id='$_GET[ncd_id]' AND patient_id='$pxid'") or die("Cannot query 957: ".mysql_error());
			else:
				$q_update_bgt = mysql_query("INSERT INTO m_consult_ncd_risk_screen_glucose SET consult_risk_id='$_GET[ncd_consult]', ncd_id='$_GET[ncd_id]',consult_id='$_GET[consult_id]',patient_id='$pxid',fbs='$_POST[txt_fbs]',rbs='$_POST[txt_rbs]',raised_blood_glucose='$raised_bgt',date_taken='$date_taken',recorded_by='$_SESSION[userid]'") or die("Cannot query 959: ".mysql_error()); 
			endif;

			if($q_update_bgt):
				if($raised_bgt=='Y'):
					echo "<script language='Javascript'>";
					echo "window.alert('The client has a RAISED BLOOD GLUCOSE. Please perform Urine Test for Ketones.')";
					echo "</script>";
				endif;
			endif;
			
		endif;

	}

	
	function form_blood_lipid(){
		$arr_lipid = array();
		$q_lipid = mysql_query("SELECT total_cholesterol,raised_lipid, date_format(date_taken,'%m/%d/%Y') as taken_date FROM m_consult_ncd_risk_screen_lipid WHERE consult_risk_id='$_GET[ncd_consult]' AND consult_id='$_GET[consult_id]'") or die("Cannot query 1030: ".mysql_error());
		
		$arr_raised = array("N"=>"No","Y"=>"Yes");

		$r_lipid = mysql_fetch_array($q_lipid);

		echo "<br>";
		echo "<table bgcolor='#66FF66'>";
		echo "<tr><td colspan='2' align='center' bgcolor='#339966' class='whitetext'>Blood Lipid Test</td></tr>";
		
		
		echo "<tr>";
		echo "<td class='boxtitle'>Total cholesterol (mmol/l)</td>";
		echo "<td>";
		echo "<input type='text' name='txt_cholesterol' size='7' value='$r_lipid[total_cholesterol]' /> ";
		echo "</td>";
		echo "</tr>";

		echo "<tr>";
		echo "<td class='boxtitle'>Raised Blood Lipids<br>(Yes if blood cholesterol is more than or equal to 200mg/100ml)</td>";
		echo "<td><select size='1' name='sel_raised_cholesterol'>";
		foreach($arr_raised as $key=>$value){
			if($key==$r_lipid["raised_lipid"]):
				echo "<option value='$key' SELECTED>$value</option>";
			else:
				echo "<option value='$key'>$value</option>";
			endif;
		}
		echo "</select></td>";
		echo "</tr>";

		echo "<tr>";
		echo "<td class='boxtitle' align='right'>Date Taken</td>";
		echo "<td>";
		echo "<input type='text' name='txt_cholesterol_taken' size='11' maxlength='10' value='$r_lipid[taken_date]'></input>";
		echo "<a href=\"javascript:show_calendar4('document.form_ncd_screening.txt_cholesterol_taken', document.form_ncd_screening.txt_cholesterol_taken.value);\"><img src='../images/cal.gif' width='16' height='16' border='0' alt='Click Here to Pick up the date'></a>";
		
		echo "</td>";
		echo "</tr>";
		
		echo "<tr><td colspan='2' align='center'>";
		echo "<input type='submit' name='submit_risk_screening' value='Save Blood Lipid Test Details'></input>";


		echo "</td>";
		echo "</tr>";

		echo "</table>";

	}

	function update_lipid(){
		//print_r($_POST);

		$pxid = healthcenter::get_patient_id($_GET["consult_id"]);
		list($m,$d,$y) = explode('/',$_POST["txt_cholesterol_taken"]);
		$date_taken = $y.'-'.$m.'-'.$d;

		if(empty($_POST["txt_cholesterol"])):
			echo "<script language='Javascript'>";
			echo "window.alert('Please supply value for the TOTAL CHOLESTEROL.')";
			echo "</script>";
		elseif(empty($_POST["txt_cholesterol_taken"])):
			echo "<script language='Javascript'>";
			echo "window.alert('Please supply value for the date where the cholesterol level was taken.')";
			echo "</script>";
		else:
			$q_lipid = mysql_query("SELECT consult_risk_id FROM m_consult_ncd_risk_screen_lipid WHERE consult_risk_id='$_GET[ncd_consult]' AND consult_id='$_GET[consult_id]'") or die("Cannot query 1030: ".mysql_error());

			if(mysql_num_rows($q_lipid)!=0): 
				$q_update_lipid = mysql_query("UPDATE m_consult_ncd_risk_screen_lipid SET total_cholesterol='$_POST[txt_cholesterol]',raised_lipid='$_POST[sel_raised_cholesterol]',date_taken='$date_taken',taken_by='$_SESSION[userid]' WHERE ncd_id='$_GET[ncd_id]' AND consult_risk_id='$_GET[ncd_consult]'") or die("Cannot query 1039: ".mysql_error());
			else:
				$q_update_lipid = mysql_query("INSERT INTO m_consult_ncd_risk_screen_lipid SET total_cholesterol='$_POST[txt_cholesterol]',raised_lipid='$_POST[sel_raised_cholesterol]',date_taken='$date_taken', ncd_id='$_GET[ncd_id]',consult_risk_id='$_GET[ncd_consult]',consult_id='$_GET[consult_id]',patient_id='$pxid',taken_by='$_SESSION[userid]'") or die("Cannot query 1041: ".mysql_error());
			endif;

			if($q_update_lipid):
				echo "<script language='Javascript'>";
				echo "window.alert('Blood Lipid Test details was successfully been saved.')";
				echo "</script>";
			endif;
		endif;

	}
	
	function form_urine_protein(){

		$q_protein = mysql_query("SELECT protein_id,protein_value FROM m_lib_ncd_urine_protein ORDER by protein_seq ASC") or die("Cannot query 533: ".mysql_error());

		$q_consult_protein  = mysql_query("SELECT protein_id,presence_urine_protein,date_format(date_taken,'%m/%d/%Y') as taken_date FROM m_consult_ncd_risk_screen_protein WHERE consult_risk_id='$_GET[ncd_consult]' AND ncd_id='$_GET[ncd_id]'") or die("Cannot query 1067: ".mysql_error());
		$r_protein = mysql_fetch_array($q_consult_protein);
		//print_r($r_protein);

		echo "<br>";
		echo "<table bgcolor='#66FF66'>";
		echo "<tr><td colspan='2' align='center' bgcolor='#339966' class='whitetext'>Urine Test for Protein</td></tr>";
		
		
		echo "<tr>";
		echo "<td class='boxtitle'>Urine Protein in g/L</td>";
		echo "<td><select size='1' name='sel_urine_protein'>";
		while(list($protein_id,$protein_value)=mysql_fetch_array($q_protein)){
			if($r_protein["protein_id"]==$protein_id):
				echo "<option value='$protein_id' SELECTED>$protein_value</option>";
			else:
				echo "<option value='$protein_id'>$protein_value</option>";
			endif;
		}
		echo "</select></td>";
		echo "</tr>";

		echo "<tr>";
		echo "<td class='boxtitle'>Presence of Urine Protein <br>(Yes if there is traces or amount of urine protein is found)</td>";
		echo "<td>";
		echo "<b>".$r_protein["presence_urine_protein"]."</b>";
		//echo "<select name='1' size='sel_urine_protein'>";
		//echo "<option value='Y'>Yes</option>";
		//echo "<option value='N' SELECTED>No</option>";
		//echo "</select></td>";
		echo "</td></tr>";

		echo "<tr>";
		echo "<td class='boxtitle' align='right'>Date Taken</td>";
		echo "<td>";
		echo "<input type='text' name='txt_urine_protein_taken' size='11' maxlength='10' value='$r_protein[taken_date]'>";
		echo "<a href=\"javascript:show_calendar4('document.form_ncd_screening.txt_urine_protein_taken', document.form_ncd_screening.txt_urine_protein_taken.value);\"><img src='../images/cal.gif' width='16' height='16' border='0' alt='Click Here to Pick up the date'></a>";
		echo "</input>";
		echo "</td>";
		echo "</tr>";
		
		echo "<tr><td colspan='2' align='center'>";
		echo "<input type='submit' name='submit_risk_screening' value='Save Urine Test for Protein Details'></input>";
		echo "</td>";
		echo "</tr>";

		echo "</table>";
	}


	function update_urine_protein(){
		//print_r($_POST);

		$pxid = healthcenter::get_patient_id($_GET["consult_id"]);

		list($m,$d,$y) = explode('/',$_POST["txt_urine_protein_taken"]);
		$date_taken = $y.'-'.$m.'-'.$d;

		if(empty($_POST["txt_urine_protein_taken"])):
			echo "<script language='Javascript'>";
			echo "window.alert('Please supply the date Urine Protein was taken.')";
			echo "</script>";
		
		else:
			if($_POST["sel_urine_protein"]!='1'):
				$presence_urine = 'Y';
			else:
				$presence_urine = 'N';
			endif;

			$q_protein = mysql_query("SELECT consult_risk_id FROM m_consult_ncd_risk_screen_protein WHERE consult_risk_id='$_GET[ncd_consult]' AND consult_id='$_GET[consult_id]'") or die("Cannot query 1030: ".mysql_error());

			if(mysql_num_rows($q_protein)!=0): 
				$q_update_protein = mysql_query("UPDATE m_consult_ncd_risk_screen_protein SET protein_id='$_POST[sel_urine_protein]',presence_urine_protein='$presence_urine',date_taken='$date_taken',taken_by='$_SESSION[userid]' WHERE ncd_id='$_GET[ncd_id]' AND consult_risk_id='$_GET[ncd_consult]'") or die("Cannot query 1039: ".mysql_error());
			else:
				$q_update_protein = mysql_query("INSERT INTO m_consult_ncd_risk_screen_protein SET protein_id='$_POST[sel_urine_protein]',presence_urine_protein='$presence_urine',date_taken='$date_taken',taken_by='$_SESSION[userid]',ncd_id='$_GET[ncd_id]',consult_risk_id='$_GET[ncd_consult]',consult_id='$_GET[consult_id]',patient_id='$pxid'") or die("Cannot query 1041: ".mysql_error());
			endif;

			if($q_update_protein):
				echo "<script language='Javascript'>";
				if($presence_urine=='Y'):
					echo "window.alert('There is a presence of urine protein. Test for Urine Protein details was successfully been saved.')";
				else:
					echo "window.alert('There is no presence of urine protein. Test for Urine Protein details was successfully been saved.')";
				endif;

				echo "</script>";
			endif;
		endif;


	}

	
	function form_urine_ketone(){

		$q_ketone = mysql_query("SELECT ketone_id,ketone_value FROM m_lib_ncd_urine_ketones ORDER by ketone_seq ASC") or die("Cannot query 533: ".mysql_error());

		$q_consult_ketone = mysql_query("SELECT ketone_id,presence_urine_ketone,date_format(date_taken,'%m/%d/%Y') as taken_date FROM m_consult_ncd_risk_screen_ketones WHERE consult_risk_id='$_GET[ncd_consult]' AND ncd_id='$_GET[ncd_id]' AND consult_risk_id='$_GET[ncd_consult]'") or die("Cannot query 1169: ".mysql_error());
		
		$r_ketone = mysql_fetch_array($q_consult_ketone);
		$arr_resp = array("N"=>"No","Y"=>"Yes");

		echo "<br>";
		echo "<table bgcolor='#66FF66'>";
		echo "<tr><td colspan='2' align='center' bgcolor='#339966' class='whitetext'>Urine Test for Ketones</td></tr>";
		
		echo "<tr>";
		echo "<td class='boxtitle'>Urine Ketone</td>";
		echo "<td><select size='1' name='sel_urine_ketone'>";
		while(list($ketone_id,$ketone_value)=mysql_fetch_array($q_ketone)){
			if($ketone_id==$r_ketone["ketone_id"]):
				echo "<option value='$ketone_id' SELECTED>$ketone_value</option>";
			else:
				echo "<option value='$ketone_id'>$ketone_value</option>";
			endif;
		}
		
		echo "</select></td>";
		echo "</tr>";

		echo "<tr>";
		echo "<td class='boxtitle'>Presence of Urine Ketone <br>(Yes if there is traces or amount of urine protein is found)</td>";
		echo "<td>";
		echo "<b>".$r_ketone["presence_urine_ketone"]."</b>";
		echo "</td>";
		echo "</tr>";

		echo "<tr>";
		echo "<td  align='right' class='boxtitle'>Date Taken</td>";
		echo "<td>";
		echo "<input type='text' name='txt_urine_ketones_taken' size='11' maxlength='10' value='$r_ketone[taken_date]'>";
		echo "<a href=\"javascript:show_calendar4('document.form_ncd_screening.txt_urine_ketones_taken', document.form_ncd_screening.txt_urine_ketones_taken.value);\"><img src='../images/cal.gif' width='16' height='16' border='0' alt='Click Here to Pick up the date'></a>";
		echo "</input>";
		echo "</td>";
		echo "</tr>";
		
		echo "<tr><td colspan='2' align='center'>";
		echo "<input type='submit' name='submit_risk_screening' value='Save Urine Test for Ketones Details'></input>";
		echo "</td>";
		echo "</tr>";

		echo "</table>";
	}

	function update_urine_ketones(){
		//print_r($_POST);

		$pxid = healthcenter::get_patient_id($_GET["consult_id"]);

		list($m,$d,$y) = explode('/',$_POST["txt_urine_ketones_taken"]);
		$date_taken = $y.'-'.$m.'-'.$d;

		if(empty($_POST["txt_urine_ketones_taken"])):
			echo "<script language='Javascript'>";
			echo "window.alert('Please supply the date Urine Ketones was taken.')";
			echo "</script>";
		else:
			if($_POST["sel_urine_ketone"]!='1'):
				$presence_ketones = 'Y';
			else:
				$presence_ketones = 'N';
			endif;

			$q_ketones = mysql_query("SELECT consult_risk_id FROM m_consult_ncd_risk_screen_ketones WHERE ncd_id='$_GET[ncd_id]' AND consult_risk_id='$_GET[ncd_consult]'") or die("Cannot query 1227: ".mysql_error());

			if(mysql_num_rows($q_ketones)!=0):
				$q_update_ketones = mysql_query("UPDATE m_consult_ncd_risk_screen_ketones SET ketone_id='$_POST[sel_urine_ketone]',presence_urine_ketone='$presence_ketones',date_taken='$date_taken',taken_by='$_SESSION[userid]' WHERE ncd_id='$_GET[ncd_id]' AND consult_risk_id='$_GET[ncd_consult]'") or die("Cannot query 1039: ".mysql_error());
			else:
				$q_update_ketones = mysql_query("INSERT INTO m_consult_ncd_risk_screen_ketones SET ketone_id='$_POST[sel_urine_ketone]',presence_urine_ketone='$presence_ketones',date_taken='$date_taken',taken_by='$_SESSION[userid]',ncd_id='$_GET[ncd_id]',consult_risk_id='$_GET[ncd_consult]',consult_id='$_GET[consult_id]',patient_id='$pxid'") or die("Cannot query 1041: ".mysql_error());
			endif;


			if($q_update_ketones):
				echo "<script language='Javascript'>";
				if($presence_ketones=='Y'):
					echo "window.alert('There is a presence of urine ketone. Test for Urine Protein details was successfully been saved.')";
				else:
					echo "window.alert('There is no presence of urine ketone. Test for Urine Protein details was successfully been saved.')";
				endif;

				echo "</script>";
			endif;
		endif;

	}


	function form_questionnaire_tia(){
		$arr_yn = array(""=>"N/A","Y"=>"Yes","N"=>"No");

		$q_ques = mysql_query("SELECT * FROM m_consult_ncd_risk_questionnaire WHERE ncd_id='$_GET[ncd_id]' AND consult_risk_id='$_GET[ncd_consult]'") or die("Cannot query 1285: ".mysql_error());

		$r_ques = mysql_fetch_array($q_ques);
		//print_r($r_ques);

		echo "<br>";
		echo "<a name='ques' />";

		echo "<form action='$_SERVER[PHP_SELF]?page=$_GET[page]&menu_id=$_GET[menu_id]&consult_id=$_GET[consult_id]&ptmenu=$_GET[ptmenu]&module=$_GET[module]&ncd=QUESTIONNAIRE&ncd_id=$_GET[ncd_id]&ncd_consult=$_GET[ncd_consult]#ques' name='form_ncd_questionnaire' method='POST'>";
		echo "<table bgcolor='#66FF66'>";
		echo "<tr><td colspan='2' align='center' bgcolor='#339966' class='whitetext'>Questionnaire to Determine Probable Angina, Heart Attack, Stroke or Transient Ischemic Attack</td></tr>";
		echo "<tr>";
		echo "<td class='boxtitle'>Angina or Heart Attack</td>";
		echo "<td>";

		echo "<b>".$r_ques["angina_heart_attack"]."</b>";

		/*echo "<select size='1' name='sel_angina'>";
		echo "<option value='Y'>Yes</option>";
		echo "<option value='N' SELECTED>No</option>";
		echo "</select>"; */

		echo "</td>";
		echo "</tr>";

		echo "<tr><td colspan='2'><hr></tr>";

		echo "<tr>";
		echo "<td class='boxtitle'>1. Have you had any pain or discomfort or any pressure or heaviness in your chest?<br>Nakakaramdam ka ba ng pananakit o kabigatan ng iyong dibdib?</td>";
		echo "<td valign='top'><select size='1' name='sel_tia_one' onchange='check_ncd_tia(1,this)'>";
		foreach($arr_yn as $key=>$value){
			if($_POST["sel_tia_one"]==$key || $r_ques["ques1"]==$key ):
				echo "<option value='$key' SELECTED>$value</option>";
			else:
				echo "<option value='$key'>$value</option>";
			endif;
		}
		//echo "<option value='Y'>Yes</option>";
		//echo "<option value='N' SELECTED>No</option>";
		echo "</select></td>";
		echo "</tr>";

		echo "<tr><td colspan='2'><hr></tr>";

		echo "<tr>";
		echo "<td class='boxtitle'>2. Do you get pain in the center of the chest or left chest or left arm?<br>Ang sakit ba ay nasa gitna ng dibdib, sa kaliwang bahagi ng dibdib o sa kaliwang braso?</td>";
		echo "<td valign='top'><select size='1' name='sel_tia_two' onchange='check_ncd_tia(2,this)'>";
		foreach($arr_yn as $key=>$value){
			if($_POST["sel_tia_two"]==$key || $r_ques["ques2"]==$key ):
				echo "<option value='$key' SELECTED>$value</option>";
			else:
				echo "<option value='$key'>$value</option>";
			endif;

		}
		//echo "<option value='Y'>Yes</option>";
		//echo "<option value='N' SELECTED>No</option>";
		echo "</select></td>";
		echo "</tr>";

		echo "<tr><td colspan='2'><hr></tr>";

		echo "<tr>";
		echo "<td class='boxtitle'>3. Do you get it when you walk uphill or hurry?<br>Nararamdaman mo ba ito kung ikaw ay nagmamadali o naglalakad nang mabilis o paakyat?</td>";
		echo "<td valign='top'><select size='1' name='sel_tia_three'>";
		foreach($arr_yn as $key=>$value){
			if($_POST["sel_tia_three"]==$key || $r_ques["ques3"]==$key ):
				echo "<option value='$key' SELECTED>$value</option>";
			else:
				echo "<option value='$key'>$value</option>";
			endif;
		}		
		//echo "<option value='Y'>Yes</option>";
		//echo "<option value='N' SELECTED>No</option>";
		echo "</select></td>";
		echo "</tr>";

		echo "<tr><td colspan='2'><hr></tr>";

		echo "<tr>";
		echo "<td class='boxtitle'>4. Do you sit down if you get the pain while walking?<br>Tumitigil ka ba sa paglalakad kapag sumakit ang iyong dibdib?</td>";
		echo "<td valign='top'><select size='1' name='sel_tia_four'>";
		foreach($arr_yn as $key=>$value){
			if($_POST["sel_tia_four"]==$key || $r_ques["ques4"]==$key ):
				echo "<option value='$key' SELECTED>$value</option>";
			else:
				echo "<option value='$key'>$value</option>";
			endif;
		}
		//echo "<option value='Y'>Yes</option>";
		//echo "<option value='N' SELECTED>No</option>";
		echo "</select></td>";
		echo "</tr>";

		echo "<tr><td colspan='2'><hr></tr>";

		echo "<tr>";
		echo "<td class='boxtitle'>5. Does the pain go away if you stand still or if you take a tablet under the tongue?<br>Nawawala ba ang sakit kapag ikaw ay di kumilos o kapag naglalagay ka ng gamot sa ilalim ng iyong dila?</td>";
		echo "<td valign='top'><select size='1' name='sel_tia_five'>";
		foreach($arr_yn as $key=>$value){
			if($_POST["sel_tia_five"]==$key || $r_ques["ques5"]==$key ):
				echo "<option value='$key' SELECTED>$value</option>";
			else:
				echo "<option value='$key'>$value</option>";
			endif;
		}
		//echo "<option value='Y'>Yes</option>";
		//echo "<option value='N' SELECTED>No</option>";
		echo "</select></td>";
		echo "</tr>";

		echo "<tr><td colspan='2'><hr></tr>";

		echo "<tr>";
		echo "<td class='boxtitle'>6. Does the pain go away in less than 10 minutes?<br>Nawawala ba ang sakit sa loob ng 10 minuto?</td>";
		echo "<td valign='top'><select size='1' name='sel_tia_six'>";
		foreach($arr_yn as $key=>$value){
			if($_POST["sel_tia_six"]==$key || $r_ques["ques6"]==$key ):
				echo "<option value='$key' SELECTED>$value</option>";
			else:
				echo "<option value='$key'>$value</option>";
			endif;
		}
		//echo "<option value='Y'>Yes</option>";
		//echo "<option value='N' SELECTED>No</option>";
		echo "</select></td>";
		echo "</tr>";

		echo "<tr><td colspan='2'><hr></tr>";

		echo "<tr>";
		echo "<td class='boxtitle'>7. Have you ever had a severe chest pain across the front of your chest lasting for half an hour or more?<br>Nakakaramdam ka ba ng pananakit ng dibdib na tumagal ng kalahating oras o higit pa?</td>";
		echo "<td valign='top'><select size='1' name='sel_tia_seven'>";
		foreach($arr_yn as $key=>$value){
			if($_POST["sel_tia_seven"]==$key || $r_ques["ques7"]==$key ):
				echo "<option value='$key' SELECTED>$value</option>";
			else:
				echo "<option value='$key'>$value</option>";
			endif;
		}
		//echo "<option value='Y'>Yes</option>";
		//echo "<option value='N' SELECTED>No</option>";
		echo "</select></td>";
		echo "</tr>";

		echo "<tr><td colspan='2'><hr></tr>";

		echo "<tr>";
		echo "<td colspan='2' class='boxtitle'>If the answer to Question 3 or 4 or 5 or 6 or 7 is YES, patient may have angina or heart attack and needs to see the doctor.</td>";
		echo "</tr>";

		echo "<tr><td colspan='2'><hr></tr>";

		echo "<tr>";
		echo "<td class='boxtitle'>Stroke and TIA</td>";
		echo "<td valign='top'>";

		echo "<b>".$r_ques["stroke_tia"]."</b>";
		//echo "<select size='1' name='sel_tia_seven'>";
		//foreach($arr_yn as $key=>$value){
		//	echo "<option value=$key>$value</option>";
		//}
		//echo "<option value='Y'>Yes</option>";
		//echo "<option value='N' SELECTED>No</option>";
		//echo "</select>";
		
		echo "</td>";
		echo "</tr>";

		echo "<tr><td colspan='2'><hr></tr>";

		echo "<tr>";
		echo "<td class='boxtitle'>8. Have you ever had any of the following: difficulty in talking, weakness of arm and/or legs on one side of the body or numbness on one side of the body?<br>Nakakaramdam ka ba ng mga sumusunod: hirap sa pagsasalita, panghiwa ng braso, at/o ng binti o pamamanhid sa kalahating bahagi ng katawan?</td>";
		echo "<td valign='top'><select size='1' name='sel_tia_eight'>";
		foreach($arr_yn as $key=>$value){
			if($_POST["sel_tia_eight"]==$key || $r_ques["ques8"]==$key):
				echo "<option value='$key' SELECTED>$value</option>";
			else:
				echo "<option value='$key'>$value</option>";
			endif;
		}
		//echo "<option value='Y'>Yes</option>";
		//echo "<option value='N' SELECTED>No</option>";
		echo "</select></td>";
		echo "</tr>";

		echo "<tr><td colspan='2'><hr></tr>";	


		echo "<tr>";
		echo "<td colspan='2' class='boxtitle'>If the answer to Question 8 is YES, the patient may have had a TIA or stroke and needs to see the doctor.</td>";
		echo "</tr>";

		echo "<tr>";
		echo "<td class='boxtitle' align='right'>Interviewed by</td>";
		echo "<td>";
		
		$q_user = mysql_query("SELECT user_id, user_lastname, user_firstname FROM game_user WHERE user_active='Y'ORDER by user_lastname ASC, user_firstname ASC") or die("Cannot query 1427: ".mysql_error());

		echo "<select name='sel_interview' value='1'>";
			echo "<option value=''>-- SELECT INTERVIEWER --</option>";
		while($r_user = mysql_fetch_array($q_user)){			
			if($r_ques["interviewed_by"]==$r_user["user_id"] || $_POST["sel_interview"]==$r_user["user_id"]):
				echo "<option value='$r_user[user_id]' SELECTED>$r_user[user_lastname], $r_user[user_firstname]</option>";
			else:
				echo "<option value='$r_user[user_id]'>$r_user[user_lastname], $r_user[user_firstname]</option>";
			endif;
		}
		echo "</select>";

		echo "</td>";
		echo "</tr>";

		echo "<tr><td colspan='2'><hr></tr>";	

		echo "<tr><td colspan='2' align='center'>";
		echo "<input type='submit' name='submit_interview' value='Save Questionnaire'></input>";
		echo "</td>";
		echo "</tr>";

		echo "</table>";

		echo "</form>";
	}


	function update_question(){
		$pxid = healthcenter::get_patient_id($_GET["consult_id"]);
		$update_flag = 0;
		
		$angina_heart_attack = $stroke_tia = 'N';

		if($_POST["sel_tia_one"]=='N' || $_POST["sel_tia_two"]=='N'):
			if($_POST["sel_tia_eight"]==""):
				echo "<script language='Javascript'>";
				echo "window.alert('Questionnaire not saved. You have answered NO in either Question 1 or 2. Please indicate a response for Question 8 (Stroke and TIA).')";
				echo "</script>";
			elseif($_POST["sel_tia_three"]!="" || $_POST["sel_tia_four"]!="" || $_POST["sel_tia_five"]!="" || $_POST["sel_tia_six"]!="" || $_POST["sel_tia_seven"]!=""):
				echo "<script language='Javascript'>";
				echo "window.alert('Questionnaire not saved. You have answered NO in either Question 1 or 2. Please indicate N/A for Questions 3-7.')";
				echo "</script>";
			else:
				$update_flag = 1;
			endif;
		else:
			$update_flag = 1;
		endif;
		
		if($update_flag):
			if($_POST["sel_tia_three"]=="Y" || $_POST["sel_tia_four"]=="Y" || $_POST["sel_tia_five"]=="Y" || $_POST["sel_tia_six"]=="Y" || $_POST["sel_tia_seven"]=="Y"):
				echo "<script language='Javascript'>";
				echo "window.alert('You have answered YES in any of the Questions 3-7. The patient may have had angina or heart attack and needs to see the doctor.')";
				echo "</script>";
				$angina_heart_attack = 'Y';

			elseif($_POST["sel_tia_one"]=='N' || $_POST["sel_tia_two"]=='N'):
				if($_POST["sel_tia_eight"]=="Y"):
					echo "<script language='Javascript'>";
					echo "window.alert('You have answered YES in Questions 8. The patient may have had stroke and TIA and needs to see the doctor.')";
					echo "</script>";
					
					$stroke_tia = 'Y';
				endif;
			else:
				echo "<script language='Javascript'>";
				echo "window.alert('The patient does not show any manifestations of angina, heart attack, stroke and TIA.')";
				echo "</script>";

			endif;
		
		
			$q_ques = mysql_query("SELECT consult_risk_id FROM m_consult_ncd_risk_questionnaire WHERE consult_risk_id='$_GET[ncd_consult]' AND ncd_id='$_GET[ncd_id]'") or die("Cannot query 1510: ".mysql_error());


			if(mysql_num_rows($q_ques)!=0):
				$q_update_ques = mysql_query("UPDATE m_consult_ncd_risk_questionnaire SET  patient_id='$pxid',consult_id='$_GET[consult_id]',ques1='$_POST[sel_tia_one]',ques2='$_POST[sel_tia_two]',ques3='$_POST[sel_tia_three]',ques4='$_POST[sel_tia_four]',ques5='$_POST[sel_tia_five]',ques6='$_POST[sel_tia_six]',ques7='$_POST[sel_tia_seven]',ques8='$_POST[sel_tia_eight]',angina_heart_attack='$angina_heart_attack',stroke_tia='$stroke_tia',interviewed_by='$_POST[sel_interview]',last_updated=NOW() WHERE consult_risk_id='$_GET[ncd_consult]' AND ncd_id='$_GET[ncd_id]'") or die("Cannot query 1514: ".mysql_error());
			else:
				$q_update_ques = mysql_query("INSERT INTO m_consult_ncd_risk_questionnaire SET consult_risk_id='$_GET[ncd_consult]',ncd_id='$_GET[ncd_id]',patient_id='$pxid',consult_id='$_GET[consult_id]',ques1='$_POST[sel_tia_one]',ques2='$_POST[sel_tia_two]',ques3='$_POST[sel_tia_three]',ques4='$_POST[sel_tia_four]',ques5='$_POST[sel_tia_five]',ques6='$_POST[sel_tia_six]',ques7='$_POST[sel_tia_seven]',ques8='$_POST[sel_tia_eight]',angina_heart_attack='$angina_heart_attack',stroke_tia='$stroke_tia',interviewed_by='$_POST[sel_interview]',last_updated=NOW()") or die("Cannot query 1516: ".mysql_error());
			endif;

			if($q_update_ques):
				echo "<script language='Javascript'>";
				echo "window.alert('The details of the questionnaire have been saved!')";
				echo "</script>";
			endif;


		endif;

	}


	function form_refer_patient(){

		$muncity_code = $_SESSION["datanode"]["code"];

		$q_units = mysql_query("SELECT facility_id, facility_name FROM m_lib_health_facility WHERE psgc_citymuncode='$muncity_code' ORDER BY facility_name ASC") or die("Cannot query 674: ".mysql_error());

		$q_to_units = mysql_query("SELECT facility_id, facility_name FROM m_lib_health_facility WHERE psgc_citymuncode='$muncity_code' ORDER BY facility_name ASC") or die("Cannot query 674: ".mysql_error());

		echo "<br>";

		echo "<table bgcolor='#66FF66'>";
		echo "<tr><td colspan='2' align='center' bgcolor='#339966' class='whitetext'>REFERRAL FORM</td></tr>";
		
		echo "<tr><td class='boxtitle'>Referring Unit</td><td><select name='sel_units' size='1'>";
		while(list($facility_id,$facility_name) = mysql_fetch_array($q_units)){
			echo "<option value='$facility_id'>$facility_name</option>";
		}
		echo "</select></td></tr>";

		echo "<tr><td class='boxtitle'>Referred To</td><td><select name='sel_to_units' size='1'>";
		while(list($facility_id,$facility_name) = mysql_fetch_array($q_to_units)){
			echo "<option value='$facility_id'>$facility_name</option>";
		}
		echo "</select></td></tr>";
		
		echo "<tr><td colspan='2'><hr></td></tr>";

		echo "<tr><td class='boxtitle'>Chief Complaint</td>";
		echo "<td>";
		echo "";
		echo "</td>";
		echo "</tr>";

		echo "<tr><td class='boxtitle'>History of Patient Illness</td>";
		echo "<td>";
		echo "";
		echo "</td>";
		echo "</tr>";

		echo "<tr><td colspan='2' class='boxtitle'>BP:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		echo "RR:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		echo "PR:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		echo "Temp:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		echo "</td>";
		echo "</tr>";
		
		echo "<tr><td colspan='2'><hr></td></tr>";

		echo "<tr><td valign='top' class='boxtitle'>Purpose of Referral / Initial Diagnosis</td>";
		echo "<td>";
		echo "<textarea name='txt_purpose' rows='5' cols='25'></textarea>";
		echo "</td>";
		echo "</tr>";

		echo "<tr><td valign='top' class='boxtitle'>Measures Taken by Referring Unit</td>";
		echo "<td>";
		echo "<textarea name='txt_measures_taken' rows='5' cols='25'></textarea>";
		echo "</td>";
		echo "</tr>";

		echo "<tr><td colspan='2' align='center'>";
		echo "<input type='submit' name='submit_refer' value='Send Referral'></input>";
		echo "</td>";
		echo "</tr>";

		echo "</table>";
	}

	function form_return_slip(){
		echo "<br>";
		echo "<table bgcolor='#66FF66'>";
		echo "<tr><td colspan='2' align='center' bgcolor='#339966' class='whitetext'>RETURN SLIP</td></tr>";
		
		echo "<tr><td class='boxtitle'>Referred To</td><td></td></tr>";

		echo "<tr><td class='boxtitle'>Referred From <br>(Name of Referral Facility)</td><td></td></tr>";

		echo "<tr><td colspan='2'><hr></td></tr>";
		
		
		echo "<tr><td valign='top' class='boxtitle'>Final Diagnosis</td>";
		echo "<td>";
		echo "<textarea name='txt_diagnosis' rows='5' cols='25'></textarea>";
		echo "</td>";
		echo "</tr>";

		echo "<tr><td valign='top' class='boxtitle'>Treatment Management</td>";
		echo "<td>";
		echo "<textarea name='txt_management' rows='5' cols='25'></textarea>";
		echo "</td>";
		echo "</tr>";

		

		echo "<tr><td colspan='2' align='center'>";
		echo "<input type='submit' name='submit_refer' value='Send Return Slip'></input>";
		echo "</td>";
		echo "</tr>";
		echo "</table>";

	}

	function check_assess_next(){
		$screen = '';
		$family_hx = array();

		$q_pxid = mysql_query("SELECT patient_id FROM m_consult WHERE consult_id='$_GET[consult_id]'") or die("Cannot query 905: ".mysql_error());
		list($patient_id) = mysql_fetch_row($q_pxid);
		
		list($m,$d,$y) = explode('/',$_POST["txt_assess_date"]);
		$date_screen = $y.'-'.$m.'-'.$d;

		$q_px_age = mysql_query("SELECT round((to_days('$date_screen')-to_days(patient_dob))/365 , 1) computed_age ,patient_gender FROM m_patient WHERE patient_id='$patient_id'") or die("Cannot query 909: ".mysql_error());

		list($computed_age,$patient_gender) = mysql_fetch_row($q_px_age);

		$q_red_flag_smoke = mysql_query("SELECT red_flag FROM m_lib_ncd_smoking_status WHERE smoking_id='$_POST[sel_smoking]'") or die("CAnnot query 915: ".mysql_error);
		list($smoke_red_flag) = mysql_fetch_row($q_red_flag_smoke);

		if(($patient_gender=='M') && ($_POST["txt_waist"]>=90)):
			$limit = 90;
			$central_adiposity_risk = 'Y';
		elseif(($patient_gender=='F') && ($_POST["txt_waist"]>=80)):
			$limit = 80;
			$central_adiposity_risk = 'Y';
		else:
			$central_adiposity_risk = 'N';
		endif;

		
		$systolic_ave = round((($_POST["txt_systolic_1st"] + $_POST["txt_systolic_2nd"]) / 2),1);
		$diastolic_ave = round((($_POST["txt_diastolic_1st"] + $_POST["txt_diastolic_2nd"]) / 2),1);

		if($_POST["1"]=='Y'):
			array_push($family_hx,'hypertension');
		endif;
		
		if($_POST["2"]=='Y'):
			array_push($family_hx,'stroke');
		endif;

		if($_POST["3"]=='Y'):
			array_push($family_hx,'heart attack');
		endif;

		if($_POST["4"]=='Y'):
			array_push($family_hx,'diabetes');
		endif;

		if($_POST["5"]=='Y'):
			array_push($family_hx,'asthma');
		endif;

		if($_POST["6"]=='Y'):
			array_push($family_hx,'cancer');
		endif;		

		if($_POST["7"]=='Y'):
			array_push($family_hx,'kidney disease');
		endif;

		
		

		if($computed_age >= 40):
			$screen .= '- Age is greater than 40 years old.<br>';
		endif;

		if($smoke_red_flag=='Y'):
			$screen .= '- Tobacco / Cigarette Smoking.<br>';
		endif;
	
		if($central_adiposity_risk == 'Y'):
			$screen .= '- Central adiposity. Waist line ('.$_POST["txt_waist"].') >= '.$limit.'<br>';
		endif;

		if($systolic_ave >= 120 && $diastolic_ave >= 80):
			$screen .= '- Raised blood pressure. Average blood pressure ('.$systolic_ave.' / '.$diastolic_ave.') is above or equal 120/80.<br>';
		endif;	

		if($_POST["sel_diabetes"]=='Y'):
			$screen .= '- There was the presence of diabetes.<br>';
		endif;
		if(count($family_hx) > 0):
			$str_family_hx = implode(',',$family_hx);
			$str_family_hx = '- Family history on the following disease: '.$str_family_hx;
			$screen .= $str_family_hx.'<br>';
		endif;
		
		//print_r($_POST);
		if($screen==''): //health information
			echo "The submitted results appears that the client <b>NEEDS TO BE PROVIDED WITH HEALTH INFORMATION and NO RISK SCREENING IS REQUIRED.</b>";
			echo "<br>Please review the entries below. Once the updates are final, click the button Save and Provide Health Information.";
			echo "<input type='hidden' name='step_taken' value='healthinfo'>";
			return 'healthinfo';
			
		else: //refer for risk screening
			echo "The submitted results appears that the client <b>NEEDS TO UNDERGO RISK SCREENING PROCESS.</b> Due to the following reasons:<br>";
			echo $screen;
			echo "<br>Please review the entries below. Once the updates are final, click the button Save and Proceed to Risk Screening.";
			echo "<input type='hidden' name='step_taken' value='screen'>";
			return 'screen';
		endif;

		
		//print_r($_SESSION);
		//echo healthcenter::get_patient_id($_GET["consult_id"])
	}


	function save_risk_assessment(){
		$pxid = healthcenter::get_patient_id($_GET["consult_id"]); 

		$q_check_ncd = mysql_query("SELECT ncd_id FROM m_patient_ncd WHERE patient_id='$pxid'") or die("Cannot query 1203: ".mysql_error());


		list($m,$d,$y) = explode('/',$_POST["txt_assess_date"]);
		$ncd_assess_date = $y.'-'.$m.'-'.$d;

		if(!empty($_POST["txt_refer_date"])):
			list($m,$d,$y) = explode('/',$_POST["txt_refer_date"]);
			$ncd_refer_date = $y.'-'.$m.'-'.$d;
		endif;

		if(mysql_num_rows($q_check_ncd)==0):
			$q_insert_ncd_px = mysql_query("INSERT INTO m_patient_ncd SET patient_id='$pxid',date_enrolled_ncd='$ncd_assess_date',enrolled_by='$_SESSION[userid]'") or die("Cannot query 1206: ".mysql_error());
			
			if($q_insert_ncd_px):
				$ncd_id = mysql_insert_id();
			endif;
		else:
			list($ncd_id) = mysql_fetch_row($q_check_ncd);
		endif;
		
		$q_check_ncd_consult = mysql_query("SELECT consult_risk_id FROM m_consult_ncd_risk_assessment WHERE patient_id='$pxid' AND date_assessment='$ncd_assess_date'") or die("Cannot query 1219: ".mysql_error());


		if(mysql_num_rows($q_check_ncd_consult)!=0):
			echo "The risk screening dated ".$ncd_assess_date." was already recorded previously.";
		else:
			$q_insert_ncd_consult = mysql_query("INSERT INTO m_consult_ncd_risk_assessment SET ncd_id='$ncd_id',consult_id='$_GET[consult_id]', patient_id='$pxid',risk_assessment_type='$_POST[sel_assessment]',facility_risk_type='$_POST[sel_facility_type]', date_assessment='$ncd_assess_date',family_hx_hypertension='$_POST[1]',family_hx_stroke='$_POST[2]', family_hx_heart_attack='$_POST[3]',family_hx_diabetes='$_POST[4]',family_hx_asthma='$_POST[5]', family_hx_cancer='$_POST[6]',family_hx_kidney_problem='$_POST[7]',smoking='$_POST[sel_smoking]',alcohol_intake='$_POST[sel_alcohol]',excessive_alcohol_intake='$_POST[sel_excessive_alcohol]',high_fat_salt='$_POST[sel_fat_salt]',dietary_fiber_vegetables='$_POST[sel_dietary]',dietary_fiber_fruits='$_POST[sel_fruits]',physical_activity='$_POST[sel_physical]',presence_diabetes='$_POST[sel_diabetes]',central_adiposity='$_POST[sel_adiposity]',waist_line='$_POST[txt_waist]',raised_bp='$_POST[sel_raised_bp]',systolic_1st='$_POST[txt_systolic_1st]',diastolic_1st='$_POST[txt_diastolic_1st]',systolic_2nd='$_POST[txt_systolic_2nd]',diastolic_2nd='$_POST[txt_diastolic_2nd]',ave_bp='$_POST[sel_ave_bp]',action_taken='$_POST[sel_action_taken]',date_referral='$ncd_refer_date',referred_by='$_POST[sel_user]',last_updated=NOW(),step_taken='$_POST[step_taken]',date_screening='NOW()'") or die("Cannot query 1225: ".mysql_error());

		if($q_insert_ncd_consult):
			//echo "The NCD Risk Assessment record was successfully been saved!";
			$consult_risk_id = mysql_insert_id();
			header("location: $_SERVER[PHP_SELF]?page=$_GET[page]&menu_id=$_GET[menu_id]&consult_id=$_GET[consult_id]&ptmenu=$_GET[ptmenu]&module=$_GET[module]&ncd=SCREENING&ncd_id=$ncd_id&ncd_consult=$consult_risk_id#rs");
		else:
			echo "The NCD Risk Assessment record was not saved!";
		endif;

		endif;
	}

	function update_risk_assessment(){
		//print_r($_POST);
		$pxid = healthcenter::get_patient_id($_GET["consult_id"]); 
		
		$q_screen_date = mysql_query("SELECT date_screening, date_assessment FROM m_consult_ncd_risk_assessment WHERE consult_risk_id='$_GET[ncd_consult]' AND ncd_id='$_GET[ncd_id]'") or die("Cannot query 1892: ".mysql_error());

		list($date_screen,$ncd_assess_date) = mysql_fetch_array($q_screen_date);

		if(!empty($_POST["txt_refer_date"])):
			list($m,$d,$y) = explode('/',$_POST["txt_refer_date"]);
			$ncd_refer_date = $y.'-'.$m.'-'.$d;
		endif;



		if($date_screen!='0000-00-00'):

		$q_update_ncd_consult = mysql_query("UPDATE m_consult_ncd_risk_assessment SET ncd_id='$_GET[ncd_id]',consult_id='$_GET[consult_id]', patient_id='$pxid', date_assessment='$ncd_assess_date',family_hx_hypertension='$_POST[1]',family_hx_stroke='$_POST[2]', family_hx_heart_attack='$_POST[3]',family_hx_diabetes='$_POST[4]',family_hx_asthma='$_POST[5]', family_hx_cancer='$_POST[6]',family_hx_kidney_problem='$_POST[7]',smoking='$_POST[sel_smoking]',alcohol_intake='$_POST[sel_alcohol]',excessive_alcohol_intake='$_POST[sel_excessive_alcohol]',high_fat_salt='$_POST[sel_fat_salt]',dietary_fiber_vegetables='$_POST[sel_dietary]',dietary_fiber_fruits='$_POST[sel_fruits]',physical_activity='$_POST[sel_physical]',presence_diabetes='$_POST[sel_diabetes]',central_adiposity='$_POST[sel_adiposity]',waist_line='$_POST[txt_waist]',raised_bp='$_POST[sel_raised_bp]',systolic_1st='$_POST[txt_systolic_1st]',diastolic_1st='$_POST[txt_diastolic_1st]',systolic_2nd='$_POST[txt_systolic_2nd]',diastolic_2nd='$_POST[txt_diastolic_2nd]',ave_bp='$_POST[sel_ave_bp]',action_taken='$_POST[sel_action_taken]',date_referral='$ncd_refer_date',referred_by='$_POST[sel_user]',last_updated=NOW(),step_taken='$_POST[step_taken]' WHERE consult_risk_id='$_GET[ncd_consult]' AND ncd_id='$_GET[ncd_id]'") or die("Cannot query 1225: ".mysql_error());

		else:
		
		$q_update_ncd_consult = mysql_query("UPDATE m_consult_ncd_risk_assessment SET ncd_id='$_GET[ncd_id]',consult_id='$_GET[consult_id]', patient_id='$pxid',date_assessment='$ncd_assess_date',family_hx_hypertension='$_POST[1]',family_hx_stroke='$_POST[2]', family_hx_heart_attack='$_POST[3]',family_hx_diabetes='$_POST[4]',family_hx_asthma='$_POST[5]', family_hx_cancer='$_POST[6]',family_hx_kidney_problem='$_POST[7]',smoking='$_POST[sel_smoking]',alcohol_intake='$_POST[sel_alcohol]',excessive_alcohol_intake='$_POST[sel_excessive_alcohol]',high_fat_salt='$_POST[sel_fat_salt]',dietary_fiber_vegetables='$_POST[sel_dietary]',dietary_fiber_fruits='$_POST[sel_fruits]',physical_activity='$_POST[sel_physical]',presence_diabetes='$_POST[sel_diabetes]',central_adiposity='$_POST[sel_adiposity]',waist_line='$_POST[txt_waist]',raised_bp='$_POST[sel_raised_bp]',systolic_1st='$_POST[txt_systolic_1st]',diastolic_1st='$_POST[txt_diastolic_1st]',systolic_2nd='$_POST[txt_systolic_2nd]',diastolic_2nd='$_POST[txt_diastolic_2nd]',ave_bp='$_POST[sel_ave_bp]',action_taken='$_POST[sel_action_taken]',date_referral='$ncd_refer_date',referred_by='$_POST[sel_user]',last_updated=NOW(),date_screening=NOW(),step_taken='$_POST[step_taken]' WHERE consult_risk_id='$_GET[ncd_consult]' AND ncd_id='$_GET[ncd_id]'") or die("Cannot query 1225: ".mysql_error());			

		endif;

	}

	function form_archives(){
		$pxid = healthcenter::get_patient_id($_GET["consult_id"]); 
		$q_ncd = mysql_query("SELECT ncd_id FROM m_patient_ncd WHERE patient_id='$pxid'") or die("Cannot query 1249: ".mysql_error());

		if(mysql_num_rows($q_ncd)!=0):
			list($ncd_id) = mysql_fetch_row($q_ncd);
			$q_ncd_consult = mysql_query("SELECT consult_risk_id, date_assessment, risk_assessment_type, referred_by,step_taken,consult_id FROM m_consult_ncd_risk_assessment WHERE ncd_id='$ncd_id' AND patient_id='$pxid' ORDER by date_assessment DESC") or die("Cannot query 1252: ".mysql_error());
			echo "<a name='archive'>";
			echo "<table bgcolor='#66FF66'>";
			echo "<tr><td colspan='5' align='center' bgcolor='#339966' class='whitetext'>LIST OF PREVIOUS NCD CONSULTATIONS MADE</td></tr>";
			echo "<tr align='center' bgcolor='#339966' class='whitetext'><td>&nbsp;DATE OF ASSESSMENT&nbsp;</td><td>&nbsp;LOCATION OF ASSESSMENT&nbsp;</td><td>&nbsp;ASSESSED BY&nbsp;</td><td>&nbsp;ACTION TAKEN&nbsp;</td><td>&nbsp;ACTION&nbsp;</td></tr>";
			while(list($consult_risk_id,$date_assessment, $location, $referred_by,$step_taken,$consult_id)=mysql_fetch_row($q_ncd_consult)){
				$q_user = mysql_query("SELECT user_firstname, user_lastname FROM game_user WHERE user_id='$referred_by'") or die("Cannot query 1263");
				list($fname,$lname) = mysql_fetch_array($q_user);

				echo "<tr>";
				echo "<td>".$date_assessment."</td>";
				echo "<td>".$location."</td>";
				echo "<td>".$lname.', '.$fname."</td>";
				echo "<td>".$step_taken."</td>";
				echo "<td><a href='$_SERVER[PHP_SELF]?page=$_GET[page]&menu_id=$_GET[menu_id]&consult_id=$consult_id&ptmenu=$_GET[ptmenu]&module=$_GET[module]&ncd=ASSESSMENT&ncd_id=$ncd_id&ncd_consult=$consult_risk_id#ra'>View</a></td>";
				echo "</tr>";
			}
			
			echo "</table>";
		else:
			echo "No previous NCD consulations were made.";
		endif;
	}

	function get_field_name_family_hx($hxid){
		switch($hxid){
			case '1';
				return 'family_hx_hypertension';
				break;
			case '2':
				return 'family_hx_stroke';
				break;
			case '3':
				return 'family_hx_heart_attack';
				break;
			case '4':
				return 'family_hx_diabetes';
				break;
			case '5':
				return 'family_hx_asthma';
				break;
			case '6':
				return 'family_hx_cancer';
				break;
			case '7':
				return 'family_hx_kidney_problem';
				break;
			default:	
				return ;
				break;
		}

	}

	function show_risk_stratification(){
		/* 1. get the location
		   2. determine if with or without diabetes
		   2. get age date assess - dob
		   3. get gender, SBP and cholesterol (if the location is facility), smoking status
		   4. query m_lib_ncd_risk_stratification_chart for the color
                   5. determine risk rate then display the health infomation message
		*/
		
		//print_r($_SESSION);
		$q_location = mysql_query("SELECT risk_assessment_type, presence_diabetes, systolic_1st, systolic_2nd, date_assessment, patient_id, smoking FROM m_consult_ncd_risk_assessment WHERE consult_risk_id='$_GET[ncd_consult]'") or die("Cannot query 1923: ".mysql_error());
		list($location,$diabetes,$systolic_1st,$systolic_2nd,$date_assess,$pxid,$smoking_id) = mysql_fetch_array($q_location);

		if($location=='community'):
			$presence_diabetes = $diabetes;
		elseif($location=='facility'):
			$q_diabetes = mysql_query("SELECT presence_diabetes FROM m_consult_ncd_risk_screen_diabetes WHERE consult_risk_id='$_GET[ncd_consult]'") or die("Cannot query 1929: ".mysql_error());
			list($presence_diabetes) = mysql_fetch_array($q_diabetes);
		else:			
			$presence_diabetes = 'NA';
		endif;
		
		$q_cholesterol = mysql_query("SELECT total_cholesterol FROM m_consult_ncd_risk_screen_lipid WHERE consult_risk_id='$_GET[ncd_consult]'") or die("Cannot query 1933: ".mysql_error());

		if(mysql_num_rows($q_cholesterol)!=0):
			list($cholesterol_mgl) = mysql_fetch_array($q_cholesterol);
			
			//list($mg,$liter) = explode('/',$cholesterol_mgl);		
			//$cholesterol = round(($mg/38),0);

			$cholesterol = $cholesterol_mgl;
		else:
			$cholesterol = '';
		endif;

		$sbp = round(($systolic_1st + $systolic_2nd)/2,1);
		
		$q_pxage = mysql_query("SELECT round((to_days('$date_assess')-to_days(patient_dob))/365 , 0) as computed_age, patient_gender FROM m_patient WHERE patient_id='$pxid'") or die("Cannot query 1945: ".mysql_error());
		list($px_age,$px_gender) = mysql_fetch_array($q_pxage);
		
		$q_smoking = mysql_query("SELECT smoking_status_label, red_flag FROM m_lib_ncd_smoking_status WHERE smoking_id='$smoking_id'") or die("Cannot query 1951: ".mysql_error());	
		list($smoking_label,$smoking_status) = mysql_fetch_array($q_smoking);
	
		//echo $presence_diabetes.'/'.$cholesterol.'/'.$sbp.'/'.$px_age.'/'.$px_gender.'/'.$smoking_label.'/'.$smoking_status;
		
		$age_group = $this->age_group_stratification($px_age);
		$sbp_group = $this->sbp_stratification($sbp);	

		
		echo "<table>";
		echo "<tr><td>";

			echo "<table bgcolor='#66FF66'>";
			echo "<tr><td colspan='2' align='center' bgcolor='#339966' class='whitetext'>RISK STRATIFICATION DETAILS</td></tr>";
	
			echo "<tr><td>Location of Assessment</td>";
			echo "<td>$location</td>";
			echo "</tr>";
	
			echo "<tr><td>Presence of Diabetes</td>";
			echo "<td>$presence_diabetes</td>";
			echo "</tr>";
	
			echo "<tr><td>Patient Gender and Age</td>";
			echo "<td>$px_gender / $px_age</td>";
			echo "</tr>";
	
			echo "<tr><td>Smoking Status</td>";
			echo "<td>$smoking_status, $smoking_label</td>";
			echo "</tr>";
	
			echo "<tr><td>Systolic Blood Pressure (Actual SBP)</td>";
			echo "<td>$sbp_group ($sbp)</td>";
			echo "</tr>";
	
			echo "<tr><td>Cholesterol (mmol/l)</td>";
			echo "<td>$cholesterol mmol/l</td>";
			echo "</tr>";
	
			echo "</table>";
	

		echo "</td><td valign='top'>";
		
		$this->show_risk_chart($location,$presence_diabetes,$px_gender,$age_group,$smoking_status,$smoking_label,$sbp_group,$cholesterol);

		echo "</td></tr></table>";

	}

	function show_patient_record(){

		$pxid = healthcenter::get_patient_id($_GET["consult_id"]);
		$arr_diag = array();
		$arr_target = array();
		$arr_counsel = array();

	
		$q_get_ncd_record = mysql_query("SELECT ncd_consult_record_id,ncd_id,consult_risk_id,patient_id,consult_id,date_format(consult_date,'%m/%d/%Y'),current_medication,palpation_heart,palpation_peripheral_pulses,palpation_abdomen,auscultation_heart,auscultation_lung,sensation_feet,other_findings,other_findings FROM m_consult_ncd_record WHERE ncd_id='$_GET[ncd_id]' AND consult_risk_id='$_GET[ncd_consult]' AND patient_id='$pxid'") or die("Cannot query 2088: ".mysql_error());

		list($ncd_consult_record_id,$ncd_id,$consult_risk_id,$patient_id,$consult_id,$consult_date,$current_medication,$palpation_heart,$palpation_peripheral,$palpation_abdomen,$auscultation_heart,$auscultation_lung,$sensation,$other_finding,$other_info) = mysql_fetch_array($q_get_ncd_record);

		if($_POST["submit_mgt"]=='Save Patient Record'):
			$this->insert_ncd_px_record('save','');
		elseif($_POST["submit_mgt"]=='Update Patient Record'):
			$this->insert_ncd_px_record('update',$ncd_consult_record_id);
		elseif($_POST["submit_drug"]=='Add Drug'):
			$this->insert_drug('save',$ncd_consult_record_id);
		elseif($_POST["submit_drug"]=='Update Drug'):
			$this->update_drug();
		elseif($_POST["submit_drug"]=='Delete Drug'):
			$this->delete_drug();
		else:
			 //echo $_GET["ncd_id"].'/'.$_GET["ncd_consult"].'/'.$pxid."<br>";
		endif;
		
		$q_get_ncd_record_diag = mysql_query("SELECT diag_id FROM m_consult_ncd_record_diagnosis WHERE ncd_id='$_GET[ncd_id]' AND consult_risk_id='$_GET[ncd_consult]' AND patient_id='$pxid'") or die("Cannot query 2094:".mysql_error());
		
		while(list($diag_id)=mysql_fetch_array($q_get_ncd_record_diag)){
			array_push($arr_diag,$diag_id);
		}
		

		$q_get_ncd_record_target = mysql_query("SELECT target_organ_id FROM m_consult_ncd_record_target WHERE ncd_id='$_GET[ncd_id]' AND consult_risk_id='$_GET[ncd_consult]' AND patient_id='$pxid'") or die("Cannot query 2103: ".mysql_error());
		
		while(list($target_id)=mysql_fetch_array($q_get_ncd_record_target)){
			array_push($arr_target,$target_id);
		}


		$q_get_ncd_record_counselling = mysql_query("SELECT ncd_management_id FROM m_consult_ncd_record_management_counselling WHERE ncd_id='$_GET[ncd_id]' AND consult_risk_id='$_GET[ncd_consult]' AND patient_id='$pxid'") or die("Cannot query 2104: ".mysql_error());

		while(list($ncd_management_id)=mysql_fetch_array($q_get_ncd_record_counselling)){
			array_push($arr_counsel,$ncd_management_id);
		}

		//echo $ncd_consult_record_id.'/'.$ncd_id.'/'.$consult_risk_id.'/'.$patient_id.'/'.$consult_id.'/'.$consult_date.'/'.$current_medication.'/'.$palpation_heart.'/'.$palpation_peripheral.'/'.$palpation_abdomen.'/'.$auscultation_heart.'/'.$auscultation_lung.'/'.$other_finding;

		$visit_count = $this->determine_if_first_visit($_GET["ncd_id"],$_GET["ncd_consult"]);
		if($visit_count==0):
			$first = 'y';
		else:
			$first = 'n';
		endif;

		echo "<a name='consult' />";

		echo "<form action='$_SERVER[PHP_SELF]?page=$_GET[page]&menu_id=$_GET[menu_id]&consult_id=$_GET[consult_id]&ptmenu=$_GET[ptmenu]&module=$_GET[module]&ncd=$_GET[ncd]&ncd_id=$_GET[ncd_id]&ncd_consult=$_GET[ncd_consult]#ques' method='POST' name='form_px_record'>";

		echo "<table bgcolor='#66FF66'>";
		echo "<tr><td colspan='2' align='center' bgcolor='#339966' class='whitetext'>&nbsp;PATIENT'S NCD CONSULTATION RECORD&nbsp;</td></tr>";
		
		echo "<tr><td>NCD previous visits / consultations made</td>";
		echo "<td>$visit_count</td>";
		echo "</tr>";		

		echo "<tr><td>Date of First Visit</td>";
		echo "<td></td>";
		echo "</tr>";

		echo "<tr><td>Date of This Consultation (mm/dd/yyyy)</td>";
		echo "<td><input type='text' name='txt_date_consult' size='11' maxlength='10' value='$consult_date'></input>&nbsp;&nbsp;<a href=\"javascript:show_calendar4('document.form_px_record.txt_date_consult', document.form_px_record.txt_date_consult.value);\"><img src='../images/cal.gif' width='16' height='16' border='0' alt='Click Here to Pick up the date'></a></td>";
		echo "</tr>";
	
		$this->form_current_medication($current_medication);

		$this->form_diagnosis_clinical_conditions($arr_diag);
	
		$this->form_target_organ_damage($arr_target);

		$this->form_physical_exam($palpation_heart,$palpation_peripheral,$palpation_abdomen,$auscultation_heart,$auscultation_lung,$sensation);

		$this->form_other_significant_findings($other_finding);


		$this->form_management($arr_counsel);

		$this->other_info_record($other_info);		

		echo "<tr align='center'><td colspan='2'>";

		if(!isset($ncd_consult_record_id)):
			echo "<input type='submit' name='submit_mgt' value='Save Patient Record'></input>&nbsp;&nbsp;&nbsp;";
		else:
			echo "<input type='submit' name='submit_mgt' value='Update Patient Record'></input>&nbsp;&nbsp;&nbsp;";
		endif;

		echo "<input type='submit' name='submit_mgt' value='Delete Patient Record'></input>";
		echo "</td></tr>";
		echo "</table>";
		echo "</form>";

		if(isset($ncd_consult_record_id)):
			$this->form_set_ncd_drugs($ncd_consult_record_id);
		endif;
	}

	function form_case_management(){
		echo "<br><br><table bgcolor='#66FF66'>";
		echo "<tr><td colspan='2' align='center' bgcolor='#339966' class='whitetext'>&nbsp;CASE MANAGEMENT&nbsp;</td></tr>";
		
		$this->form_management();
		
		$this->other_info_record();

		echo "<tr align='center'><td colspan='2'>";
		echo "<input type='submit' name='submit_mgt' value='Save Case Management Details'></input>&nbsp;&nbsp;&nbsp;";
		echo "<input type='submit' name='submit_mgt' value='Delete Case Management Details'></input>";
		echo "</td></tr>";
		echo "</table>";
	}
		

	function age_group_stratification($pxage){
		if($pxage>=25 && $pxage<=49):
			return 40;
		elseif($pxage>=50 && $pxage<=59):
			return 50;
		elseif($pxage>=60 && $pxage<=69):
			return 60;
		elseif($pxage>=70 && $pxage<=79):
			return 70;
		elseif($pxage>=80):
			return 80;
		else:
			return 'N/A';
		endif;
	}

	function sbp_stratification($sbp){
		if($sbp>=120 && $sbp<=139):
			return 120;
		elseif($sbp>=140 && $sbp<=159):
			return 140;
		elseif($sbp>=160 && $sbp<=179):
			return 160;
		elseif($sbp>=180):
			return 180;
		else:
			return 'N/A';
		endif;
	}

	function show_risk_chart($location,$presence_diabetes,$px_gender,$px_age,$smoking_details,$smoking_label,$sbp,$cholesterol){
		if($sbp<120):
			$sbp = 120;
		endif;

		if($location!='community'):
			$q_strat = mysql_query("SELECT color FROM m_lib_ncd_risk_stratification_chart WHERE type='$location' AND gender='$px_gender' AND smoking_status='$smoking_details' AND age='$px_age' AND sbp='$sbp' AND cholesterol='$cholesterol' AND diabetes_present='$presence_diabetes'") or die("Cannot query 2036: ".mysql_error());
		else:
			$q_strat = mysql_query("SELECT color FROM m_lib_ncd_risk_stratification_chart WHERE type='$location' AND gender='$px_gender' AND smoking_status='$smoking_details' AND age='$px_age' AND sbp='$sbp' AND cholesterol='0' AND diabetes_present='$presence_diabetes'") or die("Cannot query 2036: ".mysql_error());
		endif;

		//echo $location.'/'.$presence_diabetes.'/'.$px_gender.'/'.$px_age.'/'.$smoking_details.'/'.$sbp.'/'.$cholesterol;
		//echo $sbp;
		list($color) = mysql_fetch_array($q_strat);
		
		$q_color = mysql_query("SELECT risk_level FROM m_lib_ncd_risk_stratification WHERE risk_color='$color'") or die("Cannot query: 2062".mysql_error());
		list($risk_level) = mysql_fetch_array($q_color);
		
		echo "<table>";
		echo "<tr><td align='center' bgcolor='#339966' class='whitetext'>&nbsp;&nbsp;RISK LEVEL&nbsp;&nbsp;</td>";
		echo "<td bgcolor='$color'><font size='10'><b>$risk_level</b></td></tr>";
		echo "<tr><td colspan='2'><a href='../site/ncd_health_info.php' target='new'>View Health Information Messages</a></td></tr>";
		echo "<tr><td colspan='2'><a href='../site/icd10_color.php' target='new'>View WHO/ISH Risk Stratification Codes</a></td></tr>";

		echo "</table>";
	}

	function update_screening_date(){
		$q_screen_date = mysql_query("UPDATE m_consult_ncd_risk_assessment SET date_screening='$_POST[txt_date_screen]' WHERE consult_risk_id='$_GET[ncd_consult]' AND ncd_id='$_GET[ncd_id]'") or die("Cannot query 2066: ".mysql_error());
	}

	function determine_if_first_visit($ncd_id,$consult_risk_id){
		$get_assess_date = mysql_query("SELECT date_assessment FROM m_consult_ncd_risk_assessment WHERE ncd_id='$ncd_id' AND consult_risk_id='$consult_risk_id'") or die("Cannot query 2154: ".mysql_error());
		
		list($date_assess) = mysql_fetch_array($get_assess_date);
		//echo $date_assess;
		//$q_check_first = mysql_query("SELECT consult_risk_id FROM m_consult_ncd_risk_assessment WHERE ncd_id='$ncd_id' AND date_assessment<='$date_assess'") or die("Cannot query 2154: ".mysql_error());
		$q_check_first = mysql_query("SELECT ncd_consult_record_id FROM m_consult_ncd_record WHERE ncd_id='$ncd_id' AND consult_date<='$date_assess'") or die("Cannot query 2154: ".mysql_error());
		
		$visit_count = mysql_num_rows($q_check_first);
		
		return $visit_count;		

		//if($visit_count>1): //2nd of succeeding visits
		//	return 0;
		//else: //1st visit
		//	return 1;
		//endif;

	}

	function form_current_medication($current_medication){
		echo "<tr><td colspan='2'><hr></td></tr>";
		echo "<tr><td valign='top'>Current Medications</td>";
		echo "<td><textarea name='txt_current_medication' cols='30' rows='5'>$current_medication</textarea></td>";
		echo "</tr>";
	}

	function form_diagnosis_clinical_conditions($arr_diag){ 
		$q_diag_ncd = mysql_query("SELECT diagnosis_id, diagnosis_name FROM m_lib_ncd_diagnosis_clinical_conditions") or die("Cannot query 2181: ".mysql_error());
		
		echo "<tr><td colspan='2'><hr></td></tr>";

		echo "<tr><td valign='top'>Diagnosis & Associated Clinical Conditions</td>";
		
		echo "<td>";
		
		while(list($diag_id,$diag_name)=mysql_fetch_array($q_diag_ncd)){
		
		if(in_array($diag_id,$arr_diag)):
			echo "<input type='checkbox' name='diag[]' value='$diag_id' CHECKED>$diag_name</input><br>";				
		else:
			echo "<input type='checkbox' name='diag[]' value='$diag_id'>$diag_name</input><br>";
		endif;
		
}
		echo "</td>";
		echo "</tr>";
	}

	function form_target_organ_damage($arr_target){
		$q_target_organ = mysql_query("SELECT target_id, target_name FROM m_lib_ncd_record_target_damage_organ") or die("Cannot query 2196: ".mysql_error());

		echo "<tr><td colspan='2'><hr></td></tr>";

		echo "<tr><td valign='top'>Target Organ Damage</td>";
		echo "<td>";
		while(list($target_id,$target_name)=mysql_fetch_array($q_target_organ)){
			if(in_array($target_id,$arr_target)):
				echo "<input type='checkbox' name='target[]' value='$target_id' CHECKED>$target_name</input><br>";
			else:
				echo "<input type='checkbox' name='target[]' value='$target_id'>$target_name</input><br>";
			endif; 
		}
		echo "</td>";
		echo "</tr>";

	}

	function form_physical_exam($palpation_heart,$palpation_peripheral,$palpation_abdomen,$auscultation_heart,$auscultation_lung){
		
		$q_physical = mysql_query("SELECT exam_id,exam_name FROM m_lib_ncd_record_physical_examination") or die("Cannot query 2211: ".mysql_error());
		$arr_val = array('Normal','Abnormal');
		$arr_val_input = array($palpation_heart,$palpation_peripheral,$palpation_abdomen,$auscultation_heart,$auscultation_lung);
		$index = 0; 

		echo "<tr><td colspan='2'><hr></td></tr>";
		echo "<tr><td valign='top'>Physical Examination</td>";
		echo "<td>";
		echo "<table>";
		while(list($exam_id,$exam_name)=mysql_fetch_array($q_physical)){

			echo "<tr><td>";
			echo $exam_name;
			echo "</td><td>";

			echo "<select name='$exam_id' size='1'>";
			foreach($arr_val as $key=>$value){
				if($arr_val_input[$index]==$value):
					echo "<option value='$value' SELECTED>$value</option>";
				else:
					echo "<option value='$value'>$value</option>";
				endif;
			}

			echo "</select>";
			
			echo "</td>";
			echo "</tr>";
			$index++;
		}	
		echo "</table>";

		echo "</td>";
		
		echo "</tr>";
	}

	function form_other_significant_findings($other_finding){

		echo "<tr><td colspan='2'><hr></td></tr>";
		echo "<tr>";
		echo "<td valign='top'>Other Significant Finding / Laboratory Results</td>";
		echo "<td>";
		echo "<textarea name='txt_findings' cols='30' rows='5'>$other_finding</textarea>";
		echo "</td>";
		echo "</tr>";
	}

	function form_management($arr_mgt_id){
		$q_management = mysql_query("SELECT ncd_management_id, ncd_management_name FROM m_lib_ncd_record_management") or die("Cannot query 2252: ".mysql_error());

		echo "<tr><td colspan='2'><hr></td></tr>";
		echo "<tr>";
		echo "<td valign='top'>Provided Counselling On:</td>";
		echo "<td>";
		while(list($mgt_id,$mgt_name)=mysql_fetch_array($q_management)){
			if(in_array($mgt_id,$arr_mgt_id)):
				echo "<input type='checkbox' name='mgt[]' value='$mgt_id' CHECKED>$mgt_name</input><br>";
			else:
				echo "<input type='checkbox' name='mgt[]' value='$mgt_id'>$mgt_name</input><br>";
			endif;
		}
		echo "</td>";
		echo "</tr>";
	}

	function form_drug_dosage(){
		$q_drug = mysql_query("SELECT drug_id, drug_generic_name,dosage_form FROM m_lib_ncd_drug ORDER by drug_generic_name ASC") or die("Cannot query 2266: ".mysql_error());
		
		echo "<tr><td colspan='2'><hr></td></tr>";
		
		echo "<tr>";
		echo "<td valign='top'>Select prescribed drug/s and type the dosage</td>";
		echo "<td>";
		echo "<select name='sel_drug' size='1'>";
		echo "<option value='---'>---SELECT DRUG---</option>";
		while(list($drug_id,$drug_name,$dosage_form)=mysql_fetch_array($q_drug)){
			echo "<option value='$drug_id'>$drug_name ($dosage_form)</option>";
		}
		echo "</select>";
		echo "&nbsp;&nbsp;&nbsp;";
		echo "<input type='text' name='txt_dosage' size='15'></input>";
		echo "&nbsp;&nbsp;&nbsp;";
		echo "<input type='submit' value='Add Drug'>";
		echo "</td>";
		echo "</tr>";
	}


	function other_info_record($other_info){
		echo "<tr><td colspan='2'><hr></td></tr>";
		echo "<tr>";
		echo "<td valign='top'>OTHER INFORMATION<br><br>Use the REFER PATIENT link next to the <br>PATIENT RECORD link above for referrals</td>";
		echo "<td>";
		echo "<textarea name='other_info' cols='30' rows='5'>$other_info</textarea>";
		echo "</td>";
		echo "</tr>";
	}

	function insert_ncd_px_record($action,$update_ncd_consult_record_id){
		//print_r($_POST);


		$pxid = healthcenter::get_patient_id($_GET["consult_id"]);

		if(empty($_POST["txt_date_consult"])):
			echo "<script language='Javascript'>";
			echo "window.alert('Please supply the data of consultation.')";
			echo "</script>";
		else:
			list($m,$d,$y) = explode('/',$_POST["txt_date_consult"]);
			$date_consult = $y.'-'.$m.'-'.$d;


			if($action=='save'):
			$q_insert_ncd_consult = mysql_query("INSERT INTO m_consult_ncd_record SET ncd_id='$_GET[ncd_id]',consult_risk_id='$_GET[ncd_consult]',patient_id='$pxid',consult_id='$_GET[consult_id]',consult_date='$date_consult',current_medication='$_POST[txt_current_medication]',palpation_heart='$_POST[1]',palpation_peripheral_pulses='$_POST[2]',palpation_abdomen='$_POST[3]',auscultation_heart='$_POST[4]',auscultation_lung='$_POST[5]',sensation_feet='$_POST[6]',other_findings='$_POST[txt_findings]',other_info='$_POST[other_info]',date_recorded=NOW(),recorded_by='$_SESSION[userid]'") or die("Cannot query 2420: ".mysql_error());
			

			if($q_insert_ncd_consult):
				$ncd_consult_record_id = mysql_insert_id();

				foreach($_POST["diag"] as $key=>$value){
					$q_insert_ncd_diagnosis = mysql_query("INSERT INTO m_consult_ncd_record_diagnosis SET ncd_consult_record_id='$ncd_consult_record_id',ncd_id='$_GET[ncd_id]',consult_risk_id='$_GET[ncd_consult]',patient_id='$pxid',consult_id='$_GET[consult_id]',diag_id='$value'") or die("Cannot query: 2365".mysql_error());
				}

				foreach($_POST["target"] as $key2=>$value2){
					$q_insert_ncd_target = mysql_query("INSERT INTO m_consult_ncd_record_target SET ncd_consult_record_id='$ncd_consult_record_id',ncd_id='$_GET[ncd_id]',consult_risk_id='$_GET[ncd_consult]',patient_id='$pxid',consult_id='$_GET[consult_id]',target_organ_id=$value2") or die("Cannot query 2369: ".mysql_error());
				}

				
				foreach($_POST["mgt"] as $key3=>$value3){
					$q_insert_ncd_mgt = mysql_query("INSERT INTO m_consult_ncd_record_management_counselling SET  ncd_consult_record_id='$ncd_consult_record_id',ncd_id='$_GET[ncd_id]',consult_risk_id='$_GET[ncd_consult]',patient_id='$pxid',consult_id='$_GET[consult_id]',ncd_management_id='$value3'") or die("Cannot query 2465: ".mysql_error());
				}

			endif;
			
			elseif($action=='update'):
				$q_update_ncd_consult = mysql_query("UPDATE m_consult_ncd_record SET consult_date='$date_consult',current_medication='$_POST[txt_current_medication]',palpation_heart='$_POST[1]',palpation_peripheral_pulses='$_POST[2]',palpation_abdomen='$_POST[3]',auscultation_heart='$_POST[4]',auscultation_lung='$_POST[5]',sensation_feet='$_POST[6]',other_findings='$_POST[txt_findings]',other_info='$_POST[other_info]',date_recorded=NOW(),recorded_by='$_SESSION[userid]' WHERE ncd_consult_record_id='$update_ncd_consult_record_id' AND ncd_id='$_GET[ncd_id]' AND consult_risk_id='$_GET[ncd_consult]' AND patient_id='$pxid' AND consult_id='$_GET[consult_id]'") or die("Cannot query 2347: ".mysql_error());
			

				
			if($q_update_ncd_consult):
				echo "<script language='Javascript'>";
				echo "window.alert('The patient's NCD consultation record was successfully been updated!')";
				echo "</script>";

				$q_del_ncd_diagnosis = mysql_query("DELETE FROM m_consult_ncd_record_diagnosis WHERE ncd_consult_record_id='$update_ncd_consult_record_id' AND ncd_id='$_GET[ncd_id]' AND consult_risk_id='$_GET[ncd_consult]' AND patient_id='$pxid' AND consult_id='$_GET[consult_id]'") or die("Cannot query 2442: ".mysql_error());

				$q_del_ncd_target = mysql_query("DELETE FROM m_consult_ncd_record_target WHERE ncd_consult_record_id='$update_ncd_consult_record_id' AND ncd_id='$_GET[ncd_id]' AND consult_risk_id='$_GET[ncd_consult]' AND patient_id='$pxid' AND consult_id='$_GET[consult_id]'") or die("Cannot query 2456: ".mysql_error());

				$q_del_ncd_counselling = mysql_query("DELETE FROM m_consult_ncd_record_management_counselling WHERE ncd_consult_record_id='$update_ncd_consult_record_id' AND ncd_id='$_GET[ncd_id]' AND consult_risk_id='$_GET[ncd_consult]' AND patient_id='$pxid' AND consult_id='$_GET[consult_id]'") or die("Cannot query 2458: ".mysql_error());


				foreach($_POST["diag"] as $key=>$value){
					$q_insert_ncd_diagnosis = mysql_query("INSERT INTO m_consult_ncd_record_diagnosis SET ncd_consult_record_id='$update_ncd_consult_record_id',ncd_id='$_GET[ncd_id]',consult_risk_id='$_GET[ncd_consult]',patient_id='$pxid',consult_id='$_GET[consult_id]',diag_id='$value'") or die("Cannot query: 2462".mysql_error());
				}

				foreach($_POST["target"] as $key2=>$value2){
					$q_insert_ncd_target = mysql_query("INSERT INTO m_consult_ncd_record_target SET ncd_consult_record_id='$update_ncd_consult_record_id',ncd_id='$_GET[ncd_id]',consult_risk_id='$_GET[ncd_consult]',patient_id='$pxid',consult_id='$_GET[consult_id]',target_organ_id=$value2") or die("Cannot query 2466: ".mysql_error());
				}

				foreach($_POST["mgt"] as $key3=>$value3){
					$q_insert_ncd_mgt = mysql_query("INSERT INTO m_consult_ncd_record_management_counselling SET  ncd_consult_record_id='$update_ncd_consult_record_id',ncd_id='$_GET[ncd_id]',consult_risk_id='$_GET[ncd_consult]',patient_id='$pxid',consult_id='$_GET[consult_id]',ncd_management_id='$value3'") or die("Cannot query 2465: ".mysql_error());
				}
					
			endif;


			else:
			endif;

		header("location: $_SERVER[PHP_SELF]?page=$_GET[page]&menu_id=$_GET[menu_id]&consult_id=$_GET[consult_id]&ptmenu=$_GET[ptmenu]&module=$_GET[module]&ncd=PATIENTRECORD&ncd_id=$_GET[ncd_id]&ncd_consult=$_GET[ncd_consult]#consult");
		
		endif;
	}

	function form_set_ncd_drugs($ncd_consult_record_id){
		echo "<br><br>";
		echo "<form action='$_SERVER[PHP_SELF]?page=$_GET[page]&menu_id=$_GET[menu_id]&consult_id=$_GET[consult_id]&ptmenu=$_GET[ptmenu]&module=ncd&ncd=$_GET[ncd]&ncd_id=$_GET[ncd_id]&ncd_consult=$_GET[ncd_consult]' method='POST' name='form_drug'>";

		if(isset($_GET["consult_drug_id"]) && $_GET["action"]=='edit'):
			$q_ncd_drug = mysql_query("SELECT consult_drug_id, drug_id, dosage, prescription_date, qty, preparation, other_info, generika, date_recorded FROM m_consult_ncd_record_management_drug WHERE consult_drug_id='$_GET[consult_drug_id]'") or die("Cannot query 2585: ".mysql_error());

			list($consult_drug_id, $saved_drug_id,$dosage,$prescription_date,$qty,$preparation,$other_info,$generika)=mysql_fetch_array($q_ncd_drug);

			list($y,$m,$d)  = explode('-',$prescription_date);
			$prescription_date = $m.'/'.$d.'/'.$y;

			echo "<input type='hidden' value='$consult_drug_id' name='consult_drug_id'></input>";

		endif;

		echo "<table>";		

		echo "<table bgcolor='#66FF66'>";
		echo "<tr><td colspan='2' align='center' class='whitetext' bgcolor='#339966'>RECORD NCD DRUG ISSUANCE</td></tr>";

		echo "<tr><td colspan='2'>Prescription Date&nbsp;&nbsp;&nbsp;";
		
		echo "<input type='text' name='txt_date_prescription' size='5' maxlength='10' value='$prescription_date'></input>&nbsp;&nbsp;<a href=\"javascript:show_calendar4('document.form_drug.txt_date_prescription', document.form_drug.txt_date_prescription.value);\"><img src='../images/cal.gif' width='16' height='16' border='0' alt='Click Here to Pick up the date'></a>&nbsp;&nbsp;";
		//echo "<input type='submit' name='submit_drug_pres' value='Save Date' />";
		echo "</td></tr>";
	
		echo "<tr><td colspan='2'><hr></td></tr>";

		echo "<tr><td>Select Generic Name</td><td>";

		$q_drug = mysql_query("SELECT drug_id,drug_generic,drug_form,drug_mg FROM m_lib_ncd_drug_list ORDER by drug_generic ASC, drug_form ASC, drug_mg ASC") or die("Cannot query 2502: ".mysql_error());

		echo "<select name='sel_drug' size='1'>";
		while(list($drug_id,$drug_generic,$drug_form,$drug_mg)=mysql_fetch_array($q_drug)){
			if($drug_id == $saved_drug_id):
				echo "<option value='$drug_id' SELECTED>$drug_generic ($drug_form, $drug_mg)</option>";
			else:
				echo "<option value='$drug_id'>$drug_generic ($drug_form, $drug_mg)</option>";
			endif;
		}
		echo "</select>";

		echo "</td></tr>";
		
		echo "<tr><td colspan='2'><hr></td></tr>";

		echo "<tr><td>Quantity</td>";
		
		$q_drug_prep = mysql_query("SELECT prep_id, prep_name FROM m_lib_drug_preparation ORDER by prep_name ASC") or die("Cannot query 2531: ".mysql_error());

		echo "<td>";
		echo "<input type='text' name='txt_drug_qty' size='1' value='$qty'></input>&nbsp;";
		
		echo "<select name='sel_prep' size='1'>";
		echo "<option value=''>--- SELECT PREPARATION ---</option>";
		while(list($prep_id,$prep_name)=mysql_fetch_array($q_drug_prep)){
			if($prep_id==$preparation):
				echo "<option value='$prep_id' SELECTED>$prep_name</option>";
			else:
				echo "<option value='$prep_id'>$prep_name</option>";
			endif;
		}
		echo "</select>";
		echo "</td>";
		
		echo "<tr><td colspan='2'><hr></td></tr>";

		echo "</tr>";
		echo "<tr><td>Dosage</td>";
		echo "<td>";
		echo "<input type='text' name='txt_dosage' size='20' value='$dosage' />";
		echo "</td>";
		echo "</tr>";

		echo "<tr><td colspan='2'><hr></td></tr>";

		echo "<tr><td>Other Information</td>";
		echo "<td><input type='text' name='txt_other_info' size='20' value='$other_info' /></td>";
		echo "</tr>";
		
		echo "<tr><td colspan='2'><hr></td></tr>";

		echo "<tr><td>Include to Generika drug orders?</td>";		
		echo "<td>";
		if($generika=='Y'):
			echo "<input type='checkbox' name='chk_generika' value='Y' CHECKED><b>&nbsp;Yes</b></input>";
		else:
			echo "<input type='checkbox' name='chk_generika' value='Y'><b>&nbsp;Yes</b></input>";
		endif;
		echo "</td>";
		echo "</td>";
		echo "</tr>";

		echo "<tr><td colspan='2' align='center'>";

		if(isset($_GET["consult_drug_id"]) && $_GET["action"]=='edit'):
			echo "<input type='submit' name='submit_drug' value='Update Drug'></input>";
			echo "<input type='submit' name='submit_drug' value='Delete Drug'></input>";
		else:
			echo "<input type='submit' name='submit_drug' value='Add Drug'></input>";
		endif;

		echo "<input type='reset' value='Clear Values'></input>";
		echo "</td></tr>";
		echo "</table>";;
		
		echo "</form>";

		$this->disp_drug_date($ncd_consult_record_id);
	}

	function insert_drug($action,$ncd_consult_record_id){
		$pxid = healthcenter::get_patient_id($_GET["consult_id"]);

		if($action=='save'):
			if(empty($_POST["txt_date_prescription"])):
				echo "<script language='javascript'>";
				echo "window.alert('Please supply the date of prescription')";
				echo "</script>";
			else:
			
				list($m,$d,$y) = explode('/',$_POST["txt_date_prescription"]);
				
				$date_pres = $y.'-'.$m.'-'.$d;

				$q_insert = mysql_query("INSERT INTO m_consult_ncd_record_management_drug SET ncd_consult_record_id='$ncd_consult_record_id', ncd_id='$_GET[ncd_id]',consult_risk_id='$_GET[ncd_consult]', patient_id='$pxid', consult_id='$_GET[consult_id]', drug_id='$_POST[sel_drug]', dosage='$_POST[txt_dosage]', prescription_date='$date_pres', qty='$_POST[txt_drug_qty]', preparation='$_POST[sel_prep]', other_info='$_POST[txt_other_info]', generika='$_POST[chk_generika]',date_recorded=NOW()") or die("Cannot query 2594: ".mysql_error());


				if($q_insert):
					echo "<script language='javascript'>";
					echo "window.alert('Drug was successfully been saved!')";
					echo "</script>";
				endif;

			endif;

		else:

		endif;	
	}

	function disp_drug_date($ncd_consult_record_id){
		$pxid = healthcenter::get_patient_id($_GET["consult_id"]);


		$q_ncd_drug = mysql_query("SELECT consult_drug_id, drug_id, dosage, prescription_date, qty, preparation, other_info, generika, date_recorded FROM m_consult_ncd_record_management_drug WHERE ncd_consult_record_id='$ncd_consult_record_id' AND ncd_id='$_GET[ncd_id]' AND consult_risk_id='$_GET[ncd_consult]' AND patient_id='$pxid'") or die("Cannot query 2585: ".mysql_error());
				
		if(mysql_num_rows($q_ncd_drug)!=0):
			echo "<table bgcolor='#66FF66'>";
			echo "<tr><td colspan='7' class='whitetext' align='center' bgcolor='#339966'>LIST OF DRUGS ISSUED</td></tr>";		
			echo "<tr><td  class='whitetext' align='center' bgcolor='#339966'>Drug Issued</td>";
			echo "<td class='whitetext' align='center' bgcolor='#339966'>Quantity</td>";
			echo "<td class='whitetext' align='center' bgcolor='#339966'>Unit</td>";
			echo "<td class='whitetext' align='center' bgcolor='#339966'>Dosage</td>";
			echo "<td class='whitetext' align='center' bgcolor='#339966'>Other Information</td>";
			echo "<td class='whitetext' align='center' bgcolor='#339966'>Order to Generika?</td>";
			echo "<td class='whitetext' align='center' bgcolor='#339966'>Action</td>";
			echo "</tr>";
			
			
			while(list($consult_drug_id, $drug_id,$dosage,$prescription_date,$qty,$preparation,$other_info,$generika)=mysql_fetch_array($q_ncd_drug)){
				list($y,$m,$d) = explode('-',$prescription_date);
				$date_pres = $m.'/'.$d.'/'.$y;

				$q_drug_id = mysql_query("SELECT drug_generic, drug_form, drug_mg FROM m_lib_ncd_drug_list WHERE drug_id='$drug_id'") or die("CAnnot query 2637: ".mysql_error());
				list($drug_generic,$drug_form,$drug_mg) = mysql_fetch_array($q_drug_id);

				$q_prep = mysql_query("SELECT prep_name FROM m_lib_drug_preparation WHERE prep_id='$preparation'") or die("Cannot query 2640: ".mysql_error());
				list($prep_name) = mysql_fetch_array($q_prep);
				
				$generika = (($generika=='Y')?'Y':'N');

				echo "<tr align='center'>";
				echo "<td>$drug_generic ($drug_form, $drug_mg)</td>";
				echo "<td>$qty</td>";
				echo "<td>$prep_name</td>";
				echo "<td>$dosage</td>";
				echo "<td>$other_info</td>";
				echo "<td>$generika</td>";
				echo "<td><a href='$_SERVER[PHP_SELF]?page=$_GET[page]&menu_id=$_GET[menu_id]&consult_id=$_GET[consult_id]&ptmenu=$_GET[ptmenu]&module=ncd&ncd=$_GET[ncd]&ncd_id=$_GET[ncd_id]&ncd_consult=$_GET[ncd_consult]&action=edit&consult_drug_id=$consult_drug_id'>EDIT</a></td>";
				echo "</tr>";


			}
												
			echo "</table>";

		else:

		endif;
	}

	function update_drug(){

		if(empty($_POST["txt_date_prescription"])):
			echo "<script language='javascript'>";
			echo "window.alert('Please supply the date of prescription')";
			echo "</script>";

		else:

		list($m,$d,$y) = explode('/',$_POST["txt_date_prescription"]);
		$date_pres  = $y.'-'.$m.'-'.$d;
			$q_update_drug = mysql_query("UPDATE m_consult_ncd_record_management_drug SET drug_id='$_POST[sel_drug]',dosage='$_POST[txt_dosage]',prescription_date='$date_pres',qty='$_POST[txt_drug_qty]',preparation='$_POST[sel_prep]',other_info='$_POST[txt_other_info]',generika='$_POST[chk_generika]' WHERE consult_drug_id='$_POST[consult_drug_id]'") or die("Cannot query 2704: ".mysql_error());

			if($q_update_drug):
				echo "<script language='javascript'>";
				echo "window.alert('Drug was successfully been updated!')";
				echo "</script>";
			endif;


		endif;
	}

	function delete_drug(){
		$q_del_drug = mysql_query("DELETE FROM m_consult_ncd_record_management_drug WHERE consult_drug_id='$_POST[consult_drug_id]'") or die("Cannot query 2725: ".mysql_error());

		if($q_delete_drug):
			echo "<script language='javascript'>";
			echo "window.alert('Drug was successfully been deleted from the list!')";
			echo "</script>";	
		endif;

	}

}
?>
