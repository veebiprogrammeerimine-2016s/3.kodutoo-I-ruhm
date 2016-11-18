<?php
$createBoardName = $createBoardError = "";

if (isset($_POST["createBoardName"]) && !empty($_POST["createBoardName"])) {
    $createBoardName = $_POST["createBoardName"];
    if (strlen($createBoardName) > 20) {
        $createBoardError = "Board name too long! Max size 20 characters!";
    } else if (!createBoard($createBoardName)) {
        $createBoardError = "Board already exists!";
    }
} else if (isset($_POST["createBoardName"]) && empty($_POST["createBoardName"])) {
    $createBoardError = "Name cannot be empty!";
}

?>
<form method="POST">
    <label>Board name:
        <input name="createBoardName" type="text">
        <?= $createBoardError; ?>
    </label>
    <input type="submit" value="Create new board">
</form>