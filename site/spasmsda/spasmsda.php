<?php
	$dbHost = "localhost";
	//$dbUser = "root";
	//$dbPass = "";
	//$dbName = "gerona1";
	//$dbConnect = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);
	$dbConnect = mysqli_connect($dbHost, $_SESSION["dbuser"], $_SESSION["dbpass"], $_SESSION["dbname"]);
	
	function loopDatesHeader($start,$end)
	{
		$count = 0;
		while($start < $end)
		{
			$count = $count + 1;			
			$Smonth = date('M', $start);
			$Syear = date('Y', $start);
			echo "<th width='50px'>$Smonth $Syear</th>";
			//echo date('F Y', $start), PHP_EOL;
			$start = strtotime("+1 month", $start);
		}
			echo "<th width='50px'>Grand Total</th>";
	}
	
	function setTableWidth($start,$end)
	{
		$column = 0;
		while($start < $end)
		{
			$column = $column + 1;
			//echo date('F Y', $start), PHP_EOL;
			$start = strtotime("+1 month", $start);
		}
		$totalColumn = ($column + 1) * 52;
		$width = 380;
		$tableWidth = $width + $totalColumn;
		//echo $tableWidth;
		echo "<table width='".$tableWidth."px'>";
		//echo "<td colspan=$column></td>";
	}
	
	function totalCountNames($start,$end,$sql1,$rowName,$parseString1,$parseString2='')
	{
		$dbHost = "localhost";
		$dbUser = "root";
		$dbPass = "";
		$dbName = "gerona1";
		//$dbConnect = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);
		$dbConnect = mysqli_connect($dbHost, $_SESSION["dbuser"], $_SESSION["dbpass"], $_SESSION["dbname"]);
		
		while($start < $end)
		{
			$Smonth = date('F', $start);
			$Syear = date('Y', $start);
			$month_number = "";
			for($i=1; $i<=12; $i++)
			{
				if(date("F", mktime(0, 0, 0, $i, 1, 0)) == $Smonth)
				{
					$month_number = $i;
					break;
				}
			}
			//$sdate = strftime("%Y/%m/%d",mktime(0,0,0,$month_number,1,$Syear));
			//$date = strftime("%Y/%m/%d",mktime(0,0,0,($month_number + 1),0,$Syear));
			$sdate = strftime("%Y/%m/%d",mktime(0,0,0,$month_number,1,$Syear));
			$edate = strftime("%Y/%m/%d",mktime(0,0,0,($month_number + 1),0,$Syear));
			//Run SQL Query
			$sql2 = $sql1 . " '$sdate' AND '$edate'";
			$Query = mysqli_query($dbConnect,$sql2);
			$row = mysqli_fetch_array($Query);
			if ($row[$rowName] == 0)
			{
				echo "<td align='right'>".$row[$rowName]."</td>";
			}
			else 
			{
				echo "<td align='right'><a href='viewname.php?parseString1=$parseString1&parseString2=$parseString2&sDate=$sdate&eDate=$edate&count=$row[$rowName]' target='_blank'>".$row[$rowName]."</a></td>";
			}
			
			//echo date('F Y', $start), PHP_EOL;
			$start = strtotime("+1 month", $start);
			
		}
	
	}
	
	function totalCount($start,$end,$sql1,$rowName)
	{
		$dbHost = "localhost";
		$dbUser = "root";
		$dbPass = "";
		$dbName = "gerona1";
		//$dbConnect = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);
		$dbConnect = mysqli_connect($dbHost, $_SESSION["dbuser"], $_SESSION["dbpass"], $_SESSION["dbname"]);
	
		while($start < $end)
		{
			$Smonth = date('F', $start);
			$Syear = date('Y', $start);
			$month_number = "";
			for($i=1; $i<=12; $i++)
			{
			if(date("F", mktime(0, 0, 0, $i, 1, 0)) == $Smonth)
			{
			$month_number = $i;
			break;
			}
			}
			//$date = strftime("%Y/%m/%d",mktime(0,0,0,($month_number + 1),0,$Syear));
			$sdate = strftime("%Y/%m/%d",mktime(0,0,0,$month_number,1,$Syear));
			$edate = strftime("%Y/%m/%d",mktime(0,0,0,($month_number + 1),0,$Syear));	
			//Run SQL Query
			$sql2 = $sql1 . " '$sdate' AND '$edate'";
			$Query = mysqli_query($dbConnect,$sql2);
			$row = mysqli_fetch_array($Query);
				
			echo "<td align='right'>".$row[$rowName]."</td>";
			//echo date('F Y', $start), PHP_EOL;
			$start = strtotime("+1 month", $start);
		}
		
	}		
		
	function distribution($start,$end,$sql1,$resultName,$resultID,$sql2,$count,$parseString1,$parseString2)
	{
		$dbHost = "localhost";
		//$dbUser = "root";
		//$dbPass = "";
		//$dbName = "gerona1";
		//$dbConnect = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);
		$dbConnect = mysqli_connect($dbHost, $_SESSION["dbuser"], $_SESSION["dbpass"], $_SESSION["dbname"]);
	
		$aSQL = $sql1;
	
		$aQuery = mysqli_query($dbConnect,$aSQL);
		$newStart = $start;
	
		while($result = mysqli_fetch_array($aQuery))
		{
			echo "<tr>";
			echo "<td><ul style='padding-left:60px;' type='square'><li>".$result[$resultName]."</li></ul></td>";
	
	
			while($start < $end)
			{
				$Smonth = date('F', $start);
				$Syear = date('Y', $start);
				$month_number = "";
				for($i=1; $i<=12; $i++)
				{
				if(date("F", mktime(0, 0, 0, $i, 1, 0)) == $Smonth)
				{
				$month_number = $i;
				break;
				}
				}
				//$date = strftime("%Y/%m/%d",mktime(0,0,0,($month_number + 1),0,$Syear));
				$sdate = strftime("%Y/%m/%d",mktime(0,0,0,$month_number,1,$Syear));
				$edate = strftime("%Y/%m/%d",mktime(0,0,0,($month_number + 1),0,$Syear));
				//Run SQL Query
				/*$sql = "SELECT count(DISTINCT a.patient_id), a.last_modified, b.patient_id, c.family_id, c.barangay_id FROM m_lib_sms_px_enroll a JOIN " .
				"m_family_members b ON b.patient_id = a.patient_id JOIN " .
				"m_family_address c ON c.family_id = b.family_id " .
				"WHERE c.barangay_id = '".$result['barangay_id']."' AND last_modified <= '$date' ";*/
				$bSQL = "$sql2 '$result[$resultID]' AND date_format(last_modified, '%Y/%m/%d') BETWEEN '$sdate' AND '$edate' ";
				$bQuery = mysqli_query($dbConnect,$bSQL);
				$row = mysqli_fetch_array($bQuery);
				
				if ($row[$count]==0)
				{
					echo "<td align='right'>".$row[$count]."</td>";
				}
				else 
				{
					if ($parseString2 == "Distribution Per Barangay" || $parseString2 == "Distribution Per BHS")
					{
						echo "<td align='right'><a href='viewname.php?parseString1=$parseString1&parseString2=$parseString2&type=$result[$resultName]&sDate=$sdate&eDate=$edate&count=$row[$count]' target='_blank'>".$row[$count]."</a></td>";
					}
					else
					{
						echo "<td align='right'><a href='viewname.php?parseString1=$parseString1&parseString2=$parseString2&type=$result[$resultID]&sDate=$sdate&eDate=$edate&count=$row[$count]' target='_blank'>".$row[$count]."</a></td>";
					}
				}
				//echo date('F Y', $start), PHP_EOL;
				$start = strtotime("+1 month", $start);
			}
			$start = $newStart;
			
			//for Grand Total
			$Smonth = date('F', $start);
			$Syear = date('Y', $start);
			$month_number = "";
			
			for($i=1; $i<=12; $i++)
			{
				if(date("F", mktime(0, 0, 0, $i, 1, 0)) == $Smonth)
				{
					$month_number = $i;
					break;
				}
			}
			
			$Emonth = date('F', $end);
			$Eyear = date('Y', $end);
			$month_number1 = "";
			for($i=1; $i<=12; $i++)
			{
				if(date("F", mktime(0, 0, 0, $i, 1, 0)) == $Emonth)
				{
					$month_number1 = $i;
					break;
				}
			}
			$sdate = strftime("%Y/%m/%d",mktime(0,0,0,$month_number,1,$Syear));
			$edate = strftime("%Y/%m/%d",mktime(0,0,0,($month_number1 + 1),0,$Eyear));
			
			
			$bSQL = "$sql2 '$result[$resultID]' AND date_format(last_modified, '%Y/%m/%d') BETWEEN '$sdate' AND '$edate' ";
			$bQuery = mysqli_query($dbConnect,$bSQL);
			$row = mysqli_fetch_array($bQuery);
			
			if ($row[$count]==0)
			{
				echo "<td align='right'>".$row[$count]."</td>";
			}
			else
			{
				if ($parseString2 == "Distribution Per Barangay" || $parseString2 == "Distribution Per BHS")
				{
					echo "<td align='right'><a href='viewname.php?parseString1=$parseString1&parseString2=$parseString2&type=$result[$resultName]&sDate=$sdate&eDate=$edate&count=$row[$count]' target='_blank'>".$row[$count]."</a></td>";
				}
				else
				{
				echo "<td align='right'><a href='viewname.php?parseString1=$parseString1&parseString2=$parseString2&type=$result[$resultID]&sDate=$sdate&eDate=$edate&count=$row[$count]' target='_blank'>".$row[$count]."</a></td>";
				}
			}
			
			echo "</tr>";
		}
	
	}
	
	function distributionTotal($start,$end,$sql1,$resultName,$resultID,$sql2,$sql3,$count)
	{
		$dbHost = "localhost";
		$dbUser = "root";
		$dbPass = "";
		$dbName = "gerona1";
		//$dbConnect = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);
		$dbConnect = mysqli_connect($dbHost, $_SESSION["dbuser"], $_SESSION["dbpass"], $_SESSION["dbname"]);
		
		$aSQL = $sql1;
	
		$aQuery = mysqli_query($dbConnect,$aSQL);
		$newStart = $start;
		$arr = array('not_sent' => 'Not Sent', 'sent' => 'Sent', 'queue' => 'Queue', 'terminate' => 'Terminate');
		while($result = mysqli_fetch_array($aQuery))
		{
			
			if ($result[$resultName] == 'not_sent')
			{
				$viewResult = 'Not Sent';
			}
			elseif ($result[$resultName] == 'sent')
			{
				$viewResult = 'Sent';
			}
			elseif ($result[$resultName] == 'queue')
			{
				$viewResult = 'Queue';
			}
			elseif ($result[$resultName] == 'terminate')
			{
				$viewResult = 'Terminate';
			}
			else 
				$viewResult = $result[$resultName];
			
			echo "<tr>";
			echo "<td><ul style='padding-left:60px;' type='square'><li>".$viewResult."</li></ul></td>";
			
	
			while($start < $end)
			{
				$Smonth = date('F', $start);
				$Syear = date('Y', $start);
				$month_number = "";
				for($i=1; $i<=12; $i++)
				{
				if(date("F", mktime(0, 0, 0, $i, 1, 0)) == $Smonth)
				{
				$month_number = $i;
				break;
				}
				}
				//$date = strftime("%Y/%m/%d",mktime(0,0,0,($month_number + 1),0,$Syear));
				$sdate = strftime("%Y/%m/%d",mktime(0,0,0,$month_number,1,$Syear));
				$edate = strftime("%Y/%m/%d",mktime(0,0,0,($month_number + 1),0,$Syear));	
				//Run SQL Query
				/*$sql = "SELECT count(DISTINCT a.patient_id), a.last_modified, b.patient_id, c.family_id, c.barangay_id FROM m_lib_sms_px_enroll a JOIN " .
						"m_family_members b ON b.patient_id = a.patient_id JOIN " .
						"m_family_address c ON c.family_id = b.family_id " .
						"WHERE c.barangay_id = '".$result['barangay_id']."' AND last_modified <= '$date' ";*/
				$bSQL = "$sql2 '$result[$resultID]' AND $sql3 BETWEEN '$sdate' AND '$edate' ";
				$bQuery = mysqli_query($dbConnect,$bSQL);
				$row = mysqli_fetch_array($bQuery);

				echo "<td align='right'>".$row[$count]."</td>";
				//echo date('F Y', $start), PHP_EOL;
				$start = strtotime("+1 month", $start);
			}
			$start = $newStart;
			//for Grand Total
			$Smonth = date('F', $start);
			$Syear = date('Y', $start);
			$month_number = "";
			
			for($i=1; $i<=12; $i++)
			{
				if(date("F", mktime(0, 0, 0, $i, 1, 0)) == $Smonth)
				{
					$month_number = $i;
					break;
				}
			}
			
			$Emonth = date('F', $end);
			$Eyear = date('Y', $end);
			$month_number1 = "";
			for($i=1; $i<=12; $i++)
			{
				if(date("F", mktime(0, 0, 0, $i, 1, 0)) == $Emonth)
				{
					$month_number1 = $i;
					break;
				}
			}
			$sdate = strftime("%Y/%m/%d",mktime(0,0,0,$month_number,1,$Syear));
			$edate = strftime("%Y/%m/%d",mktime(0,0,0,($month_number1 + 1),0,$Eyear));
			$bSQL = "$sql2 '$result[$resultID]' AND $sql3 BETWEEN '$sdate' AND '$edate' ";
				$bQuery = mysqli_query($dbConnect,$bSQL);
				$row = mysqli_fetch_array($bQuery);

				echo "<td align='right'>".$row[$count]."</td>";
			echo "</tr>";
		}
	
	}
				
	function distributionSMStotal($start,$end,$sql1,$resultName,$resultID,$sql2,$sql3,$count)
	{
		$dbHost = "localhost";
		$dbUser = "root";
		$dbPass = "";
		$dbName = "gerona1";
		//$dbConnect = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);
		$dbConnect = mysqli_connect($dbHost, $_SESSION["dbuser"], $_SESSION["dbpass"], $_SESSION["dbname"]);
	
		$aSQL = $sql1;
	
		$aQuery = mysqli_query($dbConnect,$aSQL);
		$newStart = $start;
	
		while($result = mysqli_fetch_array($aQuery))
		{
			echo "<tr>";
			echo "<td><ul style='padding-left:60px;' type='square'><li>".$result[$resultName]."</li></ul></td>";
			if($result[$resultID]=='basic')
			{
				$alertCode = 'user';
			}
			else
			{
				$alertCode = $result[$resultID];
			}	
			while($start < $end)
			{
				$Smonth = date('F', $start);
				$Syear = date('Y', $start);
				$month_number = "";
				for($i=1; $i<=12; $i++)
				{
					if(date("F", mktime(0, 0, 0, $i, 1, 0)) == $Smonth)
					{
						$month_number = $i;
						break;
					}
				}
				$sdate = strftime("%Y/%m/%d",mktime(0,0,0,$month_number,1,$Syear));
				$edate = strftime("%Y/%m/%d",mktime(0,0,0,($month_number + 1),0,$Syear));
				//$date = strftime("%Y/%m/%d",mktime(0,0,0,($month_number + 1),0,$Syear));
					
				//Run SQL Query
				/*$sql = "SELECT count(DISTINCT a.patient_id), a.last_modified, b.patient_id, c.family_id, c.barangay_id FROM m_lib_sms_px_enroll a JOIN " .
				"m_family_members b ON b.patient_id = a.patient_id JOIN " .
				"m_family_address c ON c.family_id = b.family_id " .
				"WHERE c.barangay_id = '".$result['barangay_id']."' AND last_modified <= '$date' ";*/
				$bSQL = "$sql2 '%$alertCode%' AND $sql3 BETWEEN '$sdate' AND '$edate' ";
				$bQuery = mysqli_query($dbConnect,$bSQL);
				$row = mysqli_fetch_array($bQuery);
	
				echo "<td align='right'>".$row[$count]."</td>";
				//echo date('F Y', $start), PHP_EOL;
				$start = strtotime("+1 month", $start);
			}
			$start = $newStart;
			//for Grand Total
			$Smonth = date('F', $start);
			$Syear = date('Y', $start);
			$month_number = "";
			
			for($i=1; $i<=12; $i++)
			{
				if(date("F", mktime(0, 0, 0, $i, 1, 0)) == $Smonth)
				{
					$month_number = $i;
					break;
				}
			}
			
			$Emonth = date('F', $end);
			$Eyear = date('Y', $end);
			$month_number1 = "";
			for($i=1; $i<=12; $i++)
			{
				if(date("F", mktime(0, 0, 0, $i, 1, 0)) == $Emonth)
				{
					$month_number1 = $i;
					break;
				}
			}
			$sdate = strftime("%Y/%m/%d",mktime(0,0,0,$month_number,1,$Syear));
			$edate = strftime("%Y/%m/%d",mktime(0,0,0,($month_number1 + 1),0,$Eyear));
			$bSQL = "$sql2 '%$alertCode%' AND $sql3 BETWEEN '$sdate' AND '$edate' ";
				$bQuery = mysqli_query($dbConnect,$bSQL);
				$row = mysqli_fetch_array($bQuery);
	
				echo "<td align='right'>".$row[$count]."</td>";
			echo "</tr>";
			
			$cSQL = "SELECT * FROM m_lib_alert_indicators WHERE main_indicator = '$result[$resultID]'";
			$cQuery = mysqli_query($dbConnect,$cSQL);
			
			while($result = mysqli_fetch_array($cQuery))
			{
				echo "<tr>";
				echo "<td><ul style='padding-left:80px;' type='circle'><li>".$result['sub_indicator']."</li></ul></td>";
				while($start < $end)
				{
					$Smonth = date('F', $start);
					$Syear = date('Y', $start);
					$month_number = "";
					for($i=1; $i<=12; $i++)
					{
						if(date("F", mktime(0, 0, 0, $i, 1, 0)) == $Smonth)
						{
							$month_number = $i;
							break;
						}
					}
					//$date = strftime("%Y/%m/%d",mktime(0,0,0,($month_number + 1),0,$Syear));
					$sdate = strftime("%Y/%m/%d",mktime(0,0,0,$month_number,1,$Syear));
					$edate = strftime("%Y/%m/%d",mktime(0,0,0,($month_number + 1),0,$Syear));	
					//Run SQL Query
					/*$sql = "SELECT count(DISTINCT a.patient_id), a.last_modified, b.patient_id, c.family_id, c.barangay_id FROM m_lib_sms_px_enroll a JOIN " .
					"m_family_members b ON b.patient_id = a.patient_id JOIN " .
					"m_family_address c ON c.family_id = b.family_id " .
					"WHERE c.barangay_id = '".$result['barangay_id']."' AND last_modified <= '$date' ";*/
					$dSQL = "SELECT count(sms_id) FROM `m_lib_sms_alert` a, `m_lib_alert_indicators` b WHERE b.alert_indicator_id = CASE WHEN alert_id = 'basic' THEN '49' ELSE alert_id END AND b.alert_indicator_id = '".$result['alert_indicator_id']."' AND $sql3 BETWEEN '$sdate' AND '$edate' ";
					$dQuery = mysqli_query($dbConnect,$dSQL);
					$row = mysqli_fetch_array($dQuery);
		
					echo "<td align='right'>".$row[$count]."</td>";
					//echo date('F Y', $start), PHP_EOL;
					$start = strtotime("+1 month", $start);
				}
				$start = $newStart;
				//for Grand Total
			$Smonth = date('F', $start);
			$Syear = date('Y', $start);
			$month_number = "";
			
			for($i=1; $i<=12; $i++)
			{
				if(date("F", mktime(0, 0, 0, $i, 1, 0)) == $Smonth)
				{
					$month_number = $i;
					break;
				}
			}
			
			$Emonth = date('F', $end);
			$Eyear = date('Y', $end);
			$month_number1 = "";
			for($i=1; $i<=12; $i++)
			{
				if(date("F", mktime(0, 0, 0, $i, 1, 0)) == $Emonth)
				{
					$month_number1 = $i;
					break;
				}
			}
			$sdate = strftime("%Y/%m/%d",mktime(0,0,0,$month_number,1,$Syear));
			$edate = strftime("%Y/%m/%d",mktime(0,0,0,($month_number1 + 1),0,$Eyear));
			$dSQL = "SELECT count(sms_id) FROM `m_lib_sms_alert` a, `m_lib_alert_indicators` b WHERE b.alert_indicator_id = CASE WHEN alert_id = 'basic' THEN '49' ELSE alert_id END AND b.alert_indicator_id = '".$result['alert_indicator_id']."' AND $sql3 BETWEEN '$sdate' AND '$edate' ";
					$dQuery = mysqli_query($dbConnect,$dSQL);
					$row = mysqli_fetch_array($dQuery);
		
					echo "<td align='right'>".$row[$count]."</td>";
			echo "</tr>";
			}
			
		}
	
	}
						
	function fromDateToDate()
	{
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
		
		echo "<br />";
		
		echo "<label><span class='padding15'>From</span></label>
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
				echo "<option value=$y ".($_POST['startYear']== $y ? 'selected' : '').">$y";
			}
		echo "</select>";
		echo "<br />";
		echo "<label><span class='padding15'>To</span></label>
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
				echo "<option value=$y  ".($_POST['endYear']== $y ? 'selected' : '').">$y";
			}
		echo "</select>";
	}
	
