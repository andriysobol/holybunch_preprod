<?php

function get_category_term_link_for_taxonomy_topic($category_term, $teaching_topic) {
    $content_link = get_term_link($category_term, $taxonomy = 'oxy_content_category');
    if (!is_a($content_link, 'WP_Error')) {
        $term_link = $content_link . "?taxonomy=teaching_topics&term=" . $teaching_topic;
        return $term_link;
    }
    return '';
}

function get_taxonomy_image($taxonomy_name, $topic) {
    $term_details = term_exists($topic, $taxonomy_name);
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

function get_taxonomy_banner_image($taxonomy_name, $topic) {
    $term_details = term_exists($topic, $taxonomy_name);
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

function get_taxonomy_video_background_image($taxonomy_name, $topic) {
    $term_details = term_exists($topic, $taxonomy_name);
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

function get_post_banner_image($post, $taxonomy_name = 'teaching_topics') {
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
    $term_list = wp_get_post_terms($post->ID, $taxonomy_name, array("fields" => "all"));
    foreach ($term_list as $term) {
        $image = get_field('taxonomy_banner_image', 'teaching_topics_' . $term->term_id);
        $image_url = $image[url];
        if (!empty($image_url))
            return $image_url;
    }
    return get_theme_root_uri() . '/smartbox-theme-custom/images/banner_thema_default.jpg';
}

function get_query($taxonomy_category, $teaching_topic) {
    if (!empty($taxonomy_category)) {
        $args = array(
            // post basics
            'post_type' => 'oxy_video', // check capitalization, make sure this matches your post type slug
            'post_status' => 'publish', // you may not need this line.
            'posts_per_page' => 3, // set this yourself, 10 is a placeholder
            'post__not_in' => array($video[0]->ID),
            // taxonomy
            'tax_query' => array(
                array(
                    'taxonomy' => 'teaching_topics', // slug for desired tag goes here
                    'field' => 'slug',
                    'terms' => $teaching_topic, // should work without a slug, try it both ways...and use a variable, don't hardcode
                    'include_children' => false,
                ),
                array(
                    'taxonomy' => 'oxy_content_category', // slug for desired tag goes here
                    'field' => 'slug',
                    'terms' => $taxonomy_category, // should work without a slug, try it both ways...and use a variable, don't hardcode
                    'include_children' => false,
                )
            )
        );
    } else {
        $args = array(
            // post basics
            'post_type' => 'oxy_content', // check capitalization, make sure this matches your post type slug
            'post_status' => 'publish', // you may not need this line.
            'posts_per_page' => 3, // set this yourself, 10 is a placeholder
            'post__not_in' => array($video[0]->ID),
            // taxonomy
            'tax_query' => array(
                array(
                    'taxonomy' => 'teaching_topics', // slug for desired tag goes here
                    'field' => 'slug',
                    'terms' => $teaching_topic, // should work without a slug, try it both ways...and use a variable, don't hardcode
                    'include_children' => false,
                )
            )
        );
    }
    return new WP_Query($args);
}

function get_query_only_video($teaching_topic) {
    return get_query('video', $teaching_topic);
}

function get_query_only_music($teaching_topic) {
    return get_query('music', $teaching_topic);
}

function get_query_only_text($teaching_topic) {
    return get_query('text', $teaching_topic);
}

function get_teaching_topic_from_query() {
    //teaching topic can occur in url query as term or just a topic
    //try to get term
    $teaching_topic = get_query_var('term');
    if (empty($teaching_topic)) {
        $teaching_topic = get_query_var('teaching_topics');
    }
    return $teaching_topic;
}

function IsLocalEinvironment() {
    $server_name = $_SERVER['SERVER_NAME'];
    if (isset($server_name) && $server_name == "localhost")
        return true;
    return false;
}

function GetHostForJWScript() {
    if (is_ssl() && !IsLocalEinvironment())
        return 'https://ssl.jwpsrv.com/library/2vQezLOEEeOy_CIACi0I_Q.js';
    else if (is_ssl() && IsLocalEinvironment())
        return 'https://jwpsrv.com/library/2vQezLOEEeOy_CIACi0I_Q.js';
    else if (!IsLocalEinvironment())
        return 'https://ssl.jwpsrv.com/library/2vQezLOEEeOy_CIACi0I_Q.js';
    else
        return 'http://jwpsrv.com/library/2vQezLOEEeOy_CIACi0I_Q.js';
}

function get_taxonomy_terms_cloud($post_type, $title="Темы на выбор") {
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
    $output .= append_categories_as_list($categories, $post_type);
    $output .= '</ul>';
    $target = '_blank';
    $output .= '</div>';
    $output .= '</div>';
    echo $output;
}

function append_categories_as_list($categories, $post_type){
    $add_all = true;
    foreach ($categories as $category) {
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
                $link_all = home_url() . "/videos";
                $title = __('Show all videos', THEME_FRONT_TD). " (" . $count_all . ") ";
            }elseif (oxy_content){
                $title = __('Show all articles', THEME_FRONT_TD). " (" . $count_all . ") ";
                $link_all = home_url() . "/texts";
            }
            $output = "<li><a href='" . $link_all . "' class='tag-link-22' title='" . $count_all . " записи'  style='font-size:10pt;' >" . $title . "</a></li>";
            $add_all = FALSE;
        }
        if (!empty($count)) 
            $output .= "<li><a href='" . $link . "' class='tag-link-22' title='" . $count . " записи'  style='font-size:10pt;' >" . $tax_name . "</a></li>";
    }
    return $output;
}
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
        return create_section_with_itmes($my_query, $atts);
    }
}

