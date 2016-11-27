<?php
	require("../hw3_functions.php");
	
	require("../class/Reply.class.php");
	$Reply = new Reply($mysqli);
?>

<?php require("../header.php")?>
<!--<h2><a href="hw3_topics.php?id=.." style="text-decoration:none"> < Tagasi </a></h2>-->
<h1>Muuda oma vastust</h1>

<?php require("../footer.php")?>