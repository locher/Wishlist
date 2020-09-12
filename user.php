<?php 

include_once('inc/header.php');

$context = array();

//Get user ID and and a cookie with it
if(isset($_GET['user']) && $_GET['user'] !=''){
	$userID = $_GET['user'];
	session_start();
	$_SESSION['userID'] = $userID;
}

// Get all user exept the current one
$context['users'] = getUsers('all',$userID);

// Get current user infos
$context['currentUser']['infos'] = getUserInfo($userID);


//Birthday date of current user
$context['currentUser']['infos']['nice_birthday'] = birthdayDate($context['currentUser']['infos']['birthday_date']);

//age of current user
$context['currentUser']['infos']['age'] = age($context['currentUser']['infos']['birthday_date']);

//Get 2 latest user gifts
$context['currentUser']['gifts'] = getGifts($userID, 3);

// Render
echo $twig->render('templates/user.twig', $context);