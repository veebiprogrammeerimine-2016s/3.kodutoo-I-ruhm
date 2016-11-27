<?php
class Post {

	function __construct($mysqli){
    	$this->connection = $mysqli;
		$this->Helper = new Helper($mysqli);
	}



    //Image or text can be empty. Field check done in boards.php
	function edit($board, $id, $text, $image)
	{	
		$mysqli = $this->connection;
		if ($text == "" || $text == NULL) {
			$text = " ";
		}
		if ($image == "" || $image == NULL) {
			$image = " ";
		}
		$text = $this->Helper->cleanInput($text);
		$image = $this->Helper->cleanInput($image);


		$stmt = $this->connection->prepare("
			UPDATE $board
			SET text = ?,
			imgdir = ?
			WHERE id = $id
			");

		$stmt->bind_param("ss", $text, $image);
		if ($stmt->execute()) {
			return true;
		} else {
			return false;
		}
	}

	//createPost
	function create($board, $name, $password, $post, $image)
	{
    //Inserts new post data into database
		$mysqli = $this->connection;
		$this->Helper->cleanInput($name);
		$this->Helper->cleanInput($post);

		$password = hash("sha512", $password);
		$stmt = $mysqli->prepare("INSERT INTO $board (name, password, text, imgdir) VALUES (?,?,?,?)");
		$stmt->bind_param("ssss", $name, $password, $post, $image);

		if ($stmt->execute()) {
			echo "<b>Post created!</b>";
		}
	}

    //Used for displaying current post values in the edit window.
	function get($board, $id)
	{
		//$mysqli = $this->connection;
		$query = "SELECT text, imgdir , password FROM $board WHERE id = $id";
		if ($stmt = $this->connection->prepare($query)) {
			$stmt->execute();
			$stmt->bind_result($text, $imgDir, $password);
			$result = array();
			while ($stmt->fetch()) {
				$this->text = $text;
				$this->imgdir = $imgDir;
				$this->password = $password;

				array_push($result, $this);
			}
		} else {
			echo "This board or post doesn't exist!";
			$result = "";
		}

		return $result;
	}

    //Returns all post data necessary for displaying it for users.
	function getAll($board, $search, $sort, $sortBy)
	{
		$searchQuery = $sortQuery = NULL;

		$sortValues = ["id", "name", "created"];

		
		if ($sortBy == "ASC" || $sortBy == "DESC"){
			if (in_array($sort, $sortValues)){
				$sortQuery = "ORDER BY $sort $sortBy";
			}
		} else {
			$sortQuery = NULL;
			echo "Cannot sort by ". $sortBy."!";
		}
		
		if ($search !=" " && $search != "" && $search != NULL){
			$search = $this->Helper->cleanInput($search);
			$searchQuery = "AND (id LIKE ('%$search%') OR name LIKE ('%$search%') OR text LIKE ('%$search%') )";
		} else {
			$searchQuery = NULL;
		}
		$stmt = $this->connection->prepare("
			SELECT id, name, text, imgdir, created
			FROM $board
			WHERE trashed != 1
			$searchQuery
			$sortQuery
			");
		$stmt->bind_result($id, $name, $text, $imgDir, $created);
		$stmt->execute();
		$result = array();
		while ($stmt->fetch()) {
			$post = new stdClass();
			$post->id = $id;
			$post->name = $name;
			$post->text = $text;
			$post->imgdir = $imgDir;
			$post->created = $created;

			array_push($result, $post);
		}

		return $result;
	}

	//Archive post
	function delete($board, $id)
	{
    //Does not remove posts, just trashes them
		$mysqli = $this->connection;
		$stmt = $mysqli->prepare("
			UPDATE $board
			SET trashed = 1
			WHERE id = $id
			");
		if ($stmt->execute()) {
			return true;
		} else {
			return false;
		}
	}




}
?>