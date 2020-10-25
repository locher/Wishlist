<?php

// Form management

if(isset($_POST['firstname']) && $_POST['firstname'] != ''){
	$firstname = filter_var($_POST['firstname'], FILTER_SANITIZE_STRING);
}

if(isset($_POST['birthday']) && $_POST['birthday'] != ''){
	$birthday = filter_var($_POST['birthday'], FILTER_SANITIZE_STRING);
}else{
	$birthday = NULL;
}

if(isset($_POST['size-top']) && $_POST['size-top'] != ''){
	$sizeTop = filter_var($_POST['size-top'], FILTER_SANITIZE_STRING);
}else{
	$sizeTop = NULL;
}

if(isset($_POST['size-bottom']) && $_POST['size-bottom'] != ''){
	$sizeBottom = filter_var($_POST['size-bottom'], FILTER_SANITIZE_STRING);
}else{
	$sizeBottom = NULL;
}

if(isset($_POST['size-feet']) && $_POST['size-feet'] != ''){
	$sizeFeet = filter_var($_POST['size-feet'], FILTER_SANITIZE_STRING);
}else{
	$sizeFeet = NULL;
}

if(isset($_POST['photoChoice']) && $_POST['photoChoice'] != ''){
	$photoChoice = filter_var($_POST['photoChoice'], FILTER_SANITIZE_NUMBER_INT);
}else{
	$photoChoice = rand(1, 15);
}

if(isset($_POST['isChild']) && $_POST['isChild'] != ''){
	$isChild = 1;
}else{
	$isChild = 0;
}

// A partir du moment où il y a au moins un prénom, on peut enregistrer en BDD

if(isset($firstname)){
	
	global $bdd;

	include_once('../inc/conf/config.php');

	$currentUser = filter_var($_GET['userID'], FILTER_SANITIZE_NUMBER_INT);

	$saveBDD = $bdd->prepare("UPDATE ".$config['db_tables']['db_users']." SET name = :name, isChildAccount = :child, picture = :picture, birthday_date = :birthday, size_top = :sizetop, size_bottom = :sizebottom, size_feet = :sizefeet WHERE userID = ".$currentUser."");

	$saveBDD->bindParam(':name', $firstname, PDO::PARAM_STR);
	$saveBDD->bindParam(':child', $isChild, PDO::PARAM_STR);
	$saveBDD->bindParam(':picture', $photoChoice, PDO::PARAM_STR);
	$saveBDD->bindParam(':birthday', $birthday, PDO::PARAM_STR);
	$saveBDD->bindParam(':sizetop', $sizeTop, PDO::PARAM_STR);
	$saveBDD->bindParam(':sizebottom', $sizeBottom, PDO::PARAM_STR);
	$saveBDD->bindParam(':sizefeet', $sizeFeet, PDO::PARAM_STR);

	if($saveBDD->execute()){
		$isBDDsuccess = true;
	}else{
		$isBDDsuccess = false;
	}

}else{
	echo 'Le prénom est obligatoire';
}

// Si c'est un compte enfant

if($isChild == true){
	
	if(isset($_POST['choiceParent']) && $_POST['choiceParent'] != ''){

		//Si on est en mode edit, on supprime les connexions avant de les remettre, pour pas se prendre la tête à vérifier si les connexions existent déjà

		$deleteParent = $bdd->prepare("DELETE FROM ".$config['db_tables']['db_parents']." WHERE ID_child = ".$_GET['userID']."");

		$deleteParent->execute();

		$listParent = $_POST['choiceParent'];
			
		foreach($listParent as $parent){
			$saveBDDchild = $bdd->prepare("INSERT INTO ".$config['db_tables']['db_parents']." (ID_parent, ID_child) VALUES (?, ?)");

			$saveBDDchild->bindParam(1, $parent, PDO::PARAM_STR);
			$saveBDDchild->bindParam(2, $currentUser, PDO::PARAM_STR);
			
			if($saveBDDchild->execute()){
				$isBDDsuccessChild = true;
			}else{
				$isBDDsuccessChild = false;
			}
		}
		
	}else{
		$listParent = NULL;
	}
}else{
	//Si on a pas de parent, et qu'on est en mode edit, on supprimer les potentielles liaisons déjà existantes
	$deleteParent = $bdd->prepare("DELETE FROM ".$config['db_tables']['db_parents']." WHERE ID_child = ".$_GET['userID']."");

	$deleteParent->execute();
}


// Vérifier si tout c'est bien enregistré et messages

if($isChild == true){

	if($isBDDsuccess == true && $isBDDsuccessChild == true){
		//echo 'Réussite avec enfant';
		header("location:../user.php?user=".$currentUser."&src=EditAccountOk");
		
	}else{
		//echo 'Echec avec enfant';
		header("location:../form-account.php?mode=create&src=CreateAccountFail");
	}
	
}else{
	if($isBDDsuccess == true){
		//echo 'Réussite sans enfant';	
		header("location:../user.php?user=".$currentUser."&src=EditAccountOk");
		
	}else{
		//echo 'Echec sans enfant';
		header("location:../form-account.php?mode=create&src=CreateAccountFail");
		
	}
}


?>