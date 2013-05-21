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



	$w = array(120);
	$this->SetWidths($w);
	//$this->Row(array($_SESSION["datanode"]["name"]."\n".$_SESSION["lgu"].", ".$_SESSION["province"]));

	$this->SetFont('Arial','BI',17);
	$this->Cell(0,5,'Rx',0,1,'L');
	$this->Cell(0,5,'',0,1,'L');

	$this->SetFont('Arial','B',14);
	
	$this->Row(array($_SESSION["datanode"]["name"]."\n".$_SESSION["barangay_loc"].", ".$_SESSION["lgu"].", ".$_SESSION["province"]));

	$this->Cell(0,5,'',0,1,'L');

	$this->SetFont('Arial','',13);

	$this->Cell(0,5,'Name of Patient: '.$lname.', '.$fname.' '.$str_brgy,0,1,'L');
	$this->Cell(0,5,'Date Prescribed: '.$date_consult,0,1,'L');
	$this->Cell(0,5,'',0,1,'C');

}




function show_prescription($str_plan_details){
	$arr_plan = array();
	$str_plan_details = "<br />".$str_plan_details."<br />";
	$str_plan_details = str_replace("<br />","\r",$str_plan_details);

	array_push($arr_plan,"\n".$str_plan_details."\n\n");

	$this->SetFont('Arial','',15);

	$w = array(120);
	$this->SetWidths($w);
	$this->Row($arr_plan);
}

function smoothdate ($year, $month, $day){
    return sprintf ('%04d', $year) . sprintf ('%02d', $month) . sprintf ('%02d', $day);    
}

function date_difference ($first, $second)
{

    $month_lengths = array (31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);

    $retval = FALSE;

    if (    checkdate($first['month'], $first['day'], $first['year']) &&
            checkdate($second['month'], $second['day'], $second['year'])
        )
    {
        $start = $this->smoothdate ($first['year'], $first['month'], $first['day']);
        $target = $this->smoothdate ($second['year'], $second['month'], $second['day']);
                            
        if ($start <= $target)
        {
            $add_year = 0;
            while ($this->smoothdate ($first['year']+ 1, $first['month'], $first['day']) <= $target)
            {
                $add_year++;
                $first['year']++;
            }
                                                                                                            
            $add_month = 0;
            while ($this->smoothdate ($first['year'], $first['month'] + 1, $first['day']) <= $target)
            {
                $add_month++;
                $first['month']++;
                
                if ($first['month'] > 12)
                {
                    $first['year']++;
                    $first['month'] = 1;
                }
            }
                                                                                                                                                                            
            $add_day = 0;
            while ($this->smoothdate ($first['year'], $first['month'], $first['day'] + 1) <= $target)
            {
                if (($first['year'] % 100 == 0) && ($first['year'] % 400 == 0))
                {
                    $month_lengths[1] = 29;
                }
                else
                {
                    if ($first['year'] % 4 == 0)
                    {
                        $month_lengths[1] = 29;
                    }
                }

                $add_day++;
                $first['day']++;
                if ($first['day'] > $month_lengths[$first['month'] - 1])
                {
                    $first['month']++;
                    $first['day'] = 1;

                    if ($first['month'] > 12)
                    {
                        $first['month'] = 1;
                    }
                }
            }

            $retval = array ('years' => $add_year, 'months' => $add_month, 'days' => $add_day);
        }
    }
    return $retval;
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

$pdf=new PDF('L','mm','Letter');
$pdf->AliasNbPages();
$pdf->SetFont('Arial','',10);
$pdf->AddPage();

$pdf->show_prescription($_SESSION["plan_details"]);

$pdf->Output();

?>
