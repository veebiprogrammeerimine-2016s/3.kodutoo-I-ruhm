<?php 
	//võtab ja kopeerib faili sisu
	require("../hw3_functions.php");
	
	require("../class/User.class.php");
	$User = new User($mysqli);
	
	require("../class/Helper.class.php");
	$Helper = new Helper($mysqli);
	
	//var_dump - näitab kõike, mis muutuja sees
	//var_dump($_GET);
	//echo "<br>";
	//var_dump($_POST);
	
	//MUUTUJAD
	$loginEmailError = "";
	$loginPasswordError = "";
	$firstNameError = "";
	$lastNameError = "";
	$signupEmailError = "";
	$signupPasswordError = "";
	$phoneNumberError = "";
	
	$firstName = "";
	$lastName = "";
	$loginEmail = "";
	$signupEmail = "";
	$gender = "";
	$phoneNumber = "";
	
	$msg="";
	
	if (isset ($_POST["loginEmail"]) ){
		if (empty ($_POST["loginEmail"]) ){
			$loginEmailError = "Palun sisesta e-post!";		
		} else {
			$loginEmail = $_POST["loginEmail"];
		}
	}
	
	if (isset ($_POST["loginPassword"]) ){ 
		if (empty ($_POST["loginPassword"]) ){ 
			$loginPasswordError = "Palun sisesta parool!";		
		}
	}
	
	if (isset ($_POST["firstName"]) ){
		if (empty ($_POST["firstName"]) ){
			$firstNameError = "See väli on kohustuslik!";		
		} else {
			//The preg_match() function searches a string for pattern, returning true if the pattern exists, and false otherwise.
			if (!preg_match("/^[a-zA-Z õäöüšž-]*$/",$_POST["firstName"])) { 
				$firstNameError = "Pole nimi!"; 
			} else {
				$firstName = $_POST["firstName"];
			}
		}
	}
	
	if (isset ($_POST["lastName"]) ){
		if (empty ($_POST["lastName"]) ){
			$lastNameError = "See väli on kohustuslik!";		
		} else {
			if (!preg_match("/^[a-zA-Z õäöüšž-]*$/",$_POST["lastName"])) { 
				$lastNameError = "Pole nimi!"; 
			} else {
				$lastName = $_POST["lastName"]; 
			}
		}
	}
	
	//kas e-post oli olemas
	if (isset ($_POST["signupEmail"]) ){ //kas keegi nuppu vajutas, kas signupEmail tekkis
		if (empty ($_POST["signupEmail"]) ){ //oli email, kuid see oli tühi
			//echo "email oli tühi";
			$signupEmailError = "See väli on kohustuslik!";		
		} else {
			//email on õige, salvestan väärtuse muutujasse
			$signupEmail = $_POST["signupEmail"];
		}
	}
	
	if (isset ($_POST["signupPassword"]) ){ 
		if (empty ($_POST["signupPassword"]) ){ 
			$signupPasswordError = "See väli on kohustuslik!";		
		} else {
			//tean, et oli parool ja see ei olnud tühi
			if (strlen($_POST["signupPassword"]) < 8){ //strlen- stringi pikkus
				$signupPasswordError = "Parool peab olema vähemalt 8 tähemärki pikk!";
			}
		}
	}
	
	if (isset ($_POST["gender"]) ){ 
		if (empty ($_POST["gender"]) ){ 
			$genderError = "";
		} else {
			$gender = $_POST["gender"];
		}
	}
	
	if (isset ($_POST["phoneNumber"]) ){
		if (empty ($_POST["phoneNumber"]) ){ 
			$phoneNumberError = "";
		} else {
			if (ctype_digit($_POST["phoneNumber"])){ //ctype_digit- checks if all of the characters in the Provided string, text, are numerical.
				$phoneNumberError = "";		
				$phoneNumber = $_POST["phoneNumber"];
			} else {
				$phoneNumberError = "Ainult numbrid on lubatud!";
			}
		}
	}
	
	//Kus tean, et ühtegi viga ei olnud ja saan kasutaja andmed salvestada.
	if (isset ($_POST["firstName"])
		&& isset ($_POST["lastName"])
		&& isset($_POST["signupEmail"])
		&& isset ($_POST["signupPassword"])
		//pole kohustuslik	&& isset ($_POST["gender"])
		//pole kohustuslik	&& isset ($_POST["phoneNumber"])
		&& empty($firstNameError) 
		&& empty($lastNameError) 
		&& empty($signupEmailError) 
		&& empty($signupPasswordError)
		&& empty($phoneNumberError) ){
			
		/*echo "Salvestan...<br>";
		echo "eesnimi ".$firstName."<br>";
		echo "perenimi ".$lastName."<br>";
		echo "email ".$signupEmail."<br>";*/
		
		$password = hash("sha512", $_POST["signupPassword"]); //hash(algoritm,parool)
		/*echo "parool ".$_POST["signupPassword"]."<br>";
		echo "räsi".$password."<br>";
		
		echo "sugu ".$gender."<br>";
		echo "telefoni number ".$phoneNumber."<br>";*/
		
		//echo $serverPassword;
		$msg = $User->signup($Helper->cleanInput($firstName), $Helper->cleanInput($lastName), $Helper->cleanInput($signupEmail), $Helper->cleanInput($password), $Helper->cleanInput($gender), $Helper->cleanInput($phoneNumber)); //cleanInput igale muutujale eraldi teha
	}
	
	$notice = "";
	//Kontrollin, et kasutaja täitis välja ja võib sisse logida 
	if (isset ($_POST["loginEmail"]) && 
		isset ($_POST["loginPassword"]) && 
		!empty ($_POST["loginEmail"]) && 
		!empty ($_POST["loginPassword"])
		){
			//login sisse
			$notice= $User->login($Helper->cleanInput($_POST["loginEmail"]), $Helper->cleanInput($_POST["loginPassword"])); //functions error kandus üle notice muutujasse login funktsiooniga
		}
		
		
