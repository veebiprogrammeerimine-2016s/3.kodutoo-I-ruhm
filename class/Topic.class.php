<?php class Topic {
	
	private $connection;
	
	function __construct($mysqli) {
		$this->connection = $mysqli;
	}
	
	function createNew($subject, $content, $user, $email, $user_id){
		
		$stmt = $this->connection->prepare("INSERT INTO topics(subject, content, user, email, user_id) VALUES(?,?,?,?,?)");
		echo $this->connection->error;
		
		$stmt->bind_param("ssssi", $subject, $content, $user, $email, $user_id); 
		
		if($stmt->execute()) {
			//echo "Salvestamine õnnestus.<br>";
			$_SESSION["topic_message"] = "<p style='color:green;'>TEEMA LISATUD!</p'>";
		} else {
			echo "ERROR".$stmt->error;
		}
		
	}
	
	function addToArray ($q, $sort, $order){
		$allowedSort = ["subject", "user", "email", "created"];
		
		if(!in_array($sort, $allowedSort)) { //esimene asi, mis ta tahab, on nõel ja teine heinakuhi
			// ei ole lubatud tulp, siis sorteerime teema järgi
			$sort = "subject";
		}
		
		$orderBy = "ASC";
		
		//see if tagab, et orderby saab aint 2 väärtust olla
		if($order == "DESC") {
			$orderBy = "DESC";
		}
		
		//echo "Sorteerin ".$sort." ".$orderBy." ";
		
		//kas otsib
		if($q != "") {
			//echo "Otsib: ".$q;
			$stmt = $this->connection->prepare("
				SELECT id, subject, created, user, email
				FROM topics
				WHERE deleted IS NULL 
				AND (subject LIKE ? OR user LIKE ? OR email LIKE ? OR created LIKE ?)
				ORDER BY $sort $order
			"); 
			$searchWord = "%".$q."%";
			//echo $q;
			//echo $searchWord;
			$stmt->bind_param("ssss", $searchWord, $searchWord, $searchWord, $searchWord);
		} else {
			$stmt =  $this->connection->prepare("
				SELECT id, subject, created, user, email
				FROM topics
				WHERE deleted IS NULL
				ORDER BY $sort $order				
			");
		}
		
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
		
		$stmt = $this->connection-> prepare("SELECT subject, content, created, user, email FROM topics WHERE id=? AND deleted IS NULL");
		
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

	function checkUser($topic_id, $user_id){
		$stmt = $this->connection-> prepare("SELECT subject, content FROM topics WHERE id=? and user_id=?");
		
		echo $this->connection->error;
		
		$stmt->bind_param("ii", $topic_id, $user_id);
		$stmt->bind_result($subject, $content);
		$stmt->execute();
		
		$del_topic = "";
		
		if($stmt->fetch()){
		
			$del_topic = "<a class='btn btn-default btn-xs' href='hw3_topic.php?id=$topic_id&delete=true' style='text-decoration:none'><font color='	 #cc0000''><span class='glyphicon glyphicon-trash'></span> Kustuta oma teema</font></a>";
			//echo $del_topic;
		
		}
		
		$stmt->close();
		return $del_topic;
	}
	
	function del($topic_id, $user_id){
		$stmt = $this->connection->prepare("UPDATE topics SET deleted=NOW() WHERE id=? AND user_id=? AND deleted IS NULL");
 		$stmt->bind_param("ii",$topic_id, $user_id);
		
		// kas õnnestus salvestada
		if($stmt->execute()){
 			// õnnestus
 			//echo "Kustutamine õnnestus!";
			$_SESSION["topic_message"] = "<p style='color:red;'>TEEMA KUSTUTATUD!</p>";
 		}
 		
 		$stmt->close();
	}
}
?>