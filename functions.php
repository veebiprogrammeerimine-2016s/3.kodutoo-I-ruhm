<?php

require("../../config.php");

	
	// see fail, peab olema kõigil lehtedel kus 
	// tahan kasutada SESSION muutujat
	session_start();

	$database = "if16_jant";
	$mysqli = new mysqli($serverHost, $serverUsername,  $serverPassword, $database);
	
	function getAllInstruments() {
		
		$database = "if16_jant";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);
		
		$stmt = $mysqli->prepare("
			SELECT age, gender, instrument
			FROM signup
		");
		echo $mysqli->error;
		
		$stmt->bind_result($age, $gender, $instrument);
		$stmt->execute();
		
		
		//tekitan massiivi
		$result = array();
		
		// tee seda seni, kuni on rida andmeid
		// mis vastab select lausele
		while ($stmt->fetch()) {
			
			//tekitan objekti
			$kasutaja = new StdClass();
			
			$kasutaja->age = $age;
			$kasutaja->gender = $gender;
			$kasutaja->instrument = $instrument;
			
			//echo $plate."<br>";
			// iga kord massiivi lisan juurde nr märgi
			array_push($result, $kasutaja);
		}
		
		$stmt->close();
		$mysqli->close();
		
		return $result;
	}
	
	
	function getSingleInstrumentData($edit_id){
    
        $database = "if16_jant";
		//echo "id on ".$edit_id;
		
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);
		
		$stmt = $mysqli->prepare("SELECT instrument FROM signup WHERE id=? AND deleted IS NULL");
		$stmt->bind_param("i", $edit_id);
		$stmt->bind_result($instrument);
		$stmt->execute();
		
		//tekitan objekti
		$pill = new Stdclass();
		
		//saime ühe rea andmeid
		if($stmt->fetch()){
			// saan siin alles kasutada bind_result muutujaid
			$pill->instrument = $instrument;
			
			
			
		}else{
			// ei saanud rida andmeid kätte
			// sellist id'd ei ole olemas
			// see rida võib olla kustutatud
			header("Location: data.php");
			exit();
		}
		
		$stmt->close();
		$mysqli->close();
		
		return $pill;
		
	}
	function updateInstrument($id, $instrument){
    	
        $database = "if16_jant";
		
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);
		
		$stmt = $mysqli->prepare("UPDATE signup SET instrument =? WHERE id=?");
		$stmt->bind_param("si",$instrument, $id);
		
		// kas õnnestus salvestada
		if($stmt->execute()){
			// õnnestus
			echo "salvestus õnnestus!";
		}
		
		$stmt->close();
		$mysqli->close();
		
	}
	
	
	function deleteInstrument($id){
    	
        $database = "if16_jant";
		
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);
		
		$stmt = $mysqli->prepare("UPDATE signup SET deleted=NOW() WHERE id=? AND deleted IS NULL");
		$stmt->bind_param("s",$id);
		
		// kas õnnestus salvestada
		if($stmt->execute()){
			// õnnestus
			echo "salvestus õnnestus!";
		}
		
		$stmt->close();
		$mysqli->close();
		
	}
	
	
	
	function cleanInput($input) {
		
		//input = "romiL@tlu.ee   "
		
		$input = trim($input);
		
		//input = "romiL@tlu.ee"
			
		$input = stripslashes($input);
		$input = htmlspecialchars($input);
		return $input;
		
	}
	

?>