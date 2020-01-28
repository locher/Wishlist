<?php 

/* Page template : Login Page */

include_once('template-parts/header.php');

getUsers();

?>

<body class="template-home">

	<header class="top-header wrapper">
		<span class="top-header--home--intro">Bonjour !</span>
	</header>

	<section class="choice-client">

		<div class="wrapper choice-client--top">

			<span>Vous êtes</span>
			<?php bt('#','color-bt','Un invité'); ?>
			<p>Vous pourrez réserver un cadeau, mais n’avez pas votre liste.</p>
			<span class="choice-client--separator">ou</span>

		</div>
		
		<?php if($users_list): ?>

		<ul class="grid">
			
			<?php
			
			foreach($users_list as $user){
				
				if($user['isChildAccount'] != true){
					include('template-parts/lists/single_user.php');
				}
			}
			
			?>
			
		</ul>
		
		<?php endif;?>
		
		<div class="add-account-home">
			<?php bt('#','border-primary-bt','Créer un compte'); ?>
		</div>

	</section>
</body>

<?php require('template-parts/footer.php');?>