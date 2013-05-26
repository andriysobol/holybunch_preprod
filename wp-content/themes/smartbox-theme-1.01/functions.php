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
    $more_then_one_assigned = false;
    $summary_missing = false;
    
    // retrieve meta to be validated
    $assignedCategory = wp_get_post_terms( $pid, 'oxy_content_category', array("fields" => "all") );
    // just checking it's not empty 
    if ( empty( $assignedCategory ) ) {
        $category_missing = true;
    }
    
    if (!$category_missing && count($assignedCategory) > 1){
        $more_then_one_assigned = true;
    }
    
    $custom_fields = get_post_custom($post->ID);
    $summary = (isset($custom_fields[THEME_SHORT . '_summary'])) ? $custom_fields[THEME_SHORT . '_summary'][0] : '';
    if(empty($summary)){
        $summary_missing = true;
    }
    // on attempting to publish - check for completion and intervene if necessary
    if ( ( isset( $_POST['publish'] ) || isset( $_POST['save'] ) ) && $_POST['post_status'] == 'publish' ) {
        //  don't allow publishing while any of these are incomplete
        if ( $category_missing || $more_then_one_assigned || $summary_missing) {
            global $wpdb;
            $wpdb->update( $wpdb->posts, array( 'post_status' => 'pending' ), array( 'ID' => $pid ) );
            // filter the query URL to change the published message
            if($category_missing){
                $message_number = $summary_missing ? 100 : 99;                
                $filter_name = $summary_missing ? 'redirect_no_one_assigned_and_no_summary' : 'redirect_no_one_assigned';
            }else if($more_then_one_assigned){
                $message_number = $summary_missing ? 97 : 98;                
                $filter_name = $summary_missing ? 'redirect_more_then_one_assigned_and_no_summary' : 'redirect_more_then_one_assigned';
            }else if($summary_missing){
                $message_number = 101;
                $filter_name = 'redirect_no_summary_assigned';
            }
            add_filter( 'redirect_post_location', $filter_name, $message_number );            
        }
    }
}

function redirect_no_summary_assigned($location) {
  remove_filter('redirect_post_location', __FUNCTION__, 101);
  $location = add_query_arg('message', 101, $location);
  return $location;
}

function redirect_no_one_assigned_and_no_summary($location) {
  remove_filter('redirect_post_location', __FUNCTION__, 100);
  $location = add_query_arg('message', 100, $location);
  return $location;
}

function redirect_no_one_assigned($location) {
  remove_filter('redirect_post_location', __FUNCTION__, 99);
  $location = add_query_arg('message', 99, $location);
  return $location;
}

function redirect_more_then_one_assigned($location) {
  remove_filter('redirect_post_location', __FUNCTION__, 98);
  $location = add_query_arg('message', 98, $location);
  return $location;
}

function redirect_more_then_one_assigned_and_no_summary($location){
  remove_filter('redirect_post_location', __FUNCTION__, 97);
  $location = add_query_arg('message', 97, $location);
  return $location;    
}

add_filter('post_updated_messages', 'my_post_updated_messages_filter');
function my_post_updated_messages_filter($messages) {
  $messages['post'][97] = __('Publish not allowed, more then one category assigned. Please select exact one category. And fill the summary.', THEME_FRONT_TD);
  $messages['post'][98] = __('Publish not allowed, more then one category assigned. Please select exact one category', THEME_FRONT_TD);
  $messages['post'][99] = __('Publish not allowed, please select one category', THEME_FRONT_TD);
  $messages['post'][100] = __('Publish not allowed, please select one category. And fill the summary.', THEME_FRONT_TD);
  $messages['post'][101] = __('Publish not allowed, please fill the summary.', THEME_FRONT_TD);
  return $messages;
}

// include extra theme specific code
include INCLUDES_DIR . 'frontend.php';
include INCLUDES_DIR . 'custom_posts.php';
include MODULES_DIR  . 'woosidebars/woosidebars.php';
