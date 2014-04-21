<?php
/**
 * Child Theme functions loads the main theme class and extra options
 * @Author: Andriy Sobol
 */

require_once 'theme.php';
$theme = new OxyCustomTheme(
    array(
        'theme_name'   => 'SmartBox Child',
        'theme_short'  => 'smartbox_child',
        'text_domain'  => 'smartbox_child_textdomain',
        'min_wp_ver'   => '3.4',
        'option-pages' => array(
            'general',
            'portfolio',
            'blog',
            'flexslider',
            'permalinks',
            '404',
            'advanced'
        ),
         'sidebars' => array(
            'sidebar'            => array( 'Main Sidebar', 'Main sidebar for blog and non full width pages' ),
            'above-nav-right'    => array( 'Top right', 'Above Navigation section to the right' ),
            'above-nav-left'     => array( 'Top left', 'Above Navigation section to the left' ),
            'footer-left'        => array( 'Footer left', 'Left footer section' ),
            'footer-right'       => array( 'Footer right', 'Right footer section' ),
        ),
        'shortcodes' => array(
            'layouts',
            'features',
        ),
    )
);

//add_filter( "getarchives_where","node_custom_post_type_archive",10,2);
function node_custom_post_type_archive($where, $args) {
    $post_type = isset($args["post_type"]) ? $args["post_type"] : "post";
    $where = "WHERE post_type = " . $post_type . " AND post_status = \"publish\"";
    return $where;
}

require_once CUSTOM_INCLUDES_DIR . 'hb_frontend.php'; 
require_once CUSTOM_INCLUDES_DIR . 'hb_posts.php';
require_once CUSTOM_INCLUDES_DIR . 'hb_functions.php';
