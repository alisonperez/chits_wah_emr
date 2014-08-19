<html>

<head>
	<title>Morbidity Disease</title>
	<style type="text/css">
		table, tr, td, th {border-collapse:collapse; border:1px solid #000000; }
		table {width:
		.class_name {width:350px; display:block;)
		th div.val {width:100px; height:30px; display:block; }
		.val-total {width:60px; height:30px; display:inline-block;}
	</style>
	<?php
	//if(!isset($_SESSION["userid"])){
		//die("You must log in to WAH-EMR!");
	//}else{
		class mysqlConnect {
			public $dbHost = "localhost";
			public $dbCon;
				
			public function __construct (){
				$this->_dbOpen();
			}

			private function _dbOpen(){
				$this->dbCon = mysql_connect($this->dbHost,$_SESSION["dbuser"],$_SESSION["dbpass"]);
				//if (!$this->dbCon || !mysql_select_db($_SESSION["dbname"], $this->dbCon)) {
				//	die ("Connection Failed" . mysql_error() );
				//}
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
	
		class mySqlQuery extends mysqlConnect{
			//public $array_ageStart = array(0,    1, 4.9, 9.99, 14.99,19.99,24.99,29.99,34.99,39.99,44.99,49.99,54.99,59.99,64.99,0);
			//public $array_ageEnd = array  (0.99, 5, 10 , 15  , 20   ,25   ,30   ,35   ,40   ,45   ,50   ,55   ,60   ,65   ,200  ,200);
			//public $array_ageStart = array(61,61);
			//public $array_ageEnd = array  (300,300);
			public $array_gender = array ('M','F');
				
			
			public function getValue($array_ageStart,$array_ageEnd){
				require_once('hf.php');
				$startDate = date("Y-m-d", mktime(0,0,0,$_GET['startMonth'],1,$_GET['reportYear']));
				$endDate = date("Y-m-d", mktime(0,0,0,($_GET['endMonth']+1),0,$_GET['reportYear']));
				//print_r ($_SESSION['dbarray']); 
				foreach($_SESSION['dbarray'] as $key => $value){
					//mysql_select_db($value, $this->dbCon); //select database
					$dbConnect = mysqli_connect("localhost", "root", "root", $value);
					$descSql = "SELECT COUNT(con.class_id) AS total, class_name AS name, icd10 FROM m_consult_notes_dxclass AS con JOIN m_lib_notes_dxclass AS lib ON con.class_id = lib.class_id JOIN m_patient AS patient ON con.patient_id = patient.patient_id
							  WHERE round((to_days(con.diagnosis_date)-to_days(patient.patient_dob))/365,1) BETWEEN $array_ageStart[0] AND $array_ageEnd[0] AND date_format(con.diagnosis_date, '%Y-%m-%d') BETWEEN '".$_GET[sDate]."' AND '".$_GET[eDate]."' AND morbidity = 'Y' GROUP BY icd10 ORDER BY COUNT(con.class_id) DESC LIMIT 0,5";
					//$descQuery = $this->_dbQuery($descSql);
					$descQuery = mysqli_query($dbConnect,$descSql);
					echo "<tr>";
					
						echo "<th rowspan='5' style='background-color:#660099;color:#fff'>";
						echo $key;
						echo "</th>";
					//echo $descSql;	
					//while($descResult = $this->_dbFetch($descQuery)){
					while($descResult = mysqli_fetch_array($descQuery)){
						$array_value = array();
						echo "<th style='background-color:#660099;color:#fff' align='left'>";
						echo $descResult[name];
						echo "</th>";
						$total = 0;
						
						for($i=0;$i<=2;$i++){
							$sAge = $array_ageStart[$i];
							$eAge = $array_ageEnd[$i];
							$gender = $this->array_gender[$i];
							$groupSql = "SELECT COUNT(con.class_id) AS total FROM m_consult_notes_dxclass AS con JOIN m_lib_notes_dxclass AS lib ON con.class_id = lib.class_id JOIN m_patient AS px ON px.patient_id = con.patient_id 
										WHERE date_format(con.diagnosis_date, '%Y-%m-%d') BETWEEN '".$_GET[sDate]."' AND '".$_GET[eDate]."' 
										AND px.patient_gender = '$gender' AND round((to_days(con.diagnosis_date)-to_days(px.patient_dob))/365,1) BETWEEN $sAge AND $eAge 
										AND icd10 = '$descResult[icd10]'  AND morbidity = 'Y'";
							//$groupQuery = $this->_dbQuery($groupSql);
							$groupQuery = mysqli_query($dbConnect,$groupSql);
							//$groupResult = $this->_dbFetch($groupQuery);
							$groupResult = mysqli_fetch_array($groupQuery);
							
							if($i!=2){
								array_push($array_value, $groupResult['total']);
								$total = $total + $groupResult['total'];
							}else{
								array_push($array_value, $total);
							}
							//echo $groupSql."<br /><br />";
						}
						
						for($x=0;$x<=2;$x++){
							if($x!=2){

								//$percentage = $array_value[$x]/$array_value[14];
								if($array_value[2]==0){
									$percentage = number_format(0,2);
								}else{
									$percentage = number_format(($array_value[$x]/$array_value[2])*100,2);
								}
								echo "<td align='right' style='padding-right:5px;'>" .$array_value[$x]. "</td>";
								echo "<td align='right'style='background-color:cyan;'>" .$percentage. "</td>";
							}else{
								echo "<td align='right'> <strong>" .$array_value[$x]. "</strong> </td>";
							}
						}
						unset($array_value);
					echo "</tr>";
					}
				}
			}
		}
		$query = new mySqlQuery;
	//}
	?>
</head>
<body>
	<table style='width:898px'>
		<tr>
			<th rowspan='2' style='width:150px;background-color:#660099;color:#fff'>RHU</th>
			<th rowspan='2' style='width:300px;background-color:#660099;color:#fff'>Class Name</th>
			<th colspan='4' style='background-color:#660099;color:#fff'>Under 1</th>
			<th rowspan='2' style='background-color:#660099;color:#fff'>Total</th>
		</tr>
		
		<tr>
			<th style='width:40px;background-color:#660099;color:#fff' >M</th><th style='width:40px;background-color:#660099;color:#fff'>%</th>
			<th style='width:40px;background-color:#660099;color:#fff' >F</th><th style='width:40px;background-color:#660099;color:#fff'>%</th>
			
		</tr>
		<?php
			$array_ageStart1 = array(0,0);
			$array_ageEnd1 = array  (.99,.99);
			$query->getValue($array_ageStart1,$array_ageEnd1);
		?>
	</table>
	<br /> <br />
	<table style='width:898px'>
		<tr>
			<th rowspan='2' style='width:150px;background-color:#660099;color:#fff'>RHU</th>
			<th rowspan='2' style='width:300px;background-color:#660099;color:#fff'>Class Name</th>
			<th colspan='4' style='background-color:#660099;color:#fff'>Above 60</th>
			<th rowspan='2' style='background-color:#660099;color:#fff'>Total</th>
		</tr>
		
		<tr>
			<th style='width:40px;background-color:#660099;color:#fff' >M</th><th style='width:40px;background-color:#660099;color:#fff'>%</th>
			<th style='width:40px;background-color:#660099;color:#fff' >F</th><th style='width:40px;background-color:#660099;color:#fff'>%</th>
			
		</tr>
		<?php
			$array_ageStart2 = array(61,61);
			$array_ageEnd2 = array  (300,300);
			$query->getValue($array_ageStart2,$array_ageEnd2);
		?>
	</table>
</body>
</html>

