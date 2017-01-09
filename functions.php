<?php

    require("/home/tanelmaas/config.php");
	// functions.php
	//var_dump($GLOBALS);
		/* ÜHENDUS */
	$database = "if16_Tanelmaas_1";
	$mysqli = new mysqli($serverHost, $serverUsername, $serverPassword, $database);
	
	require("../class/User.class.php");
	$User = new User($mysqli);
	
	require("../class/Apartment.class.php");
	$Apartment= new Apartment($mysqli);
	
	require("../class/Interest.class.php");
	$Interest= new Interest ($mysqli);
	
	require("../class/Helper.class.php");
	$Helper = new Helper();
	
	// see fail, peab olema kõigil lehtedel kus 
	// tahan kasutada SESSION muutujat
	session_start();
	

	function cleanInput($input) {
		
		//input = "romiL@tlu.ee   "
		
		$input = trim($input);
		
		//input = "romiL@tlu.ee"
			
		$input = stripslashes($input);
		$input = htmlspecialchars($input);
		return $input;
		
	}
		
	
	/*function sum ($x, $y) {
		
		return $x + $y;
		
	}
	
	function hello ($firstname, $lastname) {
		
		return "Tere tulemast ".$firstname." ".$lastname."!";
		
	}
	
	echo sum(5476567567,234234234);
	echo "<br>";
	$answer = sum(10,15);
	echo $answer;
	echo "<br>";
	echo hello ("Romil", "R.");
	*/
	
	
	/*
	
	function issetAndNotEmpty($var) {	
		if ( isset ( $var ) ) {
			if ( !empty ($var ) ) {
				return true;			
			}	
		} 
		
		return false;	
	}
	
	if (issetAndNotEmpty($_POST["loginEmail"])) {
		
		//vastab tõele
		
	}
	
	
	
	
	*/
