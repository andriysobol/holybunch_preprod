<?php

/**
 * Custom shortcode functions go here
 *
 * @package Smartbox
 * @subpackage Core
 * @since 1.0
 * @author Andriy Sobol
 * @copyright (c) 2013 Oxygenna.com
 * @license http://wiki.envato.com/support/legal-terms/licensing-terms/
 * @version 1.01
 */

function oxy_content_taxonomy_topic($atts, $content = '') {
    // setup options
    extract(shortcode_atts(array(
        'title' => '',
        'topic' => '', 
        'style' => '',
        'title' => '', 
        'excerpt_length' => ''
        ), $atts));
    //verify that term exists and get term id in order to get fields(description, video) value
    $taxonomy_name = 'teaching_topics';
    $term_details = term_exists($topic, $taxonomy_name);
    if ( is_array($term_details) ){
        $term_id = $term_details['term_id'];
        $termDiscription = term_description( $term_id, $taxonomy_name );
    }else{
        return 'Темы('. $topic . '), которую ты указал в shortcode не существует, используй существующую тему';
    }
    
    //in order to get custom field 'main_video' from taxonomy we have 
    //to call advanced custom fields plugin api and provide id of post which 
    //is combination of taxonomy name and id of term e.g. term 'god' => id = 39
    $video = get_field('main_video', 'teaching_topics_' . $term_id);
    if(is_array($video)){
        global $wp_embed;
        $video_content = $video[0]->post_content;
        $video_content = $wp_embed->run_shortcode( $video_content );
    }  else {
        return 'Ты не указал видео для это темы. Укажи видео в таксономии: ' . $taxonomy_name;
    }
  
    //add video to taxonomy topic and related links
    $content .= '[row]';
    $content .= '[span1]';
    $content .= '[/span1]';
    $content .= '[span7]';
    $content .= $video_content;
    $content .= '[/span7]';
    $content .= '[span1]';
    $content .= '[/span1]';
    
    $content .= '[span3]';
    $content .= '[iconlist id="blockRigthBlack"]';
    $content .= '<h3>а также по теме...</h3>';
    $content .= '[iconitem icon="icon-facetime-video" title="null"]<a href="/oxy_content_category?topic='. $taxonomy_name .'">Видео</a>[/iconitem]';
    $content .= '[iconitem icon="icon-book" title="null"]<a href="/oxy_content_category?topic='. $taxonomy_name .'">Текстовые проповеди</a>[/iconitem]';
    $content .= '[iconitem icon="icon-headphones" title="null"]<a href="/oxy_content_category?topic='. $taxonomy_name .'">Аудиопроповеди</a>[/iconitem]';
    $content .= '[iconitem icon="icon-music" title="null"]<a href="/oxy_content_category?topic='. $taxonomy_name .'">Псалмы</a>[/iconitem]';
    $content .= '[/iconlist]';
    $content .= '[iconlist id="blockRigthBlack"]';
    $content .= '[/span3]';
    $content .= '[/row]';
    
    //add description of taxonomy
    $content .= '[row]';
    $content .= '[span11]';
    $content .= '[blockquote class="block"]';
    $content .= $termDiscription;
    $content .= '[/blockquote]';
    $content .= '[/span11]';
    $content .= '[/row]';

    $atts[title] = $title;
    if(empty($style)){
        $atts[style] = 'dark';
    }else{
        $atts[style] = $style;
    }
    
    //add related posts; query for all related posts except main video which is already shown
    $wp_query = new WP_Query;
    $args = array(
        // post basics
        'post_type' => 'oxy_content', // check capitalization, make sure this matches your post type slug
        'post_status' => 'publish', // you may not need this line.
        'posts_per_page' => 3, // set this yourself, 10 is a placeholder
        'post__not_in' => array( $video[0]->ID ),
        // taxonomy
        'tax_query' => array(
            array(
                'taxonomy' => 'teaching_topics', // slug for desired tag goes here
                'field' => 'slug',
                'terms' => 'god', // should work without a slug, try it both ways...and use a variable, don't hardcode
            )
        )
    );
    $span = 'span4';
    $my_query = new wp_query($args);
    if ($my_query->have_posts()) {
        $content .= '[row]';
        $content .= '[span11]';
        $content .= '<ul class="unstyled row-fluid">';
        while ($my_query->have_posts()) :
            $post = $my_query->the_post();
            $img = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
            $content .='<li class="' . $span . '"><div class="round-box box-big"><span class="box-inner"><img alt="' . get_the_title() . '" class="img-circle" src="' . $img[0] . '">';
            $content .='</span></div><h3 class="text-center">' . get_the_title() . '<small class="block">' . $icon . '</small></h3>';
            $post_content = get_the_content();
            $content_more = apply_filters('summary_more', ' ' . '[...]');
            $content_more = '<a href="' . get_permalink() . '">' . $content_more . '</a>';
            $excerpt_length = empty($excerpt_length) ? 50 : $excerpt_length;
            $text = wp_trim_words($post_content, $excerpt_length);
            $text     = $text . $content_more;            
            $content .='<p class="no_li">' . $text . '</p>';
            $content .='<ul class="inline text-center big social-icons">';
            $content .= '</p>';

            $content .='</ul>';
            $content .='</li>';
            //$content .= '<li><a href="' . the_permalink() . '" rel="bookmark" title="Permanent Link to ' . the_title_attribute() . '">' . the_title() . ' </a></li>';
        endwhile;
        $content .= '</ul>';
        $content .= '[/span11]';
        $content .= '[/row]';
    }

    wp_reset_query();
    $content .= '[row][span10][/span10][span2][button icon="icon-share-alt" type="warning" size="btn-default" label="далее к теме" link="/holybunch_prep/blog/oxy_content?topic='.$topic.'" place="right"]';
    $content .= '[/span2][/row]';

    $output = oxy_shortcode_section($atts, $content);
    return $output;
}

