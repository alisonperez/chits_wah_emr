<?php
// //class would display an html formatted table. entries inside the table can be drilled into patient / household accounts
class html_builder{

	function html_builder(){


	}

	function create_table($width,$header,$cell_contents){
		echo "<table border='1'>";
		$this->display_col_header($header);
		echo "</table>";

		print_r($width);
		print_r($header);
		print_r($cell_contents);
		print_r($width);
		print_r($header);
		print_r($cell_contents);
		print_r($width);
		print_r($header);
		print_r($cell_contents);
		echo 'alison perez';
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
		foreach($cell_contents as ){

		}

	}
}

?>