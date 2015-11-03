<?php 

include('inc/bdd.php');
$bdd->query('SET NAMES "utf8"');


$username = $_POST['username'];
$choix_illu = $_POST['choix-illu'];


if(isset($username)&&($username!='')){

	$bdd->query("INSERT INTO personne (id_personne, nom_personne, choix_illu) VALUES ('','".$username."','".$choix_illu."')");

	header("location:index.php"); 

}

?>
