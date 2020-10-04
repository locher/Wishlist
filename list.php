<?php 

include_once('inc/header.php');

// Get current ID
if(isset($_GET['user'])){
	$userID = $_GET['user'];
}

//Check either you can edit or reserve the current user based on your login
if(is_parent($_SESSION['userID'], $userID)){
	$context['currentUser']['permissions']['canEdit'] = true;
	$context['currentUser']['permissions']['canReserve'] = false;
}else{
	$context['currentUser']['permissions']['canEdit'] = false;
	$context['currentUser']['permissions']['canReserve'] = true;
}


// Get current user infos
$context['currentUser']['infos'] = getUserInfo($userID);

//Get all users gifts
$userGifts = getGifts($userID);

//If the gift is reserved, attached informations of the reserv-er
foreach($userGifts as $key=>$gift){
	if($gift['isReserved'] == true && $gift['reservationUserID'] != ''){

		$userGifts[$key]['reservation']['user'] = getUserInfo($gift['reservationUserID']);

		//If the loged user is the person who reserved the gift
		if($_SESSION['userID'] == $gift['reservationUserID']){
			$userGifts[$key]['reservation']['currentUser'] = true;
		}
	}
}

d($userGifts);

$context['currentUser']['gifts'] = $userGifts;

// Get all user exept the current one AND the session one
//$context['users'] = getUsers('all', '('.$userID.','. $_SESSION['userID'].')');
$context['users'] = getUsers('all', array($userID, $_SESSION['userID']));

// Render
echo $twig->render('templates/list.twig', $context);


	
