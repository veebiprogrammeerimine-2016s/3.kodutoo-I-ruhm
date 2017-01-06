    <?php
class Player {
    private $connection;
    function __construct ($mysqli){
        $this->connection = $mysqli;
    }
    function getSinglePlayerData($edit_id){

        $stmt = $this->connection->prepare("SELECT firstname, lastname, shirt_number FROM players WHERE id=? AND deleted IS NULL");
        $stmt->bind_param("i", $edit_id);
        $stmt->bind_result($firstname, $lastname, $shirt_number);
        $stmt->execute();

        //tekitan objekti
        $player = new Stdclass();

        //saime �he rea andmeid
        if($stmt->fetch()){
            // saan siin alles kasutada bind_result muutujaid
            $player->firstname = $firstname;
            $player->lastname = $lastname;
            $player->shirt_number = $shirt_number;


        }else{
            // ei saanud rida andmeid k�tte
            // sellist id'd ei ole olemas
            // see rida v�ib olla kustutatud
            header("Location: data.php");
            exit();
        }

        $stmt->close();

        return $player;

    }
    function updatePlayer($id, $firstname, $lastname, $shirt_number){

        $stmt = $this->connection->prepare("UPDATE players SET firstname=?, lastname=?, shirt_number=? WHERE id=? AND deleted IS NULL");
        $stmt->bind_param("ssii",$firstname, $lastname, $shirt_number, $id);

        // kas �nnestus salvestada
        if($stmt->execute()){
            // �nnestus
            echo "salvestus �nnestus!";
        }

        $stmt->close();

    }
    function deletePlayer($id){

        $stmt = $this->connection->prepare("UPDATE players SET deleted=NOW() WHERE id=? AND deleted IS NULL");
        $stmt->bind_param("i",$id);

        // kas �nnestus eemaldada
        if($stmt->execute()){
            // õnnestus
            echo "eemaldamine õnnestus!";
        }
        $stmt->close();
    }
    function savePlayer ($firstname, $lastname, $shirt_number) {

        $stmt = $this->connection->prepare("INSERT INTO players (firstname, lastname, shirt_number) VALUES (?, ?, ?)");

        echo $this->connection->error;

        $stmt->bind_param("ssi", $firstname, $lastname, $shirt_number);

        if ($stmt->execute()) {

            echo "Salvestamine õnnestus";
        } else {
            echo "ERROR ".$stmt->error;
        }
    }
    function getAllPlayers ($q, $sort, $order) {

        $allowedSort = ["id", "firstname", "lastname", "shirt_number"];
        if(!in_array($sort, $allowedSort)){
            $sort = "id";
        }

        $orderBy = "ASC";

        if ($order == "DESC") {

            $orderBy = "DESC";
        }
        if ($q != "") {

            echo "Otsib: ".$q;
            $stmt = $this->connection->prepare("
			  SELECT id, firstname, lastname, shirt_number
			  FROM players
			  WHERE deleted IS NULL
			  AND (firstname LIKE ? OR lastname LIKE ? OR shirt_number LIKE ?)
			  ORDER BY $sort $orderBy
		  ");
            $searchWord = "%" .$q."%";
            $stmt->bind_param("sss", $searchWord, $searchWord, $searchWord);
        }else{
            $stmt = $this->connection->prepare("
			SELECT id, firstname, lastname, shirt_number
			FROM players
			WHERE deleted IS NULL
			ORDER BY $sort $orderBy

		");
        }
        echo $this->connection->error;

        $stmt->bind_result($id, $firstname, $lastname, $shirt_number);
        $stmt->execute();


        //tekitan massiivi
        $result = array();

        // tee seda seni, kuni on rida andmeid
        // mis vastab select lausele
        while ($stmt->fetch()) {

            //tekitan objekti
            $player = new StdClass();

            $player->id = $id;
            $player->firstname = $firstname;
            $player->lastname = $lastname;
            $player->shirt_number = $shirt_number;

            //echo $plate."<br>";
            // iga kord massiivi lisan juurde nr m�rgi
            array_push($result, $player);
        }

        $stmt->close();


        return $result;
    }
}
?>