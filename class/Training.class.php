<?php 
class Training {
	
	private $connection;
	
	function __construct($mysqli){
		
		$this->connection = $mysqli;
		
	}
	
	function save ($exercise, $series) {
	
		$stmt = $this->connection->prepare("INSERT INTO Training (exercise, series) VALUES (?, ?)");
		echo $this->connection->error;
		
		$stmt->bind_param("ss", $exercise, $series);
		
		if ($stmt->execute()) {
			echo "Salvestamine õnnestus";
		} else {
			echo "ERROR".$stmt->error;
		}
	}
	
	function get($q, $sort, $order) {
		
		$allowedSort = ["id", "exercise", "series"];
		
		if(!in_array($sort, $allowedSort)) {
			//ei ole lubatud tulp
			$sort = "id";
		}
		
		$orderBy = "ASC";
		
		if ($order == "DESC") {
			$orderBy = "DESC";
		}
		//echo "Sorteerin: ".$sort." ".$orderBy." ";
		
		//kas otsib
		if ($q != "") {
			
			//echo "Otsib: ".$q;
			
			$stmt = $this->connection->prepare("
			SELECT id, exercise, series
			FROM Training
			WHERE deleted IS NULL
			AND (exercise LIKE ? OR series LIKE ?)
			ORDER BY $sort $orderBy
			
		");	
		
		$searchWord = "%".$q."%";
		$stmt->bind_param("ss", $searchWord, $searchWord);
		
		} else {
		
		$stmt = $this->connection->prepare("
			SELECT id, exercise, series
			FROM Training
			WHERE deleted IS NULL
			ORDER BY $sort $orderBy");
		}
		
		echo $this->connection->error;
		
		$stmt->bind_result($id, $exercise, $series);
		$stmt->execute();
		
		
		//tekitan massiivi
		$result = array();
		
		// tee seda seni, kuni on rida andmeid
		// mis vastab select lausele
		while ($stmt->fetch()) {
			
			//tekitan objekti
			$person = new StdClass();
			
			$person->id = $id;
			$person->exercise = $exercise;
			$person->series = $series;

			
			array_push($result, $person);
		}
		
		$stmt->close();
		
		
		return $result;
	}
	
	function AllExercises($edit_id){
		$stmt = $this->connection->prepare("SELECT exercise, series FROM Training WHERE id=? AND deleted IS NULL");
		$stmt->bind_param("i", $edit_id);
		$stmt->bind_result($exercise, $series);
		$stmt->execute();
		
		//tekitan objekti
		$person = new Stdclass();
		
		//saime ühe rea andmeid
		if($stmt->fetch()){
			// saan siin alles kasutada bind_result muutujaid
			$person->exercise = $exercise;
			$person->series = $series;
			
			
		}else{
			// ei saanud rida andmeid kätte
			// sellist id'd ei ole olemas
			// see rida võib olla kustutatud
			header("Location: data.php");
			exit();
		}
		
		$stmt->close();
		return $person;
	}
	
	function delete($id){
		$stmt = $this->connection->prepare("UPDATE Training SET deleted=NOW() WHERE id=? AND deleted IS NULL");
		$stmt->bind_param("i",$id);
		
		// kas õnnestus salvestada
		if($stmt->execute()){
			// õnnestus
			echo "kustutamine õnnestus!";
		}
		
		$stmt->close();	
	}
	
	function update($id, $exercise, $series){
    	
		$stmt = $this->connection->prepare("UPDATE Training SET exercise=?, series=? WHERE id=? AND deleted IS NULL");
		$stmt->bind_param("ssi",$exercise, $series, $id);
		
		// kas õnnestus salvestada
		if($stmt->execute()){
			// õnnestus
			echo "salvestus õnnestus!";
		}
		
		$stmt->close();
	}
}?>