<?php
class Reservation
{

    private $connection;

    function __construct($mysqli)
    {
        $this->connection = $mysqli;

    }

    function getSingleData($edit_id){


        $stmt = $this->connection->prepare("SELECT people, comment FROM broneering WHERE id=? AND deleted IS NULL");

        $stmt->bind_param("i", $edit_id);
        $stmt->bind_result($people, $comment);
        $stmt->execute();

        //tekitan objekti
        $reservation = new Stdclass();

        //saime �he rea andmeid
        if($stmt->fetch()){
            // saan siin alles kasutada bind_result muutujaid
            $reservation->people = $people;
            $reservation->comment = $comment;


        }else{
            // ei saanud rida andmeid k�tte
            // sellist id'd ei ole olemas
            // see rida v�ib olla kustutatud
            header("Location: broneering.php");
            exit();
        }

        $stmt->close();

        return $reservation;

    }


    function save($place, $time, $people, $comment)
    {


        $error = "";


        $stmt = $this->connection->prepare("INSERT INTO broneering (place,time,people,comment) VALUES (?, ?, ?, ?)");

        echo $this->connection->error;

        $stmt->bind_param("ssss", $place, $time, $people, $comment);


        if ($stmt->execute()) {

            echo "salvestamine �nnestus";

        } else {
            echo "ERROR " . $stmt->error;

        }
    }

    function getAll($q, $sort, $order) {

        $allowedSort = ["id", "place", "comment"];
        if(!in_array($sort, $allowedSort)) {
            //ei ole lubatud tulp
            $sort = "id";
        }

        $orderBy = "ASC";

        if ($order == "DESC") {
            $orderBy = "DESC";
        }
        echo "Sorteerin: ".$sort." ".$orderBy." ";

        if($q != "") {

            echo "Otsib: ".$q;

            $stmt = $this->connection->prepare("
			SELECT id, place, time, people, comment
			FROM broneering
			WHERE deleted IS NULL
			AND (place LIKE ? OR time LIKE ? OR people LIKE ? OR comment LIKE ?)
			ORDER BY $sort $orderBy
			
			");
            $searchWord="%".$q."%";
            $stmt->bind_param("ssss", $searchWord, $searchWord, $searchWord, $searchWord);

        } else {
            $stmt = $this->connection->prepare("
			SELECT id, place, time, people, comment
			FROM broneering
			WHERE deleted IS NULL
			ORDER BY $sort $orderBy
			");


        }
        echo $this->connection->error;

        $stmt->bind_result($id, $place, $time, $people, $comment);
        $stmt->execute();


        //tekitan massiivi
        $result = array();

        // tee seda seni, kuni on rida andmeid
        // mis vastab select lausele
        while ($stmt->fetch()) {

            //tekitan objekti
            $reservation = new StdClass();

            $reservation->id = $id;
            $reservation->place = $place;
            $reservation->time = $time;
            $reservation->people = $people;
            $reservation->comment = $comment;

            //echo $plate."<br>";
            // iga kord massiivi lisan juurde nr m�rgi
            array_push($result, $reservation);
        }

        $stmt->close();


        return $result;
    }


    function update($id, $people, $comment)
    {


        $stmt = $this->connection->prepare("UPDATE broneering SET people=?, comment=? WHERE id=?");
        $stmt->bind_param("ssi", $people, $comment, $id);

        // kas �nnestus salvestada
        if ($stmt->execute()) {
            // �nnestus
            echo "salvestus �nnestus!";
        }

        $stmt->close();

    }


    function delete($deleted)
    {


        $stmt = $this->connection->prepare("UPDATE broneering SET deleted=NOW() WHERE id=? AND deleted IS NULL");

        echo $this->connection->error;

        $stmt->bind_param("s", $deleted);

        if ($stmt->execute()) {
            echo "kustutamine �nnestus";
        } else {
            echo "ERROR " . $stmt->error;
        }

        $stmt->close();

    }
}

  ?>
