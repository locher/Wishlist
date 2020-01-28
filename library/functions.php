<?php


//Récupérer tous les users

function getUsers(){
	
	global $bdd, $config, $users_list;
	
	$users = $bdd->query('SELECT * FROM '.$config['db_tables']['db_users'].' ORDER BY name ASC');

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
}

/*
//// Templating ////
*/

function bt($link, $class, $text){
	
	if($link == 'button'){
		print('<button class="bt '.$class.'">'.$text.'</button>');
	}else{
		print('<a href="'.$link.'" class="bt '.$class.'">'.$text.'</a>');
	}
	
}

?>