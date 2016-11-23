<?php

class User{
	
	private $connection;
	
	function __construct($mysqli){
		
		$this->connection=$mysqli;
	}


	function signup ($Name, $Age, $Email, $password, $gender){
		
		//käsk
		$stmt=$this->connection->prepare("INSERT INTO user_sample (Name, Age, Email, password, gender) VALUES(?,?,?,?,?)");
		
		echo $this->connection->error;
		
		//asendan küsimärgi väärtustega
		//iga muutuja kohta üks täht, mis tüüpi muutuja on
		//s-string
		//i-integer
		//d-double/float
		$stmt->bind_param("sisss",$Name, $Age, $Email, $password, $gender);
		
		if($stmt->execute()) {
			echo "Salvestamine õnnestus";
			
		} else {
				echo "ERROR ".$stmt->error;
		
		}
			
	}
	
	function login($Email, $password){
		
		$error="";
		
		$stmt=$this->connection->prepare("
		
			SELECT id, Email, password, created, Name
			FROM user_sample
			WHERE Email=?
						
		");
			
		echo $this->connection->error;
		
		// asendan küsimärgi
		
		$stmt->bind_param("s", $Email);
		
		//määran tulpadele muutujad
		$stmt->bind_result($id, $EmailFromDB, $passwordFromDB, $created, $NameFromDB);
		
		$stmt->execute();
		
		//küsin rea andmeid
		if($stmt->fetch()) {
			//oli rida
			//võrdlen paroole
			$hash=hash("sha512", $password);
			if ($hash==$passwordFromDB) {
				
				echo "kasutaja ".$id." logis sisse";
				
				
				$_SESSION["userId"]=$id;
				$_SESSION["Email"]=$EmailFromDB;
				$_SESSION["name"]=$NameFromDB;
				
				//suunaks uuele lehele
				header("Location: data.php");
				exit();
				
			} else {
				$error="parool vale";
			}
					
		} else {
			//ei olnud
			
			$error="sellise emailiga ".$Email." kasutajat ei olnud";
			
		}
		
		
		return $error;
		
		
	}
	
	
}
?>	