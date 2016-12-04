<?php

  require ("../functions.php");
  require ("../class/Club.class.php");
  $Club = new Club ($mysqli);

  require("../class/Helper.class.php");
	$Helper = new Helper();
  //lisame kontrolli, kas on kasutaja sisse loginud. kui ei ole, siis
  //suunata login lehele
  //kas ?logout on aadressireal

$clubName = "";
$clubLocation = "";
$clubNameError = "";
$clubLocationError = "";
$rate = "";
$Helper = "";

if (!isset($_SESSION["userId"])){

		//suunan sisselogimise lehele
		header("Location: login3_kodune.php");
		exit();
	}

if (isset ($_GET["logout"])) {
  session_destroy();
  header ("Location: login3_kodune.php");
  exit (); // ära edasi koodi käivita, ütleb käsk "exit", seega alumisi koodiridu ei käivitata
}

$msg = "";
if(isset($_SESSION["message"])){
  $msg = $_SESSION["message"];

  //kui ühe näitame siis kustuta ära, et pärast refreshi ei näitaks
  unset($_SESSION["message"]);
}
//var_dump ($_POST);

//Kas klubi nimi ja asukoht on täidetud kontroll


if ( isset($_POST["clubName"]) &&
		 isset($_POST["clubName"]) &&
		 !empty($_POST["clubLocation"]) &&
		 !empty($_POST["clubLocation"]) &&
     !empty ($_POST["clubRate"]) &&
     !empty ($_POST["clubRate"])
   ) {

		$Club->save($Helper->cleanInput($_POST["clubName"]), $Helper->cleanInput($_POST["clubLocation"]), $Helper->cleanInput($_POST["clubRate"]));
	}



if (isset ($_GET["q"])) {
  	//kui otsib, võtame otsisõna aadressirealt
    $q = $_GET["q"];
  	//otsisõna funktsiooni sisse
} else {
     		//otsisõna on tühi (q = otsisõna)
    		$q = "";
}

$sort = "id";
	$order = "ASC";

	if(isset($_GET["sort"]) && isset($_GET["order"])) {
		$sort = $_GET["sort"];
		$order = $_GET["order"];
	}

	//otsisõna fn sisse
	$clubData = $Club->get($q, $sort, $order);

?>

<?php require("../header.php"); ?>
<h1> Andmed </h1>
<?=$msg;?>

<p>
	Tere tulemast <a href="user.php"><?=$_SESSION["userEmail"];?>!</a>
	<a href="?logout=1">Logi välja</a>
</p>


<h1> Anna hinnang klubile </h1>

<form method = "POST">

  <label> Kirjuta klubi nimi: </label>
  <input name ="clubName" type = "text" placeholder="Klubi nimi"> <?php echo $clubNameError; ?>

  <br> <br>
  <label> Kirjuta klubi asukoht: </label>

  <input  name = "clubLocation" type = "text" placeholder="Linn"> <?php echo $clubLocationError; ?>

  <br> <br>
  <label> Anna klubile hinnang:  </label>

  <br> <br>
        <?php if($rate == "1") { ?>
        <input type="radio" name="rate" value="1" checked> 1<br>
        <?php } else { ?>
        <input type="radio" name="rate" value="1" > 1<br>
        <?php } ?>

       <?php if($rate == "2") { ?>
        <input type="radio" name="rate" value="2" checked> 2<br>
        <?php } else { ?>
        <input type="radio" name="rate" value="2" > 2<br>
        <?php } ?>

        <?php if($rate == "3") { ?>
        <input type="radio" name="rate" value="3" checked> 3<br>
        <?php } else { ?>
        <input type="radio" name="rate" value="3" > 3<br>
        <?php } ?>

        <?php if($rate == "4") { ?>
        <input type="radio" name="rate" value="4" checked> 4<br>
        <?php } else { ?>
        <input type="radio" name="rate" value="4" > 4<br>
        <?php } ?>

        <?php if($rate == "5") { ?>
        <input type="radio" name="rate" value="5" checked> 5<br>
        <?php } else { ?>
        <input type="radio" name="rate" value="5" > 5<br><br>
        <?php } ?>

  <input type = "submit"  value = "SALVESTA HINNANG">

