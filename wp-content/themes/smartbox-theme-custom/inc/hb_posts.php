<?php

/*
 * File contains custom defined post types
 */

/* --------------------- content ------------------------*/
$labels = array(
    'name'               => __('Content', THEME_ADMIN_TD),
    'singular_name'      => __('Content',  THEME_ADMIN_TD),
    'add_new'            => __('Add New',  THEME_ADMIN_TD),
    'add_new_item'       => __('Add New Content',  THEME_ADMIN_TD),
    'edit_item'          => __('Edit Content',  THEME_ADMIN_TD),
    'new_item'           => __('New Content',  THEME_ADMIN_TD),
    'all_items'          => __('All Content',  THEME_ADMIN_TD),
    'view_item'          => __('View Content',  THEME_ADMIN_TD),
    'search_items'       => __('Search Content',  THEME_ADMIN_TD),
    'not_found'          => __('No Content found',  THEME_ADMIN_TD),
    'not_found_in_trash' => __('No Content found in Trash', THEME_ADMIN_TD),
    'menu_name'          => __('Content',  THEME_ADMIN_TD)
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

$labels = array(
    'name'          => __( 'Skills', THEME_ADMIN_TD ),
    'singular_name' => __( 'Skill', THEME_ADMIN_TD ),
    'search_items'  =>  __( 'Search Skills', THEME_ADMIN_TD ),
    'all_items'     => __( 'All Skills', THEME_ADMIN_TD ),
    'edit_item'     => __( 'Edit Skill', THEME_ADMIN_TD),
    'update_item'   => __( 'Update Skill', THEME_ADMIN_TD),
    'add_new_item'  => __( 'Add New Skill', THEME_ADMIN_TD),
    'new_item_name' => __( 'New Skill Name', THEME_ADMIN_TD)
);

register_taxonomy(
    'oxy_content_skills',
    'oxy_content',
    array(
        'hierarchical' => true,
        'labels'       => $labels,
        'show_ui'      => true,
        'query_var'    => true,
    )
);

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
    'oxy_content_category',
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
}
add_action('init', 'unregister_taxonomy');
?>
