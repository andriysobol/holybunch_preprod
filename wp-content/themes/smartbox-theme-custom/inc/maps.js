/**
 * Oxygenna.com
 *
 * $Template:: *(TEMPLATE_NAME)*
 * $Copyright:: *(COPYRIGHT)*
 * $Licence:: *(LICENCE)*
 */

var current_host = window.location.host;
var current_path = window.location.pathname;
var current_language = null;

var markers = [
    ['Sankt-Peterburg', 59.93428, 30.33510],
    ['Samara', 53.20278, 50.14083],
    ['Kiev', 50.45010, 30.52340, 'http://maps.google.com/mapfiles/ms/icons/purple-dot.png']
];
var zoom = 4;

var all_hosts = {
    LOCALHOST: "localhost",
    HOLYBUNCH: "k.a." //überprüfen
}

var all_laguages = {
    GERMANY: "de",
    NETHERLANDS: "nl"
};

function getMarkers() {
    if (current_host === all_hosts.LOCALHOST) {
        current_language = current_path.split('/')[2]; //de, nl, but not ru
        for (var key in all_laguages) {
            if (current_language === all_laguages[key]) {
                if (current_language === all_laguages.GERMANY) {
                    zoom = 7;
                    markers = [
                        ['Landau in der Pfalz', 49.19889, 8.11856],
                        ['Siegburg', 50.79985, 7.20745]
                    ];
                }
                if (current_language === all_laguages.NETHERLANDS) {
                    zoom = 11;
                    markers = [
                        ['Nieuwegein', 52.02482, 5.09182]
                    ];
                }
            }
        }
    }
}

(function($) {

    $(document).ready(function($) {
        var map;
        var bounds = new google.maps.LatLngBounds();
        var styles = [
            {
                stylers: [
                    {saturation: -85}
                ]
            }, {
                featureType: 'road',
                elementType: 'geometry',
                stylers: [
                    {hue: "#002bff"},
                    {visibility: 'simplified'}
                ]
            }, {
                featureType: 'road',
                elementType: 'labels',
                stylers: [
                    {visibility: 'off'}
                ]
            }
        ],
                // Create a new StyledMapType object, passing it the array of styles,
                // as well as the name to be displayed on the map type control.
                styledMap = new google.maps.StyledMapType(styles, {name: 'roadmap'}),
        map = new google.maps.Map(document.getElementById('map'), {
            scrollwheel: false,
            center: new google.maps.LatLng(49.19889, 8.11856),
            mapTypeControlOptions: {
                mapTypeIds: [google.maps.MapTypeId.ROADMAP]
            }
        });


        getMarkers();


        for (i = 0; i < markers.length; i++) {
            var position = new google.maps.LatLng(markers[i][1], markers[i][2]);
            bounds.extend(position);
            marker = new google.maps.Marker({
                position: position,
                map: map,
                icon: markers[i][3]
            });
            // Automatically center the map fitting all markers on the screen
            map.fitBounds(bounds);

            (function(marker, i) {
                // add click event
                google.maps.event.addListener(marker, 'click', function() {
                    infowindow = new google.maps.InfoWindow({
                        content: markers[i][0]
                    });
                    infowindow.open(map, marker);
                });
            })(marker, i);
        }

        var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function(event) {
            this.setZoom(zoom);
            google.maps.event.removeListener(boundsListener);
        });

        //Associate the styled map with the MapTypeId and set it to display.
        map.mapTypes.set('map_style', styledMap);
        map.setMapTypeId('map_style');
    });

})(jQuery);