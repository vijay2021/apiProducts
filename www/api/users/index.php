<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 

// required to encode json web token
include_once '../../config/core.php';
include_once '../../libs/php-jwt-master/src/BeforeValidException.php';
include_once '../../libs/php-jwt-master/src/ExpiredException.php';
include_once '../../libs/php-jwt-master/src/SignatureInvalidException.php';
include_once '../../libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;
 
// database connection will be here
 
// include database and object files
include_once '../../config/database.php';
include_once '../../objects/user.php';
 
// instantiate database and user object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$user = new User($db);
 
// read users will be here

// get posted data
$data = json_decode(file_get_contents("php://input"));
 
// get jwt
$jwt=isset($data->jwt) ? $data->jwt : "";
 
if(empty($jwt) && isset($_SERVER['HTTP_JWT'])){
	$jwt = trim($_SERVER['HTTP_JWT']);
} 
// decode jwt here


// if jwt is not empty
if($jwt  && $_SERVER['REQUEST_METHOD']=='GET'){
 
    // if decode succeed, show user details
    try {
 
        // decode jwt
        $decoded = JWT::decode($jwt, $key, array('HS256'));
 
        // set user property values here
		$user_arr = array(
			"name"=> $decoded->data->name
		 );
		 
		 echo json_encode($user_arr); 
    }
	
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
}else{
	
	// show error message
		echo json_encode(array(
			"message" => "jwt token is empty or wrong method call.",
			"status"=>http_response_code(401),
		));
}
 
// error message if jwt is empty will be here
