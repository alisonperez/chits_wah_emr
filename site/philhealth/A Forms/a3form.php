<?php
	$con = mysqli_connect("localhost",$_SESSION["dbuser"],$_SESSION["dbpass"],$_SESSION["dbname"]);
	if (!(isset($pagenum))) 
	{ 
		$pagenum = 1; 
	} 
	$page_rows = 1;
	
	$sql = "SELECT * FROM m_patient a JOIN (SELECT * FROM m_patient_philhealth GROUP BY patient_id HAVING count(patient_id)>=1) b ON a.patient_id = b.patient_id";
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
	
	$showSQL = "SELECT *,a.patient_id as patientID FROM m_patient a 
				JOIN (SELECT * FROM m_patient_philhealth GROUP BY patient_id HAVING count(patient_id)>=1) b 
				ON a.patient_id = b.patient_id 
				LEFT JOIN m_family_members c
				ON a.patient_id = c.patient_id
				LEFT JOIN m_family_address d
				ON c.family_id = d.family_id
				LEFT JOIN m_lib_barangay bgy
				ON d.barangay_id = bgy.barangay_id 
				LEFT JOIN m_patient_philhealth_info e
				ON a.patient_id = e.patient_id
				LEFT JOIN m_lib_religion f
				ON e.religion_id = f.religion_code 
				LEFT JOIN m_lib_civil_status g
				ON e.status_id = g.status_id
				LEFT JOIN m_lib_occupation h
				ON e.occup_id = h.occup_id $max";
	$showQUERY = mysqli_query($con, $showSQL) or die(mysql_error());
	$result = mysqli_fetch_array($showQUERY);
	//echo $showSQL;
	
	function _getAge($dob)
	{
		$birthDate = $dob;
  		//explode the date to get month, day and year
  		/*$birthDate = explode("-", $birthDate);
  		//get age from date or birthdate
  		$age = (date("md", date("U", mktime(0, 0, 0, $birthDate[1], $birthDate[2], $birthDate[0]))) > date("md")
    			? ((date("Y") - $birthDate[0]) - 1)
    			: (date("Y") - $birthDate[0]));
  		echo "Age is:" . $age;*/
		$date = date("Y-m-d");
		$birthday = new DateTime($birthDate);
		$interval = $birthday->diff(new DateTime($date));
		return $interval->y;
	}
	
	function _diagnostic()
	{
		if (func_num_args()>0) {
            $arg_list = func_get_args();
            $patient_id = $arg_list[0];
            $con = $arg_list[1];
        }
        
        $sql = "SELECT date_format(diagnosis_date, '%Y-%m-%d'), class_name FROM m_consult_notes_dxclass a JOIN m_lib_notes_dxclass b ON a.class_id = b.class_id WHERE patient_id = '$patient_id'";
        if ($result = mysqli_query($con, $sql) or die(mysql_error()))
        {
			if (mysqli_num_rows($result)) 
			{
				
				while(list($date, $name) = mysqli_fetch_array($result))
				{
					echo "<tr>";
						echo "<td class='center'>$date</td>";
						echo "<td>$name</td>";
						echo "<td></td>";
						echo "<td class='center'>&#10004;</td>";
						echo "<td></td>";
						echo "<td></td>";
					echo "</tr>";
				}
				
			}
        }
        
	}
	
	function getMembership()
    {
        if (func_num_args()>0) {
	    	$arg_list = func_get_args();
	      	$philhealth_id = $arg_list[0];
	  	}
       	
	  	$con = mysqli_connect("localhost",$_SESSION["dbuser"],$_SESSION["dbpass"],$_SESSION["dbname"]);
       	$checkSQL = "SELECT member_id FROM m_patient_philhealth WHERE philhealth_id = '$philhealth_id' AND member_id IN (1,2,3,5,7,8,9,10,11,12,13)";
       	if ($checkResult = mysqli_query($con,$checkSQL)) {
	    	if (mysqli_num_rows($checkResult)) {
	       		(list($memberID) = mysqli_fetch_array($checkResult));
	            	return $memberID;
	    	}
  		}
	}
