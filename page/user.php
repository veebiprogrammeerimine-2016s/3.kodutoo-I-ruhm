<?php 
	
	require("../functions.php");
	
	require("../class/Interest.class.php");
	$Interest = new Interest($mysqli);
	
	require("../class/Helper.class.php");
	$Helper = new Helper();
	
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
		  
		$Interest->save($Helper->cleanInput($_POST["interest"]));
		
	}
	
	if ( isset($_POST["userInterest"]) && 
		!empty($_POST["userInterest"])
	  ) {
		  
		$Interest->saveUser($Helper->cleanInput($_POST["userInterest"]));
		
	}
	
    $interests = $Interest->get();
    $userInterests = $Interest->getUser();
	
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
<h1><a href="data.php"> < tagasi</a><p>User account</h1>
<?=$msg;?>
<p>
	Welcome <?=$_SESSION["userEmail"];?>!
	<a href="?logout=1">Logout</a>
</p>


<h2>Insert form of art</h2>
<?php
    
    $listHtml = "<ul>";
	
	foreach($userInterests as $i){
		
		
		$listHtml .= "<li>".$i->interest."</li>";

	}
    
    $listHtml .= "</ul>";

	
	echo $listHtml;
    
?>
<form method="POST">
	
	<label>Form of art</label><br>
	<input name="interest" type="text">
	
	<input type="submit" value="Salvesta">
	
</form>



<h2>User preferences</h2>
<form method="POST">
	
	<label>Favourite form of art</label><br>
	<select name="userInterest" type="text">
        <?php
            
            $listHtml = "";
        	
        	foreach($interests as $i){
        		
        		
        		$listHtml .= "<option value='".$i->id."'>".$i->interest."</option>";
        
        	}
        	
        	echo $listHtml;
            
        ?>
    </select>
    	
	
	<input type="submit" value="Lisa">
	
</form>
