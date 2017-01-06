<?php

	require("../Functions.php");
	
	if(!isset ($_SESSION["userId"])) {
		
		header("Location: Login.php");
	}
	if(isset($_GET["logout"])) {
		
		session_destroy();
		
		header("Location: Login.php");
		
	}
	if (isset ($_POST["firstname"]) &&
			isset ($_POST["lastname"]) &&
			isset ($_POST["shirt_number"]) &&
			!empty($_POST["firstname"]) &&
			!empty($_POST["lastname"]) &&
			!empty($_POST["shirt_number"])
		){
			
		$Player->savePlayer($_POST["firstname"], $_POST["lastname"], $_POST["shirt_number"]);
	}

	if(isset($_GET["q"])) {
		$q = $_GET ["q"];
	//otsis6na fn sisse
	}else {
	//otsis6na tyhi
		$q = "";
	}

	$sort = "id";
	$order = "ASC";

	if(isset($_GET["sort"]) && isset($_GET["order"])) {
		$sort = $_GET ["sort"];
		$order = $_GET ["order"];

}
	$playerData=$Player->getAllPlayers($q, $sort, $order);

?>
<?php require("../header.php"); ?>
<style>
	.table-center {
		max-width: 500px;
		margin: 0 auto;
	}
</style>

	<h1 style="text-align:center;" >PLY</h1>
	<p>
		Oled loginud sisse kasutajaga <?=$_SESSION["email"];?>!
		<br>
		<br>
		<a href="?logout=1">Logi välja</a>
	</p>
	<center>
		<h1>Lisa enda soovitud mängija</h1>
	</center>
		
	<form method="POST">
		<center>
			<br>
			<input type="text" name="firstname" placeholder="Eesnimi" ><br><br>
			<input type="text" name="lastname" placeholder="Perekonnanimi" ><br><br>
			<input type="text" name="shirt_number" placeholder="Särgi number" ><br><br>
			<br><br>
			<input type="submit" class="btn btn-success" value="Salvesta">
			<br><br>
		</center>
	</form>
	<form>
		<div class="container">
			<div class="row">
				<div class="col-md-4 col-md-offset-0"
				<div class="form-group">
					<input class="form-control" type="search" name="</div>q" value="<?=$q;?>">
					<input type="submit" value="Otsi">
					<br><br>
				</div>
			</div>
		</div>
	</form>

<center>
	<h2>Mängijad</h2>
</center>
<?php

	$html = "<table class='table table-striped; table-center'>";

	$html .= "<tr>";
			$idOrder = "ASC";
			$firstnameOrder = "ASC";
			$lastnameOrder = "ASC";
			$numberOrder = "ASC";
			if(isset($_GET["order"]) && $_GET["order"] == "ASC"){
				$idOrder = "DESC";
			}
			if(isset($_GET["order"]) && $_GET["order"] == "ASC"){
				$firstnameOrder = "DESC";
			}
			if(isset($_GET["order"]) && $_GET["order"] == "ASC"){
				$lastnameOrder = "DESC";
			}
			if(isset($_GET["order"]) && $_GET["order"] == "ASC"){
				$numberOrder = "DESC";
			}
			$html .= "<th>
					<a href='?q=".$q."&sort=id&order=".$idOrder."'>
					id
					</a>
				  </th>";
			$html .= "<th>
					<a href='?q".$q."&sort=firstname&order=".$firstnameOrder."'>
					Eesnimi
					</a>
				  </th>";
			$html .= "<th>
					<a href='?q".$q."&sort=lastname&order=".$lastnameOrder."'>
					Perekonnanimi
					</a>
				  </th>";
			$html .= "<th>
					<a href='?q".$q."&sort=number&order=".$numberOrder."'>
					Särgi number
					</a>
				  </th>";
		$html .= "</tr>";

	foreach ($playerData as $p) {
		$html .= "<tr>";
			$html .= "<td>".$p->id."</td>";
			$html .= "<td>".$p->firstname."</td>";
			$html .= "<td>".$p->lastname."</td>";
			$html .="<td>".$p->shirt_number."</td>";
		$html .= "<td><a href='edit.php?id=".$p->id."'>Muuda</a></td>";
		$html .= "</tr>";
	}
	$html .= "</table>";
	echo $html;

?>
<?php require("../footer.php"); ?>