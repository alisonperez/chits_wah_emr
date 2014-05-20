<?php
//Alison Perez <perez.alison@gmail.com>, natality (livebirth) report , January - Feb 1 2012
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
        $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L'; //sets the alignment of text inside the cell
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
	
	$date_label = $_SESSION[months][$m1[0]].' '.$m1[1].' '.$m1[2]. ' to '.$_SESSION[months][$m2[0]].' '.$m2[1].' '.$m2[2];
	
	$municipality_label = $_SESSION[datanode][name];

	$this->SetFont('Arial','B',15);

        if($_SESSION[ques]>=120 && $_SESSION[ques]<=123): //natality livebirth questions
		$this->Cell(0,5,'NATALITY - LIVEBIRTHS ('.$date_label.')'.' - '.$municipality_label,0,1,'C');
	elseif($_SESSION[ques]>=124 && $_SESSION[ques]<=127): //natality deliveries questions
		$this->Cell(0,5,'NATALITY - DELIVERIES ('.$date_label.')'.' - '.$municipality_label,0,1,'C');
	elseif($_SESSION[ques]==128):	//summary table for natality
		$this->Cell(0,5,'NATALITY SUMMARY TABLE ('.$date_label.')'.' - '.$municipality_label,0,1,'C');
	else:

	endif;
	
	if(in_array('all',$_SESSION[brgy])):
		$brgy_label = '(All Barangays)';
	else:
		$brgy_label = '(';
		for($i=0;$i<count($_SESSION[brgy]);$i++){
			$brgy = $_SESSION[brgy][$i];
			$q_brgy = mysql_query("SELECT barangay_name FROM m_lib_barangay WHERE barangay_id='$brgy'") or die("Cannot query: 139");

			list($brgyname) = mysql_fetch_array($q_brgy);

			if($i!=(count($_SESSION[brgy])-1)):
				$brgy_label.= $brgyname.', ';
			else:
				$brgy_label.= $brgyname.')';
			endif;
			
		}
	endif;

	$this->SetFont('Arial','',13);
	$this->Cell(0,5,$brgy_label,0,1,'C');
	

	if($_SESSION[ques]>=120 && $_SESSION[ques]<=123): //natality livebirth questions
		$_SESSION["w2"] = $w2 = array(90,25,25,25,25,60,60); //340	
		$_SESSION["header2"] = $header2 = array(' ','Male','Female','Total','%','Interpretation','Recommendation / Actions Taken');
		$this->SetWidths($w2);
		$this->Row($header2);
	elseif($_SESSION[ques]>=123 && $_SESSION[ques]<=127):
		$_SESSION["w"] = $w = array(90,75,25,60,60); //340
		$_SESSION["header"] = $header = array('INDICATORS','Number','%','Interpretation','Recommendation / Actions Taken');
		$this->Ln();
		$this->SetWidths($w);
		$this->Row($header);
	elseif($_SESSION[ques]==128):
		$_SESSION["w"] = $w =		array(66,16,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,17); //340
		$_SESSION["header"] = $header = array('INDICATORS','Target','JAN','FEB','MAR','1st Q','APR','MAY','JUNE','2nd Q','JULY','AUG','SEPT','3rd Q','OCT','NOV','DEC','4th Q','TOTAL');	
		
		$this->SetWidths($w);
		$this->Row($header);
	else: 

	endif;

	
}


