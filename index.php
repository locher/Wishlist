<?php 

	require('inc/config.php');
	require('inc/bdd.php');

	require('template-parts/header.php');

	//Get all users

	$users = $bdd->query('SELECT ID, name, picture FROM '.$bdd_users.' ORDER BY name ASC');

	while($export_user = $users->fetch()){
		$users_list[] = [
			"name" => $export_user['name'],
			"ID" => $export_user['ID'],
			"picture" => $export_user['picture'],
		];
	}

?>

<body class="template-home">

	<header class="top-header wrapper">
		<span class="top-header--home--intro">Bonjour !</span>
	</header>

	<section class="choice-client">

		<div class="wrapper choice-client--top">

			<span>Vous êtes</span>
			<a href="#" class="bt color-bt">Un invité</a>
			<p>Vous pourrez réserver un cadeau, mais n’avez pas votre liste.</p>
			<span class="choice-client--separator">ou</span>

		</div>
		
		<?php 
			if($users_list):
		?>

		<ul class="grid">
			
			<?php
				foreach($users_list as $user):
			?>

			<li class="list_elt single-people">
				<img src="src/img/avatar/avatar<?php echo $user['picture'];?>.png" alt="">
				<div class="inner-singlePeople">
					<h3><?php echo $user['name'];?></h3>
					<a href="test.php?user=<?php print $user['ID'];?>" class="bt white-bt">Me connecter</a>
				</div>
			</li>
			
			<?php endforeach;?>
		</ul>
		
		<?php endif;?>
		
		<div class="add-account-home">
		
			<a href="#" class="bt border-primary-bt">Créer un compte</a>
		</div>

	</section>
</body>

<?php require('template-parts/footer.php');?>