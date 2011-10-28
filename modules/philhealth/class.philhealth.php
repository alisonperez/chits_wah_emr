<?
class philhealth {

    // Author: Herman Tolentino MD
    // CHITS Project 2004

    function philhealth() {
        //
        // do not forget to update version
        //
        $this->author = 'Herman Tolentino MD';
        $this->version = "0.3-".date("Y-m-d");
        $this->module = "philhealth";
        $this->description = "CHITS Module - Philhealth";
        // 0.2 revised for full integration with healthcenter module
        // 0.3 debugged version
    }

    // --------------- STANDARD MODULE FUNCTIONS ------------------

    function init_deps() {
    //
    // insert dependencies in module_dependencies
    //
        module::set_dep($this->module, "module");
        module::set_dep($this->module, "patient");
        module::set_dep($this->module, "healthcenter");
        module::set_dep($this->module, "family");
        module::set_dep($this->module, "ptgroup");
        module::set_dep($this->module, "lab");
    }

    function init_lang() {
    //
    // insert necessary language directives
    //

        module::set_lang("FTITLE_PHILHEALTH_CARD", "english", "PHILHEALTH CARD FORM", "Y");
        module::set_lang("FTITLE_PHILHEALTH_CARD_REGISTRATION", "english", "PHILHEALTH CARD REGISTRATION", "Y");
        module::set_lang("INSTR_LOOKUP_PATIENT", "english", "INSTRUCTIONS: Look up patient name you want to register by using the form below.", "Y");
        module::set_lang("INSTR_PHILHEALTH_CARD", "english", "INSTRUCTIONS: Fill in the following form with the correct values for this patient.", "Y");
        module::set_lang("FTITLE_PHILHEALTH_RECORD_FOR", "english", "PHILHEALTH RECORD FOR ", "Y");
        module::set_lang("THEAD_PHILHEALTH_NUMBER", "english", "PHILHEALTH ID", "Y");
        module::set_lang("THEAD_EXPIRY_DATE", "english", "EXPIRY DATE", "Y");
        module::set_lang("LBL_EXPIRY_DATE", "english", "EXPIRY DATE", "Y");
        module::set_lang("FTITLE_PHILHEALTH_LAB_LIST", "english", "PHILHEALTH LAB EXAMS", "Y");
        module::set_lang("LBL_EXISTING_LAB_EXAMS", "english", "THE FOLLOWING ARE EXISTING PHILHEALTH ACCREDITED LAB EXAMS", "Y");
        module::set_lang("FTITLE_PHILHEALTH_LAB_FORM", "english", "PHILHEALTH LAB EXAM FORM", "Y");
        module::set_lang("LBL_SELECT_LAB_ID", "english", "SELECT LAB EXAM TO INCLUDE IN PHILHEALTH", "Y");
        module::set_lang("FTITLE_PHILHEALTH_SERVICES_LIST", "english", "PHILHEALTH SERVICES", "Y");
        module::set_lang("FTITLE_PHILHEALTH_SERVICE_FORM", "english", "PHILHEALTH SERVICE FORM", "Y");
        module::set_lang("FTITLE_PHILHEALTH_RECORD", "english", "PHILHEALTH RECORD", "Y");
        module::set_lang("INSTR_PHILHEALTH_LAB", "english", "CHARGEABLE EXAMS TO PHILHEALTH CARD", "Y");
        module::set_lang("INSTR_PHILHEALTH_SERVICE", "english", "CHARGEABLE SERVICES TO PHILHEALTH CARD", "Y");
        module::set_lang("LBL_WHICH_EXAMS_USED", "english", "CHECK WHICH EXAMS WERE USED TODAY", "Y");
        module::set_lang("LBL_WHICH_SERVICES_USED", "english", "CHECK WHICH SERVICES WERE USED TODAY", "Y");
        module::set_lang("FTITLE_PHILHEALTH_LABS", "english", "PHILHEALTH LABS TODAY", "Y");
        module::set_lang("FTITLE_PHILHEALTH_SERVICES", "english", "PHILHEALTH SERVICES TODAY", "Y");
        module::set_lang("FTITLE_RELATIVE_PHILHEALTH_RECORD", "english", "PHILHEALTH ACCT - MEMBER/RELATIVE", "Y");

    }

