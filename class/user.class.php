<?php

	class user{


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

		function signup($email, $password, $gender, $birthdate){

			$stmt =  $this->connection->prepare("INSERT INTO registered_users (email, password, gender, birthdate) VALUES (?, ?, ?, ?)");
			
			echo $mysqli->error;
			$stmt->bind_param("ssss", $email, $password, $gender, $birthdate);
			
			if ($stmt->execute()) {
				echo "Registreerimine õnnestus!";
			}else{
				echo "ERROR ".$stmt->error;
			}
		}
		
		function login($email, $password){
			
			$error = "";

			$stmt = $this->connection->prepare("
				SELECT id, email, password, gender, birthdate, created
				FROM registered_users
				WHERE email = ?
			");

			echo $mysqli->error;
			
			//Asendan küsimärgi
			$stmt->bind_param("s", $email);
			
			//Määran tupladele muutujad
			$stmt->bind_result($id, $emailFromDb, $passwordFromDb, $genderFromDb, $birthdateFromDb, $created);
			$stmt->execute();
			
			//Küsin rea andmeid
			if($stmt->fetch()) {
				//Oli rida
			
				//Võrdlen paroole
				$hash = hash("sha512", $password);
				if($hash == $passwordFromDb) {
					
					echo "kasutaja ".$id." logis sisse";
					
					$_SESSION["userId"] = $id;
					$_SESSION["email"] = $emailFromDb;
					
					//Suunaks uuele lehele
					header("Location: data.php");
					exit();
					
				}else{
					$error = "Vale parool!";
				}
				
			}else{
				//Ei olnud
				$error = "E-mailiga ".$email." kasutajat ei eksisteeri!";
			}
			return $error;
		}
	}

?>