<?php

$testMode = false;

// Trouver le mode

if(isset($_POST['formMode']) && $_POST['formMode'] != ''){
	$formMode = $_POST['formMode'];
}

// Gestion Form

if(isset($_POST['userID']) && $_POST['userID'] != ''){
	$userID = $_POST['userID'];
}


if(isset($_POST['designation']) && $_POST['designation'] != ''){
	$designation = $_POST['designation'];
}

if(isset($_POST['link']) && $_POST['link'] != ''){
	$link = $_POST['link'];
}else{
	$link = '';
}

if(isset($_POST['description']) && $_POST['description'] != ''){
	$description = $_POST['description'];
}else{
	$description = '';
}

if(isset($_POST['isList']) && $_POST['isList'] != ''){
	$isList = 1;
}else{
	$isList = 0;
}

// A partir du moment où il y a au moins une designation, on peut enregistrer en BDD

if(isset($designation) && $testMode == false){
	global $bdd;
	include_once('../inc/config.php');

	//Traitement du form en mode Create
	if($formMode == 'create'){
		$saveBDD = $bdd->prepare("INSERT INTO ".$config['db_tables']['db_gifts']." (ID, userID, title, link, description, isList, isReserved, reservationUserID) VALUES (NULL, :userID, :title, :link, :description, :isList, 0, 0)");

		$saveBDD->bindParam(':userID', $userID, PDO::PARAM_STR);
		$saveBDD->bindParam(':title', $designation, PDO::PARAM_STR);
		$saveBDD->bindParam(':link', $link, PDO::PARAM_STR);
		$saveBDD->bindParam(':description', $description, PDO::PARAM_STR);
		$saveBDD->bindParam(':isList', $isList, PDO::PARAM_STR);

		if($saveBDD->execute()){
			$isBDDsuccess = true;
			echo "good";
			exit;
		}else{
			$isBDDsuccess = false;
			echo "false";
			exit;
		}
		
		$currentGift = $bdd->lastInsertId();
	}

}else{
	echo "La désignation est obligatoire.";
}

?>