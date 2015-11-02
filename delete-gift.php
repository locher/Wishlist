<?php 

include('inc/bdd.php');
$bdd->query('SET NAMES "utf8"');


$gift_id = $_POST['gift-id'];

$bdd->query("DELETE FROM liste WHERE id = '".$gift_id."'");

header("location:index.php"); 


?>
