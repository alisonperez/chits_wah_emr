<?php
   session_start();
   ob_start();

   $dbconn = mysql_connect('localhost',$_SESSION["dbuser"],$_SESSION["dbpass"]) or die("Cannot query 4 ".mysql_error());
   mysql_select_db($_SESSION["dbname"],$dbconn) or die("cannot select db");
   $arr_px_id = array();
   $arr_px_id = unserialize(stripslashes($_GET["id"]));
   $cat = stripslashes($_GET["cat"]);
	
   $arr_date_mc = array("Pregnant Women with 4 or more prenatal visits"=>"Pertains to the prenatal visit date wherein the 1-1-2 format visit has been achieved. This date would fall on the client's 3rd trimester","Pregnant Women given 2 doses of TT"=>"Pertains to the date wherein the 2nd dosage of Tetanus Toxoid was given. The administration should happened during the pregnancy of the client.","Pregnant Women given TT2 plus"=>"Pertains to the date wherein any of the 3rd,4th or 5th Tetanus Toxoid dosage has been given thereby achieving the TT2 plus status. The administration should happened during the pregnancy of the client.","Pregnant given complete iron with folic acid"=>"Pertains to the date wherein the 180 required tablet intakes for Iron with folic acid was completed. The completion of intake should happened during the pregnancy of the client","Pregnant given Vit. A"=>"Pertains to the date wherein the 200,000 IU intakes for Vitamin A was completed. The completion of intake should happened during the pregnancy of the client","Postpartum women with at least 2 PPV"=>"","Postpartum women given complete iron"=>"","Postpartum women given Vit. A"=>"","Postpartum women initiated breastfeeding"=>"");

   $i = 1; 

   if(!empty($_SESSION["arr_px_labels"])):

   echo "<table border='1'>";
   echo "<tr align='center'><td colspan='7'><b>".$cat." from ".$_SESSION["sdate2"]." to ".$_SESSION["edate2"]."<b></td></tr>";
   echo "<tr><td>#</td><td>Last Name</td><td>First Name</td><td>Patient ID</td><td>Date of Birth</td><td>Date Happened<sup>*</sup></td><td>Barangay</td></tr>";

   foreach($arr_px_id as $key=>$value){

	list($pxid,$date_happened) = explode('*',$value);
	
   	$q_px = mysql_query("SELECT * FROM m_patient WHERE patient_id='$pxid' ORDER by patient_dob ASC") or die("Cannot query: 78");
	
	while($r_px = mysql_fetch_array($q_px)){ 
		$q_brgy = mysql_query("SELECT a.barangay_name FROM m_lib_barangay a, m_family_members b, m_family_address c WHERE b.patient_id='$pxid' AND b.family_id=c.family_id AND a.barangay_id = c.barangay_id") or die("Cannot query 20: ".mysql_error());

		list($brgy_name) = mysql_fetch_array($q_brgy);

		echo "<tr><td>$i</td><td>".$r_px[patient_firstname]."</td>";
       		echo "<td>".$r_px[patient_lastname]."</td>";
		echo "<td>".$pxid."</td>";		
		echo "<td>".$r_px[patient_dob]."</td>";
		echo "<td>".$date_happened."</td>";
		echo "<td>".$brgy_name."</td>";
       		echo "</tr>";
	}
	$i++;
   }

   echo "</table>";
   echo "<br><br>";

	echo "<table width='1000' border='1'>";
	echo "<tr><td colspan='2'>DATE HAPPENED - pertains to the date of wherein the indicator has been achieved. Condition varies depending on the indicator. </td></tr>";

	if(isset($_GET["prog"])):
		$arr_to_display = array();
   		if($_GET["prog"]=='mc'):
			$arr_to_display = $arr_date_mc;	
		elseif($prog=='epi'):

		else:

		endif;
	
		if(isset($arr_to_display)):

		foreach($arr_to_display as $key=>$value){
			echo "<tr colspan='2'>";
			echo "<td>".$key."</td>";
			echo "<td>".$value."</td>";
			echo "</tr>";
		}

		endif;
	endif;
	echo "</table>";
   else:

   endif;
?>