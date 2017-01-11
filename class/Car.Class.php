<?php

class Car {
	
	private $connection;
	
	
	function __construct ($mysqli) {
		
		$this->connection = $mysqli;
	
	}
		
	/* TEISED FUNKTSIOONID  */
	
	function getAllCars($q, $sort, $orderA) {
		
		$allowedSort = ["id", "plate", "color"];
		
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
		$this->connection = new $this->connection($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);
		
		//kas otsib
		if ($q != ""){
			
			echo "Otsib: ".$q;
			
			$stmt = $this->connection->prepare("
				SELECT id, plate, color
				FROM cars_and_colors
				WHERE deleted IS NULL
				AND (plate LIKE ? OR color LIKE ?)
				ORDER BY $sort $orderBy
			");
			$searchWord = "%".$q."%";
			
			$stmt->bind_param("ss", $searchWord, $searchWord);
		
		}else{
			$stmt = $this->connection->prepare("
				SELECT id, plate, color
				FROM cars_and_colors
				WHERE deleted IS NULL
				ORDER BY $sort $orderBy
			");
		
		}
		
		echo $this->connection->error;
		
		$stmt->bind_result($id, $plate, $color);
		$stmt->execute();
		
		
		//tekitan massiivi
		$result = array();
		
		// tee seda seni, kuni on rida andmeid
		// mis vastab select lausele
		while ($stmt->fetch()) {
			
			//tekitan objekti
			$car = new StdClass();
			
			$car->id = $id;
			$car->plate = $plate;
			$car->carColor = $color;
			
			//echo $plate."<br>";
			// iga kord massiivi lisan juurde nr märgi
			array_push($result, $car);
		}
		
		$stmt->close();
		$this->connection->close();
		
		return $result;
	}
	
	function saveCar ($plate, $color) {
		
		$database = "if16_sirjemaria";
		$this->connection = new $this->connection($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);
		$stmt = $this->connection->prepare("INSERT INTO cars_and_colors (plate, color) VALUES (?, ?)");
	
		echo $this->connection->error;
		
		$stmt->bind_param("ss", $plate, $color);
		
		if($stmt->execute()) {
			echo "salvestamine õnnestus";
		} else {
		 	echo "ERROR ".$stmt->error;
		}
		
		$stmt->close();
		$this->connection->close();
		
		}
		
	}
		
?>