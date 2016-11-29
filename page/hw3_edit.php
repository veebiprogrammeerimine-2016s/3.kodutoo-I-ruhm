<?php
	require("../hw3_functions.php");
	
	require("../class/Reply.class.php");
	$Reply = new Reply($mysqli);
	
	require("../class/Helper.class.php");
	$Helper = new Helper($mysqli);
	
	$topic_id= $_GET["topic"];
	//echo $topic_id;
	$reply_id= $_GET["topic"];
	
	$access = $Reply->checkAccess($_GET["topic"], $_GET["reply"], $_SESSION["userId"]);
	//echo $access;
	if ($access == "no"){
		header("Location: hw3_data.php");
	}
	
	$reply = $Reply->find($_GET["topic"], $_GET["reply"], $_SESSION["userId"]);
	
	//kas kasutaja uuendab andmeid
	if(isset($_POST["update"])){
		//echo $_POST["new_reply"];
		$Reply->update($Helper->cleanInput($_POST["new_reply"]), $_GET["reply"]);
		$Reply->updateTime($_GET["reply"]);
		$reply = $_POST["new_reply"];
	}
	
	if(isset($_POST["delete"])){
		$Reply->del($_GET["topic"], $_GET["reply"]);
	}
	
?>

<?php require("../header.php")?>
<h2><a href="hw3_topic.php?id=<?php echo $topic_id;?>" style="text-decoration:none"> < Tagasi </a></h2>
<h1>Kustuta või muuda oma vastust</h1>
<form method="post" >
	<input type="hidden" name="id" value="<?=$_GET["id"];?>" > 
  	<label for="sisu" >Sinu vastus</label><br>
	<textarea id="new_reply" cols="40" rows="5" name="new_reply"><?php echo $reply;?> </textarea><br><br>

	<input type="submit" name="update" value="Salvesta muudatus">
	<br><br>
	<input type="submit" name="delete" value="Kustuta vastus">
  </form>

<?php require("../footer.php")?>