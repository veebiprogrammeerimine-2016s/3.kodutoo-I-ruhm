<?php

  //require ("../../config.php");
  require ("/home/innasamm/config.php");

	//function.php

  //alustan sessiooni, et saaks kasutada $_SESSION muutujaid

  session_start();

  $database = "if16_innasamm";
  $mysqli = new mysqli($serverHost, $serverUsername, $serverPassword, $database);

	//********************
	//****** SIGNUP ******
	//********************
	//$name = "innasamm";
	//var_dump($GLOBALS);

	//$database = "if16_innasamm";

	//function signup ($email, $password, $firstname, $familyname, $gender) {

		//$mysqli = new mysqli($GLOBALS["serverHost"],$GLOBALS["serverUsername"],$GLOBALS["serverPassword"],$GLOBALS["database"]);
		//$stmt = $mysqli->prepare("INSERT INTO user_sample (email, password, firstname, familyname, gender) VALUES (?, ?, ?, ?, ?)");
		//echo $mysqli->error;
	//	$stmt->bind_param("sssss", $email, $password, $firstname, $familyname, $gender);

//
// 		//if ($stmt->execute()) {
// 			//echo "salvestamine õnnestus";
// 		} else {
// 			echo "ERROR ".$stmt->error;
// 		}
//
// 	}
//
//
//
// function login ($email, $password) {
//   $error = " ";
//   $mysqli = new mysqli($GLOBALS["serverHost"],$GLOBALS["serverUsername"],$GLOBALS["serverPassword"],$GLOBALS["database"]);
//   $stmt = $mysqli->prepare("
//
//   SELECT id, email, password, created, firstname
//   FROM user_sample
//   WHERE email = ?");
//
//   echo $mysqli->error;
//
//   // asendan küsimärgi
//   $stmt->bind_param("s", $email);
//   // määran tulpadele muutujaid, kus ma tulpade andmeid http_negotiate_content_type
//
//   $stmt->bind_result($id, $emailFromDatabase, $passwordFromDb, $created, $firstnameFromDatabase);
//   $stmt->execute(); //paneb päringu teele, tulemus igal juhul tõene, päring õnnestub igal juhul
//   //küsin rea andmeid
//   if($stmt->fetch()) {
//       //oli rida
//       //võrdlen paroole
//       $hash = hash("sha512", $password);
//       if($hash == $passwordFromDb) {
//           echo "kasutaja " .$id." logis sisse";
//
//           $_SESSION ["userId"] = $id;
//           $_SESSION ["email"] = $emailFromDatabase;
//           $_SESSION ["firstname"] = $firstnameFromDatabase;
//
//
//           header ("Location: data.php ");
//           exit ();
//       } else {
//             $error =  "parool vale";
//       }
//
//   }  else {
//     //ei olnud
//     $error =  "sellise emailiga " .$email." kasutajat ei olnud";
//
//   }
//
//   return $error;
// }
//
//
// //uus funktsioon nimega saveClubs nt, kuhu saadame andmed clubName, clubLocation  ja hinde kohta tabelisse. aluseks signUp funktsioon
//
//
// function saveClubs ($clubName, $clubLocation, $rate) {
//
//   $mysqli = new mysqli($GLOBALS["serverHost"],$GLOBALS["serverUsername"],$GLOBALS["serverPassword"],$GLOBALS["database"]);
//   $stmt = $mysqli->prepare("INSERT INTO goingClubbing (clubName, clubLocation, clubRate) VALUES (?, ?, ?)");
//   echo $mysqli->error;
//   $stmt->bind_param("ssi", $clubName, $clubLocation, $rate);
//
//   if ($stmt->execute()) {
//     echo "salvestamine õnnestus";
//   } else {
//     echo "ERROR ".$stmt->error;
//   }
//
// }
//
// function getAllClubs () { //fn kõikide klubide andmete saamiseks
//   $error = " ";
//   $mysqli = new mysqli($GLOBALS["serverHost"],$GLOBALS["serverUsername"],$GLOBALS["serverPassword"],$GLOBALS["database"]);
//   $stmt = $mysqli->prepare("
//
//       SELECT id, clubName, clubLocation, clubRate
//       FROM goingClubbing
//
//       ");
//       echo $mysqli->error;
//
//       $stmt->bind_result($id, $clubName, $clubLocation, $rate);
//       $stmt->execute(); //paneb käsu tööle! saame teada, kas käsk läks läbi või mitte
//
//       // array ("Inna", "I")
//       $result = array ();
//
//       //tingimus: seni, kuni on üks rida andmeid saada. 10 rida = 10 korda
//       while ($stmt->fetch()) { //üks rida andmeid andmebaasist ja paneb need muutujate asemele
//           //echo $color. "<br>";
//           $person = new StdClass();
//           //$person->id = $id;
//           $person->clubName = $clubName;
//           $person->clubLocation = $clubLocation;
//           $person->rate = $rate;
//
//
//           array_push($result, $person);
//       }
//
//       $stmt->close();
//       $mysqli->close();
//       return $result;
//
// }
//
// //enne funktsiooni sisse saatmist tehakse cleanInput
//
// function cleanInput ($input) {   // meie e-mail jõuab siia ning salvestatakse inputi sisse
//
//   //input = sisestatud e-mail
//
//     $input = trim ($input);
//     $input = stripslashes ($input); //võtab kaldkriipsud ära
//     $input = htmlspecialchars ($input); //
//     return $input;
// }
//
//
//
// 	/*function sum ($x, $y) {
//
// 		return $x + $y;
//
// 	}
//
// 	function hello ($firstname, $lastname) {
//
// 		return "Tere tulemast ".$firstname." ".$lastname."!";
//
// 	}
//
// 	echo sum(5476567567,234234234);
// 	echo "<br>";
// 	$answer = sum(10,15);
// 	echo $answer;
// 	echo "<br>";
// 	echo hello ("Inna", "S.");
	
?>
