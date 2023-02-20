<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
include_once '../config/Database.php';
include_once '../class/Items.php';
 
$data = json_decode(file_get_contents("php://input"));

if(!empty($data->name) && !empty($data->description) &&
!empty($data->price) && !empty($data->category_id) &&
!empty($data->created)){    

    // $items->name = $data->name;
    // $items->description = $data->description;
    // $items->price = $data->price;
    // $items->category_id = $data->category_id;	
    // $items->created = date('Y-m-d H:i:s'); 

       
    if($data->name){     
        //echo $conn;    
        $stmt = $conn->prepare("
		INSERT INTO items(`name`, `description`, `price`, `category_id`, `created`)
		VALUES(?,?,?,?,?)");
	
	$name = htmlspecialchars(strip_tags($data->name));
	$description = htmlspecialchars(strip_tags($data->description));
	$price = htmlspecialchars(strip_tags($data->price));
	$category_id = htmlspecialchars(strip_tags($data->category_id));
	$created = htmlspecialchars(strip_tags($data->created));
	
	
	$stmt->bind_param("ssiis", $name, $description, $price, $category_id, $created);
	
	if($stmt->execute()){
        http_response_code(201);         
        echo json_encode(array("message" => "Item was created."));
    } else{         
        http_response_code(503);        
        echo json_encode(array("message" => "Unable to create item."));
    }
		return true;
	}
    
}else{    
    http_response_code(400);    
    echo json_encode(array("message" => "Unable to create item. Data is incomplete."));
}
?>