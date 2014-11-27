<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
add_filter('get_avatar','change_avatar_css');
//this filter replace default avatar class by template class in order to show avatar as circle
function change_avatar_css($class) {
    $class = str_replace('avatar avatar-300 wp-user-avatar wp-user-avatar-300 alignnone photo', 'img-circle  avatar-300 photo', $class) ;
    return $class;
}
function oxy_create_logo_custom() {
    if( function_exists( 'icl_get_home_url' ) ) {
        $home_link = icl_get_home_url();
    }else {
        $home_link = site_url();
    }?>
    <!-- added class brand to float it left and add some left margins -->
    <a class="brand" href="<?php echo $home_link; ?>"> <img src="" class="logoButtonLink"></a>
<?php
}

/**
 * Loads theme scripts
 *
 * @return void
 *
 **/
function hb_load_scripts() {
    global $oxy_theme_options;
    global $wp_query;
    global $post;

    // load js
    wp_enqueue_script( 'bootstrap', JS_URI . 'bootstrap.js', array( 'jquery' ), '2.3.1', true );

    wp_enqueue_script( 'flexslider', JS_URI. 'jquery.flexslider-min.js', array('jquery'), '2.1', true );

    wp_enqueue_script( 'fancybox_pack' ,JS_URI. 'jquery.fancybox.pack.js' , array('jquery'), '2.1.4', true );

    wp_enqueue_script( 'fancybox_media' ,JS_URI. 'jquery.fancybox-media.js' , array('jquery'), '2.1.4', true );

    wp_enqueue_script( 'script', JS_URI . 'script.js',array( 'bootstrap' , 'jquery', 'flexslider' ), '1.0', true  );

    wp_enqueue_script( 'wpscript', JS_URI . 'wpscript.js', array( 'bootstrap' , 'jquery', 'flexslider' ), '1.0', true );

    // post may not be set if we are in shortcode editor
    if( isset($post) ){
        if (get_post_type( $post->ID ) == 'oxy_timeline'){
            $custom_fields = get_post_custom($post->ID);
            if ( isset ($custom_fields[THEME_SHORT.'_timeline']) ){
                // retrieve the post count for the category
                if($custom_fields[THEME_SHORT.'_timeline'][0] == " "){
                     wp_localize_script( 'wpscript', 'dynData', array('category' => "" , 'total_results' => wp_count_posts()) );
                }
                else{
                    $cat_name = get_term_by('name', $custom_fields[THEME_SHORT.'_timeline'][0] , 'category');
                    $cat_id = $cat_name->term_id;
                    $cat = get_category($cat_id);
                    $count = (int)$cat->count;
                    wp_localize_script( 'wpscript', 'dynData', array('category' => $custom_fields[THEME_SHORT.'_timeline'][0] , 'total_results' => $count) );
                }

            }
        }
    }

    // send stored date to the theme script
    // also send ajax url and nonce for sign up
    wp_localize_script( 'wpscript', 'localData', array(
        // URL to wp-admin/admin-ajax.php to process the request
        'ajaxurl'        => admin_url( 'admin-ajax.php' ),
        // generate a nonce with a unique ID "myajax-post-comment-nonce"
        // so that you can check it later when an AJAX request is sent
        'nonce'          => wp_create_nonce( 'oxygenna-sign-me-up-nonce' ),

        'posts_per_page' => get_option('posts_per_page'),
        )
    );

    // if we are on a page and we want to display a map , enqueue the google maps scripts
    if( isset($post) ) {
        if( is_page() || get_post_type( get_the_ID() ) == 'oxy_service' || get_post_type( get_the_ID() ) == 'oxy_timeline'  ){
             $custom_fields = get_post_custom($post->ID);
             if ( isset($custom_fields[THEME_SHORT.'_header_type']) ){
                if ( $custom_fields[THEME_SHORT.'_header_type'][0] == 'map' ){
                    wp_enqueue_script( 'google', 'https://maps.googleapis.com/maps/api/js?sensor=false' ,  array( 'jquery' ) );
                    wp_enqueue_script( 'map', home_url(). '/wp-content/themes/smartbox-theme-custom/inc/js/' . 'maps.js', array( 'jquery' , 'google' ) );

                    // get the coordinates from the metabox value
                    $coords =  $custom_fields['loc'][0];
                    if ( $coords ){
                        list( $lat, $lng ,$zoom) = explode( ',', $coords );
                    }
                    wp_localize_script( 'map', 'mapData', array(
                        'lat'   =>  $lat,
                        'lng'   =>  $lng,
                        'zoom'  =>  $zoom
                        )
                    );
                }
            }
        }
    }

    // check for social links on single page
    if( is_single() ) {
        if( oxy_get_option( 'fb_show' ) == 'show' ) {
            wp_enqueue_script( 'facebook', JS_URI . 'facebook.js', array(), '1.0', true );
        }
        if( oxy_get_option( 'twitter_show' ) == 'show' ) {
            wp_enqueue_script( 'twitter', JS_URI . 'twitter.js', array(), '1.0', true );
        }
        if( oxy_get_option( 'google_show' ) == 'show' ) {
            wp_enqueue_script( 'google', JS_URI . 'google.js', array(), '1.0', true );
        }
    }

    // add hover dropdown menus
    if( oxy_get_option( 'menu' ) == 'hover' ) {
        wp_enqueue_script( 'hover_menus', JS_URI . 'twitter-bootstrap-hover-dropdown.min.js',  array( 'bootstrap' , 'jquery' ), '1.0', true );
    }

    // load styles
    if( is_rtl() ) {
        wp_enqueue_style( 'bootstrap', CSS_URI . 'rtl/bootstrap.css', array(), false, 'all' );
        wp_enqueue_style( 'responsive', CSS_URI . 'rtl/responsive.css', array( 'bootstrap' ), false, 'all' );
        wp_enqueue_style( 'rtl', CSS_URI . 'rtl.css', array( 'style' ), false, 'all' );
    }
    else {
        wp_enqueue_style( 'bootstrap', CSS_URI . 'bootstrap.css', array(), false, 'all' );
        wp_enqueue_style( 'responsive', CSS_URI . 'responsive.css', array( 'bootstrap' ), false, 'all' );
    }
    wp_enqueue_style( 'font-awesome-all', CSS_URI . 'font-awesome-all.css', array( 'bootstrap' ), false, 'all' );
    wp_enqueue_style( 'font', CSS_URI . oxy_get_option('main_site_font'), array( 'bootstrap' ), false, 'all' );
    wp_enqueue_style( 'fancybox', CSS_URI . 'fancybox.css', array( 'bootstrap' ), false, 'all' );
    wp_enqueue_style( 'style', CSS_URI . 'style.css', array( 'bootstrap' ), false, 'all' );



}
add_action( 'wp_enqueue_scripts', 'hb_load_scripts' , 0);


