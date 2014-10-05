<?php

function get_category_term_link_for_taxonomy_topic($category_term, $teaching_topic) {
    $content_link = get_term_link($category_term, $taxonomy = 'oxy_content_category');
    if (!is_a($content_link, 'WP_Error')) {
        $term_link = $content_link . "?taxonomy=teaching_topics&term=" . $teaching_topic;
        return $term_link;
    }
    return '';
}

function get_post_type_link_for_taxonomy_topic($post_type, $teaching_topic) {
    $post_link = get_post_type_archive_link($post_type);
    if (!is_a($post_link, 'WP_Error')) {
        $term_link = $post_link . "?taxonomy=teaching_topics&term=" . $teaching_topic;
        return $term_link;
    }
    return '';
}

function get_taxonomy_description($taxonomy_name, $topic) {
    //verify that term exists and get term id in order to get fields(description, video) value
    $term_details = term_exists($topic, $taxonomy_name);
    if (is_array($term_details)) {
        $term_id = $term_details['term_id'];
        return term_description($term_id, $taxonomy_name);
    } else {
        return '';
    }
}

function get_taxonomy_video($taxonomy_name, $topic) {
    $term_details = term_exists($topic, $taxonomy_name);
    if (is_array($term_details)) {
        $term_id = $term_details['term_id'];
    } else {
        return '';
    }
    //in order to get custom field 'main_video' from taxonomy we have 
    //to call advanced custom fields plugin api and provide id of post which 
    //is combination of taxonomy name and id of term e.g. term 'god' => id = 39
    $video = get_field('main_video', 'teaching_topics_' . $term_id);
    if (is_array($video)) {
        global $wp_embed;
        $video_content = $video[0]->post_content;
        //post content contains embed short code, we don't need it but only video url
        //it is ugly but I don't find any other solution as just replace short code by empty
        $video_content = str_replace("[embed w=800]", "", $video_content);
        $video_content = str_replace("[/embed]", "", $video_content);
        return '<a class="fancybox-media" href="' . $video_content . '"> <img class="aligncenter" alt="video" src="http://bible-core.com/wp-content/uploads/2013/08/forThemeGreh.png" width="560" height="420" /></a>';
    } else {
        return 'Ты не указал видео для это темы. Укажи видео в таксономии: ' . $taxonomy_name;
    }
}

function get_taxonomy_content_item($taxonomy_name, $topic) {
    $term_details = term_exists($topic, $taxonomy_name);
    if (is_array($term_details)) {
        $term_id = $term_details['term_id'];
    } else {
        return '';
    }
    //in order to get custom field 'main_video' from taxonomy we have 
    //to call advanced custom fields plugin api and provide id of post which 
    //is combination of taxonomy name and id of term e.g. term 'god' => id = 39
    $text = get_field('main_text', 'teaching_topics_' . $term_id);
    if (is_array($text)) {
        global $wp_embed;
        $img = wp_get_attachment_image_src(get_post_thumbnail_id($text[0]->ID), 'full');
        $post_title = $text[0]->post_title;
        $content .= '<img class="aligncenter" src="' . $img[0] . '"/>';
        $content .='<p>' . $post_title . '</p>';
        return $content;
    } else {
        return 'Ты не указал видео для это темы. Укажи видео в таксономии: ' . $taxonomy_name;
    }
}

