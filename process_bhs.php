<?php
//alison perez <perez.alison@gmail.com> August 2011 -- WAH Project
//this form facilitates the adding, assigning/unassigning of barangay and deleting barangay health stations

echo "<html><head>";
echo "<title>Process Barangay Health Station</title>";
echo "</head>";

  if(!empty($_SESSION[userid])):


    $dbconn = mysql_connect('localhost',$_SESSION["dbuser"],$_SESSION["dbpass"]) or die("Cannot query 4 ".mysql_error());
    mysql_select_db($_SESSION["dbname"],$dbconn) or die("cannot select db");

    if($_POST['btn_bhs']): 
	$q_bhs = mysql_query("INSERT INTO m_lib_bhs SET bhs_name='$_POST[txt_bhs]',barangay_id='$_POST[sel_brgy]',user_id='$_POST[sel_user]',facility_id='$_GET[facid]'") or die("Cannot query 12: ".mysql_error());

	$bhs_id = mysql_insert_id();
	
	foreach($_POST['brgy_bhs'] as $key=>$value){ echo $value.'<br>';
		$q_bhs_brgy = mysql_query("INSERT INTO m_lib_bhs_barangay SET bhs_id='$bhs_id',barangay_id='$value'") or die("Cannot query 14: ".mysql_error());
	}
    elseif($_GET['action']=='delete'):
	$del_bhs = mysql_query("DELETE FROM m_lib_bhs WHERE bhs_id='$bhs_id'") or die("Cannot query 24: ".mysql_error());
	$del_bhs_brgy = mysql_query("DELETE FROM m_lib_bhs_barangay WHERE bhs_id='$bhs_id'") or die("Cannot query 25: ".mysql_error());
    else:

    endif; 

    echo "<form action='$_SERVER[PHP_SELF]?facid=$_GET[facid]' method='POST'>";
    echo "<table style='font-family: arial;background-color:#99CCCC'>";
    echo "<tr><td valign='top'>";
    echo "<table>";
    echo "<thead><td colspan='2' align='center' style='background-color: #666666;color: #FFFF66;text-align: center;font-weight: bold;font-size:10pt;'>RECORD BHS DETAILS</td></thead>";
    echo "<tr><td>Name of the BHS</td>";
    echo "<td>";
    echo "<input type='text' name='txt_bhs' />";
    echo "</td>";

    echo "<tr><td>Assigned Midwife/Staff </td>";

    $q_user = mysql_query("SELECT user_id,user_firstname,user_lastname FROM game_user ORDER by user_lastname ASC, user_firstname ASC") or die("Cannot query 49: ".mysql_error());
   //list($userid,$fname,$lname) = mysql_fetch_array($q_user);

    echo "<td><select size='1' name='sel_user'>";
    while($r_res = mysql_fetch_array($q_user)){
  	echo "<option value='$r_res[user_id]'>$r_res[user_lastname], $r_res[user_firstname]</option>";
    }
    echo "</select>";
    echo "</td></tr>";


	echo "<tr><td valign='top'>Select Catchment Barangays</td><td>";
	$q_hf = mysql_query("SELECT a.barangay_name,a.barangay_id FROM m_lib_barangay a,m_lib_health_facility_barangay b WHERE b.facility_id='$_GET[facid]' AND a.barangay_id=b.barangay_id") or die("Cannot query 52: ".mysql_error());

	
	while($r_hf = mysql_fetch_array($q_hf)){ 
		echo "<input type='checkbox' name='brgy_bhs[]' value='$r_hf[barangay_id]'>$r_hf[barangay_name]</input><br>";
	}

	echo "</td></tr>";

	echo "<tr>";
	echo "<td>Barangay location of BHS</td>";
	echo "<td><select size='1' name='sel_brgy'>";
	$q_hf2 = mysql_query("SELECT a.barangay_name,a.barangay_id FROM m_lib_barangay a,m_lib_health_facility_barangay b WHERE b.facility_id='$_GET[facid]' AND a.barangay_id=b.barangay_id") or die("Cannot query 52: ".mysql_error());
	
	while($r_hf2=mysql_fetch_array($q_hf2)){
		echo "<option value='$r_hf2[barangay_id]'>$r_hf2[barangay_name]</option>";
	}
	echo "</select>";
	echo "</td></tr>";


	echo "<tr><td colspan='2' align='center'>";

	echo "<input type='submit' name='btn_bhs' value='Add BHS'></input>&nbsp;";
	echo "<input type='reset' name='clear' value='Clear'></input>";
	echo "</td></tr>";
	echo "</table>";

	echo "</td>";

	echo "<td valign='top'>"; 
	echo "<table border='1'>";
	echo "<tr><td colspan='5' style='background-color: #666666;color: #FFFF66;text-align: center;font-weight: bold;font-size:10pt;'>LIST OF BARANGAY HEALTH STATIONS</td></tr>";
	echo "<tr style='background-color: #666666;color: #FFFF66;text-align: center;font-weight: bold;font-size:10pt;'><td>Name of BHS</td>";
	echo "<td>Location of the BHS</td>";
	echo "<td>Assigned Midwife</td>";
	echo "<td>Catchment Barangays</td>";
	echo "<td>Action</td>";
	echo "</tr>";

	$q_bhs = mysql_query("SELECT bhs_id, bhs_name, user_id,barangay_id FROM m_lib_bhs WHERE facility_id='$_GET[facid]' ORDER by bhs_name ASC") or die("Cannot query: 89".mysql_error());
	
	while(list($bhs_id,$bhs_name,$user_id,$brgy_id) = mysql_fetch_array($q_bhs)){
		$str_brgy = '';
		$q_brgy = mysql_query("SELECT barangay_name FROM m_lib_barangay WHERE barangay_id='$brgy_id'") or die("Cannot query 90: ".mysql_error());
		list($brgy_name) = mysql_fetch_array($q_brgy);

		$q_user = mysql_query("SELECT user_lastname,user_firstname FROM game_user WHERE user_id='$user_id'") or die("Cannot query 95: ".mysql_error());
		list($lname,$fname) = mysql_fetch_array($q_user);

		$q_brgy = mysql_query("SELECT a.barangay_name FROM m_lib_barangay a, m_lib_bhs_barangay b WHERE b.bhs_id='$bhs_id' AND a.barangay_id=b.barangay_id") or die("Cannot query 98: ".mysql_error());

		while(list($brgyname) = mysql_fetch_array($q_brgy)){
			$str_brgy .= $brgyname.'<br>';
		}

		echo "<tr>";
		echo "<td>$bhs_name</td>";
		echo "<td>$brgy_name</td>";
		echo "<td>$lname, $fname</td>";
		echo "<td>$str_brgy</td>";
		echo "<td><a href='$_SERVER[PHP_SELF]?facid=$_GET[facid]&bhs_id=$bhs_id&action=delete'>Delete</td>";
		echo "</tr>";
	}
	echo "</table>";
	echo "</td>";
	echo "</tr>";

	echo "</table>";
    	echo "</form>";
  endif;

echo "</html>";
?>