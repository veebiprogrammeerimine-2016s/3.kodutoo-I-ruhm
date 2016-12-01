<?php
class User {	
	
	private $connection;

	function __construct($link) {
		$this->connection = $link;
	}

	function login($username, $password) {
		$users = array();
		$error = '';
		$stmt = $this->connection->prepare ('SELECT username, password FROM users WHERE username = ?;');
		$stmt -> bind_param('s', $username);
		$stmt -> bind_result($username_from_db, $password_from_db);
		$stmt -> execute();
		if($stmt->fetch()) {
			$hash = hash('sha512', $password);
			if ($hash ==$password_from_db) {
				$_SESSION['username'] = $username_from_db;
				header('Location: data.php');
				$stmt->close();
				exit();
			} else {
				$error = 'Vale parool või kasutajanimi!';
			}
		} else {
			$error =  'Vale parool või kasutajanimi!';
		}
		$stmt -> close();
		return $error;
	}
	
	function signup($email, $password, $username, $name, $d_o_b, $now) {
		
		//Kontroll, kas kasutajanimi on olemas juba
		$check_username_uniqueness_stmt = $this->connection->prepare("SELECT username FROM users WHERE username = ?");
		$check_username_uniqueness_stmt->bind_param("s", $username);
		$check_username_uniqueness_stmt->execute();
		if($check_username_uniqueness_stmt->fetch()) {
			echo "Kasutajanimi juba kasutusel";
			return;
		}
		
		//Kontroll, kas email on olemas juba
		$check_email_uniqueness_stmt = $this->connection->prepare("SELECT email FROM users WHERE email = ?");
		$check_email_uniqueness_stmt->bind_param("s", $email);
		$check_email_uniqueness_stmt->execute();
		if($check_email_uniqueness_stmt->fetch()) {
			echo "Email juba kasutusel";
			return;
		}
		
		//Kasutaja lisamine kasutajate tabelisse
		$stmt = $this->connection->prepare ('INSERT INTO users (email, password, username, name, date_of_birth, created) VALUES (?,?,?,?,?,?)' );
		echo $this->connection->error;
		$stmt->bind_param ('ssssss', $email, $password, $username, $name, $d_o_b, $now);
		if (!($stmt -> execute())) {
			echo 'Error'.$stmt->error;
		}
		$stmt -> close();
		
		//Tabeli tegemine kasutajale, kuhu salvestatakse tulemused
		$mk_tbl_stmt = "CREATE TABLE ".mysql_real_escape_string($username)." (
		id INT(6) AUTO_INCREMENT PRIMARY KEY,
		cube_type VARCHAR(5) NOT NULL,
		scramble VARCHAR(80) NOT NULL,
		time FLOAT NOT NULL, 
		created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
		deleted DATE NULL DEFAULT NULL
		)";
		if ($this->connection->query($mk_tbl_stmt)) {
			echo "Kasutaja tegemine õnnestus!";
		} else {
			echo "Kasutaja tegemine ebaõnnestus!<br>";
			echo $this->connection->error;
		}
	}
	
}	
?>