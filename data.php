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
		
			$date =  new DateTime($_POST['date']);
			$date =  $date->format('Y-m-d');
			
			$Health->savePeople($Helper->cleanInput($_POST["Gender"]), $Helper->cleanInput($_POST["Age"]), $Helper->cleanInput($date), $Helper->cleanInput($_POST["NumberofSteps"]), $Helper->cleanInput($_POST["LandLength"]));
		
		header("Location: data.php");
		exit();		
		}

	//$people=$Health->getAllPeople();
	
	//saan kõik kasutajate andmed
	
	//kas otsitakse
	
	if (isset($_GET["q"])){
		
		//kui otsitakse, võtame otsisõna aadressirealt
		$q=$_GET["q"];
	
	}else{
		
		//otsisõna on tühi
		$q="";
	}
	
	$sort="id";
	$order="ASC";
	
	if (isset($_GET["sort"])&& isset($_GET["order"])){
		$sort=$_GET["sort"];
		$order=$_GET["order"];
	}
	
	//otsisõna funktsiooni sisse
	$HealthData=$Health->get($q, $sort, $order);
	
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


<br><br>
<h2>Kasutajate andmed</h2>

<form>

	<input type="search" name="q" value="<?=$q;?>">
	<input type="submit" value="Otsi">
	
</form>

<?php
	$html="<table>";
		$html .="<tr>";
			$idOrder="ASC";
			if(isset($_GET["order"])&& $_GET["order"]=="ASC"){
				$idOrder="DESC";
			}
			$html .="<th>
				<a href='?q=".$q."&sort=id&order=".$idOrder."'>
				</a>
			</th>";
			
			$GenderOrder="ASC";
			if(isset($_GET["order"]) && $_GET["order"]=="ASC"){
				$GenderOrder="DESC";
			}
			$html .="<th>
				<a href='?q=".$q."&sort=Gender&order=".$GenderOrder."'> Sugu
				</a>
			</th>";
			
			$AgeOrder="ASC";
			if(isset($_GET["order"]) && $_GET["order"]=="ASC"){
				$AgeOrder="DESC";
			}
			$html .="<th>
				<a href='?=".$q."&sort=Age&order=".$AgeOrder."'>Vanus
				</a>
			</th>";
			
			$dateOrder="ASC";
			if(isset($_GET["order"]) && $_GET["order"]=="ASC"){
				$dateOrder="DESC";
			}
			$html .="<th>
				<a href='?=".$q."&sort=date&order=".$dateOrder."'>Kuupäev
				</a>
			</th>";
			
			$NumberofStepsOrder="ASC";
			if(isset($_GET["order"]) && $_GET["order"]=="ASC"){
				$NumberofStepsOrder="DESC";
			}
			$html .="<th>
				<a href='?=".$q."&sort=NumberofSteps&order=".$NumberofStepsOrder."'>Sammude arv
				</a>
			</th>";
			
			$LandLengthOrder="ASC";
			if(isset($_GET["order"]) && $_GET["order"]=="ASC"){
				$LandLengthOrder="DESC";
			}
			$html .="<th>
				<a href='?=".$q."&sort=LandLength&order=".$LandLengthOrder."'>Käidud maa pikkus km-s
				</a>
			</th>";
			
		$html .="</tr>";
	
		foreach($HealthData as $p){
			$html .="<tr>";
				$html .="<td>".$p->id."</td>";
				$html .="<td>".$p->Gender."</td>";
				$html .="<td>".$p->Age."</td>";
				$html .="<td>".$p->date."</td>";
				$html .="<td>".$p->NumberofSteps."</td>";
				$html .="<td>".$p->LandLength."</td>";
				
				$html .= "<td><a href='edit.php?id=".$p->id."'>Muuda</a></td>";
				
			
		    $html .="</tr>";
		
		}
	$html .="</table>";
	echo $html;

?>
	