</form>





<h2>Varasemad hinnangud</h2>
<form>
	<input type="search" name="q" value="<?=$q;?>">
	<input type="submit" value="Otsi">
</form>

<?php

$html = "<table class='table table-striped'>";

$html .= "<tr>";

    $idOrder = "ASC";
    $arrow = "&darr;";
    if (isset($_GET["order"]) && $_GET["order"] == "ASC"){
         $idOrder = "DESC";
         $arrow = "&uarr;";
      }

      $html .= "<th>
            <a href='?q=".$q."&sort=id&order=".$idOrder."'>

              id ".$arrow."
              </a>
            </th>";

        $rateOrder = "ASC";
     		$arrow = "&darr;";
     		if (isset($_GET["order"]) && $_GET["order"] == "ASC"){
     			$rateOrder = "DESC";
     			$arrow = "&uarr;";

     }
     $html .= "<th>
     					<a href='?q=".$q."&sort=rate&order=".$rateOrder."'>
     						rate
     					</a>
     				</th>";
     $html .= "<th>
     					<a href='?q=".$q."&sort=name'>
     						name
     					</a>
     				 </th>";
$html .= "</tr>";

$html .= "<th>KLUBI</th>";
$html .= "<th>ASUKOHT</th>";
$html .= "<th>HINNANG</th>";

//iga liikme kohta massiivis

foreach($clubData as $c){
         		// iga klubi on $c
         		//echo $c->name."<br>";

$html .= "<tr>";
      $html .= "<td>".$c->id."</td>";
      $html .= "<td>".$c->clubName."</td>";
      $html .= "<td>".$c->clubLocation."</td>";
      $html .= "<td>".$c->clubRate."</td>";
      $html .= "</tr>";

      if ($c->clubRate == 1) {
        $html .= "<td style=' background-color: red; '>".$c->clubRate."</td>";
      }
      if ($c->clubRate == 2) {
        $html .= "<td style=' background-color: salmon; '>".$c->clubRate."</td>";
      }
      if ($c->clubRate == 3) {
        $html .= "<td style=' background-color: pink; '>".$c->clubRate."</td>";
      }

      if ($c->clubRate == 4) {
        $html .= "<td style=' background-color: thistle; '>".$c->clubRate."</td>";
      }
      if ($c->clubRate == 5) {
        $html .= "<td style=' background-color: lime; '>".$c->clubRate."</td>";
      }
      $html .= "<td><a class='btn btn-default btn-sm' href='edit.php?id=".$c->id."'><span class='glyphicon glyphicon-pencil'></span> Muuda</a></td>";
    }

  $html .= "</table>";

    echo $html;

  $listHtml = "<br><br>";

  foreach($clubData as $c){

         		$listHtml .= "<h1 style='color:".$c->clubRate."'>".$c->clubName."</h1>";
         		$listHtml .= "<p>color = ".$c->clubRate."</p>";

         	}


echo $listHtml;
?>

         <br>
         <br>
         <br>
         <br>
         <br>



<?php

//
// 				$html .= "<td>".$p->clubName."</td>";
// 				$html .= "<td>".$p->clubLocation."</td>";
//
//         if ($p->rate == 1) {
//         $html .= "<td style=' background-color: red; '>".$p->rate."</td>";
//         }
//
//         if ($p->rate == 2) {
//           $html .= "<td style=' background-color: salmon; '>".$p->rate."</td>";
//         }
//
//         if ($p->rate == 3) {
//           $html .= "<td style=' background-color: pink; '>".$p->rate."</td>";
//         }
//
//         if ($p->rate == 4) {
//           $html .= "<td style=' background-color: thistle; '>".$p->rate."</td>";
//         }
//
//         if ($p->rate == 5) {
//           $html .= "<td style=' background-color: lime; '>".$p->rate."</td>";
//         }
//
//
// 			$html .= "</tr>";
//
//
// 	$html .= "</table>";
// echo $html;
?>
