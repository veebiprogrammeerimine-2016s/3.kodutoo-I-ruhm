<?php
	//edit.php
	require("functions.php");

	//kas kasutaja uuendab andmeid
	if(isset($_POST["update"])){

		$Reservation->update(cleanInput($_POST["id"]), cleanInput($_POST["people"]), cleanInput($_POST["comment"]));

		header("Location: edit.php?id=".$_POST["id"]."&success=true");
        exit();

	}


	//saadan kaasa id
	$r = $Reservation->getSingleData($_GET["id"]);
//	var_dump($r);

	if(isset($_GET["delete"])){

		$Reservation->delete($_GET["id"]);
}


?>
<br><br>
<a href="broneering.php"> tagasi </a>

<h2>Muuda kirjet</h2>
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" >
	<input type="hidden" name="id" value="<?=$_GET["id"];?>" >
  	<label for="people" >Inimeste arv</label><br>
	<input id="people" name="people" type="text" value="<?php echo $r->people;?> "><br><br>
  	<label for="comment" >Kommentaar</label><br>
	<input id="comment" name="comment" type="text" value="<?php echo $r->comment;?>"><br><br>

	<input type="submit" name="update" value="Salvesta">
	</form>
	<br><br>

  <a href="?id=<?=$_GET["id"];?>&delete=true">Kustuta</a>
