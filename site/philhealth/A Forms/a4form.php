<?php
	include "../class/dbConnect.php";
	
	echo "<html>
		<head>
			<title>A4 Form</title>
			<link rel='stylesheet' href='../styles/style.css' type='text/css'  />
			<script type='text/javascript'>
				function addTR(tableName)
				{
					var mainTable = document.getElementById(tableName);
					var i = mainTable.getElementsByTagName('span').length;
					//alert(i);
					var i = i + 1;
					
					var str = 'Med ';
				    var newName = str.concat(i);
				    var myName = newName.concat(' ');
				    
				    var input1Name = tableName.concat(i);
				    var input2Name = input1Name.concat('Mem');
				    var input3Name = input1Name.concat('Dep');
				    
					var row = document.createElement('tr');
					var col1 = document.createElement('th');
					var col2 = document.createElement('th');
					var col3 = document.createElement('th');
					
					var newSpan = document.createElement('span');
				    var newName = document.createTextNode(myName);
				    
				    var element1 = document.createElement('input');
				    var element2 = document.createElement('input');
				    var element3 = document.createElement('input');
					
				    col1.setAttribute('width','350px');
				    col2.setAttribute('width','100px');
				    col3.setAttribute('width','100px');
					
					element1.setAttribute('type', 'text');
					element1.setAttribute('style', 'width:190px');
				    element1.setAttribute('name', input1Name);
				   
				    element2.setAttribute('type', 'text');
					element2.setAttribute('style', 'width:70px; text-align:right');
				    element2.setAttribute('name', input2Name);
				    
				    element3.setAttribute('type', 'text');
					element3.setAttribute('style', 'width:70px; text-align:right');
				    element3.setAttribute('name', input3Name);
				    			    			    
				    mainTable.appendChild(row);
				    row.appendChild(col1);
				    row.appendChild(col2);
				    row.appendChild(col3);
				    
				    newSpan.appendChild(newName);
				    col1.appendChild(newSpan);
				    col1.appendChild(element1);
				    
				    col2.appendChild(element2);
				    
				    col3.appendChild(element3);
				}
				
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
					var br2 = document.createElement('br');
					var br3 = document.createElement('br');
					hfDiv.insertBefore(br, hfDiv.firstChild);
					hfDiv.insertBefore(br2, hfDiv.firstChild);
					hfDiv.insertBefore(br3, hfDiv.firstChild);
					
					tableAddMed = document.getElementsByClassName('addMed');
				    for (var i = 0; i < tableAddMed.length; i++) {
				        tableAddMed[i].style.display=\"none\";
				    }
														
				}
				
				function submitForm()
				{
					var formObject = document.forms['a4form'];
		
					if(formObject!=0)
					{
						formObject.submit();
					}
				}
			</script>
		</head>
		<body>
			<div id='container'>
				<div id='header'>
					<h4 class='shadow'><span class='indent10'>Annex A4</span></h4><br />					
					<h1>PHILIPPINE HEALTH INSURANCE CORPORATION</h1>
					<h2>QUARTERLY REPORT FORM</h2>
					<div class='width890 right Show'>
						<button type='button' title='Printer Friendly Format' onclick='hideDiv();'>
								<img src='../styles/images/printer.png' alt='Printer Friendly Format' style='width: 30px; height: 30px;'>
						</button>
						<a type='button' title='Download XML File' download href='../xml/A4.xml' style='cursor:default'>
							<img src='../styles/images/xml.png' alt='Download XML File' style='width: 30px; height: 30px;'>
						</a>
					</div>
				</div>
				<div id='body'>
					<div class='width750 center Show'>
						<form name='a4form' method='POST'>
						<br /><hr />";
						
						$function->fromDateToDate(); //Load Month and Year Selection
					
						if (isset($_REQUEST['submitDate']) && $_REQUEST['submitDate']=='Add New Form')
						{
							$sdate1 = strftime("%m/%d/%Y",mktime(0,0,0,$_POST['startMonth'],1,$_POST['year']));
							$edate1 = strftime("%m/%d/%Y",mktime(0,0,0,($_POST['endMonth']+1),0,$_POST['year']));
							
							$newSDate = date("Y-m-d", strtotime($sdate1));
							$newEDate = date("Y-m-d", strtotime($edate1));
							
							$PCB_Provider = $_SESSION['datanode']['name'];
							$PCB_ProviderNumber = $dbase->_getPCBProvider('regnumber');
							$municipality = $_SESSION['lgu'];
							$province = $_SESSION['province'];
							
							if(isset($_SESSION['lgu']) && isset($_SESSION['province']))
							{
								$location = $municipality .", ". $province;
							}
							
							if ($newSDate > $newEDate)
							{
								echo "<script>alert('Start Month is Greater Than End Month')</script>";
							}
																			
							$arrValue = array($dbase->_countPPServices('BREASTFEED', 'DEPENDENT', $newSDate, $newEDate),
												$dbase->_countPPServices('BREASTFEED', 'MEMBER', $newSDate, $newEDate),
												$dbase->_countPPServices('LIFEST', 'DEPENDENT', $newSDate, $newEDate),
												$dbase->_countPPServices('LIFEST', 'MEMBER', $newSDate, $newEDate),
												$dbase->_countPPServices('SMOKEC', 'DEPENDENT', $newSDate, $newEDate),
												$dbase->_countPPServices('SMOKEC', 'MEMBER', $newSDate, $newEDate),
												$dbase->_countPPServices('BODYM', 'DEPENDENT', $newSDate, $newEDate),
												$dbase->_countPPServices('BODYM', 'MEMBER', $newSDate, $newEDate),
												$dbase->_countPPServices('RECTAL', 'DEPENDENT', $newSDate, $newEDate),
												$dbase->_countPPServices('RECTAL', 'MEMBER', $newSDate, $newEDate),
												$dbase->_countDiagExam('CBC', 'DEPENDENT', $newSDate, $newEDate),
												$_POST['de1memReferred'],
												$dbase->_countDiagExam('CBC', 'MEMBER', $newSDate, $newEDate),
												$_POST['de1depReferred'],
												$dbase->_countDiagExam('FEC', 'DEPENDENT', $newSDate, $newEDate),
												$_POST['de3memReferred'],
												$dbase->_countDiagExam('FEC', 'MEMBER', $newSDate, $newEDate),
												$_POST['de3depReferred'],
												$dbase->_countDiagExam('SPT', 'DEPENDENT', $newSDate, $newEDate),
												$_POST['de4memReferred'],
												$dbase->_countDiagExam('SPT', 'MEMBER', $newSDate, $newEDate),
												$_POST['de4depReferred'],
												$_POST['de5depGiven'],
												$_POST['de5memReferred'],
												$_POST['de5memGiven'],
												$_POST['de5depReferred'],
												$_POST['de6depGiven'],
												$_POST['de6memReferred'],
												$_POST['de6memGiven'],
												$_POST['de6depReferred'],
												$dbase->_countDiagExam('CXR', 'DEPENDENT', $newSDate, $newEDate),
												$_POST['de7memReferred'],
												$dbase->_countDiagExam('CXR', 'MEMBER', $newSDate, $newEDate),
												$_POST['de7depReferred'],
												$dbase->_countTotalMembers('M', $newSDate, $newEDate),
												$dbase->_countTotalMembers('F', $newSDate, $newEDate),
												$dbase->_countConsultation('DEPENDENT', $newSDate, $newEDate),
												$dbase->_countConsultation('MEMBER', $newSDate, $newEDate),
												$dbase->_countDiagExam('URN', 'DEPENDENT', $newSDate, $newEDate),
												$_POST['de2memReferred'],
												$dbase->_countDiagExam('URN', 'MEMBER', $newSDate, $newEDate),
												$_POST['de2depReferred'],
												$dbase->_countTotalDependents('M', $newSDate, $newEDate),
												$dbase->_countTotalDependents('F', $newSDate, $newEDate),
												$dbase->_countPPServices('BREASTX', 'DEPENDENT', $newSDate, $newEDate),
												$dbase->_countPPServices('BREASTX', 'MEMBER', $newSDate, $newEDate),
												$dbase->_countPPServices('ACETIC', 'DEPENDENT', $newSDate, $newEDate),
												$dbase->_countPPServices('ACETIC', 'MEMBER', $newSDate, $newEDate),
												$_POST['hyperTarget'],
												$dbase->_countPPServices('BPMEAS', 'DEPENDENT', $newSDate, $newEDate),
												$dbase->_countPPServices('BPMEAS', 'MEMBER', $newSDate, $newEDate),
												$_POST['pcbeTarget'],
												$_POST['viwacTarget'],
												$_POST['nonhyperTarget'],
												$dbase->_countPPServices('ACETIC','',$newSDate,$newEDate),
												$dbase->_countPPServices('BREASTX','',$newSDate,$newEDate),
												$dbase->_countBloodPressure('Normal',$newSDate,$newEDate)==null ? 0 :$dbase->_countBloodPressure('Normal',$newSDate,$newEDate),
												$dbase->_countBloodPressure('Hypertension',$newSDate,$newEDate)==null ? 0 : $dbase->_countBloodPressure('Hypertension',$newSDate,$newEDate));

							//print_r($arrValue);
							unlink('../xml/A4.xml');
							_generateXML('A4', 'm_consult_philhealth_a4', $arrValue);
						}
						else 
						{
							$PCB_Provider = '';
							$location = ' ';
							$newSDate = ' ';
							$newEDate = ' ';
						}
																	
						
				echo "<br /> 
					<input type='submit' name='submitDate' value='Add New Form'><hr />
						<br />
					</div>
					<div id='facility' class='width750 center'>
						<h4 class='center'>Name of PCB Provider<br />
						<input style='text-align:center;' type='text' size=25 name='namePCBP' value='$PCB_Provider'></h4>
						<br />
					</div>
				
					<div class='width750'>
						<hr /><h4 class='center'>HEALTH FACILITY DATA<BR /> SUMMARY OF BENEFITS AVAILMENT (Members and Dependents)</h4><hr />
						<br />
					</div>
				
					<div class='width750'>
						<h4>I. Covered Period</h4>
						<p class='indent'><span class='width70'><label>From: </label></span><input style='width:100px' type='text' name='cpfrom' placeholder='(mm/dd/yyyy)' value=".($newSDate > $newEDate ? '' : $sdate1)."><br />
						<span class='width70'><label>To: </label></span><input style='width:100px' type='text' name='cpto' placeholder='(mm/dd/yyyy)' value=".($newSDate > $newEDate ? '' : $edate1)."></p><br /><br />
					</div>
				
					<div class='width750'>
						<h4>II. PCB Participation No.
						<input type='text' name='pcbnum' value=".$PCB_ProviderNumber."></h4><br /><br />
					</div>
				
					<div class='width750'>
						<h4>III. Municipality/City/Province
						<textarea style='vertical-align: top; font:normal 100% sans-serif;' name='muncitpro' rows='2' cols='50'>$location</textarea></h4><br /><br />
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
								<td class='center'><input style='text-align:right' type='text' name='hyperTarget' size=8 value=0></td>
								<td class='center'><input style='text-align:right' type='text' name='hyperAccomplishment' size=8 value=".($dbase->_countBloodPressure('Hypertension',$newSDate,$newEDate)==null ? 0 : $dbase->_countBloodPressure('Hypertension',$newSDate,$newEDate))."></td>
							</tr>
							<tr>
								<td><span class='indent70'>Nonhypertensive</span></th>
								<td class='center'><input style='text-align:right' type='text' name='nonhyperTarget' size=8 value=0></td>
								<td class='center'><input style='text-align:right' type='text' name='nonhyperAccomplishment' size=8 value=".($dbase->_countBloodPressure('Normal',$newSDate,$newEDate)==null ? 0 :$dbase->_countBloodPressure('Normal',$newSDate,$newEDate))."></td>
							</tr>
							<tr>
								<td>2. Periodic clinical breast examination</th>
								<td class='center'><input style='text-align:right' type='text' name='pcbeTarget' size=8 value=0></td>
								<td class='center'><input style='text-align:right' type='text' name='pcbeAccomplishment' size=8 value=".$dbase->_countPPServices('BREASTX','',$newSDate,$newEDate)."></td>
							</tr>
							<tr>
								<td>3. Visual inspection with acetic acid</th>
								<td class='center'><input style='text-align:right' type='text' name='viwacTarget' size=8 value=0></td>
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
								<td class='center inputWidth'><input type='hidden' name='pp1memReferred' size=1 value=0></td>
								<td class='center inputWidth'><input type='hidden' name='pp1depReferred' size=1 value=0></td>
							</tr>
						
							<tr >
								<td><span class='indent'>2. Visual inspection with acetic acid</span></td>
								<td class='center inputWidth'><input type='text' name='pp2memGiven' size=1 value=".$dbase->_countPPServices('ACETIC', 'MEMBER', $newSDate, $newEDate)."></td>
								<td class='center inputWidth'><input type='text' name='pp2depGiven' size=1 value=".$dbase->_countPPServices('ACETIC', 'DEPENDENT', $newSDate, $newEDate)."></td>
								<td class='center inputWidth'><input type='hidden' name='pp2memReferred' size=1 value=0></td>
								<td class='center inputWidth'><input type='hidden' name='pp2depReferred' size=1 value=0></td>
							</tr>
						
							<tr>
								<td><span class='indent'>3. Regular BP measurements</span></td>
								<td class='center inputWidth'><input type='text' name='pp3memGiven' size=1 value=".$dbase->_countPPServices('BPMEAS', 'MEMBER', $newSDate, $newEDate)."></td>
								<td class='center inputWidth'><input type='text' name='pp3depGiven' size=1 value=".$dbase->_countPPServices('BPMEAS', 'DEPENDENT', $newSDate, $newEDate)."></td>
								<td class='center inputWidth'><input type='hidden' name='pp3memReferred' size=1 value=0></td>
								<td class='center inputWidth'><input type='hidden' name='pp3depReferred' size=1 value=0></td>
							</tr>
						
							<tr>
								<td><span class='indent'>4. Breastfeeding program education</span></td>
								<td class='center inputWidth'><input type='text' name='pp4memGiven' size=1 value=".$dbase->_countPPServices('BREASTFEED', 'MEMBER', $newSDate, $newEDate)."></td>
								<td class='center inputWidth'><input type='text' name='pp4depGiven' size=1 value=".$dbase->_countPPServices('BREASTFEED', 'DEPENDENT', $newSDate, $newEDate)."></td>
								<td class='center inputWidth'><input type='hidden' name='pp4memReferred' size=1 value=0></td>
								<td class='center inputWidth'><input type='hidden' name='pp4depReferred' size=1 value=0></td>
							</tr>
						
							<tr>
								<td><span class='indent'>5. Periodic clinical breast examinations</span></td>
								<td class='center inputWidth'><input type='text' name='pp5memGiven' size=1 value=".$dbase->_countPPServices('BREASTX', 'MEMBER', $newSDate, $newEDate)."></td>
								<td class='center inputWidth'><input type='text' name='pp5depGiven' size=1 value=".$dbase->_countPPServices('BREASTX', 'DEPENDENT', $newSDate, $newEDate)."></td>
								<td class='center inputWidth'><input type='hidden' name='pp5memReferred' size=1 value=0></td>
								<td class='center inputWidth'><input type='hidden' name='pp5depReferred' size=1 value=0></td>
							</tr>
						
							<tr>
								<td><span class='indent'>6. Counselling for lifestyle modification</span></td>
								<td class='center inputWidth'><input type='text' name='pp6memGiven' size=1 value=".$dbase->_countPPServices('LIFEST', 'MEMBER', $newSDate, $newEDate)."></td>
								<td class='center inputWidth'><input type='text' name='pp6depGiven' size=1 value=".$dbase->_countPPServices('LIFEST', 'DEPENDENT', $newSDate, $newEDate)."></td>
								<td class='center inputWidth'><input type='hidden' name='pp6memReferred' size=1 value=0></td>
								<td class='center inputWidth'><input type='hidden' name='pp6depReferred' size=1 value=0></td>
							</tr>
						
							<tr>
								<td><span class='indent'>7. Counselling for smoking cessation</span></td>
								<td class='center inputWidth'><input type='text' name='pp7memGiven' size=1 value=".$dbase->_countPPServices('SMOKEC', 'MEMBER', $newSDate, $newEDate)."></td>
								<td class='center inputWidth'><input type='text' name='pp7depGiven' size=1 value=".$dbase->_countPPServices('SMOKEC', 'DEPENDENT', $newSDate, $newEDate)."></td>
								<td class='center inputWidth'><input type='hidden' name='pp7memReferred' size=1 value=0></td>
								<td class='center inputWidth'><input type='hidden' name='pp7depReferred' size=1 value=0></td>
							</tr>
						
							<tr>
								<td><span class='indent'>8. Body measurements</span></td>
								<td class='center inputWidth'><input type='text' name='pp8memGiven' size=1 value=".$dbase->_countPPServices('BODYM', 'MEMBER', $newSDate, $newEDate)."></td>
								<td class='center inputWidth'><input type='text' name='pp8depGiven' size=1 value=".$dbase->_countPPServices('BODYM', 'DEPENDENT', $newSDate, $newEDate)."></td>
								<td class='center inputWidth'><input type='hidden' name='pp8memReferred' size=1 value=0></td>
								<td class='center inputWidth'><input type='hidden' name='pp8depReferred' size=1 value=0></td>
							</tr>
						
							<tr>
								<td><span class='indent'>9. Digital rectal examination</span></td>
								<td class='center inputWidth'><input type='text' name='pp9memGiven' size=1 value=".$dbase->_countPPServices('RECTAL', 'MEMBER', $newSDate, $newEDate)."></td>
								<td class='center inputWidth'><input type='text' name='pp9depGiven' size=1 value=".$dbase->_countPPServices('RECTAL', 'DEPENDENT', $newSDate, $newEDate)."></td>
								<td class='center inputWidth'><input type='hidden' name='pp9memReferred' size=1 value=0></td>
								<td class='center inputWidth'><input type='hidden' name='pp9depReferred' size=1 value=0></td>
							</tr>
						
							<tr>
								<td colspan='5'><h4>Diagnostics Examinations</h4></th>
							</tr>
						
							<tr>
								<td><span class='indent'>1. Complete blood count (CBC)</span></td>
								<td class='center inputWidth'><input type='text' name='de1memGiven' size=1 value=".$dbase->_countDiagExam('CBC', 'MEMBER', $newSDate, $newEDate)."></td>
								<td class='center inputWidth'><input type='text' name='de1depGiven' size=1 value=".$dbase->_countDiagExam('CBC', 'DEPENDENT', $newSDate, $newEDate)."></td>
								<td class='center inputWidth'><input type='text' name='de1memReferred' size=1 value=0></td>
								<td class='center inputWidth'><input type='text' name='de1depReferred' size=1 value=0></td>
							</tr>
						
							<tr>
								<td><span class='indent'>2. Urinalysis</span></td>
								<td class='center inputWidth'><input type='text' name='de2memGiven' size=1 value=".$dbase->_countDiagExam('URN', 'MEMBER', $newSDate, $newEDate)."></td>
								<td class='center inputWidth'><input type='text' name='de2depGiven' size=1 value=".$dbase->_countDiagExam('URN', 'DEPENDENT', $newSDate, $newEDate)."></td>
								<td class='center inputWidth'><input type='text' name='de2memReferred' size=1 value=0></td>
								<td class='center inputWidth'><input type='text' name='de2depReferred' size=1 value=0></td>
							</tr>
						
							<tr>
								<td><span class='indent'>3. Fecalysis</span></td>
								<td class='center inputWidth'><input type='text' name='de3memGiven' size=1 value=".$dbase->_countDiagExam('FEC', 'MEMBER', $newSDate, $newEDate)."></td>
								<td class='center inputWidth'><input type='text' name='de3depGiven' size=1 value=".$dbase->_countDiagExam('FEC', 'DEPENDENT', $newSDate, $newEDate)."></td>
								<td class='center inputWidth'><input type='text' name='de3memReferred' size=1 value=0></td>
								<td class='center inputWidth'><input type='text' name='de3depReferred' size=1 value=0></td>
							</tr>
						
							<tr>
								<td><span class='indent'>4. Sputum microscopy</span></td>
								<td class='center inputWidth'><input type='text' name='de4memGiven' size=1 value=".$dbase->_countDiagExam('SPT', 'MEMBER', $newSDate, $newEDate)."></td>
								<td class='center inputWidth'><input type='text' name='de4depGiven' size=1 value=".$dbase->_countDiagExam('SPT', 'DEPENDENT', $newSDate, $newEDate)."></td>
								<td class='center inputWidth'><input type='text' name='de4memReferred' size=1 value=0></td>
								<td class='center inputWidth'><input type='text' name='de4depReferred' size=1 value=0></td>
							</tr>
						
							<tr>
								<td><span class='indent'>5. Fasting blood sugar (FBS)</span></td>
								<td class='center inputWidth'><input type='text' name='de5memGiven' size=1 value=0></td>
								<td class='center inputWidth'><input type='text' name='de5depGiven' size=1 value=0></td>
								<td class='center inputWidth'><input type='text' name='de5memReferred' size=1 value=0></td>
								<td class='center inputWidth'><input type='text' name='de5depReferred' size=1 value=0></td>
							</tr>
						
							<tr>
								<td><span class='indent'>6. Lipid profile</span></td>
								<td class='center inputWidth'><input type='text' name='de6memGiven' size=1 value=0></td>
								<td class='center inputWidth'><input type='text' name='de6depGiven' size=1 value=0></td>
								<td class='center inputWidth'><input type='text' name='de6memReferred' size=1 value=0></td>
								<td class='center inputWidth'><input type='text' name='de6depReferred' size=1 value=0></td>
							</tr>
						
							<tr>
								<td><span class='indent'>7. Chest x-ray</span></td>
								<td class='center inputWidth'><input type='text' name='de7memGiven' size=1 value=".$dbase->_countDiagExam('CXR', 'MEMBER', $newSDate, $newEDate)."></td>
								<td class='center inputWidth'><input type='text' name='de7depGiven' size=1 value=".$dbase->_countDiagExam('CXR', 'DEPENDENT', $newSDate, $newEDate)."></td>
								<td class='center inputWidth'><input type='text' name='de7memReferred' size=1 value=0></td>
								<td class='center inputWidth'><input type='text' name='de7depReferred' size=1 value=0></td>
							</tr>
						</table>
						<br /><br />
					</div>
				
					<div class='width750'>
						<h4>VII. Medicines Given</h4>
						<br />
						<table>
							<tr>
								<th width='350px'>(Generic Name)</th>
								<th colspan='2' width='200px'>No. of Members/ Dependents</th>
							</tr>
						
							<tr>
								<td><h4>I. Asthma<h4></td>
								<th width='100px'>M</th>
								<th width='100px'>D</th>
							</tr>
						</table>";
				
					echo "<table id='asthmed'>";
						$dbase->_genericDrugs('Asthma',$newSDate, $newEDate,'asthmed');
					echo "</table>";
						
					/*echo "<table id='asthmed'>
							<tr>
								<th width='350px'><span>Med 1 </span><input style='width:190px' type='text' name='asthmed1'></th>
								<th width='100px'><input style='width:70px; text-align:right;' type='text' name='asthmed1Mem'></th>
								<th width='100px'><input style='width:70px; text-align:right;' type='text' name='asthmed1Dep'></th>
							</tr>
						</table>";*/
												
					echo "<br />
						<table class='addMed'>
							<tr>
								<th colspan=3><input type='button' value='Add Medicine' onclick='addTR(\"asthmed\");'></th>
							</tr>
						</table>
						<br />";							
						
						
					/*echo "<table id='dehydmed'>
							<tr>
								<td colspan='3'><h4>II. AGE with no or mild dehydration</h4></td>
							</tr>
						
							<tr>
								<th width='350px'><span>Med 1 </span><input style='width:190px' type='text' name='dehydmed1'></th>
								<th width='100px'><input style='width:70px; text-align:right;' type='text' name='dehydmed1Mem'></th>
								<th width='100px'><input style='width:70px; text-align:right;' type='text' name='dehydmed1Dep'></th>
							</tr>
						</table>";*/
					
					echo "<table id='dehydmed'>";
					echo "<tr>
								<td colspan='3'><h4>II. AGE with no or mild dehydration</h4></td>
						</tr>";
						$dbase->_genericDrugs('AGE',$newSDate, $newEDate,'dehydmed');
					echo "</table>";
					
					echo "<br />
						<table class='addMed'>
							<tr>
								<th colspan=3><input type='button' value='Add Medicine' onclick='addTR(\"dehydmed\");'></th>
							</tr>
						</table>
						<br />";
												
					/*echo "<table id='urtidmed'>
							<tr>
								<td colspan='3'><h4>III. URTI/Pneumonia (minimal & low risk)</h4></td>
							</tr>
						
							<tr>
								<th width='350px'><span>Med 1 </span><input style='width:190px' type='text' name='urtidmed1'></th>
								<th width='100px'><input style='width:70px; text-align:right;' type='text' name='urtidmed1Mem'></th>
								<th width='100px'><input style='width:70px; text-align:right;' type='text' name='urtidmed1Dep'></th>
							</tr>
						</table>";*/
					
					echo "<table id='urtimed'>";
					echo "<tr>
								<td colspan='3'><h4>III. URTI/Pneumonia (minimal & low risk)</h4></td>
						</tr>";
						$dbase->_genericDrugs('URTI',$newSDate, $newEDate,'urtimed');
					echo "</table>";
					
					echo "<br />
						<table class='addMed'>
							<tr>
								<th colspan=3><input type='button' value='Add Medicine' onclick='addTR(\"urtimed\");'></th>
							</tr>
						</table>
						<br />";
					
					/*echo "<table id='utidmed'>
							<tr>
								<td colspan='3'><h4>IV. UTI</h4></td>
							</tr>
						
							<tr>
								<th width='350px'><span>Med 1 </span><input style='width:190px' type='text' name='utidmed1'></th>
								<th width='100px'><input style='width:70px; text-align:right;' type='text' name='utidmed1Mem'></th>
								<th width='100px'><input style='width:70px; text-align:right;' type='text' name='utidmed1Dep'></th>
							</tr>
						</table>";*/
					
					echo "<table id='utimed'>";
					echo "<tr>
								<td colspan='3'><h4>IV. UTI</h4></td>
						</tr>";
						$dbase->_genericDrugs('UTI',$newSDate, $newEDate,'utimed');
					echo "</table>";
					
					echo "<br />
						<table class='addMed'>
							<tr>
								<th colspan=3><input type='button' value='Add Medicine' onclick='addTR(\"utimed\");'></th>
							</tr>
						</table>
						<br />";
						
					echo "<table id='nebdmed'>
							<tr>
								<td colspan='3'><h4>V. Nebulisation services</h4></td>
							</tr>
						
							<tr>
								<th width='350px'><span>Med 1 </span><input style='width:190px' type='text' name='nebdmed1'></th>
								<th width='100px'><input style='width:70px; text-align:right;' type='text' name='nebdmed1Mem'></th>
								<th width='100px'><input style='width:70px; text-align:right;' type='text' name='nebdmed1Dep'></th>
							</tr>
						</table>";
					
					echo "<br />
						<table class='addMed'>
							<tr>
								<th colspan=3><input type='button' value='Add Medicine' onclick='addTR(\"nebdmed\");'></th>
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
					
					</form>
				</div>
			</div>
		</body>
	</html>";
						/*if (isset($_POST['downloadPDF']))
						{
							$_REQUEST['hay']=1;
						}
						if (isset($_REQUEST['hay'])&& $_REQUEST['hay']==1)
						{
							$arrValue = array($_POST['pp4depGiven'],
												$_POST['pp4memGiven'],
												$_POST['pp6depGiven'],
												$_POST['pp6memGiven'],
												$_POST['pp7depGiven'],
												$_POST['pp7memGiven'],
												$_POST['pp8depGiven'],
												$_POST['pp8memGiven'],
												$_POST['pp9depGiven'],
												$_POST['pp9memGiven'],
												$_POST['de1depGiven'],
												$_POST['de1memReferred'],
												$_POST['de1memGiven'],
												$_POST['de1depReferred'],
												$_POST['de3depGiven'],
												$_POST['de3memReferred'],
												$_POST['de3memGiven'],
												$_POST['de3depReferred'],
												$_POST['de4depGiven'],
												$_POST['de4memReferred'],
												$_POST['de4memGiven'],
												$_POST['de4depReferred'],
												$_POST['de5depGiven'],
												$_POST['de5memReferred'],
												$_POST['de5memGiven'],
												$_POST['de5depReferred'],
												$_POST['de6depGiven'],
												$_POST['de6memReferred'],
												$_POST['de6memGiven'],
												$_POST['de6depReferred'],
												$_POST['de7depGiven'],
												$_POST['de7memReferred'],
												$_POST['de7memGiven'],
												$_POST['de7depReferred'],
												$_POST['memMale'],
												$_POST['memFemale'],
												$_POST['pp1depGiven'],
												$_POST['pp1memGiven'],
												$_POST['de2depGiven'],
												$_POST['de2memReferred'],
												$_POST['de2memGiven'],
												$_POST['de2depReferred'],
												$_POST['depMale'],
												$_POST['depFemale'],
												$_POST['pp5depGiven'],
												$_POST['pp5memGiven'],
												$_POST['pp2depGiven'],
												$_POST['pp2memGiven'],
												$_POST['hyperTarget'],
												$_POST['pp3depGiven'],
												$_POST['pp3memGiven'],
												$_POST['pcbeTarget'],
												$_POST['viwacTarget'],
												$_POST['nonhyperTarget'],
												$_POST['viwacAccomplishment'],
												$_POST['pcbeAccomplishment'],
												$_POST['nonhyperAccomplishment'],
												$_POST['hyperAccomplishment'],);
							print_r($arrValue);
						}*/
?>
