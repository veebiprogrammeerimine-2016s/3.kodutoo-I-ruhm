<?php
class Favorite {
	
	private $connection;
	function __construct($mysqli){
		$this->connection = $mysqli;
	}
	
	
	function saveFavorite ($favorite) {
		
		$database = "if16_epals";
		$stmt = $this->connection->prepare("INSERT INTO favorites (favorite) VALUES (?)");
	
		echo $this->connection->error;
		
		$stmt->bind_param("s", $favorite);
		
		if($stmt->execute()) {
			echo "Salvestamine õnnestus";
		} else {
		 	echo "ERROR ".$stmt->error;
		}
		
		$stmt->close();
		
	}
	
	function saveUserFavorite ($favorite_id) {
		

		
		$database = "if16_epals";
		
		$stmt = $this->connection->prepare("SELECT id FROM user_favorites WHERE user_id=? AND favorite_id=?");
		$stmt->bind_param("ii", $_SESSION["userId"],$favorite_id);
		$stmt->execute();
		
		if ($stmt->fetch()) {
			//oli olemas
			echo "Juba olemas";
			
			//ei jätka salvestamisega
			return;
		}
		$stmt->close();
		//jätkan salvestamisega..
		
		$stmt = $this->connection->prepare("INSERT INTO user_favorites (user_id, favorite_id) VALUES (?, ?)");
	
		echo $this->connection->error;
		
		$stmt->bind_param("ii", $_SESSION["userId"],$favorite_id);
		
		if($stmt->execute()) {
			//echo "Salvestamine õnnestus";
		} else {
		 	//echo "ERROR ".$stmt->error;*/ //proovimiseks, kas töötab
		}
		$stmt->close();
		
	}
	
	function getAllFavorites() {
		
		$database = "if16_epals";
		
		$stmt = $this->connection->prepare("
			SELECT id, favorite
			FROM favorites
		");
		echo $this->connection->error;
		
		$stmt->bind_result($id, $favorite);
		$stmt->execute();
		
		//tekitan massiivi
		$result = array();
		
		// tee seda seni, kuni on rida andmeid
		// mis vastab select lausele
		while ($stmt->fetch()) {
			
			//tekitan objekti
			$i = new StdClass();
			
			$i->id = $id;
			$i->favorite = $favorite;
		
			array_push($result, $i);
		}
		
		$stmt->close();
		
		return $result;
	}
	
	function getAllUserFavorites() {
		
		$database = "if16_epals";
		
		$stmt = $this->connection->prepare("
		SELECT favorite FROM favorites JOIN user_favorites
		ON favorites.id=user_favorites.favorite_id 
		WHERE user_favorites.user_id = ?");
		
		echo $this->connection->error;
		
		$stmt->bind_param("i", $_SESSION["userId"]);
		
		$stmt->bind_result($favorite);
		$stmt->execute();
	
		$result = array();
		while ($stmt->fetch()) {
			
			$i = new StdClass();
	
			$i->favorite = $favorite;
		
			array_push($result, $i);
		}
		
		$stmt->close();
	
		return $result;
	}
}?>