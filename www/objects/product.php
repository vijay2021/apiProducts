<?php
class Product{
 
    // database connection and table name
    private $conn;
    private $table_name = "products";
 
    // object properties
    public $sku;
    public $name;
    
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
	
	
	// read products
	function read(){
	 
		// select all query
		$query = "SELECT
					p.sku, p.name
				FROM
					" . $this->table_name . " p
				ORDER BY
					p.name ASC";
	 	// prepare query statement
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		
		return $stmt;
	}
	
	
	// read purchased products
	function purchasedProduct($userid){
	 
		// select all query
		$query = "SELECT
					p.sku, p.name
				FROM
					" . $this->table_name . " p";
		if($userid!=''){
			$query .= " where p.sku IN(SELECT product_sku from purchased where user_id='".$userid."')";
		}				
		
		$query .= "ORDER BY
					p.name ASC";
	 
		// prepare query statement
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		
		return $stmt;
	}
	
	
	// delete purchased products
	function deleteUserProduct($sku,$userid){
		
		if($this->findUserProduct($sku,$userid)==1){
			
			$query = "DELETE from purchased p where p.product_sku='".$sku."' and p.user_id='".$userid."'";
		 
			// prepare query statement
			$stmt = $this->conn->prepare($query);
			if($stmt->execute()){
				return 1;
			}else{
				return 0;
			}
		}else{
			return 2;
		}
		
		
			
	}
	
	// find product from sku and id
	function findUserProduct($sku,$userid){
	 
		// select all query
		$query = "SELECT p.product_sku from purchased p where p.product_sku='".$sku."' and p.user_id='".$userid."'";
	 
		// prepare query statement
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		
		return $stmt->rowCount();
	}
}

?>