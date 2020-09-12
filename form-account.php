<?php 

include_once('inc/header.php');

$context = array();




// Add or update account

getUsers('all');

//Mode edit ou mode create
	
if($_GET['mode'] != 'edit'){
	$context['formMode'] = 'create';
}else{
	$context['formMode'] = $_GET['mode'];
}

//Textes en fonction du mode

if($context['formMode'] == 'create'){

	$context['text'] = array(
		'url' => 'form/account.php',
		'title' => 'Ajouter un compte',
		'bt_text' => 'Ajouter ce compte'
	); 

}elseif($context['formMode'] == 'edit'){

	$context['text'] = array(
		'url' => 'form/account.php',
		'title' => 'Modifier un compte',
		'bt_text' => 'Modifier ce compte'
	);
}

//Si c'est une édition, on récupère les infos liées

if($context['formMode'] == 'edit' && isset($_GET['user'])){
	$editUserID = $users_list[array_search($_GET['user'], array_column($users_list, 'ID'))];
}


// Render
echo $twig->render('templates/form-account.twig', $context);