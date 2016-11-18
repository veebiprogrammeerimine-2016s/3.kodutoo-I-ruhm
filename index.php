<html>
<head>
    <title>Boards</title>
</head>
<link rel="stylesheet" type="text/css" href="style.css">
<?php
/**
 * @author Alar Aasa <alar@alaraasa.ee>
 */
require_once("config.php");
//require_once("functions.php");

$createBoardError = "";
$createBoard = "";


echo "Created boards:<br>";


if (isset($_POST["createBoardName"])) {
    if (empty($_POST["createBoardName"])) {
        $createBoardError = "Board name cannot be empty.";
    }
    $boardName = $_POST["createBoardName"];
    if ($Board->create($boardName)) {
        echo "Board " . $boardName . " created successfully!";
    }
    echo "<br>";
    $_POST["createBoardName"] = NULL;

}

$boardList = array();
$boardList = $Board->get();
$html = "";
foreach ($boardList as $p) {
    $html .= "<a href='index.php?name=" . $p->name . "'>" . $p->name . "</a> | ";
}
echo $html;
echo "<br><br>";


if (isset($_GET["name"])) {
    require("page/board.php");
} else {
    require("page/createboard.php");
}
?>

</html>



