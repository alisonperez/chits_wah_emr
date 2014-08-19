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
		$brgy_name = mysql_query("SELECT barangay_name FROM m_lib_barangay WHERE barangay_id='$_SESSION[brgy]'") or die("Cannot query: 124".mysql_error());
		list($brgy_label) = mysql_fetch_array($brgy_name);
	endif;
	
	$this->SetFont('Arial','B',12);

	$this->Cell(0,5,'List of NHTS Registered Households - '.$_SESSION[datanode][name],0,1,'C');
    $this->SetFont('Arial','',10);
    $this->Cell(0,5,$brgy_label,0,1,'C');	


	$_SESSION["w"] = $w = array(68,68,68,68,68); //340
	$_SESSION["header"] = $header = array('HOUSEHOLD NAME','STREET,PUROK/SITIO','BARANGAY','DATE OF NHTS ENROLLMENT','HOUSEHOLD MEMBERS');   
      
    $this->SetWidths($w);
    $this->Row($header);
}

 
function show_nhts_list(){ 

	$arr_family =  array();
	$arr_nhts_record = array();

	$q_family = mysql_query("SELECT family_id, date_enroll FROM m_family_cct_member ORDER by date_enroll DESC") or die("Cannot query 145: ".mysql_error());
	
	while(list($family_id, $date_enroll)=mysql_fetch_array($q_family)){
		$surname = '';
		$members = '';
		$address = '';
		$brgy_name = '';

		$q_household_name = mysql_query("SELECT patient_id FROM m_family_members WHERE family_id='$family_id'") or die("Cannot query 148: ".mysql_error());
		
		if($brgy_name==''):
			$q_address = mysql_query("SELECT address, barangay_id FROM m_family_address WHERE family_id='$family_id'") or die("Cannot query 153: ".mysql_error());
			list($address,$brgy_id) = mysql_fetch_array($q_address);

			$q_brgy = mysql_query("SELECT barangay_name FROM m_lib_barangay WHERE barangay_id='$brgy_id'") or die("Cannot query 156: ".mysql_error());
			list($brgy_name) = mysql_fetch_array($q_brgy);
			

		endif;



		while(list($pxid)=mysql_fetch_array($q_household_name)){
			$q_px = mysql_query("SELECT patient_lastname, patient_firstname FROM  m_patient WHERE patient_id='$pxid'") or die("Cannot query 152: ".mysql_error());

			list($lname,$fname) = mysql_fetch_array($q_px);
			
			if($surname==''):
				$surname = $lname;
			endif;

			$members .= $fname.',';
		}		

		
		$arr_family = array($surname,$address,$brgy_name,$date_enroll,$members);
	
		$this->Row($arr_family);

		array_push($arr_nhts_record,$arr_family);		
	}


	return $arr_nhts_record; 
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



$pdf->show_nhts_list();


if($_GET["type"]=='html'):
	$html_tab->create_table($_SESSION["w"],$_SESSION["header"],$philhealth_records);
else:
	$pdf->Output();
endif;
?>