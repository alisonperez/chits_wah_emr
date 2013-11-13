<?php
	
	error_reporting(E_ALL ^ E_NOTICE);
	header('Content-type: text/html; charset=utf-8');
	ini_set('max_execution_time', 1360);
	ini_set('memory_limit', '1024M');
	ini_set('max_input_time', 1360);
	ini_set('max_input_nesting_level', 64);
	
	session_start();
	
	require_once dirname(__FILE__) . '/Classes/PHPExcel.php';
	require_once dirname(__FILE__) . '/Classes/PHPExcel/IOFactory.php';
	
	
	
	if(!isset($_SESSION['start']))
	{
	$_SESSION['start']=1;
	$objPHPExcel = new PHPExcel();
	
	$objReader = new PHPExcel_Reader_Excel5();
	
	//$rowIterator = $objPHPExcel->getActiveSheet()->getRowIterator();
	
	
	//$objReader->setReadDataOnly(true);
	
	//$objPHPExcel = $objReader->load( dirname(__FILE__) . '/lhio.xls' );
	
	
	

	//$sheet = $objPHPExcel->setActiveSheetIndex(0);
	
	$inputFileType = 'Excel5';
	$inputFileName = 'tarlacLHIO.xls';
	$sheetname = 'nodepcount';
	
	
	class MyReadFilter implements PHPExcel_Reader_IReadFilter {
		public function readCell($column, $row, $worksheetName = '') {
			//  Read rows 1 to 7 and columns A to E only
			
			if ($row >= 1 && $row <= 65536) {
				if (in_array($column,range('A','P'))) {
					return true;
				}
			}
			return false;
		}
	}
	
	/**  Create an Instance of our Read Filter  **/
	$filterSubset = new MyReadFilter();
	
	
	/**  Create a new Reader of the type defined in $inputFileType  **/
	$objReader = PHPExcel_IOFactory::createReader($inputFileType);
	/**  Advise the Reader of which WorkSheets we want to load  **/
	$objReader->setReadDataOnly(true);
	$objReader->setLoadSheetsOnly($sheetname);
	/**  Tell the Reader that we want to use the Read Filter that we've Instantiated  **/
	$objReader->setReadFilter($filterSubset);
	/**  Load only the rows and columns that match our filter from $inputFileName to a PHPExcel Object  **/
	$objPHPExcel = $objReader->load($inputFileName);
	
	
	
	$rowIterator = $objPHPExcel->getActiveSheet()->getRowIterator();
	
	$sheet = $objPHPExcel->getActiveSheet();
	
	$_SESSION['array_data'] = array();

	
	$count=0;
	foreach($rowIterator as $row)
	{
		$rowIndex = $row->getRowIndex ();
		$_SESSION['array_data'][$rowIndex] = array('A'=>'', 'B'=>'', 'C'=>'', 'D'=>'', 'E'=>'', 'F'=>'', 'G'=>'', 'H'=>'', 'I'=>'', 'J'=>'', 'K'=>'', 'L'=>'', 'M'=>'', 'N'=>'', 'O'=>'', 'P'=>'');
		$startA='A';
		$endP='P';
		for($startA; $startA<=$endP; $startA++)
		{
			$cell = $sheet->getCell($startA . $rowIndex);
			$_SESSION['array_data'][$rowIndex][$startA] = $cell->getValue();
			
			if($startA=='G' || $startA=='O' || $startA=='P')
			{
				$cell = $sheet->getCell($startA . $rowIndex);
				$_SESSION['array_data'][$rowIndex][$startA]=PHPExcel_Style_NumberFormat::toFormattedString($cell->getValue(), 'M-DD-Y');
			}
		} 
	}
	
	
	
	//print_r($_SESSION['array_data']);
	}
?>

