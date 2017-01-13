<?php
//mvp idee - Autopesula leht

	require("../../config.php");
	require("functions.php");
	//var_dump($_GET);
	//echo "<br>";
	//var_dump($_POST);
	
	if (isset ($_SESSION["userId"])) {
		
		header("Location: data.php");
		exit();

	}
	//MUUTUJAD
	$signupEmailError = "";
	$signupPasswordError = "";
	$loginEmailError = "";
	$loginPasswordError = "";
	$signupEmail = "";
	$gender = "";
	$error = "";
	$bday = "";
	$loginEmail = "";

	// kas e-post oli olemas
	
	if ( isset ( $_POST["signupEmail"] ) ) {
		
		if ( empty ( $_POST["signupEmail"] ) ) {
		
			// oli email, kuid see oli tühi
			$signupEmailError = "Insert correct e-mail!";
		
		} else {
			
			$signupEmail = $_POST["signupEmail"];
			
		}
		
	}
		
	
		if ( isset ( $_POST["signupPassword"] ) ) {
		
			if ( empty ( $_POST["signupPassword"] ) ) {
		
				// oli email, kuid see oli tühi
				$signupPasswordError = "Insert correct password!";
			
			} else {
				
				if ( strlen($_POST["signupPassword"]) < 8 ) {
					
					$signupPasswordError="Password must be atleast 8 character long!";
					
				}
			}
			
			
			if (isset($_POST["gender"])){
				
				$gender = $_POST["gender"];
				
			}
				
				
			
		}
		
	if ( isset ( $_POST["loginEmail"] ) ) {
			
		if ( empty ( $_POST["loginEmail"] ) ) {
				
			$loginEmailError = "Insert your e-mail!";
				
		} else {

			$loginEmail = ($_POST["loginEmail"]);

		}
			
	}

	if ( isset ( $_POST["loginPassword"] ) ) {
		
		if ( empty ($_POST["loginPassword"] ) ) {
			
			$loginPasswordError = "Insert your password!";
			
		}
		
	}
	
	
	// Kus tean et yhtegi viga ei olnud ja saan kasutaja andmed salvestada
	if ( isset($_POST["signupPassword"])  &&
		 isset($_POST["signupEmail"])  &&
		 empty($signupEmailError) &&
		 empty($signupPasswordError) 
		) {
		
		echo "Salvestan...<br>";
		//echo "email ".$signupEmail."<br>";
		
		$password = hash ("sha512", $_POST["signupPassword"]);
		
		//echo"parool ".$_POST["signupPassword"]."<br>";
		//echo"r2si ".$password."<br>";
		
		//echo $serverUsername;
		//echo $serverPassword;

		$signupEmail = cleanInput($signupEmail);
		$password = cleanInput($password);

		signup($signupEmail, $password, $gender, $bday);
	}
	
	
	// kontrollin, et kasutaja t2itas v2lja ja v6ib sisse logida
	if ( isset($_POST["loginEmail"]) &&
		 isset($_POST["loginPassword"]) &&
		 !empty($_POST["loginEmail"]) &&
		 !empty($_POST["loginPassword"])
	) {

        $_POST["loginEmail"] = cleanInput($_POST["loginEmail"]);
        $_POST["loginPassword"] = cleanInput($_POST["loginPassword"]);

	
		$error = login($_POST["loginEmail"], $_POST["loginPassword"]);
		
	}
	
	
	
	
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Log in or Sign up</title>
		<style>
			.form-input{
				max-width: 193px;
				color:darkorange;
				margin: 0 auto;
			}
			.heading-center{
				margin: 0 auto;
				max-width: 193px;

			}

		</style>
	</head>
	<body>

	<h1 class="form-input">Log in</h1>
		<form method = "POST">
			<p style="color:red;"><?=$error;?>
			<fieldset class="heading-center">
				<legend>Existing users.</legend>
				<lable>E-mail</lable><br> 
				<input name = "loginEmail" type = "email" placeholder = "E-mail"> <?php echo $loginEmailError; ?>
				
				<br><br>
				<lable>Password</lable><br>
				<input name = "loginPassword" type = "password" placeholder = "Password"> <?php echo $loginPasswordError?>
				
				<br><br>
				
				<input  type = "submit" value="Log in">
			</fieldset>	
		</form>
		
	<h1 class="form-input">Sign up</h1><br>
		<form method = "POST">
			<fieldset class="heading-center">
				<legend>Create a new user.</legend>
				<lable>E-mail</lable><br> 
				<input name = "signupEmail" type = "email" placeholder = "E-mail" value = "<?= $signupEmail;?>"> <?php  echo $signupEmailError; ?>
				
				<br><br>
				<lable>Password</lable><br>
				<input name = "signupPassword" type = "password" placeholder = "Password"> <?php  echo $signupPasswordError; ?>
				
				<br><br>
				<lable>Gender</lable><br>
				<?php if ($gender == "female") { ?>
					<input type="radio" name="gender" value="female" checked>Female
				<?php } else { ?>
					<input type="radio" name="gender" value="female" > Female<br>
				<?php } ?>
				
				<?php if ($gender == "male") { ?>
					<input type="radio" name="gender" value="male" checked> Male<br>
				<?php } else { ?>
					<input type="radio" name="gender" value="male" > Male<br>
				<?php } ?>
				<br><br>
				<lable>Date of Birth</lable><br>
				<input type="date" name="bday">
				<br><br>
				<input  type = "submit" value="Sign up">
			</fieldset>
		</form>
	</body>
</html>

