<?php 
class User {
	
	private $connection;
	
	function __construct($mysqli){
		$this->connection = $mysqli;
	}
	
	
	function signup ($email, $password, $username, $gender) {
	
		$stmt = $this->connection->prepare("INSERT INTO user_sample (email, password) VALUES (?, ?)");
		echo $this->connection->error;
		
		$stmt->bind_param("ss", $email, $password);
		
		if ($stmt->execute()) {
			echo "Salvestamine õnnestus";
		} else {
			echo "ERROR ".$stmt->error;
		}
	}
	
	function login($email, $password) {
		
		$error = "";
	
		$stmt = $this->connection->prepare("SELECT id, email, password, created FROM user_sample WHERE email = ?");
		
		echo $this->connection->error;
		
		//asendan küsimärgi
		$stmt->bind_param("s", $email);
		
		//määran tulpadele muutujad
		$stmt->bind_result($id, $emailFromDatabase, $passwordFromDatabase, $created);
		$stmt->execute();
		
		//küsin rea andmeid
		if($stmt->fetch()) {
			
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
} ?>