function get_video_as_fancybox($video_content, $style) {
    //post content contains embed short code, we don't need it but only video url
    //it is ugly but I don't find any other solution as just replace short code by empty
    $video_content = str_replace("[embed w=800]", "", $video_content);
    $video_content = str_replace("[/embed]", "", $video_content);
    $video_content = '<a class="fancybox-media" href="' . $video_content . '"> <img class="aligncenter" alt="video" src="http://bible-core.com/wp-content/uploads/2013/08/forThemeGreh.png" width="560" height="420" /></a>';
    $description = get_field('summary');
    $video_content .= '<blockquote>';
    $video_content .= $description;
    $video_content .= '</blockquote>';

    $atts[title] = $title;
    if (empty($style)) {
        $atts[style] = 'dark';
    } else {
        $atts[style] = $style;
    }
    return oxy_shortcode_section($atts, $video_content);
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
        return '';
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

function get_content_text_posts($teaching_topic) {
    $my_text_query = get_query_only_text($teaching_topic);
    $text_content;
    while ($my_text_query->have_posts()) {
        $my_text_query->the_post();
        if ($text_content == null) {
            $text_content = get_the_content();
            $content_more = '<a href="' . get_permalink() . '">' . '... <i>Читать далее</i>' . '</a>';
            $output_text .= '[section]';
            $output_text .= '[row][div style="color:#FFA500"]' . get_field('quote') . '[/div][/row]';
            $output_text .= '[row]' . wp_trim_words($text_content, 150, $content_more) . '[/row]';
            $output_text .= '[/section]';
            $text_title = get_the_title();
            $output .= oxy_shortcode_section(array('title' => $text_title, 'style' => "white"), $output_text);
        }
    }
    return $output;
}

function get_custom_template_directory() {
    $template = 'smartbox-theme-custom/';
    $theme_root = get_theme_root($template);
    $template_dir = "$theme_root/$template";
    return apply_filters('template_directory', $template_dir, $template, $theme_root);
}

function get_content_video_posts($teaching_topic) {
    $my_video_query = get_query_only_video($teaching_topic);
    $video_content;
    while ($my_video_query->have_posts()) {
        $my_video_query->the_post();
        if ($video_content == null) {
            $video_title = get_the_title();
            $video_content = get_the_content();
            $video_content = str_replace("[embed w=800]", "", $video_content);
            $video_content = str_replace("[embed]", "", $video_content);
            $video_content = str_replace("[/embed]", "", $video_content);
            $video_description = get_field('summary');
            $output_video .= '[row]';
            $output_video .= '[span7]';
            $output_video .= '<a class="fancybox-media" href="' . $video_content . '">';
            $output_video .= '<img class="aligncenter" alt="video" src="http://bible-core.com/wp-content/uploads/2013/08/forThemeGreh.png" width="444" height="325" />';
            $output_video .='</a>';
            $output_video .= '[/span7]';
            if ($video_description != null) {
                $output_video .= '[span5]' . $video_description . '[/span5]';
            }
            $output_video .= '[/row]';
            $output_video .= '[row][span9][/span9]';
            $output_video .= '[span3][button icon="icon-share-alt" type="warning" size="btn-default" label="Все видео по теме" link="' . get_category_term_link_for_taxonomy_topic('video', $teaching_topic) . '" place="right"]';
            $output_video .= '[/span3][/row]';

            $output .= oxy_shortcode_section(array('title' => $video_title, 'style' => "dark"), $output_video);
        }
    }
    return $output;
}

function get_content_for_category($taxonomy_category, $teaching_topic) {
    $my_query = get_query($taxonomy_category, $teaching_topic);
    global $wp_embed;
    global $post;
    $style = 'gray';
    while ($my_query->have_posts()) {
        $my_query->the_post();
        $content = get_the_content();
        if (get_post_format($post) == 'video') {
            if ($style == 'dark') {
                $style = 'gray';
            } else {
                $style = 'dark';
            }
            $output .= get_video_as_fancybox($content, $style);
        } else {
            $output .= '<div class="row-fluid margin-top">';
            $output .= '<div style="text-align: center"><h1>';
            $output .= the_title();
            $output .= '</h1></div><div class="span12" style = "color:#FFA500;">';
            $output .= get_field('quote');
            $output .= '</div><div class="span12">';
            $content_more = '<a href="' . get_permalink() . '">' . '... <i>Читать далее</i>' . '</a>';
            $output .= wp_trim_words($content, 150, $content_more);
            $output .= '<hr noshade size="4" align="center">';
            $output .= '</div></div></section>';
        }
    }
    return $output;
}

function get_query_for_category($taxonomy_category) {
    if (!empty($taxonomy_category)) {
        $args = array(
            // post basics
            'post_type' => 'oxy_content', // check capitalization, make sure this matches your post type slug
            'post_status' => 'publish', // you may not need this line.
            'posts_per_page' => 3, // set this yourself, 10 is a placeholder
            'post__not_in' => array($video[0]->ID),
            // taxonomy
            'tax_query' => array(
                array(
                    'taxonomy' => 'oxy_content_category', // slug for desired tag goes here
                    'field' => 'slug',
                    'terms' => $taxonomy_category, // should work without a slug, try it both ways...and use a variable, don't hardcode
                    'include_children' => false,
                )
            )
        );
    }
    return new WP_Query($args);
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

function get_label_of_source($source) {
    // Modify the last character of a string
    $end_position = strlen($source) - 4;

    //latest position of _ 
    $begin_position = strrpos($source, "_") + 1;

    if ($end_position > $begin_position)
        return substr($source, $begin_position, $end_position - $begin_position);
    return "unknow";
}

function get_taxonomy_drop_down($post_type) {
    if (empty($post_type))
        $post_type = "oxy_content";
    $args = array(
        'hide_empty' => 1,
        'taxonomy' => 'teaching_topics',
        'pad_counts' => 1,
        'hierarchical' => 1,
    );
    $categories = get_categories($args);
    $select = "<div ><h4 class='widgettitle'></h4>";
    $select .= "<select name='cat' id='cat' class='postform'>n";
    $select.= "<option value='-1'>Выбери тему</option>n";

    $root = 0;
    foreach ($categories as $category) {
        //define on which level you are by determining how depth are you from root
        $parent = $category->parent == 0 ? $category->term_id : $category->parent;
        $level = 0;
        if ($root == 0)
            $root = $parent;
        while ($parent != $root) {
            $level = $level + 1;
            foreach ($categories as $parent_category) {
                if ($parent_category->term_id == $parent) {
                    $parent = $parent_category->parent;
                    break;
                }
            }
        }
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
        //strpad makes following echo str_pad($input, 10, "-=", STR_PAD_LEFT);  // produces "-=-=-Alien"
        //we need to add whitespaces in order to create hierarchy
        $slug = $category->slug;
        $tax_name = $category->name . " (" . $count . ")";
        $tax_name = str_pad($tax_name, strlen($tax_name) + $level, ".", STR_PAD_LEFT);
        $link = home_url() . "/blog/teaching_topics/" . $category->slug . "/?post_type=" . $post_type;
        $select.= "<option value='" . $link . "'>" . $tax_name . "</option>";
    }

    $select.= "</select>";

    $select .= "<script type=\"text/javascript\">";
    $select .= "var dropdown = document.getElementById(\"cat\");";
    $select .= "function onCatChange() {";
    $select .= "    if (dropdown.options[dropdown.selectedIndex].value != -1) {       ";
    $select .= "  location.href = dropdown.options[dropdown.selectedIndex].value ";
    $select .= "    }";
    $select .= " } ";
    $select .= " dropdown.onchange = onCatChange;";
    $select .= "</script>";
    $select .= "</div>";

    echo $select;
}

function get_taxonomy_terms_cloud($post_type) {
    if (empty($post_type))
        $post_type = array('oxy_content', 'oxy_video', 'oxy_audio');
    $args = array(
        'hide_empty' => 1,
        'taxonomy' => 'teaching_topics',
        'pad_counts' => 1,
        'hierarchical' => 0,
    );
    $categories = get_categories($args);
    $output = '<div id="tag_cloud-3" class="sidebar-widget  widget_tag_cloud">';
    $output .= '<div class="tagcloud">';
    $output .= '    <div class="tagcloudThema">Темы на выбор:</div>';
    $output .= '<ul>';
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
        //strpad makes following echo str_pad($input, 10, "-=", STR_PAD_LEFT);  // produces "-=-=-Alien"
        //we need to add whitespaces in order to create hierarchy
        $slug = $category->slug;
        $tax_name = " " . $category->name . "(" . $count . ")";
        //dont add post type for theme, but do link for all post types
        if (is_array($post_type))
            $link = home_url() . "/blog/teaching_topics/" . $category->slug;
        else
            $link = home_url() . "/blog/teaching_topics/" . $category->slug . "/?post_type=" . $post_type;
        $font_size = 12 + $count / 1.5;
        $count_as_int = intval($count);
        if ($count_as_int <= 2)
            $tag = 'tag1';
        elseif ($count_as_int <= 4)
            $tag = 'tag2';
        elseif ($count_as_int <= 6)
            $tag = 'tag3';
        elseif ($count_as_int <= 8)
            $tag = 'tag4';
        elseif ($count_as_int <= 10)
            $tag = 'tag5';
        else
            $tag = 'tag6';
        if (!empty($count)) 
            $output.= "<li><a href='" . $link . "' class='tag-link-22' title='" . $count . " записи'  style='font-size:" . $font_size . "pt;' >" . $tax_name . "</a></li>";
    }
    $output .= '</ul>';
    $target = '_blank';
    $output .= '</div>';
    $output .= '</div>';
    echo $output;
}

    function get_related_posts($atts) {
        // setup options
        $atts = array(
            'title' => 'Также по теме',
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
    function create_section_with_itmes($my_query, $atts=null) {
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
                $more_text = '<Strong>Читать</Strong> далее';
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

    function get_related_posts_by_term($term) {
        // setup options
        $atts = array(
            'title' => 'Также в теме',
            'cat' => null,
            'count' => 4,
            'style' => '',
            'columns' => 4,
            'term' => '');
        $posts_by_term = get_posts(array(
            'showposts' => -1,
            'tax_query' => array(array(
                    'taxonomy' => 'teaching_topics',
                    'field' => 'slug',
                    'terms' => $term)
            )
        ));


        $output = '';
        if (!empty($posts_by_term)) {
            $columns = count($posts_by_term) > 4 ? 4 : count($posts_by_term);
            $span = $columns > 0 ? 'span' . floor(12 / $columns) : 'span3';
            $output .='<ul class="unstyled row-fluid">';
            global $post;
            $item_num = 1;
            $items_per_row = $columns;
            foreach ($posts_by_term as $post) {
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

                $output .='<li class="' . $span . '"><div class="row-fluid"><div class="span4">';
                $output .='<div class="round-box box-small box-colored"><a href="' . $post_link . '" class="box-inner">';
                if (has_post_thumbnail($post->ID)) {
                    $output .= get_the_post_thumbnail($post->ID, 'portfolio-thumb', array('title' => $post->post_title, 'alt' => $post->post_title, 'class' => 'img-circle'));
                    $output .= oxy_post_icon($post->ID, false);
                } else {
                    $output .= '<img class="img-circle" src="' . IMAGES_URI . 'box-empty.gif">';
                    $output .= oxy_post_icon($post->ID, false);
                }
                $output.='</a></div><h5 class="text-center light">' . get_the_date() . '</h5>';
                $output.='</div><div class="span8"><h3><a href="' . $post_link . '">' . get_the_title() . '</a>';
                $output.='</h3><p>' . oxy_limit_excerpt(get_the_excerpt(), 15) . '</p></div></div></li>';
                $item_num++;
            }
            $output .= '</ul>';
        }
        // reset post data
        wp_reset_postdata();
        return oxy_shortcode_section($atts, $output);
    }

    function get_video_content($post) {
        $video_shortcode = get_field('video_shortcode', $post->ID);
	$content = $post->post_content;

        $output = create_videowrapper_div($video_shortcode).
        '<div class="span4" style="margin-top: 25px;">'.
	 $content. 
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
        
        $atts[title] = 'В этой теме ...';        
        $output = oxy_shortcode_section($atts, $output);
        $output .= hb_create_flexi_slider_themen_page($taxonomy_term->slug, "oxy_video");
        return $output;
    }

    function crate_video_section_for_themen_page($video_post){
            $summary = get_field('video_summary', $video_post->ID);
            if(empty($summary))
	    	$summary = $video_post->post_content;
	    $shortcode = get_field('video_shortcode', $video_post->ID);
            $output = '<div class="container-fluid">
            <div class="span4" style="margin-top: 25px;">'.
              $summary. '
	     </div>'
             .create_videowrapper_div($shortcode).
         '</div>';
	$atts[title] = $video_post->post_title;
	return oxy_shortcode_section($atts, $output);
    }	

    function create_text_item_by_post($post) {
        $post = $post;
        $summary = get_post_summary($post);
        $more_text = '<Strong>Читать </Strong>далее';
        $link = get_post_permalink($post->ID, false, false);
        $more_text = '<a href="' . $link . '" class="more-link">' . $more_text . '</a>';
        $output .= '<div class="container-fluid">
      <div class="section-header">
        <h1>' . $post->post_title . '</h1>
      </div>
      <div class="row-fluid">
        <div class="span3"><a href="' . $link . '">' .
                add_image_to_text_item($post, 'big') .
                '</a></div>
        <div class="span9">
          <p class="lead">' . $summary . $more_text . '</p>
        </div>
      </div>
    </div>';
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
      function create_one_text_items($first_item) {
        $output = '     <div class="container-fluid">
        <div>
        </div>
        <div class="row-fluid">
            <ul class="inline row-fluid">';
        $post = $first_item;
        $output .= add_post_summary_to_main_page($post, 'span12');
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

    function add_post_summary_to_main_page($post, $span = 'span4') {
        $summary =  get_post_summary_mini($post);
	$more_text = '<Strong>Читать</Strong> далее';
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

    function add_taxonomy_term_summary($taxonomy, $span = 'span4') {
        $summary = get_taxonomy_term_summary_mini($taxonomy);
	$more_text = '<Strong>Перейти</Strong> к теме';
        $slug = $taxonomy->slug;
        $link = home_url() . "/blog/teaching_topics/" . $slug;
        $taxonomy_image_link = get_taxonomy_image('teaching_topics', $taxonomy->slug);

        $more_text = '<a href="' . $link . '" class="more-link">' . $more_text . '</a>';
        $output = '<li class="' . $span . '">
                    <div class="well blockquote-well well_custom_2col_mb">
                      <h3><a href="' . $link . '">' . $taxonomy->name . '</a></h3>
                        <blockquote><p>' . $summary . $more_text . '</p></blockquote>';
        if(has_post_thumbnail($post->ID)){
        $output .='<a href="' . $link . '">' .get_image_as_round_box($taxonomy_image_link) .'</a>';
        }
        $output.= '</div> </li>';
        return $output;
    }

    function get_taxonomy_term_summary_mini($taxonomy){
	$summary = get_field('taxonomy_summary', 'teaching_topics_' . $taxonomy->term_id);
        if(empty($taxonomy)){
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
      //  } else {
       //     return '';
        }
    }

    function get_image_as_round_box($img_source, $size = 'medium') {
        $output .='<div class="round-box box-' . $size . ' box-colored"><span class="box-inner">';
        $output .= '<img class="img-circle" src="' . $img_source . '">';
        $output .= '</span></div>';
        return $output;
    }

    function hb_create_flexslider($slug_or_ids, $post_type, $title, $style, $options = array()) {
        global $oxy_theme_options;
        global $post;
        $tmp_post = $post;
        extract(shortcode_atts(array(
            'captions' => $oxy_theme_options['captions'],
            'animation' => $oxy_theme_options['animation'],
            'speed' => $oxy_theme_options['speed'],
            'duration' => $oxy_theme_options['duration'],
            'directionnav' => $oxy_theme_options['directionnav'],
            'directionnavpos' => $oxy_theme_options['directionnavpos'],
            'controlsposition' => $oxy_theme_options['controlsposition'],
            'itemwidth' => '',
            'showcontrols' => $oxy_theme_options['showcontrols'],
            'captionanimation' => $oxy_theme_options['captionanimation'],
            'captionsize' => $oxy_theme_options['captionsize'],
            'autostart' => $oxy_theme_options['autostart'],
                        ), $options));

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
        $xtracapsclss = ( $captionanimation == 'animated') ? ' fadeup animated delayed' : '';
        //$flex_itemwidth = ($itemwidth !== '') ? ' data-flex-itemwidth=' . $itemwidth . 'px' : '';
        $id = 'flexslider-' . rand(1, 100);
        $output = '';
        $showcontrols = 'hide';
        $animation = 'true';
        $directionnav = 'show'; //directionnavpos=inside
        $directionnavpos = 'outside';
        $controlsposition = 'inside';
        $autostart = 'false';
        $flex_itemwidth = '444px';
        $output .= '<div id="' . $id . '" class="flexslider flex-directions-fancy" data-flex-itemwidth=' . $flex_itemwidth . ' data-flex-animation="' . $animation . '" data-flex-controlsalign="center" data-flex-controlsposition="' . $controlsposition . '" data-flex-directions="' . $directionnav . '" data-flex-speed="' . $speed . '" data-flex-directions-position="' . $directionnavpos . '" data-flex-controls="' . $showcontrols . '" data-flex-slideshow="' . $autostart . '" data-flex-duration="' . $duration . '">';
        $output .= '<ul class="slides">';

        global $post;
        foreach ($slides as $post) {
            setup_postdata($post);
            $output .= '<li><div class="super-hero-unit"><figure>';
            $output .='<ul class="unstyled row-fluid">';
            if ('link' == get_post_format()) {
                $post_link = oxy_get_external_link();
            } else {
                $post_link = get_permalink();
            }
            $output .='<li class="' . $span . '"><div class="row-fluid"><div class="span4">';
            $output .='<div class="round-box box-small box-colored"><a href="' . $post_link . '" class="box-inner">';
            if (has_post_thumbnail($post->ID)) {
                $output .= get_the_post_thumbnail($post->ID, 'portfolio-thumb', array('title' => $post->post_title, 'alt' => $post->post_title, 'class' => 'img-circle'));
                $output .= oxy_post_icon($post->ID, false);
            } else {
                $output .= '<img class="img-circle" src="' . IMAGES_URI . 'box-empty.gif">';
                $output .= oxy_post_icon($post->ID, false);
            }
            $output.='</a></div><h5 class="text-center light">' . get_the_date() . '</h5>';
            $output.='</div><div class="span8"><h3><a href="' . $post_link . '">' . get_the_title() . '</a>';
            $output.='</h3><p>' . oxy_limit_excerpt(get_the_excerpt(), 15) . '</p></div></div></li>';
            if ($captions == 'show') {
                $output .= '<figcaption class="flex-caption"><p class="' . $captionsize . $xtracapsclss . '">' . oxy_filter_title(get_the_title()) . '</p></figcaption>';
            }
            $output .= '</figure></div></li>';
        }
        $output .= '</ul>';
        $output .= '</div>';
        $post = $tmp_post;
        if ($post !== null) {
            //setup_postdata($post);
        }

        $atts = array('title' => $title, 'style' => $style);
        echo oxy_shortcode_section($atts, $output);
    }

    function hb_create_flexi_slider_themen_page($slug_or_ids, $post_type){
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
        if(count($slides) == 0)
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

    
    function hb_get_slide_link($post) {
        $link_type = get_post_meta($post->ID, THEME_SHORT . '_link_type', true);
        switch ($link_type) {
            case 'page':
                $id = get_post_meta($post->ID, THEME_SHORT . '_page_link', true);
                return get_permalink($id);
                break;
            case 'post':
                $id = get_post_meta($post->ID, THEME_SHORT . '_post_link', true);
                return get_permalink($id);
                break;
            case 'category':
                $slug = get_post_meta($post->ID, THEME_SHORT . '_category_link', true);
                $cat = get_category_by_slug($slug);
                return get_category_link($cat->term_id);
                break;
            case 'portfolio':
                $id = get_post_meta($post->ID, THEME_SHORT . '_portfolio_link', true);
                return get_permalink($id);
                break;
            case 'url':
                return get_post_meta($post->ID, THEME_SHORT . '_url_link', true);
                break;
        }
    }

    function create_videowrapper_div($src_url, $span="span8"){
	$output = '<div class='.$span.'>
                         <div class="entry-content">
                           <div class="videoWrapper">
                              <iframe src="'. $src_url . '" width="1250" height="703" frameborder="0" webkitallowfullscreen="" mozallowfullscreen="" allowfullscreen=""></iframe>
                           </div>
                        </div>
             </div>';
	return $output;
}
	
	function get_post_summary_mini($post){
		$summary = '';
		//in order to get custom field 'summary' from post we have 
                //to call advanced custom fields plugin api and provide id of post
                switch ($post->post_type) {
                    case 'oxy_video':
                        $summary = get_field('video_summary_mini', $post->ID);
			if(empty($summary)){
	                        $summary = get_field('video_summary', $post->ID);	
				$summary = hb_limit_excerpt($summary, 40);
			}
                        break;
                    case 'oxy_audio':
                        $summary = get_field('audio_summary_mini', $post->ID);
			if(empty($summary)){
	                        $summary = get_field('audio_summary', $post->ID);	
				$summary = hb_limit_excerpt($summary, 40);
			}
                        break;
                    case 'oxy_content':
                        $summary = get_field('summary_mini', $post->ID);
			if(empty($summary)){
	                        $summary = get_field('summary', $post->ID);	
				$summary = hb_limit_excerpt($summary, 40);
			}
                        break;
                    default :
                        break;
                }
		if(empty($summary))
			$summary = hb_limit_excerpt($post->post_content, 40);
	return $summary;
}

	function get_post_summary($post){
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
		if(empty($summary))
			$summary = $post->post_content;
	return $summary;
}

function get_corresponding_terms($post) {
    $output = '<div id="tag_cloud-3" class="sidebar-widget  widget_tag_cloud">';
    $output .= '<div class="tagcloud">';
    $output .= '    <div class="tagcloudThema">Перейти к теме:</div>';
    $output .= '<ul>';
    $taxonomy = "teaching_topics";
    $terms = wp_get_post_terms($post->ID, $taxonomy);
    if ($terms) {
        foreach ($terms as $individual_term) {
            $term_link = get_term_link($individual_term);
            $output .= "<li><a href='" . $term_link . "' title='" . $individual_term->name . "'  style='font-size:" . 12 . "pt;' >" . $individual_term->name . "</a></li>";;
        }
    }
    $output .= '</ul>'; 
    $output .= '</div>';
    $output .= '</div>'; 
    return $output;
}

    function get_more_text($post_type){
        $more_text= __( 'Read more', THEME_FRONT_TD ); 
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
    ;
    ?>