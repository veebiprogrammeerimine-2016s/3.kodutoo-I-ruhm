<?php

class Car {

	private $connection;

	function __construct($mysqli){
		$this->connection = $mysqli;

	}

	function save ($plate, $color) {

		$stmt = $this->connection->prepare("INSERT INTO cars_and_colors (plate, color) VALUES (?, ?)");

		echo $this->connection->error;

		$stmt->bind_param("ss", $plate, $color);

		if($stmt->execute()) {
			echo "salvestamine �nnestus";
		} else {
		 	echo "ERROR ".$stmt->error;
		}

		$stmt->close();

	}

	function deleteCar ($deleted) {


		$stmt = $this->connection->prepare("UPDATE cars_and_colors SET deleted=NOW() WHERE id=? AND deleted IS NULL");

		echo $this->connection->error;
		
		$stmt->bind_param("s", $deleted);

		if($stmt->execute()) {
			echo "kustutamine �nnestus";
		} else {
		 	echo "ERROR ".$stmt->error;
		}

		$stmt->close();

	}

	function getAll($q, $sort, $order) {

		$allowedSort = ["id", "plate", "color"];
		if(!in_array($sort, $allowedSort)) {
			//ei ole lubatud tulp
			$sort = "id";
		}

		$orderBy = "ASC";

		if ($order == "DESC") {
			$orderBy = "DESC";
		}
		echo "Sorteerin: ".$sort." ".$orderBy." ";

		if($q != "") {

			echo "Otsib: ".$q;

			$stmt = $this->connection->prepare("
			SELECT id, plate, color
			FROM cars_and_colors
			WHERE deleted IS NULL
			AND (plate LIKE ? OR color LIKE ?)
			ORDER BY $sort $orderBy
			
			");
			$searchWord="%".$q."%";
			$stmt->bind_param("ss", $searchWord, $searchWord);

		} else {
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
			// iga kord massiivi lisan juurde nr m�rgi
			array_push($result, $car);
		}

		$stmt->close();


		return $result;
	}


	function getSingleData($edit_id){


		$stmt = $this->connection->prepare("SELECT plate, color FROM cars_and_colors WHERE id=? AND deleted IS NULL");

		$stmt->bind_param("i", $edit_id);
		$stmt->bind_result($plate, $color);
		$stmt->execute();

		//tekitan objekti
		$car = new Stdclass();

		//saime �he rea andmeid
		if($stmt->fetch()){
			// saan siin alles kasutada bind_result muutujaid
			$car->plate = $plate;
			$car->color = $color;


		}else{
			// ei saanud rida andmeid k�tte
			// sellist id'd ei ole olemas
			// see rida v�ib olla kustutatud
			header("Location: data.php");
			exit();
		}

		$stmt->close();

		return $car;

	}


	function update($id, $plate, $color){


		$stmt = $this->connection->prepare("UPDATE cars_and_colors SET plate=?, color=? WHERE id=? AND deleted IS NULL");
		$stmt->bind_param("ssi",$plate, $color, $id);

		// kas �nnestus salvestada
		if($stmt->execute()){
			// �nnestus
			echo "salvestus �nnestus!";
		}

		$stmt->close();

	}



}	?>
