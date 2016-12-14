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
	
	
	function deleteInstrument($id){
    	
        $database = "if16_jant";
		
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);
		
		$stmt = $mysqli->prepare("UPDATE signup SET deleted=NOW() WHERE id=? AND deleted IS NULL");
		$stmt->bind_param("i",$_SESSION["userId"]);
		
		// kas õnnestus salvestada
		if($stmt->execute()){
			// õnnestus
			echo "salvestus õnnestus!";
		}
		
		$stmt->close();
	
		
	}
	
	
	function get($q) {
 		
 		//kas otsib
 		if ($q != "") {
 			
 			echo "Otsib: ".$q;
 			
 			$stmt = $this->connection->prepare("
 				SELECT gender,instrument
 				FROM signup
 				WHERE deleted IS NULL 
 				AND (gender LIKE ? OR age LIKE ? OR instrument LIKE ?)
 			");
 			$searchWord = "%".$q."%";
 			$stmt->bind_param("sss",$searchWord, $searchWord, $searchWord);
 			
 		} else {
 			
 			$stmt = $this->connection->prepare("
 				SELECT gender, age, instrument
 				FROM signup
 				WHERE deleted IS NULL
 			");
 			
			}
 		
	
	
	
}


}
?>