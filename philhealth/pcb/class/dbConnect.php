<?php
	define ("dbHost", "localhost");
	define ("dbUser", "root");
	define ("dbPass", "root");
	define ("dbName", "victoria2");
	
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
		public function _countBloodPressure($bp='',$sDate='',$eDate='')
		{
			$sql = "SELECT COUNT(DISTINCT a.consult_id),
					CASE
						WHEN vitals_systolic >= '160' OR vitals_diastolic >= '100' OR vitals_systolic BETWEEN '140' AND '159' OR vitals_diastolic BETWEEN '90' AND '99' THEN 'Hypertension'
						WHEN vitals_systolic BETWEEN '120' AND '139' OR vitals_diastolic BETWEEN '80' AND '89' OR vitals_systolic < '120' AND vitals_diastolic < '80' THEN 'Normal'
					END AS blood_pressure
					FROM `m_consult_vitals` a, `m_consult_philhealth_services` b
					WHERE a.consult_id = b.consult_id";
			
			if (($sDate!='' || $sDate!=null) && ($eDate!='' || $eDate!=null))
			{
				$sql .= " AND date_format(b.service_timestamp, '%Y-%m-%d') BETWEEN '$sDate' AND '$eDate'";
			}
			
			
			if ($bp!="" || $bp!=null)
			{
				$sql .= " GROUP BY blood_pressure HAVING blood_pressure = '$bp'";
			}
			
			$result = $this->_sqlConnect($sql);
			return $result['COUNT(DISTINCT a.consult_id)'];
		}
		
		public function _countTotalMembers($gender='',$sDate='',$eDate='')
		{
			/*$sql = "SELECT COUNT(a.patient_id) FROM `m_patient_philhealth` a, `m_patient` b 
					WHERE a.patient_id = b.patient_id 
					AND (a.member_id = 1 OR a.member_id = 2 OR a.member_id = 3 OR a.member_id = 5)";*/
			$sql = "SELECT COUNT(a.patient_id) FROM `m_patient_philhealth` a 
					JOIN `m_patient` b ON a.patient_id = b.patient_id 
					JOIN (SELECT * FROM `m_consult` GROUP BY patient_id HAVING count(patient_id)>=1) c 
					ON a.patient_id = c.patient_id 
					WHERE date_format(c.consult_date, '%Y-%m-%d') BETWEEN '$sDate' AND '$eDate' AND a.member_id IN (1,2,3,5)";
			if ($gender!="" || $gender!=null)
			{
				$sql .= " AND b.patient_gender = '$gender'";
			}
			$result = $this->_sqlConnect($sql);
			return $result['COUNT(a.patient_id)'];
		}
		
		public function _countTotalDependents($gender='',$sDate='',$eDate='')
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
			$result = $this->_sqlConnect($sql);
			return $result['COUNT(a.patient_id)'];	
		}
		
		public function _countTotalMemDep($gender='',$sDate='',$eDate='')
		{
			$sql = "SELECT COUNT(a.patient_id) FROM `m_patient_philhealth` a 
					JOIN `m_patient` b ON a.patient_id = b.patient_id 
					JOIN (SELECT * FROM `m_consult` GROUP BY patient_id HAVING count(patient_id)>=1) c 
					ON a.patient_id = c.patient_id 
					WHERE date_format(c.consult_date, '%Y-%m-%d') BETWEEN '$sDate' AND '$eDate' AND a.member_id IN (1,2,3,4,5)";
			if ($gender!="" || $gender!=null)
			{
				$sql .= " AND b.patient_gender = '$gender'";
			}
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
				$sql .= " AND member_id IN (1,2,3,5)";
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
				$sql .= " AND member_id IN (1,2,3,5)";
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
				$sql .= " AND member_id IN (1,2,3,5)";
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
					GROUP BY class_name
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
			echo "<label><span class='padding15'>From</span></label>";
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
	}
	
	$dbase = new sqlStatement();
	$function = new StartToEndDate();
	
?>