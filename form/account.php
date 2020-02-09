<?php

$testMode = false;

// Trouver le mode

if(isset($_POST['formMode']) && $_POST['formMode'] != ''){
	$formMode = $_POST['formMode'];
}

// Gestion form

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

	if(isset($formMode) && $formMode != ''){
		if($formMode == 'edit'){
			//Traitement BDD en mode EDIT

			$currentUser = $_POST['userID'];

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
	

		}elseif($formMode == 'create'){
			//Traitement BDD en mode CREATE

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


		}
	}

}else{
	echo 'Le prénom est obligatoire';
}

// Si c'est un compte enfant

if($isChild == true){
	
	if(isset($_POST['choiceParent']) && $_POST['choiceParent'] != '' && $testMode == false){

			//Si on est en mode edit, on supprime les connexions avant de les remettre, pour pas se prendre la tête à vérifier si les connexions existent déjà

			if($formMode == 'edit'){
				$deleteParent = $bdd->prepare("DELETE FROM ".$config['db_tables']['db_parents']." WHERE ID_child = ".$_POST['userID']."");

				$deleteParent->execute();
			}

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
	if($formMode == 'edit'){
		$deleteParent = $bdd->prepare("DELETE FROM ".$config['db_tables']['db_parents']." WHERE ID_child = ".$_POST['userID']."");

		$deleteParent->execute();
	}
}


// Vérifier si tout c'est bien enregistré et messages

// En mode Création

if($formMode == 'create'){

	if($isChild == true){

		if($isBDDsuccess == true && $isBDDsuccessChild == true){
			//Réussite avec enfant
			header("location:../index.php?src=CreateAccountOk&user=".$firstname);
			
		}else{
			//Echec avec enfant
			header("location:../form-account.php?mode=create&src=CreateAccountFail");
		}
		
	}else{
		if($isBDDsuccess == true){
			//Réussite sans enfant		
			header("location:../index.php?src=CreateAccountOk&user=".$firstname);
			
		}else{
			//Echec sans enfant
			header("location:../form-account.php?mode=create&src=CreateAccountFail");
			
		}
	}
}else if($formMode == 'edit'){
	//En mode édition

	if($isChild == true){

		if($isBDDsuccess == true && $isBDDsuccessChild == true){
			//Réussite avec enfant
			header("location:../user.php?user=".$_POST['userID']."&src=EditAccountOk");
			
		}else{
			//Echec avec enfant
			header("location:../form-account.php?mode=create&src=CreateAccountFail");
		}
		
	}else{
		if($isBDDsuccess == true){
			//Réussite sans enfant		
			header("location:../user.php?user=".$_POST['userID']."&src=EditAccountOk");
			
		}else{
			//Echec sans enfant
			header("location:../form-account.php?mode=create&src=CreateAccountFail");
			
		}
	}

}

?>