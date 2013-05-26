<?php
/**
 * Registering meta boxes
 *
 * All the definitions of meta boxes are listed below with comments.
 * Please read them CAREFULLY.
 *
 * You also should read the changelog to know what has been changed before updating.
 *
 * For more information, please visit:
 * @link http://www.deluxeblogtips.com/meta-box/
 */

/********************* META BOX DEFINITIONS ***********************/

/**
 * Prefix of meta keys (optional)
 * Use underscore (_) at the beginning to make keys hidden
 * Alt.: You also can make prefix empty to disable it
 */
// Better has an underscore as last sign
$prefix = THEME_SHORT. '_';

global $meta_boxes;

$meta_boxes = array();

// 1st meta box
$meta_boxes[] = array(
    // Meta box id, UNIQUE per meta box. Optional since 4.1.5
    'id' => 'metabox_header',

    // Meta box title - Will appear at the drag and drop handle bar. Required.
    'title' => 'Custom header image',

    // Post types, accept custom post types as well - DEFAULT is array('post'). Optional.
    'pages' => array( 'page' ),

    // Where the meta box appear: normal (default), advanced, side. Optional.
    'context' => 'normal',

    // Order of meta box: high (default), low. Optional.
    'priority' => 'high',

    // List of meta fields
    'fields' => array(
        array(
            'name' => __('Page Header Title', THEME_ADMIN_TD),
            'id'   => "{$prefix}header_title",
            'type' => 'text'
        ),
        array(
            'name' => __('Page Header Type', THEME_ADMIN_TD),
            'id'   => "{$prefix}header_type",
            'type' => 'select',
            'options' => array(
                'none'       => __('Nothing', THEME_ADMIN_TD),
                'slideshow'  => __('Slideshow', THEME_ADMIN_TD),
                'super_hero' => __('Super Hero', THEME_ADMIN_TD),
                'map'        => __('Map',THEME_ADMIN_TD)
            )
        ),
        // THICKBOX IMAGE UPLOAD (WP 3.3+)
        array(
            'name' => __('Super Hero Header Image', THEME_ADMIN_TD),
            'id'   => "{$prefix}thickbox",
            'type' => 'thickbox_image',
        ),
        array(
            'name'    => __('Select slideshow', THEME_ADMIN_TD),
            'id'      => "{$prefix}slideshow",
            'type'    => 'taxonomy',
            'settax'  =>  false,
            'options' => array(
                // Taxonomy name
                'taxonomy' => 'oxy_slideshow_categories',
                // How to show taxonomy: 'checkbox_list' (default) or 'checkbox_tree', 'select_tree' or 'select'. Optional
                'type' => 'checkbox_advanced',
            ),
        ),
        array(
                'id'            => 'address',
                'name'          => 'Address',
                'type'          => 'text',
                'std'           => 'Hanoi, Vietnam',
            ),
        array(
            'id'            => 'loc',
            'name'          => 'Location',
            'type'          => 'map',
            'std'           => '-6.233406,-35.049906,15',     // 'latitude,longitude[,zoom]' (zoom is optional)
            'style'         => 'width: 400px; height: 300px',
            'address_field' => 'address',                     // Name of text field where address is entered. Can be list of text fields, separated by commas (for ex. city, state)
        ),
    ),
);


$meta_boxes[] = array(
    // Meta box id, UNIQUE per meta box. Optional since 4.1.5
    'id' => 'timeline_category',

    // Meta box title - Will appear at the drag and drop handle bar. Required.
    'title' => 'Timeline Posts Category',

    // Post types, accept custom post types as well - DEFAULT is array('post'). Optional.
    'pages' => array( 'oxy_timeline'  ),

    // Where the meta box appear: normal (default), advanced, side. Optional.
    'context' => 'normal',

    // Order of meta box: high (default), low. Optional.
    'priority' => 'high',

    // List of meta fields
    'fields' => array(
       // TAXONOMY
        array(
            'name'    => 'Timeline',
            'id'      => "{$prefix}timeline",
            'type'    => 'taxonomy',
            'settax'  =>  false,
            'options' => array(
                // Taxonomy name
                'taxonomy' => 'category',
                // How to show taxonomy: 'checkbox_list' (default) or 'checkbox_tree', 'select_tree' or 'select'. Optional
                'type' => 'checkbox_advanced',
            ),
            'blank' => __('all categories',THEME_ADMIN_TD),
        ),

    ),
);

/*************************META BOXES FOR PORTFOLIO*************************/

