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
		echo "<table border='1'>";
		$this->display_col_header($header);
		$this->display_cell_content($cell_contents);
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

	function display_col_header($header){
		echo "<tr>";
		foreach($header as $key=>$header_label){
			echo "<td>";
			echo $header_label;
			echo "</td>";
		}
		echo "</tr>";

	}

	function display_cell_content($cell_contents){
		
		foreach($cell_contents as $key=>$value){
			echo "<tr>";

			foreach($value as $key2=>$value2){
				echo "<td>";
				echo $value2;
				echo "</td>";
			}

			echo "</td>";
		}

	}
}

?>