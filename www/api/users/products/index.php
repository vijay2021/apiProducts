<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 

// required to encode json web token
include_once '../../../config/core.php';
include_once '../../../libs/php-jwt-master/src/BeforeValidException.php';
include_once '../../../libs/php-jwt-master/src/ExpiredException.php';
include_once '../../../libs/php-jwt-master/src/SignatureInvalidException.php';
include_once '../../../libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;
 
// database connection will be here
 
// include database and object files
include_once '../../../config/database.php';
include_once '../../../objects/product.php';
 
// instantiate database and user object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$product = new Product($db);
 
// read users will be here

// get posted data
$data = json_decode(file_get_contents("php://input"));
 
// get jwt
$jwt=isset($data->jwt) ? $data->jwt : "";
 
// decode jwt here


// if jwt is not empty
if($jwt){
 
    // if decode succeed, show user details
    try {
 
        // decode jwt
        $decoded = JWT::decode($jwt, $key, array('HS256'));
 
        // set user property values here
		
		if(isset($_GET['sku']) && $_GET['sku']!=''){
			
			$stmt = $product->deleteUserProduct($_GET['sku'],$decoded->data->id);
			
			if($stmt==1){
				// set response code - 200 OK
				http_response_code(200);
			 
				// show product data in json format
				echo json_encode(
					array("message" => "product successfully deleted.")
				);
				
			}else{
				// set response code - 404 Not found
				http_response_code(404);
			 
				// tell the product no product found
				echo json_encode(
					array("message" => "product not deleted.")
				);
			}	
		}else{
		
		
			$stmt = $product->purchasedProduct($decoded->data->id);
			$num = $stmt->rowCount();
			 
			// check if more than 0 record found
			if($num>0){
			 
				// product array
				$product_arr=array();
				$product_arr["records"]=array();
			 
				// retrieve our table contents
				// fetch() is faster than fetchAll()
				while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
					// extract row
					// this will make $row['name'] to
					// just $name only
					extract($row);
			 
					$product_item=array(
						"sku" => $sku,
						"name" => $name
					);
			 
					array_push($product_arr["records"], $product_item);
				}
			 
				// set response code - 200 OK
				http_response_code(200);
			 
				// show product data in json format
				echo json_encode($product_arr);
			}else{
			 
				// set response code - 404 Not found
				http_response_code(404);
			 
				// tell the product no product found
				echo json_encode(
					array("message" => "No product found.")
				);
			}
		}
		
		 
	}
 
    // catch failed decoding will be here
	
	// if decode fails, it means jwt is invalid
	catch (Exception $e){
	 
		// set response code
		http_response_code(401);
	 
		// show error message
		echo json_encode(array(
			"message" => "Access denied.",
			"error" => $e->getMessage()
		));
	}
}
 
// error message if jwt is empty will be here


