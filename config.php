<?php
/**
 * @author Alar Aasa <alar@alaraasa.ee>
 * Public configuration file
 */
require_once("/home/alaraasa/config.php");
require_once("class/Helper.class.php");
require_once("class/Post.class.php");
require_once("class/Board.class.php");

$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], "if16_alaraasa_board");
$Helper = new Helper($mysqli);
$Board = new Board($mysqli);
$Post = new Post($mysqli);

?>