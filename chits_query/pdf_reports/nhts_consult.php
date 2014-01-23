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

    $this->Cell(0,5,'PhilHealth Enrollees Masterlist - '.$_SESSION[datanode][name],0,1,'C');
    $this->SetFont('Arial','',10);
    $this->Cell(0,5,$brgy_label,0,1,'C');	


	$_SESSION["w"] = $w = array(48,48,48,48,48,48,48); //340
	$_SESSION["header"] = $header = array('NAME OF MEMBER','STREET,PUROK/SITIO','BARANGAY','DATE OF BIRTH','PHILHEALTH ID','DATE OF EXPIRATION','HOUSEHOLD MEMBERS'."\n".'(* - Potential Dependents)');    	    
    
    $this->SetWidths($w);
    $this->Row($header);
}
 

function show_philhealth_consults(){
    $arr_consults = $_SESSION[arr_consult];

    foreach($arr_consults[1] as $key=>$value){
        foreach($value as $key2=>$value2){
		//echo $value2[0].'<br>';
            $philhealth_id='';
            $mem_type = $this->get_member_type($value2[0]);   //determine if the patient is a philhealth member, dependent or none of the two
            
            if($mem_type=='M'):	//member
                $member += 1;
                $philhealth_id = $value2[6].' / M';
            else:
                if($mem_type!=''): //dependent
                    $dependent += 1;
                    $philhealth_id = $mem_type.' / D';
                else:		// not a member or dependent. therefore, do not display the record
                
                endif;
            endif;
            
            //print_r($value2);
            if(!empty($philhealth_id)):
                $this->Row(array($value2[0],$value2[1],$value2[2],$value2[3],$value2[4],$value2[5],$philhealth_id,$value2[7],$value2[8],$value2[9],$value2[10],$value2[11]));
            endif;                
            
        }                    
    }
    $total = $member + $dependent;
    $this->Ln();
    $this->SetFont('Arial','B',12);
    $this->Cell(0,5,'Members: '. $member,0,1,'L');	
    $this->Cell(0,5,'Dependents: '. $dependent,0,1,'L');	
    $this->Cell(0,5,'Total: '. $total ,0,1,'L');	
    
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


$$pdf->show_nhts_consult();

$pdf->Output();

?>