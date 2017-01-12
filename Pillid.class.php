<?php 
class Pillid {
	
	private $connection;
	
	function __construct($mysqli){
		
		$this->connection = $mysqli;
		
	}

	
	/*TEISED FUNKTSIOONID */
	
	function getAllInstruments() {
		
		$database = "if16_jant";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);
		
		$stmt = $mysqli->prepare("
			SELECT id, age, gender, instrument
			FROM signup
		");
		echo $mysqli->error;
		
		$stmt->bind_result($id, $age, $gender, $instrument);
		$stmt->execute();
		
		
		//tekitan massiivi
		$result = array();
		
		// tee seda seni, kuni on rida andmeid
		// mis vastab select lausele
		while ($stmt->fetch()) {
			
			//tekitan objekti
			$kasutaja = new StdClass();
			
			$kasutaja->age = $age;
			$kasutaja->gender = $gender;
			$kasutaja->instrument = $instrument;
			
			//echo $plate."<br>";
			// iga kord massiivi lisan juurde nr märgi
			array_push($result, $kasutaja);
		}
		
		$stmt->close();
		
		
		return $result;
	}
	
	
	function getSingleInstrumentData($edit_id){
    
        $database = "if16_jant";
		//echo "id on ".$edit_id;
		
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);
		
		$stmt = $mysqli->prepare("SELECT instrument FROM signup WHERE id=? AND deleted IS NULL");
		$stmt->bind_param("i", $edit_id);
		$stmt->bind_result($instrument);
		$stmt->execute();
		
		//tekitan objekti
		$pill = new Stdclass();
		
		//saime ühe rea andmeid
		if($stmt->fetch()){
			// saan siin alles kasutada bind_result muutujaid
			$pill->instrument = $instrument;
			
			
			
		}else{
			// ei saanud rida andmeid kätte
			// sellist id'd ei ole olemas
			// see rida võib olla kustutatud
			header("Location: data.php");
			exit();
		}
		
		$stmt->close();
		
		
		return $pill;
		
	}
	function updateInstrument($instrument){
		
    	$database = "if16_jant";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);
		
		$stmt = $mysqli->prepare("UPDATE signup SET instrument =? WHERE id=? AND deleted IS NULL");
		echo $mysqli->error;
		$stmt->bind_param("si",$instrument,$_SESSION["userId"]);
		
		// kas õnnestus salvestada
		if($stmt->execute()){
			// õnnestus
			echo "salvestus õnnestus!";
		}
		
		$stmt->close();
		
		
	}
	
	
	function deleteInstrument($instrument){
		
		$database = "if16_jant";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);
    			
		$stmt = $mysqli->prepare("UPDATE signup SET deleted=NOW() WHERE id=? AND deleted IS NULL");
		$stmt->bind_param("si",$instrument,$_SESSION["userId"]);
		
		// kas õnnestus salvestada
		if($stmt->execute()){
			// õnnestus
			echo "salvestus õnnestus!";
		}
		$stmt->close();
	
		
	}
	
	
	function get($q, $sort, $order) {
		
		$allowedSort = ["age", "gender", "instrument"];
 		
 		if(!in_array($sort, $allowedSort)){
 			// ei ole lubatud tulp
 			$sort = "age";
 		}
 		
 		$orderBy = "ASC";
 		
 		if ($order == "DESC") {
				$orderBy = "DESC";
 		}
 		//echo "Sorteerin: ".$sort." ".$orderBy." ";
 		
 		//kas otsib
 		if ($q != "") {
 			
 			echo "Otsib: ".$q;
 			
 			$stmt = $this->connection->prepare("
 				SELECT age, gender, instrument
 				FROM signup
 				WHERE deleted IS NULL 
 				AND (gender LIKE ? OR instrument LIKE ?)
				ORDER BY $sort $orderBy
 			");
 			$searchWord = "%".$q."%";
 			$stmt->bind_param("ss",$searchWord, $searchWord);
 			
 		} else {
 			
 			$stmt = $this->connection->prepare("
 				SELECT age, gender, instrument
 				FROM signup
 				WHERE deleted IS NULL
				ORDER BY $sort $orderBy
 			");
 			
			}
 		echo $this->connection->error;
		
		$stmt->bind_result($age, $gender, $instrument);
		$stmt->execute();
		
		
		$instruments = array();
		
		while ($stmt->fetch()) {
			
			//tekitan objekti
			$result = new StdClass();
			
			$result->age = $age;
			$result->gender = $gender;
			$result->instrument = $instrument;
			
	
			
			//echo $plate."<br>";
			// iga kord massiivi lisan juurde nr märgi
			array_push($instruments, $result);
		}
		
		$stmt->close();
		
		
		return $instruments;
	}
		
		
		
		

	
	
	



}
?>