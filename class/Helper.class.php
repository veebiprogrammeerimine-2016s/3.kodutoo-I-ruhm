<?php
class Helper {
	
    //klassi sees saab kasutada
	private $connection;
	
	// $User = new User(see); jõuab suua sulgude vahele
	function __construct($mysqli) {
		
		//klassi sees muutuja kasutamiseks $this->
		//$this viitab sellele klassile
		
	    $this->connection = $mysqli;
		
	}
	
	/* TEISED FUNKTSIOONID */
	
	
    function cleanInput($input){
		
		$input = trim($input);
		$input = stripslashes($input);
		$input = htmlspecialchars($input);
		
		return $input;
		
	}
	
}
	
?>