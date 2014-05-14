<?
if (!isset($_SESSION["mainparam"])) {
    //session_register("mainparam");
	$_SESSION["mainparam"];
}
if (!isset($_SESSION["db"])) {
    //session_register("db");
	$_SESSION["db"];
}
if (!isset($_SESSION["validuser"])) {
    //session_register("validuser");
    $_SESSION["validuser"] = 0;
}
if (!isset($_SESSION["userid"])) {
    //session_register("userid");
	$_SESSION["userid"];
}
if (!isset($_SESSION["isadmin"])) {
    //session_register("isadmin");
	$_SESSION["isadmin"];
}
if (!isset($myencoding)) {
    //session_register("myencoding");
    $_SESSION["myencoding"] = "iso-8859-1";
}
if (!isset($user_lang)) {
    //session_register("myencoding");
    //session_register("user_lang");
    $_SESSION["user_lang"] = "english";
}
if (!isset($chits_debug)) {
    //session_register("debug");
    $_SESSION["chits_debug"] = false;
}
if (!isset($patient_id)) {
    //session_register("patient_id");
	$_SESSION["patient_id"];
}
if (!isset($consult_id)) {
    //session_register("consult_id");
	$_SESSION["consult_id"];
}
if (!isset($recordlevel)) {
    //session_register("recordlevel");
	$_SESSION["recordlevel"];
}
if (!isset($gamedb)) {
    //session_register("gamedb");
	$_SESSION["gamedb"];
}
if (!isset($datanode)) {
    //session_register("datanode");
    $_SESSION["datanode"] = array();
}
if (!isset($user_role)) {
    //session_register("user_role");
	$_SESSION["user_role"];
}
if (!isset($banner)) {
    //session_register("banner");
    $arr_banner = array('1','2','3','4');
    $_SESSION["banner"] = 'banner.'.array_rand($arr_banner).'.jpg';
}
?>
