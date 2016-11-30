<?php

	require("../functions.php");
	
	require("../class/Books.class.php");
	$Books = new Books($mysqli);
	
	require("../class/Helper.class.php");
	$Helper = new Helper();
	
	//kas on sisse loginud, kui ei ole, siis suunata login lehele
	
	if (!isset ($_SESSION["userId"])) {
		
		header("Location: login.php");
		exit();
	}
	
	//kas ?logout on aadressireal
	if (isset($_GET["logout"])) {
		
		session_destroy ();
		header ("Location: login.php");
		exit();
	}

	//ei ole tühjad väljad, mida salvestada
	if  (isset($_POST["author"]) &&
		isset($_POST["title"]) &&
		!empty($_POST["author"]) &&
		!empty($_POST["title"])
		) {
			$Books->save($Helper->cleanInput($_POST["author"]), $Helper->cleanInput($_POST["title"]));
			
		}
	
	$author = "";
	$authorError = "";
	$title = "";
	$titleError = "";
	
	if (isset ($_POST["author"]) ) {
	
		if (empty ($_POST["author"]) ) { 
			$authorError = "Palun täitke see väli!";
		} else {
			$author = $_POST["author"];
		}
	}
	
	
	if (isset ($_POST["title"]) ) {
	
		if (empty ($_POST["title"]) ) { 
			$titleError = "Palun täitke see väli!";
		} else {
			$title = $_POST["title"];
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
	
	$people = $Books->get($q, $sort, $order);
	
?>
<?php require("../header.php"); ?>
<div class "data" style="padding-left:10px;">

<h1>Andmed</h1>
<p> 
	Tere tulemast <a href="user.php"><?=$_SESSION["email"];?>!</a>
	<a href="?logout=1">Logi välja</a>
</p>

<form method="POST"> 
<label>Raamatu autor</label><br>
			
		<input type="text" name="author" value="<?=$author;?>"> <?php echo $authorError;?> <br><br>
	
<label>Raamatu pealkiri</label><br>
		
		<input type="text" name="title" value="<?=$title;?>"> <?php echo $titleError;?> <br><br>
	
	<input type="submit" value="Salvesta">	
</form>
<br>
<form>
<label>Otsing</label><br>
	<input type="search" name="q" value="<?=$q;?>">
	<input type="submit" value="Otsi">
</form>

<h2>Loetud raamatud</h2>

</div>
<?php

	$html = "<table class='table table-striped'>";
	
	//TABELI SORTEERIMINE
	$html .= "<tr>";
	
		$idOrder = "ASC";
		$authorOrder="ASC"; 
		$titleOrder="ASC"; 
		$createdOrder="ASC"; 
		$idArrow = "&uarr;";
		$authorArrow = "&uarr;";
		$titleArrow = "&uarr;";
		$createdArrow = "&uarr;";
		
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
		
		if (isset($_GET["sort"]) && $_GET["sort"] == "created") {
			if (isset($_GET["order"]) && $_GET["order"] == "ASC") {
				$createdOrder="DESC";
				$createdArrow = "&darr;";
			}
		}
		
	$html .= "<th>
				<a href='?q=".$q."&sort=id&order=".$idOrder."'>
					ID ".$idArrow."
				</a>
				</th>";
		$html .= "<th>
				<a href='?q=".$q."&sort=author&order=".$authorOrder."'>
					Autor ".$authorArrow."
				</a>	
				</th>";
		$html .= "<th>
				<a href='?q=".$q."&sort=title&order=".$titleOrder."'>
					Pealkiri ".$titleArrow."
				</a>
				</th>";
		$html .= "<th>
				<a href='?q=".$q."&sort=created&order=".$createdOrder."'>
					Loetud ".$createdArrow."
				</a>
				</th>";
	$html .= "</tr>";
	

	foreach($people as $p) {
		$html .= "<tr>";
			$html .= "<td>".$p->id."</td>";
			$html .= "<td>".$p->author."</td>";
			$html .= "<td>".$p->title."</td>";
			$html .= "<td>".$p->created."</td>";
			$html .= "<td><a class='btn btn-default btn-sm' href='edit.php?id=".$p->id."'><span class='glyphicon glyphicon-pencil'></span> Muuda</a></td>";
		$html .= "</tr>";	
	}

	$html .= "</table>";
	echo $html;
	
?>
<?php require("../footer.php"); ?>