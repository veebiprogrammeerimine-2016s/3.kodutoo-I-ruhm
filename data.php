<?php
	require("functions.php");
	
	require("Health.class.php");
	$Health=new Health($mysqli);
	
	require("Helper.class.php");
	$Helper=new Helper();
	
	//kas on sisse loginud, kui pole, siis suunata login lehele
		
	if (!isset($_SESSION["userId"])) {
		
		header("Location: login5_tund.php");
		exit();
	}
	
	//kas ?logout on aadressireal
	
	if (isset($_GET["logout"])) {
		
		session_destroy();
		
		header("Location: login5_tund.php");
		exit();
		
	}	
		//echo $date;
		
	//muutujad
	$Gender="";
	$Age="";
	$AgeError="";
	$date="";
	$dateError="";
	$NumberofSteps="";
	$NumberofStepsError="";
	$LandLength="";
	$LandLengthError="";
	
	//kontrollin, kas kasutaja sisestas andmed
	if(isset($_POST["Age"])) {
		if (empty($_POST["Age"])){
			$AgeError="See väli on kohustuslik!";
			
		}else {
			$Age=$_POST["Age"];
		}
		
	}

	if(isset($_POST["date"])) {
		if (empty($_POST["date"])){
			$dateError="See väli on kohustuslik!";
			
		}else {
			$date=$_POST["date"];
		}
		
	}

	if(isset($_POST["NumberofSteps"])) {
		if (empty($_POST["NumberofSteps"])){
			$NumberofStepsError="See väli on kohustuslik!";
			
		}else {
			$NumberofSteps=$_POST["NumberofSteps"];
		}
		
	}	

	if(isset($_POST["LandLength"])) {
		if (empty($_POST["LandLength"])){
			$LandLengthError="See väli on kohustuslik!";
			
		}else {
			$LandLength=$_POST["LandLength"];
		}
		
	}
	//ühtegi viga ei olnud ja saan kasutaja andmed salvestada
	if(isset($_POST["Gender"]) &&
		isset($_POST["Age"]) &&
		isset($_POST["date"]) &&
		isset($_POST["NumberofSteps"]) &&
		isset($_POST["LandLength"]) &&
		empty($_POST["AgeError"]) &&
		empty($_POST["dateError"]) &&
		empty($_POST["NumberofStepsError"]) &&
		empty($_POST["LandLengthError"])
		){
			
			//$Gender=cleanInput($_POST["Gender"]);
			//$Age=cleanInput($_POST["Age"]);
			//$date=cleanInput($_POST["date"]);
			//$NumberofSteps=cleanInput($_POST["NumberofSteps"]);
			//$LandLength=cleanInput($_POST["LandLength"]);
		
			$date =  new DateTime($_POST['date']);
			$date =  $date->format('Y-m-d');
			
			$Health->savePeople($Helper->cleanInput($_POST["Gender"]), $Helper->cleanInput($_POST["Age"]), $Helper->cleanInput($date), $Helper->cleanInput($_POST["NumberofSteps"]), $Helper->cleanInput($_POST["LandLength"]));
		
		header("Location: data.php");
		exit();		
		}

	$people=$Health->getAllPeople();
	
	//var_dump($people[1]);
	
?>

<h1>Hinda oma tervislikku seisundit</h1>
<p>Tere tulemast <?=$_SESSION["name"];?>!
	<a href="?logout=1">Logi välja</a>
</p>

<h1>Salvesta andmed</h1>
<form method="POST">
		<label><h3>Sugu</h3></label>
		
		<?php if($Gender=="male"){ ?>
				<input type="radio" name="Gender" value="male" checked> Mees<br>
			<?php } else { ?>
				<input type="radio" name="Gender" value="male" checked> Mees<br>
			<?php } ?>
			<?php if ($Gender=="female") { ?>
				<input type="radio" name="Gender" value="female" checked> Naine<br>
			 <?php } else { ?>
				<input type="radio" name="Gender" value="female" > Naine<br>
			 <?php } ?>
			 
			 <?php if($Gender == "unknown") { ?>
				<input type="radio" name="Gender" value="unknown" checked> Ei oska öelda<br>
			 <?php } else { ?>
				<input type="radio" name="Gender" value="unknown" > Ei oska öelda<br>
			 <?php } ?>
				
		<br><br>
		<label><h3>Vanus</h3></label>
		<input name="Age" type="age" value="<?=$Age;?>"> <?php echo $AgeError; ?>
		
		<br><br>
		<label><h3>Kuupäev</h3></label>
		<input name="date" type="date" value="<?=$date;?>"> <?php echo $dateError; ?>
		
		<br><br>
		<label><h3>Sammude arv</h3></label>
		<input name="NumberofSteps" type="numberofsteps" value="<?=$NumberofSteps;?>"> <?php echo $NumberofStepsError; ?>
		
		<br><br>
		<label><h3>Käidud maa pikkus km-s</h3></label>
		<input name="LandLength" type="landlength" value="<?=$LandLength;?>"> <?php echo $LandLengthError; ?>
		
		<br><br>
		<br><br>
		<input type="submit" value="Salvesta">
			
</form>

<!--<h2>Varasemad andmed</h2>
	
	foreach($people as $p){
		
		echo "<h3 style=' Color:".$p->Color."; '>".$p->Gender."</h3>";
		
	}
-->

<br><br>
<h2>Kasutajate andmed</h2>
<?php
	$html="<table>";
		$html .="<tr>";
			$html .="<th>id</th>";
			$html .="<th>Sugu</th>";
			$html .="<th>Vanus</th>";
			$html .="<th>Kuupäev</th>";
			$html .="<th>Sammude arv</th>";
			$html .="<th>Käidud maa pikkus km-s</th>";
		$html .="</tr>";
	
		foreach($people as $p){
			$html .="<tr>";
				$html .="<td>".$p->id."</td>";
				$html .="<td>".$p->Gender."</td>";
				$html .="<td>".$p->Age."</td>";
				$html .="<td>".$p->date."</td>";
				$html .="<td>".$p->NumberofSteps."</td>";
				$html .="<td>".$p->LandLength."</td>";
				//$html .="<td style=' background-color:".$p->Color."; '>".$p->Color."</td>";
				//<img width="200" src=' ".$url." '>
			
			
			$html .="</tr>";
		
		}
	$html .="</table>";
	echo $html;

?>
	