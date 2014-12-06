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
            'sidebar-videos'     => array( 'Video Sidebar', 'Video sidebar for video archive' ),
            'sidebar-texts'     => array( 'Text Sidebar', 'Text sidebar for text archive' ),
            'sidebar-audios'     => array( 'Audio Sidebar', 'Audio sidebar for audio archive' ),
            'sidebar-music'     => array( 'Music Sidebar', 'Sidebar for music side' ),
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

/*
 * $options = array(
            'name' => $name,
            'description'=> $desc,
            'before_widget' => '<div id="%1$s" class="sidebar-widget ' . $class  . ' %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="sidebar-header">',
            'after_title' => '</h3>',
        );
        if( null !== $id ) {
            $options['id'] = $id;
        }
        register_sidebar( $options );
 */
// Debug info
function onlygood_debug() {
 $info = '<div style="position: fixed; bottom: 10px; width: 120px; padding: 5px; line-height: 15px; color: #fff; font-size: 11px; background: rgba(0,0,0,.7);">';
  $info .= get_num_queries() . ' queries in ';
  $info .= timer_stop();
  $info .= ' sec.<br />memory: '.round(memory_get_usage()/1024/1024, 2).'mb';
 $info .= '</div>';

 echo $info;
}

function oxy_custom_child_search_form( $form ) {
    $output = '<form role="search" method="get" id="searchform" action="' . home_url( '' ) . '\">';
    $output.= '<input type="text" name="s" id="s" value="Enter keywords ..." onfocus="if(this.value==this.defaultValue)this.value=\'\';" onblur="if(this.value==\'\')this.value=this.defaultValue;"/><br />';
    $output.= '	<select name="post_type">';
    $output.= '<option value="">Choose Category:</option>';
    $output.= '		<option value="">All Categories</option>';
    $output.= '	<option value="post_type_a">Post Type A</option>';
    $output.= '		<option value="post_type_b">Post Type B</option>';
    $output.= '		 <option value="post_type_c">Post Type C</option>';
    $output.= '	</select><br />';
    $output.= '	<input type="submit" id="searchsubmit" value="Search Help" />';
    $output.= '</form>';

    return $output;
}

add_filter( 'get_search_form', 'oxy_custom_child_search_form' );

require_once CUSTOM_INCLUDES_DIR . 'hb_frontend.php'; 
require_once CUSTOM_INCLUDES_DIR . 'hb_posts.php';
require_once CUSTOM_INCLUDES_DIR . 'hb_functions.php';
require_once CUSTOM_HELPERS_DIR . 'hb_custom_post_types_archive.php';
