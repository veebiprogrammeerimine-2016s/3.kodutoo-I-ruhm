<?php
	//kui midagi ei tööta, kommenteeri headerid välja!!! Siis kui viga siis näitab, muidu lihtsalt suunab
	
	require("../hw3_functions.php");
	
	require("../class/Topic.class.php");
	$Topic = new Topic($mysqli);
	
	require("../class/Helper.class.php");
	$Helper = new Helper($mysqli);
	
	$newHeadlineError = "";
	$newContentError = "";
	
	$newHeadline = "";	
	
	$sort = "topic";
	$order = "ASC";
	
	//kas on sisse loginud, kui ei ole, siis suunata login lehele
	
	if (!isset($_SESSION["userId"])) { //kui ei ole session userId, suuna login lehele 
	//ehk data.php sisestades ribale, pole sisse logind, suunadb login.php lehele
		
		header("Location:hw3_login.php");
		exit(); // If you don't put exit() after your header('Location: ...') your script may continue resulting in unexpected behaviour. This may for example result in content being disclosed that you actually wanted to prevent with the redirect.
	}
	
	//kas ?logout on aadressireal
	if (isset($_GET["logout"])) {
		
		session_destroy();
		header("Location:hw3_login.php");
		exit();
	}
	
	if (isset ($_POST["headline"]) ){ 
		if (empty ($_POST["headline"]) ){ 
			$newHeadlineError = "See väli on kohustuslik!";
		} else {
			$newHeadline = $_POST["headline"];
		}
	}
	
	if (isset ($_POST["content"]) ){ 
		if (empty ($_POST["content"]) ){ 
			$newContentError = "See väli on kohustuslik!";
		} else {
			$newContent = $_POST["content"];
		}
	}
	
	if (isset ($_POST["headline"]) && 
		isset ($_POST["content"]) && 
		/*!empty ($_POST["headline"]) && 
		!empty ($_POST["content"])*/
		empty($newHeadlineError)&&
		empty($newContentError)
		){
			$Topic->createNew ($Helper->cleanInput($_POST["headline"]), $Helper->cleanInput($_POST["content"]), $_SESSION["firstName"], $_SESSION["email"], $_SESSION["userId"]);	
			header("Location:hw3_data.php");
			exit();
	} 
	
	//kas keegi otsib
	if(isset($_GET["q"])){
		//Kui otsib, võtame otsisõna aadressirealt
		$q = $_GET["q"];
		//otsisõna funktsiooni sisse
	} else {
		//otsisõna tühi
		$q = "";
	}
	
	if(isset($_GET["sort"]) && isset($_GET["order"])) {
		$sort = $_GET["sort"];
		$order = $_GET["order"];
	}
	
	$topics = $Topic->addToArray($q, $sort, $order);
	
	$sort_name = "";
	if(!isset($_GET["sort"])){
		$sort_name = "teema";
	} else {
		if($_GET["sort"] == "topic"){
			$sort_name = "teema";
		}
		if($_GET["sort"] == "user"){
			$sort_name = "kasutaja";
		}
		if($_GET["sort"] == "email"){
			$sort_name = "e-posti";
		}
		if($_GET["sort"] == "created"){
			$sort_name = "lisamise kuupäeva";
		}
	}
?>

<?php require("../header.php")?>
<div class="data" style="padding-left:20px;"> 
<h1>Data</h1>
<p>
	Tere tulemast <?=$_SESSION["firstName"];?>!
	<a href="?logout=1">Logi välja</a>
<p>
<h2>Loo uus postitus</h2>
<form method="POST">
	<label>Pealkiri:</label>
	<input type="text" name="headline" value="<?=$newHeadline;?>"> <?php echo $newHeadlineError; ?>
	<br><br>
	<label>Sisu:</label>
	<textarea cols="40" rows="5" name="content" <?=$newContent = ""; if (isset($_POST['content'])) { $newContent = $_POST['content'];}?> ><?php echo $newContent; ?></textarea> <?php echo $newContentError; ?> <!--Textareal pole eraldi value, sinna sisse kirjutada-->
	<br><br>
	<input type="submit" value = "Postita">
</form>

<h1>Foorum</h1>
<form>
	<input type="search" name="q" value="<?=$q;?>"> 
	<input type="submit" value="Otsi">
