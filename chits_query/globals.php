<?
  session_start();
  
  $dbname = 'chits_live';
  //$dbname = 'camiling_core_data';
  #$dbname2 = 'chitsquery';
  $_SESSION["query"] = $dbname;
  $dbuser = "root";
  $dbpwd = "root";

  $_SESSION["province"] = "Pangasinan";
  $_SESSION["lgu"] = "Calasiao";
  $_SESSION["barangay_loc"] = "Poblacion East";
  $_SESSION["barangay_id"] = "015517018";
  $_SESSION["doh_facility_code"] = "DOH000000000006749";
 
  $dbconn = mysql_connect("localhost",$dbuser,$dbpwd) or die(mysql_error());
  mysql_select_db($dbname,$dbconn);
?>
