<?php
/**
 * @description get all taxonomies which contain posts and return as term cloud, used for (video-)archive 
 * @param string $post_type <i>type of post</i>
 * @param title $title <i>title</i>
 * @return string
 */
function hb_ui_taxonomy_terms_cloud($post_type, $title) {
    if (empty($post_type))
        $post_type = array('oxy_content', 'oxy_video', 'oxy_audio');
    $args = array(
        'hide_empty' => 1,
        'taxonomy' => 'teaching_topics',
        'pad_counts' => 1,
        'hierarchical' => 0
    );
    return oxy_shortcode_layout(NULL, hb_ui_title(array(
                'tag' => 3,
                'content' => $title
            )) .  hb_ui_taxonomy_terms_as_list(get_categories($args), $post_type), 
            "sidebar-widget widget_tag_cloud");
}

/**
 * @description get all taxonomies which contain posts and return as list, used for (video-)archive 
 * @param array $taxonomies <i>taxonomies</i>
 * @param string $post_type <i>type of post</i>
 * @return string
 */
function hb_ui_taxonomy_terms_as_list($taxonomy_topics, $post_type) {
    $add_all = TRUE;
    foreach ($taxonomy_topics as $taxonomy_topic) {
        $posts_in_category = get_posts(array(
            'showposts' => -1,
            'post_type' => $post_type,
            'tax_query' => array(array(
                    'taxonomy' => 'teaching_topics',
                    'field' => 'slug',
                    'terms' => $taxonomy_topic->slug)
            )
        ));
        $count = count($posts_in_category);
        $tax_name = " " . $taxonomy_topic->name . " (" . $count . ")";
        //dont add post type for theme, but do link for all post types
        $link = get_term_link( $taxonomy_topic->slug, 'teaching_topics' );
        if (!is_array($post_type)) {
            $link = $link . "/?post_type=" . $post_type;
        }
        if ($add_all) {
            $posts_all = get_posts(array(
                'post_type' => $post_type,
                'showposts' => -1,
                    )
            );
            $count_all = count($posts_all);
            if ($post_type == 'oxy_video') {
                $link_all = home_url() . "/videoarchive";
                $title = __('Show all videos', THEME_FRONT_TD) . " (" . $count_all . ") ";
            } elseif (oxy_content) {
                $title = __('Show all articles', THEME_FRONT_TD) . " (" . $count_all . ") ";
                $link_all = home_url() . "/archive";
            }
         
            $output = hb_ui_list_wrapper(array(
                'tag' => 'li',
                'content' => hb_ui_link(array(
                        'class' => 'hb_cloud_style',
                        'link' => $link_all,
                        'content' => $title
            ))));            
           $add_all = FALSE;
        }
        if (!empty($count)) {
            $output .= hb_ui_list_wrapper(array(
                'tag' => 'li',
                'content' => hb_ui_link(array(
                        'class' => 'hb_cloud_style',
                        'link' => $link,
                        'content' => $tax_name
            ))));
        }
    }
    return hb_ui_list_wrapper(array(
        'tag' => 'ul',
        'content' => $output
    ));
}

/**
 * @description get related for particular post, is used for example on single post page
 * @param array $atts <i>attributes</i>
 * @return string
 */
function hb_ui_related_posts($atts) {
    // setup options
    $atts = array(
        'title' => __('Also in this topic', THEME_FRONT_TD),
        'cat' => null,
        'count' => 4,
        'style' => '',
        'columns' => 4);
    global $post;
    $taxonomy = "teaching_topics";
    $terms = wp_get_post_terms($post->ID, $taxonomy);
    if ($terms) {
        $term_ids = array();
        foreach ($terms as $individual_term)
            $term_ids[] = $individual_term->term_id;
        $args = array(
            'post_type' => get_post_type(),
            'tax_query' => array(
                array(
                    'taxonomy' => $taxonomy,
                    'field' => 'id',
                    'terms' => $term_ids,
                    'operator' => 'IN'
                )
            ),
            'post__not_in' => array($post->ID),
            'showposts' => $count, // Number of related posts that will be shown.  
            'caller_get_posts' => 1
        );

        $my_query = new wp_query($args);
        return hb_create_section_with_text_items($my_query, $atts);
    }
}

/**
 * @description create taxonomy topic page with short summary of taxonomy term and corresponding text/video items
 * @param string $taxonomy term <i>term of taxonomy</i>
 * @return string
 */
