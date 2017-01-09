<?php 
	
	require("../functions.php");
	
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
	$signupName = "";
	$signupNameError = "";
	$loginEmail = "";
	$loginEmailError = "";
	$loginPassword = "";
	$loginPasswordError = "";

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
		
		
		$password = hash("sha512", $_POST["signupPassword"]);
		
		
		
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
		<body style='background-color:Bisque'>
		    
			<div class="col-sm-4 col-md-3">
				<h1>Logi sisse</h1>
				<form method="POST">
					<p style="color:red;"><?=$error;?></p>
					<label>E-post</label>
					<br>
					
					<div class="form-group">
						<input class="form-control" name="loginEmail" type="text">
					</div>
					
					
					<label>Parool</label>
					<br>
					<div class="form-group">
						<input class="form-control" name="loginPassword" type="password" placeholder="Parool">
					</div>
					
					<br><br>
					
					<input class="btn btn-success btn-sm hidden-xs" type="submit" value="Logi sisse">
					<input class="btn btn-success btn-sm btn-block visible-xs-block" type="submit" value="Logi sisse 2">
					
					
				</form>
			</div>
			
			<div class="col-sm-4 col-md-3 col-sm-offset-4 col-md-offset-3">
				<h1>Loo kasutaja</h1>
				<form method="POST">
					
					<label>E-post</label>
					<br>
					
					<div class="form-group">
						<input class="form-control" name="signupEmail" type="text" value="<?=$signupEmail;?>"> <?=$signupEmailError;?>
					</div>	
										
				    <label>Eesnimi</label><br>
					<div class="form-group">
						<input class="form-control" name="signupName" type="name" value="<?=$signupName;?>"> <?php echo $signupNameError; ?>
					</div>	
						
				    <label>Perekonnanimi</label><br>
					<div class="form-group">
						<input class="form-control" name="signupName" type="name" value="<?=$signupName;?>"> <?php echo $signupNameError; ?>
					</div>

					
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
					<label>Parool</label><br>
					<div class="form-group">
						<input class="form-control" type="password" name="signupPassword" placeholder="Parool"> <?php echo $signupPasswordError; ?>
					</div>
					<br><br>
					
					<input class="btn btn-success btn-sm hidden-xs" type="submit" value="Loo kasutaja">
					<input class="btn btn-success btn-sm btn-block visible-xs-block" type="submit" value="Loo kasutaja 2">

					
					
				</form>
			</div>
			
					
		</div>
		
	</div>
<?php require("../footer.php"); ?>