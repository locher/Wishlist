<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<title>KDO – Installation</title>
	<link rel="stylesheet" type="text/css" href="../assets/style.css">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="robots" content="noindex, nofollow">

	<link rel="apple-touch-icon" sizes="180x180" href="../assets/img/favicon/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="../assets/img/favicon/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="../assets/img/favicon/favicon-16x16.png">
	<link rel="manifest" href="../assets/img/favicon/site.webmanifest">
	<link rel="mask-icon" href="../assets/img/favicon/safari-pinned-tab.svg" color="#202859">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="theme-color" content="#202859">


</head>
<body class="form-account">

	<div class="wrapper">

	<section class="background white-background title-group">
		<h1>Installation</h1>
		<h2>Configurer la base de donnée</h2>
	</section>

	<?php 
		if(isset($_GET['error']) && $_GET['error'] == 'db'):
	?>

	<section class="error">
		<p><strong>La connexion a votre base de donnée a échouée, merci de vérifier les informations et de réessayer.</strong></p>
	</section>

	<?php endif; ?>

	<?php 
		if(isset($_GET['error']) && $_GET['error'] == 'form'):
	?>

	<section class="error">
		<p><strong>Le formulaire comporte une erreur, merci de remplir tous les champs.</strong></p>
	</section>

	<?php endif; ?>

	<section class="background white-background">

	<form action="install.php" method="post">

		<div class="wrap-form">
			<div class="label-wrap">
				<label for="dbname">Nom de la BDD</label>
			</div>
			<input type="text" id="dbname" name="dbname" required>
		</div>

		<div class="wrap-form">
			<div class="label-wrap">
				<label for="dbuser">Nom d'utilisateur de la BDD</label>
			</div>
			<input type="text" id="dbuser" name="dbuser" required>
		</div>

		<div class="wrap-form">
			<div class="label-wrap">
				<label for="dbpassword">Mot de passe</label>
			</div>
			<input type="text" id="dbpassword" name="dbpassword" required>
		</div>

		<div class="wrap-form">
			<div class="label-wrap">
				<label for="dbhost">Hostname</label>
			</div>
			<input type="text" id="dbhost" name="dbhost" required value="localhost">
		</div>

		<div class="wrap-form">
			<div class="label-wrap">
				<label for="dbprefix">Préfixe des tables</label>
			</div>
			<input type="text" required id="dbprefix" name="dbprefix" value="KDO_">
		</div>

		<div class="bt-wrapper">
			<button type="submit" class="bt color-bt">Installer</button>
		</div>

	</form>
</section>

</div>



</body>
</html>