<?php
	require("../../../config.php");
	//functions.php

    $database = "if16_ALARI_VEREV";
    $mysqli = new mysqli( $serverHost, $serverUsername, $serverPassword, $database);

    require("class/User.class.php");
    $User = new User($mysqli);

    require("class/Resto.class.php");
    $Resto = new Resto($mysqli);

    function cleanInput($input) {

        $input = trim($input);
        $input = stripslashes($input);
        $input = htmlspecialchars($input);

        return $input;

    }

	//alustan sessiooni 
	//$_SESSION muutujad
	session_start();

?>