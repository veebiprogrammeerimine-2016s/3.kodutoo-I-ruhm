<?php
	//edit.php
	require("../functions.php");
	
	require("../class/Blog.class.php");
	$Blog = new Blog($mysqli);
	
	require("../class/Helper.class.php");
	$Helper = new Helper();
	
	//var_dump($_POST);
	
	//kas kasutaja uuendab andmeid
	if(isset($_POST["update"])){
		
		$Blog->update($Helper->cleanInput($_POST["id"]), $Helper->cleanInput($_POST["user"]), $Helper->cleanInput($_POST["date"]), $Helper->cleanInput($_POST["mood"]), $Helper->cleanInput($_POST["feeling"]), $Helper->cleanInput($_POST["activities"]), $Helper->cleanInput($_POST["thoughts"]));
		
		header("Location: edit.php?id=".$_POST["id"]."&success=true");
        exit();	
		
	}
	
	//kustutan
	if(isset($_GET["delete"])){
		
		$Blog->delete($_GET["id"]);
		
		header("Location: data.php");
		exit();
	}
	
	// kui ei ole id'd aadressireal siis suunan
	if(!isset($_GET["id"])){
		header("Location: data.php");
		exit();
	}
	
	//saadan kaasa id
	$person = $Blog->getSingle($_GET["id"]);
	//var_dump($c);
	
	if(isset($_GET["success"])){
		echo "SALVESTAMINE ÕNNESTUS!";
	}
?>


<!DOCTYPE html>
<html>
<head>

<h2>Muuda andmeid</h2>
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" >
	<input type="hidden" name="id" value="<?=$_GET["id"];?>" > 
	<input type="hidden" name="user" value="<?=$_GET["user"];?>" > 
	
  	<label for="date">Kuupäev</label><br>
	<input id="date" name="date" type="text" value="<?php echo $person->date;?>" ><br><br>
	
  	<label for="mood">Tuju</label><br>
	<input id="mood" name="mood" type="text" value="<?=$person->mood;?>"><br><br>
	
	<label for="feeling">Enesetunne</label><br>
	<input id="feeling" name="feeling" type="text" value="<?=$person->feeling;?>"><br><br>
	
	<label for="activities">Päevategevused</label><br>
	<input id="activities" name="activities" type="text" value="<?=$person->activities;?>"><br><br>
	
	<label for="thoughts">Mõtted</label><br>
	<input id="thoughts" name="thoughts" type="text" value="<?=$person->thoughts;?>"><br><br>
	
	<input type="submit" name="update" value="Salvesta">
  </form>

  <br>
  <a href="?id=<?=$_GET["id"];?>&delete=true">Kustuta</a>
  <br><br>
  <a href="data.php">Tagasi</a>
  
</body>
</html>