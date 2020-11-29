<?php 

require_once 'inc/header.php';

/////////////////////////////////////////
// SESSION & PERMISSIONS 
/////////////////////////////////////////

if (isset($_POST['connect']) && isset($_GET['user']) && (!isset($_SESSION['userID']) && !isset($_SESSION['guestName']))) {
    $_SESSION['userID'] = filter_var($_GET['user'], FILTER_SANITIZE_NUMBER_INT);
}

if (isset($_SESSION['userID'])) {
    $logedInUser = $_SESSION['userID'];
    $isguest = false;
}elseif(isset($_SESSION['guestName'])){
    $isguest = true;
}else{
    header('location:index.php');
}


/////////////////////////////////////////
// INIT & basic functions
/////////////////////////////////////////

$permissions = array(
    'owner' => false,
    'parent' => false,
    'guest' => false
);

if (isset($_GET['user']) && $_GET['user']) {
    $currentUser = filter_var($_GET['user'], FILTER_SANITIZE_NUMBER_INT);
}

$allUsers = getUsers();
$currentUserChildrenList = get_children($currentUser);
$currentUserGifts = getGifts($currentUser);

if(!$isguest){
    $logedInUserChildrenList = get_children($logedInUser);
}


/////////////////////////////////////////
// PERMISSIONS 
/////////////////////////////////////////

//Check if is owner

if (isset($_SESSION['userID']) && $logedInUser == $currentUser) {
    $permissions['owner'] = true;
}

//Check if it's a child page

if (isset($logedInUserChildrenList) && $logedInUserChildrenList != null) {
    foreach ($logedInUserChildrenList as $childrenID) {
        if ($childrenID == $currentUser) {
            $permissions['parent'] = true;
        }
    }
}

//Check if i'm guest

if(isset($_SESSION['guestName'])){
    $permissions['guest'] = true;
}

/////////////////////////////////////////
// USER INFOS
/////////////////////////////////////////

$currentUserArray = array_filter(
    $allUsers,
    function ($e) use (&$currentUser) {
        return $e->ID == $currentUser;
    }
);

foreach ($currentUserArray as $user){
    $currentUserInfos = $user;
}


/////////////////////////////////////////
// USER CHILDREN (IF HE HAS SOME)
/////////////////////////////////////////

if ($currentUserChildrenList != null) {
    //Get children infos

    $childrenListInfos = array_filter(
        $allUsers,
        function ($e) use (&$currentUserChildrenList) {
            return ($e->ID == in_array($e->ID, $currentUserChildrenList));
        }
    );
}

/////////////////////////////////////////
// USER GIFTS
/////////////////////////////////////////


function filterLists($value){
    return ($value['is_list'] == 1);
}

function filterGifts($value){
    return ($value['is_list'] == 0);
}

// Lists
$currentUserGiftsLists = array_filter($currentUserGifts, 'filterLists');

//Gifts
$currentUserGifts = array_filter($currentUserGifts, 'filterGifts');


//If the gift is reserved, attached informations of the reserv-er
foreach ($currentUserGifts as $key=>$gift){
   
    if ($gift['isReserved'] == true && $gift['reservationUserID'] != '' && $gift['reservationUserID'] != 0) {

        $currentUserGifts[$key]['reservation']['user'] = getUserInfo($gift['reservationUserID']);

        //If the loged user is the person who reserved the gift
        if (isset($logedInUser) && $logedInUser == $gift['reservationUserID']) {
            $currentUserGifts[$key]['reservation']['currentUser'] = true;
        }
        
    }
}

/////////////////////////////////////////
// OTHER LISTS
/////////////////////////////////////////

// All users except the current one
$otherUsers = array_filter(
    $allUsers,
    function ($e) use (&$currentUser,$allUsers) {
        return $e->ID != $currentUser;
    }
);

/////////////////////////////////////////
// FORM MESSAGES
/////////////////////////////////////////

//Gift just added message
if (isset($_GET['statut']) && isset($_GET['gift']) && $_GET['statut'] == 'giftAdded' && $_GET['gift'] != '') {
    $context['message']['object'] = getGift(filter_var($_GET['gift'], FILTER_SANITIZE_NUMBER_INT))['title'];
    $context['message']['prefixe'] = 'Le cadeau';
    $context['message']['suffixe'] = 'a bien été ajouté !';
}

//Unreserved gift
if (isset($_GET['statut']) && isset($_GET['gift']) && $_GET['statut'] == 'giftUnreserved' && $_GET['gift'] != '') {
    $context['message']['object'] = getGift(filter_var($_GET['gift'], FILTER_SANITIZE_NUMBER_INT))['title'];
    $context['message']['prefixe'] = 'La réservation du cadeau';
    $context['message']['suffixe'] = 'a bien été supprimée !';
}

//Reserved gift
if (isset($_GET['statut']) && isset($_GET['gift']) && $_GET['statut'] == 'giftReserved' && $_GET['gift'] != '') {
    $context['message']['object'] = getGift(filter_var($_GET['gift'], FILTER_SANITIZE_NUMBER_INT))['title'];
    $context['message']['prefixe'] = 'Le cadeau';
    $context['message']['suffixe'] = 'a bien été réservé !';
}

//Updated gift
if (isset($_GET['statut']) && isset($_GET['gift']) && $_GET['statut'] == 'giftUpdated' && $_GET['gift'] != '') {
    $context['message']['object'] = getGift(filter_var($_GET['gift'], FILTER_SANITIZE_NUMBER_INT))['title'];
    $context['message']['prefixe'] = 'Le cadeau';
    $context['message']['suffixe'] = 'a bien été modifié !';
}

//Edit account
if (isset($_GET['src']) && isset($_GET['user']) && $_GET['src'] == 'EditAccountOk' && $_GET['user'] != '') {
    $context['message']['object'] = getUserInfo(filter_var($_GET['user'], FILTER_SANITIZE_NUMBER_INT))->name;
    $context['message']['prefixe'] = 'Le compte';
    $context['message']['suffixe'] = 'a bien été modifié !';
}



/////////////////////////////////////////
// CONTEXT & RENDER
/////////////////////////////////////////

$context['permissions'] = $permissions;
$context['otherUsers'] = $otherUsers;

if(isset($logedInUser)){
    $context['logedInUser'] = $logedInUser;
}else{
    $context['logedInUser'] = $_SESSION['guestName'];
}


$context['currentUser']['infos'] = $currentUserInfos;
$context['currentUser']['gifts'] = $currentUserGifts;
$context['currentUser']['lists'] = $currentUserGiftsLists;

if (isset($childrenListInfos)) {
    $context['children'] = $childrenListInfos;
}

// Render
echo $twig->render('templates/user.twig', $context);