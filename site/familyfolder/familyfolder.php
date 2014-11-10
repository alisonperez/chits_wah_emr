<?php 
	$con = mysqli_connect("localhost",$_SESSION['dbuser'],$_SESSION['dbpass'],$_SESSION['dbname']);
	if (isset($_POST['barangay']) && $_POST['barangay'] != 'all' )
	{
		$sql = "SELECT * FROM `m_family` a JOIN `m_family_address` b ON a.family_id = b.family_id JOIN `m_lib_barangay` c ON b.barangay_id = c.barangay_id WHERE c.barangay_id = '".$_POST['barangay']."' ORDER BY c.barangay_id, address ASC";
	}
	else
	{
		$sql = "SELECT * FROM `m_family` a JOIN `m_family_address` b ON a.family_id = b.family_id JOIN `m_lib_barangay` c ON b.barangay_id = c.barangay_id ORDER BY c.barangay_id, address ASC";
	}
	
	$query = mysqli_query($con,$sql);
	
?>
<html>
	<head>
		<title>Family Folder</title>
		<style type="text/css">
			body, table{
				font:normal 90% sans-serif;
				margin: 0 auto;
				padding: 0;
				width: 898px;
			}
			table, tr, td, th{
				border: 1px solid #000000; border-collapse:collapse;
			}
			.center{
				text-align: center;
			}
			.mousehover:hover{
				background-color:yellow;
			}
			.nomember{
				background-color:red;
			}
		</style>
		
		<script>
			function submit()
			{
				var formObject = document.forms['selectBarangay'];
	
				if(formObject!=0)
				{
					formObject.submit();
				}
			}
			
			</script>
	</head>
	<body>
		<form method='post' name='selectBarangay'>
			<select name='barangay' onchange='submit()'>
				<option value='all'>All Barangay</option>
				<?php 
					$bgySQL = "SELECT barangay_id, barangay_name FROM `m_lib_barangay`";
					$bgyQUERY = mysqli_query($con,$bgySQL);
					while ($bgyRESULT = mysqli_fetch_array($bgyQUERY))
					{
						echo "<option value='".$bgyRESULT['barangay_id']."' ".($_POST['barangay']==$bgyRESULT['barangay_id'] ? 'selected' : '').">".$bgyRESULT['barangay_name']."</option>";
					}
				?>
			</select>
		</form>
		<table>
			<tr>
				<th>Family Id</th>
				<th>Address</th>
				<th>Barangay</th>
				<th>Cellphone Number</th>
				<th>Family Members</th>
			</tr>
			<?php while($result = mysqli_fetch_array($query)){?>
			<tr class='mousehover'>
				<td><?php echo $result['family_id'];?></td>
				<td class='center'><?php echo $result['address'];?></td>
				<td class='center'><?php echo $result['barangay_name'];?></td>
				
					<?php 
						$memberSQL = "SELECT CONCAT(patient_lastname,', ', patient_firstname, ' ', patient_middle) AS name, b.patient_cellphone AS cp FROM `m_family_members` a JOIN `m_patient` b ON a.patient_id = b.patient_id WHERE family_id = ".$result['family_id']." ORDER BY patient_lastname, patient_firstname, patient_middle ASC";
						$memberQUERY = mysqli_query($con,$memberSQL);
						$numberQUERY = mysqli_query($con,$memberSQL);
						if(mysqli_num_rows($memberQUERY)!=0)
						{
							
							echo "<td>";
								while ($numberRESULT = mysqli_fetch_array($numberQUERY))
								{
									echo $numberRESULT['cp'] . "<br />";
								}
							echo "</td>";
							
							echo "<td>";
								while ($memberRESULT = mysqli_fetch_array($memberQUERY))
								{
									echo $memberRESULT['name'] . "<br />";
								}
							echo "</td>";
						}
						else 
						{
							echo "<td class='nomember'><b>NO MEMBER</b></td>";							
						}
					?>
			</tr>
			<?php }?>
		</table>
	</body>
</html>
