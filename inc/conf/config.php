<?php 

include('db.php');

if(isset($config)){
	define("CONFIG", $config);
}else{
	header("location:install/install-form.php");
}

//Connexion BDD

try{
    $bdd = new PDO('mysql:host='.$config['db']['host'].';dbname='.$config['db']['dbname'].'', ''.$config['db']['username'].'', ''.$config['db']['password'].'');
}

catch (Exception $e){
    die(print $e->getMessage());
}

$bdd->query('SET NAMES "utf8"');
