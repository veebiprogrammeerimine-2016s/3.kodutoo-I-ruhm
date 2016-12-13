<?php 
	
	require("../functions.php");
	
	require("../class/User.class.php");
	$User = new User($mysqli);
	
	require("../class/Helper.class.php");
	$Helper = new Helper();
	
	// kui on juba sisse loginud siis suunan data lehele
	if (isset($_SESSION["userId"])){
		
		//suunan sisselogimise lehele
		header("Location: data.php");
		exit();
		
	}
	

	//echo hash("sha512", "b");
	
	
	//GET ja POSTi muutujad
	//var_dump($_GET);
	//echo "<br>";
	//var_dump($_POST);
	
	//echo strlen("äö");
	
	// MUUTUJAD
	$signupEmailError = "";
	$signupPasswordError = "";
	$signupEmail = "";
	$signupGender = "";
	$loginEmail = "";
	$loginPassword = "";
	$loginEmailError = "";
	$loginPasswordError = "";
	$nickname = "";
	$nicknameError = "";
	
	if( isset( $_POST["loginEmail"] ) ){

		if( empty( $_POST["loginEmail"] ) ){
			
			$loginEmailError = "This field is empty!";
			
		} else {
			
			// email olemas 
			$loginEmail = $_POST["loginEmail"];
			
		}
	} 
	if (isset($_POST ["loginPassword"])){
		if (empty ($_POST ["loginPassword"])){
			$loginPasswordError = "This field is empty!";
		}else {
			if (strlen($_POST["loginPassword"]) < 8) {
				$loginPasswordError = "Password is too short";
			}
		}
	}

	// on üldse olemas selline muutja
	if( isset( $_POST["signupEmail"] ) ){
		
		//jah on olemas
		//kas on tühi
		if( empty( $_POST["signupEmail"] ) ){
			
			$signupEmailError = "This field is empty!";
			
		} else {
			
			// email olemas 
			$signupEmail = $_POST["signupEmail"];
			
		}
		
	} 
	
	if( isset( $_POST["signupPassword"] ) ){
		
		if( empty( $_POST["signupPassword"] ) ){
			
			$signupPasswordError = "This field is empty!";
			
		} else {
			
			// siia jõuan siis kui parool oli olemas - isset
			// parool ei olnud tühi -empty
			
			// kas parooli pikkus on väiksem kui 8 
			if ( strlen($_POST["signupPassword"]) < 8 ) {
				
				$signupPasswordError = "Password is too short";
			
			}
			
		}
		
	}
	
	if (isset ($_POST ["nickname"])){
		if (empty($_POST ["nickname"])){
			$nicknameError = "This field is empty!";
		}else {
			if (strlen ($_POST["nickname"])< 8) {
				$nicknameError = "Nickname is too short";
			}else {
				$nickname = $_POST ["nickname"];
			}
		}	
	}
	
	
	// GENDER
	if( isset( $_POST["signupGender"] ) ){
		
		if(!empty( $_POST["signupGender"] ) ){
		
			$signupGender = $_POST["signupGender"];
			
		}
		
	} 
	
	// peab olema email ja parool
	// ühtegi errorit
	
	if ( isset($_POST["signupPassword"]) &&
		 isset($_POST["signupEmail"]) &&
		 isset($_POST["nickname"]) &&
		 empty($signupEmailError) && 
		 empty($signupPasswordError) &&
		 empty($nicknameError)) 
		 {
		
		// salvestame ab'i
		echo "Saving... <br>";
		
		echo "email: ".$signupEmail."<br>";
		//echo "password: ".$_POST["signupPassword"]."<br>";
		
		$password = hash("sha512", $_POST["signupPassword"]);
		
		//echo "password hashed: ".$password."<br>";
		
		
		//echo $serverUsername;
		
		// KASUTAN FUNKTSIOONI
		$signupEmail = $Helper->cleanInput($signupEmail);
		
		$User->signUp($signupEmail, $Helper->cleanInput($password), $Helper->cleanInput($nickname));
		
	
	}
	
	
	$error ="";
	if ( isset($_POST["loginEmail"]) && 
		isset($_POST["loginPassword"]) && 
		!empty($_POST["loginEmail"]) && 
		!empty($_POST["loginPassword"])
	  ) {
		  
		$error = $User->login($Helper->cleanInput($_POST["loginEmail"]), $Helper->cleanInput($_POST["loginPassword"]));
		
	}
	

?>

	
<!DOCTYPE html>

<html>
<head>
<style>
body {
    margin: 0;
    padding: 0;
    background: url('http://pre02.deviantart.net/5dfd/th/pre/f/2015/135/7/4/jinx_poro_by_ggmaster-d8tih77.png')  no-repeat;
}

body:before {
    content: " ";
    width: 100%;
    height: 100%;
    position: absolute;
    z-index: -1;
    top: 0;
    left: 0;
    background: -webkit-radial-gradient(top center, ellipse cover, rgba(100,255,255,0.2) 0%,rgba(100,0,0,0.5) 100%);
}
h1   {color: black;}

</style>
</head>
<body>

<form align="center"><font face = "Comic Sans MS"><h1>League of Legends Art Community</h1></font></form>

</body>
		<form align="center"><h1><font face = "Comic Sans MS"><i>Sign in</i></font></h1></form>
		<form method = "POST" align="center">
			<p style="color:red;"><?=$error;?></p>
			<!--<label>E-post</label><br>-->
			<input name="loginEmail" type = "email" placeholder="Email" value ="<?php echo $loginEmail; ?>"> <?php echo $loginEmailError; ?>
			<br><br>
			<input name="loginPassword" type = "password" placeholder="Password"> <?php echo $loginPasswordError; ?>
			<br><br>
			<input type="image" src="https://s12.postimg.org/g7fipmmgt/button.png" value="">
		</form>

	</body>
</html>

<form align="center"><h1><h1><font face = "Comic Sans MS"><i>Create account</i></font></h1></form>
		<form method = "POST" align="center">
			<!--<label>E-post</label><br>-->
			<input name="signupEmail" type = "email" placeholder="Email" value ="<?php echo $signupEmail; ?>"><?php echo $signupEmailError; ?>
			<br><br>
			<input name="signupPassword" type="password" placeholder="Password"><?php echo $signupPasswordError; ?>
			<br><br>
			<input name ="nickname" type = "text" placeholder = "Nickname"><?php echo $nicknameError; ?><br></br>
			<select name="gender">
			<option value="1" selected= "selected">male</option>
			<option value="2">female</option>
			<option value="3">other</option>
			</select>
		<p>
		
			<input type="image" src="https://s9.postimg.org/z5ko9xsy7/button_2.png" value="">
		</form>