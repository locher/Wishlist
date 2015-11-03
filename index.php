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
					$id_illu = $export_user['choix_illu'];
			?>

			<div class="user">
				<div class="illu">
					<img src="img/perso<?php echo($id_illu);?>.png">
				</div>

				<div class="wrapper-username">
					
					<h2><?php echo $nom_personne ?></h2>
					<span class="ico-delete-user"><?php echo file_get_contents("img/ico-trash.svg"); ?></span>
					<span class="ico-edit-user"><?php echo file_get_contents("img/ico-edit.svg"); ?></span>
				</div>
				

				<div class="edit-user">

					<form action="edit-user.php" method="post">

						<input type="hidden" value="<?php echo $id_personne; ?>" name="id_personne">

						<div class="wrapper-gift-input">
							<span><?php echo file_get_contents("img/ico-user.svg"); ?></span>
							<input class="input-name" type="text" name="username" placeholder="Prénom" required value="<?php echo($nom_personne);?>">
						</div>
		
						<h3>Choisir l'illustration</h3>

						<div class="wrapper-illus">

							<div class="wrapper-illustration">
								<input name="choix-illu<?php echo($id_personne); ?>" type="radio" id="radio1-<?php echo($id_personne); ?>" value="1" class="perso1" <?php if('perso'.$id_illu == 'perso1'){echo 'checked';}?>>
								<label for="radio1-<?php echo($id_personne); ?>"><img src="img/perso1.png" alt=""></label>
								
							</div>

							<?php for($i=2; $i<=8; $i++): ?>
							
							<div class="wrapper-illustration">
								<input value="<?php echo $i; ?>" name="choix-illu<?php echo($id_personne); ?>" type="radio" id="radio<?php echo $i; ?>-<?php echo($id_personne); ?>" class="perso<?php echo $i; ?>" <?php if('perso'.$id_illu == 'perso'.$i){echo 'checked';}?>>
								<label for="radio<?php echo($i); ?>-<?php echo($id_personne); ?>"><img src="img/perso<?php echo($i); ?>.png" alt=""></label>
								
							</div>

							<?php endfor; ?>

						</div>

						<div class="wrapper-bt wrapper-add">
							<input type="submit" value="Modifier la personne" class="bt">
						</div>

						<div class="wrapper-bt">
							<span class="cancel-add-user bt-cancel">Annuler</span>
						</div>

					</form>

				</div>


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



			<div class="modal-add-user modal-user user">
				<form action="add-user.php" method="post">

					<div class="illu">
						<img src="img/perso1.png" alt="">
					</div>

					<h2>Ajouter une personne</h2>

					<div class="wrapper-gift-input">
						<span><?php echo file_get_contents("img/ico-user.svg"); ?></span>
						<input class="input-name" type="text" name="username" placeholder="Prénom" required>
					</div>
	
					<h3>Choisir l'illustration</h3>

					<div class="wrapper-illus">

						<div class="wrapper-illustration">
							<input name="choix-illu" type="radio" id="radio1" checked value="1" class="perso1">
							<label for="radio1"><img src="img/perso1.png" alt=""></label>
							
						</div>

						<?php for($i=2; $i<=8; $i++): ?>
						
						<div class="wrapper-illustration">
							<input value="<?php echo $i; ?>" name="choix-illu" type="radio" id="radio<?php echo $i; ?>" class="perso<?php echo $i; ?>">
							<label for="radio<?php echo($i); ?>"><img src="img/perso<?php echo($i); ?>.png" alt=""></label>
							
						</div>

						<?php endfor; ?>

					</div>

					<div class="wrapper-bt wrapper-add">
						<input type="submit" value="Ajouter le nouvel utilisateur" class="bt">
					</div>

					<div class="wrapper-bt">
						<span class="cancel-add-user bt-cancel">Annuler</span>
					</div>

				</form>
			</div>



		</div>

		

		<div class="add-user">
			<button class="bt">Ajouter une personne</button>
		</div>

		</div>	


	</div>


	<footer></footer>


<script src="js/snowstorm-min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="js/masonry.pkgd.min.js"></script>
<script>


	// Neige

	snowStorm.flakesMaxActive = 80000;
	snowStorm.animationInterval = 25;
	snowStorm.followMouse = false;
	snowStorm.targetElement = "snow";

	// Masonry

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

	// Modal ajouter personne

		// Le titre change quand on tape

	$('.modal-add-user .input-name').keyup(function(){

		var nom_personne = $(this).val();
		var phrase_ajout = 'Ajouter '+nom_personne;

		$(this).parent().parent().find('h2').html(nom_personne);

		$(this).parent().parent().find('input[type="submit"]').attr('value',phrase_ajout);

		if(nom_personne == ''){
			$(this).parent().parent().find('h2').html('Ajouter une personne');
			$(this).parent().parent().find('input[type="submit"]').attr('value','Ajouter la personne');
		}
	});

		// L'illu change quand on la sélectionne

	$('.wrapper-illus').change(function(){
		var illu_name = $(this).find('input[type="radio"]:checked').attr('class');
		$(this).parent().parent().find('.illu').html('<img src="img/'+illu_name+'.png"/>');
	});

	// Afficher le modal au click sur 'ajouter un perso'

	$('.add-user button').click(function(){
		$('.modal-user').fadeIn(function(){
			$grid.masonry();
		})
	});

	// La virer si on annule
	$('.modal-user .bt-cancel').click(function(){
		$('.modal-user').fadeOut(function(){
			$grid.masonry();
		})
	});

	// faire apparaitre l'edit des user

	$('.ico-edit-user').click(function(){
		$(this).parent().parent().find('.edit-user').slideToggle(function(){
			$grid.masonry();
		});
	});

	// ranger l'edit user si on annule

	$('.edit-user .bt-cancel').click(function(){
		$(this).parent().parent().parent().slideToggle(function(){
			$grid.masonry();
		});
	});

	// Edit user

		// Le titre change quand on tape

	$('.edit-user .input-name').keyup(function(){

		var nom_personne = $(this).val();
		var phrase_ajout = 'Modifier '+nom_personne;

		$(this).parent().parent().parent().parent().find('h2').html(nom_personne);

		$(this).parent().parent().find('input[type="submit"]').attr('value',phrase_ajout);

		if(nom_personne == ''){
			$(this).parent().parent().parent().parent().find('h2').html('Nom de la personne');
			$(this).parent().parent().find('input[type="submit"]').attr('value','Modifier la personne');
		}
	});

		// L'illu change quand on la sélectionne

	$('.wrapper-illus').change(function(){
		var illu_name = $(this).find('input[type="radio"]:checked').attr('class');
		$(this).parent().parent().parent().find('.illu').html('<img src="img/'+illu_name+'.png"/>');
	});


</script>
</body>
</html>