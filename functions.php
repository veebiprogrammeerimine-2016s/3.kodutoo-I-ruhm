<?php

	//functions.php
	require("../../config.php");
	//alustan sessiooni, et saaks kasutada $_SESSION muutujaid
	session_start();
	
	$database="if16_mariiviita";
	$mysqli=new mysqli($serverHost, $serverUsername, $serverPassword, $database);
		
?>