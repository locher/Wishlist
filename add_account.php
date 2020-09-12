<?php 

include_once('inc/header.php');

$context = array();

$context['parents'] = getUsers('parents');

// Render
echo $twig->render('templates/add-account.twig', $context);