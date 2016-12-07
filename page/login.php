<?php
	require("functions.php");

	if (isset ($_SESSION["userId"])) {
		
		header("Location: data.php");
		exit();
	}
	

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
			$loginEmail = $_POST["loginEmail"];
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
	
	if (isset($_POST["signupPassword"]) &&
		isset($_POST["signupEmail"]) &&
		isset($_POST["signupUsername"]) &&
		isset($_POST["gender"]) &&
		empty($signupEmailError) && 
		empty($signupUsernameError) && 
		empty($genderError) && 
		empty($signupPasswordError)  
	) {
		echo "Salvestan...<br>";
		echo "kasutajatunnus ".$signupUsername."<br>";
		echo "email ".$signupEmail."<br>";
		
		$signupPassword = hash("sha512", $_POST["signupPassword"]);
		
		echo "parool ".$_POST["signupPassword"]."<br>";
		echo "räsi ".$signupPassword."<br>";
		echo "sugu ".$gender."<br>";
		
		
		$signupEmail = cleanInput($signupEmail);
		$signupPassword = cleanInput($signupPassword);
		
		signup($signupEmail, $signupPassword, $signupUsername, $gender);
		}
		
		$error = "";

		if(isset($_POST["loginEmail"]) &&
			isset($_POST["loginPassword"]) &&
			!empty($_POST["loginEmail"]) &&
			!empty($_POST["loginPassword"])
		) {
			
			$loginEmail = cleanInput($loginEmail);
			$loginPassword = cleanInput($_POST["loginPassword"]);
			
			//login sisse
			$error = login($_POST["loginEmail"], $loginPassword);
			
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
			
			<input name="loginEmail" type="email" value="<?=$loginEmail;?>" placeholder="E-maili aadress"> <?php echo $loginEmailError; ?>
			
			<br><br>
			
			<input name="loginPassword" type="password" placeholder="Parool">
			
			<br><br>
			
			<input type="submit" value="Logi sisse">
		
		</form>
		
		<br><br>
		
		<h1>Loo kasutaja</h1>
		
		<form method="POST"> 
		
			<input name="signupEmail" type="email" value="<?=$signupEmail;?>" placeholder="E-maili aadress"> <?php echo $signupEmailError; ?>
			
			<br><br>
		
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
			
			<input type="submit" value="Loo kasutaja">
		
		</form>
		
	</body>

</html>