<?php
session_start();
ob_start();
require('./fpdf/fpdf.php');
require('../layout/class.html_builder.php');

$db_conn = mysql_connect("localhost","$_SESSION[dbuser]","$_SESSION[dbpass]");
mysql_select_db($_SESSION[dbname]);

$html_tab = new html_builder();

class PDF extends FPDF{

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
        	
	if($_SESSION[brgy]=='all'):
		$brgy_label = 'All Barangays';

	else:
		$brgy_name = mysql_query("SELECT barangay_name FROM m_lib_barangay WHERE barangay_id='$_SESSION[brgy]'") or die("Cannot query: 124");
		list($brgy_label) = mysql_fetch_array($brgy_name);
	endif;
	
	$this->SetFont('Arial','B',12);

	if($_GET["type"]=='masterlist'):

	    $arr_consults = $_SESSION[arr_consult];

    	    $this->Cell(0,5,'Laboratory Client Masterlist - '.$_SESSION[datanode][name].', '.$_SESSION[lgu].', '.$_SESSION[province],0,1,'C');
    	    $this->SetFont('Arial','B',10);
    	    $this->Cell(0,5,$brgy_label .' ('.$_SESSION["sdate2"]. ' to '. $_SESSION["edate2"].') ',0,1,'C');	

    	//$_SESSION["w"] = $w = array(18,34,34,29,22,19,29,34,32,30,60);
	$_SESSION["w"] = $w = array(10,42,42,42,42,42,32,42,42);
    	$_SESSION["header"] = $header = array("#","NAME OF CLIENT","STREET/PUROK/SITO","BARANGAY","DATE OF LAB EXAM","TYPE OF EXAM","RESULT","DATE RELEASED","EXAMINED BY");

	else:
            $this->SetFont('Arial','B',12);
    	    $this->Cell(0,5,'Laboratory Service Report - '.$_SESSION[datanode][name].', '.$_SESSION[lgu].', '.$_SESSION[province],0,1,'C');
    	    $this->SetFont('Arial','B',10);
    	    $this->Cell(0,5,'('.$_SESSION["sdate2"]. ' to '. $_SESSION["edate2"].')',0,1,'C');	


	    $_SESSION["w"] = $w = array(56,56,56,56,56,56); //340
	    $_SESSION["header"] = $header = array('BARANGAY','FECALYSIS','HEMATOLOGY','SPUTUM EXAM','URINALYSIS','TOTAL');
	   
        endif;

        $this->SetWidths($w);
        $this->Row($header);
}

 
function show_lab_masterlist(){ 
	$arr_lab_record = array();
	$count = 0;
	if($_SESSION["lab_exam"]=='all'): 
		$q_lab = mysql_query("SELECT request_id, patient_id, date_format(request_timestamp,'%m/%d/%Y') as request_timestamp,  date_format(done_timestamp,'%m/%d/%Y') as done_timestamp,lab_id,done_user_id,request_done FROM m_consult_lab WHERE request_timestamp BETWEEN '$_SESSION[sdate]' AND '$_SESSION[edate]' ORDER by request_timestamp ASC") or die("Cannot query 156: ".mysql_error());
	else:
		$tbl_name = $_SESSION["lab_exam"];
		$q_lab = mysql_query("SELECT a.request_id, a.patient_id, date_format(a.request_timestamp,'%m/%d/%Y') as request_timestamp,  date_format(a.done_timestamp,'%m/%d/%Y'),a.lab_id as done_timestamp,a.done_user_id,request_done FROM m_consult_lab a, $tbl_name b WHERE a.request_id=b.request_id AND a.request_timestamp BETWEEN '$_SESSION[sdate]' AND '$_SESSION[edate]' ORDER by a.request_timestamp ASC") or die("Cannot query 160: ".mysql_error());
	endif;



	while(list($request_id,$pxid,$request_date,$done_date,$lab_id,$done_user_id,$request_done)=mysql_fetch_array($q_lab)){

		$count += 1;
		$q_px = mysql_query("SELECT patient_lastname, patient_firstname, patient_middle, date_format(patient_dob,'%m-%d-%Y') as patient_dob FROM m_patient WHERE patient_id='$pxid'") or die("Cannot query 167 ".mysql_error("Cannot query 147" .mysql_error()));

		list($px_lastname,$px_firstname,$px_middle,$px_dob) = mysql_fetch_array($q_px);

		if($_SESSION["brgy"]=='all'):
			$q_demo = mysql_query("SELECT a.barangay_name,b.address,b.family_id FROM m_lib_barangay a, m_family_address b,m_family_members c WHERE c.patient_id='$pxid' AND a.barangay_id=b.barangay_id AND b.family_id=c.family_id") or die("Cannot query 149 ".mysql_error());
		else:
			$q_demo = mysql_query("SELECT a.barangay_name,b.address,b.family_id FROM m_lib_barangay a, m_family_address b,m_family_members c WHERE c.patient_id='$pxid' AND a.barangay_id=b.barangay_id AND b.family_id=c.family_id AND b.barangay_id='$_SESSION[brgy]'") or die("Cannot query 149 ".mysql_error());
		endif;

		list($brgy_name,$address,$family_id) = mysql_fetch_array($q_demo);

		$q_user_id = mysql_query("SELECT user_firstname, user_lastname FROM game_user WHERE user_id='$done_user_id'") or die("Cannot query 175: ".mysql_error());

		if(mysql_num_rows($q_user_id)!=0):
			list($fname,$lname) = mysql_fetch_array($q_user_id);
			$ngalan = $lname.", ".$fname;
		else:
			$ngalan = '';
		endif;	

		$q_lab_exam = mysql_query("SELECT lab_name FROM m_lib_laboratory WHERE lab_id='$lab_id'") or die("Cannot query 178: ".mysql_error());
		list($lab_name) = mysql_fetch_array($q_lab_exam);

		$result = ($request_done=='Y')?'done':'pending';
		
		if(!empty($family_id)):
			$arr_lab = array($count,$px_lastname.', '.$px_firstname.'  '.$px_middle,$address,$brgy_name,$request_date,$lab_name,$result,$done_date,$ngalan);

			$this->Row($arr_lab);

			array_push($arr_lab_record,$arr_lab);
		endif;

		
		
	} 
	
	return $arr_lab_record;
}

