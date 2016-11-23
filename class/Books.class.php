<?php 
class Books {
	
	private $connection;
	
	function __construct($mysqli){
		
		$this->connection = $mysqli;
		
	}
	
	function save ($author, $title) {
	
		$stmt = $this->connection->prepare("INSERT INTO Books (author, title) VALUES (?, ?)");
		echo $this->connection->error;
		
		$stmt->bind_param("ss", $author, $title);
		
		if ($stmt->execute()) {
			echo "Salvestamine õnnestus";
		} else {
			echo "ERROR".$stmt->error;
		}
	}
	
	function AllBooks() {
	
		$stmt = $this->connection->prepare("SELECT id, author, title, created FROM Books");
		
		echo $this->connection->error;
		$stmt->bind_result($id, $author, $title, $created);
		$stmt->execute();
		
		//array("Karoliina", "Kullamaa")
		$result = array();
		
		//seni kuni on üks rida andmeid saada (10 rida = 10 korda)
		while ($stmt->fetch()) {
			
			$person = new StdClass();
			$person->id = $id;
			$person->author = $author;
			$person->title = $title;
			$person->created = $created;
		
			array_push($result, $person);
		}
		
		$stmt->close();
		
		return $result;
	}
	
	function delete($id){
		$stmt = $this->connection->prepare("UPDATE Books SET deleted=NOW() WHERE id=? AND deleted IS NULL");
		$stmt->bind_param("i",$id);
		
		// kas õnnestus salvestada
		if($stmt->execute()){
			// õnnestus
			echo "kustutamine õnnestus!";
		}
		
		$stmt->close();	
	}
	
	function update($id, $author, $title){
    	
		$stmt = $this->connection->prepare("UPDATE Books SET author=?, title=? WHERE id=? AND deleted IS NULL");
		$stmt->bind_param("ssi",$author, $title, $id);
		
		// kas õnnestus salvestada
		if($stmt->execute()){
			// õnnestus
			echo "salvestus õnnestus!";
		}
		
		$stmt->close();
	}
}?>