</form>
<p>
Sorteerimine <?=$sort_name;?> järgi.
</p>
<p>
<?php
	$html = "<table class='table table-striped table-hover'>";
		$html .= "<tr>"; 
			$topicOrder = "ASC";
			$userOrder = "ASC";
			$emailOrder = "ASC";
			$dateOrder = "ASC";
			$topicArrow = "&rarr;";
			$userArrow = "&rarr;";
			$emailArrow = "&rarr;";
			$dateArrow = "&orarr;";
			
			if (isset($_GET["sort"]) && $_GET["sort"] == "topic") {
				if (isset($_GET["order"]) && $_GET["order"] == "ASC") {
					$topicOrder="DESC"; 
					$topicArrow = "&larr;";
				}
			}
			
			if (isset($_GET["sort"]) && $_GET["sort"] == "user") {
				if (isset($_GET["order"]) && $_GET["order"] == "ASC") {
					$userOrder="DESC";
					$userArrow = "&larr;";
				}
			}
			
			if (isset($_GET["sort"]) && $_GET["sort"] == "email") {
				if (isset($_GET["order"]) && $_GET["order"] == "ASC") {
					$emailOrder="DESC"; 
					$emailArrow = "&larr;";
				}
			}
			
			if (isset($_GET["sort"]) && $_GET["sort"] == "created") {
				if (isset($_GET["order"]) && $_GET["order"] == "ASC") {
					$dateOrder="DESC";
					$dateArrow = "&olarr;";					
				}
			}
		
		$html .= "<th>
			<a href='?q=".$q."&sort=topic&order=".$topicOrder."' style='text-decoration:none'>
			<font size='4'>Teema</font><br><font size='2'>A</font>".$topicArrow."</th>";
			$html .= "<th>
			<a href='?q=".$q."&sort=user&order=".$userOrder."' style='text-decoration:none'>
			<font size='4'>Kasutaja</font><br><font size='2'>A</font>".$userArrow."</th>";
			$html .= "<th>
			<a href='?q=".$q."&sort=email&order=".$emailOrder."' style='text-decoration:none'>
			<font size='4'>Kasutaja e-post</font><br><font size='2'>A</font>".$emailArrow."</th>";
			$html .= "<th>
			<a href='?q=".$q."&sort=created&order=".$dateOrder."' style='text-decoration:none'>
			<font size='4'>Lisamise kuupäev</font><br><font size='2'>&#128336;</font>".$dateArrow."</th>";
		$html .= "</tr>";


	foreach($topics as $t){
		$html .= "<tr>";
			//$html .= "<td> <a href='#heading' onclick='changeTitle()'>".$t->subject."</a></td>";
			$html .= "<td font size='20'><a href='hw3_topic.php?id=".$t->id."' style='text-decoration:none'><font size='4'>".$t->subject."</font></a></td>";
			$html .= "<td>".$t->user."</td>";
			$html .= "<td>".$t->email."</td>";
			$html .= "<td>".$t->created."</td>";
		$html .= "</tr>";
	} 
	
	$html .= "</table>";
	echo $html;
	
	$headingName = "";
	//$html = "<table border='1'>";
	//$html = "<table border=\"1\">"; \ ei lõhu ära 
	
	/*$html = "<table>";
		$html .= "<tr>"; 
			$html .= "<th>Teema</th>";
			$html .= "<th>Sisu</th>";
		$html .= "</tr>";

	foreach($replies as $r){
		$html .= "<tr>";
			$html .= "<td>".$r->topic."</td>";
			$html .= "<td>".$r->content."</td>";
		$html .= "</tr>";
	} 
	$html .= "</table>";
	//echo $html;
	$contentTable = $html;*/
?>
<br>
<!--<button onclick="addContent()">Näita teemade sisu</button>
<button onclick="removeContent()">Peida teemade sisu</button>
</p>-->

<h1 id="heading"><span id="newHeading"></span></h1>
<p id="content">
<script>
	function addContent(){
		/*<?php $headingName =  $_SESSION["subject"];?> */
		document.getElementById('newHeading').innerHTML = 'Teemade sisu ';
		document.getElementById('content').innerHTML = '<?php echo $contentTable ?> ';
		}
	function removeContent(){
		document.getElementById('newHeading').innerHTML = '';
		document.getElementById('content').innerHTML = '';
		}
</script>
</p>
</div>
<?php require("../footer.php")?>
