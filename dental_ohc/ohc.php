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
	</style>
	
<?php

if(!isset($_SESSION["userid"])){
	die("You must log in to WAH-EMR!");
}else{
	class mysqlConnect {
		public $dbHost = "localhost";
		public $dbCon;
				
		public function __construct (){
			$this->_dbOpen();
		}

		private function _dbOpen(){
			$this->dbCon = mysql_connect($this->dbHost,$_SESSION["dbuser"],$_SESSION["dbpass"]);
			if (!$this->dbCon || !mysql_select_db($_SESSION["dbname"], $this->dbCon)) {
				die ("Connection Failed" . mysql_error() );
			}
		}
			
		public function _dbQuery($dbSql){
			$dbQuery = mysql_query($dbSql, $this->dbCon);
			$this->_dbConfirm($dbQuery);
				
			return $dbQuery;				
		}

		public function _dbFetch($dbQuery){
			return mysql_fetch_assoc($dbQuery);
		}

		private function _dbConfirm($dbQuery){
			if( !$dbQuery ){
				echo "You have an error on your last SQL Statement " . mysql_error();
			}
		}
	}


	class mysqlQuery extends mysqlConnect{
		public $array_ageStart = array(0, 1.99, 2.99, 3.99, 4.99, 0, 9.99, 59.99, 0, 5.99, 0, 0);
		public $array_ageEnd = array  (2, 3, 4, 5, 6, 6, 24.99, 300, 300, 10, 300, 300);

		public function disMonth($nameMonth){
			for($month=1;$month<=12;$month++){
				$monthName = date("F", mktime(0,0,0,$month,1,0));
				echo "<option value='$month' ".($_GET[$nameMonth]== $month ? 'selected' : '').">$monthName</option>";
			}
		}

		public function disYear($nameYear){
			$presentYear = date("Y");
			for($year=2005;$year<=$presentYear;$year++){
				if(!isset($_GET['startYear'])){
					echo "<option value='$year' ".($_GET[$nameYear] == $presentYear ? 'selected' : '').">$year</option>";
				}else{
					echo "<option value='$year' ".($_GET[$nameYear] == $year ? 'selected' : '').">$year</option>";
				}
			}
		}

		public function displayQuery($maleSql,$femaleSql,$pregSql,$totalSql,$otherMaleSql,$otherFemaleSql,$i){
			switch($i){
				case 8:
					$pregQuery = $this->_dbQuery($pregSql);
					$pregResult = $this->_dbFetch($pregQuery); //Total Pregnant Attended
					echo "<td>" .$pregResult[total]. "</td>";
					break;
				case 9:
					$otherMaleQuery = $this->_dbQuery($otherMaleSql);
					$otherMaleResult = $this->_dbFetch($otherMaleQuery);//Total Others Male
					echo "<td>" .($otherMaleResult[total] == 0 ? 0 : $otherMaleResult[total]). "</td>";

					$otherFemaleQuery = $this->_dbQuery($otherFemaleSql);
					$otherFemaleResult = $this->_dbFetch($otherFemaleQuery);//Total Others Female
					echo "<td>" .($otherFemaleResult[total] == 0 ? 0 : $otherFemaleResult[total]). "</td>";
					break;
				case 11:
					$totalQuery = $this->_dbQuery($totalSql);
					$totalResult = $this->_dbFetch($totalQuery); //Total Attended
					echo "<td>" .$totalResult[total]. "</td>";
					break;
				default:
					$maleQuery = $this->_dbQuery($maleSql);
					$maleResult = $this->_dbFetch($maleQuery); //Total Male Attended
					$femaleQuery = $this->_dbQuery($femaleSql);
					$femaleResult = $this->_dbFetch($femaleQuery); //Total Female Attended
					echo "<td>" .$maleResult[total]. "</td>";
					echo "<td>" .$femaleResult[total]. "</td>";
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
							//round((to_days(ohc.date_of_oral)-to_days(p.patient_dob))/365,1)
							$maleSql   = "SELECT COUNT(consult_id) AS total FROM m_dental_fhsis WHERE age BETWEEN $startAge AND $endAge AND gender = 'M' AND date_of_consultation BETWEEN '$startDate' AND '$endDate'";
							$femaleSql = "SELECT COUNT(consult_id) AS total FROM m_dental_fhsis WHERE age BETWEEN $startAge AND $endAge AND gender = 'F' AND date_of_consultation BETWEEN '$startDate' AND '$endDate'";
							$pregSql   = "SELECT COUNT(DISTINCT dental.consult_id) AS total FROM m_dental_fhsis AS dental JOIN m_dental_patient_ohc AS ohc ON ohc.consult_id = dental.consult_id WHERE date_of_consultation BETWEEN '$startDate' AND '$endDate' AND ohc.is_patient_pregnant = 'Y'";
							$totalSql  = "SELECT COUNT(consult_id) AS total FROM m_dental_fhsis WHERE age BETWEEN $startAge AND $endAge AND date_of_consultation BETWEEN '$startDate' AND '$endDate'";
					
							$otherSql  = "SELECT COUNT(consult_id) AS total, CASE	WHEN age BETWEEN $startAge AND $endAge OR age BETWEEN 24.99 AND 60 THEN 'OTHER' END AS totalOthers FROM m_dental_fhsis";
							$otherMaleSql= $otherSql . " WHERE gender = 'M' AND date_of_consultation BETWEEN '$startDate' AND '$endDate' GROUP BY totalOthers HAVING totalOthers= 'OTHER'";
							$otherFemaleSql = $otherSql . " WHERE gender = 'F' AND date_of_consultation BETWEEN '$startDate' AND '$endDate' GROUP BY totalOthers HAVING totalOthers= 'OTHER'";	
							break;
						case 'totalExamined':

							$maleSql = "SELECT COUNT(DISTINCT ohc.consult_id) AS total FROM m_dental_patient_ohc AS ohc JOIN m_dental_fhsis AS dental ON ohc.consult_id = dental.consult_id WHERE dental.age BETWEEN $startAge AND $endAge AND ohc.date_of_oral BETWEEN '$startDate' AND '$endDate' and gender = 'M'";
							$femaleSql = "SELECT COUNT(DISTINCT ohc.consult_id) AS total FROM m_dental_patient_ohc AS ohc JOIN m_dental_fhsis AS dental ON ohc.consult_id = dental.consult_id WHERE dental.age BETWEEN $startAge AND $endAge AND ohc.date_of_oral BETWEEN '$startDate' AND '$endDate' and gender = 'F'";
							$pregSql = "SELECT COUNT(DISTINCT ohc.consult_id) AS total FROM m_dental_patient_ohc AS ohc JOIN m_dental_fhsis AS dental ON ohc.consult_id = dental.consult_id WHERE ohc.date_of_oral BETWEEN '$startDate' AND '$endDate' AND is_patient_pregnant = 'Y'";
							$totalSql = "SELECT COUNT(DISTINCT ohc.consult_id) AS total FROM m_dental_patient_ohc AS ohc JOIN m_dental_fhsis AS dental ON ohc.consult_id = dental.consult_id WHERE dental.age BETWEEN $startAge AND $endAge AND ohc.date_of_oral BETWEEN '$startDate' AND '$endDate'";
						
							$otherSql = "SELECT COUNT(DISTINCT ohc.consult_id) AS total, CASE WHEN dental.age BETWEEN $startAge AND $endAge OR dental.age BETWEEN 24.99 AND 60 THEN 'OTHER' END AS totalOthers FROM m_dental_fhsis AS dental JOIN m_dental_patient_ohc AS ohc ON dental.consult_id = ohc.consult_id";
							$otherMaleSql= $otherSql . " WHERE gender = 'M' AND date_of_consultation BETWEEN '$startDate' AND '$endDate' GROUP BY totalOthers HAVING totalOthers= 'OTHER'";
							$otherFemaleSql= $otherSql . " WHERE gender = 'F' AND date_of_oral BETWEEN '$startDate' AND '$endDate' GROUP BY totalOthers HAVING totalOthers= 'OTHER'";
							break;
						case 'oralHealth1-5':
							$maleSql = "SELECT COUNT(DISTINCT ohctable.consult_id) AS total FROM m_dental_patient_ohc_table_a AS ohctable JOIN m_dental_fhsis AS dental ON ohctable.consult_id=dental.consult_id WHERE dental.age BETWEEN $startAge AND $endAge AND ohctable.date_of_oral BETWEEN '$startDate' AND '$endDate' AND $service = 'YES' AND gender ='M'";
							$femaleSql = "SELECT COUNT(DISTINCT ohctable.consult_id) AS total FROM m_dental_patient_ohc_table_a AS ohctable JOIN m_dental_fhsis AS dental ON ohctable.consult_id=dental.consult_id WHERE dental.age BETWEEN $startAge AND $endAge AND ohctable.date_of_oral BETWEEN '$startDate' AND '$endDate' AND $service = 'YES' AND gender ='F'";
							$pregSql = "SELECT COUNT(DISTINCT ohctable.consult_id) AS total FROM m_dental_patient_ohc_table_a AS ohctable JOIN m_dental_patient_ohc AS ohc ON ohctable.consult_id = ohc.consult_id WHERE ohctable.date_of_oral BETWEEN '$startDate' AND '$endDate' AND $service = 'YES' AND ohc.is_patient_pregnant = 'Y'";
							$totalSql = "SELECT COUNT(DISTINCT ohctable.consult_id) AS total FROM m_dental_patient_ohc_table_a AS ohctable JOIN m_dental_fhsis AS dental ON ohctable.consult_id=dental.consult_id WHERE dental.age BETWEEN $startAge AND $endAge AND ohctable.date_of_oral BETWEEN '$startDate' AND '$endDate' AND $service = 'YES'";
							
							$otherSql = "SELECT COUNT(DISTINCT ohctable.consult_id), CASE WHEN dental.age BETWEEN $startAge AND $endAge OR dental.age BETWEEN 24.99 AND 60 THEN 'OTHER' END AS totalOthers FROM m_dental_patient_ohc_table_a AS ohctable JOIN m_dental_fhsis AS dental ON dental.consult_id = ohctable.consult_id";
							$otherMaleSql = $otherSql . " WHERE gender = 'M' AND ohctable.date_of_oral BETWEEN '$startDate' AND '$endDate' AND $service = 'YES' GROUP BY totalOthers HAVING totalOthers = 'OTHER'";
							$otherFemaleSql = $otherSql . " WHERE gender = 'F' AND ohctable.date_of_oral BETWEEN '$startDate' AND '$endDate' AND $service = 'YES' GROUP BY totalOthers HAVING totalOthers = 'OTHER'";
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
							break;
						default:
							die ("Error");
							break;	
					}
					$this->displayQuery($maleSql,$femaleSql,$pregSql,$totalSql,$otherMaleSql,$otherFemaleSql,$i);
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
		?>
		<input id='submitQ' type='submit' value='submit' />
	</form>

	<table>
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
					$query->displayTotal('oralHealth1-5','dental_caries');
				?>
		</tr>
		
		<tr>
			<th class='indicator'>2. Total No. with Gingivitis/Pano Disease</th>
				<?php
					$query->displayTotal('oralHealth1-5','gingivitis_periodontal_disease');
				?>
		</tr>
		
		<tr>
			<th class='indicator'>3. Total No. with Oral Debris</th>
				<?php
					$query->displayTotal('oralHealth1-5','debris');
				?>
		</tr>
		
		<tr>
			<th class='indicator'>4. Total No. with Calculus</th>
				<?php
					$query->displayTotal('oralHealth1-5','calculus');
				?>
		</tr>
		
		<tr>
			<th class='indicator'>5. Total No. with Dento-facial Anomalies<br />(cleft lip/palate, Maloclussion, etc.)</th>
				<?php
					$query->displayTotal('oralHealth1-5','cleft_lip_palate');
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
			<th class='indicator-header'>B. Services Rendered</th><?php ?>
		</tr>
		
		<tr>
			<th class='indicator'>1. No. Given OP/Scalling</th><?php ?>
		</tr>
		
		<tr>
			<th class='indicator'>2. No. Given Permanent Fillings</th><?php ?>
		</tr>
		
		<tr>
			<th class='indicator'>3. No. Given Temporary Fillings</th><?php ?>
		</tr>
		
		<tr>
			<th class='indicator'>4. No. Given Extraction</th><?php ?>
		</tr>
		
		<tr>
			<th class='indicator'>5. No. Given Gum Treatment</th><?php ?>
		</tr>
		
		<tr>
			<th class='indicator'>6. No. Given Sealant</th><?php ?>
		</tr>
		
		<tr>
			<th class='indicator'>7. No. Completed Flouride Theraphy</th><?php ?>
		</tr>
		
		<tr>
			<th class='indicator'>8. No. Given Post Operative Treatment</th><?php ?>
		</tr>
		
		<tr>
			<th class='indicator'>9. No. of Patient with Oral Abscess Drained</th><?php ?>
		</tr>
		
		<tr>
			<th class='indicator'>10. No Given Other Services</th><?php ?>
		</tr>
		
		<tr>
			<th class='indicator'>11. No. Reffered</th><?php ?>
		</tr>
		
		<tr>
			<th class='indicator'>12. No. Given Counselling/Education<br />on Tobacco, OH, Diet, etc</th><?php ?>
		</tr>
		
		<tr>
			<th class='indicator'>13. No. Under-Six Children Completed<br />Toothbrushing Drill</th><?php ?>
		</tr>
		
		<tr>
			<th class='indicator-header'>C. No. of Orally Fit Children (OFC)</th><?php ?>
		</tr>		
	</table>
</body>
</html>
