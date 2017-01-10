<?php

	//võtab ja kopeerib faili sisu

	require("../functions.php");
	
	require("../class/User.class.php");
	$User = new User($mysqli);
	
	require("../class/Helper.class.php");
	$Helper = new Helper($mysqli);
	
	//kas kasutaja on sisse loginud
	if(isset($_SESSION["userId"])) {
		header("Location: data.php");
		exit();
	}

	//var_dump($_GET);
	//echo "<br>";
	//var_dump($_POST);
	//var_dump näitab andmetüüpi ning väärtust
	
	
	//MUUTUJAD
	$gender = ""; //kui soovid, et oleks eeltäidetud, siis lisad jutumärkide vahele female/male
	$genderError = "";
	
	$signupFirstName = "";
	$signupLastName = "";
	$signupBirthyear = "";
	$signupEmail = "";
	$signupPassword = "";
	
	$signupFirstNameError = "";
	$signupLastNameError = "";
	$signupBirthyearError = "";
	$signupEmailError = "";
	$signupPasswordError = "";
	
	$loginEmail = "";
	$loginPassword = "";
	$loginEmailError = "";
	$loginPasswordError = "";

	
	//KASUTAJA LOOMINE
	//kas eesnimi oli olemas
	if (isset ($_POST["signupFirstName"])) {
		
		if (empty ($_POST["signupFirstName"])) {
			$signupFirstNameError = "See väli on kohustuslik!";
			
		} else {
			$signupFirstName = $_POST["signupFirstName"];
		}
	}
	
	//kas perenimi oli olemas
	if (isset ($_POST["signupLastName"])) {
		
		if (empty ($_POST["signupLastName"])) {
			$signupLastNameError = "See väli on kohustuslik!";
			
		} else {
			$signupLastName = $_POST["signupLastName"];
		}
	}
	
	//kas sünniaasta oli olemas
	if (isset ($_POST["signupBirthyear"])) {
		
		if (empty ($_POST["signupBirthyear"])) {
			$signupBirthyearError = "See väli on kohustuslik!";
			
		} else {
			$signupBirthyear = $_POST["signupBirthyear"];
		}
	}	
	
	//kas epost oli olemas
	if (isset ($_POST["signupEmail"])) {
		
		if (empty ($_POST["signupEmail"])) {
			//oli email, kuid see oli tühi
			$signupEmailError = "See väli on kohustuslik!";
			
		} else {
			//email on oige, salvestan vaartuse muutujasse
			$signupEmail = $_POST["signupEmail"];
		}
	}
		
	//kas parool oli olemas
	if (isset ($_POST["signupPassword"])) {
			
		if (empty ($_POST["signupPassword"])) {
			//oli parool, kuid see oli tühi
			$signupPasswordError = "See väli on kohustuslik!";
			
		} else {
			//tean, et oli parool ja see ei olnud tühi
			//VÄHEMALT 8, soovitatav 16 täheline parool
			
			if (strlen($_POST["signupPassword"]) < 8 ) {
				$signupPasswordError = "Parool peab olema vähemalt 8 tähemärki pikk";
			}	
		}
	}
	
	//kas sugu oli olemas
	if (isset ($_POST["gender"])) {
			
		if (empty ($_POST["gender"])) {
			$genderError = "See väli on kohustuslik!";
			
		} else {
			$gender = $_POST["gender"];
		}
	}

	
	//Kus tean, et ühtegi viga ei olnud ja saan kasutaja andmed salvestada
	if( isset($_POST["signupFirstName"])&& 
		isset($_POST["signupLastName"])&&
		isset($_POST["signupBirthyear"]) &&
		isset($_POST["signupEmail"]) &&
		isset($_POST["signupPassword"]) && 
		isset($_POST["gender"]) && 
		empty($signupFirstNameError) &&
		empty($signupLastNameError) &&
		empty($signupBirthyearError) && 
		empty($signupEmailError) && 
		empty($signupPasswordError) && 
		empty($signupgenderError)){
		
		echo "Salvestan...<br>";
		echo "email ".$signupEmail. "<br>";
		
		$password = hash("sha512", $_POST["signupPassword"]);
		
		echo "parool ".$_POST["signupPassword"]."<br>";
		echo "räsi ".$password."<br>";
        
		
		//echo $serverPassword;
		
		$signupEmail = cleanInput ($signupEmail);
		$signupPassword = cleanInput($password);
		$signupFirstName = cleanInput ($signupFirstName);
        $signupLastName = cleanInput ($signupLastName);
		$signupBirthyear = cleanInput ($signupBirthyear);
		
		signup($signupFirstName, $signupLastName, $signupBirthyear, $signupEmail, $password, $gender);
	}
	
	//SISSE LOGIMINE
	
	if (isset ($_POST["loginEmail"]) ) {
	
		if (empty ($_POST["loginEmail"]) ) { 
			$loginEmailError = "See väli on kohustuslik!";
		} else {
			$loginEmail = $_POST["loginEmail"];   //jätab e-maili meelde, kui parool on vale
		}
	}
	
	$error = "";
	$notice = "";
	//kontrollin, et kasutaja täitis väljad ja võib sisse logida
	if( isset($_POST ["loginEmail"])&&
		isset($_POST["loginPassword"]) &&
		!empty($_POST["loginEmail"]) &&
		!empty($_POST["loginPassword"])
	) {
			//login sisse
			$notice= $User->login($Helper->cleanInput($_POST["loginEmail"]), $Helper->cleanInput($_POST["loginPassword"])); //functions error kandus üle notice muutujasse login funktsiooniga
		}
