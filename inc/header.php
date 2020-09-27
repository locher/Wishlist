<?php

include_once('inc/conf/twig_configuration.php');
include_once('inc/conf/config.php');
include_once('inc/functions.php');

session_start();

var_dump($_SESSION);

$context = array();

if(isset($_SESSION['userID'])){
	$context['sessionUser'] = $_SESSION['userID'];
}