?>
<html>
	<head>
		<title>SPASMS Data Analytics</title>
		<script type="text/javascript" src="script/jquery-1.10.2.min.js"></script>
		<script>
			$(document).ready(function(){
				$('.header').click(function(){
					$(this).find('span').text(function(_, value){return value=='-'?'+':'-'});
					$(this).nextUntil('tr#end').slideToggle("fast");
				});
				$('.alertheader').click(function(){
					$(this).find('span').text(function(_, value){return value=='-'?'+':'-'});
					$(this).nextUntil('tr.alertend').slideToggle("fast");
				});
			});
		</script>
		<style type="text/css">
			form, label, body 
			{
				font-size: 10pt;
	   			font-family: Arial;
			}
			table, th, tr, td
			{
	    		border-collapse: collapse;
	    		font-size: 8.5pt;
	   			font-family: Sans-Serif;
	   		}        
	    	th,td
	    	{
	    		border: 1px solid black;
	    		margin:0;
	    	}
	    	tr.header td:first-child, tr.alertheader td:first-child
			{
			    cursor:pointer;
			}
	    	ol
	    	{
	    		margin:0;
	    		padding-left:20px;
	    	}
	    	ul
	    	{
	    		margin:0;
	    	}
	    	.padding15
	    	{
	    		width:30px;
	    		display:inline-block;
	    	}
	    	.scroll
			{
				overflow:auto;
			}
		</style>
	</head>
	<body>
	<?php 
		if ($_SESSION["userid"]!="")
		{
		
		echo "<form name='spasms_form' method='post' action=''>";
			echo "<div style='width:100%; margin:0 auto; display:inline-block;'>";
			
				echo "<div style='float:left; margin:0 auto;'>";
				fromDateToDate();
				echo "<br /><br />";
				echo "<span style='width:100%; text-align:center; display:inline-block'><input type='submit' name='go' value='Submit'></span><br /><br />";
				echo "</div>";
				
				if (isset($_REQUEST['go']) && $_REQUEST['go'] == 'Submit')
				{
					$sdate = strftime("%m/%d/%Y",mktime(0,0,0,$_POST['startMonth'],1,$_POST['startYear']));
					$edate = strftime("%m/%d/%Y",mktime(0,0,0,($_POST['endMonth']+1),0,$_POST['endYear']));
				
					$newSDate = date("Y/m/d", strtotime($sdate));
					$newEDate = date("Y/m/d", strtotime($edate));
					if ($newSDate > $newEDate)
					{
						echo "<script>alert('Start Date is Greater Than End Date')</script>";
						return false;
					}
					$s = strtotime($newSDate);
					$e = strtotime($newEDate);
					
					echo "<div style='float:left; margin-left:20px; display:inline-block; width:80%; height:90%;' class='scroll'>";
					echo "<br />";
					
					//Create Table
					setTableWidth($s, $e);
					//echo $_POST['columnCount'];					
					echo "<tr>";
					echo "<th>INDICATORS</th>";
						loopDatesHeader($s, $e);
					echo "</tr>";
										
					echo "<tr>";
						$totalPatientPARSE = "Total Number of Patients Enrolled";
						echo "<td width='380px'><ol><li>$totalPatientPARSE</li></ol></td>";
						$totalPatientSQL = "SELECT count(a.patient_id) FROM m_lib_sms_px_enroll a, m_patient b WHERE b.patient_id = a.patient_id AND date_format(last_modified, '%Y/%m/%d') BETWEEN ";
						$totalPatientROW = "count(a.patient_id)";
						totalCountNames($s, $e, $totalPatientSQL, $totalPatientROW, $totalPatientPARSE);
						
						//for Grand Total
						$totalPatientQuery = mysqli_query($dbConnect,$totalPatientSQL . " '$newSDate' AND '$newEDate'");
						$totalPatientResult = mysqli_fetch_array($totalPatientQuery);
						if ($totalPatientResult[$totalPatientROW] == 0)
						{
							echo "<td align='right'>$totalPatientResult[$totalPatientROW]</td>";
						}
						else 
						{
							echo "<td align='right'><a href='viewname.php?parseString1=$totalPatientPARSE&parseString2=&sDate=$newSDate&eDate=$newEDate&count=$totalPatientResult[$totalPatientROW]' target='_blank'>$totalPatientResult[$totalPatientROW]</a></td>";
						}
						
					echo "</tr>";
					
					echo "<tr id='end' class='header'>";
						$countBrgyPARSE = "Distribution Per Barangay";
						echo "<td><ul type='none' style='margin-left:-15px'><li><span style='padding-right:9px;'>-</span>$countBrgyPARSE</li></ul></td>";
						$countBrgySQL = "SELECT count(a.patient_id), a.last_modified FROM m_lib_sms_px_enroll a JOIN " .
										"m_family_members b ON b.patient_id = a.patient_id JOIN " .
										"m_patient c ON c.patient_id = a.patient_id " .
										"WHERE date_format(a.last_modified, '%Y/%m/%d') BETWEEN ";
						$countBrgyResult = "count(a.patient_id)";
						totalCountNames($s, $e, $countBrgySQL, $countBrgyResult, $totalPatientPARSE, $countBrgyPARSE);
						
						//for Grand Total
						$countBrgyQuery = mysqli_query($dbConnect,$countBrgySQL . " '$newSDate' AND '$newEDate'");
						$countBrgyResult1 = mysqli_fetch_array($countBrgyQuery);
						if ($countBrgyResult1[$countBrgyResult] == 0)
						{
							echo "<td align='right'>$countBrgyResult1[$countBrgyResult]</td>";
						}
						else
						{
							echo "<td align='right'><a href='viewname.php?parseString1=$totalPatientPARSE&parseString2=$countBrgyPARSE&sDate=$newSDate&eDate=$newEDate&count=$countBrgyResult1[$countBrgyResult]' target='_blank'>$countBrgyResult1[$countBrgyResult]</a></td>";
						}
					echo "</tr>";
					
					// Extract all barangay with values
					$barangaySQL1 = "SELECT barangay_id, barangay_name FROM m_lib_barangay";
					$barangayName = "barangay_name";
					$barangayID = "barangay_id";
					$barangaySQL2 = "SELECT count(a.patient_id), a.last_modified, b.patient_id, c.family_id, c.barangay_id FROM m_lib_sms_px_enroll a JOIN " .
									"m_family_members b ON b.patient_id = a.patient_id JOIN " .
									"m_family_address c ON c.family_id = b.family_id JOIN " .
									"m_patient d ON d.patient_id = a.patient_id " .
									"WHERE c.barangay_id =";
					$barangayPatient = "count(a.patient_id)";
					distribution($s, $e, $barangaySQL1, $barangayName, $barangayID, $barangaySQL2, $barangayPatient, $totalPatientPARSE, $countBrgyPARSE);
					
					echo "<tr id='end' class='header'>";
						$countProgramPARSE = "Distribution Per Program";
						echo "<td><ul type='none' style='margin-left:-15px'><li><span style='padding-right:9px;'>-</span>$countProgramPARSE</li></ul></td>";
						$countProgramSQL = "SELECT count(DISTINCT enroll_id) FROM m_lib_sms_px_enroll a, m_patient b WHERE b.patient_id = a.patient_id AND date_format(last_modified, '%Y/%m/%d') BETWEEN ";
						$countProgramResult = "count(DISTINCT enroll_id)";
						totalCountNames($s, $e, $countProgramSQL, $countProgramResult, $totalPatientPARSE, $countProgramPARSE);
					
						//for Grand Total
						$countProgramQuery = mysqli_query($dbConnect,$countProgramSQL . " '$newSDate' AND '$newEDate'");
						$countProgramResult1 = mysqli_fetch_array($countProgramQuery);
						if ($countProgramResult1[$countProgramResult] == 0)
						{
							echo "<td align='right'>$countProgramResult1[$countProgramResult]</td>";
						}
						else
						{
							echo "<td align='right'><a href='viewname.php?parseString1=$totalPatientPARSE&parseString2=$countProgramPARSE&sDate=$newSDate&eDate=$newEDate&count=$countProgramResult1[$countProgramResult]' target='_blank'>$countProgramResult1[$countProgramResult]</a></td>";
						}
					echo "</tr>";
					
					//Extract all programs with values
					$programSQL1 = "SELECT DISTINCT program_id FROM m_lib_sms_px_enroll";
					$programID = "program_id"; 
					$programSQL2 = "SELECT count(enroll_id) FROM m_lib_sms_px_enroll a, m_patient b WHERE b.patient_id = a.patient_id AND program_id =";
					$programPatient = "count(enroll_id)";
					distribution($s, $e, $programSQL1, $programID, $programID, $programSQL2, $programPatient, $totalPatientPARSE, $countProgramPARSE);
					
					echo "<tr id='end' class='header'>";
						$countBHSParse = "Distribution Per BHS";
						echo "<td><ul type='none' style='margin-left:-15px'><li><span style='padding-right:9px;'>-</span>$countBHSParse</li></ul></td>";
						//blankTD($s, $e);
						$countBHSsql = "SELECT count(a.patient_id), a.last_modified, b.patient_id, c.family_id, c.barangay_id, d.barangay_id, d.bhs_id, e.bhs_id, e.bhs_name FROM m_lib_sms_px_enroll a JOIN " .
										"m_family_members b ON b.patient_id = a.patient_id JOIN " .
										"m_family_address c ON c.family_id = b.family_id JOIN " .
										"m_lib_bhs_barangay d ON d.barangay_id = c.barangay_id JOIN " .
										"m_lib_bhs e ON e.bhs_id = d.bhs_id JOIN " .
										"m_patient f ON f.patient_id = a.patient_id " .
										"WHERE date_format(a.last_modified, '%Y/%m/%d') BETWEEN ";
						$countBHSresult = "count(a.patient_id)";
						totalCountNames($s, $e, $countBHSsql, $countBHSresult, $totalPatientPARSE, $countBHSParse);
						
						//for Grand Total
						$countBHSQuery = mysqli_query($dbConnect,$countBHSsql . " '$newSDate' AND '$newEDate'");
						$countBHSResult1 = mysqli_fetch_array($countBHSQuery);
						if ($countBHSResult1[$countBHSresult] == 0)
						{
							echo "<td align='right'>$countBHSResult1[$countBHSresult]</td>";
						}
						else
						{
							echo "<td align='right'><a href='viewname.php?parseString1=$totalPatientPARSE&parseString2=$countBHSParse&sDate=$newSDate&eDate=$newEDate&count=$countBHSResult1[$countBHSresult]' target='_blank'>$countBHSResult1[$countBHSresult]</a></td>";
						}
					echo "</tr>";
					
					//Extract all bhs with values
					$bhsSQL1 = "SELECT bhs_id, bhs_name, barangay_id FROM m_lib_bhs";
					$bhsName = "bhs_name";
					$bhsID = "bhs_id";
					$bhsSQL2 = "SELECT count(a.patient_id), a.last_modified, b.patient_id, c.family_id, c.barangay_id, d.barangay_id, d.bhs_id, e.bhs_id, e.bhs_name FROM m_lib_sms_px_enroll a JOIN " .
								"m_family_members b ON b.patient_id = a.patient_id JOIN " .
								"m_family_address c ON c.family_id = b.family_id JOIN " .
								"m_lib_bhs_barangay d ON d.barangay_id = c.barangay_id JOIN " .
								"m_lib_bhs e ON e.bhs_id = d.bhs_id JOIN " .
								"m_patient f ON f.patient_id = a.patient_id " .
								"WHERE e.bhs_id =";
					$bhsPatient = "count(a.patient_id)";
					distribution($s, $e, $bhsSQL1, $bhsName, $bhsID, $bhsSQL2, $bhsPatient, $totalPatientPARSE, $countBHSParse);
					
					//Total Messages
					echo "<tr id='end'>";
						$totalMessagePARSE = "Total Number of Messages Generated";
						echo "<td><ol start='2'><li>$totalMessagePARSE</li></ol></td>";
						$totalMessageSQL = "SELECT count(DISTINCT sms_id) FROM m_lib_sms_alert WHERE date_format(alert_date, '%Y/%m/%d') BETWEEN ";
						$totalMessageROW = "count(DISTINCT sms_id)";
						totalCount($s, $e, $totalMessageSQL, $totalMessageROW);
						//Grand Total
						$totalMessageQuery = mysqli_query($dbConnect,$totalMessageSQL . " '$newSDate' AND '$newEDate'");
						$totalMessageResult = mysqli_fetch_array($totalMessageQuery);
						echo "<td align='right'>$totalMessageResult[$totalMessageROW]</td>";
					echo "</tr>";

					// PROGRAM
					echo "<tr id='end' class='header'>";
					echo "<td><ul type='none' style='margin-left:-15px'><li><span style='padding-right:9px;'>-</span>Distribution Per Program</li></ul></td>";
						//$countProgSMSsql = "SELECT count(DISTINCT b.sms_id) FROM m_lib_alert_type a JOIN " .
											//"m_lib_sms_alert b ON b.alert_id = a.alert_indicator_id " .
											//"WHERE date_format(b.alert_date, '%Y/%m/%d') BETWEEN ";
						//$countProgSMSresult = "count(DISTINCT b.sms_id)";
						$countProgSMSsql = "SELECT count(DISTINCT sms_id) FROM `m_lib_sms_alert` a, `m_lib_alert_indicators` b WHERE alert_indicator_id = CASE WHEN alert_id = 'basic' THEN 49 ELSE alert_id END AND date_format(alert_date, '%Y/%m/%d') BETWEEN ";
						$countProgSMSresult = "count(DISTINCT sms_id)";
						totalCount($s, $e, $countProgSMSsql, $countProgSMSresult);
						//Grand Total
						$countProgSMSQuery = mysqli_query($dbConnect,$countProgSMSsql . " '$newSDate' AND '$newEDate'");
						$countProgSMSresult1 = mysqli_fetch_array($countProgSMSQuery);
						echo "<td align='right'>$countProgSMSresult1[$countProgSMSresult]</td>";
					echo "</tr>";
					
					//$programSMSsql1 = "SELECT DISTINCT module_id FROM m_lib_alert_type";
					//$programSMSname = "module_id";
					//$programSMSsql2 = "SELECT count(b.sms_id) FROM m_lib_alert_type a JOIN " .
							//"m_lib_sms_alert b ON b.alert_id = a.alert_indicator_id WHERE a.module_id =";
					//$programSMSsql3 = "date_format(b.alert_date, '%Y/%m/%d')";
					//$programSMS = "count(b.sms_id)";
					$programSMSsql1 = "SELECT DISTINCT main_indicator FROM `m_lib_sms_alert` a, `m_lib_alert_indicators` b WHERE alert_indicator_id = CASE WHEN alert_id = 'basic' THEN 49 ELSE alert_id END";
					$programSMSname = "main_indicator";
					$programSMSsql2 = "SELECT count(sms_id) FROM `m_lib_sms_alert` a, `m_lib_alert_indicators` b WHERE alert_indicator_id = CASE WHEN alert_id = 'basic' THEN 49 ELSE alert_id END AND main_indicator = ";
					$programSMSsql3 = "date_format(alert_date, '%Y/%m/%d')";
					$programSMS = "count(sms_id)";
					distributionTotal($s, $e, $programSMSsql1, $programSMSname, $programSMSname, $programSMSsql2, $programSMSsql3, $programSMS);
					
					// ALERT
					echo "<tr id='end' class='header'>";
					echo "<td><ul type='none' style='margin-left:-15px'><li><span style='padding-right:9px;'>-</span>Distribution Per Alert Type</li></ul></td>";
						$countAlertSMSsql = "SELECT count(DISTINCT sms_id) FROM m_lib_sms_alert " .
											"WHERE date_format(alert_date, '%Y/%m/%d') BETWEEN ";
						$countAlertSMSresult = "count(DISTINCT sms_id)";
						totalCount($s, $e, $countAlertSMSsql, $countAlertSMSresult);
						//Grand Total
						$countAlertSMSQuery = mysqli_query($dbConnect,$countAlertSMSsql . " '$newSDate' AND '$newEDate'");
						$countAlertSMSresult1 = mysqli_fetch_array($countAlertSMSQuery);
						echo "<td align='right'>$countAlertSMSresult1[$countAlertSMSresult]</td>";
					echo "</tr>";
					
					$alertSMSsql1 = "SELECT DISTINCT main_indicator FROM m_lib_alert_indicators";
					$alertSMSname = "main_indicator";
					$alertSMSsql2 = "SELECT count(sms_id) FROM m_lib_sms_alert WHERE sms_code LIKE ";
					$alertSMSsql3 = "date_format(alert_date, '%Y/%m/%d')";
					$alertSMS = "count(sms_id)";
					distributionSMStotal($s, $e, $alertSMSsql1, $alertSMSname, $alertSMSname, $alertSMSsql2, $alertSMSsql3, $alertSMS);
					
					//SMS STATUS
					echo "<tr id='end' class='header'>";
					echo "<td><ul type='none' style='margin-left:-15px'><li><span style='padding-right:9px;'>-</span>Distribution Per Sending Status</li></ul></td>";
						$countStatusSMSsql = "SELECT count(DISTINCT sms_id) FROM m_lib_sms_alert " .
								"WHERE date_format(alert_date, '%Y/%m/%d') BETWEEN ";
						$countStatusSMSresult = "count(DISTINCT sms_id)";
						totalCount($s, $e, $countStatusSMSsql, $countStatusSMSresult);
						//Grand Total
						$countStatusSMSQuery = mysqli_query($dbConnect,$countStatusSMSsql . " '$newSDate' AND '$newEDate'");
						$countStatusSMSresult1 = mysqli_fetch_array($countStatusSMSQuery);
						echo "<td align='right'>$countStatusSMSresult1[$countStatusSMSresult]</td>";
					echo "</tr>";
					
					$statusSMSsql1 = "SELECT DISTINCT sms_status FROM m_lib_sms_alert";
					$statusSMSname = "sms_status";
					$statusSMSsql2 = "SELECT count(sms_status) FROM m_lib_sms_alert WHERE sms_status = ";
					$statusSMSsql3 = "date_format(alert_date, '%Y/%m/%d')";
					$statusSMS = "count(sms_status)";
					distributionTotal($s, $e, $statusSMSsql1, $statusSMSname, $statusSMSname, $statusSMSsql2, $statusSMSsql3, $statusSMS);
					
					//End of Table
					echo "</table>";
					echo "</div>";
				}
			
			echo "</div>";
		echo "</form>";
		}
		else
		{
			echo "<font color=\"red\">Access restricted. Please log your account in the main page.</font><br>";
  			echo "<a href='/chits/info/index.php'>Log In</a>";
  		}
	?>
	</body>
</html>