function show_lab_service(){
	$q_brgy = mysql_query("SELECT barangay_id, barangay_name FROM m_lib_barangay ORDER by barangay_id ASC") or die("Cannot query 213: ".mysql_error());
	
	while(list($brgy_id, $brgy_name)=mysql_fetch_array($q_brgy)){
		$gt = 0;

		$get_fecalysis = mysql_query("SELECT COUNT(a.request_id) FROM m_consult_lab_fecalysis a, m_family_members b, m_family_address c WHERE a.release_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND release_flag='Y' AND a.patient_id=b.patient_id AND b.family_id=c.family_id AND c.barangay_id='$brgy_id'") or die("Cannot query 217: ".mysql_error());

		list($fecalysis) = mysql_fetch_array($get_fecalysis);

		$get_urinalysis = mysql_query("SELECT COUNT(a.request_id) FROM m_consult_lab_urinalysis a, m_family_members b, m_family_address c WHERE a.release_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND release_flag='Y' AND a.patient_id=b.patient_id AND b.family_id=c.family_id AND c.barangay_id='$brgy_id'") or die("Cannot query 219: ".mysql_error());

		list($urinalysis) = mysql_fetch_array($get_urinalysis);

		$get_hematology = mysql_query("SELECT COUNT(a.request_id) FROM m_consult_lab_hematology a, m_family_members b, m_family_address c WHERE a.release_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND release_flag='Y' AND a.patient_id=b.patient_id AND b.family_id=c.family_id AND c.barangay_id='$brgy_id'") or die("Cannot query 219: ".mysql_error());

		list($hematology) = mysql_fetch_array($get_hematology);

		$get_sputum = mysql_query("SELECT COUNT(a.request_id) FROM m_consult_lab_sputum a, m_family_members b, m_family_address c WHERE a.sp3_collection_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND release_flag='Y' AND a.patient_id=b.patient_id AND b.family_id=c.family_id AND c.barangay_id='$brgy_id'") or die("Cannot query 219: ".mysql_error());

		list($sputum) = mysql_fetch_array($get_sputum);
		
		$gt = $fecalysis + $urinalysis + $hematology + $sputum;

		$arr_lab = array($brgy_name,$fecalysis,$urinalysis,$hematology,$sputum,$gt);

		$this->Row($arr_lab);

		//echo $brgy_id.'/'.$fecalysis.'/'.$urinalysis.'/'.$hematology.'/'.$sputum.'/'.$gt.'<br>';

		array_push($arr_lab_record,$arr_lab);
	}

	return $arr_lab_record;
}

function Footer(){
    //Position at 1.5 cm from bottom
    $this->SetY(-15);
    //Arial italic 8
    $this->SetFont('Arial','I',8);
    //Page number
    $this->Cell(0,10,$this->PageNo().'/{nb}',0,0,'C');
}

}


$pdf=new PDF('L','mm','Legal');
$pdf->AliasNbPages();
$pdf->SetFont('Arial','',10);
$pdf->AddPage();


if($_GET["type"]=='masterlist'):
    $pdf->show_lab_masterlist();
elseif($_GET["type"]=='service'):
    $pdf->show_lab_service();
else:
    $pdf->show_lab_masterlist();
endif;


if($_GET["type"]=='html'):
	$html_tab->create_table($_SESSION["w"],$_SESSION["header"],$arr_lab_record);
else:
	$pdf->Output();
endif;
?>
