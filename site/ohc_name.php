<html>

<head>
	<title>Oral Health Care</title>
	<style type='text/css'>
		table, th, tr, td {border:1px solid #000; border-collapse: collapse;}
		th {height:40px; padding: 5px; }
		td {padding:0 8px;}
	</style>
</head>


<?php

require_once('./mysql_connect.php');

class display_names extends mysqlConnect{
	public function disp_name($nameSql){
		$nameQuery = $this->_dbQuery($nameSql);
		$i = 1;
		echo "<table>";
			echo "<tr>";
				echo "<th></th>";
				echo "<th>Patient ID</th>";
				//echo "<th>Consult ID</th>";
				echo "<th>Last Name</th>";
				echo "<th>First Name</th>";
				echo "<th>Date of Birth</th>";
				echo "<th>Date of Dental</th>";
				if($_GET['class']=='oralHealth6-7'){
					echo "<th>Teeth Count</th>";
				}
			echo "</tr>";
				
			while($nameResult = $this->_dbFetch($nameQuery)){
				echo "<tr>";
					echo "<td>" . $i . "</td>";
					echo "<td>" . $nameResult['p_id'] . "</td>";
					//echo "<td>" . $nameResult['c_id'] . "</td>";
					echo "<td>" . $nameResult['patient_lastname'] . "</td>";
					echo "<td>" . $nameResult['patient_firstname'] . "</td>";
					echo "<td>" . $nameResult['patient_dob'] . "</td>";
					echo "<td>" . $nameResult['date_of_dental'] . "</td>";
					if($_GET['class']=='oralHealth6-7'){
						echo "<th>". $nameResult['cp_id'] ."</th>";
					}
				echo "</tr>";				
				$i++;
			}
		echo "</table>";
		//echo $nameSql;
		 
	}
	
	public function get_names(){
		$startAge = $_GET['sage'];
		$endAge = $_GET['eage'];
		$startDate = $_GET['sdate'];
		$endDate = $_GET['edate'];
		switch($_GET['class']){
			case 'totalAttend':
				if($_GET['group']=='male'){
					$maleSql = "SELECT DISTINCT consult_id AS c_id, p.patient_id AS p_id, date_of_dental, patient_firstname, patient_lastname, patient_dob FROM (SELECT consult_id, date_of_service AS date_of_dental, patient_id FROM m_dental_services UNION ALL SELECT consult_id, date_of_oral AS date_of_dental, patient_id FROM m_dental_patient_ohc_table_a UNION ALL SELECT consult_id, date_of_oral AS date_of_dental, patient_id FROM m_dental_patient_ohc UNION ALL SELECT consult_id, date_of_service AS date_of_dental, patient_id FROM m_dental_other_services) AS dental JOIN m_patient AS p ON p.patient_id = dental.patient_id WHERE round((to_days(date_of_dental)-to_days(p.patient_dob))/365,1) BETWEEN $startAge AND $endAge AND dental.date_of_dental BETWEEN '$startDate' AND '$endDate' AND p.patient_gender = 'M' GROUP BY c_id ORDER BY date_of_dental DESC";
					$this->disp_name($maleSql);
				}elseif($_GET['group']=='female'){
					$femaleSql = "SELECT DISTINCT consult_id AS c_id, p.patient_id AS p_id, date_of_dental, patient_firstname, patient_lastname, patient_dob FROM (SELECT consult_id, date_of_service AS date_of_dental, patient_id FROM m_dental_services UNION ALL SELECT consult_id, date_of_oral AS date_of_dental, patient_id FROM m_dental_patient_ohc_table_a UNION ALL SELECT consult_id, date_of_oral AS date_of_dental, patient_id FROM m_dental_patient_ohc UNION ALL SELECT consult_id, date_of_service AS date_of_dental, patient_id FROM m_dental_other_services) AS dental JOIN m_patient AS p ON p.patient_id = dental.patient_id WHERE round((to_days(date_of_dental)-to_days(p.patient_dob))/365,1) BETWEEN $startAge AND $endAge AND dental.date_of_dental BETWEEN '$startDate' AND '$endDate' AND p.patient_gender = 'F' GROUP BY c_id ORDER BY date_of_dental DESC";
					$this->disp_name($femaleSql);
				}elseif($_GET['group']=='pregnant'){
					$pregSql = "SELECT DISTINCT consult_id AS c_id, p.patient_id AS p_id, date_of_oral AS date_of_dental, patient_firstname, patient_lastname, patient_dob FROM m_dental_patient_ohc AS ohc JOIN m_patient AS p ON p.patient_id=ohc.patient_id WHERE date_of_oral BETWEEN '$startDate' AND '$endDate' AND is_patient_pregnant = 'Y' GROUP BY c_id ORDER BY date_of_dental DESC";
					$this->disp_name($pregSql);
				}elseif($_GET['group']=='total'){
					$totalSql = "SELECT DISTINCT consult_id AS c_id, p.patient_id AS p_id, date_of_dental, patient_firstname, patient_lastname, patient_dob FROM (SELECT consult_id, date_of_service AS date_of_dental, patient_id FROM m_dental_services UNION ALL SELECT consult_id, date_of_oral AS date_of_dental, patient_id FROM m_dental_patient_ohc_table_a UNION ALL SELECT consult_id, date_of_oral AS date_of_dental, patient_id FROM m_dental_patient_ohc UNION ALL SELECT consult_id, date_of_service AS date_of_dental, patient_id FROM m_dental_other_services) AS dental JOIN m_patient AS p ON p.patient_id = dental.patient_id WHERE round((to_days(date_of_dental)-to_days(p.patient_dob))/365,1) BETWEEN $startAge AND $endAge AND dental.date_of_dental BETWEEN '$startDate' AND '$endDate' GROUP BY c_id ORDER BY date_of_dental DESC";
					$this->disp_name($totalSql);
				}elseif($_GET['group']=='othermale'){						  	
					$otherMaleSql = "SELECT DISTINCT consult_id AS c_id, p.patient_id AS p_id, date_of_dental, patient_firstname, patient_lastname, patient_dob, CASE WHEN round((to_days(dental.date_of_dental)-to_days(p.patient_dob))/365,1) BETWEEN $startAge AND $endAge OR round((to_days(dental.date_of_dental)-to_days(p.patient_dob))/365,1) BETWEEN 24.99 AND 60 THEN 'others' END AS totalOthers FROM (SELECT consult_id, date_of_service AS date_of_dental, patient_id FROM m_dental_services UNION ALL SELECT consult_id, date_of_oral AS date_of_dental, patient_id FROM m_dental_patient_ohc_table_a UNION ALL SELECT consult_id, date_of_oral AS date_of_dental, patient_id FROM m_dental_patient_ohc UNION ALL SELECT consult_id, date_of_service AS date_of_dental, patient_id FROM m_dental_other_services) AS dental JOIN m_patient AS p ON p.patient_id = dental.patient_id WHERE round((to_days(date_of_dental)-to_days(p.patient_dob))/365,1) BETWEEN $startAge AND $endAge AND dental.date_of_dental BETWEEN '$startDate' AND '$endDate' AND p.patient_gender = 'M' GROUP BY c_id, totalOthers HAVING totalOthers = 'others' ORDER BY date_of_dental DESC";			  
					$this->disp_name($otherMaleSql);
				}elseif($_GET['group']=='otherfemale'){
					$otherFemaleSql = "SELECT DISTINCT consult_id AS c_id, p.patient_id AS p_id, date_of_dental, patient_firstname, patient_lastname, patient_dob, CASE WHEN round((to_days(dental.date_of_dental)-to_days(p.patient_dob))/365,1) BETWEEN $startAge AND $endAge OR round((to_days(dental.date_of_dental)-to_days(p.patient_dob))/365,1) BETWEEN 24.99 AND 60 THEN 'others' END AS totalOthers FROM (SELECT consult_id, date_of_service AS date_of_dental, patient_id FROM m_dental_services UNION ALL SELECT consult_id, date_of_oral AS date_of_dental, patient_id FROM m_dental_patient_ohc_table_a UNION ALL SELECT consult_id, date_of_oral AS date_of_dental, patient_id FROM m_dental_patient_ohc UNION ALL SELECT consult_id, date_of_service AS date_of_dental, patient_id FROM m_dental_other_services) AS dental JOIN m_patient AS p ON p.patient_id = dental.patient_id WHERE round((to_days(date_of_dental)-to_days(p.patient_dob))/365,1) BETWEEN $startAge AND $endAge AND dental.date_of_dental BETWEEN '$startDate' AND '$endDate' AND p.patient_gender = 'F' GROUP BY c_id, totalOthers HAVING totalOthers = 'others' ORDER BY date_of_dental DESC";
					$this->disp_name($otherFemaleSql);
				}else{
					die('Unexpected Result');
				}	
				break;
				
			case 'totalExamined':
				if($_GET['group']=='male'){
					$maleSql = "SELECT DISTINCT dental.patient_id AS p_id, date_of_dental, patient_firstname, patient_lastname, patient_dob FROM (SELECT date_of_service AS date_of_dental, patient_id FROM m_dental_services UNION ALL SELECT date_of_oral AS date_of_dental, patient_id FROM m_dental_patient_ohc_table_a UNION ALL SELECT date_of_oral AS date_of_dental, patient_id FROM m_dental_patient_ohc UNION ALL SELECT date_of_service AS date_of_dental, patient_id FROM m_dental_other_services) AS dental JOIN m_patient AS p ON p.patient_id = dental.patient_id WHERE round((to_days(date_of_dental)-to_days(p.patient_dob))/365,1) BETWEEN $startAge AND $endAge AND dental.date_of_dental BETWEEN '$startDate' AND '$endDate' AND p.patient_gender = 'M' GROUP BY p_id ORDER BY date_of_dental";
					$this->disp_name($maleSql);
				}elseif($_GET['group']=='female'){
					$femaleSql = "SELECT DISTINCT dental.patient_id AS p_id, date_of_dental, patient_firstname, patient_lastname, patient_dob FROM (SELECT date_of_service AS date_of_dental, patient_id FROM m_dental_services UNION ALL SELECT date_of_oral AS date_of_dental, patient_id FROM m_dental_patient_ohc_table_a UNION ALL SELECT date_of_oral AS date_of_dental, patient_id FROM m_dental_patient_ohc UNION ALL SELECT date_of_service AS date_of_dental, patient_id FROM m_dental_other_services) AS dental JOIN m_patient AS p ON p.patient_id = dental.patient_id WHERE round((to_days(date_of_dental)-to_days(p.patient_dob))/365,1) BETWEEN $startAge AND $endAge AND dental.date_of_dental BETWEEN '$startDate' AND '$endDate' AND p.patient_gender = 'F' GROUP BY p_id ORDER BY date_of_dental";
					$this->disp_name($femaleSql);
				}elseif($_GET['group']=='pregnant'){
					$pregSql = "SELECT DISTINCT p.patient_id AS p_id, date_of_oral date_of_dental, patient_firstname, patient_lastname, patient_dob FROM m_dental_patient_ohc AS ohc JOIN m_patient AS p ON p.patient_id = ohc.patient_id WHERE date_of_oral BETWEEN '$startDate' AND '$endDate' AND is_patient_pregnant = 'Y' GROUP BY p_id ORDER BY date_of_dental";
					$this->disp_name($pregSql);
				}elseif($_GET['group']=='total'){
					$totalSql = "SELECT DISTINCT dental.patient_id AS p_id, date_of_dental, patient_firstname, patient_lastname, patient_dob FROM (SELECT date_of_service AS date_of_dental, patient_id FROM m_dental_services UNION ALL SELECT date_of_oral AS date_of_dental, patient_id FROM m_dental_patient_ohc_table_a UNION ALL SELECT date_of_oral AS date_of_dental, patient_id FROM m_dental_patient_ohc UNION ALL SELECT date_of_service AS date_of_dental, patient_id FROM m_dental_other_services) AS dental JOIN m_patient AS p ON p.patient_id = dental.patient_id WHERE round((to_days(date_of_dental)-to_days(p.patient_dob))/365,1) BETWEEN $startAge AND $endAge AND dental.date_of_dental BETWEEN '$startDate' AND '$endDate' GROUP BY p_id ORDER BY date_of_dental";
					$this->disp_name($totalSql);
				}elseif($_GET['group']=='othermale'){						  	
					$otherMaleSql = "SELECT DISTINCT dental.patient_id AS p_id, date_of_dental, patient_firstname, patient_lastname, patient_dob, CASE WHEN round((to_days(dental.date_of_dental)-to_days(p.patient_dob))/365,1) BETWEEN $startAge AND $endAge OR round((to_days(dental.date_of_dental)-to_days(p.patient_dob))/365,1) BETWEEN 24.99 AND 60 THEN 'others' END AS totalOthers FROM (SELECT date_of_service AS date_of_dental, patient_id FROM m_dental_services UNION ALL SELECT date_of_oral AS date_of_dental, patient_id FROM m_dental_patient_ohc_table_a UNION ALL SELECT date_of_oral AS date_of_dental, patient_id FROM m_dental_patient_ohc UNION ALL SELECT date_of_service AS date_of_dental, patient_id FROM m_dental_other_services) AS dental JOIN m_patient AS p ON p.patient_id = dental.patient_id WHERE round((to_days(date_of_dental)-to_days(p.patient_dob))/365,1) BETWEEN $startAge AND $endAge AND dental.date_of_dental BETWEEN '$startDate' AND '$endDate' AND p.patient_gender = 'M' GROUP BY p_id, totalOthers HAVING totalOthers = 'others' ORDER BY date_of_dental";
					$this->disp_name($otherMaleSql);
				}elseif($_GET['group']=='otherfemale'){
					$otherFemaleSql = "SELECT DISTINCT dental.patient_id AS p_id, date_of_dental, patient_firstname, patient_lastname, patient_dob,  CASE WHEN round((to_days(dental.date_of_dental)-to_days(p.patient_dob))/365,1) BETWEEN $startAge AND $endAge OR round((to_days(dental.date_of_dental)-to_days(p.patient_dob))/365,1) BETWEEN 24.99 AND 60 THEN 'others' END AS totalOthers FROM (SELECT date_of_service AS date_of_dental, patient_id FROM m_dental_services UNION ALL SELECT date_of_oral AS date_of_dental, patient_id FROM m_dental_patient_ohc_table_a UNION ALL SELECT date_of_oral AS date_of_dental, patient_id FROM m_dental_patient_ohc UNION ALL SELECT date_of_service AS date_of_dental, patient_id FROM m_dental_other_services) AS dental JOIN m_patient AS p ON p.patient_id = dental.patient_id WHERE round((to_days(date_of_dental)-to_days(p.patient_dob))/365,1) BETWEEN $startAge AND $endAge AND dental.date_of_dental BETWEEN '$startDate' AND '$endDate' AND p.patient_gender = 'F' GROUP BY p_id, totalOthers HAVING totalOthers = 'others' ORDER BY date_of_dental";
					$this->disp_name($otherFemaleSql);
				}else{
					die('Unexpected Result');
				}
				break;
				
			case 'oralHealth1-5':
				switch($_GET['indicator']){
					case "dental carries":
						$service = "dental_caries = 'YES'";
						break;
					case "gingivitis periodontal disease":
						$service = "gingivitis_periodontal_disease = 'YES'";
						break;
					case "debris":
						$service = "debris = 'YES'";
						break;
					case "calculus":
						$service = "calculus = 'YES'";
						break;
					case "dento-facial anomalies":
						$service = "(cleft_lip_palate = 'YES' OR dento_facial_anomaly = 'YES' OR abnormal_growth = 'YES')";
						break;
					default:
						die("Error: Unable to Classify Indicator");
						break;
				}
				
				if($_GET['group']=='male'){
					$maleSql = "SELECT DISTINCT ohc.patient_id AS p_id, date_of_oral AS date_of_dental, patient_firstname, patient_lastname, patient_dob FROM m_dental_patient_ohc_table_a AS ohc JOIN m_patient AS px On ohc.patient_id = px.patient_id WHERE round((to_days(ohc.date_of_oral)-to_days(px.patient_dob))/365,1) BETWEEN $startAge AND $endAge AND ohc.date_of_oral BETWEEN '$startDate' AND '$endDate' AND $service AND px.patient_gender = 'M' GROUP BY p_id ORDER BY date_of_dental ASC";
					$this->disp_name($maleSql);
				}elseif($_GET['group']=='female'){
					$femaleSql = "SELECT DISTINCT ohc.patient_id AS p_id, date_of_oral AS date_of_dental, patient_firstname, patient_lastname, patient_dob FROM m_dental_patient_ohc_table_a AS ohc JOIN m_patient AS px On ohc.patient_id = px.patient_id WHERE round((to_days(ohc.date_of_oral)-to_days(px.patient_dob))/365,1) BETWEEN $startAge AND $endAge AND ohc.date_of_oral BETWEEN '$startDate' AND '$endDate' AND $service AND px.patient_gender = 'F'";
					$this->disp_name($femaleSql);
				}elseif($_GET['group']=='pregnant'){
					$pregSql = "SELECT DISTINCT ohc.patient_id AS p_id, ohc.date_of_oral AS date_of_dental, patient_lastname, patient_firstname, patient_dob FROM m_dental_patient_ohc_table_a AS ohc JOIN m_patient AS px ON ohc.patient_id = px.patient_id JOIN m_dental_patient_ohc AS preg ON preg.consult_id = ohc.consult_id WHERE ohc.date_of_oral BETWEEN '$startDate' AND '$endDate' AND $service AND preg.is_patient_pregnant = 'Y'";
					$this->disp_name($pregSql);
				}elseif($_GET['group']=='total'){
					$totalSql = "SELECT DISTINCT ohc.patient_id AS p_id, ohc.date_of_oral AS date_of_dental, patient_lastname, patient_firstname, patient_dob FROM m_dental_patient_ohc_table_a AS ohc JOIN m_patient AS px ON px.patient_id = ohc.patient_id WHERE round((to_days(ohc.date_of_oral)-to_days(px.patient_dob))/365,1) BETWEEN $startAge AND $endAge AND ohc.date_of_oral BETWEEN '$startDate' AND '$endDate' AND $service GROUP BY p_id";
					$this->disp_name($totalSql);
				}elseif($_GET['group']=='othermale'){						  	
					$otherMaleSql = "SELECT DISTINCT ohc.patient_id AS p_id, ohc.date_of_oral AS date_of_dental, patient_lastname, patient_firstname, patient_dob , CASE WHEN round((to_days(ohc.date_of_oral)-to_days(px.patient_dob))/365,1) BETWEEN $startAge AND $endAge OR round((to_days(ohc.date_of_oral)-to_days(px.patient_dob))/365,1) BETWEEN 24.99 AND 60 THEN 'others' END AS totalOthers FROM m_dental_patient_ohc_table_a AS ohc JOIN m_patient AS px ON px.patient_id = ohc.patient_id WHERE px.patient_gender = 'M' AND ohc.date_of_oral BETWEEN '$startDate' AND '$endDate' AND $service GROUP BY p_id, totalOthers HAVING totalOthers = 'others'";
					$this->disp_name($otherMaleSql);
				}elseif($_GET['group']=='otherfemale'){
					$otherFemaleSql = "SELECT DISTINCT ohc.patient_id AS p_id, ohc.date_of_oral AS date_of_dental, patient_lastname, patient_firstname, patient_dob , CASE WHEN round((to_days(ohc.date_of_oral)-to_days(px.patient_dob))/365,1) BETWEEN $startAge AND $endAge OR round((to_days(ohc.date_of_oral)-to_days(px.patient_dob))/365,1) BETWEEN 24.99 AND 60 THEN 'others' END AS totalOthers FROM m_dental_patient_ohc_table_a AS ohc JOIN m_patient AS px ON px.patient_id = ohc.patient_id WHERE px.patient_gender = 'F' AND ohc.date_of_oral BETWEEN '$startDate' AND '$endDate' AND $service GROUP BY p_id, totalOthers HAVING totalOthers = 'others'";
					$this->disp_name($otherFemaleSql);
				}else{
					die('Unexpected Result');
				}
				break;
				
			case 'oralHealth6-7':
				$service = $_GET['indicator'];
				if($_GET['group']=='male'){
					$maleSql = "SELECT COUNT(ohc.patient_id) AS cp_id, ohc.patient_id AS p_id, ohc.date_of_oral AS date_of_dental, patient_lastname, patient_firstname, patient_dob FROM m_dental_patient_ohc AS ohc JOIN m_patient AS p ON p.patient_id = ohc.patient_id WHERE round((to_days(ohc.date_of_oral)-to_days(p.patient_dob))/365,1) BETWEEN $startAge AND $endAge AND ohc.date_of_oral BETWEEN '$startDate' AND '$endDate' AND ohc.tooth_condition = '$service' AND p.patient_gender = 'M' GROUP by p_id ORDER BY p_id, date_of_oral ASC";
					$this->disp_name($maleSql);
				}elseif($_GET['group']=='female'){
					$femaleSql = "SELECT COUNT(ohc.patient_id) AS cp_id, ohc.patient_id AS p_id, ohc.date_of_oral AS date_of_dental, patient_lastname, patient_firstname, patient_dob FROM m_dental_patient_ohc AS ohc JOIN m_patient AS p ON p.patient_id = ohc.patient_id WHERE round((to_days(ohc.date_of_oral)-to_days(p.patient_dob))/365,1) BETWEEN $startAge AND $endAge AND ohc.date_of_oral BETWEEN '$startDate' AND '$endDate' AND ohc.tooth_condition = '$service' AND p.patient_gender = 'F' GROUP by p_id ORDER BY p_id, date_of_oral ASC";	
					$this->disp_name($femaleSql);
				}elseif($_GET['group']=='pregnant'){
					$pregSql = "SELECT COUNT(ohc.patient_id) AS cp_id, ohc.patient_id AS p_id, ohc.date_of_oral AS date_of_dental, patient_lastname, patient_firstname, patient_dob FROM m_dental_patient_ohc AS ohc JOIN m_patient AS p ON p.patient_id = ohc.patient_id WHERE ohc.date_of_oral BETWEEN '$startDate' AND '$endDate' AND ohc.tooth_condition = '$service' AND ohc.is_patient_pregnant = 'Y' GROUP by p_id ORDER BY p_id, date_of_oral ASC";
					$this->disp_name($pregSql);
				}elseif($_GET['group']=='total'){
					$totalSql = "SELECT COUNT(ohc.patient_id) AS cp_id, ohc.patient_id AS p_id, ohc.date_of_oral AS date_of_dental, patient_lastname, patient_firstname, patient_dob FROM m_dental_patient_ohc AS ohc JOIN m_patient AS p ON p.patient_id = ohc.patient_id WHERE round((to_days(ohc.date_of_oral)-to_days(p.patient_dob))/365,1) BETWEEN $startAge AND $endAge AND ohc.date_of_oral BETWEEN '$startDate' AND '$endDate' AND ohc.tooth_condition = '$service' GROUP by p_id ORDER BY p_id, date_of_oral ASC";
					$this->disp_name($totalSql);
				}elseif($_GET['group']=='othermale'){						  	
					$otherMaleSql = "SELECT COUNT(ohc.patient_id) AS cp_id, ohc.patient_id AS p_id, ohc.date_of_oral AS date_of_dental, patient_lastname, patient_firstname, patient_dob, CASE WHEN round((to_days(ohc.date_of_oral)-to_days(p.patient_dob))/365,1) BETWEEN $startAge AND $endAge OR round((to_days(ohc.date_of_oral)-to_days(p.patient_dob))/365,1) BETWEEN 24.99 AND 60 THEN 'OTHER' END AS totalOthers FROM m_dental_patient_ohc AS ohc JOIN m_patient AS p ON p.patient_id=ohc.patient_id WHERE p.patient_gender = 'M' and ohc.date_of_oral BETWEEN '$startDate' AND '$endDate' AND ohc.tooth_condition = '$service' GROUP BY p_id, totalOthers HAVING totalOthers = 'OTHER'";
					$this->disp_name($otherMaleSql);
				}elseif($_GET['group']=='otherfemale'){
					$otherFemaleSql = "SELECT COUNT(ohc.patient_id) AS cp_id, ohc.patient_id AS p_id, ohc.date_of_oral AS date_of_dental, patient_lastname, patient_firstname, patient_dob, CASE WHEN round((to_days(ohc.date_of_oral)-to_days(p.patient_dob))/365,1) BETWEEN $startAge AND $endAge OR round((to_days(ohc.date_of_oral)-to_days(p.patient_dob))/365,1) BETWEEN 24.99 AND 60 THEN 'OTHER' END AS totalOthers FROM m_dental_patient_ohc AS ohc JOIN m_patient AS p ON p.patient_id=ohc.patient_id WHERE p.patient_gender = 'F' and ohc.date_of_oral BETWEEN '$startDate' AND '$endDate' AND ohc.tooth_condition = '$service' GROUP BY p_id, totalOthers HAVING totalOthers = 'OTHER'";
					$this->disp_name($otherFemaleSql);
				}else{
					die('Unexpected Result');
				}
				break;
				
			case 'service1':
				$service = $_GET['indicator'];
				if($_GET['group']=='male'){
					$maleSql = "SELECT DISTINCT service.patient_id AS p_id, date_of_service AS date_of_dental, patient_firstname, patient_lastname, patient_dob FROM m_dental_services AS service JOIN m_patient AS p ON p.patient_id = service.patient_id WHERE round((to_days(service.date_of_service)-to_days(p.patient_dob))/365,1) BETWEEN $startAge AND $endAge AND service.date_of_service BETWEEN '$startDate' AND '$endDate' AND service.service_provided = '$service' AND p.patient_gender = 'M' GROUP BY p_id ORDER BY date_of_dental ASC";
					$this->disp_name($maleSql);
				}elseif($_GET['group']=='female'){
				$femaleSql = "SELECT DISTINCT service.patient_id AS p_id, date_of_service AS date_of_dental, patient_firstname, patient_lastname, patient_dob FROM m_dental_services AS service JOIN m_patient AS p ON p.patient_id = service.patient_id WHERE round((to_days(service.date_of_service)-to_days(p.patient_dob))/365,1) BETWEEN $startAge AND $endAge AND service.date_of_service BETWEEN '$startDate' AND '$endDate' AND service.service_provided = '$service' AND p.patient_gender = 'F' GROUP BY p_id ORDER BY date_of_dental ASC";
					$this->disp_name($femaleSql);
				}elseif($_GET['group']=='pregnant'){
					$pregSql = "SELECT DISTINCT service.patient_id AS p_id, date_of_service AS date_of_dental, patient_firstname, patient_lastname, patient_dob FROM m_dental_services AS service JOIN m_dental_patient_ohc AS ohc ON service.patient_id = ohc.patient_id JOIN m_patient AS p ON p.patient_id = service.patient_id WHERE service.date_of_service BETWEEN '$startDate' AND '$endDate' AND service.service_provided = '$service' AND ohc.is_patient_pregnant = 'Y' GROUP BY p_id ORDER BY date_of_dental ASC";
					$this->disp_name($pregSql);
				}elseif($_GET['group']=='total'){
					$totalSql = "SELECT DISTINCT service.patient_id AS p_id, date_of_service AS date_of_dental, patient_firstname, patient_lastname, patient_dob FROM m_dental_services AS service JOIN m_patient AS p ON p.patient_id = service.patient_id WHERE round((to_days(service.date_of_service)-to_days(p.patient_dob))/365,1) BETWEEN $startAge AND $endAge AND service.date_of_service BETWEEN '$startDate' AND '$endDate' AND service.service_provided = '$service' GROUP BY p_id ORDER BY date_of_dental ASC";
					$this->disp_name($totalSql);
				}elseif($_GET['group']=='othermale'){						  	
					$otherMaleSql = "SELECT DISTINCT service.patient_id AS p_id, date_of_service AS date_of_dental, patient_firstname, patient_lastname, patient_dob, CASE WHEN round((to_days(service.date_of_service)-to_days(p.patient_dob))/365,1) BETWEEN $startAge AND $endAge OR round((to_days(service.date_of_service)-to_days(p.patient_dob))/365,1) BETWEEN 24.99 AND 60 THEN 'OTHER' END AS totalOthers FROM m_dental_services AS service JOIN m_patient AS p ON p.patient_id = service.patient_id WHERE p.patient_gender = 'M' AND service.date_of_service BETWEEN '$startDate' AND '$endDate' AND service.service_provided = '$service' GROUP BY p_id, totalOthers HAVING totalOthers = 'OTHER' ORDER BY date_of_dental ASC";
					$this->disp_name($otherMaleSql);
				}elseif($_GET['group']=='otherfemale'){
					$otherFemaleSql = "SELECT DISTINCT service.patient_id AS p_id, date_of_service AS date_of_dental, patient_firstname, patient_lastname, patient_dob, CASE WHEN round((to_days(service.date_of_service)-to_days(p.patient_dob))/365,1) BETWEEN $startAge AND $endAge OR round((to_days(service.date_of_service)-to_days(p.patient_dob))/365,1) BETWEEN 24.99 AND 60 THEN 'OTHER' END AS totalOthers FROM m_dental_services AS service JOIN m_patient AS p ON p.patient_id = service.patient_id WHERE p.patient_gender = 'F' AND service.date_of_service BETWEEN '$startDate' AND '$endDate' AND service.service_provided = '$service' GROUP BY p_id, totalOthers HAVING totalOthers = 'OTHER' ORDER BY date_of_dental ASC";
					$this->disp_name($otherFemaleSql);
				}else{
					die('Unexpected Result');
				}
				break;
				
			case 'service2':
				$service = $_GET['indicator'];
				if($_GET['group']=='male'){
					$maleSql = "SELECT DISTINCT service.patient_id AS p_id, date_of_service AS date_of_dental, patient_lastname, patient_firstname, patient_dob FROM m_dental_other_services AS service JOIN m_patient AS p ON p.patient_id = service.patient_id WHERE round((to_days(service.date_of_service)-to_days(p.patient_dob))/365,1) BETWEEN $startAge AND $endAge AND service.date_of_service BETWEEN '$startDate' AND '$endDate' AND service.$service = 'YES' AND p.patient_gender = 'M' GROUP BY p_id ORDER BY date_of_dental ASC";
					$this->disp_name($maleSql);
				}elseif($_GET['group']=='female'){
					$femaleSql = "SELECT DISTINCT service.patient_id AS p_id, date_of_service AS date_of_dental, patient_lastname, patient_firstname, patient_dob FROM m_dental_other_services AS service JOIN m_patient AS p ON p.patient_id = service.patient_id WHERE round((to_days(service.date_of_service)-to_days(p.patient_dob))/365,1) BETWEEN $startAge AND $endAge AND service.date_of_service BETWEEN '$startDate' AND '$endDate' AND service.$service = 'YES' AND p.patient_gender = 'F' GROUP BY p_id ORDER BY date_of_dental ASC";
					$this->disp_name($femaleSql);
				}elseif($_GET['group']=='pregnant'){
					$pregSql = "SELECT DISTINCT service.patient_id AS p_id, date_of_service AS date_of_dental, patient_lastname, patient_firstname, patient_dob FROM m_dental_other_services AS service JOIN m_dental_patient_ohc AS ohc ON ohc.patient_id = service.patient_id JOIN m_patient AS p ON p.patient_id = service.patient_id WHERE service.date_of_service BETWEEN '$startDate' AND '$endDate' AND service.$service = 'YES' AND ohc.is_patient_pregnant = 'Y' GROUP BY p_id ORDER BY date_of_dental ASC";
					$this->disp_name($pregSql);
				}elseif($_GET['group']=='total'){
					$totalSql = "SELECT DISTINCT service.patient_id AS p_id, date_of_service AS date_of_dental, patient_lastname, patient_firstname, patient_dob FROM m_dental_other_services AS service JOIN m_patient AS p ON p.patient_id = service.patient_id WHERE round((to_days(service.date_of_service)-to_days(p.patient_dob))/365,1) BETWEEN $startAge AND $endAge AND service.date_of_service BETWEEN '$startDate' AND '$endDate' AND service.$service = 'YES' GROUP BY p_id ORDER BY date_of_dental ASC";
					$this->disp_name($totalSql);
				}elseif($_GET['group']=='othermale'){						  	
					$otherMaleSql = "SELECT DISTINCT service.patient_id AS p_id, date_of_service AS date_of_dental, patient_lastname, patient_firstname, patient_dob , CASE WHEN round((to_days(service.date_of_service)-to_days(p.patient_dob))/365,1) BETWEEN $startAge AND $endAge OR round((to_days(service.date_of_service)-to_days(p.patient_dob))/365,1) BETWEEN 24.99 AND 60 THEN 'OTHER' END AS totalOthers FROM m_dental_other_services AS service JOIN m_patient AS p ON p.patient_id = service.patient_id WHERE p.patient_gender = 'M' AND service.date_of_service BETWEEN '$startDate' AND '$endDate' AND service.$service = 'YES' GROUP BY p_id, totalOthers HAVING totalOthers = 'OTHER' ORDER BY date_of_dental ASC";
					$this->disp_name($otherMaleSql);
				}elseif($_GET['group']=='otherfemale'){
					$otherFemaleSql = "SELECT DISTINCT service.patient_id AS p_id, date_of_service AS date_of_dental, patient_lastname, patient_firstname, patient_dob , CASE WHEN round((to_days(service.date_of_service)-to_days(p.patient_dob))/365,1) BETWEEN $startAge AND $endAge OR round((to_days(service.date_of_service)-to_days(p.patient_dob))/365,1) BETWEEN 24.99 AND 60 THEN 'OTHER' END AS totalOthers FROM m_dental_other_services AS service JOIN m_patient AS p ON p.patient_id = service.patient_id WHERE p.patient_gender = 'F' AND service.date_of_service BETWEEN '$startDate' AND '$endDate' AND service.$service = 'YES' GROUP BY p_id, totalOthers HAVING totalOthers = 'OTHER' ORDER BY date_of_dental ASC";
					$this->disp_name($otherFemaleSql);
				}else{
					die('Unexpected Result');
				}
				break;
				
			case 'ofc':
				$service = $_GET['indicator'];
				if($_GET['group']=='male'){
					$maleSql = "SELECT DISTINCT fhsis.patient_id AS p_id, date_of_consultation AS date_of_dental, patient_lastname, patient_firstname, patient_dob FROM m_dental_fhsis AS fhsis JOIN m_patient AS p ON p.patient_id = fhsis.patient_id WHERE ROUND((to_days(fhsis.date_of_consultation)-to_days(p.patient_dob))/365,1) BETWEEN $startAge AND $endAge AND fhsis.date_of_consultation BETWEEN '$startDate' AND '$endDate' AND fhsis.indicator = 1 AND fhsis.indicator_qualified = 'YES' AND p.patient_gender = 'M' GROUP BY p_id ORDER BY date_of_dental ASC";
					$this->disp_name($maleSql);
				}elseif($_GET['group']=='female'){
					$femaleSql = "SELECT DISTINCT fhsis.patient_id AS p_id, date_of_consultation AS date_of_dental, patient_lastname, patient_firstname, patient_dob FROM m_dental_fhsis AS fhsis JOIN m_patient AS p ON p.patient_id = fhsis.patient_id WHERE ROUND((to_days(fhsis.date_of_consultation)-to_days(p.patient_dob))/365,1) BETWEEN $startAge AND $endAge AND fhsis.date_of_consultation BETWEEN '$startDate' AND '$endDate' AND fhsis.indicator = 1 AND fhsis.indicator_qualified = 'YES' AND p.patient_gender = 'F' GROUP BY p_id ORDER BY date_of_dental ASC";
					$this->disp_name($femaleSql);
				}elseif($_GET['group']=='pregnant'){
					$pregSql = "SELECT DISTINCT fhsis.patient_id AS p_id, date_of_consultation AS date_of_dental, patient_lastname, patient_firstname, patient_dob FROM m_dental_fhsis AS fhsis WHERE indicator = 10"; 
					$this->disp_name($pregSql);
				}elseif($_GET['group']=='total'){
					$totalSql = "SELECT DISTINCT fhsis.patient_id AS p_id, date_of_consultation AS date_of_dental, patient_lastname, patient_firstname, patient_dob FROM m_dental_fhsis AS fhsis JOIN m_patient AS p ON p.patient_id = fhsis.patient_id WHERE ROUND((to_days(fhsis.date_of_consultation)-to_days(p.patient_dob))/365,1) BETWEEN $startAge AND $endAge AND fhsis.date_of_consultation BETWEEN '$startDate' AND '$endDate' AND fhsis.indicator = 1 AND fhsis.indicator_qualified = 'YES' GROUP BY p_id ORDER BY date_of_dental ASC";
					$this->disp_name($totalSql);
				}elseif($_GET['group']=='othermale'){						  	
					$otherMaleSql = "SELECT DISTINCT fhsis.patient_id AS p_id, date_of_consultation AS date_of_dental, patient_lastname, patient_firstname, patient_dob FROM m_dental_fhsis AS fhsis WHERE indicator = 10"; 
					$this->disp_name($otherMaleSql);
				}elseif($_GET['group']=='otherfemale'){
					$otherFemaleSql = "SELECT DISTINCT fhsis.patient_id AS p_id, date_of_consultation AS date_of_dental, patient_lastname, patient_firstname, patient_dob FROM m_dental_fhsis AS fhsis WHERE indicator = 10"; 
					$this->disp_name($otherFemaleSql);
				}else{
					die('Unexpected Result');
				}	
				break;
				
			default:
				die ("Error");
				break;	
		}
	}
}
$names = new display_names;

?>

<body>
	<?php $names->get_names(); ?>
</body>
</html>
