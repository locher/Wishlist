<?php 

include('inc/bdd.php');
$bdd->query('SET NAMES "utf8"');


$user_id = $_POST['user-id'];

$bdd->query("DELETE FROM personne WHERE id_personne = '".$user_id."'");

header("location:index.php"); 


?>
