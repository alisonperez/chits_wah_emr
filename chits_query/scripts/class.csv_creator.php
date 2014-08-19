<?php

class csv_creator{

	function csv_creator(){
		$this->appName="QB CSV builder";
      		$this->appVersion="0.13";
      		$this->appAuthor="Alison Perez";
	}

	function test_func(){
		echo 'alison';
	}
 
	function create_csv($report_type, $arr_stats, $type){
		/* 	health_facility, region code, prov code, city/mun code, brgy code, date stats
		*/


		$facility_code = $this->get_facility_id();

		$q_psgc_code = mysql_query("SELECT psgc_regcode, psgc_provcode, psgc_citymuncode, psgc_brgycode FROM m_lib_health_facility WHERE doh_class_id='$facility_code'") or die("Cannot query 17: ".mysql_error());

		if(mysql_num_rows($q_psgc_code)!=0): 
			list($reg_code,$prov_code,$citymun_code,$brgy_code) = mysql_fetch_array($q_psgc_code);
			//echo $reg_code.'/'.$prov_code.'/'.$citymun_code.'/'.$brgy_code;

			$q_get_prog = mysql_query("SELECT b.cat_id, b.cat_label,a.report_type FROM question a, ques_cat b WHERE a.cat_id=b.cat_id AND a.ques_id='$report_type'") or die("Cannot query 26: ".mysql_error());
			
			list($cat_id,$cat_label,$report_type) = mysql_fetch_array($q_get_prog);

			$str_stat = $this->get_stats_csv($cat_id,$cat_label,$arr_stats,$report_type);

			if($report_type=='M'): 
				$date_reported = $_SESSION["sdate2"];
			elseif($report_type=='Q'):
				$date_reported = $_SESSION["edate2"];
			else:
				$date_reported = $_SESSION["sdate2"];
			endif;
			
			list($yr,$month,$date) = explode('-',$date_reported);
			$month = sprintf("%02s",$month);
			$reg_code = sprintf("%02s",$reg_code);
			$prov_code = sprintf("%04s",$prov_code);
			$citymun_code = sprintf("%06s",$citymun_code);
			$brgy_code = sprintf("%09s",$brgy_code);
			$final = "'Y'";

			if($type=='csv'):
				$date_reported = $month.'/'.$date.'/'.$yr;
				//$str_csv = $this->enclosed_single_quote($facility_code).','.$this->enclosed_single_quote($reg_code).','.$this->enclosed_single_quote($prov_code).','.$this->enclosed_single_quote($citymun_code).','.$this->enclosed_single_quote($brgy_code).','.$date_reported.','.$str_stat;
				$str_csv = $this->enclosed_single_quote($facility_code).','.$this->enclosed_single_quote($reg_code).','.$this->enclosed_single_quote($prov_code).','.$this->enclosed_single_quote($citymun_code).','.$this->enclosed_single_quote($brgy_code).','.$this->enclosed_single_quote($month).','.$this->enclosed_single_quote($yr).','.$this->enclosed_single_quote_csv($str_stat).','.$final;
				
			elseif($type=='efhsis'):
				$date_reported = $month.'/'.$date.'/'.substr($yr,-2);
				$str_csv = $reg_code.','.$prov_code.','.$citymun_code.','.$brgy_code.','.$date_reported.','.$str_stat;
			else:

			endif;

			if($cat_id!='7'):
				$this->create_file($str_csv,$cat_id,$report_type,$type);
			else:
				foreach($arr_stats as $key=>$value){  
					$str_stat = $this->get_stats_csv($cat_id,$cat_label,$value,$report_type);

					if($type=='csv'):
						//$str_csv = $facility_code.','.$reg_code.','.$prov_code.','.$citymun_code.','.$brgy_code.','.$date_reported.','.$str_stat;

						$str_csv = $this->enclosed_single_quote($facility_code).','.$this->enclosed_single_quote($reg_code).','.$this->enclosed_single_quote($prov_code).','.$this->enclosed_single_quote($citymun_code).','.$this->enclosed_single_quote($brgy_code).','.$this->enclosed_single_quote($month).','.$this->enclosed_single_quote($yr).','.$this->enclosed_single_quote_csv($str_stat).','.$final;

					elseif($type=='efhsis'):
						$str_csv = $reg_code.','.$prov_code.','.$citymun_code.','.$brgy_code.','.$date_reported.','.$str_stat;
					else:
					endif;

					$this->create_file($str_csv,$cat_id,$report_type,$type);
				}
			endif;
			
			//echo $str_csv;

		else:
			echo "<font color='red'>The specified health facility code in the configuration file is invalid.</font>";
		endif;

		
	}

	function get_facility_id(){
		if($_SESSION["new_facility_code"]!=''):
			$doh_fac_code = $_SESSION["new_facility_code"];
			return $doh_fac_code;
		elseif(isset($_SESSION["doh_facility_code"])):
			$doh_fac_code = $_SESSION["doh_facility_code"];
			return $doh_fac_code;
		else:
			return ;
		endif;
	}


