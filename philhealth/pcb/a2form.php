<?php	
	print"<html>
		<head>
			<title>A2 Form</title>
			<link rel='stylesheet' href='styles/style.css' type='text/css'  />
		</head>

		<body>
			<div id='container'>
				<div id='header'>
					<h4 class='shadow'><span class='indent10'>Annex A2</span></h4><br />					
					<h1>PHILIPPINE HEALTH INSURANCE CORPORATION</h1>
					<h2>PCB PROVIDER CLIENTELE PROFILE</h2>
					<br />
				</div>
				<div id='body'>
					<div class='width750 center'>
						<form name='a2form' method='POST'>
						<br /><hr />
						<select name='frmdate'>
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
						</select>";
						
						//selection of month and year.
						$yearstart=2005;
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
						echo "</select>
						<input type='submit' value='Add New Form'><hr />
						<br />
						<h4 class='center'>Name of Health Care Facility<br />
						<input type='text' size=25 name='nameHCF'></h4>
						<br />
						</div>
						
					<div class='width750'>
						<hr /><h4 class='center'>I. PCB Provider Data</h4><hr />
						<br />
						<p class='indent200'><span class='width110'><label>Region: </label></span><input type='text' name='region'><br />
						<span class='width110'><label>Province: </label></span><input type='text' name='province'><br />
						<span class='width110'><label>Municipality: </label></span><input type='text' name='municipality'></p>
						<br /><br />
						<h4>No. of Assigned Families:</h4><br />
						<p class='columns2 indent90'>						
						<span class='width110'><label>SP-NHTS: </label></span><input type='text' name='nhts' size=5><br />
						<span class='width110'><label>SP-LGU: </label></span><input type='text' name='lgu' size=5><br />
						<span class='width110'><label>SP-NGA: </label></span><input type='text' name='nga' size=5><br />
						<span class='width110'><label>SP-Private: </label></span><input type='text' name='private' size=5><br />
						<span class='width110'><label>IPP-OG: </label></span><input type='text' name='og' size=5><br />
						<span class='width110'><label>IPP-OFW: </label></span><input type='text' name='ofw' size=5><br />
						</p>
						<br />
						<p class='center'><label>Non-PHIC Members: </label><input type='text' name='nonphic' size=5></p><br />
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
								<td><input type='text' name='asMale' size=4></td>
								<td><input type='text' name='asFemale' size=4></td>
								<td><input type='text' name='0total' size=4></td>
							</tr>
							<tr>
								<td>2-5 Years</td>
								<td><input type='text' name='asMale' size=4></td>
								<td><input type='text' name='asFemale' size=4></td>
								<td><input type='text' name='2total' size=4></td>
							</tr>
							<tr>
								<td>6-15 Years</td>
								<td><input type='text' name='asMale' size=4></td>
								<td><input type='text' name='asFemale' size=4></td>
								<td><input type='text' name='6total' size=4></td>
							</tr>
							<tr>
								<td>16-24 Years</td>
								<td><input type='text' name='asMale' size=4></td>
								<td><input type='text' name='asFemale' size=4></td>
								<td><input type='text' name='16total' size=4></td>
							</tr>
							<tr>
								<td>25-59 Years</td>
								<td><input type='text' name='asMale' size=4></td>
								<td><input type='text' name='asFemale' size=4></td>
								<td><input type='text' name='25total' size=4></td>
							</tr>
							<tr>
								<td>60 Years and Above</td>
								<td><input type='text' name='asMale' size=4></td>
								<td><input type='text' name='asFemale' size=4></td>
								<td><input type='text' name='60total' size=4></td>
							</tr>
							<tr>
								<td></td>
								<td colspan='3'><hr / ><hr / ></td>
								
							</tr>
							<tr>
								<th>TOTAL</th>
								<td><input type='text' name='maleTotal' size=4></td>
								<td><input type='text' name='femaleTotal' size=4></td>
								<td><input type='text' name='astotal' size=4></td>
							</tr>
						</table>
						<br />
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
								<td><input type='text' name='breastMember' size=4></td>
								<td><input type='text' name='breastDependent' size=4></td>
							</tr>
							<tr>
								<th>Cervical Cancer Screening<br />Female, 25 to 55 years old with intact uterus</th>
								<td><input type='text' name='cervicalMember' size=4></td>
								<td><input type='text' name='cervicalDependent' size=4></td>
							</tr>
						</table>
						<br />
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
								<td class='center inputWidth'><input type='text' name='symmemMale' size=2></td>
								<td class='center inputWidth'><input type='text' name='symmemFemale' size=2></td>
								<td class='center inputWidth'><input type='text' name='symdepMale' size=2></td>
								<td class='center inputWidth'><input type='text' name='symdepFemale' size=2></td>
								<td class='center inputWidth'><input type='text' name='symtotMale' size=2></td>
								<td class='center inputWidth'><input type='text' name='symtotFemale' size=2></td>
							</tr>
							<tr class='border'>
								<td><h4>Waist circumference</h4></td>
								<td colspan='6'></td>
							</tr>
							<tr class='border'>
								<td><span class='indent70'>&ge;80cm (female)</span></td>
								<td></td>
								<td class='center inputWidth'><input type='text' name='80memFemale' size=2></td>
								<td></td>
								<td class='center inputWidth'><input type='text' name='80depFemale' size=2></td>
								<td></td>
								<td class='center inputWidth'><input type='text' name='80totFemale' size=2></td>
							</tr>
							<tr class='border'>
								<td><span class='indent70'>&ge;90cm (male)</span></td>
								<td class='center inputWidth'><input type='text' name='90memMale' size=2></td>
								<td></td>
								<td class='center inputWidth'><input type='text' name='90depMale' size=2></td>
								<td></td>
								<td class='center inputWidth'><input type='text' name='90totMale' size=2></td>
								<td></td>
							</tr>
							<tr class='border'>
								<td><h4>History of diagnosis of diabetes</h4></td>
								<td class='center inputWidth'><input type='text' name='historymemMale' size=2></td>
								<td class='center inputWidth'><input type='text' name='historymemFemale' size=2></td>
								<td class='center inputWidth'><input type='text' name='historydepMale' size=2></td>
								<td class='center inputWidth'><input type='text' name='historydepFemale' size=2></td>
								<td class='center inputWidth'><input type='text' name='historytotMale' size=2></td>
								<td class='center inputWidth'><input type='text' name='historytotFemale' size=2></td>
							</tr>
							<tr class='border'>
								<td><h4>Intake of oral hypoglycemic agents</h4></td>
								<td class='center inputWidth'><input type='text' name='intakememMale' size=2></td>
								<td class='center inputWidth'><input type='text' name='intakememFemale' size=2></td>
								<td class='center inputWidth'><input type='text' name='intakedepMale' size=2></td>
								<td class='center inputWidth'><input type='text' name='intakedepFemale' size=2></td>
								<td class='center inputWidth'><input type='text' name='intaketotMale' size=2></td>
								<td class='center inputWidth'><input type='text' name='intaketotFemale' size=2></td>
							</tr>
						</table>
						<br />
					</div>

					<div class='width750'>
						<hr /><h4 class='center'>V. Hypertension</h4><hr />
						<br />
						<table class='width700'>
							<tr class='border'>
								<th rowspan='4' width='200px'>Cases</th>
								<th colspan='7' width='500px'># of Members and Dependents</th>
							</tr>
							<tr class='border'>
								<th colspan='3' width='210px'>Members</th>
								<th colspan='3' width='210px'>Dependents</th>
								<th rowspan='3' width='80px'>Total</th>
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
								<td class='center inputWidth'><input type='text' name='c1memMale' size=4></td>
								<td class='center inputWidth'><input type='text' name='c1memnpFemale' size=4></td>
								<td class='center inputWidth'><input type='text' name='c1mempFemale' size=4></td>
								<td class='center inputWidth'><input type='text' name='c1depMale' size=4></td>
								<td class='center inputWidth'><input type='text' name='c1depnpFemale' size=4></td>
								<td class='center inputWidth'><input type='text' name='c1deppFemale' size=4></td>
								<td class='center inputWidth'><input type='text' name='c1Total' size=4></td>
							</tr>
							<tr class='border'>
								<td><h4>Adult with<br />BP >/= 140/90 mmHg but less than 180/120 mmHg</h4></td>
								<td class='center inputWidth'><input type='text' name='c2memMale' size=4></td>
								<td class='center inputWidth'><input type='text' name='c2memnpFemale' size=4></td>
								<td class='center inputWidth'><input type='text' name='c2mempFemale' size=4></td>
								<td class='center inputWidth'><input type='text' name='c2depMale' size=4></td>
								<td class='center inputWidth'><input type='text' name='c2depnpFemale' size=4></td>
								<td class='center inputWidth'><input type='text' name='c2deppFemale' size=4></td>
								<td class='center inputWidth'><input type='text' name='c2Total' size=4></td>
							</tr>
							<tr class='border'>
								<td><h4>Adult with<br />BP > 180/120 mmHg</h4></td>
								<td class='center inputWidth'><input type='text' name='c3memMale' size=4></td>
								<td class='center inputWidth'><input type='text' name='c3memnpFemale' size=4></td>
								<td class='center inputWidth'><input type='text' name='c3mempFemale' size=4></td>
								<td class='center inputWidth'><input type='text' name='c3depMale' size=4></td>
								<td class='center inputWidth'><input type='text' name='c3depnpFemale' size=4></td>
								<td class='center inputWidth'><input type='text' name='c3deppFemale' size=4></td>
								<td class='center inputWidth'><input type='text' name='c3sTotal' size=4></td>
							</tr>
							<tr class='border'>
								<td><h4>History of diagnosis of hypertension</h4></td>
								<td class='center inputWidth'><input type='text' name='c4memMale' size=4></td>
								<td class='center inputWidth'><input type='text' name='c4memnpFemale' size=4></td>
								<td class='center inputWidth'><input type='text' name='c4mempFemale' size=4></td>
								<td class='center inputWidth'><input type='text' name='c4depMale' size=4></td>
								<td class='center inputWidth'><input type='text' name='c4depnpFemale' size=4></td>
								<td class='center inputWidth'><input type='text' name='c4deppFemale' size=4></td>
								<td class='center inputWidth'><input type='text' name='c4sTotal' size=4></td>
							</tr> 
							<tr class='border'>
								<td><h4>Intake of hypertension medicine</h4></td>
								<td class='center inputWidth'><input type='text' name='c5memMale' size=4></td>
								<td class='center inputWidth'><input type='text' name='c5memnpFemale' size=4></td>
								<td class='center inputWidth'><input type='text' name='c5mempFemale' size=4></td>
								<td class='center inputWidth'><input type='text' name='c5depMale' size=4></td>
								<td class='center inputWidth'><input type='text' name='c5depnpFemale' size=4></td>
								<td class='center inputWidth'><input type='text' name='c5deppFemale' size=4></td>
								<td class='center inputWidth'><input type='text' name='c5ssTotal' size=4></td>
							</tr>
						</table>
						<br />
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

