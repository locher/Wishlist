$(document).ready(function() {
	
   	////////////////////////////////
	//Close modale
	////////////////////////////////

	$('.close-modale').click(function(){
		$(this).parent().toggleClass('closed');
	});

	$('#add-gift-bt').click(function(){
		$('.add-gift-form').find('.closed').toggleClass('closed');
	});

	////////////////////////////////
	//Edit gift
	////////////////////////////////

	$('.edit-gift').on('click', function(){
		var giftID = $(this).attr('data-giftid');
		var giftName = $(this).parent().parent().find('h3').text();
		var giftUrl = $(this).parent().parent().find('a').attr('href');
		var giftDescription = $(this).parent().parent().find('.gift-description p').text();
		console.log(giftID);
		
		$('.edit-gift-form').find('.gift-form').toggleClass('closed');
		$('.edit-gift-form').find('[name="designation"]').val(giftName);
		$('.edit-gift-form').find('[name="link"]').val(giftUrl);
		$('.edit-gift-form').find('[name="description"]').val(giftDescription);
		$('.edit-gift-form').find('[name="currentGift"]').val(giftID);
	});

	//Clean form when close modal

	$('.edit-gift-form .close-modale').on('click', function(){
		$('.edit-gift-form').find('[name="designation"]').val('');
		$('.edit-gift-form').find('[name="link"]').val('');
		$('.edit-gift-form').find('[name="description"]').val('');
		$('.edit-gift-form').find('[name="currentGift"]').val('');
	});

	////////////////////////////////
	//Delete gift
	////////////////////////////////

	$('.bt-delete-gift').on('click',function(){
		$(this).parent().parent().find('.overlay-delete-gift').toggleClass('open');
	});

	$('.close-delete-bt-overlay').on('click',function(){
		$(this).parent().parent().toggleClass('open');
	})

	////////////////////////////////
	// Edit user
	////////////////////////////////
	$('#child-account').on('click', function(){
		$('#choiceParent').toggleClass('hide');
	});

});