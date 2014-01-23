<?php
session_start();
ob_start();
require('./fpdf/fpdf.php');

$db_conn = mysql_connect("localhost","$_SESSION[dbuser]","$_SESSION[dbpass]");
mysql_select_db($_SESSION[dbname]);



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

	$q_name = mysql_query("SELECT a.patient_lastname, a.patient_firstname,a.patient_id,date_format(b.notes_timestamp,'%Y-%m-%d') FROM m_patient a, m_consult_notes b WHERE a.patient_id=b.patient_id AND b.notes_id='$_GET[notes]'") or die("Cannot query 114: ".mysql_error());
	list($lname,$fname,$pxid,$date_consult) = mysql_fetch_array($q_name);
	
	$q_brgy = mysql_query("SELECT a.barangay_name,b.address FROM m_lib_barangay a,m_family_address b,m_family_members c WHERE a.barangay_id=b.barangay_id AND b.family_id=c.family_id AND c.patient_id='$pxid'") or die("Cannot query 116: ".mysql_error());

	if(mysql_num_rows($q_brgy)!=0):
		list($brgy_name,$address) = mysql_fetch_array($q_brgy);
		$str_brgy = '('.$address.', '.$brgy_name.')';
	else:
		$str_brgy = '';
	endif;



	$w = array(200);
	$this->SetWidths($w);
	//$this->Row(array($_SESSION["datanode"]["name"]."\n".$_SESSION["lgu"].", ".$_SESSION["province"]));

	$this->SetFont('Arial','BI',17);
	$this->Cell(0,5,'',0,1,'L');

	$this->SetFont('Arial','B',14);
	
	$this->Row(array($_SESSION["datanode"]["name"]."\n".$_SESSION["barangay_loc"].", ".$_SESSION["lgu"].", ".$_SESSION["province"]));

	$this->Cell(0,5,'',0,1,'L');


}




function show_lab_result($str_lab_result){
	$arr_lab = array();

	$arr_lab_result = explode("<br/>",$str_lab_result);
	
	foreach($arr_lab_result as $key=>$value){
		$this->SetFont('Arial','',10);
		$this->Cell(0,5,$value,0,1,'L');		
	}
}

function Footer(){
    //Position at 1.5 cm from bottom
    $this->SetY(-15);
    //Arial italic 8
    $this->SetFont('Arial','I',8);
    //Page number
    //$this->Cell(0,10,$this->PageNo().'/{nb}',0,0,'C');
}

}

$q_process = mysql_query("SELECT esign, name_designation_esign FROM game_user WHERE user_id='$_GET[process]'") or die("Cannot query 170: ".mysql_error());
list($process_esign,$process_designation) = mysql_fetch_array($q_process);

$q_noted = mysql_query("SELECT esign, name_designation_esign FROM game_user WHERE user_id='$_GET[noted]'") or die("Cannot query 170: ".mysql_error());
list($noted_esign,$noted_designation) = mysql_fetch_array($q_noted);

$process_esign = '../'.$process_esign;
$noted_esign = '../'.$noted_esign;

$pdf=new PDF('P','mm','Letter');
$pdf->AliasNbPages();
$pdf->SetFont('Arial','',10);
$pdf->AddPage();

$pdf->show_lab_result($_SESSION["lab_print"]);


//print_r($_GET);
switch($_GET["lab_id"]){
	case 'fecalysis':
		$h = 165;
		$h_designation = 35;
		break;
	case 'urinalysis':
		$h = 350;
		$h_designation = 85;
		break;
	case 'hematology':
		$h = 200;
		$h_designation = 35;
		break;
	default:
		break;


}

$pdf->Image($process_esign,'50',$h,'20','20');
$pdf->Image($noted_esign,'140',$h,'20','20');

$pdf->Ln($h_designation);

$pdf->SetWidths(array(100,100));
$pdf->Row(array($process_designation,$noted_designation));
$pdf->Output();

?>