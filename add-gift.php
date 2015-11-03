<?php 

include('inc/bdd.php');
include('inc/config.php');

$bdd->query('SET NAMES "utf8"');


$gift_title = $_POST['gift-name'];
$gift_url = $_POST['gift-url'];
$gift_description = $_POST['gift-description'];
$gift_user = $_POST['gift-user'];


if(isset($gift_title)&&($gift_title!='')){

	$statement = $bdd->prepare("INSERT INTO ".$bdd_gifts." (id, la_personne, titre, lien, description) VALUES (NULL, ?, ?, ?, ?)");
	$statement->bindParam(1, $gift_user, PDO::PARAM_STR);
	$statement->bindParam(2, $gift_title, PDO::PARAM_STR);
	$statement->bindParam(3, $gift_url, PDO::PARAM_STR);
	$statement->bindParam(4, $gift_description, PDO::PARAM_STR);

	$statement->execute();

	//L'ajax

	$reponse = 'success';

	$gift_id = $bdd->lastInsertId();


	echo json_encode(array(
		'reponse'=>$reponse,
		'gift_title'=>$gift_title,
		'gift_url'=>$gift_url,
		'gift_description'=>$gift_description,
		'gift_id' =>$gift_id
	));

}

?>
