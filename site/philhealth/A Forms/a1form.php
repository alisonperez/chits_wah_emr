<?php
	$con = mysqli_connect("localhost",$_SESSION["dbuser"],$_SESSION["dbpass"],$_SESSION["dbname"]);
	if (!(isset($pagenum))) 
	{ 
		$pagenum = 1; 
	} 
	$page_rows = 1;
	
	$sql = "SELECT * FROM m_patient a JOIN (SELECT * FROM m_patient_philhealth GROUP BY patient_id HAVING count(patient_id)>=1) b ON a.patient_id = b.patient_id";
	$query = mysqli_query($con, $sql) or die(mysql_error());
	$rows = mysqli_num_rows($query);
	
	$last = ceil($rows/$page_rows);
		
	if ($pagenum < 1) 
	{ 
		$pagenum = 1; 
	} 
	elseif ($pagenum > $last) 
	{ 
		$pagenum = $last; 
	} 
	
	$max = 'limit ' .($pagenum - 1) * $page_rows .',' .$page_rows;
	if (isset($_REQUEST['search']) && $_REQUEST['search']=='Search'){
		$showSQL = "SELECT *,a.patient_id as patientID FROM m_patient a 
				JOIN (SELECT * FROM m_patient_philhealth GROUP BY patient_id HAVING count(patient_id)>=1) b 
				ON a.patient_id = b.patient_id 
				LEFT JOIN m_family_members c
				ON a.patient_id = c.patient_id
				LEFT JOIN m_family_address d
				ON c.family_id = d.family_id
				LEFT JOIN m_lib_barangay bgy
				ON d.barangay_id = bgy.barangay_id 
				LEFT JOIN m_patient_philhealth_info e
				ON a.patient_id = e.patient_id
				LEFT JOIN m_lib_religion f
				ON e.religion_id = f.religion_code 
				LEFT JOIN m_lib_civil_status g
				ON e.status_id = g.status_id
				LEFT JOIN m_lib_occupation h
				ON e.occup_id = h.occup_id WHERE a.patient_lastname LIKE '%".$_POST["searchLname"]."%' AND a.patient_firstname LIKE '%".$_POST["searchFname"]."%' AND b.philhealth_id='".$_POST["searchPIN"]."'";
	}
	else {
	$showSQL = "SELECT *,a.patient_id as patientID FROM m_patient a 
				JOIN (SELECT * FROM m_patient_philhealth GROUP BY patient_id HAVING count(patient_id)>=1) b 
				ON a.patient_id = b.patient_id 
				LEFT JOIN m_family_members c
				ON a.patient_id = c.patient_id
				LEFT JOIN m_family_address d
				ON c.family_id = d.family_id
				LEFT JOIN m_lib_barangay bgy
				ON d.barangay_id = bgy.barangay_id 
				LEFT JOIN m_patient_philhealth_info e
				ON a.patient_id = e.patient_id
				LEFT JOIN m_lib_religion f
				ON e.religion_id = f.religion_code 
				LEFT JOIN m_lib_civil_status g
				ON e.status_id = g.status_id
				LEFT JOIN m_lib_occupation h
				ON e.occup_id = h.occup_id $max";
	}
	$showQUERY = mysqli_query($con, $showSQL) or die(mysql_error());
	$result = mysqli_fetch_array($showQUERY);
	//echo $showSQL;
	
	function _getAge($dob)
	{
		$birthDate = $dob;
  		//explode the date to get month, day and year
  		/*$birthDate = explode("-", $birthDate);
  		//get age from date or birthdate
  		$age = (date("md", date("U", mktime(0, 0, 0, $birthDate[1], $birthDate[2], $birthDate[0]))) > date("md")
    			? ((date("Y") - $birthDate[0]) - 1)
    			: (date("Y") - $birthDate[0]));
  		echo "Age is:" . $age;*/
		$date = date("Y-m-d");
		$birthday = new DateTime($birthDate);
		$interval = $birthday->diff(new DateTime($date));
		return $interval->y;
	}
	
	function get_immunizations()
	{
		if (func_num_args()>0) {
            $arg_list = func_get_args();
            $patient_id = $arg_list[0];
            $vaccine_id = $arg_list[1];
        }
        $con = mysqli_connect("localhost",$_SESSION["dbuser"],$_SESSION["dbpass"],$_SESSION["dbname"]);
        
        if ($vaccine_id == 'MC') {
        	$sql = "SELECT CASE WHEN group_concat(vaccine_id) IS NOT NULL THEN 'TT' END  as vaccine FROM m_consult_mc_vaccine WHERE patient_id = '$patient_id' AND vaccine_id IN ('TT1','TT2','TT3','TT4','TT5')";
        }
        else {
        	$sql = "SELECT vaccine_id as vaccine FROM m_consult_ccdev_vaccine WHERE patient_id = '$patient_id' AND vaccine_id = '$vaccine_id'";
        }
        if($query = mysqli_query($con, $sql)){
        	if(mysqli_num_rows($query)){
        		$result = mysqli_fetch_array($query);
        		
        	}
        }
		return $result["vaccine"];
		//return $vaccine;
	}
	
	function get_vitals()
	{
		if (func_num_args()>0) {
            $arg_list = func_get_args();
            $patient_id = $arg_list[0];
            $type = $arg_list[1];
        }
        $con = mysqli_connect("localhost",$_SESSION["dbuser"],$_SESSION["dbpass"],$_SESSION["dbname"]);
		$sql = "SELECT *,concat(vitals_systolic,'/',vitals_diastolic) as BP FROM m_consult_vitals WHERE patient_id = '$patient_id' ORDER BY consult_id DESC LIMIT 1";
        if($query = mysqli_query($con, $sql)){
        	if(mysqli_num_rows($query)){
        		$result = mysqli_fetch_array($query);
        		
        	}
        }
        return $result[$type];
	}
	
	function get_pe_findings()
	{
		if (func_num_args()>0) {
            $arg_list = func_get_args();
            $patient_id = $arg_list[0];
            $type = $arg_list[1];
        }
        $con = mysqli_connect("localhost",$_SESSION["dbuser"],$_SESSION["dbpass"],$_SESSION["dbname"]);
		$sql = "SELECT * FROM m_consult_notes_pe WHERE patient_id = '$patient_id' ORDER BY notes_id DESC LIMIT 1";
        if($query = mysqli_query($con, $sql)){
        	if(mysqli_num_rows($query)){
        		$result = mysqli_fetch_array($query);
        		
        	}
        }
        return $result[$type];
	}
	
	function get_fp_reg()
	{
		if (func_num_args()>0) {
            $arg_list = func_get_args();
            $patient_id = $arg_list[0];
       	}
       	$con = mysqli_connect("localhost",$_SESSION["dbuser"],$_SESSION["dbpass"],$_SESSION["dbname"]);
		$sql = "SELECT * FROM m_patient_fp WHERE patient_id = '$patient_id' OR spouse_name = '$patient_id' ORDER BY consult_id DESC LIMIT 1";
        if($query = mysqli_query($con, $sql)){
        	if(mysqli_num_rows($query)){
        		$result = mysqli_fetch_array($query);
        		$remarks = 'Y';
        	}
        	else
        	{
        		$remarks = 'N';
        	}
        }
        return $remarks;
	}
	
	function explodeX( $delimiters, $string )
	{
    	return explode( chr( 1 ), str_replace( $delimiters, chr( 1 ), $string ) );
	}
 
	function get_fpal()
	{
		if (func_num_args()>0) {
            $arg_list = func_get_args();
            $patient_id = $arg_list[0];
            $fpal = $arg_list[1];
       	}
       	$con = mysqli_connect("localhost",$_SESSION["dbuser"],$_SESSION["dbpass"],$_SESSION["dbname"]);
       	$sql = "SELECT length(obscore_fpal) as fpal, obscore_fpal as newFPAL, obscore_gp, outcome_id FROM m_patient_mc WHERE patient_id = '$patient_id' ORDER BY consult_id DESC LIMIT 1";
       	if($query = mysqli_query($con, $sql)){
        	if(mysqli_num_rows($query)){
        		$result = mysqli_fetch_array($query);
        		switch ($result["fpal"]):
        			case 6:
        				$f = substr($result["newFPAL"],0,2);
        				$p = substr($result["newFPAL"],2,1);
        				$a = substr($result["newFPAL"],3,1);
        				$l = substr($result["newFPAL"],4,2);
        				break;
        			case 4:
        				$f = substr($result["newFPAL"],0,1);
        				$p = substr($result["newFPAL"],1,1);
        				$a = substr($result["newFPAL"],2,1);
        				$l = substr($result["newFPAL"],3,1);
        				break;
        			default:
        				break;
        		endswitch;
        	}
        }
        switch ($fpal):
        	case 'F':
	        	return $f;
	        	break;
	        
	        case 'P':
	        	return $p;
	        	break;
	        
	        case 'A':
	        	return $a;
	        	break;
	        
	        case 'L':
	        	return $l;
	        
	        case 'GR':
	        	$gr = explodeX(array('-','/'), $result["obscore_gp"]);
	        	return $gr[0];
	        	break;
	        
	        case 'PA':
	        	$gr = explodeX(array('-','/',',','|','&'), $result["obscore_gp"]);
	        	return $gr[1];
	        	break;
	        
	        case 'DELIVERY':
	        	$sql = "SELECT outcome_name FROM m_lib_mc_outcome WHERE outcome_id = '".$result["outcome_id"]."'";	        	
	        	if($query = mysqli_query($con, $sql)){
        			if(mysqli_num_rows($query)){
        				$result = mysqli_fetch_array($query);
        				return $result["outcome_name"];
        			}
	        	}
	        	break;
        	default:
        		;
        		break;
        endswitch;
        //return substr($result["newFPAL"],0,1);    
	}
	
	function get_history()
	{
		if (func_num_args()>0) {
            $arg_list = func_get_args();
            $patient_id = $arg_list[0];
            $type = $arg_list[1];
       	}
       	$con = mysqli_connect("localhost",$_SESSION["dbuser"],$_SESSION["dbpass"],$_SESSION["dbname"]);
       	
		$checkSQL = "select pasthistory_id, familyhistory_id, medintake_id,
	        		menarche, lmp, period_duration, cycle, pads_perday,
	        		onset_sexinter, method_id, menopause, meno_age,
	        		smoking, pack_peryear, alcohol, bottles_perday, ill_drugs
	        		from m_patient_history where patient_id = '$patient_id'";
    	if ($checkResult = mysqli_query($con,$checkSQL)) {
            if (mysqli_num_rows($checkResult)) {
                list($pasthistory_id, $familyhistory_id, $medintake_id,
	        		$menarche, $lmp, $period_duration, $cycle, $pads_perday,
	        		$onset_sexinter, $method_id, $menopause, $meno_age,
	        		$smoking, $pack_peryear, $alcohol, $bottles_perday, $ill_drugs) = mysqli_fetch_array($checkResult);
	        		switch ($type):
            			case 'PAST':
            				return $pasthistory_id;
            				break;
            			case 'FAMILY':
            				return $familyhistory_id;
            				break;
            			case 'MEDINTAKE':
            				return $medintake_id;
            				break;
            			case 'MENARCHE':
            				return $menarche;
            				break;
            			case 'LMP':
            				return $lmp;
            				break;
            			case 'PERIOD':
            				return $period_duration;
            				break;
            			case 'CYCLE':
            				return $cycle;
            				break;
            			case 'PADS':
            				return $pads_perday;
            				break;
            			case 'SEXINTER':
            				return $onset_sexinter;
            				break;
            			case 'METHOD':
				            $sql = "select method_id, method_name from m_lib_fp_methods where method_gender ='F' and method_id ='$method_id'";
							if ($result = mysqli_query($con,$sql)) {
					            if (mysqli_num_rows($result)) {
					                (list($id, $name) = mysqli_fetch_array($result));
									$method_name = $name;
					            }
							}
            				return $method_name;
            				break;
            			case 'MENOPAUSE':
            				return $menopause;
            				break;
            			case 'MENOAGE':
            				return $meno_age;
            				break;
            			case 'SMOKING':
            				return $smoking;
            				break;
            			case 'PACK':
            				return $pack_peryear;
            				break;
            			case 'ALCOHOL':
            				return $alcohol;
            				break;
            			case 'BOTTLES':
            				return $bottles_perday;
            				break;
            			case 'ILLDRUGS':
            				return $ill_drugs;
            				break;
            			default:
            				break;
	        		endswitch;
            }
        }
    }
	
	function getMembership()
    {
        if (func_num_args()>0) {
	    	$arg_list = func_get_args();
	      	$philhealth_id = $arg_list[0];
	  	}
       	
	  	$con = mysqli_connect("localhost",$_SESSION["dbuser"],$_SESSION["dbpass"],$_SESSION["dbname"]);
       	$checkSQL = "SELECT member_id FROM m_patient_philhealth WHERE philhealth_id = '$philhealth_id' AND member_id IN (1,2,3,5,7,8,9,10,11,12,13)";
       	if ($checkResult = mysqli_query($con,$checkSQL)) {
	    	if (mysqli_num_rows($checkResult)) {
	       		(list($memberID) = mysqli_fetch_array($checkResult));
	            	return $memberID;
	    	}
  		}
	}
	
	function getSurgical()
	{
		if (func_num_args()>0) {
	    	$arg_list = func_get_args();
	      	$patient_id = $arg_list[0];
	  	}
		
	  	$con = mysqli_connect("localhost",$_SESSION["dbuser"],$_SESSION["dbpass"],$_SESSION["dbname"]);
	  	$sql = "select record_id, operation, operation_date from m_patient_history_surgical where patient_id = '$patient_id' order by operation_date desc";
    	if ($result = mysqli_query($con,$sql)) {
	    	if (mysqli_num_rows($result)) {
	    		while (list($id, $operation, $operation_date) = mysqli_fetch_array($result)) {
	    			echo "<span class='width450'>Operation: <input type='text' name='pshOp[]' value='$operation'></span> Date: <input type='text' name='pshOpDate[]' style='width:150px' value='$operation_date'><br />";
	    		}
	    	}
	    	else 
	    	{
	    		echo "<span class='width450'>Operation: <input type='text' name='pshOp[]'></span> Date: <input type='text' name='pshOpDate[]' style='width:150px'><br />";
				echo "<span class='width450'>Operation: <input type='text' name='pshOp[]'></span> Date: <input type='text' name='pshOpDate[]' style='width:150px'><br />";
	    	}
    	}
	}
