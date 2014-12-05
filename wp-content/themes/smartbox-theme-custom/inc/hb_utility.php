 <?php

/**
 * @description get image value which is stored in custom field of taxonomy
 * @param string $taxonomy_name <i>name of taxonomy</i>
 * @param string $taxonomy_slug optional<i>taxonomy slug</i>
 * @return string
 */
function get_taxonomy_image($taxonomy_name, $taxonomy_slug) {
    $term_details = term_exists($taxonomy_slug, $taxonomy_name);
    if (is_array($term_details)) {
        $term_id = $term_details['term_id'];
    } else {
        return '';
    }
    //in order to get custom field 'taxonomy_image' from taxonomy we have 
    //to call advanced custom fields plugin api and provide id of post which 
    //is combination of taxonomy name and id of term e.g. term 'god' => id = 39
    $image = get_field('taxonomy_image', 'teaching_topics_' . $term_id);
    $image_url = $image[url];
    if (!empty($image_url)) {
        global $wp_embed;
        return $image_url;
    } else {
        return null;
    }
}

/**
 * @description get banner image value which is stored in custom field of taxonomy
 * @param string $taxonomy_name <i>name of taxonomy</i>
 * @param string $taxonomy_slug optional<i>taxonomy slug</i>
 * @return string
 */
function get_taxonomy_banner_image($taxonomy_name, $taxonomy_slug) {
    $term_details = term_exists($taxonomy_slug, $taxonomy_name);
    if (is_array($term_details)) {
        $term_id = $term_details['term_id'];
    } else {
        return get_theme_root_uri() . '/smartbox-theme-custom/images/banner_thema_default.jpg';;
    }
    //in order to get custom field 'taxonomy_image' from taxonomy we have 
    //to call advanced custom fields plugin api and provide id of post which 
    //is combination of taxonomy name and id of term e.g. term 'god' => id = 39
    $image = get_field('taxonomy_banner_image', 'teaching_topics_' . $term_id);
    $image_url = $image[url];
    if (!empty($image_url)) {
        global $wp_embed;
        return $image_url;
    } else {
        return get_theme_root_uri() . '/smartbox-theme-custom/images/banner_thema_default.jpg';
    }
}

/**
 * @description get video background image value which is stored in custom field of taxonomy, used for taxonomy topic page
 * @param string $taxonomy_name <i>name of taxonomy</i>
 * @param string $taxonomy_slug optional<i>taxonomy slug</i>
 * @return string
 */
function get_taxonomy_video_background_image($taxonomy_name, $taxonomy_slug) {
    $term_details = term_exists($taxonomy_slug, $taxonomy_name);
    if (is_array($term_details)) {
        $term_id = $term_details['term_id'];
    } else {
        return '';
    }
    //in order to get custom field 'taxonomy_image' from taxonomy we have 
    //to call advanced custom fields plugin api and provide id of post which 
    //is combination of taxonomy name and id of term e.g. term 'god' => id = 39
    $image = get_field('taxonomy_video_background', 'teaching_topics_' . $term_id);
    $image_url = $image[url];
    if (!empty($image_url)) {
        global $wp_embed;
        return $image_url;
    } else {
        return null;
    }
}

/**
 * @description get banner image for post from custom field of post or from corresponding taxonomy term of post 
 * @param post $post <i>post</i>
 * @param string $taxonomy_term optional<i>taxonomy term</i>
 * @return string
 */
function get_post_banner_image($post, $taxonomy_term = 'teaching_topics') {
    //in order to get custom field 'banner_image' from taxonomy we have 
    //to call advanced custom fields plugin api and provide id of post
    switch ($post->post_type) {
        case 'oxy_video': $image = get_field('video_banner_image', $post->ID);
            break;
        case 'oxy_audio': $image = get_field('audio_banner_image', $post->ID);
            break;
        case 'oxy_content': $image = get_field('content_banner_image', $post->ID);
            break;
        default :
            break;
    }
    //image found on post level return it
    $image_url = $image[url];
    if (!empty($image_url)) {
        global $wp_embed;
        return $image_url;
    }

    //try to get image from taxonomy topic assigned to post 
    //Returns All Term Items for taxonomy
    $term_list = wp_get_post_terms($post->ID, $taxonomy_term, array("fields" => "all"));
    foreach ($term_list as $term) {
        $image = get_field('taxonomy_banner_image', 'teaching_topics_' . $term->term_id);
        $image_url = $image[url];
        if (!empty($image_url))
            return $image_url;
    }
    return get_theme_root_uri() . '/smartbox-theme-custom/images/banner_thema_default.jpg';
}

