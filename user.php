<?php 

include_once('inc/header.php');

//Get user ID and and a cookie with it
if(isset($_GET['user']) && $_GET['user'] !=''){
	$userID = $_GET['user'];
	$_SESSION['userID'] = $userID;
}

// Get all user exept the current one
$allUsers = getUsers();

// All users except the current one
$context['users'] = array_filter(
    $allUsers,
    function ($e) use (&$userID) {
        return $e->ID != $userID;
    }
);

// Current user infos
$context['currentUser'] = array_filter(
    $allUsers,
    function ($e) use (&$userID) {
        return $e->ID == $userID;
    }
);

//Get the user gifts
$context['currentUser']['gifts'] = getGifts($userID);

//Liste of potential childs
$context['children'] = get_children($userID);

//Gift just added message
if(isset($_GET['statut']) && isset($_GET['gift']) && $_GET['statut'] == 'giftAdded' && $_GET['gift'] != ''){
	$context['message']['object'] = getGift(filter_var($_GET['gift'], FILTER_SANITIZE_NUMBER_INT))['title'];
	$context['message']['prefixe'] = 'Le cadeau';
	$context['message']['suffixe'] = 'a bien été ajouté !';
}

// Render
echo $twig->render('templates/user.twig', $context);