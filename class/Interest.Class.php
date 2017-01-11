<?php

class Interest {
	
	private $connection;
	
	
	function __construct ($mysqli) {
		
		$this->connection = $mysqli;
	
	}
		
	/* TEISED FUNKTSIOONID  */
	
function getAllInterest() {
		
		$database = "if16_sirjemaria";
		$this->connection = new $this->connection($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		
		$stmt = $this->connection->prepare("
			SELECT id, interest
			FROM interests
		");
		echo $this->connection->error;
		
		$stmt->bind_result($id, $interest);
		$stmt->execute();
		
		
		//tekitan massiivi
		$result = array();
		
		// tee seda seni, kuni on rida andmeid
		// mis vastab select lausele
		while ($stmt->fetch()) {
			
			//tekitan objekti
			$i = new StdClass();
			
			$i->id = $id;
			$i->interest = $interest;
		
			array_push($result, $i);
		}
		
		$stmt->close();
		$this->connection->close();
		
		return $result;
	}
	
	function getAllUserInterests() {
		
		$database = "if16_sirjemaria";
		$this->connection = new $this->connection($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		
		$stmt = $this->connection->prepare("
			SELECT interest
			FROM interests
			JOIN user_interest
			ON interests.id = user_interest.interest_id
			WHERE user_interest.user_id = ?
		");
		echo $this->connection->error;
		
		$stmt->bind_param("i", $_SESSION["userId"]);
		
		$stmt->bind_result($interest);
		$stmt->execute();
		
		
		//tekitan massiivi
		$result = array();
		
		// tee seda seni, kuni on rida andmeid
		// mis vastab select lausele
		while ($stmt->fetch()) {
			
			//tekitan objekti
			$i = new StdClass();
			
			$i->interest = $interest;
		
			array_push($result, $i);
		}
		
		$stmt->close();
		$this->connection->close();
		
		return $result;
	}

	function saveUserInterest ($interest_id) {
		
		echo "huviala: ".$interest_id."<br>";
		echo "kasutaja: ".$_SESSION["userId"]."<br>";
		
		$database = "if16_sirjemaria";
		$this->connection = new $this->connection($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		
		//kas oli juba olemas
		
		$stmt = $this->connection->prepare("
			SELECT id FROM user_interests
			WHERE user_id=? AND interest_id=?
		");
		
		$stmt->bind_param("ii", $_SESSION["userId"], $interest_id);
		$stmt->execute();
		
		if($stmt->fetch()) {
			//oli olemas
			echo "Already exists!";
			
			//ära salvestamisega jätka
			return;
		
		}
	
		$stmt->close();
		//jätkan salvestamisega...
		
		$stmt = $this->connection->prepare("
			INSERT INTO user_interests 
			(user_id, interest_id) VALUES (?, ?)
			");
			
		echo $this->connection->error;
		
		$stmt->bind_param("ii", $_SESSION["userId"], $interest_id);
		
		if($stmt->execute()) {
			echo "Saved!";
		} else {
		 	echo "ERROR ".$stmt->error;
		}
		
		$stmt->close();
		$this->connection->close();
		
	}
	
	function saveInterest ($interest) {
		
		$database = "if16_sirjemaria";
		$this->connection = new $this->connection($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $this->connection->prepare("INSERT INTO interests (interest) VALUES (?)");
	
		echo $this->connection->error;
		
		$stmt->bind_param("s", $interest);
		
		if($stmt->execute()) {
			echo "Saved!";
		} else {
		 	echo "ERROR ".$stmt->error;
		}
		
		$stmt->close();
		$this->connection->close();
		
	}

}


?>