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
   $city="";
   $street="";
   $area="";
   $rooms="";
   $cityError="";
   $streetError="";
   $areaError="";
   $roomsError="";
   $informationError="";
   
   if ( isset($_POST["city"]) &&
	     isset($_POST["street"]) &&
		 isset($_POST["area"]) &&
		 isset($_POST["rooms"]) &&
		 !empty($_POST["city"]) &&
		 !empty($_POST["street"]) &&
		 !empty($_POST["area"]) &&
		 !empty($_POST["rooms"]) ) {
		  
		$city = cleanInput($_POST["city"]);
        $street = cleanInput($_POST["street"]);
        $area = cleanInput($_POST["area"]);
        $rooms = cleanInput($_POST["rooms"]);	
		
		$Apartment->save($_POST["city"], $_POST["street"], $_POST["area"], $_POST["rooms"]);
	}
	
		
	 if (isset($_POST["city"])){
		
		if (empty($_POST["city"])){
			
			$cityError="Väli on kohustuslik!";
			 
		}
	 }
	 if (isset($_POST["street"])){
		
		if (empty($_POST["street"])){
			
			$streetError="Väli on kohustuslik!";
			 
		}
	 }
	 if (isset($_POST["area"])){
		
		if (empty($_POST["area"])){
			
			$areaError="Väli on kohustuslik!";
			 
		}
	 }
	 if (isset($_POST["rooms"])){
		
		if (empty($_POST["rooms"])){
			
			$roomsError="Väli on kohustuslik!";
			 
		}
	 }


?>

<html>
<body style='background-color:Silver'>
    <head>
	<?php require("../header.php"); ?>
        <h1>Korterikuulutuse registreerimine</h1>
	</head>	
        <body>
            <h2>Andmed</h2>
            <form method="POST">
	            <label>Linn</label><br>
	            <input name="city" type="text" value="<?=$city;?>"> <?php echo $cityError; ?>
	            <br><br>
	
	            <label>Tanav</label><br>
	            <input name="street" type="text" value="<?=$street;?>"> <?php echo $streetError; ?>
	            <br><br>
	
	            <label>Pindala</label><br>
	            <input name="area" type="int" value="<?=$rooms;?>"><?php echo $areaError; ?>
	            <br><br>
	
	            <label>Tubade arv</label><br>
	            <input name="rooms" type="int" value="<?=$area;?>"><?php echo $roomsError; ?>
	            <br><br>
	
	            <input type="submit" value="Salvesta">
				<br><br>
                </form>
		</body>		
</html>		

<br><br>
<?php require("../footer.php"); ?>