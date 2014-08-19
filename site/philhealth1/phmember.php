<?php 
	include "class/dbConnect.php";
?>
<html>
		<head>
			<title>Philhealth Membership</title>
			<link rel='stylesheet' href='styles/style.css' type='text/css'  />
			<script>
			function submit()
			{
				var formObject = document.forms['phmember'];
	
				if(formObject!=0)
				{
					formObject.submit();
				}
			}
			
			</script>
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
					
					<form name='phmember' method='POST'>
					<div class='width750 center'>
					<br />
					<hr />
					<select name='type' style='text-align:center;' onchange='submit()'>
						<option value=0 <?php if ($_POST['type']==0) { echo 'selected';} else echo '';?>>All</option>
						<option value=1 <?php if ($_POST['type']==1) { echo 'selected';} else echo '';?>>Members</option>
						<option value=2 <?php if ($_POST['type']==2) { echo 'selected';} else echo '';?>>Dependents</option>
						<option value=3 <?php if ($_POST['type']==3) { echo 'selected';} else echo '';?>>Undefined Membership</option>
					</select>
					<hr />
					<br />
					</div>	
						<table id='philhealth'>
							<tr>
								<th width='230px' colspan='2'>Last Name</th>
								<th width='180px'>First Name</th>
								<th width='110px'>Barangay</th>
								<th width='60px'>Date Registered</th>
								<th width='140px'>Philhealth Number</th>
								<th width='70px'>Patient ID</th>
								<th width='60px'>Membership Type</th>
							</tr>
							<?php 
								
								$sql = "SELECT patient_lastname, patient_firstname, barangay_name, DATE_FORMAT(philhealth_timestamp,'%Y-%m-%d') AS date_registered, philhealth_id, member_id, a.patient_id FROM `m_patient_philhealth` a
								       JOIN m_patient b ON a.patient_id = b.patient_id
								       JOIN m_family_members c ON a.patient_id = c.patient_id
								       JOIN m_family_address d ON c.family_id = d.family_id
								       JOIN m_lib_barangay e ON d.barangay_id = e.barangay_id";
								switch($_POST['type'])
								{
									case 0:
										$sql .= " ORDER BY patient_lastname, patient_firstname ASC";
										break;
									case 1:
										$sql .= " WHERE member_id IN (1,2,3,5) ORDER BY patient_lastname, patient_firstname ASC";
										break;
									case 2:
										$sql .= " WHERE member_id = 4 ORDER BY patient_lastname, patient_firstname ASC";
										break;
									case 3:
										$sql .= " WHERE member_id IN (0,6) ORDER BY patient_lastname, patient_firstname ASC";
										break;
									default:
										break;
								}
								$query = $dbase->_dbQuery($sql);
								
								
								
								$count = 0;
								while($result = $dbase->_dbFetchArr($query))
								{
									$count++;
									$memberSQL = "SELECT member_id, member_label FROM `m_lib_philhealth_member_type` LIMIT 0,6";
									$memberQUERY = $dbase->_dbQuery($memberSQL);
									//$memberResult = $dbase->_dbFetchArr($memberQUERY);
									echo "<tr class='mouseover'>";
										echo "<td width='50px'>$count.</td>";
										echo "<td>".$result['patient_lastname']."</td>";
										echo "<td>".$result['patient_firstname']."</td>";
										echo "<td class='center'>".$result['barangay_name']."</td>";
										echo "<td class='center'>".$result['date_registered']."</td>";
										echo "<td class='center'>".$result['philhealth_id']."</td>";
										echo "<td class='center'>".$result['patient_id']."</td>";
										echo "<td class='center'>";
											
											/*echo "<select name='member_type' style='width:115px;' onchange='submit();'>";
												echo "<option value=0 ".($result['member_id']==0 ? 'selected' : '').">Not Selected</option>";
												
												while($memberResult = $dbase->_dbFetchArr($memberQUERY))
												{
													echo "<option value=".$memberResult['member_id']." ".($result['member_id']==$memberResult['member_id'] ? 'selected' : '').">".$memberResult['member_label']."</option>";
												}
											
											echo "</select>";
											echo "<input type='hidden' name='memberid' value=".$_POST['member_type'].">";*/
											
											while($memberResult = $dbase->_dbFetchArr($memberQUERY))
											{
												if ($result['member_id']==$memberResult['member_id'])
												{
													if ($memberResult['member_id']==1 || $memberResult['member_id']==2 || $memberResult['member_id']==3 || $memberResult['member_id']==5)
													{
														echo "<a href='phmembership.php?id=".$result['patient_id']."'>Member</a>";
													}
													else
														echo "<a href='phmembership.php?id=".$result['patient_id']."'>".$memberResult['member_label']."</a>";
														
												}
											}
											//echo $result['member_id'];
											if($result['member_id']==0)
											{
												echo "<font color='red'><a id='undefined' href='phmembership.php?id=".$result['patient_id']."' onClick='submit()'>Undefined</a></font>";
											}
											//echo "<input type='hidden' name='patientid' value=".$result['patient_id'].">";
											//echo "<input type='submit' name='save' value='Save'>";
											
										echo "</td>";
									echo "</tr>";
								}
								
							?>
						</table>
						
					</form>
				</div>
			</div>
		
		</body>

	</html>