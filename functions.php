<?php

	require("../../config.php");
	//alustan sessiooni, et saaks kasutada $_SESSION muutujaid
	session_start();

	//****************
	//*****SIGNUP*****
	//****************
	
	$database = "if16_karoku";
	
	function signup ($email, $password, $username, $gender) {
		
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	
		$stmt = $mysqli->prepare("INSERT INTO user_sample (email, password) VALUES (?, ?)");
		echo $mysqli->error;
		
		$stmt->bind_param("ss", $email, $password);
		
		if ($stmt->execute()) {
			echo "salvestamine õnnestus";
		} else {
			echo "ERROR".$stmt->error;
		}
	}

	
	function login($email, $password) {
		
		$error = "";
		
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	
		$stmt = $mysqli->prepare("SELECT id, email, password, created FROM user_sample WHERE email = ?");
		
		echo $mysqli->error;
		
		//asendan küsimärgi
		$stmt->bind_param("s", $email);
		
		//määran tulpadele muutujad
		$stmt->bind_result($id, $emailFromDatabase, $passwordFromDatabase, $created);
		$stmt->execute();
		
		//küsin rea andmeid
		if($stmt->fetch()) {
			//oli rida
			
			//võrdlen paroole
			$hash = hash ("sha512", $password);
			if($hash == $passwordFromDatabase) {
				echo "kasutaja ".$id." logis sisse";
				
				$_SESSION["userId"] = $id;
				$_SESSION["email"] = $emailFromDatabase;
				
				//suunaks uuele lehele
				header("Location: data.php");
				exit();
				
			} else {
				$error = "Parool on vale";
			}
			
		} else {
			//ei olnud
			$error = "Sellise emailiga ".$email."kasutajat ei olnud";
			
		}
		
		return $error;
		
	}
	
	// DATA.PHP
	
	function Books ($author, $title) {
		
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	
		$stmt = $mysqli->prepare("INSERT INTO Books (author, title) VALUES (?, ?)");
		echo $mysqli->error;
		
		$stmt->bind_param("ss", $author, $title);
		
		if ($stmt->execute()) {
			echo "Salvestamine õnnestus";
		} else {
			echo "ERROR".$stmt->error;
		}
	}
	
	function AllBooks() {
		
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	
		$stmt = $mysqli->prepare("SELECT id, author, title, created FROM Books");
		
		echo $mysqli->error;

		$stmt->bind_result($id, $author, $title, $created);
		$stmt->execute();
		
		//array("Karoliina", "Kullamaa")
		$result = array();
		
		//seni kuni on üks rida andmeid saada (10 rida = 10 korda)
		while ($stmt->fetch()) {
			
			$person = new StdClass();
			$person->id = $id;
			$person->author = $author;
			$person->title = $title;
			$person->created = $created;
			
			//echo $color."<br>";
			array_push($result, $person);
		}
		
		$stmt->close();
		$mysqli->close();
		
		return $result;
	}
	
	function cleanInput($input) {
		
		$input = trim($input);
		$input = stripslashes($input);
		$input = htmlspecialchars($input);
		
		return $input;
		
	}
	
	// USER.PHP
	
	function saveFavorite ($favorite) {
		
		$database = "if16_karoku";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);

		$stmt = $mysqli->prepare("INSERT INTO favorites (favorite) VALUES (?)");
	
		echo $mysqli->error;
		
		$stmt->bind_param("s", $favorite);
		
		if($stmt->execute()) {
			echo "salvestamine õnnestus";
		} else {
		 	echo "ERROR ".$stmt->error;
		}
		
		$stmt->close();
		$mysqli->close();
		
	}
	
	function saveUserFavorite ($favorite_id) {
		
		//echo "Lemmik raamat: ".$favorite_id."<br>";
 		//echo "Kasutaja: ".$_SESSION["userId"]."<br>";
		
		$database = "if16_karoku";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);
		
		//kas on juba selline raamat olemas
		$stmt = $mysqli->prepare("SELECT id FROM user_favorites WHERE user_id=? AND favorite_id=?");
		$stmt->bind_param("ii", $_SESSION["userId"],$favorite_id);
		$stmt->execute();
		
		if ($stmt->fetch()) {
			//oli olemas
			echo "Juba olemas";
			
			//ei jätka salvestamisega
			return;
		}
		$stmt->close();
		//jätkan salvestamisega..
		

		$stmt = $mysqli->prepare("INSERT INTO user_favorites (user_id, favorite_id) VALUES (?, ?)");
	
		echo $mysqli->error;
		
		$stmt->bind_param("ii", $_SESSION["userId"],$favorite_id);
		
		/*if($stmt->execute()) {
			echo "Salvestamine õnnestus";
		} else {
		 	echo "ERROR ".$stmt->error;}*/ //proovimiseks, kas töötab
		
		$stmt->close();
		$mysqli->close();
		
	}
	
	function getAllFavorites() {
		
		$database = "if16_karoku";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);
		
		$stmt = $mysqli->prepare("
			SELECT id, favorite
			FROM favorites
		");
		echo $mysqli->error;
		
		$stmt->bind_result($id, $favorite);
		$stmt->execute();
		
		
		//tekitan massiivi
		$result = array();
		
		// tee seda seni, kuni on rida andmeid
		// mis vastab select lausele
		while ($stmt->fetch()) {
			
			//tekitan objekti
			$i = new StdClass();
			
			$i->id = $id;
			$i->favorite = $favorite;
		
			array_push($result, $i);
		}
		
		$stmt->close();
		$mysqli->close();
		
		return $result;
	}
	
	function getAllUserFavorites() {
		
		$database = "if16_karoku";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);
		
		$stmt = $mysqli->prepare("
		SELECT favorite FROM favorites JOIN user_favorites
		ON favorites.id=user_favorites.favorite_id 
		WHERE user_favorites.user_id = ?");
		
		echo $mysqli->error;
		
		$stmt->bind_param("i", $_SESSION["userId"]);
		
		$stmt->bind_result($favorite);
		$stmt->execute();
	
		$result = array();

		while ($stmt->fetch()) {
			
			$i = new StdClass();
	
			$i->favorite = $favorite;
		
			array_push($result, $i);
		}
		
		$stmt->close();
		$mysqli->close();
		
		return $result;
	}
	
?>