<html>
	<head>
	</head>
	<body>
		<?php
			$limit = 1;
			$page = 0;
			
			$sql = "SELECT * FROM m_patient_philhealth a JOIN m_patient b ON a.patient_id = b.patient_id LIMIT $page, $limit";
			$query = mysqli_query(mysqli_connect("localhost","root","root","victoria2"),$sql) or die(mysql_error());
			$fetch = mysqli_fetch_array($query);
			
			if ($page > 1)
			{
			     for ($i = 1; $i < ($page - 1); $i++)
			     {
			          print "<td><a href=\"$PHP_SELF?page=$i&search=$search\">$i</a></td>"; 
			     }
			}
			if ($page < $limit){
			   for ($i = $page; $i < $limit; $i ++)
			   {
			       print "<td><a href=\"$PHP_SELF?page=$i&search=$search\">$i</a></td>"; 
			    }
			}  
			
			/*if(!isset($_REQUEST['id']))
			{
				$query1 = "SELECT * FROM m_patient_philhealth a JOIN m_patient b ON a.patient_id = b.patient_id ORDER BY a.patient_id ASC";
				$result1=mysqli_query(mysqli_connect("localhost","root","root","victoria2"),$query1) or die(mysql_error());
				$fetch = mysqli_fetch_array($result1);
			}
			if(isset($_REQUEST['id']) && $_REQUEST['action']=='previous')
			{
				$query1 = "SELECT * FROM m_patient_philhealth a JOIN m_patient b ON a.patient_id = b.patient_id WHERE a.patient_id = (SELECT MIN(patient_id) FROM m_patient_philhealth WHERE patient_id < '".$_REQUEST['id']."') ORDER BY a.patient_id LIMIT 1";
				$result1=mysqli_query(mysqli_connect("localhost","root","root","victoria2"),$query1) or die(mysql_error());
				$fetch = mysqli_fetch_array($result1);
			}
			if(isset($_REQUEST['id']) && $_REQUEST['action']=='next')
			{
				$query1 = "SELECT * FROM m_patient_philhealth a JOIN m_patient b ON a.patient_id = b.patient_id WHERE a.patient_id > '".$_REQUEST['id']."' ORDER BY a.patient_id LIMIT 1";
				$result1=mysqli_query(mysqli_connect("localhost","root","root","victoria2"),$query1) or die(mysql_error());
				$fetch = mysqli_fetch_array($result1);
			}*/
		
		
			/*while(is_array($fetch)) 
			{
				$term_array[] = $fetch['patient_id'];
				$fetch = mysql_fetch_array($result1);
				print_r($term_array);
			}
			
			while(list($k,$v)=each($term_array))
			{ 
				if($id==$v)
				{
					$pk=$k-1;
					$nk=$k+1; 
				}
			}
			
			reset($term_array);
			
			while(list($k,$v)=each($term_array))
			{
				if($k==$pk)
				{ 
					$intPrevID = $v; 
				}
			}
			
			reset($term_array);
			
			while(list($k,$v)=each($term_array))
			{
				if($k==$nk)
				{ 
					$intNextID = $v; 
				}
			}
			
			if (empty($intPrevID)) 
			{ 
				$intPrevID = end($term_array); 
			} 
			else 
			{ 
				$intPrevID = $intPrevID; 
			}
			if (empty($intNextID)) 
			{ 
				$intNextID = reset($term_array); 
			} 
			else 
			{ 
				$intNextID = $intNextID; 
			}*/
			
		?>
		Patient ID: <input type='text' name='pxID' <?php echo "value='".$fetch['patient_id']."'";?> />
		Patient Name: <input type='text' name='pxName' <?php echo "value='".$fetch['patient_firstname']."'";?> />
		Patient Last Name:  <input type='text' name='pxLastName' <?php echo "value='".$fetch['patient_lastname']."'";?> />
		<a <?php echo "href='record.php?id=".$fetch['patient_id']."&action=previous'";?> >Previous</a>
		<a <?php echo "href='record.php?id=".$fetch['patient_id']."&action=next'";?> >Next</a>
	</body>
</html>
