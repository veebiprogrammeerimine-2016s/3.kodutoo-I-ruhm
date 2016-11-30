<?php
class Health{
	
	private $connection;
	
	function __construct($mysqli){
		
		$this->connection=$mysqli;
	}

	function get($q, $sort, $order){
			$allowedSort=["id", "Gender", "Age", "date", "NumberofSteps", "LandLength"];
				
			if(!in_array($sort, $allowedsort)){
				$sort="id";
			}
			$orderBy="ASC";

			if($order=="DESC"){
				$orderBy="DESC";
			}
			echo "Sorteerin: ".$sort." ".$orderBy."";
	
	
		//kas otsib
		if($q!=""){
			
			$stmt=$this->connection->prepare("
			SELECT id, Gender, Age, date, NumberofSteps, LandLength
			FROM HealthCondition
			ORDER BY $sort $orderBy
			
			");
			$searchWord="%".$q."%";
			$stmt->bind_param("siiii", $searchWord, $searchWord, $searchWord, $searchWord, $searchWord);
			
		}else{
			$stmt=$this->connection->prepare("
				SELECT id, Gender, Age, date, NumberofSteps, LandLength
				FROM HealthCondition
				ORDER BY $sort $orderBy
			
			");
			
		}
	
		echo $this->connection->error;
		$stmt->bind_result($id, $Gender, $Age, $date, $NumberofSteps, $LandLength);
		$stmt->execute();
		
	
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
			$this->connection->close();
			
			return $result;
			
		}
	
	}

?>