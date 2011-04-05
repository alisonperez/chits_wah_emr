<?
  session_start();
  
  $dbname = 'wah_refresher';
  #$dbname2 = 'chitsquery';
  $_SESSION["query"] = $dbname;
  $dbuser = $_SESSION["dbuser"];
  $dbpwd = $_SESSION["dbpass"];
  $_SESSION["province"] = "Tarlac";
  $_SESSION["lgu"] = "Sample LGU";
  $dbconn = mysql_connect("localhost",$dbuser,$dbpwd) or die(mysql_error());
  mysql_select_db($dbname,$dbconn);
?>
                  