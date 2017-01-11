<?php

	require("../functions.php");

	require("../class/Helper.Class.php");
	$Helper = new Helper($mysqli);
	
	require("../class/Order.Class.php");
	$Order = new Order($mysqli);
	
	//Kas on sisse loginud, kui ei ole siis
	//suunata login lehele
	if (!isset($_SESSION["userId"])) {
		
		header("Location: login.php");
		exit();
	}

	//kas ?logout on aadressireal
	if (isset($_GET["logout"])){
		
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
	
	if(isset($_POST["product"]) &&
 			isset($_POST["quantity"]) &&
 			!empty($_POST["product"]) &&
 			!empty($_POST["quantity"])
  		) {
			
			$Order->saveOrder($Helper->cleanInput($_POST["product"]), $Helper->cleanInput($_POST["quantity"]));
	}
	
	if(isset($_GET["q"])){
		//kui otsib, võtame otsisõna aadressirealt
		$q = $_GET["q"];
		
	}else{
		//otsisõna on tühi
		$q = "";
		
	}
	
	$sort = "id";
	$orderA = "ASC";
	
	if(isset($_GET["sort"])  && isset($_GET["orderA"])){
		$sort = $_GET["sort"];
		$orderA = $_GET["orderA"];
		
	}
	
	$orderData = $Order->AllOrders($q, $sort, $orderA);
	
	$orders = $Order->AllOrders($q, $sort, $orderA);

?>
<?php require("../partials/header.php");?>
<h1>Place an order!</h1>
<p>

	Welcome! <a href="user.php"><?=$_SESSION["userEmail"];?> -</a>
	<a href="?logout=1">Log out</a>

</p>

<form method="POST">

		<label>Product</label>
		<br>
		<input name="product" type="text">
		<br>
		<br>
		<label>Quantity</label>
		<br>
		<input name="quantity" type="text">
		<br>
		<br>
		
		<input type="submit" value="Add to cart">
</form>

<h2>Ordered products:</h2>
<?php

	$html = "<table>";
	
			$html .="<tr>";
				$html .= "<th>id</th>";
				$html .= "<th>product</th>";
				$html .= "<th>quantity</th>";
				$html .= "<th>created</th>";
				
			$html .="</tr>";
	
		foreach($orders as $p) {
			$html .="<tr>";
				$html .= "<td>".$p->id."</td>";
				$html .= "<td>".$p->product."</td>";
				$html .= "<td>".$p->quantity."</td>";
			$html .="</tr>";
		}
		
$html .= "</table>";
	
	echo $html;
	
	
	$listHtml = "<br><br>";
	
	foreach($orders as $p){
		
		
		$html .= "<h1>".$p->product."</h1>";
		$html .= "<td>".$p->quantity."</td>";
	}
	
	echo $listHtml;
?>
<h2>Orders</h2>

<form>

	<input type="search" name="q" value="<?=$q;?>">
	<input type="submit" value="Search">
	<br>
	<br>

</form>

<?php 
	
	$html = "<table class='table table-striped'>";
		
	$html .= "<tr>";
	
		$idOrder =  "ASC";
		$arrow = "&darr;";
		if(isset($_GET["orderA"]) && $_GET["orderA"] == "ASC"){
			$idOrder =  "DESC";
			$arrow = "&uarr;";
		}
		$html .= "<th>
					<a href='?q=".$q."&sort=id&orderA=".$idOrder."'>
						id ".$arrow."
					</a>
				</th>";
		$productOrder =  "ASC";
		$arrow = "&darr;";
		if(isset($_GET["orderA"]) && $_GET["orderA"] == "ASC"){
			$productOrder =  "DESC";
			$arrow = "&uarr;";
		}
		$html .= "<th>
					<a href='?q=".$q."&sort=product&orderA=".$productOrder."'>
						product ".$arrow."
					</a>
				</th>";
		$quantityOrder =  "ASC";
		$arrow = "&darr;";
		if(isset($_GET["orderA"]) && $_GET["orderA"] == "ASC"){
			$quantityOrder =  "DESC";
			$arrow = "&uarr;";
		}
		$html .= "<th>
					<a href='?q=".$q."&sort=quantity&orderA=".$quantityOrder."'>
						quantity ".$arrow."
					</a>
				</th>";
	$html .= "</tr>";
	
	//iga liikme kohta massiivis
	foreach($orderData as $c){
		
		$html .= "<tr>";
			$html .= "<td>".$c->id."</td>";
			$html .= "<td>".$c->product."</td>";
			$html .= "<td>".$c->quantity."</td>";

            $html .= "<td><a class='btn btn-default btn-sm' href='edit.php?id=".$c->id."'><span class='glyphicon glyphicon-pencil'></span> Muuda</a></td>";
		$html .= "</tr>";
	}
	
	$html .= "</table>";
	
	echo $html;
	
	$listHtml = "<br><br>";
	
	echo $listHtml;

?>

<?php require("../partials/footer.php");?>
