<?php
	//edit.php
	require("../functions.php");
	
	
	//var_dump($_POST);
	
	//kas kasutaja uuendab andmeid
	if(isset($_POST["update"])){
		
		$Apartment->update($Helper->cleanInput($_POST["id"]), $Helper->cleanInput($_POST["city"]), $Helper->cleanInput($_POST["street"]), $Helper->cleanInput($_POST["area"]), $Helper->cleanInput($_POST["rooms"]));
		
		header("Location: edit.php?id=".$_POST["id"]."&success=true");
        exit();	
		
	}
	
	//kustutan
	if(isset($_GET["delete"])){
		
		$Apartment->delete($_GET["id"]);
		
		header("Location: data.php");
		exit();
	}
	
	
	
	// kui ei ole id'd aadressireal siis suunan
	if(!isset($_GET["id"])){
		header("Location: data.php");
		exit();
	}
	
	//saadan kaasa id
	$c = $Apartment->getSingle($_GET["id"]);
	//var_dump($c);
	
	if(isset($_GET["success"])){
		echo "Salvestamine onnestus";
	}

	
?>
<?php require("../header.php"); ?>
<br><br>
<a href="apartments.php"> tagasi </a>

<h2>Muuda kirjet</h2>
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" >
	<input type="hidden" name="id" value="<?=$_GET["id"];?>" > 
  	<label for="city" >Linn</label><br>
	<input id="city" name="city" type="text" value="<?php echo $c->city;?>" ><br><br>
  	<label for="street" >Tanav</label><br>
	<input id="street" name="street" type="text" value="<?=$c->street;?>"><br><br>
	<label for="area" >Pindala</label><br>
	<input id="area" name="area" type="text" value="<?=$c->area;?>"><br><br>
	<label for="rooms" >Tubasid</label><br>
	<input id="rooms" name="rooms" type="text" value="<?=$c->rooms;?>"><br><br>
  	
	<input type="submit" name="update" value="Salvesta">
  </form>
  
  
 <br>
 <br>
 <br>
 <a href="?id=<?=$_GET["id"];?>&delete=true">Kustuta</a>
 <?php require("../footer.php"); ?>