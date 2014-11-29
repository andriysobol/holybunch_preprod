<?php
require_once get_template_directory() . '/inc/options/shortcodes/shortcodes.php';
require_once CUSTOM_INCLUDES_DIR . 'hb_utility.php';
/**
 * Custom shortcode functions go here
 * @author Andriy Sobol
 */
/**
 * @return DIV HTML
 */
function oxy_shortcode_div($atts, $content = null) {
    extract(shortcode_atts(array(
        'class' => '',
        'id' => ''), $atts));

    $output = '<div id="' . $id . '" class="' . $class . '">';
    $output .= do_shortcode($content); 
    $output .= '</div>';
    return $output;
}
add_shortcode('div', 'oxy_shortcode_div');


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
	'number'       => '2',
    );
    $categories = get_categories($args);
    //loop over all related posts
    $output_loop = '';
    foreach ($categories as $taxonomy) {
        $link = get_term_link( $taxonomy );
        $summary = get_taxonomy_term_summary_mini($taxonomy);
        $more_text = get_hb_more_text_link($link,  __('Go to topic', THEME_FRONT_TD));
        $title = get_hb_title(3, NULL, get_hb_link($link, NULL, $taxonomy->name));
        $blockquote = get_hb_oxy_shortcode_blockquote("margin_bottom_25px_mb", '<p>' . $summary . $more_text . '</p>', NULL); 
        $taxonomy_image_link = get_taxonomy_image('teaching_topics', $taxonomy->slug);
        $round_link = get_hb_link($link, NULL, get_image_as_round_box($taxonomy_image_link));

        $output_loop .= oxy_shortcode_div(array('class' => 'well blockquote-well'), $title . $blockquote . $round_link);
    }
    $output = oxy_shortcode_div(array('class' => 'unstyled row-fluid'), $output_loop);
    return oxy_shortcode_section($atts, $output);
}
add_shortcode('latest_taxonomy_topics', 'get_latest_taxonomy_topics_as_list');

//

/**
 * @description used on main page for latest videos
 * @global type $post
 * @param array $atts
 * @return string
 */
function hb_get_recent_oxy_video($atts) {
    extract(shortcode_atts(array(
        'title' => '',
        'cat' => null), 
            $atts));

    $args = array(
        'post_type' => array('oxy_video'),
        'showposts' => 3, // Number of related posts that will be shown.  
        'orderby' => 'date'
    );
    $my_query = new wp_query($args);
    if ($my_query->have_posts()) :    
        global $post;
        while ($my_query->have_posts()) {
            $my_query->the_post();
            setup_postdata($post);
            $date = get_the_time(get_option("date_format"));
            $post_link = get_hb_linkformat(get_post_format());            
            $icon_class_array = explode('"', oxy_post_icon($post->ID, false));
            
            $span3 = oxy_shortcode_image(array(
                'size'       => 'box-medium',
                'source'     => CUSTOM_IMAGES_DIR . 'video1.jpg',
                'icon'       => $icon_class_array[1],
                'link'       => $post_link
            ));
            $span3 .= get_hb_title(5, "text-center light", $date);
            
            $title_span9 = get_hb_title(3, "text-center", get_the_title());            
            $content_span9 = oxy_limit_excerpt(get_the_content(), 15);
            $content_span9 .= get_hb_more_text_link(get_permalink(), get_more_text($post->post_type));
            $span9 = get_hb_link($post_link, NULL, $title_span9);
            $span9 .='<p>' . apply_filters('the_content', $content_span9) . '</p>';
            
            
            $merge_spans = oxy_shortcode_layout( NULL, $span3, 'span3');
            $merge_spans .= oxy_shortcode_layout( NULL, $span9, 'span9');
            $result .= oxy_shortcode_layout( NULL, $merge_spans, 'span4');
        }
    endif;
    // reset post data
    wp_reset_postdata();
    return oxy_shortcode_section($atts, $result);
}
add_shortcode('hb_recent_videos', 'hb_get_recent_oxy_video');








##################################################
######                                      ###### 
######          Code zum Bearbeiten         ###### 
######                                      ###### 
##################################################

