<?php 

	//Võtab ja kopeerib faili sisu
	require("../functions.php");
	require("../class/pictures.class.php");
	$pictures = new pictures($mysqli);
	
	//Kas on sisseloginud
	//Kui ei ole, siis suunata login lehele
	if (!isset ($_SESSION["userId"])){
		header("Location: login.php");
		exit();
	}
	
	//Kui vajutada logi välja nuppu, viib tagasi login lehele	
	if (isset($_GET["logout"])){
		session_destroy();
		header("Location: login.php");
		exit();
	}

	//Muutujad
	$author="";
	$date_taken="";
	$description="";
	$authorError="";
	$date_takenError="";
	$descriptionError="";

	if(isset($_POST["author"])){
		if(empty($_POST["author"])){
			$authorError="<i>See väli on kohustuslik!</i>";
		}else{
			$author=$_POST["author"];
		}
	}	

	if(isset($_POST["date_taken"])){
		if(empty($_POST["date_taken"])){
			$date_takenError="<i>See väli on kohustuslik!</i>";
		}else{
			$date_taken=$_POST["date_taken"];
		}
	}	

	if(isset($_POST["description"])){
		if(empty($_POST["description"])){
			$descriptionError="<i>See väli on kohustuslik!</i>";
		}else{
			$description=$_POST["description"];
		}
	}	

	// Kus tean, et ühtegi viga ei olnud ja saan kasutaja andmed salvestada
	if (isset($_POST["author"]) &&
		isset($_POST["date_taken"]) &&
		isset($_POST["description"]) &&
		empty($authorError) &&
		empty($date_takenError) &&
		empty($descriptionError)
	){
		$author = cleanInput($author);
		$date_taken = cleanInput($date_taken);
		$description = cleanInput($description);

		$pictures->savePicture($author, $date_taken, $description);

		//Teen lehele refreshi
		header("Location: login.php");
		exit();
	}
	
?>

<?php require("../header.php"); ?>

	<h1>Kasutaja info</h1>
	<p>

		Tere tulemast <?=$_SESSION["email"];?>!

			<br>

		<a href="?logout=1">Logi välja</a>

	</p>

	<h1>Salvesta pilt</h1>
	<form method="POST" enctype="multipart/form-data">
	
		<label>Autori nimi:</label>
			<br>
		<input name="author" type="text" value="<?=$author;?>"> <?php echo "<font color='red'>$authorError</font>"; ?>
	
			<br><br>
		
		<label>Pildi tegemise kuupäev:</label>
			<br>
		<input name="date_taken" type="date" value="<?=$date_taken;?>"> <?php echo "<font color='red'>$date_takenError</font>"; ?>
		
			<br><br>
			
		<label>Pildi kirjeldus:</label>
			<br>
		<input name="description" type="text" value="<?=$description;?>"> <?php echo "<font color='red'>$descriptionError</font>"; ?>
		
			<br><br>
			
		<input type="submit" value="Lae üles">
		
	</form>

	<h1>Salvestatud pildid</h1>

	Salvestatud piltide vaatamiseks kliki <a href="user.php">siia!</a>

<?php require("../footer.php"); ?>