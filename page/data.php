<?php 
	
	require("../functions.php");
	
	require("../class/Car.class.php");
	$Car = new Car($mysqli);
	
	require("../class/Helper.class.php");
	$Helper = new Helper();
	
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
	
	$msg = "";
	if(isset($_SESSION["message"])){
		$msg = $_SESSION["message"];
		
		//kui ühe näitame siis kustuta ära, et pärast refreshi ei näitaks
		unset($_SESSION["message"]);
	}
	
	
	if ( isset($_POST["model"]) && 
		isset($_POST["plate"]) && 
		isset($_POST["color"]) && 
		isset($_POST["information"]) && 
		!empty($_POST["model"]) && 
		!empty($_POST["plate"]) && 
		!empty($_POST["color"]) && 
		!empty($_POST["information"])
	  ) {
		 
		$Car->save($Helper->cleanInput($_POST["model"]), $Helper->cleanInput($_POST["plate"]), $Helper->cleanInput($_POST["color"]), $Helper->cleanInput($_POST["information"])); 
		
	}
	

	
	//saan kõik auto andmed
	
	//kas otsib
	if(isset($_GET["q"])){
		
		//kui keegi otsib, võtame otsisõna aadressilt
		$q = $_GET["q"];
		
	}else{
		//otsisõna tühi
		$q = "";
	}	
	
	$sort = "id";
	$order = "ASC";
	
	if(isset($_GET["sort"]) && isset($_GET["order"])) {
		$sort = $_GET["sort"];
		$order = $_GET["order"];
	}
	
	//otsisõna fn sisse
	$carData = $Car->get($q, $sort, $order);
	
	
	//echo "<pre>";
	//var_dump($carData);
	//echo "</pre>";
?>
<?php require("../header.php"); ?>
<h1>Norm Masin!</h1>
<?=$msg;?>
<p>
	Tere tulemast <?=$_SESSION["userEmail"];?>!
	<br>
	<a href="?logout=1">Logi välja</a>
</p>


<h2>Salvesta auto</h2>
<form method="POST">
	
	<label>Auto mudel</label><br>
	<input name="model" type="text">
	<br><br>	
	
	<label>Auto nr</label><br>
	<input name="plate" type="text">
	<br><br>

	<label>Informatsioon</label><br>
	<input name="information" type="text"> 
	<br><br>	
		
	<label>Auto värv</label><br>
	<input type="color" name="color">
	<br><br>
	
		
	<input type="submit" value="Salvesta">
	
	
</form>

<h2>Autod</h2>

<form>
	<input type="search" name="q" value="<?=$q;?>"> 
	<input type= "submit" value="Otsi">

<form>

<?php 
	
	$html = "<table class='table table-striped'>";
	
	$html .= "<tr>";
	
		$idOrder = "ASC";
		$arrow = "&darr;";
		if (isset($_GET["order"]) && $_GET["order"] == "ASC"){
			$idOrder = "DESC";
			$arrow = "&uarr;";
		}
	
		$html .= "<th>
					<a href='?q=".$q."&sort=id&order=".$idOrder."'>
						id ".$arrow."
					</a>
				 </th>";

		$modelOrder = "ASC";
		$arrow = "&darr;";
		if (isset($_GET["order"]) && $_GET["order"] == "ASC"){
			$modelOrder = "DESC";
			$arrow = "&uarr;";
		}
	
		$html .= "<th>
					<a href='?q=".$q."&sort=model&order=".$modelOrder."'>
						model ".$arrow."
					</a>
				 </th>";
				 
		$plateOrder = "ASC";
		$arrow = "&darr;";
		if (isset($_GET["order"]) && $_GET["order"] == "ASC"){
			$plateOrder = "DESC";
			$arrow = "&uarr;";
		}

		$html .= "<th>
					<a href='?q=".$q."&sort=plate&order=".$plateOrder."'>
						plate ".$arrow."
					</a>
				 </th>";
				 
		$informationOrder = "ASC";
		$arrow = "&darr;";
		if (isset($_GET["order"]) && $_GET["order"] == "ASC"){
			$informationOrder = "DESC";
			$arrow = "&uarr;";
		}
	
		$html .= "<th>
					<a href='?q=".$q."&sort=information&order=".$informationOrder."'>
						information ".$arrow."
					</a>
				 </th>";				 				 
				 
		$colorOrder = "ASC";
		$arrow = "&darr;";
		if (isset($_GET["order"]) && $_GET["order"] == "ASC"){
			$colorOrder = "DESC";
			$arrow = "&uarr;";
		}		 
				
		$html .= "<th>
					<a href='?q=".$q."&sort=color&order=".$colorOrder."'>
						color ".$arrow."
					</a>
				 </th>";
				 
				 
	$html .= "</tr>";
	
	//iga liikme kohta massiivis
	foreach($carData as $c){
		// iga auto on $c
		//echo $c->plate."<br>";
		
		$html .= "<tr>";
			$html .= "<th>".$c->id."</th>";
			$html .= "<th>".$c->model."</th>";
			$html .= "<th>".$c->plate."</th>";
			$html .= "<th>".$c->information."</th>";
			$html .= "<td style='background-color:".$c->carColor."'>".$c->carColor."</td>";
			$html .= "<td><a class='btn btn-default btn-sm' href='edit.php?id=".$c->id."'><span class='glyphicon glyphicon-pencil'></span> Muuda</a></td>";
			
		$html .= "</tr>";
	}
	
	$html .= "</table>";
	
	echo $html;
	
	
	
	

?>

<br>
<br>
<br>
<br>
<br>

<?php require("../footer.php"); ?>