/**
 * @description teaching topic can occur in url query as term or just a topic try to get this term
 * @return string
 */
function get_teaching_topic_from_query() {
    
    $teaching_topic = get_query_var('term');
    if (empty($teaching_topic)) {
        $teaching_topic = get_query_var('teaching_topics');
    }
    return $teaching_topic;
}

/**
 * @description provides true if theme is running locally on localhost and false if on server 
 * @return boolean
 */
function is_local_environment() {
    $server_name = $_SERVER['SERVER_NAME'];
    if (isset($server_name) && $server_name == "localhost")
        return true;
    return false;
}
/**
 * @description get path to script library of jw player
 * @return string
 */
function get_host_jw_player_script() {
    if (is_ssl() && !is_local_environment())
        return 'https://ssl.jwpsrv.com/library/2vQezLOEEeOy_CIACi0I_Q.js';
    else if (is_ssl() && is_local_environment())
        return 'https://jwpsrv.com/library/2vQezLOEEeOy_CIACi0I_Q.js';
    else if (!is_local_environment())
        return 'https://ssl.jwpsrv.com/library/2vQezLOEEeOy_CIACi0I_Q.js';
    else
        return 'http://jwpsrv.com/library/2vQezLOEEeOy_CIACi0I_Q.js';
}

/**
 * @description get all taxonomies which contain posts and return as term cloud, used for (video-)archive 
 * @param string $post_type <i>type of post</i>
 * @param title $title <i>title</i>
 * @return string
 */
function get_taxonomy_terms_cloud($post_type, $title) {
    if (empty($post_type))
        $post_type = array('oxy_content', 'oxy_video', 'oxy_audio');
    $args = array(
        'hide_empty' => 1,
        'taxonomy' => 'teaching_topics',
        'pad_counts' => 1,
        'hierarchical' => 0
    );
    $categories = get_categories($args);
    $output = '<div id="tag_cloud-3" class="sidebar-widget  widget_tag_cloud">';
    $output .= '<div class="tagcloud">';
    $output .= '    <h3 class="sidebar-header">'.$title.'</h3>';
    $output .= '<ul>';
    $output .= get_taxonomy_terms_as_list($categories, $post_type);
    $output .= '</ul>';
    $output .= '</div>';
    $output .= '</div>';
    echo $output;
}

/**
 * @description get all taxonomies which contain posts and return as list, used for (video-)archive 
 * @param array $taxonomies <i>taxonomies</i>
 * @param string $post_type <i>type of post</i>
 * @return string
 */
function get_taxonomy_terms_as_list($taxonomies, $post_type){
    $add_all = true;
    foreach ($taxonomies as $category) {
        $posts_in_category = get_posts(array(
            'showposts' => -1,
            'post_type' => $post_type,
            'tax_query' => array(array(
                    'taxonomy' => 'teaching_topics',
                    'field' => 'slug',
                    'terms' => $category->slug)
            )
        ));
        $count = count($posts_in_category);
        $tax_name = " " . $category->name . " (" . $count . ")";
        //dont add post type for theme, but do link for all post types
        if (is_array($post_type))
            $link = home_url() . "/blog/teaching_topics/" . $category->slug;
        else
            $link = home_url() . "/blog/teaching_topics/" . $category->slug . "/?post_type=" . $post_type;
        if($add_all){
            $posts_all = get_posts(array(
            'post_type' => $post_type,
            'showposts' => -1,    
            )
            );
            $count_all = count($posts_all);
            if($post_type == 'oxy_video'){
                $link_all = home_url() . "/videoarchive";
                $title = __('Show all videos', THEME_FRONT_TD). " (" . $count_all . ") ";
            }elseif (oxy_content){
                $title = __('Show all articles', THEME_FRONT_TD). " (" . $count_all . ") ";
                $link_all = home_url() . "/archive";
            }
            $output = "<li><a href='" . $link_all . "' class='tag-link-22' title='" . $count_all . " записи'  style='font-size:10pt;' >" . $title . "</a></li>";
            $add_all = FALSE;
        }
        if (!empty($count)) 
            $output .= "<li><a href='" . $link . "' class='tag-link-22' title='" . $count . " записи'  style='font-size:10pt;' >" . $tax_name . "</a></li>";
    }
    return $output;
}

