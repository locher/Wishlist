<?php

include_once('classes.php');

//Récupérer tous les users

function getUsers(){
	
	global $bdd, $config, $users_list;

	$where = '';

	$users = $bdd->query('SELECT * FROM '.$config['db_tables']['db_users'].' ORDER BY name ASC');
	
	while($export_user = $users->fetch()){
		$users_list[] = new user(
			$export_user['userID'],
			$export_user['name'],
			$export_user['picture'],
			$export_user['birthday_date'],
			$export_user['size_top'],
			$export_user['size_bottom'],
			$export_user['size_feet'],
			$export_user['isChildAccount']
		);
	}

	return $users_list;
}

function getUserInfo($userID){

	global $bdd, $config, $users_list;

	$user = $bdd->query('SELECT * FROM '.$config['db_tables']['db_users'].' WHERE userID = '.$userID);

	while($export_user = $user->fetch()){
		$user_info = new user(
			$export_user['userID'],
			$export_user['name'],
			$export_user['picture'],
			$export_user['birthday_date'],
			$export_user['size_top'],
			$export_user['size_bottom'],
			$export_user['size_feet'],
			$export_user['isChildAccount']
		);
	}

	return $user_info;
}

function getGifts($userID, $nbGifts = 0){
	
	global $bdd, $config, $gifts;
	
	if($nbGifts > 0){
		$bddGifts = $bdd->query('SELECT * FROM '.$config['db_tables']['db_gifts'].' WHERE userID = '.$userID.' ORDER BY userID DESC LIMIT '.$nbGifts.'');
	}else{
		$bddGifts = $bdd->query('SELECT * FROM '.$config['db_tables']['db_gifts'].' WHERE userID = '.$userID.' ORDER BY userID DESC');
	}
		
	while($export_gifts = $bddGifts->fetch()){
		$gifts[] = [
			"title" => $export_gifts['title'],
			"description" => $export_gifts['description'],
			"link" => $export_gifts['link'],
			"ID" => $export_gifts['ID']
		];
	}

	return $gifts;
	
}

function getGift($giftID){
	
	global $bdd, $config, $gifts;
	
		$bddGifts = $bdd->query('SELECT * FROM '.$config['db_tables']['db_gifts'].' WHERE ID = '.$giftID);
		
	while($export_gifts = $bddGifts->fetch()){
		$gift = [
			"title" => $export_gifts['title'],
			"description" => $export_gifts['description'],
			"link" => $export_gifts['link'],
			"ID" => $export_gifts['ID']
		];
	}

	return $gift;
	
}

function get_parents($userID){

	global $bdd, $config, $users_list;

	$parents = $bdd->query('SELECT ID_parent FROM '.$config['db_tables']['db_parents'].' WHERE ID_child = '.$userID.'');

		$parent_list = [];

		while($export = $parents->fetch()){
			$parent_list[] = $export['ID_parent'];
		}

		return $parent_list;
}	

function get_children($parentID){
	global $bdd, $config, $users_list;

	// Get children

	$children = $bdd->query('SELECT ID_child FROM '.$config['db_tables']['db_parents'].' WHERE ID_parent = '.$parentID.'');

	$children_list = [];

	while($export = $children->fetch()){
		$children_list[] = $export['ID_child'];
	}

	// Get children infos

	$children_infos_query = $bdd->query('SELECT * FROM '.$config['db_tables']['db_users'].' WHERE userID IN ('.implode(',',$children_list).')');

	$children_infos = array();

	if($children_infos_query){

		while($export_children_infos = $children_infos_query->fetch()){
				$children_infos[] = new user(
					$export_user['userID'],
					$export_user['name'],
					$export_user['picture'],
					$export_user['birthday_date'],
					$export_user['size_top'],
					$export_user['size_bottom'],
					$export_user['size_feet'],
					$export_user['isChildAccount']
				);
		}

	}

	return $children_infos;

}


//Autres

setlocale(LC_TIME, 'fr_FR'); 

function birthdayDate($date){
	return strftime('%e %B %Y', strtotime($date));
}

function age($date){
	//age in years
	$age =  (time() - strtotime($date))/60/60/24/365.25;

	//If age < 2 years; count in month
	if($age <= 2){
		$age = $age*12;
		$age = floor($age).' mois';
	}else{
		$age = floor($age). ' ans';
	}

	return $age;
}
