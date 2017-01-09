<?php 
class Goal {
	
	private $connection;
	
	function __construct($mysqli){
		
		$this->connection = $mysqli;
		
	}

	/*TEISED FUNKTSIOONID */
	function delete($id){

		$stmt = $this->connection->prepare("UPDATE goalhelper SET deleted=NOW() WHERE id=? AND deleted IS NULL");
		$stmt->bind_param("i",$id);
		
		// kas õnnestus salvestada
		if($stmt->execute()){
			// õnnestus
			echo "Kustutamine õnnestus!";
		}
		
		$stmt->close();
		
		
	}
		
	function get($q, $sort, $order) {
		
		$allowedSort = ["id", "goal_name", "goal_explanation", "due_date", "created"];
		
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
				SELECT id, goal_name, goal_explanation, due_date, created
				FROM goalhelper
				WHERE deleted IS NULL 
				AND (goal_name LIKE ? OR goal_explanation LIKE ? OR due_date LIKE ? OR created LIKE ?)
				ORDER BY $sort $orderBy
			");
			$searchWord = "%".$q."%";
			$stmt->bind_param("ssss", $searchWord, $searchWord, $searchWord, $searchWord);
			
			
		} else {
			
			$stmt = $this->connection->prepare("
				SELECT id, goal_name, goal_explanation, due_date, created
				FROM goalhelper
				WHERE deleted IS NULL
				ORDER BY $sort $orderBy
			");
			
		}
		
		echo $this->connection->error;
		
		$stmt->bind_result($id, $goal_name, $goal_explanation, $due_date, $created);
		$stmt->execute();
		
		
		//tekitan massiivi
		$result = array();
		
		// tee seda seni, kuni on rida andmeid
		// mis vastab select lausele
		while ($stmt->fetch()) {
			
			//tekitan objekti
			$Goal = new StdClass();
			
			$Goal->id = $id;
			$Goal->goal_name = $goal_name;
			$Goal->goal_explanation = $goal_explanation;
			$Goal->due_date = $due_date;
			$Goal->created = $created;
			
			
			//echo $plate."<br>";
			// iga kord massiivi lisan juurde nr märgi
			array_push($result, $Goal);
		}
		
		$stmt->close();
		
		
		return $result;
	}
	
	function getSingle($edit_id){

		$stmt = $this->connection->prepare("SELECT goal_name, goal_explanation, due_date, created FROM goalhelper WHERE id=? AND deleted IS NULL");

		$stmt->bind_param("i", $edit_id);
		$stmt->bind_result($goal_name, $goal_explanation, $due_date, $created);
		$stmt->execute();
		
		//tekitan objekti
		$Goal = new Stdclass();
		
		//saime ühe rea andmeid
		if($stmt->fetch()){
			// saan siin alles kasutada bind_result muutujaid
			$Goal->goal_name = $goal_name;
			$Goal->goal_explanation = $goal_explanation;
			$Goal->due_date = $due_date;
			$Goal->created = $created;
			
			
		}else{
			// ei saanud rida andmeid kätte
			// sellist id'd ei ole olemas
			// see rida võib olla kustutatud
			header("Location: data.php");
			exit();
		}
		
		$stmt->close();
		
		
		return $Goal;
		
	}

	function save ($goal_name, $goal_explanation, $due_date) {
		
		$stmt = $this->connection->prepare("INSERT INTO goalhelper (goal_name, goal_explanation, due_date) VALUES (?, ?, ?)");
	
		echo $this->connection->error;
		
		$stmt->bind_param("sss", $goal_name, $goal_explanation, $due_date);
		
		if($stmt->execute()) {
			echo "Salvestamine onnestus";
		} else {
		 	echo "ERROR ".$stmt->error;
		}
		
		$stmt->close();
		
		
	}
	
	function update($id, $goal_name, $goal_explanation){
    	
		$stmt = $this->connection->prepare("UPDATE goalhelper SET goal_name=?, goal_explanation=? WHERE id=? AND deleted IS NULL");
		$stmt->bind_param("ssi", $goal_name, $goal_explanation, $id);
		
		// kas õnnestus salvestada
		if($stmt->execute()){
			// õnnestus
			echo "Salvestus onnestus!";
		}
		
		$stmt->close();
		
		
	}
	
}

?>