<?php
   require("../functions.php");
   
   //kas on sisseloginud, kui ei ole siis
   //suunata login lehele
   if (!isset ($_SESSION["userId"])) {
	   
	   //header("Location: login.php");
	   
	}
   
   //kas ?loguout on aadressireal
   if (isset($_GET["logout"])) {
	   
	   session_destroy();
	   
	   header("Location: login.php");
	   exit();
	   
   }  
   
   	if(isset($_GET["q"])){
		
		// kui otsib, võtame otsisõna aadressirealt
		$q = $_GET["q"];
		
	}else{
		
		// otsisõna tühi
		$q = "";
	}
	$sort = "id";
	$order = "ASC";
	
	if(isset($_GET["sort"]) && isset($_GET["order"])) {
		$sort = $_GET["sort"];
		$order = $_GET["order"];
	}
	
	//otsisõna fn sisse
	$people = $Apartment->get($q, $sort, $order);
	

?>
<html>
<body style='background-color:Silver'>
    <head>
	<?php require("../header.php"); ?>
<h1>Korterite tabel</h1>

<form>

	<input type="search" name="q" value="<?=$q;?>">
	<input type="submit" value="Otsi">
	
</form>
<?php 
	
	$html = "<table class='table table-striped'>";
	
	$html .= "<tr>";
	
		$idOrder = "ASC";
		$arrow ="&darr;";
		if (isset($_GET["order"]) && $_GET["order"] == "ASC"){
			$idOrder = "DESC";
			$arrow ="&uarr;";
			
		}	
	
		$html .= "<th>
					<a href='?q=".$q."&sort=id&order=".$idOrder."'>
						id ".$arrow."
					</a>
				 </th>";
				 
		$cityOrder = "ASC";
		$arrow ="&darr;";
		if (isset($_GET["order"]) && $_GET["order"] == "ASC"){
			$cityOrder = "DESC";
			$arrow ="&uarr;";
		}
		
		$html .= "<th>
					<a href='?q=".$q."&sort=city&order=".$cityOrder."'>
						Linn ".$arrow."
					</a>
				 </th>";
				 
		$streetOrder = "ASC";
		$arrow ="&darr;";
		if (isset($_GET["order"]) && $_GET["order"] == "ASC"){
			$streetOrder = "DESC";
			$arrow ="&uarr;";
		}
		
		$html .= "<th>
					<a href='?q=".$q."&sort=street&order=".$streetOrder."'>
						Tänav ".$arrow."
					</a>
				 </th>";
				 
		$areaOrder = "ASC";
		$arrow ="&darr;";
		if (isset($_GET["order"]) && $_GET["order"] == "ASC"){
			$areaOrder = "DESC";
			$arrow ="&uarr;";
		}
		
		$html .= "<th>
					<a href='?q=".$q."&sort=area&order=".$areaOrder."'>
						Pindala (m2)".$arrow."
					</a>
				 </th>";
				 
		$roomsOrder = "ASC";
		$arrow ="&darr;";
		if (isset($_GET["order"]) && $_GET["order"] == "ASC"){
			$roomsOrder = "DESC";
			$arrow ="&uarr;";
		}
		
		$html .= "<th>
					<a href='?q=".$q."&sort=rooms&order=".$roomsOrder."'>
						Tubasid ".$arrow."
					</a>
				 </th>";
	$html .= "</tr>";
	

		foreach($people as $g){
			$html .= "<tr>";
				$html .= "<td>".$g->id."</td>";
				$html .= "<td>".$g->city."</td>";
				$html .= "<td>".$g->street."</td>";
				$html .= "<td>".$g->area."</td>";
				$html .= "<td>".$g->rooms."</td>";
				$html .= "<td><a class='btn btn-default btn-sm' href='edit.php?id=".$g->id."'><span class='glyphicon glyphicon-pencil'></span>Muuda</a></td>";
			$html .= "</tr>";	
		}
		
	$html .= "</table>";
	echo $html;

?>
<br><br>
<?php require("../footer.php"); ?>