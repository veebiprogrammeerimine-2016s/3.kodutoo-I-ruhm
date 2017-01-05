<?php

	require("../functions.php");
	require("../class/pictures.class.php");
	$pictures = new pictures($mysqli);
	require("../class/helper.class.php");
	$helper = new helper($mysqli);

	//Kas on sisseloginud
	//Kui ei ole, siis suunata login lehele
	if (!isset($_SESSION["userId"])){
		header("Location: login.php");
		exit();
	}
	
	//Kui vajutada logi välja nuppu, viib tagasi login lehele
	if (isset($_GET["logout"])) {
		session_destroy();
		header("Location: login.php");
		exit();
	}

	$q = "";
	if(isset($_GET["q"])){
		$q = $helper->cleanInput($_GET["q"]);
	}
	
	//vaikimisi
	$sort = "id";
	$order = "ASC";
	
	if(isset($_GET["sort"]) && isset($_GET["order"])){
		$sort = $_GET["sort"];
		$order = $_GET["order"];
	}

	$pics = $pictures->getAllPics($q, $sort, $order);	

?>

<?php require("../header.php"); ?>

	<a href="data.php">< Tagasi</a>
	<h1>Salvestatud pildid</h1>

	<form>
		<input type="search" name="q" value="<?=$q;?>">
		<input type="submit" value= "Otsi">
	</form>

	<br>

	<?php

		$html = "<table class='table table-hover'>";
			
			$html .= "<tr>";
				
				$orderId = "ASC";
				
				if(isset($_GET["order"]) && 
					$_GET["order"] == "ASC" &&
					$_GET["sort"] == "id" ){
					$orderId = "DESC";
				}
				
				$orderAuthor = "ASC";
				if(isset($_GET["order"]) && 
					$_GET["order"] == "ASC" &&
					$_GET["sort"] == "author" ){
					$orderAuthor = "DESC";
				}
				
				$orderDate = "ASC";
				if(isset($_GET["order"]) && 
					$_GET["order"] == "ASC" &&
					$_GET["sort"] == "date_taken" ){
					$orderDate = "DESC";
				}

				$orderDescription = "ASC";
				if(isset($_GET["order"]) && 
					$_GET["order"] == "ASC" &&
					$_GET["sort"] == "description" ){
					$orderDescription = "DESC";
				}

				$html .= "<th>
				
							<a href='?q=".$q."&sort=id&order=".$orderId."'>
								ID
							</a>
						</th>";
		
				$html .= "<th>
				
							<a href='?q=".$q."&sort=author&order=".$orderAuthor."'>
								Autor
							</a>
						</th>";
				
				$html .= "<th>
				
							<a href='?q=".$q."&sort=date_taken&order=".$orderDate."'>
								Kuupäev
							</a>
						</th>";

				$html .= "<th>
				
							<a href='?q=".$q."&sort=description&order=".$orderDescription."'>
								Kirjeldus
							</a>
						</th>";
				
			$html .= "</tr>";

		foreach ($pics as $picture) {
			$html .= "<tr>";
				$html .= "<td>".$picture->id."</td>";
				$html .= "<td>".$picture->author."</td>";
				$html .= "<td>".$picture->date_taken."</td>";
				$html .= "<td>".$picture->description."</td>";
				$html .= "<td><a href='edit.php?id=".$picture->id."'> <span class='glyphicon glyphicon-pencil'><edit.php</a></td>";
			$html .= "</tr>";
		}
		
		$html .= "</table>";
		
		echo $html;

	?>

<?php require("../footer.php"); ?>