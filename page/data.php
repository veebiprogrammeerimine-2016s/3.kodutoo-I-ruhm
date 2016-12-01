<?php
	
	require_once("../functions.php");
	//kui pole sisse logitud, siinatakse login lehele
	if (!isset ($_SESSION["username"]) ) {
		header("Location: login.php");
		exit();
	}
	
	//kas ?logout on addressireal
	if (isset($_GET["logout"])) {
		session_destroy();
		header("Location: login.php");
		exit();
	}
	
	if (isset($_POST["alusta"])) {
		$_SESSION["start_time"] = microtime(TRUE);
	}
	
	if (isset($_POST["end"])) {
		$_SESSION["solve_time"] =  microtime(TRUE) - $_SESSION["start_time"];
	}
	
	if (isset($_POST["select_scramble_2x2x2"])) {
		$_SESSION["selected_scramble"] = $Scramble->save(8);
		$_SESSION["cube_type"] = "2x2x2";
	}
		
	if (isset($_POST["select_scramble_3x3x3"])) {
		$_SESSION["selected_scramble"] = $Scramble->save(25);
		$_SESSION["cube_type"] = "3x3x3";
	}
	
	if (isset($_SESSION["solve_time"]) && isset($_POST["end"]) && ($_SESSION["start_time"] != 0)) {
		$_SESSION["username"] = clean_input($_SESSION["username"]);
		$Solve->to_db($_SESSION["username"], $_SESSION["cube_type"], $_SESSION["selected_scramble"], $_SESSION["solve_time"]);
		$_SESSION["start_time"] = 0;
	};
	
	if (isset($_GET["search"])) {
		$search = clean_input($_GET["search"]);
	} else {
		$search = "";
	}
	
	if(isset($_GET["searchType"])) {
		$searchType = $_GET["searchType"];
	} else {
		$searchType = "";
	}
	
	if (isset($_GET["sort"]) && isset($_GET["order"])) {
		$sort = $_GET["sort"];
		$order = $_GET["order"];
	} else {
		$sort = "id";
		$order = "DESC";
	}

	$results = $Solve->from_db($_SESSION["username"], $sort, $order, $search, $searchType);

?>

<?php require("../partials/header.php"); ?>

	<h1> Tere Tulemast! <?=$_SESSION["username"];?> </h1>

	<form method = "POST">
		<table>
			<tr> <td colspan = "2"> <input name = "select_scramble_2x2x2" type = "submit" value = "Hangi segamise algoritm 2x2x2 kuubikule"> </td> </tr>
			<tr> <td> <input name = "select_scramble_3x3x3" type = "submit" value = "Hangi segamise algoritm 3x3x3 kuubikule"> </td> </tr>
				<?php if (isset($_POST["select_scramble_2x2x2"]) OR isset($_POST["select_scramble_3x3x3"]) OR isset($_SESSION["start_time"])): ?>
					<tr> <td style = "text-align:center"> Scramble </td> </tr>
					<tr>
						<td> <?=$_SESSION["selected_scramble"];?>
						<td>
							<?php if (!isset($_SESSION["start_time"]) OR ($_SESSION["start_time"] == 0)): ?>
								<input type="submit" name="alusta" value="Alusta lahendamist">
							<?php else: ?>
								<input type="submit" name="end" value="Lahendatud">
							<?php endif; ?>
						</td>
					</tr>
				<?php endif; ?>
		</table>
	</form>
	
	<form>
		<table>
			<tr>
				<td>
				<input type = "text" name = "search">
				<select name = "searchType">
					<option value = "cube_type"> Kuubik </option>
					<option value = "scramble"> Segamine </option>
					<option value = "time"> Aeg </option>
				</select>
				</td>
				<td> <input type = "submit" value = "Otsi"> </td>;
			</tr>
		</table>
	</form>

	<h1>Tulemused</h1>

<?php
	
	$cubeArrow = "";
	$timeArrow = "";
	
	$resutls_tbl = "<table>";
		$resutls_tbl .= "<tr>";
	
			$cubeOrder = "ASC";
			if (isset($_GET["order"]) && $_GET["order"] == "ASC" && isset($_GET["sort"]) && $_GET["sort"] == "cube_type" ) {
				$cubeOrder = "DESC";
				$cubeArrow = "&uarr;";
			} elseif (isset($_GET['sort']) && $_GET['sort'] == "cube_type") {
				$cubeArrow = "&darr;";
			}
			$resutls_tbl .= "<th style = 'text-align:center'>
				<a href = '?search=".$search."&searchType=".$searchType."&sort=cube_type&order=".$cubeOrder."'> Kuubiku tüüp".$cubeArrow." </a>
			</th>";
			
			$resutls_tbl .= "<th style = 'text-align:center'> Segamise valem </th>";
			
			$timeOrder = "ASC";
			if (isset($_GET["order"]) && $_GET["order"] == "ASC" && isset($_GET["sort"]) && $_GET["sort"] == "time" ) {
				$timeOrder = "DESC";
				$timeArrow = "&uarr;";
			} elseif (isset($_GET['sort']) && $_GET['sort'] == "time") {
				$timeArrow = "&darr;";
			}
			$resutls_tbl .= "<th style = 'text-align:center'>
				<a href = '?search=".$search."&searchType=".$searchType."&sort=time&order=".$timeOrder."'> Lahenduse aeg".$timeArrow." </a>
			</th>";
		$resutls_tbl .= "</tr>";
		
	foreach($results as $r) {
		$resutls_tbl .= "<tr>";
			$resutls_tbl .= "<td style = 'text-align:center'>".$r->cube."</td>";
			$resutls_tbl .= "<td style = 'text-align:center'>".$r->scramble."</td>";
			$resutls_tbl .= "<td style = 'text-align:center'>".$r->solve_time."</td>";
			$resutls_tbl .= "<td> <a class = 'btn btn-default btn-sm' href = 'edit.php?id=".$r->id."'> <span class = 'glyphicon glyphicon-pencil'></span> Muuda </a> </td>";
		$resutls_tbl .= "</tr>";
	};
	
	$resutls_tbl .= "</table>";
	
	echo $resutls_tbl;

?>

	<br><br><br>
	<a href ="?logout=1">Logi välja! </a>
	</body>
</html>