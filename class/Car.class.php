<?php 
class Car {
	
	private $connection;
	
	function __construct($mysqli){
		
		$this->connection = $mysqli;
		
	}

	/*TEISED FUNKTSIOONID */
	function delete($id){

		$stmt = $this->connection->prepare("UPDATE cars_and_colors1 SET deleted=NOW() WHERE id=? AND deleted IS NULL");
		$stmt->bind_param("i",$id);
		
		// kas õnnestus salvestada
		if($stmt->execute()){
			// õnnestus
			echo "kustutamine õnnestus!";
		}
		
		$stmt->close();
		
		
	}
		
	function get($q, $sort, $order) {
		
		$allowedSort = ["id", "model", "plate", "color", "information"];
		
		if(!in_array($sort, $allowedSort)){
			//ei ole lubatud tulp
			$sort = "id";
		}
		
		$orderBy = "ASC";
		
		if($order == "DESC"){
			$orderBy = "DESC";
		}
		echo "Sorteerin: ".$sort." ".$orderBy." ";
		
		//kas otsib
		if ($q != ""){
			
			echo "Otsib: ".$q;
			
			$stmt = $this->connection->prepare("
				SELECT id, model, plate, color, information
				FROM cars_and_colors1
				WHERE deleted IS NULL
				AND (model LIKE ? OR plate LIKE ? OR color LIKE ? OR information LIKE ?)
				ORDER BY $sort $orderBy
			");
			$searchWord = "%".$q."%";
			$stmt->bind_param("ssss", $searchWord, $searchWord, $searchWord, $searchWord);
					
			
		}else{
			$stmt = $this->connection->prepare("
				SELECT id, model, plate, color, information
				FROM cars_and_colors1
				WHERE deleted IS NULL
				ORDER BY $sort $orderBy
			");	
		}
	
		
		echo $this->connection->error;
		
		$stmt->bind_result($id, $model, $plate, $color, $information);
		$stmt->execute();
		
		
		//tekitan massiivi
		$result = array();
		
		// tee seda seni, kuni on rida andmeid
		// mis vastab select lausele
		while ($stmt->fetch()) {
			
			//tekitan objekti
			$car = new StdClass();
			
			$car->id = $id;
			$car->model = $model;
			$car->plate = $plate;
			$car->carColor = $color;
			$car->information = $information;
			
			//echo $plate."<br>";
			// iga kord massiivi lisan juurde nr märgi
			array_push($result, $car);
		}
		
		$stmt->close();
		
		
		return $result;
	}
	
	function getSingle($edit_id){

		$stmt = $this->connection->prepare("SELECT model, plate, color, information FROM cars_and_colors1 WHERE id=? AND deleted IS NULL");

		$stmt->bind_param("i", $edit_id);
		$stmt->bind_result($model, $plate, $color, $information);
		$stmt->execute();
		
		//tekitan objekti
		$car = new Stdclass();
		
		//saime ühe rea andmeid
		if($stmt->fetch()){
			// saan siin alles kasutada bind_result muutujaid
			$car->model = $model;
			$car->plate = $plate;
			$car->color = $color;
			$car->information = $information;
			
			
		}else{
			// ei saanud rida andmeid kätte
			// sellist id'd ei ole olemas
			// see rida võib olla kustutatud
			header("Location: data.php");
			exit();
		}
		
		$stmt->close();
		
		
		return $car;
		
	}

	function save ($model, $plate, $color, $information) {
		
		$stmt = $this->connection->prepare("INSERT INTO cars_and_colors1 (model, plate, color, information) VALUES (?, ?, ?, ?)");
	
		echo $this->connection->error;
		
		$stmt->bind_param("ssss", $model, $plate, $color, $information);
		
		if($stmt->execute()) {
			echo "salvestamine õnnestus";
		} else {
		 	echo "ERROR ".$stmt->error;
		}
		
		$stmt->close();
		
		
	}
	
	function update($id, $model, $plate, $color, $information){
    	
		$stmt = $this->connection->prepare("UPDATE cars_and_colors1 SET model=?, plate=?, color=?, information=? WHERE id=? AND deleted IS NULL");
		$stmt->bind_param("ssssi", $model, $plate, $color, $information, $id);
		
		// kas õnnestus salvestada
		if($stmt->execute()){
			// õnnestus
			echo "salvestus õnnestus!";
		}
		
		$stmt->close();
		
		
	}
	
}
?>