<?php

class User
{

//klassi sees saab kasutada
    private $connection;

//$user=new user(see); jouab siia sulgude vahele
    function __construct($mysqli)
    {

//klassi sees muutujua kasutamiseks $this->
//this viitab sellele klassile
        $this->connection = $mysqli;

    }

    function signup ($signupEmail, $signupPassword, $signupName, $signupLName, $signupage, $phonenr, $signupgender){



    //yhendus olemas
    $this->connection = new mysqli($GLOBALS["serverHost"],$GLOBALS["serverUsername"],$GLOBALS["serverPassword"],$GLOBALS["database"]);

    //kask
    $stmt = $this->connection->prepare("INSERT INTO user_sample (email,password, name, lastname, age, phonenr, gender) VALUES (?, ?, ?, ?, ?, ?, ?)");

    echo $this->connection->error;
    //asendan kysimargid vaartustega
    //iga muutuja kohta 1 taht
    //s tahistab stringi
    //i integer
    //d double/float
    $stmt->bind_param("sssssis", $signupEmail, $signupPassword, $signupName, $signupLName, $signupage, $phonenr, $signupgender);

    if($stmt->execute()){
        echo "kasutaja loomine õnnestus";
    }else {
        echo"ERROR ".$stmt->error;
    }
}
    function login($email, $password){

        $error = "";

        $this->connection = new mysqli($GLOBALS["serverHost"],$GLOBALS["serverUsername"],$GLOBALS["serverPassword"],$GLOBALS["database"]);

        //kask
        $stmt = $this->connection->prepare("
			SELECT id, email, password, gender, name, created
			FROM user_sample
			WHERE email=? 
		");

        echo $this->connection->error;

        //asendan kysimargid
        $stmt->bind_param("s", $email);

        //maaran tulpadele muutujad
        $stmt->bind_result($id, $emailfromdatabase, $passwordfromdatabase, $genderfromdb, $namefromdb, $created);
        $stmt->execute();

        if($stmt->fetch()) {
            //oli rida

            //vordlen paroole
            $hash = hash("sha512", $password);
            if($hash == $passwordfromdatabase){

                echo "kasutaja ".$id."logis sisse";

                $_SESSION["userId"]= $id;
                $_SESSION["email"]=$emailfromdatabase;
                $_SESSION["gender"]=$genderfromdb;
                $_SESSION["name"]=$namefromdb;


                //suunan uuele lehele
                header("location: restoDATA.php");


            }else {
                $error = "parool vale";
            }

        }else {
            //ei olnud
            $error = "sellise emailiga ".$email." kasutajat ei olnud";

        }

        return $error;
    }




}
?>