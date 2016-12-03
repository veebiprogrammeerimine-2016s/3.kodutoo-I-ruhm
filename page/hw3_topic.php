<?php
	//NB minu idee järgi kasutaja muuta teemat ei saa, aint vasutseid saab muuta
	
	require("../hw3_functions.php");
	
	require("../class/Topic.class.php");
	$Topic = new Topic($mysqli);
	
	require("../class/Reply.class.php");
	$Reply = new Reply($mysqli);
	
	require("../class/Helper.class.php");
	$Helper = new Helper($mysqli);
	
	if (!isset($_SESSION["userId"])) { 
	
		header("Location:hw3_login.php");
		exit(); 
	}
	
	$newReplyError = "";
	//$reply_id = "";
	if (isset ($_GET["id"]) ){ 
		unset($_SESSION["topic_message"]);
	}
	
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
	} 
	
	if(isset($_GET["delete"]) && isset($_GET["id"])) {
 		
		//lisan userId juurde, et igaüks, kes lingile õige urli sisestab ei saaks kustutada
		//TEEMA KUSTUTATUD jääb, aga vähemalt vale kasutaja kirjeid ei mõjuta!
 		$Topic->del($_GET["id"], $_SESSION["userId"]);
 		header("Location: hw3_data.php");
 		exit();
 	}
	
	$reply_msg= "";
	if(isset($_SESSION["reply_message"])){
		$reply_msg = $_SESSION["reply_message"];
		
		//kui ühe näitame siis kustuta ära, et pärast refreshi ei näitaks
		unset($_SESSION["reply_message"]);
	}
	
	$reply_del_msg= "";
	if(isset($_SESSION["reply_del_message"])){
		$reply_msg = $_SESSION["reply_del_message"];
		
		unset($_SESSION["reply_del_message"]);
	}
	
	$replies = $Reply->addToArray($_GET["id"]);
	$topic = $Topic->get($_GET["id"]);
	//teen emaili asemel pärast user_id'ga, kuna kui emaili muuta saaks, siis enam postitust kustutada ei saaks
	//$del_topic = $Topic->checkUser($_GET["id"], $_SESSION["email"]);
	
	$del_topic = $Topic->checkUser($_GET["id"], $_SESSION["userId"]);
	//$change_reply = $Reply->checkUser($_GET["id"], $_SESSION["userId"], $reply_id); 

?>

<?php require("../header.php")?>
	<div class="topic" style="padding-left:20px;">
		<div class="row">
			<div class="col-sm-6"> 
				<h2><a href="hw3_data.php" style="text-decoration:none"> < Tagasi </a></h2>
				<p><b> <?=$reply_msg;?></b></p>
				<p><b> <?=$reply_del_msg;?></b></p>
				

				<h1><?php echo $topic->subject;?></h1>
				<p style="border:1px; border-style:solid; border-color:#a6a6a6; padding: 0.5em;">
				<?php echo $topic->content;?>
				<br><br>
				<font color="grey"><em>Teema algataja: <?php echo $topic->user;?>,  <?php echo $topic->email;?></em></font>
				<br>
				<font color="grey"><em>Lisamise kuupäev: <?php echo $topic->created;?></em></font>
				</p>
				<?php echo $del_topic; ?>
				<br><br><br>
				<?php
					$html = "<table class='table table-striped'>";
						$html .= "<tr>"; 
							$html .= "<th>Vastused</th>";
							$html .= "<th>Kasutaja</th>";
							$html .= "<th>Kasutaja e-post</th>";
							$html .= "<th>Lisamise kuupäev</th>";
							$html .= "<th></th>";
						$html .= "</tr>";

					foreach($replies as $r){
						$html .= "<tr>";
							$html .= "<td>".$r->content."</td>";
							$html .= "<td>".$r->user."</td>";
							$html .= "<td>".$r->email."</td>";
							$html .= "<td>".$r->created."</td>";
							//$html .= "<td><a href='hw3_edit.php?id=".$r->id."'>Muuda või kustuta</a></td>";
							$html .= "<td>".$change_reply = $Reply->checkUser($_GET["id"], $_SESSION["userId"], $r->id)."</td>";
						$html .= "</tr>";
					} 
					
					$html .= "</table>";
					echo $html;
				?>
			</div>
			<div style="padding-top:60px;"> 
				<h2>Vasta teemale</h2>
				<form method="POST">
					<textarea cols="40" rows="5" name="reply" <?=$newReply = ""; if (isset($_POST['reply'])) { $newContent = $_POST['reply'];}?> ><?php echo $newReply; ?></textarea> <?php echo $newReplyError; ?>
					<br><br>
					<input type="submit" value = "Postita oma vastus">
				</form>
				</p>
			</div>
		</div>
	</div>
<?php require("../footer.php")?>