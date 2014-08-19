<?
  session_start();
  
  $dbname = $_SESSION['dbname'];
  $_SESSION["query"] = $dbname;
  $dbuser = "root";
  $dbpwd = "root";
  $_SESSION["province"] = "Tarlac";
  $_SESSION["lgu"] = "Concepcion Rural Health Unit 2";
  $_SESSION["barangay_loc"] = "Balutu";
  $_SESSION["barangay_id"] = "036906031";
  $_SESSION["doh_facility_code"] = "DOH000000000001802";
 
  $dbconn = mysql_connect("localhost",$dbuser,$dbpwd) or die(mysql_error());
  mysql_select_db($dbname,$dbconn);
?>
