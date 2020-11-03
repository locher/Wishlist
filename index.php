<?php 

session_start();
$_SESSION['userID'] = null;
session_destroy();

require_once 'inc/header.php';

$context['users'] = getUsers('parents');

// Popup after account creation

if (isset($_GET['src']) && isset($_GET['user']) && $_GET['user'] != '') {

    if ($_GET['src'] == 'CreateAccountOk') {

        $context['pagetype'] = array(
        'type' => 'creation',
        'user' => $_GET['user']
        );

    } else if ($_GET['src'] == 'DeleteAccountOk') {

        $context['pagetype'] = array(
        'type' => 'delete',
        'user' => $_GET['user']
        );
    }
}

/////////////////////////////////////////
// FORM MESSAGES
/////////////////////////////////////////

//Delete account
if (isset($_GET['src']) && isset($_GET['user']) && $_GET['src'] == 'DeleteAccountOk' && $_GET['user'] != '') {
    $context['message']['object'] = filter_var($_GET['user'], FILTER_SANITIZE_STRING);
    $context['message']['prefixe'] = 'Le compte';
    $context['message']['suffixe'] = 'a bien été supprimé !';
}

// Render
echo $twig->render('templates/frontpage.twig', $context);