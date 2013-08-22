<?php

session_start();
ob_start();



if(!empty($_SESSION[userid])):

$arr = array();
$arr = unserialize(stripslashes($_GET["id"]));



echo "<html>";
echo "<head><title>List of Family / Household Members</title>";
echo "<style type='text/css'>";
?>
.alert_table_header { background-color:#CC1100;color:#FFFF00;font-weight:bold;text-align:center;font-family: verdana, arial }
.alert_table_row { background-color:#FF4444;color:#FFFFFF;font-weight:bold;font-family: verdana, arial }
body { font-family: verdana, arial; }
<?php
echo "</style>";
echo "</head>";
echo "<body onload=\"window.resizeTo('500','400')\">";

$dbconn = mysql_connect('localhost',$_SESSION["dbuser"],$_SESSION["dbpass"]) or die("Cannot query 4 ".mysql_error());
mysql_select_db($_SESSION["dbname"],$dbconn) or die("cannot select db");

//print_r($arr);
//print_r(count($arr));

$q_brgy = mysql_query("SELECT b.address,a.barangay_name FROM m_lib_barangay a,m_family_address b WHERE b.family_id='$_GET[famid]' AND a.barangay_id=b.barangay_id") or die("Cannot query 36 ".mysql_error());

	if(mysql_num_rows($q_brgy)!=0):
		list($address,$brgy_name) = mysql_fetch_array($q_brgy);
		$address = $address.', '.$brgy_name;
	else:
		$address='-';
	endif;

echo "Household Address: ".$address;

echo "<table bgcolor='#FFCCFF'>";
echo "<tr align='center' class='alert_table_row'><td>&nbsp;Name of Family Member&nbsp;</td>";
echo "<td>&nbsp;Alert Indicators&nbsp;/&nbsp;Alert Message</td>";
//echo "<td>&nbsp;Alert Message&nbsp;</td>";
echo "</tr>";
foreach($arr as $key1=>$value1){
	foreach($value1 as $key2=>$value2){
		foreach($value2 as $key3=>$value3){
			foreach($value3 as $pxid=>$arr_ind){
				$case_date = "";	//clear the text aggregate for every px_id traversal
				echo "<br><br>";
				$q_name = mysql_query("SELECT patient_lastname,patient_firstname,patient_cellphone FROM m_patient WHERE patient_id='$pxid'") or die("Cannot query 30 ".mysql_error());
				list($lname,$fname,$cp) = mysql_fetch_array($q_name);
				$cp = (empty($cp)?'No cellphone number':$cp);
				echo "<tr><td valign='top'>";
				echo $lname.', '.$fname.'<br>'.'('.$cp.')';
				echo "</td>";

				echo "<td>";
				echo "<table>";
				foreach($arr_ind as $arr_ind_id=>$ind_values){
					foreach($ind_values as $ind_id=>$case_id){
						echo "<tr>";

						$case_date = $case_date.$case_id[1].'<br>';

						$q_alert = mysql_query("SELECT sub_indicator FROM m_lib_alert_indicators WHERE alert_indicator_id='$ind_id'") or die("Cannot query 60 ".mysql_error());
						
						
						$q_alert_msg = mysql_query("SELECT alert_message, alert_action, alert_actual_message FROM m_lib_alert_type WHERE alert_indicator_id='$ind_id'") or die("Cannot query 62 ".mysql_error());

						list($sub_ind) = mysql_fetch_array($q_alert);

						echo "<td bgcolor='#ffffff'>";				
						echo $sub_ind.(empty($case_id[1])?' ':' ('.$case_id[1].') ').'<br>';
						echo "</td>";

						$case_date = (empty($case_id[1])?' ':$case_id[1]);

						if(mysql_num_rows($q_alert_msg)!=0):
							list($pre,$after, $during) = mysql_fetch_array($q_alert_msg);
							if(strtotime(date('Y-m-d'))==strtotime($case_date)):
								//$alerto = $alert_msg.' '.$alert_action.' '.'<b>'.$case_id[1].'</b>';
								$alerto = $during;
							elseif(strtotime(date('Y-m-d')) < strtotime($case_date)): 
								$alerto = $pre;
							else: 
								$alerto = $after;
							endif;

							$alerto = str_replace('$name',$lname.', '.$fname,$alerto);	//replace $name with px name
							$alerto = str_replace('$date',$case_date,$alerto);		//replace $date with px date
						else:
							$alerto = '&nbsp;&nbsp;-&nbsp;&nbsp;';
						endif;
						
						echo "<td bgcolor='#ffffff'>";
						echo $alerto;
						echo "</td>";

						echo "</tr>";
					}
				}

				echo "</table>";
				echo "</td>";
				echo "</tr>";
			}	//end px_id traversal
		}
	}
}

echo "</table>";

endif;

echo "</body>";
echo "</html>";

?>