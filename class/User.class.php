<?php
class User {
	
	//klassi sees saab kasutada
	private $connection;
	
	
	// $User= new User(see); jõuab siia sulgude vahele
	function __construct ($mysqli) {
		
		//klassi sees muutuja kasutamiseks $this->
		$this->connection = $mysqli;
		
		
	}
	
	/* TEISED FUNKTSIOONID  */
	
	function login ($email, $password){
		
		$error = "";
		
		$this->connection = new $this->connection($GLOBALS["serverHost"],$GLOBALS["serverUsername"],$GLOBALS["serverPassword"], $GLOBALS["database"]);
		
			$stmt = $this->connection->prepare("
			SELECT id, email, password, created
			FROM user_sample
			WHERE email = ?");
			
		echo $this->connection->error;
		
		//asendan küsimärgi
		$stmt->bind_param("s", $email);
		
		//määran tulpadele muutujad
		$stmt->bind_result($id, $emailFromDb, $passwordFromDb, $created);
		$stmt->execute();
		
		//küsin rea andmeid
		if($stmt->fetch()){
				//oli rida
				
				//võrdlen paroole
				$hash = hash("sha512", $password);
				if($hash == $passwordFromDb){
					
					echo "User logged in ".$id;
					
					$_SESSION["userId"] = $id;
					$_SESSION["userEmail"] = $emailFromDb;
					
					$_SESSION["message"] = "<h1>Welcome!</h1>";
					
					//suunaks uuele lehele
					header("Location: data.php");
					
				} else {
					$error = "Wrong password!";
				}
						
		} else {
			//ei olnud
			
			$error = "Wrong email!";
			
			
		}		
		
		return $error;
	
	}	
	
	function signUp ($email, $password, $name){
		
		
		$this->connection = new $this->connection($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		
		$stmt = $this->connection->prepare("INSERT INTO user_sample (email, password) VALUES (?, ?)");
		echo $this->connection->error;
		
		$stmt->bind_param("ss", $email, $password);
		
		if ($stmt->execute()) {
			echo "Saved!";
	   } else {
		   echo "ERROR ".$stmt->error;
	   }
	   
		$stmt->close();
		$this->connection->close();
	   
		
	}	
	
	
	
}




?>