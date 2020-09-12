<?php 

include_once('inc/header.php');

$context = array();

session_start();
$_SESSION['userID'] = 'guest';

$context['users'] = getUsers('all');

// Render
echo $twig->render('templates/guest.twig', $context);