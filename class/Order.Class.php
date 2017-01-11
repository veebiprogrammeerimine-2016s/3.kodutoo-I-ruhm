<?php
class Order {
	
	private $connection;
	
	
	function __construct ($mysqli) {
		
		$this->connection = $mysqli;
	
	}
		
	/* TEISED FUNKTSIOONID  */
	
	function AllOrders($q, $sort, $orderA) {
		
		$allowedSort = ["id", "product", "quantity"];
		
		if(!in_array($sort, $allowedSort)){
			//ei ole lubatud tulp
			$sort = "id";
			
		}
		
		$orderBy = "ASC";
		
		if ($orderA == "DESC") {
			$orderBy = "DESC";
		}
		
		//echo "Sorteerin: ".$sort." ".$orderBy." ";
		
		$database = "if16_sirjemaria";
		$this->connection = new $this->connection($GLOBALS["serverHost"],$GLOBALS["serverUsername"],$GLOBALS["serverPassword"], $GLOBALS["database"]);
		
		if ($q != ""){
			
			echo "Otsib: ".$q;
			
			$stmt = $this->connection->prepare("
				SELECT id, product, quantity
				FROM placeAnOrder
				WHERE deleted IS NULL
				AND (product LIKE ? OR quantity LIKE ?)
				ORDER BY $sort $orderBy
			");
			
			$searchWord = "%".$q."%";
			
			$stmt->bind_param("ss", $searchWord, $searchWord);
		
		}else{
			$stmt = $this->connection->prepare("
				SELECT id, product, quantity
				FROM placeAnOrder
				WHERE deleted IS NULL
				ORDER BY $sort $orderBy
			");
		
		}
		echo $this->connection->error;
		
		$stmt->bind_result($id, $product, $quantity);
		$stmt->execute();
		
		
		// array("SML","mhm")
		$result = array();
		
		//seni kuni on 1 rida andmeid saata ehk 10 rida=10 korda
		while ($stmt->fetch()) {
			
			$orders = new StdClass();
			$orders->id = $id;
			$orders->product = $product;
			$orders->quantity = $quantity;
				
			//echo $color."<br>";
			array_push($result, $orders);
			
		}
		
		$stmt->close();
		$this->connection->close();
		
		return $result;
		
	}
	
	function saveOrder ($product, $quantity){
		
		$database = "if16_sirjemaria";
		$this->connection = new $this->connection($GLOBALS["serverHost"],$GLOBALS["serverUsername"],$GLOBALS["serverPassword"],$GLOBALS["database"]);
		
		$stmt = $this->connection->prepare("INSERT INTO placeAnOrder (product, quantity) VALUES (?, ?)");
		echo $this->connection->error;
		
		$stmt->bind_param("ss", $product, $quantity);
		
		if ($stmt->execute()) {
			echo "Saved!";
	   } else {
			echo "ERROR".$stmt->error;
	   }
	   
	   $stmt->close();
		$this->connection->close();
	}

}


?>