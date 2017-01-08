<?php



    require("../../config.php");
    $database = "if16_Aavister";
    $mysqli = new mysqli($serverHost, $serverUsername, $serverPassword, $database);

    require("User.class.php");
    $User = new User($mysqli);

    require("Reservation.class.php");
    $Reservation = new Reservation($mysqli);

	//alustan sessiooni, et saaks kasutada $_SESSION muutujaid

    session_start();



	//****************
	//*** SIGNUP *****
	//****************
	$database = "if16_Aavister";


	function cleanInput($input)  {

		$input = trim($input);
		$input = stripslashes($input);
		$input = htmlspecialchars($input);

		return $input;

	}



function savePeople ($gender, $color) {


    $error="";

    $mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);


    $stmt = $mysqli->prepare("INSERT INTO clothingOnTheCampus (gender,color) VALUES (?, ?)");

    echo $mysqli->error;

    $stmt->bind_param("ss", $gender, $color);

    if($stmt->execute()) {

        echo "salvestamine �nnestus";

    } else {
        echo "ERROR ".$stmt->error;





    }

}



function getAllPeople(){


    $mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);

    $stmt = $mysqli->prepare("SELECT id, gender, color, created FROM clothingOnTheCampus
		");

    echo $mysqli->error;

    $stmt->bind_result($id, $gender, $color, $created);
    $stmt->execute();

    $result= array();
    //seni kuni on �ks rida andmeid saada (10 rida = 10 korda)
    while ($stmt->fetch()) {

        $person = new StdClass();
        $person->id = $id;
        $person->gender = $gender;
        $person->clothingColor = $color;
        $person->created = $created;

        //echo $color."<br>";
        array_push($result, $person);


    }

    $stmt->close();
    $mysqli->close();

    return $result;

}















































	//functions.php


	/* function sum ($x, $y) {

		return $x + $y;


	}

	echo sum(5476567567, 234234234);
	echo "<br>";
	$answer = sum(10,15);
	echo $answer;
	echo "<br>"; */


	/* function hello ($firstname, $lastname) {

		return "Tere tulemast ".$firstname." ".$lastname."!";

	}
	echo hello ("Rasmus", "Aaviste");
 */



?>
