<?php

	require("/home/karolku/config.php");
	
	$database = "if16_karoku";
	$mysqli = new mysqli($serverHost, $serverUsername, $serverPassword, $database);
	
	session_start();
?>