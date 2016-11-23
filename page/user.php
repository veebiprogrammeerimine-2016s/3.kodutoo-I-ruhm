<?php

	require("../functions.php");
	
	require("../class/Favorites.class.php");
	$Favorite = new Favorite($mysqli);
	
	require("../class/Helper.class.php");
	$Helper = new Helper();
	
	//kas on sisse loginud, kui ei ole, siis suunata login lehele
	
	if (!isset ($_SESSION["userId"])) {
		
		header("Location: login.php");
		exit();
	}
	
	//kas ?logout on aadressireal
	if (isset($_GET["logout"])) {
		
		session_destroy ();
		header ("Location: login.php");
		exit();
	}

	if ( isset($_POST["favorite"]) && 
		!empty($_POST["favorite"])
	  ) {
		  
		$Favorite->saveFavorite($Helper->cleanInput($_POST["favorite"]));
		
	}
	
	if ( isset($_POST["userFavorite"]) && 
		!empty($_POST["userFavorite"])
	  ) {
		  
		$Favorite->saveUserFavorite($Helper->cleanInput($_POST["userFavorite"]));
		
	}
	
    $favorites = $Favorite->getAllFavorites();
	$userFavorites = $Favorite->getAllUserFavorites();
	
	//var_dump($userFavorites);
?>
<a href="data.php"> < tagasi</a>
<h1>Minu konto</h1> 

<p>
	Tere tulemast <?=$_SESSION["email"];?>!
	<a href="?logout=1">Logi välja</a>
</p>


<h2>Minu lemmikraamatud</h2>
<?php
    
    $listHtml = "<ul>";
	
	foreach($userFavorites as $i){
		
		
		$listHtml .= "<li>".$i->favorite."</li>";
	}
    
    $listHtml .= "</ul>";
	echo $listHtml;
    
?>
<form method="POST">
	
	<label>Raamatu nimi</label><br>
	
	<input name="favorite" type="text">
	<input type="submit" value="Salvesta">
	
</form>



<h2>Kasutajate lemmikraamatud</h2>
<form method="POST">
	
	<label>Raamatu nimi</label><br>
	<select name="userFavorite" type="text">
        <?php
            
            $listHtml = "";
        	
        	foreach($favorites as $i){
        		
        		$listHtml .= "<option value='".$i->id."'>".$i->favorite."</option>";
        	}
        	echo $listHtml;   
        ?>
    </select>
    	
	<input type="submit" value="Lisa">
	
</form>
<?php require("../footer.php"); ?>
