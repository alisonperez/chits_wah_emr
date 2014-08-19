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

				for($i=0;$i<18;$i++){ //first 18 indicators from BCG to Infant referred for NBS
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

				for($i=0;$i<6;$i++){ //provide allowance for the six INF_VITA indicators. place the value of 0 until October
					array_push($arr_numero,0);
				}

				for($i=24;$i<34;$i++){ //count for for sick child, sick child with Vit A, infant with LBW
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


				for($i=18;$i<24;$i++){ //count for for sick child, sick child with Vit A, infant with LBW
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
				for($i=1;$i<6;$i++){ //first traversal would be the user type (prev cu, na, other acceptors, drop outs, present cu)
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