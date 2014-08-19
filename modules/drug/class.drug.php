<?
session_start();

class drug extends module {

    // Author: Herman Tolentino MD
    // CHITS Project 2004

    function drug() {
        //
        // do not forget to update version
        //
        $this->author = 'Herman Tolentino MD';
        $this->version = "0.2-".date("Y-m-d");
        $this->module = "drug";
        $this->description = "CHITS Module - Drug Inventory";

    }

    // --------------- STANDARD MODULE FUNCTIONS ------------------

    function init_deps() {
    //
    // insert dependencies in module_dependencies
    //
        module::set_dep($this->module, "module");

    }

    function init_lang() {
    //
    // insert necessary language directives
    //
        module::set_lang("FTITLE_EDUCATION_FORM", "english", "EDUCATION LEVEL FORM", "Y");
        module::set_lang("LBL_EDUC_ID", "english", "EDUCATION LEVEL ID", "Y");
        module::set_lang("LBL_EDUC_NAME", "english", "EDUCATION LEVEL NAME", "Y");
        module::set_lang("FTITLE_EDUCATION_LEVEL__LIST", "english", "EDUCATION LEVEL LIST", "Y");
        module::set_lang("THEAD_ID", "english", "ID", "Y");
        module::set_lang("THEAD_NAME", "english", "NAME", "Y");

    }

    function init_stats() {
    }

    function init_help() {
    }

    function init_menu() {
        // use this for updating menu system
        if (func_num_args()>0) {
            $arg_list = func_get_args();
        }

        // menu entries
        module::set_menu($this->module, "Drug Formulation", "LIBRARIES", "_drug_formulation");
        module::set_menu($this->module, "Drug Preparation", "LIBRARIES", "_drug_preparation");
        module::set_menu($this->module, "Drug Manufacturer", "LIBRARIES", "_drug_manufacturer");
        module::set_menu($this->module, "Drug Source", "LIBRARIES", "_drug_source");
        module::set_menu($this->module, "Drugs", "LIBRARIES", "_drugs");

        // add more detail
        module::set_detail($this->description, $this->version, $this->author, $this->module);

    }

