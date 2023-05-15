<!-- 
	Student Name: Qiaoqing Wu
	Due Date: 2023-04-10
	Section: CST8285 Lab section 313
	Description: an abstract DAO file
-->
<?php
    mysqli_report(MYSQLI_REPORT_STRICT);

    class abstractDAO {
        protected $mysqli;
        // Host address for the database
        protected static $DB_HOST = "localhost";
        /* Port number on the host */
        protected static $DB_PORT = 3307;
        /* Database username */
        protected static $DB_USERNAME = "appuser";
        /* Database password */
        protected static $DB_PASSWORD = "password";
        /* Name of database */
        protected static $DB_DATABASE = "assign2Demo";

        /*
        * Constructor. Instantiates a new MySQLi object.
        * Throws an exception if there is an issue connecting
        * to the database.
        */
        function __construct() {
            try {
                $this->mysqli = new mysqli(self::$DB_HOST, self::$DB_USERNAME, 
                    self::$DB_PASSWORD, self::$DB_DATABASE, self::$DB_PORT);
            }catch(mysqli_sql_exception $e){
                throw $e;
            }
        }

        public function getMysqli() {
            return $this->mysqli;
        }
    }

?>