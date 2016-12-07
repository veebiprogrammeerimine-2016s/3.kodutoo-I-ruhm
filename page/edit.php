<?php
	require("../functions.php");
	
	require("../class/Training.class.php");
	$Training = new Training($mysqli);
	
	require("../class/Helper.class.php");
	$Helper = new Helper();
	
	//kas kasutaja uuendab andmeid
	if(isset($_POST["update"])){
		
		$Books->update($Helper->cleanInput($_POST["id"]), $Helper->cleanInput($_POST["exercise"]), $Helper->cleanInput($_POST["series"]));
		
		header("Location: edit.php?id=".$_POST["id"]."&success=true");
        exit();	
		
	}
	
	//kustutan
	if(isset($_GET["delete"])){
		
		$Training->delete($_GET["id"]);
		
		header("Location: data.php");
		exit();
	}
	
	
	// kui ei ole id'd aadressireal siis suunan
	if(!isset($_GET["id"])){
		header("Location: data.php");
		exit();
	}
	
	//saadan kaasa id
	$p = $Training->AllExercises($_GET["id"]);
	//var_dump($c);
	
	if(isset($_GET["success"])){
		//echo "Salvestamine õnnestus";
	}
	
?>
<?php require("../header.php"); ?>

<br><br>
<a href="data.php"> < tagasi </a>

<div class "edit" style="padding-left:10px;">

<h2>Muuda kirjet</h2>
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" >
	<input type="hidden" name="id" value="<?=$_GET["id"];?>" > 
  	<label for="exercise" >Harjutus</label><br>
	<input id="exercise" name="exercise" type="text" value="<?php echo $p->exercise;?>" ><br><br>
  	<label for="series" >Seeria</label><br>
	<input id="series" name="series" type="text" value="<?=$p->series;?>"><br><br>
  	
	<input type="submit" name="update" value="Salvesta">
  </form>
  
  
 <br>
 
 <a href="?id=<?=$_GET["id"];?>&delete=true">Kustuta</a>
 <br>
 <br>
 <br>
 
 </div>
 
 <?php require("../header.php"); ?>