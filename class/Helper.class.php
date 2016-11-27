<?php class Helper {
	
	/*private $connection;

	function __construct($mysqli) {
		
		$this->connection = $mysqli;
	}*/
	
	function cleanInput($input){
		
		$input = trim($input); // trim($str)-  only whitespace from the beginning and end. Muidu andmebaasi jätab tühikud isegi kui lehele ei jäta!
		$input = stripslashes($input); // function removes \
		$input = htmlspecialchars($input); //The htmlspecialchars() function converts some predefined characters to HTML
		
		return $input;
		
	}
}
?>