<html>
<head>
	<title>Consolidate Oral Health Status and Servies Report</title>
	<style type="text/css">
		table {width:1333px; }
		table, tr, td, th {border-collapse:collapse; border:1px solid #000000; }
		td {text-align:right; }
		.indicator {text-align:left; padding-left:20px; font:normal .9em serif; }
		.indicator-header {text-align:left; padding-left:0;  font:bold .9em serif; }
		.indicator-sub {text-align:left; padding-left:40px;  font:normal .9em serif; }
		.title-date {display:inline-block; width:120px; }
		#submitQ {margin-left:20px; width:120px; }
		#grouping {text-align:center; }
		a:link, a:visited, a:active {color: #0000FF;  text-decoration:none; font-weight: bold }
		a:hover {color:#FF0000}
	</style>
	<script>
		funtion pFriend(){
		
		}
	</script>
<?php

if(!isset($_SESSION["userid"])){
	die("You must log in to WAH-EMR!");
}else{
	require_once('./mysql_connect.php');


	class mysqlQuery extends mysqlConnect{
		public $array_ageStart = array(0, 1.99, 2.99, 3.99, 4.99, 0, 9.99 , 59.99, 0  , 5.99, 0  , 0);
		public $array_ageEnd = array  (2, 3   , 4   , 5   , 6   , 6, 24.99, 300  , 300, 10  , 300, 300);
		
		//public $array_ageStart = array(0   , 2   , 3   , 4   , 5   , 0   , 10   , 60 , 0  , 6   , 0  , 0);
		//public $array_ageEnd = array  (1.99, 2.99, 3.99, 4.99, 5.99, 5.99, 24.99, 300, 300, 9.99, 300, 300);
		
		public function disMonth($nameMonth){
			for($month=1;$month<=12;$month++){
				$monthName = date("F", mktime(0,0,0,$month,1,0));
				echo "<option value='$month' ".($_GET[$nameMonth]== $month ? 'selected' : '').">$monthName</option>";
			}
		}

		public function disYear($nameYear){
			$presentYear = date("Y");
			for($year=2005;$year<=$presentYear;$year++){
				if(!isset($_GET['reportYear'])){
					echo "<option value='$year' ".($_GET[$nameYear] == $presentYear ? 'selected' : '').">$year</option>";
				}else{
					echo "<option value='$year' ".($_GET[$nameYear] == $year ? 'selected' : '').">$year</option>";
				}
			}
		}

		public function displayQuery($maleSql,$femaleSql,$pregSql,$totalSql,$otherMaleSql,$otherFemaleSql,$i,$indicator, $startAge, $endAge, $startDate, $endDate, $Query){
			//unset($foo); $foo = array();
			switch($i){
				case 8:
					$pregQuery = $this->_dbQuery($pregSql);
					$pregResult = $this->_dbFetch($pregQuery); //Total Pregnant Attended
					$pregValue = $pregResult[total];
					
					//echo "<td>" .($pregValue == 0 ? 0 : $pregValue). "</td>";
					
					if($pregValue==0){
						echo "<td>0</td>";
					}else{
						echo "<td><a href='ohc_name.php?class=$Query&indicator=$indicator&group=pregnant&sage=$startAge&eage=$endAge&sdate=$startDate&edate=$endDate' target='_blank'>" .$pregValue. "</a></td>";
					}
					
					array_push($this->value_array,$pregValue);
					break;
				case 9:
					$otherMaleQuery = $this->_dbQuery($otherMaleSql);
					$otherMaleResult = $this->_dbFetch($otherMaleQuery);//Total Others Male
					$otherMaleValue = $otherMaleResult[total];
						
					$otherFemaleQuery = $this->_dbQuery($otherFemaleSql);
					$otherFemaleResult = $this->_dbFetch($otherFemaleQuery);//Total Others Female
					$otherFemaleValue = $otherFemaleResult[total];
					
					//echo "<td>" .($otherMaleValue == 0 ? 0 : $otherMaleValue). "</td>";
					//echo "<td>" .($otherFemaleValue == 0 ? 0 : $otherFemaleValue). "</td>";
					
					if($otherMaleValue==0){
						echo "<td>0</td>";
					}else{
						echo "<td><a href='ohc_name.php?class=$Query&indicator=$indicator&group=othermale&sage=$startAge&eage=$endAge&sdate=$startDate&edate=$endDate' target='_blank'>" .$otherMaleValue. "</a></td>";
					}
					
					if($otherFemaleValue==0){
						echo "<td>0</td>";
					}else{
						echo "<td><a href='ohc_name.php?class=$Query&indicator=$indicator&group=otherfemale&sage=$startAge&eage=$endAge&sdate=$startDate&edate=$endDate' target='_blank'>" .$otherFemaleValue. "</a></td>";
					}
					
					//echo $otherMaleSql . "<br />" . $otherfemaleSql;
					array_push($this->value_array,$otherMaleValue,$otherFemaleValue);
					break;
				case 11:
					$totalQuery = $this->_dbQuery($totalSql);
					$totalResult = $this->_dbFetch($totalQuery); //Total Attended
					$totalValue = $totalResult[total];
					
					//echo "<td>" .($totalValue == 0 ? 0 : $totalValue). "</td>";
					
					if($totalValue==0){
						echo "<td>0</td>";
					}else{
						echo "<td><a href='ohc_name.php?class=$Query&indicator=$indicator&group=total&sage=$startAge&eage=$endAge&sdate=$startDate&edate=$endDate' target='_blank'>" .$totalValue. "</a></td>";
					}
					
					array_push($this->value_array,$totalValue);
					$this->csv_array[$indicator][] = $this->value_array;
					unset($this->value_array);
					$this->value_array = array();

					break;
				default:
					$maleQuery = $this->_dbQuery($maleSql);
					$maleResult = $this->_dbFetch($maleQuery); //Total Male Attended
					$maleValue = $maleResult[total];
						
					$femaleQuery = $this->_dbQuery($femaleSql);
					$femaleResult = $this->_dbFetch($femaleQuery); //Total Female Attended
					$femaleValue = $femaleResult[total];
					
					//echo "<td>" .($maleValue == 0 ? 0 : $maleValue). "</td>";
					//echo "<td>" .($femaleValue == 0 ? 0 : $femaleValue). "</td>";
					
					if($maleValue==0){
						echo "<td>0</td>";
					}else{
						echo "<td><a href='ohc_name.php?class=$Query&indicator=$indicator&group=male&sage=$startAge&eage=$endAge&sdate=$startDate&edate=$endDate' target='_blank'>" .$maleValue. "</a></td>";
					}
					
					if($femaleValue==0){
						echo "<td>0</td>";
					}else{
						echo "<td><a href='ohc_name.php?class=$Query&indicator=$indicator&group=female&sage=$startAge&eage=$endAge&sdate=$startDate&edate=$endDate' target='_blank'>" .$femaleValue. "</a></td>";
					}
					
					array_push($this->value_array,$maleValue,$femaleValue);
					break;
			}
		}
		
		public function displayTotal($Query, $service){
			if(isset($_GET['startMonth']) && isset($_GET['endMonth']) && isset($_GET['reportYear'])){
				$startDate = date("Y-m-d", mktime(0,0,0,$_GET['startMonth'],1,$_GET['reportYear']));
				$endDate = date("Y-m-d", mktime(0,0,0,($_GET['endMonth']+1),0,$_GET['reportYear']));

				for($i=0;$i<=11;$i++){
					$startAge = $this->array_ageStart[$i];
					$endAge = $this->array_ageEnd[$i];
					
					switch($Query){
						case 'totalAttend':
							
							$maleSql = "SELECT COUNT(DISTINCT consult_id) AS total FROM (SELECT consult_id, date_of_service AS date_of_dental, patient_id FROM m_dental_services UNION ALL SELECT consult_id, date_of_oral AS date_of_dental, patient_id FROM m_dental_patient_ohc_table_a UNION ALL SELECT consult_id, date_of_oral AS date_of_dental, patient_id FROM m_dental_patient_ohc UNION ALL SELECT consult_id, date_of_service AS date_of_dental, patient_id FROM m_dental_other_services) AS dental JOIN m_patient AS p ON p.patient_id = dental.patient_id WHERE round((to_days(date_of_dental)-to_days(p.patient_dob))/365,1) BETWEEN $startAge AND $endAge AND dental.date_of_dental BETWEEN '$startDate' AND '$endDate' AND p.patient_gender = 'M'";
							$femaleSql = "SELECT COUNT(DISTINCT consult_id) AS total FROM (SELECT consult_id, date_of_service AS date_of_dental, patient_id FROM m_dental_services UNION ALL SELECT consult_id, date_of_oral AS date_of_dental, patient_id FROM m_dental_patient_ohc_table_a UNION ALL SELECT consult_id, date_of_oral AS date_of_dental, patient_id FROM m_dental_patient_ohc UNION ALL SELECT consult_id, date_of_service AS date_of_dental, patient_id FROM m_dental_other_services) AS dental JOIN m_patient AS p ON p.patient_id = dental.patient_id WHERE round((to_days(date_of_dental)-to_days(p.patient_dob))/365,1) BETWEEN $startAge AND $endAge AND dental.date_of_dental BETWEEN '$startDate' AND '$endDate' AND p.patient_gender = 'F'";
							$pregSql = "SELECT COUNT(DISTINCT consult_id) AS total FROM m_dental_patient_ohc WHERE date_of_oral BETWEEN '$startDate' AND '$endDate' AND is_patient_pregnant = 'Y'";
							$totalSql = "SELECT COUNT(DISTINCT consult_id) AS total FROM (SELECT consult_id, date_of_service AS date_of_dental, patient_id FROM m_dental_services UNION ALL SELECT consult_id, date_of_oral AS date_of_dental, patient_id FROM m_dental_patient_ohc_table_a UNION ALL SELECT consult_id, date_of_oral AS date_of_dental, patient_id FROM m_dental_patient_ohc UNION ALL SELECT consult_id, date_of_service AS date_of_dental, patient_id FROM m_dental_other_services) AS dental JOIN m_patient AS p ON p.patient_id = dental.patient_id WHERE round((to_days(date_of_dental)-to_days(p.patient_dob))/365,1) BETWEEN $startAge AND $endAge AND dental.date_of_dental BETWEEN '$startDate' AND '$endDate'";
											  	
							$otherMaleSql = "SELECT COUNT(DISTINCT consult_id) AS total, CASE WHEN round((to_days(dental.date_of_dental)-to_days(p.patient_dob))/365,1) BETWEEN $startAge AND $endAge OR round((to_days(dental.date_of_dental)-to_days(p.patient_dob))/365,1) BETWEEN 25 AND 59.99 THEN 'others' END AS totalOthers FROM (SELECT consult_id, date_of_service AS date_of_dental, patient_id FROM m_dental_services UNION ALL SELECT consult_id, date_of_oral AS date_of_dental, patient_id FROM m_dental_patient_ohc_table_a UNION ALL SELECT consult_id, date_of_oral AS date_of_dental, patient_id FROM m_dental_patient_ohc UNION ALL SELECT consult_id, date_of_service AS date_of_dental, patient_id FROM m_dental_other_services) AS dental JOIN m_patient AS p ON p.patient_id = dental.patient_id WHERE dental.date_of_dental BETWEEN '$startDate' AND '$endDate' AND p.patient_gender = 'M' GROUP BY totalOthers HAVING totalOthers = 'others'";			  
							$otherFemaleSql = "SELECT COUNT(DISTINCT consult_id) AS total, CASE WHEN round((to_days(dental.date_of_dental)-to_days(p.patient_dob))/365,1) BETWEEN $startAge AND $endAge OR round((to_days(dental.date_of_dental)-to_days(p.patient_dob))/365,1) BETWEEN 25 AND 59.99 THEN 'others' END AS totalOthers FROM (SELECT consult_id, date_of_service AS date_of_dental, patient_id FROM m_dental_services UNION ALL SELECT consult_id, date_of_oral AS date_of_dental, patient_id FROM m_dental_patient_ohc_table_a UNION ALL SELECT consult_id, date_of_oral AS date_of_dental, patient_id FROM m_dental_patient_ohc UNION ALL SELECT consult_id, date_of_service AS date_of_dental, patient_id FROM m_dental_other_services) AS dental JOIN m_patient AS p ON p.patient_id = dental.patient_id WHERE dental.date_of_dental BETWEEN '$startDate' AND '$endDate' AND p.patient_gender = 'F' GROUP BY totalOthers HAVING totalOthers = 'others'";
							
							$indicator = "total attend";
							break;
						case 'totalExamined':

							$maleSql = "SELECT COUNT(DISTINCT dental.patient_id) AS total FROM (SELECT date_of_service AS date_of_dental, patient_id FROM m_dental_services UNION ALL SELECT date_of_oral AS date_of_dental, patient_id FROM m_dental_patient_ohc_table_a UNION ALL SELECT date_of_oral AS date_of_dental, patient_id FROM m_dental_patient_ohc UNION ALL SELECT date_of_service AS date_of_dental, patient_id FROM m_dental_other_services) AS dental JOIN m_patient AS p ON p.patient_id = dental.patient_id WHERE round((to_days(date_of_dental)-to_days(p.patient_dob))/365,1) BETWEEN $startAge AND $endAge AND dental.date_of_dental BETWEEN '$startDate' AND '$endDate' AND p.patient_gender = 'M'";
							$femaleSql = "SELECT COUNT(DISTINCT dental.patient_id) AS total FROM (SELECT date_of_service AS date_of_dental, patient_id FROM m_dental_services UNION ALL SELECT date_of_oral AS date_of_dental, patient_id FROM m_dental_patient_ohc_table_a UNION ALL SELECT date_of_oral AS date_of_dental, patient_id FROM m_dental_patient_ohc UNION ALL SELECT date_of_service AS date_of_dental, patient_id FROM m_dental_other_services) AS dental JOIN m_patient AS p ON p.patient_id = dental.patient_id WHERE round((to_days(date_of_dental)-to_days(p.patient_dob))/365,1) BETWEEN $startAge AND $endAge AND dental.date_of_dental BETWEEN '$startDate' AND '$endDate' AND p.patient_gender = 'F'";
							$pregSql = "SELECT COUNT(DISTINCT patient_id) AS total FROM m_dental_patient_ohc WHERE date_of_oral BETWEEN '$startDate' AND '$endDate' AND is_patient_pregnant = 'Y'";
							$totalSql = "SELECT COUNT(DISTINCT dental.patient_id) AS total FROM (SELECT date_of_service AS date_of_dental, patient_id FROM m_dental_services UNION ALL SELECT date_of_oral AS date_of_dental, patient_id FROM m_dental_patient_ohc_table_a UNION ALL SELECT date_of_oral AS date_of_dental, patient_id FROM m_dental_patient_ohc UNION ALL SELECT date_of_service AS date_of_dental, patient_id FROM m_dental_other_services) AS dental JOIN m_patient AS p ON p.patient_id = dental.patient_id WHERE round((to_days(date_of_dental)-to_days(p.patient_dob))/365,1) BETWEEN $startAge AND $endAge AND dental.date_of_dental BETWEEN '$startDate' AND '$endDate'";
											  	
							$otherMaleSql = "SELECT COUNT(DISTINCT dental.patient_id) AS total, CASE WHEN round((to_days(dental.date_of_dental)-to_days(p.patient_dob))/365,1) BETWEEN $startAge AND $endAge OR round((to_days(dental.date_of_dental)-to_days(p.patient_dob))/365,1) BETWEEN 24.99 AND 60 THEN 'others' END AS totalOthers FROM (SELECT date_of_service AS date_of_dental, patient_id FROM m_dental_services UNION ALL SELECT date_of_oral AS date_of_dental, patient_id FROM m_dental_patient_ohc_table_a UNION ALL SELECT date_of_oral AS date_of_dental, patient_id FROM m_dental_patient_ohc UNION ALL SELECT date_of_service AS date_of_dental, patient_id FROM m_dental_other_services) AS dental JOIN m_patient AS p ON p.patient_id = dental.patient_id WHERE dental.date_of_dental BETWEEN '$startDate' AND '$endDate' AND p.patient_gender = 'M' GROUP BY totalOthers HAVING totalOthers = 'others'";
							$otherFemaleSql = "SELECT COUNT(DISTINCT dental.patient_id) AS total, CASE WHEN round((to_days(dental.date_of_dental)-to_days(p.patient_dob))/365,1) BETWEEN $startAge AND $endAge OR round((to_days(dental.date_of_dental)-to_days(p.patient_dob))/365,1) BETWEEN 24.99 AND 60 THEN 'others' END AS totalOthers FROM (SELECT date_of_service AS date_of_dental, patient_id FROM m_dental_services UNION ALL SELECT date_of_oral AS date_of_dental, patient_id FROM m_dental_patient_ohc_table_a UNION ALL SELECT date_of_oral AS date_of_dental, patient_id FROM m_dental_patient_ohc UNION ALL SELECT date_of_service AS date_of_dental, patient_id FROM m_dental_other_services) AS dental JOIN m_patient AS p ON p.patient_id = dental.patient_id WHERE dental.date_of_dental BETWEEN '$startDate' AND '$endDate' AND p.patient_gender = 'F' GROUP BY totalOthers HAVING totalOthers = 'others'";
							
							$indicator = "total examined";
							break;
						case 'oralHealth1-5':
							$maleSql = "SELECT COUNT(DISTINCT ohc.patient_id) AS total FROM m_dental_patient_ohc_table_a AS ohc JOIN m_patient AS px On ohc.patient_id = px.patient_id WHERE round((to_days(ohc.date_of_oral)-to_days(px.patient_dob))/365,1) BETWEEN $startAge AND $endAge AND ohc.date_of_oral BETWEEN '$startDate' AND '$endDate' AND $service AND px.patient_gender = 'M'";
							$femaleSql = "SELECT COUNT(DISTINCT ohc.patient_id) AS total FROM m_dental_patient_ohc_table_a AS ohc JOIN m_patient AS px On ohc.patient_id = px.patient_id WHERE round((to_days(ohc.date_of_oral)-to_days(px.patient_dob))/365,1) BETWEEN $startAge AND $endAge AND ohc.date_of_oral BETWEEN '$startDate' AND '$endDate' AND $service AND px.patient_gender = 'F'";
							$pregSql = "SELECT COUNT(DISTINCT ohc.patient_id) AS total FROM m_dental_patient_ohc_table_a AS ohc JOIN m_patient AS px ON ohc.patient_id = px.patient_id JOIN m_dental_patient_ohc AS preg ON preg.consult_id = ohc.consult_id WHERE ohc.date_of_oral BETWEEN '$startDate' AND '$endDate' AND $service AND preg.is_patient_pregnant = 'Y'";
							$totalSql = "SELECT COUNT(DISTINCT ohc.patient_id) AS total FROM m_dental_patient_ohc_table_a AS ohc JOIN m_patient AS px ON px.patient_id = ohc.patient_id WHERE round((to_days(ohc.date_of_oral)-to_days(px.patient_dob))/365,1) BETWEEN $startAge AND $endAge AND ohc.date_of_oral BETWEEN '$startDate' AND '$endDate' AND $service";
							
							$otherSql = "SELECT COUNT(DISTINCT ohc.patient_id) AS total, CASE WHEN round((to_days(ohc.date_of_oral)-to_days(px.patient_dob))/365,1) BETWEEN $startAge AND $endAge OR round((to_days(ohc.date_of_oral)-to_days(px.patient_dob))/365,1) BETWEEN 24.99 AND 60 THEN 'others' END AS totalOthers FROM m_dental_patient_ohc_table_a AS ohc JOIN m_patient AS px ON px.patient_id = ohc.patient_id";
							$otherMaleSql = $otherSql . " WHERE px.patient_gender = 'M' AND ohc.date_of_oral BETWEEN '$startDate' AND '$endDate' AND $service GROUP BY totalOthers HAVING totalOthers = 'others'";
							$otherFemaleSql = $otherSql . " WHERE px.patient_gender = 'F' AND ohc.date_of_oral BETWEEN '$startDate' AND '$endDate' AND $service GROUP BY totalOthers HAVING totalOthers = 'others'";
							
							switch($service){
								case "dental_caries = 'YES'":
									$indicator = "dental carries";
									break;
								case "gingivitis_periodontal_disease = 'YES'":
									$indicator = "gingivitis periodontal disease";
									break;
								case "debris = 'YES'":
									$indicator = "debris";
									break;
								case "calculus = 'YES'":
									$indicator = "calculus";
									break;
								case "(cleft_lip_palate = 'YES' OR dento_facial_anomaly = 'YES' OR abnormal_growth = 'YES')":
									$indicator = "dento-facial anomalies";
									break;
								default:
									die("Error: Unable to Classify Indicator");
									break;
							}
							break;
						case 'oralHealth6-7':
							
							//SELECT count(patient_id) FROM m_patient WHERE round((to_days(ohc.date_of_oral)-to_days(p.patient_dob))/365,1) < 18 and patient_gender='M' AND registration_date BETWEEN '$newSDate' AND '$newEDate'
							$maleSql = "SELECT COUNT(ohc.patient_id) AS total FROM m_dental_patient_ohc AS ohc JOIN m_patient AS p ON p.patient_id = ohc.patient_id WHERE round((to_days(ohc.date_of_oral)-to_days(p.patient_dob))/365,1) BETWEEN $startAge AND $endAge AND ohc.date_of_oral BETWEEN '$startDate' AND '$endDate' AND ohc.tooth_condition = '$service' AND p.patient_gender = 'M'";
							$femaleSql = "SELECT COUNT(ohc.patient_id) AS total FROM m_dental_patient_ohc AS ohc JOIN m_patient AS p ON p.patient_id = ohc.patient_id WHERE round((to_days(ohc.date_of_oral)-to_days(p.patient_dob))/365,1) BETWEEN $startAge AND $endAge AND ohc.date_of_oral BETWEEN '$startDate' AND '$endDate' AND ohc.tooth_condition = '$service' AND p.patient_gender = 'F'";
							$pregSql = "SELECT COUNT(ohc.patient_id) AS total FROM m_dental_patient_ohc AS ohc WHERE ohc.date_of_oral BETWEEN '$startDate' AND '$endDate' AND ohc.tooth_condition = '$service' AND ohc.is_patient_pregnant = 'Y'";
							$totalSql = "SELECT COUNT(ohc.patient_id) AS total FROM m_dental_patient_ohc AS ohc JOIN m_patient AS p ON p.patient_id = ohc.patient_id WHERE round((to_days(ohc.date_of_oral)-to_days(p.patient_dob))/365,1) BETWEEN $startAge AND $endAge AND ohc.date_of_oral BETWEEN '$startDate' AND '$endDate' AND ohc.tooth_condition = '$service'";
							
							$otherSql = "SELECT COUNT(ohc.patient_id) AS total, CASE WHEN round((to_days(ohc.date_of_oral)-to_days(p.patient_dob))/365,1) BETWEEN $startAge AND $endAge OR round((to_days(ohc.date_of_oral)-to_days(p.patient_dob))/365,1) BETWEEN 24.99 AND 60 THEN 'OTHER' END AS totalOthers FROM m_dental_patient_ohc AS ohc JOIN m_patient AS p ON p.patient_id=ohc.patient_id";
							$otherMaleSql = $otherSql . " WHERE p.patient_gender = 'M' and ohc.date_of_oral BETWEEN '$startDate' AND '$endDate' AND ohc.tooth_condition = '$service' GROUP BY totalOthers HAVING totalOthers = 'OTHER'";
							$otherFemaleSql = $otherSql . " WHERE p.patient_gender = 'F' and ohc.date_of_oral BETWEEN '$startDate' AND '$endDate' AND ohc.tooth_condition = '$service' GROUP BY totalOthers HAVING totalOthers = 'OTHER'";
							
							$indicator = "$service";
							break;
						case 'service1':
							$maleSql = "SELECT COUNT(DISTINCT service.patient_id) AS total FROM m_dental_services AS service JOIN m_patient AS p ON p.patient_id = service.patient_id WHERE round((to_days(service.date_of_service)-to_days(p.patient_dob))/365,1) BETWEEN $startAge AND $endAge AND service.date_of_service BETWEEN '$startDate' AND '$endDate' AND service.service_provided = '$service' AND p.patient_gender = 'M'";
							$femaleSql = "SELECT COUNT(DISTINCT service.patient_id) AS total FROM m_dental_services AS service JOIN m_patient AS p ON p.patient_id = service.patient_id WHERE round((to_days(service.date_of_service)-to_days(p.patient_dob))/365,1) BETWEEN $startAge AND $endAge AND service.date_of_service BETWEEN '$startDate' AND '$endDate' AND service.service_provided = '$service' AND p.patient_gender = 'F'";
							$pregSql = "SELECT COUNT(DISTINCT service.patient_id) AS total FROM m_dental_services AS service JOIN m_dental_patient_ohc AS ohc ON service.patient_id = ohc.patient_id WHERE service.date_of_service BETWEEN '$startDate' AND '$endDate' AND service.service_provided = '$service' AND ohc.is_patient_pregnant = 'Y'";
							$totalSql = "SELECT COUNT(DISTINCT service.patient_id) AS total FROM m_dental_services AS service JOIN m_patient AS p ON p.patient_id = service.patient_id WHERE round((to_days(service.date_of_service)-to_days(p.patient_dob))/365,1) BETWEEN $startAge AND $endAge AND service.date_of_service BETWEEN '$startDate' AND '$endDate' AND service.service_provided = '$service'";
							
							$otherSql = "SELECT COUNT(DISTINCT service.patient_id) AS total, CASE WHEN round((to_days(service.date_of_service)-to_days(p.patient_dob))/365,1) BETWEEN $startAge AND $endAge OR round((to_days(service.date_of_service)-to_days(p.patient_dob))/365,1) BETWEEN 24.99 AND 60 THEN 'OTHER' END AS totalOthers FROM m_dental_services AS service JOIN m_patient AS p ON p.patient_id = service.patient_id";
							$otherMaleSql = $otherSql . " WHERE p.patient_gender = 'M' AND service.date_of_service BETWEEN '$startDate' AND '$endDate' AND service.service_provided = '$service' GROUP BY totalOthers HAVING totalOthers = 'OTHER'";
							$otherFemaleSql = $otherSql . " WHERE p.patient_gender = 'F' AND service.date_of_service BETWEEN '$startDate' AND '$endDate' AND service.service_provided = '$service' GROUP BY totalOthers HAVING totalOthers = 'OTHER'";
							
							$indicator = "$service";
							break;
						case 'service2':
							$maleSql = "SELECT COUNT(DISTINCT service.patient_id) AS total FROM m_dental_other_services AS service JOIN m_patient AS p ON p.patient_id = service.patient_id WHERE round((to_days(service.date_of_service)-to_days(p.patient_dob))/365,1) BETWEEN $startAge AND $endAge AND service.date_of_service BETWEEN '$startDate' AND '$endDate' AND service.$service = 'YES' AND p.patient_gender = 'M'";
							$femaleSql = "SELECT COUNT(DISTINCT service.patient_id) AS total FROM m_dental_other_services AS service JOIN m_patient AS p ON p.patient_id = service.patient_id WHERE round((to_days(service.date_of_service)-to_days(p.patient_dob))/365,1) BETWEEN $startAge AND $endAge AND service.date_of_service BETWEEN '$startDate' AND '$endDate' AND service.$service = 'YES' AND p.patient_gender = 'F'";
							$pregSql = "SELECT COUNT(DISTINCT service.patient_id) AS total FROM m_dental_other_services AS service JOIN m_dental_patient_ohc AS ohc ON ohc.patient_id = service.patient_id WHERE service.date_of_service BETWEEN '$startDate' AND '$endDate' AND service.$service = 'YES' AND ohc.is_patient_pregnant = 'Y'";
							$totalSql = "SELECT COUNT(DISTINCT service.patient_id) AS total FROM m_dental_other_services AS service JOIN m_patient AS p ON p.patient_id = service.patient_id WHERE round((to_days(service.date_of_service)-to_days(p.patient_dob))/365,1) BETWEEN $startAge AND $endAge AND service.date_of_service BETWEEN '$startDate' AND '$endDate' AND service.$service = 'YES'";
						
							$otherSql = "SELECT COUNT(DISTINCT service.patient_id) AS total, CASE WHEN round((to_days(service.date_of_service)-to_days(p.patient_dob))/365,1) BETWEEN $startAge AND $endAge OR round((to_days(service.date_of_service)-to_days(p.patient_dob))/365,1) BETWEEN 24.99 AND 60 THEN 'OTHER' END AS totalOthers FROM m_dental_other_services AS service JOIN m_patient AS p ON p.patient_id = service.patient_id";
							$otherMaleSql = $otherSql . " WHERE p.patient_gender = 'M' AND service.date_of_service BETWEEN '$startDate' AND '$endDate' AND service.$service = 'YES' GROUP BY totalOthers HAVING totalOthers = 'OTHER'";
							$otherFemaleSql = $otherSql . " WHERE p.patient_gender = 'F' AND service.date_of_service BETWEEN '$startDate' AND '$endDate' AND service.$service = 'YES' GROUP BY totalOthers HAVING totalOthers = 'OTHER'";
							
							$indicator = "$service";
							break;
						case 'ofc':
							$maleSql = "SELECT COUNT(DISTINCT fhsis.patient_id) AS total FROM m_dental_fhsis AS fhsis JOIN m_patient AS p ON p.patient_id = fhsis.patient_id WHERE ROUND((to_days(fhsis.date_of_consultation)-to_days(p.patient_dob))/365,1) BETWEEN $startAge AND $endAge AND fhsis.date_of_consultation BETWEEN '$startDate' AND '$endDate' AND fhsis.indicator = 1 AND fhsis.indicator_qualified = 'YES' AND p.patient_gender = 'M'";
							$femaleSql = "SELECT COUNT(DISTINCT fhsis.patient_id) AS total FROM m_dental_fhsis AS fhsis JOIN m_patient AS p ON p.patient_id = fhsis.patient_id WHERE ROUND((to_days(fhsis.date_of_consultation)-to_days(p.patient_dob))/365,1) BETWEEN $startAge AND $endAge AND fhsis.date_of_consultation BETWEEN '$startDate' AND '$endDate' AND fhsis.indicator = 1 AND fhsis.indicator_qualified = 'YES' AND p.patient_gender = 'F'";
							$pregSql = "SELECT COUNT(DISTINCT fhsis.patient_id) AS total FROM m_dental_fhsis AS fhsis WHERE indicator = 10"; 
							$totalSql = "SELECT COUNT(DISTINCT fhsis.patient_id) AS total FROM m_dental_fhsis AS fhsis JOIN m_patient AS p ON p.patient_id = fhsis.patient_id WHERE ROUND((to_days(fhsis.date_of_consultation)-to_days(p.patient_dob))/365,1) BETWEEN $startAge AND $endAge AND fhsis.date_of_consultation BETWEEN '$startDate' AND '$endDate' AND fhsis.indicator = 1 AND fhsis.indicator_qualified = 'YES'";
							
							$otherMaleSql = "SELECT COUNT(DISTINCT fhsis.patient_id) AS total FROM m_dental_fhsis AS fhsis WHERE indicator = 10"; 
							$otherFemaleSql = "SELECT COUNT(DISTINCT fhsis.patient_id) AS total FROM m_dental_fhsis AS fhsis WHERE indicator = 10"; 
							
							$indicator = "ofc";
							
							break;
						default:
							die ("Error");
							break;	
					}
					$this->displayQuery($maleSql,$femaleSql,$pregSql,$totalSql,$otherMaleSql,$otherFemaleSql,$i,$indicator, $startAge, $endAge, $startDate, $endDate, $Query);
				}
			}
		}	
	}
	$query = new mysqlQuery;
}	
?>
</head>
<body>
	<form name='ohc' method='GET' action=''>
		<?php
			echo "<div style='width:100%; display:block'><div style='margin:0 auto; width:450px; font:18px arial bold'>Consolidated Oral Health Status and Service Report</div></div>";
			echo "FROM ";
				echo "<select name='startMonth'>";
					$nameMonth = 'startMonth'; $query->disMonth($nameMonth); 
				echo "</select>";
			echo " TO ";
				echo "<select name='endMonth'>";
					$nameMonth = 'endMonth'; $query->disMonth($nameMonth); 
				echo "</select>";
				echo "<select name='reportYear'>";
					$nameYear = 'reportYear'; $query->disYear($nameYear);
				echo "</select>";
			echo "<input id='submitQ' type='submit' value='submit' />";
			//echo "<input name='print' class='submitQ' type='button' onClick='' value='print friendly' />";
			echo "<br />";
			echo "Center for Health Development : " . $_SESSION["lgu"] . "<br />";
			echo "Municipality/City/Province : " . $_SESSION["province"];
			
		?>
		
	</form>

	<table style='margin:-13px 0 0 0;'>
		<tr id='grouping'>
			<th rowspan='3' width='350px'></th>
			<th colspan='12' width='500px'>UNDER SIX CHILDREN</th>
			<th colspan='2' rowspan='2' width='100px'>YOUNG ADULT<br />10-24 Y/O</th>
			<th colspan='2' rowspan='2' width='100px'>OLDER PERSON<br />60+ Y/O</th>
			<th rowspan='3' width='70px'>PREGNANT<br />WOMEN</th>
			<th colspan='2' rowspan='2' width='100px'>OTHER GROUPS<br />6-9 and OTHER ADULTS</th>
			<th colspan='2' rowspan='2' width='100px'>TOTAL<br />ALL AGES</th>
			<th rowspan='3' width='70px'>GRAND<br />TOTAL</th>
		</tr>
		<tr>
			<th colspan='2' width='100px'>1</th>
			<th colspan='2' width='100px'>2</th>
			<th colspan='2' width='100px'>3</th>
			<th colspan='2' width='100px'>4</th>
			<th colspan='2' width='100px'>5</th>
			<th colspan='2' width='100px'>TOTAL</th>
		</tr>
		
		<tr>
			<?php
				for($i=1;$i<=10;$i++){
					echo "<th width='50px'>M</th>";
					echo "<th width='50px'>F</th>";
				}
			?>
		</tr>
		
		<tr>
			<th class='indicator-header'>No. of Person Attended</th>
			<?php
				$query->displayTotal('totalAttend','');
			?>
		</tr>
		
		<tr>
			<th class='indicator-header'>No. of Person Examined</th>
			<?php
				$query->displayTotal('totalExamined','');
			?>
		</tr>
		
		<tr>
			<th class='indicator-header'>A. Oral Health Status</th>
		</tr>
		
		<tr>
			<th class='indicator'>1. Total No. with Dental Caries</th>
				<?php
					$query->displayTotal('oralHealth1-5',"dental_caries = 'YES'");
				?>
		</tr>
		
		<tr>
			<th class='indicator'>2. Total No. with Gingivitis/Perio Disease</th>
				<?php
					$query->displayTotal('oralHealth1-5',"gingivitis_periodontal_disease = 'YES'");
				?>
		</tr>
		
		<tr>
			<th class='indicator'>3. Total No. with Oral Debris</th>
				<?php
					$query->displayTotal('oralHealth1-5',"debris = 'YES'");
				?>
		</tr>
		
		<tr>
			<th class='indicator'>4. Total No. with Calculus</th>
				<?php
					$query->displayTotal('oralHealth1-5',"calculus = 'YES'");
				?>
		</tr>
		
		<tr>
			<th class='indicator'>5. Total No. with Dento-facial Anomalies<br />(cleft lip/palate, Maloclussion, etc.)</th>
				<?php
					$query->displayTotal('oralHealth1-5',"(cleft_lip_palate = 'YES' OR dento_facial_anomaly = 'YES' OR abnormal_growth = 'YES')");
				?>
		</tr>
		
		<tr>
			<th class='indicator'>6. Total df</th>
		</tr>
		
		<tr>
			<th class='indicator-sub'>a. Total decayed (d)</th>
				<?php
					$query->displayTotal('oralHealth6-7','d');
				?>
		</tr>
		
		<tr>
			<th class='indicator-sub'>b. Total filled (f)</th>
				<?php
					$query->displayTotal('oralHealth6-7','f');
				?>
		</tr>
		
		<tr>
			<th class='indicator'>7. Total DMF</th>
		</tr>
		
		<tr>
			<th class='indicator-sub'>a. Total Decayed (D)</th>
				<?php
					$query->displayTotal('oralHealth6-7','D');
				?>
		</tr>
		
		<tr>
			<th class='indicator-sub'>b. Total Missing (M)</th>
				<?php
					$query->displayTotal('oralHealth6-7','M');
				?>
		</tr>
		
		<tr>
			<th class='indicator-sub'>c. Total Filled (F)</th>
				<?php
					$query->displayTotal('oralHealth6-7','F');
				?>
		</tr>
		
		<tr>
			<th class='indicator-header'>B. Services Rendered</th>
		</tr>
		
		<tr>
			<th class='indicator'>1. No. Given OP/Scalling</th>
				<?php 
					$query->displayTotal('service1','OP');
				?>
		</tr>
		
		<tr>
			<th class='indicator'>2. No. Given Permanent Fillings</th>
				<?php 
					$query->displayTotal('service1','PF');
				?>
		</tr>
		
		<tr>
			<th class='indicator'>3. No. Given Temporary Fillings</th>
				<?php 
					$query->displayTotal('service1','TF');
				?>
		</tr>
		
		<tr>
			<th class='indicator'>4. No. Given Extraction</th>
				<?php 
					$query->displayTotal('service1','X')
				?>
		</tr>
		
		<tr>
			<th class='indicator'>5. No. Given Gum Treatment</th>
				<?php 
					$query->displayTotal('service2','gum_treatment')
				?>
		</tr>
		
		<tr>
			<th class='indicator'>6. No. Given Sealant</th>
				<?php 
					$query->displayTotal('service1','S')
				?>
		</tr>
		
		<tr>
			<th class='indicator'>7. No. Completed Flouride Theraphy</th>
				<?php 
					$query->displayTotal('service1','FL')
				?>
		</tr>
		
		<tr>
			<th class='indicator'>8. No. Given Post Operative Treatment</th>
				<?php 
					$query->displayTotal('service2','out_treatment_of_post_extraction_complications')
				?>
		</tr>
		
		<tr>
			<th class='indicator'>9. No. of Patient with Oral Abscess Drained</th>
				<?php 
					$query->displayTotal('service2','out_drainage_of_localized_oral_abscess')
				?>
		</tr>
		
		<tr>
			<th class='indicator'>10. No Given Other Services</th>
				<?php 
					$query->displayTotal('service1','O')
				?>
		</tr>
		
		<tr>
			<th class='indicator'>11. No. Reffered</th>
				<?php 
					$query->displayTotal('service2','out_referral_of_complicates_cases')
				?>
		</tr>
		
		<tr>
			<th class='indicator'>12. No. Given Counselling/Education<br />on Tobacco, OH, Diet, etc</th>
				<?php 
					$query->displayTotal('service2','education_and_counselling')
				?>
		</tr>
		
		<tr>
			<th class='indicator'>13. No. Under-Six Children Completed<br />Toothbrushing Drill</th><?php ?>
		</tr>
		
		<tr>
			<th class='indicator-header'>C. No. of Orally Fit Children (OFC)</th>
				<?php 
					$query->displayTotal('ofc','')
				?>
		</tr>		
	</table>
	
	<?php
		echo "<br />";
		echo "<div style='float:right'>";
		echo "Submitted by: ____________________________________ <br />";
		echo "</div>";
	?>
</body>
</html>
