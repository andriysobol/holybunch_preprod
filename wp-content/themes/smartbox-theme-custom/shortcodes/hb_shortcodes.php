<?php

require_once get_template_directory() . '/inc/options/shortcodes/shortcodes.php';
require_once CUSTOM_INCLUDES_DIR . 'hb_utility.php';
/**
 * Custom shortcode functions go here
 * @author Andriy Sobol
 */

/**
 * @description latest taxonomy topics shown on main page
 * @param array $atts
 * @return string
 */
function get_latest_taxonomy_topics_as_list($atts) {
    $args = array(
        'hide_empty' => 1,
        'taxonomy' => 'teaching_topics',
        'pad_counts' => 1,
        'hierarchical' => 0,
        'number' => '2',
    );
    
    foreach (get_categories($args) as $taxonomy) {
        $link = get_term_link($taxonomy);
        
        //generate title as link
        $title = get_hb_title(array(
                    'tag' => 3,
                    'content' => get_hb_link(array(
                                'link' => $link,
                                'content' => $taxonomy->name))));
        
        //prepare info to generate a blockquote
        $summary = get_taxonomy_term_summary_mini($taxonomy);
        $link_more = get_hb_link(array(
                'link' => $link,
                'class' => 'more-link',
                'content' => __('Go to topic', THEME_FRONT_TD)));
        //generate a blockquote
        $blockquote = get_hb_oxy_shortcode_blockquote(array(
                    'class' => 'margin_bottom_25px_mb',
                    'content' => '<p>' . $summary . $link_more . '</p>'));

        //generate a round image as link
        $round_link = get_hb_link(array(
            'link' => $link,
            'content' => get_image_as_round_box(get_taxonomy_image('teaching_topics', $taxonomy->slug))));

        //builde layout
        $output_loop .= oxy_shortcode_layout(NULL, $title . $blockquote . $round_link, 'well blockquote-well');
    }
    return oxy_shortcode_section($atts, $output_loop);
}
add_shortcode('latest_taxonomy_topics', 'get_latest_taxonomy_topics_as_list');

/**
 * @description used on main page for latest videos
 * @global WP_Post $post
 * @param array $atts
 * @return string
 */
function hb_get_recent_oxy_video($atts) {
    extract(shortcode_atts(array(
        'title' => '',
        'cat' => null), $atts));

    $args = array(
        'post_type' => array('oxy_video'),
        'showposts' => 3, // Number of related posts that will be shown.  
        'orderby' => 'date'
    );
    $my_query = new wp_query($args);
    if ($my_query->have_posts()) {
        global $post;
        while ($my_query->have_posts()) {
            $my_query->the_post();
            setup_postdata($post);
            $post_link = get_hb_linkformat(get_post_format());
            $icon_class_array = explode('"', oxy_post_icon($post->ID, false));
            
            //generate round image with icon as link 
            $image_and_date = oxy_shortcode_image(array(
                'size' => 'box-medium',
                'source' => CUSTOM_IMAGES_DIR . 'video1.jpg',
                'icon' => $icon_class_array[1],
                'link' => $post_link
            ));
            //generate date
            $image_and_date .= get_hb_title(array(
                'tag' => 5,
                'class' => 'text-center light',
                'content' => get_the_time(get_option("date_format"))));

            //generate a title as link
            $title_right = get_hb_title(array(
                'tag' => 3,
                'class' => 'text-center',
                'content' => get_the_title()));
            //text with link get more text
            $content_right = '<p>' . oxy_limit_excerpt(get_the_content(), 15) . '</p>';
            $content_right .= get_hb_link(array(
                'link' => get_permalink(),
                'class' => 'more-link',
                'content' => get_more_text($post->post_type)));
            $span_right = get_hb_link(array(
                'link' => $post_link,
                'content' => $title_right));
            $span_right .= apply_filters('the_content', $content_right);

            //build layout
            $merge_spans = oxy_shortcode_layout(NULL, $image_and_date, 'span3');
            $merge_spans .= oxy_shortcode_layout(NULL, $span_right, 'span9');
            $result .= oxy_shortcode_layout(NULL, $merge_spans, 'span4');
        }
    }
    wp_reset_postdata();
    return oxy_shortcode_section($atts, $result);
}
add_shortcode('hb_recent_videos', 'hb_get_recent_oxy_video');

