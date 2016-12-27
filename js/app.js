$(function(){

	/*animacion menu*/
	$("#bg-menu").sticky({ topSpacing: 0 });
	$('#bg-menu').on('sticky-start', function() {
	 	// $("#social-header").css('height', '0px');

	 	$("#social-header").css('height', '0');
	 	$("#social-header ul").css('height', '0');

	 	if ((screen.width>=1024)) {
	 		$("#header").css('padding', '18px 0');
			$("#logo img").css('width', '120px');
	 		$("#logo img").css('margin-top', '0');
		}else{
	 		$("#header").css('padding', '0');
			$("#logo img").css('width', '0');
			$("#menu-principal").css('margin-top', '20px');
		}

	 	$("#space-header").css('height', '200px');

	 	$("#menu-principal ul").css('margin-top', '28px');
	});

	$('#bg-menu').on('sticky-end', function() {
	 	// $("#social-header").css('height', '0px');

	 	$("#social-header").css('height', '150px');
	 	$("#social-header ul").css('height', 'inherit');

	 	if ((screen.width>=1024)) {
	 		$("#header").css('padding', '0');
			$("#logo img").css('width', '100%');
		 	$("#logo img").css('margin-top', '30px');
		}else{
			$("#logo img").css('width', '190px');
			$("#menu-principal").css('margin-top', '150px');
		}

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

	$(".item-producto").hover(function(e){
		var btn = $(this);
		var id = btn.data('id');

		e.preventDefault();

		$(".fotos-producto").removeClass('mostrar');
		$(".fotos-producto-"+id).addClass('mostrar');

		$(".lista-productos-2 li a").removeClass('producto-seleccionado');
		btn.addClass('producto-seleccionado');
	});

	$(".item-producto").on('click', function(e){
		e.preventDefault();
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

    $("#btn_adinistrar_servicio").on('click', function(){
		$("#modal_servicios").modal('show');
    });

    $("#guardar_form_servicio").on('click', function(){

    	var form_servicios = $("#form_servicios");

		form_servicios.validate({
            rules: {

                titulo: {
					required: true
				},
                descripcion: {
                    required: true
                }
            },
            messages: {

                titulo: {
                    required: 'Campo requerido',
                },
                descripcion: {
                    required: 'Campo requerido',
                }
            },
            errorPlacement: function(error, element) {
                if (element.is(":radio") || element.is(":checkbox")) {
                    element.closest('.option-group').after(error);
                }
                else {
                    error.insertAfter(element);
                }
            }
        });

        if (form_servicios.valid(true)) {

			var datos = new FormData( form_servicios[0] );

			$.ajax({
				url: Routing.generate('ajax_guardar_servicio'),
				data: datos,
				dataType: 'json',
				method: 'post',
				contentType:false,
				processData:false,
				cache:false
			}).done(function(json){
				if(json.result)
				{
					location.reload(true);
				}
			});
		}

	});

	$("#btn_agregar_producto").on('click', function(){
		var texto = $("#input_titulo_producto").val();

		if(texto != '')
		{
			$.ajax({
				url: Routing.generate('ajax_guardar_producto'),
				data: {texto: texto},
				dataType: 'json',
				method: 'post'
			}).done(function(json){
				if(json.result)
				{
					location.reload(true);
				}
			});
		}
	});

	$(".btn_eliminar_servicio").on('click', function(e){
		e.preventDefault();

		var btn = $(this);
		var id = btn.data('id');

		$.ajax({
			url: Routing.generate('ajax_eliminar_servicio'),
			data: {id: id},
			dataType: 'json',
			method: 'post'
		}).done(function(json){
			if(json.result)
			{
				location.reload(true);
			}
		});
	});

	$(".input_foto_producto").change(function(){
		var btn = $(this);
		var id = btn.data('id');
		var form = btn.parent();

		var datos = new FormData( form[0] );
		datos.append('id', id);

		$.ajax({
			url: Routing.generate('ajax_guardar_foto_producto'),
			data: datos,
			dataType: 'json',
			method: 'post',
			contentType:false,
			processData:false,
			cache:false
		}).done(function(json){
			if(json.result)
			{
				location.reload(true);
			}
		});

	});

	$(".btn_eliminar_producto").on('click', function(e){
		e.preventDefault();

		var btn = $(this);
		var id = btn.data('id');

		$.ajax({
			url: Routing.generate('ajax_eliminar_producto'),
			data: {id: id},
			dataType: 'json',
			method: 'post'
		}).done(function(json){
			if(json.result)
			{
				location.reload(true);
			}
		});

	});

});