    function init_sql() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
        }

        module::execsql("CREATE TABLE `m_lib_drug_category` (".
            "`cat_id` varchar(10) NOT NULL default '',".
            "`cat_name` varchar(50) NOT NULL default '',".
            "PRIMARY KEY  (`cat_id`)".
            ") TYPE=InnoDB; ");

        // load initial data
        module::execsql("INSERT INTO m_lib_drug_category VALUES ('ABIO','Antibiotics');");
        module::execsql("INSERT INTO m_lib_drug_category VALUES ('AHELM','Anti-helminthic');");
        module::execsql("INSERT INTO m_lib_drug_category VALUES ('AHIST','Antihistamines');");
        module::execsql("INSERT INTO m_lib_drug_category VALUES ('AHPN','Anti-hypertensives');");
        module::execsql("INSERT INTO m_lib_drug_category VALUES ('ANALG','Analgesic/Anti-inflammatory');");
        module::execsql("INSERT INTO m_lib_drug_category VALUES ('ANTITB','Antituberculous Agents');");
        module::execsql("INSERT INTO m_lib_drug_category VALUES ('APYR','Antipyretic');");
        module::execsql("INSERT INTO m_lib_drug_category VALUES ('ASPASM','Antispasmodic Agents');");
        module::execsql("INSERT INTO m_lib_drug_category VALUES ('ASTHMA','Anti-asthmatics');");
        module::execsql("INSERT INTO m_lib_drug_category VALUES ('CONTR','Contraceptive Agents');");
        module::execsql("INSERT INTO m_lib_drug_category VALUES ('EYE','Opthalmic Solutions/Drops');");
        module::execsql("INSERT INTO m_lib_drug_category VALUES ('HYDR','Rehydration Solutions');");
        module::execsql("INSERT INTO m_lib_drug_category VALUES ('VIT','Vitamins');");

        module::execsql("CREATE TABLE `m_lib_drug_formulation` (".
            "`form_id` int(11) NOT NULL auto_increment,".
            "`form_name` varchar(100) NOT NULL default '',".
            "PRIMARY KEY  (`form_id`)".
            ") TYPE=InnoDB; ");

        // load initial data
        module::execsql("INSERT INTO m_lib_drug_formulation VALUES (1,'125mg/5ml x 100ml');");
        module::execsql("INSERT INTO m_lib_drug_formulation VALUES (2,'250mg/5ml x 100ml');");
        module::execsql("INSERT INTO m_lib_drug_formulation VALUES (3,'200mg + 40mg / 5ml x 100ml');");
        module::execsql("INSERT INTO m_lib_drug_formulation VALUES (4,'100mg/ml x 30ml');");
        module::execsql("INSERT INTO m_lib_drug_formulation VALUES (5,'100mg/ml x 60ml');");
        module::execsql("INSERT INTO m_lib_drug_formulation VALUES (6,'100mg/ml x 10ml');");
        module::execsql("INSERT INTO m_lib_drug_formulation VALUES (7,'500mg/tab');");
        module::execsql("INSERT INTO m_lib_drug_formulation VALUES (8,'500mg/cap');");
        module::execsql("INSERT INTO m_lib_drug_formulation VALUES (9,'100,000IU/cap');");
        module::execsql("INSERT INTO m_lib_drug_formulation VALUES (10,'200,000IU/cap');");
        module::execsql("INSERT INTO m_lib_drug_formulation VALUES (11,'800mg + 160mg / tab');");
        module::execsql("INSERT INTO m_lib_drug_formulation VALUES (12,'400mg + 80mg / tab');");
        module::execsql("INSERT INTO m_lib_drug_formulation VALUES (13,'2mg/5ml x 60ml');");
        module::execsql("INSERT INTO m_lib_drug_formulation VALUES (14,'400mg/tab');");
        module::execsql("INSERT INTO m_lib_drug_formulation VALUES (15,'60mg/tab');");
        module::execsql("INSERT INTO m_lib_drug_formulation VALUES (16,'2mg/tab');");
        module::execsql("INSERT INTO m_lib_drug_formulation VALUES (17,'27.9g/sachet (see sachet for details)');");
        module::execsql("INSERT INTO m_lib_drug_formulation VALUES (18,'10,000IU/cap');");
        module::execsql("INSERT INTO m_lib_drug_formulation VALUES (19,'150mg/ml x 1 vial');");
        module::execsql("INSERT INTO m_lib_drug_formulation VALUES (20,'0.3mg norgestrel + 0.03mg ethinyl estradiol/tab x 21 tabs');");
        module::execsql("INSERT INTO m_lib_drug_formulation VALUES (21,'Type 1, R450mg x 7 caps + I300mg x 7 tabs + P500mg x 14 tabs / blister pack');");
        module::execsql("INSERT INTO m_lib_drug_formulation VALUES (22,'Type 2, R450mg x 7 caps + I300mg x 7 tabs / blister pack');");
        module::execsql("INSERT INTO m_lib_drug_formulation VALUES (23,'1gm/vial');");
        module::execsql("INSERT INTO m_lib_drug_formulation VALUES (24,'200mg/5ml x 60ml');");

        module::execsql("CREATE TABLE `m_lib_drug_manufacturer` (".
            "`manufacturer_id` varchar(10) NOT NULL default '',".
            "`manufacturer_name` varchar(50) NOT NULL default '',".
            "PRIMARY KEY  (`manufacturer_id`)".
            ") TYPE=InnoDB;");

        module::execsql("CREATE TABLE `m_lib_drug_preparation` (".
            "`prep_id` varchar(10) NOT NULL default '',".
            "`prep_name` varchar(50) NOT NULL default '',".
            "PRIMARY KEY  (`prep_id`)".
            ") TYPE=MyISAM;");

        // load initial data
        module::execsql("INSERT INTO m_lib_drug_preparation VALUES ('SUSP','Suspension');");
        module::execsql("INSERT INTO m_lib_drug_preparation VALUES ('TAB','Tablet');");
        module::execsql("INSERT INTO m_lib_drug_preparation VALUES ('CAP','Capsule');");
        module::execsql("INSERT INTO m_lib_drug_preparation VALUES ('BPACK','Blister pack');");
        module::execsql("INSERT INTO m_lib_drug_preparation VALUES ('SACH','Sachet');");
        module::execsql("INSERT INTO m_lib_drug_preparation VALUES ('VIAL','Vial');");
        module::execsql("INSERT INTO m_lib_drug_preparation VALUES ('NEB','Nebule/Respule');");

        module::execsql("CREATE TABLE `m_lib_drug_source` (".
            "`source_id` varchar(10) NOT NULL default '',".
            "`source_name` varchar(40) NOT NULL default '',".
            "PRIMARY KEY  (`source_id`)".
            ") TYPE=InnoDB; ");

        // load initial data

        module::execsql("INSERT INTO m_lib_drug_source VALUES ('CDS','DOH');");
        module::execsql("INSERT INTO m_lib_drug_source VALUES ('LGU','LGU');");

        module::execsql("CREATE TABLE `m_lib_drugs` (".
            "`drug_id` varchar(10) NOT NULL default '',".
            "`drug_cat` varchar(10) NOT NULL default '',".
            "`drug_name` varchar(50) NOT NULL default '',".
            "`drug_preparation` varchar(10) NOT NULL default '',".
            "`drug_formulation` varchar(10) NOT NULL default '',".
            "`manufacturer_id` varchar(10) NOT NULL default '',".
            "`drug_source` varchar(10) NOT NULL default '',".
            "PRIMARY KEY  (`drug_id`)".
            ") TYPE=InnoDB;");

    }

    function drop_tables() {

        module::execsql("DROP TABLE `m_lib_drug_category`;");
        module::execsql("DROP TABLE `m_lib_drug_formulation`;");
        module::execsql("DROP TABLE `m_lib_drug_manufacturer`;");
        module::execsql("DROP TABLE `m_lib_drug_preparation`;");
        module::execsql("DROP TABLE `m_lib_drug_source`;");
        module::execsql("DROP TABLE `m_lib_drugs`;");
    }


    // --------------- CUSTOM MODULE FUNCTIONS ------------------

    function _consult_drug() {
    //
    // main submodule for consult drug
    // left panel
    //
        // always check dependencies

        if ($exitinfo = $this->missing_dependencies('drug')) {
            return print($exitinfo);
        }
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        $d = new drug;
        $d->drug_menu($menu_id, $post_vars, $get_vars, $validuser, $isadmin);
        
        if ($post_vars["submitdrug"]) {
            $d->process_dispense_drug($menu_id, $post_vars, $get_vars, $validuser, $isadmin);
        }
        
   		switch($get_vars["drug"]) {
   			case "DISP":
   				$d->form_dispense_drug($menu_id, $post_vars, $get_vars);
   				break;
	        case "PCB":  // Create notes entry for this consult
	            $d->form_pcb_drugs($menu_id, $post_vars, $get_vars);
	            break;
	    }
    }
    
    function get_notes_id(){
    	if (func_num_args()>0) {
            $arg_list = func_get_args();
            $consult = $arg_list[0];
    	}
    	$sql = "select distinct(notes_id) from m_consult_notes_dxclass where consult_id = '$consult' ";
    	if ($result = mysql_query($sql)) {
	    	if (mysql_num_rows($result)) {
	    		list($id) = mysql_fetch_array($result);
	    	}
    	}
    	return $id;
    }
    
	function get_philhealth_id(){
    	if (func_num_args()>0) {
            $arg_list = func_get_args();
            $patient_id = $arg_list[0];
    	}
    	$sql = "select distinct(philhealth_id) from m_patient_philhealth where patient_id = '$patient_id' ";
    	if ($result = mysql_query($sql)) {
	    	if (mysql_num_rows($result)) {
	    		list($id) = mysql_fetch_array($result);
	    	}
    	}
    	return $id;
    }
    
    function process_dispense_drug() {
    	if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        $patient_id = healthcenter::get_patient_id($get_vars["consult_id"]);
        $consult_date = healthcenter::get_consult_date($get_vars["consult_id"]);
		$notes_id = drug::get_notes_id($get_vars["consult_id"]);
		$philhealth_id = drug::get_philhealth_id($patient_id);
	    		
		//$note_link = $this->switch_notes($get_vars["notes"]);
		switch($post_vars["submitdrug"]) {
        	case "Dispense Drugs":
        		if ($notes_id!=null || $notes_id!="")
        		{
        			/*$sql = "insert into m_consult_pcb_drugs (consult_id, notes_id, patient_id, user_id, dispense_timestamp) ".
			                   "values ('".$get_vars["consult_id"]."', '$notes_id', '$patient_id', '".$_SESSION["userid"]."', sysdate())";*/
			    	$sql = "insert into m_consult_pcb_dispense (consult_id, patient_id, user_id, dispense_timestamp, dispense_date) ".
			               "values ('".$get_vars["consult_id"]."', '$patient_id', '".$_SESSION["userid"]."', sysdate(), sysdate())";
		        	//echo $sql;
			    	if ($result = mysql_query($sql)) {
			    		$insert_id = mysql_insert_id();
			           	//header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=NOTES&module=notes&notes=NOTES&notes_id=$insert_id#menu");
						header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DRUGS&module=drug&drug=PCB&drug_id=$insert_id#menu");
			    	}
        		}
        		else 
        		{
        			print "<font color='red'>Please create consult notes <br/>and diagnosis before dispensing.</font>";
        		}
	            break;

	    	case "Dispense":
	    		if ($philhealth_id!=null || $philhealth_id!="")
        		{
		    		if ($post_vars["selectdiag"] && $post_vars["selectDrug"] && $post_vars["quantity"]) {
			    		$sql_select = "select * from m_consult_pcb_drugs WHERE generic_id = '".$post_vars["selectDrug"]."' and consult_id = '".$get_vars["consult_id"]."' and patient_id = '$patient_id'";
			    		if ($result_select = mysql_query($sql_select)) {
			    			if (mysql_num_rows($result_select)) {
			    				$sql = "update m_consult_pcb_drugs set generic_id='".$post_vars["selectDrug"]."', user_id='".$_SESSION["userid"]."', dispense_timestamp=sysdate(), quantity='".$post_vars["quantity"]."', class_id='".$post_vars["selectdiag"]."' where patient_id = '$patient_id' and consult_id = '".$get_vars["consult_id"]."' and generic_id = '".$post_vars["selectDrug"]."'";
			                echo $sql;
			    			}
			    			else
			    			{
			    				$sql = "insert into m_consult_pcb_drugs (dispense_id, generic_id, consult_id, notes_id, patient_id, user_id, dispense_timestamp, quantity, class_id) ".
			                   		   "values ('".$get_vars["drug_id"]."', '".$post_vars["selectDrug"]."', '".$get_vars["consult_id"]."', '$notes_id', '$patient_id', '".$_SESSION["userid"]."', sysdate(), '".$post_vars["quantity"]."', '".$post_vars["selectdiag"]."' )";
			    			}
			    		}
		    		}
		    		//$sql = "insert into m_consult_pcb_drugs (dispense_id, generic_id, consult_id, notes_id, patient_id, user_id, dispense_timestamp, quantity, class_id) ".
		                   //"values ('".$get_vars["drug_id"]."', '".$post_vars["selectDrug"]."', '".$get_vars["consult_id"]."', '$notes_id', '$patient_id', '".$_SESSION["userid"]."', sysdate(), '".$post_vars["quantity"]."', '".$post_vars["selectdiag"]."' )";
	           	 	echo $sql;
		            if ($result = mysql_query($sql)) {
		                $dispense_id = $get_vars["drug_id"];
		                //header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=NOTES&module=notes&notes=NOTES&notes_id=$insert_id#menu");
						header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DRUGS&module=drug&drug=PCB&drug_id=$dispense_id#menu");
		            }
        		}
        		else 
        		{
        			print "<font color='red'>PCB Drug Dispensing is for Philhealth Members Only.</font>";
        		}
	            break;
        }
    }
	
	function form_dispense_drug() {
    //
    // create new notes entry for this consult
    //
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
		
		$sql_drugs = mysql_query("SELECT drug_id FROM m_consult_pcb_drugs WHERE consult_id='$get_vars[consult_id]'") or die("Cannot query: 357");
		
		if(mysql_num_rows($sql_drugs)==0):

        print "<a name='pcb_form'>";
        print "<table width='300'>";
        print "<form method='post' action='#pcb_form' name='pcb_form'>";

	    print "<tr><td>";
		print "<b>DRUG DISPENSE</b><br/>";
		print "<br/></td></tr>";
		print "<tr><td>";
		print "<b>Use this form to create new drug dispensing for this consult. </b><br/>";
		print "<br/></td></tr>";
		print "<tr><td>";
		print "<input type='submit' name='submitdrug' value='Dispense Drugs' class='textbox' style='border: 1px solid black'/> ";
		print "<br/></td></tr>";
		
        print "</form>";
        print "</table><br/>";
		
		else:
			echo "<font color='red' size='2'>Please click the consult date on the right to <br> view details of drug dispensing.</font>";
		endif;
	}
	
	function edit_dispense_drug () {
    	if (func_num_args()>0) {
            $arg_list = func_get_args();
            $post_vars = $arg_list[0];
            $get_vars = $arg_list[1];
        }
        print "<script>
				function change(value)
				{
					document.getElementById(\"drugCategory\").value= value.drugCategory;
					document.getElementById(\"selectDrug\").value= value.selectDrug;
					document.getElementById(\"quantity\").value= value.quantity;
					document.getElementById(\"recordID\").value= value.id;
					document.getElementById(\"add\").value= value.add;
				}
				</script>";
        
        $patient_id = healthcenter::get_patient_id($_GET["consult_id"]);
        $sql = "select drug_id, cat_id, b.record_id, generic_name, quantity from m_consult_pcb_drugs a JOIN m_lib_pcb_drugs b on a.generic_id = b.record_id where dispense_id = '".$_GET["drug_id"]."' order by drug_id desc";
    	
        if ($result = mysql_query($sql)) {
	    	if (mysql_num_rows($result)) {
	    		print "<br/><br/>";
	    		print "<table width='320px' style='border: 1px solid black;border-collapse:collapse'>";
	        		print "<tr class='tinylight'>";
				        print "<th width='170px' style='border: 1px solid black'>Generic Name</th>";
				        print "<th width='70px' style='border: 1px solid black'>Quantity</th>";
				        //print "<th width='50px' style='border: 1px solid black'>Action</th>";
		        	print "</tr>";
	           	while (list($drug_id, $cat_id, $generic_id, $generic_name, $quantity) = mysql_fetch_array($result)) {
	           		$date=date("m/d/Y", strtotime($operation_date));
					print "<tr>";
						print "<td style='border: 1px solid black'>$generic_name</td>";
						print "<td style='border: 1px solid black; text-align:center;'>$quantity</td>";
						//print "<td style='border: 1px solid black; text-align:center;'><input id='edit' type='button' name='edit' value='Edit' onclick=\"change({drugCategory:'$cat_id',selectDrug:'$generic_id',quantity:'$quantity',id:'$drug_id',add:'Update Dispense'});\"></td>";
					print "</tr>";	        		
	        	}
	        	print "</table>";
	    	}
		}
    }
    
    function form_pcb_drugs() {
    	if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        print "<a name='pcb_form'>";
        print "<table width='420px'>";
        print "<form method='post' action='#pcb_form' name='pcb_form'>";
        print "<tr>";
       		print "<td>";
        		print "<b>PCB DRUG DISPENSING</b>";
        	print "</td>";
        print "</tr>";
        /*print "<tr>";
       		print "<td>";
        		print "<br />Prescription Date: <input type='text' name='prescriptiondate' style='width:90px' value=''>";
        		print "&nbsp;<a href=\"javascript:show_calendar4('document.pcb_form.prescriptiondate', document.pcb_form.prescriptiondate.value);\"><img src='../images/cal.gif' width='16' height='16' border='0' alt='Click Here to Pick up the date'></a>";
        	print "</td>";
        print "</tr>";*/
        print "<tr>";
       		print "<td>";
        		print "<br />".drug::select_diagnosis($menu_id, $post_vars, $get_vars);
        	print "</td>";
        print "</tr>";
        print "<tr>";
       		print "<td>";
        		print "<br />Medicine Category: <select id='drugCategory' name='drugCategory' onchange='submit();' onload='submit();'>";
        		print "<option value=''>--Select Category--</option>";
        		$sql_list = "SELECT cat_id, cat_name FROM m_lib_pcb_drug_category";
        		if ($result_list = mysql_query($sql_list)) {
           	 		if (mysql_num_rows($result_list)) {
	           	 		while (list($id, $name) = mysql_fetch_array($result_list)) {
							print "<option value='$id' ".($_POST["drugCategory"]==$id ? 'selected' : '')."> $name</option>";
		                }
           	 		}
        		}
           	 	print "</select>";
           	 	print "<br />";
           	 	//if ($_POST["drugCategory"]!="") {
           	 		print "<br />Generic Name: <select id='selectDrug' name='selectDrug' onchange='submit(); onload='submit();'>";
        			print "<option value=''>--Select Medicine--</option>";
        			if ($_POST["drugCategory"]!="") {
        				$sql_list = "SELECT record_id, generic_name FROM m_lib_pcb_drugs WHERE cat_id = '".$_POST["drugCategory"]."' ORDER BY generic_name ASC";
        			}
	           	 	else {
	           	 		$sql_list = "SELECT record_id, generic_name FROM m_lib_pcb_drugs ORDER BY generic_name ASC";
	           	 	}
	        		if ($result_list = mysql_query($sql_list)) {
	           	 		if (mysql_num_rows($result_list)) {
		           	 		while (list($id, $name) = mysql_fetch_array($result_list)) {
								print "<option value='$id' ".($_POST["selectDrug"]==$id ? 'selected' : '')."> $name</option>";
			                }
	           	 		}
	        		}
	        		print "</select>";
	        		print "<br /><br />";
	        	//}
	        	
	        	//if ($_POST["drugCategory"]!="" && $_POST["selectDrug"]!="") {
					print "Quantity: <input id='quantity' type='text' name='quantity' style='width:50px' value='".($_POST["quantity"]?$_POST["quantity"]:'')."'>";
					print "<br /><br />";
					print "<input id='recordID' type='hidden' name='recordID' value=''>";
					print "<input id='add' type='submit' name='submitdrug' value='Dispense' class='textbox' style='border: 1px solid black'/> ";
	        	//}
        	print drug::edit_dispense_drug()."</td>";
        print "</tr>";
        print "</table>";
        print "</form>";
        
    }
    
    function drug_menu() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        if (!isset($get_vars["drug"])) {
            header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]."&drug=DISP".($get_vars["drug_id"]?"&drug_id=".$get_vars["drug_id"]:"")."#menu");
        }

        
        print "<table cellpadding='1' cellspacing='1' width='300' bgcolor='#9999FF' style='border: 1px solid black'><tr valign='top'><td nowrap>";
        /*print "<a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=NOTES&module=drug&drug=DISP".($get_vars["drug_id"]?"&drug_id=".$get_vars["drug_id"]:"")."' class='groupmenu'>".strtoupper(($get_vars["drug"]=="DISP"?"<b>DISPENSING</b>":"DISPENSING"))."</a>"; */
		//print "<a href=\"$PHP_SELF\">".strtoupper(($get_vars["drug"]=="DISP"?"<b>DISPENSING</b>":"DISPENSING"))."</a>";
		
        print "<a name='menu'></a>";
		print "<a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DRUGS&module=drug&drug=DISP".($get_vars["drug_id"]?"&drug_id=".$get_vars["drug_id"]:"")."' class='groupmenu'>".strtoupper(($get_vars["drug"]=="DISP"?"<b>DISPENSING</b>":"DISPENSING"))."</a>";
        if ($get_vars["drug_id"]) {
        	print "<a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DRUGS&module=drug&drug=PCB&drug_id=".$get_vars["drug_id"]."' class='groupmenu'>".strtoupper(($get_vars["drug"]=="PCB"?"<b>PCB</b>":"PCB"))."</a>";
		}
        print "</td></tr></table><br/>";
    }
	
 	function display_drug_dispense() {
    //
    // lists notes generated for this
    //  consult alone
    //
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
        }
	
		$patient_id = healthcenter::get_patient_id($get_vars["consult_id"]);

        print "<b>DISPENSED DRUGS TODAY</b><br>";
        /*$sql_list = "select notes_id, date_format(notes_timestamp, '%a %d %b %Y, %h:%i%p') ts ".
                    "from m_consult_notes where consult_id = '".$get_vars["consult_id"]."'";
		*/
		$sql_list = "select a.dispense_id, date_format(a.dispense_date, '%a %d %b %Y') ts ".
                    "from m_consult_pcb_dispense a, m_consult b where a.consult_id=b.consult_id AND a.consult_id = '".$get_vars["consult_id"]."'";
        if ($result_list = mysql_query($sql_list)) {
            if (mysql_num_rows($result_list)) {
                while (list($id, $ts) = mysql_fetch_array($result_list)) {
                    echo "<form method='post' name='form_dispense_date'>";
                    print "<img src='../images/arrow_redwhite.gif' border='0'/> ";
                    print "<a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DRUGS&module=drug&drug=PCB&drug_id=$id'>$ts</a>";

                    if($_SESSION["priv_update"]):
                        echo "&nbsp;&nbsp;<input type='submit' name='submitdrug' value='Edit Date' class='tinylight' style='border: 1px solid black'></input><br>";
                        $this->process_dispense_dates($menu_id, $post_vars, $get_vars);
                    endif;
 
                    echo "</form>";

                    if ($get_vars["drug_id"]==$id) {
                    	drug::display_drug_dispense_detail($menu_id, $post_vars, $get_vars);
                    }					
                }
            } else {
                print "<font color='red'>No recorded drugs for this consult.</font><br/>";
            }
        }

    }
    
	function process_dispense_dates(){
		if(func_num_args()>0):
			$arg_list = func_get_args();
			$menu_id = $arg_list[0];
			$post_vars = $arg_list[1];
			$get_vars = $arg_list[2];
		endif;
		
		switch($post_vars["submitdrug"]){
		
		case "Edit Date":
			$q_date = mysql_query("SELECT date_format(dispense_date,'%m/%d/%Y') cons_date FROM m_consult_pcb_dispense WHERE consult_id='$get_vars[consult_id]'") or die("Cannot query: 849");
			
			list($r_date) = mysql_fetch_array($q_date);
			
			echo "<span class='tinylight'>ACTUAL DISPENSE DATE&nbsp;<input type='text' size='8' value='$r_date' name='txt_dispense_date'></input></span>";
			
			echo "&nbsp;<a href=\"javascript:show_calendar4('document.form_dispense_date.txt_consult_date', document.form_dispense_date.txt_dispense_date.value);\"><img src='../images/cal.gif' width='16' height='16' border='0' alt='Click Here to Pick up the date'></a>";

			echo "<br><input type='submit' name='submitdrug' value='Save Date' class='tinylight' style='border: 1px solid black'></input>&nbsp;<input type='button' name='dispense_date_cancel' value='Cancel' class='tinylight' style='border: 1px solid black' onclick='history.go(-1)'></input>";
			break;
		
		case "Save Date":
			
			$patient_id = healthcenter::get_patient_id($get_vars["consult_id"]);
			list($m,$d,$y) = explode('/',$post_vars["txt_dispense_date"]);
			//$date_consult = $y.'-'.$m.'-'.$d.' 00:00:00';
			$date_con = $y.'-'.$m.'-'.$d;

			$q_px = mysql_query("SELECT patient_id FROM m_patient WHERE patient_id='$patient_id' AND (TO_DAYS('$date_con')-TO_DAYS(patient_dob)) >= 0") or die("Cannot query: 865"); 

			if(mysql_num_rows($q_px)==0):
				echo "<font color='red'>Dispense date should be after patient's birthday.</font>";
			else:
				$update_consult_date = mysql_query("UPDATE m_consult_pcb_dispense SET dispense_date='$date_con' WHERE consult_id='$get_vars[consult_id]'") or die("Cannot query: 864");
	            
				header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DRUGS&module=drug&drug=".$get_vars["drug"]."&drug_id=".$get_vars["drug_id"]);

			endif;
			break;

		case "Print Plan":
			//print $_SESSION["plan_details"];
			//print $_POST["notes_id"];
			header("Location: ../chits_query/pdf_reports/prescription.php?notes=$_POST[notes_id]");	

			break;

		default:
			break;
		}
	}
    
	function display_drug_dispense_detail() {
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
        }
        // do some processing here
        /*if ($get_vars["delete_complaint_id"]) {
            if (module::confirm_delete($menu_id, $post_vars, $get_vars)) {
                $sql = "delete from m_consult_notes_complaint ".
                       "where notes_id = '".$get_vars["notes_id"]."' and ".
                       "consult_id = '".$get_vars["consult_id"]."' and ".
                       "complaint_id = '".$get_vars["delete_complaint_id"]."'";
                if ($result = mysql_query($sql)) {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DETAILS&module=notes&notes=".$get_vars["notes"]."&notes_id=".$get_vars["notes_id"]);
                }
            } else {
                if ($post_vars["confirm_delete"]=="No") {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DETAILS&module=notes&notes=".$get_vars["notes"]."&notes_id=".$get_vars["notes_id"]);
                }
            }
        }
        if ($get_vars["delete_class_id"]) {
            if (module::confirm_delete($menu_id, $post_vars, $get_vars)) {
                $sql = "delete from m_consult_notes_dxclass ".
                       "where notes_id = '".$get_vars["notes_id"]."' and ".
                       "consult_id = '".$get_vars["consult_id"]."' and ".
                       "class_id = '".$get_vars["delete_class_id"]."'";
                if ($result = mysql_query($sql)) {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DETAILS&module=notes&notes=".$get_vars["notes"]."&notes_id=".$get_vars["notes_id"]);
                }
            } else {
                if ($post_vars["confirm_delete"]=="No") {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DETAILS&module=notes&notes=".$get_vars["notes"]."&notes_id=".$get_vars["notes_id"]);
                }
            }
        }*/
        // continue with real task
        /*$sql = "select notes_id, consult_id, notes_history, ".
               "notes_physicalexam, notes_plan, user_id, date_format(notes_timestamp, '%a %d %b %Y, %h:%i%p') ts, plan_px_info ".
               "from m_consult_notes where consult_id = '".$get_vars["consult_id"]."' and ".
               "notes_id = '".$get_vars["notes_id"]."'";*/
        $sql = "select *, date_format(dispense_timestamp, '%a %d %b %Y, %h:%i%p') ts  from m_consult_pcb_dispense WHERE consult_id = '".$get_vars["consult_id"]."' and dispense_id = '".$get_vars["drug_id"]."'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                $drug = mysql_fetch_array($result);
		//list($min,$max,$class) = wtforage::_wtforage($_GET["consult_id"]);
	    
                print "<form method='post' action=''>";
                print "<table width='300' cellpadding='2' style='border: 1px dashed black'><tr><td>";
                print "<span class='tinylight'>";
                print "<b>DISPENSE ID:</b> <font color='red'>".module::pad_zero($drug["dispense_id"],7)."</font><br/>";
                print "<b>DATE/TIME:</b> ".$drug["ts"]."<br/>";					
                print "<b>TAKEN BY:</b> ".user::get_username($drug["user_id"])."<br/>";
                
                print "<hr size='1'/>";
                
                print "<b>DIAGNOSIS:</b><br/>";
                drug::show_diagnosis($menu_id, $post_vars, $get_vars);
                print "<br><br><hr size='1'/>";
                print "<b>PLAN:</b><br/>";
                
                $notes_id = drug::get_notes_id($get_vars["consult_id"]);
                $sql_notes = "select notes_id, consult_id, notes_history, ".
		               "notes_physicalexam, notes_plan, user_id, date_format(notes_timestamp, '%a %d %b %Y, %h:%i%p') ts, plan_px_info ".
		               "from m_consult_notes where consult_id = '".$get_vars["consult_id"]."' and ".
		               "notes_id = '$notes_id'";
                
                if ($result_notes = mysql_query($sql_notes)) {
            		if (mysql_num_rows($result_notes)) {
                		$notes = mysql_fetch_array($result_notes);
		                if (strlen($notes["notes_plan"])>=0) {
		                    print stripslashes(nl2br($notes["notes_plan"]))."<br/><br />";
							print stripslashes(nl2br($notes["plan_px_info"]))."<br/>";
		
							$plan = stripslashes(nl2br($notes["notes_plan"]));
							
			                
		                } else {
		                    print "<font color='red'>No recorded plan.</font><br/>";
		                }
            		}
                }
                print "<hr size='1'/>";



                print "<input type='hidden' name='drug_id' value='".$get_vars["drug_id"]."' />";
                if ($_SESSION["priv_delete"]) {
                    print "<input type='submit' name='submitdrug' value='Delete Drugs' class='tinylight' style='border: 1px solid black; background-color: #FF6633;'/> ";
                }
                print "</span>";
                
                print "</td></tr></table><br>";
                print "</form>";
            }
        }
    }
    
    function select_diagnosis() {
    	if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            //print_r($arg_list);
        }
        $notes_id = drug::get_notes_id($get_vars["consult_id"]);
        
        $sql = "select c.class_id, l.class_name ".
               "from m_consult_notes_dxclass c, m_lib_notes_dxclass l ".
               "where c.class_id = l.class_id and ".
               "consult_id = '".$get_vars["consult_id"]."' and notes_id = '$notes_id'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                print "<br />Diagnosis: <select id='selectdiag' name='selectdiag'>";
                print "<option value=''>--Select Diagnosis--</option>";
                while (list($id, $name) = mysql_fetch_array($result)) {
                	print "<option value='$id' ".($_POST["selectdiag"]==$id ? 'selected' : '')."> $name</option>";
                }
                print "</select>";
            } else {
                print "<font color='red'>No diagnosis class recorded</font><br/>";
            }
        }
    }
    
	function show_diagnosis() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            //print_r($arg_list);
        }
        $notes_id = drug::get_notes_id($get_vars["consult_id"]);
        
        $sql = "select c.class_id, l.class_name ".
               "from m_consult_notes_dxclass c, m_lib_notes_dxclass l ".
               "where c.class_id = l.class_id and ".
               "consult_id = '".$get_vars["consult_id"]."' and notes_id = '$notes_id'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                print "<span class='textbox'>";
                while (list($id, $name) = mysql_fetch_array($result)) {
                    print "<img src='../images/arrow_redwhite.gif' border='0'/> $name <br/>";
                    //print "<a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DETAILS&module=notes&notes=".$get_vars["notes"]."&notes_id=".$get_vars["notes_id"]."&delete_class_id=$id'><img src='../images/delete.png' border='0'/></a><br/>";
                }
                print "</span>";
            } else {
                print "<font color='red'>No diagnosis class recorded</font><br/>";
            }
        }
    }
    
    function _details_drug() {
    //
    // main submodule for consult drug
    // right panel
    //
        // always check dependencies
        if ($exitinfo = $this->missing_dependencies('drug')) {
            return print($exitinfo);
        }
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        $d = new drug;
        switch($get_vars["drug"]) {
        	case "DISP":
        	case "PCB":
        		$d->display_drug_dispense($menu_id, $post_vars, $get_vars);
        		break;
        	default:
        		break;
        }
        
    }

// end of class
}
?>
