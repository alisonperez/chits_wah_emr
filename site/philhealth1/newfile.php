<?php
	$sql = "SELECT * from m_patient_philhealth a JOIN m_patient b ON a.patient_id = b.patient_id";
	$result = mysqli_query(mysqli_connect("localhost","root","root","victoria2"),$sql) or die('Sorry cannot run query');
	$limit = 1;   
	$query_count = "SELECT * from m_patient_philhealth a JOIN m_patient b ON a.patient_id = b.patient_id";   
	$result_count = mysqli_query(mysqli_connect("localhost","root","root","victoria2"),$query_count);   
	$totalrows = mysqli_num_rows($result_count);   
	if(empty($page))   
	    $page = 1;   
	$limitvalue = $page * $limit - ($limit);   
	$query = "SELECT * from m_patient_philhealth a JOIN m_patient b ON a.patient_id = b.patient_id LIMIT $limitvalue, $limit ";   
	$result = mysqli_queryi(mysqli_connect("localhost","root","root","victoria2"),$query) or die("Error: " . mysql_error());   
	$count_result = mysqli_num_rows($result);   
	
	echo '<center>';
	echo '<table>';
	echo '<tr>';
	if(($totalrows - ($limit * $page)) > 0){   
	    $pagenext = $page + 1;
	    echo "<td><a href=\"$PHP_SELF?page=$pagenext&search=$search\">NEXT</a></td>";   
	}   
	if($page != 1){   
	    $pageprev = $page - 1;
	    echo "<td><a href=\"$PHP_SELF?page=$pageprev&search=$search\">PREV</a>&nbsp;</td>";  } 
	echo '</tr>';
	echo '</table>';
	echo '</center>';  
?>