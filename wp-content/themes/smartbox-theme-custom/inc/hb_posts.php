<?php

/*
 * File contains custom defined post types
 */

/* --------------------- content ------------------------*/
$labels = array(
    'name'               => __('Text', THEME_ADMIN_TD),
    'singular_name'      => __('Text',  THEME_ADMIN_TD),
    'add_new'            => __('Add New',  THEME_ADMIN_TD),
    'add_new_item'       => __('Add New Text Item',  THEME_ADMIN_TD),
    'edit_item'          => __('Edit Text Item',  THEME_ADMIN_TD),
    'new_item'           => __('New Text Item',  THEME_ADMIN_TD),
    'all_items'          => __('All Text Items',  THEME_ADMIN_TD),
    'view_item'          => __('View Text Item',  THEME_ADMIN_TD),
    'search_items'       => __('Search Text Items',  THEME_ADMIN_TD),
    'not_found'          => __('No Text Item found',  THEME_ADMIN_TD),
    'not_found_in_trash' => __('No Text Item found in Trash', THEME_ADMIN_TD),
    'menu_name'          => __('Text',  THEME_ADMIN_TD)
);

$args = array(
    'labels'             => $labels,
    'public'             => true,
    'publicly_queryable' => true,
    'show_ui'            => true,
    'show_in_menu'       => true,
    'query_var'          => true,
    'capability_type'    => 'post',
    'has_archive'        => true,
    'hierarchical'       => false,
    'menu_position'      => null,
    'menu_icon'          => ADMIN_ASSETS_URI . 'images/staff.png',
    'supports'           => array( 'title', 'editor', 'thumbnail', 'post-formats' )
);
register_post_type('oxy_content', $args);

/* --------------------- video ------------------------*/
$labels = array(
    'name'               => __('Video', THEME_ADMIN_TD),
    'singular_name'      => __('Video',  THEME_ADMIN_TD),
    'add_new'            => __('Add New',  THEME_ADMIN_TD),
    'add_new_item'       => __('Add New Video Item',  THEME_ADMIN_TD),
    'edit_item'          => __('Edit Video Item',  THEME_ADMIN_TD),
    'new_item'           => __('New Video Item',  THEME_ADMIN_TD),
    'all_items'          => __('All Video Items',  THEME_ADMIN_TD),
    'view_item'          => __('View Video Item',  THEME_ADMIN_TD),
    'search_items'       => __('Search Video Items',  THEME_ADMIN_TD),
    'not_found'          => __('No Video Item found',  THEME_ADMIN_TD),
    'not_found_in_trash' => __('No Video Item found in Trash', THEME_ADMIN_TD),
    'menu_name'          => __('Video',  THEME_ADMIN_TD)
);

$args = array(  
    'labels'             => $labels,
    'public'             => true,
    'publicly_queryable' => true,
    'show_ui'            => true,
    'show_in_menu'       => true,
    'query_var'          => true,
    'capability_type'    => 'post',
    'has_archive'        => true,
    'hierarchical'       => false,
    'menu_position'      => null,
    'menu_icon'          => ADMIN_ASSETS_URI . 'images/staff.png',
    'supports'           => array( 'title', 'editor', 'thumbnail', 'post-formats' )
);
register_post_type('oxy_video', $args);


/* --------------------- audio ------------------------*/
$labels = array(
    'name'               => __('Audio', THEME_ADMIN_TD),
    'singular_name'      => __('Audio',  THEME_ADMIN_TD),
    'add_new'            => __('Add New',  THEME_ADMIN_TD),
    'add_new_item'       => __('Add New Audio Item',  THEME_ADMIN_TD),
    'edit_item'          => __('Edit Audio Item',  THEME_ADMIN_TD),
    'new_item'           => __('New Audio Item',  THEME_ADMIN_TD),
    'all_items'          => __('All Audio Items',  THEME_ADMIN_TD),
    'view_item'          => __('View Audio Item',  THEME_ADMIN_TD),
    'search_items'       => __('Search Audio Items',  THEME_ADMIN_TD),
    'not_found'          => __('No Audio Item found',  THEME_ADMIN_TD),
    'not_found_in_trash' => __('No Audio Item found in Trash', THEME_ADMIN_TD),
    'menu_name'          => __('Audio',  THEME_ADMIN_TD)
);

$args = array(
    'labels'             => $labels,
    'public'             => true,
    'publicly_queryable' => true,
    'show_ui'            => true,
    'show_in_menu'       => true,
    'query_var'          => true,
    'capability_type'    => 'post',
    'has_archive'        => true,
    'hierarchical'       => false,
    'menu_position'      => null,
    'menu_icon'          => ADMIN_ASSETS_URI . 'images/staff.png',
    'supports'           => array( 'title', 'editor', 'thumbnail', 'post-formats' )
);
register_post_type('oxy_audio', $args);

$labels = array(
    'name'          => __( 'Categorys', THEME_ADMIN_TD ),
    'singular_name' => __( 'Category', THEME_ADMIN_TD ),
    'search_items'  =>  __( 'Search Categorys', THEME_ADMIN_TD ),
    'all_items'     => __( 'All Categorys', THEME_ADMIN_TD ),
    'edit_item'     => __( 'Edit Category', THEME_ADMIN_TD),
    'update_item'   => __( 'Update Category', THEME_ADMIN_TD),
    'add_new_item'  => __( 'Add New Category', THEME_ADMIN_TD),
    'new_item_name' => __( 'New Category Name', THEME_ADMIN_TD)
);

register_taxonomy(
    'oxy_content',
    array(
        'hierarchical' => true,
        'labels'       => $labels,
        'show_ui'      => true,
    )
);

//this function unregister oxy_staff post type which registered in parent theme
function unregister_taxonomy(){
    register_post_type('oxy_staff', array());
    register_post_type('oxy_testimonial', array());
    register_post_type('oxy_timeline', array());
    register_post_type('oxy_portfolio_image', array());
    register_post_type('oxy_service', array());
}
add_action('init', 'unregister_taxonomy');
  
?>
