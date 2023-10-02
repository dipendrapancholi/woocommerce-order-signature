window.onload = function () {
	
	var wrapper	= document.getElementById( "signature-pad" ),
	clearButton	= wrapper.querySelector( "[data-action=clear]" ),
	saveButton	= wrapper.querySelector( "[data-action=save]" ),
	canvas		= wrapper.querySelector( "canvas" ),
	signaturePad;
	
	var sigOptions	= {
						penColor:			Woo_os_APP.wooos_pen_colour,
						/*backgroundColor:	Woo_os_APP.wooos_signaturepad_background*/
					};
	
	// Adjust canvas coordinate space taking into account pixel ratio,
	// to make it look crisp on mobile devices.
	// This also causes canvas to be cleared.
	function resizeCanvas() {
		
		// When zoomed out to less than 100%, for some very strange reason,
		// some browsers report devicePixelRatio as less than 1
		// and only part of the canvas is cleared then.
		var ratio =  Math.max(window.devicePixelRatio || 1, 1);
		canvas.width = canvas.offsetWidth * ratio;
		canvas.height = canvas.offsetHeight * ratio;
		canvas.getContext("2d").scale(ratio, ratio);
	}
	
	if( Woo_os_APP.no_screen_resize != true ) {
		window.onresize	= resizeCanvas;
	}
	resizeCanvas();
	
	signaturePad	= new SignaturePad( canvas, sigOptions );
	
	clearButton.addEventListener( "click", function (event) {
		
		signaturePad.clear();
		document.getElementById( 'wooos_hidden_signature' ).value	= '';
	});
	
	saveButton.addEventListener( "click", function (event) {
		
		if( signaturePad.isEmpty() ) {
			alert( "Please provide signature first." );
		} else {
			document.getElementById( 'wooos_hidden_signature' ).value	= signaturePad.toDataURL();
			//window.open( signaturePad.toDataURL() );
			
			var savedMessage = document.getElementById( 'woo-os-signature-saved' );
			
			savedMessage.className = '';
			savedMessage.style.display = 'block';
			
			setTimeout( function() {
				savedMessage.className = 'fade';
			}, 1000 ); //delay is in milliseconds
			
		}
	});
}
jQuery(document).ready(function($){
	var width = $(window).width();
	
	if (width <= '720') {
	        
			$('.m-signature-pad').addClass('sign-popup-btn');
			
	        $('.m-signature-pad').click(function(e){
					e.stopPropagation();
				if( !$(this).hasClass('signature-popup') ){
					$(this).removeClass('sign-popup-btn');
					$(this).before('<div class="overlay-mobile"></div>');
					$(this).addClass('signature-popup');
				}
				
			});
			
	}
	
	$('body').click(function() {
		$(".m-signature-pad").removeClass('signature-popup');
		$(".m-signature-pad").addClass('sign-popup-btn');
		$(".overlay-mobile").remove();
	});
	
	$('.wooos-save').click(function() {
	
		setTimeout(function(){
			$(".m-signature-pad").removeClass('signature-popup');
			$(".m-signature-pad").addClass('sign-popup-btn');
			$(".overlay-mobile").remove();
		}, 1000);
	});
	
	$(window).resize(function () {
	    if (width <= '720') {
	        $('.m-signature-pad').addClass('sign-popup-btn');
	    }
	});
});
