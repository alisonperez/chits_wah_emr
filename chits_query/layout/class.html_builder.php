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

		echo "<table border='1'>";
		$this->display_col_header($header,$width);
		$this->display_subheader($subheader,$subwidth);
		$this->display_cell_content($cell_contents,$subwidth);
		echo "</table>";

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
		echo "<tr>";
		for($i=0;$i<count($header);$i++){
			if($this->lookup_ques_for_colspan() && $i>=$this->where_to_colspan()):
				echo "<td width='$width[$i]' colspan='2'>";
			else:
				echo "<td width='$width[$i]'>";
			endif; 

			echo $header[$i];
			echo "</td>";
		}
		echo "</tr>";
	}

	function display_subheader($subheader,$subwidth){
		echo "<tr>";
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
		
		foreach($cell_contents as $key=>$value){
			echo "<tr>";

			for($i=0;$i<count($value);$i++){
				//if($this->lookup_ques_for_colspan() && $i<$this->where_to_colspan()):
				//	echo "<td colspan='2'>";
				//else:
					echo "<td>";
				//endif;

				echo $value[$i];
				echo "</td>";
			}

			echo "</tr>";

		}

	}

	function lookup_ques_for_colspan(){
		$arr_with_colspan = array('39');
		return in_array($_SESSION["ques"],$arr_with_colspan);
	}

	function where_to_colspan(){
		$arr_where_colspan = array('39'=>'2');
		return $arr_where_colspan[$_SESSION["ques"]];
	}
}

?>