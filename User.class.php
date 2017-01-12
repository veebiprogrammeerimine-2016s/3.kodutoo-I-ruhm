<?php 
class User {
	
	private $connection;
	
	function __construct($mysqli){
		
		$this->connection = $mysqli;
		
	}

	
	/*TEISED FUNKTSIOONID */
	

	function login ($email, $password) {
		
		$error = "";
		
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		
		$stmt = $mysqli->prepare("
				SELECT id, email, password, created
				FROM signup
				WHERE email = ?
	");
	
		echo $mysqli->error;
		
		//asendan küsimärgi
		$stmt->bind_param("s", $email);
		
		//määran väärtused muutujatesse
		$stmt->bind_result($id, $emailFromDb, $passwordFromDb, $created);
		$stmt->execute();
		
		//andmed tulid andmebaasist või mitte
		// on tõene kui on vähemalt üks vaste
		if($stmt->fetch()){
			
			//võrdlen paroole
			$hash = hash("sha512", $password);
			if ($hash == $passwordFromDb) {
				
				echo "Kasutaja ".$id." logis sisse";
				
				//määran sessiooni muutujad, millele saan ligi
				// teistelt lehtedelt
				$_SESSION["userId"] = $id;
				$_SESSION["userEmail"] = $emailFromDb;
				
				header("Location: data.php");
				exit();
				
			}else {
				$error = "Vale parool. Palun proovige uuesti!";
			}
			
			
		} else {
			
			// ei leidnud kasutajat selle meiliga
			$error = "Sellise emailiga kasutajat ei ole!";
		}
		
		return $error;
		
	}
	
	function signUp ($email, $password, $age, $gender, $instrument) {
		
		
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		
		$stmt = $mysqli->prepare("INSERT INTO signup (email, password, age, gender, instrument) VALUES (?, ?, ?, ?, ?)");
		echo $mysqli->error;
		
		$stmt->bind_param("ssiss", $email, $password, $age, $gender, $instrument);
		
		if($stmt->execute()) {
			echo "Salvestamine õnnestus!";
		} else {
		 	echo "ERROR ".$stmt->error;
		}
		
		
	}

	
	
}
?>