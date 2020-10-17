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

if(isset($_POST['currentGift']) && $_POST['currentGift'] != ''){
	$currentGift = filter_var($_POST['currentGift'], FILTER_SANITIZE_NUMBER_INT);
}

// If we have a designation & a gift ID

if(isset($designation) && $designation != '' && isset($currentGift) && $currentGift != ''){

	global $bdd;
	include_once('../inc/conf/config.php');

	//Traitement du form en mode Create

	$saveBDD = $bdd->prepare("UPDATE ".$config['db_tables']['db_gifts']." SET title = :title, link = :link, description = :description, isList = :isList WHERE ID = :giftID");

	$saveBDD->bindParam(':title', $designation, PDO::PARAM_STR);
	$saveBDD->bindParam(':link', $link, PDO::PARAM_STR);
	$saveBDD->bindParam(':description', $description, PDO::PARAM_STR);
	$saveBDD->bindParam(':isList', $isList, PDO::PARAM_STR);
	$saveBDD->bindParam(':giftID', $currentGift, PDO::PARAM_STR);

	if($saveBDD->execute()){
		echo $userID;
		header("location:../user.php?user=".$userID."&statut=giftUpdated&gift=".$currentGift);
	}else{
		echo "Il y a eu un soucis, déso :(";
	}
	
}else{
	echo "La désignation est obligatoire.";
}


?>