<?php
$dbHost = "localhost";

$dbCon = mysqli_connect($dbHost,$_SESSION["dbuser"],$_SESSION["dbpass"],$_SESSION["dbname"]);

if(!isset($_SESSION["dbname"]) && !isset($_SESSION["dbuser"]) && !isset($_SESSION["dbpass"])){
	die("You need to login to EMR before using this updater");
}else{
       	//update trimester1_date and trimester2_date field of m_patient_mc
	$selectMCSql = "SELECT mc_id, patient_lmp FROM m_patient_mc ORDER BY mc_id ASC"; 
	$selectMCResult = mysqli_query($dbCon, $selectMCSql);

	while($fetchMCResult = mysqli_fetch_assoc($selectMCResult)){
		
		$mc_id = $fetchMCResult[mc_id];
		$lmp_date = $fetchMCResult[patient_lmp];
		
		$updateTriSql = "UPDATE m_patient_mc SET " .
				"trimester1_date = from_days(to_days('$lmp_date')+84)," .
				"trimester2_date = from_days(to_days('$lmp_date')+189)" .
				"WHERE mc_id = $mc_id";

		$updateTriResult = mysqli_query($dbCon, $updateTriSql) or die("Unable to update trimester date");
	}
		
	//update trimester value on m_consult_mc_prenatal
	$selectPreSql = "SELECT con.mc_id AS mc_id, con.patient_id AS patient_id, con.consult_id AS consult_id, con.prenatal_date AS prenatal_date, patient.patient_lmp AS lmp FROM m_consult_mc_prenatal AS con INNER JOIN m_patient_mc AS patient ON con.mc_id = patient.mc_id ORDER BY con.mc_id ASC";
	$selectPreResult = mysqli_query($dbCon, $selectPreSql);
	while($fetchPreResult = mysqli_fetch_assoc($selectPreResult)){
		$lmp_date = $fetchPreResult[lmp];
		$consult_id = (int)$fetchPreResult[consult_id];
		$mc_id = (int)$fetchPreResult[mc_id];
		$patient_id = (int)$fetchPreResult[patient_id];

		$prenatal_date= $fetchPreResult[prenatal_date];
		$pre_date = date('Y-m-d', strtotime($fetchPreResult[prenatal_date]));
		$firstTri = date('Y-m-d', strtotime($lmp_date. ' + 84 days'));
		$secondTri = date('Y-m-d', strtotime($lmp_date. ' + 189 days'));
		$thirdTri = date('Y-m-d', strtotime($lmp_date. ' + 280 days'));
		
		if($pre_date <= $firstTri){
			$trimester = 1;
		}elseif(($pre_date > $firstTri) and ($pre_date <= $secondTri)){
			$trimester = 2;
		}elseif($pre_date > $secondTri){
			$trimester = 3;
		}
		
		var_dump($prenatal_date); echo "<br />";
		var_dump($pre_date); echo "<br />";
		var_dump($patient_id, $mc_id, $consult_id, $trimester); echo "<br />";

		$updatePreSql = "UPDATE m_consult_mc_prenatal SET " .
				"trimester=$trimester " .
				"WHERE consult_id=$consult_id " .
				"AND mc_id=$mc_id " .
				"AND patient_id=$patient_id " .
				"AND prenatal_date LIKE '$pre_date%'";

		echo $updatePreSql . "<br /><br />";
		/*$updatePreSql = "UPDATE m_consult_mc_prenatal SET " .
				"trimester=$trimester " .
				"WHERE consult_id=$consult_id " .
				"AND mc_id=$mc_id " .
				"AND patient_id=$patient_id " .
				"AND prenatal_date LIKE '$pre_date%'"; */

		/*$updatePreSql = "UPDATE m_consult_mc_prenatal SET " .
				"trimester=2 " .
				"WHERE consult_id=17163 " .
				"AND mc_id=680 " .
				"AND patient_id=5229 " .
				"AND prenatal_date LIKE '$pre_date%'";*/

		$updatePreResult = mysqli_query($dbCon, $updatePreSql) or die('Unable to update trimester value');	
	}
	echo "<script>alert('update successful :)')</script>";
	
}
	
	

?>
