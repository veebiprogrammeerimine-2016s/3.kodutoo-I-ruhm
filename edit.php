<?php
	//edit.php
	require("functions.php");
	
	require("book.class.php");
	$books = new books($mysqli);
	
	require("helper.class.php");
	$Helper = new Helper();
	
	//var_dump($_POST);
	
	//kas kasutaja uuendab andmeid
	if(isset($_POST["update"])){
		
		$books->update($Helper->cleanInput($_POST["id"]), $Helper->cleanInput($_POST["book"]), $Helper->cleanInput($_POST["autor"]), $Helper->cleanInput($_POST["rating"]));
		
		header("Location: edit.php?id=".$_POST["id"]."&success=true");
        exit();	
		
	}
	
	//kustutan
	if(isset($_GET["delete"])){
		
		$books->delete($_GET["id"]);
		
		header("Location: data.php");
		exit();
	}
	
	
	
	// kui ei ole id'd aadressireal siis suunan
	if(!isset($_GET["id"])){
		header("Location: data.php");
		exit();
	}
	
	//saadan kaasa id
	$c = $books->getSingle($_GET["id"]);
	//var_dump($c);
	
	if(isset($_GET["success"])){
		echo "salvestamine õnnestus";
	}
	
?>

<a href="data.php"> tagasi </a>

<h2>Muuda kirjet</h2>
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" >
	<input type="hidden" name="id" value="<?=$_GET["id"];?>" > 
  	<label for="book" >Raamatu pealkiri</label><br>
	<input id="book" name="book" type="text" value="<?php echo $c->book;?>" ><br><br>
  	<label for="autor" >Autor</label><br>
	<input id="autor" name="autor" type="text" value="<?=$c->autor;?>"><br><br>
	<label for="rating" >Hinnang</label><br>
	<input id="rating" name="rating" type="text" value="<?=$c->rating;?>"><br><br>
  	
	<input type="submit" name="update" value="Salvesta">
  </form>
  
  
 <br>
 <br>
 <br>
 <a href="?id=<?=$_GET["id"];?>&delete=true">kustuta</a>
