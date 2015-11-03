<?php 

include('inc/bdd.php');
$bdd->query('SET NAMES "utf8"');


$gift_id = $_POST['gift-id'];

$statement = $bdd->prepare("DELETE FROM liste WHERE id = :id");

$statement->bindParam(':id', $gift_id, PDO::PARAM_STR);
$statement->execute();

header("location:index.php"); 


?>
