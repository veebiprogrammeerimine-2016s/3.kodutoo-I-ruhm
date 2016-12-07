<?php
	require("../functions.php");
	
	require("../class/Favorites.class.php");
	$Favorite = new Favorite($mysqli);
	
	require("../class/Helper.class.php");
	$Helper = new Helper();
	
	
	if (!isset ($_SESSION["userId"])) {
		
		header("Location: login.php");
		exit();
	}
	

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
	

?>
<?php require("../header.php"); ?>

<div class "data" style="padding-left:10px;">
<h1><p style="color:green"><b>Minu konto</b></p></h1>
	<p>
		<a href="data.php">Tagasi avalehele</a><br>
		<a href="?logout=1">Logi välja</a>
	</p>
</div>

<h2><p style="color:green">Tehtud harjutused</p></h2>

<form method="POST">
					
	<label>Harjutus</label><br>
					
	<input name="favorite" type="text">
	<input type="submit" value="Salvesta">
	
</form>

<?php
    
    $listHtml = "<ul>";
	
	foreach($userFavorites as $i){
		
		
		$listHtml .= "<li>".$i->favorite."</li>";
	}
    
    $listHtml .= "</ul>";
	echo $listHtml;
    
?>

<h2><p style="color:green">Lemmik harjutused</p></h2>
<form method="POST">
			
<label>Harjutus</label><br>
<select name="userFavorite" type="text">

<?php
					
	$listHtml = "";
					
	foreach($favorites as $i){		
		$listHtml .= "<option value='".$i->id."'>".$i->favorite."</option>";}
	echo $listHtml;   
?>
</select>
				
<input type="submit" value="Lisa">
			
</form>
</div>
<?php require("../footer.php"); ?>