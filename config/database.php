<?php 
    class Database {
        private $host = "127.0.0.1";
        private $database_name = "booking";
        private $username = "root";
        private $password = "password";

        public $connection;

        public function getConnection(){
            $this->connection = null;
            try{
                $this->connection = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->database_name, $this->username, $this->password);
                $this->connection->exec("set names utf8");
            }catch(PDOException $exception){
                echo "Database could not be connected: " . $exception->getMessage();
            }
            return $this->conn;
        }
    }  
?>