<?php
/*
 * File contains csutom defined shortcuts
 */

function oxy_load_child_scripts() {
    wp_enqueue_style('child-style', get_stylesheet_directory_uri() . '/style.css', array('style'), false, 'all');
}

add_action('wp_enqueue_scripts', 'oxy_load_child_scripts');

//validation for custom taxonomy
//add_action('save_post', 'completion_validator', 10, 2);

function completion_validator($pid, $post) {
    // don't do on autosave or when new posts are first created
    if (( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) || $post->post_status == 'auto-draft')
        return $pid;
    // abort if not my custom type
    if ($post->post_type != 'oxy_content')
        return $pid;

    // init completion marker (add more as needed)
    $category_missing = false;
    $more_then_one_assigned = false;
    $summary_missing = false;

    // retrieve meta to be validated
    $assignedCategory = wp_get_post_terms($pid, 'oxy_content_category', array("fields" => "all"));
    // just checking it's not empty 
    if (empty($assignedCategory)) {
        $category_missing = true;
    }

    if (!$category_missing && count($assignedCategory) > 1) {
        $more_then_one_assigned = true;
    }

    //get value of summary
    $summary = get_field('summary', $post->ID);
    if (empty($summary)) {
        $summary_missing = true;
    }

    // on attempting to publish - check for completion and intervene if necessary
    if (( isset($_POST['publish']) || isset($_POST['save']) ) && $_POST['post_status'] == 'publish') {
        //  don't allow publishing while any of these are incomplete
        if ($category_missing || $more_then_one_assigned || $summary_missing) {
            global $wpdb;
            $wpdb->update($wpdb->posts, array('post_status' => 'pending'), array('ID' => $pid));
            // filter the query URL to change the published message
            if ($category_missing) {
                $message_number = $summary_missing ? 100 : 99;
                $filter_name = $summary_missing ? 'redirect_no_one_assigned_and_no_summary' : 'redirect_no_one_assigned';
            } else if ($more_then_one_assigned) {
                $message_number = $summary_missing ? 97 : 98;
                $filter_name = $summary_missing ? 'redirect_more_then_one_assigned_and_no_summary' : 'redirect_more_then_one_assigned';
            } else if ($summary_missing) {
                $message_number = 101;
                $filter_name = 'redirect_no_summary_assigned';
            }
            add_filter('redirect_post_location', $filter_name, $message_number);
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

function redirect_more_then_one_assigned_and_no_summary($location) {
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

Class Recent_Bloggers extends WP_Widget {

    function __construct() {
        parent::__construct(
// Base ID of your widget
                'wpb_widget',
// Widget name will appear in UI
                __('Recent Bloggers', 'wpb_widget_domain'),
// Widget description
                array('description' => __('Самые крутые блоггеры', 'wpb_widget_domain'),)
        );
    }

    function widget($args, $instance) {

        extract($args);

        $title = apply_filters('widget_title', empty($instance['title']) ? __('Recent Bloggers', THEME_FRONT_TD) : $instance['title'], $instance, $this->id_base);
        if (empty($instance['number']) || !$number = absint($instance['number']))
            $number = 5;

        $user_query = new WP_User_Query(array('orderby' => 'post_count', 'order' => 'DESC')); //new WP_Query( apply_filters( 'widget_posts_args', array( 'posts_per_page' => $number, 'no_found_rows' => true, 'post_status' => 'publish', 'ignore_sticky_posts' => true ) ) );
        // User Loop
        if (!empty($user_query->results)) {
            echo $before_widget;
            if ( $title )
                echo $before_title . $title . $after_title;
            ?>
            <ul>
            <?php
                $counter = 0;
                foreach ($user_query->results as $user) {
                    ++$counter;
            ?>
	    <div class="row-fluid">
	    <div class="span3">
            <div class="round-box box-mini box-colored">
                <?php echo get_avatar($user->ID, 300); ?>
            </div>
            </div>
	    <div class="span9">
            <h4>
               	<?php echo $user->user_nicename; ?>
            </h4>
            </div>
            </div>
            <?php if($counter == $number) break;} ?>
            </ul>

            <?php
            echo $after_widget;
            wp_reset_postdata();
        } else {
            echo 'No users found.';
        }
    }
    function form( $instance ) {
		$title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$number    = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of posts to show:' ); ?></label>
		<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>

<?php
	}
}

// replace default widgets
register_widget('Recent_Bloggers');
?>