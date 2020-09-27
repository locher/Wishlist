<?php


// Gestion Form

if(isset($_POST['userID']) && $_POST['userID'] != ''){
	$userID = filter_var($_POST['userID'], FILTER_SANITIZE_NUMBER_INT);
}

if(isset($_POST['designation']) && $_POST['designation'] != ''){
	$designation = filter_var($_POST['designation'], FILTER_SANITIZE_STRING);
}

if(isset($_POST['link']) && $_POST['link'] != ''){
	$link = filter_var($_POST['link'], FILTER_SANITIZE_URL);
}else{
	$link = '';
}

if(isset($_POST['description']) && $_POST['description'] != ''){
	$description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
}else{
	$description = '';
}

if(isset($_POST['isList']) && $_POST['isList'] != ''){
	$isList = 1;
}else{
	$isList = 0;
}

// A partir du moment où il y a au moins une designation, on peut enregistrer en BDD

if(isset($designation) && $designation != ''){
	global $bdd;
	include_once('../inc/conf/config.php');

	//Traitement du form en mode Create

	$saveBDD = $bdd->prepare("INSERT INTO ".$config['db_tables']['db_gifts']." (ID, userID, title, link, description, isList, isReserved, reservationUserID) VALUES (NULL, :userID, :title, :link, :description, :isList, 0, 0)");

	$saveBDD->bindParam(':userID', $userID, PDO::PARAM_STR);
	$saveBDD->bindParam(':title', $designation, PDO::PARAM_STR);
	$saveBDD->bindParam(':link', $link, PDO::PARAM_STR);
	$saveBDD->bindParam(':description', $description, PDO::PARAM_STR);
	$saveBDD->bindParam(':isList', $isList, PDO::PARAM_STR);

	if($saveBDD->execute()){
		$isBDDsuccess = true;
		header("location:../user.php?user=".$userID);
	}else{
		$isBDDsuccess = false;
		echo "Il y a eu un soucis, déso :(";
	}
	
	$currentGift = $bdd->lastInsertId();


}else{
	echo "La désignation est obligatoire.";
}


?>