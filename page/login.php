<?php

	require("../functions.php");
	
	require("../class/User.class.php");
	$User = new User($mysqli);
	
	require("../class/Helper.class.php");
	$Helper = new Helper();

	//kas kasutaja on sisse loginud
	if (isset ($_SESSION["userId"])) {
		
		header("Location: data.php");
		exit();
	}
	
	//MUUTUJAD
	$signupUsernameError = "";
	$signupEmailError = "";
	$signupPasswordError = "";
	$loginEmailError = "";
	$genderError = "";
	
	$loginEmail = "";
	$signupEmail = "";
	$signupUsername = "";
	$gender = "";
	

	if (isset ($_POST["loginEmail"]) ) {
	
		if (empty ($_POST["loginEmail"]) ) { 
			$loginEmailError = "See väli on kohustuslik!";
		} else {
			$loginEmail = $_POST["loginEmail"];   //jätab e-maili meelde, kui parool on vale
		}
	}
	

	if (isset ($_POST["signupUsername"]) ) {
	
		if (empty ($_POST["signupUsername"]) ) { 
			$signupUsernameError = "See väli on kohustuslik!";
		
		} else {
			$signupUsername = $_POST["signupUsername"];
		}
	}
	
	if (isset ($_POST["signupPassword"]) ) {
	
		if (empty ($_POST["signupPassword"]) ) { 
			$signupPasswordError = "See väli on kohustuslik!";
		
		} else {
			
			if (strlen ($_POST["signupPassword"]) <= 5 ){
				$signupPasswordError = "Parool peab olema 5 tähemärki pikk!";
			}
		} 
		
	}

	$gender = "";
	if (isset ($_POST["gender"]) ) {
	
		if (empty ($_POST["gender"]) ) { 
			$genderError = "See väli on kohustuslik!";
		} else {
			$gender = $_POST["gender"];
		}
	}
	
	if (isset ($_POST["signupEmail"]) ) {
	
		if (empty ($_POST["signupEmail"]) ) { 
			$signupEmailError = "See väli on kohustuslik!";
		} else {
			$signupEmail = $_POST["signupEmail"];
		}
	}
	
	// Kus tean, et ühtegi viga ei olnud ja saan kasutaja andmed salvestada
	if (isset($_POST["signupPassword"]) &&
		isset($_POST["signupEmail"]) &&
		isset($_POST["signupUsername"]) &&
		isset($_POST["gender"]) &&
		empty($signupEmailError) && 
		empty($signupUsernameError) && 
		empty($genderError) && 
		empty($signupPasswordError)  
	) {
			//näitab, millised andmed kasutaja sisestas: email, parool, räsi
		//echo "Salvestan...<br>";
		//echo "kasutajatunnus ".$signupUsername."<br>";
		//echo "email ".$signupEmail."<br>";
		
		$signupPassword = hash("sha512", $_POST["signupPassword"]);
		
		//echo "parool ".$_POST["signupPassword"]."<br>";
		//echo "räsi ".$signupPassword."<br>";
		//echo "sugu ".$gender."<br>";
		
		//echo $serverUsername; 
		
		$signupEmail = $Helper->cleanInput($signupEmail);
		$signupPassword = $Helper->cleanInput($signupPassword);
		
		$User->signUp($signupEmail, $signupPassword, $signupUsername, $gender);
		
		}
		
	//kontrollin, et kasutaja täitis välja ja võib sisse logida
	$error ="";
	if ( isset($_POST["loginEmail"]) && 
		isset($_POST["loginPassword"]) && 
		!empty($_POST["loginEmail"]) && 
		!empty($_POST["loginPassword"])
	  ) {
		  
		$error = $User->login($Helper->cleanInput($_POST["loginEmail"]), $Helper->cleanInput($_POST["loginPassword"]));
	}
	
?>

<?php require("../header.php"); ?>

<div class "edit" style="padding-left:30px;">

		<h1>Logi sisse</h1>

		<form method="POST">
			<p style="color:red;"><?=$error;?></p>
			
			<input name="loginEmail" type="email" value="<?=$loginEmail;?>" placeholder="E-maili aadress"> <?php echo $loginEmailError; ?>
			
			<br><br>
			
			<input name="loginPassword" type="password" placeholder="Parool">
			
			<br><br>
			
			<input type="submit" value="Logi sisse">
		
		</form>
		
		<br><br>
		
		<h1>Loo kasutaja</h1>
		
		<form method="POST"> 
		
			<input name="signupUsername" type="username" value="<?=$signupUsername;?>" placeholder="Kasutajatunnus"> <?php echo $signupUsernameError; ?>
		
			<br><br>
			
			<input name="signupPassword" type="password" placeholder="Parool"> <?php echo $signupPasswordError; ?>
			
			<br><br>
			
			<label>Sugu</label><br>
			
			 <?php if($gender == "male") { ?>
				<input type="radio" name="gender" value="male" checked> Male<br>
			 <?php } else { ?>
				<input type="radio" name="gender" value="male" > Male<br>
			 <?php } ?>
			 
			 <?php if($gender == "female") { ?>
				<input type="radio" name="gender" value="female" checked> Female<br>
			 <?php } else { ?>
				<input type="radio" name="gender" value="female" > Female<br>
			 <?php } ?>
			 
			 <?php if($gender == "other") { ?>
				<input type="radio" name="gender" value="other" checked> Other<br>
			 <?php } else { ?>
				<input type="radio" name="gender" value="other" > Other<br>
			 <?php } ?>
			
			<br><br>
		
			<input name="signupEmail" type="email" value="<?=$signupEmail;?>" placeholder="E-maili aadress"> <?php echo $signupEmailError; ?>
			
			<br><br>
			
			<input type="submit" value="Loo kasutaja">
		
		</form>
		
</div>
		
<?php require("../footer.php"); ?>