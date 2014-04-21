<?php

/**
 * Shows a simple gallery post
 *
 * @package Smartbox
 * @subpackage Frontend
 * @since 1.0
 *
 * @copyright (c) 2013 Oxygenna.com
 * @license http://wiki.envato.com/support/legal-terms/licensing-terms/
 * @version 1.5
 */
global $post;
?>
    <?php
    $content .= '<div class="span4"> <span class="lead margin-top"><p>';
    $content .= get_field('video_shortcode', $post->ID);
    $content .= '</p></span></div>';
    $content .= '<div class="span8">' . get_the_content('') . '</div>';
    $atts = array(
        'title' => $post->post_title,
        'style' => 'grey'
    );
    $content = oxy_shortcode_section( $atts, $content );
    $video_shortcode = oxy_get_content_shortcode($post, 'embed');
    if ($video_shortcode !== null) {
        if (isset($video_shortcode[0])) {
            $video_shortcode = $video_shortcode[0];
            if (isset($video_shortcode[0])) {
                // use the video in the archives
                global $wp_embed;
                echo $wp_embed->run_shortcode($video_shortcode[0]);
                $content = str_replace($video_shortcode[0], '', get_the_content());
            }
        }
    } else if (has_post_thumbnail()) {
        $img = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
        echo '<figure><img alt="featured image" src="' . $img[0] . '"></figure>';
    }
    echo apply_filters('the_content', isset($content) ? $content : get_the_content() );
    get_template_part('partials/social-links', null);
?>
