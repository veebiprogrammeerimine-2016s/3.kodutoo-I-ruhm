<?php

  //require ("../../config.php");
  require ("/home/innasamm/config.php");

	//function.php

  //alustan sessiooni, et saaks kasutada $_SESSION muutujaid

  session_start();

  $database = "if16_innasamm";
  $mysqli = new mysqli($serverHost, $serverUsername, $serverPassword, $database);


?>
