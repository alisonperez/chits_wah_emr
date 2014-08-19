<?php
	class gen_services extends module{
		
		function gen_services(){
	        $this->author = 'darth_ali';
	        $this->version = '0.1-'.date("Y-m-d");
		    $this->module = 'gen_services';
			$this->description = 'Module - General Services';
		}

		function init_deps(){
		    module::set_dep($this->module, "module");
	        module::set_dep($this->module, "healthcenter");
		    module::set_dep($this->module, "patient");
	        module::set_dep($this->module, "vaccine");
		    module::set_dep($this->module, "ccdev");
			module::set_dep($this->module, "philhealth");
		}
	
		function init_lang(){
		
		}

		function init_menu(){
	        module::set_menu($this->module, "General Services", "CONSULTS", "_gen_services");
	        module::set_detail($this->description, $this->version, $this->author, $this->module);
		}
		
	
		function init_stats(){

		}

		
		function init_help(){

		}


		function init_sql(){
			module::execsql("CREATE TABLE IF NOT EXISTS `m_consult_service` (`consult_id` float NOT NULL DEFAULT '0',
			  `patient_id` float NOT NULL DEFAULT '0',`user_id` float NOT NULL DEFAULT '0',
			  `service_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			  `actual_service_date` date NOT NULL DEFAULT '0000-00-00',`source_module` varchar(25) NOT NULL DEFAULT 'vaccine',`adr_flag` char(1) NOT NULL DEFAULT 'N',`service_id` varchar(10) NOT NULL DEFAULT '',
			  PRIMARY KEY (`patient_id`,`service_id`,`consult_id`), 
			  KEY `key_patient` (`patient_id`),
			  KEY `key_vaccine` (`service_id`),
			  KEY `key_user` (`user_id`),
			  KEY `key_consult` (`consult_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;");			
		}

		function drop_tables() {

			module::execsql("DROP TABLE `m_consult_service`;");
			module::execsql("SET foreign_key_checks=1;");
		}

		//---- CUSTOM MODULES ----

		function _gen_services(){
	        if ($exitinfo = $this->missing_dependencies('gen_services')) {
		        return print($exitinfo);
			}

			if (func_num_args()>0) {
				$arg_list = func_get_args();
	            $menu_id = $arg_list[0];
		        $post_vars = $arg_list[1];
	            $get_vars = $arg_list[2];
	            $validuser = $arg_list[3];
	            $isadmin = $arg_list[4];
		    }
	
			$s = new gen_services;
	        if ($post_vars["submitvaccine"]) {
		        $s->process_consult_service($menu_id, $post_vars, $get_vars, $validuser, $isadmin);
	        }

			$s->form_service($menu_id, $post_vars, $get_vars, $validuser, $isadmin);				
		}


		function form_service(){		
		}

	
	}
?>