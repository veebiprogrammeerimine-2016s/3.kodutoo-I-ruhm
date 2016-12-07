<?php
	require("/home/elispals/config.php");
	
	$database = "if16_epals";
	$mysqli = new mysqli($serverHost, $serverUsername, $serverPassword, $database);
	
	session_start();
?>