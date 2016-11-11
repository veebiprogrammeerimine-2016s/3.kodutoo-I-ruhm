<?php

	require("functions.php");
	
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
	if(isset($_POST["author"]) &&
			isset($_POST["title"]) &&
			!empty($_POST["author"]) &&
			!empty($_POST["title"])
		) {
			Books(cleanInput($_POST["author"]), cleanInput($_POST["title"]));
			
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
	
	$people = AllBooks();
	
?>
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



<h2>Loetud raamatud</h2>
<?php

	$html = "<table>";
	
		$html .= "<tr>";
			$html .= "<th>ID</th>";
			$html .= "<th>Raamatu autor</th>";
			$html .= "<th>Raamatu pealkiri</th>";
			$html .= "<th>Loetud</th>";
		$html .= "</tr>";

	foreach($people as $p) {
		$html .= "<tr>";
			$html .= "<td>".$p->id."</td>";
			$html .= "<td>".$p->author."</td>";
			$html .= "<td>".$p->title."</td>";
			$html .= "<td>".$p->created."</td>";
		$html .= "</tr>";	
	}

	$html .= "</table>";
	echo $html;
	
?>