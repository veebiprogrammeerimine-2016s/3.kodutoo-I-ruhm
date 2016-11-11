<?php
/**
 * @author Alar Aasa <alar@alaraasa.ee>
 */

require("config.php");
require("functions.php");

$board = $_GET["name"];
$id = $_GET["id"];


//Getting all single post data form the database.
$post = editGetPost($board, $id);
if (!empty($post)) {
    $array = json_decode(json_encode($post), True);
    echo "<br>";
    $textValue = $array[0]["text"];
    $password = $array[0]["password"];
    $imgDir = $array[0]["imgdir"];

    $passwordError = "";
    $passwordCheck = "";
    $contentError = "";

//Editing post when password is true. NOT REQUIRED text AND imgDir, but one of them IS REQUIRED
    if (isset($_POST["password"])) {
        if (!empty($_POST["password"])) {
            $passwordCheck = hash("sha512", $_POST["password"]);
            //PASSWORD CHECK
            if ($password == $passwordCheck) {
                if (!empty($_POST["text"]) || !empty($_POST["imgDir"])) {
                    if (editPost($board, $id, $_POST["text"], $_POST["imgDir"])) {
                        //close edit tab and refresh board without post data
                        echo "<script>
                        window.opener.location = window.opener.location;
                        window.close();
                    </script>";
                    } else {
                        echo "Post edit not successful.";
                    }
                } else {
                    $contentError = "You must enter an image URL, some text or both!";
                }

                //DELETE POST
                if (isset($_POST["delete"]) && $_POST["delete"]) {
                    if (deletePost($board, $id)) {
                        //close edit tab and refresh board without post data
                        echo "<script>
                    window.opener.location = window.opener.location;
                    window.close();
                </script>";
                    };
                }
            } else {
                $imgDir = $_POST["imgDir"];
                $textValue = $_POST["text"];
                $passwordError = "That password is wrong.";
            }
        } else {
            $passwordError = "You must enter a password!";
        }
    }

    $html = '
    <form method="post">
    <label>Password
        <input type="password" name="password" style="width:400px;">
        ' . $passwordError . '
    </label><br>

    <label>Image
        <input type="url" name="imgDir" value="' . $imgDir . '" style="width:400px;">
    </label><br>
    <label>Text:
        <textarea name="text" style="width:400px;height:150px;">' . $textValue . '</textarea>
    </label><br>
    <label>Delete
        <input type="checkbox" name="delete" value="false">
    </label><br>
    <input type="submit" value="Edit post">
    </form>
            ';
    echo $html;
    echo "<br> $contentError";
}

?>