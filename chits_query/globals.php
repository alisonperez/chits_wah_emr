<?
  session_start();
  
  $dbname = 'victoria2_09032013';
  //$dbname = 'camiling_core_data';
  #$dbname2 = 'chitsquery';
  $_SESSION["query"] = $dbname;
  $dbuser = "root";
  $dbpwd = "root";
  $_SESSION["province"] = "Zamboanga Sibugay";
  $_SESSION["lgu"] = "Ipil RHU";
  $_SESSION["barangay_loc"] = "Poblacion";
  $_SESSION["barangay_id"] = "098305023";
  $_SESSION["doh_facility_code"] = "DOH000000000007320";
 
  $dbconn = mysql_connect("localhost",$dbuser,$dbpwd) or die(mysql_error());
  mysql_select_db($dbname,$dbconn);
?>
