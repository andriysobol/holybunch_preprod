<?php
/**
 * Test Options Page
 *
 * @package Smartbox
 * @subpackage options-pages
 * @since 1.0
 *
 * @copyright (c) 2013 Oxygenna.com
 * @license http://wiki.envato.com/support/legal-terms/licensing-terms/
 * @version 1.01
 */

return array(
    'page_title' => THEME_NAME . ' - ' . __('General', THEME_ADMIN_TD),
    'menu_title' => __('General', THEME_ADMIN_TD),
    'slug'       => THEME_SHORT . '-general',
    'main_menu'  => true,
    'icon'       => 'tools',
    'menu_icon'  => ADMIN_ASSETS_URI . 'images/theme.png',
    'sections'   => array(
        'general-section' => array(
            'title'   => __('Site Style', THEME_ADMIN_TD),
            'header'  => __('Set the style of your  page', THEME_ADMIN_TD),
            'fields' => array(
                array(
                    'name'    => __('Style', THEME_ADMIN_TD),
                    'desc'    => __('Choose a style to use for your page', THEME_ADMIN_TD),
                    'id'      => 'style',
                    'type'    => 'radio',
                    'options' => array(
                        ''            => __('Blue', THEME_ADMIN_TD),
                        'theme-brown' => __('Brown', THEME_ADMIN_TD),
                        'theme-red'   => __('Red', THEME_ADMIN_TD),
                    ),
                    'default' => '',
                ),
            )
        ),
        'logo-section' => array(
            'title'   => __('Logo', THEME_ADMIN_TD),
            'header'  => __('Upload and configure your site logo here', THEME_ADMIN_TD),
            'fields' => array(
                array(
                    'name'    => __('Logo Type', THEME_ADMIN_TD),
                    'desc'    => __('Select which kind of logo you would like', THEME_ADMIN_TD),
                    'id'      => 'logo_type',
                    'type'    => 'radio',
                    'options' => array(
                        'text'  => __('Use Text', THEME_ADMIN_TD),
                        'image' => __('Use Image', THEME_ADMIN_TD),
                    ),
                    'default' => 'text',
                ),
                array(
                    'name'    => __('Logo Text', THEME_ADMIN_TD),
                    'desc'    => __('Add your logo text here ( to use light font wrap in underscores like _title_ )', THEME_ADMIN_TD),
                    'id'      => 'logo_text',
                    'type'    => 'text',
                    'default' => 'SMART_BOX_',
                ),
                array(
                    'name'    => __('Logo', THEME_ADMIN_TD),
                    'desc'    => __('Upload a logo for your site', THEME_ADMIN_TD),
                    'id'      => 'logo_image',
                    'store'   => 'id',
                    'type'    => 'upload',
                    'default' => '',
                ),
                array(
                    'name'    => __('Retina Logo', THEME_ADMIN_TD),
                    'desc'    => __('Use retina logo (NOTE - you will need to upload a logo that is twice the size intended to display)', THEME_ADMIN_TD),
                    'id'      => 'logo_retina',
                    'type'    => 'radio',
                    'options' => array(
                        'on'  => __('Retina Logo', THEME_ADMIN_TD),
                        'off' => __('Normal Logo', THEME_ADMIN_TD),
                    ),
                    'default' => 'off',
                ),
                array(
                    'name'      => __('Header Height', THEME_ADMIN_TD),
                    'desc'      => __('Set the height of the header in case you use a custom logo image', THEME_ADMIN_TD),
                    'id'        => 'header_height',
                    'type'      => 'slider',
                    'default'   => 85,
                    'attr'      => array(
                        'max'       => 300,
                        'min'       => 85,
                        'step'      => 1
                    )
                ),
            )
        ),
        'blog-section' => array(
            'title'   => __('Blog', THEME_ADMIN_TD),
            'header'  => __('Setup your blog here', THEME_ADMIN_TD),
            'fields' => array(
                array(
                    'name'    => __('Show Comments On', THEME_ADMIN_TD),
                    'desc'    => __('Where to allow comments. All (show all), Pages (only on pages), Posts (only on posts), Off (all comments are off)', THEME_ADMIN_TD),
                    'id'      => 'site_comments',
                    'type'    => 'radio',
                    'options' => array(
                        'all'   => __('All', THEME_ADMIN_TD),
                        'pages' => __('Pages', THEME_ADMIN_TD),
                        'posts' => __('Posts', THEME_ADMIN_TD),
                        'Off'   => __('Off', THEME_ADMIN_TD)
                    ),
                    'default' => 'posts',
                ),
                array(
                    'name' => __('Blog title', THEME_ADMIN_TD),
                    'desc' => __('The title that appears at the top of your blog', THEME_ADMIN_TD),
                    'id' => 'blog_title',
                    'type' => 'text',
                    'default' => 'Our Blog',
                ),
                array(
                    'name' => __('Blog read more link', THEME_ADMIN_TD),
                    'desc' => __('The text that will be used for your read more links', THEME_ADMIN_TD),
                    'id' => 'blog_readmore',
                    'type' => 'text',
                    'default' => '<strong>Read</strong> More',
                ),
                array(
                    'name'    => __('Display avatars', THEME_ADMIN_TD),
                    'desc'    => __('toogle avatars on/off', THEME_ADMIN_TD),
                    'id'      => 'site_avatars',
                    'type'    => 'radio',
                    'options' => array(
                        'on'   => __('On', THEME_ADMIN_TD),
                        'off'  => __('Off', THEME_ADMIN_TD),
                    ),
                    'default' => 'on',
                ),
            )
        ),
        'google-section' => array(
            'title'   => __('Google', THEME_ADMIN_TD),
            'header'  => __('Set your google options here', THEME_ADMIN_TD),
            'fields' => array(
                'google_anal' => array(
                    'name' => __('Google Analytics', THEME_ADMIN_TD),
                    'desc' => __('Paste your google analytics code here', THEME_ADMIN_TD),
                    'id' => 'google_anal',
                    'type' => 'text',
                    'default' => 'UA-XXXXX-X',
                )
            )
        )
    )
);