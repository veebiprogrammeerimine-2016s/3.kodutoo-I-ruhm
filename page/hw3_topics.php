<?php
	require("../hw3_functions.php");
	
	require("../class/Topic.class.php");
	$Topic = new Topic($mysqli);
	
	require("../class/Reply.class.php");
	$Reply = new Reply($mysqli);
	
	require("../class/Helper.class.php");
	$Helper = new Helper($mysqli);
	
	$newReplyError = "";
	
	if (isset ($_POST["reply"]) ){ 
		if (empty ($_POST["reply"]) ){ 
			$newReplyError = "Palun täida väli!";
		} else {
			$newReply = $_POST["reply"];
		}
	}
	
	if (isset ($_POST["reply"]) && 
		empty($newReplyError)
		){
			$Reply->createNew($Helper->cleanInput($_POST["reply"]), $Helper->cleanInput($_GET["id"]), $_SESSION["firstName"], $_SESSION["email"], $_SESSION["userId"]); 	
			//header("Location:hw3_topics.php");
			//exit();
	} 
	
	if(isset($_GET["delete"]) && isset($_GET["id"])) {
 		
 		$Topic->del($_GET["id"]);
 		header("Location: hw3_data.php");
 		exit();
 	}
	
	$replies = $Reply->addToArray($_GET["id"]);
	$topic = $Topic->get($_GET["id"]);
	//teen emaili asemel pärast user_id'ga, kuna kui emaili muuta saaks, siis enam postitust kustutada ei saaks
	//$del_topic = $Topic->checkUser($_GET["id"], $_SESSION["email"]);
	//NB kasutaja muuta teemat ei saa, aint vasutseid saab muuta
	$del_topic = $Topic->checkUser($_GET["id"], $_SESSION["userId"]);
	$change_reply = $Reply->checkUser($_GET["id"], $_SESSION["userId"]);

?>

<?php require("../header.php")?>

<h2><a href="hw3_data.php" style="text-decoration:none"> < Tagasi </a></h2>

<h1><?php echo $topic->subject;?></h1>
<p>
<?php echo $topic->content;?>
<br><br>
<font color="grey"><em>Teema algataja: <?php echo $topic->user;?>,  <?php echo $topic->email;?></em></font>
<br>
<font color="grey"><em>Lisamise kuupäev: <?php echo $topic->created;?></em></font>
<br><br>
<?php echo $del_topic; ?>
<?php
	$html = "<table>";
		$html .= "<tr>"; // 
			$html .= "<th>Vastused</th>";
			$html .= "<th>Kasutaja</th>";
			$html .= "<th>Kasutaja e-post</th>";
			$html .= "<th>Lisamise kuupäev</th>";
		$html .= "</tr>";

	foreach($replies as $r){
		$html .= "<tr>";
			$html .= "<td>".$r->content."</td>";
			$html .= "<td>".$r->user."</td>";
			$html .= "<td>".$r->email."</td>";
			$html .= "<td>".$r->created."</td>";
			//$html .= "<td><a href='hw3_edit.php?id=".$r->id."'>Muuda või kustuta</a></td>";
			$html .= "<td>".$change_reply."</td>";
		$html .= "</tr>";
	} 
	
	$html .= "</table>";
	echo $html;
?>
<h2>Vasta teemale</h2>
<form method="POST">
	<textarea cols="40" rows="5" name="reply" <?=$newReply = ""; if (isset($_POST['reply'])) { $newContent = $_POST['reply'];}?> ><?php echo $newReply; ?></textarea> <?php echo $newReplyError; ?>
	<br><br>
	<input type="submit" value = "Postita oma vastus">
</form>
</p>
<?php require("../footer.php")?>