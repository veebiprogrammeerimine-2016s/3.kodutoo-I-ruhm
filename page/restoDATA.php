<?php
	require("../restoFUNCTIONS.php");

	if(!isset ($_SESSION["userId"])) {
		
		header("Location: restoSISSELOGIMINE.php");
		exit();
	}
	if(isset($_GET["logout"])) {
		
		session_destroy();
		
		header("Location: restoSISSELOGIMINE.php");
		exit();
	}


	
	$restoName = "";
	$grade = "";
	$comment= "";
	$customer_sex = "";
	$person = "";
    $restoNameError = "";
    $commentError = "";
	//kontrollin et valjad poleks tyhjad
	if( isset($_POST["restoName"]) &&
		isset($_POST["comment"]) &&
		!empty($_POST["restoName"]) &&
		!empty($_POST["comment"])
	)	{
		//login sisse
		$Resto->saverestos($_POST["restoName"],$_POST["grade"],$_POST["comment"],$_SESSION["gender"]);
		header("Location: restoDATA.php");
		exit();
	}

		if(isset($_GET["q"])){
			//kui otsib siis votame otsisona aadressirealt
			$q = $_GET["q"];
		}else {
			//otsisona tyhi
			$q="";
		}

		$sort="id";
		$order="ASC";
		if(isset($_GET["sort"]) && isset($_GET["order"])){
			$sort = $_GET["sort"];
			$order = $_GET["order"];
		}

		$person = $Resto->getallrestos($q, $sort, $order);

    if (isset ($_POST ["restoName"])) {
        // oli olemas, ehk keegi vajutas nuppu
        if (empty($_POST ["restoName"])) {
            //oli tõesti tühi
            $restoNameError = "Sisesta restorani nimi!";
        } else {
            $restoName = $_POST ["restoName"];
        }
    }
    if (isset ($_POST ["comment"])) {
        // oli olemas, ehk keegi vajutas nuppu
        if (empty($_POST ["comment"])) {
            //oli tõesti tühi
            $commentError = "Sisesta kommentaar!";
        } else {
            $comment = $_POST ["comment"];
        }
    }



//echo"<pre>";
		//var_dump($person);
		//echo"</pre>";


?>
	<?php require("../header.php");?>
		
			
			<style>
                .errors {
                    max-width: 150px;
                    color:red;
                }
				table, th, td{
					border: 2px solid dodgerblue;
					border-collapse: collapse;
					margin: 0 auto;
				}
				th, td{
					padding: 10px;
				}
				.center{
					margin: 0 auto;
					max-width: 300px;
				}
				.feedback{
					float:left;
				}
			</style>
			<br>

		<span style="float: right"><a class='btn-danger btn-sm' href="?logout=1" style="color: white"><span class="glyphicon glyphicon-log-out"></span>  Logi välja</a></span><br><br>
		<span style="float: right"><a class='btn-warning btn-sm' href="restoUSER.php" style="color: white"><span class="glyphicon glyphicon-user"></span> <?=$_SESSION["name"];?></span></a>

	
	<h1 style="color: dodgerblue;font-size: 70px" class="text-center">RestoGuru</h1>

		<p style="color: dodgerblue;font-size: 25px" class="text-center"> Tere <?=$_SESSION["name"];?>!</p>

	<br><br>
		<fieldset style="border-bottom-width: 5px;border-top-width: 5px;border-right-width: 0;border-left-width: 0px" class="center">
		<form  method="POST">
            <p class="errors"><?php echo $restoNameError; ?></p>
			<span style="color: lightcoral" class="glyphicon glyphicon-asterisk"></span><a style="color: dodgerblue"> Nimi</a>
			<input class="form-control" placeholder="Restorani nimi" name="restoName" type="text">
			
			<br><span style="color: lightcoral" class="glyphicon glyphicon-asterisk" "></span>
			<a style="color: dodgerblue"> Hinnang:</a>
					<input type="radio" name="grade" value="1">1</input>
					<input type="radio" name="grade" value="2">2</input>
					<input type="radio" name="grade" value="3">3</input>
					<input type="radio" name="grade" value="4">4</input>
					<input type="radio" name="grade" value="5" checked>5</input>
			
			<br>
            <p class="errors"><?php echo $commentError; ?></p>
			<div class="form-group">
				<span style="color: lightcoral" class="glyphicon glyphicon-asterisk"></span>
				<a for="comment" style="color: dodgerblue">Kommentaar:</a>
				<textarea class="form-control" rows="5" id="comment" name="comment" placeholder="Kommentaar"></textarea>
			</div>
			
			<br>
			
			<input class='btn-success btn-lg' style="width: 300px;height: 50px" type="submit">
		
		</form>

		</fieldset><br>

