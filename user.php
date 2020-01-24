<?php 

	// LA PAGE DE L'UTILISATEUR CONNECTÉ

	
	//Récuperer l'userID si on vient de la page de connexion

	if(isset($_POST['userID']) && $_POST['userID'] !=''){
		$userID = $_POST['userID'];
		session_start();
		$_SESSION['userID'] = $userID;
	}

	//Chargement

	require('template-parts/header.php');

	// Query

	// // Récupérer les infos du user actif

	$activeUser = $bdd->query('SELECT * FROM '.$bdd_users.' WHERE ID = '.$_SESSION['userID']);
		
	while($export_user = $activeUser->fetch()){
		$active_user = [
			"ID" => $export_user['ID'],
			"name" => $export_user['name'],
			"isChildAccount" => $export_user['isChildAccount'],
			"picture" => $export_user['picture'],
			"birthday_date" => $export_user['birthday_date'],
			"size_top" => $export_user['size_top'],
			"size_bottom" => $export_user['size_bottom'],
			"size_feet" => $export_user['size_feet'],
		];
	}

	//Calcul et affichage date anniversaire

	setlocale(LC_TIME, 'fr_FR'); 
	
	$date_anniversaire = strftime('%e %B %Y', strtotime($active_user['birthday_date']));

	//age en année
	$age = floor((time() - strtotime($active_user['birthday_date']))/60/60/24/365.25);
	
	//Récupérer les infos des autres user pour affichage liste

	$users = $bdd->query('SELECT ID, name, picture FROM '.$bdd_users.' WHERE ID != '.$_SESSION['userID'].' ORDER BY name ASC');

	while($export_user = $users->fetch()){
		$users_list[] = [
			"name" => $export_user['name'],
			"ID" => $export_user['ID'],
			"picture" => $export_user['picture'],
		];
	}
?>

<body class="connected-user-profil">

	<header class="header-connected">
		<button class="arrow left-arrow">
			<span class="arrow-text">Retour</span>
			<span class="arrow-shape"></span>
		</button>
		<a href="" class="bt border-white-bt">Mes listes</a>
	</header>
	
	<?php if($active_user): ?>

	<section class="user-infos">
		<img src="src/img/avatar/avatar<?php echo $active_user['picture'];?>.png" alt="">
		<div class="inner-user-infos">
			<h1><?php echo $active_user['name'];?></h1>

			<?php if($date_anniversaire):?>
		
			<div class="birthday-infos">
				<span class="birthday"><?php echo $date_anniversaire;?></span>
				<span class="age"><?php echo $age;?> ans</span>
			</div>
			
			<?php endif;?>
			
			<?php if($active_user['size_top'] or $active_user['size_bottom'] or $active_user['feet']):?>

			<div class="size-infos">
			
				<?php if($active_user['size_top']):?>
				<span class="top"><?php echo $active_user['size_top'];?></span>
				<?php endif;?>
				
				<?php if($active_user['size_bottom']):?>
				<span class="bottom"><?php echo $active_user['size_bottom'];?></span>
				<?php endif;?>
				
				<?php if($active_user['size_feet']):?>
				<span class="feet"><?php echo $active_user['size_feet'];?></span>
				<?php endif;?>
			</div>
			
			<?php endif;?>

			<a href="#_" class="bt border-primary-bt">Modifier les infos</a>
		</div>
	</section>
	
	<?php endif;?>
	
	<section>
		<h2>Mes envies</h2>
		
		<ul class="grid">

	<li class="list_elt single-gift">
	
		<div class="gift-content">
			<div class="gift-header">
				<h3>Audi A4 miniature</h3>
				<a href="#_" target="_blank" class="bt border-pink-bt">Voir le site</a>
			</div>
			
			<div class="gift-description">
				<p>Pour aller dans la collection du bureau avec ma mégane et ma 2CV.</p>
			</div>
			
		</div>
	</li>
	
	<li class="list_elt single-gift">
	
		<div class="gift-content">
			<div class="gift-header">
				<h3>Audi A4 miniature</h3>
				<a href="#_" target="_blank" class="bt border-pink-bt">Voir le site</a>
			</div>
			
			<div class="gift-description">
				<p>Pour aller dans la collection du bureau avec ma mégane et ma 2CV.</p>
			</div>
			
		</div>
	</li>
		
</ul>
	
	<div class="overlay-wish">
		<a href="" class="bt color-bt">Modifier ma liste</a>
	</div>
		
	</section>
	
			<?php 
			if($users_list):
		?>


	<section>
		<h2>Voir les listes</h2>


		<ul class="grid">
			
			<?php
				foreach($users_list as $user):
			?>

			<li class="list_elt single-people">
				<img src="src/img/avatar/avatar<?php echo $user['picture'];?>.png" alt="">
				<div class="inner-singlePeople">
					<h3><?php echo $user['name'];?></h3>
					
					<form action="#" method="post">
						<input type="hidden" name="userID" value="<?php print $user['ID'];?>">
						<button class="bt white-bt">Voir la liste</button>
					</form>
					
				</div>
			</li>
			
			<?php endforeach;?>
		</ul>
		
		

	</section>
	
	<?php endif;?>
	
</body>