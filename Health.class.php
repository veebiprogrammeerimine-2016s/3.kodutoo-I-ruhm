<?php
class Health{
	
	private $connection;
	
	function __construct($mysqli){
		
		$this->connection=$mysqli;
	}

	function delete($id){
		$stmt=$this->connection->prepare("UPDATE HealthCondition SET deleted=NOW() WHERE id=? AND deleted IS NULL");
		$stmt->bind_param("i", $id);
		
		//kas õnnestus salvestada
		if($stmt->execute()){
			//õnnestus
			echo "Kustutamine õnnestus!";
		}
		$stmt->close();
	}
	
	function update($id, $Gender, $Age, $date, $NumberofSteps, $LandLength){
		
		
		$stmt = $this->connection->prepare("UPDATE HealthCondition SET Gender=?, Age=?, date=?, NumberofSteps=?, LandLength=? WHERE id=? AND deleted IS NULL");
		$stmt->bind_param("siiiii",$Gender, $Age, $date, $NumberofSteps, $LandLength, $id);
		
		// kas õnnestus salvestada
		if($stmt->execute()){
			// õnnestus
			echo "Salvestus õnnestus!";
		}
		
		$stmt->close();
	}
	
	function get($q, $sort, $order){
			$allowedSort=["id", "Gender", "Age", "date", "NumberofSteps", "LandLength"];
				
			if(!in_array($sort, $allowedSort)){
				$sort="id";
			}
			$orderBy="ASC";

			if($order=="DESC"){
				$orderBy="DESC";
			}
			//echo "Sorteerin: ".$sort." ".$orderBy."";
				
		//kas otsib
		if($q!=""){
			
			$stmt=$this->connection->prepare("
			SELECT id, Gender, Age, date, NumberofSteps, LandLength
			FROM HealthCondition
			WHERE deleted IS NULL
			AND (Gender LIKE ? OR Age LIKE ? OR date LIKE ? OR NumberofSteps LIKE ? OR LandLength LIKE ?)
			ORDER BY $sort $orderBy
			
			");
			$searchWord="%".$q."%";
			$stmt->bind_param("sssss", $searchWord, $searchWord, $searchWord, $searchWord, $searchWord);
			
		}else{
			$stmt=$this->connection->prepare("
				SELECT id, Gender, Age, date, NumberofSteps, LandLength
				FROM HealthCondition
				WHERE deleted IS NULL
				ORDER BY $sort $orderBy
			
			");
			
		}
	
		echo $this->connection->error;
		$stmt->bind_result($id, $Gender, $Age, $date, $NumberofSteps, $LandLength);
		$stmt->execute();
		
				//tekitan massiivi
		$result=array();
			//seni kuni on üks rida andmeid saada (10 rida=10 korda)
			while($stmt->fetch()) {
				$person=new StdClass();
				$person->id=$id;
				$person->Gender=$Gender;
				$person->Age=$Age;
				$person->date=$date;
				$person->NumberofSteps=$NumberofSteps;
				$person->LandLength=$LandLength;
				
				//echo $Color."<br>";
				array_push($result, $person);
			}
			$stmt->close();
			
			return $result;

		
	
	}

	function getSingle($edit_id){

		$stmt = $this->connection->prepare("SELECT Gender, Age, date, NumberofSteps, LandLength FROM HealthCondition WHERE id=? AND deleted IS NULL");

		$stmt->bind_param("i", $edit_id);
		$stmt->bind_result($Gender, $Age, $date, $NumberofSteps, $LandLength);
		$stmt->execute();
		
		//tekitan objekti
		$Health = new Stdclass();
		
		//saime ühe rea andmeid
		if($stmt->fetch()){
			// saan siin alles kasutada bind_result muutujaid
			$Health->Gender = $Gender;
			$Health->Age = $Age;
			$Health->date = $date;
			$Health->NumberofSteps = $NumberofSteps;
			$Health->LandLength = $LandLength;
			
			
		}else{
			// ei saanud rida andmeid kätte
			// sellist id'd ei ole olemas
			// see rida võib olla kustutatud
			header("Location: data.php");
			exit();
		}
		
		$stmt->close();
		
		
		return $Health;
		
	}
	
	function savePeople ($Gender, $Age, $date, $NumberofSteps, $LandLength){
			
			//käsk
			$stmt=$this->connection->prepare("INSERT INTO HealthCondition (Gender, Age, date, NumberofSteps, LandLength) VALUES(?,?,?,?,?)");
			
			$stmt->bind_param("siiii",$Gender, $Age, $date, $NumberofSteps, $LandLength);
			
			if($stmt->execute()) {
				echo "Salvestamine õnnestus";
				
			} else {
					echo "ERROR ".$stmt->error;
			
			}
			$stmt->close();	
	}
	
	function getAllPeople () {
			
			//käsk
			$stmt=$this->connection->prepare("
				SELECT id, Gender, Age, date, NumberofSteps, LandLength
				FROM HealthCondition
			");
			echo $this->connection->error;
			$stmt->bind_result($id, $Gender, $Age, $date, $NumberofSteps, $LandLength);
			$stmt->execute();
			
			//array("Marii", "M")
			$result=array();
			//seni kuni on üks rida andmeid saada (10 rida=10 korda)
			while($stmt->fetch()) {
				$person=new StdClass();
				$person->id=$id;
				$person->Gender=$Gender;
				$person->Age=$Age;
				$person->date=$date;
				$person->NumberofSteps=$NumberofSteps;
				$person->LandLength=$LandLength;
				
				//echo $Color."<br>";
				array_push($result, $person);
			}
			$stmt->close();
			
			return $result;
			
		}
	
	}

?>