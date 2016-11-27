<?php

class Helper {
	function __construct($mysqli){
		$this->connection = $mysqli;
	}

	function cleanInput($input)
	{
		$input = trim($input);
		$input = stripslashes($input);
		$input = htmlspecialchars($input);
		return $input;
	}

	function random_str()
	{
    //For random password generation
		$string = bin2hex(openssl_random_pseudo_bytes(10));
		return $string;
	}

	//editTable
    //For all sorts of queries
	function editTable($query)
	{
		$mysqli = $this->connection;
		if (!$mysqli->query($query)) {
			echo "Table edit failed: (" . $mysqli->errno . ") " . $mysqli->error;
			return false;
		}
		return true;
	}
}


?>