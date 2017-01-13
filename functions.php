<?php

	require("../../config.php");
	
	$database = "if16_siim_1";
	$mysqli = new mysqli($serverHost, $serverUsername, $serverPassword, $database);
	
	require("class/User.class.php");
	$User = new User($mysqli);
	require("class/Homework.class.php");
	$Homework = new Homework($mysqli);
    require("class/Interest.class.php");
	$Interest = new Interest($mysqli);
	require("class/Helper.class.php");
	$Helper = new Helper($mysqli);
	
	session_start();
	
	
	

	

?>