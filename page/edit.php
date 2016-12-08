<?php
	//edit.php
	require("../functions.php");
	
	require("../class/Finish.class.php");
	$Finish = new Finish($mysqli);
	
	require("../class/Helper.class.php");
	$Helper = new Helper();
	
	//var_dump($_POST);
	
	//kas kasutaja uuendab andmeid
	if(isset($_POST["update"])){
		
		$Finish->update($Helper->cleanInput($_POST["id"]), $Helper->cleanInput($_POST["birthday"]), $Helper->cleanInput($_POST["country"]));
		
		header("Location: edit.php?id=".$_POST["id"]."&success=true");
        exit();	
		
	}
	
	//kustutan
	if(isset($_GET["delete"])){
		
		$Finish->delete($_GET["id"]);
		
		header("Location: data.php");
		exit();
	}
	
	
	
	// kui ei ole id'd aadressireal siis suunan
	if(!isset($_GET["id"])){
		header("Location: data.php");
		exit();
	}
	
	//saadan kaasa id
	$f = $Finish->getSingle($_GET["id"]);
	//var_dump($c);
	
	if(isset($_GET["success"])){
		echo "Success!";
	}

	
?>
<head>
<style>


body:before {
    content: " ";
    width: 100%;
    height: 100%;
    position: absolute;
    z-index: -1;
    top: 0;
    left: 0;
    background: -webkit-radial-gradient(top center, ellipse cover, rgba(100,255,255,0.2) 0%,rgba(100,0,0,0.5) 100%);
}



</style>
</head>
<a href="data.php"> Back </a>

<h2>Edit</h2>
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" >
	<input type="hidden" name="id" value="<?=$_GET["id"];?>" > 
  	<label for="number_birthday" >Birthday</label><br>
	<input id="number_birthday" name="birthday" type="text" value="<?php echo $f->birthday;?>" ><br><br>
  	<label for="country" >Country</label><br>
	<input id="country" name="country" type="text" value="<?=$f->country;?>"><br><br>
  	
	<input type="submit" name="update" value="Save">
  </form>
  
  
 <br>
 <br>
 <br>
 <a href="?id=<?=$_GET["id"];?>&delete=true">Delete</a>