/**
 * @description get related for particular post, is used for example on single post page
 * @param array $atts <i>attributes</i>
 * @return string
 */
function get_related_posts($atts) {
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
        return create_section_with_text_items($my_query, $atts);
    }
}

/**
 * @description create taxonomy topic page with short summary of taxonomy term and corresponding text/video items
 * @param string $taxonomy term <i>term of taxonomy</i>
 * @return string
 */
function create_section_with_text_items($my_query, $atts = null) {
    $columns = $my_query->post_count > 4 ? 4 : $my_query->post_count;
    $span = $columns > 0 ? 'span' . floor(12 / $columns) : 'span3';

    $output = '';
    if ($my_query->have_posts()) :
        $output .='<ul class="unstyled row-fluid">';
        global $post;
        $item_num = 1;
        $items_per_row = $columns;
        //loop over all related posts
        while ($my_query->have_posts()) {
            $my_query->the_post();
            setup_postdata($post);
            if ('link' == get_post_format()) {
                $post_link = oxy_get_external_link();
            } else {
                $post_link = get_permalink();
            }

            if ($item_num > $items_per_row) {
                $output.= '</ul><ul class="unstyled row-fluid">';
                $item_num = 1;
            }

            $output .='<li class="' . $span . '">';
            $output .='<div class="round-box box-medium box-colored"><a href="' . $post_link . '" class="box-inner">';
            //get post icon
            if (has_post_thumbnail($post->ID)) {
                $output .= get_the_post_thumbnail($post->ID, 'portfolio-thumb', array('title' => $post->post_title, 'alt' => $post->post_title, 'class' => 'img-circle'));
                $output .= oxy_post_icon($post->ID, false);
            } else {
                $output .= '<img class="img-circle" src="' . IMAGES_URI . 'box-empty.gif">';
                $output .= oxy_post_icon($post->ID, false);
            }

            $output .='</div>';
            //$output.='</a>';
            $output.='<a href="' . $post_link . '"> <h3 class="text-center">' . get_the_title() . '</h3></a>';

            $content = get_post_summary_mini($post);
            $more_text = __('Read more', THEME_FRONT_TD);
            $link = get_permalink();
            $content .= '<a href="' . $link . '" class="more-link">' . $more_text . '</a>';
            $output.='<p>' . apply_filters('the_content', $content) . '</p></li>';
            $item_num++;
        }
        $output .= '</ul>';
        // reset post data
        wp_reset_postdata();
        return oxy_shortcode_section($atts, $output);
    endif;
}

/**
 * @description do limit string by word count
 * @param string $string <i>string to be limited</i>
 * @param int $word_limit <i>amount of words for limit</i>
 * @param bool $add_punkts <i>whether to add punkts at the end </i>
 * @return string
 */
function hb_limit_string($string, $word_limit, $add_punkts = false) {
    $words = explode(' ', $string, ($word_limit + 1));
    if (count($words) > $word_limit) {
        array_pop($words);
    }

    if ($add_punkts)
        return implode(' ', $words) . ' ...';
    return implode(' ', $words);
}
/**
 * @description get attached video from post and return it as video wrapper, used for archive
 * @param post $post <i>post</i>
 * @return string
 */
function get_video_content($post) {
    $video_shortcode = get_field('video_shortcode', $post->ID);
    $content = $post->post_content;

    $output = create_videowrapper_div($video_shortcode) .
            '<div class="span4" style="margin-top: 25px;">' .
            $content .
            '</div>';
    echo $output;
}

