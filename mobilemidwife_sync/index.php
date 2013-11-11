<?php
  
  echo "<html>";
  echo "<head>";
  echo "<style type='text/css'>";
  echo ".connect_table { background-color:#6600FF; font-family: arial,sans-serif; color: white; border-radius: 15px; }";
  echo "td.connect_table { background-color:#6666FF; font-family: arial,sans-serif; color: white; font-size: 25px;}";

  echo ".view_tables { background-color:#6600FF; font-family: arial,sans-serif; font-size: 20px; color: white;border-radius: 10px; }";
  echo "tr.view_tables { background-color:#6666FF; font-family: arial,sans-serif; font-size: 20px; color: #FFFF33; text-align: center; font-weight: bold; }";
  echo "a:hover { color:yellow; }";
  echo "a:active { color:yellow; }";
  echo "a { color:white; }";
  echo ".message_info { font-family: arial,sans-serif; font-size: 13px; color: #006600; }";
  echo ".warning { font-family: arial,sans-serif; font-size: 13px; color: #FF0000; }";

  echo "</style>";
  echo "</head>";

  echo "<body>";

  require("./layout/class.mmsync_layout.php");
  require("./scripts/class.mmsync.php");

  $dbconn = mysql_connect('localhost',$_SESSION["dbuser"],$_SESSION["dbpass"]) or die("Please login to access the Mobile Midwife Synchronization interfaces".mysql_error());
  mysql_select_db($_SESSION["dbname"],$dbconn) or die("cannot select db");


  $layout = new mmsync_layout();
  $script = new mmsync();

  $json_str = '';
  $arr_json_id = array();
  $arr_json = array();

	
  //logic
  if($_POST["submit_ip_port"] || $_GET["action"]=='reload'):
    //print_r($_POST);
    //$script->connect_ip_address();

    if($json_str = $script->connect_ip_address()):
      if($json_str!=''): 
        $arr_json_id = $script->get_json_docs($json_str);
      endif;
    endif;

  endif;

  //interface
  if(isset($_SESSION[userid])): 
    $layout->display_form_ip_port();

    $arr_json = $script->get_json_elements($arr_json_id);  //saves all JSON files into the array

	 $script->check_mm_content($arr_json);
  else:
    echo "<font color='red'>Please login to access the Mobile Midwife Synchronization interfaces</font>";
  endif;

  echo "</body>";

  echo "</html>";

?>