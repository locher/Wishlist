<?php

if(isset($_POST['giftID']) && $_POST['giftID'] != ''){
	$giftID = filter_var($_POST['giftID'], FILTER_SANITIZE_NUMBER_INT);
}

if(isset($giftID)){
	
	global $bdd;
	include_once('../inc/conf/config.php');

	$saveBDD = $bdd->prepare("UPDATE ".$config['db_tables']['db_gifts']." SET isReserved = 0, reservationUserID = 0 WHERE ID = ".$giftID);

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
	echo 'Réussite';
	header("location:../list.php?user=".$_POST['userID']."&statut=giftUnreserved&gift=".$giftID);
	
}else{
	echo 'Echec';
}
	