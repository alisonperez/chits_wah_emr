<?php
  
  echo "<html>";
  
  echo "<head>";
  echo "<title>Search Patient</title>";
  echo "<script language='Javascript' src='../js/functions.js'></script>";
  echo "</head>";
  
  echo "<body>";
  if(!empty($_SESSION[userid])):
    $dbconn = mysql_connect('localhost',$_SESSION["dbuser"],$_SESSION["dbpass"]) or die("Cannot query 4 ".mysql_error());
    mysql_select_db($_SESSION["dbname"],$dbconn) or die("cannot select db");
    
    echo "<form name='form_searchpx' method='post' action='$_SERVER[PHP_SELF]?arg1=$_GET[arg1]&arg2=$_GET[arg2]&arg3=$_GET[arg3]'>";
    //echo "First Name <input type='text' name='txt_first' size='10'></input><b>OR</b><br>";
    
    echo "Last Name  <input type='text' name='txt_last' size='10'></input><br><br>";
    
    echo "<input type='submit' value='Search Patient' name='search_submit'></input><br><br>";
    
    if($_POST["search_submit"]):

      $q_px = mysql_query("SELECT patient_id,patient_firstname,patient_lastname FROM m_patient WHERE patient_lastname LIKE '%$_POST[txt_last]%' ORDER by patient_firstname ASC, patient_id ASC") or die("Cannot query: 3");

      if(mysql_num_rows($q_px)!=0):
        while(list($pxid,$first,$last)=mysql_fetch_array($q_px)){
          echo "<a href=\"javascript:pick('$pxid','$first','$last','$_GET[arg1]','$_GET[arg2]','$_GET[arg3]')\">".$pxid.' '.$first.' '.$last."</a><br>";
        }
      else:
        echo "<font color='red'><b>No result/s found</b></font>";
      endif;
    endif;
    
      echo "<br><b><br><br>If the person you are searching is not in the database, please click <a href=\"javascript:pick(0,'Others / ','NA','$_GET[arg1]','$_GET[arg2]','$_GET[arg3]')\">OTHERS / NA</a> for the meantime. Update this record as soon as you have added the person's profile.";
      
    echo "</form>";
  else:
    echo "<font color='red'>Unauthorized access.</font>";  
  endif;

  echo "</body>";
  echo "</html>";

?>