<?php

class Edit
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

    function getSingleRestoData($edit_id)
    {


        $stmt = $this->connection->prepare("SELECT restoName, grade, comment FROM restoranid WHERE id=? and deleted is NULL");
        $stmt->bind_param("i", $edit_id);
        $stmt->bind_result($restoName, $grade, $comment);
        $stmt->execute();

        //tekitan objekti
        $resto = new Stdclass();

        //saime ühe rea andmeid
        if ($stmt->fetch()) {
            // saan siin alles kasutada bind_result muutujaid
            $resto->restoName = $restoName;
            $resto->grade = $grade;
            $resto->comment = $comment;


        } else {
            // ei saanud rida andmeid kätte
            // sellist id'd ei ole olemas
            // see rida võib olla kustutatud
            echo "Midagi laks valesti:/";
            header("Location: restoDATA.php");
            exit();
        }

        $stmt->close();

        return $resto;

    }

    function updateResto($id, $grade, $comment)
    {

        $stmt = $this->connection->prepare("UPDATE restoranid SET grade=?, comment=? WHERE id=? and deleted is NULL");
        $stmt->bind_param("isi", $grade, $comment, $id);

        // kas õnnestus salvestada
        if ($stmt->execute()) {
            // õnnestus
            echo "salvestus õnnestus!";
        }

        $stmt->close();

    }

    function deleteResto($deleted)
    {

        $stmt = $this->connection->prepare("UPDATE restoranid SET deleted=NOW() WHERE id=? and deleted is NULL");
        $stmt->bind_param("i", $deleted);

        // kas õnnestus eemaldada
        if ($stmt->execute()) {
            // õnnestus
            echo "eemaldamine õnnestus!";
        }

        $stmt->close();

    }
}
?>