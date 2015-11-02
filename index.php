<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Wishlist de Noël</title>
	<link rel="stylesheet" href="style.css">
	<link href='https://fonts.googleapis.com/css?family=Lato:400,900,700' rel='stylesheet' type='text/css'>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
</head>
<body>

	<div id="snow"></div>

	<div class="wrapper">

		<header class="logo">
			<?php echo file_get_contents("img/logo.svg"); ?>
		</header>
		
		<div class="content">

			<div class="grid-sizer"></div>

			<?php 
				include('inc/bdd.php');

				$users = $bdd->query('SELECT * FROM personne ORDER BY nom_personne ASC');

				while($export_user = $users->fetch()):

					$nom_personne = $export_user['nom_personne'];
					$id_personne = $export_user['id_personne'];
			?>

			<div class="user">
				<div class="illu">
					<?php echo file_get_contents("img/perso2.svg"); ?>
				</div>
				<h2><?php echo $nom_personne ?></h2>
				<ul>

					<?php 

						$gifts = $bdd->query('SELECT * FROM liste WHERE la_personne = '.$id_personne.' ORDER BY titre ASC');

						while($gift = $gifts->fetch()):

							$nom_gift = $gift['titre'];
							$link_gift = $gift['lien'];
							$description_gift = $gift['description'];
							$id_gift = $gift['id'];
					?>

					<li>
						<div class="wrapper-title">
							<p class="gift-title"><?php echo $nom_gift; ?></p>
							<?php if($link_gift): ?>
								<a title="Lien vers le cadeau" href="<?php echo $link_gift; ?>" class="gift-link"><?php echo file_get_contents("img/link.svg"); ?></a>
							<?php endif; ?>

							<span class="submit-delete ico-trash"><?php echo file_get_contents("img/ico-trash.svg"); ?></span>

							<div class="confirmation-suppression">
									<p>Êtes-vous sûr ?</p>
									<form action="delete-gift.php" method="post">
										<input type="hidden" value="<?php echo $id_gift; ?>" name="gift-id">
										<input type="submit" class="confirm-suppression bt" value="Oui" />
									</form>
									<p class="annuler-suppression">Non, annuler</p>
							</div>

							<span class="ico-edit" title="Éditer le cadeau"><?php echo file_get_contents("img/ico-edit.svg"); ?></span>
						</div>
						
						<?php if($description_gift): ?>
						<p class="gift-description"><?php echo $description_gift; ?></p>
						<?php endif; ?>

						<?php //Le formulaire, pour edition ?>
						<form class="form-gift form-edit" action="update-gift.php" method="post">
							<div class="wrapper-gift-input">
								<span><?php echo file_get_contents("img/ico-item.svg"); ?></span>
								<input type="text" name="gift-name" required placeholder="Désignation" value="<?php echo $nom_gift; ?>">
							</div>
							<div class="wrapper-gift-input">
								<span><?php echo file_get_contents("img/link.svg"); ?></span>
								<input type="text" name="gift-url" placeholder="Lien optionnel" value="<?php echo $link_gift; ?>">
							</div>
							
							<textarea name="gift-description" id="" rows="3" placeholder="Détail optionnel"><?php echo $description_gift; ?></textarea>

							<input type="hidden" value="<?php echo $id_gift; ?>" name="gift-id">

							<input type="submit" class="bt bt-edit-gift" value="Modifier le cadeau">

							<div class="wrapper-bt-edit-gift">
								<span class="cancel-edit-gift bt-cancel">Annuler</span>
							</div>
						</form>
					</li>

					<?php endwhile; ?>

				</ul>

				<form class="form-gift form-add" action="add-gift.php" method="post">
					<div class="wrapper-gift-input">
						<span><?php echo file_get_contents("img/ico-item.svg"); ?></span>
						<input type="text" name="gift-name" required placeholder="Désignation">
					</div>
					<div class="wrapper-gift-input">
						<span><?php echo file_get_contents("img/link.svg"); ?></span>
						<input type="text" name="gift-url" placeholder="Lien optionnel">
					</div>
					
					<textarea name="gift-description" id="" rows="3" placeholder="Détail optionnel"></textarea>

					<input type="hidden" value="<?php echo $id_personne; ?>" name="gift-user">

					<input type="submit" class="bt" value="Ajouter le cadeau">
				</form>

				<div class="wrapper-bt wrapper-add">
					<button class="bt bt-add-gift">Ajouter un cadeau</button>
				</div>				

			</div>

			<?php endwhile; 
			?>

		</div>	

	</div>


	<footer></footer>


<script src="js/snowstorm-min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="js/masonry.pkgd.min.js"></script>
<script>

	snowStorm.flakesMaxActive = 80000;
	snowStorm.animationInterval = 25;
	snowStorm.followMouse = false;
	snowStorm.targetElement = "snow";

	var $grid = $('.content').masonry({
	  itemSelector: '.user',
	  columnWidth: '.grid-sizer',
	  percentPosition: true,
	  gutter: 50
	});

	$grid;

	// Ouverture et fermeture du form d'ajout cadeau

	$('.bt-add-gift').click(function(){
		$(this).parent().parent().find('.form-add').toggleClass('open');
		$grid.masonry();
		$(this).toggleClass('bt open bt-cancel');
		$(this).parent().parent().find('.form-add').slideToggle(function(){
			$grid.masonry();
		});

		if($(this).hasClass('open')){
			$(this).html('Annuler')
		}

		else{
			$(this).html('Ajouter un cadeau');
		}

	});

	// Edition des cadeaux

	$('.ico-edit').click(function(){
		$('.form-edit').slideUp(function(){
			$grid.masonry();
		});
		$(this).parent().parent().find('.form-edit').slideToggle(function(){
			$grid.masonry();
		});
	});

	$('.cancel-edit-gift').click(function(){
		$(this).parent().parent().slideToggle(function(){
			$grid.masonry();
		});
	});

	// Suppression cadeau

	$('.submit-delete').click(function(){
		$(this).parent().find('.confirmation-suppression').fadeIn();
	});

	$('.annuler-suppression').click(function(){
		$(this).parent().fadeOut();
	});



</script>
</body>
</html>