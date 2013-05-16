<?php
/**
 * Main functions file
 *
 * @package Smartbox
 * @subpackage Frontend
 * @since 0.1
 *
 * @copyright (c) 2013 Oxygenna.com
 * @license http://wiki.envato.com/support/legal-terms/licensing-terms/
 * @version 1.01
 */

require_once get_template_directory() . '/inc/core/theme.php';

// create theme
$theme = new OxyTheme(
    array(
        'theme_name'   => 'SmartBox',
        'theme_short'  => 'smartbox',
        'text_domain'  => 'smartbox_textdomain',
        'min_wp_ver'   => '3.4',
        'option-pages' => array(
            'general',
            'portfolio',
            '404',
            'flexslider',
            'permalinks',
            'advanced'
        ),
         'sidebars' => array(
            'sidebar'            => array( 'Main Sidebar', 'Main sidebar for blog and non full width pages' ),
            'above-nav-right'    => array( 'Top right', 'Above Navigation section to the right' ),
            'above-nav-left'     => array( 'Top left', 'Above Navigation section to the left' ),
            'footer-left'        => array( 'Footer left', 'Left footer section' ),
            'footer-right'       => array( 'Footer right', 'Right footer section' ),
        ),
        'widgets' => array(
            'Smartbox_twitter' => 'smartbox_twitter.php',
            'Smartbox_social'  => 'smartbox_social.php',
        ),
        'shortcodes' => array(
            'layouts',
            'features',
        ),
    )
);

// include extra theme specific code
include INCLUDES_DIR . 'frontend.php';
include INCLUDES_DIR . 'custom_posts.php';
include MODULES_DIR  . 'woosidebars/woosidebars.php';
