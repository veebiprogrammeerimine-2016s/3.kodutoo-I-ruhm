<?php 
	
	require("../functions.php");
	
	
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
	
	
	if ( isset($_POST["interest"]) && 
		!empty($_POST["interest"])
	  ) {
		  
		$Interest->save2($Helper->cleanInput($_POST["interest"]));
		
	}
	
	if ( isset($_POST["userInterest"]) && 
		!empty($_POST["userInterest"])
	  ) {
		  
		$Interest->saveUser($Helper->cleanInput($_POST["userInterest"]));
		
	}
	
	
	
    $interest = $Interest->get();
    $userInterest = $Interest->getUser();
	
?>
<html>
<body style='background-color:Bisque'>
    <head>
	<?php require("../header.php"); ?>

<h1><a href="data.php"> < tagasi</a> Kasutaja leht</h1>
<?=$msg;?>
<p>
	Tere tulemast <?=$_SESSION["userEmail"];?>!
	<a href="?logout=1">Logi välja</a>
</p>


<h2>Salvesta hobi</h2>
<?php
    
    $listHtml = "<ul>";
	
	foreach($userInterest as $i){
		
		
		$listHtml .= "<li>".$i->interest."</li>";

	}
    
    $listHtml .= "</ul>";

	
	echo $listHtml;
    
?>
<form method="POST">
	<div class="col-xs-4">
		
		<label>Hobi/huviala nimi</label><br>
		<div class="form-group">
			<input class="form-control" name="interest" type="text">
		</div>
	
		<input class="btn btn-success btn-sm hidden-xs" type="submit" value="Salvesta"> 
	</div>
	
</form>

<br><br>
<br><br>
<br><br>

<h2>Kasutaja hobid</h2>
<form method="POST">
	
	<label>Hobi/huviala nimi</label><br>
	<select name="userInterest" type="text">
        <?php
            
            $listHtml = "";
        	
        	foreach($interest as $i){
        		
        		
        		$listHtml .= "<option value='".$i->id."'>".$i->interest."</option>";
        
        	}
        	
        	echo $listHtml;
            
        ?>
    </select>
    	
	
	<input class="btn btn-success btn-sm hidden-xs" type="submit" value="Lisa"> 
	
</form>
<?php require("../footer.php"); ?>