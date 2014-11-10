<?php 
	include "class/dbConnect.php";
	require_once('calendar/classes/tc_calendar.php');
?>
<html>
		<head>
			<title>Philhealth Membership</title>
			<link rel='stylesheet' href='styles/style.css' type='text/css'  />
			<script>
				function submit()
				{
					var formObject = document.forms['phmembership'];
		
					if(formObject!=0)
					{
						formObject.submit();
					}
				}
				
			</script>
			<script type="text/javascript" src="calendar/calendar.js"></script>
			</head>

		<body id='member'>

			<div id='container'>

				<div  id='header'>
					<h4 class='shadow'><span class='indent10'></span></h4>
					<h1>WIRELESS ACCESS FOR HEALTH</h1>
					<h2>PHILIPPINE HEALTH INSURANCE CORPORATION</h2>
					<h3>List of Philhealth Members</h3>
					<br />
				</div>

				<div id='body'>
					<?php 
						$patientID = $_REQUEST['id'];
						$sql = "SELECT CONCAT(patient_lastname,', ', patient_firstname, ' ', patient_middle) AS name, barangay_name, DATE_FORMAT(philhealth_timestamp,'%Y-%m-%d') AS date_registered, philhealth_id, member_id, a.patient_id, expiry_date FROM `m_patient_philhealth` a
								JOIN m_patient b ON a.patient_id = b.patient_id
								JOIN m_family_members c ON a.patient_id = c.patient_id
								JOIN m_family_address d ON c.family_id = d.family_id
								JOIN m_lib_barangay e ON d.barangay_id = e.barangay_id
								WHERE a.patient_id = $patientID";
						$query = $dbase->_dbQuery($sql);
						$result = $dbase->_dbFetchArr($query);
					?>
					<form name='phmembership' method='POST'>
					<div class='width450Div'>
					<br />
					<br />
					<br />
					<fieldset>
					<?php
						$memberSQL = "SELECT member_id, member_label FROM `m_lib_philhealth_member_type`";
						$memberQUERY = $dbase->_dbQuery($memberSQL);
						echo "<div class='center'><label><span class='center'>PHILHEALTH RECORD</span></label><hr /></div>";
						echo "<label><span class='width150'>Patient ID</span>: </label>" . $result['patient_id'];
						echo "<br /><label><span class='width150'>Beneficiary</span>: </label>" . "<span ".($result['member_id']==4 ? "":(($result['member_id']==6 ? "" : (($result['member_id']==0 ? "" : "style='background-color:yellow;'"))))).">" . $result['name'] . "</span>";
						echo "<br /><label><span class='width150'>Barangay</span>: </label>" . $result['barangay_name'];
					
						echo "<br /><label><span class='width150'>Philhealth ID</span>: </label><input style='width:150px' type='text' name='phID' value='".$result['philhealth_id']."'>";  
							
							
						echo "<table id='expiry'><tr>";
																					
						echo "<td><label><span class='width150'>Expiry Date</span>: </label></td>";
						echo "<td>";
						$date_default = $result['expiry_date'];
						$function->_calendar($date_default);
						echo "</td></tr></table>";
							
						echo "<label><span class='width150'>Membership Type</span>: </label>";
						echo "<select name='member_type'>";
						echo "<option value=0 ".($result['member_id']==0 ? 'selected' : '').">Undefined</option>";
							
						while($memberResult = $dbase->_dbFetchArr($memberQUERY))
						{
							echo "<option value=".$memberResult['member_id']." ".($result['member_id']==$memberResult['member_id'] ? 'selected' : '').">".$memberResult['member_label']."</option>";
						}
								
						echo "</select>";
						echo "<input type='hidden' name='memberid' value=".$_POST['member_type'].">";
						echo "<br /><br /><div class='center'><input type='submit' name='update' value='Update'><input type='button' name='cancel' onClick=\"window.location='phmember.php';\"  value='Cancel'></div>";
							
					?>
	
					</fieldset>
					<br />
					<br />
					
					<?php
						 
						 
						$sql2 = "SELECT CONCAT(patient_lastname,', ', patient_firstname, ' ', patient_middle) AS name, philhealth_id, member_id, a.patient_id, expiry_date FROM `m_patient_philhealth` a
								JOIN m_patient b ON a.patient_id = b.patient_id
								JOIN m_family_members c ON a.patient_id = c.patient_id
								JOIN m_family_address d ON c.family_id = d.family_id
								JOIN m_lib_barangay e ON d.barangay_id = e.barangay_id
								WHERE philhealth_id = '".$result['philhealth_id']."' AND a.patient_id != ".$result['patient_id']." ORDER BY patient_lastname, patient_firstname ASC ";
						$query2 = $dbase->_dbQuery($sql2);
						 
						if(mysqli_num_rows($query2)!=0)
						{
							echo "<fieldset>";
							echo "<div class='center'><label><span class='center'>LIST OF MEMBER/DEPENDENT(S)</span></label><hr /><br />";
						
							while ($result2 = $dbase->_dbFetchArr($query2))
							{
								if($result2['member_id']==4 || $result2['member_id']==6 || $result2['member_id']==0)
								{
									echo "<a href='phmembership.php?id=".$result2['patient_id']."'>". $result2['name'] . "</a><br />";
								}
								else
								{
									echo "<a href='phmembership.php?id=".$result2['patient_id']."'  style='background-color:yellow;'>". $result2['name'] . "</a><br />";
								}
							}	
						
							echo "</div>";
							echo "<br />";
							echo "</fieldset>";
						}
					?>
					<br />
					<br />
					</div>	
					</form>
				</div>
			</div>
			<?php
				$memberType = $_POST['member_type'];
				$theDate = isset($_REQUEST["date1"]) ? $_REQUEST["date1"] : "$date_default";
				//echo $theDate;
				if (isset($_REQUEST['update']) && $_REQUEST['update']=='Update')
				{
					$sql = "UPDATE m_patient_philhealth SET member_id='$memberType'";
					if ($result['philhealth_id']!=$_POST['phID'])
					{
						$sql .= ", philhealth_id='".$_POST['phID']."'";
					}
					if ($result['expiry_date']!=$theDate)
					{
						$sql .= ", expiry_date='$theDate'";
					}
					
					$sql .=" WHERE patient_id='$patientID'";
					
					$query = $dbase->_dbQuery($sql);
					if($query)
					{
						echo "<script>alert('Record Has Been Updated!');</script>";
						echo "<META HTTP-EQUIV='refresh' CONTENT='0'>";
						//echo $sql;
					}
					else
						die ("An unexpected error occured while saving the record, Please try again!");
				} 
			?>

		</body>

	</html>