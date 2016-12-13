<?php
class Reservation
{

    private $connection;

    function __construct($mysqli)
    {
        $this->connection = $mysqli;

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

    function getAll()
    {


        $stmt = $this->connection->prepare("SELECT id, place, time, people, comment FROM broneering WHERE deleted IS NULL
		");

        echo $this->connection->error;

        $stmt->bind_result($id, $place, $time, $people, $comment);
        $stmt->execute();

        $result = array();
        //seni kuni on �ks rida andmeid saada (10 rida = 10 korda)
        while ($stmt->fetch()) {

            $reservation = new StdClass();
            $reservation->id = $id;
            $reservation->place = $place;
            $reservation->time = $time;
            $reservation->people = $people;
            $reservation->comment = $comment;


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
