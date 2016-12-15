<?php
	//edit.php
	require("../functions.php");
    require("../class/Car.class.php");
    $Car = new Car($mysqli);

	
	//kas kasutaja uuendab andmeid
	if(isset($_POST["update"])){
		
		$Car->update(cleanInput($_POST["id"]), cleanInput($_POST["plate"]), cleanInput($_POST["color"]));
		
		header("Location: edit.php?id=".$_POST["id"]."&success=true");
        exit();	
		
	}
	
	
	//saadan kaasa id
	$c = $Car->getSingleData($_GET["id"]);
	//var_dump($c);

	if(isset($_GET["delete"])){
		
		$Car->deleteCar($_GET["id"]);
	
	}
	
?>
<?php require ("../header.php");?>
<br><br>
<a href="data.php"> tagasi </a>

<h2>Muuda kirjet</h2>
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" >
	<input type="hidden" name="id" value="<?=$_GET["id"];?>" > 
  	<label for="number_plate" >auto nr</label><br>
	<input id="number_plate" name="plate" type="text" value="<?php echo $c->plate;?>" ><br><br>
  	<label for="color" >värv</label><br>
	<input id="color" name="color" type="color" value="<?=$c->color;?>"><br><br>
  	
	<input type="submit" name="update" value="Salvesta">
  </form>
  
  <br><br>
  
  <a href="?id=<?=$_GET["id"];?>&delete=true">Kustuta</a>
<?php require ("../footer.php");?>
  