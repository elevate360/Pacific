jQuery(document).ready(function($){
	
	$('.sticky-side-menu').on('click' , '.menu-toggle', function(){
		
		$(this).parent('.sticky-side-menu').toggleClass('active');
		$(this).toggleClass('active');
	});
	
});
