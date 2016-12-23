$(function(){

	/*animacion menu*/
	$("#bg-menu").sticky({ topSpacing: 0 });
	$('#bg-menu').on('sticky-start', function() {
	 	// $("#social-header").css('height', '0px');
	 	$("#header").css('padding', '18px 0');

	 	$("#social-header").css('height', '0');
	 	$("#social-header ul").css('height', '0');

	 	$("#logo img").css('width', '120px');
	 	$("#logo img").css('margin-top', '0');

	 	$("#space-header").css('height', '200px');

	 	$("#menu-principal ul").css('margin-top', '28px');
	});

	$('#bg-menu').on('sticky-end', function() {
	 	// $("#social-header").css('height', '0px');
	 	$("#header").css('padding', '0');

	 	$("#social-header").css('height', '150px');
	 	$("#social-header ul").css('height', 'inherit');

	 	$("#logo img").css('width', '100%');
	 	$("#logo img").css('margin-top', '30px');

	 	$("#space-header").css('height', '210px');

	 	$("#menu-principal ul").css('margin-top', '10px');
	});

	/*hash menu*/
	$('a[href*="#"]:not([href="#"])').click(function() {
	    if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
	      var target = $(this.hash);
	      target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
	      if (target.length) {
	        $('html, body').animate({
	          scrollTop: target.offset().top
	        }, 1000);
	        return false;
	      }
	    }
	});

});