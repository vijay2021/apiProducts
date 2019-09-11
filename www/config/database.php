<?php
class Database{
 
    // specify your own database credentials
    //private $host = "localhost:3360";
	private $host = "172.27.0.2";
    private $db_name = "myDb";
    private $username = "user";
    private $password = "test";
    public $conn;
 
    // get the database connection
    public function getConnection(){
 
        $this->conn = null;
 
        try{
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
			//$this->conn = new PDO("mysql:unix_socket=/var/run/mysqld/mysqld.sock", $this->username, $this->password);
            $this->conn->exec("set names utf8");
        }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }
 
        return $this->conn;
    }
}
?>