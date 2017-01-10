<?php

	require("../functions.php");
	
	require("../class/Blog.class.php");
	$Blog = new Blog($mysqli);
	
	require("../class/Helper.class.php");
	$Helper = new Helper($mysqli);
	
	$date = "";
	$dateError = "";
	$mood = "";
	$moodError = "";
	$feeling = "";
	$feelingError = "";
	$activities = "";
	$activitiesError = "";	
	$thoughts = "";
	$thoughtsError = "";
	$error = "";
	
	//kas on sisse loginud, kui ei ole, siis suunata login lehele
	if(!isset($_SESSION["userId"])) {
		header("Location: login.php");
		exit();
	}
	
	//kui tahan salvestamise andmeid kuvada
	//var_dump($_POST);
	
	//kui on logout aadressireal, siis login välja
	if(isset($_GET["logout"]))  {
		session_destroy();
		header("Location: login.php");
		exit();
	}
	
	$msg = "";
	if(isset($_SESSION["message"])){
		$msg = $_SESSION["message"];
		
		//kui ühe näitame siis kustuta ära, et pärast refreshi ei näitaks
		unset($_SESSION["message"]);
	}
	
	//kas kasutaja täitis kõik väljad?
	if (isset($_POST["date"]) ) {
		if (empty($_POST["date"]) ) { 
			$dateError = "See väli jäi täitmata";
		} else {
			$date = $_POST["date"];
		}
	}
	
	if (isset($_POST["mood"]) ) {
		if (empty($_POST["mood"]) ) { 
			$moodError = "See väli jäi täitmata";
		} else {
			$mood = $_POST["mood"];
		}
	}
			
	if (isset($_POST["feeling"]) ) {
		if (empty($_POST["feeling"]) ) { 
			$feelingError = "See väli jäi täitmata";
		} else {
			$feeling = $_POST["feeling"];
		}
	}
	
	if (isset($_POST["activities"]) ) {
		if (empty($_POST["activities"]) ) { 
			$activitiesError = "See väli jäi täitmata";
		} else {
			$activities = $_POST["activities"];
		}
	}
	
	if (isset($_POST["thoughts"]) ) {
		if (empty($_POST["thoughts"]) ) { 
			$thoughtsError = "See väli jäi täitmata";
		} else {
			$thoughts = $_POST["thoughts"];
		}
	}
	
	//ei ole tühjad väljad mida salvestada
	if( isset($_POST["date"]) &&
		isset($_POST["mood"]) &&
		isset($_POST["feeling"]) &&
		isset($_POST["activities"]) &&
		isset($_POST["thoughts"]) &&
		empty($dateError) &&
		empty($moodError) &&
		empty($feelingError) &&
		empty($activitiesError) &&
		empty($thoughtsError)
		){

							
			$Blog->save($Helper->cleanInput($_POST["date"]), $Helper->cleanInput($_POST["mood"]), $Helper->cleanInput($_POST["feeling"]), $Helper->cleanInput($_POST["activities"]), $Helper->cleanInput($_POST["thoughts"]), $_SESSION["userEmail"], $_SESSION["userId"]);
			header("Location:data.php");
			exit();
				
			$date =  new DateTime($_POST['date']);
			$date =  $date->format('Y-m-d');
	}
	
	//kas keegi otsib
	if(isset($_GET["q"])){
		//Kui otsib, võtame otsisõna aadressirealt
		$q = $_GET["q"];
		//otsisõna funktsiooni sisse
	} else {
		//otsisõna tühi
		$q = "";
	}
	
	$sort = "id";
	$order = "ASC";
	
	if(isset($_GET["sort"]) && isset($_GET["order"])) {
		$sort = $_GET["sort"];
		$order = $_GET["order"];
	}
	
	$person = $Blog->get($q, $sort, $order);
	
	$sort_name = "";
	if(!isset($_GET["sort"])){
		$sort_name = "kuupäeva";
	} else {
		if($_GET["sort"] == "user"){
			$sort_name = "kasutaja";
		}
		if($_GET["sort"] == "date"){
			$sort_name = "kuupäeva";
		}
		if($_GET["sort"] == "mood"){
			$sort_name = "tuju";
		}
		if($_GET["sort"] == "feeling"){
			$sort_name = "enesetunde";
		}
		if($_GET["sort"] == "activities"){
			$sort_name = "tegevuste";
		}
		if($_GET["sort"] == "thoughts"){
			$sort_name = "mõtete";
		}
	}
?>

<?php require("../header.php"); ?>

	<div class="container">
	
		<div class="row">
		
			<div class="col-sm-9">
			<h1>Sinu igapäevane blogi</h1>
			<p>
			Tere tulemast <a href="user.php"><?=$_SESSION["userEmail"];?></a>, nüüd saad hakata oma andmeid sisestama!
			<a href="?logout=1">Logi välja</a>
			</p><br>
		
		<form method="POST">
			<p style="color:red;"><?=$error;?></p>
			<label>Tänane kuupäev</label><br>
			<input name="date" type="date" value="<?=$date;?>"><?php echo $dateError;?>
			<br><br>
		
			<label>Tuju</label><br>
			<input name="mood" type="mood" value="<?=$mood;?>"><?php echo $moodError;?>
			<br><br>
			
			<label>Enesetunne</label><br>
			<input name="feeling" type="feeling" value="<?=$feeling;?>"><?php echo $feelingError;?>	
			<br><br>
			
			<label>Päevategevused</label><br>
			<input name="activities" type="activities" value="<?=$activities;?>"><?php echo $activitiesError;?>
			<br><br>
			
			<label>Mõtted</label><br>
			<input name="thoughts" type="thoughts" value="<?=$thoughts;?>"><?php echo $thoughtsError;?>
			<br><br>
			
			<input class="btn btn-success btn-sm hidden-xs" type="submit" value="Salvesta">
			
		</form>

		<br>
		<b>Otsi sissekandeid</b>
		<form>
			<input type="search" name="q" value="<?=$q;?>"> 
			<input type="submit" value="Otsi">
		</form>
			
		</div>
		<br><br>

