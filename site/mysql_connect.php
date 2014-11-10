<?php
	class mysqlConnect {
		public $dbHost = "localhost";
		public $value_array = array();
		public $csv_array = array();
		public $dbCon;
				
		public function __construct (){
			$this->_dbOpen();
		}

		private function _dbOpen(){
			$this->dbCon = mysql_connect($this->dbHost,$_SESSION["dbuser"],$_SESSION["dbpass"]);
			if (!$this->dbCon || !mysql_select_db($_SESSION["dbname"], $this->dbCon)) {
				die ("Connection Failed" . mysql_error() );
			}
		}
			
		public function _dbQuery($dbSql){
			$dbQuery = mysql_query($dbSql, $this->dbCon);
			$this->_dbConfirm($dbQuery);
				
			return $dbQuery;				
		}

		public function _dbFetch($dbQuery){
			return mysql_fetch_assoc($dbQuery);
		}

		private function _dbConfirm($dbQuery){
			if( !$dbQuery ){
				echo "You have an error on your last SQL Statement " . mysql_error();
			}
		}
	}
	
	$database = new mysqlConnect();
?>