add_shortcode('content_taxonomy_topic', 'oxy_content_taxonomy_topic');

/* Content List */

function oxy_content_itemlist_enhanced($atts, $content = '') {
    // setup options
    extract(shortcode_atts(array(
        'title' => '',
        'count' => 3,
        'contenttype' => '',
        'columns' => 3,
        'style' => '',
        'category' => '',
        'orderby' => '',
        'excerpt_length' => 5,
        'addicon' => '',
        'addtitle' => '',
        'style' => ''
                    ), $atts));

    //andrey: shortcode staff changed, column for value 1 added
    switch ($columns) {
        case 1:
            $span = 'span8';
            break;
        case 3:
            $span = 'span4';
            break;
        case 4:
            $span = 'span3';
            break;
        default:
            break;
    }
    
    //it is possible to provide several categories, split them and do array
    $category = trim( preg_replace( "/[\n\r\t ]+/", '', $category ), '' );
    $category = empty($category) ? '' : explode(',', $category) ;
   
     $query_options = array(
        'post_type' => 'oxy_content',
        'numberposts' => $count,
        'orderby' => $orderby
    );
    //add taxonomy for query if needed
    if (!empty($category)) {
        $query_options['tax_query'] = array(
            array(
                'taxonomy' => 'oxy_content_category',
                'field' => 'slug',
                'terms' => $category
            )
        );
    }

    // fetch posts
    $items = get_posts($query_options);
    $items_count = count($items);
    $output = '';
    if ($items_count > 0):
        if(!empty($style)) $output .= '<div id="'.$style.'">';
        if($addicon) $output .= '<ul class="icons " id="">';
        foreach ($items as $member) :
            global $post;
            $post = $member;
            setup_postdata($post);
            if($contenttype != 'content') $output .= '<li>';            
            //add icon which refers to category
            if ($addicon) {
                $assignedCategory = wp_get_post_terms( $post->ID, 'oxy_content_category', array("fields" => "slugs") );
                switch ($assignedCategory[0]) {
                    case 'video':
                        $icon = 'icon-facetime-video';
                        break;
                    case 'music':
                        $icon = 'icon-music';
                        break;
                    case 'text':
                        $icon = 'icon-book';
                        break;
                    default:
                        break;
                }
                $output .= '<h4>';
                $output .= '<i class="' . $icon . '"></i>';
                if(!$addtitle) $output .= '</h4>';
            } 
            
            if($addtitle) {
                if (!$addicon) $output .= '<h4>';
                $output .= get_the_title() . " : ";
                $output .= '</h4>';
            }
            if ($contenttype == 'excerpt') {
                $text = get_the_excerpt();
                $excerpt_more = apply_filters('excerpt_more', ' ' . '[...]');
                $excerpt_more = '<a href="' . get_permalink() . '">' . $excerpt_more . '</a>';
                $excerpt_length = $excerpt_length == 0 ? 999 : $excerpt_length;
                $text = wp_trim_words($text, $excerpt_length, $excerpt_more);
                $output .= $text;
            } else if($contenttype == 'summary') {
                $output .= '<p>';
                //get value of summary
                $summary = get_field('summary', $post->ID);    
                $summary_more = apply_filters('summary_more', ' ' . '[...]');
                $summary_more = '<a href="' . get_permalink() . '">' . $summary_more . '</a>';
                $excerpt_length = empty($excerpt_length) ? 999 : $excerpt_length;
                $text = wp_trim_words($summary, $excerpt_length);
                $output .= $text . $summary_more;
                $output .= '</p>';
            } else if($contenttype == 'content'){
                $output .= get_the_content();
            }
            $member_num++;
            if($contenttype != 'content') $output .= '</li>';
        endforeach;
        if ($addicon) $output .= '</ul>';
        if(!empty($style)) $output .= '</div>';
    endif;
    wp_reset_postdata();
    return $output;
}

add_shortcode('content_itemlist_enhanced', 'oxy_content_itemlist_enhanced');
