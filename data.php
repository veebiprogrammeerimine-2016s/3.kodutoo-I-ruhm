<?php 
	require("functions.php");
	
	// kas on sisseloginud, kui ei ole siis
	// suunata login lehele
	if (!isset ($_SESSION["userId"])) {
		
		header("Location: login.php");
		
	}
	
	//kas ?logout on aadressireal
	if (isset($_GET["logout"])) {
		
		session_destroy();
		
		header("Location: login.php");
		
	}
	
	if ( isset($_POST["place"]) &&
		isset($_POST["time"]) &&
		isset($_POST["people"]) &&
		isset($_POST["comment"]) &&
		!empty($_POST["place"]) &&
		!empty($_POST["time"]) &&
		!empty($_POST["people"]) &&
		!empty($_POST["comment"]) 
		
	  ) {
		  
		 
		broneering($_POST["place"], $_POST["time"], $_POST["people"], $_POST["comment"]);
		  
		  
	}
	
	
	//ei ole tühjad väljad mida salvestada
	
	if ( isset($_POST["gender"]) &&
		isset($_POST["color"]) &&
		!empty($_POST["gender"]) &&
		!empty($_POST["color"]) 
	  ) {
		  
		 
		savePeople($_POST["gender"], $_POST["color"]);
		  
		  
	}

	
	$people = getAllPeople();
//
//	echo "<pre>";
//	var_dump($people);
//	echo "</pre>";
//
//
	
?>
<h1>Data</h1>
<p>
	Tere tulemast <?=$_SESSION["userId"];?>!
	<a href="?logout=1">Logi välja</a>
</p> 

<h1>Salvesta inimene</h1>
<form method="POST">
			
	<label>Sugu</label><br>
	<input type="radio" name="gender" value="male" > Mees<br>
	<input type="radio" name="gender" value="female" > Naine<br>
	<input type="radio" name="gender" value="other" > Ei oska öelda<br>
	
	<!--<input type="text" name="gender" ><br>-->
	
	<br><br>
	<label>Värv</label><br>
	<input name="color" type="color"> 
	
	<br><br>
	<input type="submit" value="Salvesta">
	
</form>

<h2>Arhiiv</h2>

<?php

	foreach($people as $p) {
		
		
		echo "<h3 style=' color:" .$p->clothingColor."; '>"
			 .$p->gender
			 ."</h3>";
		
		
	}

?>


<h2>Arhiivitabel</h2>
<?php

	$html="<table>";
	
	$html .= "<tr>";
		$html .= "<th></th>";
			$html .= "<th>id</th>";
			$html .= "<th>Sugu</th>";
			$html .= "<th>Värv</th>";
			$html .= "<th>Loodud</th>";
	$html .= "</tr>";

	foreach($people as $p) {
		
	$html .= "<tr>";
		
		$html .= "<td>".$p->id."</td>";
		$html .= "<td>".$p->gender."</td>";
		$html .= "<td style=' background-color:".$p->clothingColor."; '>" .$p->clothingColor."</td>";
		$html .= "<td>".$p->created."</td>";
	$html .= "</tr>";

		
	}
	
	$html .= "</table>";
	
	echo $html;



?>


<h1>Laudade broneerimine</h1>



<a href="broneering.php">BRONEERIMISE LEHELE</a>




