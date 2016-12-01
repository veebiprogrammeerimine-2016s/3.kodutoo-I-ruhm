<?php
	
	require_once("../../config.php");
	require_once("class/User.class.php");
	require_once("class/Solve.class.php");
	require_once("class/Scramble.class.php");
	
	$database = "if16_randotm_web";
	$link = new mysqli($serverHost, $serverUsername, $serverPassword, $database);
	
	$User = new User($link);
	$Solve = new Solve($link);
	$Scramble = new Scramble();
	
	date_default_timezone_set("Europe/Tallinn");
	$now = Date("Y/m/d H:i:s");
	
	//alustan sessiooni, et saaks kasutada $_SESSION muutujaid
	session_start();
	
	function clean_input($input) {
		$input = trim($input);
		$input = stripslashes($input);
		$input = htmlspecialchars($input);
		return $input;
	}

	function checkField ($field) {
		if (isset ($field) ) {
			if (empty ($field) ) {
				$fieldError = "Väli on kohustuslik!";
			} else {
				$fieldError = "";
			}
		}
		return $fieldError;
	}
?>