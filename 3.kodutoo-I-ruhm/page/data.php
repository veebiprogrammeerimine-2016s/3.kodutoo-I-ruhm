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
		
		// kui otsib, võtame otsisõna aadressirealt
		$q = $_GET["q"];
		
	}else{
		
		// otsisõna tühi
		$q = "";
	}
	$sort = "id";
	$order = "ASC";
	
	if(isset($_GET["sort"]) && isset($_GET["order"])) {
		$sort = $_GET["sort"];
		$order = $_GET["order"];
	}
	
	//otsisõna fn sisse
	$people = $Goal->get($q, $sort, $order);
	
   if ( isset($_POST["goal_name"]) &&
	     isset($_POST["goal_explanation"]) &&
		 isset($_POST["due_date"]) &&
		 //isset($_POST["created"]) &&
		 !empty($_POST["goal_name"]) &&
		 !empty($_POST["goal_explanation"]) &&
		 !empty($_POST["due_date"])  
		 //!empty($_POST["created"])
		) {
		  
		$goal_name = $Helper->cleanInput($_POST["goal_name"]);
        $goal_explanation = $Helper->cleanInput($_POST["goal_explanation"]);
        $due_date = $Helper->cleanInput($_POST["due_date"]);
	
		
		$Goal->save($_POST["goal_name"], $_POST["goal_explanation"], $_POST["due_date"]);
	}
	
	 if (empty ($_POST["goal_name"]) or
	     empty($_POST["goal_explanation"]) or
		 empty($_POST["due_date"])) {

			 $informationError = "Täita tuleb kõik väljad!";
			 
	}	

?>
<html>
<body style='background-color:Bisque'>
    <head>
	<?php require("../header.php"); ?>
	
<h1>Goalhelper</h1>

<p>
	Tere tulemast <a href="user.php"><?=$_SESSION["userEmail"];?>!</a>
	<a href="?logout=1">Logi välja</a>
</p>

<h2>Salvesta eesmärk</h2>
<form method="POST">
	<div class="col-xs-4">
	<label>Eesmärgi nimi</label>
	<div class="form-group">
		<input class="form-control" name="goal_name" type="text"
	</div>
	<br>
	
	<label>Eesmärk</label>
	<div class="form-group">
		<input class="form-control" name="goal_explanation" type="text" >
	</div>
	<br>
	
	<label>Tähtaeg</label>
	<div class="form-group">
		<input class="form-control" name="due_date" type="text">
	</div>
	<br>
	
	<input class="btn btn-success btn-sm hidden-xs" type="submit" value="Salvesta"> <?php echo $informationError;?>
	</div>
	
</form>
	
<h3>Eesmärkide tabel</h3>

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
				 
		$goalnameOrder = "ASC";
		$arrow ="&darr;";
		if (isset($_GET["order"]) && $_GET["order"] == "ASC"){
			$goalnameOrder = "DESC";
			$arrow ="&uarr;";
		}
		
		$html .= "<th>
					<a href='?q=".$q."&sort=goalname&order=".$goalnameOrder."'>
						goal ".$arrow."
					</a>
				 </th>";
				 
		$goalexplanationOrder = "ASC";
		$arrow ="&darr;";
		if (isset($_GET["order"]) && $_GET["order"] == "ASC"){
			$goalexplanationOrder = "DESC";
			$arrow ="&uarr;";
		}
		
		$html .= "<th>
					<a href='?q=".$q."&sort=goalexplanation&order=".$goalexplanationOrder."'>
						explanation ".$arrow."
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
						duedate ".$arrow."
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
						created ".$arrow."
					</a>
				 </th>";
	$html .= "</tr>";
	

		foreach($people as $g){
			$html .= "<tr>";
				$html .= "<td>".$g->id."</td>";
				$html .= "<td>".$g->goal_name."</td>";
				$html .= "<td>".$g->goal_explanation."</td>";
				$html .= "<td>".$g->due_date."</td>";
				$html .= "<td>".$g->created."</td>";
				$html .= "<td><a class='btn btn-default btn-sm' href='edit.php?id=".$g->id."'><span class='glyphicon glyphicon-pencil'></span>Muuda</a></td>";
			$html .= "</tr>";	
		}
		
	$html .= "</table>";
	echo $html;
	
	

?>
<br><br>
<?php require("../footer.php"); ?>