?>
<?php require("../header.php"); ?>

	<div class="container">
	
		<div class="row">
		
			<div class="col-sm-3">
			<h1>Logi sisse</h1>
		
		<form method="POST">
		
			<p style="color:red;"><?=$error;?></p>
			<label>E-post</label><br>
			<input name="loginEmail" type="email" value="<?=$loginEmail;?>"> <?php echo $loginEmailError;?>
			
			<br><br>
			
			<label>Parool</label><br>
			<input name="loginPassword" type="password" value="<?=$loginPassword;?>"><?php echo $loginPasswordError;?>
			<br><br>
						
			<input class="btn btn-success btn-sm hidden-xs" type="submit" value="Logi sisse">

		</form>	
		</div>
		
			
		<div class="col-sm-3 col-sm-offset-3">
		<h1>Loo kasutaja</h1>
		
		<form method="POST">
		
			<label>Eesnimi</label><br>
			<input name="signupFirstName" type="name" value="<?=$signupFirstName;?>"> <?php echo $signupFirstNameError; ?>
			
			<br><br>
			
			<label>Perekonnanimi</label><br>
			<input name="signupLastName" type="name" value="<?=$signupLastName;?>"> <?php echo $signupLastNameError; ?>			
			
			<br><br>
			
			<label>Sünniaasta</label><br>
			<input name = "signupBirthyear" type = "birthyear" value="<?=$signupBirthyear;?>"> <?php echo $signupBirthyearError; ?>
			
			<br><br>
			 
			<label>E-post</label><br>
			<input name="signupEmail" type="email" value="<?=$signupEmail;?>"> <?php echo $signupEmailError; ?>
			
			<br><br>
			
			<label>Parool</label><br>
			<input name="signupPassword" type="password"> <?php echo $signupPasswordError; ?>
			
			<br><br>

			<label>Sugu</label><br>
			 <?php if($gender == "male") { ?>
				<input type="radio" name="gender" value="male" checked> Mees<br>
			 <?php } else { ?>
				<input type="radio" name="gender" value="male" > Mees<br>
			 <?php } ?>
			 
			 <?php if($gender == "female") { ?>
				<input type="radio" name="gender" value="female" checked> Naine<br>
			 <?php } else { ?>
				<input type="radio" name="gender" value="female" > Naine<br>
			 <?php } ?>

			<br>
			
			<input class="btn btn-success btn-sm hidden-xs" type="submit" value="Loo kasutaja">
			
				</form>
			</div>
							
		</div>
		
	</div>

	</div>
<?php require("../footer.php"); ?>