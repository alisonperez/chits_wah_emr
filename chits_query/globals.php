<?
  session_start();
  
  $dbname = 'chits_server';
  #$dbname2 = 'chitsquery';
  $_SESSION["query"] = $dbname;
  $dbuser = "root";
  $dbpwd = "root";
  $_SESSION["province"] = "Tarlac";
  $_SESSION["lgu"] = "Paniqui";
  $_SESSION["barangay_loc"] = "Poblacion Norte";
  $_SESSION["barangay_id"] = "036910024";
  
  $dbconn = mysql_connect("localhost",$dbuser,$dbpwd) or die(mysql_error());
  mysql_select_db($dbname,$dbconn);
?>
