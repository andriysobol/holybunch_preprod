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

function get_video_as_fancybox($video_content, $style){
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
        if(empty($style)){
            $atts[style] = 'dark';
        }else{
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
        return 'Ты не указал картинку для это темы. Укажи картинку в таксономии: ' . $taxonomy_name;
    }
}

function get_query($taxonomy_category, $teaching_topic) {
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

function get_content_text_posts($teaching_topic){
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
    
function get_content_video_posts($teaching_topic){    
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

function get_content_for_category($taxonomy_category, $teaching_topic){
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

;
?>