    function init_menu() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $module_id = $arg_list[0];
        }
        //module::set_menu($this->module, "Philhealth Card", "PATIENTS", "_philhealth_patient");
        module::set_menu($this->module, "Philhealth Report", "STATS", "_philhealth_report");
        module::set_menu($this->module, "Philhealth Labs", "LIBRARIES", "_philhealth_labs");
        module::set_menu($this->module, "Philhealth Services", "LIBRARIES", "_philhealth_services");

        // put in more details
        module::set_detail($this->description, $this->version, $this->author, $this->module);
    }

    function init_stats() {
    }

    function init_help() {
    }

    function init_sql() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $module_id = $arg_list[0];
        }

        module::execsql("CREATE TABLE `m_patient_philhealth` (".
            "`philhealth_id` varchar(50) NOT NULL default '',".
            "`healthcenter_id` varchar(10) NOT NULL default '0',".
            "`patient_id` float NOT NULL default '0',".
            "`philhealth_timestamp` timestamp(14) NOT NULL,".
            "`expiry_date` date NOT NULL default '0000-00-00',".
            "INDEX `key_philhealth_id` (`philhealth_id`), ".
            "PRIMARY KEY  (`patient_id`,`philhealth_id`,`expiry_date`),".
            "FOREIGN KEY (`patient_id`) REFERENCES `m_patient` (`patient_id`) ON DELETE CASCADE".
            ") TYPE=InnoDB; ");

        module::execsql("CREATE TABLE `m_lib_philhealth_services` (".
            "`service_id` varchar(10) NOT NULL default '',".
            "`service_name` varchar(50) NOT NULL default '',".
            "PRIMARY KEY  (`service_id`)".
            ") TYPE=InnoDB; ");

        // load initial data
        module::execsql("INSERT INTO `m_lib_philhealth_services` VALUES ('BPMEAS', 'Regular BP Measurement');");
        module::execsql("INSERT INTO `m_lib_philhealth_services` VALUES ('RECTAL', 'Annual Digital Rectal Exam');");
        module::execsql("INSERT INTO `m_lib_philhealth_services` VALUES ('BODYM', 'Body Measurement');");
        module::execsql("INSERT INTO `m_lib_philhealth_services` VALUES ('BREASTX', 'Periodic Breast Exam');");
        module::execsql("INSERT INTO `m_lib_philhealth_services` VALUES ('SMOKEC', 'Counselling for Smoking Cessation');");
        module::execsql("INSERT INTO `m_lib_philhealth_services` VALUES ('LIFEST', 'Lifestyle Modification');");

        module::execsql("CREATE TABLE `m_lib_philhealth_labs` (".
            "`lab_id` varchar(10) NOT NULL default '',".
            "PRIMARY KEY  (`lab_id`)".
            ") TYPE=InnoDB;");

        // load initial data
        module::execsql("INSERT INTO `m_lib_philhealth_labs` VALUES ('CBC');");
        module::execsql("INSERT INTO `m_lib_philhealth_labs` VALUES ('SPT');");
        module::execsql("INSERT INTO `m_lib_philhealth_labs` VALUES ('CXR');");
        module::execsql("INSERT INTO `m_lib_philhealth_labs` VALUES ('FEC');");
        module::execsql("INSERT INTO `m_lib_philhealth_labs` VALUES ('URN');");
        module::execsql("INSERT INTO `m_lib_philhealth_labs` VALUES ('CCS');");

        module::execsql("CREATE TABLE `m_consult_philhealth_services` (".
            "`consult_id` float NOT NULL default '0',".
            "`patient_id` float NOT NULL default '0',".
            "`philhealth_id` varchar(50) NOT NULL default '',".
            "`service_id` varchar(10) NOT NULL default '',".
            "`user_id` float NOT NULL default '0',".
            "`service_timestamp` timestamp(14) NOT NULL,".
            "PRIMARY KEY  (`consult_id`,`service_id`),".
            "KEY `key_philhealth_id` (`philhealth_id`),".
            "KEY `key_patient` (`patient_id`),".
            "CONSTRAINT `m_consult_philhealth_services_ibfk_1` FOREIGN KEY (`consult_id`) REFERENCES `m_consult` (`consult_id`) ON DELETE CASCADE".
            ") TYPE=InnoDB;");

        module::execsql("CREATE TABLE `m_consult_philhealth_labs` (".
            "`consult_id` float NOT NULL default '0',".
            "`patient_id` float NOT NULL default '0',".
            "`philhealth_id` varchar(50) NOT NULL default '',".
            "`lab_id` varchar(10) NOT NULL default '',".
            "`user_id` float NOT NULL default '0',".
            "`lab_timestamp` timestamp(14) NOT NULL,".
            "PRIMARY KEY  (`consult_id`,`lab_id`),".
            "KEY `key_philhealth_id` (`philhealth_id`),".
            "KEY `key_patient` (`patient_id`),".
            "CONSTRAINT `m_consult_philhealth_labs_ibfk_1` FOREIGN KEY (`consult_id`) REFERENCES `m_consult` (`consult_id`) ON DELETE CASCADE".
            ") TYPE=InnoDB;");

    }

    function drop_tables() {

        module::execsql("DROP TABLE `m_patient_philhealth`;");
        module::execsql("DROP TABLE `m_lib_philhealth_services`;");
        module::execsql("DROP TABLE `m_lib_philhealth_labs`;");
        module::execsql("DROP TABLE `m_consult_philhealth_services`;");
        module::execsql("DROP TABLE `m_consult_philhealth_labs`;");

    }

    // --------------- CUSTOM MODULE FUNCTIONS ------------------

    function _consult_philhealth() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        // always check dependencies
        if ($exitinfo = $this->missing_dependencies('philhealth')) {
            return print($exitinfo);
        }
        $p = new philhealth;
        $p->philhealth_menu($menu_id, $post_vars, $get_vars);
        switch($get_vars["philhealth"]) {
        case "CARD":
            if ($post_vars["submitcard"]) {
                $p->process_card($menu_id, $post_vars, $get_vars);
            }
            $p->form_philhealth($menu_id, $post_vars, $get_vars);
            break;
        case "LABS":
            if ($post_vars["submitlab"]) {
                $p->process_consult_lab($menu_id, $post_vars, $get_vars);
            }
            $p->form_consult_lab($menu_id, $post_vars, $get_vars);
            break;
        case "SVC":
            if ($post_vars["submitservice"]) {
                $p->process_consult_services($menu_id, $post_vars, $get_vars);
            }
            $p->form_consult_service($menu_id, $post_vars, $get_vars);
            break;
        }

    }

    function form_consult_service() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
        }
        print "<table width='300'>";
        print "<form action = '".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DETAILS&module=philhealth&philhealth=SVC' name='form_consult_service' method='post'>";
        print "<tr valign='top'><td>";
        print "<b>".INSTR_PHILHEALTH_SERVICE."</b><br/><br/>";
        // does member have philhealth id?
        $patient_id = healthcenter::get_patient_id($get_vars["consult_id"]);
        $patient_name = patient::get_name($patient_id);
        $philhealth_id = philhealth::get_philhealth_id($patient_id);
        if (!$philhealth_id) {
            // try to see if any family member has a philhealth id
            $family_id = family::get_family_id($patient_id);
            $philhealth_info = philhealth::whois_philhealth_member($family_id);
            $patient_name = $philhealth_info["patient_lastname"].", ".$philhealth_info["patient_firstname"];
            $philhealth_id = $philhealth_info["philhealth_id"];
        }
        if ($philhealth_id && $patient_name) {
            print "BENEFICIARY: <b>$patient_name</b><br/>";
            print "PHILHEALTH ID: <b>$philhealth_id</b><br/><br/>";
            print "<input type='hidden' name='philhealth_id' value='$philhealth_id'/>";
        } else {
            print "<font color='red'>No PHILHEALTH membership found.</font><br/>";
        }
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_WHICH_SERVICES_USED."</span><br> ";
        print philhealth::checkbox_philhealth_services();
        print "<br/></td></tr>";
        print "<tr><td>";
        if ($_SESSION["priv_add"]) {
            print "<input type='submit' value = 'Save Data' class='textbox' name='submitservice' style='border: 1px solid #000000'><br> ";
        }
        print "</td></tr>";
        print "</form>";
        print "</table><br>";
    }

    function form_consult_lab() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
        }
        print "<table width='300'>";
        print "<form action = '".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DETAILS&module=philhealth&philhealth=LABS' name='form_consult_lab' method='post'>";
        print "<tr valign='top'><td>";
        print "<b>".INSTR_PHILHEALTH_LAB."</b><br/><br/>";
        // does member have philhealth id?
        $patient_id = healthcenter::get_patient_id($get_vars["consult_id"]);
        $patient_name = patient::get_name($patient_id);
        $philhealth_id = philhealth::get_philhealth_id($patient_id);
        if (!$philhealth_id) {
            // try to see if any family member has a philhealth id
            $family_id = family::get_family_id($patient_id);
            $philhealth_info = philhealth::whois_philhealth_member($family_id);
            $patient_name = $philhealth_info["patient_lastname"].", ".$philhealth_info["patient_firstname"];
            $philhealth_id = $philhealth_info["philhealth_id"];
        }
        if ($philhealth_id && $patient_name) {
            print "BENEFICIARY: <b>$patient_name</b><br/>";
            print "PHILHEALTH ID: <b>$philhealth_id</b><br/>";
            print "EXPIRY: <b>".philhealth::get_expiry_date($philhealth_id)."</b><br/><br/>";
            print "<input type='hidden' name='philhealth_id' value='$philhealth_id'/>";
        } else {
            print "<font color='red'>No PHILHEALTH membership found.</font><br/>";
        }
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_WHICH_EXAMS_USED."</span><br> ";
        print philhealth::checkbox_philhealth_labs();
        print "<br/></td></tr>";
        print "<tr><td>";
        if ($_SESSION["priv_add"]) {
            print "<input type='submit' value = 'Save Data' class='textbox' name='submitlab' style='border: 1px solid #000000'><br> ";
        }
        print "</td></tr>";
        print "</form>";
        print "</table><br>";
    }

    function process_consult_lab() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
        }
        if ($post_vars["philhealth_id"]) {
            switch($post_vars["submitlab"]) {
            case "Save Data":
                if ($post_vars["lab_exam"]) {
                    foreach($post_vars["lab_exam"] as $key=>$value) {
                        $sql = "insert into m_consult_philhealth_labs (consult_id, ".
                               "patient_id, philhealth_id, lab_id, user_id, ".
                               "lab_timestamp) ".
                               "values ('".$get_vars["consult_id"]."', '$patient_id', ".
                               "'".$post_vars["philhealth_id"]."', '$value', '".$_SESSION["userid"]."', ".
                               "sysdate())";
                        $result = mysql_query($sql);
                    }
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DETAILS&module=philhealth&philhealth=LABS");
                }
            }
        } else {
            print "<font color='red'>No PHILHEALTH ID found.</font><br/>";
        }
    }

    function process_consult_services() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
        }
        if ($post_vars["philhealth_id"]) {
            switch($post_vars["submitservice"]) {
            case "Save Data":
                if ($post_vars["service"]) {
                    foreach($post_vars["service"] as $key=>$value) {
                        $sql = "insert into m_consult_philhealth_services (consult_id, ".
                               "patient_id, philhealth_id, service_id, user_id, ".
                               "service_timestamp) ".
                               "values ('".$get_vars["consult_id"]."', '$patient_id', ".
                               "'".$post_vars["philhealth_id"]."', '$value', '".$_SESSION["userid"]."', ".
                               "sysdate())";
                        $result = mysql_query($sql);
                    }
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DETAILS&module=philhealth&philhealth=SVC");
                }
            }
        } else {
            print "<font color='red'>No PHILHEALTH ID found.</font><br/>";
        }
    }

    function philhealth_menu() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        if (!isset($get_vars["philhealth"])) {
            header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]."&philhealth=CARD");
        }
        print "<table cellpadding='1' cellspacing='1' width='300' bgcolor='#9999FF' style='border: 1px solid black'><tr valign='top'><td nowrap>";
        print "<a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]."&philhealth=CARD' class='groupmenu'>".strtoupper(($get_vars["philhealth"]=="CARD"?"<b>CARD</b>":"CARD"))."</a>";
        print "<a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]."&philhealth=LABS' class='groupmenu'>".strtoupper(($get_vars["philhealth"]=="LABS"?"<b>LABS</b>":"LABS"))."</a>";
        print "<a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]."&philhealth=SVC' class='groupmenu'>".strtoupper(($get_vars["philhealth"]=="SVC"?"<b>SERVICES</b>":"SERVICES"))."</a>";
        print "</td></tr></table><br/>";
    }

    function form_philhealth() {
    //
    // form for registering patients for philhealth cards
    // Medicare Para Sa Masa
    //
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
            if ($get_vars["pid"]) {
                $sql = "select philhealth_id, expiry_date from m_patient_philhealth ".
                       "where philhealth_id = '".$get_vars["pid"]."'";
                if ($result = mysql_query($sql)) {
                    if (mysql_num_rows($result)) {
                        $card = mysql_fetch_array($result);
                    }
                }
            }
        }
        print "<table width='300'>";
        print "<form action = '".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DETAILS&module=philhealth&philhealth=CARD' name='form_philhealth_card' method='post'>";
        print "<tr valign='top'><td>";
        $patient_id = healthcenter::get_patient_id($get_vars["consult_id"]);
        $patient_name = patient::get_name($patient_id);
        print "<b>".FTITLE_PHILHEALTH_CARD_REGISTRATION."</b><br/>";
        print "<span class='patient'><font color='black'>".strtoupper($patient_name)."</font></span><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>PHILHEALTH CARD NUMBER</span><br/>";
        print "<input type='text' class='textbox' size='25' maxlength='50' name='philhealth_id' value='".($card["philhealth_id"]?$card["philhealth_id"]:$post_vars["philhealth_id"])."' style='border: 1px solid #000000'><br>";
        print "<input type='hidden' name='patient_id' value='$patient_id' />";
        print "<input type='hidden' name='healthcenter_id' value='".$_SESSION["datanode"]["code"]."'/>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        if ($card["expiry_date"]) {
            list($year, $month, $day) = explode("-", $card["expiry_date"]);
            $expiry_date = "$month/$day/$year";
        }
        print "<span class='boxtitle'>".LBL_EXPIRY_DATE."</span><br> ";
        print "<input type='text' size='15' maxlength='10' class='textbox' name='expiry_date' value='".($expiry_date?$expiry_date:$post_vars["expiry_date"])."' style='border: 1px solid #000000'> ";
        print "<a href=\"javascript:show_calendar4('document.form_philhealth_card.expiry_date', document.form_philhealth_card.expiry_date.value);\"><img src='../images/cal.gif' width='16' height='16' border='0' alt='Click Here to Pick up the date'></a><br>";
        print "<small>Click on the calendar icon to select date. Otherwise use MM/DD/YYYY format.</small><br>";
        print "<br/></td></tr>";
        print "<tr><td>";
        if ($get_vars["pid"]||$post_vars["philhealth_id"]) {
            print "<input type='hidden' name='philhealth_id' value='".$get_vars["pid"]."'>";
            print "<input type='submit' value = 'Update Card' class='textbox' name='submitcard' style='border: 1px solid #000000'> ";
            print "<input type='submit' value = 'Delete Card' class='textbox' name='submitcard' style='border: 1px solid #000000'> ";
        } else {
            print "<input type='submit' value = 'Add Card' class='textbox' name='submitcard' style='border: 1px solid #000000'> ";
        }
        print "</td></tr>";
        print "</form>";
        print "</table><br>";
    }

    function process_card() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        switch ($post_vars["submitcard"]) {
        case "Add Card":
            //if ($post_vars["philhealth_id"] && $post_vars["expiry_date"] && $post_vars["patient_id"]) {
	    if ($post_vars["philhealth_id"] && $post_vars["patient_id"]) {
                list($month,$day,$year) = explode("/", $post_vars["expiry_date"]);
                $expiry_date = $year."-".str_pad($month, 2, "0", STR_PAD_LEFT)."-".str_pad($day, 2, "0", STR_PAD_LEFT);
                $sql = "insert into m_patient_philhealth (philhealth_id, healthcenter_id, patient_id, philhealth_timestamp, expiry_date) ".
                       "values ('".$post_vars["philhealth_id"]."', '".$_SESSION["datanode"]["code"]."', '".$post_vars["patient_id"]."', sysdate(), '$expiry_date')";
                $result = mysql_query($sql);
                // save this any way and refresh page
                header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]."&philhealth=CARD");
            }
            break;
        case "Delete Card":
            if (module::confirm_delete($menu_id, $post_vars, $get_vars)) {
                $sql = "delete from m_patient_philhealth where philhealth_id = '".$post_vars["philhealth_id"]."'";
                if ($result = mysql_query($sql)) {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]."&philhealth=CARD");
                }
            } else {
                if ($post_vars["confirm_delete"]=="No") {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]."&philhealth=CARD");
                }
            }
            break;

	case "Update Card":
	    if ($post_vars["philhealth_id"] && $post_vars["patient_id"]) {
                list($month,$day,$year) = explode("/", $post_vars["expiry_date"]);
                $expiry_date = $year."-".str_pad($month, 2, "0", STR_PAD_LEFT)."-".str_pad($day, 2, "0", STR_PAD_LEFT);
                $sql = "update m_patient_philhealth (philhealth_id, healthcenter_id, patient_id, philhealth_timestamp, expiry_date) ".
                       "values ('".$post_vars["philhealth_id"]."', '".$_SESSION["datanode"]["code"]."', '".$post_vars["patient_id"]."', sysdate(), '$expiry_date')";

		$sql = "update m_patient_philhealth set healthcenter_id='$_SESSION[datanode][code]',patient_id='$post_vars[patient_id]',philhealth_timestamp='sysdate()',expiry_date='$expiry_date' WHERE philhealth_id='$post_vars[philhealth_id]'";

                $result = mysql_query($sql);

                // save this any way and refresh page
                header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=".$get_vars["ptmenu"]."&module=".$get_vars["module"]."&philhealth=CARD");
            }
            break;

	    break;
        }
    }

    function _details_philhealth() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
        }
        philhealth::display_philhealth($menu_id, $post_vars, $get_vars);
        philhealth::display_labs($menu_id, $post_vars, $get_vars);
        philhealth::display_services($menu_id, $post_vars, $get_vars);
    }

    function display_philhealth() {
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
        $patient_name = patient::get_name($get_vars["patient_id"]);
        print "<table width='300'>";
        print "<tr valign='top'><td>";
        print "<b>".FTITLE_RELATIVE_PHILHEALTH_RECORD."</b><br>";
        // does member have philhealth id?
        $patient_id = healthcenter::get_patient_id($get_vars["consult_id"]);
        $patient_name = patient::get_name($patient_id);
        $philhealth_id = philhealth::get_philhealth_id($patient_id);
        if (!$philhealth_id) {
            // try to see if any family member has a philhealth id
            $family_id = family::get_family_id($patient_id);
            $philhealth_info = philhealth::whois_philhealth_member($family_id);
            $patient_name = $philhealth_info["patient_lastname"].", ".$philhealth_info["patient_firstname"];
            $philhealth_id = $philhealth_info["philhealth_id"];
        }
        if ($philhealth_id && $patient_name) {
            print "BENEFICIARY: <b>$patient_name</b><br/>";
            print "PHILHEALTH ID: <b>$philhealth_id</b><br/>";
            print "EXPIRY: <b>".philhealth::get_expiry_date($philhealth_id)."</b><br/><br/>";
            print "<input type='hidden' name='philhealth_id' value='$philhealth_id'/>";
        } else {
            print "<font color='red'>No PHILHEALTH membership found.</font><br/>";
        }
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<b>".FTITLE_PHILHEALTH_RECORD."</b><br>";
        print "</td></tr>";
        $patient_id = healthcenter::get_patient_id($get_vars["consult_id"]);
        $sql = "select h.patient_id, ".
               "concat(p.patient_lastname, ', ',p.patient_firstname, ' ', p.patient_middle), philhealth_id, ".
               "expiry_date, to_days(sysdate()), to_days(expiry_date) ".
               "from m_patient_philhealth h, m_patient p ".
               "where h.patient_id = p.patient_id and h.patient_id = '$patient_id' ".
               "order by h.expiry_date desc";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                print "<tr valign='top'><td>";
                while (list($pid, $name, $hid, $expiry, $days_sysdate, $days_expirydate) = mysql_fetch_array($result)) {
                    print "<img src='../images/arrow_redwhite.gif' border='0'/> ";
                    print "$name <a href='".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DETAILS&module=philhealth&philhealth=CARD&pid=$hid'>$hid</a> ".
                          "[EXP ".($days_expirydate<=$days_sysdate?"<font color='red'><b>$expiry</b></font>":"$expiry")."]<br/>";
                }
                print "</td></tr>";
            } else {
                print "<tr valign='top'><td><font color='red'>No records.</font></td></tr>";
            }
        }
        print "</table><br>";
    }

    function display_labs() {
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
        $patient_name = patient::get_name($get_vars["patient_id"]);
        print "<table width='300'>";
        print "<tr valign='top'><td>";
        print "<b>".FTITLE_PHILHEALTH_LABS."</b><br>";
        print "</td></tr>";
        $sql = "select h.patient_id, h.philhealth_id, l.lab_name ".
               "from m_lib_laboratory l, m_consult_philhealth_labs h ".
               "where l.lab_id = h.lab_id and h.consult_id = '".$get_vars["consult_id"]."'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                print "<tr valign='top'><td>";
                while (list($pid, $hid, $lab) = mysql_fetch_array($result)) {
                    print "<img src='../images/arrow_redwhite.gif' border='0'/> ";
                    print "<a href='".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DETAILS&module=philhealth&philhealth=LABS&pid=$hid'>$hid</a> $lab<br/>";
                }
                print "</td></tr>";
            } else {
                print "<tr valign='top'><td><font color='red'>No records.</font></td></tr>";
            }
        }
        print "</table><br>";
    }

    function display_services() {
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
        $patient_name = patient::get_name($get_vars["patient_id"]);
        print "<table width='300'>";
        print "<tr valign='top'><td>";
        print "<b>".FTITLE_PHILHEALTH_SERVICES."</b><br>";
        print "</td></tr>";
        $sql = "select h.patient_id, h.philhealth_id, s.service_name ".
               "from m_lib_philhealth_services s, m_consult_philhealth_services h ".
               "where s.service_id = h.service_id and h.consult_id = '".$get_vars["consult_id"]."'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                print "<tr valign='top'><td>";
                while (list($pid, $hid, $lab) = mysql_fetch_array($result)) {
                    print "<img src='../images/arrow_redwhite.gif' border='0'/> ";
                    print "<a href='".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&consult_id=".$get_vars["consult_id"]."&ptmenu=DETAILS&module=philhealth&philhealth=LABS&pid=$hid'>$hid</a> $lab<br/>";
                }
                print "</td></tr>";
            } else {
                print "<tr valign='top'><td><font color='red'>No records.</font></td></tr>";
            }
        }
        print "</table><br>";
    }

    // ---------------------- LIBRARY FUNCTIONS -------------------------

    function _philhealth_services() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
        }
        if ($post_vars["submitservice"]) {
            $this->process_philhealth_services($menu_id,$post_vars,$get_vars,$validuser,$isadmin);
        }
        $this->display_philhealth_services($menu_id,$post_vars,$get_vars,$validuser,$isadmin);
        $this->form_philhealth_services($menu_id,$post_vars,$get_vars,$validuser,$isadmin);
    }

    function form_philhealth_services() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            if ($get_vars["service_id"]) {
                $sql = "select service_id, service_name from m_lib_philhealth_services where service_id = '".$get_vars["service_id"]."'";
                if ($result = mysql_query($sql)) {
                    if (mysql_num_rows($result)) {
                        $service = mysql_fetch_array($result);
                    }
                }
            }
        }
        print "<table width='300'>";
        print "<form action = '".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id' name='form_philhealth_services' method='post'>";
        print "<tr valign='top'><td>";
        print "<span class='library'>".FTITLE_PHILHEALTH_SERVICE_FORM."</span><br/><br/>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_SERVICE_ID."</span><br> ";
        print "<input type='text' size='15' maxlength='10' ".($get_vars["service_id"]?"disabled":"")." class='textbox' name='service_id' value='".($service["service_id"]?$service["service_id"]:$post_vars["service_id"])."' style='border: 1px solid #000000'><br>";
        print "<br/></td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_SERVICE_NAME."</span><br> ";
        print "<input type='text' size='15' maxlength='50' class='textbox' name='service_name' value='".($service["service_name"]?$service["service_name"]:$post_vars["service_name"])."' style='border: 1px solid #000000'><br>";
        print "<br/></td></tr>";
        print "<tr><td>";
        if ($get_vars["service_id"]) {
            print "<input type='hidden' name='service_id' value='".$get_vars["service_id"]."'>";
            if ($_SESSION["priv_update"]) {
                print "<input type='submit' value = 'Update Service' class='textbox' name='submitservice' style='border: 1px solid #000000'> ";
            }
            if ($_SESSION["priv_delete"]) {
                print "<input type='submit' value = 'Delete Service' class='textbox' name='submitservice' style='border: 1px solid #000000'> ";
            }
        } else {
            if ($_SESSION["priv_add"]) {
                print "<br><input type='submit' value = 'Add Service' class='textbox' name='submitservice' style='border: 1px solid #000000'><br> ";
            }
        }
        print "</td></tr>";
        print "</form>";
        print "</table><br>";
    }

    function process_philhealth_services() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        switch ($post_vars["submitservice"]) {
        case "Add Service":
            if ($post_vars["service_id"] && $post_vars["service_name"]) {
                $sql = "insert into m_lib_philhealth_services (service_id, service_name) ".
                       "values ('".$post_vars["service_id"]."', '".$post_vars["service_name"]."')";
                if ($result = mysql_query($sql)) {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]);
                }
            }
            break;
        case "Update Service":
            if ($post_vars["service_id"] && $post_vars["service_name"]) {
                $sql = "update m_lib_philhealth_services set ".
                       "service_name = '".$post_vars["service_name"]."' ".
                       "where service_id = '".$post_vars["service_id"]."'";
                if ($result = mysql_query($sql)) {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]);
                }
            }
            break;
        case "Delete Service":
            if (module::confirm_delete($menu_id, $post_vars, $get_vars)) {
                $sql = "delete from m_lib_philhealth_services where service_id = '".$post_vars["service_id"]."'";
                if ($result = mysql_query($sql)) {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]);
                }
            } else {
                if ($post_vars["confirm_delete"]=="No") {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]);
                }
            }
            break;
        }
    }

    function display_philhealth_services() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
        }
        print "<table width='400'>";
        print "<tr valign='top'><td colspan='2'>";
        print "<span class='library'>".FTITLE_PHILHEALTH_SERVICES_LIST."</span><br>";
        print "</td></tr>";
        print "<tr valign='top'><td><b>".THEAD_ID."</b></td><td><b>".THEAD_NAME."</b></td></tr>";
        $sql = "select service_id, service_name ".
               "from m_lib_philhealth_services ".
               "order by service_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while (list($id, $name) = mysql_fetch_array($result)) {
                    print "<tr valign='top'><td>$id</td><td><a href='".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id&service_id=$id'>$name</a></td></tr>";
                }
            }
        }
        print "</table><br>";
    }

    function show_philhealth_services() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $service_id = $arg_list[0];
        }
        $sql = "select service_id, service_name ".
               "from m_lib_philhealth_services ".
               "order by service_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                $ret_val .= "<select name='service_id' class='textbox'>";
                while (list($id, $name) = mysql_fetch_array($result)) {
                    $ret_val .= "<option value='$id' ".($service_id==$id?"selected":"").">$name</option>";
                }
                $ret_val .= "</select>";
            } else {
                $ret_val .= "<font color='red'>No service codes in library.</font><br/>";
            }
            return $ret_val;
        }
    }

    function checkbox_philhealth_services() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $service_id = $arg_list[0];
        }
        $sql = "select service_id, service_name ".
               "from m_lib_philhealth_services ".
               "order by service_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while (list($id, $name) = mysql_fetch_array($result)) {
                    $ret_val .= "<input type='checkbox' name='service[]' value='$id'> $name<br>";
                }
            } else {
                $ret_val .= "<font color='red'>No service codes in library.</font><br/>";
            }
            return $ret_val;
        }
    }

    function get_service_name() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $service_id = $arg_list[0];
        }
        $sql = "select service_name from m_lib_philhealth_services where service_id = '$service_id'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($name) = mysql_fetch_array($result);
                return $name;
            }
        }
    }

    function _philhealth_labs() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
        }
        if ($post_vars["submitlab"]) {
            $this->process_philhealth_lab($menu_id,$post_vars,$get_vars,$validuser,$isadmin);
        }
        $this->display_philhealth_lab($menu_id,$post_vars,$get_vars,$validuser,$isadmin);
        $this->form_philhealth_lab($menu_id,$post_vars,$get_vars,$validuser,$isadmin);
    }

    function form_philhealth_lab() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
        }
        print "<table width='300'>";
        print "<form action = '".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id' name='form_mechanism' method='post'>";
        print "<tr valign='top'><td>";
        print "<span class='library'>".FTITLE_PHILHEALTH_LAB_FORM."</span><br/><br/>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_SELECT_LAB_ID."</span><br> ";
        print lab::checkbox_lab_exams();
        print "<br/></td></tr>";
        print "<tr><td>";
        if ($_SESSION["priv_add"]) {
            print "<input type='submit' value = 'Add Lab Exam' class='textbox' name='submitlab' style='border: 1px solid #000000'><br> ";
        }
        print "</td></tr>";
        print "</form>";
        print "</table><br>";
    }

    function process_philhealth_lab() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        switch ($post_vars["submitlab"]) {
        case "Add Lab Exam":
            if ($post_vars["lab_exam"]) {
                foreach($post_vars["lab_exam"] as $key=>$value) {
                    print $sql = "insert into m_lib_philhealth_labs (lab_id) ".
                           "values ('$value')";
                    $result = mysql_query($sql);
                }
                header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]);
            }
            break;
        case "Delete Lab Exam":
            if (module::confirm_delete($menu_id, $post_vars, $get_vars)) {
                if ($post_vars["lab_exam"]) {
                    print $sql = "delete from m_lib_philhealth_labs where lab_id = '".$post_vars["lab_exam"]."'";
                    $result = mysql_query($sql);
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]);
                }
            } else {
                if ($post_vars["confirm_delete"]=="No") {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]);
                }
            }
            break;
        }
    }

    function display_philhealth_lab() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
        }
        print "<table width='300'>";
        print "<form action = '".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id' name='form_mechanism' method='post'>";
        print "<tr valign='top'><td>";
        print "<span class='library'>".FTITLE_PHILHEALTH_LAB_LIST."</span><br/><br/>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_EXISTING_LAB_EXAMS."</span><br> ";
        print philhealth::radio_philhealth_labs();
        print "<br/></td></tr>";
        print "<tr><td>";
        if ($_SESSION["priv_delete"]) {
            print "<input type='submit' value = 'Delete Lab Exam' class='textbox' name='submitlab' style='border: 1px solid #000000'><br> ";
        }
        print "</td></tr>";
        print "</form>";
        print "</table><br>";
    }

    function radio_philhealth_labs() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $lab_id = $arg_list[0];
        }
        $sql = "select p.lab_id, l.lab_name ".
               "from m_lib_philhealth_labs p, m_lib_laboratory l ".
               "where l.lab_id = p.lab_id order by l.lab_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while (list($id, $name) = mysql_fetch_array($result)) {
                    $ret_val .= "<input type='radio' name='lab_exam' value='$id'> $name<br>";
                }
                return $ret_val;
            }
        }
    }

    function checkbox_philhealth_labs() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $lab_id = $arg_list[0];
        }
        $sql = "select p.lab_id, l.lab_name ".
               "from m_lib_philhealth_labs p, m_lib_laboratory l ".
               "where l.lab_id = p.lab_id order by l.lab_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while (list($id, $name) = mysql_fetch_array($result)) {
                    $ret_val .= "<input type='checkbox' name='lab_exam[]' value='$id'> $name<br>";
                }
                return $ret_val;
            }
        }
    }

    // ------------------------- MISCELLANEOUS --------------------------

    function get_philhealth_id() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $patient_id = $arg_list[0];
        }
        $sql = "select philhealth_id from m_patient_philhealth where patient_id = '$patient_id'";
        if ($result  = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($id) = mysql_fetch_array($result);
                return $id;
            }
        }
    }

    function get_expiry_date () {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $philhealth_id = $arg_list[0];
        }
        $sql = "select expiry_date from m_patient_philhealth where philhealth_id = '$philhealth_id' order by expiry_date desc limit 1";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($date) = mysql_fetch_array($result);
                return $date;
            }
        }

    }

    function whois_philhealth_member() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $family_id = $arg_list[0];
        }
        // get only unexpired philhealth ids
        $sql = "select f.patient_id, p.patient_lastname, p.patient_firstname, h.philhealth_id ".
               "from m_family_members f, m_patient p, m_patient_philhealth h ".
               "where f.patient_id = p.patient_id and h.patient_id = f.patient_id ".
               "and f.family_id = '$family_id' limit 1";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                if ($member_array = mysql_fetch_array($result)) {
                    return $member_array;
                }
            }
        }
    }

}
?>
