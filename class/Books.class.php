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
	
	function get($q, $sort, $order) {
		
		$allowedSort = ["id", "author", "title", "created"];
		
		if(!in_array($sort, $allowedSort)) {
			//ei ole lubatud tulp
			$sort = "id";
		}
		
		$orderBy = "ASC";
		
		if ($order == "DESC") {
			$orderBy = "DESC";
		}
		//echo "Sorteerin: ".$sort." ".$orderBy." ";
		
		//kas otsib
		if ($q != "") {
			
			//echo "Otsib: ".$q;
			
			$stmt = $this->connection->prepare("
			SELECT id, author, title, created
			FROM Books
			WHERE deleted IS NULL
			AND (author LIKE ? OR title LIKE ?)
			ORDER BY $sort $orderBy
			
		");	
		
		$searchWord = "%".$q."%";
		$stmt->bind_param("ss", $searchWord, $searchWord);
		
		} else {
		
		$stmt = $this->connection->prepare("
			SELECT id, author, title, created
			FROM Books
			WHERE deleted IS NULL
			ORDER BY $sort $orderBy");
		}
		
		echo $this->connection->error;
		
		$stmt->bind_result($id, $author, $title, $created);
		$stmt->execute();
		
		
		//tekitan massiivi
		$result = array();
		
		// tee seda seni, kuni on rida andmeid
		// mis vastab select lausele
		while ($stmt->fetch()) {
			
			//tekitan objekti
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
	

	function AllBooks($edit_id){
		$stmt = $this->connection->prepare("SELECT author, title FROM Books WHERE id=? AND deleted IS NULL");
		$stmt->bind_param("i", $edit_id);
		$stmt->bind_result($author, $title);
		$stmt->execute();
		
		//tekitan objekti
		$person = new Stdclass();
		
		//saime ühe rea andmeid
		if($stmt->fetch()){
			// saan siin alles kasutada bind_result muutujaid
			$person->author = $author;
			$person->title = $title;
			
			
		}else{
			// ei saanud rida andmeid kätte
			// sellist id'd ei ole olemas
			// see rida võib olla kustutatud
			header("Location: data.php");
			exit();
		}
		
		$stmt->close();
		return $person;
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