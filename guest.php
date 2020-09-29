<?php 

include_once('inc/header.php');

session_start();
$_SESSION['userID'] = 'guest';

$context['users'] = getUsers('all');

// Render
echo $twig->render('templates/guest.twig', $context);