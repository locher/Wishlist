<?php

include_once('classes.php');

//Récupérer tous les users

function getUsers($type = null){
	
	global $bdd;

	if($type == 'parents'){
		$condition[] = 'isChildAccount = 0';
	}elseif($type == 'children'){
		$condition[] = ' isChildAccount = 1';
	}

	if(isset($condition) AND !is_null($condition)){

		$where = '';

		foreach($condition as $key => $cond){
			$where .= $cond;

			end($condition);
			if($key != key($condition)){
				$where .= ' AND ';
			}
		}

		$users = $bdd->query('SELECT * FROM '.CONFIG['db_tables']['db_users'].' WHERE '.$where.' ORDER BY name ASC');
	}else{
		$users = $bdd->query('SELECT * FROM '.CONFIG['db_tables']['db_users'].' ORDER BY name ASC');		
	}

	
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


function getGifts($userID, $nbGifts = 0){
	
	global $bdd;
	
	if($nbGifts > 0){
		$bddGifts = $bdd->query('SELECT * FROM '.CONFIG['db_tables']['db_gifts'].' WHERE userID = '.$userID.' ORDER BY userID DESC LIMIT '.$nbGifts.'');
	}else{
		$bddGifts = $bdd->query('SELECT * FROM '.CONFIG['db_tables']['db_gifts'].' WHERE userID = '.$userID.' ORDER BY userID DESC');
	}
		
	while($export_gifts = $bddGifts->fetch()){
		$gifts[] = [
			"title" => $export_gifts['title'],
			"description" => $export_gifts['description'],
			"link" => $export_gifts['link'],
			"ID" => $export_gifts['ID'],
			"isReserved" => $export_gifts['isReserved'],
			"reservationUserID" => $export_gifts['reservationUserID'],
			"isReserved" => $export_gifts['isReserved'],
			"reservationUserID" => $export_gifts['reservationUserID'],
			"reservationGuestName" => $export_gifts['reservationGuestName']

		];
	}

	return $gifts;
	
}

function getGift($giftID){
	
	global $bdd;
	
		$bddGifts = $bdd->query('SELECT * FROM '.CONFIG['db_tables']['db_gifts'].' WHERE ID = '.$giftID);
		
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

	global $bdd;

	$parents = $bdd->query('SELECT ID_parent FROM '.CONFIG['db_tables']['db_parents'].' WHERE ID_child = '.$userID.'');

		$parent_list = [];

		while($export = $parents->fetch()){
			$parent_list[] = $export['ID_parent'];
		}

		return $parent_list;
}	



function get_children($parentID){

	global $bdd;

	// Get children

	$children = $bdd->query('SELECT ID_child FROM '.CONFIG['db_tables']['db_parents'].' WHERE ID_parent = '.$parentID.'');

	$children_list = [];

	while($export = $children->fetch()){
		$children_list[] = $export['ID_child'];
	}

	return $children_list;

}

function nbChildren($parentID){

	global $bdd;

	// Get children

	$children = $bdd->query('SELECT ID_child FROM '.CONFIG['db_tables']['db_parents'].' WHERE ID_parent = '.$parentID.'');

	return $children->rowCount();

}

function is_parent($parentID, $childID){

	global $bdd;

	// Get children

	$matches = $bdd->query('SELECT ID_child FROM '.CONFIG['db_tables']['db_parents'].' WHERE ID_parent = '.$parentID.' AND ID_child = '.$childID);

	if($matches->rowCount() > 0){
		return true;
	}else{
		return false;
	}
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


function getUserInfo($userID){

	global $bdd;

	$user = $bdd->query('SELECT * FROM '.CONFIG['db_tables']['db_users'].' WHERE userID = '.$userID);

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