/**
 * @description overreid <b>blockquote</b> from parent template
 * @param array $atts
 * @param String $content
 * @return String
 */
function hb_get_shortcode_blockquote($atts, $content) {
    return get_hb_oxy_shortcode_blockquote(array(
        'content' => $content,
        'params' => $atts));
}
add_shortcode('blockquote', 'hb_get_shortcode_blockquote');

/**
 * @description shows recents blogs on main page
 * @global WP_Post $post
 * @param array $atts
 * @return String
 */
function hb_get_recent_blog_posts($atts) {
    extract(shortcode_atts(array(
        'title' => '',
        'style' => '',
        'src_url' => ''
                    ), $atts));

    $args = array(
        'showposts' => 2, // Number of related posts that will be shown.  
        'orderby' => 'date',
    );
    $my_query = new wp_query($args);
    if ($my_query->have_posts()) {
        while ($my_query->have_posts()) {
            global $post;
            $my_query->the_post();
            setup_postdata($post);
            $author_avatar = get_avatar(get_the_author_meta('ID'), 300);
            $author = get_the_author();
            $date = get_the_time(get_option("date_format"));
            $post_link = get_hb_linkformat(get_post_format());

            //generate round image 
            //@todo
            $avatar_blog = oxy_shortcode_layout(NULL, $author_avatar, 'round-box box-small');
            //generate name of bloger
            $avatar_blog .= get_hb_title(array(
                'tag' => 5,
                'class' => 'text-center',
                'content' => $author));
            //generate date
            $avatar_blog .= get_hb_title(array(
                'tag' => 5,
                'class' => 'text-center light',
                'content' => $date));

            //generate a title as link
            $content_blog = get_hb_title(array(
                'tag' => 3,
                'content' => get_hb_link(array(
                                'content' => get_the_title(),
                                'link' => $post_link))));
            //generate text + more link
            $content = oxy_limit_excerpt(strip_tags(get_the_content()), 30);
            $content .= get_hb_link(array(
                'content' => get_more_text($post->post_type),
                'link' => $post_link,
                'class' => 'more-link'));
            $content_blog .= '<p>' . apply_filters('the_content', $content) . '</p>';

            //builde layout
            $merge_spans = oxy_shortcode_layout(NULL, $avatar_blog, 'span3 post-info');
            $merge_spans .= oxy_shortcode_layout(NULL, $content_blog, 'span9');
            $output_loop .= oxy_shortcode_layout(NULL, oxy_shortcode_row(NULL, $merge_spans, NULL), 'span6');
        }
    }
    return oxy_shortcode_section($atts, $output_loop);
}
add_shortcode('hb_blog_posts', 'hb_get_recent_blog_posts');

/**
 * @description used on contact page, about us as contact form
 * @param array $atts
 * @param String $content
 * @return String
 */
function hb_get_contact_form($atts, $content = null) {
    // setup options
    extract(shortcode_atts(array(
        'title' => 'Contact us',
        'id' => ''), $atts));
    $div_content = oxy_shortcode_layout(NULL, do_shortcode($content), 'contact-details');
    $div_left = oxy_shortcode_layout(NULL, do_shortcode($div_content), 'span5');
    $div_right = oxy_shortcode_layout(NULL, do_shortcode('[contact-form-7 id="' . $id . '" title="ContactForm"]'), 'span7');
    return oxy_shortcode_section($atts, $div_left . $div_right);
}
add_shortcode('hb_contact_form', 'hb_get_contact_form');

/**
 * @description used on main pages in dutch and german in order to show latest conten
 * @global WP_Post $post
 * @param array $atts
 * @return String
 */
