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

	if($_GET["type"]=='consult'):
	    
	    $arr_consults = $_SESSION[arr_consult];
	    
    	    $this->Cell(0,5,'PhilHealth Consultations Report - '.$_SESSION[datanode][name],0,1,'C');
    	    $this->SetFont('Arial','',10);
    	    $this->Cell(0,5,$brgy_label .' ('.$_SESSION["sdate2"]. ' to '. $_SESSION["edate2"].') ',0,1,'C');	
    	    
    	    //$_SESSION["w"] = $w = array(18,34,34,29,22,19,29,34,32,30,60);
	$_SESSION["w"] = $w = array(14,34,34,29,22,19,29,20,32,30,34,34);
    	$_SESSION["header"] = $header = $arr_consults[0];
    	        	    
	else:
    	    $this->Cell(0,5,'PhilHealth Enrollees Masterlist - '.$_SESSION[datanode][name],0,1,'C');
    	    $this->SetFont('Arial','',10);
    	    $this->Cell(0,5,$brgy_label,0,1,'C');	


	    $_SESSION["w"] = $w = array(48,48,48,48,48,48,48); //340
	    $_SESSION["header"] = $header = array('NAME OF MEMBER','STREET,PUROK/SITIO','BARANGAY','DATE OF BIRTH','PHILHEALTH ID','DATE OF EXPIRATION','HOUSEHOLD MEMBERS'."\n".'(* - Potential Dependents)');    	    
        endif;

        $this->SetWidths($w);
        $this->Row($header);
}

 
function show_philhealth_list(){ 
	//print_r($_SESSION["philhealth_id"]);
	$arr_px =  $_SESSION["px_id"];
	$arr_philhealth_record = array();
	//print_r($arr_px);
	
	for($i=0;$i<count($arr_px);$i++){
		$arr_philhealth = array();

	        $relatives = '';

		$q_px = mysql_query("SELECT patient_lastname, patient_firstname, patient_middle, date_format(patient_dob,'%m-%d-%Y') as patient_dob FROM m_patient WHERE patient_id='$arr_px[$i]'") or die("Cannot query 147 ".mysql_error("Cannot query 147" .mysql_error()));

		list($px_lastname,$px_firstname,$px_middle,$px_dob) = mysql_fetch_array($q_px);

		//echo $px_lastname.' '.$px_firstname.' '.$px_dob;
		
		$q_demo = mysql_query("SELECT a.barangay_name,b.address,b.family_id FROM m_lib_barangay a, m_family_address b,m_family_members c WHERE c.patient_id='$arr_px[$i]' AND a.barangay_id=b.barangay_id AND b.family_id=c.family_id") or die("Cannot query 149 ".mysql_error());

		list($brgy_name,$address,$family_id) = mysql_fetch_array($q_demo);

		//echo '<br>'.$family_id.' '.$brgy_name.' '.$address;

		$q_hh = mysql_query("SELECT a.patient_id,b.patient_lastname,b.patient_firstname,round((to_days(now())-to_days(b.patient_dob))/365 , 1) computed_age FROM m_family_members a, m_patient b WHERE a.patient_id!='$arr_px[$i]' AND a.patient_id=b.patient_id AND a.family_id='$family_id'") or die("Cannot query 159 ".mysql_error());

		while(list($pxid,$px_lname,$px_fname,$age) = mysql_fetch_array($q_hh)){
		        $marked = ($age>=60 || $age <= 21)?'*':'';		        
			$relatives .= $px_fname.' '.$px_lname.' ('.$age.')'.$marked.', ';
		}

		$q_philhealth = mysql_query("SELECT philhealth_id,date_format(expiry_date,'%m-%d-%Y') as expiration_date FROM m_patient_philhealth WHERE patient_id='$arr_px[$i]' ORDER by expiry_date ASC") or die("Cannot query 165". mysql_error());
		list($philhealth_id,$expiration) = mysql_fetch_array($q_philhealth);
		
		$arr_philhealth = array($px_lastname.', '.$px_firstname.'  '.$px_middle,$address,$brgy_name,$px_dob,$philhealth_id,$expiration,$relatives);
	
		$this->Row($arr_philhealth);

		array_push($arr_philhealth_record,$arr_philhealth);
	}

	return $arr_philhealth_record; 
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


function get_member_type($pxid){
    $q_philhealth = mysql_query("SELECT DISTINCT(a.patient_id) FROM m_patient_philhealth a, m_family_address b, m_family_members c,m_lib_barangay d, m_patient e WHERE a.patient_id='$pxid' AND a.patient_id=c.patient_id AND c.family_id=b.family_id AND b.barangay_id=d.barangay_id AND a.patient_id=e.patient_id") or die("Cannot query 199 ".mysql_error());                
    if(mysql_num_rows($q_philhealth)!=0):
        
        return 'M';    //return M for member
    else:
        //check if there a co-member that is a philhealth member
        $q_demo = mysql_query("SELECT a.barangay_name,b.address,b.family_id FROM m_lib_barangay a, m_family_address b,m_family_members c WHERE c.patient_id='$pxid' AND a.barangay_id=b.barangay_id AND b.family_id=c.family_id") or die("Cannot query 204 ".mysql_error());
        list($brgy_name,$address,$family_id) = mysql_fetch_array($q_demo);                
        $q_philhealth_family_member = mysql_query("SELECT a.philhealth_id,date_format(a.expiry_date,'%m-%d-%Y'),a.patient_id as expiration_date FROM m_patient_philhealth a, m_family_members b WHERE b.family_id='$family_id' AND b.patient_id=a.patient_id AND a.patient_id!='$pxid' ORDER by expiry_date ASC") or die("Cannot query 208 ".mysql_error());
        
        if(mysql_num_rows($q_philhealth_family_member)!=0):
            list($philhealth_id,$expiration_date,$patient_id) = mysql_fetch_array($q_philhealth_family_member);
            return $philhealth_id;                  // return the philhealth id of the co-member with Philhealth
        else:
            return '';				
        endif;
        
        
    endif;
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


if($_GET["type"]=='consult'): 
    //$philhealth_records = $pdf->show_philhealth_list(); 
    $pdf->show_philhealth_consults();
else:
    //$philhealth_records = $pdf->show_philhealth_list();
    $pdf->show_philhealth_list();
endif;


if($_GET["type"]=='html'):
	//echo "<font color='red'>alisonalisonalisonalisonalisonalisonalisonalisonalisonalisonalisonalisonalison";
	$html_tab->create_table($_SESSION["w"],$_SESSION["header"],$philhealth_records);
else:
	$pdf->Output();
endif;
?>
