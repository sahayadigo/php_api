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
        
        // Create token header as a JSON string
$header = json_encode(['alg' => 'HS256', 'clientid' => 'bduatv2krl']);

// {
//     "mercid": “BDMERCID”,
//     "": ,
//     "": “300.00”,
//     "": “”,
//     "": “356”,
//     "ru" : “https://merchant.com”,
//     "": {
//     "additional_info1": “Details1”,
//     "additional_info2": “Details2”
//     },
//     "itemcode": “DIRECT”,
//     "device": {
//     "init_channel" : “internet”,
//     "ip": “124.124.1.1”,
//     "mac": “11-AC-58-21-1B-AA”,
//     "imei": “990000112233445”,
//     "accept_header": “text/html”,
//     "fingerprintid": “61b12c18b5d0cf901be34a23ca64bb19”
//     }
//     }
// Create token payload as a JSON string
$payload = json_encode(['mercid' => 'BDUATV2KRL', 
                        'orderid' => 'order45608988', 
                        'amount' => 300.00, 
                        'order_date' => '2021-03-05T10:59:15+05:30', 
                        'currency' => 356, 
                        'ru' => 'https://merchant.com', 
                        'additional_info' => [
                            'additional_info1' => 'Details1',
                            'additional_info2' => 'Details1'
                        ]
                    ]);

// Encode Header to Base64Url String
$base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));

// Encode Payload to Base64Url String
$base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));

// Create Signature Hash
$signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, 'abC123!', true);

// Encode Signature to Base64Url String
$base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

// Create JWT
$jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

echo $jwt;

        $stmt = $conn->prepare("
		INSERT INTO items(`name`, `description`, `price`, `category_id`, `created`)
		VALUES(?,?,?,?,?)");
	
	$name = htmlspecialchars(strip_tags($data->name));
	$description = $jwt;
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