?>
	<html>
		<head>
			<title>A1 Form</title>
			<link rel='stylesheet' href='../styles/style.css' type='text/css'  />
		</head>
		<body>
		<form name='a1form' method='POST'>
			<div id='container'>
				<div id='header'>
					<h4 class='shadow'><span class='indent10'>Annex A1</span></h4><br />					
					<h1>PHILIPPINE HEALTH INSURANCE CORPORATION</h1>
					<br /><br />
					<input name='searchLname' value='' placeholder='Last Name'>
					<input name='searchFname' value='' placeholder='First Name'>
					<input name='searchPIN' value='' placeholder='Philhealth ID'>
					<input type='submit' name='search' value='Search'>
					<br/>
					<?php 
						if ($pagenum == 1)
						{
					
						}
						else
						{
							$previous = $pagenum - 1;
							
							echo " <a class ='page' href='{$_SERVER['PHP_SELF']}?pagenum=1'>First</a> ";
							echo " ";
							echo " <a class ='page' href='{$_SERVER['PHP_SELF']}?pagenum=$previous'>Previous</a> ";
						} 
						
						//just a spacer
						//echo " ---- ";
					
						//This does the same as above, only checking if we are on the last page, and then generating the Next and Last links
						
						if ($pagenum == $last)
						{
					
						} 
						else 
						{
							$next = $pagenum + 1;
					
							echo " <a class ='page' href='{$_SERVER['PHP_SELF']}?pagenum=$next'>Next</a> ";
							echo " ";
							echo " <a class ='page' href='{$_SERVER['PHP_SELF']}?pagenum=$last'>Last</a> ";
						}
					?>
				</div>
				<div id='body'>
				
					<div class='width750'>
						<br />
						<hr /><h4 class='center'>INDIVIDUAL HEALTH PROFILE</h4><hr />
						<br />
						<?php //echo $result["patientID"];?>
						<label>PIN: </label><input type='text' name='pin' <?php echo "value='".$result['philhealth_id']."'";?>></span>
						<br /><br />
						<label>Patient Name:</label>
						<p class='columns5 indent'>
						<span class='width85'>Last Name: </span><input type='text' name='lname' style='width:190px' <?php echo "value='".$result['patient_lastname']."'";?>><br />
						<span class='width85'>First Name: </span><input type='text' name='fname' style='width:190px' <?php echo "value='".$result['patient_firstname']."'";?>><br />
						<span class='width100'>Middle Name: </span><input type='text' name='mname' style='width:190px' <?php echo "value='".$result['patient_middle']."'";?>><br />
						<span class='width100'>Extension: </span><input type='text' name='exname' placeholder='(Sr., Jr., etc.)' style='width:190px'><br />
						</p>
						<br />
						<label>Address:</label>
						<textarea style='vertical-align: top' name='address' rows='2' cols='40'><?php echo $result["address"].", ".$result["barangay_name"].", ".$_SESSION['lgu'].", ".$_SESSION['province'];?></textarea>
						<br /><br />
					</div>
					
					<div class='width750'>
						<label>Age:</label><br />
						<p class='columns3 indent'>
						<input type='radio' name='yearAge' value='0-1' <?php if (_getAge($result['patient_dob'])<=1){ echo "checked";}?>>0-1 Year<br />
						<input type='radio' name='yearAge' value='2-5' <?php if (_getAge($result['patient_dob'])>=2 && _getAge($result['patient_dob'])<=5){ echo "checked";}?>>2-5 Year<br />
						<input type='radio' name='yearAge' value='6-15' <?php if (_getAge($result['patient_dob'])>=6 && _getAge($result['patient_dob'])<=15){ echo "checked";}?>>6-15 Year<br />
						<input type='radio' name='yearAge' value='16-24' <?php if (_getAge($result['patient_dob'])>=16 && _getAge($result['patient_dob'])<=24){ echo "checked";}?>>16-24 Year<br />
						<input type='radio' name='yearAge' value='25-59' <?php if (_getAge($result['patient_dob'])>=25 && _getAge($result['patient_dob'])<=59){ echo "checked";}?>>25-59 Year<br />
						<input type='radio' name='yearAge' value='60' <?php if (_getAge($result['patient_dob'])>=60){ echo "checked";}?>>60 Years and Above<br />
						</p>
						<br />
						<span class='width80'><label>Birthdate:</label></span>
						<input type='text' size=8 name='personalinfodate' placeholder='(mm/dd/yyyy)' <?php echo "value='".date("m/d/Y", strtotime($result['patient_dob']))."'";?>><br /><br />
						
						<span class='width80'><label>Sex:</label></span>
						<select name='gender'><option value='M' <?php if ($result["patient_gender"]=='M'){ echo "selected";}?>>Male</option><option value='F' <?php if ($result["patient_gender"]=='F'){ echo "selected";}?>>Female</option></select><br /><br />
						
						<span class='width80'><label>Religion:</label></span>
						<input type='text' name='religion' <?php echo "value='".$result['religion_desc']."'";?>>
						
						<br /><br />
						
						<label>Civil Status:</label><br />
						<p class='columns6 indent'>
						<input type='radio' name='civilstatus' value='SNGL' <?php if ($result['status_id']=='SNGL') { echo "checked";}?>>Single<br />
						<input type='radio' name='civilstatus' value='MRRD' <?php if ($result['status_id']=='MRRD') { echo "checked";}?>>Married<br />
						<input type='radio' name='civilstatus' value='ANLD' <?php if ($result['status_id']=='ANLD') { echo "checked";}?>>Annuled<br />
						<input type='radio' name='civilstatus' value='WDWD' <?php if ($result['status_id']=='WDWD') { echo "checked";}?>>Widowed<br />
						<input type='radio' name='civilstatus' value='SPRTD' <?php if ($result['status_id']=='SPRTD') { echo "checked";}?>>Separated<br /><br />
						<input type='radio' name='civilstatus' value='Others'>Others, specify<input type='text' name='cvlstatus' style='width:190px'><br />
						</p>
						<br /><br />
						
						<label>Highest Completed Educational Attainment:</label><br />
						<p class='columns6 indent'>
						<input type='radio' name='hceAttainment' value='COLL' <?php if ($result['educ_id']=='COLL') { echo "checked";}?>>College degree, Post Graduate<br />
						<input type='radio' name='hceAttainment' value='SEC' <?php if ($result['educ_id']=='SEC') { echo "checked";}?>>High School<br />
						<input type='radio' name='hceAttainment' value='PRIM' <?php if ($result['educ_id']=='PRIM') { echo "checked";}?>>Elementary<br />
						<input type='radio' name='hceAttainment' value='VOC' <?php if ($result['educ_id']=='VOC') { echo "checked";}?>>Vocational<br />
						<input type='radio' name='hceAttainment' value='NOSCH' <?php if ($result['educ_id']=='NOSCH') { echo "checked";}?>>No Schooling<br />
						</p>
						<br />
						<label>Occupation:</label>
						<input type='text' name='occupation' <?php echo "value='".$result['occup_name']."'";?>><br /><br />
					</div>
					
					<div class='width750'>
						<hr /><h4 class='center'>Membership Information:</h4><hr />
						<br /><span class='width200'><label>PHIC Membership</label></span>
						<input type='radio' name='membership' value='Member' <?php if ($result["member_id"]==1||$result["member_id"]==2||$result["member_id"]==3||$result["member_id"]==5||$result["member_id"]==7||$result["member_id"]==8||$result["member_id"]==9||$result["member_id"]==10||$result["member_id"]==11||$result["member_id"]==12){ echo "checked";}?>><span class='width100'>Member</span>
						<input type='radio' name='membership' value='Dependent' <?php if ($result["member_id"]==4){ echo "checked";}?>><span class='width100'>Dependent</span>
						<input type='radio' name='membership' value='Non Member'>Non Member
						<br /><span class='width200'><label>Sponsor</label></span>
						<input type='radio' name='sponsor' value='NHTS' <?php if ($result["member_id"]==4){$memberID = getMembership($result["philhealth_id"]); if ($memberID==7){echo "checked";}}elseif ($result["member_id"]==7){echo "checked";}?>><span class='width100'>NHTS</span> 
						<input type='radio' name='sponsor' value='NGA' <?php if ($result["member_id"]==4){$memberID = getMembership($result["philhealth_id"]); if ($memberID==8){echo "checked";}}elseif ($result["member_id"]==8){echo "checked";}?>><span class='width100'>NGA</span>
						<input type='radio' name='sponsor' value='LGU' <?php if ($result["member_id"]==4){$memberID = getMembership($result["philhealth_id"]); if ($memberID==9){echo "checked";}}elseif ($result["member_id"]==9){echo "checked";}?>><span class='width100'>LGU</span>
						<input type='radio' name='sponsor' value='PRIVATE' <?php if ($result["member_id"]==4){$memberID = getMembership($result["philhealth_id"]); if ($memberID==10){echo "checked";}}elseif ($result["member_id"]==10){echo "checked";}?>>PRIVATE
						<br /><span class='width200'><label>IPP</label></span>
						<input type='radio' name='ipp' value='OG' <?php if ($result["member_id"]==4){$memberID = getMembership($result["philhealth_id"]); if ($memberID==11){echo "checked";}}elseif ($result["member_id"]==11){echo "checked";}?>><span class='width100'>OG</span> 
						<input type='radio' name='ipp' value='OFW' <?php if ($result["member_id"]==4){$memberID = getMembership($result["philhealth_id"]); if ($memberID==3){echo "checked";}}elseif ($result["member_id"]==3){echo "checked";}?>><span class='width100'>OFW</span>
						<input type='radio' name='ipp' value='Voluntary' <?php if ($result["member_id"]==4){$memberID = getMembership($result["philhealth_id"]); if ($memberID==1){echo "checked";}}elseif ($result["member_id"]==1){echo "checked";}?>>Voluntary/Self-employed
						<br /><span class='width200'><label>Employment</label></span>
						<input type='radio' name='employment' value='Government' <?php if ($result["member_id"]==4){$memberID = getMembership($result["philhealth_id"]); if ($memberID==12){echo "checked";}}elseif ($result["member_id"]==12){echo "checked";}?>><span class='width100'>Government</span>
						<input type='radio' name='employment' value='Private' <?php if ($result["member_id"]==4){$memberID = getMembership($result["philhealth_id"]); if ($memberID==5){echo "checked";}}elseif ($result["member_id"]==5){echo "checked";}?>>Private
						<br /><span class='width200'><label>Lifetime</label></span>
						<input type='radio' name='lifetime' value='Lifetime' <?php if ($result["member_id"]==4){$memberID = getMembership($result["philhealth_id"]); if ($memberID==13){echo "checked";}}elseif ($result["member_id"]==13){echo "checked";}?>>Lifetime
						<br /><br />
					</div>
					
					<div class='width750'>
						<hr /><h4 class='center'>Past Medical History:</h4><hr />
						<br /><p class='columns3'>
						<input type='checkbox' name='pmhistory' value='Alergy' <?php $arr = explode (',',get_history($result["patientID"],"PAST")); foreach ($arr as $key => $value){if($value=='1'){echo "checked";}}?>>Alergy, specify: <input type='text' name='pmhalergy' style='width:150px'><br />
						<input type='checkbox' name='pmhistory' value='Asthma' <?php $arr = explode (',',get_history($result["patientID"],"PAST")); foreach ($arr as $key => $value){if($value=='2'){echo "checked";}}?>>Asthma<br />
						<input type='checkbox' name='pmhistory' value='Cancer' <?php $arr = explode (',',get_history($result["patientID"],"PAST")); foreach ($arr as $key => $value){if($value=='3'){echo "checked";}}?>>Cancer, specify organ: <input type='text' name='pmhcancer' style='width:150px'><br />
						<input type='checkbox' name='pmhistory' value='Cerebrovascular Disease' <?php $arr = explode (',',get_history($result["patientID"],"PAST")); foreach ($arr as $key => $value){if($value=='4'){echo "checked";}}?>>Cerebrovascular Disease<br />
						<input type='checkbox' name='pmhistory' value='Coronary Artery Disease' <?php $arr = explode (',',get_history($result["patientID"],"PAST")); foreach ($arr as $key => $value){if($value=='5'){echo "checked";}}?>>Coronary Artery Disease<br />
						<input type='checkbox' name='pmhistory' value='Diabetes Mellitus' <?php $arr = explode (',',get_history($result["patientID"],"PAST")); foreach ($arr as $key => $value){if($value=='6'){echo "checked";}}?>>Diabetes Mellitus<br />
						<input type='checkbox' name='pmhistory' value='Emphysema' <?php $arr = explode (',',get_history($result["patientID"],"PAST")); foreach ($arr as $key => $value){if($value=='7'){echo "checked";}}?>>Emphysema<br />
						<input type='checkbox' name='pmhistory' value='Epilepsy/Seizure Disorder' <?php $arr = explode (',',get_history($result["patientID"],"PAST")); foreach ($arr as $key => $value){if($value=='8'){echo "checked";}}?>>Epilepsy/Seizure Disorder<br />
						<input type='checkbox' name='pmhistory' value='Hepatitis' <?php $arr = explode (',',get_history($result["patientID"],"PAST")); foreach ($arr as $key => $value){if($value=='9'){echo "checked";}}?>>Hepatitis, specify type: <input type='text' name='pmhhepatitis' style='width:150px'><br />
						<input type='checkbox' name='pmhistory' value='Hyperlipidemia' <?php $arr = explode (',',get_history($result["patientID"],"PAST")); foreach ($arr as $key => $value){if($value=='10'){echo "checked";}}?>>Hyperlipidemia<br />
						<input type='checkbox' name='pmhistory' value='Hypertension' <?php $arr = explode (',',get_history($result["patientID"],"PAST")); foreach ($arr as $key => $value){if($value=='11'){echo "checked";}}?>>Hypertension, highest BP: <input type='text' name='pmhbloodpressure' style='width:150px'><br />
						<input type='checkbox' name='pmhistory' value='Peptic Ulcer Disease' <?php $arr = explode (',',get_history($result["patientID"],"PAST")); foreach ($arr as $key => $value){if($value=='12'){echo "checked";}}?>>Peptic Ulcer Disease<br />
						<input type='checkbox' name='pmhistory' value='Pneumonia' <?php $arr = explode (',',get_history($result["patientID"],"PAST")); foreach ($arr as $key => $value){if($value=='13'){echo "checked";}}?>>Pneumonia<br />
						<input type='checkbox' name='pmhistory' value='Thyroid Disease' <?php $arr = explode (',',get_history($result["patientID"],"PAST")); foreach ($arr as $key => $value){if($value=='14'){echo "checked";}}?>>Thyroid Disease<br />
						<input type='checkbox' name='pmhistory' value='Tuberculosis' <?php $arr = explode (',',get_history($result["patientID"],"PAST")); foreach ($arr as $key => $value){if($value=='15'){echo "checked";}}?>>Tuberculosis, specify organ: <input type='text' name='pmhtuberculosis' style='width:140px'><br />
						<span class='indent21'>If PTB, what category? <input type='text' name='ptbCategory' style='width:150px'></span><br />
						<input type='checkbox' name='pmhistory' value='Urinary Tract Infection' <?php $arr = explode (',',get_history($result["patientID"],"PAST")); foreach ($arr as $key => $value){if($value=='16'){echo "checked";}}?>>Urinary Tract Infection<br />
						<input type='checkbox' name='pmhistory' value='Others'>Others: <input type='text' name='pmhOthers' style='width:150px'><br />
						</p><br />
					</div>
					
					<div class='width750'>
						<hr /><h4 class='center'>Past Surgical History:</h4><hr /><br />
						<!-- <span class='width450'>Operation: <input type='text' name='pshOp[]'></span> Date: <input type='text' name='pshOpDate[]' style='width:150px'><br />
						<span class='width450'>Operation: <input type='text' name='pshOp[]'></span> Date: <input type='text' name='pshOpDate[]' style='width:150px'><br />-->
						<?php getSurgical($result["patientID"]);?>
						<br />
					</div>
					
					<div class='width750'>
						<hr /><h4 class='center'>Family History:</h4><hr />
						<br /><p class='columns3'>
						<input type='checkbox' name='famhistory' value='Alergy' <?php $arr = explode (',',get_history($result["patientID"],"FAMILY")); foreach ($arr as $key => $value){if($value=='1'){echo "checked";}}?>>Alergy, specify: <input type='text' name='fhalergy' style='width:150px'><br />
						<input type='checkbox' name='famhistory' value='Asthma' <?php $arr = explode (',',get_history($result["patientID"],"FAMILY")); foreach ($arr as $key => $value){if($value=='2'){echo "checked";}}?>>Asthma<br />
						<input type='checkbox' name='famhistory' value='Cancer' <?php $arr = explode (',',get_history($result["patientID"],"FAMILY")); foreach ($arr as $key => $value){if($value=='3'){echo "checked";}}?>>Cancer, specify organ: <input type='text' name='fhcancer' style='width:150px'><br />
						<input type='checkbox' name='famhistory' value='Cerebrovascular Disease' <?php $arr = explode (',',get_history($result["patientID"],"FAMILY")); foreach ($arr as $key => $value){if($value=='4'){echo "checked";}}?>>Cerebrovascular Disease<br />
						<input type='checkbox' name='famhistory' value='Coronary Artery Disease' <?php $arr = explode (',',get_history($result["patientID"],"FAMILY")); foreach ($arr as $key => $value){if($value=='5'){echo "checked";}}?>>Coronary Artery Disease<br />
						<input type='checkbox' name='famhistory' value='Diabetes Mellitus' <?php $arr = explode (',',get_history($result["patientID"],"FAMILY")); foreach ($arr as $key => $value){if($value=='6'){echo "checked";}}?>>Diabetes Mellitus<br />
						<input type='checkbox' name='famhistory' value='Emphysema' <?php $arr = explode (',',get_history($result["patientID"],"FAMILY")); foreach ($arr as $key => $value){if($value=='7'){echo "checked";}}?>>Emphysema<br />
						<input type='checkbox' name='famhistory' value='Epilepsy/Seizure Disorder' <?php $arr = explode (',',get_history($result["patientID"],"FAMILY")); foreach ($arr as $key => $value){if($value=='8'){echo "checked";}}?>>Epilepsy/Seizure Disorder<br />
						<input type='checkbox' name='famhistory' value='Hepatitis' <?php $arr = explode (',',get_history($result["patientID"],"FAMILY")); foreach ($arr as $key => $value){if($value=='9'){echo "checked";}}?>>Hepatitis, specify type: <input type='text' name='fhhepatitis' style='width:150px'><br />
						<input type='checkbox' name='famhistory' value='Hyperlipidemia' <?php $arr = explode (',',get_history($result["patientID"],"FAMILY")); foreach ($arr as $key => $value){if($value=='10'){echo "checked";}}?>>Hyperlipidemia<br />
						<input type='checkbox' name='famhistory' value='Hypertension' <?php $arr = explode (',',get_history($result["patientID"],"FAMILY")); foreach ($arr as $key => $value){if($value=='11'){echo "checked";}}?>>Hypertension<br />
						<input type='checkbox' name='famhistory' value='Peptic Ulcer Disease' <?php $arr = explode (',',get_history($result["patientID"],"FAMILY")); foreach ($arr as $key => $value){if($value=='12'){echo "checked";}}?>>Peptic Ulcer Disease<br />
						<input type='checkbox' name='famhistory' value='Thyroid Disease' <?php $arr = explode (',',get_history($result["patientID"],"FAMILY")); foreach ($arr as $key => $value){if($value=='14'){echo "checked";}}?>>Thyroid Disease<br />
						<input type='checkbox' name='famhistory' value='Tuberculosis' <?php $arr = explode (',',get_history($result["patientID"],"FAMILY")); foreach ($arr as $key => $value){if($value=='15'){echo "checked";}}?>>Tuberculosis, specify organ: <input type='text' name='fhtuberculosis' style='width:140px'><br />
						<span class='indent21'>If PTB, what category? <input type='text' name='ptbCategory' style='width:150px'></span><br />
						<input type='checkbox' name='famhistory' value='Others'>Others: <input type='text' name='fhOthers' style='width:150px'><br />
						</p><br />
					</div>
					
					<div class='width750'>
						<hr /><h4 class='center'>Personal/Social History:</h4><hr />
						<br />
						<span class='width100'><label>Smoking: </label></span>
						<span class='width70'><input type='radio' name='smoking' value='Yes' <?php if (get_history($result["patientID"],"SMOKING")=='Y'){echo "checked";}?>>Yes</span>
						<span class='width70'><input type='radio' name='smoking' value='No' <?php if (get_history($result["patientID"],"SMOKING")=='N'){echo "checked";}?>>No</span>
						<input type='radio' name='smoking' value='Quit' <?php if (get_history($result["patientID"],"SMOKING")=='Q'){echo "checked";}?>><span class='width150'>Quit</span>
						<span class='width150'>No. of pack/year?</span><input type='text' name='cigarpack' style='width:150px' <?php echo "value='".get_history($result["patientID"],"PACK")."'";?>>
						<br />
						<span class='width100'><label>Alcohol: </label></span>
						<span class='width70'><input type='radio' name='alcohol' value='Yes' <?php if (get_history($result["patientID"],"ALCOHOL")=='Y'){echo "checked";}?>>Yes</span>
						<span class='width70'><input type='radio' name='alcohol' value='No' <?php if (get_history($result["patientID"],"ALCOHOL")=='N'){echo "checked";}?>>No</span>
						<input type='radio' name='alcohol' value='Quit' <?php if (get_history($result["patientID"],"ALCOHOL")=='Q'){echo "checked";}?>><span class='width150'>Quit</span>
						<span class='width150'>No. of bottles/day?</span><input type='text' name='alcoholbottle' style='width:150px' <?php echo "value='".get_history($result["patientID"],"BOTTLES")."'";?>>
						<br />
						<span class='width100'><label>Illicit drugs: </label></span>
						<span class='width70'><input type='radio' name='drugs' value='Yes' <?php if (get_history($result["patientID"],"ILLDRUGS")=='Y'){echo "checked";}?>>Yes</span>
						<span class='width70'><input type='radio' name='drugs' value='No' <?php if (get_history($result["patientID"],"ILLDRUGS")=='N'){echo "checked";}?>>No</span>
						<input type='radio' name='drugs' value='Quit' <?php if (get_history($result["patientID"],"ILLDRUGS")=='Q'){echo "checked";}?>>Quit
						<br /><br />
					</div>
					
					<div class='width750'>
						<hr /><h4 class='center'>Immunizations:</h4><hr />
						<br />
						<label>For Children:</label><br />
						<p class='columns4 indent'>
						<input type='checkbox' name='imchild' value='BCG' <?php if(get_immunizations($result["patientID"],'BCG')=='BCG'){echo "checked";}?>>BCG
						<br /><input type='checkbox' name='imchild' value='OPV1' <?php if(get_immunizations($result["patientID"],'OPV1')=='OPV1'){echo "checked";}?>>OPV1
						<br /><input type='checkbox' name='imchild' value='OPV2' <?php if(get_immunizations($result["patientID"],'OPV2')=='OPV2'){echo "checked";}?>>OPV2
						<br /><input type='checkbox' name='imchild' value='OPV3' <?php if(get_immunizations($result["patientID"],'OPV3')=='OPV3'){echo "checked";}?>>OPV3
						<br /><input type='checkbox' name='imchild' value='DPT1' <?php if(get_immunizations($result["patientID"],'DPT1')=='DPT1'){echo "checked";}?>>DPT1
						<br /><input type='checkbox' name='imchild' value='DPT2' <?php if(get_immunizations($result["patientID"],'DPT2')=='DPT2'){echo "checked";}?>>DPT2
						<br /><input type='checkbox' name='imchild' value='DPT3' <?php if(get_immunizations($result["patientID"],'DPT3')=='DPT3'){echo "checked";}?>>DPT3
						<br /><input type='checkbox' name='imchild' value='MSL' <?php if(get_immunizations($result["patientID"],'MSL')=='MSL'){echo "checked";}?>>Measles
						<br /><input type='checkbox' name='imchild' value='HEPB1' <?php if(get_immunizations($result["patientID"],'HEPB1')=='HEPB1'){echo "checked";}?>>Hepatitis B1
						<br /><input type='checkbox' name='imchild' value='HEPB2' <?php if(get_immunizations($result["patientID"],'HEPB2')=='HEPB2'){echo "checked";}?>>Hepatitis B2
						<br /><input type='checkbox' name='imchild' value='HEPB3' <?php if(get_immunizations($result["patientID"],'HEPB3')=='HEPB3'){echo "checked";}?>>Hepatitis B3
						<br /><input type='checkbox' name='imchild' value='HEPA'>Hepatitis A
						<br /><input type='checkbox' name='imchild' value='Varicella'>Varicella (Chicken Pox)
						</p>
						
						<br />
						<label>For young women:</label>
						<input type='checkbox' name='imywomen' value='HPV'>HPV
						<input type='checkbox' name='imywomen' value='MMR'>MMR
						
						<br /><br />
						<label>For pregnant women:</label>
						<input type='checkbox' name='impwomen' value='TT' <?php if(get_immunizations($result["patientID"],'MC')=='TT'){echo "checked";}?>>Tetanus toxoid
						
						<br /><br />
						<label>For elderly and immunocompromised:</label>
						<input type='checkbox' name='imelderly' value='Pnuemococcal vaccine'>Pnuemococcal vaccine
						<input type='checkbox' name='imyelderly' value='Flu vaccine'>Flu vaccine
						
						<br /><br />
						<label>Others: Specify</label>
						<input type='text' name='imothers' style='width:150px'>
						<br /><br />
					</div>
					
					<div class='width750'>
						<hr /><h4 class='center'>Menstrual History:</h4><hr />
						<br />
						<p class='columns3'>
						Menarche: <input type='text' name='mhmenarche' style='width:150px' <?php echo "value='".get_history($result["patientID"],"MENARCHE")."'";?>><br />
						Last Menstrual Period: <input type='text' name='mplastmp' style='width:150px' <?php echo "value='".get_history($result["patientID"],"LMP")."'";?>><br />
						Period Duration: <input type='text' name='mppduration' style='width:150px' <?php echo "value='".get_history($result["patientID"],"PERIOD")."'";?>><br />
						Interval/Cycle: <input type='text' name='mpinterval' style='width:150px' <?php echo "value='".get_history($result["patientID"],"CYCLE")."'";?>><br />
						No. of pads/day during menstruation: <input type='text' name='mpnopadsdm' style='width:100px' <?php echo "value='".get_history($result["patientID"],"PADS")."'";?>><br />
						Onset of sexual intercourse: <input type='text' name='mpsexinter' style='width:150px' <?php echo "value='".get_history($result["patientID"],"SEXINTER")."'";?>><br />
						Birth control method: <input type='text' name='mpbirthcontrol' style='width:150px' <?php echo "value='".get_history($result["patientID"],"METHOD")."'";?>><br />
						Menopause: <input type='radio' name='mpmenopause' value='Yes' <?php if (get_history($result["patientID"],"MENOPAUSE")=='Y'){echo "checked";}?>>Yes
						<input type='radio' name='mpmenopause' value='No' <?php if (get_history($result["patientID"],"MENOPAUSE")=='N'){echo "checked";}?>>No<br />
						If yes, at what age?: <input type='text' name='mpmenopauseage' style='width:150px' <?php echo "value='".get_history($result["patientID"],"MENOAGE")."'";?>><br />
						</p><br />
					</div>
					
					<div class='width750'>
						<hr /><h4 class='center'>Pregnancy History:</h4><hr />
						<br />
						<p class='columns3'>
						Gravity (No. of Pregnancy): <input type='text' name='phpregnancygravity' style='width:100px' <?php echo "value='".get_fpal($result["patientID"],'GR')."'";?>><br />
						No. of Full Term: <input type='text' name='phfullterm' style='width:100px' <?php echo "value='".get_fpal($result["patientID"],'F')."'";?>><br />
						No. of Premature: <input type='text' name='phpremature' style='width:100px' <?php echo "value='".get_fpal($result["patientID"],'P')."'";?>><br />
						No. of Abortion: <input type='text' name='phabortion' style='width:100px' <?php echo "value='".get_fpal($result["patientID"],'A')."'";?>><br />
						No. of Living Children: <input type='text' name='phlivingchildren' style='width:100px' <?php echo "value='".get_fpal($result["patientID"],'L')."'";?>><br />
						Parity (No. of Delivery): <input type='text' name='phdeliveryparity' style='width:100px' <?php echo "value='".get_fpal($result["patientID"],'PA')."'";?>><br />
						Type of Delivery: <input type='text' name='phdeliverytype' style='width:150px' <?php echo "value='".get_fpal($result["patientID"],'DELIVERY')."'";?>><br />
						</p>
						<br />
						<input type='checkbox' name='pheclampsia' value='Pregnancy-induced hypertension'>Pregnancy-induced hypertension (Pre-eclampsia)
						<br /><br />
					</div>
					
					<div class='width750'>
						<hr /><p class='center'><b>Access to Family Planning Counseling:</b>
						<input type='radio' name='fpcounseling' value='Y' <?php if(get_fp_reg($result["patientID"])=='Y'){echo "checked";}?>>Yes
						<input type='radio' name='fpcounseling' value='N' <?php if(get_fp_reg($result["patientID"])=='N'){echo "checked";}?>>No</p>
						<hr /><br />
					</div>
					
					<div class='width750'>
						<hr /><h4 class='center'>Pertinent Physical Examination Findings:</h4><hr />
						<br />
						<p class='columns5 indent70'>
						BP: <input type='text' name='pebp' style='width:100px' <?php echo "value='".get_vitals($result["patientID"],"BP")."'";?>><br />
						HR: <input type='text' name='pehr' style='width:100px' <?php echo "value='".get_vitals($result["patientID"],"vitals_heartrate")."'";?>><br />
						RR: <input type='text' name='perr' style='width:100px' <?php echo "value='".get_vitals($result["patientID"],"vitals_resprate")."'";?>><br />
						Height: <input type='text' name='peht' style='width:100px' <?php echo "value='".get_vitals($result["patientID"],"vitals_height")."'";?>>(cm)<br />
						Weight: <input type='text' name='pewt' style='width:100px' <?php echo "value='".get_vitals($result["patientID"],"vitals_weight")."'";?>>(kg)<br />
						Waist circumference: <input type='text' name='pewc' style='width:100px' <?php echo "value='".get_vitals($result["patientID"],"vitals_waist")."'";?>>(cm)<br />
						</p><br /><br />
						
						<label>Skin:</label>
						<p class='columns4 indent'>
						<input type='checkbox' name='skin' value='Pallor' <?php $arr = explode (',',get_pe_findings($result["patientID"],"skin_code")); foreach ($arr as $key => $value){if($value=='Skin01'){echo "checked";}}?>>Pallor<br />
						<input type='checkbox' name='skin' value='Rashes' <?php $arr = explode (',',get_pe_findings($result["patientID"],"skin_code")); foreach ($arr as $key => $value){if($value=='Skin02'){echo "checked";}}?>>Rashes<br />
						<input type='checkbox' name='skin' value='Jaundice' <?php $arr = explode (',',get_pe_findings($result["patientID"],"skin_code")); foreach ($arr as $key => $value){if($value=='Skin03'){echo "checked";}}?>>Jaundice<br />
						<input type='checkbox' name='skin' value='Good skin turgor' <?php $arr = explode (',',get_pe_findings($result["patientID"],"skin_code")); foreach ($arr as $key => $value){if($value=='Skin04'){echo "checked";}}?>>Good skin turgor<br />
						</p>
						<br />
						<p class='center'><label>Remarks: </label><textarea style='vertical-align: top' name='skinremarks' rows=2 cols='60'><?php echo get_pe_findings($result["patientID"],"skin_remarks");?></textarea></p>
						<br /><br />
						
						<label>HEENT:</label>
						<p class='columns3 indent'>
						<input type='checkbox' name='heent' value='Anicteric Sclerae' <?php $arr = explode (',',get_pe_findings($result["patientID"],"heent_code")); foreach ($arr as $key => $value){if($value=='HEENT01'){echo "checked";}}?>>Anicteric Sclerae<br />
						<input type='checkbox' name='heent' value='Pupils Briskly Reactive To Light' <?php $arr = explode (',',get_pe_findings($result["patientID"],"heent_code")); foreach ($arr as $key => $value){if($value=='HEENT02'){echo "checked";}}?>>Pupils Briskly Reactive To Light<br />
						<input type='checkbox' name='heent' value='Aural Discharge' <?php $arr = explode (',',get_pe_findings($result["patientID"],"heent_code")); foreach ($arr as $key => $value){if($value=='HEENT03'){echo "checked";}}?>>Aural Discharge<br />
						<input type='checkbox' name='heent' value='Intact Tympanic Membrane' <?php $arr = explode (',',get_pe_findings($result["patientID"],"heent_code")); foreach ($arr as $key => $value){if($value=='HEENT04'){echo "checked";}}?>>Intact Tympanic Membrane<br />
						<input type='checkbox' name='heent' value='Alar Flaring' <?php $arr = explode (',',get_pe_findings($result["patientID"],"heent_code")); foreach ($arr as $key => $value){if($value=='HEENT05'){echo "checked";}}?>>Alar Flaring<br />
						<input type='checkbox' name='heent' value='Nasal Discharge' <?php $arr = explode (',',get_pe_findings($result["patientID"],"heent_code")); foreach ($arr as $key => $value){if($value=='HEENT06'){echo "checked";}}?>>Nasal Discharge<br />
						<input type='checkbox' name='heent' value='Tonsillopharyngeal Congestion' <?php $arr = explode (',',get_pe_findings($result["patientID"],"heent_code")); foreach ($arr as $key => $value){if($value=='HEENT07'){echo "checked";}}?>>Tonsillopharyngeal Congestion<br />
						<input type='checkbox' name='heent' value='Hypertrophic Tonsils' <?php $arr = explode (',',get_pe_findings($result["patientID"],"heent_code")); foreach ($arr as $key => $value){if($value=='HEENT08'){echo "checked";}}?>>Hypertrophic Tonsils<br />
						<input type='checkbox' name='heent' value='Palpable Mass' <?php $arr = explode (',',get_pe_findings($result["patientID"],"heent_code")); foreach ($arr as $key => $value){if($value=='HEENT09'){echo "checked";}}?>>Palpable Mass<br />
						<input type='checkbox' name='heent' value='Exudates' <?php $arr = explode (',',get_pe_findings($result["patientID"],"heent_code")); foreach ($arr as $key => $value){if($value=='HEENT10'){echo "checked";}}?>>Exudates<br />
						</p><br />
						<p class='center'><label>Remarks: </label><textarea style='vertical-align: top' name='heentremarks' rows=2 cols='60'><?php echo get_pe_findings($result["patientID"],"heent_remarks");?></textarea></p>
						<br /><br />
						
						<label>Chest/Lungs:</label>
						<p class='columns6 indent'>
						<input type='checkbox' name='chest' value='Symmetrical Chest Expansion' <?php $arr = explode (',',get_pe_findings($result["patientID"],"chest_code")); foreach ($arr as $key => $value){if($value=='Chest/Lungs01'){echo "checked";}}?>>Symmetrical Chest Expansion<br />
						<input type='checkbox' name='chest' value='Clear Breathsounds' <?php $arr = explode (',',get_pe_findings($result["patientID"],"chest_code")); foreach ($arr as $key => $value){if($value=='Chest/Lungs02'){echo "checked";}}?>>Clear Breathsounds<br />
						<input type='checkbox' name='chest' value='Reactions' <?php $arr = explode (',',get_pe_findings($result["patientID"],"chest_code")); foreach ($arr as $key => $value){if($value=='Chest/Lungs03'){echo "checked";}}?>>Reactions<br />
						<input type='checkbox' name='chest' value='Crackles/Rales' <?php $arr = explode (',',get_pe_findings($result["patientID"],"chest_code")); foreach ($arr as $key => $value){if($value=='Chest/Lungs04'){echo "checked";}}?>>Crackles/Rales<br />
						<input type='checkbox' name='chest' value='Wheezes' <?php $arr = explode (',',get_pe_findings($result["patientID"],"chest_code")); foreach ($arr as $key => $value){if($value=='Chest/Lungs05'){echo "checked";}}?>>Wheezes<br />
						</p><br />
						<p class='center'><label>Remarks: </label><textarea style='vertical-align: top' name='chestremarks' rows=2 cols='60'><?php echo get_pe_findings($result["patientID"],"chest_remarks");?></textarea></p>
						<br /><br />
						
						<label>Heart:</label>
						<p class='columns6 indent'>
						<input type='checkbox' name='heart' value='Adynamic Precordium' <?php $arr = explode (',',get_pe_findings($result["patientID"],"heart_code")); foreach ($arr as $key => $value){if($value=='Heart01'){echo "checked";}}?>>Adynamic Precordium<br />
						<input type='checkbox' name='heart' value='Normal Rate Regular Rhythm' <?php $arr = explode (',',get_pe_findings($result["patientID"],"heart_code")); foreach ($arr as $key => $value){if($value=='Heart02'){echo "checked";}}?>>Normal Rate Regular Rhythm<br />
						<input type='checkbox' name='heart' value='Heaves/Thrills' <?php $arr = explode (',',get_pe_findings($result["patientID"],"heart_code")); foreach ($arr as $key => $value){if($value=='Heart03'){echo "checked";}}?>>Heaves/Thrills<br />
						<input type='checkbox' name='heart' value='Murmurs' <?php $arr = explode (',',get_pe_findings($result["patientID"],"heart_code")); foreach ($arr as $key => $value){if($value=='Heart04'){echo "checked";}}?>>Murmurs<br />
						</p><br />
						<p class='center'><label>Remarks: </label><textarea style='vertical-align: top' name='heartremarks' rows=2 cols='60'><?php echo get_pe_findings($result["patientID"],"heart_remarks");?></textarea></p>
						<br /><br />
						
						<label>Abdomen:</label>
						<p class='columns6 indent'>
						<input type='checkbox' name='abdomen' value='Flat' <?php $arr = explode (',',get_pe_findings($result["patientID"],"abdomen_code")); foreach ($arr as $key => $value){if($value=='Abdomen01'){echo "checked";}}?>>Flat<br />
						<input type='checkbox' name='abdomen' value='Globular' <?php $arr = explode (',',get_pe_findings($result["patientID"],"abdomen_code")); foreach ($arr as $key => $value){if($value=='Abdomen02'){echo "checked";}}?>>Globular<br />
						<input type='checkbox' name='abdomen' value='Flabby' <?php $arr = explode (',',get_pe_findings($result["patientID"],"abdomen_code")); foreach ($arr as $key => $value){if($value=='Abdomen03'){echo "checked";}}?>>Flabby<br />
						<input type='checkbox' name='abdomen' value='Muscle Guarding' <?php $arr = explode (',',get_pe_findings($result["patientID"],"abdomen_code")); foreach ($arr as $key => $value){if($value=='Abdomen04'){echo "checked";}}?>>Muscle Guarding<br />
						<input type='checkbox' name='abdomen' value='Tenderness' <?php $arr = explode (',',get_pe_findings($result["patientID"],"abdomen_code")); foreach ($arr as $key => $value){if($value=='Abdomen05'){echo "checked";}}?>>Tenderness<br />
						<input type='checkbox' name='abdomen' value='Palpable Mass' <?php $arr = explode (',',get_pe_findings($result["patientID"],"abdomen_code")); foreach ($arr as $key => $value){if($value=='Abdomen06'){echo "checked";}}?>>Palpable Mass<br />
						</p><br />
						<p class='center'><label>Remarks: </label><textarea style='vertical-align: top' name='abdomenremarks' rows=2 cols='60'><?php echo get_pe_findings($result["patientID"],"abdomen_remarks");?></textarea></p>
						<br /><br />
						
						<label>Extremities:</label>
						<p class='columns6 indent'>
						<input type='checkbox' name='extrem' value='Gross Deformity' <?php $arr = explode (',',get_pe_findings($result["patientID"],"extremities_code")); foreach ($arr as $key => $value){if($value=='Extremities01'){echo "checked";}}?>>Gross Deformity<br />
						<input type='checkbox' name='extrem' value='Normal Gait' <?php $arr = explode (',',get_pe_findings($result["patientID"],"extremities_code")); foreach ($arr as $key => $value){if($value=='Extremities02'){echo "checked";}}?>>Normal Gait<br />
						<input type='checkbox' name='extrem' value='Full and Equal Pulses' <?php $arr = explode (',',get_pe_findings($result["patientID"],"extremities_code")); foreach ($arr as $key => $value){if($value=='Extremities03'){echo "checked";}}?>>Full and Equal Pulses<br />
						</p><br />
						<p class='center'><label>Remarks: </label><textarea style='vertical-align: top' name='extremremarks' rows=2 cols='60'><?php echo get_pe_findings($result["patientID"],"extremities_remarks");?></textarea></p>
						<br /><br />
					</div>
					
					<!-- <div class='width750 center'>
						<hr />
						<label>Select Existing Form: </label>
						<select name='existform'>
						<option value=''>Form 1</option>
						<option value=''>Form 2</option>
						<option value=''>Form 3</option>
						<option value=''>Form 4</option>
						<option value=''>Form 5</option>
						</select>
						<input type='submit' value='Select Form'>
						<hr />
					</div>
					
					<div class='width750 center'>
						
						<button type='submit' title='Save Form'>
							<img src='../styles/images/save.png' alt='Save Form'>
						</button>
						<button type='submit' title='Cancel'>
							<img src='../styles/images/cancel.png' alt='Cancel'>
						</button>
						<br />
						<button type='submit' title='Update Form'>
							<img src='../styles/images/update.png' alt='Update Form'>
						</button>
						<button type='submit' title='Delete Form'>
							<img src='../styles/images/delete.png' alt='Delete Form'>
						</button>
						<button type='submit' title='Printer Friendly Format'>
							<img src='../styles/images/printer.png' alt='Printer Friendly Format'>
						</button>
						<button type='submit' title='Download PDF File'>
							<img src='../styles/images/pdf.png' alt='Download PDF File'>
						</button>
						<button type='submit' title='Download XML File'>
							<img src='../styles/images/xml.png' alt='Download XML File'>
						</button>
						
					</div>
										
					<div id='submit' class='width750'>
						<hr />
						<span class='width100'>
						<input type='submit' name='submit' value='Submit'></span>
						<input type='reset' name='submit' value='Reset'>
						<hr /><br />
					</div>-->
					
					</form>
				</div>
			</div>
		</body>
	</html>


