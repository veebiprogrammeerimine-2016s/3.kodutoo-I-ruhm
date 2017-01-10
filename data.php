<?php 
	
	
	require("functions.php");
	
	require("Pillid.class.php");
	$Pillid = new Pillid($mysqli);
	
	
	//kui ei ole kasutaja id'd
	if (!isset($_SESSION["userId"])){
		
		//suunan sisselogimise lehele
		header("Location: login.php");
		
	}
	
	//kui on ?logout aadressireal siis login välja
	if (isset($_GET["logout"])) {
		
		session_destroy();
		header("Location: login.php");
		
	}
	
	$msg = "";
	if(isset($_SESSION["message"])) {
		
		$msg = $_SESSION["message"];
		
		//kustutan ära, et pärast ei näitaks
		unset($_SESSION["message"]);
	}
	
		
 	//kas otsib
 	if(isset($_GET["q"])){
 		
 		// kui otsib, võtame otsisõna aadressirealt
 		$q = $_GET["q"];
 		
 	}else{
 		
 		// otsisõna tühi
 		$q = "";
 	}
 	$sort = "age";
	$order = "ASC";
	
	if(isset($_GET["sort"]) && isset($_GET["order"])){
			$sort = $_GET["sort"];
			$order = $_GET["order"];
	}
	
 	

	
 	//otsisõna fn sisse
 	$instruments = $Pillid->get($q, $sort, $order);
	
?>


<h1>Leia endale parim bändikaaslane!</h1>
</p>

		Tere tulemast <a href="user.php"><?=$_SESSION["userEmail"];?>!</a>
		<a href="?logout=1">Logi välja</a>
		<br><br>
		Leht täieneb jooksvalt, vabandame ebamugavuste pärast!
		<br><br>
<form>
	
	<input type="search" name="q" value="<?=$q;?>">
	<input type="submit" value="Otsi">

</form>
<?php


$html = "<table>";
	
	$html .= "<tr>";
		
		
		
	$html .= "</tr>";
	
	$ageOrder = "ASC";
 	$arrow = "&darr;";
 	if (isset($_GET["order"]) && $_GET["order"] == "ASC"){
 			$ageOrder = "DESC";
 			$arrow = "&uarr;";
 		}
 	
 	$html .= "<th>
 					<a href='?q=".$q."&sort=age&order=".$ageOrder."'>
 						age ".$arrow."
 					</a>
 				 </th>";
	
	$genderOrder = "ASC";
 	$arrow = "&darr;";
 	if (isset($_GET["order"]) && $_GET["order"] == "ASC"){
 			$genderOrder = "DESC";
 			$arrow = "&uarr;";
 		}
 	
	

 	$html .= "<th>
 					<a href='?q=".$q."&sort=gender&order=".$genderOrder."'>
 						gender ".$arrow."
 					</a>
 				 </th>";
 				 
 				 
 	$instrumentOrder = "ASC";
 	$arrow = "&darr;";
 	if (isset($_GET["order"]) && $_GET["order"] == "ASC"){
 			$instrumentOrder = "DESC";
 			$arrow = "&uarr;";
 		}
 	$html .= "<th>
 					<a href='?q=".$q."&sort=instrument&order=".$instrumentOrder."'>
 						instrument
 					</a>
 				 </th>";
				
	
	//iga liikme kohta massiivis
	foreach($instruments as $c){
		// iga kasutaja on $c
		//echo $c->age."<br>";
		
		$html .= "<tr>";
			$html .= "<td>".$c->age."</td>";
			$html .= "<td>".$c->gender."</td>";
			$html .= "<td>".$c->instrument."</td>";
			
		$html .= "</tr>";
	}
	
	$html .= "</table>";
	
	echo $html;
	
	
	$listHtml = "<br><br>";
	

	
?>
</body>
</html>