	function get_regcode(){

	}

	function get_program_name($program_id){
		switch($program_id){
		
			case '4':
				return 'mc';
				break;
			case '7':
				return 'morbidity';
				break;
			case '8':
				return 'childcare';
				break;
			case '9':
				return 'fp';
				break;
			case '12':
				return 'dhc';
				break;				
			case '15':
				return 'natality';
				break;
			default:
				return '';
				break;

		}

	}

	function get_stats_csv($cat_id,$cat_label,$arr_stats,$report_type){
		$arr_numero = array(); 
		//print_r($arr_stats);
		switch($cat_id){

			case '4': //maternal care data set
				foreach($arr_stats as $key=>$value){ 
					if($report_type=='M'): 
						$value_to_push = $value[1];
					elseif($report_type=='Q'):
						$value_to_push = $value[2];
					else:
					endif;

					array_push($arr_numero,$value_to_push);
				}

				$str_stat = implode(",",$arr_numero);
				
				break;

			case '7': //notifiable diseases data set
				// traverse through the notifiable diseases array but do not include the last 2 values (total m , total f)
				for($i=1;$i<count($arr_stats)-2;$i++){
					array_push($arr_numero,$arr_stats[$i]);
				}
				

				$str_stat = implode(",",$arr_numero);
				break;

			case '8': //child care

				for($i=0;$i<=12;$i++){ //first 12 indicators from BCG to Measles excluding the Hepa w/in 24 hours ($i=8). HepB and Hepa within 24 hrs will be added
					
					if($i!=8):
						foreach($arr_stats[$i] as $key=>$value){ 
							if($i==7):
								$value = $value + $arr_stats[8][$key];
							endif;

							if($report_type=='M'):
								if($key!=0):
									array_push($arr_numero,$value);
								endif;
							elseif($report_type=='Q'):
								if($key==2 || $key==3):
									array_push($arr_numero,$value);
								endif;
							else:
							endif;				
						}					
					endif;
				}

				for($i=23;$i<=28;$i++){ //indicator 23 (FIC) to index 28 (NBS)
					foreach($arr_stats[$i] as $key=>$value){ 
						if($report_type=='M'):
							if($key!=0):
								array_push($arr_numero,$value);
							endif;
						elseif($report_type=='Q'):
							if($key==2 || $key==3):
								array_push($arr_numero,$value);
							endif;
						else:
						endif;				
					}
				}
				

				for($i=65;$i<=67;$i++){ //indicator 65 (infant 6-11 given vit A) to index 67 (infant 60-71 given vit A)
					foreach($arr_stats[$i] as $key=>$value){ 
						if($report_type=='M'):
							if($key!=0):
								array_push($arr_numero,$value);
							endif;
						elseif($report_type=='Q'):
							if($key==2 || $key==3):
								array_push($arr_numero,$value);
							endif;
						else:
						endif;				
					}
				}
				
				for($i=35;$i<=44;$i++){ //indicator 35 (Sick child 6-11 seen) to index 44 (Anemic children 2-59 given iron)
					foreach($arr_stats[$i] as $key=>$value){ 
						if($report_type=='M'):
							if($key!=0):
								array_push($arr_numero,$value);
							endif;
						elseif($report_type=='Q'):
							if($key==2 || $key==3):
								array_push($arr_numero,$value);
							endif;
						else:
						endif;				
					}
				}
				

				for($i=29;$i<=34;$i++){ //indicator 29 (no. diarrea cases seen) to index 34 (pneumonia given treatment)
					foreach($arr_stats[$i] as $key=>$value){ 
						if($report_type=='M'):
							if($key!=0):
								array_push($arr_numero,$value);
							endif;
						elseif($report_type=='Q'):
							if($key==2 || $key==3):
								array_push($arr_numero,$value);
							endif;
						else:
						endif;				
					}
				}

				for($i=16;$i<=18;$i++){ //indicator 16 (penta 1) to index 18 (penta 3)
					foreach($arr_stats[$i] as $key=>$value){ 
						if($report_type=='M'):
							if($key!=0):
								array_push($arr_numero,$value);
							endif;
						elseif($report_type=='Q'):
							if($key==2 || $key==3):
								array_push($arr_numero,$value);
							endif;
						else:
						endif;				
					}
				}

				//push measles again for the indicator MCV1
				array_push($arr_numero,$arr_stats[12][1],$arr_stats[12][2]);

				//push MMR for the indicator MCV2
				array_push($arr_numero,$arr_stats[19][1],$arr_stats[19][2]);


				for($i=13;$i<=15;$i++){ //indicator 13 (rota 1) to index 15 (rota 3)
					foreach($arr_stats[$i] as $key=>$value){ 
						if($report_type=='M'):
							if($key!=0):
								array_push($arr_numero,$value);
							endif;
						elseif($report_type=='Q'):
							if($key==2 || $key==3):
								array_push($arr_numero,$value);
							endif;
						else:
						endif;				
					}
				}

				for($i=20;$i<=22;$i++){ //indicator 20 (pcv 1) to index 22 (pcv 3)
					foreach($arr_stats[$i] as $key=>$value){ 
						if($report_type=='M'):
							if($key!=0):
								array_push($arr_numero,$value);
							endif;
						elseif($report_type=='Q'):
							if($key==2 || $key==3):
								array_push($arr_numero,$value);
							endif;
						else:
						endif;				
					}
				}

				for($i=45;$i<=60;$i++){ //indicator 45 (Total livebirths) to index 46 (child 12-59 given deworming tablet) excluding $i=47 (NBS Screening done)

					if($i!=47):	//excluding $i = 47
						foreach($arr_stats[$i] as $key=>$value){ 
							if($report_type=='M'):
								if($key!=0):
									array_push($arr_numero,$value);
								endif;
							elseif($report_type=='Q'):
								if($key==2 || $key==3):
									array_push($arr_numero,$value);
								endif;
							else:
							endif;				
						}
					endif;
				}
				
				//push MMR for the indicator infants 2-6 with LBW given Iron
				array_push($arr_numero,$arr_stats[42][1],$arr_stats[42][2]);

				for($i=61;$i<=64;$i++){ //indicator 61 (anemic children 6-11 seen) to index 64 (anemic children 12-59 months old received full dose of Iron)
					foreach($arr_stats[$i] as $key=>$value){ 
						if($report_type=='M'):
							if($key!=0):
								array_push($arr_numero,$value);
							endif;
						elseif($report_type=='Q'):
							if($key==2 || $key==3):
								array_push($arr_numero,$value);
							endif;
						else:
						endif;				
					}
				}
				
				$str_stat = implode(",",$arr_numero);
				break;

			case '9':  //fp data sets
				for($i=1;$i<7;$i++){ //first traversal would be the user type (prev cu, na, other acceptors, drop outs, present cu)
					for($j=0;$j<count($arr_stats);$j++){  //second traversal will on the 11 methods
						foreach($arr_stats[$j] as $key=>$value){
							if($key==$i):
								array_push($arr_numero,$value);
							endif;
						}
					}
				}
				
				$str_stat = implode(",",$arr_numero); 
				break; 
			default:
				echo "<font color='red'>No CSV file output yet for this program. Press BACK button from the browser to continue.</font>";
				break;

		}
		return $str_stat;
	}

