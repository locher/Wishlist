<?php 

include_once('inc/header.php');

// Get current ID
if(isset($_GET['user'])){
	$userID = $_GET['user'];
}

// Get current user infos
$context['currentUser']['infos'] = getUserInfo($userID);

//Get all users gifts
$context['currentUser']['gifts'] = getGifts($userID);

// Get all user exept the current one AND the session one
//$context['users'] = getUsers('all', '('.$userID.','. $_SESSION['userID'].')');
$context['users'] = getUsers('all', array($userID, $_SESSION['userID']));

// Render
echo $twig->render('templates/list.twig', $context);


	
