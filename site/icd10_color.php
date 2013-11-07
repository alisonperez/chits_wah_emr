<?php
	$dbconn = mysql_connect("localhost","root","root");
	mysql_select_db("pateros_ncd",$dbconn) or die("Cannot query 8: ".mysql_error());

	$arr_sex = array('M','F');
	$arr_smoking = array('Y','N');
	$arr_sbp = array(180,160,140,120);
	$arr_age = array(70,60,50,40);
	$arr_cholesterol = array(4,5,6,7,8);
	$arr_diab = array('Y','N');
	$arr_color = array('#009900'=>'Green','#CCFF00'=>'Yellow','#FF9900'=>'Orange','#CC0000'=>'Red','#990000'=>'Maroon');
		

	if($_POST["submit_risk_chart"]):
		header("Location: $_SERVER[PHP_SELF]");
	endif;

	echo "<form action='$_SERVER[PHP_SELF]' method='POST'>";
		echo "<table bgcolor='gray'>";
		echo "<tr>";
		//echo "<td colspan='10' style='text-align:center;background-color:black;color:yellow;font-style:verdana;'>NCD RISK CHART COLOR SETTINGS</td>";
		echo "<td colspan='9' style='text-align:center;background-color:black;color:yellow;font-style:verdana;'>WHO/ISH RISK STRATIFICATION CHART</td>";		
		echo "</tr>";

		echo "<tr style='text-align:center;background-color:black;color:yellow;font-style:verdana;'>";
		echo "<td>&nbsp;Count&nbsp;</td>";
		echo "<td>&nbsp;Gender&nbsp;</td>";
		echo "<td>&nbsp;Smoking&nbsp;</td>";
		echo "<td>&nbsp;Age&nbsp;</td>";
		echo "<td>&nbsp;SBP&nbsp;</td>";
		echo "<td>&nbsp;Cholesterol&nbsp;</td>";
		echo "<td>&nbsp;Diabetes Present&nbsp;</td>";
		echo "<td>&nbsp;Type&nbsp;</td>";
		echo "<td>&nbsp;Color&nbsp;</td>";
		//echo "<td>&nbsp;Set Color&nbsp;</td>";
		echo "</tr>";

		$q_insert = mysql_query("SELECT * FROM m_lib_ncd_risk_stratification_chart ORDER BY type ASC, diabetes_present ASC, chart_id ASC") or die("Cannot query 17: ".mysql_error());

		while($r_strat = mysql_fetch_assoc($q_insert)){

		/*if($_POST["submit_risk_chart"]):
			if($_POST[$r_strat[chart_id]]):
				$kulay = $_POST[$r_strat[chart_id]];
				$q_update_color = mysql_query("UPDATE m_lib_ncd_risk_stratification SET color='$kulay' WHERE chart_id='$r_strat[chart_id]'") or die("Cannot query 35: ".mysql_error());

			endif;
		endif;	*/
 
		echo "<tr style='text-align:center;background-color:333333;color:white;font-style:verdana;'>";
		echo "<td>$r_strat[chart_id]</td>";
		echo "<td>$r_strat[gender]</td>";
		echo "<td>$r_strat[smoking_status]</td>";
		echo "<td>$r_strat[age]</td>";
		echo "<td>$r_strat[sbp]</td>";
		echo "<td>$r_strat[cholesterol]</td>";
		echo "<td>$r_strat[diabetes_present]</td>";
		echo "<td>$r_strat[type]</td>";
		echo "<td bgcolor='$r_strat[color]'></td>";

		/*echo "<td>";
		echo "<select name='$r_strat[chart_id]' value='1'>";
		echo "<option value=''>Set Color</option>";
		foreach($arr_color as $key=>$value){
			echo "<option value='$key'>$value</option>";
		}
		echo "</select></td>"; */

		echo "</tr>";

		}
	//echo "<tr><td colspan='10' align='center'>";
	//echo "<input type='submit' name='submit_risk_chart' value='Save Chart' />";
	//echo "</td></tr>";
	echo "</table>";
	echo "</form>";
?>
