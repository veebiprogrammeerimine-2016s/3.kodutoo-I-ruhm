<?php
require("../config.php");
require("functions.php");
require("helper.class.php");
//require("user.class.php");
require("book.class.php");

	$books_class = new books($mysqli);
	$Helper = new Helper();
	
	// kas on sisseloginud, kui ei ole siis
	// suunata login lehele
	if (!isset ($_SESSION["userID"])) {
		
		header("Location: login.php");
		exit();
		
	}
	
	//kas ?logout on aadressireal
	if (isset($_GET["logout"])) {
		
		session_destroy();
		
		header("Location: login.php");
		exit();
		
	}
	
	// ei ole tühjad väljad mida salvestada
	if ( isset($_POST["book"]) &&
		 isset($_POST["autor"]) &&
		 isset ($_POST ["rating"]) &&
		 !empty($_POST["book"]) &&
		 !empty($_POST["autor"]) &&
		 !empty ($_POST["rating"]) &&
		 !empty ($_POST["notes"])
	  ) {
		$book = $Helper->cleanInput($_POST["book"]);
		$books_class->insert($book, $Helper->cleanInput($_POST["autor"]), $_POST["rating"], $_POST["notes"]);
	}

	// aadress
	if( isset($_POST["search"])){

		$content = $Helper->cleanInput($_POST["content"]);
		$order = $Helper->cleanInput($_POST["order"]);

		if( isset($_POST["content"]) &&
			isset($_POST["order"]) &&
			!empty($_POST["content"]) &&
			!empty($_POST["order"])
		){

			$people = $books_class->get("true", $content, $order, $sort);

		}

		if(empty($_POST["content"])){

			echo "<br />Viga otsingus! Sisesta otsingusõna või number!<br />";

			$people = $books_class->get("false", "", "ASC", "id"); }


	} else {

		$people = $books_class->get("false", "", "ASC", "id");

	}
	
?>
<h1>Data</h1>
<p>
	Tere tulemast <?=$_SESSION["email"];?>!
	<a href="?logout=1">Logi välja</a>
</p> 

<h1>Salvesta raamat</h1>
<form method="POST">
			
	<label>Raamatu pealkiri ja autor</label><br>
	<input type="text" name="book" placeholder="<?php if(!empty($_POST["book"])) {
			echo $_POST["book"];
		} else {
			echo "book";
		}?>" > 
	<input type="text" name="autor" placeholder="<?php if(!empty($_POST["autor"])) {
			echo $_POST["autor"];
		} else {
			echo "autor";
		}?>" > 
	<br><br>
	<textarea name="notes" placeholder="<?php if(!empty($_POST["notes"])) {
			echo $_POST["notes"];
		} else {
			echo "Märkmed";
		}?>"></textarea>
	

	

	<br><br>
	<label>Hinnang</label><br>
	 <fieldset id='demo1' class="rating">
                        <input class="stars" type="radio" id="star5" name="rating" value="5" />
                        <label class = "5" for="star5" title="Awesome - 5 stars"></label>5
                        <input class="stars" type="radio" id="star4" name="rating" value="4" />
                        <label class = "4" for="star4" title="Pretty good - 4 stars"></label>4
                        <input class="stars" type="radio" id="star3" name="rating" value="3" />
                        <label class = "3" for="star3" title="Meh - 3 stars"></label>3
                        <input class="stars" type="radio" id="star2" name="rating" value="2" />
                        <label class = "2" for="star2" title="Kinda bad - 2 stars"></label>2
                        <input class="stars" type="radio" id="star1" name="rating" value="1" />
                        <label class = "1" for="star1" title="Sucks big time - 1 star"></label>1

                    </fieldset> 
	
	<br><br>
	<input type="submit" value="Salvesta">
	
</form>

<h2>Otsing</h2>
<form method="POST">

	<input type="text" name="content" placeholder="Otsisõna" <?php if(!empty($_POST["content"])){ echo "value='".$_POST["content"]."'"; } ?>/>
	<label>Järjesta
		<select name="cond">
			<option value="id" <?php if(!empty($_POST["cond"])){ if($_POST["cond"] == "id"){ echo "selected='selected'"; } } ?>>ID</option>
			<option value="book" <?php if(!empty($_POST["cond"])){ if($_POST["cond"] == "book"){ echo "selected='selected'"; } } ?>>RAAMAT</option>
			<option value="autor" <?php if(!empty($_POST["cond"])){ if($_POST["cond"] == "autor"){ echo "selected='selected'"; } } ?>>AUTOR</option>
			<option value="rating" <?php if(!empty($_POST["cond"])){ if($_POST["cond"] == "rating"){ echo "selected='selected'"; } } ?>>RATING</option>
		</select>
		järgi
	</label>
	<label>
		<select name="order">
			<option value="ASC" <?php if(!empty($_POST["order"])){ if($_POST["order"] == "ASC"){ echo "selected='selected'"; } } ?>>Kasvavas</option>
			<option value="DESC" <?php if(!empty($_POST["order"])){ if($_POST["order"] == "DESC"){ echo "selected='selected'"; } } ?>>Kahanevas</option>
		</select>
		järjekorras
	</label>
	<button type="submit" name="search" value="search">Otsi</button>

</form>


<h2>Loetud raamatute nimekiri</h2>
<form method="POST">
<?php
	
	$html = "<table>";
		$html .= "<tr>";
			$html .= "<th>id</th>";
			$html .= "<th>Book</th>";
			$html .= "<th>Autor</th>";
			$html .= "<th>Rating</th>";
			$html .= "<th>Märkmed</th>";
			$html .= "<th>Toimingud</th>";
		$html .= "</tr>";

		foreach($people as $p){
			$html .= "<tr>";
				$html .= "<td>".$p->id."</td>";
				$html .= "<td>".$p->book."</td>";
				$html .= "<td>".$p->autor."</td>";
				$html .= "<td>".$p->rating."</td>";
				$html .= "<td>".$p->notes."</td>";
				$html .= "<td><a href='edit.php?id=".$p->id."'>MUUDA VÕI KUSTUTA</a></td>";
				
			$html .= "</tr>";	
		}

	$html .= "</table>";
	echo $html;

?>
</form>