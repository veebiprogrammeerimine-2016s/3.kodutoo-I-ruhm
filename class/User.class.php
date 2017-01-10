<?php 
class User {
	
	private $connection;
	
	function __construct($mysqli){
		
		//this viitab klassile (this == User)
		$this->connection = $mysqli;
		
	}
	
	/*login ja signup (Logi sisse ja loo kasutaja) funktsioonid*/
	
	function login ($email, $password) {
		
		$error = "";
		
		$stmt = $this->connection->prepare("
		SELECT id, email, password, created 
		FROM user_sample
		WHERE email = ?");
	
		echo $this->connection->error;
		
		//asendan k¸sim‰rgi
		$stmt->bind_param("s", $email);
		
		//m‰‰ran v‰‰rtused muutujatesse
		$stmt->bind_result($id, $emailFromDb, $passwordFromDb, $created);
		$stmt->execute();
		
		//andmed tulid andmebaasist vıi mitte
		// on tıene kui on v‰hemalt ¸ks vaste
		if($stmt->fetch()){
			//oli sellise meiliga kasutaja
			//password millega kasutaja tahab sisse logida, vırdlen neid paroole
			$hash = hash("sha512", $password);
			if ($hash == $passwordFromDb) {
				
				echo "kasutaja ".$id." logis sisse";
				
				//m‰‰ran sessiooni muutujad, millele saan ligi teistelt lehtedelt
				$_SESSION["userId"] = $id;
				$_SESSION["userEmail"] = $emailFromDb;
				$_SESSION["message"] = "<h1>Tere tulemast!</h1>";
				
				//suunab uuele lehele, kus kasutaja saab andmeid sisestama hakata
				header("Location: data.php");
				exit();
				
			} else {
				$error = "Parool on vale! Proovi uuesti.";
			}
			
		} else {
			
			// ei leidnud kasutajat selle meiliga
			$error = "Sellise emailiga ".$email." kasutajat ei ole.";
		}
		
		return $error;
	}
	
	function signUp ($signupFirstName, $signupLastName, $signupBirthyear, $signupEmail, $signupPassword, $gender) {
		
		$stmt = $this->connection->prepare("INSERT INTO user_sample (firstname, lastname, birthyear, email, password, gender) VALUES (?, ?, ?, ?, ?, ?)");
		echo $this->connection->error;
		
		$stmt->bind_param("ssisss", $signupFirstName, $signupLastName, $signupBirthyear, $signupEmail, $signupPassword, $gender);
		
		$msg = "";
		if($stmt->execute()) {
			//echo "Salvestamine ınnestus.";
			$msg = "KASUTAJA LOODUD!";
		} else {
			//echo "ERROR".$stmt->error;
			$msg = "<p style='color:red;'>SELLINE E-POST ON JUBA KASUTUSEL!</p>";
		}
		
		return $msg;	
	}
} 
?>