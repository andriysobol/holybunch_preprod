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

$capabilities = array(
    'publish_posts' => 'publish_text',
    'edit_posts' => 'edit_texts',
    'edit_others_posts' => 'edit_others_text',
    'edit_published_posts' => 'edit_published_text',
    'edit_private_posts' => 'edit_private_text',
    'delete_posts' => 'delete_text',
    'delete_private_posts' => 'delete_private_texts',
    'delete_published_posts' => 'delete_published_texts',
    'delete_others_posts' => 'delete_others_text',
    'read_private_posts' => 'read_private_text',
    'edit_post' => 'edit_text',
    'delete_post' => 'delete_text',
    'read_post' => 'read_text'
);

$args = array(
    'labels'             => $labels,
    'public'             => true,
    'publicly_queryable' => true,
    'show_ui'            => true,
    'show_in_menu'       => true,
    'query_var'          => true,
    'capability_type'    => 'post',
    'capabilities'       => $capabilities,
    'has_archive'        => true,
    'hierarchical'       => false,
    'menu_position'      => null,
    'menu_icon'          => ADMIN_ASSETS_URI . 'images/staff.png',
    'supports'           => array( 'title', 'editor', 'thumbnail', 'post-formats' ),
    'map_meta_cap'       => true
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

$capabilities = array(
    'publish_posts' => 'publish_video',
    'edit_posts' => 'edit_videos',
    'edit_others_posts' => 'edit_others_videos',
    'edit_published_posts' => 'edit_published_videos',
    'edit_private_posts' => 'edit_private_videos',
    'delete_posts' => 'delete_videos',
    'delete_private_posts' => 'delete_private_videos',
    'delete_published_posts' => 'delete_published_videos',
    'delete_others_posts' => 'delete_others_videos',
    'read_private_posts' => 'read_private_videos',
    'edit_post' => 'edit_video',
    'delete_post' => 'delete_video',
    'read_post' => 'read_video'
);

$args = array(  
    'labels'             => $labels,
    'public'             => true,
    'publicly_queryable' => true,
    'show_ui'            => true,
    'show_in_menu'       => true,
    'query_var'          => true,
    'capability_type'    => 'post',
    'capabilities'       => $capabilities,
    'rewrite'            => true,
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

$capabilities = array(
    'publish_posts' => 'publish_audios',
    'edit_posts' => 'edit_audios',
    'edit_others_posts' => 'edit_others_audios',
    'edit_published_posts' => 'edit_published_audios',
    'edit_private_posts' => 'edit_private_audios',
    'delete_posts' => 'delete_audios',
    'delete_private_posts' => 'delete_private_audios',
    'delete_published_posts' => 'delete_published_audios',
    'delete_others_posts' => 'delete_others_audios',
    'read_private_posts' => 'read_private_audios',
    'edit_post' => 'edit_audio',
    'delete_post' => 'delete_audio',
    'read_post' => 'read_audio'
);

$args = array(
    'labels'             => $labels,
    'public'             => true,
    'publicly_queryable' => true,
    'show_ui'            => true,
    'show_in_menu'       => true,
    'query_var'          => true,
    'capability_type'    => 'post',
    'capabilities'       => $capabilities,
    'has_archive'        => true,
    'hierarchical'       => false,
    'menu_position'      => null,
    'menu_icon'          => ADMIN_ASSETS_URI . 'images/staff.png',
    'supports'           => array( 'title', 'editor', 'thumbnail', 'post-formats' )
);
register_post_type('oxy_audio', $args);

$result = add_role(
    'Dummy',
    __( 'Dummy' ),
    array(
        'publish_audios' => true,
        'edit_audios' => true,
        'edit_others_audios' => true,
        'edit_published_audios' => true,
        'edit_private_audios' => true,
        'delete_audios' => true,
        'delete_private_audios' => true,
        'delete_published_audios' => true,
        'delete_others_audios' => true,
        'read_private_audios' => true,
        'edit_audio' => true,
        'delete_audio' => true,
        'read_audio' => true,

        'publish_video' => true,
        'edit_videos' => true,
        'edit_others_videos' => true,
        'edit_published_videos' => true,
        'edit_private_videos' => true,
        'delete_videos' => true,
        'delete_private_videos' => true,
        'delete_published_videos' => true,
        'delete_others_videos' => true,
        'read_private_videos' => true,
        'edit_video' => true,
        'delete_video' => true,
        'read_video' => true,
        
        'publish_text' => true,
        'edit_texts' => true,
        'edit_others_text' => true,
        'edit_published_text' => true,
        'edit_private_text' => true,
        'delete_text' => true,
        'delete_private_texts' => true,
        'delete_published_texts' => true,
        'delete_others_text' => true,
        'read_private_text' => true,
        'edit_text' => true,
        'delete_text' => true,
        'read_text' => true,
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
