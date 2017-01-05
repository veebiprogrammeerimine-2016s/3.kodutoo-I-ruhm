<?php

	require("../functions.php");
	require("../class/pictures.class.php");
	$pictures = new pictures($mysqli);
	require("../class/helper.class.php");
	$helper = new helper($mysqli);

	if(isset($_GET["delete"])){
		// saadan kaasa aadressirealt id
		$pictures->deletePic($_GET["id"]);
		header("Location: user.php");
		exit();
		
	}

	if(isset($_POST["update"])){
	
	$pictures->updatePic($helper->cleanInput($_POST["id"]), $helper->cleanInput($_POST["author"]), $helper->cleanInput($_POST["date_taken"]), $helper->cleanInput($_POST["description"]));
	
	header("Location: edit.php?id=".$_POST["id"]."&success=true");
    exit();	
		
	}

	$c = $pictures->getSinglePicData($_GET["id"]);
?>

<?php require("../header.php"); ?>

<br><br>
<a href="user.php"> < Tagasi </a>

<h2>Muuda kirjet</h2>
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" >
	<input type="hidden" name="id" value="<?=$_GET["id"];?>" >
  	<label for="author" >Autor</label><br>
	<textarea  id="author" name="author"><?php echo $c->author;?></textarea><br>
	<label for="description" >Kirjeldus</label><br>
	<textarea  id="description" name="description"><?php echo $c->description;?></textarea><br>
  	
	<input type="submit" name="update" value="Salvesta">
  </form>
  
<br>
<a href="?id=<?=$_GET["id"];?>&delete=true">kustuta</a>

<?php require("../footer.php"); ?>