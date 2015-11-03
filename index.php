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

	<div class="svg-wrapper" aria-hidden="true">
		<?php echo file_get_contents('img/svg-prod/sprite/svgs.svg'); ?>
	</div>	

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

			<div class="user" id="user<?php echo $id_personne;?>">
				<div class="illu">
					<img src="img/perso<?php echo($id_illu);?>.png">
				</div>

				<div class="wrapper-username">
					
					<h2><?php echo $nom_personne ?></h2>
						<span class="ico-delete-user">
							<svg viewBox="0 0 100 100" class="icon">
								<use xlink:href="#icon-ico-trash"></use>
							</svg>
						</span>
					<span class="ico-edit-user">
						<svg viewBox="0 0 100 100" class="icon">
							<use xlink:href="#icon-ico-edit"></use>
						</svg>
					</span>

					<div class="confirmation-suppression">
							<p>Êtes-vous sûr ?</p>
							<form action="delete-user.php" method="post">
								<input type="hidden" value="<?php echo $id_personne; ?>" name="user-id">
								<input type="submit" class="confirm-suppression bt" value="Oui" />
							</form>
							<p class="annuler-suppression">Non, annuler</p>
					</div>
				</div>
				

				<div class="edit-user">

					<form action="edit-user.php" method="post">

						<input type="hidden" value="<?php echo $id_personne; ?>" name="id_personne">

						<div class="wrapper-gift-input">
							<span>
								<svg viewBox="0 0 100 100" class="icon">
									<use xlink:href="#icon-ico-user"></use>
								</svg>
							</span>
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


				<ul class="gift-list">

					<?php 

						$gifts = $bdd->query('SELECT * FROM liste WHERE la_personne = '.$id_personne.' ORDER BY titre ASC');
					?>

					<?php

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
								<a title="Lien vers le cadeau" href="<?php echo $link_gift; ?>" class="gift-link">
									<svg viewBox="0 0 100 100" class="icon">
										<use xlink:href="#icon-link"></use>
									</svg>
								</a>
							<?php endif; ?>

							<span class="submit-delete ico-trash">
								<svg viewBox="0 0 100 100" class="icon">
									<use xlink:href="#icon-ico-trash"></use>
								</svg>
							</span>

							<div class="confirmation-suppression">
									<p>Êtes-vous sûr ?</p>
									<form action="delete-gift.php" method="post">
										<input type="hidden" value="<?php echo $id_gift; ?>" name="gift-id">
										<input type="submit" class="confirm-suppression bt" value="Oui" />
									</form>
									<p class="annuler-suppression">Non, annuler</p>
							</div>

							<span class="ico-edit" title="Éditer le cadeau">
								<svg viewBox="0 0 100 100" class="icon">
									<use xlink:href="#icon-ico-edit"></use>
								</svg>
							</span>
						</div>
						
						<?php if($description_gift): ?>
						<p class="gift-description"><?php echo $description_gift; ?></p>
						<?php endif; ?>

						<?php //Le formulaire, pour edition ?>
						<form class="form-gift form-edit" action="update-gift.php" method="post">
							<div class="wrapper-gift-input">
								<span>
									<svg viewBox="0 0 100 100" class="icon">
										<use xlink:href="#icon-ico-item"></use>
									</svg>
								</span>
								<input type="text" name="gift-name" required placeholder="Désignation" value="<?php echo $nom_gift; ?>">
							</div>
							<div class="wrapper-gift-input">
								<span>
									<svg viewBox="0 0 100 100" class="icon">
										<use xlink:href="#icon-link"></use>
									</svg>
								</span>
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

				<form class="form-gift form-add" action="add-gift.php" method="post" id="add-user">
					<div class="wrapper-gift-input">
						<span>
							<svg viewBox="0 0 100 100" class="icon">
								<use xlink:href="#icon-ico-item"></use>
							</svg>
						</span>
						<input type="text" name="gift-name" required placeholder="Désignation">
					</div>
					<div class="wrapper-gift-input">
						<span>
							<svg viewBox="0 0 100 100" class="icon">
								<use xlink:href="#icon-link"></use>
							</svg>
						</span>
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



			<div class="modal-add-user modal-user user" id="modal-add-user">
				<form action="add-user.php" method="post">

					<div class="illu">
						<img src="img/perso1.png" alt="">
					</div>

					<h2>Ajouter une personne</h2>

					<div class="wrapper-gift-input">
						<span>
							<svg viewBox="0 0 100 100" class="icon">
								<use xlink:href="#icon-ico-trash"></use>
							</svg>
						</span>
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
<script src="js/jquery.scrollTo.min.js"></script>
<script src="js/scripts.js"></script>

</body>
</html>



