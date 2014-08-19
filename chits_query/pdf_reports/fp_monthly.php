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

    $q_pop = mysql_query("SELECT SUM(population) FROM m_lib_population WHERE population_year='$_SESSION[year]'") or die("Cannot query: 123". mysql_error());
    list($population)= mysql_fetch_array($q_pop);

    $this->q_report_header($population);
    $this->Ln(10);

    $this->SetFont('Arial','BI','20');
    $this->Cell(340,10,'F A M I L Y   P L A N N I N G',1,1,C);

    $this->SetFont('Arial','B','12');
    $w = array(88,42,42,42,42,42,42);
    $this->SetWidths($w);
    //$_SESSION["header"] = $label = array('Indicators','Current User '."\n".'(Begin Mo)','New Acceptors','Others','Dropout','Current User'."\n".'(End Mo)');

	$_SESSION["header"] = $label = array('Indicators','Current User '."\n".'(Begin Mo)','New Acceptors'."\n".'(Prev Month)','Others','Dropout','Current User'."\n".'(End Mo)','New Acceptors'."\n".'(Present Month)');

    $this->Row($label);
}

function q_report_header($population){
    $this->SetFont('Arial','B','12');
    $this->Cell(0,5,'FHSIS REPORT FOR THE MONTH: '.date('F',mktime(0,0,0,$_SESSION[smonth],1,0))."          YEAR: ".$_SESSION[year],0,1,L);
    $this->Cell(0,5, 'NAME OF BHS: '.$this->get_brgy(),0,1,L);    
    $this->Cell(0,5, 'MUNICIPALITY/CITY NAME: '.$_SESSION[datanode][name],0,1,L);
    $this->Cell(0,5,'PROVINCE: '.$_SESSION[province],0,1,L);
    $this->Cell(0,5, 'PROJECTED POPULATION OF THE YEAR: '.$population,0,1,L);
}


