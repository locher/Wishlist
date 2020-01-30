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


//Bouton
function bt($link, $class, $text){
	
	if($link == 'button'){
		return('<button class="bt '.$class.'">'.$text.'</button>');
	}else{
		return('<a href="'.$link.'" class="bt '.$class.'">'.$text.'</a>');
	}
}

//Single user
function printSingleUser($user, $txt_bt){
	return('
	
	<li class="list_elt single-people">
	<img src="src/img/avatar/avatar' . $user['picture'] .'.png" alt="">
	<div class="inner-singlePeople">
		<h3>'. $user['name'] . '</h3>

		<form action="user.php" method="post">
			<input type="hidden" name="userID" value="'. $user['ID'].'">'.bt('button', 'color-bt', $txt_bt).'
		</form>

	</div>
</li>
	
	');
}

?>