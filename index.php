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
					?>

					<li>
						<p class="gift-title"><?php echo $nom_gift; ?></p>
						<?php if($link_gift): ?>
						<a href="<?php echo $link_gift; ?>" class="gift-link"><?php echo file_get_contents("img/link.svg"); ?></a>
						<?php endif; ?>
						<?php if($description_gift): ?>
						<p class="gift-description"><?php echo $description_gift; ?></p>
						<?php endif; ?>
					</li>

					<?php endwhile; ?>

				</ul>

				<form class="form-gift" action="add-gift.php" method="post">
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

	$('.bt-add-gift').click(function(){
		$(this).parent().parent().find('.form-gift').slideDown(function(){
			$grid.masonry();
		});
		$(this).hide();
	});


</script>
</body>
</html>