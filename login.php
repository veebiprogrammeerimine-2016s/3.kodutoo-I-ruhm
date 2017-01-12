<?php

	//võtab ja kopeerib faili sisu
	
	require ("functions.php");
	
	require("User.class.php");
	$User = new User($mysqli);
	
	require("Helper.class.php");
	$Helper = new Helper($mysqli);
	
	require("Pillid.class.php");
	$Pillid = new Pillid($mysqli);
	
	
	

	
	//kas kasutaja on sisse logitud
	if (isset ($_SESSION["userId"])) {
		
		header("Location: data.php");
		exit();
	}
	
	
	//GET ja POST muutujad
	//var_dump($_GET);
	//echo "<br>";
	//var_dump($_POST);
	
	//Muutujad
	$LoginEmail = "";
	$error = "";
	$signupEmailError = "";
	$signupPasswordError = "";
	$signupEmail = "";
	$genderError = "";
	$signupAge = "";
	$signupInstrument = "";
	$signupAgeError = "";
	$signupInstrumentError = "";
	$gender = "mees";
	$age = "";
	$instrument = "";
	
	//($_POST["signupEmail"])
	// on üldse olemas selline muutuja
	
	if(isset( $_POST["signupEmail"])){
		
		//jah on olemas
		// kas on tühi
		if( empty($_POST["signupEmail"])){
			
			//oli email, kuid see oli tühi
			$signupEmailError = "See väli on kohustuslik";
			
		} else {
			
			//email on õige, salvestan väärtuse muutujasse
			$signupEmail = $_POST["signupEmail"];
			
		}
	}
		
		if(isset( $_POST["signupPassword"])){
		
			if( empty($_POST["signupPassword"])){
			
				$signupPasswordError = "Parool peab olema vähemalt 8 tähemärki pikk!";
			}else {
				//siia jõuan siis kui parool oli olemas -isset
				//parool ei olnud tühi -empty
				
				if(strlen($_POST["signupPassword"]) <8 ) {
					
					$signupPasswordError = "Parool peab olema vähemalt 8 tähemärki pikk";
				
				}
			
			}
		}

	$gender = "mees";
	//KUI TÜHI
	//$gender = "";
	
	if(isset($_POST["gender"])) {
		if (empty($_POST["gender"])){
			$genderError = "See väli on kohustuslik!";
		} else {
			$gender = $_POST["gender"];
		}
	}
	
	
	
	
	
	
	if(isset($_POST["signupAge"])){
			
			if ( empty($_POST["signupAge"])){
				
				$signupAgeError = "Vanuse sisestamine on kohustuslik";
				
			} else {
				//Miinimumvanus peab olema vähemalt 16 aastat
				
				if(strlen($_POST["signupAge"]) >16 ) {
					
					$signupAgeError = "Registreerumiseks peate olema vähemalt 16 aastat vana";
				}
			$age = $_POST["signupAge"];
		}
		
		
	}
	
	if(isset($_POST["signupInstrument"])){
		
		if ( empty($_POST["signupInstrument"])){
			
			$signupInstrumentError = "Instrumendi lisamine on kohustuslik";
			
		} else {
				//Instrumendi lisamine on kohustuslik
				
			$instrument = $_POST["signupInstrument"];
		}		
		
	}

	if ( isset ($_POST["signupPassword"]) &&
			isset ($_POST["signupEmail"]) &&
			isset ($_POST["signupAge"]) &&
			isset ($_POST["signupInstrument"]) &&
			isset ($_POST["gender"]) &&
			empty($signupEmailError) && 
			empty($signupPasswordError) &&
			empty($signupAgeError) &&
			empty($genderError) &&
		 empty($signupInstrumentError)
		) {
		
			$password = hash("sha512", $_POST["signupPassword"]);
		
			//echo $serverPassword;
			
			$signupEmail = $Helper->cleanInput($signupEmail);
			$password = cleanInput($password);
			
			$User->signUp($signupEmail, $Helper->cleanInput($password), $Helper->cleanInput($age), $gender, $instrument);
		}	
	
	
	$error = "";
	//kontrollin, et kasutaja täitis kõik väljad ja võib sisse logida
	if ( isset($_POST["LoginEmail"]) &&
		 isset($_POST["LoginPassword"]) &&
		 !empty($_POST["LoginEmail"]) &&
		 !empty($_POST["LoginPassword"])
	) {
		
		$_POST["LoginEmail"] = cleanInput($_POST["LoginEmail"]);
		$_POST["LoginPassword"] = cleanInput($_POST["LoginPassword"]);
		
		//login sisse
		$error = $User->login($Helper->cleanInput($_POST["LoginEmail"]), $Helper->cleanInput($_POST["LoginPassword"]));
		
		
	}		
		
			
?>





<!DOCTYPE html>
<html>
<head>
	<title>Logi sisse või loo kasutaja</title>
</head>
<body>

	<h1>Logi sisse</h1>
	<form method="POST">
		<p style="color:red;"><?=$error;?></p>
		
		<input name="LoginEmail" type="text" placeholder = "Email" value= "<?=$LoginEmail;?>">
  
		<br><br>
		
		<input type="password" name="LoginPassword" placeholder="Parool">
		<br><br>
		
		<input type="submit" value="Logi sisse">
		
	
		
	</form>
	
	<h1>Loo kasuataja</h1>
	<form method="POST">
		<label>E-post</label>
		<br>
		
		<input name="signupEmail" type="email" placeholder = "Email" value= "<?=$signupEmail;?>"> <?php echo $signupEmailError; ?>
		<br><br>
		
		<input type="password" name="signupPassword" placeholder="Parool"> <?php echo $signupPasswordError; ?>
		<br><br>
		<?php if($gender == "mees") { ?>
				<input type="radio" name="gender" value="mees" checked> Mees<br>
			 <?php } else { ?>
				<input type="radio" name="gender" value="mees" > Mees<br>
			 <?php } ?>
			 
			 <?php if($gender == "naine") { ?>
				<input type="radio" name="gender" value="naine" checked> Naine<br>
			 <?php } else { ?>
				<input type="radio" name="gender" value="naine" > Naine<br>
			 <?php } ?>
			 
			 <?php if($gender == "muu") { ?>
				<input type="radio" name="gender" value="muu" checked> Muu<br>
			 <?php } else { ?>
				<input type="radio" name="gender" value="muu" > Muu<br>
			 <?php } ?>
		
		<label>Vanus
			<input name="signupAge" type="number">
			<?php echo $signupAgeError; ?>
			<br><br>
		
		<label>Millist instrumenti mängid?
			<select name="signupInstrument">
				<option value=""> Vali...</option>
				<option value="kitarr">Kitarr</option>
				<option value="basskitarr">Basskitarr</option>
				<option value="löökpillid">Löökpillid</option>
				<option value="klahvpill">Klahvpillid</option>
				<option value="mingi muu">Mingi muu</option>

			</select>
			<?php? echo $signupInstrumentError; ?>
			
			<br><br>
			<input type="submit" value="Loo kasutaja">
	
		</form>

</body>
</html>