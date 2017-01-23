<?php
	//edit.php
	require("../functions.php");
	require("../class/Club.class.php");
	require ("../class/Helper.class.php");

		$Club = new Club($mysqli);

$Helper = "";

	//kas kasutaja uuendab andmeid
	if(isset($_POST["update"])){
	$Club->update($_POST["id"], $_POST["clubName"],  $_POST["clubLocation"], $_POST["rate"]);

		header("Location: edit.php?id=".$_POST["id"]."&success=true");
				exit();
	}

	//kustutan
	if(isset($_GET["delete"])){

		$Club->delete($_GET["id"]);

		header("Location: data3.php");
		exit();
	}



	// kui ei ole id'd aadressireal siis suunan
	if(!isset($_GET["id"])){
		header("Location: data3.php");
		exit();
	}

	//saadan kaasa id
	$c = $Club->getSingle($_GET["id"]);
	//var_dump($c);

	if(isset($_GET["success"])){
	//	$Club->update($_GET["id"]);
		echo "Salvestamine õnnestus";
	}

?>
<br><br>
<a href="data3.php"> Tagasi </a>

<h2>Muuda kirjet</h2>
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" >

	<input type="hidden" name="id" value="<?=$_GET["id"];?>" >

  	<label for="clubName" >Klubi nimi</label><br>
	<input id="clubName" name="clubName" type="text" value="<?php echo $c->clubName;?>" ><br><br>

  	<label for="clubLocation" >Asukoht</label><br>
	<input id="clubLocation" name="clubLocation" type="text" value="<?=$c->clubLocation;?>"><br><br>

  <label for="rate" >Hinnang</label><br>
<input id="rate" name="clubRate" type="number" value="<?=$c->rate;?>"><br><br>

	<input type="submit" name="update" value="Salvesta">

 <a href="?id=<?=$_GET["id"];?>&delete=true">Kustuta</a>
</form>
