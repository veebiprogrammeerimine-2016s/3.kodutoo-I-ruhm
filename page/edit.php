<?php
	//edit.php
	require("../functions.php");
	require("../class/Club.class.php");
	$Club = new Club($mysqli);

	//var_dump($_POST);

	//kas kasutaja uuendab andmeid
	if(isset($_POST["update"])){

		$Club->update($Helper->cleanInput($_POST['id']), $Helper->cleanInput($_POST["clubName"]), $Helper->cleanInput($_POST["clubLocation"]), $Helper->cleanInput($_POST["clubRate"]));
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
		echo "salvestamine õnnestus";
	}

?>
<br><br>
<a href="data3.php"> Tagasi </a>

<h2>Muuda kirjet</h2>
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" >

	<input type="hidden" name="id" value="<?=$_GET["id"];?>" >

  	<label for="clubName" >Klubi nimi</label><br>
	<input id="clubName" name="name" type="text" value="<?php echo $c->name;?>" ><br><br>

  	<label for="clubLocation" >Asukoht</label><br>
	<input id="clubLocation" name="location" type="text" value="<?=$c->location;?>"><br><br>

  <label for="rate" >Hinnang</label><br>
<input id="rate" name="rate" type="number" value="<?=$c->rate;?>"><br><br>

	<input type="submit" name="update" value="Salvesta">

  </form>


 <br>
 <br>
 <br>
 <a href="?id=<?=$_GET["id"];?>&delete=true">kustuta</a>
