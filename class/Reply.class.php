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
			//echo "Vastus on lisatud.<br><br>";
			$_SESSION["reply_message"] = "<p style='color:green;'>VASTUS LISATUD!</p>";
		} else {
			echo "ERROR".$stmt->error;
		}
		
	}
	
	function addToArray ($topic_id){
		
		$stmt = $this->connection->prepare("
			SELECT id, content, created, user, email
			FROM replies
			WHERE topic_id=?
			AND deleted IS NULL
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
	
	function checkUser($topic_id, $user_id, $reply_id) {
		$stmt = $this->connection-> prepare("SELECT content FROM replies WHERE id=? and topic_id=? and user_id=?");
		
		echo $this->connection->error;
		
		$stmt->bind_param("iii", $reply_id, $topic_id, $user_id);
		$stmt->bind_result($content);
		$stmt->execute();
		
		$change_reply = "";
		
		if($stmt->fetch()){
			
			//echo $reply_id;
			
			//panen hiljem reply_id juurde
			//$change_reply = "<a href='hw3_edit.php?id=$topic_id&change=true' style='text-decoration:none'>Kustuta või muuda oma vastust</a>";
			$change_reply = "<a class='btn btn-default btn-xs' href='hw3_edit.php?topic=$topic_id&reply=$reply_id' style='text-decoration:none'><span class='glyphicon glyphicon-pencil'></span> Muuda või kustuta oma vastus</a>";
		
		}	
		$stmt->close();
		return $change_reply;
	}
	
	function checkAccess($topic_id, $reply_id, $user_id){
		$stmt = $this->connection-> prepare("SELECT content FROM replies WHERE topic_id=? and id=? and user_id=?");
		
		echo $this->connection->error;
		
		$stmt->bind_param("iii", $topic_id, $reply_id, $user_id);
		$stmt->bind_result($content);
		$stmt->execute();
		
		$access = "no";
		
		if($stmt->fetch()){
			
			$access = "yes";
		
		} 
		
		return $access;
	}
	
	//A function can not return multiple values, but similar results can be obtained by returning an array.
	
	function find($topic_id, $reply_id, $user_id ){
		$stmt = $this->connection-> prepare("SELECT content FROM replies WHERE topic_id=? and id=? and user_id=?");
		
		echo $this->connection->error;
		
		$stmt->bind_param("iii", $topic_id, $reply_id, $user_id);
		$stmt->bind_result($content);
		$stmt->execute();

		$reply = ""; 
		
		if($stmt->fetch()){
			$reply= $content;
		}
		
		$stmt->close();
		
		return $reply;
		
	}
	
	function update($reply, $reply_id){
		$stmt = $this->connection->prepare("UPDATE replies SET content=? WHERE id=?");
		
		$stmt->bind_param("si",$reply, $reply_id);
		
		// kas õnnestus salvestada
		if($stmt->execute()){
			// õnnestus
			//echo "Muudatus salvestatud!";
			$_SESSION["reply_change_message"] = "<p style='color:green;'>VASTUS MUUDETUD!</p>";
		}
		
		$stmt->close();
	}
	
	function updateTime($reply_id){
		$stmt = $this->connection->prepare("UPDATE replies SET created = current_timestamp WHERE id=?");
		
		$stmt->bind_param("i",$reply_id);
		
		// kas õnnestus salvestada
		if($stmt->execute()){
			// õnnestus
		}
		
		$stmt->close();
	}
	
	function del($topic_id, $reply_id){
		$stmt = $this->connection->prepare("UPDATE replies SET deleted=NOW() WHERE id=? AND topic_id=? AND deleted IS NULL");
		$stmt->bind_param("ii",$reply_id, $topic_id);

 		if($stmt->execute()){
 			//echo "Kustutamine õnnestus!";
			$_SESSION["reply_del_message"] = "<p style='color:red;'>VASTUS KUSTUTATUD!</p>";
 		}
 		
 		$stmt->close();
		
	}
	
}
?>