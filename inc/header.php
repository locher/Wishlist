<?php

include_once('inc/conf/twig_configuration.php');
include_once('inc/conf/config.php');
include_once('inc/functions.php');

$context = array();

session_start();

if(isset($_SESSION['userID'])){
	echo $_SESSION['userID'];
}