function show_fp_quarterly(){

    $arr_consolidate = array();

    $arr_method = array('a'=>'FSTRBTL','b'=>'MSV','c'=>'PILLS','d'=>'IUD','e'=>'DMPA','f'=>'NFPCM','g'=>'NFPBBT','h'=>'NFPLAM','i'=>'NFPSDM','j'=>'NFPSTM','k'=>'CONDOM','l'=>'IMPLANT');
    $w = array(88,42,42,42,42,42,42);
    $str_brgy = $this->get_brgy();

    //echo $_SESSION[sdate2].'/'.$_SESSION[edate2];

    foreach($arr_method as $col_code=>$method_code){
        $q_fp = mysql_query("SELECT method_name FROM m_lib_fp_methods WHERE method_id='$method_code'") or die("Cannot query: 151".mysql_error());    
        list($method_name) = mysql_fetch_array($q_fp);

        $cu_prev = $this->get_current_users($_SESSION[sdate2],$_SESSION[edate2],$method_code,$str_brgy,2);
		$true_prev_na = $this->get_current_users($_SESSION[sdate2],$_SESSION[edate2],$method_code,$str_brgy,7);		
        $other_pres = $this->get_current_users($_SESSION[sdate2],$_SESSION[edate2],$method_code,$str_brgy,4);
        $dropout_pres = $this->get_current_users($_SESSION[sdate2],$_SESSION[edate2],$method_code,$str_brgy,5 );
		$prev_na = $this->get_current_users($_SESSION[sdate2],$_SESSION[edate2],$method_code,$str_brgy,6);
        $na_pres = $this->get_current_users($_SESSION[sdate2],$_SESSION[edate2],$method_code,$str_brgy,3);

        //$cu_pres = ($cu_prev + $na_pres + $other_pres) - $dropout_pres;

		$cu_pres = ($cu_prev + $prev_na + $other_pres) - $dropout_pres;				

		//$fp_contents = array($col_code.'. '.$method_name,$cu_prev,$na_pres,$other_pres,$dropout_pres,$cu_pres);
		
		$fp_contents = array($col_code.'. '.$method_name,$cu_prev,$prev_na,$other_pres,$dropout_pres,$cu_pres,$na_pres);
	
		array_push($arr_consolidate,$fp_contents);

        for($x=0;$x<count($fp_contents);$x++){
            $this->Cell($w[$x],6,$fp_contents[$x],'1',0,'L');
        }
        
		$this->Ln();
    }

    return $arr_consolidate;
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

function get_current_users(){
    if(func_num_args()>0):
        $args = func_get_args();
        $start = $args[0];
        $end = $args[1];
        $method = $args[2];
        $brgy = $args[3];
        $col_code = $args[4];

	$s_date = strtotime("-1 month", strtotime($start));
	$s_date = date("Y-m-d",$s_date);

	list($syear,$smonth,$sdate) = explode('-',$s_date);
	
	$edate = date("t",$s_date);

	$e_date = $syear.'-'.$smonth.'-'.$edate;

	/* $s_date and $e_date would contain the first and last day of the previous month */

    endif;
    
    switch($col_code){

        case '2': //this will compute the Current User beginning the Quarter ((NA+Others)-Dropout)prev
            //$q_active_prev = mysql_query("SELECT fp_px_id,patient_id,date_registered FROM m_patient_fp_method WHERE date_registered < '$start' AND method_id='$method' AND NOT EXISTS (SELECT fp_px_id FROM m_patient_fp_method WHERE client_code='NA' AND date_registered BETWEEN '$s_date' AND '$e_date' AND method_id='$method')") or die("Cannot query 198: ".mysql_error());
		    $q_active_prev = mysql_query("SELECT fp_px_id,patient_id,date_registered FROM m_patient_fp_method WHERE date_registered < '$start' AND method_id='$method'") or die("Cannot query 198: ".mysql_error());

		    $q_dropout_prev = mysql_query("SELECT fp_px_id,patient_id,date_registered FROM m_patient_fp_method WHERE date_dropout < '$start' AND drop_out='Y' AND method_id='$method'") or die("Cannot query: 199". mysql_error());

			$q_na_prev = mysql_query("SELECT fp_px_id,patient_id,date_registered FROM m_patient_fp_method WHERE date_registered BETWEEN '$s_date' AND '$e_date' AND client_code='NA' AND method_id='$method'") or die("Cannot query 215 ".mysql_error());

            //echo mysql_num_rows($q_active_prev);

            $arr_active_prev = $this->sanitize_brgy($q_active_prev,$brgy,$method,$col_code);
            $arr_dropout_prev = $this->sanitize_brgy($q_dropout_prev,$brgy,$method,$col_code);
		    $arr_na_prev = $this->sanitize_brgy($q_na_prev,$brgy,$method,$col_code);

            $cu_na_prev = count($arr_na_prev);


            $cu_prev = count($arr_active_prev)-count($arr_dropout_prev);
		    $cu_prev -= $cu_na_prev;


			$arr_cu_prev = $this->get_arr_cu_prev($arr_active_prev,$arr_dropout_prev,$arr_na_prev);
			
			$arr_cu_prev2 = array();

			//echo $method.'/'.count($arr_active_prev).' less '.count($arr_dropout_prev).'='.$diff."<br>";
            //return $cu_prev;
			foreach($arr_cu_prev as $key=>$fp_px_id){
				$q_fp_info = mysql_query("SELECT patient_id, date_registered FROM m_patient_fp_method WHERE fp_px_id='$fp_px_id'");
				list($pxid,$date_reg) = mysql_fetch_array($q_fp_info);
				array_push($arr_cu_prev2,array($pxid,'Current User (Beginning)'.$method,'fp',$date_reg));
			}

			array_push($_SESSION["arr_px_labels"]["fp"],$arr_cu_prev2); 


			if(count($arr_cu_prev2)!=0):
				$_SESSION["fp_cu_prev"] = $arr_cu_prev2; 
			else:
				$_SESSION["fp_cu_prev"] = '';
			endif;

			//return count($arr_cu_prev);
			return $cu_prev;
            
			break;


        case '3':
            $q_na = mysql_query("SELECT fp_px_id,patient_id,date_registered FROM m_patient_fp_method WHERE date_registered BETWEEN '$start' AND '$end' AND client_code='NA' AND method_id='$method'") or die("Cannot query 215 ".mysql_error());

            $arr_na_pres = $this->sanitize_brgy($q_na,$brgy,$method,$col_code);

			$arr_na_pres2 = array();

			foreach($arr_na_pres as $key=>$value){
					$q_fp_info = mysql_query("SELECT patient_id, date_registered FROM m_patient_fp_method WHERE fp_px_id='$value[0]'");
					list($pxid,$date_reg) = mysql_fetch_array($q_fp_info);
					array_push($arr_na_pres2,array($pxid,'New Acceptor '.$method,'fp',$date_reg));
			}
			
			array_push($_SESSION["arr_px_labels"]["fp"],$arr_na_pres2); 

            $cu_na = count($arr_na_pres);

			if(count($arr_na_pres2)!=0):
				$_SESSION["fp_na_pres"] = $arr_na_pres2; 
			else:
				$_SESSION["fp_na_pres"] = '';
			endif;

            return $cu_na;

            break;

        case '4': //cu for others
            $q_others = mysql_query("SELECT fp_px_id,patient_id,date_registered FROM m_patient_fp_method WHERE date_registered BETWEEN '$start' AND '$end' AND client_code!='NA' AND method_id='$method'") or die("Cannot query 235 ".mysql_error());
            $arr_others = $this->sanitize_brgy($q_others,$brgy,$method,$col_code);

			$arr_others2 = array();

			foreach($arr_others as $key=>$value){ 
				$q_fp_info = mysql_query("SELECT patient_id, date_registered FROM m_patient_fp_method WHERE fp_px_id='$value[0]'");
				
				list($pxid,$date_reg) = mysql_fetch_array($q_fp_info);

				array_push($arr_others2,array($pxid,'Others (CC,CM,RS) '.$method,'fp',$date_reg));
			}


            $cu_others = count($arr_others);

			array_push($_SESSION["arr_px_labels"]["fp"],$arr_others2); 

			if(count($arr_others2)!=0):
				$_SESSION["fp_arr_others"] = $arr_others2; 
			else:
				$_SESSION["fp_arr_others"] = '';
			endif;


			return $cu_others;

            break;

        case '5': //dropouts for a given quarter
        
            $q_dropout = mysql_query("SELECT fp_px_id,patient_id,date_registered FROM m_patient_fp_method WHERE date_dropout BETWEEN '$start' AND '$end' AND drop_out='Y' AND method_id='$method'") or die("Cannot query 240 ".mysql_error());
            $arr_dropout_pres = $this->sanitize_brgy($q_dropout,$brgy,$method,$col_code);            

			$arr_dropout_pres2 = array();


			foreach($arr_dropout_pres as $key=>$value){
				$q_fp_info = mysql_query("SELECT patient_id, date_registered FROM m_patient_fp_method WHERE fp_px_id='$value[0]'");
				
				list($pxid,$date_reg) = mysql_fetch_array($q_fp_info);

				array_push($arr_dropout_pres2,array($pxid,'Dropout for '.$method,'fp',$date_reg));
			}

            $dropout_count = count($arr_dropout_pres);
            
			array_push($_SESSION["arr_px_labels"]["fp"],$arr_dropout_pres2); 

			if(count($arr_dropout_pres2)!=0):
				$_SESSION["fp_dropout_pres"] = $arr_dropout_pres2; 
			else:
				$_SESSION["fp_dropout_pres"] = '';
			endif;

			
			return $dropout_count;
            
            break;
        
        case '6': //previous NA of the past period (m/q/a)
	    $q_na_prev = mysql_query("SELECT fp_px_id,patient_id,date_registered FROM m_patient_fp_method WHERE date_registered BETWEEN '$s_date' AND '$e_date' AND client_code='NA' AND method_id='$method'") or die("Cannot query 215 ".mysql_error());
		
	    $arr_na_prev = $this->sanitize_brgy($q_na_prev,$brgy,$method,$col_code);

		$arr_na_prev2 = array();
	
		foreach($arr_na_prev as $key=>$value){ 
				$q_fp_info = mysql_query("SELECT patient_id, date_registered FROM m_patient_fp_method WHERE fp_px_id='$value[0]'");
				
				list($pxid,$date_reg) = mysql_fetch_array($q_fp_info);

				array_push($arr_na_prev2,array($pxid,$col_code.'/'.$method,'fp',$date_reg));
		}


        $cu_na_prev = count($arr_na_prev);

		//array_push($_SESSION["arr_px_labels"]["fp"],$arr_na_prev2); 


		if(count($arr_na_prev2)!=0):
			$_SESSION["fp_na_prev"] = $arr_na_prev2; 
		else:
			$_SESSION["fp_na_prev"] = '';
		endif;


		
		$arr_cu_pres = array();

		$arr_cu_pres = $this->get_arr_cu_pres(); // array will contain the fp_px_ids of the CU (Pres)
		$arr_cu_pres2 = array();
		
		foreach($arr_cu_pres as $key=>$value){
				list($px_id,$date_reg) = explode("*",$value);

				//list($pxid,$date_reg) = mysql_fetch_array($q_fp_info); 
				array_push($arr_cu_pres2,array($px_id,'Current User (End)'.$method_code,'fp',$date_reg));
		}

		array_push($_SESSION["arr_px_labels"]["fp"],$arr_cu_pres2);

		return $cu_na_prev;

	    break;


		case 7: //true previous NA. purpose is just to save the names in the array arr_px_labels
		    $q_na_prev = mysql_query("SELECT fp_px_id,patient_id,date_registered FROM m_patient_fp_method WHERE date_registered BETWEEN '$s_date' AND '$e_date' AND client_code='NA' AND method_id='$method'") or die("Cannot query 215 ".mysql_error());
		
		    $arr_na_prev = $this->sanitize_brgy($q_na_prev,$brgy,$method,$col_code);

			$arr_na_prev2 = array();
	
			foreach($arr_na_prev as $key=>$value){ 
				$q_fp_info = mysql_query("SELECT patient_id, date_registered FROM m_patient_fp_method WHERE fp_px_id='$value[0]'");
				
				list($pxid,$date_reg) = mysql_fetch_array($q_fp_info);
				array_push($arr_na_prev2,array($pxid,'New Acceptor (Prev Month)'.$method,'fp',$date_reg));
			}
			
			$na_prev_count = count($arr_na_prev2);
			array_push($_SESSION["arr_px_labels"]["fp"],$arr_na_prev2);
			
			return $na_prev_count;

			break;

        default:
        break;

    }

}

function get_cpr(){
    if(func_num_args()>0){
        $args = func_get_args();
        $cu = $args[0];
    }
    $target_pop = 0.85;
    $elig_pop = 0.145; 
 
    if(in_array('all',$_SESSION[brgy])):
        $q_pop = mysql_query("SELECT SUM(population) FROM m_lib_population WHERE population_year='$_SESSION[year]'") or die("Cannot query 272 ".mysql_error());
    else:
       $str_brgy = implode(',',$_SESSION[brgy]);
       $q_pop = mysql_query("SELECT SUM(population) FROM m_lib_population WHERE population_year='$_SESSION[year]' AND barangay_id IN ($str_brgy)") or die("Cannot query 275 ".mysql_error());
    endif;
    
    
    
        list($tp) = mysql_fetch_array($q_pop);
        $cpr = ($tp!=0)?(($cu/$tp) * $target_pop * $elig_pop * 100):0;

    return round($cpr,3);
}


function sanitize_brgy(){
    if(func_num_args()>0):
        $args = func_get_args();
        $query = $args[0];
        $brgy = $args[1];    
		$method = $args[2];
		$col_code = $args[3];
    endif;



    $arr_count = array();

	$arr_fp = array(1=>array(),2=>array(),3=>array(),4=>array(),5=>array(),6=>array(),7=>array(),8=>array(),9=>array(),10=>array(),11=>array(),12=>array());
    
    if(mysql_num_rows($query)!=0):
        while($r_query = mysql_fetch_array($query)){
            if($this->get_px_brgy($r_query[patient_id],$brgy)):
                array_push($arr_count,$r_query);
				//array_push($arr_fp[$this->get_max_month($r_query[2])],array($r_query[1],$method.' '.$col_code,'fp',$r_query[2]));				
            endif;
        }
		
	endif;
    
	//array_push($_SESSION["arr_px_labels"]["fp"],$arr_fp); 

    return $arr_count;
}


function get_px_brgy(){

        if(func_num_args()>0):
                $arg_list = func_get_args();
                $pxid = $arg_list[0];
                $str = $arg_list[1];
        endif;

        if($str=='All Barangay'):
	    $q_px = mysql_query("SELECT a.barangay_id FROM m_family_address a, m_family_members b WHERE b.patient_id='$pxid' AND b.family_id=a.family_id") or die("cannot query 389: ".mysql_error());
        else:
	    $str = implode(',',$_SESSION[brgy]);
            $q_px = mysql_query("SELECT a.barangay_id FROM m_family_address a, m_family_members b WHERE b.patient_id='$pxid' AND b.family_id=a.family_id AND a.barangay_id IN ($str)") or die("cannot query 389: ".mysql_error());
        endif;

        if(mysql_num_rows($q_px)!=0):
                return 1;
        else:
                return ;
        endif; 
}

function get_max_month($date){
	list($taon,$buwan,$araw) = explode('-',$date);
	$max_date = date("n",mktime(0,0,0,$buwan,$araw,$taon)); //get the unix timestamp then return month without trailing 0

	return $max_date;
}

function get_arr_cu_prev($arr_active_prev,$arr_dropout_prev,$arr_na_prev){
	$arr_active_prev2 = array();
	$arr_dropout_prev2 = array();
	$arr_na_prev2 = array();

	foreach($arr_active_prev as $key=>$value){ 
		foreach($value as $key2=>$value2){
			if($key2=='fp_px_id'):
				array_push($arr_active_prev2,$value2);
			endif;
		}
	}

	foreach($arr_dropout_prev as $key=>$value){
		foreach($value as $key2=>$value2){
			if($key2=='fp_px_id'):
				array_push($arr_dropout_prev2,$value2);
			endif;
		}
	}

	foreach($arr_na_prev as $key=>$value){
		foreach($value as $key2=>$value2){
			if($key2=='fp_px_id'): 
				array_push($arr_na_prev2,$value2);
			endif;
		}
	}

	$arr_active_prev2 = array_unique($arr_active_prev2);
	$arr_dropout_prev2 = array_unique($arr_dropout_prev2);
	$cu_na_prev2 = array_unique($arr_na_prev2);

	$arr_diff1 = array_diff($arr_active_prev2,$arr_dropout_prev2);
	$arr_diff2 = array_diff($arr_diff1, $arr_na_prev2);
	


	return array_unique($arr_diff2);
}

function get_arr_cu_pres(){
		/*$_SESSION["fp_cu_prev"]
		$_SESSION["fp_na_pres"]
		$_SESSION["fp_arr_others"]
		$_SESSION["fp_dropout_pres"]
		$_SESSION["fp_na_prev"]

		make a function that get the CU of pres using these SESSION vars! */
		
		$main_arr = array(); //merges fp_px_id's of $cu_prev, $prev_na, $other_pres
		$dropout_arr = array();

		//$cu_pres = ($cu_prev + $prev_na + $other_pres) - $dropout_pres;

		//echo count($_SESSION["fp_cu_prev"]).'/'.count($_SESSION["fp_na_prev"]).'/'.count($_SESSION["fp_arr_others"]).'/'.count($_SESSION["fp_dropout_pres"])."<br><br>";

		foreach($_SESSION["fp_cu_prev"] as $key=>$value){
			array_push($main_arr,$value[0].'*'.$value[3]);		
		}

		foreach($_SESSION["fp_na_prev"] as $key=>$value){
			array_push($main_arr,$value[0].'*'.$value[3]);
		}

		foreach($_SESSION["fp_arr_others"] as $key=>$value){
			array_push($main_arr,$value[0].'*'.$value[3]);
		}

		foreach($_SESSION["fp_dropout_pres"] as $key=>$value){
			array_push($dropout_arr,$value[0].'*'.$value[3]);
		}

		$main_arr = array_unique($main_arr);
		$dropout_arr = array_unique($dropout_arr);

		$arr_diff = array_diff($main_arr,$dropout_arr);

		/*print_r($arr_diff);
		echo "<br><br><br>";
		echo count($arr_diff);
		echo "<br><br><br>";*/
		//echo count($arr_diff);
		return $arr_diff;
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

$_SESSION["arr_px_labels"] = array('fp'=>array());
$fp_rec = $pdf->show_fp_quarterly();

//$pdf->AddPage();
//$pdf->show_fp_summary();
if($_GET["type"]=='html'):
	$html_tab->create_table($_SESSION["w"],$_SESSION["header"],$fp_rec);	
elseif($_GET["type"]=='csv'):
	//print_r($fp_rec);
	$csv_creator->create_csv($_SESSION["ques"],$fp_rec,'csv');
elseif($_GET["type"]=='efhsis'):
	$csv_creator->create_csv($_SESSION["ques"],$fp_rec,'efhsis');
else:
	$pdf->Output();
endif;

?>
