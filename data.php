<?php 
	
	
	require("functions.php");
	
	require("Pillid.class.php");
	$Pillid = new Pillid($mysqli);
	
	$instrumentData = getAllInstruments();
	
	
	
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
 	
 	//otsisõna fn sisse
 	$PillidData = $Pillid->get($q);
	
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
		$html .= "<th>age</th>";
		$html .= "<th>gender</th>";
		$html .= "<th>instrument</th>";
	$html .= "</tr>";
	
	//iga liikme kohta massiivis
	foreach($instrumentData as $c){
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



