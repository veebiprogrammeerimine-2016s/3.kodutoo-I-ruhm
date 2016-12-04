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
	

	// on üldse olemas selline muutja
	if( isset( $_POST["signupEmail"] ) ){
		
		//jah on olemas
		//kas on tühi
		if( empty( $_POST["signupEmail"] ) ){
			
			$signupEmailError = "See väli on kohustuslik";
			
		} else {
			
			// email olemas 
			$signupEmail = $_POST["signupEmail"];
			
		}
		
	} 
	
	if( isset( $_POST["signupPassword"] ) ){
		
		if( empty( $_POST["signupPassword"] ) ){
			
			$signupPasswordError = "Parool on kohustuslik";
			
		} else {
			
			// siia jõuan siis kui parool oli olemas - isset
			// parool ei olnud tühi -empty
			
			// kas parooli pikkus on väiksem kui 8 
			if ( strlen($_POST["signupPassword"]) < 8 ) {
				
				$signupPasswordError = "Parool peab olema vähemalt 8 tähemärkki pikk";
			
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
	
	if ( isset($_POST["signupEmail"]) && 
		 isset($_POST["signupPassword"]) && 
		 $signupEmailError == "" && 
		 empty($signupPasswordError)
		) {
		
		// salvestame ab'i
		echo "Salvestan... <br>";
		
		echo "email: ".$signupEmail."<br>";
		echo "password: ".$_POST["signupPassword"]."<br>";
		
		$password = hash("sha512", $_POST["signupPassword"]);
		
		echo "password hashed: ".$password."<br>";
		
		
		//echo $serverUsername;
		
		// KASUTAN FUNKTSIOONI
		$signupEmail = $Helper->cleanInput($signupEmail);
		
		$User->signUp($signupEmail, $Helper->cleanInput($password));
		
	
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
<?php require("../header.php"); ?>

	<div class="container">
		<div class="row">
		
			<div class="col-sm-6">
	
				<h1>Logi sisse</h1>
				<form method="POST">
					<p style="color:red;"><?=$error;?></p>
					<label>E-post</label>
					<br>
					
					<div class="form-group">
						<input class="form-control" name="loginEmail" type="text">
					</div>
					
					<br><br>
					
					<input type="password" name="loginPassword" placeholder="Parool">
					<br><br>
					
					<input class="btn btn-success btn-sm hidden-xs" type="submit" value="Logi sisse">
					<input class="btn btn-success btn-sm btn-block visible-xs" type="submit" value="Logi sisse">
					
					
				</form>
			</div>
			
			<div class="col-sm-6">
	
	
				<h1>Loo kasutaja</h1>
				<form method="POST">
					
					<label>E-post</label>
					<br>
					
					<input name="signupEmail" type="text" value="<?=$signupEmail;?>"> <?=$signupEmailError;?>
					<br><br>
					
					<?php if($signupGender == "male") { ?>
						<input type="radio" name="signupGender" value="male" checked> Male<br>
					<?php }else { ?>
						<input type="radio" name="signupGender" value="male"> Male<br>
					<?php } ?>
					
					<?php if($signupGender == "female") { ?>
						<input type="radio" name="signupGender" value="female" checked> Female<br>
					<?php }else { ?>
						<input type="radio" name="signupGender" value="female"> Female<br>
					<?php } ?>
					
					<?php if($signupGender == "other") { ?>
						<input type="radio" name="signupGender" value="other" checked> Other<br>
					<?php }else { ?>
						<input type="radio" name="signupGender" value="other"> Other<br>
					<?php } ?>
					
					
					<br>
					<input type="password" name="signupPassword" placeholder="Parool"> <?php echo $signupPasswordError; ?>
					<br><br>
					
					<input type="submit" value="Loo kasutaja">
					
					
				</form>
			</div>
	</div>
	<?php require("../footer.php"); ?>