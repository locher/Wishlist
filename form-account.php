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
		$formURL = 'form/account.php';
		$pageTitle = 'Ajouter un compte';
		$boutonText = 'Ajouter ce compte';
	}else if($formMode == 'edit'){
		$formURL = 'form/account.php';
		$pageTitle = 'Modifier un compte';
		$boutonText = 'Modifier ce compte';
	}

	//Si c'est une édition, on récupère les infos liées

	if($formMode == 'edit' && isset($_GET['user'])){
		$editUserID = $users_list[array_search($_GET['user'], array_column($users_list, 'ID'))];
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
		<form action="<?php echo $formURL;?>" method="post">

			<input type="hidden" name="formMode" value="<?php echo $formMode;?>">

			<?php
			//Si on est en mode edit, on récupère l'ID user
			if($formMode == 'edit'):
			?>

			<input type="hidden" name="userID" value="<?php echo $_GET['user'];?>">

			<?php endif;?>

			<div class="wrap-form">
				<div class="label-wrap">
					<label for="firstname">Prénom</label>
				</div>
				<input
					type="text" id="firstname" name="firstname" required <?php if($editUserID['name']) echo 'value='.$editUserID['name'];?>
				>
			</div>

			<div class="wrap-form">
				<div class="label-wrap">
					<label for="birthdate">Date de naissance</label>
					<span class="helper">JJ/MM/AAAA</span>
				</div>

				<input
					type="date" id="birthdate" min="1930-01-01" max="<?php echo date('o-m-d');?>" name="birthday"
					<?php if($editUserID['birthday_date']) echo 'value='.$editUserID['birthday_date'];?>
				>
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
						<input
							type="text" id="size-top" name="size-top"
							<?php if($editUserID['size_top']) echo 'value='.$editUserID['size_top'];?>
						>
					</div>

					<div class="wrap-form mini-form">
						<div class="label-wrap">
							<label for="size-bottom">Bas</label>
						</div>
						<input type="text" id="size-bottom" name="size-bottom" <?php if($editUserID['size_bottom']) echo 'value='.$editUserID['size_bottom'];?>>
					</div>

					<div class="wrap-form mini-form">
						<div class="label-wrap">
							<label for="size-feet">Pied</label>
						</div>
						<input type="text" id="size-feet" name="size-feet" <?php if($editUserID['size_feet']) echo 'value='.$editUserID['size_feet'];?>>
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
						<input type="radio" id="photo<?php echo $i;?>" value="<?php echo $i;?>" name="photoChoice"
						<?php if($editUserID['picture'] && $editUserID['picture'] == $i) echo 'checked';?>>
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
					<input type="checkbox" id="child-account" v-on:change="reverseSwitch" name="isChild" value="true"
					<?php if($editUserID['isChildAccount'] && $editUserID['isChildAccount'] == true) echo 'checked';?>>
					<label for="child-account" class="">
						<span class="switch-option1">Oui</span>
						<span class="switch-option2">Non</span>
					</label>
				</div>
			</div>
			
			<?php

			//Si c'est un compte enfant, et qu'on est en mode modif, il faut chercher les parents
			if($editUserID['isChildAccount'] && $editUserID['isChildAccount'] == true && $formMode == 'edit'){
				
	
				$parents = $bdd->query('SELECT ID_parent FROM '.$config['db_tables']['db_parents'].' WHERE ID_child = '.$_GET['user'].'');
	
				while($export = $parents->fetch()){
					$parent_list[] = $export['ID_parent'];
				}
			}
			
			?>
			

			<div class="wrap-form choiceParent" id="choiceParent" v-show="isDisplay">
				<div class="label-wrap">
					<span class="fake-label">Qui peut modifier cette liste ?</span>
				</div>

				<?php if($users_list): ?>

				<ul class="grid">

					<?php foreach($users_list as $user): ?>
					<?php if($user['isChildAccount'] != true): ?>
					

					
					<li>
						
						<?php
						
						//Checked les parents en mode modif
						if($formMode == 'edit' && isset($parent_list)){
	
							if(gettype(array_search($user['ID'], $parent_list)) != 'boolean'){
								$parentChecked = "checked";		
							}else{
								$parentChecked = "";			
							}
						}else{
							$parentChecked = "";
						}
						
						?>
				
						<input type="checkbox" name="choiceParent[]" value="<?php echo $user['ID'];?>" id="parent<?php echo $user['ID'];?>" <?php echo $parentChecked;?>/>
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


		<?php if($formMode == 'edit'):?>
		
		<form action="form/delete-account.php" method="POST" id="delete-confirmation">
			<input type="hidden" name="userID" value="<?php echo $_GET['user'];?>">
			<input type="hidden" name="userName" value="<?php echo $editUserID['name']; ?>">

							<div class="bt-group">
				<button class="bt red-bt" type="button" v-on:click="reverseDisplay">
					Supprimer ce compte
				</button>
			</div>
			
			<transition name="fade">

			<div class="background primary-background message" v-show="isDisplay">

				<p>Supprimer <strong><?php echo $editUserID['name'];?></strong> ?</p>
				<p>Toutes ses informations et ses cadeaux associés seront définitivement supprimés. </p>
				<div class="bt-group">
					<button class="bt border-white-bt" type="button" v-on:click="reverseDisplay">
					Non, annuler
				</button>
					<?php echo bt('submit', 'red-bt', 'Oui, supprimer');?>
				</div>
			</div>
			
			</transition>
		</form>

		<?php endif;?>
		
	</section>
</body>

<?php include('template-parts/footer.php');?>

<?php
if($editUserID['isChildAccount'] && $editUserID['isChildAccount'] == true && $formMode == 'edit'):
?>

<script>formDisplay.isDisplay = true;</script>

<?php endif;?>