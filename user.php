<?php

	require("functions.php");

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

	$picData = getAllPics();
	$q = "";	

?>

<!DOCTYPE html>

	<a href="data.php">< Tagasi</a>
	<h1>Salvestatud pildid</h1>

	<form>
		<input type="search" name="q" value="<?=$q;?>">
		<input type="submit" value= "Otsi">
	</form>

	<br>

	<?php
    
		$html = "<table>";
		
		$html .= "<tr>";
			$html .= "<th>ID</th>";
			$html .= "<th>Autor</th>";
			$html .= "<th>Kuupäev</th>";
			$html .= "<th>Kirjeldus</th>";
		$html .= "</tr>";
		
		//Iga liikme kohta massiivis
		foreach($picData as $p){
			//Iga pilt on $p
			
			$html .= "<tr>";
				$html .= "<td>".$p->id."</td>";
				$html .= "<td>".$p->author."</td>";
				$html .= "<td>".$p->date_taken."</td>";
				$html .= "<td>".$p->description."</td>";
			$html .= "</tr>";
		}
		
		$html .= "</table>";
		
		echo $html;
    
	?>

</html>