<?php

////on vérifie s'il y a des listes enfants

if(isset($_SESSION['userID'])){
	
	$parents = $bdd->query('SELECT * FROM '.$config['db_tables']['db_parents'].' WHERE ID_parent = '.$_SESSION['userID'].' OR ID_child = '.$_SESSION['userID'].'');
	
	while($export = $parents->fetch()){
		$child_list[] = [
			"child" => $export['ID_child'],
		];
	}
}

?>

<header class="header-connected">
	
	<?php
	
	if($child_list): 
	getUsers();
	
	?>
	
	<input type="checkbox" id="displayMenu" class="display-menu">
	
	<?php endif;?>


	<button class="arrow left-arrow">
		<span class="arrow-text">Retour</span>
		<span class="arrow-shape"></span>
	</button>
	
	<?php if($child_list): ?>

	<button class="bt header-toggle border-white-bt" for="displayMenu">
		<label for="displayMenu">
			<span class="displayMenuOuvert">Fermer</span>
			<span class="displayMenuFerme">Mes listes</span>
		</label>
	</button>
	
	<?php 
	
	// Key de l'utilisateur actif pour récupérer nom et photo
	
	$user_key = array_search($_SESSION['userID'], array_column($users_list, 'ID'));
	
	?>


	<ul class="multi-user">
		<li>
		<a href="" class="single-people">
			<span><?php echo $users_list[$user_key]['name'];?></span>
			<img src="src/img/avatar/avatar<?php echo $users_list[$user_key]['picture'];?>.png" alt="">
		</a>
		</li>
		
		<?php
		
		foreach($child_list as $child):
		
		//Key de chaque enfant pour récupérer nom et photo
		$child_key = array_search($child['child'], array_column($users_list, 'ID'));
		
		?>		
		
		<li>
		<a href="" class="single-people">
			<span><?php echo $users_list[$child_key]['name'];?></span>
			<img src="src/img/avatar/avatar<?php echo $users_list[$child_key]['picture'];?>.png" alt="">
		</a>
		</li>
		
		<?php endforeach;?>

	</ul>
	
	<?php else: ?>
	
	<?php print bt('#', 'border-white-bt', 'Ma liste');?>
	
	<?php endif;?>
	
</header>