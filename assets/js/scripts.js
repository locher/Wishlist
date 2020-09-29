$(document).ready(function() {
   
	//Close modale

	$('#close-modale').click(function(){
		$(this).parent().toggleClass('closed');
	});

	$('#add-gift-bt').click(function(){
		console.log('clic');
		$(this).parent().find('.closed').toggleClass('closed');
	});

	//Edit gift

	$('.edit-gift').on('click', function(){
		var giftID = $(this).attr('data-giftid');
		var giftName = $(this).parent().parent().find('h3').text();
		var giftUrl = $(this).parent().parent().find('a').attr('href');
		var giftDescription = $(this).parent().parent().find('.gift-description p').text();
		console.log(giftID);
		
		$('.gift-form').toggleClass('closed');
		$('.gift-form').find('[name="designation"]').val(giftName);
	});


	//Delete gift

	$('.bt-delete-gift').on('click',function(){
		$(this).parent().parent().find('.overlay-delete-gift').toggleClass('open');
	});

	$('.close-delete-bt-overlay').on('click',function(){
		$(this).parent().parent().toggleClass('open');
	})

});