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
    'sections'   => array(
        'twitter-section' => array(
            'title'   => __('Test Option', THEME_ADMIN_TD),
            'header'  => __('My options', THEME_ADMIN_TD),
            'fields' => array(
                 array(
                    'name' => __('Facebook', THEME_ADMIN_TD),
                    'id' => 'facebook',
                    'type' => 'text',
                    'default' => '',
                    'attr'      =>  array(
                        'class'    => 'widefat',
                    ),
                ),
                array(
                    'name' => __('Twitter', THEME_ADMIN_TD),
                    'id' => 'twitter',
                    'type' => 'text',
                    'default' => '',
                    'attr'      =>  array(
                        'class'    => 'widefat',
                    ),
                ),
                array(
                    'name' => __('Pinterest', THEME_ADMIN_TD),
                    'id' => 'pinterest',
                    'type' => 'text',
                    'default' => '',
                    'attr'      =>  array(
                        'class'    => 'widefat',
                    ),
                ),
                array(
                    'name' => __('Google Plus', THEME_ADMIN_TD),
                    'id' => 'googleplus',
                    'type' => 'text',
                    'default' => '',
                    'attr'      =>  array(
                        'class'    => 'widefat',
                    ),
                ),
                array(
                    'name' => __('LinkedIn', THEME_ADMIN_TD),
                    'id' => 'linkedin',
                    'type' => 'text',
                    'default' => '',
                    'attr'      =>  array(
                        'class'    => 'widefat',
                    ),
                ),
            )//fields
        )//section
    )//sections
);//array

