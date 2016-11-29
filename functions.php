<?php

	require("/home/johareil/config.php");

	/* ALUSTAN SESSIOONI */
	session_start();
		
	/* ÜHENDUS */
	$database = "if16_JohanR";
	$mysqli = new mysqli($serverHost, $serverUsername, $serverPassword, $database);
	

?>