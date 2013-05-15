<?php
	session_start();
	ob_start();

   $dbconn = mysql_connect('localhost',$_SESSION["dbuser"],$_SESSION["dbpass"]) or die("Cannot query 4 ".mysql_error());
   mysql_select_db($_SESSION["dbname"],$dbconn) or die("cannot select db");


	class dbcleanup{

		function dbcleanup(){
		  $this->app = "DB Cleanup -- remove patient_id in consultation tables";
	      $this->version = "0.2";
		  $this->author = "Alison Perez";
		}



		function clean_db(){

			$arr_tables = array(array('m_patient_mc','m_consult_mc_prenatal'),array('m_patient_mc','m_consult_mc_postpartum'),array('m_patient_mc','m_consult_mc_services'),array('m_patient_mc','m_consult_mc_vaccine'),array('m_patient_mc','m_consult_mc_visit_risk'),array('m_patient_fp','m_patient_fp_dropout'),array('m_patient_fp','m_patient_fp_hx'),array('m_patient_fp','m_patient_fp_method'),array('m_patient_fp','m_patient_fp_method_service'),array('m_patient_fp','m_patient_fp_obgyn'),array('m_patient_fp','m_patient_fp_obgyn_details'),array('m_patient_fp','m_patient_fp_pe'),array('m_patient_fp','m_patient_fp_pelvic'),array('m_patient_ccdev','m_consult_ccdev_report_dailyservice'),array('m_patient_ccdev','m_consult_ccdev_services'),array('m_patient_ccdev','m_consult_ccdev_vaccine'),array('m_family_members',''));

			$count = 0;

			foreach($arr_tables as $key=>$value){
				$q_sel_id = mysql_query("SELECT a.patient_id FROM $value[0] a") or die("Cannot query 12: ".mysql_error());
		
				while(list($pxid)=mysql_fetch_array($q_sel_id)){
					$q_pxid = mysql_query("SELECT patient_id FROM m_patient WHERE patient_id='$pxid'") or die("Cannot query 15: ".mysql_error());

					if(mysql_num_rows($q_pxid)==0):
						$del_pxid = mysql_query("DELETE FROM $value[0] WHERE patient_id='$pxid'") or die("Cannot query 15: ".mysql_error());

						if($value[1]!=''):
							$del_pxid_sub = mysql_query("DELETE FROM $value[1] WHERE patient_id='$pxid'") or die("Cannot query 21: ".mysql_error());
						endif;				

						$count++;
					endif;		
				}
			}
	
			echo "<script language='Javascript'>";
			echo "window.alert('$count patients have been cleared in the database!')";
			echo "</script>";
		}
	}
?>