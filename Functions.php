<?php

	require("../../config.php");

	$database = "if16_atsklemm_1";
    $mysqli = new mysqli( $serverHost, $serverUsername, $serverPassword, $database);

    require("class/Player.class.php");
    $Player = new Player($mysqli);

    require("class/User.class.php");
    $User = new User($mysqli);

    require("class/Helper.class.php");
    $Helper = new Helper($mysqli);



    function cleanInput($input)
{

    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);

    return $input;

}
    session_start ();
?>