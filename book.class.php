<?php 
class books {
	
	private $connection;
	
	function __construct($mysqli){
		
		$this->connection = $mysqli;
		
	}
	/*TEISED FUNKTSIOONID */
	function delete($id){
		$stmt = $this->connection->prepare("UPDATE Lugemispaevik SET deleted=NOW() WHERE id=? AND deleted IS NULL");
		$stmt->bind_param("i",$id);
		
		// kas õnnestus salvestada
		if($stmt->execute()){
			// õnnestus
			echo "Kustutamine õnnestus! Uuendame lehte 1 sekundi pärast!";
		}
		
		$stmt->close();

		header("Refresh:1; url=data.php");
		
		
	}
		
	function get($q, $content, $order, $sort) {
		
		$allowedSort = ["id", "book", "autor", "rating"];
		
		if(!in_array($sort, $allowedSort)){
			// ei ole lubatud tulp
			$sort = "id";
		} else {
			// kuion lubatud tulp
			$sort = $sort;
		}
		
		$orderBy = "ASC";
		
		if ($order == "DESC") {
			$orderBy = "DESC";
		}
		//echo "Sorteerin: ".$sort." ".$orderBy." ";
		//echo $q;
		
		
		//kas otsib
		if ($q == "true") {
			
			echo "Otsib: ". $q;

			$stmt = $this->connection->prepare("
				SELECT id, book, autor, rating, notes
				FROM Lugemispaevik
				WHERE deleted IS NULL 
				AND ((book LIKE ?) OR (autor LIKE ?) OR (rating LIKE ?) )
				ORDER BY $sort $order
			");
			$content = "%".$content."%";
			$stmt->bind_param("sss", $content, $content, $content);
			
		} else {

			$stmt = $this->connection->prepare("
				SELECT id, book, autor, rating, notes
				FROM Lugemispaevik
				WHERE deleted IS NULL
				ORDER BY $sort $order
			");
			
		}
		
		echo $this->connection->error;
		
		$stmt->bind_result($id, $book, $autor, $rating, $notes);
		$stmt->execute();
		
		
		//tekitan massiivi
		$result = array();
		
		// tee seda seni, kuni on rida andmeid
		// mis vastab select lausele
		while ($stmt->fetch()) {
			
			//tekitan objekti
			$books = new StdClass();
			
			$books->id = $id;
			$books->book = $book;
			$books->autor = $autor;
			$books->rating = $rating;
			$books->notes = $notes;
			
			//echo $books."<br>";
			// iga kord massiivi lisan juurde raamatu pealkirja
			array_push($result, $books);
		}
		
		$stmt->close();

		return $result;
	}
	
	function getSingle($edit_id){
		$stmt = $this->connection->prepare("SELECT book, autor, rating, notes FROM Lugemispaevik WHERE id=? AND deleted IS NULL");
		$stmt->bind_param("i", $edit_id);
		$stmt->bind_result($book, $autor, $rating, $notes);
		$stmt->execute();
		
		//tekitan objekti
		$books = new Stdclass();
		
		//saime ühe rea andmeid
		if($stmt->fetch()){
			// saan siin alles kasutada bind_result muutujaid
			$books->book = $book;
			$books->autor = $autor;
			$books->rating = $rating;
			$books->notes = $notes;
			
			
		}else{
			// ei saanud rida andmeid kätte
			// sellist id'd ei ole olemas
			// see rida võib olla kustutatud
			header("Location: data.php");
			exit();
		}
		
		$stmt->close();
		
		
		return $books;
		
	}


	function update($id, $book, $autor, $rating, $notes){

		$stmt = $this->connection->prepare("UPDATE Lugemispaevik SET book=?, autor=?, rating=?, notes=? WHERE id=? AND deleted IS NULL");
		$stmt->bind_param("ssisi",$book, $autor, $rating, $notes, $id);

		// kas õnnestus salvestada
		if($stmt->execute()){
			// õnnestus
			echo "salvestus õnnestus!";
		}

		$stmt->close();


	}

	function insert($book, $autor, $rating, $notes){
    	
		$stmt = $this->connection->prepare("INSERT INTO Lugemispaevik (Book, Autor, Rating, Notes) VALUES (?, ?, ?, ?)");
		$stmt->bind_param("ssis",$book, $autor, $rating, $notes);
		
		// kas õnnestus salvestada
		if($stmt->execute()){
			// õnnestus
			echo "lsaimine õnnestus!";
		}
		
		$stmt->close();
		
		
	}
	/*
		function getAllPeople () {
		
		$mysqli = new mysqli($GLOBALS["serverHost"],$GLOBALS["serverUsername"],$GLOBALS["serverPassword"],$GLOBALS["database"]);

		$stmt = $mysqli->prepare("
			SELECT id, book, autor, rating, notes
			FROM Lugemispaevik
		");
		echo $mysqli->error;
		
		$stmt->bind_result($id, $book, $autor, $rating, $notes);
		$stmt->execute();
		
	
		$result = array();
		
		// seni kuni on üks rida andmeid saada (10 rida = 10 korda)
		while ($stmt->fetch()) {
			
			$person = new StdClass();
			$person->id = $id;
			$person->book = $book;
			$person->autor = $autor;
			$person->rating = $rating;
			$person->notes = $notes;
			
			//echo $color."<br>";
			array_push($result, $person);
		}
		
		$stmt->close();
		$mysqli->close();
		
		return $result;
		
	}*/
	
	function cleanInput($input) { 
		$input = trim($input);
		$input = stripslashes($input);
		$input = htmlspecialchars($input);
		return $input;
	}
}
?>