?>	 
	 <html>
		 <head>
			 <title>A3 Form</title>
			 <link rel='stylesheet' href='../styles/style.css' type='text/css'  />
		 </head>
		 <body>
			 <div id='container'>
				 <div id='header'>
					 <h4 class='shadow'><span class='indent10'>Annex A3</span></h4>
					 <br />					
					 <h1>PHILIPPINE HEALTH INSURANCE CORPORATION</h1>
					 <h2>PCB PATIENT LEDGER</h2>
					 <br /><br />
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
				 	<form name='a3form' method='POST'>
					 <div class='width750 center'>
						<br />
						<h4 class='center'>Name of Health Care Facility<br />
						<input style='text-align:center;' type='text' size=25 name='nameHCF' <?php echo "value='".$_SESSION['datanode']['name']."'";?>></h4>
					 	<br />
					 </div>					
					 <hr />
					 <br />
					 <h4 class='indent'>Part I</h4>
						 <div class='width750'>
							 <hr /><h4 class='center'>Personal Information</h4><hr />
							 <br /><span class='width70'><label>Name:</label></span><span class='width285'><input style='width: 263px;' type='text' name='patientname' <?php echo "value='".$result['patient_lastname'].", ".$result['patient_firstname']." ".$result['patient_middle']."'";?>></span>
								<span class='width70'><label>Age:</label></span><span class='width150'><input type='text' name='age' style='width:50px;' <?php echo "value='". _getAge($result['patient_dob']) . "'";?>></span>
								<span class='width70'><label>Sex:</label></span><select name='gender'><option value='M' <?php if ($result["patient_gender"]=='M'){ echo "selected";}?>>Male</option><option value='F' <?php if ($result["patient_gender"]=='F'){ echo "selected";}?>>Female</option></select>
							 <br /><br /><span class='width70'><label>Address:</label></span><textarea style='vertical-align: top' name='address' rows='2' cols='30'><?php echo $result["address"].", ".$result["barangay_name"].", ".$_SESSION['lgu'].", ".$_SESSION['province'];?></textarea><span class='width21'></span>
								<span class='width70'><label>PIN:</label></span><input type='text' name='pin' <?php echo "value='".$result['philhealth_id']."'";?>>
						 </div>
						 <div class='width750'>
							 <br /><hr /><h4 class='center'>Membership Information</h4><hr />
							 <br /><span class='width200'><label>PHIC Membership</label></span>
							 	<input type='radio' name='membership' value='Member' <?php if ($result["member_id"]==1||$result["member_id"]==2||$result["member_id"]==3||$result["member_id"]==5||$result["member_id"]==7||$result["member_id"]==8||$result["member_id"]==9||$result["member_id"]==10||$result["member_id"]==11||$result["member_id"]==12){ echo "checked";}?>><span class='width100'>Member</span>
								<input type='radio' name='membership' value='Dependent' <?php if ($result["member_id"]==4){ echo "checked";}?>><span class='width100'>Dependent</span>
								<input type='radio' name='membership' value='Non Member'>Non Member
							 <br /><span class='width200'><label>Sponsor</label></span>
								<input type='radio' name='sponsor' value='NHTS' <?php if ($result["member_id"]==4){$memberID = getMembership($result["philhealth_id"]); if ($memberID==7){echo "checked";}}elseif ($result["member_id"]==7){echo "checked";}?>><span class='width100'>NHTS</span> 
								<input type='radio' name='sponsor' value='NGA' <?php if ($result["member_id"]==4){$memberID = getMembership($result["philhealth_id"]); if ($memberID==8){echo "checked";}}elseif ($result["member_id"]==8){echo "checked";}?>><span class='width100'>NGA</span>
								<input type='radio' name='sponsor' value='LGU' <?php if ($result["member_id"]==4){$memberID = getMembership($result["philhealth_id"]); if ($memberID==9){echo "checked";}}elseif ($result["member_id"]==9){echo "checked";}?>><span class='width100'>LGU</span>
								<input type='radio' name='sponsor' value='PRIVATE' <?php if ($result["member_id"]==4){$memberID = getMembership($result["philhealth_id"]); if ($memberID==10){echo "checked";}}elseif ($result["member_id"]==10){echo "checked";}?>>PRIVATE
							 <br /><span class='width200'><label>IPP</label></span>
								<input type='radio' name='ipp' value='OG' <?php if ($result["member_id"]==4){$memberID = getMembership($result["philhealth_id"]); if ($memberID==11){echo "checked";}}elseif ($result["member_id"]==11){echo "checked";}?>><span class='width100'>OG</span> 
								<input type='radio' name='ipp' value='OFW' <?php if ($result["member_id"]==4){$memberID = getMembership($result["philhealth_id"]); if ($memberID==3){echo "checked";}}elseif ($result["member_id"]==3){echo "checked";}?>><span class='width100'>OFW</span>
								<input type='radio' name='ipp' value='Voluntary' <?php if ($result["member_id"]==4){$memberID = getMembership($result["philhealth_id"]); if ($memberID==1){echo "checked";}}elseif ($result["member_id"]==1){echo "checked";}?>>Voluntary/Self-employed
							 <br /><span class='width200'><label>Employment</label></span>
								<input type='radio' name='employment' value='Government' <?php if ($result["member_id"]==4){$memberID = getMembership($result["philhealth_id"]); if ($memberID==12){echo "checked";}}elseif ($result["member_id"]==12){echo "checked";}?>><span class='width100'>Government</span>
								<input type='radio' name='employment' value='Private' <?php if ($result["member_id"]==4){$memberID = getMembership($result["philhealth_id"]); if ($memberID==5){echo "checked";}}elseif ($result["member_id"]==5){echo "checked";}?>>Private
							 <br /><span class='width200'><label>Lifetime</label></span>
								<input type='radio' name='lifetime' value='Lifetime' <?php if ($result["member_id"]==4){$memberID = getMembership($result["philhealth_id"]); if ($memberID==13){echo "checked";}}elseif ($result["member_id"]==13){echo "checked";}?>>Lifetime
						 </div>
						 <div class='width750'>
							 <br /><hr /><h4 class='center'>Obligated Services</h4><hr />
							 <br />
							 <span class='width350'><label>BP Measurement</label>
								<select name='bp'><option value='Hypertensive'>Hypertensive</option>
								<option value='Nonhypertensive'>Nonhypertensive</option></select></span>
								<label>Date Performed:</label> <input type='text' size=8 name='bpdate'>(mm/dd/yyyy)
							 <br /><br /><span class='width350'><label>Periodic Clinical Breast Examination</label></span>
								<label>Date Performed:</label> <input type='text' size=8 name='pcbedate'>(mm/dd/yyyy)
							 <br /><br /><span class='width350'><label>Visual Inspection with Acetic Acid</label></span>
								<label>Date Performed:</label> <input type='text' size=8 name='vidate'>(mm/dd/yyyy)
						 </div>
						 <div class='width750'>
							 <br /><hr /><h4 class='center'>Diagnostic Examination Services</h4><hr />
							 <!-- <br /><span class='width100'><label>Date:</label></span>
								<input type='text' size =8 name='desdate'>(mm/dd/yyyy)
							 <br /><br /><span class='width100'><label>Diagnosis:</label></span>
								<textarea style='vertical-align: top' name='desdiagnosis' rows='2' cols='60'></textarea>
							 <br /><br /><span class='width100'><label>Type:</label></span> 
								<select name='destype'><option value='type1'>Type1</option>
								<option value='type2'>Type2</option>
								<option value='type3'>Type3</option>
								<option value='type4'>Type4</option>
								<option value='type5'>Type5</option></select>
							 <br /><br /><span class='width100'><label>Given:</label></span>
								<input type='text' name ='desgiven'>
							 <br /><br /><span class='width100'><label>Referred:</label></span>
								<input type='text' name ='desreferred'>
							 <br /><br /><span class='width100'><label>Remarks:</label></span>
								<textarea style='vertical-align: top' name='desremarks' rows='2' cols='60'></textarea>-->
							<br />
							<table class='width750'>
								<tr>
									<th>Date</th>
									<th>Diagnosis</th>
									<th>Type</th>
									<th>Given</th>
									<th>Referred</th>
									<th>Remarks</th>
								</tr>
								<?php _diagnostic($result["patientID"],$con);?>
							</table>
						 </div>
						 <div class='width750'>
							 <br /><hr /><h4 class='center'><label>Other PCB1 Services</label></h4><hr />
							 <!-- <br /><span class='width100'><label>Date:</label></span>
								<input type='text' size=8 name='opsdate'>(mm/dd/yyyy)
							 <br /><br /><span class='width100'><label>Diagnosis:</label></span>
								<textarea style='vertical-align: top' name='opsdiagnosis' rows='2' cols='60'></textarea>
							 <br /><br /><span class='width100'><b>Type:</b></span> 
								<select name='opstype'><option value='type1'>Type1</option>
								<option value='type2'>Type2</option>
								<option value='type3'>Type3</option>
								<option value='type4'>Type4</option>
								<option value='type5'>Type5</option></select>
							 <br /><br /><span class='width100'><label>Remarks:</label></span>
								<textarea style='vertical-align: top' name='opsremarks' rows='2' cols='60'></textarea>-->
							<br />
							<table class='width750'>
								<tr>
									<th>Date</th>
									<th>Diagnosis</th>
									<th>Type</th>
									<th>Remarks</th>
								</tr>
							</table>
						 </div>
						 <div class='width750'>
							 <br /><hr /><h4 class='center'>Other Services</h4><hr />
							 <!-- <br /><span class='width100'><label>Date:</label></span>
								<input type='text' size=8 name='osdate'>(mm/dd/yyyy)
							 <br /><br /><span class='width100'><label>Diagnosis:</label></span>
								<textarea style='vertical-align: top' name='osdiagnosis' rows='2' cols='60'></textarea>
							 <br /><br /><span class='width100'><label>Type:</label></span> 
								<select name='ostype'><option value='type1'>Type1</option>
								<option value='type2'>Type2</option>
								<option value='type3'>Type3</option>
								<option value='type4'>Type4</option>
								<option value='type5'>Type5</option></select>
							 <br /><br /><span class='width100'><label>Remarks:</label></span>
								<textarea style='vertical-align: top' name='osremarks' rows='2' cols='60'></textarea>
							 <br /><br />-->
							 <br />
							 <table class='width750'>
								<tr>
									<th>Date</th>
									<th>Diagnosis</th>
									<th>Type</th>
									<th>Remarks</th>
								</tr>
							</table>
						 </div>
						
						
					 <br /><hr /><br /><h4 class='indent'>Part II</h4>
						 <div class='width750'>
							 <hr /><h4 class='center'>Please use this part for consultation of illness/well check-up (FP, immunization, etc.). You may use any equivalent ledger in your facility.</h4><hr />
							 <!-- <br /><span class='width100'><label>Date:</label></span>
								<input type='text' size=8 name='p2date'>(mm/dd/yyyy)
							 <br /><br /><span class='width100'><label>History of Present Illness:</label></span>
								<textarea style='vertical-align: top' name='p2history' rows='2' cols='60'></textarea>
							 <br /><br /><span class='width100'><label>Physical Exam:</label></span>
								<textarea style='vertical-align: top' name='p2pe' rows='2' cols='60'></textarea>
							 <br /><br /><span class='width100'><label>Assessment/ Impression:</label></span>
								<textarea style='vertical-align: top' name='p2assessment' rows='2' cols='60'></textarea>
							 <br /><br /><span class='width100'><label>Treatment/ Management Plan:</label></span>
								<textarea style='vertical-align: top' name='p2treatment' rows='2' cols='60'></textarea><br /><br />
							 </div>-->
							 
							<br />
							<table class='width750'>
								<tr>
									<th>Date</th>
									<th>History of Present Illness</th>
									<th>Physical Exam</th>
									<th>Assessment/ Impression</th>
									<th>Treatment/ Management Plan</th>
								</tr>
							</table>
						</div>
					 </form>
				 </div>
			 </div>
		 </body>
	 </html>