	function get_period($period_type){
		$year = $_SESSION["year"];
		$qtr = $_SESSION["quarter"];
		$month = $_SESSION["smonth"];

		switch($period_type){
			case 'M':
				return $month.'-'.$year;
				break;

			case 'Q':
				return $qtr.'Q-'.$year;
				break;

			case 'A': 
				return $year;
				break;	
	
			default:

			break;

		}
	}

	function create_file($csvdata,$program_id,$period_type,$type){ 
		if($_SESSION["new_facility_code"]!=''):
			$q_facility_name = mysql_query("SELECT facility_name FROM m_lib_health_facility WHERE doh_class_id='$_SESSION[new_facility_code]'") or die("Cannot query 272: ".mysql_error());
			list($rhu_name) = mysql_fetch_array($q_facility_name);
			$rhu_name = str_replace(' ','',$rhu_name);			
		else:
			$rhu_name = $_SESSION["datanode"]["name"];
			$rhu_name = str_replace(' ','',$rhu_name);
		endif;


		$program_name = $this->get_program_name($program_id);
		$period = $this->get_period($period_type);


		$csv_dir = '../../site/csv/';

		if($type=='csv'):
			$csv_file_name = $rhu_name.'_'.$period.'_'.$program_name.'_'.'csv'.'.csv';
		elseif($type=='efhsis'):
			$csv_file_name = $rhu_name.'_'.$period.'_'.$program_name.'_'.'efhsis'.'.csv';
		else:

		endif;

		$csv_location = $csv_dir.$csv_file_name;


		$fp = fopen($csv_location,'w+'); //read or write the file, create if it is not existing yet
		fwrite($fp,$csvdata.PHP_EOL);
		fclose($fp);

		header('Content-type: application/csv');
		header("Content-Disposition: inline; filename=".basename($csv_location));
		readfile($csv_location);
	}

	function enclosed_single_quote($term){
		$term = "'".$term."'";
		return $term;
	}

	function enclosed_single_quote_csv($csv_term){
		$arr_csv = explode(',',$csv_term);
		for($i=0;$i<count($arr_csv);$i++){
			$arr_csv[$i] = "'".$arr_csv[$i]."'";
		}
		$str_term = implode(',',$arr_csv);
		return $str_term;
	}

}
?>