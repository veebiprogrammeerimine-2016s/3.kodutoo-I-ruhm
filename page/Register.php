<?php
require("../Functions.php");

$signupEmailError = "";
$signupPassword= "";
$signupPasswordError = "";
$signupEmail = "";
$gender = "";
$signupName ="";
$signupNameError ="";
$signupLName ="";
$signupLNameError ="";
$error ="";

if ( isset ( $_POST["signupEmail"] ) ) {
    if ( empty ( $_POST["signupEmail"] ) ) {
        $signupEmailError = "Sisesta korrektne e-mail!";
    } else {
        $signupEmail = $_POST["signupEmail"];
    }
}
if ( isset ( $_POST["signupPassword"] ) ) {
    if ( empty ( $_POST["signupPassword"] ) ) {
        $signupPasswordError = "See väli on kohustuslik!";
    } else {
        if ( strlen($_POST["signupPassword"]) < 8 ) {
            $signupPasswordError = "Parool peab olema vähemalt 8 tähemärkki pikk";
        }
    }
}
if (isset ($_POST["signupName"])) {
    // oli olemas, ehk keegi vajutas nuppu
    if (empty($_POST["signupName"])) {
        //oli tõesti tühi
        $signupNameError = "Sisesta eesnimi!";
    } else {
        $signupName = $_POST["signupName"];
    }
}
if (isset ($_POST["signupLName"])) {
    // oli olemas, ehk keegi vajutas nuppu
    if (empty($_POST["signupLName"])) {
        //oli tõesti tühi
        $signupLNameError = "Sisesta perekonnanimi!";
    } else {
        $signupLName = $_POST["signupLName"];
    }
}
if ( isset ( $_POST["gender"] ) ) {
    if ( empty ( $_POST["gender"] ) ) {
        $genderError = "See väli on kohustuslik!";
    } else {
        $gender = $_POST["gender"];
    }
}
if ( isset($_POST["signupPassword"]) &&
    isset($_POST["signupEmail"]) &&
    isset($_POST["signupName"]) &&
    isset($_POST["signupLName"]) &&
    empty($signupEmailError) &&
    empty($signupPasswordError) &&
    empty($signupNameError) &&
    empty($signupLNameError)
) {

    echo "Salvestan...<br>";
    echo "email ".$signupEmail."<br>";
    $password = hash ("sha512", $_POST["signupPassword"]);

    //echo "parool ".$_POST["signupPassword"]."<br>";
    echo "räsi ".$password."<br>";

    $signupEmail = $Helper->cleanInput($signupEmail);
    $signupPassword = $Helper->cleanInput($password);
    $User->signUP($signupEmail, $password, $signupName, $signupLName);
}
?>
<?php require("../header.php"); ?>
<a class="btn btn-link btn-sm" href="Login.php"> Tagasi </a>
<center><h1>Kasutaja loomine</h1>

<form method="POST">
    <input style="text-align:center;" name="signupEmail" type="text" placeholder="Email" value="<?=$signupEmail;?>"> <?php echo $signupEmailError; ?>
    <br><br>
    <input style="text-align:center;" name="signupPassword" type="password" placeholder="Parool"> <?php echo $signupPasswordError; ?>
    <br><br>
    <input style="text-align:center;" name="signupName" type="text" placeholder="Eesnimi" value="<?=$signupName;?>">
    <br><br>
    <input style="text-align:center;" name="signupLName" type="text" placeholder="Perekonna nimi" value="<?=$signupLName;?>">
    <br><br>

    <?php if($gender == "male") { ?>
        <input type="radio" name="gender" value="male"> Mees<br>
    <?php } else { ?>
        <input type="radio" name="gender" value="male" > Mees<br>
    <?php } ?>

    <?php if($gender == "female") { ?>
        <input type="radio" name="gender" value="female"> Naine<br>
    <?php } else { ?>
        <input type="radio" name="gender" value="female" > Naine<br>
    <?php } ?>

    <?php if($gender == "other") { ?>
        <input type="radio" name="gender" value="other"> Muu<br>
    <?php } else { ?>
        <input type="radio" name="gender" value="other" > Muu<br>
    <?php } ?>
    <br>
    <input type="submit" value="Loo kasutaja">

</form></center>
<?php require("../footer.php"); ?>
