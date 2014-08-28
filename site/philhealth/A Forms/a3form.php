<?php
	print "<html>";
		print "<head>";
			print "<title>A3 Form</title>";
			print "<link rel='stylesheet' href='../styles/style.css' type='text/css'  />";
		print "</head>";
		print "<body>";
			print "<div id='container'>";
				print "<div id='header'>";
					print "<h4 class='shadow'><span class='indent10'>Annex A3</span></h4>";
					print "<br />";					
					print "<h1>PHILIPPINE HEALTH INSURANCE CORPORATION</h1>";
					print "<h2>PCB PATIENT LEDGER</h2>";
					print "<br />";
				print "</div>";
				print "<div id='body'>";
					print "<div class='width750 center'>
						<form name='a3form' method='POST'>
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
						<br />";
					
					print "<h4 class='center'>Name of Health Care Facility<br />
						<input type='text' size=25 name='nameHCF'></h4>";
					print "<br /></div>";					
					//print "<hr />";
					print "<h4 class='indent'>Part I</h4>";
						print "<div class='width750'>";
							print "<hr /><h4 class='center'>Personal Information</h4><hr />";
							print "<br /><span class='width70'><label>Name:</label></span><span class='width285'><input type='text' size=39 name='patientname'></span>
								<span class='width70'><label>Age:</label></span><span class='width150'><input type='text' size=4 name='age'></span>
								<span class='width70'><label>Sex:</label></span><select name='gender'><option value='Male'>Male</option><option value='Female'>Female</option></select>";
							print "<br /><br /><span class='width70'><label>Address:</label></span><textarea style='vertical-align: top' name='address' rows='2' cols='30'></textarea><span class='width21'></span>
								<span class='width70'><label>PIN:</label></span><input type='text' name='pin'>";
						print "</div>";
						print "<div class='width750'>";
							print "<br /><hr /><h4 class='center'>Membership Information</h4><hr />";
							print "<br /><span class='width200'><label>PHIC Membership</label></span>
								<input type='radio' name='membership' value='Member'><span class='width100'>Member</span>
								<input type='radio' name='membership' value='Dependent'><span class='width100'>Dependent</span>
								<input type='radio' name='membership' value='Non Member'>Non Member";
							print "<br /><span class='width200'><label>Sponsor</label></span>
								<input type='radio' name='sponsor' value='NHTS'><span class='width100'>NHTS</span> 
								<input type='radio' name='sponsor' value='NGA'><span class='width100'>NGA</span>
								<input type='radio' name='sponsor' value='LGU'><span class='width100'>LGU</span>
								<input type='radio' name='sponsor' value='PRIVATE'>PRIVATE";
							print "<br /><span class='width200'><label>IPP</label></span>
								<input type='radio' name='ipp' value='OG'><span class='width100'>OG</span> 
								<input type='radio' name='ipp' value='OFW'><span class='width100'>OFW</span>
								<input type='radio' name='ipp' value='Voluntary'>Voluntary/Self-employed";
							print "<br /><span class='width200'><label>Employment</label></span>
								<input type='radio' name='employment' value='Government'><span class='width100'>Government</span>
								<input type='radio' name='employment' value='Private'>Private";
							print "<br /><span class='width200'><label>Lifetime</label></span>
								<input type='radio' name='lifetime' value='Lifetime'>Lifetime"; 
						print "</div>";
						print "<div class='width750'>";
							print "<br /><hr /><h4 class='center'>Obligated Services</h4><hr />";
							print "<br />";
							print "<span class='width350'><label>BP Measurement</label>
								<select name='bp'><option value='Hypertensive'>Hypertensive</option>
								<option value='Nonhypertensive'>Nonhypertensive</option></select></span>
								<label>Date Performed:</label> <input type='date' size=8 name='bpdate'>(mm/dd/yyyy)";
							print "<br /><br /><span class='width350'><label>Periodic Clinical Breast Examination</label></span>
								<label>Date Performed:</label> <input type='date' size=8 name='pcbedate'>(mm/dd/yyyy)";
							print "<br /><br /><span class='width350'><label>Visual Inspection with Acetic Acid</label></span>
								<label>Date Performed:</label> <input type='date' size=8 name='vidate'>(mm/dd/yyyy)";
						print "</div>";
						print "<div class='width750'>";
							print "<br /><hr /><h4 class='center'>Diagnostic Examination Services</h4><hr />";
							print "<br /><span class='width100'><label>Date:</label></span>
								<input type='date' size =8 name='desdate'>(mm/dd/yyyy)";
							print "<br /><br /><span class='width100'><label>Diagnosis:</label></span>
								<textarea style='vertical-align: top' name='desdiagnosis' rows='2' cols='60'></textarea>";
							print "<br /><br /><span class='width100'><label>Type:</label></span> 
								<select name='destype'><option value='type1'>Type1</option>
								<option value='type2'>Type2</option>
								<option value='type3'>Type3</option>
								<option value='type4'>Type4</option>
								<option value='type5'>Type5</option></select>";
							print "<br /><br /><span class='width100'><label>Given:</label></span>
								<input type='text' name ='desgiven'>";
							print "<br /><br /><span class='width100'><label>Referred:</label></span>
								<input type='text' name ='desreferred'>";
							print "<br /><br /><span class='width100'><label>Remarks:</label></span>
								<textarea style='vertical-align: top' name='desremarks' rows='2' cols='60'></textarea>";
						print "</div>";
						print "<div class='width750'>";
							print "<br /><hr /><h4 class='center'><label>Other PCB1 Services</label></h4><hr />";
							print "<br /><span class='width100'><label>Date:</label></span>
								<input type='date' size=8 name='opsdate'>(mm/dd/yyyy)";
							print "<br /><br /><span class='width100'><label>Diagnosis:</label></span>
								<textarea style='vertical-align: top' name='opsdiagnosis' rows='2' cols='60'></textarea>";
							print "<br /><br /><span class='width100'><b>Type:</b></span> 
								<select name='opstype'><option value='type1'>Type1</option>
								<option value='type2'>Type2</option>
								<option value='type3'>Type3</option>
								<option value='type4'>Type4</option>
								<option value='type5'>Type5</option></select>";
							print "<br /><br /><span class='width100'><label>Remarks:</label></span>
								<textarea style='vertical-align: top' name='opsremarks' rows='2' cols='60'></textarea>";
						print "</div>";
						print "<div class='width750'>";
							print "<br /><hr /><h4 class='center'>Other Services</h4><hr />";
							print "<br /><span class='width100'><label>Date:</label></span>
								<input type='date' size=8 name='osdate'>(mm/dd/yyyy)";
							print "<br /><br /><span class='width100'><label>Diagnosis:</label></span>
								<textarea style='vertical-align: top' name='osdiagnosis' rows='2' cols='60'></textarea>";
							print "<br /><br /><span class='width100'><label>Type:</label></span> 
								<select name='ostype'><option value='type1'>Type1</option>
								<option value='type2'>Type2</option>
								<option value='type3'>Type3</option>
								<option value='type4'>Type4</option>
								<option value='type5'>Type5</option></select>";
							print "<br /><br /><span class='width100'><label>Remarks:</label></span>
								<textarea style='vertical-align: top' name='osremarks' rows='2' cols='60'></textarea>";
							print "<br /><br />";
						print "</div>";
						
						
					print "<br /><hr /><br /><h4 class='indent'>Part II</h4>";
						print "<div class='width750'>";
							print "<hr /><h4 class='center'>Please use this part for consultation of illness/well check-up (FP, immunization, etc.). You may use any equivalent ledger in your facility.</h4><hr />";
							print "<br /><span class='width100'><label>Date:</label></span>
								<input type='date' size=8 name='p2date'>(mm/dd/yyyy)";
							print "<br /><br /><span class='width100'><label>History of Present Illness:</label></span>
								<textarea style='vertical-align: top' name='p2history' rows='2' cols='60'></textarea>";
							print "<br /><br /><span class='width100'><label>Physical Exam:</label></span>
								<textarea style='vertical-align: top' name='p2pe' rows='2' cols='60'></textarea>";
							print "<br /><br /><span class='width100'><label>Assessment/ Impression:</label></span>
								<textarea style='vertical-align: top' name='p2assessment' rows='2' cols='60'></textarea>";
							print "<br /><br /><span class='width100'><label>Treatment/ Management Plan:</label></span>
								<textarea style='vertical-align: top' name='p2treatment' rows='2' cols='60'></textarea><br /><br />";
							print "</div>";
					
						print "<div class='width750 center'>
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
						</div>";
					
						print "<div id='submit' class='width750'>";
							print "<hr />";
							print "<span class='width100'>
								<input type='submit' name='submit' value='Submit'></span>";
							print "<input type='reset' name='submit' value='Reset'>";
							print "<hr /><br />";
						print "</div>";
									
					print "</form>";
				print "</div>";
			print "</div>";
		print "</body>";
	print "</html>";
?>
