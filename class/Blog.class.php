<?php
class Blog {

	private $connection;

	function __construct($mysqli){

		$this->connection = $mysqli;

	}
	
	/*TEISED FUNKTSIOONID */
	/*function delete($id){

		$stmt = $this->connection->prepare("UPDATE EverydayBlog SET deleted=NOW() WHERE id=? AND created IS NULL");
		$stmt->bind_param("i", $id);
		
		// kas õnnestus salvestada
		if($stmt->execute()){
			// õnnestus
			echo "Kustutamine õnnestus!";
		}
		
		$stmt->close();

	}*/
		
	function get($q, $sort, $order) {
		
		$allowedSort = ["id", "user", "date", "mood", "feeling", "activities", "thoughts"];
		
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
			
			echo "Otsib: ".$q;
			
			$stmt = $this->connection->prepare("
				SELECT id, user, date, mood, feeling, activities, thoughts
				FROM EverydayBlog
				WHERE created IS NULL
				AND (date LIKE ? OR mood LIKE ? OR feeling LIKE ? OR activities LIKE ? OR thoughts LIKE ?)
				ORDER BY $sort $orderBy
			");	
			$searchWord = "%".$q."%".$q."%".$q."%".$q."%";
			$stmt->bind_param("issss", $searchWord, $searchWord, $searchWord, $searchWord, $searchWord);
			
		} else {
			
			$stmt = $this->connection->prepare("
			SELECT id, user, date, mood, feeling, activities, thoughts
			FROM EverydayBlog
			WHERE created IS NULL
			ORDER BY $sort $orderBy
			");		
		}
		
		echo $this->connection->error;
		
		$stmt->bind_result($id, $user, $date, $mood, $feeling, $activities, $thoughts);
		$stmt->execute();
		
		//tekitan massiivi
		$result = array();
			
			//tekitan objekti
		while ($stmt->fetch()) {
			$person = new StdClass();
			$person-> id = $id;
			$person-> user = $user;
			$person -> date = $date;
			$person -> mood = $mood;
			$person -> feeling = $feeling;
			$person -> activities = $activities;
			$person -> thoughts = $thoughts;

			array_push($result, $person);
		}
		
		$stmt->close();
		return $result;
	}
	
	function getSingle($edit_id){

		$stmt = $this->connection->prepare("SELECT date, mood, feeling, activities, thoughts FROM EverydayBlog WHERE id=? AND user=? AND created IS NULL");

		$stmt->bind_param("i", $edit_id);
		$stmt->bind_result($mood, $feeling, $activities, $thoughts);
		$stmt->execute();
		
		//tekitan objekti
		$person = new Stdclass();
		
		//saime ühe rea andmeid
		if($stmt->fetch()){
			// saan siin alles kasutada bind_result muutujaid
			$person -> date = $date;
			$person -> mood = $mood;
			$person -> feeling = $feeling;
			$person -> activities = $activities;
			$person -> thoughts = $thoughts;
			
		} else{
			// ei saanud rida andmeid kätte
			// sellist id'd ei ole olemas
			// see rida võib olla kustutatud
			header("Location: data.php");
			exit();
		}
		
		$stmt->close();
		return $person;
	}

	function save ($date, $mood, $feeling, $activities, $thoughts) {
		
		$stmt = $this->connection->prepare("INSERT INTO EverydayBlog (date, mood, feeling, activities, thoughts) VALUES (?, ?, ?, ?, ?)");
	
		echo $this->connection->error;
		
		$stmt->bind_param("issss", $date, $mood, $feeling, $activities, $thoughts);
		
		if($stmt->execute()) {
			echo "Salvestamine õnnestus!";
		} else {
		 	echo "ERROR ".$stmt->error;
		}
		$stmt->close();
	}
	
	function update($id, $user, $date, $mood, $feeling, $activities, $thoughts){
    	
		$stmt = $this->connection->prepare("UPDATE EverydayBlog SET date=?, mood=?, feeling=?, activities=?, thoughts=? WHERE id=? AND user=? AND created IS NULL");
		$stmt->bind_param("isssii", $date, $mood, $feeling, $activities, $thoughts, $id, $user);
		
		// kas õnnestus salvestada
		if($stmt->execute()){
			// õnnestus
			echo "Salvestamine õnnestus!";
		}
		$stmt->close();
	}
}
?>