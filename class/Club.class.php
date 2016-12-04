<?php
class Club {

	private $connection;

	function __construct($mysqli){

		$this->connection = $mysqli;

	}
	/*TEISED FUNKTSIOONID */
	function delete($id){
		$stmt = $this->connection->prepare("UPDATE goingClubbing SET deleted=NOW() WHERE id=? AND deleted IS NULL");
		$stmt->bind_param("i",$id);

		// kas õnnestus salvestada
		if($stmt->execute()){
			// õnnestus
			echo "kustutamine õnnestus!";
		}

		$stmt->close();


	}

	function get ($q, $sort, $order) {

		//defineerin lubatud tulbad
		$allowedSort = ["id", "clubName", "clubLocation", "clubRate"];
		if (!in_array ($sort, $allowedSort)) {
			//kui ei ole lubatud tulp, siis sorteerib id järgi
			$sort = "id";

		}


		$orderBy = "ASC";


		if ($order == "DESC") {
				$orderBy = "DESC";

	 }

	 echo "Sorteerin: ".$sort. " ".$orderBy." ";

		//kas otsib
		if ($q != "") {

			// ei otsi
				echo "Otsib : ".$q;
			$stmt = $this->connection->prepare("
				SELECT id, clubName, clubLocation, clubRate
				FROM goingClubbing
				WHERE deleted IS NULL
				AND (clubName LIKE ? OR clubLocation LIKE ? OR clubRate LIKE ?)
				ORDER BY $sort $orderBy
			");

			$searchWord = "%".$q. "%";
			$stmt->bind_param("ss", $searchWord, $searchWord);

		}else {


			//otsib
			$stmt = $this->connection->prepare("
				SELECT id, clubName, clubLocation, clubRate
				FROM goingClubbing
				WHERE deleted IS NULL
				ORDER BY $sort $orderBy
			");
		}

		echo $this->connection->error;


		$stmt->bind_result($id, $clubName, $clubLocation, $clubRate);
		$stmt->execute();


		//tekitan massiivi
		$result = array();

		// tee seda seni, kuni on rida andmeid
		// mis vastab select lausele
		while ($stmt->fetch()) {

			//tekitan objekti
			$club = new StdClass();

			$club->id = $id;
			$club->clubName = $clubName;
			$club->clubLocation = $clubLocation;
      $club->clubRate = $clubRate;

			//echo $plate."<br>";
			// iga kord massiivi lisan juurde nr märgi
			array_push($result, $club);
		}

		$stmt->close();


		return $result;
	}

	function getSingle($edit_id){
		$stmt = $this->connection->prepare("SELECT clubName, clubLocation, clubRate FROM goingClubbing WHERE id=? AND deleted IS NULL");
		$stmt->bind_param("i", $edit_id);
		$stmt->bind_result($name, $location, $rate);
		$stmt->execute();

		//tekitan objekti
		$club = new Stdclass();

		//saime ühe rea andmeid
		if($stmt->fetch()){
			// saan siin alles kasutada bind_result muutujaid
       $club->clubName = $name;
       $club->clubLocation = $location;
       $club->clubRate = $rate;




		}else{
			// ei saanud rida andmeid kätte
			// sellist id'd ei ole olemas
			// see rida võib olla kustutatud
			header("Location: data3.php");
			exit();
		}

		$stmt->close();


		return $club;

	}
	function save ($clubName, $clubLocation, $clubRate) {

		$stmt = $this->connection->prepare("INSERT INTO goingClubbing (clubName, clubLocation,  clubRate) VALUES (?, ?, ?)");

		echo $this->connection->error;

		$stmt->bind_param("ssi", $clubName, $clubLocation, $clubRate);

		if($stmt->execute()) {
			echo "salvestamine õnnestus";
		} else {
		 	echo "ERROR ".$stmt->error;
		}

		$stmt->close();


	}

	function update($id, $clubName, $clubLocation, $clubRate){

		$stmt = $this->connection->prepare("UPDATE goingClubbing SET clubName=?, clubLocation=?, clubRate=? WHERE id=? AND deleted IS NULL");
		$stmt->bind_param("ssi",$clubName, $clubLocation, $clubRate);

		// kas õnnestus salvestada
		if($stmt->execute()){
			// õnnestus
			echo "salvestus õnnestus!";
		}

		$stmt->close();


	}

}
?>
