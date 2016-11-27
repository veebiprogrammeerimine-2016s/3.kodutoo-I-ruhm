<?php

	require("/home/marikraav/config.php");
	// functions.php
	//var_dump($GLOBALS);
	
	$database = "if16_marikraav";
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);
	
	// see fail, peab olema kõigil lehtedel kus 
	// tahan kasutada SESSION muutujat
	session_start();
?>