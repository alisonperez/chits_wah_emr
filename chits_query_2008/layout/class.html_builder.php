<?php
// //class would display an html formatted table. entries inside the table can be drilled into patient / household accounts
/* to make this class useable in other QB modules:
1. include('../layout/class.html_builder.php') 
2. create a class instance called $html_tab=new html_builder()
3. save the $w (width) as $_SESSION[w] and header labels as $_SESSION[header]. this is done at the Header function
4. Inside the main function that generates the cell contents, return the array result. 
5. the array result should be saved on an instance in the main function
6. create a conditional statement that would determine what file format is being called
7. pass the arguments $html_tab->create_table($_SESSION["w"],$_SESSION["header"],$demog_records);	
8. the $pdf->Output() line should be able to generate PDF on the fly
*/

class html_builder{

	function html_builder(){
      		$this->appName="HTML Builder";
      		$this->appVersion="0.13";
      		$this->appAuthor="Alison Perez";

		list($this->syear,$this->smonth,$this->sdate) = explode('-',$_SESSION["sdate2"]);
		list($this->eyear,$this->emonth,$this->emonth) = explode('-',$_SESSION["edate2"]); 
		$this->smonth = ltrim($this->smonth,'0'); 
		$this->emonth = ltrim($this->emonth,'0');
	}

	function create_table($width,$header,$cell_contents){

		if(func_num_args()>0):
			$args = func_get_args();
			$width = $args[0];
			$header = $args[1];
			$cell_contents = $args[2];
			$subwidth = $args[3];
			$subheader = $args[4];
		endif;
		
		
		if(sizeof($cell_contents)!=0):
			echo "<table style='font-family: arial; width: 100%; background-color: #CCCCCC'>";
			$this->display_col_header($header,$width);
			$this->display_subheader($subheader,$subwidth);
			$this->display_cell_content($cell_contents,$subwidth);
			echo "</table>";
		else: 
			echo "No data found";
		endif;

		/*print_r($width);
		print_r($header);
		print_r($cell_contents);
		print_r($width);
		print_r($header);
		print_r($cell_contents);
		print_r($width);
		print_r($header);
		print_r($cell_contents);*/
	}

	function display_col_header($header,$width){
		echo "<tr style='background-color: #000000;  color: white; border: 0px; margin: 0px; font-family: Verdana, Geneva, Arial, Helvetica, sans-serif; font-weight: bold; text-align: center; white-space: nowrap; font-size: 19px;'>";
		for($i=0;$i<count($header);$i++){
			if($this->lookup_ques_for_colspan() && $i>=$this->where_to_colspan() && (($this->end_colspan()!=0)?$i<$this->end_colspan():true)):
				$cols = $this->get_colspan();
				echo "<td width='$width[$i]' colspan='$cols'>";
			else:
				echo "<td width='$width[$i]'>";
			endif; 

			echo $header[$i];
			echo "</td>";
		}
		echo "</tr>";
	}

	function display_subheader($subheader,$subwidth){
		echo "<tr style='color: white; background-color: #000000; text-align: center; font-size: 19px;'>";
		for($i=0;$i<count($subheader);$i++){
			//if($this->lookup_ques_for_colspan() && $i>$this->where_to_colspan()):
			//	echo "<td width='$subwidth[$i]' colspan='2'>";
			//else:
				echo "<td width='$subwidth[$i]'>";
			//endif;

			echo $subheader[$i];
			echo "</td>";
		}
		echo "</tr>";
	}

	function display_cell_content($cell_contents,$width){
		$ques = $_SESSION["ques"];
		$q_report_period = mysql_query("SELECT report_type FROM question WHERE ques_id='$ques'") or die("Cannot query: 93");
		list($report_period) = mysql_fetch_array($q_report_period);
		

		$arr_px_labels = $_SESSION["arr_px_labels"];		

		foreach($cell_contents as $key=>$value){ 
			echo "<tr style='background-color: #666666; color: #FFFF66; font-weight:bold; white-space: nowrap; font-size: 19px;'>";

			for($i=0;$i<count($value);$i++){ 
				$arr_names = array();
				
				echo "<td>";

				if(!empty($arr_px_labels)): 
					if($i!=0 && $value[$i]!=0):
						if(isset($arr_px_labels["epi"])):
							$cat = 'epi';
							$arr_names = $this->return_px_names(((($key*2)+$i)-1),$arr_px_labels,$cat);
							$ser_arr_names = serialize($this->return_px_names(((($key*2)+$i)-1),$arr_px_labels,$cat));
							$label = $value[0];

						elseif(isset($arr_px_labels["mc"])):
							$cat = 'mc';
							$arr_names = $this->return_px_names($key,$arr_px_labels,$cat);
							$ser_arr_names = serialize($this->return_px_names($key,$arr_px_labels,$cat)); 
							$label = $value[0];

						elseif(isset($arr_px_labels["fp"])): //echo ((($key*2)+$i)-1); //print_r($arr_px_labels); 
							$cat = 'fp';
							$range = ((($key*5)+$i)-1);
							$arr_names = $this->return_px_names($range,$arr_px_labels,$cat);
							$ser_arr_names = serialize($this->return_px_names($range,$arr_px_labels,$cat)); 

							list($code,$label) = explode(".",$value[0]);
							$label = trim($label);

							switch(($range % 5)){
								case 0:
									$col_type = 'Current User (Begin)';
									break;
								case 1:
									$col_type = 'New Acceptor';
									break;
								case 2:
									$col_type = 'Others (RS,CC,CM)';
									break;
								case 3:
									$col_type = 'Dropout';
									break;
								case 4:
									$col_type = 'Current User (End)';
									break;
								case 6:
									$col_type = '';
									break;
							}		
							$label = $col_type.' '.$label;
						else: 

						endif;

						//echo "<a href='../../site/disp_name.php?id=$ser_arr_names&cat=$value[0]&prog=$cat' target='new'>".$value[$i]."</a>";
						if($report_period=='M'):   //show only edqc links in monthly reports
							echo "<a href='../../site/disp_name.php?id=$ser_arr_names&cat=$label&prog=$cat' target='new'>".$value[$i]."</a>"; 
						else:
							echo $value[$i];
						endif;
						
					else:
						echo $value[$i]; 
					endif;
				else:
					echo $value[$i];
				endif;
				echo "</td>";
			}

			echo "</tr>";
		}
	}

