<?php 
class Apartment {
	
	private $connection;
	
	function __construct($mysqli){
		
		$this->connection = $mysqli;
		
	}

	/*TEISED FUNKTSIOONID */
	function delete($id){

		$stmt = $this->connection->prepare("UPDATE korterid SET deleted=NOW() WHERE id=? AND deleted IS NULL");
		$stmt->bind_param("i",$id);
		
		// kas õnnestus salvestada
		if($stmt->execute()){
			// õnnestus
			echo "Kustutamine õnnestus!";
		}
		
		$stmt->close();
		
		
	}
		
	function get($q, $sort, $order) {
		
		$allowedSort = ["id", "city", "street", "area", "rooms"];
		
		if(!in_array($sort, $allowedSort)){
			// ei ole lubatud tulp
			$sort = "id";
		}
		
		$orderBy = "ASC";
		
		if ($order == "DESC") {
			$orderBy = "DESC";
		}
		//echo "Sorteerin: ".$sort." ".$orderBy." ";
		
		
		//kas otsib
		if ($q != "") {
			
			echo "Otsib: ".$q;
			
			$stmt = $this->connection->prepare("
				SELECT id, city, street, area, rooms
				FROM korterid
				WHERE deleted IS NULL 
				AND (city LIKE ? OR street LIKE ? OR area LIKE ? OR rooms LIKE ?)
				ORDER BY $sort $orderBy
			");
			$searchWord = "%".$q."%";
			$stmt->bind_param("ssss", $searchWord, $searchWord, $searchWord, $searchWord);
			
			
		} else {
			
			$stmt = $this->connection->prepare("
				SELECT id, city, street, area, rooms
				FROM korterid
				WHERE deleted IS NULL
				ORDER BY $sort $orderBy
			");
			
		}
		
		echo $this->connection->error;
		
		$stmt->bind_result($id, $city, $street, $area, $rooms);
		$stmt->execute();
		
		
		//tekitan massiivi
		$result = array();
		
		// tee seda seni, kuni on rida andmeid
		// mis vastab select lausele
		while ($stmt->fetch()) {
			
			//tekitan objekti
			$apartment = new StdClass();
			
			$apartment->id = $id;
			$apartment->city = $city;
			$apartment->street = $street;
			$apartment->area = $area;
			$apartment->rooms = $rooms;
			
			
			//echo $plate."<br>";
			// iga kord massiivi lisan juurde nr märgi
			array_push($result, $apartment);
		}
		
		$stmt->close();
		
		
		return $result;
	}
	
	function getSingle($edit_id){

		$stmt = $this->connection->prepare("SELECT city, street, area, rooms FROM korterid WHERE id=? AND deleted IS NULL");

		$stmt->bind_param("i", $edit_id);
		$stmt->bind_result($city, $street, $area, $rooms);
		$stmt->execute();
		
		//tekitan objekti
		$Apartment = new Stdclass();
		
		//saime ühe rea andmeid
		if($stmt->fetch()){
			// saan siin alles kasutada bind_result muutujaid
			$Apartment->city = $city;
			$Apartment->street = $street;
			$Apartment->area = $area;
			$Apartment->rooms = $rooms;
			
			
		}else{
			// ei saanud rida andmeid kätte
			// sellist id'd ei ole olemas
			// see rida võib olla kustutatud
			header("Location: data.php");
			exit();
		}
		
		$stmt->close();
		
		
		return $Apartment;
		
	}

	function save ($city, $street, $area, $rooms) {
		
		$stmt = $this->connection->prepare("INSERT INTO korterid (city, street, area, rooms) VALUES (?, ?, ?, ?)");
	
		echo $this->connection->error;
		
		$stmt->bind_param("ssii", $city, $street, $area, $rooms);
		
		if($stmt->execute()) {
			echo "Salvestamine onnestus";
		} else {
		 	echo "ERROR ".$stmt->error;
		}
		
		$stmt->close();
		
		
	}
	
	function update($id, $city, $street, $area, $rooms){
    	
		$stmt = $this->connection->prepare("UPDATE korterid SET city=?, street=?, area=?, rooms=? WHERE id=? AND deleted IS NULL");
		$stmt->bind_param("ssiii", $city, $street, $area, $rooms, $id);
		
		// kas õnnestus salvestada
		if($stmt->execute()){
			// õnnestus
			echo "Salvestus onnestus!";
		}
		
		$stmt->close();
		
		
	}
	
}
?>