<?php

require("../../config.php");
	//functions.php
	
	//alusan sessiooni, et saaks kasutada
	//$_SESSION muutujaid
	
	session_start();
	
	//********************************
	//**********SIGNUP***************
	//*******************************
	//var_dump($GLOBALS[]);
	
	
	$database = "if16_siim_1";
    //$order1 = "";
    //$cartype = "";
    //$clientname ="";
    //$washdate ="";

	function signup ($email, $password, $gender, $bday) {
		
		$date=date_create($bday);
		$newDate = date_format($date,"Y-m-d");
		
		//echo $gender;
		
		
		$mysqli = new mysqli($GLOBALS["serverHost"],$GLOBALS["serverUsername"],$GLOBALS["serverPassword"],$GLOBALS["database"]);
		
		$stmt = $mysqli->prepare("INSERT INTO user_sample (email,password, bday, gender) VALUES (?, ?, ?, ?)");
		echo $mysqli->error;
		
		$stmt->bind_param("ssss", $email, $password, $newDate, $gender); 
		
		if ($stmt->execute()) {
			
			echo "salvestamine 6nnestus";
		} else {
			echo "ERROR" .$stmt->error;
			
		}
	}
		
function login($email, $password){
		
	$error = "";	
		
	$mysqli = new mysqli($GLOBALS["serverHost"],$GLOBALS["serverUsername"],$GLOBALS["serverPassword"],$GLOBALS["database"]);
		
	$stmt = $mysqli->prepare("SELECT id, email, password, created FROM user_sample WHERE email = ?");
	echo $mysqli->error;
	
	//asendan kysim2rgi
	$stmt->bind_param("s", $email);
	
	//m22ran tulpadele mutujad
	$stmt->bind_result($id, $emailFromDb, $passwordFromDb, $created);
	$stmt->execute();
	
	if($stmt->fetch()){
		
		$hash = hash("sha512", $password);
		if($hash == $passwordFromDb){
			
			echo "kasuaja ".$id." logis sisse";
			
			$_SESSION["userID"] = $id;
			$_SESSION["email"] = $emailFromDb;
			
			//suunaks uuele lehele	
			header("Location: data.php");
			
			
		} else {
			$error = "parool vale";
			
		}
		
	} else {	
		
		$error = "sellise emailiga " .$email. " kasutajat ei olnud";
		
	}
	
	
	return $error;
	
}


 
 function cleanInput ($input) {

    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);

    return $input;

 }

function carWash ($order1, $cartype, $clientname, $washdate) {

    $error="";

    $mysqli = new mysqli($GLOBALS["serverHost"],$GLOBALS["serverUsername"],$GLOBALS["serverPassword"],$GLOBALS["database"]);

    $stmt = $mysqli->prepare("INSERT INTO Autopesula (order1, cartype, clientname, washdate ) VALUES (?, ?, ?, ?)");
    echo $mysqli->error;

    $stmt->bind_param("ssss", $order1, $cartype, $clientname, $washdate);

    if ($stmt->execute()) {

        echo "Broneering Õnnestus!";
    } else {
        echo "ERROR" .$stmt->error;

    }
}

function getWashData () {

    $mysqli = new mysqli($GLOBALS["serverHost"],$GLOBALS["serverUsername"],$GLOBALS["serverPassword"],$GLOBALS["database"]);

    $stmt = $mysqli->prepare("SELECT id, order1, cartype, clientname, washdate FROM Autopesula");
    echo $mysqli->error;

    $stmt->bind_result($id, $order1, $cartype, $clientname, $washdate);
    $stmt->execute();

    $result = array();

    //seni kuni on yks rida andmeid saada (10 rida = 10 korda)
    while ($stmt->fetch()) {

        $wash = new StdClass();
        $wash->id = $id;
        $wash->order1 = $order1;
        $wash->cartype = $cartype;
        $wash->clientname = $clientname;
        $wash->washdate = $washdate;

        //echo $color."<br>";
        array_push($result, $wash);


    }

    $stmt->close();
    $mysqli->close();

    return $result;

}








/*
function clothingCampus ($color, $gender_1) {

   $mysqli = new mysqli($GLOBALS["serverHost"],$GLOBALS["serverUsername"],$GLOBALS["serverPassword"],$GLOBALS["database"]);

   $stmt = $mysqli->prepare("INSERT INTO clothingOnTheCampus (color, gender_1) VALUES (?, ?)");
   echo $mysqli->error;

   $stmt->bind_param("ss", $color, $gender_1);

   if ($stmt->execute()) {

       echo "salvestamine 6nnestus";
   } else {
       echo "ERROR" .$stmt->error;

   }
} */

/*function getAllPeople () {

    $mysqli = new mysqli($GLOBALS["serverHost"],$GLOBALS["serverUsername"],$GLOBALS["serverPassword"],$GLOBALS["database"]);

    $stmt = $mysqli->prepare("SELECT id, color, gender_1, created FROM clothingOnTheCampus");
    echo $mysqli->error;

    $stmt->bind_result($id, $gender_1, $color, $created);
    $stmt->execute();

    $result = array();

    //seni kuni on yks rida andmeid saada (10 rida = 10 korda)
    while ($stmt->fetch()) {

        $person = new StdClass();
        $person->id = $id;
        $person->gender = $gender_1;
        $person->clothingColor = $color;
        $person->created = $created;

        //echo $color."<br>";
        array_push($result, $person);


    }

    $stmt->close();
    $mysqli->close();

    return $result;

}*/
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 /*
	function sum ($x, $y) {
	
		return $x + $y;
	
	}

	echo sum(427643634265,7473264);
	echo "<br>";
	$answer = sum (10,15);
	echo $answer;
	echo "<br>";

	
	function hello ($first_name, $last_name) {
		
		return "Tere tulemast ".$first_name." ".$last_name."!";
		
	}
	
	echo hello ("Siim", "Hytsi");






*/
?>