/**
 * @description create taxonomy topic page with short summary of taxonomy term and corresponding text/video items
 * @param string $taxonomy term <i>term of taxonomy</i>
 * @return string
 */
function get_taxonomy_topic_page($taxonomy_term) {
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
        $summary = $taxonomy_term->description . '<p><br><br></p>';
        $output = oxy_shortcode_layout(NULL, $summary, 'container-fluid');
        $output = oxy_shortcode_layout(NULL, $output, 'row-fluid');
        $output = oxy_shortcode_layout(NULL, $output, 'span12');
    }

    if ($count == 1) {
        $output .= create_one_text_items($text_items[0]);
    } elseif ($count == 2) {
        $output .= create_two_text_items($text_items[0], $text_items[1]);
    } elseif ($count == 3) {
        $output .= create_three_text_items($text_items[0], $text_items[1], $text_items[2]);
    } elseif ($count == 4) {
        $output .= create_two_text_items($text_items[0], $text_items[1]);
        $output .= create_two_text_items($text_items[2], $text_items[3]);
    } elseif ($count == 5) {
        $output .= create_two_text_items($text_items[0], $text_items[1]);
        $output .= create_three_text_items($text_items[2], $text_items[3], $text_items[4]);
    } elseif ($count == 6) {
        $output .= create_three_text_items($text_items[0], $text_items[1], $text_items[2]);
        $output .= create_three_text_items($text_items[3], $text_items[4], $text_items[5]);
    } elseif ($count > 6) {
        //$output . create_more_text_items($text_items);
        $output .= create_section_with_text_items(new WP_Query($query));
    }
    
    $atts[title] = __('In this topic ...', THEME_FRONT_TD); //'В этой теме ...';        
    $output = oxy_shortcode_section($atts, $output);
    $output .= get_flexi_slider_for_taxonomy_topic_page($taxonomy_term->slug);
    return $output;
}

/**
 * @description create text item as quote for taxonomy topic page
 * @param post $post <i>post</i>
 * @param string $content optional <i>optional content</i>
 * @return string
 */
function create_one_text_items($post, $content = '') {
    $output = get_post_summary_as_quote($post, 'span12', $content);
    $output = oxy_shortcode_layout(NULL, $output, 'container-fluid');
    return $output;
}

/**
 * @description create 2 text items as quote for taxonomy topic page
 * @param post $first_item <i>first post item</i>
 * @param post $second_item <i>second post item</i>
 * @return string
 */
function create_two_text_items($first_item, $second_item) {
    $post = $first_item;
    $output = get_post_summary_as_quote($post, 'span6');
    $post = $second_item;
    $output .= get_post_summary_as_quote($post, 'span6');
    $output = oxy_shortcode_layout(NULL, $output, 'container-fluid');
    return $output;
}

/**
 * @description create 3 text items as quote for taxonomy topic page
 * @param post $first_item <i>first post item</i>
 * @param post $second_item <i>second post item</i>
 * @param post $third_item <i>third post item</i>
 * @return string
 */
function create_three_text_items($first_item, $second_item, $third_item) {
    $post = $first_item;
    $output = get_post_summary_as_quote($post);
    $post = $second_item;
    $output .= get_post_summary_as_quote($post);

    $post = $third_item;
    $output .= get_post_summary_as_quote($post);
      
    $output = oxy_shortcode_layout(NULL, $output, 'container-fluid');
    return $output;
}

/**
 * @description get summary of post as quote, used on taxonomy topic page 
 * @param post $post <i>post instance of text post</i>
 * @param string $span optional <i>span size, default is span4</i>
 * @param string $summary optional <i>summary text, default is empty</i>
 * @return string
 */
