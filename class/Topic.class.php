<?php class Topic {
	
	private $connection;
	
	function __construct($mysqli) {
		$this->connection = $mysqli;
	}
	
	function createNew($subject, $content, $user, $email){
		
		$stmt = $this->connection->prepare("INSERT INTO topics(subject, content, user, email) VALUES(?,?,?,?)");
		echo $this->connection->error;
		
		$stmt->bind_param("ssss", $subject, $content, $user, $email); 
		
		if($stmt->execute()) {
			echo "Salvestamine õnnestus.<br>";
		} else {
			echo "ERROR".$stmt->error;
		}
	}
	
	function addToArray (){
		
		$stmt = $this->connection->prepare("
			SELECT id, subject, created, user, email
			FROM topics
		");
		echo $this->connection->error;
		
		$stmt->bind_result ($id, $subject, $date, $user, $email);
		$stmt-> execute();
		
		$result = array();

		while ($stmt->fetch()){	
			$topic = new StdClass();
			$topic->id = $id;
			$topic->subject = $subject;
			$topic->created = $date;
			$topic->user = $user;
			$topic->email = $email;
			
			array_push ($result, $topic);
			$_SESSION["subject"] = $subject;
		}
		$stmt->close();
		//$mysqli->close();
		
		return $result;
	}
	
	function get($topic_id){
		
		$stmt = $this->connection-> prepare("SELECT subject, content, created, user, email FROM topics WHERE id=? ");
		
		echo $this->connection->error;

		$stmt->bind_param("i", $topic_id);
		$stmt->bind_result($subject, $content, $created, $user, $email);
		$stmt->execute();
		
		//tekitan objekti
		$topic = new Stdclass();
		
		//saime ühe rea andmeid
		if($stmt->fetch()){
			// saan siin alles kasutada bind_result muutujaid
			$topic->subject = $subject;
			$topic->content = $content;
			$topic->created = $created;
			$topic->user = $user;
			$topic->email = $email;
			
		}else{
			// ei saanud rida andmeid kätte
			// sellist id'd ei ole olemas
			// see rida võib olla kustutatud
			header("Location: hw3_data.php");
			exit();
		}
		
		$stmt->close();
		//$mysqli->close();
		
		return $topic;
	}	
}
?>