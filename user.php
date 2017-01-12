<?php

 	require("functions.php");
	
	require("Pillid.class.php");
	$Pillid = new Pillid($mysqli);

 	//kui ei ole kasutaja id'd
 	if (!isset($_SESSION["userId"])){
 		
 		//suunan sisselogimise lehele
 		header("Location: login.php");
 		exit();
 	}
 	
 	
 	//kui on ?logout aadressireal siis login välja
 	if (isset($_GET["logout"])) {
 		
 		session_destroy();
 		header("Location: login.php");
 		exit();
 	}
 	

	if(isset($_GET["delete"])) {
		// kustutan
		
		deleteInstrument(cleanInput($_GET["instrument"]));
		header("Location: data.php");
		exit();
	}
	
	
	//kas kasutaja uuendab andmeid
	if(isset($_POST["update"])){
		
		$Pillid->updateInstrument(($_POST["instrument"]));
		
		//header("Location: user.php");
		//header("Location: data.php?instrument".$_POST["instrument"]);
        //exit();	
		
		//saadan kaasa id
		
	
	}
		//$c = getSingleInstrumentData($_GET["id"], $_GET["instrument"]);
		//var_dump($c);
	
?>
<br><br>
<a href="data.php"> tagasi </a>

<h2>Muuda instrumenti</h2>
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" >
			<select name="instrument">
				<option value=""> Vali...</option>
				<option value="kitarr">Kitarr</option>
				<option value="basskitarr">Basskitarr</option>
				<option value="löökpillid">Löökpillid</option>
				<option value="klahvpill">Klahvpillid</option>
				<option value="mingi muu">Mingi muu</option>
			</select>
	<input type="submit" name="update" value="Salvesta">
  </form>
  
  
 <br>
 <br>
 <a href="?id=<?=$_GET["instrument"];?>&delete=true">Kustuta</a>