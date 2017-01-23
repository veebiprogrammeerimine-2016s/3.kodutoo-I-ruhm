<?php

require_once("../../config.php");


function saveClubs ($clubName, $clubLocation, $rate) {

   $mysqli = new mysqli($GLOBALS["serverHost"],$GLOBALS["serverUsername"],$GLOBALS["serverPassword"],$GLOBALS["database"]);
/   $stmt = $mysqli->prepare("INSERT INTO goingClubbing (clubName, clubLocation, clubRate) VALUES (?, ?, ?)");
/   echo $mysqli->error;
/   $stmt->bind_param("ssi", $clubName, $clubLocation, $rate);
/
/   if ($stmt->execute()) {
/     echo "salvestamine õnnestus";
/   } else {
/     echo "ERROR ".$stmt->error;
/   }
/
/ }
/
/ function getAllClubs () { //fn kõikide klubide andmete saamiseks
/   $error = " ";
/   $mysqli = new mysqli($GLOBALS["serverHost"],$GLOBALS["serverUsername"],$GLOBALS["serverPassword"],$GLOBALS["database"]);
/   $stmt = $mysqli->prepare("
/
/       SELECT id, clubName, clubLocation, clubRate
/       FROM goingClubbing
/
/       ");
/       echo $mysqli->error;
/
/       $stmt->bind_result($id, $clubName, $clubLocation, $rate);
/       $stmt->execute(); //paneb käsu tööle! saame teada, kas käsk läks läbi või mitte
/
/       // array ("Inna", "I")
/       $result = array ();
/
/       //tingimus: seni, kuni on üks rida andmeid saada. 10 rida = 10 korda
/       while ($stmt->fetch()) { //üks rida andmeid andmebaasist ja paneb need muutujate asemele
/
/           $person = new StdClass();
/           //$person->id = $id;
/           $person->clubName = $clubName;
/           $person->clubLocation = $clubLocation;
/           $person->rate = $rate;
/
/
/           array_push($result, $person);
/       }
/
/       $stmt->close();
/       $mysqli->close();
/       return $result;
/
/ }
/
/ //enne funktsiooni sisse saatmist tehakse cleanInput
function update ($clubName, $clubLocation, $rate = '1, 2, 3, 4, 5') {

      $database = "if16_innasamm";

  $mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);

  $stmt = $mysqli->prepare("UPDATE goingClubbing SET clubName=?, clubLocation=?, clubRate=?  WHERE id=? AND deleted IS NULL");

  echo $mysqli->error;
  $stmt->bind_param("sss", $clubName, $clubLocation, $rate);

  // kas õnnestus salvestada
  if($stmt->execute()){
    // õnnestus
    echo "Salvestus õnnestus!";

  $stmt->close();
  $mysqli->close();

}


	function deleteClub($id){

        $database = "if16_innasamm";

		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);

		$stmt = $mysqli->prepare("UPDATE goingClubbing SET deleted=NOW() WHERE id=? AND deleted IS NULL");
		$stmt->bind_param("i",$id);

		// kas õnnestus salvestada
		if($stmt->execute()){
			// õnnestus
			echo "salvestus õnnestus!";
		}

		$stmt->close();
		$mysqli->close();

	}


?>
