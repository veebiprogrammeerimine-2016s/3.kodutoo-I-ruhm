<?php

	require("../functions.php");
	
	require("../class/Books.class.php");
	$Books = new Books($mysqli);
	
	require("../class/Helper.class.php");
	$Helper = new Helper();
	
	//kas kasutaja uuendab andmeid
	if(isset($_POST["update"])){
		
		$Books->update($Helper->cleanInput($_POST["id"]), $Helper->cleanInput($_POST["author"]), $Helper->cleanInput($_POST["title"]));
		
		header("Location: edit.php?id=".$_POST["id"]."&success=true");
        exit();	
		
	}
	
	//kustutan
	if(isset($_GET["delete"])){
		
		$Books->delete($_GET["id"]);
		
		header("Location: data.php");
		exit();
	}
	
	
	
	// kui ei ole id'd aadressireal siis suunan
	if(!isset($_GET["id"])){
		header("Location: data.php");
		exit();
	}
	
	//saadan kaasa id
	$b = $Books->AllBooks($_GET["id"]);
	//var_dump($c);
	
	if(isset($_GET["success"])){
		echo "salvestamine õnnestus";
	}
	
?>
<?php require("../header.php"); ?>

<br><br>
<a href="data.php"> tagasi </a>

<h2>Muuda kirjet</h2>
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" >
	<input type="hidden" name="id" value="<?=$_GET["id"];?>" > 
  	<label for="author" >Autor</label><br>
	<input id="author" name="author" type="text" value="<?php echo $b->author;?>" ><br><br>
  	<label for="title" >Pealkiri</label><br>
	<input id="title" name="title" type="text" value="<?=$b->title;?>"><br><br>
  	
	<input type="submit" name="update" value="Salvesta">
  </form>
  
  
 <br>
 <br>
 <br>
 <a href="?id=<?=$_GET["id"];?>&delete=true">Kustuta</a>
 
 <?php require("../header.php"); ?>
