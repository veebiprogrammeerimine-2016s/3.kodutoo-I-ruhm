<?php
	//edit.php
	require("../functions.php");
	
	require("../page/editFunctions.php");
	
	require("../class/Order.Class.php");
	$Order = new Order($mysqli);
	
	require("../class/Helper.Class.php");
	$Helper = new Helper($mysqli);
	
	if(isset($_GET["delete"])&&isset($_GET["id"])) {
		deleteOrder($Helper->cleanInput($_GET["id"]));
		header("Location: data.php");
		exit();
	}
	
	//kas kasutaja uuendab andmeid
	if(isset($_POST["update"])){
		
		updateOrder($Helper->cleanInput($_POST["id"]),$Helper->cleanInput ($_POST["product"]),$Helper->cleanInput($_POST["quantity"]));
		
		header("Location: edit.php?id=".$_POST["id"]."&success=true");
        exit();	
		
	}
	
	//saadan kaasa id
	$c = getSingleOrderData($_GET["id"]);
	var_dump($c);
	
?>
<?php require("../partials/header.php");?>
<br><br>
<a href="data.php"> Go back </a>

<h2>Change the order</h2>
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" >
	<input type="hidden" name="id" value="<?=$_GET["id"];?>" > 
  	<label for="product" >Product</label><br>
	<input id="product" name="product" type="text" value="<?php echo $c->product;?>" ><br><br>
  	<label for="quantity" >Quantity</label><br>
	<input id="quantity" name="quantity" type="text" value="<?=$c->quantity;?>"><br><br>
  	
	<input type="submit" name="update" value="Save">
  </form>
  
  <br>
  <br>
  
  <a href="?id=<?=$_GET["id"];?>&delete=true">Delete</a>
  <br>
  <?php require("../partials/footer.php");?>