function get_post_summary_as_quote($post, $span = 'span4', $summary = '') {
    if (empty($summary))
        $summary = get_post_summary_mini($post);
    else
        $summary = $summary;
    $more_text = __('Read more', THEME_FRONT_TD);
    $link = get_post_permalink($post->ID, false, false);
    $more_text = '<a href="' . $link . '" class="more-link">' . $more_text . '</a>';
    $output = '<h3><a href="' . get_post_permalink($post->ID, false, false) . '">' . $post->post_title . '</a></h3>
                        <blockquote><p>' . $summary . $more_text . '</p></blockquote><a href="' . get_post_permalink($post->ID, false, false) . '">' .
            add_image_to_text_item($post) .
            '</a>';
    $output = oxy_shortcode_layout(NULL, $output, 'well blockquote-well well_custom_2col_mb');
    $output = oxy_shortcode_layout(NULL, $output, $span);
    return $output;
}

/**
 * @description get taxonomy term summary, used for example on topic page 
 * @param taxonomy $taxonomy <i>post instance of text post</i>
  * @return string
 */
function get_taxonomy_term_summary_mini($taxonomy) {
    $summary = get_field('taxonomy_summary', 'teaching_topics_' . $taxonomy->term_id);
    if (empty($taxonomy)) {
        $summary = $taxonomy->description . " ";
        $summary = oxy_limit_excerpt($summary, 40);
    }
    return $summary;
}

/**
 * @description create round box with image in it for short summary of text item on topic page 
 * @param post $post <i>post instance of text post</i>
 * @param string size <i>size of image box</i>
 * @return string
 */
function add_image_to_text_item($post, $size = 'medium') {
    if (has_post_thumbnail($post->ID)) {
        $output .='<div class="round-box box-' . $size . ' box-colored"><span class="box-inner">';
        $output .= get_the_post_thumbnail($post->ID, 'portfolio-thumb', array('title' => $post->post_title, 'alt' => $post->post_title, 'class' => 'img-circle'));
        $output .= oxy_post_icon($post->ID, false);
        $output .= '</span></div>';
        return $output;
    }
}

/**
 * @description create round box with image in it
 * @param string $img_src_url <i>image url</i>
 * @param string size <i>size of box</i>
 * @return string
 */
function get_image_as_round_box($img_src_url, $size = 'medium') {
    $output .='<div class="round-box box-' . $size . ' box-colored"><span class="box-inner">';
    $output .= '<img class="img-circle" src="' . $img_src_url . '">';
    $output .= '</span></div>';
    return $output;
}

/**
 * @description function creates flexi slider with videos from one particular taxonomy topic(term) it, used on topic page
 * @param string $slug_or_id <i>taxonomy term slug or id</i>
 * @return string
 */
