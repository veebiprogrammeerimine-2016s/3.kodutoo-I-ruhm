<?php class Reply {
	
	private $connection;
	
	function __construct($mysqli) {
		$this->connection = $mysqli;
	}
	
	function createNew($content, $subject_id, $user, $email, $user_id){
		
		$stmt = $this->connection->prepare("INSERT INTO replies(content, user, email, topic_id, user_id) VALUES(?,?,?,?,?)");
		echo $this->connection->error;
		
		$stmt->bind_param("sssii", $content, $user, $email, $subject_id, $user_id); 
		
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
	
	function checkUser($topic_id, $user_id) {
		$stmt = $this->connection-> prepare("SELECT content FROM replies WHERE topic_id=? and user_id=?");
		
		echo $this->connection->error;
		
		$stmt->bind_param("ii", $topic_id, $user_id);
		$stmt->bind_result($content);
		$stmt->execute();
		
		$change_reply = "";
		
		if($stmt->fetch()){
			
			//panen hiljem reply_id juurde
			$change_reply = "<a href='hw3_edit.php?id=$topic_id&change=true' style='text-decoration:none'>Kustuta või muuda oma vastust</a>";
		
		}
		
		$stmt->close();
		return $change_reply;
	}
	
}
?>