<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<?php 
	
	$dbHost = "localhost";
	$dbUser = $_SESSION["dbuser"];
	$dbPass = $_SESSION["dbpass"];
	//$dbUser = "root";
	//$dbPass = "root";
	//$dbarray = array("Anao RHU 1" => "hf_anao1", "Bamban RHU 1" => "hf_bamban1", "Bamban RHU 2" => "hf_bamban2", "Capas RHU 1" => "hf_capas1", "Capas RHU 2" => "hf_capas2", "Capas RHU 3" => "hf_capas3", "Tarlac CHC 1" => "hf_chc1", "Tarlac CHC 5" => "hf_chc5", "Tarlac CHC 6" => "hf_chc6", "Tarlac CHC 7" => "hf_chc7", "Tarlac CHC 8" => "hf_chc8", "Tarlac CHC 9" => "hf_chc9", "Tarlac CHC 10" => "hf_chc10", "Concepcion RHU 1" => "hf_concepcion1", "Gerona RHU 1" => "hf_gerona1", "Gerona RHU 2" => "hf_gerona2", "Gerona RHU 3" => "hf_gerona3", "Lapaz RHU 1" => "hf_lapaz1", "Lapaz RHU 2" => "hf_lapaz2", "Moncada RHU 1" => "hf_moncada1", "Moncada RHU 2" => "hf_moncada2", "Paniqui RHU 1" => "hf_paniqui1", "Pura RHU 1" => "hf_pura1", "Ramos RHU 1" => "hf_ramos1","Victoria RHU 1" => "hf_victoria1", "Victoria RHU 2" => "hf_victoria2");
	//$dbarray = array("Anao RHU 1" => "hf_anao1", "Bamban RHU 1" => "hf_bamban1", "Bamban RHU 2" => "hf_bamban2", "Capas RHU 1" => "hf_capas1", "Capas RHU 2" => "hf_capas2", "Capas RHU 3" => "hf_capas3", "Tarlac CHC 6" => "hf_chc6", "Tarlac CHC 8" => "hf_chc8", "Tarlac CHC 10" => "hf_chc10", "Gerona RHU 1" => "hf_gerona1", "Gerona RHU 2" => "hf_gerona2", "Ipil RHU 1" => "hf_ipil1", "Lapaz RHU 1" => "hf_lapaz1", "Lapaz RHU 2" => "hf_lapaz2", "Moncada RHU 1" => "hf_moncada1", "Moncada RHU 2" => "hf_moncada2", "Paniqui RHU 1" => "hf_paniqui1", "Pateros RHU 1" => "hf_pateros1", "Pura RHU 1" => "hf_pura1", "Ramos RHU 1" => "hf_ramos1", "Victoria RHU 1" => "hf_victoria1", "Kabasalan RHU 1" => "hf_kabasalan1");
	$dbarray = array($_SESSION['datanode']['name'] => $_SESSION['dbname']);
	if(!isset($_POST['rhu']))
	{
		$_POST['rhu']=$dbarray;
	}
	
	//$dbConnect = mysqli_connect($dbhost, $dbUser, $dbPass, $dbName);
	
	//$sql = "SELECT";
	
	//$dbQuery = mysqli_query($dbConnect,$sql);
?>