$meta_boxes[] = array(
    // Meta box id, UNIQUE per meta box. Optional since 4.1.5
    // 'id' => 'portfolio_meta',

    // Meta box title - Will appear at the drag and drop handle bar. Required.
    'title' => 'Custom header image',

    // Post types, accept custom post types as well - DEFAULT is array('post'). Optional.
    'pages' => array( 'oxy_portfolio_image' ),

    // Where the meta box appear: normal (default), advanced, side. Optional.
    'context' => 'normal',

    // Order of meta box: high (default), low. Optional.
    'priority' => 'high',

    // List of meta fields
    'fields' => array(
        array(
            'name' => __('Open in fancybox', THEME_ADMIN_TD),
            'id'   => "{$prefix}open_fancybox",
            'type' => 'checkbox',
            'std' => true,
            'desc' => __('Opens gallery and video post formats using fancybox', THEME_ADMIN_TD),
        ),
        array(
            'name' => __('Portfolio Custom Background', THEME_ADMIN_TD),
            'id'   => "{$prefix}background",
            'type' => 'thickbox_image',
        ),
    ),
);


$meta_boxes[] = array(
    // Meta box id, UNIQUE per meta box. Optional since 4.1.5
    'id' => 'Citation',

    // Meta box title - Will appear at the drag and drop handle bar. Required.
    'title' => 'Citation',

    // Post types, accept custom post types as well - DEFAULT is array('post'). Optional.
    'pages' => array( 'oxy_testimonial'  ),

    // Where the meta box appear: normal (default), advanced, side. Optional.
    'context' => 'normal',

    // Order of meta box: high (default), low. Optional.
    'priority' => 'high',

    // List of meta fields
    'fields' => array(
       // TAXONOMY
       array(
            // Field name - Will be used as label
            'name'  => 'Citation',
            // Field ID, i.e. the meta key
            'id'    => "{$prefix}citation",
            // Field description (optional)
            'desc'  => 'Reference to the source of the quote',
            'type'  => 'text',
            // Default value (optional)
            'std'   => '',
        ),

    ),
);



$meta_boxes[] = array(
    // Meta box id, UNIQUE per meta box. Optional since 4.1.5
    'id' => 'summary',

    // Meta box title - Will appear at the drag and drop handle bar. Required.
    'title' => 'Summary',

    // Post types, accept custom post types as well - DEFAULT is array('post'). Optional.
    'pages' => array( 'oxy_content' ),

    // Where the meta box appear: normal (default), advanced, side. Optional.
    'context' => 'normal',

    // Order of meta box: high (default), low. Optional.
    'priority' => 'high',

    // List of meta fields
    'fields' => array(
       // TAXONOMY
       array(
            // Field name - Will be used as label
            'name'  => 'Summary',
            // Field ID, i.e. the meta key
            'id'    => "{$prefix}summary",

            'type'  => 'text',
            // Default value (optional)
            'std'   => '',
        ),

    ),
);
            
$meta_boxes[] = array(
    // Meta box id, UNIQUE per meta box. Optional since 4.1.5
    'id' => 'services_meta',

    // Meta box title - Will appear at the drag and drop handle bar. Required.
    'title' => 'Service',

    // Post types, accept custom post types as well - DEFAULT is array('post'). Optional.
    'pages' => array( 'oxy_service' ),

    // Where the meta box appear: normal (default), advanced, side. Optional.
    'context' => 'normal',

    // Order of meta box: high (default), low. Optional.
    'priority' => 'high',

    // List of meta fields
    'fields' => array(
        // THICKBOX IMAGE UPLOAD (WP 3.3+)
        array(
            'name' => __('Service Icon', THEME_ADMIN_TD),
            'id'   => "{$prefix}icon",
            'type' => 'icon',
        ),
    ),
);

/********************* META BOX REGISTERING ***********************/

/**
 * Register meta boxes
 *
 * @return void
 */
function oxy_register_meta_boxes()
{
    global $pagenow;
    // only load this when we need it - causing bugs in list pages
    if( $pagenow == 'post.php' || $pagenow == 'post-new.php' ) {

        // Make sure there's no errors when the plugin is deactivated or during upgrade
        if ( !class_exists( 'RW_Meta_Box' ) ) {
            return;
        }
        global $meta_boxes;
        foreach ( $meta_boxes as $meta_box ) {
            new RW_Meta_Box( $meta_box );
        }
    }
}
// Hook to 'admin_init' to make sure the meta box class is loaded before
// (in case using the meta box class in another plugin)
// This is also helpful for some conditionals like checking page template, categories, etc.
add_action( 'admin_init', 'oxy_register_meta_boxes' );