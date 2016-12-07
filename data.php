<?php
	require("functions.php");

    //$order1 = "";
    //$cartype = "";
    //$clientname = "";
    //$washdate = "";

	//kas on sisseloginud, kui eiole siis suunata login lehele

	if (!isset($_SESSION["userID"])){

		header ("Location: login.php");
		exit ();
	}


	if (isset($_GET["logout"])){

		session_destroy();

		header("Location: login.php");
        exit ();
	}

	// ei ole tyhjad v2ljad
    if ( isset($_POST["order1"]) &&
    isset($_POST["cartype"]) &&
    isset($_POST["clientname"]) &&
    isset($_POST["washdate"]) &&
    !empty($_POST["order1"]) &&
    !empty($_POST["cartype"]) &&
    !empty($_POST["clientname"]) &&
    !empty($_POST["washdate"])
    ) {
        $order1 = ($_POST["order1"]);
        $cartype = ($_POST["cartype"]);
        $clientname = ($_POST["clientname"]);
        $washdate = ($_POST["washdate"]);

        carWash($_POST["order1"], $_POST["cartype"], $_POST["clientname"], $_POST["washdate"]);


    }

    $_POST["order1"] = cleanInput($_POST["order1"]);
    $_POST["cartype"] = cleanInput($_POST["cartype"]);
    $_POST["clientname"] = cleanInput($_POST["clientname"]);
    $_POST["washdate"] = cleanInput($_POST["washdate"]);
    $carWash = getWashData();


    //echo "<pre>";
    //var_dump($people);
    //echo"</pre>";
?>
<h1>Autopesula - MobileSPA</h1>
<p>
	Tere tulemast! <?=htmlspecialchars($_SESSION["email"]);?>!
	<a href="?logout=1">Log out</a>
</p>
<h2>Esita oma tellimus.</h2>
    <form method = "POST">
        <lable>Broneeri aeg</lable><br>
        <style>
            table, th, td{
                border: 1px solid black;
                border-collapse: collapse;
            }
            th, td {
                padding: 10px;

            }
        </style>
        <br>
        <label>Sinu nimi</label><br>
        <input name="clientname" type="text" placeholder="Sisesta enda nimi"><br>
        <br>
        <lable>Vali pakett</lable><br>
        <select name = "order1" type = "order1">
            <option value="Tavaline survepesu">Tavaline survepesu</option>
            <option value="Põhjalik survepesu koos leotusega">Põhjalik survepesu koos leotusega</option>
            <option value="Detaili poleerimine (hind sõltub detailist)">Detaili poleerimine (hind sõltub detailist)</option>
            <option value="Terve auto poleerimine">Terve auto poleerimine</option>
            <option value="Sisepesu koos nahahooldusega">Sisepesu koos nahahooldusega</option>
            <option value="Põhjalik sisepesu koos põhjaliku välispesuga">Põhjalik sisepesu koos põhjaliku välispesuga</option>
            <option value="Põhjalik sisepesu koos põhjaliku välispesuga ja terve auto poleerimine">Põhjalik sisepesu koos põhjaliku välispesuga ja terve auto poleerimine</option>
        </select>
        <br><br>
        <lable>Vali auto tüüp</lable><br>
        <select name = "cartype" type = "cartype">
            <option value="Luukpära">Luukpära</option>
            <option value="Sedaan">Sedaan</option>
            <option value="Universaal">Universaal</option>
            <option value="Mahtuniversaal">Mahtuniversaal</option>
            <option value="Maastur">Maastur</option>
            <option value="Kaubik">Kaubik</option>
        </select>
        <br><br>
        <lable>Vali oma autopesu jaoks sobiv päev.</lable><br>
        <input type="date" name="washdate">
        <br><br>
        <input type="submit" value="Broneeri">
    </form>
<!--h3>Archive</h3-->

<?php


    $html = "<table style='width: 100%'>";

        $html .= "<tr>";
            $html .= "<th>ID</th>";
            $html .= "<th>Tellimus</th>";
            $html .= "<th>Tüüp</th>";
            $html .= "<th>Broneeringu aeg</th>";
            $html .= "<th>Klient</th>";
        $html .= "<tr>";

    foreach ($carWash as $w) {
        $html .= "<tr>";
            $html .= "<td>".$w->id."</td>";
            $html .= "<td>".$w->order1."</td>";
            $html .= "<td>".$w->cartype."</td>";
            $html .= "<td>".$w->washdate."</td>";
            $html .= "<td>".$w->clientname."</td>";
        $html .= "<tr>";

    }



    echo $html;

?>
