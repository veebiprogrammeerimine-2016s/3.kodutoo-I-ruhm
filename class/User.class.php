<?php
class User {
    private $connection;
    function __construct($mysqli) {
        $this->connection = $mysqli;
    }
    function signUP ($signupEmail, $password, $signupName, $signupLName) {

        $stmt = $this->connection->prepare("INSERT INTO user_sample (email, password, firstname, lastname) VALUES (?, ?, ?, ?)");

        echo $this->connection->error;

        $stmt->bind_param("ssss", $signupEmail, $password, $signupName, $signupLName);

        if ($stmt->execute()) {
            echo "Salvestamine õnnestus";
        } else {
            echo "ERROR ".$stmt->error;
        }
    }
    function login ($email, $password) {

        $error = "";

        $this->connection = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);

        $stmt = $this->connection->prepare("
			
			SELECT id, email, password, created
			FROM user_sample
			WHERE email = ?
		");


        echo $this->connection->error;

        $stmt->bind_param ("s", $email);
        $stmt->bind_result($id, $emailFromDb, $passwordFromDb, $created);
        $stmt->execute();


        if ($stmt->fetch()) {

            $hash = hash("sha512", $password);
            if($hash == $passwordFromDb) {


                echo "kasutaja " .$id." logis sisse";

                $_SESSION["userId"] = $id;
                $_SESSION["email"] = $emailFromDb;

                header("Location: data.php");
            }else  {
                $error = "Vale parool";
            }

        }else {
            $error = "Sellise emailiga ".$email. "kasutajat ei olnud";
        }
        return $error;

    }
}
?>