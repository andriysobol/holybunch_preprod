/**
 * Oxygenna.com
 *
 * $Template:: *(TEMPLATE_NAME)*
 * $Copyright:: *(COPYRIGHT)*
 * $Licence:: *(LICENCE)*
 */

(function( $ ){

    $(document).ready(function($){
        var map;
        var bounds = new google.maps.LatLngBounds();
        var styles = [
            {
                stylers: [
                    { saturation: -85 }
                ]
            },{
                featureType: 'road',
                elementType: 'geometry',
                stylers: [
                    { hue: "#002bff" },
                    { visibility: 'simplified' }
                ]
            },{
                featureType: 'road',
                elementType: 'labels',
                stylers: [
                    { visibility: 'off' }
                ]
            }
        ],
        // put your locations lat and long here
        lat  = mapData.lat,
        lng  = mapData.lng,

        // Create a new StyledMapType object, passing it the array of styles,
        // as well as the name to be displayed on the map type control.
        styledMap = new google.maps.StyledMapType(styles,
          {name: 'roadmap'}),

        // Create a map object, and include the MapTypeId to add
        // to the map type control.
        mapOptions = {
          zoom: parseInt(mapData.zoom, 10),
          scrollwheel: false,
          center: new google.maps.LatLng( lat, lng ),
          mapTypeControlOptions: {
              mapTypeIds: [google.maps.MapTypeId.ROADMAP]
          }
        },
        map = new google.maps.Map(document.getElementById('map'), mapOptions);
           var markers = [
        ['London Eye, London', 51.503454,-0.119562],
        ['Palace of Westminster, London', 51.499633,-0.124755],
        ['Palace of Westminster, London', 45.499633,-0.124755]

    ];     

    // Loop through our array of markers & place each one on the map  
    for( i = 0; i < markers.length; i++ ) {
        var position = new google.maps.LatLng(markers[i][1], markers[i][2]);
        bounds.extend(position);
        marker = new google.maps.Marker({
            position: position,
            map: map
        });

        // Automatically center the map fitting all markers on the screen
        map.fitBounds(bounds);
    }
    
     var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function(event) {
        this.setZoom(4);
        google.maps.event.removeListener(boundsListener);
    });
        
        //Associate the styled map with the MapTypeId and set it to display.
        map.mapTypes.set('map_style', styledMap);
        map.setMapTypeId('map_style');

    });

})( jQuery );