<h1 style="color: dodgerblue;margin: 0 auto;max-width: 370px;font-size: 38px">Kasutajate tagasiside</h1><br>
	<fieldset style="border-width: 0px;margin: 0 auto;max-width: 370px">
	<form>
		<input class="form-control" style="color: dodgerblue" name="q"  placeholder="Otsi restoranide, hinnete või kommentaari järgi" value="<?=$q;?>"><br>
		<p class="text-center"><button type="submit" class="btn btn-info" style="width: 370px">
			<span class="glyphicon glyphicon-search"></span> Search
		</button></p>
	</form>
	</fieldset>
	<br><br>
	<fieldset style="border-bottom-width: 15px;border-top-width: 15px;border-right-width: 0;border-left-width: 0px">
<?php
	foreach($person as $P){
			if($P->grade=="1"){
				echo '<h3 class="feedback" style="color:red;font-size: 22px">'.$P->restoName.'</h3>';
			}
			if($P->grade=="2"){
				echo '<h3 class="feedback" style="color:crimson;font-size: 27px">'.$P->restoName.'</h3>';
			}
			if($P->grade=="3"){
				echo '<h3 class="feedback" style="color:blueviolet;font-size: 32px">'.$P->restoName.'</h3>';
			}
			if($P->grade=="4"){
				echo '<h3 class="feedback" style="color:slateblue;font-size: 37px">'.$P->restoName.'</h3>';
			}
			if($P->grade=="5"){
				echo '<h3 class="feedback" style="color:dodgerblue;font-size: 42px">'.$P->restoName.'</h3>';
		}
		
	}
?></fieldset><br><br><br><br><br><br>
<fieldset style="max-width: 450px;margin: 0 auto">
<h1 class="text-center" style="color: dodgerblue">Kasutajate tagasiside tabel</h1>
</fieldset>
<?php

	$html = "<table style='width: auto'>";
		$html .= "<tr>";

			$idOrder= "ASC";
			if(isset($_GET["order"]) && $_GET["order"] == "ASC"){
					$idOrder = "DESC";
			}
			$restoNameOrder= "ASC";
			if(isset($_GET["order"]) && $_GET["order"] == "ASC"){
				$restoNameOrder = "DESC";
			}
			$gradeOrder= "ASC";
			if(isset($_GET["order"]) && $_GET["order"] == "ASC"){
				$gradeOrder = "DESC";
			}
			$commentOrder= "ASC";
			if(isset($_GET["order"]) && $_GET["order"] == "ASC"){
				$commentOrder = "DESC";
			}
			$genderOrder= "ASC";
			if(isset($_GET["order"]) && $_GET["order"] == "ASC"){
				$genderOrder = "DESC";
			}
			$createdOrder= "ASC";
			if(isset($_GET["order"]) && $_GET["order"] == "ASC"){
				$createdOrder = "DESC";
			}

			$html .= "<th style=\"background-color: lightskyblue\">
						<a href='?q=".$q."&sort=id&order=".$idOrder."'>id</a></th>";
			$html .= "<th style=\"background-color: lightblue\">
						<a href='?q=".$q."&sort=restoName&order=".$restoNameOrder."'>restorani nimi</th>";
			$html .= "<th style=\"background-color: lightskyblue\">
						<a href='?q=".$q."&sort=grade&order=".$gradeOrder."'>hinne</th>";
			$html .= "<th style=\"background-color: lightblue\">
						<a href='?q=".$q."&sort=comment&order=".$commentOrder."'>kommentaar</th>";
			$html .= "<th style=\"background-color: lightskyblue\">
						<a href='?q=".$q."&sort=gender&order=".$genderOrder."'>kliendi sugu</th>";
			$html .= "<th style=\"background-color: lightblue\">
						<a href='?q=".$q."&sort=created&order=".$createdOrder."'>loodud</th>";
			$html .= "<th style='background-color: lightskyblue'></th>";
		$html .= "</tr>";

	foreach($person as $P){
		$html .= "<tr>";
			$html .= '<td style="background-color: lightblue">'.$P->id."</td>";
			$html .= '<td style="background-color: lightskyblue">'.$P->restoName."</td>";
			$html .= '<td style="background-color: lightblue">'.$P->grade."</td>";
			$html .= '<td style="background-color: lightskyblue">'.$P->comment."</td>";
			$html .= '<td style="background-color: lightblue">'.$P->gender."</td>";
			$html .= '<td style="background-color: lightskyblue">'.$P->created."</td>";
        $html .= "<td style='background-color: lightblue;padding: 0px'><a class='btn btn-outline-danger btn-md' href='restoEDIT.php?id=".$P->id."'><span style='color:red;' class='glyphicon glyphicon-edit'></span></a></td>";
		$html .= "</tr>";
		
	}
	$html .= "<?Table>";
	echo $html;
	
?>
<audio fadein autoplay loop >
    <source src="firstrain.mp3" type="audio/mpeg"  >;
</audio>
<?php require("../footer.php");?>