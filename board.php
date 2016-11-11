<?php
/**
 * @author Alar Aasa <alar@alaraasa.ee>
 */
$boardName = $_GET["name"];
$wrongBoardError = "This board doesn't exist!";
$postContentError = "";
$postUrlError = "";
$postName = $postContent = $postImage = $postPassword = "";

//Board exists check
$boardCheck = getTables();

foreach ($boardCheck as $a) {
    if ($a->name == $_GET["name"]) {
        $wrongBoardError = "";
    }
}

//If the board does not exist, remove GET and POST data
if (!empty($wrongBoardError)) {
    $_GET["name"] = NULL;
    $_POST["post"] = NULL;
    $_POST["image"] = NULL;
    $_POST["password"] = NULL;
}

if (isset($_POST["name"]) && !empty($_POST["name"])) {
    $postName = $_POST["name"];
} else {
    $postName = "Anonymous";
}
if (isset($_POST["post"]) && !empty($_POST["post"])) {
    $postContent = $_POST["post"];

}
if (isset($_POST["image"]) && !empty($_POST["image"])) {
    if (filter_var($_POST["image"], FILTER_VALIDATE_URL)) {
        $postImage = $_POST["image"];
    } else {
        $postImage = " ";
        $postImageError = "This is not a valid address!";
    }
} else {
    $postImage = " ";
}
if (isset($_POST["password"]) && !empty($_POST["password"])) {
    $postPassword = $_POST["password"];
} else {
    //can't be edited
    $postPassword = random_str();
}

if (empty($wrongBoardError) &&
    empty($postContentError) &&
    empty($postUrlError) &&
    (isset($_POST["post"]) && !empty($_POST["post"])) ||
    (isset($_POST["image"]) && !empty($_POST["image"]))
) {
    createPost($boardName, $postName, $postPassword, $postContent, $postImage);
    //remove POST for easy user refresh, POST is unnecessary after sending it once.
    echo "
<script>
    window.location = window.location;
</script>
";
} else if (isset($_POST["post"]) || isset($_POST["imgDir"])) {
    echo "Post not created. Check the fields for any errors.";
}


if (empty($wrongBoardError)) {
    echo '
    <h1>' . $boardName . '</h1>
    <form method="post">
        <label>Name:
            <input name="name" type="text">
        </label>
        <br>
        <label>Image URL:
            <input name="image" name="url" type="url">
            <?=$postUrlError?>
        </label>
        <br>
        <label>Post:
            <textarea name="post" style="width:250px;height:150px;"></textarea> </label>
            <br>
            <label>Password:
                <input name="password" type="password">
            </label>
            <br>
            <input type="submit" value="Create post">
        </form>
        <br>
        <br>
        <br>
        ';
}

if (empty($wrongBoardError)) {
    $post = getAllPosts($boardName);
    $html = "<table>";
    $html .= "<tr>";
    $html .= "<th>#</th>";
    $html .= "<th>Image</th>";
    $html .= "<th>Name</th>";
    $html .= "<th>Post</th>";
    $html .= "<th>Created</th>";
    $html .= "<th>Edit</th>";
    $html .= "</tr>";
    foreach ($post as $p) {
        $html .= "<tr>";
        $html .= "<td>" . $p->id . "</td>";
        if ($p->imgdir == " " || $p->imgdir == "") {
            $html .= "<td></td>";
        } else {
            $html .= "'<td><a href='" . $p->imgdir .
                "'><img src='" . $p->imgdir . "' height='100' width='auto'>" . "</td></a>";
        }
        $html .= "<td>" . $p->name . "</td>";
        $html .= "<td>" . $p->text . "</td>";
        $html .= "<td>" . $p->created . "</td>";
        $html .= "<td>" . "<a href='editpost.php?name=" . $boardName . "&id=" . $p->id . "' target='_blank'>Edit post</a></td>";
        $html .= "</tr>";
    }
    $html .= "</table>";
    echo $html;
} else {
    echo $wrongBoardError;
}

echo "<br><a href='" . "index.php" . "'>Change board </a>";
?>
