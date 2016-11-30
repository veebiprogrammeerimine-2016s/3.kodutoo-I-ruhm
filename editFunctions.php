<?php

	require_once("../../config.php");
	
	function AllBooksData($edit_id){
    
        $database = "if16_karoku";
		
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);
		
		$stmt = $mysqli->prepare("SELECT author, title FROM Books WHERE id=? AND deleted IS NULL");

		$stmt->bind_param("i", $edit_id);
		$stmt->bind_result($author, $title);
		$stmt->execute();
		
		//tekitan objekti
		$person = new Stdclass();
		
		//saime ühe rea andmeid
		if($stmt->fetch()){
			// saan siin alles kasutada bind_result muutujaid
			$person->author = $author;
			$person->title = $title;

			}else{
				// ei saanud rida andmeid kätte
				// sellist id'd ei ole olemas
				// see rida võib olla kustutatud
				header("Location: data.php");
				exit();
		}
		
		$stmt->close();
		$mysqli->close();
		
		return $person;
	}


	function updateBook($id, $author, $title){
    	
        $database = "if16_karoku";
		
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);
		
		$stmt = $mysqli->prepare("UPDATE Books SET author=?, title=? WHERE id=? AND deleted IS NULL");
		$stmt->bind_param("ssi",$author, $title, $id);
		
		// kas õnnestus salvestada
		if($stmt->execute()){
			// õnnestus
			echo "salvestus õnnestus!";
		}
		
		$stmt->close();
		$mysqli->close();
		
	}
	
	
	//KUSTUTAMINE
	function deleteBook($id){
     	
        $database = "if16_karoku";
 		
 		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);
 		
 		$stmt = $mysqli->prepare("UPDATE Books SET deleted=NOW() WHERE id=? AND deleted IS NULL");
 		$stmt->bind_param("i",$id);
 		
		// kas õnnestus salvestada
		if($stmt->execute()){
 			// õnnestus
 			echo "salvestus õnnestus!";
 		}
	
 		$stmt->close();
 		$mysqli->close();
 		
 	}
?>