<?php

// AJOUTER OU MODIFIER UN CADEAU

//Chargement
include_once('template-parts/header.php');

//Mode edit ou mode create
	
if($_GET['mode'] != 'edit'){
	$formMode = 'create';
}else{
	$formMode = $_GET['mode'];
}

//Textes en fonction du mode

if($formMode == 'create'){
	$pageTitle = 'Ajouter un cadeau';
	$boutonText = 'Ajouter ce cadeau';
}else if($formMode == 'edit'){
	$pageTitle = 'Modifier un cadeau';
	$boutonText = 'Modifier ce cadeau';
}

?>

<body>
	
<?php include('template-parts/header-top.php');?>

<section class="background white-background">
	<h1>
		<?php echo $pageTitle;?>
	</h1>
</section>

<section class="background white-background">
	
	<form action="form/gift.php" method="POST">

		<input type="hidden" name="formMode" value="<?php echo $formMode; ?>">

		<input type="hidden" name="userID" value="<?php echo $_GET['user']; ?>">
		
		<div class="wrap-form">
			<div class="label-wrap">
				<label for="">Désignation</label>
			</div>
			<input type="text required" name="designation">
		</div>

		<div class="wrap-form">
			<div class="label-wrap">
				<label for="">Lien</label>
				<span class="helper">Facultatif</span>
			</div>
			<input type="text" name="link">
		</div>

		<div class="wrap-form">
			<div class="label-wrap">
				<label for="">Description</label>
				<span class="helper">Facultatif</span>
			</div>
			<textarea height=3 name="description"></textarea>
		</div>

		<div class="wrap-form wrap-form-borderless" id="switch-child">
			<div class="label-wrap">
				<span class="fake-label">Est-ce une liste ?</span>
			</div>

			<div class="switch">
				<input type="checkbox" id="isList" v-on:change="reverseSwitch" name="isList" value="true">
				<label for="isList" class="">
					<span class="switch-option1">Oui</span>
					<span class="switch-option2">Non</span>
				</label>
			</div>
		</div>

		<div class="bt-wrapper">
			<?php echo bt('submit', 'color-bt', $boutonText);?>
		</div>

	</form>

</section>

<?php

// Notification création de cadeau

if(isset($_GET['src']) && isset($_GET['user']) && $_GET['user'] != ''):

	if($_GET['src'] == 'createGiftFail'){

		$textMessage = "<p>Aïe !</p><p>Le cadeau n'a pas été enregistré :(</p>";
	}


?>

<div class="primary-background background message animation">
	<?php echo $textMessage;?>
</div>

<?php endif;?>

</body>