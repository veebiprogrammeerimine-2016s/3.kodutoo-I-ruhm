<?php class Reply {
	
	private $connection;
	
	function __construct($mysqli) {
		$this->connection = $mysqli;
	}
	
	function createNew($content, $subject_id, $user, $email){
		
		$stmt = $this->connection->prepare("INSERT INTO replies(content, user, email, topic_id) VALUES(?,?,?,?)");
		echo $this->connection->error;
		
		$stmt->bind_param("sssi", $content, $user, $email, $subject_id); 
		
		if($stmt->execute()) {
			echo "Vastus on lisatud.<br><br>";
		} else {
			echo "ERROR".$stmt->error;
		}
		
	}
	
	function addToArray ($topic_id){
		
		$stmt = $this->connection->prepare("
			SELECT id, content, created, user, email
			FROM replies
			WHERE topic_id=?
		");
		echo $this->connection->error;
		
		$stmt->bind_param("i", $topic_id);
		
		$stmt->bind_result($id, $content, $created, $user, $email);
		$stmt-> execute();
		
		$result = array();
		
		while ($stmt->fetch()){	
			$reply = new StdClass();
			$reply->id = $id;
			$reply->content = $content;
			$reply->created = $created;
			$reply->user = $user;
			$reply->email = $email;
		
			array_push ($result, $reply);
		}
		$stmt->close();
		//$mysqli->close();
		
		return $result;
	}
	
}
?>