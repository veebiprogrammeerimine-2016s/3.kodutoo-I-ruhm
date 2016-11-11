<?php
/**
 * @author Alar Aasa <alar@alaraasa.ee>
 */
require("/../../config.php")
require("config.php");
//quick and simple database regeneration
function createDB($database)
{
    //works
    $mysqli = connectDB();
    sqlConnectTest($mysqli);
    if (!$mysqli->query("DROP DATABASE IF EXISTS if16_alaraasa_boards") &&
        !$mysqli->query("CREATE DATABASE if16_alaraasa_boards")
    ) {
        echo "Database creation failed: (" . $mysqli->errno . ") " . $mysqli - error;
    }
    $mysqli->close();
}
echo "This page regenerates the database!";

//DO NOT REMOVE COMMENT, COMPLETELY UNTESTED ON TLU SERVERS
//createDB();


?>