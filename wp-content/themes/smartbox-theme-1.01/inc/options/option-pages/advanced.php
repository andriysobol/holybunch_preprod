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
    'page_title' => THEME_NAME . ' - ' . __('Advanced Theme Options', THEME_ADMIN_TD),
    'menu_title' => __('Advanced', THEME_ADMIN_TD),
    'slug'       => THEME_SHORT . '-advanced',
    'main_menu'  => false,
    'menu_icon'  => ADMIN_ASSETS_URI . 'images/theme.png',
    'sections'   => array(
        'portfolio-section' => array(
            'title'   => __('CSS', THEME_ADMIN_TD),
            'fields' => array(
                 array(
                    'name'    => __('Extra CSS', THEME_ADMIN_TD),
                    'desc'    => __('Add extra CSS rules to be included in all pages', THEME_ADMIN_TD),
                    'id'      => 'extra_css',
                    'type'    => 'textarea',
                    'attr'    => array( 'rows' => '10', 'style' => 'width:100%' ),
                    'default' => '',
                )
            )
        ),
    )
);