<?php
	session_start();
echo "<html>";
  echo "<head>";
  echo "<style type='text/css'>";
  echo ".connect_table { background-color:#6600FF; font-family: arial,sans-serif; color: white; border-radius: 15px; }";
  echo "td.connect_table { background-color:#6666FF; font-family: arial,sans-serif; color: white; font-size: 25px;}";

  echo ".view_tables { background-color:white; font-family: arial,sans-serif; font-size: 20px; color: white;border-radius: 10px; }";
  echo "tr.view_tables { background-color:#336699; font-family: arial,sans-serif; font-size: 20px; color: #FFFF33; text-align: center; font-weight: bold; }";
  echo "a:hover { color:yellow; }";
  echo "a:active { color:yellow; }";
  echo "a { color:white; }";
  echo ".message_info { font-family: arial,sans-serif; font-size: 13px; color: #006600; }";
  echo ".warning { font-family: arial,sans-serif; font-size: 13px; color: #FF0000; }";
  echo ".tr_inner_label { background-color:#6600FF; font-family: arial,sans-serif; font-size: 15px; color: #FFFF33; text-align: center; font-weight: bold; }";
  echo ".tr_inner { background-color:#6666FF; font-family: arial,sans-serif; font-size: 15px; color: white; text-align: center; }";

  echo "</style>";
  echo "</head>";

	$dbconn = mysql_connect('localhost',$_SESSION["dbuser"],$_SESSION["dbpass"]) or die("Please login to access the Mobile Midwife Synchronization interfaces".mysql_error());
	mysql_select_db($_SESSION["dbname"],$dbconn) or die("cannot select db");

	$q_brgy = mysql_query("SELECT barangay_id, barangay_name FROM m_lib_barangay") or die("Cannot query 7: ".mysql_error());


	echo "<html>";
	echo "<head>";
	echo "<script language='Javascript'>";
?>

	function select_relative(family_id,pxid,fname,lname){
		if(window.confirm("Are you sure you wanted to add " + fname + lname + " to the selected family?")){
			document.form_search_family.family_id.value = family_id;
			document.form_search_family.px_id.value = pxid;

			document.forms["form_search_family"].submit();
		}		
	}

	window.onunload = function(){
	  window.opener.location.reload();
	};

<?php

	echo "</script>";
	echo "</head>";
	echo "<body>";
  if(isset($_SESSION[userid]) && mysql_num_rows($q_brgy)!=0): 


	echo "<form action='$_SERVER[PHP_SELF]?fname=$_GET[fname] &lname=$_GET[lname]&brgy=$_GET[brgy]&address=$_GET[address]&pxid=$_GET[pxid]' method='POST' name='form_search_family'>";

	echo "<input type='hidden' name='family_id' value=''></input>";	
	echo "<input type='hidden' name='px_id' value=''></input>";	

	echo "<table class='view_tables'>";
	echo "<tr>";
	
	echo "<tr class='view_tables'><td colspan='2'>&nbsp;Search family folders with similar lastname and barangay&nbsp;</td></tr>";
	
	echo "<tr>";
	echo "<td class='tr_inner_label'>Lastname</td>";
	echo "<td><input type='text' size='20' name='txt_lname' value='$_GET[lname]'></input></td>";
	echo "</tr>";

	echo "<tr>";
	echo "<td class='tr_inner_label'>Barangay</td>";
	echo "<td>";

	echo "<select name='sel_brgy' value='1'>";
	while(list($brgy_id,$brgy_name)=mysql_fetch_array($q_brgy)){
		if($brgy_name==$_GET["brgy"]):
			echo "<option value='$brgy_id' SELECTED>$brgy_name</option>";
		else:
			echo "<option value='$brgy_id'>$brgy_name</option>";
		endif;
	}
	echo "</select>";

	echo "</td>";
	echo "</tr>";
	
	echo "<tr><td colspan='2' align='center'>";
	echo "<input type='submit' name='submit_search' value='Search Relatives'></input>";
	echo "</td></tr>";

	echo "</table>";

	echo "</form>";

	show_result();	
	insert_select_patient();
else:
    echo "<font color='red'>Please login to access the Mobile Midwife Synchronization interfaces</font>";	
endif;

	function show_result(){
		$arr_family_id = array();
		if($_POST["submit_search"]=="Search Relatives"):
		
			//print_r($_POST);

			$q_family = mysql_query("SELECT DISTINCT a.family_id FROM m_family_members a, m_patient b, m_family_address c, m_lib_barangay d  WHERE a.patient_id=b.patient_id AND b.patient_lastname='$_POST[txt_lname]' AND b.patient_id!='$_GET[pxid]'") or die("Cannot query 57: ".mysql_error());

			if(mysql_num_rows($q_family)==0):
				echo "<script language='Javascript'>";
				echo "if(window.confirm('No family with the surname ".strtoupper($_POST[txt_lname])." was found. Do you wish to return to the previous window? Otherwise, press Cancel and search another surname.')){";
				echo "window.close()";
				echo "}";
				echo "</script>";

			else:
				echo "<table>";
				echo "<tr class='tr_inner_label'><td>&nbsp;Last Name&nbsp;</td>";
				echo "<td>&nbsp;Barangay&nbsp;</td>";
				echo "<td>&nbsp;Members&nbsp;</td>";				
				echo "<td>&nbsp;Action&nbsp;</td>";				
				echo "</tr>";

				while(list($family_id,$patient_id)=mysql_fetch_array($q_family)){
					$arr_relative = array();
					$q_brgy = mysql_query("SELECT a.barangay_name FROM m_lib_barangay a, m_family_address b WHERE b.family_id='$family_id' AND a.barangay_id=b.barangay_id") or die("Cannot query 78: ".mysql_error());
					list($brgy_name) = mysql_fetch_array($q_brgy);

					$q_members = mysql_query("SELECT a.patient_firstname FROM m_patient a, m_family_members b WHERE b.family_id='$family_id' AND a.patient_id=b.patient_id") or die("Cannot query 78: ".mysql_error());

					while(list($fname)=mysql_fetch_array($q_members)){
						array_push($arr_relative,$fname);
					}

					$str_relatives = implode(",",$arr_relative);

					echo "<tr class='tr_inner'>";
					echo "<td>".$_POST["txt_lname"]."</td>";
					echo "<td>".$brgy_name."</td>";
					echo "<td>".$str_relatives."</td>";
					echo "<td>";
					echo "<a onclick=\"select_relative('$family_id','$_GET[pxid]','$_GET[fname]','$_GET[lname]')\">Select Family</a>";
					echo "</td>";
					echo "</tr>";
				}

				echo "</table>";
			endif;

		endif;	
	}
	
	function insert_select_patient(){
		if(!empty($_POST["family_id"]) && !empty($_POST["px_id"])):
			$insert_family = mysql_query("INSERT INTO m_family_members SET family_id='$_POST[family_id]',family_role='member',patient_id='$_POST[px_id]'") or die("Cannot query 132: ".mysql_error());

			if($insert_family):
				echo "<script language='Javascript'>";
					echo "window.close()";
				echo "</script>";
			endif;
		endif;		 
	}


	echo "</body>";
	echo "</html>";
?>