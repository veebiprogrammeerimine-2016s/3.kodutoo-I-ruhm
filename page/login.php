<?php 
	
	require("../functions.php");
	
	$User = new User($mysqli);
	
	$Helper = new Helper();
	
	// kui on juba sisse loginud siis suunan data lehele
	if (isset($_SESSION["userId"])){
		
		//suunan sisselogimise lehele
		header("Location: data.php");
		exit();
		
	}
	
	$loginEmail="";
	$loginPassword="";
	$emailError="";
	$passwordError="";
	
	//echo hash("sha512", "b");
	
	
	//GET ja POSTi muutujad
	//var_dump($_GET);
	//echo "<br>";
	//var_dump($_POST);
	
	//echo strlen("äö");
	
	if (isset($_POST["loginEmail"])){
				
		if (empty($_POST["loginEmail"])){
			$emailError="Väli on kohustuslik!";
		
			} else {
			$loginEmail=$_POST["loginEmail"];	
		}
	}
	
	if (isset($_POST["loginPassword"])){
				
		if (empty($_POST["loginPassword"])){
			$passwordError="Väli on kohustuslik!";
		}
	}
	$error ="";
	if ( isset($_POST["loginEmail"]) && 
		isset($_POST["loginPassword"]) && 
		!empty($_POST["loginEmail"]) && 
		!empty($_POST["loginPassword"])
	  ) 
	  
	  {
		  
		$error = $User->login($Helper->cleanInput($_POST["loginEmail"]), $Helper->cleanInput($_POST["loginPassword"]));
		
	}
	

?>
<?php require("../loginheader.php"); ?>

	<div class="container">
	
		<div class="row">
		<body style='background-color:Silver'>
			<div class="col-sm-4 "></div>
			<div class="col-sm-4 ">
				<h1>Logi sisse</h1>
				<form method="POST">
					<p style="color:red;"><?=$error;?></p>
					<label>E-post</label>
					<br>
					
					<div class="form-group">
						<input class="form-control" name="loginEmail" type="text" value="<?=$loginEmail;?>"><?php echo $emailError; ?>
					</div>
					
					
					<input type="password" name="loginPassword" placeholder="Parool" value="<?=$loginPassword;?>"> <?php echo $passwordError; ?>
					<br><br>
					
					<input class="btn btn-success btn-sm hidden-xs" type="submit" value="Logi sisse">
					<input class="btn btn-success btn-sm btn-block visible-xs-block" type="submit" value="Logi sisse">
					<br>
					
					<a href="registration.php">Pole kasutajat? Registreeru SIIN!</a>
					
					
				</form>
			</div>
		</div>
		
	</div>
<?php require("../footer.php"); ?>