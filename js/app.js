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

	$(".item-producto").hover(function(){
		var btn = $(this);
		var id = btn.data('id');

		$(".fotos-producto").removeClass('mostrar');
		$(".fotos-producto-"+id).addClass('mostrar');

		$(".lista-productos-2 li a").removeClass('producto-seleccionado');
		btn.addClass('producto-seleccionado');
	});

	var map = new GMaps({
            el: "#mapa",
            lat: -37.4762325,
            lng: -72.3284743,
            zoom: 15,
            scrollwheel: false
        });
        map.addMarker({
            lat: -37.4762325,
            lng: -72.3284743,
            title: "GreenWalk"
        });

    var map2 = new GMaps({
            el: "#mapa-2",
            lat: -37.4762325,
            lng: -72.3284743,
            zoom: 15,
            scrollwheel: false
        });
        map2.addMarker({
            lat: -37.4762325,
            lng: -72.3284743,
            title: "GreenWalk"
        });

});