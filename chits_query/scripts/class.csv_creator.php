<?php

class csv_creator{

	function csv_creator(){
		$this->appName="HTML Builder";
      		$this->appVersion="0.13";
      		$this->appAuthor="Alison Perez";
	}

	function test_func(){
		echo 'alison';
	}
 
	function create_csv($report_type, $arr_stats){
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

			$str_csv = $facility_code.','.$reg_code.','.$prov_code.','.$citymun_code.','.$brgy_code.','.$_SESSION["edate2"].','.$str_stat;
			
			$this->create_file($str_csv,$cat_id,$report_type);
			
			//echo $str_csv;

		else:
			echo "<font color='red'>The specified health facility code in the configuration file is invalid.</font>";
		endif;

		
	}

	function get_facility_id(){
		return 'DOH000000000003678';
	}


	function get_regcode(){

	}

	function get_program_name($program_id){
		switch($program_id){
		
			case '4':
				return 'mc';
				break;
			case '8':
				return 'childcare';
				break;
			case '7':
				return 'morbidity';
				break;
			case '9':
				return 'fp';
				break;
			case '15':
				return 'natality';
				break;
			case '12':
				return 'dhc';
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

	function create_file($csvdata,$program_id,$period_type){ 
		$rhu_name = $_SESSION["datanode"]["name"];
		$rhu_name = str_replace(' ','',$rhu_name);

		$program_name = $this->get_program_name($program_id);
		$period = $this->get_period($period_type);

		$csv_dir = '../../site/csv/';


		$csv_file_name = $rhu_name.'_'.$period.'_'.$program_name.'.csv';
		$csv_location = $csv_dir.$csv_file_name;

		$fp = fopen($csv_location,'w+'); //read or write the file, create if it is not existing yet
		fwrite($fp,$csvdata);
		fclose($fp);

		header('Content-type: application/csv');
		header("Content-Disposition: inline; filename=".$csv_location);
		readfile($csv_location);
	}

}
?>