?>
<?php require("../header.php")?>
	<div class="login" style="padding-left:20px;">
		<h1>Logi sisse</h1>	
		
		<form method="POST">
			<p style="color:red;"><?=$notice;?></p>
			<input name="loginEmail" type="email" placeholder="E-post" value= "<?=$loginEmail;?>"> <?php echo $loginEmailError; ?>
			<br><br>
			
			<input name="loginPassword" type="password" placeholder="Parool"> <?php echo $loginPasswordError; ?><!--Parooli väljale meelde ei jäta-->
			<br><br>
			
			<input type="submit" value = "Logi sisse">
		</form>
		
		<br>
		<p style="color:green;"> <b> <?=$msg;?> </b> </p>
		<h1>Loo kasutaja</h1>	
		
		<form method="POST">
			<p><span style="color:red; font-size: 10pt">* </span><span style="font-size: 10pt">Kohustuslik väli</span></p>
			<label>Eesnimi <span style="color:red">*</span></label>
			<br>
			<input name="firstName" type="text" value= "<?=$firstName;?>"> <?php echo $firstNameError; ?>
			<br><br>
			
			<label>Perekonnanimi <span style="color:red">*</span></label>
			<br>
			<input name="lastName" type="text" value= "<?=$lastName;?>"> <?php echo $lastNameError; ?>
			<br><br>
		
			<label>E-Post <span style="color:red">*</span></label>
			<br>
			<input name="signupEmail" type="email" value= "<?=$signupEmail;?>" > <?php echo $signupEmailError; ?> <!--jätab signupEmaili meelde väljale-->
			<br><br>
			
			<label>Parool <span style="color:red">*</span></label>
			<br>
			<input name="signupPassword" type="password"> <?php echo $signupPasswordError; ?>
			<br><br>
			
			
			<label>Sugu:</label> <!--Jätan vabatahtlikuks väljaks-->
			<?php if($gender == "female") { ?>
			<input type="radio" name="gender" value="female" checked>Naine
			<?php } else { ?>
			<input type="radio" name="gender" value="female">Naine
			<?php } ?>
			
			<?php if($gender == "male") { ?>
			<input type="radio" name="gender" value="male" checked>Mees
			<?php } else { ?>
			<input type="radio" name="gender" value="male">Mees
			<?php } ?>
			<br><br>
			
			<label>Telefoni number</label> <!--Jätan vabatahtlikuks väljaks-->
			<br>
			<input name="phoneNumber" type="text" value= "<?=$phoneNumber;?>"> <?php echo $phoneNumberError; ?>
			<br><br>
			
			<input type="submit" value = "Loo kasutaja">
		</form>
		<br><br>
	</div>
		 <!--Mvp ideeks on üldine foorum, kuhu saab postitada erinevaid teemasid ning kommenteerida olemasolevaid. Vastates teiste kasutajate teemadele saab koguda punkte ning neid kasutada oma teemadele "high priority" märkimisel või toodete/autasude lunastamisel. "High priority" eest saab oma teema tõsta teiste seast esile/ettepoole ning sellele motiveerib rohkem vastama, kuna võimalus on teenida rohkem punkte. Väga originaalset ideed hetkel ei ole, aga ehk tuleb teostamise käigus ning võib-olla idee ka muutub natukene.-->
<?php require("../footer.php")?>
