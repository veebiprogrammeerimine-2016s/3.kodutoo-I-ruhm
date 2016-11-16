<?php
require_once("../../../config.php");

$database = "if16_ALARI_VEREV";
$mysqli = new mysqli( $serverHost, $serverUsername, $serverPassword, $database);

require("class/Edit.class.php");
$Edit = new Edit($mysqli);

?>