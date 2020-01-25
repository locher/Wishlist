<?php 

require_once('inc/config.php');
require_once('inc/bdd.php');

//On vérifie si on est connecté, si oui on ammène directement à la page user, sinon à la page de connexion




//Si la session est ouverte, go page user
if(isset($_SESSION['userID'])&&($_SESSION['userID']!='')){
    //header('Location: user.php');
	//echo 1;
}

else{
	//Si la sessions est pas ouverte et qu'on est sur la page de login, il se passe rien
	if(strstr($_SERVER['REQUEST_URI'], 'index.php')){
		//echo 2;
	//Si la session est pas ouverte et qu'on est pas sur la page de login, go page de login
	}else{
		//echo 3;
		//header('Location: index.php');
	}
	
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>KDO</title>
	<link rel="stylesheet" type="text/css" href="src/style.css">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>