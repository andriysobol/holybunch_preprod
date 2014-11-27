/**
 * Oxygenna.com
 *
 * $Template:: *(TEMPLATE_NAME)*
 * $Copyright:: *(COPYRIGHT)*
 * $Licence:: *(LICENCE)*
 */

/** 
 * https://sites.google.com/site/gmapsdevelopment/ 
 */
var ICON_PATH = "http://maps.google.com/mapfiles/ms/icons/";
var images = {
    PURPURLE: "purple.png",
    RED: "red.png",
    YELLOW: "yellow.png",
    BLUE: "blue.png",
    GREEN: "green.png",
    LIGHTBLUE: "lightblue.png",
    ORANGE: "orange.png",
    PINK: "pink.png"
}
/** 
 * http://universimmedia.pagesperso-orange.fr/geo/loc.htm
 */
var markers = [
    //Russia
    ['Sankt-Peterburg', 59.93428, 30.33510, ICON_PATH + images.ORANGE],
    ['Samara', 53.20278, 50.14083, ICON_PATH + images.ORANGE],
    ['Novosibirsk', 55.00835, 82.93573, ICON_PATH + images.ORANGE],
    ['Krasnojarsk', 56.01528, 92.87909, ICON_PATH + images.ORANGE],
    ['Severouralsk', 60.15444, 59.95639, ICON_PATH + images.ORANGE],
    ['Kandalaksha', 67.15000, 32.40000, ICON_PATH + images.ORANGE],
    //Ukraina
    ['Kiev', 50.45010, 30.52340, ICON_PATH + images.PURPURLE], 
    ['Odessa', 46.48253, 30.72331, ICON_PATH + images.PURPURLE],
    //Germany
    ['Landau in der Pfalz', 49.19889, 8.11856, ICON_PATH + images.RED],
    ['Siegburg', 50.79985, 7.20745, ICON_PATH + images.RED],
    //Netherlands
    ['Nieuwegein', 52.02482, 5.09182, ICON_PATH + images.YELLOW]
];
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
];
var infowindow = null;
var map;
var bounds = new google.maps.LatLngBounds();

(function($) {
    $(document).ready(function($) {
        styledMap = new google.maps.StyledMapType(styles, {name: 'roadmap'}),
        map = new google.maps.Map(document.getElementById('map'), {
            scrollwheel: false,
            mapTypeControlOptions: {
                mapTypeIds: [google.maps.MapTypeId.ROADMAP]
            }
        });

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

            myCenter = new google.maps.LatLng(59.93428, 30.33510);
            google.maps.event.addListenerOnce(map, 'bounds_changed', function() {
               // map.setCenter(myCenter);
                map.setZoom(4);
            });

            (function(marker, i) {
                google.maps.event.addListener(marker, 'click', function() {
                    if (infowindow) {
                        infowindow.close();
                    }
                    infowindow = new google.maps.InfoWindow({
                        content: '<div class="infoWindow">' + markers[i][0] + '</div>'
                    });
                    infowindow.open(map, marker);
                });
            })(marker, i);
        }
        map.mapTypes.set('map_style', styledMap);
        map.setMapTypeId('map_style');

    });

})(jQuery);