function show_natality(){
	$arr_csv = array();
	$arr_consolidate = array();
	
	//$criteria = array('Livebirths (LB)','LB w/ weights 2500 grams & greater','LB w/ weights less than 2500 grams','LB - Not known weight','LB delivered by doctors','LB delivered by nurses','LB delivered by midwives','LB delivered by hilot / TBA','LB delivered by others','Deliveries','Normal Pregnancy','Risk Pregnancy','Unknown Pregnancy','Normal Deliveries','Normal Deliveries at Home','Normal Deliveries at Hospital','Normal Deliveries - Other Place','Other Types of Deliveries','Other Type of Deliveries at Home','Other Type of Deliveries at Hospital','Other Type of Deliveries - Other Places');

	$criteria = array('Livebirths (LB)','LB w/ weights 2500 grams & greater','LB w/ weights less than 2500 grams','LB - Not known weight','LB delivered by doctors','LB delivered by nurses','LB delivered by midwives','LB delivered by hilot/TBA','LB delivered by others','LB delivered by unknown','Total number of Deliveries by Place','Number of deliveries at Health Facilities','Number of deliveries at RHU','Number of deliveries at Hospitals','Number of deliveries at BHS','Number of deliveries at Lying-in','Number of Non-institutional Deliveries (NID)','Number of deliveries at Home','Number of deliveries Others','Total number of deliveries by Type','Number of Normal Deliveries (NSD)','Number of Operative Deliveries','Total Number of Pregnancies','Livebirths','Fetal Death','Abortion','Total Number of Pregnancies','Livebirths','Fetal Death','Abortion','Normal Deliveries','Normal Deliveries at Home','Normal Deliveries at Health Facility','Normal Deliveries - Other Places','Operative Deliveries','Operative Deliveries at Health Facility','Operative Deliveries at Other Places','Number of Pregnancies','Livebirths','Fetal Death','Abortion','Number of Deliveries','Number of Normal Deliveries (NSD)','Number of Operative Deliveries','LB w/ weights 2500 grams & greater','LB w/ weights less than 2500 grams','LB - Not known weight','LB delivered by doctors','LB delivered by nurses','LB delivered by midwives','LB delivered by hilot/TBA','LB delivered by others');

	if($_SESSION[ques]>=120 && $_SESSION[ques]<=123): //natality livebirth questions
		/*$start = 26;
		$end = 37;*/
		$start = 37;
		$end = count($criteria);
	elseif($_SESSION[ques]>=124 && $_SESSION[ques]<=127): //natality deliveries questions
		/*$start = 37;
		$end = count($criteria);*/
		$start = 26;
		$end = 37;
	elseif($_SESSION[ques]==128):
		$start = 0;
		$end = 26;
	else:

	endif;

	$brgy_pop = $this->get_brgy_pop();
	
	for($i=$start;$i<$end;$i++){ 
		//$arr_natality_stat = $this->compute_indicator($i+1); //compute_indicator will return an array with three elements (m,f, total)
		
		$arr_natality_stat = $this->compute_indicator($i); 

		$perc = $this->get_target_percentage($i,$arr_natality_stat["total"]);		
		

		if($_SESSION[ques]>=120 && $_SESSION[ques]<=123): //natality livebirth questions
			$w = array(90,25,25,25,25,60,60); 
			
			array_push($arr_consolidate,array($criteria[$i],$arr_natality_stat["M"],$arr_natality_stat["F"],$arr_natality_stat["total"],$perc,'',''));
			
			$this->Row(array($criteria[$i],$arr_natality_stat["M"],$arr_natality_stat["F"],$arr_natality_stat["total"],$perc,'',''));
			$this->Row($arr_consolidate);
		elseif($_SESSION[ques]>=124 && $_SESSION[ques]<=127):
			$w = array(90,75,25,60,60); 
			array_push($arr_consolidate,array($criteria[$i],$arr_natality_stat[0],$perc,'',''));
			
			$this->Row(array($criteria[$i],$arr_natality_stat[0],$perc,'',''));
		elseif($_SESSION[ques]==128):
			$w =		array(66,16,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,17); //340


			array_push($arr_consolidate,array($criteria[$i],$arr_natality_stat[0],$perc,'',''));
			$this->Row(array($criteria[$i],$arr_natality_stat[0],$perc,'',''));

		else:

		endif;
	}

	return $arr_consolidate;
}