function get_flexi_slider_for_taxonomy_topic_page($slug_or_id) {
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
        $output .= create_hero_section_with_video($atts);
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
function create_videowrapper_div($src_url, $span = "span8", $width = "1250", $height = "703") {
    $output = '<div class=' . $span . '>
                         <div class="entry-content">
                           <div class="videoWrapper">
                              <iframe src="' . $src_url . '" width="' . $width . '" height="' . $height . '" frameborder="0" webkitallowfullscreen="" mozallowfullscreen="" allowfullscreen=""></iframe>
                           </div>
                        </div>
             </div>';
    return $output;
}

/**
 * @description function to get summary of custom posts
 * @param string $post <i>post item</i>
 * @return string
 */
function get_post_summary_mini($post) {
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
function get_assigned_taxonomy_terms($post) {
    $output = '<div id="tag_cloud-3" class="sidebar-widget  widget_tag_cloud">';
    $output .= '<div class="tagcloud">';
    $output .= '    <div class="tagcloudThema">' . __('Go to topic', THEME_FRONT_TD) . ':' . '</div>';
    $output .= '<ul>';
    $taxonomy = "teaching_topics";
    $terms = wp_get_post_terms($post->ID, $taxonomy);
    if ($terms) {
        foreach ($terms as $individual_term) {
            $term_link = get_term_link($individual_term);
            $output .= "<li><a href='" . $term_link . "' title='" . $individual_term->name . "'  style='font-size:" . 12 . "pt;' >" . $individual_term->name . "</a></li>";
        }
    }
    $output .= '</ul>';
    $output .= '</div>';
    $output .= '</div>';
    return $output;
}

/**
 * @description function to create more text string
 * @param string $post_type <i> post type of post </i>
 * @return more text string
 */
function get_more_text($post_type) {
    $more_text = __('Read more', THEME_FRONT_TD);
    switch ($post_type) {
        case 'oxy_video':
            $more_text = __('Goto video', THEME_FRONT_TD);
            break;
        case 'oxy_audio':
            $more_text = __('Goto audio', THEME_FRONT_TD);;
            break;
        default :
            break;
    }
    return $more_text;
}

//////UI_ELEMENTS///////////

function get_hb_more_text_link($link, $more_text) {
    return get_hb_link(
            array(
                'class' => 'more-link',
                'content' => $more_text,
                'link' => $link));
}

/**
 * @package UI_ELEMENT_HTML
 * @description function to create a link
 * @param array $atts <i> id , class, content, link </i>
 * @return string
 */
function get_hb_link($atts) {
    extract(shortcode_atts(array(
        'id' => '',
        'class' => '',
        'content' => '',
        'link' => ''), $atts));
    return '<a href="' . $link . '" ' . set_attributes_hb($id, $class) . '>' . $content . '</a>';
}

/**
 * @package UI_ELEMENT_HTML
 * @description function to create a title (H1, H2 ...)
 * @param array $atts <i> id , class, content, tag </i>
 * @example $tag for H3 is 3
 * @return string
 */
function get_hb_title($atts) {
    extract(shortcode_atts(array(
        'id' => '',
        'class' => '',
        'content' => '',
        'tag' => ''), $atts));
   
    return $result = '<h' . $tag . set_attributes_hb($id, $class) . '>' . $content . '</h' . $tag . '>';
}

/**
 * @package UI_ELEMENT_HTML
 * @description function to create a blockquote
 * @param array $atts <i> id , class, content, params (who , site) </i>
 * @see oxy_shortcode_blockquote($params, $content);
 * @return string
 */
function get_hb_oxy_shortcode_blockquote($atts) {
    extract(shortcode_atts(array(
        'id' => '',
        'class' => '',
        'content' => '',
        'params' => ''), $atts));

    if ($params == '' || $params == NULL) {
        $result = '<blockquote ' . set_attributes_hb($id, $class) . '>';
        $result .= $content;
        $result .= '</blockquote>';
    } else {
        extract(shortcode_atts(array(
            'who' => '',
            'cite' => ''), $params));
        if ($who != null && $cite != null) {
            return oxy_shortcode_blockquote($params, $content);
        } else if ($who != null) {
            return '<blockquote' . set_attributes_hb($id, $class) . '>' . do_shortcode($content) . '<small>' . $who . '</small></blockquote>';
        } else {
            return get_hb_oxy_shortcode_blockquote(array(
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
function get_hb_section_background_image_simple($atts) {
    extract(shortcode_atts(array(
        'class' => '',
        'data_background' => '',
        'image_link' => '',
        'content' => ''), $atts));
    return '<section class="' . $class . '"data-background="' . $data_background . '" style="' . $image_link . '">'
            . $content . '</section>';
}

/**
 * @package HTML_HELPER
 * @description function to generate a attributes for html-elements
 * @param string $id
 * @param string $class
 * @return string
 */
function set_attributes_hb($id, $class) {
    $string = ' ';
    if (checkElement($id)) {
        $string .= 'id="' . $id . '" ';
    }
    if (checkElement($class)) {
        $string .= 'class="' . $class . '" ';
    }
    return $string;
}

/**
 * @package HELPER
 * @description function to check if a element exists
 * @param string $element
 * @return boolean only <b>TRUE</b> or <b>FALSE</b>. Not NULL.
 */
function checkElement($element) {
    if ($element != NULL && $element != '') {
        return TRUE;
    }
    return FALSE;
}

/**
 * @package HELPER
 * @description get to back <b>externa link</b> or <b>permalink</b>
 * @param string $post_format
 * @return string
 */
function get_hb_linkformat($post_format) {
    if (post_format == 'link') {
        return oxy_get_external_link();
    } else {
        return get_permalink();
    }
}

?>
