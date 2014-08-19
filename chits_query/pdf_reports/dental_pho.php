<?php
// Consolidated Oral Health Status and Services Report <perez.alison@gmail.com>, September 17, 2012

session_start();

ob_start();

require('./fpdf/fpdf.php');
require('../layout/class.html_builder.php');
require('../scripts/class.csv_creator.php');

$db_conn = mysql_connect("localhost","$_SESSION[dbuser]","$_SESSION[dbpass]");
mysql_select_db($_SESSION[dbname]);

$html_tab = new html_builder();
$csv_creator = new csv_creator();

class PDF extends FPDF
{
	var $widths;
	var $aligns;
	var $page;	

function SetWidths($w)
{
    //Set the array of column widths
    $this->widths=$w;
}

function SetAligns($a)
{
    //Set the array of column alignments
    $this->aligns=$a;
}

function Row($data)
{
    //Calculate the height of the row
    $nb=0;
    for($i=0;$i<count($data);$i++)
        $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
    $h=5*$nb;
    //Issue a page break first if needed
    $this->CheckPageBreak($h);
    //Draw the cells of the row
    for($i=0;$i<count($data);$i++)
    {
        $w=$this->widths[$i];
        $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'C'; //sets the alignment of text inside the cell
        //Save the current position
        $x=$this->GetX();
        $y=$this->GetY();
        //Draw the border
        $this->Rect($x,$y,$w,$h);
        //Print the text
        $this->MultiCell($w,5,$data[$i],0,$a);
        //Put the position to the right of the cell
        $this->SetXY($x+$w,$y);
    }
    //Go to the next line
    $this->Ln($h);
}


function CheckPageBreak($h)
{
    //If the height h would cause an overflow, add a new page immediately
    if($this->GetY()+$h>$this->PageBreakTrigger)
        $this->AddPage($this->CurOrientation);
}


function NbLines($w,$txt)
{
    //Computes the number of lines a MultiCell of width w will take
    $cw=&$this->CurrentFont['cw'];
    if($w==0)
        $w=$this->w-$this->rMargin-$this->x;
    $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
    $s=str_replace("\r",'',$txt);
    $nb=strlen($s);
    if($nb>0 and $s[$nb-1]=="\n")
        $nb--;
    $sep=-1;
    $i=0;
    $j=0;
    $l=0;
    $nl=1;
    while($i<$nb)
    {
        $c=$s[$i];
        if($c=="\n")
        {
            $i++;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
            continue;
        }
        if($c==' ')
            $sep=$i;
        $l+=$cw[$c];
        if($l>$wmax)
        {
            if($sep==-1)
            {
                if($i==$j)
                    $i++;
            }
            else
                $i=$sep+1;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
        }
        else
            $i++;
    }
    return $nl;
}


function Header()
{
	$m1 = explode('/',$_SESSION[sdate_orig]);
	$m2 = explode('/',$_SESSION[edate_orig]);
	$date_label = ($m1[0]==$m2[0])?$_SESSION[months][$m1[0]].' '.$m1[2]:$_SESSION[months][$m1[0]].' to '.$_SESSION[months][$m2[0]].' '.$m1[2];
	$municipality_label = $_SESSION[datanode][name];

	$this->SetFont('Arial','B',12);

	$this->Cell(0,5,'Consolidated Oral Health Status and Service Report ( '.$date_label.' )'.' - '.$municipality_label,0,1,'C');
	
	if(in_array('all',$_SESSION[brgy])):
		$brgy_label = '(All Barangays)';
	else:
		$brgy_label = '(';
		for($i=0;$i<count($_SESSION[brgy]);$i++){
			$brgy = $_SESSION[brgy];
			$q_brgy = mysql_query("SELECT barangay_name FROM m_lib_barangay WHERE barangay_id='$brgy'") or die("Cannot query: 139");

			list($brgyname) = mysql_fetch_array($q_brgy);

			if($i!=(count($_SESSION[brgy])-1)):
				$brgy_label.= $brgyname.', ';
			else:
				$brgy_label.= $brgyname.')';
			endif;
			
		}
	endif;

	$this->SetFont('Arial','',8);
	$this->Cell(0,5,$brgy_label,0,1,'C');
	$_SESSION["w"] = $w = array(40,24,24,24,24,24,24,24,24,24,24,24,24); //340
	
	$_SESSION["header"] = $header = array('','1 Years Old','2 Years Old','3 Years Old','4 Years Old','5 Years Old','Total for Under 6 Children','Adult (10-24)','Older Persons (60+)','Pregnant Women','Other Groups (6-9) and other adults','Total All Ages','Grand Total');

	$_SESSION["w2"] = $w2 = array(40,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,24,12,12,12,12,24); //340
	$_SESSION["subheader"] = $subheader = array('','M','F','M','F','M','F','M','F','M','F','M','F','M','F','M','F','','M','F','M','F','');
	
	
	$this->Ln();
	$this->SetWidths($w);
	$this->Row($header);
	$this->SetWidths($w2);
	$this->Row($subheader);
}

function show_dental_summary(){
	//print_r($_SESSION);
	$arr_csv = array();
	$arr_consolidate = array();
	$w2 = array(40,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,24,12,12,12,12,24); //340

	//$criteria = array('No. of Persons Attended','No. of Persons Examined',array("Oral Health Status"=>array('Total No. with Dental Caries','Total No. with Gingivitis / Perio Disease','Total No. with Oral Debris','Total No. with Calculus','Total No. with Dento-facial Anomalies (Cleft lip/palate, malocclusion, etc)')),array("Total df"=>array('Total decayed (d)','Total filled (f)')),array("Total DMF"=>array('Total Decayed (D)','Total Missing (M)','Total Filled (F)')),array("Services Rendered"=>array('No. given OP/Scaling','No. Given Permanent Fillings','No. Given Temporary Fillings','No. Given Extraction','No. Given Gum Treatment','No. Given Sealant','No. Completed Flouride Therapy','No. Given Post Operative Treatment','No. of Patient with Oral Abscess Drained','No. Given Other Services','No. Referred','No. Given Counselling/Education on Tobacco,OH,Diet and etc','No. Under Six Children Completed Toothbrushing Drill')),'No. of Orally Fit Children (OFC)');

	$criteria = array('No. of Persons Attended','No. of Persons Examined','Total No. with Dental Caries','Total No. with Gingivitis / Perio Disease','Total No. with Oral Debris','Total No. with Calculus','Total No. with Dento-facial Anomalies (Cleft lip/palate, malocclusion, etc)','Total df','Total decayed (d)','Total filled (f)','Total DMF','Total Decayed (D)','Total Missing (M)','Total Filled (F)','No. given OP/Scaling','No. Given Permanent Fillings','No. Given Temporary Fillings','No. Given Extraction','No. Given Gum Treatment','No. Given Sealant','No. Completed Flouride Therapy','No. Given Post Operative Treatment','No. of Patient with Oral Abscess Drained','No. Given Other Services','No. Referred','No. Given Counselling/Education on Tobacco,OH,Diet and etc','No. Under Six Children Completed Toothbrushing Drill','No. of Orally Fit Children (OFC)');
    	
	$q_brgy = mysql_query("SELECT barangay_name from m_lib_barangay LIMIT 1") or die("Cannot query: 202");
	list($csv_brgy) = mysql_fetch_array($q_brgy);

	array_push($arr_csv,strtoupper($csv_brgy),$_SESSION["edate_orig"]);

	for($i=0;$i<count($criteria);$i++){

		$arr_count = $this->compute_indicator(($i+1));
               	$this->SetWidths($w2);
               	$this->Row(array($criteria[$i],'0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0'));
		
	}

	//return $arr_csv;
	return $arr_consolidate;
 }

function compute_indicator($crit,$sub_crit){
	$month_stat = array(1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0);
	list($syr,$smonth,$sdate) = explode('-',$_SESSION[sdate2]);
	list($eyr,$emonth,$edate) = explode('-',$_SESSION[edate2]);
	$brgy_array = $this->get_brgy_array();
	$brgy_array = implode(',',$brgy_array);
	$sdate = $syr.'/'.$smonth.'/'.$sdate;
	$edate = $eyr.'/'.$emonth.'/'.$edate;

	
	//$criteria = array('No. of Persons Attended','No. of Persons Examined','Total No. with Dental Caries','Total No. with Gingivitis / Perio Disease','Total No. with Oral Debris','Total No. with Calculus','Total No. with Dento-facial Anomalies (Cleft lip/palate, malocclusion, etc)','Total df','Total decayed (d)','Total filled (f)','Total DMF','Total Decayed (D)','Total Missing (M)','Total Filled (F)','No. given OP/Scaling','No. Given Permanent Fillings','No. Given Temporary Fillings','No. Given Extraction','No. Given Gum Treatment','No. Given Sealant','No. Completed Flouride Therapy','No. Given Post Operative Treatment','No. of Patient with Oral Abscess Drained','No. Given Other Services','No. Referred','No. Given Counselling/Education on Tobacco,OH,Diet and etc','No. Under Six Children Completed Toothbrushing Drill','No. of Orally Fit Children (OFC)');
    	
	switch($crit){

		case '1': //no. of persons attended
			$q_dental = mysql_query("SELECT DISTINCT patient_id, date_of_oral FROM m_dental_patient_ohc WHERE date_of_oral BETWEEN '$sdate' AND '$edate'") or die("Cannot query 214: ".mysql_error());
			break;

		case '2': //no. of persons examined
			$q_dental = mysql_query("SELECT DISTINCT patient_id, date_of_oral FROM m_dental_patient_ohc WHERE date_of_oral BETWEEN '$sdate' AND '$edate'") or die("Cannot query 214: ".mysql_error());
			break;

		case '3': //total no. with dental caries
			$q_dental = mysql_query("SELECT DISTINCT consult_id, date_of_oral FROM m_dental_patient_ohc_table_a WHERE date_of_oral BETWEEN '$sdate' AND '$edate' AND dental_caries='YES'") or die("Cannot query 221: ".mysql_error());
			break;

		case '4': //total no. with gingivitis/ perio disease
			$q_dental = mysql_query("SELECT DISTINCT consult_id, date_of_oral FROM m_dental_patient_ohc_table_a WHERE date_of_oral BETWEEN '$sdate' AND '$edate' AND gingivitis_periodontal_disease='YES'") or die("Cannot query 225: ".mysql_error());
			break;
		case '5': //oral debris
			$q_dental = mysql_query("SELECT DISTINCT consult_id, date_of_oral FROM m_dental_patient_ohc_table_a WHERE date_of_oral BETWEEN '$sdate' AND '$edate' AND debris='YES'") or die("Cannot query 225: ".mysql_error());
			break;

		case '6': //calculus
			$q_dental = mysql_query("SELECT DISTINCT consult_id, date_of_oral FROM m_dental_patient_ohc_table_a WHERE date_of_oral BETWEEN '$sdate' AND '$edate' AND calculus='YES'") or die("Cannot query 225: ".mysql_error());
			break;
		case '7': //dento-facial anomaly
			$q_dental = mysql_query("SELECT DISTINCT consult_id, date_of_oral FROM m_dental_patient_ohc_table_a WHERE date_of_oral BETWEEN '$sdate' AND '$edate' AND dento_facial_anomaly='YES'") or die("Cannot query 225: ".mysql_error());
			break;
		case '8': //Total df 
			$q_dental = mysql_query("SELECT DISTINCT consult_id, date_of_oral FROM m_dental_patient_ohc WHERE tooth_condition IN ('d','f') AND date_of_oral BETWEEN '$sdate' AND '$edate'") or die("Cannot query 241: ".mysql_error());
			break;
		case '9': //Total d
			$q_dental = mysql_query("SELECT DISTINCT consult_id, date_of_oral FROM m_dental_patient_ohc WHERE tooth_condition = 'd' AND ((tooth_number BETWEEN 50 AND 66) OR (tooth_number BETWEEN 70 AND 86)) AND date_of_oral BETWEEN '$sdate' AND '$edate'") or die("Cannot query 242: ".mysql_error());
			break;
		case '10': //Total f
			$q_dental = mysql_query("SELECT DISTINCT consult_id, date_of_oral FROM m_dental_patient_ohc WHERE tooth_condition = 'd' AND ((tooth_number BETWEEN 50 AND 66) OR (tooth_number BETWEEN 70 AND 86)) AND date_of_oral BETWEEN '$sdate' AND '$edate'") or die("Cannot query 245: ".mysql_error());
			break;
		case '11': //Total DMF
			$q_dental = mysql_query("SELECT DISTINCT consult_id, date_of_oral FROM m_dental_patient_ohc WHERE tooth_condition IN ('D','M','F') AND date_of_oral BETWEEN '$sdate' AND '$edate'") or die("Cannot query 248: ".mysql_error());
			break;
		case '12': //Total D
			$q_dental = mysql_query("SELECT DISTINCT consult_id, date_of_oral FROM m_dental_patient_ohc WHERE tooth_condition = 'D' AND ((tooth_number BETWEEN 10 AND 29) OR (tooth_number BETWEEN 30 AND 49)) AND date_of_oral BETWEEN '$sdate' AND '$edate'") or die("Cannot query 251: ".mysql_error());
			break;
		case '13': //TOtal M
			$q_dental = mysql_query("SELECT DISTINCT consult_id, date_of_oral FROM m_dental_patient_ohc WHERE tooth_condition = 'M' AND ((tooth_number BETWEEN 10 AND 29) OR (tooth_number BETWEEN 30 AND 49)) AND date_of_oral BETWEEN '$sdate' AND '$edate'") or die("Cannot query 254: ".mysql_error());
			break;
		case '14': //Total F
			$q_dental = mysql_query("SELECT DISTINCT consult_id, date_of_oral FROM m_dental_patient_ohc WHERE tooth_condition = 'F' AND ((tooth_number BETWEEN 10 AND 29) OR (tooth_number BETWEEN 30 AND 49)) AND date_of_oral BETWEEN '$sdate' AND '$edate'") or die("Cannot query 257: ".mysql_error());
			break;
		case '15': //OP/Scaling
			$q_dental = mysql_query("SELECT DISTINCT consult_id, date_of_service FROM m_dental_services WHERE service_provided='OP' AND date_of_service BETWEEN '$sdate' AND '$edate'") or die("Cannot query 260: ".mysql_error());
			break;
		case '16': //Permanent Filling
			$q_dental = mysql_query("SELECT DISTINCT consult_id, date_of_service FROM m_dental_services WHERE service_provided='PF' AND date_of_service BETWEEN '$sdate' AND '$edate'") or die("Cannot query 263: ".mysql_error());
			break;
		case '17': //Temporary Filling
			$q_dental = mysql_query("SELECT DISTINCT consult_id, date_of_service FROM m_dental_services WHERE service_provided='TF' AND date_of_service BETWEEN '$sdate' AND '$edate'") or die("Cannot query 266: ".mysql_error());
			break;
		case '18': //Extraction
			$q_dental = mysql_query("SELECT DISTINCT consult_id, date_of_service FROM m_dental_services WHERE service_provided='X' AND date_of_service BETWEEN '$sdate' AND '$edate'") or die("Cannot query 269: ".mysql_error());
			break;
		case '19': //Gum Treatment
			$q_dental = mysql_query("SELECT DISTINCT consult_id, date_of_service FROM m_dental_other_services WHERE gum_treatment='YES' AND date_of_service BETWEEN '$sdate' AND '$edate'") or die("Cannot query 272: ".mysql_error());
			break;
		case '20': //Sealant 
			$q_dental = mysql_query("SELECT DISTINCT consult_id, date_of_service FROM m_dental_services WHERE service_provided='S' AND date_of_service BETWEEN '$sdate' AND '$edate'") or die("Cannot query 275: ".mysql_error());
			break;
		case '21': //Flouride therapy
			$q_dental = mysql_query("SELECT DISTINCT consult_id, date_of_service FROM m_dental_services WHERE service_provided='FL' AND date_of_service BETWEEN '$sdate' AND '$edate'") or die("Cannot query 278: ".mysql_error());
			break;
		case '22': //Postoperative Treatment
			
			break;
		case '23': //Oral Abcess Drained
			$q_dental = mysql_query("SELECT DISTINCT consult_id, date_of_service FROM m_dental_other_services WHERE out_drainage_of_localized_oral_abscess='YES' AND date_of_service BETWEEN '$sdate' AND '$edate'") or die("Cannot query 284: ".mysql_error());
			break;
		case '24': //Other Services
			
			break;
		case '25': //Referred
			$q_dental = mysql_query("SELECT DISTINCT consult_id, date_of_service FROM m_dental_other_services WHERE out_referral_of_complicates_cases='YES' AND date_of_service BETWEEN '$sdate' AND '$edate'") or die("Cannot query 290: ".mysql_error());
			break;
		case '26': //counselling
			$q_dental = mysql_query("SELECT DISTINCT consult_id, date_of_service FROM m_dental_other_services WHERE education_and_counselling='YES' AND date_of_service BETWEEN '$sdate' AND '$edate'") or die("Cannot query 293: ".mysql_error());
			break;
		case '27': //Under 6YO toothbrushing drill
			
			break;
		case '28': //orally fit children
			
			break;
		default:

			break;

	}

	while(list($pxid,$consult_date)=mysql_fetch_array($q_dental)){
		if($crit=='1' || $crit=='2'): //this is already patient_id
			//$get_age_gender = mysql_query("SELECT round(((to_days('$consult_date')-to_days(patient_dob))/365)*12,0), patient_gender, patient_dob FROM m_patient WHERE patient_id='$pxid'") or die("Cannot query 309: ".mysql_error());

			//list($age_in_months,$px_gender,$patient_dob) = mysql_fetch_array($get_age_gender);

			//echo $crit.'/'.$pxid.'/'.$consult_date.'/'.$age_in_months.'/'.$px_gender.'/'.$patient_dob."<br>";
			//get the gender and age at the point of consult
		else:  //this is the consult_id
			//echo $crit.'/'.$pxid.'/'.$consult_id."<br>";
		endif;
	}
	
	return $arr_ncd;
}

function get_brgy_array(){
	$mga_brgy = array();
	if(in_array('all',$_SESSION[brgy])):
		$q_brgy = mysql_query("SELECT barangay_id FROM m_lib_barangay ORDER by barangay_id ASC") or die("Cannot query: 448");
		while(list($b_id)=mysql_fetch_array($q_brgy)){
			array_push($mga_brgy,$b_id);
		}
		return $mga_brgy;

	else:
		return $_SESSION[brgy];
	endif;	
}

function get_max_month($date){
	list($taon,$buwan,$araw) = explode('-',$date);
	$max_date = date("n",mktime(0,0,0,$buwan,$araw,$taon)); //get the unix timestamp then return month without trailing 0

	return $max_date;
}

function get_brgy_pop(){
	list($taon,$buwan,$araw) = explode('-',$_SESSION[edate2]);
	if(in_array('all',$_SESSION[brgy])):
		$q_brgy_pop = mysql_query("SELECT SUM(population) FROM m_lib_population WHERE population_year='$taon'") or die("Cannot query: 286");
	else:
		$str = implode(',',$_SESSION[brgy]);
		$q_brgy_pop = mysql_query("SELECT SUM(population) FROM m_lib_population WHERE population_year='$taon' AND barangay_id='$_SESSION[brgy]'") or die("Cannot query: 372".mysql_error());
	
	endif;	

	if(mysql_num_rows($q_brgy_pop)!=0):
		list($populasyon) = mysql_fetch_array($q_brgy_pop);
	endif;		
	
	return $populasyon;
}

function get_target($criteria){
	if($criteria>=1 && $criteria<=5):
		$perc = '.035';
	else:
		$perc = '.03';
	endif;
	return $perc;
}

function get_quarterly_total($r_month,$target){
	$q_total = array();
	$counter = 0;
	
	for($i=1;$i<=4;$i++){
		//$sum = $r_month[$i+$counter] + $r_month[$i+$counter+1] + $r_month[$i+$counter+2];
		
		$q_total[$i] = $r_month[$i+$counter] + $r_month[$i+$counter+1] + $r_month[$i+$counter+2];
		
		//$q_total[$i] = round(($sum/$target),3)*100;
		$counter+=2;
	}
	return $q_total;
}


function get_brgy(){
    $arr_brgy = array();
    $str_brgy = '';    

    if(in_array('all',$_SESSION[brgy])):
        /*$q_brgy = mysql_query("SELECT barangay_name FROM m_lib_barangay ORDER by barangay_id ASC") or die("Cannot query 252". mysql_error());        
        while(list($brgy_name) = mysql_fetch_array($q_brgy)){            
            array_push($arr_brgy,$brgy_id);
        }*/
        $str_brgy = 'All Barangay';
    else:
        $arr_brgy = $_SESSION[brgy];
		
	for($x=0;$x<count($arr_brgy);$x++){
	
        $q_brgy = mysql_query("SELECT barangay_name FROM m_lib_barangay WHERE barangay_id = '$arr_brgy[$x]' ORDER by barangay_id ASC") or die("Cannot query 252". mysql_error());

	while(list($brgy) = mysql_fetch_array($q_brgy)){
		$str_brgy = $str_brgy.'  '.$brgy;
	}

	}
    endif;

    return $str_brgy;
}


function Footer(){
    $this->SetY(-15);
    //Arial italic 8
    $this->SetFont('Arial','I',8);
    //Page number
    $this->Cell(0,10,$this->PageNo().'/{nb}',0,0,'C');
}


}


$pdf = new PDF('L','mm','Legal');
$pdf->AliasNbPages();
$pdf->SetFont('Arial','',10);
$pdf->AddPage();


$_SESSION["arr_px_labels"] = array('dental'=>array());
$dental_content = $pdf->show_dental_summary();

if($_GET["type"]=='html'): 
	$html_tab->create_table($_SESSION["w"],$_SESSION["header"],$dental_content);
elseif($_GET["type"]=='csv'):
	$csv_creator->create_csv($_SESSION["ques"],$dental_content,'csv');
elseif($_GET["type"]=='efhsis'):
	$csv_creator->create_csv($_SESSION["ques"],$dental_content,'efhsis');
else:
	echo 'Consolidated Dental Report is still under construction';
	//$pdf->Output();
endif;

?>