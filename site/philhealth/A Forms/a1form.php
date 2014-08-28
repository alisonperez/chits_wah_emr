<?php
	echo "<html>
		<head>
			<title>A1 Form</title>
			<link rel='stylesheet' href='../styles/style.css' type='text/css'  />
		</head>
		<body>
			<div id='container'>
				<div id='header'>
					<h4 class='shadow'><span class='indent10'>Annex A1</span></h4><br />					
					<h1>PHILIPPINE HEALTH INSURANCE CORPORATION</h1>
					<br /><br />
				</div>
				<div id='body'>
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
						<br />
						<h4 class='center'>Name of PCB Provider<br />
						<input type='text' size =25 name='nameHCF'></h4>
						<br />
					</div>
					<div class='width750'>
						<hr /><h4 class='center'>INDIVIDUAL HEALTH PROFILE</h4><hr />
						<br />
						<label>PIN: </label><input type='text' name='pin'></span>
						<br /><br />
						<label>Patient Name:</label>
						<p class='columns5 indent'>
						<span class='width85'>Last Name: </span><input type='text' name='lname'><br />
						<span class='width85'>First Name: </span><input type='text' name='fname'><br />
						<span class='width90'>Middle Name: </span><input type='text' name='mname'><br />
						<span class='width90'>Extension: </span><input type='text' name='exname'>(Sr., Jr., etc.)<br />
						</p>
						<br />
						<label>Address:</label>
						<textarea style='vertical-align: top' name='address' rows='2' cols='40'></textarea>
						<br /><br />
					</div>
					
					<div class='width750'>
						<label>Age:</label><br />
						<p class='columns4 indent'>
						<input type='radio' name='1year' value='0-1'>0-1 Year<br />
						<input type='radio' name='5year' value='2-5'>2-5 Year<br />
						<input type='radio' name='15year' value='6-15'>6-15 Year<br />
						<input type='radio' name='25year' value='25-59'>25-59 Year<br />
						<input type='radio' name='60year' value='60'>60 Years and Above<br />
						</p>
						<br />
						<span class='width70'><label>Birthdate:</label></span>
						<input type='date' size=8 name='personalinfodate'>(mm/dd/yyyy)<br /><br />
						
						<span class='width70'><label>Sex:</label></span>
						<select name='gender'><option value='Male'>Male</option><option value='Female'>Female</option></select><br /><br />
						
						<span class='width70'><label>Religion:</label></span>
						<input type='text' name='religion'>
						
						<br /><br />
						
						<label>Civil Status:</label><br />
						<p class='columns6 indent'>
						<input type='radio' name='civilstatus' value='Single'>Single<br />
						<input type='radio' name='civilstatus' value='Married'>Married<br />
						<input type='radio' name='civilstatus' value='Annuled'>Annuled<br />
						<input type='radio' name='civilstatus' value='Widowed'>Widowed<br />
						<input type='radio' name='civilstatus' value='Separated'>Separated<br /><br />
						<input type='radio' name='civilstatus' value='Others'>Others, specify<input type='text' name='cvlstatus'><br />
						</p>
						<br /><br />
						
						<label>Highest Completed Educational Attainment:</label><br />
						<p class='columns6 indent'>
						<input type='radio' name='hceAttainment' value='College degree, Post Graduate'>College degree, Post Graduate<br />
						<input type='radio' name='hceAttainment' value='High School'>High School<br />
						<input type='radio' name='hceAttainment' value='Elementary'>Elementary<br />
						<input type='radio' name='hceAttainment' value='Vocational'>Vocational<br />
						<input type='radio' name='hceAttainment' value='No Schooling'>No Schooling<br />
						</p>
						<br />
						<label>Occupation:</label>
						<input type='text' name='occupation'><br /><br />
					</div>
					
					<div class='width750'>
						<hr /><h4 class='center'>Membership Information:</h4><hr />
						
						<br /><span class='width200'><label>PHIC Membership</label></span>
						<input type='radio' name='membership' value='Member'><span class='width100'>Member</span>
						<input type='radio' name='membership' value='Dependent'><span class='width100'>Dependent</span>
						<input type='radio' name='membership' value='Non Member'>Non Member
						<br /><span class='width200'><label>Sponsor</label></span>
						<input type='radio' name='sponsor' value='NHTS'><span class='width100'>NHTS</span> 
						<input type='radio' name='sponsor' value='NGA'><span class='width100'>NGA</span>
						<input type='radio' name='sponsor' value='LGU'><span class='width100'>LGU</span>
						<input type='radio' name='sponsor' value='PRIVATE'>PRIVATE
						<br /><span class='width200'><label>IPP</label></span>
						<input type='radio' name='ipp' value='OG'><span class='width100'>OG</span> 
						<input type='radio' name='ipp' value='OFW'><span class='width100'>OFW</span>
						<input type='radio' name='ipp' value='Voluntary'>Voluntary/Self-employed
						<br /><span class='width200'><label>Employment</label></span>
						<input type='radio' name='employment' value='Government'><span class='width100'>Government</span>
						<input type='radio' name='employment' value='Private'>Private
						<br /><span class='width200'><label>Lifetime</label></span>
						<input type='radio' name='lifetime' value='Lifetime'>Lifetime
						<br /><br />
					</div>
					
					<div class='width750'>
						<hr /><h4 class='center'>Past Medical History:</h4><hr />
						<br /><p class='columns3'>
						<input type='checkbox' name='pmhistory' value='Alergy'>Alergy, specify: <input type='text' name='pmhalergy'><br />
						<input type='checkbox' name='pmhistory' value='Asthma'>Asthma<br />
						<input type='checkbox' name='pmhistory' value='Cancer'>Cancer, specify organ: <input type='text' name='pmhcancer'><br />
						<input type='checkbox' name='pmhistory' value='Cerebrovascular Disease'>Cerebrovascular Disease<br />
						<input type='checkbox' name='pmhistory' value='Coronary Artery Disease'>Coronary Artery Disease<br />
						<input type='checkbox' name='pmhistory' value='Diabetes Mellitus'>Diabetes Mellitus<br />
						<input type='checkbox' name='pmhistory' value='Emphysema'>Emphysema<br />
						<input type='checkbox' name='pmhistory' value='Epilepsy/Seizure Disorder'>Epilepsy/Seizure Disorder<br />
						<input type='checkbox' name='pmhistory' value='Hepatitis'>Hepatitis, specify type: <input type='text' name='pmhhepatitis'><br />
						<input type='checkbox' name='pmhistory' value='Hyperlipidemia'>Hyperlipidemia<br />
						<input type='checkbox' name='pmhistory' value='Hypertension'>Hypertension, highest BP: <input type='text' name='pmhbloodpressure'><br />
						<input type='checkbox' name='pmhistory' value='Peptic Ulcer Disease'>Peptic Ulcer Disease<br />
						<input type='checkbox' name='pmhistory' value='Pneumonia'>Pneumonia<br />
						<input type='checkbox' name='pmhistory' value='Thyroid Disease'>Thyroid Disease<br />
						<input type='checkbox' name='pmhistory' value='Tuberculosis'>Tuberculosis, specify organ: <input type='text' name='pmhtuberculosis'><br />
						<span class='indent21'>If PTB, what category? <input type='text' name='ptbCategory'></span><br />
						<input type='checkbox' name='pmhistory' value='Urinary Tract Infection'>Urinary Tract Infection<br />
						<input type='checkbox' name='pmhistory' value='Others'>Others: <input type='text' name='pmhOthers'><br />
						</p><br />
					</div>
					
					<div class='width750'>
						<hr /><h4 class='center'>Past Surgical History:</h4><hr /><br />
						<span class='width450'>Operation: <input type='text' size=50 name='pshOp1'></span> Date: <input type='text' name='pshOp1Date'><br />
						<span class='width450'>Operation: <input type='text' size=50 name='pshOp2'></span> Date: <input type='text' name='pshOp2Date'><br />
						<br />
					</div>
					
					<div class='width750'>
						<hr /><h4 class='center'>Family History:</h4><hr />
						<br /><p class='columns3'>
						<input type='checkbox' name='famhistory' value='Alergy'>Alergy, specify: <input type='text' name='fhalergy'><br />
						<input type='checkbox' name='famhistory' value='Asthma'>Asthma<br />
						<input type='checkbox' name='famhistory' value='Cancer'>Cancer, specify organ: <input type='text' name='fhcancer'><br />
						<input type='checkbox' name='famhistory' value='Cerebrovascular Disease'>Cerebrovascular Disease<br />
						<input type='checkbox' name='famhistory' value='Coronary Artery Disease'>Coronary Artery Disease<br />
						<input type='checkbox' name='famhistory' value='Diabetes Mellitus'>Diabetes Mellitus<br />
						<input type='checkbox' name='famhistory' value='Emphysema'>Emphysema<br />
						<input type='checkbox' name='famhistory' value='Epilepsy/Seizure Disorder'>Epilepsy/Seizure Disorder<br />
						<input type='checkbox' name='famhistory' value='Hepatitis'>Hepatitis, specify type: <input type='text' name='fhhepatitis'><br />
						<input type='checkbox' name='famhistory' value='Hyperlipidemia'>Hyperlipidemia<br />
						<input type='checkbox' name='famhistory' value='Hypertension'>Hypertension<br />
						<input type='checkbox' name='famhistory' value='Peptic Ulcer Disease'>Peptic Ulcer Disease<br />
						<input type='checkbox' name='famhistory' value='Thyroid Disease'>Thyroid Disease<br />
						<input type='checkbox' name='famhistory' value='Tuberculosis'>Tuberculosis, specify organ: <input type='text' name='fhtuberculosis'><br />
						<span class='indent21'>If PTB, what category? <input type='text' name='ptbCategory'></span><br />
						<input type='checkbox' name='famhistory' value='Others'>Others: <input type='text' name='fhOthers'><br />
						</p><br />
					</div>
					
					<div class='width750'>
						<hr /><h4 class='center'>Personal/Social History:</h4><hr />
						<br />
						<span class='width100'><label>Smoking: </label></span>
						<span class='width70'><input type='radio' name='smoking' value='Yes'>Yes</span>
						<span class='width70'><input type='radio' name='smoking' value='No'>No</span>
						<input type='radio' name='smoking' value='Quit'><span class='width150'>Quit</span>
						<span class='width125'>No. of pack/year?</span><input type='text' name='cigarpack'>
						<br />
						<span class='width100'><label>Alcohol: </label></span>
						<span class='width70'><input type='radio' name='alcohol' value='Yes'>Yes</span>
						<span class='width70'><input type='radio' name='alcohol' value='No'>No</span>
						<input type='radio' name='alcohol' value='Quit'><span class='width150'>Quit</span>
						<span class='width125'>No. of bottles/day?</span><input type='text' name='alcoholbottle'>
						<br />
						<span class='width100'><label>Illicit drugs: </label></span>
						<span class='width70'><input type='radio' name='drugs' value='Yes'>Yes</span>
						<span class='width70'><input type='radio' name='drugs' value='No'>No</span>
						<input type='radio' name='drugs' value='Quit'>Quit
						<br /><br />
					</div>
					
					<div class='width750'>
						<hr /><h4 class='center'>Immunizations:</h4><hr />
						<br />
						<label>For Children:</label><br />
						<p class='columns4 indent'>
						<input type='checkbox' name='imchild' value='BCG'>BCG
						<br /><input type='checkbox' name='imchild' value='OPV1'>OPV1
						<br /><input type='checkbox' name='imchild' value='OPV2'>OPV2
						<br /><input type='checkbox' name='imchild' value='OPV3'>OPV3
						<br /><input type='checkbox' name='imchild' value='DPT1'>DPT1
						<br /><input type='checkbox' name='imchild' value='DPT2'>DPT2
						<br /><input type='checkbox' name='imchild' value='DPT3'>DPT3
						<br /><input type='checkbox' name='imchild' value='Measles'>Measles
						<br /><input type='checkbox' name='imchild' value='Hepatitis B1'>Hepatitis B1
						<br /><input type='checkbox' name='imchild' value='Hepatitis B2'>Hepatitis B2
						<br /><input type='checkbox' name='imchild' value='Hepatitis B3'>Hepatitis B3
						<br /><input type='checkbox' name='imchild' value='Hepatitis A'>Hepatitis A
						<br /><input type='checkbox' name='imchild' value='Varicella'>Varicella (Chicken Pox)
						</p>
						
						<br />
						<label>For young women:</label>
						<input type='checkbox' name='imywomen' value='HPV'>HPV
						<input type='checkbox' name='imywomen' value='MMR'>MMR
						
						<br /><br />
						<label>For pregnant women:</label>
						<input type='checkbox' name='impwomen' value='Tetanus toxoid'>Tetanus toxoid
						
						<br /><br />
						<label>For elderly and immunocompromised:</label>
						<input type='checkbox' name='imelderly' value='Pnuemococcal vaccine'>Pnuemococcal vaccine
						<input type='checkbox' name='imyelderly' value='Flu vaccine'>Flu vaccine
						
						<br /><br />
						<label>Others: Specify</label>
						<input type='text' name='imothers'>
						<br /><br />
					</div>
					
					<div class='width750'>
						<hr /><h4 class='center'>Menstrual History:</h4><hr />
						<br />
						<p class='columns3'>
						Menarche: <input type='text' name='mhmenarche'><br />
						Last Menstrual Period: <input type='text' name='mplastmp'><br />
						Period Duration: <input type='text' name='mppduration'><br />
						Interval/Cycle: <input type='text' name='mpinterval'><br />
						No. of pads/day during menstruation: <input type='text' size=7 name='mpnopadsdm'><br />
						Onset of sexual intercourse: <input type='text' name='mpsexinter'><br />
						Birth control method: <input type='text' name='mpbirthcontrol'><br />
						Menopause: <input type='radio' name='mpmenopause' value='Yes'>Yes
						<input type='radio' name='mpmenopause' value='No'>No<br />
						If yes, at what age?: <input type='text' size=7 name='mpmenopauseage'><br />
						</p><br />
					</div>
					
					<div class='width750'>
						<hr /><h4 class='center'>Pregnancy History:</h4><hr />
						<br />
						<p class='columns3'>
						Gravity (No. of Pregnancy): <input type='text' name='phpregnancygravity'><br />
						No. of Full Term: <input type='text' name='phfullterm'><br />
						No. of Premature: <input type='text' name='phpremature'><br />
						No. of Abortion: <input type='text' name='phabortion'><br />
						No. of Living Children: <input type='text' name='phlivingchildren'><br />
						Parity (No. of Delivery): <input type='text' name='phdeliveryparity'><br />
						Type of Delivery: <input type='text' name='phdeliverytype'><br />
						</p>
						<br />
						<input type='checkbox' name='pheclampsia' value='Pregnancy-induced hypertension'>Pregnancy-induced hypertension (Pre-eclampsia)
						<br /><br />
					</div>
					
					<div class='width750'>
						<hr /><p class='center'><b>Access to Family Planning Counseling:</b>
						<input type='radio' name='fpcounseling' value='Yes'>Yes
						<input type='radio' name='fpcounseling' value='No'>No</p>
						<hr /><br />
					</div>
					
					<div class='width750'>
						<hr /><h4 class='center'>Pertinent Physical Examination Findings:</h4><hr />
						<br />
						<p class='columns5 indent70'>
						BP: <input type='text' name='pebp'><br />
						HR: <input type='text' name='pebp'><br />
						RR: <input type='text' name='pebp'><br />
						Height: <input type='text' name='pebp'>(cm)<br />
						Weight: <input type='text' name='pebp'>(kg)<br />
						Waist circumference: <input type='text' name='pebp'>(cm)<br />
						</p><br /><br />
						
						<label>Skin:</label>
						<p class='columns4 indent'>
						<input type='checkbox' name='skin' value='Pallor'>Pallor<br />
						<input type='checkbox' name='skin' value='Rashes'>Rashes<br />
						<input type='checkbox' name='skin' value='Jaundice'>Jaundice<br />
						<input type='checkbox' name='skin' value='Good skin turgor'>Good skin turgor<br />
						</p>
						<br />
						<p class='center'><label>Remarks: </label><textarea style='vertical-align: top' name='skinremarks' rows=2 cols='60'></textarea></p>
						<br /><br />
						
						<label>HEENT:</label>
						<p class='columns3 indent'>
						<input type='checkbox' name='heent' value='Anicteric Sclerae'>Anicteric Sclerae<br />
						<input type='checkbox' name='heent' value='Pupils Briskly Reactive To Light'>Pupils Briskly Reactive To Light<br />
						<input type='checkbox' name='heent' value='Aural Discharge'>Aural Discharge<br />
						<input type='checkbox' name='heent' value='Intact Tympanic Membrane'>Intact Tympanic Membrane<br />
						<input type='checkbox' name='heent' value='Alar Flaring'>Alar Flaring<br />
						<input type='checkbox' name='heent' value='Nasal Discharge'>Nasal Discharge<br />
						<input type='checkbox' name='heent' value='Tonsillopharyngeal Congestion'>Tonsillopharyngeal Congestion<br />
						<input type='checkbox' name='heent' value='Hypertrophic Tonsils'>Hypertrophic Tonsils<br />
						<input type='checkbox' name='heent' value='Palpable Mass'>Palpable Mass<br />
						<input type='checkbox' name='heent' value='Exudates'>Exudates<br />
						</p><br />
						<p class='center'><label>Remarks: </label><textarea style='vertical-align: top' name='heentremarks' rows=2 cols='60'></textarea></p>
						<br /><br />
						
						<label>Chest/Lungs:</label>
						<p class='columns6 indent'>
						<input type='checkbox' name='chest' value='Symmetrical Chest Expansion'>Symmetrical Chest Expansion<br />
						<input type='checkbox' name='chest' value='Clear Breathsounds'>Clear Breathsounds<br />
						<input type='checkbox' name='chest' value='Reactions'>Reactions<br />
						<input type='checkbox' name='chest' value='Crackles/Rales'>Crackles/Rales<br />
						<input type='checkbox' name='chest' value='Wheezes'>Wheezes<br />
						</p><br />
						<p class='center'><label>Remarks: </label><textarea style='vertical-align: top' name='chestremarks' rows=2 cols='60'></textarea></p>
						<br /><br />
						
						<label>Heart:</label>
						<p class='columns6 indent'>
						<input type='checkbox' name='heart' value='Adynamic Precordium'>Adynamic Precordium<br />
						<input type='checkbox' name='heart' value='Normal Rate Regular Rhythm'>Normal Rate Regular Rhythm<br />
						<input type='checkbox' name='heart' value='Heaves/Thrills'>Heaves/Thrills<br />
						<input type='checkbox' name='heart' value='Murmurs'>Murmurs<br />
						</p><br />
						<p class='center'><label>Remarks: </label><textarea style='vertical-align: top' name='heartremarks' rows=2 cols='60'></textarea></p>
						<br /><br />
						
						<label>Abdomen:</label>
						<p class='columns6 indent'>
						<input type='checkbox' name='abdomen' value='Flat'>Flat<br />
						<input type='checkbox' name='abdomen' value='Globular'>Globular<br />
						<input type='checkbox' name='abdomen' value='Flabby'>Flabby<br />
						<input type='checkbox' name='abdomen' value='Muscle Guarding'>Muscle Guarding<br />
						<input type='checkbox' name='abdomen' value='Tenderness'>Tenderness<br />
						<input type='checkbox' name='abdomen' value='Palpable Mass'>Palpable Mass<br />
						</p><br />
						<p class='center'><label>Remarks: </label><textarea style='vertical-align: top' name='abdomenremarks' rows=2 cols='60'></textarea></p>
						<br /><br />
						
						<label>Extremities:</label>
						<p class='columns6 indent'>
						<input type='checkbox' name='extrem' value='Gross Deformity'>Gross Deformity<br />
						<input type='checkbox' name='extrem' value='Normal Gait'>Normal Gait<br />
						<input type='checkbox' name='extrem' value='Full and Equal Pulses'>Full and Equal Pulses<br />
						</p><br />
						<p class='center'><label>Remarks: </label><textarea style='vertical-align: top' name='extremremarks' rows=2 cols='60'></textarea></p>
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
					</div>
					
					</form>
				</div>
			</div>
		</body>
	</html>";

?>
