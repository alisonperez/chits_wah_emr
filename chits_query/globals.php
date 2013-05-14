<?
  session_start();
  
  $dbname = 'paniqui1_05082013';
  #$dbname2 = 'chitsquery';
  $_SESSION["query"] = $dbname;
  $dbuser = "root";
  $dbpwd = "root";
  $_SESSION["province"] = "Tarlac";
  $_SESSION["lgu"] = "Moncada RHU 1";
  $_SESSION["barangay_loc"] = "Poblacion 1";
  $_SESSION["barangay_id"] = "036915009";
  $_SESSION["doh_facility_code"] = "DOH000000000007151";
 
  $dbconn = mysql_connect("localhost",$dbuser,$dbpwd) or die(mysql_error());
  mysql_select_db($dbname,$dbconn);
?>
