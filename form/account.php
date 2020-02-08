<?php

$testMode = false;

if(isset($_POST['firstname']) && $_POST['firstname'] != ''){
	$firstname = $_POST['firstname'];
}

if(isset($_POST['birthday']) && $_POST['birthday'] != ''){
	$birthday = $_POST['birthday'];
}else{
	$birthday = NULL;
}

if(isset($_POST['size-top']) && $_POST['size-top'] != ''){
	$sizeTop = $_POST['size-top'];
}else{
	$sizeTop = NULL;
}

if(isset($_POST['size-bottom']) && $_POST['size-bottom'] != ''){
	$sizeBottom = $_POST['size-bottom'];
}else{
	$sizeBottom = NULL;
}

if(isset($_POST['size-feet']) && $_POST['size-feet'] != ''){
	$sizeFeet = $_POST['size-feet'];
}else{
	$sizeFeet = NULL;
}

if(isset($_POST['photoChoice']) && $_POST['photoChoice'] != ''){
	$photoChoice = $_POST['photoChoice'];
}else{
	$photoChoice = rand(1, 15);
}

if(isset($_POST['isChild']) && $_POST['isChild'] != ''){
	$isChild = 1;
}else{
	$isChild = 0;
}

// A partir du moment où il y a au moins un prénom, on peut enregistrer en BDD

if(isset($firstname) && $testMode == false){
	
	global $bdd;

	include_once('../inc/config.php');

	$saveBDD = $bdd->prepare("INSERT INTO ".$config['db_tables']['db_users']." (userID, name, isChildAccount, picture, birthday_date, size_top, size_bottom, size_feet) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?)");

	$saveBDD->bindParam(1, $firstname, PDO::PARAM_STR);
	$saveBDD->bindParam(2, $isChild, PDO::PARAM_STR);
	$saveBDD->bindParam(3, $photoChoice, PDO::PARAM_STR);
	$saveBDD->bindParam(4, $birthday, PDO::PARAM_STR);
	$saveBDD->bindParam(5, $sizeTop, PDO::PARAM_STR);
	$saveBDD->bindParam(6, $sizeBottom, PDO::PARAM_STR);
	$saveBDD->bindParam(7, $sizeFeet, PDO::PARAM_STR);

	$saveBDD->execute();
	
	$currentUser = $bdd->lastInsertId();
	
	if($saveBDD->execute()){
		$isBDDsuccess = true;
	}else{
		$isBDDsuccess = false;
	}
	
}else{
	echo 'Faut rentrer un prénom, gros';
}

// Si c'est un compte enfant

if($isChild == true){
	
	if(isset($_POST['choiceParent']) && $_POST['choiceParent'] != '' && $testMode == false){
		$listParent = $_POST['choiceParent'];
			
		foreach($listParent as $parent){
			$saveBDDchild = $bdd->prepare("INSERT INTO ".$config['db_tables']['db_parents']." (ID_parent, ID_child) VALUES (?, ?)");

			$saveBDDchild->bindParam(1, $parent, PDO::PARAM_STR);
			$saveBDDchild->bindParam(2, $currentUser, PDO::PARAM_STR);

			$saveBDDchild->execute();
			
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


// Vérifier si tout c'est bien enregistré

if($isChild == true){
	if($isBDDsuccess == true && $isBDDsuccessChild == true){
		
		//Réussite avec enfant
		header("location:../index.php?src=CreateAccountOk");
		
	}else{
		//Echec avec enfant
	}
	
}else{
	if($isBDDsuccess == true){
		//Réussite sans enfant		
		header("location:../index.php?src=CreateAccountOk");
		
	}else{
		//Echec sans enfant
		
	}
}

?>