<?php

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
//	print_r($_SESSION[months][1]);
	


	$date_label = ($m1[0]==$m2[0])?$_SESSION[months][$m1[0]].' '.$m1[2]:$_SESSION[months][$m1[0]].' to '.$_SESSION[months][$m2[0]].' '.$m1[2];
	$municipality_label = $_SESSION[datanode][name];
        
	$this->SetFont('Arial','B',12);        

        if($_SESSION[ques]==36): //maternal care summary table                
	$this->Cell(0,5,'Maternal Care Summary Table ( '.$date_label.' )'.' - '.$municipality_label,0,1,'C');
	
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

	$this->SetFont('Arial','',10);
	$this->Cell(0,5,$brgy_label,0,1,'C');
	$_SESSION["w"] = $w = array(30,18,18,18,18,15,18,18,18,15,18,18,18,15,18,18,18,15,18); //340
	$_SESSION["header"] = $header = array('INDICATORS','Target','JAN','FEB','MAR','1st Q','APR','MAY','JUNE','2nd Q','JULY','AUG','SEPT','3rd Q','OCT','NOV','DEC','4th Q','TOTAL');
	
	elseif($_SESSION[ques]==80 || $_SESSION[ques]==81): //maternal care monthly and quarterly report respectively
	    $q_pop = mysql_query("SELECT SUM(population) FROM m_lib_population WHERE population_year='$_SESSION[year]'") or die("CAnnot query: 164");
	    
	    if(mysql_num_rows($q_pop)!=0):
                list($population) = mysql_fetch_row($q_pop);
            else:
	        $population = 0;
	    endif;
	    
            if($_SESSION[ques]==80):
                $this->Cell(0,5,'FHSIS REPORT FOR THE MONTH: '.date('F',mktime(0,0,0,$_SESSION[smonth],1,0)).'          YEAR: '.$_SESSION[year],0,1,L);
                $this->Cell(0,5,'NAME OF BHS: '.$this->get_brgy(),0,1,L); 
                $_SESSION["w"] = $w = array(200,40);                
                $_SESSION["header"] = $header = array('MATERNAL CARE', 'No.');
                
            elseif($_SESSION[ques]==81):
                $_SESSION["w"] = $w = array(161,30,25,25,50,45);
                $_SESSION["header"] = $header = array('Indicators', 'Eligible Population','No.','% / Rate','Interpretation','Recommendation/Action Taken');            
                $this->Cell(0,5,'FHSIS REPORT FOR THE QUARTER: '.$_SESSION[quarter].'          YEAR: '.$_SESSION[year],0,1,L);            
            else:
            
            endif;
            
            $this->Cell(0,5,'MUNICIPALITY/CITY OF: '.$_SESSION[lgu],0,1,L);
            $this->Cell(0,5,'PROVINCE: '.$_SESSION[province].'          PROJECTED POPULATION OF THE YEAR: '.$population,0,1,L);
            
            $this->SetFont('Arial','B','13');
        
        else:
             
	endif;
	
	$this->Ln();
	$this->SetWidths($w);
	$this->Row($header);	
}

function show_mc_summary(){
	
	$arr_csv = array();
	$arr_consolidate = array();
	
	$criteria = array('Pregnant Women with 4 or more prenatal visits','Pregnant Women given 2 doses of TT','Pregnant Women given TT2 plus','Pregnant given complete iron with folic acid','Pregnant given Vit. A','Postpartum women with at least 2 PPV','Postpartum women given complete iron','Postpartum women given Vit. A','Postpartum women initiated breastfeeding','Women 10-49 years old women given iron supplementation','Number of deliveries','Number of pregnant women','Number of pregnant women tested for syphilis','Number of pregnant women positive for syphilis','Number of pregnant women given penicillin');
    	
	$q_brgy = mysql_query("SELECT barangay_name from m_lib_barangay LIMIT 1") or die("Cannot query: 202");
	list($csv_brgy) = mysql_fetch_array($q_brgy);

	array_push($arr_csv,strtoupper($csv_brgy),$_SESSION["edate_orig"]);

	for($i=0;$i<count($criteria);$i++){
	
		$array_target = array();
		$q_array = array();
		$gt = 0;

		$mstat = $this->compute_indicator($i+1);
		$brgy_pop = $this->get_brgy_pop();
		

		$target_perc = $this->get_target($i+1);

		$target = round(($brgy_pop * $target_perc));

		for($j=1;$j<=count($mstat);$j++){
			if($target==0):
				$array_target[$j] = 0;
			else:
				$array_target[$j] = round($mstat[$j]/$target,3)*100;
			endif;
		}

		$q_array = $this->get_quarterly_total($mstat,$target);
		$gt = array_sum($mstat);

			array_push($arr_csv,$q_array[$_SESSION["quarter"]]);

                if($_SESSION[ques]==36):
                    $w = array(30,18,18,18,18,15,18,18,18,15,18,18,18,15,18,18,18,15,18); //340
                    $this->SetWidths($w);

		    array_push($arr_consolidate,array($criteria[$i],$target,$mstat[1],$mstat[2],$mstat[3],$q_array[1],$mstat[4],$mstat[5],$mstat[6],$q_array[2],$mstat[7],$mstat[8],$mstat[9],$q_array[3],$mstat[10],$mstat[11],$mstat[12],$q_array[4],$gt));

                    $this->Row(array($criteria[$i],$target,$mstat[1],$mstat[2],$mstat[3],$q_array[1],$mstat[4],$mstat[5],$mstat[6],$q_array[2],$mstat[7],$mstat[8],$mstat[9],$q_array[3],$mstat[10],$mstat[11],$mstat[12],$q_array[4],$gt));

		
                elseif($_SESSION[ques]==80):
                    $w = array(200,40); //340 //monthly report
                    $this->SetWidths($w);
                    $arr_disp = array();
                    $this->SetFont('Arial','',13);
                    array_push($arr_disp,$criteria[$i],$mstat[$_SESSION[smonth]]);

		    array_push($arr_consolidate,$arr_disp);

		    for($x=0;$x<count($arr_disp);$x++){
                        if($x==0):
                            $this->Cell($w[$x],6,($i+1).'. '.$arr_disp[$x],'1',0,'1');
                        else:
                            $this->Cell($w[$x],6,$arr_disp[$x],'1',0,'1');
                        endif;
                    }

                    $this->Ln();

                elseif($_SESSION[ques]==81): //quarterly report
                    $w = array(161,30,25,25,50,45);
                    $this->SetWidths($w);
                    $arr_disp = array();
                    $this->SetFont('Arial','',13);

                    array_push($arr_disp,$criteria[$i],$target,$q_array[$_SESSION[quarter]],$this->compute_mc_rate($target,$q_array[$_SESSION[quarter]]).'%',' ',' ');

				    array_push($arr_consolidate,$arr_disp);

                    for($x=0;$x<count($arr_disp);$x++){
                        if($x==0):
                            $this->Cell($w[$x],6,($i+1).'. '.$arr_disp[$x],'1',0,'1');
                        else:
                            $this->Cell($w[$x],6,$arr_disp[$x],'1',0,'1');
                        endif;
                    }

                    $this->Ln();
                else:

                endif;
	
		//$this->Row(array($criteria[$i],$target,$array_target[1],$array_target[2],$array_target[3],$q_array[1],$array_target[4],$array_target[5],$array_target[6],$q_array[2],$array_target[7],$array_target[8],$array_target[9],$q_array[3],$array_target[10],$array_target[11],$array_target[12],$q_array[4],$gt));

	}

	//return $arr_csv;
	return $arr_consolidate;
 }

