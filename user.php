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

	$activeUser = $bdd->query('SELECT * FROM '.$bdd_users.' WHERE userID = '.$_SESSION['userID']);
		
	while($export_user = $activeUser->fetch()){
		$active_user = [
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

	//Calcul et affichage date anniversaire

	setlocale(LC_TIME, 'fr_FR'); 
	
	$date_anniversaire = strftime('%e %B %Y', strtotime($active_user['birthday_date']));

	//age en année
	$age = floor((time() - strtotime($active_user['birthday_date']))/60/60/24/365.25);
	
	//Récupérer les infos des autres user pour affichage liste

	$users = $bdd->query('SELECT userID, name, picture FROM '.$bdd_users.' WHERE userID != '.$_SESSION['userID'].' ORDER BY name ASC');

	while($export_user = $users->fetch()){
		$users_list[] = [
			"name" => $export_user['name'],
			"ID" => $export_user['userID'],
			"picture" => $export_user['picture'],
		];
	}

	//Récupérer les 2 derniers cadeaux de l'utilisateur connecté

	$bddGifts = $bdd->query('SELECT title, description, link FROM '.$bdd_gifts.' WHERE userID = '.$_SESSION['userID'].' ORDER BY userID DESC LIMIT 2');
		
	while($export_gifts = $bddGifts->fetch()){
		$lastgifts[] = [
			"title" => $export_gifts['title'],
			"description" => $export_gifts['description'],
			"link" => $export_gifts['link'],
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
			<h1>
				<?php echo $active_user['name'];?>
			</h1>

			<?php if($active_user['birthday_date']):?>

			<div class="birthday-infos">
				<div class="svg-wrapper">
					<object data="src/img/svg-prod/baby.svg" type="image/svg+xml"></object>
				</div>
				<span class="birthday"><?php echo $date_anniversaire;?></span>
				<span class="age"><?php echo $age;?> ans</span>
			</div>

			<?php endif;?>

			<?php if($active_user['size_top'] or $active_user['size_bottom'] or isset($active_user['size_feet'])):?>

			<div class="size-infos">

				<?php if($active_user['size_top']):?>
				<div class="wrapper-size" title="Taille haut">
					<div class="svg-wrapper">
						<object data="src/img/svg-prod/tshirt.svg" type="image/svg+xml"></object>
					</div>
					<span class="top"><?php echo $active_user['size_top'];?></span>
				</div>
				
				<?php endif;?>

				<?php if($active_user['size_bottom']):?>
				<div class="wrapper-size" title="Taille pantalon">
					<div class="svg-wrapper">
						<object data="src/img/svg-prod/pant.svg" type="image/svg+xml"></object>
					</div>
					<span class="bottom"><?php echo $active_user['size_bottom'];?></span>
				</div>

				<?php endif;?>

				<?php if(isset($active_user['size_feet'])):?>
				<div class="wrapper-size" title="Taille chaussure">
					<div class="svg-wrapper">
						<object data="src/img/svg-prod/shoe.svg" type="image/svg+xml"></object>
					</div>
					<span class="feet"><?php echo $active_user['size_feet'];?></span>
				</div>

				<?php endif;?>
			</div>

			<?php endif;?>
		</div>
		
		<a href="#_" class="bt border-primary-bt">Modifier les infos</a>
		
	</section>

	<?php endif;?>
	
	<?php if(isset($export_gifts)):?>

	<section>
		<h2>Mes envies</h2>

		<ul class="grid overlay-parent">
			
			<?php foreach($lastgifts as $gift): ?>
		
			<li class="list_elt single-gift">

				<div class="gift-content">
					<div class="gift-header">
						<h3><?php echo $gift["title"];?></h3>
						
						<?php if($gift['link'] != ''):?>
						
						<a href="<?php echo $gift['link'];?>" target="_blank" class="bt border-pink-bt">Voir le site</a>
						
						<?php endif;?>
					</div>
					
					<?php if($gift['description'] != ''):?>

					<div class="gift-description">
						<p><?php echo $gift['description'];?></p>
					</div>
					
					<?php endif;?>

				</div>
			</li>
			
			<?php endforeach;?>
			
			<div class="overlay-wish">
				<a href="" class="bt color-bt">Modifier ma liste</a>
			</div>

		</ul>

		

	</section>
	
	<?php endif;?>

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
						<h3>
							<?php echo $user['name'];?>
						</h3>

						<a href="user-list.php?listID=<?php print $user['ID'];?>" class="bt white-bt">Voir la liste</a>

					</div>
				</li>

				<?php endforeach;?>
		</ul>



	</section>

	<?php endif;?>

</body>