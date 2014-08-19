<?php
//SELECT * FROM (SELECT consult_id AS consultID, service_id AS serviceID, service_timestamp AS serviceDATE FROM m_consult_philhealth_services UNION ALL SELECT  consult_id AS consultID, lab_id AS labID, lab_timestamp AS labDATE FROM m_consult_philhealth_labs) AS philhealthConsult JOIN m_consult a ON philhealthConsult.consultID=a.consult_id WHERE consultID=14780 AND serviceID='LIFEST' GROUP BY consultID
	$con = mysqli_connect("localhost",$_SESSION["dbuser"],$_SESSION["dbpass"],$_SESSION["dbname"]);
	if (!(isset($pagenum))) 
	{ 
		$pagenum = 1; 
	} 
	$page_rows = 1;
	
	//$sql = "SELECT * FROM m_patient_philhealth a JOIN m_patient b ON a.patient_id = b.patient_id GROUP BY philhealth_id";
	$sql = "SELECT * FROM m_consult a 
			JOIN m_patient_philhealth b ON a.patient_id = b.patient_id 
			JOIN m_patient c ON b.patient_id = c.patient_id
			JOIN m_consult_philhealth_services d ON a.consult_id = d.consult_id
			GROUP BY a.consult_id";
	
	$sql = "SELECT * FROM (SELECT consult_id AS consultID, service_id AS serviceID, service_timestamp AS serviceDATE FROM m_consult_philhealth_services UNION ALL SELECT  consult_id AS consultID, lab_id AS labID, lab_timestamp AS labDATE FROM m_consult_philhealth_labs) AS philhealthConsult JOIN m_consult a ON philhealthConsult.consultID=a.consult_id JOIN m_patient b ON a.patient_id = b.patient_id JOIN m_patient_philhealth c ON a.patient_id = c.patient_id GROUP BY consultID";
	$query = mysqli_query($con, $sql) or die(mysql_error());
	$rows = mysqli_num_rows($query);
	
	$last = ceil($rows/$page_rows);
		
	if ($pagenum < 1) 
	{ 
		$pagenum = 1; 
	} 
	elseif ($pagenum > $last) 
	{ 
		$pagenum = $last; 
	} 
	
	$max = 'limit ' .($pagenum - 1) * $page_rows .',' .$page_rows; 
	
	//$showSQL = "SELECT * FROM m_patient_philhealth a JOIN m_patient b ON a.patient_id = b.patient_id  GROUP BY philhealth_id $max";
	$showSQL = "SELECT DISTINCT a.consult_id, a.patient_id, b.philhealth_id, patient_lastname, patient_firstname, d.service_id FROM m_consult a 
			JOIN m_patient_philhealth b ON a.patient_id = b.patient_id 
			JOIN m_patient c ON b.patient_id = c.patient_id
			JOIN m_consult_philhealth_services d ON a.consult_id = d.consult_id
			GROUP BY a.consult_id $max";
	$showSQL = "SELECT * FROM (SELECT consult_id AS consultID, service_id AS serviceID, service_timestamp AS serviceDATE FROM m_consult_philhealth_services UNION ALL SELECT  consult_id AS consultID, lab_id AS labID, lab_timestamp AS labDATE FROM m_consult_philhealth_labs) AS philhealthConsult JOIN m_consult a ON philhealthConsult.consultID=a.consult_id JOIN m_patient b ON a.patient_id = b.patient_id JOIN m_patient_philhealth c ON a.patient_id = c.patient_id GROUP BY consultID $max";
	$showQUERY = mysqli_query($con, $showSQL) or die(mysql_error());
	$result = mysqli_fetch_array($showQUERY);
	
	/*if ($pagenum == 1)
	{

	}
	else
	{
		$previous = $pagenum - 1;
		
		echo " <a href='{$_SERVER['PHP_SELF']}?pagenum=1'> <<-First</a> ";
		echo " ";
		echo " <a href='{$_SERVER['PHP_SELF']}?pagenum=$previous'> <-Previous</a> ";
	} 
	
	//just a spacer
	echo " ---- ";

	//This does the same as above, only checking if we are on the last page, and then generating the Next and Last links
	
	if ($pagenum == $last)
	{

	} 
	else 
	{
		$next = $pagenum + 1;

		echo " <a href='{$_SERVER['PHP_SELF']}?pagenum=$next'>Next -></a> ";
		echo " ";
		echo " <a href='{$_SERVER['PHP_SELF']}?pagenum=$last'>Last ->></a> ";
	}*/ 
	
	function _getAge($dob, $date)
	{
		$birthDate = $dob;
  		//explode the date to get month, day and year
  		/*$birthDate = explode("-", $birthDate);
  		//get age from date or birthdate
  		$age = (date("md", date("U", mktime(0, 0, 0, $birthDate[1], $birthDate[2], $birthDate[0]))) > date("md")
    			? ((date("Y") - $birthDate[0]) - 1)
    			: (date("Y") - $birthDate[0]));
  		echo "Age is:" . $age;*/
		$birthday = new DateTime($birthDate);
		$interval = $birthday->diff(new DateTime($date));
		return $interval->y;
	}
	
	function _benefitsGiven($con, $consultID, $service)
	{
		$sql = "SELECT * FROM (SELECT consult_id AS consultID, service_id AS serviceID, service_timestamp AS serviceDATE FROM m_consult_philhealth_services UNION ALL SELECT  consult_id AS consultID, lab_id AS labID, lab_timestamp AS labDATE FROM m_consult_philhealth_labs) AS philhealthConsult JOIN m_consult a ON philhealthConsult.consultID=a.consult_id JOIN m_patient b ON a.patient_id = b.patient_id JOIN m_patient_philhealth c ON a.patient_id = c.patient_id WHERE consultID = '$consultID' and serviceID = '$service'";
		$query = mysqli_query($con, $sql) or die(mysql_error());
		$result = mysqli_fetch_array($query);
		
		return $result['serviceID'];
	}
	
	function _diagnosis($con, $patientID)
	{
		$sql = "SELECT class_name FROM m_consult_notes_dxclass a JOIN m_lib_notes_dxclass b ON a.class_id = b.class_id WHERE patient_id = '$patientID'";
		$query = mysqli_query($con, $sql) or die(mysql_error());
		$diag = array();
		while (list($diagnosis)= mysqli_fetch_array($query))
		{
			$diag[] = $diagnosis;
		}
		$diag = implode(", ", $diag);
		return $diag;
	}
	
	function _medicine($con, $patientID)
	{
		$sql = "SELECT generic_name FROM m_consult_pcb_drugs a JOIN m_lib_pcb_drugs b ON a.generic_id = b.record_id WHERE patient_id = '$patientID'";
		$query = mysqli_query($con, $sql) or die(mysql_error());
		$med = array();
		while (list($medicine)= mysqli_fetch_array($query))
		{
			$med[] = $medicine;
		}
		$med = implode(", ", $med);
		return $med;
	}
	//echo _getAge($result['patient_dob'],$result['serviceDATE']);
	//echo _benefitsGiven($con, $result['consultID'], 'BODYM');
