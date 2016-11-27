<?php

	require_once("../../../config.php");
	
	function getSingleCarData($edit_id){
    
        $database = "if16_case112";

		//echo "id on ".$edit_id;
		
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);
		
		$stmt = $mysqli->prepare("SELECT make, model, fuel, carcolor FROM cars WHERE id=? AND deleted is NULL");
		

		$stmt->bind_param("i", $edit_id);
		$stmt->bind_result($make, $model, $fuel, $carcolor);
		$stmt->execute();
		
		//tekitan objekti
		$car = new Stdclass();
		
		//saime �he rea andmeid
		if($stmt->fetch()){
			// saan siin alles kasutada bind_result muutujaid
			$car->make = $make;
			$car->model = $model;
			$car->fuel = $fuel;
			$car->carcolor = $carcolor;
			
			
		}else{
			// ei saanud rida andmeid k�tte
			// sellist id'd ei ole olemas
			// see rida v�ib olla kustutatud
			header("Location: data.php");
			exit();
		}
		
		$stmt->close();
		$mysqli->close();
		
		return $car;
		
	}


	function updateCar($id, $make, $model, $fuel, $carcolor){
    	
        $database = "if16_case112";

		
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);
		
		$stmt = $mysqli->prepare("UPDATE cars SET make=?, model=?, fuel=?, carcolor=? WHERE id=? AND deleted is NULL");
		$stmt->bind_param("ssssi",$make, $model, $fuel, $carcolor, $id);
		
		// kas �nnestus salvestada
		if($stmt->execute()){
			// �nnestus
			echo "salvestus �nnestus!";
		}
		
		$stmt->close();
		$mysqli->close();
		
	}
	
	function deleteCar($id, $make, $model, $fuel, $carcolor){
    	
        $database = "if16_case112";

		
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);
		
		$stmt = $mysqli->prepare("UPDATE cars SET deleted=NOW() WHERE id=? AND deleted is NULL");
		$stmt->bind_param("i",$id);
		
		// kas �nnestus salvestada
		if($stmt->execute()){
			// �nnestus
			echo "salvestus �nnestus!";
		}
		
		$stmt->close();
		$mysqli->close();
		
	}
	
	
	
?>