function create_section_with_itmes($my_query, $atts = null) {
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

function hb_limit_excerpt($string, $word_limit, $add_punkts = false) {
    $words = explode(' ', $string, ($word_limit + 1));
    if (count($words) > $word_limit) {
        array_pop($words);
    }

    if ($add_punkts)
        return implode(' ', $words) . ' ...';
    return implode(' ', $words);
}

function get_video_content($post) {
    $video_shortcode = get_field('video_shortcode', $post->ID);
    $content = $post->post_content;

    $output = create_videowrapper_div($video_shortcode) .
            '<div class="span4" style="margin-top: 25px;">' .
            $content .
            '</div>';
    echo $output;
}

function get_audio_content() {
    $audio_shortcode = get_field('audio_shortcode', the_ID());
    if ($audio_shortcode !== null) {
        // use the video in the archives
        echo apply_filters('the_content', $audio_shortcode);
    } elseif (has_post_thumbnail()) {
        $img = wp_get_attachment_image_src(get_post_thumbnail_id(the_ID()), 'full');
        $img_link = is_single() ? $img[0] : get_permalink();
        $link_class = is_single() ? 'class="fancybox"' : '';
        echo '<figure>';
        if (oxy_get_option('blog_fancybox') == 'on') {
            echo '<a href="' . $img_link . '" ' . $link_class . '>';
        }
        echo '<img alt="featured image" src="' . $img[0] . '">';
        if (oxy_get_option('blog_fancybox') == 'on') {
            echo '</a>';
        }
        echo '</figure>';
    }
}

function hb_create_topic_page($taxonomy_term) {

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
        //$output .= oxy_shortcode_section($atts, '[lead centered="no"]'.$taxonomy_term->description.'[/lead]');
        $output .= create_text_item($taxonomy_term->description);
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
        $output .= create_section_with_itmes(new WP_Query($query));
    }

    $atts[title] = __('In this topic ...', THEME_FRONT_TD); //'В этой теме ...';        
    $output = oxy_shortcode_section($atts, $output);
    $output .= hb_create_flexi_slider_themen_page($taxonomy_term->slug, "oxy_video");
    return $output;
}

function create_text_item($summary) {
    $output = '<div class="container-fluid">
         <div class="row-fluid">
        <div class="span12">
          <p>' . $summary . '</p>
          <p><br><br></p>
        </div>
      </div>
    </div>';
    return $output;
}

function create_one_text_items($first_item, $content = '') {
    $output = '     <div class="container-fluid">
        <div>
        </div>
        <div class="row-fluid">
            <ul class="inline row-fluid">';
    $post = $first_item;
    $output .= add_post_summary_to_main_page($post, 'span12', $content);
    $output .= '</ul></div></div>';
    return $output;
}

function create_two_text_items($first_item, $second_item) {
    $output = '     <div class="container-fluid">
        <div>
        </div>
        <div class="row-fluid">
            <ul class="inline row-fluid">';
    $post = $first_item;
    $output .= add_post_summary_to_main_page($post, 'span6');
    $post = $second_item;
    $output .= add_post_summary_to_main_page($post, 'span6');
    $output .= '</ul></div></div>';
    return $output;
}

function create_three_text_items($first_item, $second_item, $third_item) {
    $post = $first_item;
    $output = '     <div class="container-fluid">
        <div>
        </div>
        <div class="row-fluid">
            <ul class="inline row-fluid">';

    $output .= add_post_summary_to_main_page($post);
    $post = $second_item;
    $output .= add_post_summary_to_main_page($post);

    $post = $third_item;
    $output .= add_post_summary_to_main_page($post);
    $output .= '</ul></div></div>';
    return $output;
}

function add_post_summary_to_main_page($post, $span = 'span4', $summary = '') {
    if (empty($summary))
        $summary = get_post_summary_mini($post);
    else
        $summary = $summary;
    $more_text = __('Read more', THEME_FRONT_TD);
    $link = get_post_permalink($post->ID, false, false);
    $more_text = '<a href="' . $link . '" class="more-link">' . $more_text . '</a>';
    $output = '<li class="' . $span . '">
                    <div class="well blockquote-well well_custom_2col_mb">
                      <h3><a href="' . get_post_permalink($post->ID, false, false) . '">' . $post->post_title . '</a></h3>
                        <blockquote><p>' . $summary . $more_text . '</p></blockquote><a href="' . get_post_permalink($post->ID, false, false) . '">' .
            add_image_to_text_item($post) .
            '</a></div>
                </li>';
    return $output;
}