function compute_indicator($crit){
	$month_stat = array(1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0);
	list($syr,$smonth,$sdate) = explode('-',$_SESSION[sdate2]);
	list($eyr,$emonth,$edate) = explode('-',$_SESSION[edate2]);
	$brgy_array = $this->get_brgy_array();
	$brgy_array = implode(',',$brgy_array);
	
	
	//print_r($brgy_array);

		switch($crit){

		case 1: //pregnant with 4 or more prenatal visits
			$anc_name_px = array(1=>array(),2=>array(),3=>array(),4=>array(),5=>array(),6=>array(),7=>array(),8=>array(),9=>array(),10=>array(),11=>array(),12=>array());

//				if(in_array('all',$_SESSION[brgy])):
			//$get_visits = mysql_query("SELECT distinct mc_id,patient_id,MIN(prenatal_date) FROM m_consult_mc_prenatal WHERE visit_sequence >=  4 AND trimester=3 AND prenatal_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' GROUP by mc_id") or die("Cannot query: 186");

			$get_visits = mysql_query("SELECT distinct mc_id,patient_id,MIN(prenatal_date) FROM m_consult_mc_prenatal WHERE trimester=3 AND prenatal_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' GROUP by mc_id") or die("Cannot query: 186");
//				else:
//					$get_visits = mysql_query("SELECT distinct a.mc_id,a.patient_id,MIN(a.prenatal_date) FROM m_consult_mc_prenatal a ,m_family_members b, m_family_address c WHERE a.visit_sequence >=  4 AND a.trimester=3 AND a.prenatal_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND a.patient_id=b.patient_id AND b.family_id=c.family_id AND c.barangay_id IN ($brgy_array) GROUP by a.mc_id") or die(mysql_error());				
//				endif;

			if(mysql_num_rows($get_visits)!=0):
				$arr_px_id = array();
			
			while(list($mcid,$pxid,$predate)=mysql_fetch_array($get_visits)){ 
				$banat = 0;
				if(in_array('all',$_SESSION[brgy])):
					$banat = 1;
				else:
				
				$str = implode(',',$_SESSION[brgy]);
				$get_brgy = mysql_query("SELECT a.barangay_id FROM m_family_address a, m_family_members b WHERE b.patient_id='$pxid' AND b.family_id=a.family_id AND a.barangay_id IN ($str)") or die(mysql_error());
															
					if(mysql_num_rows($get_brgy)!=0):
						$banat = 1;
					else:
						$banat = 0;
					endif;
				endif;

				if($banat==1): //$banat variable, if set to 1 mean the patient is in the barangay
					
				for($j=1;$j<=3;$j++){   //traverse for checking the trimester format 1-1-2
					$get_tri = mysql_query("SELECT consult_id, prenatal_date FROM m_consult_mc_prenatal WHERE trimester='$j' AND mc_id='$mcid' ORDER by prenatal_date DESC") or die("Cannot query: 186");

					$num = mysql_num_rows($get_tri);
	



					if($num!=0):
					   if($j==3):

						$q_min_date = mysql_query("SELECT MIN(prenatal_date) FROM m_consult_mc_prenatal WHERE mc_id='$mcid' AND trimester='$j' AND prenatal_date!=(SELECT MIN(prenatal_date) FROM m_consult_mc_prenatal WHERE mc_id='$mcid' AND trimester='$j')") or die("cannot query: 204");

						  if(mysql_num_rows($q_min_date)!=0):

							list($sec_date) = mysql_fetch_array($q_min_date);						
							list($latestdate) = explode(' ',$sec_date);
							list($latesty,$latestm,$latestd) = explode('-',$latestdate);
							$yr = date('Y');
							$max_date = date("n",mktime(0,0,0,$latestm,$latestd,$yr)); //get the unix timestamp then return month without trailing 0
							$arr[$j] = ($num>=2)?1:0; //check if the third trimester has at least 2 visits
							
						  endif; 
					   else:
						  $arr[$j] = 1; //marked trimester 1 and 2 with 1's if $num!=0
					   endif; 
					else: 
						  $arr[$j] = 0;
					endif;
					
				} //exit 1-1-4 format checking

				
				if($arr[1]==1 && $arr[2]==1 && $arr[3]==1):

					if(!(in_array($pxid,$arr_px_id))):
						array_push($anc_name_px[$max_date],array($pxid,'Pregnant women with 4 or more prenatal visits','mc',$latestdate));
						//array_push($anc_name_px,$pxid,'Pregnant women with 4 or more prenatal visits','mc');
						$month_stat[$max_date]+=1;
						array_push($arr_px_id,$pxid);
					endif;
				endif;				
				
			
				
				endif;
			} //end while

			endif; //end 

			array_push($_SESSION["arr_px_labels"]["mc"],$anc_name_px); 
			//print_r($_SESSION["arr_px_labels"]);
			break;
			
		case 2: 

			$tt2_name_px = array(1=>array(),2=>array(),3=>array(),4=>array(),5=>array(),6=>array(),7=>array(),8=>array(),9=>array(),10=>array(),11=>array(),12=>array());

			if(in_array('all',$_SESSION[brgy])):
				$q_px_tt = mysql_query("SELECT patient_id,actual_vaccine_date FROM m_consult_mc_vaccine WHERE vaccine_id='TT1'") or die(mysql_error());
			else:
				$q_px_tt = mysql_query("SELECT a.patient_id,a.actual_vaccine_date FROM m_consult_mc_vaccine a,m_family_members b,m_family_address c WHERE a.vaccine_id='TT1' AND a.patient_id=b.patient_id AND b.family_id=c.family_id AND c.barangay_id IN ($brgy_array)") or die(mysql_error());
			endif;
			
			if(mysql_num_rows($q_px_tt)!=0):
				$arr_px_id = array();
			while(list($pxid,$vacc_date)=mysql_fetch_array($q_px_tt)){			
				//condition 1: prenatal is the base date
				//$q_t2 = mysql_query("SELECT a.patient_id,a.actual_vaccine_date FROM m_consult_mc_vaccine a,m_consult_mc_prenatal b WHERE a.vaccine_id='TT2' AND a.patient_id='$pxid' AND a.patient_id=b.patient_id AND b.prenatal_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND b.visit_sequence='1'") or die(mysql_error());
				

				//condition 2: pregnant women given 2 doses of TT or TT2 plus protected women
				//committed a long standing wrong assumption, but has been corrected
				//report the same pregnant woman every month until EDC as long as she is protected by TT
 
				//$q_t2 = mysql_query("SELECT DISTINCT a.patient_id,a.actual_vaccine_date,c.patient_edc FROM m_consult_mc_vaccine a,m_consult_mc_prenatal b,m_patient_mc c WHERE a.vaccine_id='TT2' AND a.patient_id='$pxid' AND a.patient_id=c.patient_id AND (TO_DAYS(c.patient_edc)-TO_DAYS(a.actual_vaccine_date)) <= 1095 AND c.end_pregnancy_flag='N' AND c.delivery_date='0000-00-00' AND a.actual_vaccine_date <= '$_SESSION[edate2]'") or die(mysql_error());
				
				//condition 3: 1). patient is pregnant, 2). patient was injected with TT2 between the start and end date  3). distance between vaccine date and patient EDC is less than 1095 days
				$q_t2 = mysql_query("SELECT a.patient_id,a.actual_vaccine_date,c.patient_edc,a.consult_id FROM m_consult_mc_vaccine a,m_consult_mc_prenatal b,m_patient_mc c WHERE a.vaccine_id='TT2' AND a.patient_id='$pxid' AND a.patient_id=c.patient_id AND (TO_DAYS(c.patient_edc)-TO_DAYS(a.actual_vaccine_date)) <= 1095 AND a.actual_vaccine_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' ORDER by a.actual_vaccine_date DESC LIMIT 1") or die(mysql_error());

				if(mysql_num_rows($q_t2)!=0):
					while(list($pxid,$vacc_date,$edc)=mysql_fetch_array($q_t2)){
						//condition 2 for statement
						/*for($i=$_SESSION[smonth];$i<=$this->get_max_month($edc);$i++){ 
							//echo $vacc_date.' '.$pxid.' '.$edc.'<br>';
							//$month_stat[$this->get_max_month($vacc_date)]+=1;
							$month_stat[$i]+=1;
						}*/
						if(!(in_array($pxid,$arr_px_id))):
							array_push($tt2_name_px[$this->get_max_month($vacc_date)],array($pxid,'Pregnant Women given 2 doses of TT','mc',$vacc_date));
							$month_stat[$this->get_max_month($vacc_date)]+=1;
							array_push($arr_px_id,$pxid);
						endif;
					}
				endif;
			}

			endif;

			array_push($_SESSION["arr_px_labels"]["mc"],$tt2_name_px); 

			break;
		
		case 3: //pregnant women who are protected with TT2 plus protection
			$arr_tt = array(1=>0,2=>0,3=>0,4=>0,5=>0);
			
			$vacc = array('TT1','TT2','TT3','TT4','TT5');
			

			$tt_duration = array(1=>0,2=>1095,3=>1825,4=>3650,5=>10000); //number of days of effectiveness
			$highest_tt = 0;
			$protected = 0;
			
			$ttplus_name_px = array(1=>array(),2=>array(),3=>array(),4=>array(),5=>array(),6=>array(),7=>array(),8=>array(),9=>array(),10=>array(),11=>array(),12=>array());

			if(in_array('all',$_SESSION[brgy])):				
				$get_px_tt = mysql_query("SELECT distinct patient_id, max(vaccine_id), actual_vaccine_date FROM m_consult_mc_vaccine WHERE vaccine_id IN ('TT2','TT3','TT4','TT5') AND actual_vaccine_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' GROUP by patient_id") or die(mysql_error());

			else:
				$get_px_tt = mysql_query("SELECT distinct a.patient_id, max(a.vaccine_id), a.actual_vaccine_date FROM m_consult_mc_vaccine a, m_family_members b, m_family_address c WHERE a.vaccine_id IN ('TT2','TT3','TT4','TT5') AND a.patient_id=b.patient_id AND b.family_id=c.family_id AND c.barangay_id IN ($brgy_array) AND a.actual_vaccine_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' GROUP by a.patient_id") or die(mysql_error());
			endif;
			
			if(mysql_num_rows($get_px_tt)!=0):
			
				$arr_px_id = array();

			while(list($pxid,$vacc_id,$vacc_date)=mysql_fetch_array($get_px_tt)){ 
				//check if the patient is in the active maternal cases for the time span
				//echo $pxid.'/'.$vacc_id.'/'.$vacc_date.'<br>';
				
				/*if($vacc_id!='TT1'):
				
					list($ttbuffer,$tt_num) = explode('TT',$vacc_id);				

					if($this->check_all_antigen($pxid,$vacc)):
				
						$q_check_mc = mysql_query("SELECT a.patient_id,a.actual_vaccine_date,c.patient_edc FROM m_consult_mc_vaccine a,m_consult_mc_prenatal b,m_patient_mc c WHERE a.vaccine_id='TT5' AND a.patient_id='$pxid' AND a.patient_id=c.patient_id AND (TO_DAYS(c.patient_edc)-TO_DAYS(a.actual_vaccine_date)) <= 10000 AND a.actual_vaccine_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' ORDER by a.actual_vaccine_date DESC LIMIT 1") or die(mysql_error());
				
						if(mysql_num_rows($q_check_mc)!=0):
							list($mcid,$vdate,$px_edc) = mysql_fetch_array($q_check_mc);
							$month_stat[$this->get_max_month($vdate)]+=1;
						endif;
				/*	endif;
					
				endif; */

				if(!(in_array($pxid,$arr_px_id))):
					array_push($ttplus_name_px[$this->get_max_month($vacc_date)],array($pxid,'Pregnant Women given TT2 plus','mc',$vacc_date));
					$month_stat[$this->get_max_month($vacc_date)]+=1;
					array_push($arr_px_id,$pxid);
				endif;
			}

			endif;

			array_push($_SESSION["arr_px_labels"]["mc"],$ttplus_name_px);

			break;

		case 4:	//pregnant women who have taken 180 tablets of iron with folic acid throughout the prenancy duration
			
			$iron_name_px = array(1=>array(),2=>array(),3=>array(),4=>array(),5=>array(),6=>array(),7=>array(),8=>array(),9=>array(),10=>array(),11=>array(),12=>array());

			if(in_array('all',$_SESSION[brgy])):
				$get_iron_mc = mysql_query("SELECT distinct a.mc_id,a.patient_id FROM m_consult_mc_services a,m_patient b WHERE a.service_id='IRON' AND a.patient_id=b.patient_id ORDER by a.mc_id ASC, a.actual_service_date ASC") or die("cannot query: 346");
			else:
				$get_iron_mc = mysql_query("SELECT distinct a.mc_id,a.patient_id FROM m_consult_mc_services a,m_family_members b, m_family_address c WHERE a.service_id='IRON' AND a.patient_id=b.patient_id AND b.family_id=c.family_id AND c.barangay_id IN ($brgy_array) ORDER by a.mc_id ASC, a.actual_service_date ASC") or die(mysql_error());				
			endif;

			if(mysql_num_rows($get_iron_mc)!=0): 
					$arr_px_id = array();
				while(list($mcid,$pxid)=mysql_fetch_array($get_iron_mc)){ 
					$iron_total = 0;
					$target_reach = 0; //reset the flag target reach for every mc_id

					$q_mc = mysql_query("SELECT a.service_qty, a.actual_service_date, b.delivery_date FROM m_consult_mc_services a,m_patient_mc b,m_patient c WHERE a.mc_id=b.mc_id AND a.mc_id='$mcid' AND a.service_id='IRON' AND a.actual_service_date BETWEEN b.patient_lmp AND '$_SESSION[edate2]' AND a.actual_service_date<=b.patient_edc AND a.patient_id=c.patient_id ORDER by a.actual_service_date ASC") or die("Cannot query; 277");


					while(list($qty,$serv_date,$delivery_date)=mysql_fetch_array($q_mc)){

						if($delivery_date=='0000-00-00' || ((strtotime($delivery_date) - strtotime($serv_date)) > 0)): 							
						//echo $mcid.'/'.$pxid.'/'.$qty.'/'.$serv_date.'<br>';
							$iron_total+=$qty;
							$s_serv_date = strtotime($serv_date) - strtotime($_SESSION["sdate2"]); //from date of service - minus start date of range
							$e_serv_date = strtotime($_SESSION["edate2"]) - strtotime($serv_date); //from end date minus date of service

							if($iron_total == 180 && $target_reach==0 && $s_serv_date>=0 && $e_serv_date>=0):

								if(!(in_array($pxid,$arr_px_id))):
									$target_reach = 1;
									list($taon,$buwan,$araw) = explode('-',$serv_date);
									$max_date = date("n",mktime(0,0,0,$buwan,$araw,$taon)); //get the unix timestamp then return month without trailing 0

									array_push($iron_name_px[$this->get_max_month($serv_date)],array($pxid,'Pregnant given complete iron with folic acid','mc',$serv_date));

									$month_stat[$max_date]+=1;
								endif;
			
							endif;
						endif;
					}
				}

			
			//print_r($_SESSION["arr_px_labels"]);
			endif;

			array_push($_SESSION["arr_px_labels"]["mc"],$iron_name_px); 

			break;


		case 5: // pregnant given vitamin A

			if(in_array('all',$_SESSION[brgy])):
				$get_vita = mysql_query("SELECT distinct mc_id,patient_id FROM m_consult_mc_services WHERE service_id='VITA' ORDER by mc_id ASC, actual_service_date ASC") or die("Cannot query: 358");
			else:
				$get_vita = mysql_query("SELECT distinct a.mc_id,a.patient_id FROM m_consult_mc_services a,m_family_members b,m_family_address c WHERE a.service_id='VITA' AND a.patient_id=b.patient_id AND b.family_id=c.family_id AND c.barangay_id IN ($brgy_array) ORDER by a.mc_id ASC, a.actual_service_date ASC") or die("Cannot query: 358");	
			endif;

			$vita_name_px = array(1=>array(),2=>array(),3=>array(),4=>array(),5=>array(),6=>array(),7=>array(),8=>array(),9=>array(),10=>array(),11=>array(),12=>array());

			if(mysql_num_rows($get_vita)!=0):
				$arr_px_id = array();

				while(list($mcid,$pxid)=mysql_fetch_array($get_vita)){
					$vit_total = 0;
					$target_reach = 0;
						//$q_mc = mysql_query("SELECT a.service_qty, a.actual_service_date,b.delivery_date FROM m_consult_mc_services a,m_patient_mc b WHERE a.mc_id=b.mc_id AND a.mc_id='$mcid' AND a.service_id='VITA' AND a.actual_service_date BETWEEN b.patient_lmp AND '$_SESSION[edate2]' AND a.actual_service_date <= b.patient_edc ORDER by a.actual_service_date ASC") or die("Cannot query; 277");
						$q_mc = mysql_query("SELECT a.service_qty, a.actual_service_date,b.delivery_date FROM m_consult_mc_services a,m_patient_mc b WHERE a.mc_id=b.mc_id AND a.mc_id='$mcid' AND a.service_id='VITA' AND a.actual_service_date BETWEEN b.patient_lmp AND '$_SESSION[edate2]' AND a.actual_service_date <= b.patient_edc ORDER by a.actual_service_date ASC") or die("Cannot query; 514");

					while(list($qty,$serv_date,$delivery_date)=mysql_fetch_array($q_mc)){
						//echo $delivery_date.' '.$serv_date.' '.$_SESSION[edate2].' '.(strtotime($delivery_date) - strtotime($serv_date)).'<br>';
				
						if($delivery_date=='0000-00-00' || ((strtotime($delivery_date) - strtotime($serv_date)) > 0)):
							$vita_total+=$qty;
							$s_serv_date = strtotime($serv_date) - strtotime($_SESSION["sdate2"]); //from date of service - minus start date of range
							$e_serv_date = strtotime($_SESSION["edate2"]) - strtotime($serv_date); //from end date minus date of service

							if($vita_total == 200000 && $target_reach==0 && $s_serv_date>=0 && $e_serv_date>=0):

								if(!(in_array($pxid,$arr_px_id))):
									$target_reach = 1;
									array_push($vita_name_px[$this->get_max_month($serv_date)],array($pxid,'Pregnant given Vit. A','mc',$serv_date));
									$month_stat[$this->get_max_month($serv_date)]+=1;
									array_push($arr_px_id,$pxid);
								endif;
							endif;
						endif;
					}
				}

			endif;
			
			array_push($_SESSION["arr_px_labels"]["mc"],$vita_name_px); 

			break;
		case 6:    //postpartum women given at with 2PPV
			
			if(in_array('all',$_SESSION[brgy])):
				$q_post = mysql_query("SELECT a.mc_id,a.postpartum_date,b.delivery_date,a.patient_id FROM m_consult_mc_postpartum a, m_patient_mc b WHERE a.mc_id=b.mc_id AND (TO_DAYS(a.postpartum_date)-TO_DAYS(b.delivery_date))<=1") or die("Cannot query: 297"); // get mc_id of patients who visited 24 hours after giving birth			
			else:
				$q_post = mysql_query("SELECT a.mc_id,a.postpartum_date,b.delivery_date,a.patient_id FROM m_consult_mc_postpartum a, m_patient_mc b,m_family_members c, m_family_address d WHERE a.mc_id=b.mc_id AND a.patient_id=c.patient_id AND c.family_id=d.family_id AND d.barangay_id IN ($brgy_array) AND (TO_DAYS(a.postpartum_date)-TO_DAYS(b.delivery_date))<=1") or die("Cannot query: 380"); 
			endif;

			$ppv2_name_px = array(1=>array(),2=>array(),3=>array(),4=>array(),5=>array(),6=>array(),7=>array(),8=>array(),9=>array(),10=>array(),11=>array(),12=>array());

			if(mysql_num_rows($q_post)!=0):
				$arr_px_id = array();

			while(list($mcid,$post_date,$del_date,$pxid)=mysql_fetch_array($q_post)){ //check if the mcid(24-hrs) has 1-week (+3/-3) visit
			   $q_wk = mysql_query("SELECT a.postpartum_date FROM m_consult_mc_postpartum a, m_patient_mc b WHERE a.mc_id='$mcid' AND (TO_DAYS(a.postpartum_date)-TO_DAYS(b.delivery_date)) BETWEEN 4 AND 10 AND a.postpartum_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND a.postpartum_date!='$post_date' ORDER by a.postpartum_date ASC") or die(mysql_error());
				
				
				if(mysql_num_rows($q_wk)!=0):
					list($postdate) = mysql_fetch_array($q_wk);

					if(!(in_array($pxid,$arr_px_id))):
						array_push($ppv2_name_px[$this->get_max_month($postdate)],array($pxid,'Postpartum women with at least 2 PPV','mc',$postdate));					
						$month_stat[$this->get_max_month($postdate)]+=1;
						array_push($arr_px_id,$pxid);
					endif;
				else:

				endif;
			
			} 
			endif;

			array_push($_SESSION["arr_px_labels"]["mc"],$ppv2_name_px); 

			break; 

		case 7://postpartum mothers wih complete iron w/ folic acid intake
			if(in_array('all',$_SESSION[brgy])):
				//$get_iron_mc = mysql_query("SELECT distinct a.mc_id,a.patient_id FROM m_consult_mc_services a, m_patient_mc b WHERE a.service_id='IRON' AND b.postpartum_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' ORDER by a.mc_id ASC, a.actual_service_date ASC") or die("Cannot query: 525 ".mysql_error());
				$get_iron_mc = mysql_query("SELECT distinct a.mc_id,a.patient_id FROM m_consult_mc_services a, m_patient_mc b WHERE a.service_id='IRON' AND a.actual_service_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' ORDER by a.mc_id ASC, a.actual_service_date ASC") or die("Cannot query: 525 ".mysql_error());
			else:
				//$get_iron_mc = mysql_query("SELECT distinct a.mc_id,a.patient_id FROM m_consult_mc_services a,m_family_members b,m_family_address c,m_patient_mc d WHERE a.service_id='IRON' AND a.patient_id=b.patient_id AND b.family_id=c.family_id AND c.barangay_id IN ($brgy_array) AND d.postpartum_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' ORDER by a.mc_id ASC, a.actual_service_date ASC") or die("Cannot query: 527".mysql_error());
				$get_iron_mc = mysql_query("SELECT distinct a.mc_id,a.patient_id FROM m_consult_mc_services a,m_family_members b,m_family_address c,m_patient_mc d WHERE a.service_id='IRON' AND a.patient_id=b.patient_id AND b.family_id=c.family_id AND c.barangay_id IN ($brgy_array) AND a.actual_service_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' ORDER by a.mc_id ASC, a.actual_service_date ASC") or die("Cannot query: 527".mysql_error());
			endif;

			$iron_name_px = array(1=>array(),2=>array(),3=>array(),4=>array(),5=>array(),6=>array(),7=>array(),8=>array(),9=>array(),10=>array(),11=>array(),12=>array());

			if(mysql_num_rows($get_iron_mc)):
				$arr_px_id = array();

				while(list($mcid,$pxid)=mysql_fetch_array($get_iron_mc)){

					$iron_total = 0;
					$target_reach = 0;

				$q_mc = mysql_query("SELECT a.service_qty, a.actual_service_date, b.delivery_date FROM m_consult_mc_services a,m_patient_mc b WHERE a.mc_id=b.mc_id AND a.mc_id='$mcid' AND a.service_id='IRON' AND a.actual_service_date BETWEEN b.delivery_date AND '$_SESSION[edate2]' AND (TO_DAYS(a.actual_service_date)-TO_DAYS(b.delivery_date)) BETWEEN 0 AND 93 AND b.delivery_date!='0000-00-00' ORDER by a.actual_service_date ASC") or die("Cannot query; 277 ".mysql_error());
					
					while(list($qty,$serv_date,$delivery_date)=mysql_fetch_array($q_mc)){
						//echo $mcid.'/'.$qty.'/'.$serv_date.'<br>';
						if((strtotime($serv_date) - strtotime($delivery_date)) >= 0):
							$iron_total+=$qty;
							if($iron_total >= 90 && $target_reach==0):	
								//echo $pxid.'/'.$delivery_date.'/'.$serv_date.'/'.$_SESSION["edate2"].'<br>';

							if(!(in_array($pxid,$arr_px_id))):
								$target_reach = 1;
								array_push($iron_name_px[$this->get_max_month($serv_date)],array($pxid,'Postpartum mothers wih complete iron w/ folic acid intake','mc',$serv_date));
								$month_stat[$this->get_max_month($serv_date)]+=1;
								array_push($arr_px_id,$px_id);
							endif;

							endif;
						endif;
					}
				}

			endif;

			array_push($_SESSION["arr_px_labels"]["mc"],$iron_name_px); 

			break;

		case 8: // postpartum women given vitamin A supplementation
			if(in_array('all',$_SESSION[brgy])):
				//$get_vita = mysql_query("SELECT distinct a.mc_id,a.patient_id FROM m_consult_mc_services a, m_patient_mc b WHERE a.service_id='VITA' AND b.postpartum_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' ORDER by a.mc_id ASC, a.actual_service_date ASC") or die("Cannot query: 358".mysql_error());
				$get_vita = mysql_query("SELECT distinct a.mc_id,a.patient_id FROM m_consult_mc_services a, m_patient_mc b WHERE a.service_id='VITA' AND a.actual_service_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' ORDER by a.mc_id ASC, a.actual_service_date ASC") or die("Cannot query: 358".mysql_error());
			else:
				//$get_vita = mysql_query("SELECT distinct a.mc_id,a.patient_id FROM m_consult_mc_services a,m_family_members b,m_family_address c,m_patient_mc d WHERE a.service_id='VITA' AND a.patient_id=b.patient_id AND b.family_id=c.family_id AND c.barangay_id IN ($brgy_array) AND d.postpartum_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' ORDER by a.mc_id ASC, a.actual_service_date ASC") or die("Cannot query: 558".mysql_error());
				$get_vita = mysql_query("SELECT distinct a.mc_id,a.patient_id FROM m_consult_mc_services a,m_family_members b,m_family_address c,m_patient_mc d WHERE a.service_id='VITA' AND a.patient_id=b.patient_id AND b.family_id=c.family_id AND c.barangay_id IN ($brgy_array) AND a.actual_service_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' ORDER by a.mc_id ASC, a.actual_service_date ASC") or die("Cannot query: 558".mysql_error());
			endif;

			$vita_name_px = array(1=>array(),2=>array(),3=>array(),4=>array(),5=>array(),6=>array(),7=>array(),8=>array(),9=>array(),10=>array(),11=>array(),12=>array());

			if(mysql_num_rows($get_vita)!=0):
			
				$arr_px_id = array();

				while(list($mcid,$pxid)=mysql_fetch_array($get_vita)){
					$vit_total = 0;
					$target_reach = 0;
					$q_mc = mysql_query("SELECT a.service_qty, a.actual_service_date FROM m_consult_mc_services a,m_patient_mc b WHERE a.mc_id=b.mc_id AND a.mc_id='$mcid' AND a.service_id='VITA' AND a.actual_service_date BETWEEN b.delivery_date AND '$_SESSION[edate2]' AND (TO_DAYS(a.actual_service_date)-TO_DAYS(b.delivery_date))<=42 AND b.delivery_date!='0000-00-00' ORDER by a.actual_service_date ASC") or die("Cannot query; 277 ".mysql_error());

					while(list($qty,$serv_date)=mysql_fetch_array($q_mc)){	
						$vita_total+=$qty;

						if($vita_total >= 200000 && $target_reach==0): 
							$target_reach = 1;
							
							if(!(in_array($pxid,$arr_px_id))):
								array_push($vita_name_px[$this->get_max_month($serv_date)],array($pxid,'Postpartum women given Vit. A','mc',$serv_date));
								$month_stat[$this->get_max_month($serv_date)]+=1;
								array_push($arr_px_id,$pxid);
							endif;
							//echo $max_date.'<br>'.$mcid;
						endif;
					}
				}
			endif;

			array_push($_SESSION["arr_px_labels"]["mc"],$vita_name_px); 

			break;

		case 9: //postpartum women initiated breadstfeeding after giving birth
			if(in_array('all',$_SESSION[brgy])):
				$get_post_bfeed = mysql_query("SELECT a.mc_id, a.delivery_date, a.patient_id FROM m_patient_mc a WHERE a.breastfeeding_asap='Y' AND a.delivery_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND a.delivery_date=a.date_breastfed ORDER by a.delivery_date") or die("cannot query: 350");
			else:
				$get_post_bfeed = mysql_query("SELECT a.mc_id, a.delivery_date, a.patient_id FROM m_patient_mc a,m_family_members b, m_family_address c WHERE a.breastfeeding_asap='Y' AND  a.patient_id=b.patient_id AND b.family_id=c.family_id AND c.barangay_id IN ($brgy_array) AND a.delivery_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND a.delivery_date=a.date_breastfed ORDER by a.delivery_date") or die(mysql_error());
			endif;

			$bfed_name_px = array(1=>array(),2=>array(),3=>array(),4=>array(),5=>array(),6=>array(),7=>array(),8=>array(),9=>array(),10=>array(),11=>array(),12=>array());

			if(mysql_num_rows($get_post_bfeed)!=0):
				$arr_px_id = array();
				while(list($mcid,$deldate,$pxid)=mysql_fetch_array($get_post_bfeed)){ //echo $deldate;
						
						if(!(in_array($pxid,$arr_px_id))):
							array_push($bfed_name_px[$this->get_max_month($deldate)],array($pxid,'Postpartum women initiated breastfeeding','mc',$deldate));
							$month_stat[$this->get_max_month($deldate)]+=1;
							array_push($arr_px_id,$pxid);
						endif;
				}

			endif;

			array_push($_SESSION["arr_px_labels"]["mc"],$bfed_name_px); 

			break;

		case 10: //10-49 year old women given vitamin A supplementation. As of per aggrement with Ms. Pinky, 0 this

			$vita_name_px = array(1=>array(),2=>array(),3=>array(),4=>array(),5=>array(),6=>array(),7=>array(),8=>array(),9=>array(),10=>array(),11=>array(),12=>array());

			array_push($_SESSION["arr_px_labels"]["mc"],$vita_name_px);			

			break;

		case 11:   //number of deliveries, all types
			
			if(in_array('all',$_SESSION[brgy])):
				$q_delivery = mysql_query("SELECT mc_id,patient_id,delivery_date,outcome_id FROM m_patient_mc WHERE delivery_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' ORDER by delivery_date ASC") or die("Cannot query 434: ".mysql_error());
			else:
				$q_delivery = mysql_query("SELECT a.mc_id, a.patient_id,a.delivery_date,a.outcome_id FROM m_patient_mc a,m_family_members b, m_family_address c WHERE a.delivery_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND a.patient_id=b.patient_id AND b.family_id=c.family_id AND c.barangay_id IN ($brgy_array) ORDER by delivery_date ASC") or die("Cannot query 436: ".mysql_error());
			endif;

			$delivery_name_px = array(1=>array(),2=>array(),3=>array(),4=>array(),5=>array(),6=>array(),7=>array(),8=>array(),9=>array(),10=>array(),11=>array(),12=>array());

			if(mysql_num_rows($q_delivery)!=0): 
				$arr_px_id = array();

				while(list($mc_id,$pxid,$delivery_date,$outcome_id)=mysql_fetch_array($q_delivery)){
					if(!(in_array($pxid,$arr_px_id))):
						array_push($delivery_name_px[$this->get_max_month($delivery_date)],array($pxid,'Number of Deliveries','mc',$delivery_date));
						$month_stat[$this->get_max_month($delivery_date)]+=1;					
						array_push($arr_px_id,$pxid);
					endif;
				}
			endif;

			array_push($_SESSION["arr_px_labels"]["mc"],$delivery_name_px); 
			
			break;

		case 12:	//number of pregnant women
			$pregnant_name_px = array(1=>array(),2=>array(),3=>array(),4=>array(),5=>array(),6=>array(),7=>array(),8=>array(),9=>array(),10=>array(),11=>array(),12=>array());
			$arr_px_id = array();

			if(in_array('all',$_SESSION[brgy])):
				//$q_pregnant = mysql_query("SELECT DISTINCT a.patient_id, a.mc_id, a.patient_edc, a.delivery_date FROM m_patient_mc a, m_patient b WHERE a.patient_id=b.patient_id AND a.patient_lmp <= '$_SESSION[sdate2]' ORDER by a.patient_edc,a.delivery_date ASC") or die("Cannot query 788: ".mysql_error());
				
				$q_pregnant = mysql_query("SELECT DISTINCT a.patient_id, a.mc_id, a.patient_edc, a.patient_lmp, date_format(c.prenatal_date,'%Y-%m-%d') FROM m_patient_mc a, m_patient b, m_consult_mc_prenatal c WHERE a.patient_id=b.patient_id AND date_format(c.prenatal_date,'%Y-%m-%d') BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND c.visit_sequence='1' AND a.mc_id=c.mc_id ORDER by c.prenatal_date,a.delivery_date ASC") or die("Cannot query 788: ".mysql_error());
			else:
				//$q_pregnant = mysql_query("SELECT DISTINCT a.patient_id, a.mc_id, a.patient_edc,a.delivery_date FROM m_patient_mc a,m_family_members b, m_family_address c,m_patient d WHERE a.patient_id=d.patient_id AND a.patient_lmp <= '$_SESSION[sdate2]' AND a.patient_id=d.patient_id AND b.family_id=c.family_id AND c.barangay_id IN ($brgy_array) ORDER by a.patient_edc,a.delivery_date ASC") or die("Cannot query 790: ".mysql_error());

				$q_pregnant = mysql_query("SELECT DISTINCT a.patient_id, a.mc_id, a.patient_edc,a.patient_lmp,date_format(e.prenatal_date,'%Y-%m-%d') FROM m_patient_mc a,m_family_members b, m_family_address c,m_patient d, m_consult_mc_prenatal e WHERE a.patient_id=d.patient_id AND date_format(e.prenatal_date,'%Y-%m-%d') BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND a.patient_id=d.patient_id AND b.family_id=c.family_id AND c.barangay_id IN ($brgy_array) AND e.visit_sequence='1' AND a.mc_id=e.mc_id ORDER by e.prenatal_date,a.delivery_date ASC") or die("Cannot query 790: ".mysql_error());

			endif;

			if(mysql_num_rows($q_pregnant)!=0):


				while(list($pxid,$mc_id,$edc,$lmp,$first_prenatal)=mysql_fetch_array($q_pregnant)){ 
   				 if(!(in_array($pxid,$arr_px_id))):
				/*	$end_mc_date = '';

					if($delivery_date!='0000-00-00'): 
						$end_mc_date = $delivery_date; 
					else: 						
						$end_mc_date = $edc;
					endif;

					if($end_mc_date >= $_SESSION["edate2"]):
				*/

				$q_prenatal = mysql_query("SELECT DISTINCT mc_id, prenatal_date FROM m_consult_mc_prenatal WHERE patient_id='$pxid' AND mc_id='$mc_id' AND date_format(prenatal_date,'%Y-%m-%d') < '$first_prenatal'") or die("Cannot query 814: ".mysql_error());


						if(mysql_num_rows($q_prenatal)==0):

						$q_px = mysql_query("SELECT patient_id FROM m_patient WHERE patient_id='$pxid'") or die("Cannot query 806: ".mysql_error());
							if(mysql_num_rows($q_px)!=0): 
								array_push($pregnant_name_px[$this->get_max_month($first_prenatal)],array($pxid,'Number of Pregnant Women','mc',$first_prenatal));
								$month_stat[$this->get_max_month($first_prenatal)]+=1;
								array_push($arr_px_id,$pxid); 
							endif;

						endif;
				/*	else:

					endif; */
				endif; 
				}

			endif; 

			//$_SESSION["preggy"] = $arr_px_id;
			array_push($_SESSION["arr_px_labels"]["mc"],$pregnant_name_px);

			break;

		case 13:	//number of pregnant women tested for syphilis
			$syphilis_test_name_px = array(1=>array(),2=>array(),3=>array(),4=>array(),5=>array(),6=>array(),7=>array(),8=>array(),9=>array(),10=>array(),11=>array(),12=>array());
			$arr_preg_syp_test = array();


			/*if(count($_SESSION["preggy"])!=0):
				$arr_px_preg = $_SESSION["preggy"];
				$str_px_preg = implode(',',$arr_px_preg);


			if(in_array('all',$_SESSION[brgy])):
				$q_pregnant = mysql_query("SELECT mc_id, patient_id, patient_edc, delivery_date FROM m_patient_mc WHERE patient_id IN ($str_px_preg) ORDER by patient_edc, delivery_date ASC") or die("Cannot query 835: ".mysql_error());
			else:
				$q_pregnant = mysql_query("SELECT a.mc_id, a.patient_id,a.patient_edc,a.delivery_date FROM m_patient_mc a,m_family_members b, m_family_address c WHERE a.patient_id=b.patient_id AND a.patient_id IN ($str_px_preg) AND b.family_id=c.family_id AND c.barangay_id IN ($brgy_array) ORDER by a.patient_edc, a.delivery_date ASC") or die("Cannot query 436: ".mysql_error());

			endif;
			*/

			//if(mysql_num_rows($q_pregnant)!=0): 

			//	while(list($mc_id,$pxid,$edc,$delivery_date)=mysql_fetch_array($q_pregnant)){ 
					//$q_syphilis = mysql_query("SELECT actual_service_date FROM m_consult_mc_services WHERE patient_id='$pxid' AND service_id='SYP' AND actual_service_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]'") or die("Cannot query 839: ".mysql_error());

					$q_syphilis = mysql_query("SELECT DISTINCT patient_id, actual_service_date FROM m_consult_mc_services WHERE service_id='SYP' AND actual_service_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]'") or die("Cannot query 839: ".mysql_error());
					
					if(mysql_num_rows($q_syphilis)!=0):
						while(list($pxid,$actual_service_date) = mysql_fetch_array($q_syphilis)){
							array_push($syphilis_test_name_px[$this->get_max_month($actual_service_date)],array($pxid,'Number of Pregnant Women Tested for Syphilis','mc',$actual_service_date));
							$month_stat[$this->get_max_month($actual_service_date)]+=1;
							array_push($arr_preg_syp_test,$pxid);
						}
					endif;

			/*	}

			endif; 
			
			endif;
			*/
			$_SESSION["preg_syp_test"] = $arr_preg_syp_test; 
			

			array_push($_SESSION["arr_px_labels"]["mc"],$syphilis_test_name_px);
	
			break;

		case 14:	//number of pregnant women positive for syphillis 
			$syphilis_positive_name_px = array(1=>array(),2=>array(),3=>array(),4=>array(),5=>array(),6=>array(),7=>array(),8=>array(),9=>array(),10=>array(),11=>array(),12=>array());

			$arr_preg_syp_test = $_SESSION["preg_syp_test"];
			$str_preg_syp_test = implode(',',$arr_preg_syp_test); 
			
			
			foreach($arr_preg_syp_test as $key=>$value){
					$q_syphilis_positive = mysql_query("SELECT actual_service_date FROM m_consult_mc_services WHERE patient_id='$value' AND service_id='SYP' AND actual_service_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND syphilis_result='Y'") or die("Cannot query 875: ".mysql_error());	

					if(mysql_num_rows($q_syphilis_positive)!=0):
						list($actual_service_date) = mysql_fetch_array($q_syphilis_positive);
						array_push($syphilis_positive_name_px[$this->get_max_month($actual_service_date)],array($value,'Number of Pregnant Women Positive for Syphilis','mc',$actual_service_date));
						$month_stat[$this->get_max_month($actual_service_date)]+=1;
					endif;
			}

			array_push($_SESSION["arr_px_labels"]["mc"],$syphilis_positive_name_px);

			break;

		case 15:	//number of pregnant women given penicillin
			$penicillin_name_px = array(1=>array(),2=>array(),3=>array(),4=>array(),5=>array(),6=>array(),7=>array(),8=>array(),9=>array(),10=>array(),11=>array(),12=>array());

			//$arr_preg = $_SESSION["preggy"];

			//foreach($arr_preg as $key=>$value){
				$q_penicillin = mysql_query("SELECT patient_id, actual_service_date FROM m_consult_mc_services WHERE service_id='SYP' AND intake_penicillin='Y' AND actual_service_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]'") or die("Cannot query 839: ".mysql_error());

				if(mysql_num_rows($q_penicillin)!=0):
					while(list($pxid,$actual_service_date) = mysql_fetch_array($q_penicillin)){
						array_push($penicillin_name_px[$this->get_max_month($actual_service_date)],array($pxid,'Number of pregnant women given penicillin','mc',$actual_service_date));
						$month_stat[$this->get_max_month($actual_service_date)]+=1;
					}
				endif;
									
			//}

			array_push($_SESSION["arr_px_labels"]["mc"],$penicillin_name_px);

			break;

		default:

			break;

		} // end <switch>
//	} //end <for> months

	return $month_stat; //throw this consolidated array of months
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
		$q_brgy_pop = mysql_query("SELECT SUM(population) FROM m_lib_population WHERE population_year='$taon' AND barangay_id IN ($str)") or die("Cannot query: 372");
	
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

function compute_mc_rate($target,$actual){
        if($target==0):
            return 0;
        else:
            return round((($actual/$target)*100),0);
        endif;
}

function check_all_antigen($pxid,$vacc){

	$arr_vacc = array();

	for($i=0;$i<count($vacc);$i++){
		$q_vacc = mysql_query("SELECT vaccine_id FROM m_consult_mc_vaccine WHERE patient_id='$pxid' AND vaccine_id='$vacc[$i]'") or die("Cannot query 718 ".mysql_error());

		if(mysql_num_rows($q_vacc)!=0):
			list($vaccine_id) = mysql_fetch_array($q_vacc);
			array_push($arr_vacc,$vaccine_id);
		endif;
	}

	if(count($arr_vacc)==5):
		return true;
	else:
		return false;
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
$pdf->SetFont('Arial','',10);
$pdf->AddPage();

/*ini_set("include_path", "/var/www/html/chits/site/Csv/");


$arr_csv = $pdf->show_mc_summary();

if($_GET["form"]=='csv' || $_GET["form"]=='email'):

	$fhsis_csv = fopen("../../site/data_field_efhsis.csv","r");
	
	if($fhsis_csv):

	while(!feof($fhsis_csv)){
		$line = fgets($fhsis_csv,4096);	
		$arr_line = explode(',',$line);
		if($arr_line[0]=='MATERNAL CARE'):
			for($i=1;$i<count($arr_line);$i++){
				if($i==(count($arr_line)-1)):
				$header_csv .= $arr_line[$i];
				else:
					$header_csv .= $arr_line[$i].',';
				endif;
				
				
			}
		endif;
	}

	endif;		

	$mch_csv = implode($arr_csv,',');
	
	$filename = '../../site/csv_dir/'.ereg_replace(' +','',$_SESSION["lgu"]).'_MCH_'.$_SESSION["quarter"].'Q'.$_SESSION["year"].'.csv';
	$filehandle = fopen($filename,'w') or die("file cannot be opened");

	fwrite($filehandle,$header_csv);
	fwrite($filehandle,$mch_csv);
	
	fclose($filehandle);
	
	if($_GET["form"]=='csv'):
		header("location: ".$filename);
	else: */
		/*$subj = $_SESSION["lgu"].' Maternal Care Quarterly Report'.' - '.$_SESSION["quarter"].'Q '.$_SESSION["year"];
		$headers = "From: moncadarhu1@gmail.com\r\nReply-To: moncadarhu1@gmail.com";
		$attachment = chunk_split(base64_encode(file_get_contents($filename)));
		$message = "Attached is the ".$_SESSION["lgu"]." health center maternal care report for ".$_SESSION["quarter"]. "of ".$_SESSION["year"];

		$email_list = fopen("../../site/email_list.txt","r");
		
		while(!feof($email_list)){
			$email_receiver = fgets($email_list,4096);
			$message = ob_get_clean();
			
			$sent_mail = mail($email_receiver,$subj,$message,$headers);
		}

		echo $sent_mail?"Mail sent!":"Mail failed"; */

		
		
/*	endif;
	
//	print_r($_SESSION);
	

else:
	$pdf->Output();
endif; */


$_SESSION["arr_px_labels"] = array('mc'=>array());
$mc_content = $pdf->show_mc_summary();

if($_GET["type"]=='html'):
	$html_tab->create_table($_SESSION["w"],$_SESSION["header"],$mc_content);
elseif($_GET["type"]=='csv'):
	$arr_csv = array(); //


	$csv_creator->create_csv($_SESSION["ques"],$mc_content,'csv');
elseif($_GET["type"]=='efhsis'):
	$csv_creator->create_csv($_SESSION["ques"],$mc_content,'efhsis');
else:
	$pdf->Output();
endif;

?>
