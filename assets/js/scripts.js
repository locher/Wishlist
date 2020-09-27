$(document).ready(function() {
   
	//Close modale

	$('#close-modale').click(function(){
		$(this).parent().toggleClass('closed');
	});

	$('#add-gift-bt').click(function(){
		console.log('clic');
		$(this).parent().find('.closed').toggleClass('closed');
	});

});