function hb_create_section_with_text_items($my_query, $atts = null) {
    $columns = $my_query->post_count > 4 ? 4 : $my_query->post_count;
    $span = $columns > 0 ? 'span' . floor(12 / $columns) : 'span3';

    $output = NULL;
    if ($my_query->have_posts()) {
        global $post;
        $item_num = 1;
        $items_per_row = $columns;
        //loop over all related posts
        while ($my_query->have_posts()) {
            $my_query->the_post();
            setup_postdata($post);            
            $post_link = hb_get_linkformat(get_post_format());

            $innen = hb_ui_link(array(
                'link' => $post_link,
                'content' => hb_ui_title(array(
                    'tag' => 3,
                    'class' => 'text-center',
                    'content' => get_the_title()
                ))
            ));
            
            $innen.= apply_filters('the_content', hb_get_post_summary_mini($post) . 
                    hb_ui_link(array(
                'link' => get_permalink(),
                'content' => __('Read more', THEME_FRONT_TD),
                'class' => 'more-link'
            )));
          
            $output_loop .= oxy_shortcode_layout(NULL, $innen, $span);
            $item_num++;
            
            if ($item_num > $items_per_row) {
                $output .= oxy_shortcode_layout(NULL, $output_loop, 'unstyled row-fluid');
                $item_num = 1;
                $output_loop = NULL;
            }
        }        
        if ($item_num > 1) {
            $output .= oxy_shortcode_layout(NULL, $output_loop, 'unstyled row-fluid');
        }
        wp_reset_postdata();
        return oxy_shortcode_section($atts, $output);
    }
}

/**
 * @description get attached video from post and return it as video wrapper, used for archive
 * @param post $post <i>post</i>
 * @return string
 */
function hb_ui_video_content($post) {
    $video_shortcode = get_field('video_shortcode', $post->ID);
    $content = $post->post_content;
    return hb_create_videowrapper_div($video_shortcode) . oxy_shortcode_layout(NULL, $content, 'span4 hb_video_archive_single');
}

/**
 * @description create taxonomy topic page with short summary of taxonomy term and corresponding text/video items
 * @param string $taxonomy term <i>term of taxonomy</i>
 * @return string
 */
function hb_ui_taxonomy_topic_page($taxonomy_term) {
    //get post of type text
    $query = array(
        'numberposts' => -1,
        'post_type' => 'oxy_content',
        'tax_query' => array(
            array(
                'taxonomy' => 'teaching_topics',
                'field' => 'slug',
                'terms' => $taxonomy_term->slug
            )
        ),
        'orderby' => 'menu_order',
        'order' => 'ASC'
    );

    $text_items = get_posts($query);
    $count = count($text_items);

    if (isset($taxonomy_term->description)) {
        $output = oxy_shortcode_layout(NULL, $taxonomy_term->description, 'container-fluid hb_taxonomy_desc');
    }

    if ($count >= 1 && $count <= 3) {
        $output .= hb_ui_items($text_items);
    } elseif ($count == 4) {
        $output .= hb_ui_items(array($text_items[0], $text_items[1]));
        $output .= hb_ui_items(array($text_items[2], $text_items[3]));
    } elseif ($count == 5) {
        $output .= hb_ui_items(array($text_items[0], $text_items[1]));
        $output .= hb_ui_items(array($text_items[2], $text_items[3], $text_items[4]));
    } elseif ($count == 6) {
        $output .= hb_ui_items(array($text_items[0], $text_items[1], $text_items[2]));
        $output .= hb_ui_items(array($text_items[3], $text_items[4], $text_items[5]));
    } elseif ($count > 6) {
        $output .= hb_create_section_with_text_items(new WP_Query($query));
    }

    $atts[title] = __('In this topic ...', THEME_FRONT_TD); //'В этой теме ...';        
    $output = oxy_shortcode_section($atts, $output);
    $output .= hb_get_flexi_slider_for_taxonomy_topic_page($taxonomy_term->slug);
    return $output;
}

/**
 * @description create 1-3 text items as quote for taxonomy topic page
 * @param array $text_item <i>count should be not more <b>4</b> </i> 
 * @return string
 */
function hb_ui_items($text_items) {
    $span = 'span3';
    switch (count($text_items)) {
        case 1:
            $span = 'span12';
            break;
        case 2:
            $span = 'span6';
            break;
        case 3:
            $span = 'span4';
            break;
    }
    foreach ($text_items as $value) {
        $output .= hb_ui_posts_as_quote($value, $span);
    }   
    return oxy_shortcode_layout(NULL, $output, 'container-fluid');
}

/**
 * @description get summary of post as quote, used on taxonomy topic page 
 * @param post $post <i>post instance of text post</i>
 * @param string $span optional <i>span size, default is span4</i>
 * @param string $summary optional <i>summary text, default is empty</i>
 * @return string
 */
