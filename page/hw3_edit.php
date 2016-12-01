<?php
	require("../hw3_functions.php");
	
	require("../class/Reply.class.php");
	$Reply = new Reply($mysqli);
	
	require("../class/Helper.class.php");
	$Helper = new Helper($mysqli);
	
	$topic_id= $_GET["topic"];
	//echo $topic_id;
	$reply_id= $_GET["topic"];
	
	//Tegin, et teised kasutajad, kes ei oma muutmise õigust ei saaks urlile õigeid ID'si sisestades sinna lehele
	$access = $Reply->checkAccess($_GET["topic"], $_GET["reply"], $_SESSION["userId"]);
	//echo $access;
	if ($access == "no"){
		header("Location: hw3_data.php");
	}
	
	//kunagi võiks ka teha ifDel, kui kasutajad postitusi nt taastama tahavad hakata
	
	$reply = $Reply->find($_GET["topic"], $_GET["reply"], $_SESSION["userId"]);
	
	//kas kasutaja uuendab andmeid
	if(isset($_POST["update"])){
		//echo $_POST["new_reply"];
		$Reply->update($Helper->cleanInput($_POST["new_reply"]), $_GET["reply"]);
		$Reply->updateTime($_GET["reply"]);
		$reply = $_POST["new_reply"];
	}
	
	$topic_id = $_GET["topic"];
	if(isset($_POST["delete"])){
		$Reply->del($_GET["topic"], $_GET["reply"]);
		header("Location: hw3_topic.php?id=$topic_id.php");
 		//exit();
	}
	
	$reply_change_msg= "";
	if(isset($_SESSION["reply_change_message"])){
		$reply_change_msg = $_SESSION["reply_change_message"];
		
		//kui ühe näitame siis kustuta ära, et pärast refreshi ei näitaks
		unset($_SESSION["reply_change_message"]);
	}
	
?>
<?php require("../header.php")?>

<style>
.inner-addon { 
    position: relative; 
}

/* style icon */
.inner-addon .glyphicon {
  position: absolute;
  padding: 3px;
  pointer-events: none;
  color:#cc0000;
}

/* align icon */
.left-addon .glyphicon  { left:  0px;}
.right-addon .glyphicon { right: 0px;}

/* add padding  */
.left-addon input  { padding-left:  20px; }
.right-addon input { padding-right: 30px; }
</style>

	<div class="edit" style="padding-left:20px;">
		<h2><a href="hw3_topic.php?id=<?php echo $topic_id;?>" style="text-decoration:none"> < Tagasi </a></h2>
		<p><b><?=$reply_change_msg;?></b></p>
		<h1>Muuda või kustuta oma vastus</h1>
		<form method="post" >
			<input type="hidden" name="id" value="<?=$_GET["id"];?>" > 
			<label for="sisu" >Sinu vastus</label><br>
			<textarea id="new_reply" cols="40" rows="5" name="new_reply"><?php echo $reply;?> </textarea><br><br>

			<input type="submit" type="button" class="btn btn-default btn-sm" name="update" value="Salvesta muudatus">
			<br><br>
			<div class="inner-addon left-addon">
				<span class='glyphicon glyphicon-trash'></span>
				<input type="submit" type="button" class="btn btn-default btn-xs" style="color:#cc0000;" name="delete" value="Kustuta vastus">
			</div>
		  </form>
	 </div>

<?php require("../footer.php")?>