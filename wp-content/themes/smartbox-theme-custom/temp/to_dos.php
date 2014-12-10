<?php

/*
 * rtmp player for playing stream from wowza, it is only prototype.
 * Suggestion: create page and put coding row in it in order to give
 * possibility to enhance it
 */
function oxy_shortcode_rtmp_player($atts) {
    extract(shortcode_atts(array(
        'ip' => '84.200.83.137',
        'stream' => 'myStream'
                    ), $atts));
    $output = '<div class = "span4">';
    $output .= '<strong>Начало:</strong> 12.00 Ландау (14.00 Московское время, 13.00 Киевское, 06.00 восточное сша)';
    $output .= '</div>';
    $output .= '<div class = "span8">';
    $output .= '<div id = "playerygRpQJGcOwEP">';
    $output .= '<script src = "' . hb_get_host_jw_player_script() . '"></script>';
    $output .= '<script type = \'text/javascript\'>';
    $output .= 'jwplayer(\'playerygRpQJGcOwEP\').setup({';
    $output .= 'playlist: [{';
    $output .= 'image: "' . get_theme_root_uri() . '/smartbox-theme-custom/images/broadcast.jpg",';
    $output .= 'sources: [';
    $output .= '{ file: "rtmp://84.200.83.137/live/myStream.sdp", },';
    $output .= '],';
    $output .= '}],';
    $output .= 'sources: [{';
    $output .= 'file: "http://84.200.83.137:1935/vod/mp4:sample.mp4/manifest.f4m"';
    $output .= '}],';
    $output .= 'sources: [{';
    $output .= 'file: "http://84.200.83.137:1935/vod/mp4:sample.mp4/playlist.m3u8"';
    $output .= '}],';
    $output .= 'height: 360,';
    $output .= 'rtmp: {';
    $output .= 'subscribe: true';
    $output .= '},';
    $output .= 'width: 640';
    $output .= '});';
    $output .= '</script></div></div>';
    $atts = array(
        'title' => 'Прямая трансляция'
    );
    return oxy_shortcode_section($atts, $output);
}

//add_shortcode('rtmp_player', 'oxy_shortcode_rtmp_player');

/*only prototype,*/
function oxy_shortcode_js_player($atts) {
    extract(shortcode_atts(array(
        'sources' => '',
        'title' => '',
        'image' => '',
        'height' => '',
        'width' => ''
                    ), $atts));

    if (empty($image))
        $image = "\"" . get_theme_root_uri() . "/smartbox-theme-custom/images/broadcast.jpg\"";

    if (empty($height))
        $height = "200";

    if (empty($width))
        $width = "360";
    $uniqid = "player" . uniqid();
    $output = '<div id = "' . $uniqid . '">';
    $output .= '<script src = "' . hb_get_host_jw_player_script() . '"></script>';
    //$output .= '<script src = "http://84.200.83.37/wp-content/themes/smartbox-theme-custom/inc/js/JS_Player.js' . '"></script>';
    $output .= '<script type = \'text/javascript\'>';
    $output .= 'jwplayer(\'' . $uniqid . '\').setup({';
    $output .= ' height: ' . $height . ',';
    $output .= ' width: ' . $width . ',';
    $output .= 'playlist: [{';
    $output .= 'image: ' . $image . ',';
    $output .= 'sources: [';
    $sourcesArray = explode(",", $sources);
    $addComma = false;
    $counter = 0;
    foreach ($sourcesArray as $source) {
        if (isset($source)) {
            $counter = $counter + 1;
            if ($addComma)
                $output .= ',';
            $output .= '{ file: "' . $source . '", label: "' . $counter . '" }';
            $addComma = true;
        }
    }
    $output .= ']';
    $output .= '}]';
    $output .= '});';
    $output .= '</script></div>';
    $atts = array(
        'title' => $title
    );

    return $output;
}
//add_shortcode('js_player', 'oxy_shortcode_js_player');
?>
