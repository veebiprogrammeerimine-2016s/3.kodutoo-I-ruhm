<?php
     
	//võtab ja kopeerib faili sisu
	require("../../../config.php");
	require("../functions.php");
	
	//kas kasutaja on sisse logitud
	if (isset ($_SESSION["userid"])) {
		
		header("Location: data.php");
		exit();
	}
	
	
	//var_dump($_GET);
	//echo "<br>";
	//var_dump($_POST);
	
	//MUUTUJAD
	$signupEmailError="";
	$signupPasswordError="";
	$signupEmail = "";
	$signupName = "";
	
		if (isset($_POST["signupPassword"])){
		
		if (empty($_POST["signupPassword"])){
			
			$signupPasswordError="Väli on kohustuslik!";
			
				} else {
			
				if (strlen($_POST["signupPassword"]) <8) {
					$signupPasswordError="Parool peab olema vähemalt 8 tähemärki pikk";
				}
			
		}
	}
	if (isset($_POST["signupEmail"])){
				
		if (empty($_POST["signupEmail"])){
					
			$signupEmailError="Väli on kohustuslik!";
		
	
		} else {
			
			// email on õige, salvestan väärtuse muutujasse
			$signupEmail = $_POST["signupEmail"]; 
		
		}
	}
	if (isset($_POST["signupName"])){
		
		if (empty($_POST["signupName"])){
			
			$signupNameError="Väli on kohustuslik!";
			
		}
	}	
	
	$gender = "male";
	// KUI Tühi
	// $gender = "";
	
	if ( isset ( $_POST["gender"] ) ) {
		if ( empty ( $_POST["gender"] ) ) {
			$genderError = "See väli on kohustuslik!";
		} else {
			$gender = $_POST["gender"];
		}
	}
	
	
	// Kus tean et ühtegi viga ei olnud ja saan kasutaja andmed salvestada
	if ( isset($_POST["signupPassword"]) &&
	     isset($_POST["signupEmail"]) &&
	     empty($signupEmailError) &&  
		 empty($signupPasswordError) 
	   ) {
		
		// Salvestame andmebaasi
		
		$password = hash("sha512", $_POST["signupPassword"]);
	    
		//echo $serverPassword
		
		$signupEmail = $Helper->cleanInput($signupEmail);
		$password = $Helper->cleanInput($password);
		$signupName = $Helper->cleanInput($signupName);
		$loginEmail = $Helper->cleanInput($signupEmail);
		
		$User->signUp($signupEmail, $Helper->cleanInput($password));
		
		
		
	   
	
	}
?>
<?php require("../loginheader.php"); ?>
<!DOCTYPE html>
<html>
<style>
input[type=text], select {
    width: 100%;
    padding: 12px 20px;
    margin: 8px 0;
    display: inline-block;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}

input[type=submit] {
    width: 100%;
    background-color: #4CAF50;
    color: white;
    padding: 14px 20px;
    margin: 8px 0;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

input[type=submit]:hover {
    background-color: #45a049;
}

div {
    border-radius: 5px;
    background-color: #f2f2f2;
    padding: 20px;
}
</style>

		
	
				<div class="container">
	
				<div class="row">
				<body style='background-color:Silver'>
				<div class="col-sm-4 "></div>
				<div class="col-sm-4 ">
				<h3>Loo kasutaja</h3>
				<form method="POST">
				<label for="fname">E-post</label><br>
				<input type="email" id="fname" name="signupEmail"value="<?=$signupEmail;?>"> <?php echo $signupEmailError; ?>
				
				<br><br>
				
				<label for="lname">Parool</label><br>
				<input type="password" id="lname" name="signupPassword" placeholder="Parool"> <?php echo $signupPasswordError;?>
				
				<br> <br>
	
				<input class="btn btn-success btn-sm hidden-xs" type="submit" value="Registreeru">
				<input class="btn btn-success btn-sm btn-block visible-xs-block" type="submit" value="Registreeru">
				<br>
				<a href="login.php">Tagasi logima</a>
				
			<br><br>
			</form>

