<?php

	//Võtab ja kopeerib faili sisu
	require("functions.php");

	//Kas kasutaja on sisse logitud
	//Kui jah, siis suunata data lehele
	if(isset($_SESSION["userId"])){
		header("Location: data.php");
		exit();
	}

	//Muutujuad
	$signupEmailError="";
	$signupPasswordError="";
	$loginEmailError="";
	$loginPasswordError="";
	$genderError="";
	$gender="male";
	$signupEmail="";
	$loginError="";
	$loginSalvestatudEmail="";
	$birthdateError="";
	$birthdate="";

	//Kas see on üldse olemas
	if(isset($_POST["signupEmail"])){
		//On olemas, ehk keegi vajutas nuppu
		if(empty($_POST["signupEmail"])){
			//Kui oli tõesti väli tühjaks jäetud
			$signupEmailError="<i>See väli on kohustuslik!</i>";
		}else{
			$signupEmail=$_POST["signupEmail"];
		}
	}

	if(isset($_POST["signupPassword"])){
		if(empty($_POST["signupPassword"])){
			$signupPasswordError="<i>See väli on kohustuslik!</i>";
		}else{
			if(strlen($_POST["signupPassword"]) <8 ){
				$signupPasswordError="<i>Parool peab olema vähemalt 8 tähemärki pikk!</i>";
			}
		}
	}
		
	if(isset($_POST["gender"])){
		$gender = $_POST["gender"];
	}

	if(isset($_POST["loginEmail"])){
		if(empty($_POST["loginEmail"])){
			$loginEmailError="<i>See väli on kohustuslik!</i>";
		}
	}
	
	if(isset($_POST["loginPassword"])){
		if(empty($_POST["loginPassword"])){
			$loginPasswordError="<i>See väli on kohustuslik!</i>";
		}
	}

	if(isset($_POST["birthdate"])){
		if(empty( $_POST["birthdate"])){
			$birthdateError="<i>See väli on kohustuslik!</i>";
		}else{
			$birthdate=$_POST["birthdate"];
		}
	}
	
	// Kus tean, et ühtegi viga ei olnud ja saan kasutaja andmed salvestada
	if (isset($_POST["signupEmail"]) &&
		isset($_POST["signupPassword"]) &&
		isset($_POST["gender"]) &&
		isset($_POST["birthdate"]) &&
		empty($signupEmailError) && 
		empty($signupPasswordError) &&
		empty($genderError) &&
		empty($birthdateError)
	){
		//EI TÖÖTA !!! ------------------
		echo "Registreerin..<br>";
		echo "email".$signupEmail."<br>";
		//EI TÖÖTA !!! ------------------
		
		$password=hash("sha512", $_POST["signupPassword"]);
		
		$signupEmail = cleanInput($signupEmail);
		$password = cleanInput($password);
		$gender = cleanInput($gender);

		signup($signupEmail, $password, $gender, $birthdate);
		
		//Teen lehele refreshi
		header("Location: login.php");
		exit();
	}

	if(isset($_POST["loginEmail"]) &&
		isset($_POST["loginPassword"]) &&
		!empty($_POST["loginEmail"]) &&
		!empty($_POST["loginPassword"])
	){
		$_POST["loginEmail"] = cleanInput($_POST["loginEmail"]);
		$_POST["loginPassword"] = cleanInput($_POST["loginPassword"]);

		//Login sisse
		$loginError=login($_POST["loginEmail"], $_POST["loginPassword"]);
	}

?>

<!DOCTYPE html>
<html>
	
	<head>
		<title>Sisselogimine</title>
	</head>

	<body>
		
		<h2>Logi sisse:</h2>

		<form method="POST">
			
			<p style="color:red;"><?=$loginError;?></p>
			<input name="loginEmail" type="email" placeholder="E-Mail" value="<?=$loginSalvestatudEmail; ?>"> <?php echo "<font color='red'>$loginEmailError</font>"; ?>

				<br><br>

			<input name="loginPassword" type="password" placeholder="Parool"> <?php echo "<font color='red'>$loginPasswordError</font>"; ?>

				<br><br>

			<input type="submit" value="Logi sisse">
			
				<br><br>

		</form>

		<h2>Loo uus kasutaja:</h2>

		<form method="POST">

			<label>E-mail: </label>
			<input name="signupEmail" type="email" value="<?=$signupEmail;?>"> <?php echo "<font color='red'>$signupEmailError</font>"; ?>

				<br><br>

			<label>Parool: </label>
			<input name="signupPassword" type="password"> <?php echo "<font color='red'>$signupPasswordError</font>"; ?>

				<br><br>

			<label>Sugu: </label>
			
			<?php if($gender == "male") { ?>
				<input type="radio" name="gender" value="male" checked> Mees
			<?php }else{ ?>
				<input type="radio" name="gender" value="male"> Mees
			<?php } ?>
			
			<?php if($gender == "female") { ?>
				<input type="radio" name="gender" value="female" checked> Naine
			<?php }else{ ?>
				<input type="radio" name="gender" value="female"> Naine
			<?php } ?>

				<br><br>
			
			<label>Sünnikuupäev: </label>
			<input type="date" name="birthdate" value="<?=$birthdate;?>"> <?php echo "<font color='red'>$birthdateError</font>"; ?>

				<br><br>

			<input type="submit" value="Loo kasutaja">
			
		</form>

	</body>

</html>