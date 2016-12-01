<?php
	
	require("../functions.php");
	
	if (!isset($_GET["id"]) OR !isset($_SESSION["username"])) {
		header("Location: data.php");
		exit();
	}
	
	if (isset($_GET["delete"])) {
		$Solve->delSolve($_GET["id"]);
		header("Location: data.php");
		exit();
	}
	
	if (isset($_POST["update"])) {
		$Solve->update(clean_input($_GET["id"]), clean_input($_POST["cube"]), clean_input($_POST["scramble"]), clean_input($_POST["time"]));
		header("Location: data.php");
		exit();
	}
	
	
	$r = $Solve->getSingle($_GET["id"]);
	
?>

<?php require("../partials/header.php"); ?>

<h2>Muuda</h2>

<form  method = "POST" >
	<input type = "hidden" name = "id" value = "<?=$_GET["id"];?>" > 
  	<label for = "cube" >Kuubik</label><br>
	<input id = "cube" name = "cube" type="text" value = "<?=$r->cube_type;?>" ><br><br>
  	<label for = "scramble" >Segamise valem</label><br>
	<input id = "scramble" name = "scramble" type="text" value = "<?=$r->scramble;?>"><br><br>
	<label for = "time" >Aeg</label><br>
	<input id = "time" name = "time" type = "text" value = "<?=$r->solve_time;?>"><br><br>
	<input type = "submit" name = "update" value = "Salvesta">
</form>

<br> <br>
<a class = "btn btn-default btn-sm" href = "?id=<?=$_GET['id'];?>&delete=true">Kustuta</a>
<br> <br>
<a class = "btn btn-default btn-sm" href="data.php"> tagasi </a>
<?php require("../partials/footer.php"); ?>