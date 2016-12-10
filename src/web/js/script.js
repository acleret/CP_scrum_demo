$(document).ready(function() {
	$('body').append('<a href="#top" class="top_link" title="Revenir en haut de la page">Haut</a>');
	$('.top_link').css({
		'position':	'fixed',
		'right'   :	'20px',
		'bottom'  :	'50px',
		'display' :	'none',
		'padding'	:	'15px',
		'background' : '#fff',
		'-moz-border-radius'	:	'40px',
		'-webkit-border-radius'	:	'40px',
		'border-radius'	  :	'40px',
		'opacity'				  :	'1',
		'z-index'				  :	'2000',
		'color'           : '#fff',
		'background-color': '#222'
	});
	$(window).scroll(function(){
		posScroll = $(document).scrollTop();
		if(posScroll >=550) 
			$('.top_link').fadeIn(600);
		else
			$('.top_link').fadeOut(600);
	});
});
