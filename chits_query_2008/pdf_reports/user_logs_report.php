<?php
    session_start();
    ob_start();

    require('./fpdf/fpdf.php');

    $db_conn = mysql_connect("localhost","$_SESSION[dbuser]","$_SESSION[dbpass]");
    mysql_select_db($_SESSION[dbname]);

    class PDF extends FPDF{
	var $widths;
	var $aligns;
	var $page;
	
	
	function SetWidths($w){
	    //Set the array of column widths
	    $this->widths=$w;
	}

	function SetAligns($a){
	    //Set the array of column alignments
	    $this->aligns=$a;
	}

	
	function Row($data){
	    //Calculate the height of the row
	    $nb=0;
	    for($i=0;$i<count($data);$i++)
	        $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
	    $h=5*$nb;
	    //Issue a page break first if needed
	    $this->CheckPageBreak($h);
	    //Draw the cells of the row
	    for($i=0;$i<count($data);$i++){
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


	function CheckPageBreak($h){
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
    
		while($i<$nb){
		        $c=$s[$i];
	        if($c=="\n"){
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
	        if($l>$wmax){
	            if($sep==-1){
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
      
      function Header(){ //print_r($_SESSION);
          $facility_label = $_SESSION[datanode][name];
          $municipality_label = $_SESSION[lgu];
	  $province_label = $_SESSION[province];

          $this->SetFont('Arial','B','10');
          $this->Cell(0,5,'USER LOGS REPORT FROM '.$_SESSION[usage_sdate].' to '.$_SESSION[usage_edate].' - '.$facility_label.', '.$municipality_label.', '.$province_label,0,1,'C');
         
      }    

     function show_usage($arr_user_log){
	$q_user = mysql_query("SELECT user_firstname, user_lastname FROM game_user WHERE user_id='$_GET[user_id]'")	or die("Cannot query 128: ".mysql_error());
	list($fname,$lname) = mysql_fetch_array($q_user);

	$w = array(40,40,40,40,40);
	$header_content = array('No.','Date','Login Time','Logout Time', 'Total Minutes Logged');

	$this->SetFont('Arial','','8');
        $this->Cell(0,5,'Name of End-User: '.$lname.', '.$fname,0,1,'L');

	$this->SetWidths($w);
	$this->Row($header_content);

	foreach($arr_user_log as $key=>$value){ 
		$q_date_logs = mysql_query("SELECT log_id,login,logout,round((unix_timestamp(logout)-unix_timestamp(login))/60,2) as log_minutes FROM user_logs WHERE log_id='$value' ORDER BY login ASC") or die("Cannot query 251: ".mysql_error());
		list($log_id,$login,$logout,$time_elapsed)=mysql_fetch_array($q_date_logs);
		list($login_date,$login_time) = explode(' ',$login);
		list($logout_date,$logout_time) = explode(' ',$logout);
		
		if($time_elapsed<0):
			$time_elapsed = 0;
		endif;
				
		$count += 1;
		$this->SetFont('Arial','','10');
		$gt_elapsed += $time_elapsed;
		$this->SetWidths($w);
		$this->Row(array($count,$login_date,$login_time,$logout_time,$time_elapsed));
	}

	$this->SetFont('Arial','','8');
	$ave_mins = round(($gt_elapsed/count($arr_user_log)),2);
	$this->Cell(0,5,'Total Logs Made: '.$count.'     Total Minutes Logged: '.$gt_elapsed.'     Average Minutes Per Log: '.$ave_mins,0,1,'L');
	$this->Cell(0,5,'Date/Time Generated: '.date('Y-m-d H:m:s'),0,1,'L');

     }

      function Footer(){
        $this->SetY(-15);
        //Arial italic 8
        $this->SetFont('Arial','I',10);
        //Page number
        $this->Cell(0,10,$this->PageNo(),0,0,'C');
      }

    }

    $pdf=new PDF('P','mm','Legal');
    //Column titles
    //Data loading

    $pdf->SetFont('Arial','',10);
    $pdf->AddPage();

    $arr_user_log = unserialize(stripslashes($_GET["user_log"]));

    $pdf->show_usage($arr_user_log);

    $pdf->Output();
?>