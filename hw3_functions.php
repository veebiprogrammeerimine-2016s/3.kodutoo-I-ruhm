<?php
	//functions.php
	
	//require("../../config.php"); //nõuab seda faili ja crashib kui ei saa
	require_once("../../config.php"); //kontrollib kas see fail on juba lisatud, võtab vähem ressurssi 
	
	//alustan sessiooni, et saaks kasutada $_SESSION muutujaid
	session_start(); //sessioon töötab, isegi kui kasutaja pole veel sisse logind
	
	//********************
	//****** SIGNUP ******
	//********************
	//GLOBALSI SEES kõik GET, POST, COOKIE ja FILES andmed, näeme kõiki muutujaid, mis pole funktsioonides
	//var_dump($GLOBALS);
	//$GLOBALS is a PHP super global variable which is used to access global variables from anywhere in the PHP script (also from within functions or methods).
	
	$database = "if16_marikraav"; //database väljapoole nähtav
	function signup ($firstName, $lastName, $email, $password, $gender, $phoneNumber){
		//selle sees muutujad pole väljapoole nähtavad
		
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		
		$stmt = $mysqli->prepare("INSERT INTO user_sample(firstname, lastname, email, password, gender, phonenumber) VALUES(?,?,?,?,?,?)");
		echo $mysqli->error;
		
		$stmt->bind_param("ssssss", $firstName, $lastName, $email, $password, $gender, $phoneNumber); //$signupEmail emailiks lihtsalt
		
		if($stmt->execute()) {
			echo "Salvestamine õnnestus.";
		} else {
			echo "ERROR".$stmt->error;
		}
	}
	
	function login ($email, $password){
		
		$error = "";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		
		$stmt = $mysqli->prepare("
			SELECT id, firstname, email, password, created
			FROM user_sample
			WHERE email = ?
		");
		echo $mysqli->error;
		
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
	
	function createNewPost($subject, $content, $user, $email){
		
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		
		$stmt = $mysqli->prepare("INSERT INTO topics(subject, content, user, email) VALUES(?,?,?,?)");
		echo $mysqli->error;
		
		$stmt->bind_param("ssss", $subject, $content, $user, $email); 
		
		if($stmt->execute()) {
			echo "Salvestamine õnnestus.<br>";
		} else {
			echo "ERROR".$stmt->error;
		}
	}
	
	function createNewReply($content, $subject_id, $user, $email){
		
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		
		$stmt = $mysqli->prepare("INSERT INTO replies(content, user, email, topic_id) VALUES(?,?,?,?)");
		echo $mysqli->error;
		
		$stmt->bind_param("sssi", $content, $user, $email, $subject_id); 
		
		if($stmt->execute()) {
			echo "Vastus on lisatud.<br><br>";
		} else {
			echo "ERROR".$stmt->error;
		}
		
	}
	
	function addPostToArray (){
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		
		$stmt = $mysqli->prepare("
			SELECT id, subject, created, user, email
			FROM topics
		");
		echo $mysqli->error;
		
		$stmt->bind_result ($id, $subject, $date, $user, $email);
		$stmt-> execute();
		
		$result = array();

		while ($stmt->fetch()){	
			$topic = new StdClass();
			$topic->id = $id;
			$topic->subject = $subject;
			$topic->created = $date;
			$topic->user = $user;
			$topic->email = $email;
			
			array_push ($result, $topic);
			$_SESSION["subject"] = $subject;
		}
		$stmt->close();
		$mysqli->close();
		
		return $result;
	}
	
	function addReplyToArray ($topic_id){
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		
		$stmt = $mysqli->prepare("
			SELECT id, content, created, user, email
			FROM replies
			WHERE topic_id=?
		");
		echo $mysqli->error;
		
		$stmt->bind_param("i", $topic_id);
		
		$stmt->bind_result($id, $content, $created, $user, $email);
		$stmt-> execute();
		
		$result = array();
		
		while ($stmt->fetch()){	
			$reply = new StdClass();
			$reply->id = $id;
			$reply->content = $content;
			$reply->created = $created;
			$reply->user = $user;
			$reply->email = $email;
		
			array_push ($result, $reply);
		}
		$stmt->close();
		$mysqli->close();
		
		return $result;
	}
	
	function cleanInput($input){
		
		$input = trim($input); // trim($str)-  only whitespace from the beginning and end. Muidu andmebaasi jätab tühikud isegi kui lehele ei jäta!
		$input = stripslashes($input); // function removes \
		$input = htmlspecialchars($input); //The htmlspecialchars() function converts some predefined characters to HTML
		
		return $input;
		
	}
	
	function getTopic($topic_id){
		
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli-> prepare("SELECT subject, content, created, user, email FROM topics WHERE id=? ");
		
		echo $mysqli->error;

		$stmt->bind_param("i", $topic_id);
		$stmt->bind_result($subject, $content, $created, $user, $email);
		$stmt->execute();
		
		//tekitan objekti
		$topic = new Stdclass();
		
		//saime ühe rea andmeid
		if($stmt->fetch()){
			// saan siin alles kasutada bind_result muutujaid
			$topic->subject = $subject;
			$topic->content = $content;
			$topic->created = $created;
			$topic->user = $user;
			$topic->email = $email;
			
		}else{
			// ei saanud rida andmeid kätte
			// sellist id'd ei ole olemas
			// see rida võib olla kustutatud
			header("Location: hw3_data.php");
			exit();
		}
		
		$stmt->close();
		//$mysqli->close();
		
		return $topic;
	}	
	
?>