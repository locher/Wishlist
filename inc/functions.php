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
		$condition[] = 'userID != '.$excludeID;
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
		];
	}

	return $gifts;
	
}

//Autres

setlocale(LC_TIME, 'fr_FR'); 

function birthdayDate($date){
	return strftime('%e %B %Y', strtotime($date));
}

function age($date){
	return floor((time() - strtotime($date))/60/60/24/365.25);
}




/*
//// Templating ////
*/



/* @TODO : SUPPRIMER */

//Bouton
function bt($link, $class, $text){
	
	if($link == 'button'){
		return('<button type= "button" class="bt '.$class.'">'.$text.'</button>');
	}elseif($link == 'submit'){
		return('<button type="submit" class="bt '.$class.'">'.$text.'</button>');
	}else{
		return('<a href="'.$link.'" class="bt '.$class.'">'.$text.'</a>');
	}
}