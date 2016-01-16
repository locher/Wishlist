<?php include('inc/config.php'); ?>
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

				$users = $bdd->query('SELECT * FROM '.$bdd_users.' ORDER BY nom_personne ASC');

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
					
					<h2><?php echo $nom_personne; ?></h2>
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
					
					<form class="wrapper-bt wrapper-add" action="user-connection.php" method="post">
                        <input type="hidden" value="<?php echo $id_personne; ?>" name="id_personne">
                        <input type="submit" value="Je suis <?php echo $nom_personne; ?> !" class="bt">
                    </form>

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
								<use xlink:href="#icon-ico-user"></use>
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



