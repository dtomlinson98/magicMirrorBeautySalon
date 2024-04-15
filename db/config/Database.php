<?php
    //DB parameters
    class Database {
        private $conn;
        private $host;
        private $port;
        private $db_name;
        private $username;
        private $password;

        public function __construct() {
            $this->host = getenv('HOST');
            $this->port = getenv('PORT');
            $this->db_name = getenv('DBNAME');
            $this->username = getenv('USERNAME');
            $this->password = getenv('PASSWORD');
        }
        
        
        //DB connect
        public function connect() {
            if ($this->conn) {
                return $this->conn;
            } else {
                $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->db_name};";

                try {
                    $this->conn = new PDO($dsn, $this->username, $this->password);
                    $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    return $this->conn;
                } catch(PDOException $e) {
                    echo 'Connection Error: ' . $e->getMessage();
                }
            }
        }
    }
?>
