<?
  session_start();
  
  $dbname = 'chits_live';
  //$dbname = 'camiling_core_data';
  #$dbname2 = 'chitsquery';
  $_SESSION["query"] = $dbname;
  $dbuser = "root";
  $dbpwd = "root";

  $_SESSION["province"] = "Tarlac";
  $_SESSION["lgu"] = "Victoria";
  $_SESSION["barangay_loc"] = "Villa Aglipay";
  $_SESSION["barangay_id"] = "036917009";
  $_SESSION["doh_facility_code"] = "DOH000000000002522";
 
  $dbconn = mysql_connect("localhost",$dbuser,$dbpwd) or die(mysql_error());
  mysql_select_db($dbname,$dbconn);
?>
