<?php
	include "class/dbConnect.php";
	echo "<html>
		<head>
			<title>A4 Form</title>
			<link rel='stylesheet' href='styles/style.css' type='text/css'  />
		</head>
		<body>
			<div id='container'>
				<div id='header'>
					<h4 class='shadow'><span class='indent10'>Annex A4</span></h4><br />					
					<h1>PHILIPPINE HEALTH INSURANCE CORPORATION</h1>
					<h2>QUARTERLY REPORT FORM</h2>
					<br />
				</div>
				<div id='body'>
					<div class='width750 center'>
						<form name='a4form' method='POST'>
						<br /><hr />";
						
						$function->fromDateToDate(); //Load Month and Year Selection
					
						if (isset($_REQUEST['submitDate']) && $_REQUEST['submitDate']=='Add New Form')
						{
							$sdate = strftime("%m/%d/%Y",mktime(0,0,0,$_POST['startMonth'],1,$_POST['year']));
							$edate = strftime("%m/%d/%Y",mktime(0,0,0,($_POST['endMonth']+1),0,$_POST['year']));
							
							$newSDate = date("Y-m-d", strtotime($sdate));
							$newEDate = date("Y-m-d", strtotime($edate));
						
							
							if ($newSDate > $newEDate)
							{
								echo "<script>alert('Start Month is Greater Than End Month')</script>";
							}
												
						}
						else 
						{
							$newSDate = ' ';
							$newEDate = ' ';
						}
																	
						
				echo "<br /> 
					<input type='submit' name='submitDate' value='Add New Form'><hr />
						<br />
						<h4 class='center'>Name of PCB Provider<br />
						<input type='text' size=25 name='namePCBP'></h4>
						<br />
						</div>
				
					<div class='width750'>
						<hr /><h4 class='center'>HEALTH FACILITY DATA<BR /> SUMMARY OF BENEFITS AVAILMENT (Members and Dependents)</h4><hr />
						<br />
					</div>
				
					<div class='width750'>
						<h4>I. Covered Period</h4>
						<p class='indent'><span class='width70'><label>From: </label></span><input style='width:100px' type='date' name='cpfrom' placeholder='(mm/dd/yyyy)' value=".($newSDate > $newEDate ? '' : $sdate)."><br />
						<span class='width70'><label>To: </label></span><input style='width:100px' type='date' name='cpto' placeholder='(mm/dd/yyyy)' value=".($newSDate > $newEDate ? '' : $edate)."></p><br /><br />
					</div>
				
					<div class='width750'>
						<h4>II. PCB Participation No.
						<input type='text' name='pcbnum'></h4><br /><br />
					</div>
				
					<div class='width750'>
						<h4>III. Municipality/City/Province
						<textarea style='vertical-align: top' name='muncitpro' rows='2' cols='50'></textarea></h4><br /><br />
					</div>
				
					<div class='width750'>
						<h4>IV. Obligated Services</h4>
						<br />
						<table class='width700'>
							<tr>
								<th width='400px'>PRIMARY PREVENTIVE SERVICES</th>
								<th width='150px'>TARGET<br />(for the quarter)</th>
								<th width='150px'>ACCOMPLISHMENT<br />(number)</th>
							</tr>
							<tr>
								<td>1. BP measurement</th>
								<td></td>
								<td></td>
							</tr>
							<tr>
								<td><span class='indent70'>Hypertensive</span></th>
								<td class='center'><input style='text-align:right' type='text' name='hyperTarget' size=8></td>
								<td class='center'><input style='text-align:right' type='text' name='hyperAccomplishment' size=8 value=".($dbase->_countBloodPressure('Hypertension',$newSDate,$newEDate)==null ? 0 : $dbase->_countBloodPressure('Hypertension',$newSDate,$newEDate))."></td>
							</tr>
							<tr>
								<td><span class='indent70'>Nonhypertensive</span></th>
								<td class='center'><input style='text-align:right' type='text' name='nonhyperTarget' size=8></td>
								<td class='center'><input style='text-align:right' type='text' name='nonhyperAccomplishment' size=8 value=".($dbase->_countBloodPressure('Normal',$newSDate,$newEDate)==null ? 0 :$dbase->_countBloodPressure('Normal',$newSDate,$newEDate))."></td>
							</tr>
							<tr>
								<td>2. Periodic clinical breast examination</th>
								<td class='center'><input style='text-align:right' type='text' name='pcbeTarget' size=8></td>
								<td class='center'><input style='text-align:right' type='text' name='pcbeAccomplishment' size=8 value=".$dbase->_countPPServices('BREASTX','',$newSDate,$newEDate)."></td>
							</tr>
							<tr>
								<td>3. Visual inspection with acetic acid</th>
								<td class='center'><input style='text-align:right' type='text' name='viwacTarget' size=8></td>
								<td class='center'><input style='text-align:right' type='text' name='viwacAccomplishment' size=8 value=".$dbase->_countPPServices('ACETIC','',$newSDate,$newEDate)."></td>
							</tr>
						</table>
						<br /><br />
					</div>
				
					<div class='width750'>
						<h4>V. Members and Dependents Served</h4>
						<br />
						<table>
							<tr>
								<th width='200px'></th>
								<th width='100px'>Male</th>
								<th width='100px'>Female</th>
								<th width='100px'>TOTAL</th>
							</tr>
						
							<tr>
								<th>Members</th>
								<th><input style='text-align:right' type='text' name='memMale' size=4 value=".$dbase->_countTotalMembers('M', $newSDate, $newEDate)."></th>
								<th><input style='text-align:right' type='text' name='memFemale' size=4 value=".$dbase->_countTotalMembers('F', $newSDate, $newEDate)."></th>
								<th><input style='text-align:right' type='text' name='memTotal' size=4 value=".$dbase->_countTotalMembers('', $newSDate, $newEDate)."></th>
							</tr>
						
							<tr>
								<th>Dependents</th>
								<th><input style='text-align:right' type='text' name='depMale' size=4 value=".$dbase->_countTotalDependents('M', $newSDate, $newEDate)."></th>
								<th><input style='text-align:right' type='text' name='depFemale' size=4 value=".$dbase->_countTotalDependents('F', $newSDate, $newEDate)."></th>
								<th><input style='text-align:right' type='text' name='depTotal' size=4 value=".$dbase->_countTotalDependents('', $newSDate, $newEDate)."></th>
							</tr>
						
							<tr>
								<th>TOTAL</th>
								<th><input style='text-align:right' type='text' name='totMale' size=4 value=".$dbase->_countTotalMemDep('M', $newSDate, $newEDate)."></th>
								<th><input style='text-align:right' type='text' name='totFemale' size=4 value=".$dbase->_countTotalMemDep('F', $newSDate, $newEDate)."></th>
								<th><input style='text-align:right' type='text' name='totTotal' size=4 value=".$dbase->_countTotalMemDep('', $newSDate, $newEDate)."></th>
							</tr>
						
						</table>
						<br /><br />
					</div>
				
					<div class='width750'>
						<h4>VI. Benefits/Services Provided</h4>
						<br />
						<table class='width600'>
							<tr>
								<th rowspan='2' width='400px'></th>
								<th colspan='4' width='200px'>No. of Members/ Dependents</th>
							</tr>
						
							<tr>
								<th colspan='2' width='100px'>Given</th>
								<th colspan='2' width='100px'>Referred</th>
							</tr>
						
							<tr>
								<td><h4>Primary Preventive Services</h4></th>
								<th width='50px'>M</th>
								<th width='50px'>D</th>
								<th width='50px'>M</th>
								<th width='50px'>D</th>
							</tr>
						
							<tr>
								<td><span class='indent'>1. Consultation</span></td>
								<td class='center inputWidth'><input type='text' name='pp1memGiven' size=1 value=".$dbase->_countConsultation('MEMBER', $newSDate, $newEDate)."></td>
								<td class='center inputWidth'><input type='text' name='pp1depGiven' size=1 value=".$dbase->_countConsultation('DEPENDENT', $newSDate, $newEDate)."></td>
								<td class='center inputWidth'><input type='text' name='pp1memReferred' size=1></td>
								<td class='center inputWidth'><input type='text' name='pp1depReferred' size=1></td>
							</tr>
						
							<tr >
								<td><span class='indent'>2. Visual inspection with acetic acid</span></td>
								<td class='center inputWidth'><input type='text' name='pp2memGiven' size=1 value=".$dbase->_countPPServices('ACETIC', 'MEMBER', $newSDate, $newEDate)."></td>
								<td class='center inputWidth'><input type='text' name='pp2depGiven' size=1 value=".$dbase->_countPPServices('ACETIC', 'DEPENDENT', $newSDate, $newEDate)."></td>
								<td class='center inputWidth'><input type='text' name='pp2memReferred' size=1></td>
								<td class='center inputWidth'><input type='text' name='pp2depReferred' size=1></td>
							</tr>
						
							<tr>
								<td><span class='indent'>3. Regular BP measurements</span></td>
								<td class='center inputWidth'><input type='text' name='pp3memGiven' size=1 value=".$dbase->_countPPServices('BPMEAS', 'MEMBER', $newSDate, $newEDate)."></td>
								<td class='center inputWidth'><input type='text' name='pp3depGiven' size=1 value=".$dbase->_countPPServices('BPMEAS', 'DEPENDENT', $newSDate, $newEDate)."></td>
								<td class='center inputWidth'><input type='text' name='pp3memReferred' size=1></td>
								<td class='center inputWidth'><input type='text' name='pp3depReferred' size=1></td>
							</tr>
						
							<tr>
								<td><span class='indent'>4. Breastfeeding program education</span></td>
								<td class='center inputWidth'><input type='text' name='pp4memGiven' size=1 value=".$dbase->_countPPServices('BREASTFEED', 'MEMBER', $newSDate, $newEDate)."></td>
								<td class='center inputWidth'><input type='text' name='pp4depGiven' size=1 value=".$dbase->_countPPServices('BREASTFEED', 'DEPENDENT', $newSDate, $newEDate)."></td>
								<td class='center inputWidth'><input type='text' name='pp4memReferred' size=1></td>
								<td class='center inputWidth'><input type='text' name='pp4depReferred' size=1></td>
							</tr>
						
							<tr>
								<td><span class='indent'>5. Periodic clinical breast examinations</span></td>
								<td class='center inputWidth'><input type='text' name='pp5memGiven' size=1 value=".$dbase->_countPPServices('BREASTX', 'MEMBER', $newSDate, $newEDate)."></td>
								<td class='center inputWidth'><input type='text' name='pp5depGiven' size=1 value=".$dbase->_countPPServices('BREASTX', 'DEPENDENT', $newSDate, $newEDate)."></td>
								<td class='center inputWidth'><input type='text' name='pp5memReferred' size=1></td>
								<td class='center inputWidth'><input type='text' name='pp5depReferred' size=1></td>
							</tr>
						
							<tr>
								<td><span class='indent'>6. Counselling for lifestyle modification</span></td>
								<td class='center inputWidth'><input type='text' name='pp6memGiven' size=1 value=".$dbase->_countPPServices('LIFEST', 'MEMBER', $newSDate, $newEDate)."></td>
								<td class='center inputWidth'><input type='text' name='pp6depGiven' size=1 value=".$dbase->_countPPServices('LIFEST', 'DEPENDENT', $newSDate, $newEDate)."></td>
								<td class='center inputWidth'><input type='text' name='pp6memReferred' size=1></td>
								<td class='center inputWidth'><input type='text' name='pp6depReferred' size=1></td>
							</tr>
						
							<tr>
								<td><span class='indent'>7. Counselling for smoking cessation</span></td>
								<td class='center inputWidth'><input type='text' name='pp7memGiven' size=1 value=".$dbase->_countPPServices('SMOKEC', 'MEMBER', $newSDate, $newEDate)."></td>
								<td class='center inputWidth'><input type='text' name='pp7depGiven' size=1 value=".$dbase->_countPPServices('SMOKEC', 'DEPENDENT', $newSDate, $newEDate)."></td>
								<td class='center inputWidth'><input type='text' name='pp7memReferred' size=1></td>
								<td class='center inputWidth'><input type='text' name='pp7depReferred' size=1></td>
							</tr>
						
							<tr>
								<td><span class='indent'>8. Body measurements</span></td>
								<td class='center inputWidth'><input type='text' name='pp8memGiven' size=1 value=".$dbase->_countPPServices('BODYM', 'MEMBER', $newSDate, $newEDate)."></td>
								<td class='center inputWidth'><input type='text' name='pp8depGiven' size=1 value=".$dbase->_countPPServices('BODYM', 'DEPENDENT', $newSDate, $newEDate)."></td>
								<td class='center inputWidth'><input type='text' name='pp8memReferred' size=1></td>
								<td class='center inputWidth'><input type='text' name='pp8depReferred' size=1></td>
							</tr>
						
							<tr>
								<td><span class='indent'>9. Digital rectal examination</span></td>
								<td class='center inputWidth'><input type='text' name='pp9memGiven' size=1 value=".$dbase->_countPPServices('RECTAL', 'MEMBER', $newSDate, $newEDate)."></td>
								<td class='center inputWidth'><input type='text' name='pp9depGiven' size=1 value=".$dbase->_countPPServices('RECTAL', 'DEPENDENT', $newSDate, $newEDate)."></td>
								<td class='center inputWidth'><input type='text' name='pp9memReferred' size=1></td>
								<td class='center inputWidth'><input type='text' name='pp9depReferred' size=1></td>
							</tr>
						
							<tr>
								<td colspan='5'><h4>Diagnostics Examinations</h4></th>
							</tr>
						
							<tr>
								<td><span class='indent'>1. Complete blood count (CBC)</span></td>
								<td class='center inputWidth'><input type='text' name='de1memGiven' size=1 value=".$dbase->_countDiagExam('CBC', 'MEMBER', $newSDate, $newEDate)."></td>
								<td class='center inputWidth'><input type='text' name='de1depGiven' size=1 value=".$dbase->_countDiagExam('CBC', 'DEPENDENT', $newSDate, $newEDate)."></td>
								<td class='center inputWidth'><input type='text' name='de1memReferred' size=1></td>
								<td class='center inputWidth'><input type='text' name='de1depReferred' size=1></td>
							</tr>
						
							<tr>
								<td><span class='indent'>2. Urinalysis</span></td>
								<td class='center inputWidth'><input type='text' name='de1memGiven' size=1 value=".$dbase->_countDiagExam('URN', 'MEMBER', $newSDate, $newEDate)."></td>
								<td class='center inputWidth'><input type='text' name='de1depGiven' size=1 value=".$dbase->_countDiagExam('URN', 'DEPENDENT', $newSDate, $newEDate)."></td>
								<td class='center inputWidth'><input type='text' name='de1memReferred' size=1></td>
								<td class='center inputWidth'><input type='text' name='de1depReferred' size=1></td>
							</tr>
						
							<tr>
								<td><span class='indent'>3. Fecalysis</span></td>
								<td class='center inputWidth'><input type='text' name='de1memGiven' size=1 value=".$dbase->_countDiagExam('FEC', 'MEMBER', $newSDate, $newEDate)."></td>
								<td class='center inputWidth'><input type='text' name='de1depGiven' size=1 value=".$dbase->_countDiagExam('FEC', 'DEPENDENT', $newSDate, $newEDate)."></td>
								<td class='center inputWidth'><input type='text' name='de1memReferred' size=1></td>
								<td class='center inputWidth'><input type='text' name='de1depReferred' size=1></td>
							</tr>
						
							<tr>
								<td><span class='indent'>4. Sputum microscopy</span></td>
								<td class='center inputWidth'><input type='text' name='de1memGiven' size=1 value=".$dbase->_countDiagExam('SPT', 'MEMBER', $newSDate, $newEDate)."></td>
								<td class='center inputWidth'><input type='text' name='de1depGiven' size=1 value=".$dbase->_countDiagExam('SPT', 'DEPENDENT', $newSDate, $newEDate)."></td>
								<td class='center inputWidth'><input type='text' name='de1memReferred' size=1></td>
								<td class='center inputWidth'><input type='text' name='de1depReferred' size=1></td>
							</tr>
						
							<tr>
								<td><span class='indent'>5. Fasting blood sugar (FBS)</span></td>
								<td class='center inputWidth'><input type='text' name='de1memGiven' size=1></td>
								<td class='center inputWidth'><input type='text' name='de1depGiven' size=1></td>
								<td class='center inputWidth'><input type='text' name='de1memReferred' size=1></td>
								<td class='center inputWidth'><input type='text' name='de1depReferred' size=1></td>
							</tr>
						
							<tr>
								<td><span class='indent'>6. Lipid profile</span></td>
								<td class='center inputWidth'><input type='text' name='de1memGiven' size=1></td>
								<td class='center inputWidth'><input type='text' name='de1depGiven' size=1></td>
								<td class='center inputWidth'><input type='text' name='de1memReferred' size=1></td>
								<td class='center inputWidth'><input type='text' name='de1depReferred' size=1></td>
							</tr>
						
							<tr>
								<td><span class='indent'>7. Chest x-ray</span></td>
								<td class='center inputWidth'><input type='text' name='de1memGiven' size=1 value=".$dbase->_countDiagExam('CXR', 'MEMBER', $newSDate, $newEDate)."></td>
								<td class='center inputWidth'><input type='text' name='de1depGiven' size=1 value=".$dbase->_countDiagExam('CXR', 'DEPENDENT', $newSDate, $newEDate)."></td>
								<td class='center inputWidth'><input type='text' name='de1memReferred' size=1></td>
								<td class='center inputWidth'><input type='text' name='de1depReferred' size=1></td>
							</tr>
						</table>
						<br /><br />
					</div>
				
					<div class='width750'>
						<h4>VII. Medicines Given</h4>
						<br />
						<table>
							<tr>
								<th width='300px'>(Generic Name)</th>
								<th colspan='2' width='200px'>No. of Members/ Dependents</th>
							</tr>
						
							<tr>
								<td><h4>I. Asthma<h4></td>
								<th width='100px'>M</th>
								<th width='100px'>D</th>
							</tr>
						
							<tr>
								<th>Med 1 <input type='text' size=15 name='asthmed1'></th>
								<th><input type='text' size=4 name='asthmed1Mem'></th>
								<th><input type='text' size=4 name='asthmed1Dep'></th>
							</tr>
						
							<tr>
								<th>Med 2 <input type='text' size=15 name='asthmed2'></th>
								<th><input type='text' size=4 name='asthmed2Mem'></th>
								<th><input type='text' size=4 name='asthmed2Dep'></th>
							</tr>
						
							<tr>
								<td colspan='3'><h4>II. AGE with no or mild dehydration</h4></td>
							</tr>
						
							<tr>
								<th>Med 1 <input type='text' size=15 name='dehydmed1'></th>
								<th><input type='text' size=4 name='dehydmed1Mem'></th>
								<th><input type='text' size=4 name='dehydmed1Dep'></th>
							</tr>
						
							<tr>
								<th>Med 2 <input type='text' size=15 name='dehydmed2'></th>
								<th><input type='text' size=4 name='dehydmed2Mem'></th>
								<th><input type='text' size=4 name='dehydmed2Dep'></th>
							</tr>
						
							<tr>
								<th>Med 3 <input type='text' size=15 name='dehydmed3'></th>
								<th><input type='text' size=4 name='dehydmed3Mem'></th>
								<th><input type='text' size=4 name='dehydmed3Dep'></th>
							</tr>
						
							<tr>
								<th>Med 4 <input type='text' size=15 name='dehydmed4'></th>
								<th><input type='text' size=4 name='dehydmed4Mem'></th>
								<th><input type='text' size=4 name='dehydmed4Dep'></th>
							</tr>
						
							<tr>
								<td colspan='3'><h4>III. URTI/Pneumonia (minimal & low risk)</h4></td>
							</tr>
						
							<tr>
								<th>Med 1 <input type='text' size=15 name='urtidmed1'></th>
								<th><input type='text' size=4 name='urtidmed1Mem'></th>
								<th><input type='text' size=4 name='urtidmed1Dep'></th>
							</tr>
						
							<tr>
								<th>Med 2 <input type='text' size=15 name='urtidmed2'></th>
								<th><input type='text' size=4 name='urtidmed2Mem'></th>
								<th><input type='text' size=4 name='urtidmed2Dep'></th>
							</tr>
						
							<tr>
								<th>Med 3 <input type='text' size=15 name='urtidmed3'></th>
								<th><input type='text' size=4 name='urtidmed3Mem'></th>
								<th><input type='text' size=4 name='urtidmed3Dep'></th>
							</tr>
						
							<tr>
								<td colspan='3'><h4>IV. UTI</h4></td>
							</tr>
						
							<tr>
								<th>Med 1 <input type='text' size=15 name='utidmed1'></th>
								<th><input type='text' size=4 name='utidmed1Mem'></th>
								<th><input type='text' size=4 name='utidmed1Dep'></th>
							</tr>
						
							<tr>
								<th>Med 2 <input type='text' size=15 name='utidmed2'></th>
								<th><input type='text' size=4 name='utidmed2Mem'></th>
								<th><input type='text' size=4 name='utidmed2Dep'></th>
							</tr>
						
							<tr>
								<td colspan='3'><h4>V. Nebulisation services</h4></td>
							</tr>
						
							<tr>
								<th>Med 1 <input type='text' size=15 name='nebdmed1'></th>
								<th><input type='text' size=4 name='nebdmed1Mem'></th>
								<th><input type='text' size=4 name='nebdmed1Dep'></th>
							</tr>
						
							<tr>
								<th>Med 1 <input type='text' size=15 name='nebdmed2'></th>
								<th><input type='text' size=4 name='nebdmed2Mem'></th>
								<th><input type='text' size=4 name='nebdmed2Dep'></th>
							</tr>
						
						</table>
						<br /><br />
					</div>
				
					<div class='width750'>
						<h4>VIII. Top 10 Common Illnesses</h4>
						<br />
						<table width='600px'>
							<tr>
								<th width='500px'>(Morbidity)</th>
								<th width='100px'>Number of Cases</th>
							</tr>";
							// this will echo the top 10 Morbidity
							$dbase->_countTopMorbidity($newSDate, $newEDate);
							
							
							/*<tr>
								<td><span class='width25'>1.</span><input style='width:350px' type='text' size=50 name='mor1' /></td>
								<td class='center'><input type='text' size=4 name='morCase1' />
							</tr>
						
							<tr>
								<td><span class='width25'>2.</span><input style='width:350px' type='text' size=50 name='mor2' /></td>
								<td class='center'><input type='text' size=4 name='morCase2' />
							</tr>
						
							<tr>
								<td><span class='width25'>3.</span><input style='width:350px' type='text' size=50 name='mor3' /></td>
								<td class='center'><input type='text' size=4 name='morCase3' />
							</tr>
						
							<tr>
								<td><span class='width25'>4.</span><input style='width:350px' type='text' size=50 name='mor4' /></td>
								<td class='center'><input type='text' size=4 name='morCase4' />
							</tr>
						
							<tr>
								<td><span class='width25'>5.</span><input style='width:350px' type='text' size=50 name='mor5' /></td>
								<td class='center'><input type='text' size=4 name='morCase5' />
							</tr>
						
							<tr>
								<td><span class='width25'>6.</span><input style='width:350px' type='text' size=50 name='mor6' /></td>
								<td class='center'><input type='text' size=4 name='morCase6' />
							</tr>
						
							<tr>
								<td><span class='width25'>7.</span><input style='width:350px' type='text' size=50 name='mor7' /></td>
								<td class='center'><input type='text' size=4 name='morCase7' />
							</tr>
						
							<tr>
								<td><span class='width25'>8.</span><input style='width:350px' type='text' size=50 name='mor8' /></td>
								<td class='center'><input type='text' size=4 name='morCase8' />
							</tr>
						
							<tr>
								<td><span class='width25'>9.</span><input style='width:350px' type='text' size=50 name='mor9' /></td>
								<td class='center'><input type='text' size=4 name='morCase9' />
							</tr>
						
							<tr>
								<td><span class='width25'>10.</span><input style='width:350px' type='text' size=50 name='mor10' /></td>
								<td class='center'><input type='text' size=4 name='morCase10' />
							</tr>*/
						echo "</table>
						<br /><br />
					</div>
					
					<div class='width750 center'>
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
							<img src='styles/images/save.png' alt='Save Form'>
						</button>
						<button type='submit' title='Cancel'>
							<img src='styles/images/cancel.png' alt='Cancel'>
						</button>
						<br />
						<button type='submit' title='Update Form'>
							<img src='styles/images/update.png' alt='Update Form'>
						</button>
						<button type='submit' title='Delete Form'>
							<img src='styles/images/delete.png' alt='Delete Form'>
						</button>
						<button type='submit' title='Printer Friendly Format'>
							<img src='styles/images/printer.png' alt='Printer Friendly Format'>
						</button>
						<button type='submit' title='Download PDF File'>
							<img src='styles/images/pdf.png' alt='Download PDF File'>
						</button>
						<button type='submit' title='Download XML File'>
							<img src='styles/images/xml.png' alt='Download XML File'>
						</button>
					</div>
					
					<div id='submit' class='width750'>
						<hr />
						<span class='width100'>
						<input type='submit' name='submit' value='Submit'></span>
						<input type='reset' name='submit' value='Reset'>
						<hr /><br />
					</div>
				
					</form>
				</div>
			</div>
		</body>
	</html>";
?>
