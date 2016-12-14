<?php
	
	require("../Functions.php");
	if (isset ($_SESSION["userid"])) {
		
		header("Location: data.php");
	}

	$loginEmail = "";
	$loginEmailError = "";
	$loginPassword = "";
	$loginPasswordError = "";
	$error = "";

	if ( isset ( $_POST["loginEmail"] ) ) {
		if ( empty ( $_POST["loginEmail"] ) ) {
			$loginEmailError = "See väli on kohustuslik!";
		} else {
			$loginEmail = $_POST["loginEmail"];
		}
	}

	if ( isset ( $_POST["loginPassword"] ) ) {
		if ( empty ( $_POST["loginPassword"] ) ) {
			$loginPasswordError = "See väli on kohustuslik!";
		} else {
			$loginPassword = $_POST["loginPassword"];
		}
	}

	if (isset ($_POST["loginEmail"]) &&
		isset ($_POST["loginPassword"]) &&
		!empty($_POST["loginEmail"]) &&
		!empty($_POST["loginPassword"]) 
	) {
		$_POST["loginEmail"] = $Helper->cleanInput($_POST["loginEmail"]);
		$_POST["loginPassword"] = $Helper->cleanInput($_POST["loginPassword"]);
		$error = $User->login($_POST["loginEmail"], $_POST["loginPassword"]);
		
	} 
?>
<?php require("../header.php"); ?>
	</head>
	<body>

		<center><h1>Sisselogimine Atsi kodutöösse</h1>
		
		<form method="POST">
			<p style="color:red;"><?=$error;?> </p>
			<div class="container">
				<div class="row">
					<div class="col-md-4 col-md-offset-4">
						<div class="form-group">
							<input class="form-control" name="loginEmail" type="text" placeholder="Email" value="<?=$loginEmail;?>"> <?php echo $loginEmailError; ?>
						</div>
						<div class="form-group">
							<input class="form-control" name="loginPassword" type="password" placeholder="Parool"> <?php echo $loginPasswordError; ?>
						</div>
					</div>
				</div>
			</div>


			<br><br>

			<input type="submit" class="btn btn-success" value="Logi sisse">
			<a href='Register.php'</a> <br><br> Registreeru
		</form>
	</center>
<?php require("../footer.php"); ?>