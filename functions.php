<?php
	
	// functions.php
	require("/home/rauntege/config.php");
	
	// et saab kasutada $_SESSION muutujaid
	// kõigis failides mis on selle failiga seotud
	session_start(); 
	
	/* ÜHENDUS */
	$database = "if16_raunot_web";
	$mysqli = new mysqli($serverHost, $serverUsername,  $serverPassword, $database);
	
	
	function cleanInput($input){
		
		$input = trim($input);
		$input = stripslashes($input);
		$input = htmlspecialchars($input);
		
		return $input;
	}

?>