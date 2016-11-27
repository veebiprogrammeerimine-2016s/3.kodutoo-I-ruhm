<?php
	//edit.php
	require("functions.php");
	require("editFunctions.php");
	
	if(isset($_GET["delete"]) && isset($_GET["id"])) {
		// kustutan
		
		deleteCar(cleanInput($_GET["id"]));
		header("Location: data.php");
		exit();
	}
	
	//kas kasutaja uuendab andmeid
	if(isset($_POST["update"])){
		
		updateCar(cleanInput($_POST["id"]), cleanInput($_POST["make"]), cleanInput($_POST["model"]), cleanInput($_POST["fuel"]), cleanInput($_POST["carcolor"]));
		
		header("Location: edit.php?id=".$_POST["id"]."&success=true");
		
        exit();	
		
	}
	
	
		
	
	//saadan kaasa id
	$c = getSingleCarData($_GET["id"]);
	var_dump($c);

	
?>
<br><br>
<a href="data.php"> tagasi </a>

<h2>Muuda kirjet</h2>
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" >
	<input type="hidden" name="id" value="<?=$_GET["id"];?>" > 
	<label for="make">Mark</label><br>
	<input id="make" name="make" type="text" value="<?=$c->make;?>">
	<br><br>
	<label>Mudel</label><br>
	<input id="model" name="model" type="text" value="<?=$c->model;?>">

	<br><br>
	<label for="fuel">Kütus</label><br>
	<input id="fuel" name="fuel" type="text" value="<?=$c->fuel;?>"><br><br>
  	<label for="carcolor" >Värv</label><br>
	<input id="carcolor" name="carcolor" type="color" value="<?=$c->carcolor;?>"><br><br>
  	
	<input type="submit" name="update" value="Salvesta">
	
	
	
	
	
  </form>
  
  <br>
  <br>
  <a href="?id=<?=$_GET["id"];?>&delete=true">Kustuta</a>
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  