<?php	

			$html = "<table class='table table-striped table-hover'>";
				$html .= "<tr class='success'>";
					$idOrder = "ASC";
					$userOrder = "ASC";
					$dateOrder = "ASC";
					$moodOrder = "ASC";
					$feelingOrder = "ASC";
					$activitiesOrder = "ASC";
					$thoughtsOrder = "ASC";
					$idArrow = "&larr;";
					$userArrow = "&larr;";
					$dateArrow = "&olarr;";
					$moodArrow = "&larr;";
					$feelingArrow = "&larr;";
					$activitiesArrow = "&larr;";
					$thoughtsArrow = "&larr;";
					
					if (isset($_GET["sort"]) && $_GET["sort"] == "id") {
						if (isset($_GET["order"]) && $_GET["order"] == "ASC") {
							$idOrder="DESC"; 
							$idArrow = "&rarr;";
						}
					}
					
					if (isset($_GET["sort"]) && $_GET["sort"] == "user") {
						if (isset($_GET["order"]) && $_GET["order"] == "ASC") {
							$userOrder="DESC";
							$userArrow = "&rarr;";
						}
					}
					
					if (isset($_GET["sort"]) && $_GET["sort"] == "date") {
						if (isset($_GET["order"]) && $_GET["order"] == "ASC") {
							$dateOrder="DESC"; 
							$dateArrow = "&orarr;";
						}
					}
					
					if (isset($_GET["sort"]) && $_GET["sort"] == "mood") {
						if (isset($_GET["order"]) && $_GET["order"] == "ASC") {
							$moodOrder="DESC";
							$moodArrow = "&rarr;";					
						}
					}
					
					if (isset($_GET["sort"]) && $_GET["sort"] == "feeling") {
						if (isset($_GET["order"]) && $_GET["order"] == "ASC") {
							$feelingOrder="DESC";
							$feelingArrow = "&rarr;";					
						}
					}
					
					if (isset($_GET["sort"]) && $_GET["sort"] == "activities") {
						if (isset($_GET["order"]) && $_GET["order"] == "ASC") {
							$activitiesOrder="DESC";
							$activitiesArrow = "&rarr;";					
						}
					}
					
					if (isset($_GET["sort"]) && $_GET["sort"] == "thoughts") {
						if (isset($_GET["order"]) && $_GET["order"] == "ASC") {
							$thoughtsOrder="DESC";
							$thoughtsArrow = "&rarr;";					
						}
					}
				
				$html .= "<th>
					<a href='?q=".$q."&sort=id&order=".$idOrder."' style='text-decoration:none'>
					<font size='4'>Id</font><br><font size='2'>&#128336;</font>".$idArrow."</th>";
					$html .= "<th>
					<a href='?q=".$q."&sort=user&order=".$userOrder."' style='text-decoration:none'>
					<font size='4'>Kasutaja</font><br><font size='2'>&#128336;</font>".$userArrow."</th>";
					$html .= "<th>
					<a href='?q=".$q."&sort=date&order=".$dateOrder."' style='text-decoration:none'>
					<font size='4'>Kuupäev</font><br><font size='2'>&#128336;</font>".$dateArrow."</th>";
					$html .= "<th>
					<a href='?q=".$q."&sort=mood&order=".$moodOrder."' style='text-decoration:none'>
					<font size='4'>Tuju</font><br><font size='2'>A</font>".$moodArrow."</th>";
					$html .= "<th>
					<a href='?q=".$q."&sort=feeling&order=".$feelingOrder."' style='text-decoration:none'>
					<font size='4'>Enesetunne</font><br><font size='2'>A</font>".$feelingArrow."</th>";
					$html .= "<th>
					<a href='?q=".$q."&sort=activities&order=".$activitiesOrder."' style='text-decoration:none'>
					<font size='4'>Päevategevused</font><br><font size='2'>A</font>".$activitiesArrow."</th>";
					$html .= "<th>
					<a href='?q=".$q."&sort=thoughts&order=".$thoughtsOrder."' style='text-decoration:none'>
					<font size='4'>Mõtted</font><br><font size='2'>A</font>".$thoughtsArrow."</th>";
				$html .= "</tr>";


			foreach($person as $c){
				$html .= "<tr>";
					$html .= "<td font size='20'><a href='user.php?id=".$c->id."' style='text-decoration:none'><font size='4'>".$c->date."</font></a></td>";
					$html .= "<td>".$c->user."</td>";
					$html .= "<td>".$c->mood."</td>";
					$html .= "<td>".$c->feeling."</td>";
					$html .= "<td>".$c->activities."</td>";
					$html .= "<td>".$c->thoughts."</td>";
					$html .= "<td>".$c->created."</td>";
				$html .= "</tr>";
			} 
			
			$html .= "</table>";
			echo $html;
?>
	
<?php require("../footer.php");?>