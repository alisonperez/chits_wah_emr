<?php
  class mmsync_layout{ 

       function mmsync_layout(){ 
         $this->module = "Mobile Midwife Sync";
         $this->author = "darth_ali";
         $this->date = "2012-11-19";
         $this->desc = "Mobile Midwife Sync is the component that resides in the server. Sync, cleans and validates tablets data";
       }

       function display_form_ip_port(){
          echo "<form action='$_SERVER[PHP_SELF]' method='POST' name='form_ip_port'>";  
          echo "<table class='connect_table'>";
          echo "<tr><td class='connect_table'>";
          echo "&nbsp;Enter the IP address of the device&nbsp;";
          echo "</td><td>";
          echo "<input type='text' name='txt_ip' size='12' value='$_SESSION[txt_ip]' style='height:40px;font-size:25px;font-weight:bold'></input>";
          echo "</td></tr>";
          echo "<tr><td class='connect_table'>&nbsp;Enter the port number (i.e. 5984)&nbsp;</td>";
          echo "<td><input type='text' name='txt_port' size='3' value='$_SESSION[txt_port]' style='height:40px;font-size:25px;font-weight:bold'></input></td>";
          echo "</tr>";

          echo "<tr>";
          echo "<td colspan='2' align='center'><input type='submit' name='submit_ip_port' value='SEARCH and CONNECT TO THE DEVICE' style='height:40px;font-size:20px'></td>";
          echo "</tr>";
		  echo "</table>";
          echo "</form>";
	   	  echo "<br>";
       }
  }
?>