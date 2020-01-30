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

	getUsers();

	// Récupérer les infos du user actif
	$active_user = $users_list[array_search($userID, array_column($users_list, 'ID'))];

	//Récupérer les 2 derniers cadeaux de l'utilisateur connecté
	getGifts($_SESSION['userID'], 2);

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

	<section class="user-infos background white-background">
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
				<span class="birthday"><?php echo birthdayDate($active_user['birthday_date']);?></span>
				<span class="age"><?php echo age($active_user['birthday_date']);?> ans</span>
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
	
	<?php if(isset($gifts)):?>

	<section class="list-gifts background white-background">

		<ul class="grid overlay-parent">
			
			<?php foreach($gifts as $gift): ?>
		
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

	<?php if($users_list): ?>


	<section class="primary-background background">
		<h2>Voir les listes</h2>
		
		<ul class="grid">
		
			<?php
			
			foreach($users_list as $user){
				if($user['ID'] != $userID){
					echo printSingleUser($user, 'Voir la liste');
				}
			}
			
			?>
			
		</ul>
		
	</section>

	<?php endif;?>

</body>