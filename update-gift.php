<?php 

include('inc/bdd.php');
$bdd->query('SET NAMES "utf8"');


$gift_title = $_POST['gift-name'];
$gift_url = $_POST['gift-url'];
$gift_description = $_POST['gift-description'];
$gift_id = $_POST['gift-id'];


if(isset($gift_title)&&($gift_title!='')){

	$bdd->query("UPDATE liste SET titre = '".$gift_title."', lien = '".$gift_url."', description = '".$gift_description."' WHERE id = ".$gift_id."");

	header("location:index.php"); 

}

?>
