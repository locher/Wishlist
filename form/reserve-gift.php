<?php

if(isset($_POST['giftID']) && $_POST['giftID'] != '' && isset($_POST['sessionUser']) && $_POST['sessionUser'] != ''){
	$giftID = filter_var($_POST['giftID'], FILTER_SANITIZE_NUMBER_INT);

	//Case registred user
	if(is_numeric($_POST['sessionUser'])){
		$sessionUser = filter_var($_POST['sessionUser'], FILTER_SANITIZE_NUMBER_INT);
		$isguest = false;
	}else{
	//Case guest
		$sessionUser = filter_var($_POST['sessionUser'], FILTER_SANITIZE_STRING);
		$isguest = true;
	}
	
}

//Case registred user

if(isset($giftID) && isset($sessionUser) && $isguest == false){
	
	global $bdd;
	include_once('../inc/conf/config.php');

	$saveBDD = $bdd->prepare("UPDATE ".$config['db_tables']['db_gifts']." SET isReserved = 1, reservationUserID = ".$sessionUser." WHERE ID = ".$giftID);

	if($saveBDD->execute()){
		$isBDDsuccess = true;
	}else{
		$isBDDsuccess = false;
	}

//Case Guest

}elseif(isset($giftID) && isset($sessionUser) && $isguest == true){

	global $bdd;
	include_once('../inc/conf/config.php');

	$saveBDD = $bdd->prepare("UPDATE ".$config['db_tables']['db_gifts']." SET isReserved = 1, reservationGuestName = '".$sessionUser."' WHERE ID = ".$giftID);

	if($saveBDD->execute()){
		$isBDDsuccess = true;
	}else{
		$isBDDsuccess = false;
	}

}else{
	echo 'Erreur.';
}

// Vérifier si tout c'est bien enregistré et messages

if($isBDDsuccess == true){
	header("location:../user.php?user=".$_POST['userID']."&statut=giftReserved&gift=".$giftID);
	
}else{
	echo 'Echec';
}