 <?php
   session_start();
   ob_start();
   include('../chits_query/layout/class.widgets.php');
   $widconn = new widgets();


   $dbconn = mysql_connect('localhost',$_SESSION["dbuser"],$_SESSION["dbpass"]) or die("Cannot query 4 ".mysql_error());
   mysql_select_db($_SESSION["dbname"],$dbconn) or die("cannot select db");
   $arr_px_id = array();
   $arr_px_id = unserialize(stripslashes($_GET["id"]));
   $cat = stripslashes($_GET["cat"]);

   //$arr_date_mc = array("Pregnant Women with 4 or more prenatal visits"=>"Pertains to the prenatal visit date wherein the 1-1-2 format visit has been achieved. This date would fall on the client's 3rd trimester","Pregnant Women given 2 doses of TT"=>"Pertains to the date wherein the 2nd dosage of Tetanus Toxoid was given. The administration should happened during the pregnancy of the client.","Pregnant Women given TT2 plus"=>"Pertains to the date wherein any of the 3rd,4th or 5th Tetanus Toxoid dosage has been given thereby achieving the TT2 plus status. The administration should happened during the pregnancy of the client.","Pregnant given complete iron with folic acid"=>"Pertains to the date wherein the 180 required tablet intakes for Iron with folic acid was completed. The completion of intake should happened during the pregnancy of the client","Pregnant given Vit. A"=>"Pertains to the date wherein the 200,000 IU intakes for Vitamin A was completed. The completion of intake should happened during the pregnancy of the client","Postpartum women with at least 2 PPV"=>"Pertains to the date of the second postpartum visit. To qualify, the first postpartum visit should happened 24 hours within the date of delivery and the second postpartum visit between 4 to 10 days from the date of delivery.","Postpartum women given complete iron"=>"Pertains to the date wherein the Vitamin A intake during postpartum phase was completed. To qualify, the postpartum client should be able to complete 200,000 of Vitamin A within 4 weeks (28 days) from the date of delivery.","Postpartum women given Vit. A"=>"Pertains to the date wherein the iront with folic acid intake during postpartum phase was completed. To qualify, the postpartum client should be able to complete 90 tablets of iron with folic acid within 3 months from the date of delivery.","Postpartum women initiated breastfeeding"=>"Pertains to the date wherein the the first breastfeeding session was done. To qualify, the date of breastfeeding coincide with the date of delivery.");

	$arr_date_mc = array("Pregnant Women with 4 or more prenatal visits"=>"Pertains to the prenatal visit date wherein the 1-1-2 format visit has been achieved. This date would fall on the client's 3rd trimester","Pregnant Women given 2 doses of TT"=>"Pertains to the date wherein the 2nd dosage of Tetanus Toxoid was given. The administration should happened during the pregnancy of the client.","Pregnant Women given TT2 plus"=>"Pertains to the date wherein any of the 3rd,4th or 5th Tetanus Toxoid dosage has been given thereby achieving the TT2 plus status. The administration should happened during the pregnancy of the client.","Pregnant given complete iron with folic acid"=>"Pertains to the date wherein the 180 required tablet intakes for Iron with folic acid was completed. The completion of intake should happened during the pregnancy of the client","Pregnant given Vit. A"=>"Pertains to the date wherein the 200,000 IU intakes for Vitamin A was completed. The completion of intake should happened during the pregnancy of the client","Postpartum women with at least 2 PPV"=>"Pertains to the date of the second postpartum visit. To qualify, the first postpartum visit should happened 24 hours within the date of delivery and the second postpartum visit between 4 to 10 days from the date of delivery.","Postpartum women given complete iron"=>"Pertains to the date wherein the iront with folic acid intake during postpartum phase was completed. To qualify, the postpartum client should be able to complete 90 tablets of iron with folic acid within 3 months from the date of delivery.","Postpartum women given Vit. A"=>"Pertains to the date wherein the Vitamin A intake during postpartum phase was completed. To qualify, the postpartum client should be able to complete 200,000 of Vitamin A within 4 weeks (28 days) from the date of delivery.","Postpartum women initiated breastfeeding"=>"Pertains to the date wherein the the first breastfeeding session was done. To qualify, the date of breastfeeding coincide with the date of delivery.","Women 10-49 years old women given iron supplementation"=>"","Number of deliveries"=>"Total number of NSD deliveries recorded.","Number of pregnant women"=>"Refers to the active pregnancies during the month being queried. Active pregnancy is between the last menstual period until the EDC or date of delivery.","Number of pregnant women tested for syphilis"=>"Pregnant women tested for syphilis as recorded under the MC Services","Number of pregnant women positive for syphilis"=>"Pregnant women tested positive for Syphilis. Recorded by checking the result checkbox in the MC services.","Number of pregnant women given penicillin"=>"Pregnant women given penicillin as recorded under the MC Services.");


   $arr_date_epi = array("BCG"=>"Pertains to the date wherein the BCG vaccine was administered","DPT1"=>"Pertains to the date wherein the DPT1 vaccine was administered","BCG"=>"Pertains to the date wherein the BCG vaccine was administered","DPT2"=>"Pertains to the date wherein the DPT2 vaccine was administered","DPT3"=>"Pertains to the date wherein the DPT3 vaccine was administered","OPV1"=>"Pertains to the date wherein the OPV1 vaccine was administered","OPV2"=>"Pertains to the date wherein the OPV2 vaccine was administered","OPV3"=>"Pertains to the date wherein the OPV3 vaccine was administered","Hepa B1 w/in 24 hrs"=>"Pertains to the date wherein the Hepa B1 w/in 24 hours vaccine was administered. To qualify, Hepa B1 should have been given at the date of birth or the day after.","Hepa B1 > 24 hrs"=>"Pertains to the date wherein the Hepa B1 more than 24 hours vaccine was administered.","Hepatitis B2"=>"Pertains to the date wherein the Hepa B2 vaccine was administered","Hepatitis B3"=>"Pertains to the date wherein the Hepa B3 vaccine was administered","Measles"=>"Pertains to the date wherein the Measles vaccine was administered","Rotavirus"=>"Pertains to the date wherein the Rotavirus vaccine was administered","Rotavirus 2"=>"Pertains to the date wherein the Rotavirus 2 vaccine was administered","Fully Immunized Child"=>"Pertains to the date wherein child was fully immunized. To qualify, all 11 antigens should have been provided before the age of 1. The last antigen that was administered to make the child FIC will be the basis of the FIC date","Completely Immunized Child (12-23 mos)"=>"Pertains to the date wherein child was completely immunized. To qualify, all 11 antigens should have been provided more than the age of 1. The last antigen that was administered to make the child CIC will be the basis of the CIC date");

	$arr_date_fp = array("Date of Registration"=>"Pertains to the date wherein the client was enrolled into the method.");

   $i = 1; 

   if(!empty($_SESSION["arr_px_labels"])):

   echo "<a href='javascript: history.go(-1)'><< Back to the Query Browser</a>";
   echo "<table><tr><td valign='top'>";
   echo "<table width='650' border='1' style=\"font-family: arial\">";
   echo "<tr align='center'><td colspan='7' style='background-color: #666666;color: #FFFF66;text-align: center;font-weight: bold;font-size:12pt;'><b>".$cat." from ".$_SESSION["sdate2"]." to ".$_SESSION["edate2"]."<b></td></tr>";
   echo "<tr style=\"background-color: #666666;color: #FFFF66;text-align: center;font-weight: bold;font-size:11pt;\"><td>#</td><td>Last Name</td><td>First Name</td><td>Patient ID</td><td>Date of Birth</td><td>Date Happened<sup>*</sup></td><td>Barangay</td></tr>";

   foreach($arr_px_id as $key=>$value){

	list($pxid,$date_happened) = explode('*',$value);
	
   	$q_px = mysql_query("SELECT * FROM m_patient WHERE patient_id='$pxid' ORDER by patient_dob ASC") or die("Cannot query: 78");

	if(mysql_num_rows($q_px)!=0): 

	while($r_px = mysql_fetch_array($q_px)){ 
		$q_brgy = mysql_query("SELECT a.barangay_name FROM m_lib_barangay a, m_family_members b, m_family_address c WHERE b.patient_id='$pxid' AND b.family_id=c.family_id AND a.barangay_id = c.barangay_id") or die("Cannot query 20: ".mysql_error());

		list($brgy_name) = mysql_fetch_array($q_brgy);

		echo "<tr style='background-color: #666666; color: #FFFFFF; font-weight:bold; white-space: nowrap; font-size: 12pt;' align='center'><td>$i</td><td>".$r_px[patient_lastname]."</td>";
       		echo "<td>".$r_px[patient_firstname]."</td>";
		echo "<td>".$pxid."</td>";		
		echo "<td>".$r_px[patient_dob]."</td>";
		echo "<td>".$date_happened."</td>";
		echo "<td>".$brgy_name."</td>";
    	echo "</tr>";
	}
	$i++;


	endif;
   }

   echo "</table></td>";
   
   echo "<td valign='top'>";
	echo "<table width='600' border='1' style=\"font-family: arial\">";
	echo "<tr><td colspan='2' style='background-color: #666666;color: #FFFF66;text-align: center;font-weight: bold;font-size:12pt;'><sup>*</sup>DATE HAPPENED - pertains to the date of wherein the indicator has been achieved. Condition varies depending on the indicator. </td></tr>";

	if(isset($_GET["prog"])):
		$arr_to_display = array();
   		if($_GET["prog"]=='mc'):
			$arr_to_display = $arr_date_mc;	
		elseif($prog=='epi'):
			$arr_to_display = $arr_date_epi;	
		elseif($prog=='fp'):
			$arr_to_display = $arr_date_fp;
		else:

		endif;
	
		if(isset($arr_to_display)):

		foreach($arr_to_display as $key=>$value){ 

			$color = ((trim($cat)==$key)?'#FFFF66;':'#FFFFFF;');

			echo "<tr colspan='2'>"; 
			echo "<td  style='background-color: #666666; color: $color font-weight:bold; font-size: 11pt;'>".$key."</td>";
			echo "<td  style='background-color: #666666; color: #FFFFFF; font-weight:bold; font-size: 11pt;'>".$value."</td>";
			echo "</tr>";
		}

		endif;
	endif;
	echo "</table>";
   echo "</td></tr>";
   echo "</table>";

   $widconn->footer();
   else:

   endif;
?>
