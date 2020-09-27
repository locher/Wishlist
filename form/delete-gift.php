<?php

if(isset($_POST['giftID']) && $_POST['giftID'] != ''){

	global $bdd;
	include_once('../inc/conf/config.php');

	$giftID = $_POST['giftID'];

	//Supprimer le compte

	$deleteGift = $bdd->prepare("DELETE FROM ".$config['db_tables']['db_gifts']." WHERE ID = ".$giftID);

	if($deleteGift->execute()){
		header("location:../user.php?user=".$_POST['userID']);
	}else{
		echo "oups";
	}
}

?>
