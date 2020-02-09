<?php 

/* Page template : Login Page */

include_once('template-parts/header.php');

getUsers();

?>

<body class="template-home">
	
	<?php

	if(isset($_GET['src']) && isset($_GET['user']) && $_GET['user'] != ''):

		if($_GET['src'] == 'CreateAccountOk'){

			$textMessage = "<p><strong>".$_GET['user']."</strong> a bien été ajouté(e) !</p><p>Bienvenue dans la famille :)</p>";

		}else if($_GET['src'] == 'DeleteAccountOk'){
			$textMessage = "<p><strong>".$_GET['user']."</strong> a bien été supprimé(e) !</p>";
		}


	?>

	<div class="primary-background background message animation">
		<?php echo $textMessage;?>
	</div>
	
	<?php endif;?>
	
	<section class="home-header white-background background">
	
		<h1 class="top-header--home--intro">Bonjour</h1>
		<p class="top-header--sousTitre">C'est gentil de faire un cadeau</p>
		
		
		<div class="choice-client--top">
			<?php echo bt('guest.php','color-bt','Me connecter en tant qu\'invité'); ?>
			<p class="home-txt">Vous pourrez réserver un cadeau, mais n’avez pas votre liste.</p>
			<span class="choice-client--separator">ou</span>
		</div>
	</section>
	
	<section class="list-connection primary-background background">
		<?php if($users_list): ?>
			<ul class="grid">
			<?php

			foreach($users_list as $user){

				if($user['isChildAccount'] != true){
					echo printSingleUser($user, 'Me connecter', 'user.php?user='.$user['ID']);
				}
			}

			?>
			</ul>

			<?php endif;?>

			<div class="add-account-home">
				<?php echo bt('form-account.php?mode=create','border-white-bt','Ajouter un compte'); ?>
			</div>
	</section>
	
</body>

<?php require('template-parts/footer.php');?>