function compute_indicator($crit){

	list($syr,$smonth,$sdate) = explode('-',$_SESSION[sdate2]);
	list($eyr,$emonth,$edate) = explode('-',$_SESSION[edate2]);
	$brgy_array = $this->get_brgy_array();
	$brgy_array = implode(',',$brgy_array);

	$month_stat = array();

    for($i=1;$i<13;$i++){
        $month_stat[$i] = 0;
    }

	switch($crit){

		//case 1:	//total number of live births
		case (in_array($crit,array(0,23,27,38))):	//total number of live births, outcome is normal & operative

			$arr_natality = array("M"=>0,"F"=>0,"total"=>0);

			if(in_array('all',$_SESSION[brgy])):
				$q_natality = mysql_query("SELECT mc_id, patient_id,delivery_date,outcome_id FROM m_patient_mc WHERE delivery_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND outcome_id IN ('NSDF','NSDM','LSCSF','LSCSM')") or die("Cannot query 234: ".mysql_error());
			else:
				$q_natality = mysql_query("SELECT a.mc_id, a.patient_id,a.delivery_date,a.outcome_id FROM m_patient_mc a,m_family_members b, m_family_address c WHERE a.delivery_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND a.outcome_id IN ('NSDF','NSDM','LSCSF','LSCSM') AND a.patient_id=b.patient_id AND b.family_id=c.family_id AND c.barangay_id IN ($brgy_array) AND a.delivery_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]'") or die("Cannot query 236: ".mysql_error());
			endif;
			
			
			while(list($mc_id,$pxid,$delivery_date,$outcome_id) = mysql_fetch_array($q_natality)){
				$arr_natality[$this->get_px_gender($outcome_id)] += 1;

                $month_stat[$this->get_max_month($delivery_date)] += 1;
			}

			//array_push($_SESSION["arr_px_labels"]["natality"],$arr_natality_lb);
			$arr_natality["total"] = $arr_natality["M"] + $arr_natality["F"];			

			$arr_natality[0] = $_SESSION["livebirths"] = $arr_natality["total"];
	
			break;

		//case 2: //lb with weight 2500 grams or greater
		case (in_array($crit,array(1,44))):
			$arr_natality = array("M"=>0,"F"=>0,"total"=>0);
			if(in_array('all',$_SESSION[brgy])):
				$q_lb = mysql_query("SELECT mc_id,patient_id,delivery_date,outcome_id FROM m_patient_mc WHERE delivery_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND birthweight >= '2.5' AND outcome_id IN ('NSDF','NSDM','LSCSF','LSCSM')") or die("Cannot query 251: ".mysql_error());
			else:
				$q_lb = mysql_query("SELECT a.mc_id, a.patient_id,a.delivery_date,a.outcome_id FROM m_patient_mc a,m_family_members b, m_family_address c WHERE a.delivery_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND a.birthweight >= '2.5' AND AND a.outcome_id IN ('NSDF','NSDM','LSCSF','LSCSM') AND a.patient_id=b.patient_id AND b.family_id=c.family_id AND c.barangay_id IN ($brgy_array)") or die("Cannot query 253: ".mysql_error());
			endif;
			
			while(list($mc_id,$pxid,$delivery_date,$outcome_id) = mysql_fetch_array($q_lb)){
				$arr_natality[$this->get_px_gender($outcome_id)] += 1;
			}

			$arr_natality["total"] = $arr_natality["M"] + $arr_natality["F"];
			break;

		//case 3: //lb with weight less than 2500 grams
		case (in_array($crit,array(2,45))): 
			$arr_natality = array("M"=>0,"F"=>0,"total"=>0);
			if(in_array('all',$_SESSION[brgy])):
				$q_lb = mysql_query("SELECT mc_id,patient_id,delivery_date,outcome_id FROM m_patient_mc WHERE delivery_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND birthweight < '2.5' AND birthweight > '0' AND outcome_id IN ('NSDF','NSDM','LSCSF','LSCSM')") or die("Cannot query 284: ".mysql_error());
			else:
				$q_lb = mysql_query("SELECT a.mc_id, a.patient_id,a.delivery_date,a.outcome_id FROM m_patient_mc a,m_family_members b, m_family_address c WHERE a.delivery_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND a.birthweight < '2.5' AND birthweight > '0' AND AND a.outcome_id IN ('NSDF','NSDM','LSCSF','LSCSM') AND a.patient_id=b.patient_id AND b.family_id=c.family_id AND c.barangay_id IN ($brgy_array)") or die("Cannot query 367: ".mysql_error());
			endif;
			
			while(list($mc_id,$pxid,$delivery_date,$outcome_id) = mysql_fetch_array($q_lb)){
				$arr_natality[$this->get_px_gender($outcome_id)] += 1;
			}

			$arr_natality["total"] = $arr_natality["M"] + $arr_natality["F"];
		
			break;

		//case 4:	//lb not know weight
		case (in_array($crit,array(3,46))):
			$arr_natality = array("M"=>0,"F"=>0,"total"=>0);
			if(in_array('all',$_SESSION[brgy])):
				$q_lb = mysql_query("SELECT mc_id,patient_id,delivery_date,outcome_id FROM m_patient_mc WHERE delivery_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND birthweight='' AND outcome_id IN ('NSDF','NSDM','LSCSF','LSCSM')") or die("Cannot query 281: ".mysql_error());
			else:
				$q_lb = mysql_query("SELECT a.mc_id, a.patient_id,a.delivery_date,a.outcome_id FROM m_patient_mc a,m_family_members b, m_family_address c WHERE a.delivery_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND a.birthweight='' AND a.outcome_id IN ('NSDF','NSDM','LSCSF','LSCSM') AND a.patient_id=b.patient_id AND b.family_id=c.family_id AND c.barangay_id IN ($brgy_array)") or die("Cannot query 283: ".mysql_error());
			endif;
			
			while(list($mc_id,$pxid,$delivery_date,$outcome_id) = mysql_fetch_array($q_lb)){
				$arr_natality[$this->get_px_gender($outcome_id)] += 1;
			}

			$arr_natality["total"] = $arr_natality["M"] + $arr_natality["F"];
			break;
			
		//case 5: // lb delivered by doctors MD, RN, MW, UTH, TRH, OTH
		case (in_array($crit,array(4,47))): //livebirths delivered by doctors


			$arr_natality = array("M"=>0,"F"=>0,"total"=>0);
			if(in_array('all',$_SESSION[brgy])):
				$q_lb = mysql_query("SELECT mc_id,patient_id,delivery_date,outcome_id FROM m_patient_mc WHERE delivery_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND birthmode='MD' AND outcome_id IN ('NSDF','NSDM','LSCSF','LSCSM')") or die("Cannot query 296: ".mysql_error());
			else:
				$q_lb = mysql_query("SELECT a.mc_id, a.patient_id,a.delivery_date,a.outcome_id FROM m_patient_mc a,m_family_members b, m_family_address c WHERE a.delivery_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND a.birthmode='MD' AND outcome_id IN ('NSDF','NSDM','LSCSF','LSCSM') AND a.patient_id=b.patient_id AND b.family_id=c.family_id AND c.barangay_id IN ($brgy_array)") or die("Cannot query 298: ".mysql_error());
			endif;
			
			while(list($mc_id,$pxid,$delivery_date,$outcome_id) = mysql_fetch_array($q_lb)){
				$arr_natality[$this->get_px_gender($outcome_id)] += 1;
			}

			$arr_natality["total"] = $arr_natality["M"] + $arr_natality["F"];

			break;

		//case 6:    //lb delivered by nurses
		case (in_array($crit,array(5,48))):	//livebirths delivered by nurses

			$arr_natality = array("M"=>0,"F"=>0,"total"=>0);
			if(in_array('all',$_SESSION[brgy])):
				$q_lb = mysql_query("SELECT mc_id,patient_id,delivery_date,outcome_id FROM m_patient_mc WHERE delivery_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND birthmode='RN' AND outcome_id IN ('NSDF','NSDM','LSCSF','LSCSM')") or die("Cannot query 311: ".mysql_error());
			else:
				$q_lb = mysql_query("SELECT a.mc_id, a.patient_id,a.delivery_date,a.outcome_id FROM m_patient_mc a,m_family_members b, m_family_address c WHERE a.delivery_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND a.birthmode='RN' AND a.outcome_id IN ('NSDF','NSDM','LSCSF','LSCSM') AND a.patient_id=b.patient_id AND b.family_id=c.family_id AND c.barangay_id IN ($brgy_array)") or die("Cannot query 313: ".mysql_error());
			endif;
			
			while(list($mc_id,$pxid,$delivery_date,$outcome_id) = mysql_fetch_array($q_lb)){
				$arr_natality[$this->get_px_gender($outcome_id)] += 1;
			}

			$arr_natality["total"] = $arr_natality["M"] + $arr_natality["F"];
			
			break; 

		//case 7://lb delivered by midwives

		case (in_array($crit,array(6,49))): //livebirths delivered by midwives
			$arr_natality = array("M"=>0,"F"=>0,"total"=>0);
			if(in_array('all',$_SESSION[brgy])):
				$q_lb = mysql_query("SELECT mc_id,patient_id,delivery_date,outcome_id FROM m_patient_mc WHERE delivery_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND birthmode='MW' AND outcome_id IN ('NSDF','NSDM','LSCSF','LSCSM')") or die("Cannot query 327: ".mysql_error());
			else:
				$q_lb = mysql_query("SELECT a.mc_id, a.patient_id,a.delivery_date,a.outcome_id FROM m_patient_mc a,m_family_members b, m_family_address c WHERE a.delivery_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND a.birthmode='MW' AND a.outcome_id IN ('NSDF','NSDM','LSCSF','LSCSM') AND a.patient_id=b.patient_id AND b.family_id=c.family_id AND c.barangay_id IN ($brgy_array)") or die("Cannot query 329: ".mysql_error());
			endif;
			
			while(list($mc_id,$pxid,$delivery_date,$outcome_id) = mysql_fetch_array($q_lb)){
				$arr_natality[$this->get_px_gender($outcome_id)] += 1;
			}

			$arr_natality["total"] = $arr_natality["M"] + $arr_natality["F"];

			break;

		//case 8: // lb delivered by hilot / tba
		case (in_array($crit,array(7,50))):	//livebirths delivered by hilot / TBA
			$arr_natality = array("M"=>0,"F"=>0,"total"=>0);
			if(in_array('all',$_SESSION[brgy])):
				$q_lb = mysql_query("SELECT mc_id,patient_id,delivery_date,outcome_id FROM m_patient_mc WHERE delivery_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND birthmode IN ('UTH','TRH') AND outcome_id IN ('NSDF','NSDM','LSCSF','LSCSM')") or die("Cannot query 344: ".mysql_error());
			else:
				$q_lb = mysql_query("SELECT a.mc_id, a.patient_id,a.delivery_date,a.outcome_id FROM m_patient_mc a,m_family_members b, m_family_address c WHERE a.delivery_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND birthmode IN ('UTH','TRH') AND a.outcome_id IN ('NSDF','NSDM','LSCSF','LSCSM') AND a.patient_id=b.patient_id AND b.family_id=c.family_id AND c.barangay_id IN ($brgy_array)") or die("Cannot query 346: ".mysql_error());
			endif;
			
			while(list($mc_id,$pxid,$delivery_date,$outcome_id) = mysql_fetch_array($q_lb)){
				$arr_natality[$this->get_px_gender($outcome_id)] += 1;
			}

			$arr_natality["total"] = $arr_natality["M"] + $arr_natality["F"];

			break;

		//case 9: //lb delivered by others
		case (in_array($crit,array(8,51))):	
			$arr_natality = array("M"=>0,"F"=>0,"total"=>0);
			if(in_array('all',$_SESSION[brgy])):
				$q_lb = mysql_query("SELECT mc_id,patient_id,delivery_date,outcome_id FROM m_patient_mc WHERE delivery_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND birthmode='OTH' AND outcome_id IN ('NSDF','NSDM','LSCSF','LSCSM')") or die("Cannot query 361: ".mysql_error());
			else:
				$q_lb = mysql_query("SELECT a.mc_id, a.patient_id,a.delivery_date,a.outcome_id FROM m_patient_mc a,m_family_members b, m_family_address c WHERE a.delivery_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND birthmode='OTH' AND a.outcome_id IN ('NSDF','NSDM','LSCSF','LSCSM') AND a.patient_id=b.patient_id AND b.family_id=c.family_id AND c.barangay_id IN ($brgy_array)") or die("Cannot query 363: ".mysql_error());
			endif;
			
			while(list($mc_id,$pxid,$delivery_date,$outcome_id) = mysql_fetch_array($q_lb)){
				$arr_natality[$this->get_px_gender($outcome_id)] += 1;
			}

			$arr_natality["total"] = $arr_natality["M"] + $arr_natality["F"];

			break;

		//case 10: //deliveries (NSDs)
		case (in_array($crit,array(20,42,30))):
			$arr_natality = array('0');
			$arr_natality = array("M"=>0,"F"=>0,"total"=>0);

			if(in_array('all',$_SESSION[brgy])):
				$q_lb = mysql_query("SELECT mc_id,patient_id,delivery_date,outcome_id FROM m_patient_mc WHERE delivery_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND outcome_id IN ('NSDM','NSDF')") or die("Cannot query 378: ".mysql_error());
			else:
				$q_lb = mysql_query("SELECT a.mc_id, a.patient_id,a.delivery_date,a.outcome_id FROM m_patient_mc a,m_family_members b, m_family_address c WHERE a.delivery_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND outcome_id IN ('NSDM','NSDF') AND a.patient_id=b.patient_id AND b.family_id=c.family_id AND c.barangay_id IN ($brgy_array)") or die("Cannot query 380: ".mysql_error());
			endif;


			while(list($mc_id,$pxid,$delivery_date,$outcome_id)=mysql_fetch_array($q_lb)){
				$arr_natality[$this->get_px_gender($outcome_id)] += 1;
			}

			$arr_natality["total"] = $arr_natality["M"] + $arr_natality["F"];

			$arr_natality[0] = $_SESSION["nsd"] = $arr_natality["total"];

			break;


		//case 15: //normal deliveries at home
		case (in_array($crit,array(17,31))): //nsd at home
			$arr_natality = array('0');
			$arr_natality = array("M"=>0,"F"=>0,"total"=>0);

			if(in_array('all',$_SESSION[brgy])):
				$q_lb = mysql_query("SELECT mc_id,patient_id,delivery_date,outcome_id FROM m_patient_mc WHERE delivery_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND outcome_id IN ('NSDM','NSDF') AND delivery_location='HOME'") or die("Cannot query 450: ".mysql_error());
			else:
				$q_lb = mysql_query("SELECT a.mc_id, a.patient_id,a.delivery_date,a.outcome_id FROM m_patient_mc a,m_family_members b, m_family_address c WHERE a.delivery_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND outcome_id IN ('NSDM','NSDF') AND a.patient_id=b.patient_id AND b.family_id=c.family_id AND c.barangay_id IN ($brgy_array) AND a.delivery_location='HOME'") or die("Cannot query 452: ".mysql_error());
			endif;


			while(list($mc_id,$pxid,$delivery_date,$outcome_id)=mysql_fetch_array($q_lb)){
				$arr_natality[$this->get_px_gender($outcome_id)] += 1;
			}			

			$arr_natality["total"] = $arr_natality["M"] + $arr_natality["F"];

			$arr_natality[0] = $arr_natality["total"];

			break;

		//case 16: //normal deliveries at all health facilities
		case (in_array($crit,array(32))):	//nsd at health facility
			$arr_natality = array('0');
			$arr_natality = array("M"=>0,"F"=>0,"total"=>0);

			if(in_array('all',$_SESSION[brgy])):
				$q_lb = mysql_query("SELECT mc_id,patient_id,delivery_date,outcome_id FROM m_patient_mc WHERE delivery_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND outcome_id IN ('NSDM','NSDF') AND delivery_location IN ('HC','BHS','HOSP','LYIN')") or die("Cannot query 466: ".mysql_error());
			else:
				$q_lb = mysql_query("SELECT a.mc_id, a.patient_id,a.delivery_date,a.outcome_id FROM m_patient_mc a,m_family_members b, m_family_address c WHERE a.delivery_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND outcome_id IN ('NSDM','NSDF') AND a.patient_id=b.patient_id AND b.family_id=c.family_id AND c.barangay_id IN ($brgy_array) AND a.delivery_location IN ('HC','BHS','HOSP','LYIN')") or die("Cannot query 468: ".mysql_error());
			endif;


			while(list($mc_id,$pxid,$delivery_date,$outcome_id)=mysql_fetch_array($q_lb)){
				$arr_natality[$this->get_px_gender($outcome_id)] += 1;
			}			

			$arr_natality["total"] = $arr_natality["M"] + $arr_natality["F"];

			$arr_natality[0] = $arr_natality["total"];

			break;

		//case 17: //normal deliveries at other places
		case (in_array($crit,array(18,33))): 
			$arr_natality = array('0');
			$arr_natality = array("M"=>0,"F"=>0,"total"=>0);

			if(in_array('all',$_SESSION[brgy])):
				$q_lb = mysql_query("SELECT mc_id,patient_id,delivery_date,outcome_id FROM m_patient_mc WHERE delivery_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND outcome_id IN ('NSDM','NSDF') AND delivery_location IN ('OTHERS')") or die("Cannot query 482: ".mysql_error());
			else:
				$q_lb = mysql_query("SELECT a.mc_id, a.patient_id,a.delivery_date,a.outcome_id FROM m_patient_mc a,m_family_members b, m_family_address c WHERE a.delivery_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND outcome_id IN ('NSDM','NSDF') AND a.patient_id=b.patient_id AND b.family_id=c.family_id AND c.barangay_id IN ($brgy_array) AND a.delivery_location IN ('OTHERS')") or die("Cannot query 484: ".mysql_error());
			endif;


			while(list($mc_id,$pxid,$delivery_date,$outcome_id)=mysql_fetch_array($q_lb)){
				$arr_natality[$this->get_px_gender($outcome_id)] += 1;
			}			

			$arr_natality["total"] = $arr_natality["M"] + $arr_natality["F"];

			$arr_natality[0] = $arr_natality["total"];


			break;

		//case 18: //other types of deliveries (other than NSD)
		case (in_array($crit,array(21,34,43))); //operative deliveries

			$arr_natality = array('0');
			$arr_natality = array("M"=>0,"F"=>0,"total"=>0);

			if(in_array('all',$_SESSION[brgy])):
				$q_lb = mysql_query("SELECT mc_id,patient_id,delivery_date,outcome_id FROM m_patient_mc WHERE delivery_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND outcome_id IN ('LSCSF','LSCSM')") or die("Cannot query 498: ".mysql_error());
			else:
				$q_lb = mysql_query("SELECT a.mc_id, a.patient_id,a.delivery_date,a.outcome_id FROM m_patient_mc a,m_family_members b, m_family_address c WHERE a.delivery_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND outcome_id IN ('LSCSF','LSCSM') AND a.patient_id=b.patient_id AND b.family_id=c.family_id AND c.barangay_id IN ($brgy_array)") or die("Cannot query 500: ".mysql_error());
			endif;


			while(list($mc_id,$pxid,$delivery_date,$outcome_id)=mysql_fetch_array($q_lb)){
				$arr_natality[$this->get_px_gender($outcome_id)] += 1;
			}			
			
			$arr_natality["total"] = $arr_natality["M"] + $arr_natality["F"];

			$arr_natality[0] = $_SESSION["operative"] = $arr_natality["total"];

			break;
		
		case (in_array($crit,array(22,26,37))): //number of pregnancies

			$arr_natality = array("M"=>0,"F"=>0,"total"=>0);
			if(in_array('all',$_SESSION[brgy])):
				$q_lb = mysql_query("SELECT mc_id,patient_id,delivery_date,outcome_id FROM m_patient_mc WHERE delivery_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]'") or die("Cannot query 327: ".mysql_error());
			else:
				$q_lb = mysql_query("SELECT a.mc_id, a.patient_id,a.delivery_date,a.outcome_id FROM m_patient_mc a,m_family_members b, m_family_address c WHERE a.delivery_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND a.patient_id=b.patient_id AND b.family_id=c.family_id AND c.barangay_id IN ($brgy_array)") or die("Cannot query 329: ".mysql_error());
			endif;
			
			while(list($mc_id,$pxid,$delivery_date,$outcome_id) = mysql_fetch_array($q_lb)){
				$arr_natality[$this->get_px_gender($outcome_id)] += 1;
			}

			$arr_natality["total"] = $arr_natality["M"] + $arr_natality["F"];

			$arr_natality[0] = $_SESSION["pregnancies"] = $arr_natality["total"];
			
			break;

		case (in_array($crit,array(28,39))): //fetal death pregnancy, disaggregated into male and female

			$arr_natality = array("M"=>0,"F"=>0,"total"=>0);
			if(in_array('all',$_SESSION[brgy])):
				$q_lb = mysql_query("SELECT mc_id,patient_id,delivery_date,outcome_id FROM m_patient_mc WHERE delivery_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND outcome_id IN ('FDU','FDUF','FDUM')") or die("Cannot query 327: ".mysql_error());
			else:
				$q_lb = mysql_query("SELECT a.mc_id, a.patient_id,a.delivery_date,a.outcome_id FROM m_patient_mc a,m_family_members b, m_family_address c WHERE a.delivery_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND outcome_id IN ('FDU','FDUF','FDUM') AND a.patient_id=b.patient_id AND b.family_id=c.family_id AND c.barangay_id IN ($brgy_array)") or die("Cannot query 329: ".mysql_error());
			endif;
			
			while(list($mc_id,$pxid,$delivery_date,$outcome_id) = mysql_fetch_array($q_lb)){

				$arr_natality[$this->get_px_gender($outcome_id)] += 1;
			}

			$arr_natality["total"] = $arr_natality["M"] + $arr_natality["F"];

			$arr_natality[0] = $arr_natality["total"];
			
			break;
		
		case (in_array($crit,array(29,40))):	//abortion
			$arr_natality["M"] = $arr_natality["F"] = 0;

			$arr_natality["total"] = $arr_natality["M"] + $arr_natality["F"];

			$arr_natality[0] = $arr_natality["total"];
			
			break;

		case (in_array($crit,array(35))):	//number of operative deliveries at health facility (HC,BHS,HOSP,LYING-IN)

			$arr_natality = array('0');
			$arr_natality = array("M"=>0,"F"=>0,"total"=>0);

			if(in_array('all',$_SESSION[brgy])):
				$q_lb = mysql_query("SELECT mc_id,patient_id,delivery_date,outcome_id FROM m_patient_mc WHERE delivery_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND outcome_id IN ('LSCSF','LSCSM') AND delivery_location IN ('HC','BHS','HOSP','LYIN')") or die("Cannot query 498: ".mysql_error());
			else:
				$q_lb = mysql_query("SELECT a.mc_id, a.patient_id,a.delivery_date,a.outcome_id FROM m_patient_mc a,m_family_members b, m_family_address c WHERE a.delivery_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND a.outcome_id IN ('LSCSF','LSCSM') AND a.delivery_location IN ('HC','BHS','HOSP','LYIN') AND a.patient_id=b.patient_id AND b.family_id=c.family_id AND c.barangay_id IN ($brgy_array)") or die("Cannot query 500: ".mysql_error());
			endif;


			while(list($mc_id,$pxid,$delivery_date,$outcome_id)=mysql_fetch_array($q_lb)){
				$arr_natality[$this->get_px_gender($outcome_id)] += 1;
			}			
			
			$arr_natality["total"] = $arr_natality["M"] + $arr_natality["F"];

			$arr_natality[0] = $arr_natality["total"];

			break;
		
		case (in_array($crit,array(36))):	//number of operative deliveries at OTHERS
			$arr_natality = array('0');
			$arr_natality = array("M"=>0,"F"=>0,"total"=>0);

			if(in_array('all',$_SESSION[brgy])):
				$q_lb = mysql_query("SELECT mc_id,patient_id,delivery_date,outcome_id FROM m_patient_mc WHERE delivery_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND outcome_id IN ('LSCSF','LSCSM') AND delivery_location IN ('OTHERS')") or die("Cannot query 498: ".mysql_error());
			else:
				$q_lb = mysql_query("SELECT a.mc_id, a.patient_id,a.delivery_date,a.outcome_id FROM m_patient_mc a,m_family_members b, m_family_address c WHERE a.delivery_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND a.outcome_id IN ('LSCSF','LSCSM') AND a.delivery_location IN ('OTHERS') AND a.patient_id=b.patient_id AND b.family_id=c.family_id AND c.barangay_id IN ($brgy_array)") or die("Cannot query 500: ".mysql_error());
			endif;


			while(list($mc_id,$pxid,$delivery_date,$outcome_id)=mysql_fetch_array($q_lb)){
				$arr_natality[$this->get_px_gender($outcome_id)] += 1;
			}			
			
			$arr_natality["total"] = $arr_natality["M"] + $arr_natality["F"];

			$arr_natality[0] = $arr_natality["total"];

			break;

		case (in_array($crit,array(41))): //number of deliveries. includes NSD, Operative, Still birth

			$arr_natality = array('0');
			$arr_natality = array("M"=>0,"F"=>0,"total"=>0);

			if(in_array('all',$_SESSION[brgy])):
				$q_lb = mysql_query("SELECT mc_id,patient_id,delivery_date,outcome_id FROM m_patient_mc WHERE delivery_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]'") or die("Cannot query 498: ".mysql_error());
			else:
				$q_lb = mysql_query("SELECT a.mc_id, a.patient_id,a.delivery_date,a.outcome_id FROM m_patient_mc a,m_family_members b, m_family_address c WHERE a.delivery_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND a.patient_id=b.patient_id AND b.family_id=c.family_id AND c.barangay_id IN ($brgy_array)") or die("Cannot query 500: ".mysql_error());
			endif;


			while(list($mc_id,$pxid,$delivery_date,$outcome_id)=mysql_fetch_array($q_lb)){
				$arr_natality[$this->get_px_gender($outcome_id)] += 1;
			}			
			
			$arr_natality["total"] = $arr_natality["M"] + $arr_natality["F"];

			$arr_natality[0] = $arr_natality["total"];

			break;

		default:

			break;

		} // end <switch>
//	} //end <for> months
		if($_SESSION[ques]==128):
			return $month_stat;
		else:
			return $arr_natality;
		endif;
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
		$q_brgy_pop = mysql_query("SELECT SUM(population) FROM m_lib_population WHERE population_year='$taon' AND barangay_id IN ($str)") or die("Cannot query: 372".mysql_error());
	
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

function get_px_gender($outcome_id){ 
	
	$index = strlen($outcome_id)-1;
	
	if($outcome_id[$index]=='M'):
		return "M";	
	elseif($outcome_id[$index]=='F'):
		return "F";
	else:

	endif;
}

function get_target_percentage($indicator,$total){
	$arr_preg_deno = array(0,23,27,28,29,20,30,34);
	$arr_nsd_deno = array(17,31,32,18,33,21);
	$arr_operative_deno = array(35,36);
	$arr_livebirth_deno = array(44,45,46,47,48,49,50,51);
	$denominator = '';


	if(in_array($indicator,$arr_preg_deno)):
		$denominator = $_SESSION["pregnancies"];
	elseif(in_array($indicator,$arr_nsd_deno)): 
		$denominator = $_SESSION["nsd"]; 
	elseif(in_array($indicator,$arr_operative_deno)):
		$denominator = $_SESSION["operative"];
	elseif(in_array($indicator,$arr_livebirth_deno)):
		$denominator = $_SESSION["livebirths"];
	else: 
		$denominator = '-';
	endif;


	if($denominator!='-'): 
		return round((($total / $denominator)*100),2);
	else: 
		return $denominator;
	endif;
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
$pdf->SetFont('Arial','',13);
$pdf->AddPage();


$natality_content = $pdf->show_natality();

if($_GET["type"]=='html'):
	if($_SESSION[ques]>=120 && $_SESSION[ques]<=123): //natality livebirth questions	
		$html_tab->create_table($_SESSION["w2"],$_SESSION["header2"],$natality_content); //livebirth
	elseif($_SESSION[ques]>=124 && $_SESSION[ques]<=127):
		$html_tab->create_table($_SESSION["w"],$_SESSION["header"],$natality_content); //deliveries
	else:

	endif;
elseif($_GET["type"]=='csv'):

	//print_r($natality_content);
	if($_SESSION[ques]>=120 && $_SESSION[ques]<=123): //natality livebirth questions	
		
	//	$html_tab->create_table($_SESSION["w2"],$_SESSION["header2"],$natality_content); //livebirth
	elseif($_SESSION[ques]>=124 && $_SESSION[ques]<=127):
	//	$html_tab->create_table($_SESSION["w"],$_SESSION["header"],$natality_content); //deliveries
	else:

	endif;
else:
	$pdf->Output();
endif;
?>