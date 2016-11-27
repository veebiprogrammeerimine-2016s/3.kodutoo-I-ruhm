<?php

	require("../../../config.php");
     /*
	function sum ($x, $y) {
		
		return $x + $y;
	}
	
	echo sum (87656789,7656788675);
	echo "<br>";
	$answer = sum(10,15);
	echo $answer;
	echo "<br>";
	
	function hello ($firstname,$lastname) {
		
		return "Tere tulemast ".$firstname." " .$lastname."!";
	}
	
	echo hello ("Oskar","N.");
	echo "<br>";
	
    */

	////////////////
	///	Signup
	///////////////
	
	// alustan sessiooni et saaks kasutada $_SESSION muutujaid
	session_start();
	
	$database = "if16_case112";
	
	function signup ($email, $password, $name, $gender, $birthday) {
		
		$error = "";
		
		//ühendus
		$mysqli = new mysqli($GLOBALS["serverHost"],$GLOBALS["serverUsername"],$GLOBALS["serverPassword"],$GLOBALS["database"]);
		//käsk
		$stmt = $mysqli->prepare("INSERT INTO users (email, password, name, gender, birthday) VALUES (?, ?, ?, ?, ?)");
		
		echo $mysqli->error;
		//asendan küsimärgi väärtusetega
		//iga muutuja kohta 1 täht, mis tüüpi muutuja on
		// s- sring, i- integer, d- double/float
		$stmt->bind_param("sssss", $email, $password, $name, $gender, $birthday);
		
		if ($stmt->execute()) {
			
			
			echo "salvestamine õnnestus";
		} else {
			echo "ERROR ".$stmt->error;
		}
		
		
	}
	
	function login($email, $password) {
		
		//ühendus
		$mysqli = new mysqli($GLOBALS["serverHost"],$GLOBALS["serverUsername"],$GLOBALS["serverPassword"],$GLOBALS["database"]);
		//käsk
		$stmt = $mysqli->prepare(" SELECT id, email, password FROM users WHERE email = ?");
		
		echo $mysqli->error;
		//asendan küsimärgi
		$stmt->bind_param("s", $email);
		
		//määran tulpadele muutujad
		$stmt->bind_result($id, $emailFromDb, $passwordFromDb);
		$stmt->execute();
		
		if ($stmt->fetch()) {
			//oli rida
			
			//võrdlen paroole
			$hash = hash("sha512", $password);
			if ($hash == $passwordFromDb) {
				echo "Kasutaja ".$id." logis sisse.";
				
				$_SESSION["userId"] = $id;
				$_SESSION["email"] = $emailFromDb;
				
				//suunan kasutaja uuele lehele
				header("Location: data.php");
				exit();
				
			} else {
				$error = "Vale parool!";
			}
			
		} else {
			//ei olnud
			
			$error = "Sellise emailiga ".$email." kasutajat ei olnud!";
			
			
		}
		
		return $error;
		
	}
	
	//andmete salvestamine andmebaasi
	function saveCars ($make, $model, $fuel, $carcolor) {
		
		$error = "";
		
		//ühendus
		$mysqli = new mysqli($GLOBALS["serverHost"],$GLOBALS["serverUsername"],$GLOBALS["serverPassword"],$GLOBALS["database"]);
		//käsk
		$stmt = $mysqli->prepare("INSERT INTO cars (make, model, fuel, carcolor) VALUES (?, ?, ?, ?)");
		
		echo $mysqli->error;
		//asendan küsimärgi väärtusetega
		//iga muutuja kohta 1 täht, mis tüüpi muutuja on
		// s- sring, i- integer, d- double/float
		$stmt->bind_param("ssss", $make, $model, $fuel, $carcolor);
		
		if ($stmt->execute()) {
			
			
			echo "Salvestamine õnnestus!"."<br>";
		} else {
			echo "ERROR ".$stmt->error;
		}
		
		
	}
	
	
	//fetchib andmeid andmebaasist
	function getAllCars ($q, $sort, $order) {
		$mysqli = new mysqli($GLOBALS["serverHost"],$GLOBALS["serverUsername"],$GLOBALS["serverPassword"],$GLOBALS["database"]);
		
		$allowedSort = ["id", "make", "model", "fuel", "carcolor"];
		
		if(!in_array($sort, $allowedSort)){
			// ei ole lubatud tulp
			$sort = "id";
		}
		
		$orderBy = "ASC";
		
		if ($order == "DESC") {
			$orderBy = "DESC";
		}
		echo "Sorteerin: ".$sort." ".$orderBy." ";
		
		
		//kas otsib
		if ($q != "") {
				
			echo "Otsib: ".$q;
			
			$stmt = $mysqli->prepare(" SELECT id, make, model, fuel, carcolor FROM cars WHERE deleted is NULL AND (make LIKE ? OR model LIKE ?) ORDER BY $sort $orderBy ");
			$searchWord = "%".$q."%";
			$stmt->bind_param("ss", $searchWord, $searchWord);
			
		}else {
			$stmt = $mysqli->prepare("SELECT id, make, model, fuel, carcolor FROM cars WHERE deleted is NULL ORDER BY $sort $orderBy ");
		}
		
		echo $mysqli->error;
		
		$stmt->bind_result($id, $make, $model, $fuel, $carcolor);
		$stmt->execute();
		
		$result = array();
		
		
		//seni kuni on 1 rida andmeid saada (10 rida = 10 korda)
		while ($stmt->fetch()) {
			
			$singleCar = new StdClass();
			$singleCar->id = $id;
			$singleCar->make = $make;
			$singleCar->model = $model;
			$singleCar->fuel = $fuel;
			$singleCar->carcolor = $carcolor;
			
			//echo $color."<br>";
			array_push($result, $singleCar);
			
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



?>