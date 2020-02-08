<?php 

	// AJOUTER OU MODIFIER UN COMPTE

	//Chargement
	include_once('template-parts/header.php');
	getUsers();

	//Mode edit ou mode create
		
	if($_GET['mode'] != 'edit'){
		$formMode = 'create';
	}else{
		$formMode = $_GET['mode'];
	}

	//Textes en fonction du mode

	if($formMode == 'create'){
		$pageTitle = 'Ajouter un compte';
		$boutonText = 'Ajouter ce compte';
	}else if($formMode == 'edit'){
		$pageTitle = 'Modifier un compte';
		$boutonText = 'Modifier ce compte';
	}

?>

<body class="connected-user-profil">

	<?php include('template-parts/header-top.php');?>


	<section class="background white-background">
		<h1>
			<?php echo $pageTitle;?>
		</h1>
	</section>

	<section class="background white-background">
		<form action="form/account.php" method="post">

			<div class="wrap-form">
				<div class="label-wrap">
					<label for="firstname">Prénom</label>
				</div>
				<input type="text" id="firstname" name="firstname" required>
			</div>

			<div class="wrap-form">
				<div class="label-wrap">
					<label for="birthdate">Date de naissance</label>
					<span class="helper">JJ/MM/AAAA</span>
				</div>

				<input type="date" id="birthdate" min="1930-01-01" max="<?php echo date('o-m-d');?>" name="birthday">
			</div>

			<div class="wrap-form">

				<div class="label-wrap">
					<span class="fake-label">Tailles</span>
					<span class="helper">Facultatif</span>
				</div>


				<div class="wrapper-form">

					<div class="wrap-form mini-form">
						<div class="label-wrap">
							<label for="size-top">Haut</label>
						</div>
						<input type="text" id="size-top" name="size-top">
					</div>

					<div class="wrap-form mini-form">
						<div class="label-wrap">
							<label for="size-bottom">Bas</label>
						</div>
						<input type="text" id="size-bottom" name="size-bottom">
					</div>

					<div class="wrap-form mini-form">
						<div class="label-wrap">
							<label for="size-feet">Pied</label>
						</div>
						<input type="text" id="size-feet" name="size-feet">
					</div>
				</div>
			</div>

			<div class="wrap-form">

				<div class="label-wrap">
					<span class="fake-label">Illustration</span>
				</div>

				<div class="content-img-choice">

					<?php for($i=1; $i<=15; $i++): ?>
					<div class="single-choice">
						<input type="radio" id="photo<?php echo $i;?>" value="<?php echo $i;?>" name="photoChoice">
						<label for="photo<?php echo $i;?>">
							<img src="src/img/avatar/avatar<?php echo $i;?>.png" alt="" class="avatar">
						</label>
					</div>

					<?php endfor;?>

				</div>
			</div>

			<div class="wrap-form wrap-form-borderless" id="switch-child">
				<div class="label-wrap">
					<span class="fake-label">Compte enfant ?</span>
				</div>

				<div class="switch">
					<input type="checkbox" id="child-account" v-on:change="reverseSwitch" name="isChild" value="true">
					<label for="child-account" class="">
						<span class="switch-option1">Oui</span>
						<span class="switch-option2">Non</span>
					</label>
				</div>
			</div>

			<div class="wrap-form choiceParent" id="choiceParent" v-show="isDisplay">
				<div class="label-wrap">
					<span class="fake-label">Qui peut modifier cette liste ?</span>
				</div>

				<?php getUsers(); ?>

				<?php if($users_list): ?>

				<ul class="grid">

					<?php foreach($users_list as $user): ?>
					<?php if($user['isChildAccount'] != true): ?>
					

					<li>
						<input type="checkbox" name="choiceParent[]" value="<?php echo $user['ID'];?>" id="parent<?php echo $user['ID'];?>">
						<label for="parent<?php echo $user['ID'];?>">
							<img src="src/img/avatar/avatar<?php echo $user['picture'];?>.png" alt="" class="avatar">
							<?php echo $user['name'];?>
						</label>
					</li>
					
					<?php endif;?>

					<?php endforeach;?>

				</ul>

				<?php endif;?>
			</div>
			
			<div class="bt-wrapper">
				<?php echo bt('submit', 'color-bt', $boutonText);?>
			</div>
			
		</form>
	</section>
</body>

<?php include('template-parts/footer.php');?>