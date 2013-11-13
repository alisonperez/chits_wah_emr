<?php
	print "<html>
		<head>
			<title>A5 Form</title>
			<link rel='stylesheet' href='styles/style.css' type='text/css'  />
		</head>

		<body>

			<div id='container'>

				<div  id='header'>
					<h4 class='shadow'><span class='indent10'>Annex A5</span></h4>
					<h1>PHILIPPINE HEALTH INSURANCE CORPORATION</h1>
					<h2>PCB FORM 1A</h2>
					<h3>QUARTERLY SUMMARY OF PCB SERVICES PROVIDED</h3>
					<br />
				</div>

				<div id='body'>

					<form name='a5form' method='POST'>
						<div class='width750 center'>
							<form name='a1form' method='POST'>
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
						</div>
						
						<div class='width750'>
							<br /><h4 class='center'>Name of Health Care Facility<br />
							<input type='text' size=25 name='nameHCF'></h4><br />
						</div>

						<div class='width750'>

							<hr /><h4 class='center'>Personal Information</h4><hr />
							<br />
							<span class='width70'><label>Date:</label></span>
							<span class='width285'><input type='date' size=8 name='personalinfodate'>(mm/dd/yyyy)</span>

							<span class='width150'><label>Philhealth #:</label></span><input type='number name='pin'>

							<br /><span class='width70'><label>Name:</label></span>
							<span class='width285'><input type='text' name='patientname'></span>

							<span class='width150'><label>Age:</label></span><input type='text' size=4 name='age'>

							<br /><span class='width70'><label>Gender:</label></span>
							<span class='width285'><input type='radio' name='gender' value='Male'>Male
							<input type='radio' name='gender' value='Female'>Female</span>
							
							<span class='width150'><label>Membership:</label></span><input type='radio' name='membership' value='Member'>Member
							<input type='radio' name='membership' value='Dependent'>Dependent
						
						</div>

						<div class='width750'>
							<br />
							<hr /><h4 class='center'>Other Information</h4><hr />
							<br />
							<p class='center'><label>Diagnosis</label><br />
							<textarea style='vertical-align: top' name='diagnosis' rows='2' cols='50'></textarea></p>
						
							<br /><br />
							<h4>BENEFITS GIVEN</h4>(Number of Times Benefit Given)
							<br />
							<br /><p class='columns indent70'>
							<span class='width30'><input type='checkbox' name='benefits' value='Consultation'></span>Consultation

							<br /> 
							<span class='width30'><input type='checkbox' name='benefits' value='Visual Inspection with Acetic Acid'></span>Visual Inspection with Acetic Acid

							<br />
							<span class='width30'><input type='checkbox' name='benefits' value='Regular BP Measurement'></span>Regular BP Measurement

							<br /> 
							<span class='width30'><input type='checkbox' name='benefits' value='Breastfeeding Program Education'></span>Breastfeeding Program Education

							<br />
							<span class='width30'><input type='checkbox' name='benefits' value='Periodic Clinical Breast Examination'></span>Periodic Clinical Breast Examination

							<br /> 
							<span class='width30'><input type='checkbox' name='benefits' value='Counselling for Lifestyle Modification'></span>Counselling for Lifestyle Modification

							<br />
							<span class='width30'><input type='checkbox' name='benefits' value='Counselling for Smoking Cessation'></span>Counselling for Smoking Cessation

							<br /> 
							<span class='width30'><input type='checkbox' name='benefits' value='Body Measurements'></span>Body Measurements

							<br />
							<span class='width30'><input type='checkbox' name='benefits' value='Digital Rectal Exam'></span>Digital Rectal Exam

							<br /> 
							<span class='width30'><input type='checkbox' name='benefits' value='CBC'></span>CBC

							<br />
							<span class='width30'><input type='checkbox' name='benefits' value='Urinalysis'></span>Urinalysis

							<br /> 
							<span class='width30'><input type='checkbox' name='benefits' value='Fecalysis'></span>Fecalysis

							<br />
							<span class='width30'><input type='checkbox' name='benefits' value='Sputum Microscopy'></span>Sputum Microscopy

							<br /> 
							<span class='width30'><input type='checkbox' name='benefits' value='FBS'></span>FBS

							<br />
							<span class='width30'><input type='checkbox' name='benefits' value='Lipid Profile'></span>Lipid Profile

							<br /> 
							<span class='width30'><input type='checkbox' name='benefits' value='Chest X-Ray'></span>Chest X-Ray
							</p>

							<p class='center'>
							<br /><br /><label>Medicines Given</label><br />
							<textarea style='vertical-align: top' name='medicines' rows='2' cols='50'></textarea>
							<br /><br /><br /></p>

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
