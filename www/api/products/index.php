<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json; charset=UTF-8");
 
// include database and object files
include_once '../../config/database.php';
include_once '../../objects/product.php';
 
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$product = new Product($db);
 
// read product will be here

// query product
$stmt = $product->read();
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0 && $_SERVER['REQUEST_METHOD']=='GET'){
 
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
        array("message" => "No product found or wrong request method.",'status'=>http_response_code(404))
    );
}
 
// no product found will be here