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
					 <br />
				 </div>
				 <div id='body'>
				 	<form name='a3form' method='POST'>
					 <div class='width750 center'>
						<br />
						<h4 class='center'>Name of Health Care Facility<br />
						<input type='text' size=25 name='nameHCF'></h4>
					 	<br />
					 </div>					
					 <hr />
					 <br />
					 <h4 class='indent'>Part I</h4>
						 <div class='width750'>
							 <hr /><h4 class='center'>Personal Information</h4><hr />
							 <br /><span class='width70'><label>Name:</label></span><span class='width285'><input type='text' size=39 name='patientname'></span>
								<span class='width70'><label>Age:</label></span><span class='width150'><input type='text' size=4 name='age'></span>
								<span class='width70'><label>Sex:</label></span><select name='gender'><option value='Male'>Male</option><option value='Female'>Female</option></select>
							 <br /><br /><span class='width70'><label>Address:</label></span><textarea style='vertical-align: top' name='address' rows='2' cols='30'></textarea><span class='width21'></span>
								<span class='width70'><label>PIN:</label></span><input type='text' name='pin'>
						 </div>
						 <div class='width750'>
							 <br /><hr /><h4 class='center'>Membership Information</h4><hr />
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
							 <br /><span class='width100'><label>Date:</label></span>
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
								<textarea style='vertical-align: top' name='desremarks' rows='2' cols='60'></textarea>
						 </div>
						 <div class='width750'>
							 <br /><hr /><h4 class='center'><label>Other PCB1 Services</label></h4><hr />
							 <br /><span class='width100'><label>Date:</label></span>
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
								<textarea style='vertical-align: top' name='opsremarks' rows='2' cols='60'></textarea>
						 </div>
						 <div class='width750'>
							 <br /><hr /><h4 class='center'>Other Services</h4><hr />
							 <br /><span class='width100'><label>Date:</label></span>
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
							 <br /><br />
						 </div>
						
						
					 <br /><hr /><br /><h4 class='indent'>Part II</h4>
						 <div class='width750'>
							 <hr /><h4 class='center'>Please use this part for consultation of illness/well check-up (FP, immunization, etc.). You may use any equivalent ledger in your facility.</h4><hr />
							 <br /><span class='width100'><label>Date:</label></span>
								<input type='text' size=8 name='p2date'>(mm/dd/yyyy)
							 <br /><br /><span class='width100'><label>History of Present Illness:</label></span>
								<textarea style='vertical-align: top' name='p2history' rows='2' cols='60'></textarea>
							 <br /><br /><span class='width100'><label>Physical Exam:</label></span>
								<textarea style='vertical-align: top' name='p2pe' rows='2' cols='60'></textarea>
							 <br /><br /><span class='width100'><label>Assessment/ Impression:</label></span>
								<textarea style='vertical-align: top' name='p2assessment' rows='2' cols='60'></textarea>
							 <br /><br /><span class='width100'><label>Treatment/ Management Plan:</label></span>
								<textarea style='vertical-align: top' name='p2treatment' rows='2' cols='60'></textarea><br /><br />
							 </div>
					
						
					 </form>
				 </div>
			 </div>
		 </body>
	 </html>