?>

	<!--  Patient ID: <input type='text' name='pxID' <?php //echo "value='".$result['patient_id']."'";?> />
	Patient Name: <input type='text' name='pxName' <?php //echo "value='".$result['patient_firstname']."'";?> />
	Patient Last Name:  <input type='text' name='pxLastName' <?php //echo "value='".$result['patient_lastname']."'";?> />
	<?php  //echo " --Page $pagenum of $last-- <p>"; ?>-->
	
	<html>
		<head>
			<title>A5 Form</title>
			<link rel='stylesheet' href='../styles/style.css' type='text/css'  />
		</head>

		<body>

			<div id='container'>

				<div  id='header'>
					<h4 class='shadow'><span class='indent10'>Annex A5</span></h4>
					<h1>PHILIPPINE HEALTH INSURANCE CORPORATION</h1>
					<h2>PCB FORM 1A</h2>
					<h3>QUARTERLY SUMMARY OF PCB SERVICES PROVIDED</h3>
					<br />
					<?php 
						if ($pagenum == 1)
						{
					
						}
						else
						{
							$previous = $pagenum - 1;
							
							echo " <a class ='page' href='{$_SERVER['PHP_SELF']}?pagenum=1'>First</a> ";
							echo " ";
							echo " <a class ='page' href='{$_SERVER['PHP_SELF']}?pagenum=$previous'>Previous</a> ";
						} 
						
						//just a spacer
						//echo " ---- ";
					
						//This does the same as above, only checking if we are on the last page, and then generating the Next and Last links
						
						if ($pagenum == $last)
						{
					
						} 
						else 
						{
							$next = $pagenum + 1;
					
							echo " <a class ='page' href='{$_SERVER['PHP_SELF']}?pagenum=$next'>Next</a> ";
							echo " ";
							echo " <a class ='page' href='{$_SERVER['PHP_SELF']}?pagenum=$last'>Last</a> ";
						}
					?>
				</div>

				<div id='body'>

					<form name='a5form' method='POST'>
						<div class='width750 center' style='display:none;'>
							<br /><hr />
							<!-- <select name='frmdate'>
							<option value=1>January</option>
							<option value=2>February</option>
							<option value=3>March</option>
							<option value=4>April</option>
							<option value=5>May</option>
							<option value=6>June</option>
							<option value=7>July</option>
							<option value=8>August</option>
							<option value=9>September</option>
							<option value=10>October</option>
							<option value=11>November</option>
							<option value=12>December</option>
							</select>
							<label>To</label>
							<select name='todate'>
							<option value=1>January</option>
							<option value=2>February</option>
							<option value=3>March</option>
							<option value=4>April</option>
							<option value=5>May</option>
							<option value=6>June</option>
							<option value=7>July</option>
							<option value=8>August</option>
							<option value=9>September</option>
							<option value=10>October</option>
							<option value=11>November</option>
							<option value=12>December</option>
							</select>-->
							<?php 
							//selection of month and year.
							/*$yearstart=2005;
							$yearend=2045;
							$year =date("Y");
							echo "<select name='year'>";
							for($y=$yearstart; $y<=$yearend; $y++)
							{
								if($year==$y):
								echo "<option value=$y selected=$year>$y";
								else:
								echo "<option value=$y>$y";
								endif;
							}
							
							
							echo "</select>";*/?>
							<input type='submit' value='Add New Form'><hr />
						</div>
						
						<div class='width750'>
							<br /><h4 class='center'>Name of Health Care Facility<br />
							<input style='text-align:center;' type='text' size=25 name='nameHCF' <?php echo "value='".$_SESSION['datanode']['name']."'";?>></h4><br />
						</div>

						<div class='width750'>

							<hr /><h4 class='center'>Personal Information</h4><hr />
							<br />
							<span class='width70'><label>Date:</label></span>
							<span class='width285'><input type='text' size=8 name='personalinfodate' <?php echo "value='".date("m-d-Y", strtotime($result['serviceDATE']))."'";?>></span>

							<span class='width150'><label>Philhealth #:</label></span><input type='text' name='pin' <?php echo "value='".$result['philhealth_id']."'";?>>

							<br /><span class='width70'><label>Name: </label></span>
							<span class='width285'><input type='text' name='patientname' <?php echo "value='".$result['patient_lastname'].", ".$result['patient_firstname']."'";?>></span>

							<span class='width150'><label>Age:</label></span><input type='text' size=4 name='age' <?php echo "value='"._getAge($result['patient_dob'],$result['serviceDATE'])."'";?>>

							<br /><span class='width70'><label>Gender:</label></span>
							<span class='width285'><input type='radio' name='gender' value='Male' <?php echo ($result['patient_gender']=='M' ? "checked" : "");?>>Male
							<input type='radio' name='gender' value='Female' <?php echo ($result['patient_gender']=='F' ? "checked" : "");?>>Female</span>
							
							<span class='width150'><label>Membership:</label></span><input type='radio' name='membership' value='Member' <?php if ($result['member_id'] == 1 || $result['member_id'] == 2 || $result['member_id'] == 3 || $result['member_id'] == 5 || $result['member_id'] == 7 || $result['member_id'] == 8 || $result['member_id'] == 9 || $result['member_id'] == 10 || $result['member_id'] == 11 || $result['member_id'] == 12 || $result['member_id'] == 13){ echo "checked"; }?>>Member
							<input type='radio' name='membership' value='Dependent' <?php if ($result['member_id'] == 4){ echo "checked"; }?>>Dependent
						
						</div>

						<div class='width750'>
							<br />
							<hr /><h4 class='center'>Other Information</h4><hr />
							<br />
							<p class='center'><label>Diagnosis</label><br />
							<textarea class='center' style='vertical-align: top' name='diagnosis' rows='2' cols='50'><?php echo _diagnosis($con, $result["patient_id"])?></textarea></p>
						
							<br /><br />
							<h4>BENEFITS GIVEN</h4>(Number of Times Benefit Given)
							<br />
							<br /><p class='columns indent70'>
							<span class='width30'><input type='checkbox' name='benefits' value='Consultation'></span>Consultation

							<br /> 
							<span class='width30'><input type='checkbox' name='benefits' value='Visual Inspection with Acetic Acid' <?php if (_benefitsGiven($con, $result['consultID'], 'ACETIC') != null){ echo "checked"; }?>></span>Visual Inspection with Acetic Acid

							<br />
							<span class='width30'><input type='checkbox' name='benefits' value='Regular BP Measurement' <?php if (_benefitsGiven($con, $result['consultID'], 'BPMEAS') != null){ echo "checked"; }?>></span>Regular BP Measurement

							<br /> 
							<span class='width30'><input type='checkbox' name='benefits' value='Breastfeeding Program Education' <?php if (_benefitsGiven($con, $result['consultID'], 'BREASTFEED') != null){ echo "checked"; }?>></span>Breastfeeding Program Education

							<br />
							<span class='width30'><input type='checkbox' name='benefits' value='Periodic Clinical Breast Examination' <?php if (_benefitsGiven($con, $result['consultID'], 'BREASTX') != null){ echo "checked"; }?>></span>Periodic Clinical Breast Examination

							<br /> 
							<span class='width30'><input type='checkbox' name='benefits' value='Counselling for Lifestyle Modification' <?php if (_benefitsGiven($con, $result['consultID'], 'LIFEST') != null){ echo "checked"; }?>></span>Counselling for Lifestyle Modification

							<br />
							<span class='width30'><input type='checkbox' name='benefits' value='Counselling for Smoking Cessation' <?php if (_benefitsGiven($con, $result['consultID'], 'SMOKEC') != null){ echo "checked"; }?>></span>Counselling for Smoking Cessation

							<br /> 
							<span class='width30'><input type='checkbox' name='benefits' value='Body Measurements' <?php if (_benefitsGiven($con, $result['consultID'], 'BODYM') != null){ echo "checked"; }?>></span>Body Measurements

							<br />
							<span class='width30'><input type='checkbox' name='benefits' value='Digital Rectal Exam' <?php if (_benefitsGiven($con, $result['consultID'], 'RECTAL') != null){ echo "checked"; }?>></span>Digital Rectal Exam

							<br /> 
							<span class='width30'><input type='checkbox' name='benefits' value='CBC' <?php if (_benefitsGiven($con, $result['consultID'], 'CBC') != null){ echo "checked"; }?>></span>CBC

							<br />
							<span class='width30'><input type='checkbox' name='benefits' value='Urinalysis' <?php if (_benefitsGiven($con, $result['consultID'], 'URN') != null){ echo "checked"; }?>></span>Urinalysis

							<br /> 
							<span class='width30'><input type='checkbox' name='benefits' value='Fecalysis' <?php if (_benefitsGiven($con, $result['consultID'], 'FEC') != null){ echo "checked"; }?>></span>Fecalysis

							<br />
							<span class='width30'><input type='checkbox' name='benefits' value='Sputum Microscopy' <?php if (_benefitsGiven($con, $result['consultID'], 'SPT') != null){ echo "checked"; }?>></span>Sputum Microscopy

							<br /> 
							<span class='width30'><input type='checkbox' name='benefits' value='FBS'></span>FBS

							<br />
							<span class='width30'><input type='checkbox' name='benefits' value='Lipid Profile'></span>Lipid Profile

							<br /> 
							<span class='width30'><input type='checkbox' name='benefits' value='Chest X-Ray' <?php if (_benefitsGiven($con, $result['consultID'], 'CXR') != null){ echo "checked"; }?>></span>Chest X-Ray
							</p>

							<p class='center'>
							<br /><br /><label>Medicines Given</label><br />
							<textarea style='vertical-align: top' name='medicines' rows='2' cols='50'><?php echo _medicine($con, $result["patient_id"])?></textarea>
							<br /><br /><br /></p>

						</div>
						
						<!--<div class='width750 center'>
							<hr />
							<label>Select Existing Form: </label>
							<select name='existform'>
							<option value=''>Form 1</option>
							<option value=''>Form 2</option>
							<option value=''>Form 3</option>
							<option value=''>Form 4</option>
							<option value=''>Form 5</option>
							</select>
							<input type='submit' value='Select Form'>
							<hr />
						</div>
						
						<div class='width750 center'>
							<button type='submit' title='Save Form'>
								<img src='../styles/images/save.png' alt='Save Form'>
							</button>
							<button type='submit' title='Cancel'>
								<img src='../styles/images/cancel.png' alt='Cancel'>
							</button>
							<br />
							<button type='submit' title='Update Form'>
								<img src='../styles/images/update.png' alt='Update Form'>
							</button>
							<button type='submit' title='Delete Form'>
								<img src='../styles/images/delete.png' alt='Delete Form'>
							</button>
							<button type='submit' title='Printer Friendly Format'>
								<img src='../styles/images/printer.png' alt='Printer Friendly Format'>
							</button>
							<button type='submit' title='Download PDF File'>
								<img src='../styles/images/pdf.png' alt='Download PDF File'>
							</button>
							<button type='submit' title='Download XML File'>
								<img src='../styles/images/xml.png' alt='Download XML File'>
							</button>
						</div>
					
						<div id='submit' class='width750'>
							<hr />
							<span class='width100'>
								<input type='submit' name='submit' value='Submit'></span>
							<input type='reset' name='submit' value='Reset'>
							<hr /><br />
						</div>-->

					</form>
		
				</div>

			</div>

		</body>

	</html>
	
	