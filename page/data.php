<?php
	require("../functions.php");
	
	require("../class/Training.class.php");
	$Training = new Training($mysqli);
	
	require("../class/Helper.class.php");
	$Helper = new Helper();
	
	
	if (!isset ($_SESSION["userId"])) {
		
		header("Location: login.php");
		exit();
	}
	
	if (isset($_GET["logout"])) {
		
		session_destroy ();
		header ("Location: login.php");
		exit();
	}

	if(isset($_POST["exercise"]) &&
			isset($_POST["series"]) &&
			!empty($_POST["exercise"]) &&
			!empty($_POST["series"])
		) {
				$Training->save($Helper->cleanInput($_POST["exercise"]), $Helper->cleanInput($_POST["series"]));
			
		}
	
	$exercise = "";
	$exerciseError = "";
	$series = "";
	$seriesError = "";
	
	if (isset ($_POST["exercise"]) ) {
	
		if (empty ($_POST["exercise"]) ) { 
			$exerciseError = "Palun täitke see väli!";
		} else {
			$exercise = $_POST["exercise"];
		}
	}
	
	
	if (isset ($_POST["series"]) ) {
	
		if (empty ($_POST["series"]) ) { 
			$seriesError = "Palun täitke see väli!";
		} else {
			$series = $_POST["series"];
		}
	}
	
	if(isset($_GET["q"])) {
		
		$q = $_GET["q"];
	} else {
		
		$q = "";
	}
	
	$sort = "id";
	$order = "ASC";
	
	if (isset($_GET["sort"]) && isset($_GET["order"])) {
		$sort = $_GET["sort"];
		$order = $_GET["order"];
	}
	
	
	$trainer = $Training->get($q, $sort, $order);
	

?>
<?php require("../header.php"); ?>

<div class "data" style="padding-left:10px;">

<h1><p style="color:green"><b>Avaleht</b></p></h1>
	<p> 
		Tere tulemast <a href="user.php"><?=$_SESSION["email"];?>!</a>
		<br> <a href="?logout=1">Logi välja</a>
	</p>

	
<form method="POST"> 
<label>Harjutus</label><br>
			
		<input type="text" name="exercise" value="<?=$exercise;?>"> <?php echo $exerciseError;?> <br><br>
	
<label>Kordused</label><br>
		
		<input type="text" name="series" value="<?=$series;?>"> <?php echo $seriesError;?> <br><br>
	
	<input type="submit" value="Salvesta">	
</form>



<h2><p style="color:green"><b>Tehtud harjutused</b></p></h2>
<form>
	<input type="search" name="q" value="<?=$q;?>">
	<input type="submit" value="Otsi">
</form>
</div>
<br>

<?php
	$html = "<table class='table table-striped'>";
	
	//TABELI SORTEERIMINE
	$html .= "<tr>";
	
		$idOrder = "ASC";
		$exerciseOrder="ASC"; 
		$seriesOrder="ASC"; 

		$idArrow = "&uarr;";
		$exerciseArrow = "&uarr;";
		$seriesArrow = "&uarr;";
		
		
		if (isset($_GET["sort"]) && $_GET["sort"] == "id") {
			if (isset($_GET["order"]) && $_GET["order"] == "ASC") {
				$idOrder="DESC";
				$idArrow = "&darr;";
			}
		}
		
		if (isset($_GET["sort"]) && $_GET["sort"] == "author") {
			if (isset($_GET["order"]) && $_GET["order"] == "ASC") {
				$authorOrder="DESC"; 
				$authorArrow = "&darr;";
			}
		}
		
		if (isset($_GET["sort"]) && $_GET["sort"] == "title") {
			if (isset($_GET["order"]) && $_GET["order"] == "ASC") {
				$titleOrder="DESC";
				$titleArrow = "&darr;";
			}
		}
		

		
	$html .= "<th>
				<a href='?q=".$q."&sort=id&order=".$idOrder."'>
					ID ".$idArrow."
				</a>
				</th>";
		$html .= "<th>
				<a href='?q=".$q."&sort=exercise&order=".$exerciseOrder."'>
					Harjutus ".$exerciseArrow."
				</a>	
				</th>";
		$html .= "<th>
				<a href='?q=".$q."&sort=series&order=".$seriesOrder."'>
					Seeria ".$seriesArrow."
				</a>
				</th>";

	$html .= "</tr>";
	
	foreach($trainer as $t) {
		$html .= "<tr>";
			$html .= "<td>".$t->id."</td>";
			$html .= "<td>".$t->exercise."</td>";
			$html .= "<td>".$t->series."</td>";

			$html .= "<td><a class='btn btn-default btn-sm' href='edit.php?id=".$t->id."'><span class='glyphicon glyphicon-pencil'></span> Muuda</a></td>";
		$html .= "</tr>";	
	}
	$html .= "</table>";
	echo $html;
	
?>
<?php require("../footer.php"); ?>