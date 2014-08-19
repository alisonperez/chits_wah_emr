<html>
  <head>
    <style type="text/css">
    table {
    	border-collapse: collapse;
    }        
    th,td {
    	border: 1px solid black;
    	padding: 0 0.5em;
    }        
    </style>
  </head>
  <body>
	<?php
	error_reporting(E_ALL ^ E_NOTICE);
	header('Content-type: text/html; charset=utf-8');
	ini_set('max_execution_time', 1360);
	ini_set('memory_limit', '1024M');
	ini_set('max_input_time', 1360);
	ini_set('max_input_nesting_level', 64);
	?>
    <form method='get' action='' name='form_search'>
    	
    <?php
		
		//$excel->read('sample.xls');
		
		
		
		if(isset($_REQUEST['AddtoEMR']) && $_REQUEST['AddtoEMR']=='Add to EMR')
		{
			$rows=$_REQUEST['pin'];
			echo "<table>";
			echo "\t<tr>\n";
				  	echo "\t\t<td>" . $_REQUEST['pin'] . "</td><td>" . $_REQUEST['lname'] . "</td>\n"; // etc...
				  	echo "\t\t<td>" . $_REQUEST['fname'] . "</td><td>" . $_REQUEST['mname'] . "</td>\n";
				  	echo "\t\t<td>" . $_REQUEST['gender'] . "</td>\n";
				  	echo "\t</tr>\n";
			echo "</table>";
		}
		?>
	
	</form>
  </body>
</html>