<html>
	<head>
		<title>Philhealth Membership Verification</title>
		<style>
			#tw-form-outer {
			
			}
			#tw-form{
			font-family: Tahoma, Geneva, sans-serif;
			-moz-border-radius: 4px;
			-webkit-border-radius: 4px;
			border: #aaa 1px solid;
			background: #DDDDDD;
			background: -moz-linear-gradient(top, #C4C4C4 0%, #EAEAEA 0%, #D3D3D3 100%); /* firefox */
			background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#C4C4C4), color-stop(0%,#EAEAEA), color-stop(100%,#D3D3D3)); /* webkit */
			filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#C4C4C4', endColorstr='#D3D3D3',GradientType=0 ); /* ie */
			width: 395px;
			float: left;
			padding: 0 4px;
			border-top-left-radius: 4px 4px;
			border-top-right-radius: 4px 4px;
			border-bottom-right-radius: 4px 4px;
			border-bottom-left-radius: 4px 4px;
			}
			#tw-form #tw-input-text{
			width: 145px;
			float: left;
			border: 0;
			background: #DDDDDD;
			background: -moz-linear-gradient(top, #C4C4C4 0%, #EAEAEA 0%, #D3D3D3 100%); /* firefox */
			background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#C4C4C4), color-stop(0%,#EAEAEA), color-stop(100%,#D3D3D3)); /* webkit */
			filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#C4C4C4', endColorstr='#D3D3D3',GradientType=0 ); /* ie */
			color: #777;
			line-height: 100%;
			font-size: 12px;
			font-family: Tahoma, Geneva, sans-serif;
			margin-top:3px;margin-bottom:3px;
			height:20px;
			}
			
			#tw-form #tw-select-text{
			width: 80px;
			float: left;
			border: 0;
			background: #DDDDDD;
			background: -moz-linear-gradient(top, #C4C4C4 0%, #EAEAEA 0%, #D3D3D3 100%); /* firefox */
			background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#C4C4C4), color-stop(0%,#EAEAEA), color-stop(100%,#D3D3D3)); /* webkit */
			filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#C4C4C4', endColorstr='#D3D3D3',GradientType=0 ); /* ie */
			color: #777;
			line-height: 100%;
			font-size: 12px;
			font-family: Tahoma, Geneva, sans-serif;
			margin-top:3px;margin-bottom:3px;
			height:20px;
			}
			#tw-form #tw-input-text:focus{
			outline:none;
			color:#333;
			}
			#tw-form #tw-input-submit{
			background: url(search-zoom-icon.png) no-repeat 8px 5px;
			border: 0;
			float: left;
			width: 22px;
			z-index: 100;
			cursor: pointer;
			}
			
			table {
    		border-collapse: collapse;
   			}        
    		th,td {
    		border: 1px solid black;
    		padding: 0 0.5em;
    		}
    		
    		.container
			{
			width:898px;
			margin:0 auto;
			}
			.contain1
			{
			width:400px;
			margin:0 auto;
			}   
			.contain
			{
			width:898px;
			margin:0 auto;
			}     
		</style>
    </head>
    <body>
    	<div class='container'>
	    	<div class='contain1'>
	    	<form name='search_form' action='' method='get' id='tw-form'>
	        	<input type='text' name='searchLastName' id='tw-input-text' placeholder='Last Name'/>
	            <input type='text' name='searchFirstName' id='tw-input-text' placeholder='First Name' />
	            <select name='searchGender' id='tw-select-text'>
		        	<option value='0' selected='selected' disabled='disabled'>--Gender--</option>
		        	<option value='M'>Male</option>
		            <option value='F'>Female</option>
	        	</select>
	            <input type='submit' name='searchSubmit' value='' id='tw-input-submit'/>
	            <br />
	            <input type='text' name='searchMunicipality' placeholder='Municipality' style='width:145px;line-height: 100%;font-size: 12px;font-family: Tahoma, Geneva, sans-serif;margin-top:3px;margin-bottom:3px;height:20px;'/>
	            <!--<input type='text' name='searchDob' placeholder='Date of Birth' style='width:145px;line-height: 100%;font-size: 12px;font-family: Tahoma, Geneva, sans-serif;margin-top:3px;margin-bottom:3px;height:20px;'/>-->
	            <!--<input type='submit' name='goBack' value='back' />-->
	        </form>
	        </div>
	        <br />
	        <br />
	        <br />
	        
	        <?php
	        
		        function cleanDate($input)
		        {
		        	$parts = explode('/', $input);
		        	if(count($parts) != 3) return false;
		        
		        	$month = (int)$parts[0];
		        	$day = (int)$parts[1];
		        	$year = (int)$parts[2];
		        
		        	if($year < 100)
		        	{
		        		if($year < 70)
		        		{
		        			$year += 2000;
		        		}
		        		else
		        		{
		        			$year += 1900;
		        		}
		        	}
		        
		        	if(!checkdate($month, $day, $year)) return false;
		        
		        	return sprintf('%02d/%02d/%d', $month, $day, $year);
		        	// OR
		        	$time = mktime(0, 0, 0, $month, $day, $year);
		        	return date('m/d/Y', $time);
		        }
	        
				if(isset($_REQUEST['searchSubmit']) && $_REQUEST['searchSubmit']=='')
				{
					$country=$_GET['searchLastname'];
					$rcount=0;
					
					$lname = $_GET['searchLastName'];
					$fname = $_GET['searchFirstName'];
					$gender = $_GET['searchGender'];
					$municipality = $_GET['searchMunicipality'];
					//$dob = $_GET['searchDob'];
										
					//print_r($_SESSION['array_data']);
					echo "<br />";
					echo "Search For: " . strtoupper($lname) .", ".strtoupper($fname);
					echo "<table class='contain'>";
					echo "<tr>";
					echo "<th>Last Name</th>";
					echo "<th>First Name</th>";
					echo "<th>Middle Name</th>";
					echo "<th>Gender</th>";
					echo "<th>Birthdate</th>";
					echo "<th>Municipality</th>";
					echo "<th>Valid</th>";
					echo "<th>Add Patient</th>";
					echo "</tr>";
						
										
					foreach($_SESSION['array_data'] as $key => $value)
					{
						
						$compare1 = soundex($lname) == soundex($value['C']);
						$compare2 = soundex($fname) == soundex($value['D']);
						$compare3 = $gender == $value['F'];
						$compare4 = soundex($municipality) == soundex($value['J']);
						//$compare5 = strtotime(cleanDate($dob)) == strtotime($value['G']);
						
						
						if (($municipality=="" || $municipality==null))
						{
							$totalComp = $compare1 && $compare2 && $compare3;
						}
						else 
						{
							$totalComp = $compare1 && $compare2 && $compare3 && $compare4;
						}
						
						if ($totalComp)
						{
							$rcount = $rcount + 1;
							echo "<tr>";
								echo "<td>". $value['C'] ."</td>";
								echo "<td>". $value['D'] ."</td>";
								echo "<td>". $value['E'] ."</td>";
								echo "<td align='center'>". $value['F'] ."</td>";
								echo "<td align='center'>". $value['G'] ."</td>";
								echo "<td align='center'>". $value['J'] ."</td>";
								$expire_date=strtotime($value['P']);
								$date=strtotime(date("M-DD-Y"));
								if($expire_date < $date)
								{
									echo "<td align='center'>(".$value['P'].")-No</td>\n";
								}
								else
								{
									echo "<td align='center'>(".$value['P'].")-Yes</td>\n";
								}
								echo "<td align='center'><input type='submit' name='AddtoEMR' value='Add to EMR' onclick=\"location.href='verify2.php?pin=".$value['B']."&lname=".$value['C']."&fname=".$value['D']."&mname=".$value['E']."&gender=".$value['F']."&AddtoEMR=Add to EMR';\"></td>";
									
							echo "</tr>";
						}
						
						
						
					}
					/*for($row=1; $row<=$count; $row++)
					{
						if(soundex($country)==soundex($_SESSION['array_data'][$row]['C']))
						{
							$rcount=$rcount+1;
							echo "<tr>";
							echo "<td>" . $_SESSION['array_data'][$row]['C'] . "</td>";
							echo "<td>" . $_SESSION['array_data'][$row]['D'] . "</td>"; 
							echo "</tr>";
						}
					}*/
					
					echo "</table>";
					echo $rcount . " Record(s) Found";
					
				}
				/*if(isset($_REQUEST['goBack']) && $_REQUEST['goBack']=='back')
				{
					unset($_SESSION['start']);
					header('Location:/experiment');
				}*/
			?>
			
		</div>
    </body>
</html>