<?php
	//võtab ja kopeerib faili sisu
	
	require("functions.php");
	
	require("User.class.php");
	$User=new User($mysqli);
	
	require("Helper.class.php");
	$Helper=new Helper($mysqli);
	
	//kas kasutaja on sisse logitud
	if (isset($_SESSION["userId"])) {
		
		header("Location: data.php");
		exit();
	}
	
	//$random = " ";
	//var_dump(empty($random));
	
	//var_dump("Marii"); //näitab muutuja andmetüüpi, selle väärtust ja stringi pikkust
	
	//var_dump($_GET);
	//echo "<br>";
	//var_dump($_POST);
	
	//muutujad
	$SignupName="";
	$gender="";
	$SignupAge="";
	$SignupEmail="";
	$SignupEmailError="";
	$SignupPasswordError="";
	$SignupAgeError="";
	$SignupgenderError="";
	$SignupNameError="";
	$LoginEmail="";
	$LoginEmailError="";
	$LoginPassword="";
	$LoginPasswordError="";

	//kas e-post oli sisse logimisel olemas
	
	if (isset ($_POST["LoginEmail"]) ) {
		if (empty ($_POST["LoginEmail"]) ) {
		
			//oli email, kuid see oli tühi
		$LoginEmailError="See väli on kohustuslik!";
		
		} else{
			//email on õige, salvestan väärtuse muutujasse
			$LoginEmail=$_POST["LoginEmail"];
		}
		
	}
	
	//kas e-post oli kasutaja loomisel olemas
	if (isset ($_POST["SignupEmail"]) ) {
	
		if (empty ($_POST["SignupEmail"]) ) {
		
			//oli email, kuid see oli tühi
		
		$SignupEmailError="See väli on kohustuslik!";
		
		} else{
			//email on õige
			$SignupEmail=$_POST["SignupEmail"];
		}
		
	}
	
	//kas password oli sisse logimisel olemas
	if (isset ($_POST["LoginPassword"] ) ) {
		
		if (empty($_POST["LoginPassword"] ) ) {
		
			//oli password, kuid see oli tühi
			$LoginPasswordError="See väli on kohustuslik!";
			
		} else {
			
			//tean, et on parool ja see ei olnud tühi
			//vähemalt 8 tähemärki
			
			if (strlen($_POST["LoginPassword"] ) <8 ) {
				
					$LoginPasswordError="Parool peab olema vähemalt 8 tähemärki pikk";
					
			}
			
		}
		
	}
	
	//kas password oli kasutaja loomisel olemas
	if (isset ($_POST["SignupPassword"] ) ) {
		
		if (empty($_POST["SignupPassword"] ) ) {
		
			//oli password, kuid see oli tühi
			$SignupPasswordError="See väli on kohustuslik!";
			
		} else {
			
			//tean, et on parool ja see ei olnud tühi
			//vähemalt 8 tähemärki
			
			if (strlen($_POST["SignupPassword"] ) <8 ) {
				
					$SignupPasswordError="Parool peab olema vähemalt 8 tähemärki pikk";
					
			}
			
		}
		
	}
	
	$gender="male";
	// kui tühi
	//$gender="";
	
	if (isset ($_POST["SignupName"])) {
		if (empty($_POST["SignupName"])) {
			$SignupNameError="See väli on kohustuslik!";
		}else {
			$SignupName=$_POST["SignupName"];
		}
	}
	
	
	if (isset ($_POST["SignupAge"])) {
		if (empty($_POST["SignupAge"])) {
			$SignupAgeError="See väli on kohustuslik!";
		}else {
			$SignupAge=$_POST["SignupAge"];
		}
	}
	
	if (isset ($_POST["gender"])) {
		if (empty ($_POST["gender"])) {
			$genderError="See väli on kohustuslik!";
		} else{
			$gender=$_POST["gender"];
		}
	}

	//tean, et ühtegi viga ei olnud ja saan kasutaja andmed salvestada
if( isset($_POST["SignupName"])&& 
	isset($_POST["SignupAge"]) &&
	isset ($_POST["SignupEmail"]) &&
	isset ($_POST["SignupPassword"]) && 
	isset($_POST["gender"]) && 
	empty($SignupNameError) && 
	empty($SignupAgeError) && 
	empty($SignupEmailError) && 
	empty($SignupPasswordError) && 
	empty($SignupgenderError)){
		//echo "siin";
		//echo "Salvestamine õnnestus<br>";
		//echo "Email ".$SignupEmail."<br>";
		
		$password=hash("sha512", $_POST["SignupPassword"]);
		
		//echo "parool ".$_POST["SignupPassword"]."<br>";
		//echo "räsi ".$password."<br>";
		
		//echo $serverPassword;
		
		$SignupEmail=cleanInput($SignupEmail);
		$SignupPassword=cleanInput($password);
		
		$User->signup($SignupName,$SignupAge,$SignupEmail, $Helper->cleanInput($password),$gender);
			
	
	}
	
	$error="";
	
	
	//kontrollin, et kasutaja täitis välja ja võib sisse logida
	if(isset($_POST["LoginEmail"]) && isset($_POST["LoginPassword"]) && !empty($_POST["LoginEmail"]) && !empty($_POST["LoginPassword"])){
	
		//echo "siin";
		//$_POST["loginEmail"] = $Helper->cleanInput($_POST["loginEmail"]);
		//$_POST["loginPassword"] = $Helper->cleanInput($_POST["loginPassword"]);
		
	//login sisse
		$error=$User->login($Helper->cleanInput($_POST["LoginEmail"]), $Helper->cleanInput($_POST["LoginPassword"]));
		
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
			
			<input name="LoginEmail" type="email" placeholder="E-post" value="<?=$LoginEmail;?>"> <?php echo $LoginEmailError;?>
		
			<br><br>
		
			<input name="LoginPassword" type="password" placeholder="Parool"><?php echo $LoginPasswordError;?>
		
			<br><br>
		
			<input type="submit" value="Logi sisse">
			
		</form>
	
		<h1>Loo kasutaja</h1>
		<form method="POST">
			
			<input name="SignupName" type="name" placeholder="Nimi" value="<?=$SignupName;?>"> <?php echo $SignupNameError; ?>
			
			<br><br>
			
			<input name="SignupAge" type="age" placeholder="Vanus" value="<?=$SignupAge;?>"> <?php echo $SignupAgeError; ?>
			
			<br><br>
			
			<input name="SignupEmail" type="email" placeholder="E-post" value="<?=$SignupEmail;?>"> <?php echo $SignupEmailError; ?>
		
			<br><br>
		
			<input name="SignupPassword" type="password" placeholder="Parool"> <?php echo $SignupPasswordError; ?>
			
			<br><br>
			
			<?php if($gender=="male"){ ?>
				<input type="radio" name="gender" value="male" checked> Male<br>
			<?php } else { ?>
				<input type="radio" name="gender" value="male" checked> Male<br>
			<?php } ?>
			<?php if ($gender=="female") { ?>
				<input type="radio" name="gender" value="female" checked> Female<br>
			 <?php } else { ?>
				<input type="radio" name="gender" value="female" > Female<br>
			 <?php } ?>
			 
			 <?php if($gender == "other") { ?>
				<input type="radio" name="gender" value="other" checked> Other<br>
			 <?php } else { ?>
				<input type="radio" name="gender" value="other" > Other<br>
			 <?php } ?>
				
			<input type="submit" value="Loo kasutaja">
			
		</form>

	</body>
</html>