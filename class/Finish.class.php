<?php 
class Finish {
	
	private $connection;
	
	function __construct($mysqli){
		
		$this->connection = $mysqli;
		
	}

	/*TEISED FUNKTSIOONID */
	function delete($id){

		$stmt = $this->connection->prepare("UPDATE birthday_country SET deleted=NOW() WHERE id=? AND deleted IS NULL");
		$stmt->bind_param("i",$id);
		
		// kas õnnestus salvestada
		if($stmt->execute()){
			// õnnestus
			echo "Deleted!";
		}
		
		$stmt->close();
		
		
	}
		
	function get($q, $sort, $order) {
		
		$allowedSort = ["id", "birthday", "country"];
		
		if(!in_array($sort, $allowedSort)){
			// ei ole lubatud tulp
			$sort = "id";
		}
		
		$orderBy = "ASC";
		
		if ($order == "DESC") {
			$orderBy = "DESC";
		}
		echo "Sorting: ".$sort." ".$orderBy." ";
		
		
		//kas otsib
		if ($q != "") {
			
			echo "Looking for: ".$q;
			
			$stmt = $this->connection->prepare("
				SELECT id, birthday, country
				FROM birthday_country
				WHERE deleted IS NULL 
				AND (birthday LIKE ? OR country LIKE ?)
				ORDER BY $sort $orderBy
			");
			$searchWord = "%".$q."%";
			$stmt->bind_param("ss", $searchWord, $searchWord);
			
		} else {
			
			$stmt = $this->connection->prepare("
				SELECT id, birthday, country
				FROM birthday_country
				WHERE deleted IS NULL
				ORDER BY $sort $orderBy
			");
			
		}
		
		echo $this->connection->error;
		
		$stmt->bind_result($id, $birthday, $country);
		$stmt->execute();
		
		
		//tekitan massiivi
		$result = array();
		
		// tee seda seni, kuni on rida andmeid
		// mis vastab select lausele
		while ($stmt->fetch()) {
			
			//tekitan objekti
			$finish = new StdClass();
			
			$finish->id = $id;
			$finish->birthday = $birthday;
			$finish->country = $country;
			
			// iga kord massiivi lisan juurde nr märgi
			array_push($result, $finish);
		}
		
		$stmt->close();
		
		
		return $result;
	}
	
	function getSingle($edit_id){

		$stmt = $this->connection->prepare("SELECT birthday, country FROM birthday_country WHERE id=? AND deleted IS NULL");

		$stmt->bind_param("i", $edit_id);
		$stmt->bind_result($birthday, $country);
		$stmt->execute();
		
		//tekitan objekti
		$finish = new Stdclass();
		
		//saime ühe rea andmeid
		if($stmt->fetch()){
			// saan siin alles kasutada bind_result muutujaid
			$finish->birthday = $birthday;
			$finish->country = $country;
			
			
		}else{
			// ei saanud rida andmeid kätte
			// sellist id'd ei ole olemas
			// see rida võib olla kustutatud
			header("Location: data.php");
			exit();
		}
		
		$stmt->close();
		
		
		return $finish;
		
	}

	function save ($birthday, $country) {
		
		$stmt = $this->connection->prepare("INSERT INTO birthday_country (birthday, country) VALUES (?, ?)");
	
		echo $this->connection->error;
		
		$stmt->bind_param("ss", $birthday, $country);
		
		if($stmt->execute()) {
			echo "Success!";
		} else {
		 	echo "ERROR ".$stmt->error;
		}
		
		$stmt->close();
		
		
	}
	
	function update($id, $birthday, $country){
    	
		$stmt = $this->connection->prepare("UPDATE birthday_country SET birthday=?, country=? WHERE id=? AND deleted IS NULL");
		$stmt->bind_param("ssi",$birthday, $country, $id);
		
		// kas õnnestus salvestada
		if($stmt->execute()){
			// õnnestus
			echo "Success!";
		}
		
		$stmt->close();
		
		
	}
	
}
?>