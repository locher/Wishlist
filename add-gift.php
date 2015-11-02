<?php 

include('inc/bdd.php');
$bdd->query('SET NAMES "utf8"');


$gift_title = $_POST['gift-name'];
$gift_url = $_POST['gift-url'];
$gift_description = $_POST['gift-description'];
$gift_user = $_POST['gift-user'];


if(isset($gift_title)&&($gift_title!='')){

	$bdd->query("INSERT INTO liste (id, la_personne, titre, lien, description) VALUES ('','".$gift_user."','".$gift_title."','".$gift_url."','".$gift_description."')");

	header("location:index.php"); 

}

?>