/* Show content items of category, used for archive of internal recorded videos */
function oxy_shortcode_content_items($atts) {
    extract(shortcode_atts(array(
        'category' => '',
        'count' => 3,
        'columns' => 3,
        'links' => 'show',
        'lead' => 'hide',
        'title' => '',
        'style' => '',
        'title_size' => 'medium',
        'image_style' => ''
                    ), $atts));

    $query = array(
        'post_type' => 'oxy_content',
        'posts_per_page' => $count,
        'orderby' => 'title'
    );

    if (!empty($category)) {
        $query['tax_query'] = array(
            array(
                'taxonomy' => 'oxy_content_category',
                'field' => 'slug',
                'terms' => $category
            )
        );
    }

    global $post;
    $tmp_post = $post;

    $content_items = get_posts($query);
    $output = '';
    if (count($content_items > 0)) {
        $output .= '<ul class="unstyled row-fluid">';
        if ($title_size == 'big')
            $header = 'h2';
        else if ($title_size == 'medium')
            $header = 'h3';
        else
            $header = 'h4';
        $size = ($columns == 4) ? 'round-medium' : 'box-big';
        $text_class = ($lead == 'show') ? ' class="lead text-center"' : '';
        $items_per_row = ($columns == 3) ? 3 : 4;
        $span = ($columns == 4) ? 'span3' : 'span4';
        $service_num = 1;
        foreach ($content_items as $post) {
            setup_postdata($post);
            global $more;
            $more = 0;
            if ($links == 'show') {
                $link = oxy_get_slide_link($post);
                if (null == $link) {
                    $link = get_permalink();
                }
            }
            if ($service_num > $items_per_row) {
                $output .='</ul><ul class="unstyled row-fluid">';
                $service_num = 1;
            }
            $icon = get_post_meta($post->ID, THEME_SHORT . '_icon', true);
            $output .= '<li class="' . $span . '">';
            $output .= '<div class="round-box ' . $size . ' ' . $image_style . '">';
            if ($links == 'show') {
                $output .= '<a href="' . $link . '" class="box-inner">';
            } else {
                $output .= '<span class="box-inner">';
            }
            $output .= get_the_post_thumbnail($post->ID, 'portfolio-thumb', array('class' => 'img-circle', 'alt' => get_the_title()));
            if ($links == 'show') {
                $output .= '</a>';
            } else {
                $output .= '</span>';
            }
            if ($icon != '') {
                $output .= '<i class="' . $icon . '"></i>';
            }
            $output .= '</span>';
            $output .= '</div>';
            if ($links == 'show') {
                $output .= '<a href="' . $link . '">';
            }
            $output .= '<' . $header . ' class="text-center">' . get_the_title() . '</' . $header . '>';
            if ($links == 'show') {
                $output .= '</a>';
            }
            $shortcode_value = get_field('video_shortcode', $post->ID);
            $output .= '<p' . $text_class . '>' . apply_filters('the_content', $shortcode_value) . '</p>';
            if ($links == 'show') {
                $more_text = oxy_get_option('blog_readmore') ? oxy_get_option('blog_readmore') : 'Read more';
                $output .= '<a href="' . $link . '" class="more-link">' . $more_text . '</a>';
            }
            $output .= '</li>';
            $service_num++;
        }
        $output .= '</ul>';
    }

    //Always check if it's an error before continuing. get_term_link() can be finicky sometimes
    $term = get_term_by('slug', $category, 'oxy_content_category');
    $term_link = get_term_link($term, 'oxy_content_category');
    if (is_wp_error($term_link))
        continue;
    
    //We successfully got a link. Print it out.
    //Might be buggy
    /*$output .= '<div id="" class="span10"></div>';
    $output .= '<div id="" class="span2" style="height: 60px;border: orange 1px solid;margin-top: 0px;width: 170px;padding: 10px;margin-left:41px;">';
    $output .= '<span style="font-size: 15px; color: orange;"><i class="icon-signin icon-large"></i>';
    $output .= '<a href="' . $term_link . '"> &nbsp Далее к рубрике </a>';
    $output .= '</span><p></p></div>';
    */
    $post = $tmp_post;

    return oxy_shortcode_section($atts, $output);
}
add_shortcode('content_items', 'oxy_shortcode_content_items');

//used for example on main page for section with random video
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
    }else {
        $post_video = $post_video;
    }

    if (!empty($post_video)) {
        $title = $post_video->post_title;
        $summary = hb_limit_excerpt($post_video->post_content, 50);
        $shortcode = get_field('video_shortcode', $post_video->ID);
    }
    
    if(!empty($image)){
	$img_attachment = wp_get_attachment_image_src($image);
	$image = $img_attachment[0];
    }    
    
    if(empty($image)){
        $image = get_taxonomy_video_background_image('teaching_topics',$taxonomy_slug);
        if(empty($image))
            $image = get_theme_root_uri() . '/smartbox-theme-custom/images/background_video_default.jpg';
    }
  
    $output = '<section class="section section-padded section-dark" data-background="url(' . $image . ') no-repeat top" style="background: url(' . $image . ') 50% 0% no-repeat;">
                <div class="container-fluid">
                    <div class="super-hero-unit">
                        <h1 class="animated fadeinup delayed text-center">' . $title . '</h1>
                        <div class="row-fluid margin-top">
                            <div class="span4 margin-top margin-bottom">
                                <span align="left">
                                    <p>' . $summary . '</p></span>
                            </div>' . create_videowrapper_div($shortcode) . 
                        '</div>
                    </div>
                </div>
            </section>';
    return $output;
}
add_shortcode('hero_section_with_video', 'create_hero_section_with_video');

