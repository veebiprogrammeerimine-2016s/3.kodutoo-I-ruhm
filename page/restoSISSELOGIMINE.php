<?php
	//votab ja kopeerib faili sisu
	require("../restoFUNCTIONS.php");

	
	//kas kasutaja on sisse loginud
	if(isset ($_SESSION["userId"])) {
		
		header ("Location: restoDATA.php");
		exit();
		
	}
	
	
	//var_dump($_GET);
	//echo "<br>";
	//var_dump($_POST);
	
	$signupEmailError = "";
    $signupPassword2 = "";
	$signupPasswordError = "";
	$signupError = "";
	$signupEmail = "";
	$signupage = "";
	$signupageError = "";
	$loginpassword = "";
	$loginpasswordError = "";
	$loginEmail = "";
	$loginemailError = "";
	$phonenr = "";
	$signupgender = "";
    $signupgenderError = "";
	$signupName = "";
	$signupNameError = "";
	$signupLName = "";
	$signupLNameError = "";

	
	//kas on üldse olemas
	if (isset ($_POST ["signupEmail"])) {
		// oli olemas, ehk keegi vajutas nuppu
		if (empty($_POST ["signupEmail"])) {
			//oli tõesti tühi
			$signupEmailError = "Sisesta E-mail!";
		} else {
			
			$signupEmail = $_POST ["signupEmail"];
			
		}
			
	}
	//kas on üldse olemas
	if (isset ($_POST["signupName"])) {
		// oli olemas, ehk keegi vajutas nuppu
		if (empty($_POST["signupName"])) {
			//oli tõesti tühi
			$signupNameError = "Sisesta eesnimi!";
		} else {

			$signupName = $_POST["signupName"];

		}

	}
	//kas on üldse olemas
	if (isset ($_POST["signupLName"])) {
		// oli olemas, ehk keegi vajutas nuppu
		if (empty($_POST["signupLName"])) {
			//oli tõesti tühi
			$signupLNameError = "Sisesta perekonnanimi!";
		} else {

			$signupLName = $_POST["signupLName"];

		}

	}
    if (isset ($_POST ["phonenr"])) {
        // oli olemas, ehk keegi vajutas nuppu
        if (empty($_POST ["phonenr"])) {
            //oli tõesti tühi
            $phonenrError = "Sisesta telefoni number!";
        } else {

            $phonenr = $_POST ["phonenr"];

        }

    }
    if (isset ($_POST ["signupage"])) {
        // oli olemas, ehk keegi vajutas nuppu
        if (empty($_POST ["signupage"])) {
            //oli tõesti tühi
            $signupageError = "Sisesta vanus!";
        } else {

            $signupage = $_POST ["signupage"];

        }

    }
    if (isset ($_POST ["signupage"])) {
        // oli olemas, ehk keegi vajutas nuppu
        if (empty($_POST ["signupgender"])) {
            //oli tõesti tühi
            $signupgenderError = "Vali sugu!";
        } else {

            $signupgender = $_POST ["signupgender"];

        }

    }
    //kas on üldse olemas
    if (isset ($_POST["signupPassword"])) {
        // oli olemas, ehk keegi vajutas nuppu
        if (empty($_POST["signupPassword"])) {
            //oli tõesti tühi
            $signupPasswordError = "Sisesta parool!";

        } else {
            //oli midagi, ei olnud tühi
            //kas pikkust vähemalt 8

            if (strlen($_POST["signupPassword"]) < 8 ) {

                $signupPasswordError = "Parool peab olema vähemalt 8 tähemärki pikk";

            }
        }
    }
    if (isset ($_POST ["signupPassword"]) or (isset($_POST ["signupPassword2"]))) {
        if (empty($_POST ["signupPassword"]) or (empty($_POST ["signupPassword2"]))) {
            //oli tõesti tühi
            $signupPasswordError = "Sisesta Parool 2 korda!";
        } elseif (($_POST ["signupPassword"]) != ($_POST ["signupPassword2"])) {
            $signupPasswordError = "Paroolid ei ühti!";
        } else {

            $signupPassword = $_POST ["signupPassword"];

        }
    }

		//tean yhtegi viga ei olnud
	if( empty($signupEmailError)&&
		empty($signupPasswordError)&&
		empty($signupNameError)&&
		empty($signupLNameError)&&
		isset($_POST["signupPassword"])&&
		isset($_POST["signupEmail"])&&
		isset($_POST["signupName"])&&
		isset($_POST["signupLName"])&&
        isset($_POST["signupage"])&&
		isset($_POST["signupgender"])&&
        isset($_POST["phonenr"])
		
		)
		{
		
		echo "SALVESTAN...<br>";
		//echo "email: ".$signupEmail."<br>";
		$password = hash ("sha512", $_POST["signupPassword"]);
		//echo "parool: ".$_POST["signupPassword"]."<br>";
		//echo "parooli rasi: ".$password."<br>";
		//echo "vanus: ".$signupage."<br>";
		//echo "nimi: ".$signupName." ".$signupLName."<br>";
		//echo "sugu: ".$signupgender."<br>";
        //echo "telefoni number: ".$phonenr."<br>";
		
		$signupEmail = cleanInput($signupEmail);
		$password = cleanInput($password);
		$User->signup($signupEmail, $password, $signupName, $signupLName, $signupage, $phonenr, $signupgender);
		
	}
	


	if (isset($_POST["loginPassword"])){
		
		if (empty($_POST["loginPassword"])){
			
			$loginpasswordError = "sisesta parool!";
		}
	}
	if (isset($_POST["loginEmail"])){
		
		if (empty($_POST["loginEmail"])){
			
			$loginemailError = "sisesta e-mail!";
		}
	}

	$error= "";
	//kontrollin et kasutaja taitis valjad ja voib sisse logida
	if( isset($_POST["loginEmail"]) &&
		isset($_POST["loginPassword"]) &&
		!empty($_POST["loginEmail"]) &&
		!empty($_POST["loginPassword"])

	)	{
		
		$_POST["loginEmail"] = cleanInput($_POST["loginEmail"]);
		$_POST["loginPassword"] = cleanInput($_POST["loginPassword"]);
		//login sisse
		$error = $User->login($_POST["loginEmail"],$_POST["loginPassword"]);
	}
