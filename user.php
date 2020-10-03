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
$currentUserArray = array_filter(
    $allUsers,
    function ($e) use (&$userID) {
        return $e->ID == $userID;
    }
);

//Liste of potential childs
$childrenList = get_children($userID);

if($childrenList != null){
    //Get children infos

    $childrenListInfos = array_filter(
        $allUsers,
        function ($e) use (&$singleChildren) {
            global $childrenList;
            return ($e->ID == in_array($e->ID, $childrenList));
        }
    );

    $context['children'] = $childrenListInfos;
}

foreach($currentUserArray as $user){
	$context['currentUser']['infos'] = $user;
}

//Get the user gifts
$context['currentUser']['gifts'] = getGifts($userID);



//Gift just added message
if(isset($_GET['statut']) && isset($_GET['gift']) && $_GET['statut'] == 'giftAdded' && $_GET['gift'] != ''){
	$context['message']['object'] = getGift(filter_var($_GET['gift'], FILTER_SANITIZE_NUMBER_INT))['title'];
	$context['message']['prefixe'] = 'Le cadeau';
	$context['message']['suffixe'] = 'a bien été ajouté !';
}

// Render
echo $twig->render('templates/user.twig', $context);