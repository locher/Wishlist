<?php 

	// LA PAGE DE L'INVITÉ

	session_start();
	$_SESSION['userID'] = 'guest';

	//Chargement
	require('template-parts/header.php');

	getUsers();

?>

<body class="guest">

	<?php include('template-parts/header-top.php');?>
	
	<section class="user-infos background white-background">
		<div class="inner-user-infos">
		<h1>Invité</h1>
		<p>Vous pouvez voir toutes les listes et réserver des cadeaux.</p>
		</div>
	</section>

	<?php if($users_list): ?>

	<section class="primary-background background">
		<h2>Voir les listes</h2>
		
		<ul class="grid">
		
			<?php
			
			foreach($users_list as $user){			
				echo printSingleUser($user, 'Voir la liste', 'list.php?list='.$user['ID']);
			}
			
			?>
			
		</ul>
		
	</section>

	<?php endif;?>

</body>