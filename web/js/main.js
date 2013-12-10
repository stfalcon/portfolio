var nav = $('header nav');

// show mobile navigation
$('.show-mobile-nav').on('click', function(){
    nav.fadeIn(200);
});

$('.close-mobile-nav').on('click', function(){
    nav.fadeOut(200);
});

if(!$('html').hasClass('lt-ie10')) {
	enquire.register("screen and (min-width:620px)", {
	    match: function() {
	    	nav.show();
	    },
	    unmatch: function(){
	    	nav.hide();
	    }
	})
}
