<?php

class Board {


    function __construct($mysqli){
      $this->connection = $mysqli;
      $this->Helper = new Helper($mysqli);

  }

//createBoard
  function create($table)
  {
    //Creates new table that will be used for a new board
    $table = $this->Helper->cleanInput($table);

    $query = "
    CREATE TABLE " . $table .
    " (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100),
    password VARCHAR(150),
    text TEXT(1000),
    imgdir VARCHAR(150),
    created TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    trashed TINYINT(1) DEFAULT 0
    )";

    return $this->Helper->editTable($query);
}

//getTables
//Returns a list of tables into $return.
function get()
{
    $mysqli = $this->connection;
    //sqlConnectTest("boards");
    $query = "SHOW TABLES FROM if16_alaraasa_board WHERE tables_in_if16_alaraasa_board NOT LIKE 'trashed_%'";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_result($boards);
    $stmt->execute();
    $result = array();
    while ($stmt->fetch()) {
        $boardList = new stdClass();
        $boardList->name = $boards;

        array_push($result, $boardList);
    }

    return $result;
}
//trashBoard
function delete($board)
{
    //Available only to admin
    //Adds "trashed" to table beginning, trashed board will not show for users
    $mysqli = $this->connection;
    sqlConnectTest($mysqli);
    $query = "
    RENAME TABLE $board TO trashed_$board
    ";
    if (!$mysqli->query($query)) {
        echo "Table trash failed: (" . $mysqli->errno . ") " . $mysqli->error;
        return false;
    }
    return true;
}

}




?>