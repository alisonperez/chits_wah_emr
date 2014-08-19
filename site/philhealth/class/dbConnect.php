<?php
	define ("dbHost", "localhost");
	define ("dbUser", $_SESSION['dbuser']);
	define ("dbPass", $_SESSION['dbpass']);
	define ("dbName", $_SESSION['dbname']);
	
	Class dbConnect
	{
		public $dbCon;
		
		public function __construct()
		{
			$this->_dbOpen();
		}
		
		private function _dbOpen()
		{
			$this->dbCon = mysqli_connect(dbHost,dbUser,dbPass,dbName);
			if(!$this->dbCon)
			{
				die ("Connection Failed" . mysqli_errno());
			}
		}
		
		public function _dbQuery($sql)
		{
			return mysqli_query($this->dbCon,$sql);
		}
		
		public function _dbFetchArr($dbQuery)
		{
			return mysqli_fetch_array($dbQuery);
		}
		
		public function _sqlConnect($sql)
		{
			return $this->_dbFetchArr($this->_dbQuery($sql));
		}
		
	}
	
	Class sqlStatement extends dbConnect
	{
		public function _genericDrugs($type='', $sDate='', $eDate='', $inputName='')
		{
			switch ($type)
			{
				case 'Asthma':
					$icd10 = "J45";
					break;
				case 'AGE':
					$icd10 = "A09";
					break;
				case 'URTI':
					$icd10 = "J06|J18";
					break;
				case 'UTI':
					$icd10 = "N39";
					break;
				default:
					break;
			}
			$sql = "SELECT generic_id, generic_name FROM `m_consult_pcb_drugs` a JOIN m_patient_philhealth b ON a.patient_id=b.patient_id JOIN m_lib_pcb_drugs c ON a.generic_id= c.record_id JOIN m_lib_notes_dxclass d ON a.class_id=d.class_id JOIN m_consult_pcb_dispense e ON a.dispense_id=e.dispense_id WHERE icd10 REGEXP '$icd10' AND date_format(dispense_date, '%Y-%m-%d') BETWEEN '$sDate' AND '$eDate' GROUP BY generic_id ORDER BY generic_name ASC";
			//"SELECT COUNT(drug_id), generic_id, generic_name FROM `m_consult_pcb_drugs` a JOIN m_patient_philhealth b ON a.patient_id=b.patient_id JOIN m_lib_pcb_drugs c ON a.generic_id= c.record_id GROUP BY cat_id";
			if ($query = $this->_dbQuery($sql))
			{
				$count=0;
				if (mysqli_num_rows($query))
				{				
					while ($result = $this->_dbFetchArr($query))
					{
						$count++;
						$sql_member = "SELECT COUNT(generic_id) AS countDrug FROM `m_consult_pcb_drugs` a JOIN m_patient_philhealth b ON a.patient_id=b.patient_id JOIN m_lib_pcb_drugs c ON a.generic_id= c.record_id JOIN m_lib_notes_dxclass d ON a.class_id=d.class_id JOIN m_consult_pcb_dispense e ON a.dispense_id=e.dispense_id WHERE icd10 REGEXP '$icd10' AND b.member_id IN (1,2,3,5,7,8,9,10,11,12,13) AND date_format(dispense_date, '%Y-%m-%d') BETWEEN '$sDate' AND '$eDate' GROUP BY generic_id HAVING generic_id = '".$result["generic_id"]."'";
						$result_member = $this->_sqlConnect($sql_member);
						$sql_dependent = "SELECT COUNT(generic_id) AS countDrug FROM `m_consult_pcb_drugs` a JOIN m_patient_philhealth b ON a.patient_id=b.patient_id JOIN m_lib_pcb_drugs c ON a.generic_id= c.record_id JOIN m_lib_notes_dxclass d ON a.class_id=d.class_id JOIN m_consult_pcb_dispense e ON a.dispense_id=e.dispense_id WHERE icd10 REGEXP '$icd10' AND b.member_id IN (4) AND date_format(dispense_date, '%Y-%m-%d') BETWEEN '$sDate' AND '$eDate' GROUP BY generic_id HAVING generic_id = '".$result["generic_id"]."'";
						$result_dependent = $this->_sqlConnect($sql_dependent);
						echo "<tr>";
							echo "<th width='350px'><span>Med $count </span><input style='width:190px' type='text' name='".$inputName.$count."' value='".$result["generic_name"]."'></th>";
							echo "<th width='100px'><input style='width:70px; text-align:right;' type='text' name='".$inputName.$count."Mem' value='".($result_member["countDrug"]==null ?'0' : $result_member["countDrug"])."'></th>";
							echo "<th width='100px'><input style='width:70px; text-align:right;' type='text' name='".$inputName.$count."Dep' value='".($result_dependent["countDrug"]==null ?'0' : $result_dependent["countDrug"])."'></th>";
						echo "</tr>";
					}
				}
				else 
				{
					echo "<tr>";
						echo "<th width='350px'><span>Med 1 </span><input style='width:190px' type='text' name='".$inputName.$count."' value=''></th>";
						echo "<th width='100px'><input style='width:70px; text-align:right;' type='text' name='".$inputName.$count."Mem' value=''></th>";
						echo "<th width='100px'><input style='width:70px; text-align:right;' type='text' name='".$inputName.$count."Dep' value=''></th>";
					echo "</tr>";
				}
			}
		}
		
		public function _breastCancerScreening($type='' , $sDate='', $eDate='')
		{
			$sql = "SELECT COUNT(DISTINCT consult_id) FROM m_consult_notes_pe a JOIN m_patient b ON a.patient_id = b.patient_id JOIN (SELECT * FROM m_patient_philhealth GROUP BY patient_id HAVING count(patient_id)>=1) c ON a.patient_id = c.patient_id WHERE breast_screen != '' ";
			switch ($type)
			{
				case 'Member':
					$sql .= " AND c.member_id IN (1,2,3,5,7,8,9,10,11,12,13) ";
					break;
				case 'Dependent':
					$sql .= " AND c.member_id = 4 ";
					break;
				case 'Total':
					$sql .= " AND c.member_id IN (1,2,3,4,5,7,8,9,10,11,12,13) ";
					 
					break;
				default:
					break;
			}
			$sql .= " AND date_format(pe_timestamp, '%Y-%m-%d') BETWEEN '$sDate' AND '$eDate' ";
			$sql .= " AND round((to_days(pe_timestamp)-to_days(patient_dob))/365,1) >= 25 AND patient_gender = 'F'";
			
			$result = $this->_sqlConnect($sql);
			return $result["COUNT(DISTINCT consult_id)"];
		}
		
		public function _waist($gender='', $type='' , $sDate='', $eDate='')
		{
			$sql = "SELECT COUNT(DISTINCT consult_id) FROM `m_consult_vitals` a JOIN m_patient b ON a.patient_id = b.patient_id JOIN (SELECT * FROM m_patient_philhealth GROUP BY patient_id HAVING count(patient_id)>=1) c ON a.patient_id = c.patient_id ";
			switch ($gender) {
				case 'M':
					$sql .= " WHERE patient_gender = 'M' AND vitals_waist >= 90 ";
					break;
					
				case 'F':
					$sql .= " WHERE patient_gender = 'F' AND vitals_waist >= 80 ";
					break;
				
				default:
					break;
			}
			
			switch ($type)
			{
				case 'Member':
					$sql .= " AND c.member_id IN (1,2,3,5,7,8,9,10,11,12,13) ";
					break;
				case 'Dependent':
					$sql .= " AND c.member_id = 4 ";
					break;
				case 'Total':
					$sql .= " AND c.member_id IN (1,2,3,4,5,7,8,9,10,11,12,13) ";
					 
					break;
				default:
					break;
			}
			
			$sql .= " AND date_format(vitals_timestamp, '%Y-%m-%d') BETWEEN '$sDate' AND '$eDate' ";
			
			$result = $this->_sqlConnect($sql);
			return $result['COUNT(DISTINCT consult_id)'];
		}
		
		public function _history($history='',  $type='', $gender='', $pregnant='', $sDate='', $eDate='')
		{
			//$sql = "SELECT COUNT(a.notes_id) FROM `m_consult_notes` a JOIN m_patient b ON a.patient_id = b.patient_id JOIN m_patient_philhealth c ON a.patient_id = c.patient_id WHERE notes_history LIKE '%$history%'";
			$sql = "SELECT COUNT(record_id) FROM `m_patient_history` a JOIN m_patient b ON a.patient_id = b.patient_id JOIN m_patient_philhealth c ON a.patient_id = c.patient_id ";
			if ($history == '6' || $history == '11')
			{
				$sql .= "WHERE pasthistory_id LIKE '%$history%'";
			}
			if ($history == 'ORALHYPO' || $history =='HYPERMED')
			{
				$sql .= "WHERE medintake_id LIKE '%$history%'";
			}
			
			switch ($gender)
			{
				case 'M' OR 'F':
					$sql .= " AND patient_gender = '$gender' ";
					break;
				default:
					break;
					
			}
							
			$sql .= " AND date_format(history_timestamp, '%Y-%m-%d') BETWEEN '$sDate' AND '$eDate' ";
			
			switch($pregnant)
			{
				case 'Y':
					//$sql .= " AND b.vitals_timestamp < CASE WHEN delivery_date = '0000-00-00' THEN patient_edc ELSE delivery_date END ";
					$sql .= " AND a.consult_id IN (SELECT a.consult_id FROM m_consult_notes a JOIN m_patient_mc b ON a.patient_id = b.patient_id  WHERE date_format(notes_timestamp, '%Y-%m-%d') BETWEEN '$sDate' AND '$eDate' AND notes_timestamp < CASE WHEN delivery_date = '0000-00-00' THEN patient_edc ELSE delivery_date END) ";
					break;
				case 'N':
					$sql .= " AND a.consult_id NOT IN (SELECT a.consult_id FROM m_consult_notes a JOIN m_patient_mc b ON a.patient_id = b.patient_id  WHERE date_format(notes_timestamp, '%Y-%m-%d') BETWEEN '$sDate' AND '$eDate' AND notes_timestamp < CASE WHEN delivery_date = '0000-00-00' THEN patient_edc ELSE delivery_date END) ";
					break;
				default:
					break;
			}
			
			switch ($type)
			{
				case 'Member':
					$sql .= " AND c.member_id IN (1,2,3,5,7,8,9,10,11,12,13) ";
					break;
				case 'Dependent':
					$sql .= " AND c.member_id = 4 ";
					break;
				case 'Total':
					$sql .= " AND c.member_id IN (1,2,3,4,5,7,8,9,10,11,12,13) ";
					 
					break;
				default:
					break;
			}
			//echo $sql . "<br />";
			$result = $this->_sqlConnect($sql);
			return $result['COUNT(record_id)'];
		}
		
		public function _ppsScreening($service='', $type='', $sDate='', $eDate='')
		{
			$sql = "SELECT COUNT(a.consult_id) FROM `m_consult_philhealth_labs` a 
					JOIN m_consult b ON a.consult_id = b.consult_id 
					JOIN m_patient c ON b.patient_id = c.patient_id 
					JOIN m_patient_philhealth d ON c.patient_id = d.patient_id 
					WHERE lab_id = '$service' ";
			if (($sDate!='' || $sDate!=null) && ($eDate!='' || $eDate!=null))
			{
				$sql .= " AND date_format(lab_timestamp, '%Y-%m-%d') BETWEEN '$sDate' AND '$eDate'";
			}
			if ($type=='Member')
			{
				$sql .= " AND member_id IN (1,2,3,5,7,8,9,10,11,12,13) ";
			}
			elseif ($type=='Dependent')
			{
				$sql .= " AND member_id = 4 ";
			}
			
			$sql .= " AND round((to_days(lab_timestamp)-to_days(patient_dob))/365,1) BETWEEN '25' AND '55.99' AND patient_gender = 'F'";
			//echo "<br />" . $sql;
			$result = $this->_sqlConnect($sql);
			return $result['COUNT(a.consult_id)'];
		}
		
		public function _countHypertension($case='', $type='', $gender='', $pregnant='', $sDate='', $eDate='')
		{
			$sample = "SELECT COUNT(a.consult_id), e.patient_id, CASE WHEN delivery_date = '0000-00-00' THEN a.service_timestamp < patient_edc ELSE a.service_timestamp < delivery_date END AS Pregnant FROM `m_consult_philhealth_services` a
	JOIN m_consult_vitals b ON a.consult_id = b.consult_id
	JOIN m_patient c ON b.patient_id = c.patient_id
	JOIN m_patient_philhealth d ON c.patient_id = d.patient_id
        JOIN m_patient_mc e ON b.patient_id = e.patient_id 
        WHERE a.service_timestamp BETWEEN '2013-01-01' AND '2013-12-31'
        GROUP BY patient_id";
			$try = "SELECT COUNT(a.consult_id), e.patient_id 
        FROM `m_consult_philhealth_services` a
	JOIN m_consult_vitals b ON a.consult_id = b.consult_id
	JOIN m_patient c ON b.patient_id = c.patient_id
	JOIN m_patient_philhealth d ON c.patient_id = d.patient_id
        JOIN m_patient_mc e ON b.patient_id = e.patient_id 
        WHERE a.service_timestamp BETWEEN '2013-01-01' AND '2013-12-31' AND a.service_timestamp < CASE WHEN delivery_date = '0000-00-00' THEN patient_edc ELSE delivery_date END 
        GROUP BY patient_id";
			$itona ="SELECT COUNT(a.consult_id), e.patient_id, delivery_date, patient_edc, b.vitals_timestamp, vitals_systolic, vitals_diastolic, 
        CASE
          WHEN vitals_systolic >= '160' OR vitals_diastolic >= '100' THEN 'Hypertension Stage 2'
          WHEN vitals_systolic BETWEEN '140' AND '159' OR vitals_diastolic BETWEEN '90' AND '99' THEN 'Hypertesnsion Stage 1'
          WHEN vitals_systolic BETWEEN '120' AND '139' OR vitals_diastolic BETWEEN '80' AND '89' THEN 'Prehypertension'
          WHEN vitals_systolic < '120' AND vitals_diastolic < '80' THEN 'Normal'
        END AS Pregnant 
        FROM `m_consult_philhealth_services` a
	JOIN m_consult_vitals b ON a.consult_id = b.consult_id
	JOIN m_patient c ON b.patient_id = c.patient_id
	JOIN m_patient_philhealth d ON c.patient_id = d.patient_id
        JOIN m_patient_mc e ON b.patient_id = e.patient_id WHERE date_format(b.vitals_timestamp, '%Y-%m-%d') BETWEEN '2013-01-01' AND '2013-05-31' AND b.vitals_timestamp < CASE WHEN delivery_date = '0000-00-00' THEN patient_edc ELSE delivery_date END
       GROUP BY Pregnant";
			//$sql = "SELECT c.patient_id, vitals_systolic, vitals_diastolic FROM `m_consult_philhealth_services` a JOIN m_consult_vitals b ON a.consult_id = b.consult_id JOIN m_patient c ON b.patient_id = c.patient_id JOIN m_patient_philhealth d ON c.patient_id = d.patient_id WHERE round((to_days(a.service_timestamp)-to_days(patient_dob))/365,1) >= 18 AND patient_gender = 'M' AND (vitals_systolic < 140 AND vitals_diastolic < 90) AND d.member_id IN (1,2,3,5)";
			//$sql = "SELECT c.patient_id, vitals_systolic, vitals_diastolic FROM `m_consult_philhealth_services` a JOIN m_consult_vitals b ON a.consult_id = b.consult_id JOIN m_patient c ON b.patient_id = c.patient_id JOIN m_patient_philhealth d ON c.patient_id = d.patient_id WHERE round((to_days(a.service_timestamp)-to_days(patient_dob))/365,1) >= 18 AND patient_gender = 'F' AND (vitals_systolic < 140 AND vitals_diastolic < 90) AND d.member_id IN (1,2,3,5) AND a.consult_id NOT IN (SELECT consult_id FROM m_patient_mc)";
			//$sql = "SELECT c.patient_id, vitals_systolic, vitals_diastolic FROM `m_consult_philhealth_services` a JOIN m_consult_vitals b ON a.consult_id = b.consult_id JOIN m_patient c ON b.patient_id = c.patient_id JOIN m_patient_philhealth d ON c.patient_id = d.patient_id JOIN m_patient_mc e ON b.patient_id = e.patient_id WHERE round((to_days(a.service_timestamp)-to_days(patient_dob))/365,1) >= 18 AND patient_gender = 'F' AND (vitals_systolic < 140 AND vitals_diastolic < 90) AND d.member_id IN (1,2,3,5) AND date_format(a.service_timestamp, '%Y-%m-%d') BETWEEN '2013-01-01' AND '2013-12-31'";
			/*$sql = "SELECT COUNT(a.consult_id) FROM `m_consult_philhealth_services` a
					JOIN m_consult_vitals b ON a.consult_id = b.consult_id
					JOIN m_patient c ON b.patient_id = c.patient_id
					JOIN m_patient_philhealth d ON c.patient_id = d.patient_id ";*/
			/*$sql = "SELECT COUNT(DISTINCT a.consult_id), 
        			CASE
          				WHEN vitals_systolic >= '180' OR vitals_diastolic >= '120' THEN 'Hypertension Stage 2'
          				WHEN vitals_systolic BETWEEN '140' AND '179' OR vitals_diastolic BETWEEN '90' AND '119' THEN 'Hypertension Stage 1'
          				WHEN vitals_systolic BETWEEN '120' AND '139' OR vitals_diastolic BETWEEN '80' AND '89' THEN 'Prehypertension'
          				WHEN vitals_systolic < '120' AND vitals_diastolic < '80' THEN 'Normal'
        			END AS Pregnant 
        			FROM `m_consult_philhealth_services` a
					JOIN m_consult_vitals b ON a.consult_id = b.consult_id
					JOIN m_patient c ON b.patient_id = c.patient_id
					JOIN m_patient_philhealth d ON c.patient_id = d.patient_id";*/
			$sql = "SELECT COUNT(DISTINCT a.consult_id), 
        			CASE
          				WHEN vitals_systolic >= '180' OR vitals_diastolic >= '120' THEN 'Hypertension Stage 2'
          				WHEN vitals_systolic BETWEEN '140' AND '179' OR vitals_diastolic BETWEEN '90' AND '119' THEN 'Hypertension Stage 1'
          				WHEN vitals_systolic BETWEEN '120' AND '139' OR vitals_diastolic BETWEEN '80' AND '89' THEN 'Prehypertension'
          				WHEN vitals_systolic < '120' AND vitals_diastolic < '80' THEN 'Normal'
        			END AS BP 
        			FROM `m_consult_vitals` a
					JOIN m_patient b ON b.patient_id = a.patient_id
					JOIN m_patient_philhealth c ON c.patient_id = b.patient_id";
			
			/*switch($pregnant)
					{
						case 'Y':
							$sql .= " JOIN m_patient_mc e ON b.patient_id = e.patient_id ";
							break;
						default:
							break;
					}*/
			
			$sql .= " WHERE round((to_days(a.vitals_timestamp)-to_days(patient_dob))/365,1) >= 18 ";
			
			switch ($gender)
			{
				case 'M' OR 'F':
					$sql .= " AND patient_gender = '$gender' ";
					break;
				default:
					break;
					
			}
							
			$sql .= " AND date_format(a.vitals_timestamp, '%Y-%m-%d') BETWEEN '$sDate' AND '$eDate' ";
			
			switch($pregnant)
			{
				case 'Y':
					//$sql .= " AND b.vitals_timestamp < CASE WHEN delivery_date = '0000-00-00' THEN patient_edc ELSE delivery_date END ";
					$sql .= " AND a.consult_id IN (SELECT a.consult_id FROM m_consult_vitals a JOIN m_patient_mc b ON a.patient_id = b.patient_id  WHERE date_format(vitals_timestamp, '%Y-%m-%d') BETWEEN '$sDate' AND '$eDate' AND vitals_timestamp < CASE WHEN delivery_date = '0000-00-00' THEN patient_edc ELSE delivery_date END) ";
					break;
				case 'N':
					$sql .= " AND a.consult_id NOT IN (SELECT a.consult_id FROM m_consult_vitals a JOIN m_patient_mc b ON a.patient_id = b.patient_id  WHERE date_format(vitals_timestamp, '%Y-%m-%d') BETWEEN '$sDate' AND '$eDate' AND vitals_timestamp < CASE WHEN delivery_date = '0000-00-00' THEN patient_edc ELSE delivery_date END) ";
					break;
				default:
					break;
			}
			
			switch ($type)
			{
				case 'Member':
					$sql .= " AND c.member_id IN (1,2,3,5,7,8,9,10,11,12,13) ";
					break;
				case 'Dependent':
					$sql .= " AND c.member_id = 4 ";
					break;
				case 'Total':
					$sql .= " AND c.member_id IN (1,2,3,4,5,7,8,9,10,11,12,13) ";
					 
					break;
				default:
					break;
			}
			
			if($case!='' || $case!=null)
			{
				$sql .= " GROUP BY BP Having BP = '$case'";
			}
			
			//echo "<br />" . $sql . "<br />";
			$result = $this->_sqlConnect($sql);
			return $result['COUNT(DISTINCT a.consult_id)'];
		}
		
		public function _countMemberByType($type='',$sDate='',$eDate='')
		{
			switch ($type)
			{
				case 'SP-NHTS':
					$sql = "SELECT COUNT(DISTINCT a.family_id) FROM `m_family_members` a  
							JOIN m_family_cct_member c ON a.family_id = c.family_id 
							JOIN (SELECT * FROM m_patient_philhealth GROUP BY patient_id HAVING count(patient_id)>=1) b ON a.patient_id = b.patient_id
							JOIN m_consult d ON d.patient_id = a.patient_id WHERE date_format(d.consult_date, '%Y-%m-%d') BETWEEN '$sDate' AND '$eDate'";
					/*$sql = "SELECT COUNT(DISTINCT a.family_id) FROM `m_family_members` a
					JOIN m_family_cct_member b ON a.family_id = b.family_id
					JOIN m_consult c ON a.patient_id = c.patient_id WHERE date_format(c.consult_date, '%Y-%m-%d') BETWEEN '$sDate' AND '$eDate'";*/
					$sql2 = "SELECT COUNT(DISTINCT philhealth_id) FROM `m_patient_philhealth` a 
							JOIN m_consult b ON a.patient_id = b.patient_id LEFT JOIN `m_family_members` c ON a.patient_id = c.patient_id 
							WHERE member_id = 7 AND consult_date BETWEEN '$sDate' AND '$eDate'";
					$countID = "COUNT(DISTINCT a.family_id)";
					//echo "<br />" . $sql;
					break;
								
				case 'SP-LGU':
					$sql = "SELECT COUNT(DISTINCT philhealth_id) FROM `m_patient_philhealth` a 
							JOIN m_consult b ON a.patient_id = b.patient_id 
							WHERE member_id = 9 AND consult_date BETWEEN '$sDate' AND '$eDate'";
					$countID = "COUNT(DISTINCT philhealth_id)";
					break;
					
				case 'SP-NGA':
					$sql = "SELECT COUNT(DISTINCT philhealth_id) FROM `m_patient_philhealth` a 
							JOIN m_consult b ON a.patient_id = b.patient_id 
							WHERE member_id = 8 AND consult_date BETWEEN '$sDate' AND '$eDate'";
					$countID = "COUNT(DISTINCT philhealth_id)";
					break;
					
				case 'SP-Private':
					$sql = "SELECT COUNT(DISTINCT philhealth_id) FROM `m_patient_philhealth` a 
							JOIN m_consult b ON a.patient_id = b.patient_id 
							WHERE member_id = 10 AND consult_date BETWEEN '$sDate' AND '$eDate'";
					$countID = "COUNT(DISTINCT philhealth_id)";
					break;
					
				case 'IPP-OFW':
					$sql = "SELECT COUNT(DISTINCT philhealth_id) FROM `m_patient_philhealth` a 
							JOIN m_consult b ON a.patient_id = b.patient_id 
							WHERE member_id = 3 AND consult_date BETWEEN '$sDate' AND '$eDate'";
					$countID = "COUNT(DISTINCT philhealth_id)";
					break;
				
				case 'IPP-OG':
					$sql = "SELECT COUNT(DISTINCT philhealth_id) FROM `m_patient_philhealth` a 
							JOIN m_consult b ON a.patient_id = b.patient_id 
							WHERE member_id = 11 AND consult_date BETWEEN '$sDate' AND '$eDate'";
					$countID = "COUNT(DISTINCT philhealth_id)";
					break;
					
				case 'NON-PHIC':
					/*$sql = "SELECT COUNT(a.patient_id) FROM `m_patient` a 
							WHERE a.patient_id NOT IN 
							(SELECT a.patient_id FROM m_patient_philhealth a JOIN m_consult b ON b.patient_id = a.patient_id WHERE date_format(b.consult_date, '%Y-%m-%d') BETWEEN '$sDate' AND '$eDate' GROUP BY a.patient_id HAVING count(a.patient_id)>=1)";*/
					/*$sql = "SELECT COUNT(a.patient_id) FROM `m_patient` a 
							JOIN (SELECT * FROM m_consult GROUP BY patient_id HAVING count(patient_id)>=1) b ON a.patient_id = b.patient_id 
							WHERE a.patient_id NOT IN (SELECT a.patient_id FROM m_patient_philhealth a JOIN m_consult b ON b.patient_id = a.patient_id GROUP BY a.patient_id HAVING count(a.patient_id)>=1) 
							AND date_format(b.consult_date, '%Y-%m-%d') BETWEEN '$sDate' AND '$eDate'";*/
					/*$sql = "SELECT COUNT(a.patient_id) FROM `m_patient` a
							JOIN (SELECT * FROM m_consult GROUP BY patient_id HAVING count(patient_id)>=1) b ON a.patient_id = b.patient_id
							WHERE a.patient_id NOT IN (SELECT a.patient_id FROM m_patient_philhealth a JOIN (SELECT * FROM m_consult GROUP BY patient_id HAVING count(patient_id)>=1) b ON b.patient_id = a.patient_id)
							AND date_format(b.consult_date, '%Y-%m-%d') BETWEEN '$sDate' AND '$eDate'";*/
					
					/*$sql = "SELECT COUNT(DISTINCT family_id) FROM `m_family_members` a 
							JOIN (SELECT * FROM m_consult GROUP BY patient_id HAVING count(patient_id)>=1) b ON a.patient_id = b.patient_id 
							WHERE a.patient_id NOT IN (SELECT a.patient_id FROM m_patient_philhealth a JOIN m_consult b ON b.patient_id = a.patient_id GROUP BY a.patient_id HAVING count(a.patient_id)>=1) 
							AND date_format(b.consult_date, '%Y-%m-%d') BETWEEN '$sDate' AND '$eDate'";*/
					$sql = "SELECT COUNT(DISTINCT family_id) FROM `m_family_members` a 
							JOIN (SELECT * FROM m_consult GROUP BY patient_id HAVING count(patient_id)>=1) b ON a.patient_id = b.patient_id 
							LEFT JOIN m_patient_philhealth c ON a.patient_id = c.patient_id WHERE date_format(b.consult_date, '%Y-%m-%d') BETWEEN '$sDate' AND '$eDate' group by c.patient_id having isnull(c.patient_id)";
					$countID = "COUNT(DISTINCT family_id)";
					//echo $sql;
					break;
				default:
					break;
			}
			$result = $this->_sqlConnect($sql);
			return $result[$countID];
		}
		
		public function _getPCBProvider($select='')
		{
			$sql = "SELECT * FROM `m_lib_health_facility` WHERE doh_class_id = '".$_SESSION['doh_facility_code']."'";
			$result = $this->_sqlConnect($sql);
			if ($select=='region')
			{
				return $result['psgc_regcode'];
			}
			elseif ($select=='regnumber')
			{
				return $result['philhealth_reg_num'];
			}
		}
		
		public function _countBloodPressure($bp='',$sDate='',$eDate='')
		{
			/*$sql = "SELECT COUNT(DISTINCT a.consult_id),
					CASE
						WHEN vitals_systolic >= '160' OR vitals_diastolic >= '100' OR vitals_systolic BETWEEN '140' AND '159' OR vitals_diastolic BETWEEN '90' AND '99' THEN 'Hypertension'
						WHEN vitals_systolic BETWEEN '120' AND '139' OR vitals_diastolic BETWEEN '80' AND '89' OR vitals_systolic < '120' AND vitals_diastolic < '80' THEN 'Normal'
					END AS blood_pressure
					FROM `m_consult_vitals` a, `m_consult_philhealth_services` b
					WHERE a.consult_id = b.consult_id";*/
			$sql = "SELECT COUNT(DISTINCT a.consult_id),
					CASE
						WHEN vitals_systolic >= '160' OR vitals_diastolic >= '100' OR vitals_systolic BETWEEN '140' AND '159' OR vitals_diastolic BETWEEN '90' AND '99' THEN 'Hypertension'
						WHEN vitals_systolic BETWEEN '120' AND '139' OR vitals_diastolic BETWEEN '80' AND '89' OR vitals_systolic < '120' AND vitals_diastolic < '80' THEN 'Normal'
					END AS blood_pressure
					FROM `m_consult_vitals` a JOIN `m_patient_philhealth` b ON a.patient_id = b.patient_id ";
			
			if (($sDate!='' || $sDate!=null) && ($eDate!='' || $eDate!=null))
			{
				$sql .= " AND date_format(a.vitals_timestamp, '%Y-%m-%d') BETWEEN '$sDate' AND '$eDate'";
			}
			
			
			if ($bp!="" || $bp!=null)
			{
				$sql .= " GROUP BY blood_pressure HAVING blood_pressure = '$bp'";
			}
			//echo $sql;
			$result = $this->_sqlConnect($sql);
			return $result['COUNT(DISTINCT a.consult_id)'];
		}
		
		public function _countTotalMembers($gender='',$sDate='',$eDate='', $ageStart='', $ageEnd='')
		{
			/*$sql = "SELECT COUNT(a.patient_id) FROM `m_patient_philhealth` a, `m_patient` b 
					WHERE a.patient_id = b.patient_id 
					AND (a.member_id = 1 OR a.member_id = 2 OR a.member_id = 3 OR a.member_id = 5)";*/
			$sql = "SELECT COUNT(a.patient_id) FROM `m_patient_philhealth` a 
					JOIN `m_patient` b ON a.patient_id = b.patient_id 
					JOIN (SELECT * FROM `m_consult` GROUP BY patient_id HAVING count(patient_id)>=1) c 
					ON a.patient_id = c.patient_id 
					WHERE date_format(c.consult_date, '%Y-%m-%d') BETWEEN '$sDate' AND '$eDate' AND a.member_id IN (1,2,3,5,7,8,9,10,11,12,13)";
			if ($gender!="" || $gender!=null)
			{
				$sql .= " AND b.patient_gender = '$gender'";
			}
			if (($ageStart!='' && $ageStart!=60) && ($ageEnd=='' || $ageEnd==null))
			{
				$sql .= " AND round((to_days('$eDate')-to_days(b.patient_dob))/365,1) <= $ageStart + .99";
			}
			if (($ageStart!='' && $ageStart==60) && ($ageEnd=='' || $ageEnd==null))
			{
				$sql .= " AND round((to_days('$eDate')-to_days(b.patient_dob))/365,1) >= $ageStart";
			}
			if ($ageEnd!='' || $ageEnd!=null )
			{
				$sql .= " AND round((to_days('$eDate')-to_days(b.patient_dob))/365,1) >= $ageStart
						  AND round((to_days('$eDate')-to_days(b.patient_dob))/365,1) <= $ageEnd + .99";
			}
			$result = $this->_sqlConnect($sql);
			return $result['COUNT(a.patient_id)'];
		}
		
		public function _countTotalDependents($gender='',$sDate='',$eDate='', $ageStart='', $ageEnd='')
		{
			/*$sql = "SELECT COUNT(a.patient_id) FROM `m_patient_philhealth` a, `m_patient` b 
					WHERE a.patient_id = b.patient_id 
					AND a.member_id = 4";*/
			$sql = "SELECT COUNT(a.patient_id) FROM `m_patient_philhealth` a 
					JOIN `m_patient` b ON a.patient_id = b.patient_id 
					JOIN (SELECT * FROM `m_consult` GROUP BY patient_id HAVING count(patient_id)>=1) c 
					ON a.patient_id = c.patient_id 
					WHERE date_format(c.consult_date, '%Y-%m-%d') BETWEEN '$sDate' AND '$eDate' AND a.member_id = 4";
			if ($gender!="" || $gender!=null)
			{
				$sql .= " AND b.patient_gender = '$gender'";
			}
			if (($ageStart!='' && $ageStart!=60) && ($ageEnd=='' || $ageEnd==null))
			{
				$sql .= " AND round((to_days('$eDate')-to_days(b.patient_dob))/365,1) <= $ageStart + .99";
			}
			if (($ageStart!='' && $ageStart==60) && ($ageEnd=='' || $ageEnd==null))
			{
				$sql .= " AND round((to_days('$eDate')-to_days(b.patient_dob))/365,1) >= $ageStart";
			}
			if ($ageEnd!='' || $ageEnd!=null )
			{
				$sql .= " AND round((to_days('$eDate')-to_days(b.patient_dob))/365,1) >= $ageStart
						  AND round((to_days('$eDate')-to_days(b.patient_dob))/365,1) <= $ageEnd + .99";
			}
			$result = $this->_sqlConnect($sql);
			return $result['COUNT(a.patient_id)'];	
		}
		
		public function _countTotalMemDep($gender='',$sDate='',$eDate='', $ageStart='', $ageEnd='')
		{
			$sql = "SELECT COUNT(a.patient_id) FROM `m_patient_philhealth` a 
					JOIN `m_patient` b ON a.patient_id = b.patient_id 
					JOIN (SELECT * FROM `m_consult` GROUP BY patient_id HAVING count(patient_id)>=1) c 
					ON a.patient_id = c.patient_id 
					WHERE date_format(c.consult_date, '%Y-%m-%d') BETWEEN '$sDate' AND '$eDate' AND a.member_id IN (1,2,3,4,5,7,8,9,10,11,12,13)";
			if ($gender!="" || $gender!=null)
			{
				$sql .= " AND b.patient_gender = '$gender'";
			}
			if (($ageStart!='' && $ageStart!=60) && ($ageEnd=='' || $ageEnd==null))
			{
				$sql .= " AND round((to_days('$eDate')-to_days(b.patient_dob))/365,1) <= $ageStart + .99";
			}
			if (($ageStart!='' && $ageStart==60) && ($ageEnd=='' || $ageEnd==null))
			{
				$sql .= " AND round((to_days('$eDate')-to_days(b.patient_dob))/365,1) >= $ageStart";
			}
			if ($ageEnd!='' || $ageEnd!=null )
			{
				$sql .= " AND round((to_days('$eDate')-to_days(b.patient_dob))/365,1) BETWEEN $ageStart AND $ageEnd + .99";
						  //AND round((to_days('$eDate')-to_days(b.patient_dob))/365,1) <= $ageEnd + .99";
			}
			
			//echo "<br/>".$sql . "<br/> <br/>";
			$result = $this->_sqlConnect($sql);
			return $result['COUNT(a.patient_id)'];
		}
		
		public function _countConsultation($membership='', $sDate='', $eDate='')
		{
			$sql = "SELECT COUNT(a.consult_id) FROM `m_consult` a, `m_consult_notes_dxclass` b 
					JOIN (SELECT * FROM `m_patient_philhealth` 
					GROUP BY philhealth_id HAVING count(philhealth_id)>=1) c 
					ON b.patient_id = c.patient_id 
					WHERE a.consult_id = b.consult_id";
			if (($sDate!='' || $sDate!=null) && ($eDate!='' || $eDate!=null))
			{
				$sql .= " AND date_format(b.diagnosis_date, '%Y-%m-%d') BETWEEN '$sDate' AND '$eDate'";
			}
			if ($membership !='' && $membership == "MEMBER")
			{
				$sql .= " AND member_id IN (1,2,3,5,7,8,9,10,11,12,13)";
			}
			else if ($membership !='' && $membership == "DEPENDENT")
			{
				$sql .= " AND member_id = 4";
			}
			$result = $this->_sqlConnect($sql);
			return $result['COUNT(a.consult_id)'];
		}
		
		public function _countPPServices($service, $membership='', $sDate='', $eDate='')
		{
			/*$sql = "SELECT COUNT(a.consult_id) FROM `m_consult_philhealth_services` a, `m_patient_philhealth` b 
					WHERE a.philhealth_id = b.philhealth_id 
					AND service_id = '$service'";*/
			$sql = "SELECT COUNT(a.consult_id) FROM `m_consult_philhealth_services` a 
					JOIN (SELECT * FROM `m_patient_philhealth` 
					GROUP BY philhealth_id HAVING count(philhealth_id)>=1) b 
					ON a.philhealth_id = b.philhealth_id 
					AND service_id = '$service'";
			
			if (($sDate!='' || $sDate!=null) && ($eDate!='' || $eDate!=null))
			{
				$sql .= " AND date_format(a.service_timestamp, '%Y-%m-%d') BETWEEN '$sDate' AND '$eDate'";
			}
			
			if ($membership !='' && $membership == "MEMBER")
			{
				$sql .= " AND member_id IN (1,2,3,5,7,8,9,10,11,12,13)";
			}
			else if ($membership !='' && $membership == "DEPENDENT")
			{
				$sql .= " AND member_id = 4";
			}
			
			$result = $this->_sqlConnect($sql);
			return $result['COUNT(a.consult_id)'];
		}
		
		public function _countDiagExam($service, $membership='' , $sDate='', $eDate='')
		{
			$sql = "SELECT COUNT(a.consult_id) FROM `m_consult_philhealth_labs` a 
					JOIN (SELECT * FROM `m_patient_philhealth` 
					GROUP BY philhealth_id HAVING count(philhealth_id)>=1) b 
					ON a.philhealth_id = b.philhealth_id 
					AND lab_id = '$service'";
			if (($sDate!='' || $sDate!=null) && ($eDate!='' || $eDate!=null))
			{
				$sql .= " AND date_format(a.lab_timestamp, '%Y-%m-%d') BETWEEN '$sDate' AND '$eDate'";
			}
			if ($membership !='' && $membership == "MEMBER")
			{
				$sql .= " AND member_id IN (1,2,3,5,7,8,9,10,11,12,13)";
			}
			else if ($membership !='' && $membership == "DEPENDENT")
			{
				$sql .= " AND member_id = 4";
			}
			$result = $this->_sqlConnect($sql);
			return $result['COUNT(a.consult_id)'];
		}
		
		public function _countTopMorbidity($sDate='', $eDate='')
		{
			$count = 0;
			/*$sql = "SELECT COUNT(a.class_id), class_name, morbidity FROM `m_consult_notes_dxclass` a, `m_lib_notes_dxclass` b, `m_patient_philhealth` c
					WHERE a.class_id = b.class_id
					AND a.patient_id = c.patient_id
					AND b.morbidity = 'Y'
					GROUP BY class_name
					ORDER BY count(a.class_id) DESC LIMIT 10";*/
			
			/*$sql = "SELECT COUNT(a.class_id), class_name, morbidity FROM `m_consult_notes_dxclass` a, `m_lib_notes_dxclass` b
					WHERE a.class_id = b.class_id
					AND b.morbidity = 'Y'
					GROUP BY class_name
					ORDER BY count(a.class_id) DESC LIMIT 10";*/
			
			$sql = "SELECT COUNT(a.class_id), class_name, morbidity FROM `m_consult_notes_dxclass` a, `m_lib_notes_dxclass` b, `m_consult` c
					WHERE a.class_id = b.class_id 
					AND a.consult_id = c.consult_id
					AND b.morbidity = 'Y' 
					AND date_format(consult_date, '%Y-%m-%d') BETWEEN '$sDate' AND '$eDate'
					GROUP BY icd10
					ORDER BY count(a.class_id) DESC LIMIT 10";
			
			$query = $this->_dbQuery($sql);
							
			while ($result = $this->_dbFetchArr($query))
			{
				$count++;
				echo "<tr>
					<td><span class='width25'>$count.</span><input style='width:350px' type='text' name='mor$count' value='".$result['class_name']."' readonly></td>
					<td class='center'><input style='text-align:center' type='text' size=4 name='morCase$count' value=".$result['COUNT(a.class_id)']." readonly>
				  </tr>";
			}
		}
	}
	
	Class StartToEndDate
	{
		public function fromDateToDate()
		{
			$yearstart=2005;
			$yearend=2035;
			if(!isset($_POST['startMonth']))
			{
				$_POST['startMonth'] = 1;
			}
			if(!isset($_POST['endMonth']))
			{
				$dateMonth = date("m");
				$dMonth = date("m", strtotime("+2 month",$dateMonth));
				$_POST['endMonth'] = $dMonth;
			}
			if(!isset($_POST['year']))
			{
				$_POST['year'] = date("Y");
			}
		
			//Start Month
			echo "<label><span class='padding15'>From </span></label>";
			echo "<select name='startMonth'>";
				for ($x=1; $x<=12; $x++)
				{
					$month = date("F", mktime(0, 0, 0, $x, 1, 0));
					echo "<option value=$x ".($_POST['startMonth']==$x ? 'selected' : '').">$month</option>";
				}
			echo "</select>";
			
			//End Month
			echo "<label><span class='padding15'> To </span></label>";
			echo "<select name='endMonth'>";
				for ($x=1; $x<=12; $x++)
				{
					$month = date("F", mktime(0, 0, 0, $x, 1, 0));
					echo "<option value=$x ".($_POST['endMonth']==$x ? 'selected' : '').">$month</option>";
				}
			echo "</select>";
		
			//selection year.
			echo "<select name='year'>";
				for($y=$yearstart; $y<=$yearend; $y++)
				{
					echo "<option value=$y  ".($_POST['year']== $y ? 'selected' : '').">$y";
				}
			echo "</select>";
		}

		public function _calendar($date)
		{
			$myCalendar = new tc_calendar("date1", true, false);
			$myCalendar->setIcon("calendar/images/iconCalendar.gif");
			//$myCalendar->setDate(date('d'), date('m'), date('Y'));
			if ($date!='0000-00-00')
			{
				$myCalendar->setDate(date('d', strtotime($date))
						, date('m', strtotime($date))
						, date('Y', strtotime($date)));
			}
			$myCalendar->setPath("calendar/");
			$myCalendar->setYearInterval(2005, 2045);
			//$myCalendar->dateAllow('2008-05-13', '2015-03-01');
			$myCalendar->setDateFormat('Y-m-d');
			//$myCalendar->setHeight(350);
			//$myCalendar->autoSubmit(true, "phmembership");
			$myCalendar->setAlignment('left', 'bottom');
			//$myCalendar->setSpecificDate(array("2011-04-01", "2011-04-04", "2011-12-25"), 0, 'year');
			//$myCalendar->setSpecificDate(array("2011-04-10", "2011-04-14"), 0, 'month');
			// $myCalendar->setSpecificDate(array("2011-06-01"), 0, '');
			$myCalendar->writeScript();
		}
	}
	
	$dbase = new sqlStatement();
	$function = new StartToEndDate();
	
	
	
	//function for xml generation
	function _generateXML ($form='', $table='', $arrValue)
	{
		$host = "localhost";
		$user = $_SESSION["dbuser"];
		$pass = $_SESSION["dbpass"];
		$dbname = "philhealth";
		$con = mysqli_connect($host,$user,$pass,$dbname);
		
		$sql = "SELECT * FROM m_lib_philhealth_pcb_indicators WHERE table_source = '$table'";
		$query = mysqli_query($con,$sql);
		
		switch ($form) 
		{
			case 'A2':
				$start = 1;
				$end = 62;
				break;
			
			case 'A4':
				$start = 63;
				$end = 120;
				break;
			
			default:
			break;
		}
		// Set the content type to be XML, so that the browser will   recognise it as XML.
		header( "content-type: application/xml; charset=UTF-8" );
		
		// "Create" the document.
		$xml = new DOMDocument( "1.0", "UTF-8" );
		$xml->preserveWhiteSpace = false;
		$xml->formatOutput = true;
		
		// Create some elements.
		$xml_dataValueSet = $xml->createElement("dataValueSet");
		
		
		// Create Comments
		$CommentTitle = ' Form: PCB Form ' . $form;
		$CommentNodeTitle = $xml->createComment(str_replace('--', '-'.chr(194).chr(173).'-', $CommentTitle));
		
		$arrKey = array();
		for($i=$start; $i<=$end; $i++)
		{
			$arrKey[] = $i;
		}
		
		$arrID = array_combine($arrKey, $arrValue);
		
		while($result = mysqli_fetch_array($query))
		{
			foreach($arrID as $key => $value)
			{
				if($result['indicator_id']==$key)
				{
					$xmlValue = $value;
				}
			}
			$xml_dataValue = $xml->createElement("dataValue");
			
			$CommentDE = " DE: ".$result['Indicator']." (integer)  ";
			$CommentNodeDE = $xml->createComment(str_replace('--', '-'.chr(194).chr(173).'-', $CommentDE));
			$CommentCategory = " Category: (".$result['Category'].")  ";
			$CommentNodeCategory = $xml->createComment(str_replace('--', '-'.chr(194).chr(173).'-', $CommentCategory));
						
			// Set the attributes.
			$xml_dataValueSet->setAttribute("xmlns", "http://dhis2.org/schema/dxf/2.0");
			$xml_dataValueSet->setAttribute("dataSet", $result['dataSet']);
			$xml_dataValueSet->setAttribute("period", "PERIOD");
			$xml_dataValueSet->setAttribute("orgUnit", "ORGUNIT");
			
			$xml_dataValue->setAttribute("dataElement", $result['dataElement']);
			$xml_dataValue->setAttribute("categoryOptionCombo", $result['categoryOptionCombo']);
			$xml_dataValue->setAttribute("value", "$xmlValue");
								
			// Append dataValue and Comments to dataValueSet
			$xml_dataValueSet->appendChild($CommentNodeDE);
			$xml_dataValueSet->appendChild($CommentNodeCategory);
			$xml_dataValueSet->appendChild($xml_dataValue);
		}
		
		$xml->appendChild($CommentNodeTitle);
		$xml->appendChild($xml_dataValueSet);
		
		// Parse the XML.
		print $xml->saveXML();
		$xml->save("../xml/".$form.".xml");
	}
	
?>
