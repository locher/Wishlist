(function ($, root, undefined) {
	
	$(function () {
		
		'use strict';
		
		// Neige

snowStorm.flakesMaxActive = 80000;
snowStorm.animationInterval = 25;
snowStorm.followMouse = false;
snowStorm.targetElement = "snow";

// Masonry

var $grid = $('.content').masonry({
  itemSelector: '.user',
  columnWidth: '.grid-sizer',
  percentPosition: true,
  gutter: 50
});

$grid;

// Ouverture et fermeture du form d'ajout cadeau

$('.bt-add-gift').click(function(){
	$(this).parent().parent().find('.form-add').toggleClass('open');
	$grid.masonry();
	$(this).toggleClass('bt open bt-cancel');
	$(this).parent().parent().find('.form-add').slideToggle(function(){
		$grid.masonry();
	});

	if($(this).hasClass('open')){
		$(this).html('Annuler')
	}

	else{
		$(this).html('Ajouter un cadeau');
	}

});

// Edition des cadeaux

$('.ico-edit').click(function(){
	$('.form-edit').slideUp(function(){
		$grid.masonry();
	});
	$(this).parent().parent().find('.form-edit').slideToggle(function(){
		$grid.masonry();
	});
});

$('.cancel-edit-gift').click(function(){
	$(this).parent().parent().slideToggle(function(){
		$grid.masonry();
	});
});

// Suppression cadeau

$('.submit-delete').click(function(){
	$(this).parent().find('.confirmation-suppression').fadeIn();
});

$('.annuler-suppression').click(function(){
	$(this).parent().fadeOut();
});

// Modal ajouter personne

	// Le titre change quand on tape

$('.modal-add-user .input-name').keyup(function(){

	var nom_personne = $(this).val();
	var phrase_ajout = 'Ajouter '+nom_personne;

	$(this).parent().parent().find('h2').html(nom_personne);

	$(this).parent().parent().find('input[type="submit"]').attr('value',phrase_ajout);

	if(nom_personne == ''){
		$(this).parent().parent().find('h2').html('Ajouter une personne');
		$(this).parent().parent().find('input[type="submit"]').attr('value','Ajouter la personne');
	}
});

	// L'illu change quand on la sélectionne

$('.modal-add-user .wrapper-illus').change(function(){
	var illu_name = $(this).find('input[type="radio"]:checked').attr('class');
	$(this).parent().parent().find('.illu').html('<img src="img/'+illu_name+'.png"/>');
});

// Afficher le modal au click sur 'ajouter un perso'

$('.add-user button').click(function(){
	$('.modal-user').fadeIn(function(){
		$grid.masonry();
	})
});

// La virer si on annule
$('.modal-user .bt-cancel').click(function(){
	$('.modal-user').fadeOut(function(){
		$grid.masonry();
	})
});

// faire apparaitre l'edit des user

$('.ico-edit-user').click(function(){
	$(this).parent().parent().find('.edit-user').slideToggle(function(){
		$grid.masonry();
	});
});

// ranger l'edit user si on annule

$('.edit-user .bt-cancel').click(function(){
	$(this).parent().parent().parent().slideToggle(function(){
		$grid.masonry();
	});
});

// Edit user

	// Le titre change quand on tape

$('.edit-user .input-name').keyup(function(){

	var nom_personne = $(this).val();
	var phrase_ajout = 'Modifier '+nom_personne;


	$(this).parent().parent().find('input[type="submit"]').attr('value',phrase_ajout);

	if(nom_personne == ''){
		$(this).parent().parent().find('input[type="submit"]').attr('value','Modifier la personne');
	}
});

	// L'illu change quand on la sélectionne

$('.edit-user .wrapper-illus').change(function(){
	var illu_name = $(this).find('input[type="radio"]:checked').attr('class');
	$(this).parent().parent().parent().find('.illu').html('<img src="img/'+illu_name+'.png"/>');
});

// Delete user

$('.ico-delete-user').click(function(){
	$(this).parent().find('.confirmation-suppression').fadeIn();
});



// Edit cadeau ajax

$('.form-edit').on('submit', function(e) {
    e.preventDefault();

    var $this = $(this);

    // Je récupère les valeurs
    var gift_title = $(this).find('input[name="gift-name"]').val();
    var gift_url = $(this).find('input[name="gift-url"]').val();
    var gift_description = $(this).find('input[name="gift-description"]').val();
    var gift_user = $(this).find('input[name="gift-user"]').val();

    // Je vérifie une première fois pour ne pas lancer la requête HTTP
    // si je sais que mon PHP renverra une erreur
    if(gift_title === '') {
        alert('Les champs doivent êtres remplis');
    } else {
        // Envoi de la requête HTTP en mode asynchrone
        $.ajax({
            url: $this.attr('action'), 
            type: $this.attr('method'),
            data: $this.serialize(),
            dataType: 'json', // JSON
            success: function(json) {
                if(json.reponse === 'success') {
                	gift_title = json.gift_title;
                	gift_url = json.gift_url;
                	gift_description = json.gift_description;

                	//ce qui se passe si succès
                	
                	$this.parent().find('.gift-title').html(gift_title);

                    // Gérer le lien
                    $this.parent().find('.gift-link').remove();

                    if(gift_url != ''){
                        $this.parent().find('.wrapper-title').append('<a title="Lien vers le cadeau" href="'+gift_url+'" class="gift-link"><svg viewBox="0 0 100 100" class="icon"><use xlink:href="#icon-link"></use></svg></a>');
                    }

                    //Gérer la description

                    $this.parent().find('.gift-description').remove();

                    if(gift_description != ''){
                        $this.parent().find('.wrapper-title').after('<p class="gift-description">'+gift_description+'</p>');
                    }
                	
                	$this.slideUp(function(){
                		$grid.masonry();
                	});

                } else {
                    alert('Erreur : '+ json.reponse);
                }
            }
        });
    }
});

// Edit user AJAX

$('.edit-user form').on('submit', function(e) {
    e.preventDefault();

    var $this = $(this);

    // Je récupère les valeurs
    var username = $(this).find('input[name="username"]').val();

    // Je vérifie une première fois pour ne pas lancer la requête HTTP
    // si je sais que mon PHP renverra une erreur
    if(username === '') {
        alert('Les champs doivent êtres remplis');
    } else {
        // Envoi de la requête HTTP en mode asynchrone
        $.ajax({
            url: $this.attr('action'), 
            type: $this.attr('method'),
            data: $this.serialize(),
            dataType: 'json', // JSON
            success: function(json) {
                if(json.reponse === 'success') {
                    username = json.username;

                    //ce qui se passe si succès
                    $this.parent().parent().find('h2').text(username);
                    $this.parent().slideUp(function(){
                        $grid.masonry();
                    })

                } else {
                    alert('Erreur : '+ json.reponse);
                }
            }
        });
    }
});




});
	
})(jQuery, this);