function hb_ui_posts_as_quote($post, $span = 'span4', $summary = '') {
    if (empty($summary)) {
        $summary = hb_get_post_summary_mini($post);
    }
    $content = hb_ui_title(array(
                'tag' => 3,
                'content' => hb_ui_link(array(
                    'link' => get_post_permalink($post->ID, false, false),
                    'content' => $post->post_title
        )))) . hb_get_blockquote(array(
                'content' => $summary . hb_ui_link(array(
                    'link' => get_post_permalink($post->ID, false, false),
                    'class' => 'more-link dddd',
                    'content' => __('Read more', THEME_FRONT_TD)
        ))));    
    
    return oxy_shortcode_layout(NULL, $content, $span . ' well blockquote-well');
}

/**
 * @description create round box with image in it for short summary of text item on topic page 
 * @param post $post <i>post instance of text post</i>
 * @param string size <i>size of image box</i>
 * @return string
 */
function hb_ui_image_in_text_item($post, $size = 'medium') {
    if (has_post_thumbnail($post->ID)) {
        $image = get_the_post_thumbnail($post->ID, 'portfolio-thumb', array(
            'title' => $post->post_title,
            'alt' => $post->post_title,
            'class' => 'img-circle'
        ));
        $icon = oxy_post_icon($post->ID, false);
        return oxy_shortcode_layout(NULL, oxy_shortcode_layout(NULL, $image . $icon, 'box-inner'), 'round-box box-' . $size . ' box-colored');
    }
}

/**
 * @description create round box with image in it
 * @param string $img_src_url <i>image url</i>
 * @param string size <i>size of box</i>
 * @return string
 */
function hb_ui_image_as_round_box($img_src_url, $size = 'medium') {
    return hb_shortcode_image(array(
        'source' => $img_src_url,
        'size' => $size
    ));
}

/**
 * @description function creates flexi slider with videos from one particular taxonomy topic(term) it, used on topic page
 * @param string $slug_or_id <i>taxonomy term slug or id</i>
 * @return string
 */
function hb_get_flexi_slider_for_taxonomy_topic_page($slug_or_id) {
    $slides = get_posts(array(
        'numberposts' => -1,
        'post_type' => 'oxy_video',
        'tax_query' => array(
            array(
                'taxonomy' => 'teaching_topics',
                'field' => 'slug',
                'terms' => $slug_or_id
            )
        ),
        'orderby' => 'menu_order',
        'order' => 'ASC'
    ));
    if (count($slides) == 0)
        return '';

    $output .= '<div id="flexslider-100" class="flexslider flex-directions-fancy flex-controls-inside flex-controls-center" data-flex-animation="slide" data-flex-controlsalign="center" data-flex-controlsposition="inside" data-flex-directions="show" data-flex-speed="30000" data-flex-directions-position="inside" data-flex-controls="show" data-flex-slideshow="true">';
    $output .= '<ul class="slides">';
    foreach ($slides as $slide) {
        $output .= '<li>';
        $atts[random_posts] = false;
        $atts[post_video] = $slide;
        $atts[taxonomy_slug] = $slug_or_id;
        $output .= hb_shortcode_hero_section_with_video($atts);
        $output .= '</li>';
    }
    $output .= '</ul>';
    $output .= '</div>';
    return $output;
}

/**
 * @description function to video wrapper with video or image in it
 * @param string $src_url <i>source url</i>
 * @param string $span <i>span size of wrapper</i>
 * @param string $width/$height <i>width, height size of wrapper</i>
 * @return string
 */
function hb_create_videowrapper_div($src_url, $span = "span8", $width = "1250", $height = "703") {
    $videoWrapperLayout = oxy_shortcode_layout(NULL, 
            hb_ui_iframe(array(
                'src_url' => $src_url,
                'width' => $width,
                'height' => $height)), 'entry-content videoWrapper');
    return oxy_shortcode_layout(NULL, $videoWrapperLayout, $span);
}

/**
 * @description function to get summary of custom posts
 * @param string $post <i>post item</i>
 * @return string
 */
function hb_get_post_summary_mini($post) {
    $summary = '';
    //in order to get custom field 'summary' from post we have 
    //to call advanced custom fields plugin api and provide id of post
    switch ($post->post_type) {
        case 'oxy_video':
            $summary = get_field('video_summary_mini', $post->ID);
            if (empty($summary)) {
                $summary = get_field('video_summary', $post->ID);
                $summary = hb_limit_string($summary, 40);
            }
            break;
        case 'oxy_audio':
            $summary = get_field('audio_summary_mini', $post->ID);
            if (empty($summary)) {
                $summary = get_field('audio_summary', $post->ID);
                $summary = hb_limit_string($summary, 40);
            }
            break;
        case 'oxy_content':
            $summary = get_field('summary_mini', $post->ID);
            if (empty($summary)) {
                $summary = get_field('summary', $post->ID);
                $summary = hb_limit_string($summary, 40);
            }
            break;
        default :
            break;
    }
    if (empty($summary))
        $summary = hb_limit_string($post->post_content, 40);
    return $summary;
}