?>
<?php require("../header.php");?>
<fieldset style="margin: 0 auto;width: 360px;">
<h1 style="color: dodgerblue;"><b style="font-size: 70px;">RestoGuru</b></h1>
</fieldset>
	<div class="container">

		<div class="row">



			<div class="col-sm-6 col-md-3 col-sm-offset-4 col-md-offset-3">

				<h2>Logi sisse</h2>

				<p><?php echo $loginemailError; ?>
				<p><?php echo $loginpasswordError; ?>
				<p><?=$error;?></p>
				<form method="POST">

						<input class="form-control" placeholder="E-mail" name="loginEmail" type="email">

						<br><br>

						<input class="form-control" placeholder="Parool" name="loginPassword" type="password">


						<br><br>

					<p class="text-center"><button type="submit" class="btn btn-info">Logi sisse
							<span class="glyphicon glyphicon-log-in"></span>
						</button></p>

				</form>
				</div>
					<div class="col-sm-4 col-md-3 col-sm-offset-4 col-md-offset-0">

				<h1>Loo kasutaja</h1>

				<form method="POST">
						<p style="color:lightcoral;"><span style="color: lightcoral" class="glyphicon glyphicon-asterisk"></span>Kohustuslikud väljad </p>


					<p><?php echo $signupNameError; ?></p><a style="color: dodgerblue"><span style="color: lightcoral" class="glyphicon glyphicon-asterisk"></span>Eesnimi</a><br>
						<input class="form-control" placeholder="Eesnimi" name="signupName" type="text"  value = "<?=$signupName;?>">

					<p ><?php echo $signupLNameError; ?></p><a style="color: dodgerblue"><span style="color: lightcoral" class="glyphicon glyphicon-asterisk"></span>Perekonnanimi</a><br>
						<input class="form-control" placeholder="Perekonnanimi" name="signupLName" type="text" value = "<?=$signupLName;?>">

					<p><?php echo $signupEmailError; ?></p><a style="color: dodgerblue"><span style="color: lightcoral" class="glyphicon glyphicon-asterisk"></span>E-mail</a><br>
						<input class="form-control" placeholder="E-mail" name="signupEmail" type="email"  value = "<?=$signupEmail;?>">

					<p><?php echo $signupPasswordError; ?></p><a style="color: dodgerblue"><span style="color: lightcoral" class="glyphicon glyphicon-asterisk"></span>Parool</a><br>
						<input class="form-control" placeholder="Parool" name="signupPassword" type="password"><br>
						<input class="form-control" placeholder="Korda parooli" name="signupPassword2" type="password">

					<p><?php echo $signupageError; ?></p><a style="color: dodgerblue"><span style="color: lightcoral" class="glyphicon glyphicon-asterisk"></span>Vanus</a>
						<input  class="form-control" placeholder="Vanus" name="signupage" type="text"  value = "<?=$signupage;?>">

					<a style="color: dodgerblue"><span style="color: lightcoral" class="glyphicon glyphicon-asterisk"></span>Telefoni number</a>
						<input class="form-control" placeholder="telefoni number" name="phonenr" type="number">

					<p><?php echo $signupgenderError; ?></p><a style="color: dodgerblue"><span style="color: lightcoral" class="glyphicon glyphicon-asterisk"></span>Sugu</a>
					<br>
						<input type="radio" name="signupgender" value="Mees" checked> Mees
						<input type="radio" name="signupgender" value="Naine"> Naine
						<input type="radio" name="signupgender" value="Muu"> Muu
						<br><br>

							<a style="color: dodgerblue">Soovin RestoGuru soovitusi e-mailile</a>
							<br>
						Jah<input name="Olen RestoGuru" type="radio" checked>&nbsp&nbsp&nbsp&nbsp
						<input name="Olen RestoGuru" type="radio">Ei

						<br><br>

					<p class="text-center"><button type="submit" class="btn btn-success">Loo kasutaja
							<span class="glyphicon glyphicon-check"></span>
						</button></p>

						<br>


					<audio autoplay loop >
						<source src="intro.mp3" type="audio/mpeg"  >;
					</audio>



				</form>

				</div>
			</div>
		</div>
<?php require("../footer.php");?>
