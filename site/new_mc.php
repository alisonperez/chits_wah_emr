<?php
	$arr_user = array();
	$q_pregnant = mysql_query("SELECT DISTINCT a.patient_id, a.mc_id, a.patient_edc, a.patient_lmp, date_format(c.prenatal_date,'%Y-%m-%d') FROM m_patient_mc a, m_patient b, m_consult_mc_prenatal c WHERE a.patient_id=b.patient_id AND date_format(c.prenatal_date,'%Y-%m-%d') BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND a.mc_id=c.mc_id AND c.flag_private <> 'Y'	ORDER by c.prenatal_date, a.delivery_date ASC") or die("Cannot query 788: ".mysql_error());

	if(mysql_num_rows($q_pregnant)!=0):
		while(list($pxid,$mc_id,$edc,$lmp,$first_prenatal)=mysql_fetch_array($q_pregnant)){ 
			if(!(in_array($pxid,$arr_px_id))):
				$q_pregseen = mysql_query("SELECT DISTINCT patient_id, mc_id, date_format(prenatal_date,'%Y-%m-%d') AS date_seen FROM m_consult_mc_prenatal WHERE patient_id ='$pxid' AND mc_id='$mc_id' AND flag_private <> 'Y' ORDER BY prenatal_date ASC LIMIT 0,1") or die ("Cannot query 813: ".mysql_error());
					
				if(mysql_num_rows($q_pregseen) > 0){
					$r_preg_seen = mysql_fetch_array($q_pregseen);
					$date_seen = $r_preg_seen['date_seen'];
						
					$check_date = mysql_query("SELECT DISTINCT a.patient_id, a.mc_id, date_format(a.prenatal_date,'%Y-%m-%d') AS date_seen, b.user_lastname AS user_lastname, b.user_firstname AS user_lastname FROM m_consult_mc_prenatal a JOIN game_user b ON a.user_id = b_user_id WHERE patient_id ='$pxid' AND mc_id='$mc_id' AND date_format(a.prenatal_date,'%Y-%m-%d') = '$date_seen' AND date_format(prenatal_date,'%Y-%m-%d') BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]'") or die("Cannot query date: ".mysql_error());
						
					if(mysql_num_rows($check_date) > 0){
						$r_check_date = mysql_fetch_array($check_date);
						$mc_id = $r_check_date['mc_id'];
						$px_id = $r_check_date['patient_id'];
						$prenatal_date = $r_check_date['date_seen'];
						$user_name = $r_check_date['user_lastname'] . ", " . $r_check_date['user_firstname'];
					}else{
						$mc_id = 0;
						$pxid = 0;
					}
					
				}else{
					$mc_id = 0;
					$pxid = 0;
				}
				
				if($px_id != 0):
					
				endif;

			endif; 
		}

	endif; 

	
	break;
?>