<?php class User {
	
	//klassi sees saab kasutada 
	private $connection;
	
	//2 alakriipsu järjest __construct
	//$User = new User(see); jõuab siia sulgude vahele
	//$mysqli - võtan ühenduse vastu functions.php failist
	function __construct($mysqli) {
		//klassi sees muutuja kasutamiseks $this-> ...seda private $connectioni, ilma this kasutamata klassi enda uus muutuja $connection
		//$this viitab sellele klassile
		$this->connection = $mysqli;
	}
	
	/*TEISED FUNKTSIOONID*/
	
	function signup ($firstName, $lastName, $email, $password, $gender, $phoneNumber){
		//selle sees muutujad pole väljapoole nähtavad
		
		//$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		
		$stmt = $this->connection->prepare("INSERT INTO user_sample(firstname, lastname, email, password, gender, phonenumber) VALUES(?,?,?,?,?,?)");
		echo $this->connection->error;
		
		$stmt->bind_param("ssssss", $firstName, $lastName, $email, $password, $gender, $phoneNumber); //$signupEmail emailiks lihtsalt
		
		if($stmt->execute()) {
			echo "Salvestamine õnnestus.";
		} else {
			echo "ERROR".$stmt->error;
		}
	}
	
	function login ($email, $password){
		
		$error = "";
		
		$stmt = $this->connection->prepare("
			SELECT id, firstname, email, password, created
			FROM user_sample
			WHERE email = ?
		");
		echo $this->connection->error;
		
		//asendan küsimärgi
		$stmt->bind_param("s", $email); //s-string
		
		//määran tulpadele muutujad
		$stmt->bind_result($id, $firstNameFromDb, $emailFromDb, $passwordFromDb, $created); //Db-database
		$stmt->execute(); //päring läheb läbi executiga, isegi kui ühtegi vastust ei tule
		
		if($stmt->fetch()) { //fetch küsin rea andmeid
			//oli rida
			//võrdlen paroole 
			$hash = hash("sha512", $password);
			if($hash == $passwordFromDb){
				echo "kasutaja ".$id." logis sisse";
				
				$_SESSION["userId"] = $id;
				$_SESSION["email"] = $emailFromDb;
				$_SESSION["firstName"] = $firstNameFromDb;
				
				//suunaks uuele lehele
				header("Location: hw3_data.php");
				exit();
				
			} else {
				$error = "Parool on vale!";
			}
		} else {
			//ei olnud
			$error = "Sellise emailiga ".$email." kasutajat ei ole.";
		}
		
		return $error;
	}
}

?>