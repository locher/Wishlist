<?php 

$config = array(
    "db" => array(
	  "dbname" => "KDO2020",
	  "username" => "root",
	  "password" => "root",
	  "host" => "localhost"
    ),
	
	"db_tables" => array(
		"db_users" => "KDO_peoples",
		"db_gifts" => "KDO_gifts",
		
	),
    "urls" => array(
        "baseUrl" => "http://localhost:8888/Wishlist19"
    )
);

//Connexion BDD

try{
    $bdd = new PDO('mysql:host='.$config['db']['host'].';dbname='.$config['db']['dbname'].'', ''.$config['db']['username'].'', ''.$config['db']['password'].'');
}

catch (Exception $e){
    die(print $e->getMessage());
}

$bdd->query('SET NAMES "utf8"');

?>