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
{	$q_name = mysql_query("SELECT patient_lastname, patient_firstname FROM m_patient WHERE patient_id='$_GET[pxid]'") or die("Cannot query 114: ".mysql_error());
	list($lname,$fname) = mysql_fetch_array($q_name);
	
	$q_brgy = mysql_query("SELECT a.barangay_name,b.address FROM m_lib_barangay a,m_family_address b,m_family_members c WHERE a.barangay_id=b.barangay_id AND b.family_id=c.family_id AND c.patient_id=$_GET[pxid]") or die("Cannot query 116: ".mysql_error());

	if(mysql_num_rows($q_brgy)!=0):
		list($brgy_name,$address) = mysql_fetch_array($q_brgy);
		$str_brgy = '('.$address.', '.$brgy_name.')';
	else:
		$str_brgy = '';
	endif;

	$this->SetFont('Arial','B',13);
	$this->Cell(0,5,$_SESSION[datanode][name],0,1,'C');
	$this->Cell(0,5,'General Consultation Record of '.$lname.', '.$fname.' '.$str_brgy,0,1,'C');
	$this->Cell(0,5,'',0,1,'C');
	$this->SetFont('Arial','B',13);

	$main_width = array(45,45,45,45,45,60,50);
	$main_content = array('Consultation Date','Vital Signs','Chief Complaint','History','Physical Exam','Diagnosis','Treatment Plan');
	
	$this->SetWidths($main_width);
	$this->Row($main_content);
}


function show_consult($arr_consult_id){
	
	$w = array(45,45,45,45,45,60,50);
	$this->SetFont('Arial','',13);

	foreach($arr_consult_id as $key=>$consult_id){
		$str_diag = $complain = '';

		$q_consult = mysql_query("SELECT date_format(consult_date,'%m-%d-%Y') consult_date FROM m_consult WHERE consult_id='$consult_id'") or die("Cannot query 147: ".mysql_error());
		list($consult_date) = mysql_fetch_array($q_consult);

		$q_notes_details = mysql_query("SELECT a.notes_complaint,a.notes_history,a.notes_physicalexam,a.notes_plan FROM m_consult_notes a WHERE a.consult_id='$consult_id'") or die("Cannot query 142: ".mysql_error());
		list($complaint,$history,$pe,$plan) = mysql_fetch_array($q_notes_details);

		$q_notes_vs = mysql_query("SELECT vitals_weight,vitals_temp,vitals_systolic,vitals_diastolic,vitals_heartrate,vitals_resprate,vitals_height,vitals_pulse FROM m_consult_vitals WHERE consult_id='$consult_id'") or die("Cannot query 144: ".mysql_error());

		$q_diag = mysql_query("SELECT a.class_id,b.class_name FROM m_consult_notes_dxclass a, m_lib_notes_dxclass b WHERE a.class_id=b.class_id AND a.consult_id='$consult_id'") or die("Cannot query 153: ".mysql_error());

		while($r_diag = mysql_fetch_array($q_diag)){
			$i++;
			if($i!=mysql_num_rows($q_diag)):
				$str_diag = $str_diag.$r_diag["class_name"].', ';
			else:
				$str_diag = $str_diag.$r_diag["class_name"];
			endif;
			
		}

		$q_complaint = mysql_query("select c.complaint_id, l.complaint_name from m_consult_notes_complaint c, m_lib_complaint l where c.complaint_id = l.complaint_id and consult_id = '$consult_id'") or die("Cannot query 167: ".mysql_error());

		while($r_complain = mysql_fetch_array($q_complaint)){
			$i++;
			if($i!=mysql_num_rows($q_complaint)):	
				$complain .= $r_complain["complaint_name"].', ';
			else:
				$complain .= $r_complain["complaint_name"];
			endif;
		}
		
		list($wt,$temp,$sys,$dias,$heart,$resp,$ht,$pulse) = mysql_fetch_array($q_notes_vs);
		$vs = 'WT:'.$wt.',TEMP:'.$temp.',BP:'.$sys.'/'.$dias.',HR:'.$heart.',RR:'.$resp.',HT:'.$ht.'cm '.',PR:'.$pulse;

		$record_arr = array($consult_date,$vs,$complain,$history,$pe,$str_diag,$plan);

		$this->SetWidths($w);
		$this->Row($record_arr);
	}
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
    $this->Cell(0,10,$this->PageNo().'/{nb}',0,0,'C');
}

}

$pdf=new PDF('L','mm','Legal');
$pdf->AliasNbPages();
$pdf->SetFont('Arial','',10);
$pdf->AddPage();

$arr_consult_id = unserialize(stripslashes($_GET["consult_rec"]));

$pdf->show_consult($arr_consult_id);

$pdf->Output();

?>