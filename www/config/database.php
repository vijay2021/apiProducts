<?php
class Database{
 
    // specify your own database credentials
    //please find host value from docker command - docker inspect mysql container id
	private $host = "172.22.0.2";
    private $db_name = "myDb";
    private $username = "user";
    private $password = "test";
    public $conn;
 
    // get the database connection
    public function getConnection(){
 
        $this->conn = null;
 
        try{
           $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
           $this->conn->exec("set names utf8");
        }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }
 
        return $this->conn;
    }
}
?>