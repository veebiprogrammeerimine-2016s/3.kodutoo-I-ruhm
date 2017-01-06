<?php
//edit.php
require("../Functions.php");

if(isset($_GET["deleted"])) {
    $Player->deletePlayer($Helper->cleanInput($_GET["id"]));
    header("Location: data.php");
}

//kas kasutaja uuendab andmeid
if(isset($_POST["update"])){

    $Player->updatePlayer(($_POST["id"]),$Helper->cleanInput($_POST["firstname"]),$Helper->cleanInput($_POST["lastname"]),$Helper->cleanInput($_POST["shirt_number"]));

    //header("Location: data.php?id=".$_POST["id"]."&success=true");
    //exit();
}

//saadan kaasa id
$p = $Player->getSinglePlayerData($_GET["id"]);
//var_dump($p);
if(isset($_GET["success"])){
    echo "salvestamine õnnestus";
}


?>
<?php require("../header.php"); ?>
<br><br>
<ul class="pager">
    <li class="previous"><a href="data.php"> Tagasi eelmisele lehele </a></li>
</ul>
<center>
<h2>Muuda kirjet</h2>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" >
    <input type="hidden" name="id" value="<?=$_GET["id"];?>" >
    <input type="text" name="firstname" placeholder="Eesnimi" value="<?php echo $p->firstname;?>"><br><br>
    <input type="text" name="lastname" placeholder="Perekonnanimi" value="<?php echo $p->lastname;?>"><br><br>
    <input type="text" name="shirt_number" placeholder="Särgi number" value="<?php echo $p->shirt_number;?>"><br><br>
    <br>
    <input type="submit" class="btn btn-success" name="update" value="Salvesta">
</form>

<br>
<br>
<a href="?id=<?=$_GET["id"];?>&deleted=true">Kustuta</a>
</center>
<?php require("../footer.php"); ?>