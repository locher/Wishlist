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


$('body').on('click', '.bt-add-gift', function(){
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

$('body').on('click', '.ico-edit', function(){
	$('.form-edit').slideUp(function(){
		$grid.masonry();
	});
	$(this).parent().parent().find('.form-edit').slideToggle(function(){
		$grid.masonry();
	});
});

$('body').on('click', '.cancel-edit-gift', function(){
	$(this).parent().parent().slideToggle(function(){
		$grid.masonry();
	});
});

// Suppression cadeau

$('body').on('click', '.submit-delete', function(){
	$(this).parent().find('.confirmation-suppression').fadeIn();
});

$('body').on('click', '.annuler-suppression', function(){
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

$('body').on('click', '.add-user button', function(){    
	$('.modal-user').fadeIn(function(){
		$grid.masonry();
	})
    var pos_modal = $('#modal-add-user').offset();
    $(window).scrollTo(pos_modal.top - 200,300);
});

// La virer si on annule
$('body').on('click', '.modal-user .bt-cancel', function(){
	$('.modal-user').fadeOut(function(){
		$grid.masonry();
	})
});

// faire apparaitre l'edit des user

$('body').on('click', '.ico-edit-user', function(){
	$(this).parent().parent().find('.edit-user').slideToggle(function(){
		$grid.masonry();
	});
});

// ranger l'edit user si on annule

$('body').on('click', '.edit-user .bt-cancel', function(){
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
$('body').on('click', '.ico-delete-user', function(){
	$(this).parent().find('.confirmation-suppression').fadeIn();
});


// Ajout cadeau AJAX

$('body').on('submit', '.form-add', function(e){
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
                    var gift_id = json.gift_id;

                    var gift_url_code = '';
                    var gift_description_code = '';

                    if(gift_url != ''){
                        gift_url_code = '<a title="Lien vers le cadeau" href="'+gift_url+'" class="gift-link"><svg viewBox="0 0 100 100" class="icon"><use xlink:href="#icon-link"></use></svg></a>'
                    }

                     if(gift_description != ''){
                        gift_description_code = '<p class="gift-description">'+gift_description+'</p>'
                    }

                    $this.parent().find('ul').append('<li><div class="wrapper-title"><p class="gift-title">'+gift_title+'</p>'+gift_url_code+'<span class="submit-delete ico-trash"><svg viewBox="0 0 100 100" class="icon"><use xlink:href="#icon-ico-trash"></use></svg></span><div class="confirmation-suppression"><p>Êtes-vous sûr ?</p><form action="delete-gift.php" method="post"><input type="hidden" value="'+gift_id+'" name="gift-id"><input type="submit" class="confirm-suppression bt" value="Oui" /></form><p class="annuler-suppression">Non, annuler</p></div><span class="ico-edit" title="Éditer le cadeau"><svg viewBox="0 0 100 100" class="icon"><use xlink:href="#icon-ico-edit"></use></svg></span></div>'+gift_description_code+'<form class="form-gift form-edit" action="update-gift.php" method="post"><div class="wrapper-gift-input"><span><svg viewBox="0 0 100 100" class="icon"><use xlink:href="#icon-ico-item"></use></svg></span><input type="text" name="gift-name" required placeholder="Désignation" value="'+gift_title+'"></div><div class="wrapper-gift-input"><span><svg viewBox="0 0 100 100" class="icon"><use xlink:href="#icon-link"></use></svg></span><input type="text" name="gift-url" placeholder="Lien optionnel" value="'+gift_url+'"></div><textarea name="gift-description" id="" rows="3" placeholder="Détail optionnel">'+gift_description+'</textarea><input type="hidden" value="'+gift_id+'" name="gift-id"><input type="submit" class="bt bt-edit-gift" value="Modifier le cadeau"><div class="wrapper-bt-edit-gift"><span class="cancel-edit-gift bt-cancel">Annuler</span></div></form></li>').children(':last').hide().fadeIn(1000);               
                }

                $this.find("input[type=text], textarea").val("");
                
            }
        });
    }
});

// Edit cadeau ajax
$('body').on('submit', '.form-edit', function(e){
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
$('body').on('submit', '.edit-user form', function(e){
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

// delete gift et user AJAX
$('body').on('submit', '.confirmation-suppression form', function(e){
    e.preventDefault();

    var $this = $(this);

    $.ajax({
        url: $this.attr('action'), 
        type: $this.attr('method'),
        data: $this.serialize(),
        dataType: 'json', // JSON
        success: function(json) {
            if(json.reponse === 'success') {

                //ce qui se passe si succès
                $this.parent().parent().parent().fadeOut(function(){
                    $grid.masonry();
                })

            } else {
                alert('Erreur : '+ json.reponse);
            }
        }
    });
});
        
//Reserver un cadeau AJAX
$('body').on('submit', '#form-resa', function(e){
    e.preventDefault();

    var $this = $(this);

    $.ajax({
        url: $this.attr('action'), 
        type: $this.attr('method'),
        data: $this.serialize(),
        dataType: 'json', // JSON
        success: function(json) {
            if(json.reponse === 'success') {
                var gift_id = json.gift_id;

                //ce qui se passe si succès
                $this.parent().parent().addClass('reserve');               
                $this.parent().append('<form action="delete_reservation.php" id="cancel_resa" method="post"><input type="hidden" value="'+gift_id+'" name="gift-id"><input type="submit" value="Annuler" class="bt bt_annuler" title="Tu as indiqué vouloir réserver ce cadeau. Changé d\'avis ?"></form>');
                 $this.remove();


            } else {
                alert('Erreur : '+ json.reponse);
            }
        }
    });
});  
        
        
//Annuler une résa AJAX
$('body').on('submit', '#cancel_resa', function(e){
    e.preventDefault();

    var $this = $(this);

    $.ajax({
        url: $this.attr('action'), 
        type: $this.attr('method'),
        data: $this.serialize(),
        dataType: 'json', // JSON
        success: function(json) {
            if(json.reponse === 'success') {
                var gift_id = json.gift_id;

                //ce qui se passe si succès
                $this.parent().parent().removeClass('reserve');               
                $this.parent().append('<form action="gift-reservation.php" method="post" id="form-resa"><input type="hidden" value="'+gift_id+'" name="gift-id"><input type="submit" value="Réserver" class="bt_resa bt"></form>');
                 $this.remove();


            } else {
                alert('Erreur : '+ json.reponse);
            }
        }
    });
}); 
        

});
	
})(jQuery, this);
