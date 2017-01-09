<?php
	//edit.php
	require("../functions.php");
	
	if(isset($_GET["delete"]) && isset($_GET["id"])) {
		//kustutan
		
		$Goal->delete($Helper->cleanInput($_GET["id"]));
		header("Location: data.php");
		exit();
	}
	
	//kas kasutaja uuendab andmeid
	if(isset($_POST["update"])){
		
		$Goal->update($Helper->cleanInput($_POST["id"]), $Helper->cleanInput($_POST["goal_name"]), $Helper->cleanInput($_POST["goal_explanation"]));
		
		header("Location: edit.php?id=".$_POST["id"]."&success=true");
        exit();	
		
	}
	
	//saadan kaasa id
	$g = $Goal->getSingle($_GET["id"]);
	//var_dump($g);

	
?>
<html>
<body style='background-color:Bisque'>


<?php require("../header.php"); ?>
<br>


<div class="col-xs-4">
<div class="form-group">
<a href="data.php"> Tagasi </a>
<h2>Muuda eesmärgi nime ja selgitust</h2>
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" >
	<input class="form-control" type="hidden" name="id" value="<?=$_GET["id"];?>" > 
  	<label for="goal_name" >Eesmärgi nimi</label><br>
	<input class="form-control" id="goal_name" name="goal_name" type="text" value="<?php echo $g->goal_name;?>" ><br><br>
  	<label for="goal_explanation" >Eesmärk</label><br>
	<input class="form-control" id="goal_explanation" name="goal_explanation" type="text" value="<?=$g->goal_explanation;?>"><br><br>

  	
	<input class="btn btn-success btn-sm hidden-xs" type="submit" name="update" value="Salvesta">
  </form>
</div>  
  
  
  <br>
  <br>
  <a href="?id=<?=$_GET["id"];?>&delete=true">Kustuta</a>
  <br>
  <br>
<?php require("../footer.php"); ?>  
  