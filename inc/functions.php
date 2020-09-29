<?php


//Récupérer tous les users

function getUsers($type="all", $excludeID=false){
	
	global $bdd, $config, $users_list;

	if($type == 'parents'){
		$condition[] = 'isChildAccount = 0';
	}elseif($type == 'children'){
		$condition[] = ' isChildAccount = 1';
	}

	if($excludeID != false){

		if(is_array($excludeID)){
			$condition[] = 'userID NOT IN ('.implode(',',$excludeID).')';
		}else{
			$condition[] = 'userID != '.$excludeID;
		}

		
	}

	$where = '';

	if(isset($condition) AND !is_null($condition)){
		foreach($condition as $key => $cond){
			$where .= $cond;

			end($condition);
			if($key != key($condition)){
				$where .= ' AND ';
			}
		}

		$users = $bdd->query('SELECT * FROM '.$config['db_tables']['db_users'].' WHERE '.$where.' ORDER BY name ASC');
	}else{
		$users = $bdd->query('SELECT * FROM '.$config['db_tables']['db_users'].' ORDER BY name ASC');		
	}
	
	while($export_user = $users->fetch()){
		$users_list[] = [
			"ID" => $export_user['userID'],
			"name" => $export_user['name'],
			"isChildAccount" => $export_user['isChildAccount'],
			"picture" => $export_user['picture'],
			"birthday_date" => $export_user['birthday_date'],
			"size_top" => $export_user['size_top'],
			"size_bottom" => $export_user['size_bottom'],
			"size_feet" => $export_user['size_feet'],
		];
	}

	return $users_list;
}

function getUserInfo($userID){

	global $bdd, $config, $users_list;

	$user = $bdd->query('SELECT * FROM '.$config['db_tables']['db_users'].' WHERE userID = '.$userID);

	while($export_user = $user->fetch()){
		$user_info = [
			"ID" => $export_user['userID'],
			"name" => $export_user['name'],
			"isChildAccount" => $export_user['isChildAccount'],
			"picture" => $export_user['picture'],
			"birthday_date" => $export_user['birthday_date'],
			"size_top" => $export_user['size_top'],
			"size_bottom" => $export_user['size_bottom'],
			"size_feet" => $export_user['size_feet'],
		];
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
			$children_infos[] = [
				"ID" => $export_children_infos['userID'],
				"name" => $export_children_infos['name'],
				"isChildAccount" => $export_children_infos['isChildAccount'],
				"picture" => $export_children_infos['picture'],
				"birthday_date" => $export_children_infos['birthday_date'],
				"size_top" => $export_children_infos['size_top'],
				"size_bottom" => $export_children_infos['size_bottom'],
				"size_feet" => $export_children_infos['size_feet'],
			];
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
