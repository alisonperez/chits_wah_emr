<html>
	<head>
		<title>SPASMS Data Analytics</title>
	</head>
	<body>
		<?php
			if (isset($_REQUEST['eDate']) && $_SESSION["userid"]!="")
			{
				$dbHost = "localhost";
				$dbUser = "root";
				$dbPass = "";
				$dbName = "gerona1";
				//$dbConnect = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);
				$dbConnect = mysqli_connect($dbHost, $_SESSION["dbuser"], $_SESSION["dbpass"], $_SESSION["dbname"]);
				
				$startDate = $_REQUEST['sDate'];
				$endDate = $_REQUEST['eDate'];
		
				//$sql = "SELECT * FROM m_patient a, m_lib_sms_px_enroll b  WHERE b.patient_id = a.patient_id AND b.last_modified <= '$endDate' ORDER BY a.patient_lastname ASC";
				if ((($_REQUEST['parseString1'] == "Total Number of Patients Enrolled") && $_REQUEST['parseString2'] == "")  || (($_REQUEST['parseString1'] == "Total Number of Patients Enrolled") && ($_REQUEST['parseString2'] == "Distribution Per Barangay") && !isset($_REQUEST['type'])))
				{
					$sql = "SELECT * FROM m_patient a, m_lib_sms_px_enroll b, m_family_members c, m_family_address d, m_lib_barangay e  WHERE b.patient_id = a.patient_id AND c.patient_id = b.patient_id AND d.family_id = c.family_id AND e.barangay_id = d.barangay_id AND date_format(b.last_modified, '%Y/%m/%d') BETWEEN '$startDate' AND '$endDate' ORDER BY a.patient_lastname ASC";
				}
				//Per Barangay
				elseif (($_REQUEST['parseString1'] == "Total Number of Patients Enrolled") && ($_REQUEST['parseString2'] == "Distribution Per Barangay") && isset($_REQUEST['type']))
				{
					$sql = "SELECT * FROM m_patient a, m_lib_sms_px_enroll b, m_family_members c, m_family_address d, m_lib_barangay e  WHERE b.patient_id = a.patient_id AND c.patient_id = b.patient_id AND d.family_id = c.family_id AND e.barangay_id = d.barangay_id AND e.barangay_name = '".$_REQUEST['type']."' AND date_format(b.last_modified, '%Y/%m/%d') BETWEEN '$startDate' AND '$endDate' ORDER BY a.patient_lastname ASC";
				}
				//Per Program
				elseif (($_REQUEST['parseString1'] == "Total Number of Patients Enrolled") && ($_REQUEST['parseString2'] == "Distribution Per Program") && !isset($_REQUEST['type']))
				{
					$sql = "SELECT * FROM m_patient a, m_lib_sms_px_enroll b, m_family_members c, m_family_address d, m_lib_barangay e  WHERE b.patient_id = a.patient_id AND c.patient_id = b.patient_id AND d.family_id = c.family_id AND e.barangay_id = d.barangay_id AND date_format(b.last_modified, '%Y/%m/%d') BETWEEN '$startDate' AND '$endDate' ORDER BY a.patient_lastname ASC";
				}
				elseif (($_REQUEST['parseString1'] == "Total Number of Patients Enrolled") && ($_REQUEST['parseString2'] == "Distribution Per Program") && isset($_REQUEST['type']))
				{
					$sql = "SELECT * FROM m_patient a, m_lib_sms_px_enroll b, m_family_members c, m_family_address d, m_lib_barangay e  WHERE b.patient_id = a.patient_id AND c.patient_id = b.patient_id AND d.family_id = c.family_id AND e.barangay_id = d.barangay_id AND program_id = '".$_REQUEST['type']."' AND date_format(b.last_modified, '%Y/%m/%d') BETWEEN '$startDate' AND '$endDate' ORDER BY a.patient_lastname ASC";
				}
				//Per BHS
				elseif (($_REQUEST['parseString1'] == "Total Number of Patients Enrolled") && ($_REQUEST['parseString2'] == "Distribution Per BHS") && !isset($_REQUEST['type']))
				{
					$sql = "SELECT * FROM m_patient a, m_lib_sms_px_enroll b, m_family_members c, m_family_address d, m_lib_bhs_barangay e, m_lib_bhs f, m_lib_barangay g  WHERE b.patient_id = a.patient_id AND c.patient_id = b.patient_id AND d.family_id = c.family_id AND e.barangay_id = d.barangay_id AND f.bhs_id = e.bhs_id AND g.barangay_id = d.barangay_id AND date_format(b.last_modified, '%Y/%m/%d') BETWEEN '$startDate' AND '$endDate' ORDER BY a.patient_lastname ASC";
				}
				elseif (($_REQUEST['parseString1'] == "Total Number of Patients Enrolled") && ($_REQUEST['parseString2'] == "Distribution Per BHS") && isset($_REQUEST['type']))
				{
					$sql = "SELECT * FROM m_patient a, m_lib_sms_px_enroll b, m_family_members c, m_family_address d, m_lib_bhs_barangay e, m_lib_bhs f, m_lib_barangay g  WHERE b.patient_id = a.patient_id AND c.patient_id = b.patient_id AND d.family_id = c.family_id AND e.barangay_id = d.barangay_id AND f.bhs_id = e.bhs_id AND g.barangay_id = d.barangay_id AND f.bhs_name = '".$_REQUEST['type']."' AND date_format(b.last_modified, '%Y/%m/%d') BETWEEN '$startDate' AND '$endDate' ORDER BY a.patient_lastname ASC";
				}
				//Total Message
				elseif ((($_REQUEST['parseString1'] == "Total Number of Messages Generated") && $_REQUEST['parseString2'] == ""))
				{
					$sql = "SELECT * FROM m_patient a, m_lib_sms_alert b, m_family_members c, m_family_address d, m_lib_barangay e  WHERE b.patient_id = a.patient_id AND c.patient_id = b.patient_id AND d.family_id = c.family_id AND e.barangay_id = d.barangay_id AND date_format(b.last_modified, '%Y/%m/%d') BETWEEN '$startDate' AND '$endDate' ORDER BY a.patient_lastname ASC";
				}
				
				$query = mysqli_query($dbConnect,$sql);
				
				if (($_REQUEST['parseString2'] != "") && !isset($_REQUEST['type']))
				{
					$view = $_REQUEST['parseString2'] . " = " . $_REQUEST['count'];
				}
				elseif (($_REQUEST['parseString2'] != "") && isset($_REQUEST['type']))
				{
					$view = $_REQUEST['parseString2'] ." ". $_REQUEST['type']. " = " . $_REQUEST['count'];
				}
				else
					$view = $_REQUEST['parseString1'] . " = " . $_REQUEST['count'];
				
				//echo $view;
				echo "<table border=1 style='border-collapse:collapse'>";
				
				echo "<tr>";
					echo "<th colspan='4'>$view</th>";
				echo "</tr>";
				
				echo "<tr>";
					echo "<th colspan='2'>Patient Name</th>";
					echo "<th width='180px'>Barangay</th>";
					echo "<th width='180px'>Date of Enrollment</th>";
				echo "</tr>";
				
				$count = 0;
				
				while($result = mysqli_fetch_array($query))
				{
					$count = $count + 1;
					$lastModified = date("m/d/Y", strtotime($result['last_modified']));
					echo "<tr>";
					echo "<td>$count.</td>";
					echo "<td>".$result['patient_lastname'].", ".$result['patient_firstname']." ".$result['patient_middle']."</td>";
					echo "<td align='center'>".$result['barangay_name']."</td>";
					echo "<td align='center'>".$lastModified."</td>";
					echo "</tr>";
				}
			}
			else 
				header('Location:spasmsda.php');
		?>
	</body>
</html>
