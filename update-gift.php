<?php 

include('inc/bdd.php');
$bdd->query('SET NAMES "utf8"');


$gift_title = $_POST['gift-name'];
$gift_url = $_POST['gift-url'];
$gift_description = $_POST['gift-description'];
$gift_id = $_POST['gift-id'];


if(isset($gift_title)&&($gift_title!='')){

	$statement = $bdd->prepare("UPDATE liste SET titre = :title, lien = :lien, description = :description WHERE id = :id");

	$statement->bindParam(':title', $gift_title, PDO::PARAM_STR);
	$statement->bindParam(':lien', $gift_url, PDO::PARAM_STR);
	$statement->bindParam(':description', $gift_description, PDO::PARAM_STR);
	$statement->bindParam(':id', $gift_id, PDO::PARAM_INT);


	$statement->execute();
	header("location:index.php"); 

}

?>
