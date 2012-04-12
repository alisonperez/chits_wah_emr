<?
  session_start();
  
  $dbname = 'paniqui_0329';
  #$dbname2 = 'chitsquery';
  $_SESSION["query"] = $dbname;
  $dbuser = "root";
  $dbpwd = "root";
  $_SESSION["province"] = "Tarlac";
  $_SESSION["lgu"] = "Paniqui";
  $dbconn = mysql_connect("localhost",$dbuser,$dbpwd) or die(mysql_error());
  mysql_select_db($dbname,$dbconn);
?>
