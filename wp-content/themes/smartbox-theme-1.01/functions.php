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

//add automatic excerpt
function excerpt_read_more_link($output) {
 global $post;
 return $output . '<a href="'. get_permalink($post->ID) . '"> Read More...</a>';
}
add_filter('the_excerpt', 'excerpt_read_more_link');


//validation for custom taxonomy
add_action('save_post', 'completion_validator', 10, 2);

function completion_validator($pid, $post) {
    // don't do on autosave or when new posts are first created
    if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || $post->post_status == 'auto-draft' ) return $pid;
    // abort if not my custom type
    if ( $post->post_type != 'oxy_content' ) return $pid;

    // init completion marker (add more as needed)
    $category_missing = false;

    // retrieve meta to be validated
    $mymeta = wp_get_post_terms( $pid, 'oxy_content_category', array("fields" => "all") );
    // just checking it's not empty 
    if ( empty( $mymeta ) ) {
        $category_missing = true;
    }
 
    // on attempting to publish - check for completion and intervene if necessary
    if ( ( isset( $_POST['publish'] ) || isset( $_POST['save'] ) ) && $_POST['post_status'] == 'publish' ) {
        //  don't allow publishing while any of these are incomplete
        if ( $category_missing ) {
            global $wpdb;
            $wpdb->update( $wpdb->posts, array( 'post_status' => 'pending' ), array( 'ID' => $pid ) );
            // filter the query URL to change the published message
            add_filter( 'redirect_post_location', 'my_redirect_post_location_filter', 99 );            
        }
    }
}

function my_redirect_post_location_filter($location) {
  remove_filter('redirect_post_location', __FUNCTION__, 99);
  $location = add_query_arg('message', 99, $location);
  return $location;
}

add_filter('post_updated_messages', 'my_post_updated_messages_filter');
function my_post_updated_messages_filter($messages) {
  $messages['post'][99] = __('Publish not allowed, please select one category', THEME_FRONT_TD);
  return $messages;
}

// include extra theme specific code
include INCLUDES_DIR . 'frontend.php';
include INCLUDES_DIR . 'custom_posts.php';
include MODULES_DIR  . 'woosidebars/woosidebars.php';