	function return_px_names($cell_num,$arr_px_labels,$prog){ 
		$arr_px_names = array();
		if(count($arr_px_labels)!=0):
			/*
			if($prog=='epi'):	
				foreach($arr_px_labels as $key_prog=>$val_arr){
					foreach($val_arr[$cell_num] as $key2=>$val_arr2){ 
						if($key2>=$this->smonth && $key2<=$this->emonth): 
							foreach($val_arr2 as $key3=>$val_arr3){
								array_push($arr_px_names,$val_arr3[0]);
							}
						endif;
					}	
				}

			elseif($prog=='mc'):  
				foreach($arr_px_labels as $key_prog=>$val_arr){ //print_r($val_arr);
					foreach($val_arr[$cell_num] as $key2=>$val_arr2){ 
						if($key2>=$this->smonth && $key2<=$this->emonth):
							foreach($val_arr2 as $key3=>$val_arr3){
								array_push($arr_px_names,$val_arr3[0]);
							}
						endif;
					}
					
				}
			else: 

			endif; */


			if($prog=='fp'):
				foreach($arr_px_labels as $key_prog=>$val_arr){	
   					foreach($val_arr[$cell_num] as $key2=>$val_arr2){

						array_push($arr_px_names,$val_arr2[0].'*'.$val_arr2[3]); //extract the patient ID and push it to the array
					}
				}

			else: //parsing for mc and epi

				foreach($arr_px_labels as $key_prog=>$val_arr){ 

					foreach($val_arr[$cell_num] as $key2=>$val_arr2){
						if($key2>=$this->smonth && $key2<=$this->emonth): 
							foreach($val_arr2 as $key3=>$val_arr3){
								array_push($arr_px_names,$val_arr3[0].'*'.$val_arr3[3]); //extract the patient ID and push it to the array
							}
						endif;
					}					
				}

			endif; 

		endif;
		$arr_px_names = array_unique($arr_px_names);

		return $arr_px_names;
	}

	function lookup_ques_for_colspan(){
		$arr_with_colspan = array('39','51','70','71','72','73','92','94','90');
		return in_array($_SESSION["ques"],$arr_with_colspan);
	}

	function where_to_colspan(){
		#question_number => rows where colspan would start
		$arr_where_colspan = array('39'=>'2','51'=>'2','70'=>'2','71'=>'2','72'=>'2','73'=>'2','92'=>'1','94'=>'2','90'=>'6');
		return $arr_where_colspan[$_SESSION["ques"]];
	}

	function end_colspan(){
		$arr_end_colspan = array('51'=>'3','90'=>'8');
		if($arr_end_colspan[$_SESSION["ques"]]):
			return $arr_end_colspan[$_SESSION["ques"]];
		else:
			return 0;
		endif;
	}

	function get_colspan(){
		/*switch($_SESSION["ques"]){
			case '51':
				return '3';
				break;
			case '92':
				return '3';
				break;
			case ''
			default:
				return '2';
				break;
		}*/


		//if($_SESSION["ques"]=='51' || $_SESSION["ques"]=='92' || $_SESSION["ques"]=='120' || $_SESSION["ques"]=='121' || $_SESSION["ques"]=='122' || $_SESSION["ques"]=='123'):
		if($_SESSION["ques"]=='51' || $_SESSION["ques"]=='92'):
			return '3';
		elseif($_SESSION["ques"]=='120' || $_SESSION["ques"]=='121' || $_SESSION["ques"]=='122' || $_SESSION["ques"]=='123'):
			return '5';
		else:
			return '2';
		endif;
	}


	function link_builder(){

	}
}

// ?>