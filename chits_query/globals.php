<?php
session_start();

$dbname = $_SESSION["dbname"];
$_SESSION["query"] = $dbname;
$dbuser = $_SESSION["dbuser"];
$dbpwd = $_SESSION["dbpass"];
$_SESSION["province"] = "Western Samar";
$_SESSION["lgu"] = "Matuguinao Rural Health Unit I";
$_SESSION["lbarangay_id"] = "036917007";
$_SESSION["barangay_loc"] = "Bulo";
$_SESSION["doh_facility_code"] = "DOH000000000007868";
$dbconn = mysql_connect("localhost",$dbuser,$dbpwd) or die(mysql_error());
mysql_select_db($dbname,$dbconn)

?>
