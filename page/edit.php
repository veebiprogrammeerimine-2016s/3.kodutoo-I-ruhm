<?php
	//edit.php
	require("../functions.php");
	
	if(isset($_GET["delete"]) && isset($_GET["id"])) {
		//kustutan
		
		$Homework->delete($Helper->cleanInput($_GET["id"]));
		header("Location: data.php");
		exit();
	}
	
	//kas kasutaja uuendab andmeid
	if(isset($_POST["update"])){
		
		$Homework->update($Helper->cleanInput($_POST["id"]), $Helper->cleanInput($_POST["homework_name"]), $Helper->cleanInput($_POST["homework_explanation"]));
		
		header("Location: edit.php?id=".$_POST["id"]."&success=true");
        exit();	
		
	}
	
	//saadan kaasa id
	$g = $Homework->getSingle($_GET["id"]);
	//var_dump($g);

	
?>
<html>
<body>


<?php require("../header.php"); ?>
<br>


<div class="col-xs-4">
<div class="form-group">
<a href="data.php"> Tagasi </a>
<h2>Muuda koduse too nime ja selgitust</h2>
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" >
	<input class="form-control" type="hidden" name="id" value="<?=$_GET["id"];?>" > 
  	<label for="homework_name" >Koduse too nimi</label><br>
	<input class="form-control" id="homework_name" name="homework_name" type="text" value="<?php echo $g->homework_name;?>" ><br><br>
  	<label for="homework_explanation" >Selgitus</label><br>
	<input class="form-control" id="homework_explanation" name="homework_explanation" type="text" value="<?=$g->homework_explanation;?>"><br><br>

  	
	<input class="btn btn-success btn-sm hidden-xs" type="submit" name="update" value="Salvesta">
  </form>
</div>  
  
  
  <br>
  <br>
  <a href="?id=<?=$_GET["id"];?>&delete=true">Kustuta</a>
  <br>
  <br>
<?php require("../footer.php"); ?>  
  