function get_taxonomy_term_summary_mini($taxonomy) {
    $summary = get_field('taxonomy_summary', 'teaching_topics_' . $taxonomy->term_id);
    if (empty($taxonomy)) {
        $summary = $taxonomy->description . " ";
        $summary = oxy_limit_excerpt($summary, 40);
    }
    return $summary;
}

function add_image_to_text_item($post, $size = 'medium') {
    if (has_post_thumbnail($post->ID)) {
        $output .='<div class="round-box box-' . $size . ' box-colored"><span class="box-inner">';
        $output .= get_the_post_thumbnail($post->ID, 'portfolio-thumb', array('title' => $post->post_title, 'alt' => $post->post_title, 'class' => 'img-circle'));
        $output .= oxy_post_icon($post->ID, false);
        $output .= '</span></div>';
        return $output;
    }
}

function get_image_as_round_box($img_source, $size = 'medium') {
    $output .='<div class="round-box box-' . $size . ' box-colored"><span class="box-inner">';
    $output .= '<img class="img-circle" src="' . $img_source . '">';
    $output .= '</span></div>';
    return $output;
}

function hb_create_flexi_slider_themen_page($slug_or_ids, $post_type) {
    $slides = get_posts(array(
        'numberposts' => -1,
        'post_type' => $post_type,
        'tax_query' => array(
            array(
                'taxonomy' => 'teaching_topics',
                'field' => 'slug',
                'terms' => $slug_or_ids
            )
        ),
        'orderby' => 'menu_order',
        'order' => 'ASC'
    ));
    $xtracapsclss = ' fadeup animated delayed';
    $id = 'flexslider-' . rand(1, 100);
    if (count($slides) == 0)
        return '';
    $output .= '<section class="section section-alt">';
    $output .= '<div id="flexslider-100" class="flexslider flex-directions-fancy flex-controls-inside flex-controls-center" data-flex-animation="slide" data-flex-controlsalign="center" data-flex-controlsposition="inside" data-flex-directions="show" data-flex-speed="30000" data-flex-directions-position="inside" data-flex-controls="show" data-flex-slideshow="true">';
    $output .= '<ul class="slides">';
    foreach ($slides as $slide) {
        $output .= '<li>';
        $atts[random_posts] = false;
        $atts[post_video] = $slide;
        $atts[taxonomy_slug] = $slug_or_ids;
        $output .= create_hero_section_with_video($atts);
        $output .= '</li>';
    }
    $output .= '</ul>';
    $output .= '</div>';
    $output .= '</section>';
    return $output;
}

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

function get_post_summary_mini($post) {
    $summary = '';
    //in order to get custom field 'summary' from post we have 
    //to call advanced custom fields plugin api and provide id of post
    switch ($post->post_type) {
        case 'oxy_video':
            $summary = get_field('video_summary_mini', $post->ID);
            if (empty($summary)) {
                $summary = get_field('video_summary', $post->ID);
                $summary = hb_limit_excerpt($summary, 40);
            }
            break;
        case 'oxy_audio':
            $summary = get_field('audio_summary_mini', $post->ID);
            if (empty($summary)) {
                $summary = get_field('audio_summary', $post->ID);
                $summary = hb_limit_excerpt($summary, 40);
            }
            break;
        case 'oxy_content':
            $summary = get_field('summary_mini', $post->ID);
            if (empty($summary)) {
                $summary = get_field('summary', $post->ID);
                $summary = hb_limit_excerpt($summary, 40);
            }
            break;
        default :
            break;
    }
    if (empty($summary))
        $summary = hb_limit_excerpt($post->post_content, 40);
    return $summary;
}

function get_post_summary($post) {
    $summary = '';
    //in order to get custom field 'summary' from post we have 
    //to call advanced custom fields plugin api and provide id of post
    switch ($post->post_type) {
        case 'oxy_video':
            $summary = get_field('video_summary', $post->ID);
            break;
        case 'oxy_audio':
            $summary = get_field('audio_summary', $post->ID);
            break;
        case 'oxy_content':
            $summary = get_field('summary', $post->ID);
            break;
        default :
            break;
    }
    if (empty($summary))
        $summary = $post->post_content;
    return $summary;
}

function get_corresponding_terms($post) {
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
            ;
        }
    }
    $output .= '</ul>';
    $output .= '</div>';
    $output .= '</div>';
    return $output;
}

function get_more_text($post_type) {
    $more_text = __('Read more', THEME_FRONT_TD);
    switch ($post_type) {
        case 'oxy_video':
            $more_text = '<Strong>Перейти</Strong> к видео';
            break;
        case 'oxy_audio':
            $more_text = '<Strong>Перейти</Strong> к аудио';
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
