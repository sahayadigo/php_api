<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/Database.php';
include_once '../class/Items.php';

// $database = new Database();
// $db = $database->getConnection();
 
// $items = $db;

//$items->id = (isset($_GET['id']) && $_GET['id']) ? $_GET['id'] : '0';
//print_r($conn);
$sql = "SELECT * FROM items";
$result = $conn->query($sql);
//$result = $items->read();
// print_r($result);
// if($items->id) {
//     $stmt = $conn->prepare("SELECT * FROM ".$this->itemsTable." WHERE id = ?");
//     $stmt->bind_param("i", $this->id);					
// } else {
//     $stmt = $this->conn->prepare("SELECT * FROM ".$this->itemsTable);
//     print_r($conn);
//     print_r($stmt);		
// }		
// $stmt->execute();			
// $result = $stmt->get_result();		
// return $result;

if($result->num_rows > 0){    
    $itemRecords=array();
    $itemRecords["items"]=array(); 
	while ($item = $result->fetch_assoc()) { 	
        extract($item); 
        $itemDetails=array(
            "id" => $id,
            "name" => $name,
            "description" => $description,
			"price" => $price,
            "category_id" => $category_id,            
			"created" => $created,
            "modified" => $modified			
        ); 
       array_push($itemRecords["items"], $itemDetails);
    }    
    http_response_code(200);     
    echo json_encode($itemRecords);
}else{     
    http_response_code(404);     
    echo json_encode(
        array("message" => "No item found.")
    );
} 
?>