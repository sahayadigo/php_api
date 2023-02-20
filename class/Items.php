<?php

function create(){
		
	$stmt = $this->conn->prepare("
		INSERT INTO ".$this->itemsTable."(`name`, `description`, `price`, `category_id`, `created`)
		VALUES(?,?,?,?,?)");
	
	$this->name = htmlspecialchars(strip_tags($this->name));
	$this->description = htmlspecialchars(strip_tags($this->description));
	$this->price = htmlspecialchars(strip_tags($this->price));
	$this->category_id = htmlspecialchars(strip_tags($this->category_id));
	$this->created = htmlspecialchars(strip_tags($this->created));
	
	
	$stmt->bind_param("ssiis", $this->name, $this->description, $this->price, $this->category_id, $this->created);
	
	if($stmt->execute()){
		return true;
	}
 
	return false;		 
}

function read(){	
	if($this->id) {
		$stmt = $conn->prepare("SELECT * FROM ".$this->itemsTable." WHERE id = ?");
		$stmt->bind_param("i", $this->id);					
	} else {
		$stmt = $this->conn->prepare("SELECT * FROM ".$this->itemsTable);
		print_r($stmt);		
	}		
	$stmt->execute();			
	$result = $stmt->get_result();		
	return $result;	
}
?>