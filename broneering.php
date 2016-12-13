<?php
require("functions.php");

$place = "";
$time = "";
$people = "";
$comment = "";
$placeError ="";
$timeError = "";
$peopleError = "";
$commentError = "";

	if (isset ($_POST["place"])) {
		
		if (empty ($_POST ["place"])) {
			$placeError = "See väli on kohustuslik!";
		} else {
			$place = $_POST["place"];
			   }	
		}
	if (isset ($_POST["time"])) {
		
		if (empty ($_POST ["time"])) {
			$timeError = "See väli on kohustuslik!";
		} else {
			$time = $_POST["time"];
		}
	}
	if (isset ($_POST["people"])) {
		
		if (empty ($_POST ["people"])) {
			$peopleError = "See väli on kohustuslik!";
		} else {
			$people = $_POST["people"];
			   }
	}
	if (isset ($_POST["comment"])) {
		
		if (empty ($_POST ["comment"])) {
			$commentError = "See väli on kohustuslik!";
		} else {
			$comment = $_POST["comment"];
		}
	}
		
		if ( isset($_POST["place"]) &&
		isset($_POST["time"]) &&
		isset($_POST["people"]) &&
		isset($_POST["comment"]) &&
		!empty($_POST["place"]) &&
		!empty($_POST["time"]) &&
		!empty($_POST["people"]) &&
		!empty($_POST["comment"]) 
		
	)

	
	{
		  	 
		$Reservation->save($_POST["place"], $_POST["time"], cleanInput($_POST["people"]), cleanInput($_POST["comment"]));
			  
	}
	
	// elseif (
		
		// isset($_POST["place"]) &&
		// isset($_POST["time"]) &&
		// isset($_POST["people"]) &&
		// isset($_POST["comment"]) &&
		// (empty($_POST["place"]) OR
		// empty($_POST["time"]) OR
		// empty($_POST["people"]) OR
		// empty($_POST["comment"]) 
	
		// )
		
	// ) {
	
		// echo "täida kõik väljad!";
		
	// }   EI OSANUD PANNA NII, ET JÄTAKS VÄLJAD TÄIDETUKS KUI MÕNI TÜHJAKS JÄI
	
	$save = $Reservation->getAll();


?>

<h1>Laudade broneerimine</h1>

<form method="POST">
<label>Millisesse kohta soovite lauda broneerida?</label>
<br><br>
<select name="place" type="text" value="<?php echo $place; ?>"> <?php echo $placeError;  ?>
  <option value="">Vali koht</option>
  <option value="butterfly">Butterfly Lounge</option>
  <option value="tuljak">Tuljak</option>
  <option value="wesset">Villa Wesset</option>
  <option value="steffani">Steffani Pizzarestoran</option>
  <option value="noa">NOA</option>
  <option value="raimond">Restoran Raimond</option>
</select>
<br><br>


<label>Mis kell saabute?</label>
<br><br>
<input name="time" type="time" placeholder="Kellaaeg" value="<?php echo $time; ?>"> <?php echo $timeError;  ?>
<br><br>

	

<label>Mitu inimest teid on?</label>
<br><br>
<input name="people" type="text" placeholder="Mitu inimest?" value="<?php echo $people; ?>"> <?php echo $peopleError;  ?>
<br><br>

<label>Siia kirjutage oma erisoovid.</label>
<br><br>
<input name="comment" type="text" placeholder="Erisoov" style="width: 500px;" value="<?php echo $comment; ?>"> <?php echo $commentError;  ?>
<br><br>

<br><br>
<input type="submit" value="Salvesta">


</form>


<h2>Broneeringute tabel</h2>
<?php

	$html="<table>";
	
	$html .= "<tr>";
		
			$html .= "<th>id</th>";
			$html .= "<th>place</th>";
			$html .= "<th>time</th>";
			$html .= "<th>people</th>";
			$html .= "<th>comment</th>";
	$html .= "</tr>";

	foreach($save as $r) {
		
		$html .= "<tr>";
		
			$html .= "<td>".$r->id."</td>";
			$html .= "<td>".$r->place."</td>";
			$html .= "<td>".$r->time."</td>";
			$html .= "<td>".$r->people."</td>";
			$html .= "<td>".$r->comment."</td>";
			$html .= "<td><a href='edit.php?id=".$r->id."'>edit.php</a></td>";
		$html .= "</tr>";

		
	}
	
	// $html .= "</table>";
	
	echo $html;

	// echo "<pre>";
	// var_dump($reservation);
	// echo "</pre>";


?>