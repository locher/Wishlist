<?php

if(isset($_POST['userID']) && $_POST['userID'] != ''){

	global $bdd;
	include_once('../inc/config.php');

	$userID = $_POST['userID'];

	//Supprimer le compte

	$deleteAccount = $bdd->prepare("DELETE FROM ".$config['db_tables']['db_users']." WHERE userID = ".$userID." ");

	if($deleteAccount->execute()){
		$deleteAccountStatut = true;
	}else{
		$deleteAccountStatut = false;

		echo 'delete account a chié';
	}

	//Supprimer les potentielles associations avec les parents/enfants

	$deleteParents = $bdd->prepare("DELETE FROM ".$config['db_tables']['db_parents']." WHERE ID_parent = ".$userID." OR ID_child = ".$userID."");

	if($deleteParents->execute()){
		$deleteParentsStatut = true;
	}else{
		$deleteParentsStatut = false;

		echo 'delete parent a chié';
	}

	//Supprimer les cadeaux 

	$deleteGifts = $bdd->prepare("DELETE FROM ".$config['db_tables']['db_gifts']." WHERE userID = ".$userID."");

	if($deleteGifts->execute()){
		$deleteGiftsStatut = true;
	}else{
		$deleteGiftsStatut = false;

		echo 'delete cadeaux a chié';
	}


	// Affichage retour

	if($deleteAccountStatut == true && $deleteParentsStatut == true && $deleteGiftsStatut == true){

		header("location:../index.php?src=DeleteAccountOk&user=".$_POST['userName']);

	}else{
		echo "La suppression a échoué";
	}

}

?>
