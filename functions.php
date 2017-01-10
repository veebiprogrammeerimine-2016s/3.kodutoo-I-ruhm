<?php

	require("/home/regikriv/config.php");
	
	//alustan sessiooni, et saaks kasutada &_SESSION muutujaid
	session_start();
	
	$database = "if16_regiinakrivulina";
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
?>