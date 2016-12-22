$(function(){
	var map = new GMaps({
            el: "#mapa-contacto",
            lat: -37.4762325,
            lng: -72.3284743,
            zoom: 15,
            scrollwheel: false
        });
        map.addMarker({
            lat: -37.4762325,
            lng: -72.3284743,
            title: "CsCupcakes"
        });
});