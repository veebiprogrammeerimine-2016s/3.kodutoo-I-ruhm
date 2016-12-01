<?php
class Solve {

	private $connection;

	function __construct($link) {
		$this->connection = $link;
	}

	function to_db($username, $cube_type, $scramble, $time) {
		$stmt = $this->connection->prepare ("INSERT INTO ".$username." (cube_type, scramble, time) VALUES (?,?,?)");
		$stmt -> bind_param ('sss', $cube_type, $scramble, $time);
		$stmt -> execute();
		$stmt -> close();
	}

	function from_db ($username, $sortBy, $order, $searchValue, $searchType) {
		$allowedSort = array("id", "cube_type", "time");
		$allowedSearch = array("cube_type", "scramble", "time");
		$results = array();

		if (!in_array($sortBy, $allowedSort)) {
			$sortBy = "id";
		}
		
		if ($order == "DESC") {
			$order = "DESC";
		} else {
			$order = "ASC";
		}
		
		if (!in_array($searchType, $allowedSearch)) {
			$searchType = "cube_type";
		}
		if ($searchValue == "" && $sortBy != "id") {
			$stmt = $this->connection->prepare("
				SELECT id, cube_type, scramble, time FROM $username
				WHERE deleted IS NULL ORDER BY $sortBy $order
			");
		} elseif ($searchValue == "") {
			$stmt = $this->connection->prepare("
				SELECT id, cube_type, scramble, time FROM $username 
				WHERE deleted IS NULL ORDER BY id DESC
			");
		} else {
			$stmt = $this->connection->prepare("
				SELECT id, cube_type, scramble, time FROM $username
				WHERE $searchType LIKE ? AND deleted IS NULL
				ORDER BY $sortBy $order
			");
			$searchValue = "%".$searchValue."%";
			$stmt->bind_param("s", $searchValue);
		}
		echo $this->connection->error;
		$stmt -> bind_result($id, $cube, $scramble, $time);
		$stmt -> execute();

		while ($stmt -> fetch()) {
			$result = new StdClass();
			$result->id = $id;
			$result->cube = $cube;
			$result->scramble = $scramble;
			$result->solve_time = $time;
			array_push($results, $result);
		}
		return $results;
		$stmt -> close();
	}
	
	function getSingle($id) {

		$stmt = $this->connection->prepare("
			SELECT cube_type, scramble, time FROM ".$_SESSION['username']." WHERE id = ? AND deleted IS NULL
		");
		echo $stmt->error;
		$stmt->bind_param("i", $id);
		$stmt->bind_result($cube_type, $scramble, $solve_time);
		$stmt->execute();
		$solve = new StdClass();
		if ($stmt->fetch()) {
			$solve->cube_type = $cube_type;
			$solve->scramble = $scramble;
			$solve->solve_time = $solve_time;
		} else {
			header("Location: data.php");
			exit();
		}
		$stmt->close();
		return $solve;
	}
	
	function delSolve($id) {

		$stmt = $this->connection->prepare("
			UPDATE ".$_SESSION["username"]." 
			SET deleted = CURDATE() WHERE id = ? AND deleted IS NULL
		");
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$stmt->close();	
	}
	
	function update($id, $cube_type, $scramble, $solve_time) {
		
		$stmt = $this->connection->prepare("
			UPDATE ".$_SESSION["username"]." 
			SET cube_type = ?, scramble = ?, time = ? WHERE id = ? AND deleted IS NULL
		");
		$stmt->bind_param("ssdi", $cube_type, $scramble, $solve_time, $id);
		if ($stmt->execute()) {
			echo "Salvestamine õnnestus!";
		}
	}
	
}
?>