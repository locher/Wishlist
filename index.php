<?php
    session_start(); 
    if(!isset($_SESSION['user']) && empty($_SESSION['user'])){
        header('Location: login.php');
    }
    include('inc/config.php');
?>
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
					<h2><?php echo $nom_personne ?></h2>
				</div>


				<ul class="gift-list">

					<?php 
						$gifts = $bdd->query('SELECT * FROM '.$bdd_gifts.' WHERE la_personne = '.$id_personne.' ORDER BY titre ASC');
					?>

					<?php

						while($gift = $gifts->fetch()):

							$nom_gift = $gift['titre'];
							$link_gift = $gift['lien'];
							$description_gift = $gift['description'];
							$id_gift = $gift['id'];
                            $resa_gift = $gift['reserve'];
                            $resa_par = $gift['IdUser_resa'];
					?>
					
					
					
					<li <?php //Si le cadeau est réservé et qu'on est pas sur notre propre liste, on le marque différemment
                        if($resa_gift == true && $id_personne != $_SESSION['user']) echo 'class="reserve"';?>
                    >
						<div class="wrapper-title">
						
							<p class="gift-title"><?php echo $nom_gift; ?></p>
							
							<?php if($link_gift): ?>
								<a title="Lien vers le cadeau" href="<?php echo $link_gift; ?>" class="gift-link">
									<svg viewBox="0 0 100 100" class="icon">
										<use xlink:href="#icon-link"></use>
									</svg>
								</a>
							<?php endif; ?>
							
							<?php 
                                //Chaque personne identifié peut modifier sa propre liste
                                if($id_personne == $_SESSION['user']):
                            ?>

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
							
							<?php 
                                //Si on est pas le propriétaire la liste, on gère la réservation
                                else:                                
                                // On récupère l'état de réservation, la personne qui l'a éventuellement réservé
                                $resa = $bdd->query('SELECT reserve,IdUser_resa FROM '.$bdd_gifts.' WHERE id = '.$id_gift);
                                
                            	while($export_resa = $resa->fetch()):
                                $etat_reservation = $export_resa['reserve'];
                                $user_reservation = $export_resa['IdUser_resa'];
                            ?>
                            
                                <?php
                                    //Si le cadeau n'est pas réservé, j'affiche le bouton "réserver"
                                    if($etat_reservation == 0):
                                ?>
                            
							    <form action="gift-reservation.php" method="post" id="form-resa">
							        <input type="hidden" value="<?php echo $id_gift; ?>" name="gift-id">
							        <input type="submit" value="Réserver" class="bt_resa bt">
							    </form>
                          
                                <?php 
                                    // Si le cadeau est réservé
                                    else:
                            
                                        //Si il est réservé par moi, je peux annuler
                                        if($user_reservation == $_SESSION['user']):
                                ?>
                                
                                        <form action="delete_reservation.php" method="post" id="cancel_resa">
                                            <input type="hidden" value="<?php echo $id_gift; ?>" name="gift-id">
                                            <input type="submit" value="Annuler" class="bt bt_annuler" title="Tu as indiqué vouloir réserver ce cadeau. Changé d'avis ?">
                                        </form>
                                
                                <?php
                                        //Si il est réservé par qqun d'autre, j'affiche ce qqun d'autre
                                        else:
                                        $mec_resa = $bdd->query('SELECT nom_personne, choix_illu FROM '.$bdd_gifts.' INNER JOIN '.$bdd_users.' ON '.$bdd_gifts.'.IdUser_resa = '.$bdd_users.'.id_personne WHERE id = '.$id_gift);
                                        while($export_mec = $mec_resa->fetch()):
                                        $nom_dumec = $export_mec['nom_personne'];
                                        $illu = $export_mec['choix_illu'];
                                ?>
                                
                                        
                                
                                <?php
                                        endwhile;
                                ?>
                                
                                       <div class="resaPar" title="<?php echo $nom_dumec;?> a réservé ce cadeau">
                                            <img src="img/perso<?php echo($illu);?>.png">
                                            <span><?php echo $nom_dumec;?></span>
                                        </div>
                                        
                                
                                <?php
                                        endif;
                                ?>
                           
                                <?php endif;?>
                            
                            
                            <?php 
                                endwhile;                    
                            ?> 	
							    					
							<?php endif;?>
							
						</div>
						
						<?php if($description_gift): ?>
						<p class="gift-description"><?php echo $description_gift; ?></p>
						<?php endif; ?>
						
                        <?php 
                            //Chaque personne identifié peut modifier sa propre liste
                            if($id_personne == $_SESSION['user']):
                        ?>

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
						
						<?php endif; ?>
						
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
				
				<?php if($id_personne == $_SESSION['user']): ?>

				<div class="wrapper-bt wrapper-add">
					<button class="bt bt-add-gift">Ajouter un cadeau</button>
				</div>
				
				<?php endif;?>
    

			</div>

			<?php endwhile; 
			?>

		</div>



		</div>	


	</div>
	
	


	<footer><div class="logout"><a href="logout.php">Se déconnecter</a></div></footer>


<script src="js/snowstorm-min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="js/masonry.pkgd.min.js"></script>
<script src="js/jquery.scrollTo.min.js"></script>
<script src="js/scripts.js"></script>

</body>
</html>



