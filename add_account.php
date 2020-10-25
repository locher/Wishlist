<?php 

include_once('inc/header.php');

$context['all_parents'] = getUsers('parents');

//Content

$context['content'] = array(
	'title' => 'Ajouter un compte',
	'submit' => 'Créer ce compte',
	'form_url' => 'form/create-account.php'
);

// Render
echo $twig->render('templates/form-account.twig', $context);