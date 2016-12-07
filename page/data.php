<?php
	require("functions.php");
	
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
		
		//kui otsib, võtame otsisõna aadressirealt
		$q = $_GET["q"];
	} else {
		
		//kas otsisõna on tühi
		$q = "";
	}
	
	$sort = "id";
	$order = "ASC";
	
	if (isset($_GET["sort"]) && isset($_GET["order"])) {
		$sort = $_GET["sort"];
		$order = $_GET["order"];
	}
	
	//saan kõik andmed
	
	$people = $Training->get($q, $sort, $order);
	
	//$trainer = AllExercises();
	
	//echo "<pre>";
	//var_dump($trainer);
	//echo "</pre>";
?>
<?php require("../header.php"); ?>

<div class "data" style="padding-left:10px;">

	<div align="center"><h1>Andmed</h1>
		<p> 
			Tere tulemast <a href="user.php"><?=$_SESSION["email"];?>!</a>
			<br> <a href="?logout=1">Logi välja</a>
		</p>
	</div>
	
<form method="POST"> 
<label>Harjutus</label><br>
			
		<input type="text" name="exercise" value="<?=$exercise;?>"> <?php echo $exerciseError;?> <br><br>
	
<label>Kordused</label><br>
		
		<input type="text" name="series" value="<?=$series;?>"> <?php echo $seriesError;?> <br><br>
	
	<input type="submit" value="Salvesta">	
</form>



<h2>Tehtud harjutused</h2>
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
		//$createdOrder="ASC"; 
		$idArrow = "&uarr;";
		$exerciseArrow = "&uarr;";
		$seriesArrow = "&uarr;";
		//$createdArrow = "&uarr;";
		
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
		
		/*if (isset($_GET["sort"]) && $_GET["sort"] == "created") {
			if (isset($_GET["order"]) && $_GET["order"] == "ASC") {
				$createdOrder="DESC";
				$createdArrow = "&darr;";
			}
		}*/
		
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
		/*$html .= "<th>
				<a href='?q=".$q."&sort=created&order=".$createdOrder."'>
					Loetud ".$createdArrow."
				</a>
				</th>";*/
	$html .= "</tr>";
	
	foreach($trainer as $p) {
		$html .= "<tr>";
			$html .= "<td>".$p->id."</td>";
			$html .= "<td>".$p->exercise."</td>";
			$html .= "<td>".$p->series."</td>";
			//$html .= "<td>".$p->created."</td>";
			$html .= "<td><a class='btn btn-default btn-sm' href='edit.php?id=".$p->id."'><span class='glyphicon glyphicon-pencil'></span> Muuda</a></td>";
		$html .= "</tr>";	
	}
	$html .= "</table>";
	echo $html;
	
?>
<?php require("../footer.php"); ?>