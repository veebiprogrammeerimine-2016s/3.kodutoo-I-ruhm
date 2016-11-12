<?php

class Resto
{

    //klassi sees saab kasutada
    private $connection;

    //$user=new user(see); jouab siia sulgude vahele
    function __construct($mysqli){

        //klassi sees muutujua kasutamiseks $this->
        //this viitab sellele klassile
        $this->connection=$mysqli;

    }


function saverestos($restoName, $grade, $comment, $gender){


    //yhendus olemas
    //kask
    $stmt =$this->connection->prepare("INSERT INTO restoranid (restoName,grade,comment,customer_sex) VALUES (?, ?, ?, ?)");

    echo $this->connection->error;
    //asendan kysimargid vaartustega
    //iga muutuja kohta 1 taht
    //s tahistab stringi
    //i integer
    //d double/float
    $stmt->bind_param("ssss", $restoName, $grade, $comment, $gender);

    if($stmt->execute()){
        echo "salvestamine onnestus ";
    }else {
        echo"ERROR ".$stmt->error;
    }



}

function getallrestos($q, $sort, $order){

    $allwoedSort = ["id","restoName","grade","comment","customer_sex","created"];


    if(!in_array($sort,$allwoedSort)){
        //ei ole lubatud tulp
        $sort="id";

    }
    $orderBy = "ASC";
    if($order == "DESC"){
        $orderBy = "DESC";
    }
    //echo "sorteerin: ".$sort." ".$orderBy." ";
    //kask

    if($q!="") {
        $stmt = $this->connection->prepare("SELECT id, restoName, grade, comment, customer_sex, created FROM restoranid WHERE deleted is NULL AND (restoName LIKE? OR comment LIKE? OR grade LIKE?)
                                            ORDER BY $sort $orderBy");
        $searchWord="%".$q."%";
        $stmt->bind_param("sss", $searchWord, $searchWord, $searchWord);
    }else{
        $stmt = $this->connection->prepare("SELECT id, restoName, grade, comment, customer_sex, created FROM restoranid WHERE deleted is NULL
                                            ORDER BY $sort $orderBy");
    }
    echo $this->connection->error;

    $stmt->bind_result($id, $restoName, $grade, $comment, $gender, $created);
    $stmt->execute();

    $result = array();


    //seni kuni on 1 rida andmeid saada(10rida=10korda)
    while($stmt->fetch()){

        $person = new StdClass();
        $person->id = $id;
        $person->restoName = $restoName;
        $person->grade = $grade;
        $person->comment = $comment;
        $person->gender = $gender;
        $person->created = $created;

        //echo $color. "<br>";
        array_push($result, $person);

    }
    $stmt->close();
    return $result;
}

}
?>