<?php 

include_once('inc/header.php');

$context = array();


// Get current ID
if(isset($_GET['user'])){
	$userID = $_GET['user'];
}

// Get current user infos
$context['currentUser']['infos'] = getUserInfo($userID);

//Birthday date of current user
$context['currentUser']['infos']['nice_birthday'] = birthdayDate($context['currentUser']['infos']['birthday_date']);

//age of current user
$context['currentUser']['infos']['age'] = age($context['currentUser']['infos']['birthday_date']);


//Get all users gifts
$context['currentUser']['gifts'] = getGifts($userID);

// Get all user exept the current one
$context['users'] = getUsers('all',$userID);

// Render
echo $twig->render('templates/list.twig', $context);


	
