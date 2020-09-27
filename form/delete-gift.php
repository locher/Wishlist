<?php

if(isset($_POST['giftID']) && $_POST['giftID'] != ''){

	global $bdd;
	include_once('../inc/conf/config.php');

	$giftID = filter_var($_POST['giftID'], FILTER_SANITIZE_NUMBER_INT);

	//Supprimer le compte

	$deleteGift = $bdd->prepare("DELETE FROM ".$config['db_tables']['db_gifts']." WHERE ID = ".$giftID);

	if($deleteGift->execute()){
		header("location:../user.php?user=".$_POST['userID']);
	}else{
		echo "oups";
	}
}

?>
