<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET,POST,DELETE");
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
	
// get posted data
$data = json_decode(file_get_contents("php://input")); 
// get jwt
$jwt=isset($data->jwt) ? $data->jwt : "";

//if token is not available in body
if(empty($jwt) && isset($_SERVER['HTTP_JWT'])){
	$jwt = trim($_SERVER['HTTP_JWT']);
}
 
// decode jwt here


// if jwt is not empty
if($jwt){
 
    // if decode succeed, show user details
    try {
 
        // decode jwt
        $decoded = JWT::decode($jwt, $key, array('HS256'));
		
		if(isset($_SERVER['argv'][0]) && $_SERVER['argv'][0]!=''){
			$sku = explode("=",$_SERVER['argv'][0]);
		}
        // set user property values here
		// delete user product method with sku and id
		if((isset($sku[1]) && $sku[1]!='') && $_SERVER['REQUEST_METHOD']=='DELETE'){
			
			$stmt = $product->deleteUserProduct($sku[1],$decoded->data->id);
			
			if($stmt==1){
				// set response code - 200 OK
				http_response_code(200);
			 
				// show product data in json format
				echo json_encode(
					array("message" => "product successfully deleted.",'status'=>http_response_code(200))
				);
				
			}elseif($stmt==2){
				// set response code - 200 OK
				http_response_code(404);
			 
				// no product or sku wrong
				echo json_encode(
					array("message" => "product sku is not correct or sku not belongs to current user.",'status'=>http_response_code(404))
				);
				
			}else{
				// set response code - 200 Not found
				http_response_code(404);
			 
				// tell the product no product found
				echo json_encode(
					array("message" => "product not deleted.",'status'=>http_response_code(404))
				);
			} 		
		}elseif($_SERVER['REQUEST_METHOD']=='POST' || $_SERVER['REQUEST_METHOD']=='GET'){
		
		
			$stmt = $product->purchasedProduct($decoded->data->id);
			$num = $stmt->rowCount();
			 
			// check if more than 0 record found
			if($num>0){
			 
				// product array
				$product_arr=array();
				$product_arr=array();
			 
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
			 
					array_push($product_arr, $product_item);
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
					array("message" => "No product found.",'status'=>http_response_code(404))
				);
			}
		}else{
			echo json_encode(
					array("message" => "Wrong method call.",'status'=>http_response_code(404))
				);
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
			"error" => $e->getMessage(),
			'status'=>http_response_code(401)
		));
	}
}
 
// error message if jwt is empty will be here
