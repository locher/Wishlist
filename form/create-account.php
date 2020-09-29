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

if(isset($_POST['choiceParent']) && $_POST['choiceParent'] != ''){
	$isChild = 1;
}else{
	$isChild = 0;
}

// If the firstname is set, we can save into BDD

if(isset($firstname)){
	
	global $bdd;

	include_once('../inc/conf/config.php');

	$saveBDD = $bdd->prepare("INSERT INTO ".$config['db_tables']['db_users']." (userID, name, isChildAccount, picture, birthday_date, size_top, size_bottom, size_feet) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?)");

	$saveBDD->bindParam(1, $firstname, PDO::PARAM_STR);
	$saveBDD->bindParam(2, $isChild, PDO::PARAM_STR);
	$saveBDD->bindParam(3, $photoChoice, PDO::PARAM_STR);
	$saveBDD->bindParam(4, $birthday, PDO::PARAM_STR);
	$saveBDD->bindParam(5, $sizeTop, PDO::PARAM_STR);
	$saveBDD->bindParam(6, $sizeBottom, PDO::PARAM_STR);
	$saveBDD->bindParam(7, $sizeFeet, PDO::PARAM_STR);

	if($saveBDD->execute()){
		$isBDDsuccess = true;
	}else{
		$isBDDsuccess = false;
	}
	
	$currentUser = $bdd->lastInsertId();

}else{
	echo 'Le prénom est obligatoire';
}

// Si c'est un compte enfant

if($isChild == true){
	
	if(isset($_POST['choiceParent']) && $_POST['choiceParent'] != ''){

			//Si on est en mode edit, on supprime les connexions avant de les remettre, pour pas se prendre la tête à vérifier si les connexions existent déjà

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
}

// Vérifier si tout c'est bien enregistré et messages

if($isChild == true){

	if($isBDDsuccess == true && $isBDDsuccessChild == true){
		echo 'Réussite avec enfant';
		header("location:../user.php?user=".$currentUser);
		
	}else{
		echo 'Echec avec enfant';
		//header("location:../form-account.php?mode=create&src=CreateAccountFail");
	}
	
}else{
	if($isBDDsuccess == true){
		echo 'Réussite sans enfant';	
		header("location:../user.php?user=".$currentUser);
		
	}else{
		echo 'Echec sans enfant';
		//header("location:../form-account.php?mode=create&src=CreateAccountFail");
		
	}
}


?>