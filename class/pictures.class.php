<?php

	class pictures{

		//private $name = "Romil";
		//public $familyName = "Robtsenkov";
		private $connection;
		
		// käivitatakse siis kui new ja see mis saadekse
		//sulgudesse new User(?) see jõuab siia
		function __construct($mysqli){
			
			// this viitab sellele klassile siin
			// selle klassi muutuja connection
			$this->connection = $mysqli;
		}

		function savePicture($author, $date_taken, $description){
		
			$stmt = $this->connection->prepare("INSERT INTO pic_info (author, date_taken, description) VALUES (?, ?, ?)");

			echo $mysqli->error;
			$stmt->bind_param("sss", $author, $date_taken, $description);
			
			if ($stmt->execute()) {
				echo "Salvestamine õnnestus!";
			}else{
				echo "ERROR ".$stmt->error;
			}
		}

		function getAllPics($q, $sort, $order){

			//Lubatud tulbad
			$allowedSort = ["id", "author", "date_taken", "description"];

			if(!in_array($sort, $allowedSort)){
			//ei olnud lubatud tulpade sees
			$sort = "id"; 
			//las sorteerib id järgi			
			}

			$orderBy = "ASC";
		
			if($order == "DESC"){
				$orderBy = "DESC";
			}

			echo "Sorteerin.. ".$sort." ".$orderBy." ";

			//Otsing
			if($q != ""){

				echo "Otsin: ".$q;
			
				$stmt = $this->connection->prepare("
					SELECT id, author, date_taken, description
					FROM pic_info
					WHERE deleted IS NULL
					AND (author LIKE ? OR date_taken LIKE ? OR description LIKE ?)
					ORDER BY $sort $orderBy
				");

				$searchWord = "%".$q."%";
				$stmt->bind_param("sss", $searchWord, $searchWord, $searchWord);
			
			}else{
				//ei otsi
				$stmt = $this->connection->prepare("
					SELECT id, author, date_taken, description
					FROM pic_info
					WHERE deleted IS NULL
					ORDER BY $sort $orderBy
				");
			}
				
			echo $this->connection->error;
			$stmt->bind_result($id, $author, $date_taken, $description);
			$stmt->execute();
			
			$result = array();
			
			// tsükkel töötab seni, kuni saab uue rea AB'i
			// nii mitu korda palju SELECT lausega tuli
			while ($stmt->fetch()) {
				//echo $note."<br>";
				
				$object = new StdClass();
				$object->id = $id;
				$object->author = $author;
				$object->date_taken = $date_taken;
				$object->description = $description;		
				
				array_push($result, $object);
				
			}
			
			return $result;
		}

		function deletePic($id){
		
		$stmt = $this->connection->prepare("
			UPDATE pic_info 
			SET deleted=NOW() 
			WHERE id=? AND deleted IS NULL
		");

		$stmt->bind_param("i", $id);
		
		// kas õnnestus salvestada
		if($stmt->execute()){
			// õnnestus
			echo "salvestus õnnestus!";
		}
		
		$stmt->close();
		
		}

		function updatePic($id, $author, $date_taken, $description){
				
		$stmt = $this->connection->prepare("UPDATE pic_info SET author=?, date_taken=?, description=? WHERE id=? AND deleted IS NULL");
		$stmt->bind_param("sssi", $author, $date_taken, $description, $id);
		
		// kas õnnestus salvestada
		if($stmt->execute()){
			// õnnestus
			echo "salvestus õnnestus!";
		}
		
		$stmt->close();
		
		}

		function getSinglePicData($edit_id){
    		
		$stmt = $this->connection->prepare("SELECT author, description FROM pic_info WHERE id=? AND deleted IS NULL");

		$stmt->bind_param("i", $edit_id);
		$stmt->bind_result($author, $description);
		$stmt->execute();
		
		//tekitan objekti
		$n = new Stdclass();
		
		//saime ühe rea andmeid
		if($stmt->fetch()){
			// saan siin alles kasutada bind_result muutujaid
			$n->author = $author;
			$n->description = $description;
			
		}else{
			// ei saanud rida andmeid kätte
			// sellist id'd ei ole olemas
			// see rida võib olla kustutatud
			header("Location: data.php");
			exit();
		}
		
		$stmt->close();		
		return $n;
		
	}
	}
?>