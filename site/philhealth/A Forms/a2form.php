<?php
	include "../class/dbConnect.php";
	include "class/mypdf/mypdf.php";
	echo"<html>
		<head>
			<title>A2 Form</title>
			<link rel='stylesheet' href='../styles/style.css' type='text/css'  />
			<script type='text/javascript'>
				function hideDiv()
				{
					var divHeader = document.getElementById('header');
					divHeader.style.background=\"#FFFFFF\";
					
					var divHeader1 = document.getElementsByTagName('h1');
					for (var i=0; i<divHeader1.length; i++) 
					{
					    // applies css style
					    divHeader1[i].style.cssText = \"text-shadow: 2px 2px 2px #FFFFFF; color:black;\"
					    
					}
					
					var divHeader2 = document.getElementsByTagName('h2');
					for (var i=0; i<divHeader2.length; i++) 
					{
					    // applies css style
					    divHeader2[i].style.cssText = \"text-shadow: 2px 2px 2px #FFFFFF; color:black;\"
					    
					}
					
					var divHeader4 = document.getElementsByTagName('h4');
					for (var i=0; i<divHeader4.length; i++) 
					{
					    // applies css style
					    divHeader4[i].style.cssText = \"text-shadow: 2px 2px 2px #FFFFFF; color:black;\"
					    
					}
					
				
					var bodyDiv = document.getElementById('body');
					bodyDiv.style.backgroundColor=\"#FFFFFF\";
					
					mainDiv = document.getElementsByClassName('Show');
				    for (var i = 0; i < mainDiv.length; i++) {
				        mainDiv[i].style.display=\"none\";
				    }
										
					var hfDiv = document.getElementById('facility');
					var br = document.createElement('br');
					hfDiv.insertBefore(br, hfDiv.firstChild);
					hfDiv.insertBefore(br, hfDiv.firstChild);
					
														
				}
			</script>
		</head>

		<body>
			<div id='container'>
				<div id='header'>
					<h4 class='shadow'><span class='indent10'>Annex A2</span></h4><br />					
					<h1>PHILIPPINE HEALTH INSURANCE CORPORATION</h1>
					<h2>PCB PROVIDER CLIENTELE PROFILE</h2>
					
					<div class='width890 right Show'>
						<button type='button' title='Printer Friendly Format' onclick='hideDiv();'>
							<img src='../styles/images/printer.png' alt='Printer Friendly Format'>
						</button>
						<a type='button' title='Download XML File' download href='../xml/A2.xml' style='cursor:default'>
							<img src='../styles/images/xml.png' alt='Download XML File' style='width: 30px; height: 30px;'>
						</a>
					</div>
				</div>
				<div id='body'>
					<div class='width750 center Show'>
						<form name='a2form' method='POST'>
						<br /><hr />";
	
						$function->fromDateToDate(); //Load Month and Year Selection
						
						if (isset($_REQUEST['submitDate']) && $_REQUEST['submitDate']=='Add New Form')
						{
							$sdate1 = strftime("%m/%d/%Y",mktime(0,0,0,$_POST['startMonth'],1,$_POST['year']));
							$edate1 = strftime("%m/%d/%Y",mktime(0,0,0,($_POST['endMonth']+1),0,$_POST['year']));
								
							$newSDate = date("Y-m-d", strtotime($sdate1));
							$newEDate = date("Y-m-d", strtotime($edate1));
								
							$PCB_Provider = $_SESSION['datanode']['name'];
							$region = $dbase->_getPCBProvider('region');
							$municipality = $_SESSION['lgu'];
							$prov = $_SESSION['province'];
								
														
							if ($newSDate > $newEDate)
							{
								echo "<script>alert('Start Month is Greater Than End Month')</script>";
							}
							
							$arrValue = array($dbase->_history('11', 'Member', 'M', '' , $newSDate, $newEDate),
												$dbase->_history('11', 'Dependent', 'M', '' , $newSDate, $newEDate),
												$dbase->_history('11', 'Dependent', 'F', 'Y' , $newSDate, $newEDate),
												$dbase->_history('11', 'Member', 'F', 'N' , $newSDate, $newEDate),
												$dbase->_history('11', 'Dependent', 'F', 'N' , $newSDate, $newEDate),
												$dbase->_history('11', 'Member', 'F', 'Y' , $newSDate, $newEDate),
												$dbase->_countTotalMemDep('M',$newSDate, $newEDate, 60),
												$dbase->_countTotalMemDep('M',$newSDate, $newEDate, 25, 59),
												$dbase->_countTotalMemDep('F',$newSDate, $newEDate, 60),
												$dbase->_countTotalMemDep('F',$newSDate, $newEDate, 25, 59),
												$dbase->_countTotalMemDep('F',$newSDate, $newEDate, 2, 5),
												$dbase->_countTotalMemDep('F',$newSDate, $newEDate, 16, 24),
												$dbase->_countTotalMemDep('M',$newSDate, $newEDate, 6, 15),
												$dbase->_countTotalMemDep('F',$newSDate, $newEDate, 1),
												$dbase->_countTotalMemDep('F',$newSDate, $newEDate, 6, 15),
												$dbase->_countTotalMemDep('M',$newSDate, $newEDate, 1),
												$dbase->_countTotalMemDep('M',$newSDate, $newEDate, 2, 5),
												$dbase->_countTotalMemDep('M',$newSDate, $newEDate, 16, 24),
												$dbase->_breastCancerScreening('Dependent', $newSDate, $newEDate),
												$dbase->_breastCancerScreening('Member', $newSDate, $newEDate),
												$dbase->_history('HYPERMED', 'Member', 'M', '' , $newSDate, $newEDate),
												$dbase->_history('HYPERMED', 'Dependent', 'M', '' , $newSDate, $newEDate),
												$dbase->_history('HYPERMED', 'Dependent', 'F', 'Y' , $newSDate, $newEDate),
												$dbase->_history('HYPERMED', 'Member', 'F', 'N' , $newSDate, $newEDate),
												$dbase->_history('HYPERMED', 'Dependent', 'F', 'N' , $newSDate, $newEDate),
												$dbase->_history('HYPERMED', 'Member', 'F', 'Y' , $newSDate, $newEDate),
												($dbase->_countHypertension('Hypertension Stage 2', 'Member', 'M', '', $newSDate, $newEDate)==null ? 0 : $dbase->_countHypertension('Hypertension Stage 2', 'Member', 'M', '', $newSDate, $newEDate)),
												($dbase->_countHypertension('Hypertension Stage 2', 'Dependent', 'M', '', $newSDate, $newEDate)==null ? 0 : $dbase->_countHypertension('Hypertension Stage 2', 'Dependent', 'M', '', $newSDate, $newEDate)),
												($dbase->_countHypertension('Hypertension Stage 2', 'Dependent', 'F', 'Y', $newSDate, $newEDate)==null ? 0 : $dbase->_countHypertension('Hypertension Stage 2', 'Dependent', 'F', 'Y', $newSDate, $newEDate)),
												($dbase->_countHypertension('Hypertension Stage 2', 'Member', 'F', 'N', $newSDate, $newEDate)==null ? 0 : $dbase->_countHypertension('Hypertension Stage 2', 'Member', 'F', 'N', $newSDate, $newEDate)),
												($dbase->_countHypertension('Hypertension Stage 2', 'Dependent', 'F', 'N', $newSDate, $newEDate)==null ? 0 : $dbase->_countHypertension('Hypertension Stage 2', 'Dependent', 'F', 'N', $newSDate, $newEDate)),
												($dbase->_countHypertension('Hypertension Stage 2', 'Member', 'F', 'Y', $newSDate, $newEDate)==null ? 0 : $dbase->_countHypertension('Hypertension Stage 2', 'Member', 'F', 'Y', $newSDate, $newEDate)),
												$dbase->_history('6', 'Member', 'M', '' , $newSDate, $newEDate),
												$dbase->_history('6', 'Dependent', 'M', '' , $newSDate, $newEDate),
												$dbase->_history('6', 'Member', 'F', '' , $newSDate, $newEDate),
												$dbase->_history('6', 'Dependent', 'F', '' , $newSDate, $newEDate),
												$dbase->_history('ORALHYPO', 'Member', 'M', '' , $newSDate, $newEDate),
												$dbase->_history('ORALHYPO', 'Dependent', 'M', '' , $newSDate, $newEDate),
												$dbase->_history('ORALHYPO', 'Member', 'F', '' , $newSDate, $newEDate),
												$dbase->_history('ORALHYPO', 'Dependent', 'F', '' , $newSDate, $newEDate),
												0,
												0,
												0,
												0,
												$dbase->_ppsScreening('CCS', 'Dependent', $newSDate, $newEDate),
												$dbase->_ppsScreening('CCS', 'Member', $newSDate, $newEDate),
												($dbase->_countHypertension('Prehypertension', 'Member', 'M', '', $newSDate, $newEDate)==null ? 0 : $dbase->_countHypertension('Prehypertension', 'Member', 'M', '', $newSDate, $newEDate)),
												($dbase->_countHypertension('Prehypertension', 'Dependent', 'M', '', $newSDate, $newEDate)==null ? 0 : $dbase->_countHypertension('Prehypertension', 'Dependent', 'M', '', $newSDate, $newEDate)),
												($dbase->_countHypertension('Prehypertension', 'Dependent', 'F', 'Y', $newSDate, $newEDate)==null ? 0 : $dbase->_countHypertension('Prehypertension', 'Dependent', 'F', 'Y', $newSDate, $newEDate)),
												($dbase->_countHypertension('Prehypertension', 'Member', 'F', 'N', $newSDate, $newEDate)==null ? 0 : $dbase->_countHypertension('Prehypertension', 'Member', 'F', 'N', $newSDate, $newEDate)),
												($dbase->_countHypertension('Prehypertension', 'Dependent', 'F', 'N', $newSDate, $newEDate)==null ? 0 : $dbase->_countHypertension('Prehypertension', 'Dependent', 'F', 'N', $newSDate, $newEDate)),
												($dbase->_countHypertension('Prehypertension', 'Member', 'F', 'Y', $newSDate, $newEDate)==null ? 0 : $dbase->_countHypertension('Prehypertension', 'Member', 'F', 'Y', $newSDate, $newEDate)),
												$dbase->_waist('M', 'Member', $newSDate, $newEDate),
												$dbase->_waist('M', 'Dependent', $newSDate, $newEDate),
												$dbase->_waist('F', 'Member', $newSDate, $newEDate),
												$dbase->_waist('F', 'Dependent', $newSDate, $newEDate),
												($dbase->_countHypertension('Hypertension Stage 1', 'Member', 'M', '', $newSDate, $newEDate)==null ? 0 : $dbase->_countHypertension('Hypertension Stage 1', 'Member', 'M', '', $newSDate, $newEDate)),
												($dbase->_countHypertension('Hypertension Stage 1', 'Dependent', 'M', '', $newSDate, $newEDate)==null ? 0 : $dbase->_countHypertension('Hypertension Stage 1', 'Dependent', 'M', '', $newSDate, $newEDate)),
												($dbase->_countHypertension('Hypertension Stage 1', 'Dependent', 'F', 'Y', $newSDate, $newEDate)==null ? 0 : $dbase->_countHypertension('Hypertension Stage 1', 'Dependent', 'F', 'Y', $newSDate, $newEDate)),
												($dbase->_countHypertension('Hypertension Stage 1', 'Member', 'F', 'N', $newSDate, $newEDate)==null ? 0 : $dbase->_countHypertension('Hypertension Stage 1', 'Member', 'F', 'N', $newSDate, $newEDate)),
												($dbase->_countHypertension('Hypertension Stage 1', 'Dependent', 'F', 'N', $newSDate, $newEDate)==null ? 0 : $dbase->_countHypertension('Hypertension Stage 1', 'Dependent', 'F', 'N', $newSDate, $newEDate)),
												($dbase->_countHypertension('Hypertension Stage 1', 'Member', 'F', 'Y', $newSDate, $newEDate)==null ? 0 : $dbase->_countHypertension('Hypertension Stage 1', 'Member', 'F', 'Y', $newSDate, $newEDate)));
							unlink('../xml/A2.xml');
							_generateXML('A2', 'm_consult_philhealth_a2', $arrValue);
						
						}
						else
						{
							$PCB_Provider = '';
							$region = '';
							$municipality = '';
							$prov = '';
							$newSDate = ' ';
							$newEDate = ' ';
						}
							
						
				echo "<br />
						<input type='submit' name='submitDate' value='Add New Form'><hr />
						<br />
					</div>
					<div id='facility' class='width750 center'>
						<h4 class='center'>Name of Health Care Facility<br />
						<input style='text-align:center;' type='text' size=25 name='nameHCF' value='$PCB_Provider'></h4>
						<br />
					</div>
						
					<div class='width750'>
						<hr /><h4 class='center'>I. PCB Provider Data</h4><hr />
						<br />
						<p class='indent200'><span class='width110'><label>Region: </label></span><input type='text' name='region' value='$region'><br />
						<span class='width110'><label>Province: </label></span><input type='text' name='prov' value='$prov'><br />
						<span class='width110'><label>Municipality: </label></span><input type='text' name='municipality' value=$municipality></p>
						<br /><br />
						<h4>No. of Assigned Families:</h4><br />
						<p class='columns2 indent90'>						
						<span class='width110'><label>SP-NHTS: </label></span><input type='text' name='nhts' size=5 value=".($dbase->_countMemberByType('SP-NHTS',$newSDate, $newEDate)==null ? 0 : $dbase->_countMemberByType('SP-NHTS',$newSDate, $newEDate))."><br />
						<span class='width110'><label>SP-LGU: </label></span><input type='text' name='lgu' size=5 value=".($dbase->_countMemberByType('SP-LGU',$newSDate, $newEDate)==null ? 0 : $dbase->_countMemberByType('SP-LGU',$newSDate, $newEDate))."><br />
						<span class='width110'><label>SP-NGA: </label></span><input type='text' name='nga' size=5 value=".($dbase->_countMemberByType('SP-NGA',$newSDate, $newEDate)==null ? 0 : $dbase->_countMemberByType('SP-NGA',$newSDate, $newEDate))."><br />
						<span class='width110'><label>SP-Private: </label></span><input type='text' name='private' size=5 value=".($dbase->_countMemberByType('SP-Private',$newSDate, $newEDate)==null ? 0 : $dbase->_countMemberByType('SP-Private',$newSDate, $newEDate))."><br />
						<span class='width110'><label>IPP-OG: </label></span><input type='text' name='og' size=5 value=".($dbase->_countMemberByType('IPP-OG',$newSDate, $newEDate)==null ? 0 : $dbase->_countMemberByType('IPP-OG',$newSDate, $newEDate))."><br />
						<span class='width110'><label>IPP-OFW: </label></span><input type='text' name='ofw' size=5 value=".($dbase->_countMemberByType('IPP-OFW',$newSDate, $newEDate)==null ? 0 : $dbase->_countMemberByType('IPP-OFW',$newSDate, $newEDate))."><br />
						</p>
						<br />
						<p class='center'><label>Non-PHIC Members: </label><input type='text' name='nonphic' size=5 value=".($dbase->_countMemberByType('NON-PHIC',$newSDate, $newEDate)==null ? 0 : $dbase->_countMemberByType('NON-PHIC',$newSDate, $newEDate))."></p><br />
					</div>
					
					<div class='width750'>
						<hr /><h4 class='center'>II. Age - Sex Distribution</h4><hr />
						<br />
						<table class='center'>
							<tr>
								<th rowspan='2' width='200px'>Age Group</th>
								<th colspan='3' width='300px'>Members and Dependents</th>
							</tr>
							<tr>
								
								<th width='100px'>Male</th>
								<th width='100px'>Female</th>
								<th width='100px'>Total</th>
							</tr>
							<tr>
								<td>0-1 Year</td>
								<td><input type='text' name='asMale' size=4 value=".$dbase->_countTotalMemDep('M',$newSDate, $newEDate, 1)."></td>
								<td><input type='text' name='asFemale' size=4 value=".$dbase->_countTotalMemDep('F',$newSDate, $newEDate, 1)."></td>
								<td><input type='text' name='0total' size=4 value=".$dbase->_countTotalMemDep('',$newSDate, $newEDate, 1)."></td>
							</tr>
							<tr>
								<td>2-5 Years</td>
								<td><input type='text' name='asMale' size=4 value=".$dbase->_countTotalMemDep('M',$newSDate, $newEDate, 2, 5)."></td>
								<td><input type='text' name='asFemale' size=4 value=".$dbase->_countTotalMemDep('F',$newSDate, $newEDate, 2, 5)."></td>
								<td><input type='text' name='2total' size=4 value=".$dbase->_countTotalMemDep('',$newSDate, $newEDate, 2, 5)."></td>
							</tr>
							<tr>
								<td>6-15 Years</td>
								<td><input type='text' name='asMale' size=4 value=".$dbase->_countTotalMemDep('M',$newSDate, $newEDate, 6, 15)."></td>
								<td><input type='text' name='asFemale' size=4 value=".$dbase->_countTotalMemDep('F',$newSDate, $newEDate, 6, 15)."></td>
								<td><input type='text' name='6total' size=4 value=".$dbase->_countTotalMemDep('',$newSDate, $newEDate, 6, 15)."></td>
							</tr>
							<tr>
								<td>16-24 Years</td>
								<td><input type='text' name='asMale' size=4 value=".$dbase->_countTotalMemDep('M',$newSDate, $newEDate, 16, 24)."></td>
								<td><input type='text' name='asFemale' size=4 value=".$dbase->_countTotalMemDep('F',$newSDate, $newEDate, 16, 24)."></td>
								<td><input type='text' name='16total' size=4 value=".$dbase->_countTotalMemDep('',$newSDate, $newEDate, 16, 24)."></td>
							</tr>
							<tr>
								<td>25-59 Years</td>
								<td><input type='text' name='asMale' size=4 value=".$dbase->_countTotalMemDep('M',$newSDate, $newEDate, 25, 59)."></td>
								<td><input type='text' name='asFemale' size=4 value=".$dbase->_countTotalMemDep('F',$newSDate, $newEDate, 25, 59)."></td>
								<td><input type='text' name='25total' size=4 value=".$dbase->_countTotalMemDep('',$newSDate, $newEDate, 25, 59)."></td>
							</tr>
							<tr>
								<td>60 Years and Above</td>
								<td><input type='text' name='asMale' size=4 value=".$dbase->_countTotalMemDep('M',$newSDate, $newEDate, 60)."></td>
								<td><input type='text' name='asFemale' size=4 value=".$dbase->_countTotalMemDep('F',$newSDate, $newEDate, 60)."></td>
								<td><input type='text' name='60total' size=4 value=".$dbase->_countTotalMemDep('',$newSDate, $newEDate, 60)."></td>
							</tr>
							<tr>
								<td></td>
								<td colspan='3'><hr / ><hr / ></td>
								
							</tr>
							<tr>
								<th>TOTAL</th>
								<td><input type='text' name='maleTotal' size=4 value=".$dbase->_countTotalMemDep('M',$newSDate, $newEDate)."></td>
								<td><input type='text' name='femaleTotal' size=4 value=".$dbase->_countTotalMemDep('F',$newSDate, $newEDate)."></td>
								<td><input type='text' name='astotal' size=4 value=".$dbase->_countTotalMemDep('',$newSDate, $newEDate)."></td>
							</tr>
						</table>
						<br /><br />
					</div>

					<div class='width750'>
						<hr /><h4 class='center'>III. Primary Preventive Services</h4><hr />
						<br />
						<table class='center'>
							<tr>
								<th rowspan='2' width='300px'></th>
								<th colspan='2' width='200px'># of Members and Dependents</th>
							</tr>
							<tr>
								<th width='100px'>Members</th>
								<th width='100px'>Dependents</th>
							</tr>
							<tr>
								<th>Breast Cancer Screening<br />Female, 25 yearls old and above</th>
								<td><input type='text' name='breastMember' size=4 value=".$dbase->_breastCancerScreening('Member', $newSDate, $newEDate)."></td>
								<td><input type='text' name='breastDependent' size=4 value=".$dbase->_breastCancerScreening('Dependent', $newSDate, $newEDate)."></td>
							</tr>
							<tr>
								<th>Cervical Cancer Screening<br />Female, 25 to 55 years old with intact uterus</th>
								<td><input type='text' name='cervicalMember' size=4 value=".$dbase->_ppsScreening('CCS', 'Member', $newSDate, $newEDate)."></td>
								<td><input type='text' name='cervicalDependent' size=4 value=".$dbase->_ppsScreening('CCS', 'Dependent', $newSDate, $newEDate)."></td>
							</tr>
						</table>
						<br /><br />
					</div>

					<div class='width750'>
						<hr /><h4 class='center'>IV. Diabetes Mellitus</h4><hr />
						<br />
						<table class='width650'>
							<tr class='border'>
								<th rowspan='3' width='350px'>Cases</th>
								<th colspan='6' width='300px'># of Members and Dependents</th>
							</tr>
							<tr class='border'>
								<th colspan='2' width='100px'>Member</th>
								<th colspan='2' width='100px'>Dependent</th>
								<th colspan='2' width='100px'>Total</th>
							</tr>
							<tr class='border'>
								<th width='50px'>M</th>
								<th width='50px'>F</th>
								<th width='50px'>M</th>
								<th width='50px'>F</th>
								<th width='50px'>M</th>
								<th width='50px'>F</th>
							</tr>
							<tr class='border'>
								<td><h4>with symptoms/signs of polyuria, polydipsia, weight loss</h4></td>
								<td class='center inputWidth'><input type='text' name='symmemMale' size=2 value=0></td>
								<td class='center inputWidth'><input type='text' name='symmemFemale' size=2 value=0></td>
								<td class='center inputWidth'><input type='text' name='symdepMale' size=2 value=0></td>
								<td class='center inputWidth'><input type='text' name='symdepFemale' size=2 value=0></td>
								<td class='center inputWidth'><input type='text' name='symtotMale' size=2 value=0></td>
								<td class='center inputWidth'><input type='text' name='symtotFemale' size=2 value=0></td>
							</tr>
							<tr class='border'>
								<td><h4>Waist circumference</h4></td>
								<td colspan='6'></td>
							</tr>
							<tr class='border'>
								<td><span class='indent70'>&ge;80cm (female)</span></td>
								<td></td>
								<td class='center inputWidth'><input type='text' name='80memFemale' size=2 value=".$dbase->_waist('F', 'Member', $newSDate, $newEDate)."></td>
								<td></td>
								<td class='center inputWidth'><input type='text' name='80depFemale' size=2 value=".$dbase->_waist('F', 'Dependent', $newSDate, $newEDate)."></td>
								<td></td>
								<td class='center inputWidth'><input type='text' name='80totFemale' size=2 value=".$dbase->_waist('F', 'Total', $newSDate, $newEDate)."></td>
							</tr>
							<tr class='border'>
								<td><span class='indent70'>&ge;90cm (male)</span></td>
								<td class='center inputWidth'><input type='text' name='90memMale' size=2 value=".$dbase->_waist('M', 'Member', $newSDate, $newEDate)."></td>
								<td></td>
								<td class='center inputWidth'><input type='text' name='90depMale' size=2 value=".$dbase->_waist('M', 'Dependent', $newSDate, $newEDate)."></td>
								<td></td>
								<td class='center inputWidth'><input type='text' name='90totMale' size=2 value=".$dbase->_waist('M', 'Total', $newSDate, $newEDate)."></td>
								<td></td>
							</tr>
							<tr class='border'>
								<td><h4>History of diagnosis of diabetes</h4></td>
								<td class='center inputWidth'><input type='text' name='historymemMale' size=2 value=".$dbase->_history('6', 'Member', 'M', '' , $newSDate, $newEDate)."></td>
								<td class='center inputWidth'><input type='text' name='historymemFemale' size=2 value=".$dbase->_history('6', 'Member', 'F', '' , $newSDate, $newEDate)."></td>
								<td class='center inputWidth'><input type='text' name='historydepMale' size=2 value=".$dbase->_history('6', 'Dependent', 'M', '' , $newSDate, $newEDate)."></td>
								<td class='center inputWidth'><input type='text' name='historydepFemale' size=2 value=".$dbase->_history('6', 'Dependent', 'F', '' , $newSDate, $newEDate)."></td>
								<td class='center inputWidth'><input type='text' name='historytotMale' size=2 value=".$dbase->_history('6', 'Total', 'M', '' , $newSDate, $newEDate)."></td>
								<td class='center inputWidth'><input type='text' name='historytotFemale' size=2 value=".$dbase->_history('6', 'Total', 'F', '' , $newSDate, $newEDate)."></td>
							</tr>
							<tr class='border'>
								<td><h4>Intake of oral hypoglycemic agents</h4></td>
								<td class='center inputWidth'><input type='text' name='intakememMale' size=2 value=".$dbase->_history('ORALHYPO', 'Member', 'M', '' , $newSDate, $newEDate)."></td>
								<td class='center inputWidth'><input type='text' name='intakememFemale' size=2 value=".$dbase->_history('ORALHYPO', 'Member', 'F', '' , $newSDate, $newEDate)."></td>
								<td class='center inputWidth'><input type='text' name='intakedepMale' size=2 value=".$dbase->_history('ORALHYPO', 'Dependent', 'M', '' , $newSDate, $newEDate)."></td>
								<td class='center inputWidth'><input type='text' name='intakedepFemale' size=2 value=".$dbase->_history('ORALHYPO', 'Dependent', 'F', '' , $newSDate, $newEDate)."></td>
								<td class='center inputWidth'><input type='text' name='intaketotMale' size=2 value=".$dbase->_history('ORALHYPO', 'Total', 'M', '' , $newSDate, $newEDate)."></td>
								<td class='center inputWidth'><input type='text' name='intaketotFemale' size=2 value=".$dbase->_history('ORALHYPO', 'Total', 'F', '' , $newSDate, $newEDate)."></td>
							</tr>
						</table>
						<br /><br />
					</div>

					<div class='width750'>
						<hr /><h4 class='center'>V. Hypertension</h4><hr />
						<br />
						<table class='width700'>
							<tr class='border'>
								<th rowspan='4' width='210px'>Cases</th>
								<th colspan='7' width='490px'># of Members and Dependents</th>
							</tr>
							<tr class='border'>
								<th colspan='3' width='210px'>Members</th>
								<th colspan='3' width='210px'>Dependents</th>
								<th rowspan='3' width='70px'>Total</th>
							</tr>
							<tr class='border'>
								<th rowspan='2' width='60px'>Male</th>
								<th colspan='2' width='150px'>Female</th>
								<th rowspan='2' width='60px'>Male</th>
								<th colspan='2' width='150px'>Female</th>
							</tr>
							<tr class='border'>
								<th width='75px'>Non Pregnant</th>
								<th width='75px'>Pregnant</th>
								<th width='75px'>Non Pregnant</th>
								<th width='75px'>Pregnant</th>
							</tr>
							<tr class='border'>
								<td><h4>Adult with<br />BP < 140/90 mmHg</h4></td>
								<td class='center inputWidth'><input type='text' name='c1memMale' size=4 value=".($dbase->_countHypertension('Prehypertension', 'Member', 'M', '', $newSDate, $newEDate)==null ? 0 : $dbase->_countHypertension('Prehypertension', 'Member', 'M', '', $newSDate, $newEDate))."></td>
								<td class='center inputWidth'><input type='text' name='c1memnpFemale' size=4 value=".($dbase->_countHypertension('Prehypertension', 'Member', 'F', 'N', $newSDate, $newEDate)==null ? 0 : $dbase->_countHypertension('Prehypertension', 'Member', 'F', 'N', $newSDate, $newEDate))."></td>
								<td class='center inputWidth'><input type='text' name='c1mempFemale' size=4 value=".($dbase->_countHypertension('Prehypertension', 'Member', 'F', 'Y', $newSDate, $newEDate)==null ? 0 : $dbase->_countHypertension('Prehypertension', 'Member', 'F', 'Y', $newSDate, $newEDate))."></td>
								<td class='center inputWidth'><input type='text' name='c1depMale' size=4 value=".($dbase->_countHypertension('Prehypertension', 'Dependent', 'M', '', $newSDate, $newEDate)==null ? 0 : $dbase->_countHypertension('Prehypertension', 'Dependent', 'M', '', $newSDate, $newEDate))."></td>
								<td class='center inputWidth'><input type='text' name='c1depnpFemale' size=4 value=".($dbase->_countHypertension('Prehypertension', 'Dependent', 'F', 'N', $newSDate, $newEDate)==null ? 0 : $dbase->_countHypertension('Prehypertension', 'Dependent', 'F', 'N', $newSDate, $newEDate))."></td>
								<td class='center inputWidth'><input type='text' name='c1deppFemale' size=4 value=".($dbase->_countHypertension('Prehypertension', 'Dependent', 'F', 'Y', $newSDate, $newEDate)==null ? 0 : $dbase->_countHypertension('Prehypertension', 'Dependent', 'F', 'Y', $newSDate, $newEDate))."></td>
								<td class='center inputWidth'><input type='text' name='c1Total' size=4 value=".($dbase->_countHypertension('Prehypertension', 'Total', '', '', $newSDate, $newEDate)==null ? 0 : $dbase->_countHypertension('Prehypertension', 'Total', '', '', $newSDate, $newEDate))."></td>
							</tr>
							<tr class='border'>
								<td><h4>Adult with<br />BP >/= 140/90 mmHg but less than 180/120 mmHg</h4></td>
								<td class='center inputWidth'><input type='text' name='c2memMale' size=4 value=".($dbase->_countHypertension('Hypertension Stage 1', 'Member', 'M', '', $newSDate, $newEDate)==null ? 0 : $dbase->_countHypertension('Hypertension Stage 1', 'Member', 'M', '', $newSDate, $newEDate))."></td>
								<td class='center inputWidth'><input type='text' name='c2memnpFemale' size=4 value=".($dbase->_countHypertension('Hypertension Stage 1', 'Member', 'F', 'N', $newSDate, $newEDate)==null ? 0 : $dbase->_countHypertension('Hypertension Stage 1', 'Member', 'F', 'N', $newSDate, $newEDate))."></td>
								<td class='center inputWidth'><input type='text' name='c2mempFemale' size=4 value=".($dbase->_countHypertension('Hypertension Stage 1', 'Member', 'F', 'Y', $newSDate, $newEDate)==null ? 0 : $dbase->_countHypertension('Hypertension Stage 1', 'Member', 'F', 'Y', $newSDate, $newEDate))."></td>
								<td class='center inputWidth'><input type='text' name='c2depMale' size=4 value=".($dbase->_countHypertension('Hypertension Stage 1', 'Dependent', 'M', '', $newSDate, $newEDate)==null ? 0 : $dbase->_countHypertension('Hypertension Stage 1', 'Dependent', 'M', '', $newSDate, $newEDate))."></td>
								<td class='center inputWidth'><input type='text' name='c2depnpFemale' size=4 value=".($dbase->_countHypertension('Hypertension Stage 1', 'Dependent', 'F', 'N', $newSDate, $newEDate)==null ? 0 : $dbase->_countHypertension('Hypertension Stage 1', 'Dependent', 'F', 'N', $newSDate, $newEDate))."></td>
								<td class='center inputWidth'><input type='text' name='c2deppFemale' size=4 value=".($dbase->_countHypertension('Hypertension Stage 1', 'Dependent', 'F', 'Y', $newSDate, $newEDate)==null ? 0 : $dbase->_countHypertension('Hypertension Stage 1', 'Dependent', 'F', 'Y', $newSDate, $newEDate))."></td>
								<td class='center inputWidth'><input type='text' name='c2Total' size=4 value=".($dbase->_countHypertension('Hypertension Stage 1', 'Total', '', '', $newSDate, $newEDate)==null ? 0 : $dbase->_countHypertension('Hypertension Stage 1', 'Total', '', '', $newSDate, $newEDate))."></td>
							</tr>
							<tr class='border'>
								<td><h4>Adult with<br />BP > 180/120 mmHg</h4></td>
								<td class='center inputWidth'><input type='text' name='c3memMale' size=4 value=".($dbase->_countHypertension('Hypertension Stage 2', 'Member', 'M', '', $newSDate, $newEDate)==null ? 0 : $dbase->_countHypertension('Hypertension Stage 2', 'Member', 'M', '', $newSDate, $newEDate))."></td>
								<td class='center inputWidth'><input type='text' name='c3memnpFemale' size=4 value=".($dbase->_countHypertension('Hypertension Stage 2', 'Member', 'F', 'N', $newSDate, $newEDate)==null ? 0 : $dbase->_countHypertension('Hypertension Stage 2', 'Member', 'F', 'N', $newSDate, $newEDate))."></td>
								<td class='center inputWidth'><input type='text' name='c3mempFemale' size=4 value=".($dbase->_countHypertension('Hypertension Stage 2', 'Member', 'F', 'Y', $newSDate, $newEDate)==null ? 0 : $dbase->_countHypertension('Hypertension Stage 2', 'Member', 'F', 'Y', $newSDate, $newEDate))."></td>
								<td class='center inputWidth'><input type='text' name='c3depMale' size=4 value=".($dbase->_countHypertension('Hypertension Stage 2', 'Dependent', 'M', '', $newSDate, $newEDate)==null ? 0 : $dbase->_countHypertension('Hypertension Stage 2', 'Dependent', 'M', '', $newSDate, $newEDate))."></td>
								<td class='center inputWidth'><input type='text' name='c3depnpFemale' size=4 value=".($dbase->_countHypertension('Hypertension Stage 2', 'Dependent', 'F', 'N', $newSDate, $newEDate)==null ? 0 : $dbase->_countHypertension('Hypertension Stage 2', 'Dependent', 'F', 'N', $newSDate, $newEDate))."></td>
								<td class='center inputWidth'><input type='text' name='c3deppFemale' size=4 value=".($dbase->_countHypertension('Hypertension Stage 2', 'Dependent', 'F', 'Y', $newSDate, $newEDate)==null ? 0 : $dbase->_countHypertension('Hypertension Stage 2', 'Dependent', 'F', 'Y', $newSDate, $newEDate))."></td>
								<td class='center inputWidth'><input type='text' name='c3Total' size=4 value=".($dbase->_countHypertension('Hypertension Stage 2', 'Total', '', '', $newSDate, $newEDate)==null ? 0 : $dbase->_countHypertension('Hypertension Stage 2', 'Total', '', '', $newSDate, $newEDate))."></td>
							</tr>
							<tr class='border'>
								<td><h4>History of diagnosis of hypertension</h4></td>
								<td class='center inputWidth'><input type='text' name='c4memMale' size=4 value=".$dbase->_history('11', 'Member', 'M', '' , $newSDate, $newEDate)."></td>
								<td class='center inputWidth'><input type='text' name='c4memnpFemale' size=4 value=".$dbase->_history('11', 'Member', 'F', 'N' , $newSDate, $newEDate)."></td>
								<td class='center inputWidth'><input type='text' name='c4mempFemale' size=4 value=".$dbase->_history('11', 'Member', 'F', 'Y' , $newSDate, $newEDate)."></td>
								<td class='center inputWidth'><input type='text' name='c4depMale' size=4 value=".$dbase->_history('11', 'Dependent', 'M', '' , $newSDate, $newEDate)."></td>
								<td class='center inputWidth'><input type='text' name='c4depnpFemale' size=4 value=".$dbase->_history('11', 'Dependent', 'F', 'N' , $newSDate, $newEDate)."></td>
								<td class='center inputWidth'><input type='text' name='c4deppFemale' size=4 value=".$dbase->_history('11', 'Dependent', 'F', 'Y' , $newSDate, $newEDate)."></td>
								<td class='center inputWidth'><input type='text' name='c4sTotal' size=4 value=".$dbase->_history('11', 'Total', '', '' , $newSDate, $newEDate)."></td>
							</tr> 
							<tr class='border'>
								<td><h4>Intake of hypertension medicine</h4></td>
								<td class='center inputWidth'><input type='text' name='c5memMale' size=4 value=".$dbase->_history('HYPERMED', 'Member', 'M', '' , $newSDate, $newEDate)."></td>
								<td class='center inputWidth'><input type='text' name='c5memnpFemale' size=4 value=".$dbase->_history('HYPERMED', 'Member', 'F', 'N' , $newSDate, $newEDate)."></td>
								<td class='center inputWidth'><input type='text' name='c5mempFemale' size=4 value=".$dbase->_history('HYPERMED', 'Member', 'F', 'Y' , $newSDate, $newEDate)."></td>
								<td class='center inputWidth'><input type='text' name='c5depMale' size=4 value=".$dbase->_history('HYPERMED', 'Dependent', 'M', '' , $newSDate, $newEDate)."></td>
								<td class='center inputWidth'><input type='text' name='c5depnpFemale' size=4 value=".$dbase->_history('HYPERMED', 'Dependent', 'F', 'N' , $newSDate, $newEDate)."></td>
								<td class='center inputWidth'><input type='text' name='c5deppFemale' size=4 value=".$dbase->_history('HYPERMED', 'Dependent', 'F', 'Y' , $newSDate, $newEDate)."></td>
								<td class='center inputWidth'><input type='text' name='c5ssTotal' size=4 value=".$dbase->_history('HYPERMED', 'Total', '', '' , $newSDate, $newEDate)."></td>
							</tr>
						</table>
						<br /><br />
					</div>
					
					</form>
				</div>
			</div>
		</body>
	</html>";
?>

