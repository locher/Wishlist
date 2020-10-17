<?php 

include_once('inc/header.php');

//$context['users'] = getUsers('all');

// Render
echo $twig->render('templates/guest.twig', $context);