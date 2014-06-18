<?php

/*
 * File contains csutom defined shortcuts
 */

function oxy_load_child_scripts() {
    wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array('style'), false,'all' );
}
add_action( 'wp_enqueue_scripts', 'oxy_load_child_scripts');

//validation for custom taxonomy
//add_action('save_post', 'completion_validator', 10, 2);

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
  
    //get value of summary
    $summary = get_field('summary', $post->ID);
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

/* Allow only 1 column option on screen options
add_filter('screen_layout_columns', 'one_column_on_screen_options');
function one_column_on_screen_options($columns) {
    $columns['oxy_video'] = 1;
    return $columns;
}

// Allow only 1 column option on screen options for taxonomy
add_filter('screen_layout_columns', 'one_column_on_screen_options');
function one_column_on_teaching_topics_screen_options($columns) {
    $columns['teaching_topics'] = 1;
    return $columns;
}

// Ignore user preferences stored in DB, and serve only one column layout    
add_filter('get_user_option_screen_layout_oxy_content', 'one_column_layout');
function one_column_layout($option) {
    return 1;
}*/
?>