/**
 * @description function to get list of taxonomy terms which are assigned to post
 * @param string $post <i>post item</i>
 * @return string
 */
function hb_get_assigned_taxonomy_terms($post) {
    $terms = wp_get_post_terms($post->ID, "teaching_topics");
    if ($terms) {
        $inhalt = oxy_shortcode_layout(NULL, __('Go to topic', THEME_FRONT_TD) . ':', 'tagcloudThema');
        foreach ($terms as $individual_term) {
            $li .= hb_ui_list_wrapper(array(
                'tag' => 'li',
                'content' => hb_ui_link(array(
                'link' => get_term_link($individual_term),
                'content' => $individual_term->name,
                'class' => 'hb_cloud_style'
            ))));
        }
        
        $inhalt .= hb_ui_list_wrapper(array(
            'tag' => 'ul',
            'content' => $li
        ));
    }
    return oxy_shortcode_layout(NULL, $inhalt, 'widget_tag_cloud');
}

/**
 * @package UI_ELEMENT_HTML
 * @description function to create a link
 * @param array $atts <i> id , class, content, link </i>
 * @return string
 */
function hb_ui_link($atts) {
    extract(shortcode_atts(array(
        'id' => '',
        'class' => '',
        'content' => '',
        'link' => ''), $atts));
    return '<a href="' . $link . '" ' . hb_set_attributes($id, $class) . '>' . $content . '</a>';
}

/**
 * @package UI_ELEMENT_HTML
 * @description function to create a title (H1, H2 ...)
 * @param array $atts <i> id , class, content, tag </i>
 * @example $tag for H3 is 3
 * @return string
 */
function hb_ui_title($atts) {
    extract(shortcode_atts(array(
        'id' => '',
        'class' => '',
        'content' => '',
        'tag' => ''), $atts));

    return '<h' . $tag . hb_set_attributes($id, $class) . '>' . $content . '</h' . $tag . '>';
}

/**
 * @package  UI_ELEMENT_HTML
 * @description function to create a iframe for video
 * @param array $atts (src_url, width, height)
 * @return String
 */
function hb_ui_iframe($atts) {
    extract(shortcode_atts(array(
        'src_url' => '',
        'width' => '',
        'height' => ''), $atts));
    
    return  '<iframe src="' . $src_url . '" width="' . $width . '" height="' . $height . '" frameborder="0"></iframe>';
}

/**
 * @description Content to list (<b>li</b> or <b>ul</b>)
 * @param array $atts <i>class, content, tag(li, ul)</i>
 * @return String
 */
function hb_ui_list_wrapper($atts) {
    extract(shortcode_atts(array(
        'class' => '',
        'content' => '',
        'tag' => ''), $atts));
    return '<' . $tag . hb_set_attributes($class) . '>' . $content . '</' . $tag . '>';
}

/**
 * @package UI_ELEMENT_HTML
 * @description function to create a blockquote
 * @param array $atts <i> id , class, content, params (who , site) </i>
 * @see oxy_shortcode_blockquote($params, $content);
 * @return string
 */
function hb_get_blockquote($atts) {
    extract(shortcode_atts(array(
        'id' => '',
        'class' => '',
        'content' => '',
        'params' => ''), $atts));

    if ($params == '' || $params == NULL) {
        $result = '<blockquote ' . hb_set_attributes($id, $class) . '>';
        $result .= $content;
        $result .= '</blockquote>';
    } else {
        extract(shortcode_atts(array(
            'who' => '',
            'cite' => ''), $params));
        if ($who != null && $cite != null) {
            return oxy_shortcode_blockquote($params, $content);
        } else if ($who != null) {
            return '<blockquote' . hb_set_attributes($id, $class) . '>' . do_shortcode($content) . '<small>' . $who . '</small></blockquote>';
        } else {
            return hb_get_blockquote(array(
                'id' => $id,
                'class' => $class,
                'content' => $content,
                'params' => NULL));
        }
    }
    return $result;
}

/**
 * @package UI_ELEMENT_HTML
 * @description function to create a section with image-background
 * @param array $atts <i>class, data_background, image_link, content </i>
 */
function hb_get_section_background_image_simple($atts) {
    extract(shortcode_atts(array(
        'class' => '',
        'data_background' => '',
        'image_link' => '',
        'content' => ''), $atts));
    return '<section class="' . $class . '"data-background="' . $data_background . '" style="' . $image_link . '">'
            . $content . '</section>';
}
?>
