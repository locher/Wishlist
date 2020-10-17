<?php 

require_once 'inc/header.php';

/////////////////////////////////////////
// SESSION 
/////////////////////////////////////////

if (isset($_POST['guestname'])) {
    $_SESSION['guestName'] = filter_var($_POST['guestname'], FILTER_SANITIZE_STRING);
}

$allUsers = getUsers();

/////////////////////////////////////////
// CONTEXT & RENDER
/////////////////////////////////////////

$context['users'] = $allUsers;

// Render
echo $twig->render('templates/listusers.twig', $context);