function hb_get_recent_oxy_content($atts) {
    extract(shortcode_atts(array(
        'title' => '',
        'cat' => null,
        'style' => ''), $atts));

    $args = array(
        'post_type' => array('oxy_content'),
        'showposts' => 3, // Number of related posts that will be shown.  
        'orderby' => 'date'
    );
    $my_query = new wp_query($args);
    if ($my_query->have_posts()) {
        global $post;
        while ($my_query->have_posts()) {
            $my_query->the_post();
            setup_postdata($post);
            $title_link = get_hb_link(array(
                'link' => get_hb_linkformat(get_post_format()),
                'content' => get_hb_title(array(
                    'tag' => 3,
                    'class' => 'text-center',
                    'content' => get_the_title()
                ))
            ));
            $content = get_field('summary', $post->ID);
            $content .= get_hb_link(array(
                'content' => get_more_text($post->post_type),
                'link' => get_permalink(),
                'class' => 'more-link'));
            $text = '<p>' . apply_filters('the_content', $content) . '</p>';
            $output_loop .= oxy_shortcode_layout(NULL, do_shortcode($title_link . $text), 'span4');
        }
    }
    $output = oxy_shortcode_layout(NULL, $output_loop, 'unstyled row-fluid');
    wp_reset_postdata();
    return oxy_shortcode_section($atts, $output);
}
add_shortcode('hb_recent_content', 'hb_get_recent_oxy_content');

/**
 * @description used for example on main page for section with random video
 * @param array $atts
 * @return string
 */
function create_hero_section_with_video($atts) {
    extract(shortcode_atts(array(
        'image' => '',
        'title' => '',
        'summary' => '',
        'random_posts' => 'true',
        'post_video' => '',
        'taxonomy_slug' => ''
                    ), $atts));

    $title = $title === null ? oxy_get_option('blog_title') : $title;

    //take random video post and show it
    if ($random_posts) {
        $args = array(
            'post_type' => 'oxy_video',
            'showposts' => 1,
            'orderby' => 'rand'
        );
        $my_query = new wp_query($args);
        $post_video = $my_query->post;
    }

    if (!empty($post_video)) {
        $title = $post_video->post_title;
        $summary = hb_limit_excerpt($post_video->post_content, 50);
        $shortcode = get_field('video_shortcode', $post_video->ID);
    }

    if (!empty($image)) {
        $img_attachment = wp_get_attachment_image_src($image);
        $image = $img_attachment[0];
    } else {
        $image = get_taxonomy_video_background_image('teaching_topics', $taxonomy_slug);
        if (empty($image)) {
            $image = CUSTOM_IMAGES_DIR . 'background_video_default.jpg';
        }
    }

    $title_ui = get_hb_title(array(
        'tag' => 1,
        'class' => 'animated fadeinup delayed text-center',
        'content' => $title
    ));
    $text_left = oxy_shortcode_layout(NULL, do_shortcode('<p>' . $summary . '</p>'), 'span4  margin-top margin-bottom');
    $row = oxy_shortcode_row(NULL, $text_left . create_videowrapper_div($shortcode), NULL);
    $super_hero_unit = oxy_shortcode_layout(NULL, do_shortcode($title_ui . $row), 'container-fluid super-hero-unit');
    
    return get_hb_section_background_image_simple(
            array(
                'class' => 'section section-padded section-dark',
                'data_background' => 'url(' . $image . ') no-repeat top',
                'image_link' => 'background: url(' . $image . ') 50% 0% no-repeat;',
                'content' => $super_hero_unit
    ));
}
add_shortcode('hero_section_with_video', 'create_hero_section_with_video');

/**
 * @description used for integration of google calendar into section
 * @param array $atts
 * @return String
 */
function hb_add_element_into_wrapper($atts) {
    extract(shortcode_atts(array(
        'title' => '',
        'style' => '',
        'src_url' => ''
                    ), $atts));
    return oxy_shortcode_section($atts, create_videowrapper_div($src_url, "span12"));
}
add_shortcode('hb_add_into_wrapper', 'hb_add_element_into_wrapper');