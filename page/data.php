
<?php
   require("../functions.php");
   
   $informationError = "";
   
   //kas on sisseloginud, kui ei ole siis
   //suunata login lehele
   if (!isset ($_SESSION["userId"])) {
	   
	   header("Location: login.php");
	   exit();
	   
	}
   
   //kas ?loguout on aadressireal
   if (isset($_GET["logout"])) {
	   
	   session_destroy();
	   
	   header("Location: login.php");
	   exit();
	   
   }  
   
   	if(isset($_GET["q"])){
		
		// kui otsib, v�tame otsis�na aadressirealt
		$q = $_GET["q"];
		
	}else{
		
		// otsis�na t�hi
		$q = "";
	}
	$sort = "id";
	$order = "ASC";
	
	if(isset($_GET["sort"]) && isset($_GET["order"])) {
		$sort = $_GET["sort"];
		$order = $_GET["order"];
	}
	
	//otsis�na fn sisse
	$people = $Homework->get($q, $sort, $order);
	
   if ( isset($_POST["homework_name"]) &&
	     isset($_POST["homework_explanation"]) &&
		 isset($_POST["due_date"]) &&
		 //isset($_POST["created"]) &&
		 !empty($_POST["homework_name"]) &&
		 !empty($_POST["homework_explanation"]) &&
		 !empty($_POST["due_date"])  
		 //!empty($_POST["created"])
		) {
		  
		$homework_name = $Helper->cleanInput($_POST["homework_name"]);
        $homework_explanation = $Helper->cleanInput($_POST["homework_explanation"]);
        $due_date = $Helper->cleanInput($_POST["due_date"]);
	
		
		$Homework->save($_POST["homework_name"], $_POST["homework_explanation"], $_POST["due_date"]);
	}
	
	 if (empty ($_POST["homework_name"]) or
	     empty($_POST["homework_explanation"]) or
		 empty($_POST["due_date"])) {

			 $informationError = "Salvestamiseks taide koik lahtrid!";
			 
	}	

?>
<html>

<body>
<center>
    <head>
	<?php require("../header.php"); ?>
	<center>
<h1>MobileSpa</h1>

<p>
	Tere tulemast
</p>
<br><br>
<h3>Otsing</h3>
<form>

	<div class="form-group">
		<input class="form-control" type="search" name="q" value="<?=$q;?>">
	</div>

	<input class="btn btn-success btn-sm hidden-xs" type="submit" value="Otsi">
	
</form>
<?php 
	
	$html = "<table class='table table-striped'>";
	
	$html .= "<tr>";
	
		$idOrder = "ASC";
		$arrow ="&darr;";
		if (isset($_GET["order"]) && $_GET["order"] == "ASC"){
			$idOrder = "DESC";
			$arrow ="&uarr;";
			
		}	
	
		$html .= "<th>
					<a href='?q=".$q."&sort=id&order=".$idOrder."'>
						id ".$arrow."
					</a>
				 </th>";
				 
		$homeworknameOrder = "ASC";
		$arrow ="&darr;";
		if (isset($_GET["order"]) && $_GET["order"] == "ASC"){
			$homeworknameOrder = "DESC";
			$arrow ="&uarr;";
		}
		
		$html .= "<th>
					<a href='?q=".$q."&sort=homeworkname&order=".$homeworknameOrder."'>
						nimi ".$arrow."
					</a>
				 </th>";
				 
		$homeworkexplanationOrder = "ASC";
		$arrow ="&darr;";
		if (isset($_GET["order"]) && $_GET["order"] == "ASC"){
			$homeworkexplanationOrder = "DESC";
			$arrow ="&uarr;";
		}
		
		$html .= "<th>
					<a href='?q=".$q."&sort=homeworkexplanation&order=".$homeworkexplanationOrder."'>
						Pesu teenus ".$arrow."
					</a>
				 </th>";
				 
		$duedateOrder = "ASC";
		$arrow ="&darr;";
		if (isset($_GET["order"]) && $_GET["order"] == "ASC"){
			$duedateOrder = "DESC";
			$arrow ="&uarr;";
		}
		
		$html .= "<th>
					<a href='?q=".$q."&sort=duedate&order=".$duedateOrder."'>
						Broneeringu kuupäev ".$arrow."
					</a>
				 </th>";
				 
		$createdOrder = "ASC";
		$arrow ="&darr;";
		if (isset($_GET["order"]) && $_GET["order"] == "ASC"){
			$createdOrder = "DESC";
			$arrow ="&uarr;";
		}
		
		$html .= "<th>
					<a href='?q=".$q."&sort=created&order=".$createdOrder."'>
						loodud ".$arrow."
					</a>
				 </th>";
	$html .= "</tr>";
	

		foreach($people as $g){
			$html .= "<tr>";
				$html .= "<td>".$g->id."</td>";
				$html .= "<td>".$g->homework_name."</td>";
				$html .= "<td>".$g->homework_explanation."</td>";
				$html .= "<td>".$g->due_date."</td>";
				$html .= "<td>".$g->created."</td>";
				$html .= "<td><a class='btn btn-default btn-sm' href='edit.php?id=".$g->id."'></span>Muuda</a></td>";
			$html .= "</tr>";	
		}
		
	$html .= "</table>";
	echo $html;
	
	

?>
<br><br>

<h2>SISESTA OMA BRONEERING</h2></center>
<form method="POST">
	<center>
	<div class="col-xs-4 ">
	<label>Sinu nimi</label>
	<div class="form-group">
		<input class="form-control" name="homework_name" type="text"
	</div>
	<br>
	
	<label>Pesu teenus</label>
	<div class="form-group">
		<input class="form-control" name="homework_explanation" type="text" >
	</div>
	<br>
	
	<label>Broneeringu kuupäev</label>
	<div class="form-group">
		<input class="form-control" name="due_date" type="text">
	</div>
	<br>
	
	<input class="btn btn-success btn-sm hidden-xs" type="submit" value="Salvesta"> <?php echo $informationError;?>
	</div>
	
</form>
	



<?php require("../footer.php"); ?>