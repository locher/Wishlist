<?php 

include_once('inc/header.php');

$context = array();

//Get user infos

if(isset($_GET['user']) && $_GET['user'] != ''){
	$context['user'] = getUserInfo($_GET['user']);
}

//If child, get parents
$context['current_parents'] = get_parents($_GET['user']);
d($context['current_parents']);

//Get parents list
$context['parents'] = getUsers('parents');

d($context['user']);

// Render
echo $twig->render('templates/add-account.twig', $context);