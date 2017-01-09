<?php

	require("../../../config.php");
	
	$database = "if16_henriv";
	$mysqli = new mysqli($serverHost, $serverUsername, $serverPassword, $database);
	
	require("../class/User.class.php");
	$User = new User($mysqli);
	require("../class/Goal.class.php");
	$Goal = new Goal($mysqli);
    require("../class/Interest.class.php");
	$Interest = new Interest($mysqli);
	require("../class/Helper.class.php");
	$Helper = new Helper($mysqli);
	
	session_start();
	
	
	

	

?>