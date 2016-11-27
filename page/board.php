<?php
/**
 * @author Alar Aasa <alar@alaraasa.ee>
 */
require("../../config.php");

$boardName = $_GET["name"];
$wrongBoardError = "This board doesn't exist!";
$postContentError = "";
$postUrlError = "";
$postName = $postContent = $postImage = $postPassword = "";

if (isset($_GET["sortby"])){
	$sortby = $_GET["sortby"];
    if ($sortby != "ASC" && $sortby != "DESC"){
        $sortby = "ASC";
    }
} else {
	$sortby = "ASC";
}

//Board exists check
$boardList = array();
$boardList = $Board->get();

foreach ($boardList as $a) {
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
    $postPassword = $Helper->random_str();
}

if (empty($wrongBoardError) &&
    empty($postContentError) &&
    empty($postUrlError) &&
    (isset($_POST["post"]) && !empty($_POST["post"])) ||
    (isset($_POST["image"]) && !empty($_POST["image"]))
    ) {
    $Post->create($boardName, $postName, $postPassword, $postContent, $postImage);
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
    <h1>' . $boardName . '
    </h1>
    <form method="post">
        <label>Name:
            <input name="name" type="text">
        </label>
        <br>
        <label>Image URL:
            <input name="image" name="url" type="url">
            '.$postUrlError.'
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
    ?>
    <form method="POST">
        <label>Search posts
            <input type=text name="search">
        </label>
        <input type=submit value="Search">
    </form>

    <?php

    if (empty($wrongBoardError)) {
        if (isset($_POST["search"]) && !empty($_POST["search"])){
            $search = $_POST["search"];
        } else {
            $search = NULL;
        };

        if (isset($_GET["sort"])){
          $sort = $_GET["sort"];
      } else {
          $sort = NULL;
      };
      $post = $Post->getAll($boardName, $search, $sort, $sortby);

	//existing sorting
    if ($sortby == "DESC"){
    	$sortby = "ASC";
    } else {
    	$sortby = "DESC";

    }

      echo "\n" . $sort . " | " . $sortby;

    $html = "<table>";
    $html .= "<tr>";

    if (isset($_GET["sort"]) && $_GET["sort"] == "id"){
      $html .= "<th><a href='?name=$boardName&sort=id&sortby=$sortby'>#</a></th>";
  } else {
      $html .= "<th><a href='?name=$boardName&sort=id&sortby=DESC'>#</a></th>";
  }

    $html .= "<th>Image</a></th>"; //Can't sort by image


    if (isset($_GET["sort"]) && $_GET["sort"] == "name"){
      $html .= "<th><a href='?name=$boardName&sort=name&sortby=$sortby'>Name</a></th>";
  } else {
      $html .= "<th><a href='?name=$boardName&sort=name&sortby=DESC'>Name</a></th>";
  }

    $html .= "<th>Post</th>"; //Not meant for sorting, too large and too many

    if (isset($_GET["sort"]) && $_GET["sort"] == "created"){
      $html .= "<th><a href='?name=$boardName&sort=created&sortby=$sortby'>Created</a></th>";
  } else {
      $html .= "<th><a href='?name=$boardName&sort=created&sortby=DESC'>Created</a></th>";
  }
   

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
    $html .= "<td>" . "<a href='page/editpost.php?name=" . $boardName . "&id=" . $p->id . "' target='_blank'>Edit post</a></td>";
    $html .= "</tr>";
}

$html .= "</table>";
echo $html;
} else {
    echo $wrongBoardError;
}

echo "<br><a href='" . "index.php" . "'>Change board </a>";
?>
