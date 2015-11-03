<?php 

include('inc/bdd.php');
$bdd->query('SET NAMES "utf8"');

$id_personne = $_POST['id_personne'];
$username = $_POST['username'];
$choix_illu = $_POST['choix-illu'.$id_personne];

if(isset($username)&&($username!='')){

	$bdd->query("UPDATE personne SET nom_personne = '".$username."', choix_illu = '".$choix_illu."' WHERE id_personne = '".$id_personne."'");

	header("location:index.php"); 

}

?>