//used on contact page, about us as contact form
function hb_get_contact_form($atts,  $content = null) {
    // setup options
    extract(shortcode_atts(array(
        'title' => 'Contact us',
	'id' => ''), $atts));
    
    $output ='<div class="span5">';
    $output.= '<div class="contact-details">' . do_shortcode( $content ) . '</div>';
    $output.='</div>'; 
    $output .='<div class="span7">';
    $output .= do_shortcode( '[contact-form-7 id="'.$id.'" title="ContactForm"]' );
    $output.='</div>';
          
    return oxy_shortcode_section($atts, $output);
}
add_shortcode('hb_contact_form', 'hb_get_contact_form');

//used for integration of google calendar into section
function hb_add_element_into_wrapper($atts){
    extract(shortcode_atts(array(
        'title' => '',
        'style' => '',
        'src_url' => ''
                    ), $atts));
	$output = create_videowrapper_div($src_url, "span12");
	return oxy_shortcode_section($atts, $output);
}
add_shortcode('hb_add_into_wrapper', 'hb_add_element_into_wrapper');

//Blockquote
function hb_get_shortcode_blockquote( $atts, $content ) {
    extract( shortcode_atts( array(
        'who' =>'',
        'cite'  => '',
    ), $atts ));
    if($who != null && $cite != null){
        return '<blockquote>' . do_shortcode($content) . '<small>'.$who.' <cite title="source title">'.$cite.'</cite></small></blockquote>';
    } else if ($who != null){
        return '<blockquote>' . do_shortcode($content) . '<small>'.$who.'</small></blockquote>';
    } else {
        return '<blockquote>' . do_shortcode($content) . '</blockquote>';      
    }
}
add_shortcode( 'blockquote', 'hb_get_shortcode_blockquote' );

//shows recents blogs on main page
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
 $output = '';
    $my_query = new wp_query($args);
    if ($my_query->have_posts()){
        $output .='<ul class="unstyled row-fluid">';
			while ($my_query->have_posts()) {
			 global $post;
			$my_query->the_post();
			setup_postdata($post);
				$author_avatar = get_avatar(get_the_author_meta('ID'), 300 );
				$author  = get_the_author();
				$date = get_the_time(get_option("date_format"));
				if ('link' == get_post_format()) {
                   $post_link = oxy_get_external_link();
               } else {
                    $post_link = get_permalink();
                }
			
				$more_text=  get_more_text($post->post_type);
				$output .= '<li class="span6">';
				$output .= '<div class="row-fluid"><div class="span3 post-info">';
				$output .='<div class="round-box box-small">';
				$output .= $author_avatar. '</div><h5 class="text-center">'.$author.'</h5>';
				$output .= '<h5 class="text-center light">'.$date.'</h5></div>';
				$output .= '<div class="span9">';
				$output .= '<h3><a href="' . $post_link . '"> '. get_the_title() . '</a></h3>';
			 
				$content = oxy_limit_excerpt(strip_tags(get_the_content()), 30)  ;
				$content .='<a href="' . $post_link . '" class="more-link">' . $more_text . '</a>';
             
				$output.='<p>' . apply_filters('the_content', $content) . '</p></div></li>';
			}
    	}
	$output .= '</ul>';
              
    return oxy_shortcode_section($atts, $output);
}	
add_shortcode('hb_blog_posts', 'hb_get_recent_blog_posts');

//used on main pages in dutch and german in order to show latest conten
function hb_get_recent_oxy_content($atts) {          
    // setup options
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
    $output = '';
        if ($my_query->have_posts()) :
            $output .='<ul class="unstyled row-fluid">';
            global $post;
            //loop over all related posts
            while ($my_query->have_posts()) {
                $my_query->the_post();
                setup_postdata($post);
				$output .= '<li class="span4">';
				if ('link' == get_post_format()) {
                    $post_link = oxy_get_external_link();
                } else {
                    $post_link = get_permalink();
                }
                $output.='<a href="' . $post_link . '"> <h3 class="text-center">' . get_the_title() . '</h3></a>';
                $content =  get_field('summary', $post->ID);
                $more_text=  get_more_text($post->post_type);
                $content .= get_hb_more_text_link(get_permalink(), $more_text);
                $output.='<p>' . apply_filters('the_content', $content) . '</p></li>';
               
            }
            $output .= '</ul>';
    endif;
    // reset post data
    wp_reset_postdata();
    return oxy_shortcode_section($atts, $output);
}
add_shortcode('hb_recent_content', 'hb_get_recent_oxy_content');










