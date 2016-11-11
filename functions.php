<?php
/**
 * @author Alar Aasa <alar@alaraasa.ee>
 */
require_once("config.php");

function createBoard($table)
{
    //Creates new table that will be used for a new board
    $table = cleanInput($table);

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

    return editTable($query);
}


function editTable($query)
{
    //For all sorts of queries
    $mysqli = connectDB();
    sqlConnectTest($mysqli);
    if (!$mysqli->query($query)) {
        echo "Table edit failed: (" . $mysqli->errno . ") " . $mysqli->error;
        return false;
    }
    $mysqli->close();
    return true;
}

function createPost($board, $name, $password, $post, $image)
{
    //Inserts new post data into database
    $mysqli = connectDB();
    sqlConnectTest($mysqli);
    cleanInput($name);
    cleanInput($post);

    $password = hash("sha512", $password);
    $stmt = $mysqli->prepare("INSERT INTO $board (name, password, text, imgdir) VALUES (?,?,?,?)");
    $stmt->bind_param("ssss", $name, $password, $post, $image);

    if ($stmt->execute()) {
        echo "<b>Post created!</b>";
    }
    $stmt->close();
    $mysqli->close();
}

function getAllPosts($board)
{
    //Returns all post data necessary for displaying it for users.
    $mysqli = connectDB();
    $stmt = $mysqli->prepare("
        SELECT id, name, text, imgdir, created
        FROM $board
        WHERE trashed != 1
        ");
    echo $mysqli->error;
    $stmt->bind_result($id, $name, $text, $imgDir, $created);
    $stmt->execute();
    $result = array();
    while ($stmt->fetch()) {
        $post = new stdClass();
        $post->id = $id;
        $post->name = $name;
        $post->text = $text;
        $post->imgdir = $imgDir;
        $post->created = $created;

        array_push($result, $post);
    }

    $stmt->close();
    $mysqli->close();

    return $result;
}

function editGetPost($board, $id)
{
    //Used for displaying current post values in the edit window.
    $mysqli = connectDB();
    $query = "SELECT text, imgdir , password FROM $board WHERE id = $id";
    if ($stmt = $mysqli->prepare($query)) {
        $stmt->execute();
        $stmt->bind_result($text, $imgDir, $password);
        $result = array();
        while ($stmt->fetch()) {
            $post = new stdClass();
            $post->text = $text;
            $post->imgdir = $imgDir;
            $post->password = $password;

            array_push($result, $post);
        }
        $stmt->close();

    } else {
        echo "This board or post doesn't exist!";
        $result = "";
    }
    $mysqli->close();

    return $result;
}

function editPost($board, $id, $text, $image)
{
    //Image or text can be empty. Field check done in boards.php
    $mysqli = connectDB();
    if ($text == "" || $text == NULL) {
        $text = " ";
    }
    if ($image == "" || $image == NULL) {
        $image = " ";
    }
    $text = cleanInput($text);
    $image = cleanInput($image);


    $stmt = $mysqli->prepare("
        UPDATE $board
        SET text = ?,
        imgdir = ?
        WHERE id = $id
        ");

    $stmt->bind_param("ss", $text, $image);
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

function deletePost($board, $id)
{
    //Does not remove posts, just trashes them
    $mysqli = connectDB();
    $stmt = $mysqli->prepare("
        UPDATE $board
        SET trashed = 1
        WHERE id = $id
        ");
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

function getTables()
{
    //Returns a list of tables into $return.
    $mysqli = connectDB();
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
    $stmt->close();
    $mysqli->close();
    return $result;
}


function trashBoard($board)
{
    //Available only to admin
    //Adds "trashed" to table beginning, trashed board will not show for users
    $mysqli = connectDB();
    sqlConnectTest($mysqli);
    $query = "
    RENAME TABLE $board TO trashed_$board
    ";
    if (!$mysqli->query($query)) {
        echo "Table trash failed: (" . $mysqli->errno . ") " . $mysqli->error;
        return false;
    }
    $mysqli->close();
    return true;
}

function cleanInput($input)
{
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    return $input;
}

function random_str()
{
    //For random password generation
    $string = bin2hex(openssl_random_pseudo_bytes(10));
    return $string;
}