<html>

	<head>
		<title>Wireless Access for Health</title>
	
		<link rel="stylesheet" type="text/css" href="src/jquery-ui-1.8.13.custom.css">
	    <link rel="stylesheet" type="text/css" href="src/ui.dropdownchecklist.themeroller.css">
			<!-- Include the basic JQuery support (core and ui) -->
	    <script type="text/javascript" src="src/jquery-1.6.1.min.js"></script>
	    <script type="text/javascript" src="src/jquery-ui-1.8.13.custom.min.js"></script>
	    
	    <!-- Include the DropDownCheckList supoprt -->
	    <!-- <script type="text/javascript" src="ui.dropdownchecklist.js"></script> -->
	    <script type="text/javascript" src="src/ui.dropdownchecklist-1.4-min.js"></script>
	    
	    <!-- Apply dropdown check list to the selected items  -->
	    <script type="text/javascript">
	        $(document).ready(function() {
	            $("#s1").dropdownchecklist();
	            $("#s2").dropdownchecklist( {icon: {}, width: 150 } );
	            $("#s3").dropdownchecklist( { width: 150 } );
	            $("#s4").dropdownchecklist( { maxDropHeight: 150 } );
	            $("#s5").dropdownchecklist( { firstItemChecksAll: true, explicitClose: '...close' } );
	            $("#s6").dropdownchecklist();
	            $("#s7").dropdownchecklist();
	            $("#s8").dropdownchecklist( { emptyText: "Please Select...", width: 150 } );
	            $("#s9").dropdownchecklist( { textFormatFunction: function(options) {
	                var selectedOptions = options.filter(":selected");
	                var countOfSelected = selectedOptions.size();
	                switch(countOfSelected) {
	                    case 0: return "<i>Nobody<i>";
	                    case 1: return selectedOptions.text();
	                    case options.size(): return "<b>Everybody</b>";
	                    default: return countOfSelected + " People";
	                }
	            } });
	            $("#s10").dropdownchecklist( { forceMultiple: true
	, onComplete: function(selector) {
		var values = "";
	  	for( i=0; i < selector.options.length; i++ ) {
	    	if (selector.options[i].selected && (selector.options[i].value != "")) {
	      		if ( values != "" ) values += ";";
	      		values += selector.options[i].value;
	    	}
	  	}
	  	alert( values );
	} 
	, onItemClick: function(checkbox, selector){
		var justChecked = checkbox.prop("checked");
		var checkCount = (justChecked) ? 1 : -1;
		for( i = 0; i < selector.options.length; i++ ){
			if ( selector.options[i].selected ) checkCount += 1;
		}
	    if ( checkCount > 3 ) {
			alert( "Limit is 3" );
			throw "too many";
		}
	}
	            });
	        });
	    </script>
			<style type="text/css">
				form, label, body {
				font-size: 10pt;
	   			font-family: Arial;
				}
				table {
	    		border-collapse: collapse;
	    		font-size: 8.5pt;
	   			font-family: Sans-Serif;
	   			}        
	    		th,td {
	    		border: 1px solid black;
	    		margin:0;
	    		}
	    		.bold, label, legend {
				font-weight:bold;
				}
				
	    		.container1
				{
				position:fixed;
				top:10px;
				left:50%;
				bottom:0;
				width:100%;
				margin: 0 0 0 -20%;
				}   
				#container
				{
				width:115%;
				margin:0 auto;
				}     
			        
		</style>
	</head>
	
	<body>
    	<div id='container'>
        
		<form method='post' action='' name='form_statistics'>
			<div class='container1'>
			<?php
			
				$yearstart=2005;
				$yearend=2035;
				if(!isset($_POST['startMonth']))
				{
					$_POST['startMonth']=1;
				}
				if(!isset($_POST['startYear']))
				{
					$_POST['startYear'] = date("Y");
				}
				if(!isset($_POST['endMonth']))
				{
					$_POST['endMonth']=1;
				}
				if(!isset($_POST['endYear']))
				{
					$_POST['endYear'] = date("Y");
				}
				//$year =date("Y");
				echo "<br />";
				//echo "<fieldset style='width: 500;'>";
				//echo "<legend>Select Dates To View Results</legend><br />";
				echo "<label>From</label>
						<select name='startMonth'>
							<option value=1 ".($_POST['startMonth']==1 ? 'selected' : '').">January</option>
							<option value=2 ".($_POST['startMonth']==2 ? 'selected' : '').">February</option>
							<option value=3 ".($_POST['startMonth']==3 ? 'selected' : '').">March</option>
							<option value=4 ".($_POST['startMonth']==4 ? 'selected' : '').">April</option>
							<option value=5 ".($_POST['startMonth']==5 ? 'selected' : '').">May</option>
							<option value=6 ".($_POST['startMonth']==6 ? 'selected' : '').">June</option>
							<option value=7 ".($_POST['startMonth']==7 ? 'selected' : '').">July</option>
							<option value=8 ".($_POST['startMonth']==8 ? 'selected' : '').">August</option>
							<option value=9 ".($_POST['startMonth']==9 ? 'selected' : '').">September</option>
							<option value=10 ".($_POST['startMonth']==10 ? 'selected' : '').">October</option>
							<option value=11 ".($_POST['startMonth']==11 ? 'selected' : '').">November</option>
							<option value=12 ".($_POST['startMonth']==12 ? 'selected' : '').">December</option>
						</select>";
				
				echo "<select name='startYear'>";
					for($y=$yearstart; $y<=$yearend; $y++)
					{
						//if($year==$y):
							//echo "<option value=$y selected=$year>$y";
						//else:
							echo "<option value=$y ".($_POST['startYear']== $y ? 'selected' : '').">$y";
						//endif;
					}
				echo "</select>";		
						
				echo "<label> To</label>
						<select name='endMonth'>
							<option value=1 ".($_POST['endMonth']==1 ? 'selected' : '').">January</option>
							<option value=2 ".($_POST['endMonth']==2 ? 'selected' : '').">February</option>
							<option value=3 ".($_POST['endMonth']==3 ? 'selected' : '').">March</option>
							<option value=4 ".($_POST['endMonth']==4 ? 'selected' : '').">April</option>
							<option value=5 ".($_POST['endMonth']==5 ? 'selected' : '').">May</option>
							<option value=6 ".($_POST['endMonth']==6 ? 'selected' : '').">June</option>
							<option value=7 ".($_POST['endMonth']==7 ? 'selected' : '').">July</option>
							<option value=8 ".($_POST['endMonth']==8 ? 'selected' : '').">August</option>
							<option value=9 ".($_POST['endMonth']==9 ? 'selected' : '').">September</option>
							<option value=10 ".($_POST['endMonth']==10 ? 'selected' : '').">October</option>
							<option value=11 ".($_POST['endMonth']==11 ? 'selected' : '').">November</option>
							<option value=12 ".($_POST['endMonth']==12 ? 'selected' : '').">December</option>
						</select>";
			
				//selection of month and year.
				
				echo "<select name='endYear'>";
					for($y=$yearstart; $y<=$yearend; $y++)
					{
						//if($year==$y):
							//echo "<option value=$y selected=$year>$y";
						//else:
							echo "<option value=$y  ".($_POST['endYear']== $y ? 'selected' : '').">$y";
						//endif;
					}
				echo "</select>";
			?>
			<!--<br />
			<label>Patient Age Based On:</label>
			<select name='age' >
				<option value='reg_date'>Registration Date</option>
				<option value='end_date'>End Date</option>
			</select>-->
			
            <input type='submit' name='go' value='Submit'>
			
          	</div>
        	
		</form>
		
		
		<?php
			if (isset($_REQUEST['go']) && $_REQUEST['go'] == 'Submit')
			{
				$sdate = strftime("%m/%d/%Y",mktime(0,0,0,$_POST['startMonth'],1,$_POST['startYear']));
				$edate = strftime("%m/%d/%Y",mktime(0,0,0,($_POST['endMonth']+1),0,$_POST['endYear']));
				
				$newSDate = date("Y-m-d", strtotime($sdate));
				$newEDate = date("Y-m-d", strtotime($edate));
				
				if(isset($_POST['go'])){
					echo "<a href='./topmor.php?sDate=$newSDate&eDate=$newEDate' target='_blank'>Show Morbidity UNDER 1 AND ABOVE 60</a>";
					echo "<br />";
					echo "<a href='./mor.php?sDate=$newSDate&eDate=$newEDate' target='_blank'>Show Morbidity Report</a>";
					echo "<br />";
					//echo "<a href='./fp.php?sDate=$newSDate&eDate=$newEDate' target='_blank'>Show Family Planning Report</a>";
					//echo "<input type='button' name='mor' onClick=\"window.location.href='./mor.php?sDate=$newSDate&eDate=$newEDate'\" target='_blank' value='Show MOR' >";
				}
				
				if ($newSDate > $newEDate)
				{
					echo "<script>alert('Start Date is Greater Than End Date')</script>";
					return false;
				}
				/*if(count($_POST['rhu'])==4)
				{
					unset($_POST['rhu'][0]);
				}*/
				if (isset($_POST['rhu'][0]) && $_POST['rhu'][0]==1)
				{
					unset($_POST['rhu'][0]);
				}
						
				foreach ($_POST['rhu'] as $key => $value)
				{
					foreach ($dbarray as $hf_name => $rhuName)
					{
						if($value == $rhuName)
						{
							$_POST['rhu'][$hf_name]=$_POST['rhu'][$key];
							unset($_POST['rhu'][$key]);
		
						}
					
					}
				}
				
				if(empty($_POST['rhu']))
				{
					$dbName = $dbarray;
				}
				else
				{
					$dbName = $_POST['rhu'];
				}
				
				$_SESSION['dbarray']=$dbName; //for morbidity link
				//print_r($dbName);
				//echo implode(",",$dbName);
				
				echo "<div>";
				echo "<br /><br /><br />";
				echo "<span style='width: 75px; display: inline-block;'><strong>Start Date:</strong></span>".$sdate;
				echo "<br />";
				echo "<span style='width: 75px; display: inline-block;'><strong>End Date:</strong></span>".$edate;
				echo "<br />";
				
				/*if(isset($_POST['age']) && $_POST['age']=='reg_date')
				{
					$basedAge = "Date of Registration";
				}
				elseif(isset($_POST['age']) && $_POST['age']=='end_date')
				{
					$basedAge = "Selected End Date";
				}
				echo "Patient age based on ". $basedAge;*/
				
				echo "<table class='container'>
						<tr>
							<th bgcolor='#DF7401' rowspan='2' style='width:6%;'>Rhu</th>
							<th bgcolor='#00BFFF' colspan='4'>Patient's Age &lt;18</th>
							<th bgcolor='#00BFFF' colspan='4'>Patient's Age &gt;=18</th>
							<th bgcolor='#00BFFF' colspan='4'>Total Patients (Gender)</th>
							<th bgcolor='#00BFFF' colspan='4'>Total Patients (Age Group)</th>
							<th bgcolor='#00BFFF' rowspan='2'>Grand Total Patients</th>
							<th bgcolor='#DF7401' rowspan='2'>Households</th>
							<th bgcolor='#FA58D0' colspan='4'>Opened ITR &lt;18</th>
							<th bgcolor='#FA58D0' colspan='4'>Opened ITR &gt;=18</th>
							<th bgcolor='#FA58D0' colspan='4'>Total Opened ITR (Gender)</th>
							<th bgcolor='#FA58D0' colspan='4'>Total Opened ITR (Age Group)</th>
							<th bgcolor='#FA58D0' rowspan='2'>Grand Total Opened ITR</th>
							<th bgcolor='#DF7401' rowspan='2'># of End Users</th>
							<th bgcolor='#DF7401' rowspan='2'>Patient w/o FF</th>
							<th bgcolor='#DF7401' rowspan='2'>FF w/o Members</th>
							<th bgcolor='#FA58D0' colspan='4'>Notes Consults &lt;18</th>
							<th bgcolor='#FA58D0' colspan='4'>Notes Consults &gt;=18</th>
							<th bgcolor='#FA58D0' colspan='4'>Total Notes Consults (Gender)</th>
							<th bgcolor='#FA58D0' colspan='4'>Total Notes Consults (Age Group)</th>
							<th bgcolor='#DF7401' rowspan='2'>Grand Total Notes Consults</th>
							<th bgcolor='#00BFFF' colspan='4'>Total MC Patients (Age Group)</th>
							<th bgcolor='#00BFFF' rowspan='2'>GRAND Total MC Patients</th>
							<th bgcolor='#FA58D0' colspan='4'>MC Consults (Age Group)</th>
							<th bgcolor='#DF7401' rowspan='2'>Total MC Consults</th>
							<th bgcolor='#00BFFF' colspan='4'>Total CCDEV Patients (Gender)</th>
							<th bgcolor='#00BFFF' rowspan='2'>GRAND Total CCDEV Patients</th>
							<th bgcolor='#FA58D0' colspan='4'>CCDEV Consults (Gender)</th>
							<th bgcolor='#DF7401' rowspan='2'>Total CCDEV Consults</th>
							<th bgcolor='#DF7401' rowspan='2'>FP Consults</th>
						</tr>
							
						<tr>
	            			<th width='50'>M</th>
							<th bgcolor='#BDBDBD' width='70' style='padding:15;'>%</th>	
							<th width='50'>F</th>
							<th bgcolor='#BDBDBD' width='70' style='padding:15;'>%</th>
							<th width='50'>M</th>
							<th bgcolor='#BDBDBD' width='70' style='padding:15;'>%</th>
							<th width='50'>F</th>
							<th bgcolor='#BDBDBD' width='70' style='padding:15;'>%</th>
							<th width='50'>M</th>
							<th bgcolor='#BDBDBD' width='70' style='padding:15;'>%</th>
							<th width='50'>F</th>
							<th bgcolor='#BDBDBD' width='70' style='padding:15;'>%</th>
							<th width='50'>&lt18</th>
							<th bgcolor='#BDBDBD' width='70' style='padding:15;'>%</th>
							<th width='50'>&gt=18</th>
							<th bgcolor='#BDBDBD' width='70' style='padding:15;'>%</th>
							
							<th width='50'>M</th>
							<th bgcolor='#BDBDBD' width='70' style='padding:15;'>%</th>	
							<th width='50'>F</th>
							<th bgcolor='#BDBDBD' width='70' style='padding:15;'>%</th>
							<th width='50'>M</th>
							<th bgcolor='#BDBDBD' width='70' style='padding:15;'>%</th>
							<th width='50'>F</th>
							<th bgcolor='#BDBDBD' width='70' style='padding:15;'>%</th>
							<th width='50'>M</th>
							<th bgcolor='#BDBDBD' width='70' style='padding:15;'>%</th>
							<th width='50'>F</th>
							<th bgcolor='#BDBDBD' width='70' style='padding:15;'>%</th>
							<th width='50'>&lt18</th>
							<th bgcolor='#BDBDBD' width='70' style='padding:15;'>%</th>
							<th width='50'>&gt=18</th>
							<th bgcolor='#BDBDBD' width='70' style='padding:15;'>%</th>
							
							<th width='50'>M</th>
							<th bgcolor='#BDBDBD' width='70' style='padding:15;'>%</th>	
							<th width='50'>F</th>
							<th bgcolor='#BDBDBD' width='70' style='padding:15;'>%</th>
							<th width='50'>M</th>
							<th bgcolor='#BDBDBD' width='70' style='padding:15;'>%</th>
							<th width='50'>F</th>
							<th bgcolor='#BDBDBD' width='70' style='padding:15;'>%</th>
							<th width='50'>M</th>
							<th bgcolor='#BDBDBD' width='70' style='padding:15;'>%</th>
							<th width='50'>F</th>
							<th bgcolor='#BDBDBD' width='70' style='padding:15;'>%</th>
							<th width='50'>&lt18</th>
							<th bgcolor='#BDBDBD' width='70' style='padding:15;'>%</th>
							<th width='50'>&gt=18</th>
							<th bgcolor='#BDBDBD' width='70' style='padding:15;'>%</th>
							
							<th width='50'>&lt18</th>
							<th bgcolor='#BDBDBD' width='70' style='padding:15;'>%</th>
							<th width='50'>&gt=18</th>
							<th bgcolor='#BDBDBD' width='70' style='padding:15;'>%</th>
							
							<th width='50'>&lt18</th>
							<th bgcolor='#BDBDBD' width='70' style='padding:15;'>%</th>
							<th width='50'>&gt=18</th>
							<th bgcolor='#BDBDBD' width='70' style='padding:15;'>%</th>
							
							<th width='50'>M</th>
							<th bgcolor='#BDBDBD' width='70' style='padding:15;'>%</th>
							<th width='50'>F</th>
							<th bgcolor='#BDBDBD' width='70' style='padding:15;'>%</th>
							
							<th width='50'>M</th>
							<th bgcolor='#BDBDBD' width='70' style='padding:15;'>%</th>
							<th width='50'>F</th>
							<th bgcolor='#BDBDBD' width='70' style='padding:15;'>%</th>
							
						</tr>";
										
				$countPlt18M = 0;
				$countPlt18F = 0;
				$countPgt18M = 0;
				$countPgt18F = 0;
				$countTotPGMale = 0;
				$countTotPGFemale = 0;
				$countTotPAMale = 0;
				$countTotPAFemale = 0;
				$countGrandTotP = 0;
				
				$countHouseHolds = 0;
				
				$countClt18M = 0;
				$countClt18F = 0;
				$countCgt18M = 0;
				$countCgt18F = 0;
				$countTotCGMale = 0;
				$countTotCGFemale = 0;
				$countTotCAMale = 0;
				$countTotCAFemale = 0;
				$countGrandTotC = 0;
				
				$countEndUser = 0;
				
				$countPatientNoFF = 0;
				$countFFNoMember = 0;
				
				$countNlt18M = 0;
				$countNlt18F = 0;
				$countNgt18M = 0;
				$countNgt18F = 0;
				$countTotNGMale = 0;
				$countTotNGFemale = 0;
				$countTotNAMale = 0;
				$countTotNAFemale = 0;
				$countNConsults = 0;
				
				$countMCPlt18 = 0;
				$countMCPgt18 = 0;
				$countMCPtot = 0;
				
				$countMClt18 = 0;
				$countMCgt18 = 0;
				$countMCConsult = 0;
				
				$countCCDEVPMale = 0;
				$countCCDEVPFemale = 0;
				$countCCDEVPtot = 0;
				
				$countCCDEVMale = 0;
				$countCCDEVFemale = 0;
				$countCCDEVConsult = 0;
				
				$countFPConsult = 0;
				
				foreach ($dbName as $key => $value)
				{
					$dbConnect = mysqli_connect($dbHost, $dbUser, $dbPass, $value);
					
					/*if ($value=='gerona2')
					{
						$rhuName = "Gerona RHU 2";
					}
					elseif ($value=='victoria1')
					{
						$rhuName = "Victoria RHU 1";
					}
					elseif ($value=='victoria2')
					{
						$rhuName = "Victoria RHU 2";
					}*/
					

					/*if(isset($_POST['age']) && $_POST['age']=='reg_date')
					{
						$selectSql1 = "SELECT count(patient_id) FROM m_patient WHERE round((to_days(registration_date)-to_days(patient_dob))/365,1) < 18 and patient_gender='M' AND date_format(registration_date,'%m/%d/%Y') BETWEEN '$sdate' AND '$edate'";
						$selectSql2 = "SELECT count(patient_id) FROM m_patient WHERE round((to_days(registration_date)-to_days(patient_dob))/365,1) < 18 and patient_gender='F' AND date_format(registration_date,'%m/%d/%Y') BETWEEN '$sdate' AND '$edate'";
						$selectSql3 = "SELECT count(patient_id) FROM m_patient WHERE round((to_days(registration_date)-to_days(patient_dob))/365,1) >= 18 and patient_gender='M' AND date_format(registration_date,'%m/%d/%Y') BETWEEN '$sdate' AND '$edate'";
						$selectSql4 = "SELECT count(patient_id) FROM m_patient WHERE round((to_days(registration_date)-to_days(patient_dob))/365,1) >= 18 and patient_gender='F' AND date_format(registration_date,'%m/%d/%Y') BETWEEN '$sdate' AND '$edate'";
					}
					elseif(isset($_POST['age']) && $_POST['age']=='end_date')
					{
						$selectSql1 = "SELECT count(patient_id) FROM m_patient WHERE round((to_days('$newEDate')-to_days(patient_dob))/365,1) < 18 and patient_gender='M' AND date_format(registration_date,'%m/%d/%Y') BETWEEN '$sdate' AND '$edate'";
						$selectSql2 = "SELECT count(patient_id) FROM m_patient WHERE round((to_days('$newEDate')-to_days(patient_dob))/365,1) < 18 and patient_gender='F' AND date_format(registration_date,'%m/%d/%Y') BETWEEN '$sdate' AND '$edate'";
						$selectSql3 = "SELECT count(patient_id) FROM m_patient WHERE round((to_days('$newEDate')-to_days(patient_dob))/365,1) >= 18 and patient_gender='M' AND date_format(registration_date,'%m/%d/%Y') BETWEEN '$sdate' AND '$edate'";
						$selectSql4 = "SELECT count(patient_id) FROM m_patient WHERE round((to_days('$newEDate')-to_days(patient_dob))/365,1) >= 18 and patient_gender='F' AND date_format(registration_date,'%m/%d/%Y') BETWEEN '$sdate' AND '$edate'";
					}*/
					
					$selectSql1 = "SELECT count(patient_id) FROM m_patient WHERE round((to_days(registration_date)-to_days(patient_dob))/365,1) < 18 and patient_gender='M' AND date_format(registration_date,'%Y-%m-%d') BETWEEN '$newSDate' AND '$newEDate'";
					$selectSql2 = "SELECT count(patient_id) FROM m_patient WHERE round((to_days(registration_date)-to_days(patient_dob))/365,1) < 18 and patient_gender='F' AND date_format(registration_date,'%Y-%m-%d') BETWEEN '$newSDate' AND '$newEDate'";
					$selectSql3 = "SELECT count(patient_id) FROM m_patient WHERE round((to_days(registration_date)-to_days(patient_dob))/365,1) >= 18 and patient_gender='M' AND date_format(registration_date,'%Y-%m-%d') BETWEEN '$newSDate' AND '$newEDate'";
					$selectSql4 = "SELECT count(patient_id) FROM m_patient WHERE round((to_days(registration_date)-to_days(patient_dob))/365,1) >= 18 and patient_gender='F' AND date_format(registration_date,'%Y-%m-%d') BETWEEN '$newSDate' AND '$newEDate'";
					$selectSql5 = "SELECT count(family_id) FROM m_family_address WHERE address_year BETWEEN YEAR('$newSDate') AND YEAR('$newEDate')";
					
					$selectSql6 = "SELECT count(consult_id) FROM m_consult INNER JOIN m_patient ON m_consult.patient_id=m_patient.patient_id WHERE round((to_days(consult_date)-to_days(m_patient.patient_dob))/365,1) < 18 and m_patient.patient_gender='M' AND date_format(consult_date,'%Y-%m-%d') BETWEEN '$newSDate' AND '$newEDate'";
					$selectSql7 = "SELECT count(consult_id) FROM m_consult INNER JOIN m_patient ON m_consult.patient_id=m_patient.patient_id WHERE round((to_days(consult_date)-to_days(m_patient.patient_dob))/365,1) < 18 and m_patient.patient_gender='F' AND date_format(consult_date,'%Y-%m-%d') BETWEEN '$newSDate' AND '$newEDate'";
					$selectSql8 = "SELECT count(consult_id) FROM m_consult INNER JOIN m_patient ON m_consult.patient_id=m_patient.patient_id WHERE round((to_days(consult_date)-to_days(m_patient.patient_dob))/365,1) >= 18 and m_patient.patient_gender='M' AND date_format(consult_date,'%Y-%m-%d') BETWEEN '$newSDate' AND '$newEDate'";
					$selectSql9 = "SELECT count(consult_id) FROM m_consult INNER JOIN m_patient ON m_consult.patient_id=m_patient.patient_id WHERE round((to_days(consult_date)-to_days(m_patient.patient_dob))/365,1) >= 18 and m_patient.patient_gender='F' AND date_format(consult_date,'%Y-%m-%d') BETWEEN '$newSDate' AND '$newEDate'";
					$selectSql10 = "SELECT count(consult_id) FROM m_consult WHERE date_format(consult_date,'%Y-%m-%d') BETWEEN '$newSDate' AND '$newEDate'";
					
					/*$selectSql6 = "SELECT count(consult_id) FROM m_consult INNER JOIN m_patient ON m_consult.patient_id=m_patient.patient_id WHERE round((to_days(consult_date)-to_days(m_patient.patient_dob))/365,1) < 18 and m_patient.patient_gender='M' AND see_doctor_flag = 'Y' AND date_format(consult_date,'%Y-%m-%d') BETWEEN '$newSDate' AND '$newEDate'";
					$selectSql7 = "SELECT count(consult_id) FROM m_consult INNER JOIN m_patient ON m_consult.patient_id=m_patient.patient_id WHERE round((to_days(consult_date)-to_days(m_patient.patient_dob))/365,1) < 18 and m_patient.patient_gender='F' AND see_doctor_flag = 'Y' AND date_format(consult_date,'%Y-%m-%d') BETWEEN '$newSDate' AND '$newEDate'";
					$selectSql8 = "SELECT count(consult_id) FROM m_consult INNER JOIN m_patient ON m_consult.patient_id=m_patient.patient_id WHERE round((to_days(consult_date)-to_days(m_patient.patient_dob))/365,1) >= 18 and m_patient.patient_gender='M' AND see_doctor_flag = 'Y' AND date_format(consult_date,'%Y-%m-%d') BETWEEN '$newSDate' AND '$newEDate'";
					$selectSql9 = "SELECT count(consult_id) FROM m_consult INNER JOIN m_patient ON m_consult.patient_id=m_patient.patient_id WHERE round((to_days(consult_date)-to_days(m_patient.patient_dob))/365,1) >= 18 and m_patient.patient_gender='F' AND see_doctor_flag = 'Y' AND date_format(consult_date,'%Y-%m-%d') BETWEEN '$newSDate' AND '$newEDate'";
					$selectSql10 = "SELECT count(consult_id) FROM m_consult WHERE see_doctor_flag = 'Y' AND consult_date BETWEEN '$newSDate' AND '$newEDate'";*/
					$selectSql11 = "SELECT count(user_id) FROM game_user WHERE user_active='Y'";
					//$selectSql12 = "SELECT count(patient_id) FROM m_patient WHERE registration_date BETWEEN '$newSDate' AND '$newEDate'";
					$selectSql12 = "SELECT count(patient_id) FROM `m_patient` WHERE patient_id NOT IN (SELECT patient_id FROM m_family_members)";//patient w/o ff
					$selectSql13 = "SELECT count(family_id) FROM `m_family` WHERE family_id NOT IN (SELECT family_id FROM m_family_members)";//ff w/o member
					
					$selectSql14_1 = "SELECT count(notes_id) FROM m_consult_notes JOIN m_patient ON m_consult_notes.patient_id=m_patient.patient_id WHERE round((to_days(notes_timestamp)-to_days(m_patient.patient_dob))/365,1) < 18 AND m_patient.patient_gender='M' AND date_format(notes_timestamp, '%Y-%m-%d') BETWEEN '$newSDate' AND '$newEDate'";
					$selectSql14_2 = "SELECT count(notes_id) FROM m_consult_notes JOIN m_patient ON m_consult_notes.patient_id=m_patient.patient_id WHERE round((to_days(notes_timestamp)-to_days(m_patient.patient_dob))/365,1) < 18 AND m_patient.patient_gender='F' AND date_format(notes_timestamp, '%Y-%m-%d') BETWEEN '$newSDate' AND '$newEDate'";
					$selectSql14_3 = "SELECT count(notes_id) FROM m_consult_notes JOIN m_patient ON m_consult_notes.patient_id=m_patient.patient_id WHERE round((to_days(notes_timestamp)-to_days(m_patient.patient_dob))/365,1) >= 18 AND m_patient.patient_gender='M' AND date_format(notes_timestamp, '%Y-%m-%d') BETWEEN '$newSDate' AND '$newEDate'";
					$selectSql14_4 = "SELECT count(notes_id) FROM m_consult_notes JOIN m_patient ON m_consult_notes.patient_id=m_patient.patient_id WHERE round((to_days(notes_timestamp)-to_days(m_patient.patient_dob))/365,1) >= 18 AND m_patient.patient_gender='F' AND date_format(notes_timestamp, '%Y-%m-%d') BETWEEN '$newSDate' AND '$newEDate'";
					$selectSql14 = "SELECT count(notes_id) FROM m_consult_notes WHERE date_format(notes_timestamp, '%Y-%m-%d') BETWEEN '$newSDate' AND '$newEDate'";
					
					
					$selectSql15_a = "SELECT count(a.patient_id) FROM `m_patient` a JOIN (SELECT * FROM m_patient_mc GROUP BY patient_id HAVING count(patient_id)>=1) b ON a.patient_id = b.patient_id WHERE round((to_days(mc_consult_date)-to_days(patient_dob))/365,1) < 18 AND date_format(mc_consult_date, '%Y-%m-%d') BETWEEN '$newSDate' AND '$newEDate'";
					$selectSql15_b = "SELECT count(a.patient_id) FROM `m_patient` a JOIN (SELECT * FROM m_patient_mc GROUP BY patient_id HAVING count(patient_id)>=1) b ON a.patient_id = b.patient_id WHERE round((to_days(mc_consult_date)-to_days(patient_dob))/365,1) >= 18 AND date_format(mc_consult_date, '%Y-%m-%d') BETWEEN '$newSDate' AND '$newEDate'";
					$selectSql15_tot = "SELECT count(a.patient_id) FROM `m_patient` a JOIN (SELECT * FROM m_patient_mc GROUP BY patient_id HAVING count(patient_id)>=1) b ON a.patient_id = b.patient_id WHERE date_format(mc_consult_date, '%Y-%m-%d') BETWEEN '$newSDate' AND '$newEDate'";
					
					$selectSql15_1 = "SELECT count(id) FROM (SELECT patient_id AS pID, consult_id AS id, postpartum_date AS mcDate FROM m_consult_mc_postpartum UNION ALL SELECT patient_id AS pID, consult_id AS id, prenatal_date AS mcDate FROM m_consult_mc_prenatal UNION ALL SELECT patient_id AS pID, consult_id AS id, actual_service_date AS mcDate FROM m_consult_mc_services UNION ALL SELECT patient_id AS pID, consult_id AS id, actual_vaccine_date AS mcDate FROM m_consult_mc_vaccine) AS mc JOIN m_patient a ON mc.pID=a.patient_id WHERE round((to_days(mcDate)-to_days(a.patient_dob))/365,1) < 18 AND date_format(mcDate, '%Y-%m-%d') BETWEEN '$newSDate' AND '$newEDate'";
					$selectSql15_2 = "SELECT count(id) FROM (SELECT patient_id AS pID, consult_id AS id, postpartum_date AS mcDate FROM m_consult_mc_postpartum UNION ALL SELECT patient_id AS pID, consult_id AS id, prenatal_date AS mcDate FROM m_consult_mc_prenatal UNION ALL SELECT patient_id AS pID, consult_id AS id, actual_service_date AS mcDate FROM m_consult_mc_services UNION ALL SELECT patient_id AS pID, consult_id AS id, actual_vaccine_date AS mcDate FROM m_consult_mc_vaccine) AS mc JOIN m_patient a ON mc.pID=a.patient_id WHERE round((to_days(mcDate)-to_days(a.patient_dob))/365,1) > 18 AND date_format(mcDate, '%Y-%m-%d') BETWEEN '$newSDate' AND '$newEDate'";
					$selectSql15 = "SELECT count(id) FROM (SELECT consult_id AS id, postpartum_date AS mcDate FROM m_consult_mc_postpartum UNION ALL SELECT consult_id AS id, prenatal_date AS mcDate FROM m_consult_mc_prenatal UNION ALL SELECT consult_id AS id, actual_service_date AS mcDate FROM m_consult_mc_services UNION ALL SELECT consult_id AS id, actual_vaccine_date AS mcDate FROM m_consult_mc_vaccine) AS mc WHERE date_format(mcDate, '%Y-%m-%d') BETWEEN '$newSDate' AND '$newEDate'";//MC
					
					$selectSql16_a = "SELECT count(a.patient_id) FROM `m_patient` a JOIN (SELECT * FROM m_patient_ccdev GROUP BY patient_id HAVING count(patient_id)>=1) b ON a.patient_id = b.patient_id WHERE patient_gender='M' AND date_format(date_registered, '%Y-%m-%d') BETWEEN '$newSDate' AND '$newEDate'";//CCDEV
					$selectSql16_b = "SELECT count(a.patient_id) FROM `m_patient` a JOIN (SELECT * FROM m_patient_ccdev GROUP BY patient_id HAVING count(patient_id)>=1) b ON a.patient_id = b.patient_id WHERE patient_gender='F' AND date_format(date_registered, '%Y-%m-%d') BETWEEN '$newSDate' AND '$newEDate'";//CCDEV
					$selectSql16_tot = "SELECT count(a.patient_id) FROM `m_patient` a JOIN (SELECT * FROM m_patient_ccdev GROUP BY patient_id HAVING count(patient_id)>=1) b ON a.patient_id = b.patient_id WHERE date_format(date_registered, '%Y-%m-%d') BETWEEN '$newSDate' AND '$newEDate'";//CCDEV

					$selectSql16_1 = "SELECT count(id) FROM (SELECT patient_id AS pID, consult_id AS id, actual_vaccine_date AS ccdevDate FROM m_consult_ccdev_vaccine UNION ALL SELECT patient_id AS pID, consult_id AS id, ccdev_service_date AS ccdevDate FROM m_consult_ccdev_services) AS ccdev JOIN m_patient a ON ccdev.pID=a.patient_id WHERE patient_gender='M' AND date_format(ccdevDate, '%Y-%m-%d') BETWEEN '$newSDate' AND '$newEDate'";//CCDEV
					$selectSql16_2 = "SELECT count(id) FROM (SELECT patient_id AS pID, consult_id AS id, actual_vaccine_date AS ccdevDate FROM m_consult_ccdev_vaccine UNION ALL SELECT patient_id AS pID, consult_id AS id, ccdev_service_date AS ccdevDate FROM m_consult_ccdev_services) AS ccdev JOIN m_patient a ON ccdev.pID=a.patient_id WHERE patient_gender='F' AND date_format(ccdevDate, '%Y-%m-%d') BETWEEN '$newSDate' AND '$newEDate'";//CCDEV
													
					$selectSql16 = "SELECT count(id) FROM (SELECT patient_id AS pID, consult_id AS id, actual_vaccine_date AS ccdevDate FROM m_consult_ccdev_vaccine UNION ALL SELECT patient_id AS pID, consult_id AS id, ccdev_service_date AS ccdevDate FROM m_consult_ccdev_services) AS ccdev JOIN m_patient a ON ccdev.pID=a.patient_id WHERE date_format(ccdevDate, '%Y-%m-%d') BETWEEN '$newSDate' AND '$newEDate'";//CCDEV
					$selectSql17 = "SELECT count(consult_id) FROM `m_patient_fp_method_service` WHERE date_format(date_service, '%Y-%m-%d') BETWEEN '$newSDate' AND '$newEDate'";
					
					$selectQuery1 = mysqli_query($dbConnect,$selectSql1);
					$selectQuery2 = mysqli_query($dbConnect,$selectSql2);
					$selectQuery3 = mysqli_query($dbConnect,$selectSql3);
					$selectQuery4 = mysqli_query($dbConnect,$selectSql4);
					$selectQuery5 = mysqli_query($dbConnect,$selectSql5);
					$selectQuery6 = mysqli_query($dbConnect,$selectSql6);
					$selectQuery7 = mysqli_query($dbConnect,$selectSql7);
					$selectQuery8 = mysqli_query($dbConnect,$selectSql8);
					$selectQuery9 = mysqli_query($dbConnect,$selectSql9);
					$selectQuery10 = mysqli_query($dbConnect,$selectSql10);
					$selectQuery11 = mysqli_query($dbConnect,$selectSql11);
					$selectQuery12 = mysqli_query($dbConnect,$selectSql12);
					$selectQuery13 = mysqli_query($dbConnect,$selectSql13);
					
					$selectQuery14_1 = mysqli_query($dbConnect,$selectSql14_1);
					$selectQuery14_2 = mysqli_query($dbConnect,$selectSql14_2);
					$selectQuery14_3 = mysqli_query($dbConnect,$selectSql14_3);
					$selectQuery14_4 = mysqli_query($dbConnect,$selectSql14_4);
					$selectQuery14 = mysqli_query($dbConnect,$selectSql14);
					
					
					$selectQuery15_a = mysqli_query($dbConnect,$selectSql15_a);
					$selectQuery15_b = mysqli_query($dbConnect,$selectSql15_b);
					$selectQuery15_tot = mysqli_query($dbConnect,$selectSql15_tot);
					$selectQuery15_1 = mysqli_query($dbConnect,$selectSql15_1);
					$selectQuery15_2 = mysqli_query($dbConnect,$selectSql15_2);
					$selectQuery15 = mysqli_query($dbConnect,$selectSql15);
					
					$selectQuery16_a = mysqli_query($dbConnect,$selectSql16_a);
					$selectQuery16_b = mysqli_query($dbConnect,$selectSql16_b);
					$selectQuery16_tot = mysqli_query($dbConnect,$selectSql16_tot);
					$selectQuery16_1 = mysqli_query($dbConnect,$selectSql16_1);
					$selectQuery16_2 = mysqli_query($dbConnect,$selectSql16_2);
										
					$selectQuery16 = mysqli_query($dbConnect,$selectSql16);
					$selectQuery17 = mysqli_query($dbConnect,$selectSql17);
					
					$row1 = mysqli_fetch_array($selectQuery1);
					$row2 = mysqli_fetch_array($selectQuery2);
					$row3 = mysqli_fetch_array($selectQuery3);
					$row4 = mysqli_fetch_array($selectQuery4);
					$row5 = mysqli_fetch_array($selectQuery5);
					$row6 = mysqli_fetch_array($selectQuery6);
					$row7 = mysqli_fetch_array($selectQuery7);
					$row8 = mysqli_fetch_array($selectQuery8);
					$row9 = mysqli_fetch_array($selectQuery9);
					$row10 = mysqli_fetch_array($selectQuery10);
					$row11 = mysqli_fetch_array($selectQuery11);
					$row12 = mysqli_fetch_array($selectQuery12);
					$row13 = mysqli_fetch_array($selectQuery13);
					
					$row14_1 = mysqli_fetch_array($selectQuery14_1);
					$row14_2 = mysqli_fetch_array($selectQuery14_2);
					$row14_3 = mysqli_fetch_array($selectQuery14_3);
					$row14_4 = mysqli_fetch_array($selectQuery14_4);
					$row14 = mysqli_fetch_array($selectQuery14);
					
					$row15_a = mysqli_fetch_array($selectQuery15_a);
					$row15_b = mysqli_fetch_array($selectQuery15_b);
					$row15_tot = mysqli_fetch_array($selectQuery15_tot);
					$row15_1 = mysqli_fetch_array($selectQuery15_1);
					$row15_2 = mysqli_fetch_array($selectQuery15_2);
					$row15 = mysqli_fetch_array($selectQuery15);
					
					$row16_a = mysqli_fetch_array($selectQuery16_a);
					$row16_b = mysqli_fetch_array($selectQuery16_b);
					$row16_tot = mysqli_fetch_array($selectQuery16_tot);
					$row16_1 = mysqli_fetch_array($selectQuery16_1);
					$row16_2 = mysqli_fetch_array($selectQuery16_2);
									
					$row16 = mysqli_fetch_array($selectQuery16);
					$row17 = mysqli_fetch_array($selectQuery17);
					
					//percentage computation on Patients
					if ($row1['count(patient_id)']!=0 && $row2['count(patient_id)']!=0)
					{
						$lt18Male = number_format(($row1['count(patient_id)']/($row1['count(patient_id)']+$row2['count(patient_id)']))*100,2);
						$lt18Female = number_format(($row2['count(patient_id)']/($row1['count(patient_id)']+$row2['count(patient_id)']))*100,2);
					}
					else 
					{
						$lt18Male = number_format((0)*100,2);
						$lt18Female = number_format((0)*100,2);
					}
					
					if ($row3['count(patient_id)']!=0 && $row4['count(patient_id)']!=0)
					{
						$gt18Male = number_format(($row3['count(patient_id)']/($row3['count(patient_id)']+$row4['count(patient_id)']))*100,2);
						$gt18Female = number_format(($row4['count(patient_id)']/($row3['count(patient_id)']+$row4['count(patient_id)']))*100,2);
					}
					else
					{
						$gt18Male = number_format((0)*100,2);
						$gt18Female = number_format((0)*100,2);
					}
					
					$totalMale = $row1['count(patient_id)'] + $row3['count(patient_id)'];
					$totalFemale = $row2['count(patient_id)'] + $row4['count(patient_id)'];
					
					if ($totalMale!=0 && $totalFemale!=0)
					{
						$percentTotalMale = number_format((($row1['count(patient_id)']+$row3['count(patient_id)'])/($totalMale+$totalFemale))*100,2);
						$percentTotalFemale = number_format((($row2['count(patient_id)']+$row4['count(patient_id)'])/($totalMale+$totalFemale))*100,2);
					}
					else
					{
						$percentTotalMale = number_format((0)*100,2);
						$percentTotalFemale = number_format((0)*100,2);
					}
					
					$TotalMFlt18 = $row1['count(patient_id)'] + $row2['count(patient_id)'];
					$TotalMFgt18 = $row3['count(patient_id)'] + $row4['count(patient_id)'];
					
					if ($TotalMFlt18!=0 && $TotalMFgt18!=0)
					{
						$percentTotalMFlt18 = number_format((($row1['count(patient_id)']+$row2['count(patient_id)'])/($TotalMFlt18+$TotalMFgt18))*100,2);
						$percentTotalMFgt18 = number_format((($row3['count(patient_id)']+$row4['count(patient_id)'])/($TotalMFlt18+$TotalMFgt18))*100,2);
					}
					else 
					{
						$percentTotalMFlt18 = number_format((0)*100,2);
						$percentTotalMFgt18 = number_format((0)*100,2);
					}
					
					$grandTotal = $TotalMFlt18 + $TotalMFgt18;
					
					//Consult
					//percentage computation
					if ($row6['count(consult_id)']!=0 && $row7['count(consult_id)']!=0)
					{
						$Conlt18Male = number_format(($row6['count(consult_id)']/($row6['count(consult_id)']+$row7['count(consult_id)']))*100,2);
						$Conlt18Female = number_format(($row7['count(consult_id)']/($row6['count(consult_id)']+$row7['count(consult_id)']))*100,2);
					}
					else
					{
						$Conlt18Male = number_format((0)*100,2);
						$Conlt18Female = number_format((0)*100,2);
					}
						
					if ($row8['count(consult_id)']!=0 && $row9['count(consult_id)']!=0)
					{
						$Congt18Male = number_format(($row8['count(consult_id)']/($row8['count(consult_id)']+$row9['count(consult_id)']))*100,2);
						$Congt18Female = number_format(($row9['count(consult_id)']/($row8['count(consult_id)']+$row9['count(consult_id)']))*100,2);
					}
					else
					{
						$Congt18Male = number_format((0)*100,2);
						$Congt18Female = number_format((0)*100,2);
					}
						
					$ContotalMale = $row6['count(consult_id)'] + $row8['count(consult_id)'];
					$ContotalFemale = $row7['count(consult_id)'] + $row9['count(consult_id)'];
						
					if ($ContotalMale!=0 && $ContotalFemale!=0)
					{
						$ConpercentTotalMale = number_format((($row6['count(consult_id)']+$row8['count(consult_id)'])/($ContotalMale+$ContotalFemale))*100,2);
						$ConpercentTotalFemale = number_format((($row7['count(consult_id)']+$row9['count(consult_id)'])/($ContotalMale+$ContotalFemale))*100,2);
					}
					else
					{
						$ConpercentTotalMale = number_format((0)*100,2);
						$ConpercentTotalFemale = number_format((0)*100,2);
					}
						
					$ConTotalMFlt18 = $row6['count(consult_id)'] + $row7['count(consult_id)'];
					$ConTotalMFgt18 = $row8['count(consult_id)'] + $row9['count(consult_id)'];
						
					if ($ConTotalMFlt18!=0 && $ConTotalMFgt18!=0)
					{
						$ConpercentTotalMFlt18 = number_format((($row6['count(consult_id)']+$row7['count(consult_id)'])/($ConTotalMFlt18+$ConTotalMFgt18))*100,2);
						$ConpercentTotalMFgt18 = number_format((($row8['count(consult_id)']+$row9['count(consult_id)'])/($ConTotalMFlt18+$ConTotalMFgt18))*100,2);
					}
					else
					{
						$ConpercentTotalMFlt18 = number_format((0)*100,2);
						$ConpercentTotalMFgt18 = number_format((0)*100,2);
					}
						
					$CongrandTotal = $ConTotalMFlt18 + $ConTotalMFgt18;
					//$CongrandTotal = $row10['count(consult_id)'];
					
					
					//Notes Consult
					//percentage computation
					if ($row14_1['count(notes_id)']!=0 && $row14_2['count(notes_id)']!=0)
					{
						$Noteslt18Male = number_format(($row14_1['count(notes_id)']/($row14_1['count(notes_id)']+$row14_2['count(notes_id)']))*100,2);
						$Noteslt18Female = number_format(($row14_2['count(notes_id)']/($row14_1['count(notes_id)']+$row14_2['count(notes_id)']))*100,2);
					}
					else
					{
						$Noteslt18Male = number_format((0)*100,2);
						$Notest18Female = number_format((0)*100,2);
					}
					
					if ($row14_3['count(notes_id)']!=0 && $row14_4['count(notes_id)']!=0)
					{
						$Notesgt18Male = number_format(($row14_3['count(notes_id)']/($row14_3['count(notes_id)']+$row14_4['count(notes_id)']))*100,2);
						$Notesgt18Female = number_format(($row14_4['count(notes_id)']/($row14_3['count(notes_id)']+$row14_4['count(notes_id)']))*100,2);
					}
					else
					{
						$Notesgt18Male = number_format((0)*100,2);
						$Notesgt18Female = number_format((0)*100,2);
					}
					
					$NotestotalMale = $row14_1['count(notes_id)'] + $row14_3['count(notes_id)'];
					$NotestotalFemale = $row14_2['count(notes_id)'] + $row14_4['count(notes_id)'];
					
					if ($NotestotalMale!=0 && $NotestotalFemale!=0)
					{
						$NotespercentTotalMale = number_format((($row14_1['count(notes_id)']+$row14_3['count(notes_id)'])/($NotestotalMale+$NotestotalFemale))*100,2);
						$NotespercentTotalFemale = number_format((($row14_2['count(notes_id)']+$row14_4['count(notes_id)'])/($NotestotalMale+$NotestotalFemale))*100,2);
					}
					else
					{
						$NotespercentTotalMale = number_format((0)*100,2);
						$NotespercentTotalFemale = number_format((0)*100,2);
					}
					
					$NotesTotalMFlt18 = $row14_1['count(notes_id)'] + $row14_2['count(notes_id)'];
					$NotesTotalMFgt18 = $row14_3['count(notes_id)'] + $row14_4['count(notes_id)'];
					
					if ($NotesTotalMFlt18!=0 && $NotesTotalMFgt18!=0)
					{
						$NotespercentTotalMFlt18 = number_format((($row14_1['count(notes_id)']+$row14_2['count(notes_id)'])/($NotesTotalMFlt18+$NotesTotalMFgt18))*100,2);
						$NotespercentTotalMFgt18 = number_format((($row14_3['count(notes_id)']+$row14_4['count(notes_id)'])/($NotesTotalMFlt18+$NotesTotalMFgt18))*100,2);
					}
					else
					{
						$NotespercentTotalMFlt18 = number_format((0)*100,2);
						$NotespercentTotalMFgt18 = number_format((0)*100,2);
					}
					
					$NotesgrandTotal = $NotesTotalMFlt18 + $NotesTotalMFgt18;
					//$NotesgrandTotal = $row14['count(notes_id)'];
					
					//MC Patients
					$MCPlt18 = $row15_a['count(a.patient_id)'];
					$MCPgt18 = $row15_b['count(a.patient_id)'];
					if ($MCPlt18!=0 && $MCPgt18!=0)
					{
						$MCPpercentTotallt18 = number_format(($MCPlt18/($MCPlt18+$MCPgt18))*100,2);
						$MCPpercentTotalgt18 = number_format(($MCPgt18/($MCPlt18+$MCPgt18))*100,2);
					}
					else
					{
						$MCPpercentTotallt18 = number_format((0)*100,2);
						$MCPpercentTotalgt18 = number_format((0)*100,2);
					}
						
					
					//MC Consult
					$MClt18 = $row15_1['count(id)'];
					$MCgt18 = $row15_2['count(id)'];
					if ($MClt18!=0 && $MCgt18!=0)
					{
						$MCpercentTotallt18 = number_format(($MClt18/($MClt18+$MCgt18))*100,2);
						$MCpercentTotalgt18 = number_format(($MCgt18/($MClt18+$MCgt18))*100,2);
					}
					else
					{
						$MCpercentTotallt18 = number_format((0)*100,2);
						$MCpercentTotalgt18 = number_format((0)*100,2);
					}
					$MCageTotal = $MClt18 + $MCgt18;
					
					//CCDEV Patients
					$CCDEVPMale = $row16_a['count(a.patient_id)'];
					$CCDEVPFemale = $row16_b['count(a.patient_id)'];
				
					if ($CCDEVPMale!=0 && $CCDEVPFemale!=0)
					{
						$CCDEVPpercentTotalMale = number_format(($CCDEVPMale/($CCDEVPMale+$CCDEVPFemale))*100,2);
						$CCDEVPpercentTotalFemale = number_format(($CCDEVPFemale/($CCDEVPMale+$CCDEVPFemale))*100,2);
					}
					else
					{
						$CCDEVPpercentTotalMale = number_format((0)*100,2);
						$CCDEVPpercentTotalFemale = number_format((0)*100,2);
					}
					
					//CCDEV Consult
					$CCDEVMale = $row16_1['count(id)'];
					$CCDEVFemale = $row16_2['count(id)'];
					if ($CCDEVMale!=0 && $CCDEVFemale!=0)
					{
						$CCDEVpercentTotalMale = number_format(($CCDEVMale/($CCDEVMale+$CCDEVFemale))*100,2);
						$CCDEVpercentTotalFemale = number_format(($CCDEVFemale/($CCDEVMale+$CCDEVFemale))*100,2);
					}
					else
					{
						$CCDEVpercentTotalMale = number_format((0)*100,2);
						$CCDEVpercentTotalFemale = number_format((0)*100,2);
					}
					
					$countPlt18M = $countPlt18M + $row1['count(patient_id)'];
					$countPlt18F = $countPlt18F + $row2['count(patient_id)'];
					$countPgt18M = $countPgt18M + $row3['count(patient_id)'];
					$countPgt18F = $countPgt18F + $row4['count(patient_id)'];
					
					$countTotPGMale = $countTotPGMale + $totalMale;
					$countTotPGFemale = $countTotPGFemale + $totalFemale;
					$countTotPAMale = $countTotPAMale + $TotalMFlt18;
					$countTotPAFemale = $countTotPAFemale + $TotalMFgt18;
					
					$countGrandTotP = $countGrandTotP + $grandTotal;
					
					$countHouseHolds = $countHouseHolds + $row5['count(family_id)'];
					
					$countClt18M = $countClt18M + $row6['count(consult_id)'];
					$countClt18F = $countClt18F + $row7['count(consult_id)'];
					$countCgt18M = $countCgt18M + $row8['count(consult_id)'];
					$countCgt18F = $countCgt18F + $row9['count(consult_id)'];
					
					$countTotCGMale = $countTotCGMale + $ContotalMale;
					$countTotCGFemale = $countTotCGFemale + $ContotalFemale;
					$countTotCAMale = $countTotCAMale + $ConTotalMFlt18;
					$countTotCAFemale = $countTotCAFemale + $ConTotalMFgt18;
				
					$countGrandTotC = $countGrandTotC + $CongrandTotal;
					
					$countEndUser = $countEndUser + $row11['count(user_id)'];
					
					$countPatientNoFF = $countPatientNoFF + $row12['count(patient_id)'];
					$countFFNoMember = $countFFNoMember + $row13['count(family_id)'];
					
					$countNlt18M = $countNlt18M + $row14_1['count(notes_id)'];
					$countNlt18F = $countNlt18F + $row14_2['count(notes_id)'];
					$countNgt18M = $countNgt18M + $row14_3['count(notes_id)'];
					$countNgt18F = $countNgt18F + $row14_4['count(notes_id)'];
						
					$countTotNGMale = $countTotNGMale + $NotestotalMale;
					$countTotNGFemale = $countTotNGFemale + $NotestotalFemale;
					$countTotNAMale = $countTotNAMale + $NotesTotalMFlt18;
					$countTotNAFemale = $countTotNAFemale + $NotesTotalMFgt18;
					
					//$countGrandTotC = $countGrandTotC + $CongrandTotal;
					//$countNConsults = $countNConsults + $row14['count(notes_id)'];
					$countNConsults = $countNConsults + $NotesgrandTotal;
					
					$countMCPlt18 = $countMCPlt18 + $MCPlt18;
					$countMCPgt18 = $countMCPgt18 + $MCPgt18;
					$countMCPtot = $countMCPtot + $row15_tot['count(a.patient_id)'];
					$countMClt18 = $countMClt18 + $MClt18;
					$countMCgt18 = $countMCgt18 + $MCgt18;
					//$countMCConsult = $countMCConsult + $row15['count(id)'];
					$countMCConsult = $countMClt18 + $countMCgt18;
					
					$countCCDEVPMale = $countCCDEVPMale + $CCDEVPMale;
					$countCCDEVPFemale = $countCCDEVPFemale + $CCDEVPFemale;
					$countCCDEVPtot = $countCCDEVPtot + $row16_tot['count(a.patient_id)'];
					$countCCDEVMale = $countCCDEVMale + $CCDEVMale;
					$countCCDEVFemale = $countCCDEVFemale + $CCDEVFemale;
					$countCCDEVConsult = $countCCDEVConsult + $row16['count(id)'];
					$countFPConsult = $countFPConsult + $row17['count(consult_id)'];
					
					echo "<tr>
							<td bgcolor='#FFFF00'>$key</td>
							<td align='right'>".number_format($row1['count(patient_id)'])."</td>
							<td bgcolor='#BDBDBD' align='center'>".$lt18Male."%</td>
							<td align='right'>".number_format($row2['count(patient_id)'])."</td>
							<td bgcolor='#BDBDBD' align='center'>".$lt18Female."%</td>
							<td align='right'>".number_format($row3['count(patient_id)'])."</td>
							<td bgcolor='#BDBDBD' align='center'>".$gt18Male."%</td>
							<td align='right'>".number_format($row4['count(patient_id)'])."</td>
							<td bgcolor='#BDBDBD' align='center'>".$gt18Female."%</td>
							<td align='right'>".number_format($totalMale)."</td>
							<td bgcolor='#BDBDBD' align='center'>".$percentTotalMale."%</td>
							<td align='right'>".number_format($totalFemale)."</td>
							<td bgcolor='#BDBDBD' align='center'>".$percentTotalFemale."%</td>
							<td align='right'>".number_format($TotalMFlt18)."</td>
							<td bgcolor='#BDBDBD' align='center'>".$percentTotalMFlt18."%</td>
							<td align='right'>".number_format($TotalMFgt18)."</td>
							<td bgcolor='#BDBDBD' align='center'>".$percentTotalMFgt18."%</td>
							<td bgcolor='#FFFF00' align='right'>".number_format($grandTotal)."</td>
							<td bgcolor='#FFFF00' align='right'>".number_format($row5['count(family_id)'])."</td>
							<td align='right'>".number_format($row6['count(consult_id)'])."</td>
							<td bgcolor='#BDBDBD' align='center'>".$Conlt18Male."%</td>
							<td align='right'>".number_format($row7['count(consult_id)'])."</td>
							<td bgcolor='#BDBDBD' align='center'>".$Conlt18Female."%</td>
							<td align='right'>".number_format($row8['count(consult_id)'])."</td>
							<td bgcolor='#BDBDBD' align='center'>".$Congt18Male."%</td>
							<td align='right'>".number_format($row9['count(consult_id)'])."</td>
							<td bgcolor='#BDBDBD' align='center'>".$Congt18Female."%</td>
							<td align='right'>".number_format($ContotalMale)."</td>
							<td bgcolor='#BDBDBD' align='center'>".$ConpercentTotalMale."%</td>
							<td align='right'>".number_format($ContotalFemale)."</td>
							<td bgcolor='#BDBDBD' align='center'>".$ConpercentTotalFemale."%</td>
							<td align='right'>".number_format($ConTotalMFlt18)."</td>
							<td bgcolor='#BDBDBD' align='center'>".$ConpercentTotalMFlt18."%</td>
							<td align='right'>".number_format($ConTotalMFgt18)."</td>
							<td bgcolor='#BDBDBD' align='center'>".$ConpercentTotalMFgt18."%</td>
							<td bgcolor='#FFFF00' align='right'>".number_format($CongrandTotal)."</td>
							<td bgcolor='#FFFF00' align='right'>".number_format($row11['count(user_id)'])."</td>
							<td bgcolor='#FFFF00' align='right'>".number_format($row12['count(patient_id)'])."</td>
							<td bgcolor='#FFFF00' align='right'>".number_format($row13['count(family_id)'])."</td>
							<td align='right'>".number_format($row14_1['count(notes_id)'])."</td>
							<td bgcolor='#BDBDBD' align='center'>".$Noteslt18Male."%</td>
							<td align='right'>".number_format($row14_2['count(notes_id)'])."</td>
							<td bgcolor='#BDBDBD' align='center'>".$Noteslt18Female."%</td>
							<td align='right'>".number_format($row14_3['count(notes_id)'])."</td>
							<td bgcolor='#BDBDBD' align='center'>".$Notesgt18Male."%</td>
							<td align='right'>".number_format($row14_4['count(notes_id)'])."</td>
							<td bgcolor='#BDBDBD' align='center'>".$Notesgt18Female."%</td>
							<td align='right'>".number_format($NotestotalMale)."</td>
							<td bgcolor='#BDBDBD' align='center'>".$NotespercentTotalMale."%</td>
							<td align='right'>".number_format($NotestotalFemale)."</td>
							<td bgcolor='#BDBDBD' align='center'>".$NotespercentTotalFemale."%</td>
							<td align='right'>".number_format($NotesTotalMFlt18)."</td>
							<td bgcolor='#BDBDBD' align='center'>".$NotespercentTotalMFlt18."%</td>
							<td align='right'>".number_format($NotesTotalMFgt18)."</td>
							<td bgcolor='#BDBDBD' align='center'>".$NotespercentTotalMFgt18."%</td>
							<td bgcolor='#FFFF00' align='right'>".number_format($NotesgrandTotal)."</td>
							<td align='right'>".number_format($MCPlt18)."</td>
							<td bgcolor='#BDBDBD' align='center'>".$MCPpercentTotallt18."%</td>
							<td align='right'>".number_format($MCPgt18)."</td>
							<td bgcolor='#BDBDBD' align='center'>".$MCPpercentTotalgt18."%</td>
							<td bgcolor='#FFFF00' align='right'>".number_format($row15_tot['count(a.patient_id)'])."</td>
							<td align='right'>".number_format($MClt18)."</td>
							<td bgcolor='#BDBDBD' align='center'>".$MCpercentTotallt18."%</td>
							<td align='right'>".number_format($MCgt18)."</td>
							<td bgcolor='#BDBDBD' align='center'>".$MCpercentTotalgt18."%</td>
							<td bgcolor='#FFFF00' align='right'>".number_format($MCageTotal)."</td>
							<td align='right'>".number_format($CCDEVPMale)."</td>
							<td bgcolor='#BDBDBD' align='center'>".$CCDEVPpercentTotalMale."%</td>
							<td align='right'>".number_format($CCDEVPFemale)."</td>
							<td bgcolor='#BDBDBD' align='center'>".$CCDEVPpercentTotalFemale."%</td>
							<td bgcolor='#FFFF00' align='right'>".number_format($row16_tot['count(a.patient_id)'])."</td>
							<td align='right'>".number_format($CCDEVMale)."</td>
							<td bgcolor='#BDBDBD' align='center'>".$CCDEVpercentTotalMale."%</td>
							<td align='right'>".number_format($CCDEVFemale)."</td>
							<td bgcolor='#BDBDBD' align='center'>".$CCDEVpercentTotalFemale."%</td>
							<td bgcolor='#FFFF00' align='right'>".number_format($row16['count(id)'])."</td>
							<td bgcolor='#FFFF00' align='right'>".number_format($row17['count(consult_id)'])."</td>
						<tr>";		
				
					
				} 
				
				if ($countPlt18M!=0 && $countPlt18F!=0)
				{
					$percentTotPMlt18 = number_format(($countPlt18M / ($countPlt18M + $countPlt18F))*100,2);
					$percentTotPFlt18 = number_format(($countPlt18F / ($countPlt18M + $countPlt18F))*100,2);
				}
				else
				{
					$percentTotPMlt18 = number_format((0)*100,2);
					$percentTotPFlt18 = number_format((0)*100,2);
				}
				
				if ($countPgt18M!=0 && $countPgt18F!=0)
				{
					$percentTotPMgt18 = number_format(($countPgt18M / ($countPgt18M + $countPgt18F))*100,2);
					$percentTotPFgt18 = number_format(($countPgt18F / ($countPgt18M + $countPgt18F))*100,2);
				}
				else
				{
					$percentTotPMgt18 = number_format((0)*100,2);
					$percentTotPFgt18 = number_format((0)*100,2);
				}
				
				if ($countTotPGMale!=0 && $countTotPGFemale!=0)
				{
					$percentTotPGMale = number_format(($countTotPGMale / ($countTotPGMale + $countTotPGFemale))*100,2);
					$percentTotPGFemale = number_format(($countTotPGFemale / ($countTotPGMale + $countTotPGFemale))*100,2);
				}
				else
				{
					$percentTotPGMale = number_format((0)*100,2);
					$percentTotPGFemale = number_format((0)*100,2);
				}
				
				if ($countTotPAMale!=0 && $countTotPAFemale!=0)
				{
					$percentTotPAMale = number_format(($countTotPAMale / ($countTotPAMale + $countTotPAFemale))*100,2);
					$percentTotPAFemale = number_format(($countTotPAFemale / ($countTotPAMale + $countTotPAFemale))*100,2);
				}
				else
				{
					$percentTotPAMale = number_format((0)*100,2);
					$percentTotPAFemale = number_format((0)*100,2);
				}
								
				//COUNSULT COMPUTATION OF PERCENTAGE
				if ($countClt18M!=0 && $countClt18F!=0)
				{
					$percentTotCMlt18 = number_format(($countClt18M / ($countClt18M + $countClt18F))*100,2);
					$percentTotCFlt18 = number_format(($countClt18F / ($countClt18M + $countClt18F))*100,2);
				}
				else
				{
					$percentTotCMlt18 = number_format((0)*100,2);
					$percentTotCFlt18 = number_format((0)*100,2);
				}
				
				if ($countCgt18M!=0 && $countCgt18F!=0)
				{
					$percentTotCMgt18 = number_format(($countCgt18M / ($countCgt18M + $countCgt18F))*100,2);
					$percentTotCFgt18 = number_format(($countCgt18F / ($countCgt18M + $countCgt18F))*100,2);
				}
				else
				{
					$percentTotCMgt18 = number_format((0)*100,2);
					$percentTotCFgt18 = number_format((0)*100,2);
				}
				
				if ($countTotCGMale!=0 && $countTotCGFemale!=0)
				{
					$percentTotCGMale = number_format(($countTotCGMale / ($countTotCGMale + $countTotCGFemale))*100,2);
					$percentTotCGFemale = number_format(($countTotCGFemale / ($countTotCGMale + $countTotCGFemale))*100,2);
				}
				else
				{
					$percentTotCGMale = number_format((0)*100,2);
					$percentTotCGFemale = number_format((0)*100,2);
				}
				
				if ($countTotCAMale!=0 && $countTotCAFemale!=0)
				{
					$percentTotCAMale = number_format(($countTotCAMale / ($countTotCAMale + $countTotCAFemale))*100,2);
					$percentTotCAFemale = number_format(($countTotCAFemale / ($countTotCAMale + $countTotCAFemale))*100,2);
				}
				else
				{
					$percentTotCAMale = number_format((0)*100,2);
					$percentTotCAFemale = number_format((0)*100,2);
				}
				
				//NOTES COUNSULT COMPUTATION OF PERCENTAGE
				if ($countNlt18M!=0 && $countNlt18F!=0)
				{
					$percentTotNMlt18 = number_format(($countNlt18M / ($countNlt18M + $countNlt18F))*100,2);
					$percentTotNFlt18 = number_format(($countNlt18F / ($countNlt18M + $countNlt18F))*100,2);
				}
				else
				{
					$percentTotNMlt18 = number_format((0)*100,2);
					$percentTotNFlt18 = number_format((0)*100,2);
				}
				
				if ($countNgt18M!=0 && $countNgt18F!=0)
				{
					$percentTotNMgt18 = number_format(($countNgt18M / ($countNgt18M + $countNgt18F))*100,2);
					$percentTotNFgt18 = number_format(($countNgt18F / ($countNgt18M + $countNgt18F))*100,2);
				}
				else
				{
					$percentTotNMgt18 = number_format((0)*100,2);
					$percentTotNFgt18 = number_format((0)*100,2);
				}
				
				if ($countTotNGMale!=0 && $countTotNGFemale!=0)
				{
					$percentTotNGMale = number_format(($countTotNGMale / ($countTotNGMale + $countTotNGFemale))*100,2);
					$percentTotNGFemale = number_format(($countTotNGFemale / ($countTotNGMale + $countTotNGFemale))*100,2);
				}
				else
				{
					$percentTotNGMale = number_format((0)*100,2);
					$percentTotNGFemale = number_format((0)*100,2);
				}
				
				if ($countTotNAMale!=0 && $countTotNAFemale!=0)
				{
					$percentTotNAMale = number_format(($countTotNAMale / ($countTotNAMale + $countTotNAFemale))*100,2);
					$percentTotNAFemale = number_format(($countTotNAFemale / ($countTotNAMale + $countTotNAFemale))*100,2);
				}
				else
				{
					$percentTotNAMale = number_format((0)*100,2);
					$percentTotNAFemale = number_format((0)*100,2);
				}
				
				//MC Patient
				if ($countMCPlt18!=0 && $countMCPgt18!=0)
				{
					$percentTotMCPlt18 = number_format(($countMCPlt18 / ($countMCPlt18 + $countMCPgt18))*100,2);
					$percentTotMCPgt18 = number_format(($countMCPgt18 / ($countMCPlt18 + $countMCPgt18))*100,2);
				}
				else
				{
					$percentTotMCPlt18 = number_format((0)*100,2);
					$percentTotMCPgt18 = number_format((0)*100,2);
				}
				
				//MC Consult
				if ($countMClt18!=0 && $countMCgt18!=0)
				{
					$percentTotMClt18 = number_format(($countMClt18 / ($countMClt18 + $countMCgt18))*100,2);
					$percentTotMCgt18 = number_format(($countMCgt18 / ($countMClt18 + $countMCgt18))*100,2);
				}
				else
				{
					$percentTotMClt18 = number_format((0)*100,2);
					$percentTotMCgt18 = number_format((0)*100,2);
				}
				
				//CCDEV patient
				if ($countCCDEVPMale!=0 && $countCCDEVPFemale!=0)
				{
					$percentTotCCDEVPMale = number_format(($countCCDEVPMale / ($countCCDEVPMale + $countCCDEVPFemale))*100,2);
					$percentTotCCDEVPFemale = number_format(($countCCDEVPFemale / ($countCCDEVPMale + $countCCDEVPFemale))*100,2);
				}
				else
				{
					$percentTotCCDEVPMale = number_format((0)*100,2);
					$percentTotCCDEVPFemale = number_format((0)*100,2);
				}
				
				//CCDEV Consult
				if ($countCCDEVMale!=0 && $countCCDEVFemale!=0)
				{
					$percentTotCCDEVMale = number_format(($countCCDEVMale / ($countCCDEVMale + $countCCDEVFemale))*100,2);
					$percentTotCCDEVFemale = number_format(($countCCDEVFemale / ($countCCDEVMale + $countCCDEVFemale))*100,2);
				}
				else
				{
					$percentTotCCDEVMale = number_format((0)*100,2);
					$percentTotCCDEVFemale = number_format((0)*100,2);
				}
				
				echo "<tr class='bold'>
						<td bgcolor='#FF8C10'>TOTAL</td>
						<td bgcolor='#43E1E6' align='right'>".number_format($countPlt18M)."</td>
						<td bgcolor='#3ADF00' align='center'>".$percentTotPMlt18." %</td>
						<td bgcolor='#43E1E6' align='right'>".number_format($countPlt18F)."</td>
						<td bgcolor='#3ADF00' align='center'>".$percentTotPFlt18." %</td>
						<td bgcolor='#43E1E6' align='right'>".number_format($countPgt18M)."</td>
						<td bgcolor='#3ADF00' align='center'>".$percentTotPMgt18." %</td>
						<td bgcolor='#43E1E6' align='right'>".number_format($countPgt18F)."</td>
						<td bgcolor='#3ADF00' align='center'>".$percentTotPFgt18." %</td>
						<td bgcolor='#43E1E6' align='right'>".number_format($countTotPGMale)."</td>
						<td bgcolor='#3ADF00' align='center'>".$percentTotPGMale." %</td>
						<td bgcolor='#43E1E6' align='right'>".number_format($countTotPGFemale)."</td>
						<td bgcolor='#3ADF00' align='center'>".$percentTotPGFemale." %</td>
						<td bgcolor='#43E1E6' align='right'>".number_format($countTotPAMale)."</td>
						<td bgcolor='#3ADF00' align='center'>".$percentTotPAMale." %</td>
						<td bgcolor='#43E1E6' align='right'>".number_format($countTotPAFemale)."</td>
						<td bgcolor='#3ADF00' align='center'>".$percentTotPAFemale." %</td>
						<td bgcolor='#FF8C10' align='right'>".number_format($countGrandTotP)."</td>
						<td bgcolor='#FF8C10' align='right'>".number_format($countHouseHolds)."</td>
						<td bgcolor='#43E1E6' align='right'>".number_format($countClt18M)."</td>
						<td bgcolor='#3ADF00' align='center'>".$percentTotCMlt18." %</td>
						<td bgcolor='#43E1E6' align='right'>".number_format($countClt18F)."</td>
						<td bgcolor='#3ADF00' align='center'>".$percentTotCFlt18." %</td>
						<td bgcolor='#43E1E6' align='right'>".number_format($countCgt18M)."</td>
						<td bgcolor='#3ADF00' align='center'>".$percentTotCMgt18." %</td>
						<td bgcolor='#43E1E6' align='right'>".number_format($countCgt18F)."</td>
						<td bgcolor='#3ADF00' align='center'>".$percentTotCFgt18." %</td>
						<td bgcolor='#43E1E6' align='right'>".number_format($countTotCGMale)."</td>
						<td bgcolor='#3ADF00' align='center'>".$percentTotCGMale." %</td>
						<td bgcolor='#43E1E6' align='right'>".number_format($countTotCGFemale)."</td>
						<td bgcolor='#3ADF00' align='center'>".$percentTotCGFemale." %</td>
						<td bgcolor='#43E1E6' align='right'>".number_format($countTotCAMale)."</td>
						<td bgcolor='#3ADF00' align='center'>".$percentTotCAMale." %</td>
						<td bgcolor='#43E1E6' align='right'>".number_format($countTotCAFemale)."</td>
						<td bgcolor='#3ADF00' align='center'>".$percentTotCAFemale." %</td>
						<td bgcolor='#FF8C10' align='right'>".number_format($countGrandTotC)."</td>
						<td bgcolor='#FF8C10' align='right'>".number_format($countEndUser)."</td>
						<td bgcolor='#FF8C10' align='right'>".number_format($countPatientNoFF)."</td>
						<td bgcolor='#FF8C10' align='right'>".number_format($countFFNoMember)."</td>
						<td bgcolor='#43E1E6' align='right'>".number_format($countNlt18M)."</td>
						<td bgcolor='#3ADF00' align='center'>".$percentTotNMlt18." %</td>
						<td bgcolor='#43E1E6' align='right'>".number_format($countNlt18F)."</td>
						<td bgcolor='#3ADF00' align='center'>".$percentTotNFlt18." %</td>
						<td bgcolor='#43E1E6' align='right'>".number_format($countNgt18M)."</td>
						<td bgcolor='#3ADF00' align='center'>".$percentTotNMgt18." %</td>
						<td bgcolor='#43E1E6' align='right'>".number_format($countNgt18F)."</td>
						<td bgcolor='#3ADF00' align='center'>".$percentTotNFgt18." %</td>
						<td bgcolor='#43E1E6' align='right'>".number_format($countTotNGMale)."</td>
						<td bgcolor='#3ADF00' align='center'>".$percentTotNGMale." %</td>
						<td bgcolor='#43E1E6' align='right'>".number_format($countTotNGFemale)."</td>
						<td bgcolor='#3ADF00' align='center'>".$percentTotNGFemale." %</td>
						<td bgcolor='#43E1E6' align='right'>".number_format($countTotNAMale)."</td>
						<td bgcolor='#3ADF00' align='center'>".$percentTotNAMale." %</td>
						<td bgcolor='#43E1E6' align='right'>".number_format($countTotNAFemale)."</td>
						<td bgcolor='#3ADF00' align='center'>".$percentTotNAFemale." %</td>
						<td bgcolor='#FF8C10' align='right'>".number_format($countNConsults)."</td>
						<td bgcolor='#43E1E6' align='right'>".number_format($countMCPlt18)."</td>
						<td bgcolor='#3ADF00' align='center'>".$percentTotMCPlt18." %</td>
						<td bgcolor='#43E1E6' align='right'>".number_format($countMCPgt18)."</td>
						<td bgcolor='#3ADF00' align='center'>".$percentTotMCPgt18." %</td>
						<td bgcolor='#FF8C10' align='right'>".number_format($countMCPtot)."</td>
						<td bgcolor='#43E1E6' align='right'>".number_format($countMClt18)."</td>
						<td bgcolor='#3ADF00' align='center'>".$percentTotMClt18." %</td>
						<td bgcolor='#43E1E6' align='right'>".number_format($countMCgt18)."</td>
						<td bgcolor='#3ADF00' align='center'>".$percentTotMCgt18." %</td>
						<td bgcolor='#FF8C10' align='right'>".number_format($countMCConsult)."</td>
						<td bgcolor='#43E1E6' align='right'>".number_format($countCCDEVPMale)."</td>
						<td bgcolor='#3ADF00' align='center'>".$percentTotCCDEVPMale." %</td>
						<td bgcolor='#43E1E6' align='right'>".number_format($countCCDEVPFemale)."</td>
						<td bgcolor='#3ADF00' align='center'>".$percentTotCCDEVPFemale." %</td>
						<td bgcolor='#FF8C10' align='right'>".number_format($countCCDEVPtot)."</td>
						<td bgcolor='#43E1E6' align='right'>".number_format($countCCDEVMale)."</td>
						<td bgcolor='#3ADF00' align='center'>".$percentTotCCDEVMale." %</td>
						<td bgcolor='#43E1E6' align='right'>".number_format($countCCDEVFemale)."</td>
						<td bgcolor='#3ADF00' align='center'>".$percentTotCCDEVFemale." %</td>
						<td bgcolor='#FF8C10' align='right'>".number_format($countCCDEVConsult)."</td>
						<td bgcolor='#FF8C10' align='right'>".number_format($countFPConsult)."</td>
					</tr>";
				echo "</table>";
				echo "</div>";
				//print_r($_POST['tot']);
				
			}
		?>
		
		
		</div>
	</body>
	
</html>