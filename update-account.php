<?php 

include_once('inc/header.php');

$context = array();

//Get user infos

if(isset($_GET['user']) && $_GET['user'] != ''){
	$context['user'] = getUserInfo($_GET['user']);
}

//Mode
$context['mode'] = 'edit';

//If child, get parents
$context['account_parents'] = get_parents($_GET['user']);

//Get parents list
$context['all_parents'] = getUsers('parents');

//Content

$context['content'] = array(
	'title' => 'Modifier ce compte',
	'submit' => 'Modifier ce compte',
	'form_url' => 'form/update-account.php?userID='.$_GET['user']
);

// Render
echo $twig->render('templates/form-account.twig', $context);