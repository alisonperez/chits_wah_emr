<?php
   session_start();
   ob_start();

   $dbconn = mysql_connect('localhost',$_SESSION["dbuser"],$_SESSION["dbpass"]) or die("Cannot query 4 ".mysql_error());
   mysql_select_db($_SESSION["dbname"],$dbconn) or die("cannot select db");
   $arr_px_id = array();
   $arr_px_id = unserialize(stripslashes($_GET["id"]));
   $cat = stripslashes($_GET["cat"]);

   $i = 1; 

   if(!empty($_SESSION["arr_px_labels"])):

   echo "<table border='1'>";
   echo "<tr align='center'><td colspan='5'><b>".$cat." from ".$_SESSION["sdate2"]." to ".$_SESSION["edate2"]."<b></td></tr>";
   echo "<tr><td>#</td><td>Last Name</td><td>First Name</td><td>Date of Birth</td><td>Barangay</td></tr>";

   foreach($arr_px_id as $key=>$value){
	
   	$q_px = mysql_query("SELECT * FROM m_patient WHERE patient_id='$value[0]' ORDER by patient_dob ASC") or die("Cannot query: 78");
	
	while($r_px = mysql_fetch_array($q_px)){
		$q_brgy = mysql_query("SELECT a.barangay_name FROM m_lib_barangay a, m_family_members b, m_family_address c WHERE b.patient_id='$value[0]' AND b.family_id=c.family_id AND a.barangay_id = c.barangay_id") or die("Cannot query 20: ".mysql_error());

		list($brgy_name) = mysql_fetch_array($q_brgy);

		echo "<tr><td>$i</td><td>".$r_px[patient_firstname]."</td>";
       		echo "<td>".$r_px[patient_lastname]."</td>";
		echo "<td>".$r_px[patient_dob]."</td>";
		echo "<td>".$brgy_name."</td>";
       		echo "</tr>";
	}
	$i++;
   }

   echo "</table>";

   else:

   endif;
?>