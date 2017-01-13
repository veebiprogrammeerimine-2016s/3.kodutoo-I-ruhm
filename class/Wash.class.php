<?php 
class Homework {
	
	private $connection;
	
	function __construct($mysqli){
		
		$this->connection = $mysqli;
		
	}

	/*TEISED FUNKTSIOONID */
	function delete($id){

		$stmt = $this->connection->prepare("UPDATE homeworks SET deleted=NOW() WHERE id=? AND deleted IS NULL");
		$stmt->bind_param("i",$id);
		
		// kas õnnestus salvestada
		if($stmt->execute()){
			// õnnestus
			echo "Kustutamine õnnestus!";
		}
		
		$stmt->close();
		
		
	}
		
	function get($q, $sort, $order) {
		
		$allowedSort = ["id", "homework_name", "homework_explanation", "due_date", "created"];
		
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
				SELECT id, homework_name, homework_explanation, due_date, created
				FROM homework
				WHERE deleted IS NULL 
				AND (homework_name LIKE ? OR homework_explanation LIKE ? OR due_date LIKE ? OR created LIKE ?)
				ORDER BY $sort $orderBy
			");
			echo $this->connection->error;
			$searchWord = "%".$q."%";
			$stmt->bind_param("ssss", $searchWord, $searchWord, $searchWord, $searchWord);
			
			
		} else {
			
			$stmt = $this->connection->prepare("
				SELECT id, homework_name, homework_explanation, due_date, created
				FROM homework
				WHERE deleted IS NULL
				ORDER BY $sort $orderBy
			");
			
		}
		
		echo $this->connection->error;
		
		$stmt->bind_result($id, $homework_name, $homework_explanation, $due_date, $created);
		$stmt->execute();
		
		
		//tekitan massiivi
		$result = array();
		
		// tee seda seni, kuni on rida andmeid
		// mis vastab select lausele
		while ($stmt->fetch()) {
			
			//tekitan objekti
			$Homework = new StdClass();
			
			$Homework->id = $id;
			$Homework->homework_name = $homework_name;
			$Homework->homework_explanation = $homework_explanation;
			$Homework->due_date = $due_date;
			$Homework->created = $created;
			
			
			//echo $plate."<br>";
			// iga kord massiivi lisan juurde nr märgi
			array_push($result, $Homework);
		}
		
		$stmt->close();
		
		
		return $result;
	}
	
	function getSingle($edit_id){

		$stmt = $this->connection->prepare("SELECT homework_name, homework_explanation, due_date, created FROM homework WHERE id=? AND deleted IS NULL");

		$stmt->bind_param("i", $edit_id);
		$stmt->bind_result($homework_name, $homework_explanation, $due_date, $created);
		$stmt->execute();
		
		//tekitan objekti
		$Homework = new Stdclass();
		
		//saime ühe rea andmeid
		if($stmt->fetch()){
			// saan siin alles kasutada bind_result muutujaid
			$Homework->homework_name = $homework_name;
			$Homework->homework_explanation = $homework_explanation;
			$Homework->due_date = $due_date;
			$Homework->created = $created;
			
			
		}else{
			// ei saanud rida andmeid kätte
			// sellist id'd ei ole olemas
			// see rida võib olla kustutatud
			header("Location: data.php");
			exit();
		}
		
		$stmt->close();
		
		
		return $Homework;
		
	}

	function save ($homework_name, $homework_explanation, $due_date) {
		
		$stmt = $this->connection->prepare("INSERT INTO homework (homework_name, homework_explanation, due_date) VALUES (?, ?, ?)");
	
		echo $this->connection->error;
		
		$stmt->bind_param("sss", $homework_name, $homework_explanation, $due_date);
		
		if($stmt->execute()) {
			echo "Salvestamine onnestus";
		} else {
		 	echo "ERROR ".$stmt->error;
		}
		
		$stmt->close();
		
		
	}
	
	function update($id, $homework_name, $homework_explanation){
    	
		$stmt = $this->connection->prepare("UPDATE homework SET homework_name=?, homework_explanation=? WHERE id=? AND deleted IS NULL");
		$stmt->bind_param("ssi", $homework_name, $homework_explanation, $id);
		
		// kas õnnestus salvestada
		if($stmt->execute()){
			// õnnestus
			echo "Salvestus onnestus!";
		}
		
		$stmt->close();
		
		
	}
	
}

?>