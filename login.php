<?php
	
	// võtab ja kopeerib faili sisu
	
	require("functions.php");
	
	if (isset ($_SESSION["userId"])) {
		
		header("Location: data.php");
		
	}




	//var_dump($_GET);
	//echo "<br>";
	//var_dump($_POST);
	
	$signupEmailerror = "";
	$signupEmail = "";
	$error = "";
	$loginEmail = "";
	$loginPassword = "";
	$loginEmailerror = "";
	$loginPassworderror = "";
	$age = "";
	$number = "";

	//kas e-post oli olemas
	if (isset ($_POST["signupEmail"])) {
		
		if (empty ($_POST ["signupEmail"])) {
			//oli e-post, kuid see oli tühi
			$signupEmailerror = "See väli on kohustuslik!";
		
		} else {
			
			// email on õige, salvestan väärtuse muutujasse
			$signupEmail = $_POST["signupEmail"];
		}
		
	}
	
	$signupPassworderror = "";

	//kas parool oli olemas
	if (isset ($_POST["signupPassword"])) {
		
		if (empty ($_POST ["signupPassword"])) {
			//oli parool, kuid see oli tühi
			$signupPassworderror = "See väli on kohustuslik!";
			
			
		}	else {
			
			//tean et parool ja see ei olnud tühi
			//vähemalt 8
			
			if (strlen($_POST["signupPassword"]) < 8 ) {
				$signupPassworderror = "Parool peab olema vähemalt 8 tähemärki pikk"; 
			}
		}
	}
	
	$signupAgeerror = "";
	
	//kas vanus oli vähemalt 18
	
	if (isset ($_POST["signupAge"])) {
		
		if (empty ($_POST ["signupAge"])) {
			$signupAgeerror = "See väli on kohustuslik!";
			
		}else { 
		
			if  (($_POST["signupAge"]) < 18 ) {
				$signupAgeerror = "Pead olema vähemalt 18 aastat vana";
				
			}

		}
	
		
	}
	
		
	$signupNumbererror = "";
	
	if (isset ($_POST["signupNumber"])) {
		
		if (empty ($_POST ["signupNumber"])) {
			//oli number, kuid see oli tühi
			$signupNumbererror = "See väli on kohustuslik!";
			
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
	
	// Kus tean, et ühtegi viga ei olnud ja saan kasutaja andmed salvestada
	
	if ( 
		 isset($_POST["signupPassword"]) &&
		 isset($_POST["signupEmail"]) &&
		 empty($signupEmailerror) && 
		 empty($signupPassworderror)    
		
		
		) {
		
		echo "Salvestan...<br>";
		echo "email ".$signupEmail."<br>";
		
		$password = hash("sha512", $_POST["signupPassword"]);
		
		echo "parool ".$_POST["signupPassword"]."<br>";
		echo "räsi ".$password."<br>";  
		
		//echo $serverUsername;
		
		$signupAge = $_POST["signupAge"];
		$signupNumber = $_POST["signupNumber"];

		$User->signup($signupEmail, $password, $signupAge, $signupNumber);
		
		
	}
	
	
	//kontrollin, et kasutaja täitis välja ja võib sisse logida
	
	if ( isset($_POST["loginEmail"]) &&
		isset($_POST["loginPassword"]) &&
		!empty($_POST["loginEmail"]) &&
		!empty($_POST["loginPassword"]) 
	  ) {
		  
		 //login sisse
		$error = $User->login($_POST["loginEmail"], $_POST["loginPassword"]);
		  
		  
		}


	// kas sisselogimisel on email ja parool olemas
	
	if (isset ($_POST["loginEmail"])) {
		
		
		if (empty ($_POST ["loginEmail"])) {
			//oli e-post, kuid see oli tühi
			$loginEmailerror = "See väli on kohustuslik!";
		}
	}	
	
	if (isset ($_POST["loginPassword"])) {
		
		if (empty ($_POST ["loginPassword"])) {
			//oli parool, kuid see oli tühi
			$loginPassworderror = "See väli on kohustuslik!";
		
		}
	}	
?>




<!DOCTYPE html>
<html>
	<head>
		<title>Sisselogimise lehekülg</title>
	</head>
	<body>

		<h1>Logi sisse</h1>
		
		<form method="POST">
			<p style="color:red;"><?=$error;?></p>
			<input name="loginEmail" type="email"  placeholder="E-post" value="<?php echo $loginEmail; ?>"> <?php echo $loginEmailerror;  ?>
			<br><br>
			<input name="loginPassword" type="password" placeholder="Parool"> <?php echo $loginPassworderror; ?>
			<br><br>
			<input type="submit" value="Logi sisse">
		
		
		</form>
		
		<head>
		<title>Sisselogimise lehekülg</title>
	</head>
	<body>

		<h1>Loo kasutaja</h1>
		
		<form method="POST">
			
			<input name="signupEmail" type="email" placeholder="E-post" value="<?php echo $signupEmail; ?>">  <?php echo $signupEmailerror;  ?>
			<br><br>
			<input name="signupPassword" type="password" placeholder="Parool"> <?php echo $signupPassworderror; ?>
			<br><br>
			<input name="signupAge" type="age" placeholder="Vanus"> <?php echo $signupAgeerror; ?>
			<br><br>
			<input name="signupNumber" type="number" placeholder="Telefoninumber">  <?php echo $signupNumbererror; ?>
			<br><br>
			<?php if($gender == "male") { ?>
				<input type="radio" name="gender" value="male" checked> Mees<br>
			 <?php } else { ?>
				<input type="radio" name="gender" value="male" > Mees<br>
			 <?php } ?>
			 
			 <?php if($gender == "Naine") { ?>
				<input type="radio" name="gender" value="female" checked> Naine<br>
			 <?php } else { ?>
				<input type="radio" name="gender" value="female" > Naine<br>
			 <?php } ?>
			 
			 <?php if($gender == "other") { ?>
				<input type="radio" name="gender" value="other" checked> Muu<br>
			 <?php } else { ?>
				<input type="radio" name="gender" value="other" > Muu<br>
			 <?php } ?>
			<br><br>
			<input type="submit" value="Loo kasutaja">
			
		
		</form>

	</body>
</html>