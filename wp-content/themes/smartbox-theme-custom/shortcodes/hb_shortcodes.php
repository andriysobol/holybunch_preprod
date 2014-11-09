<?php

/**
 * Custom shortcode functions go here
 * @author Andriy Sobol
 */
/**
 * DIV
 *
 * @return DIV HTML
 * */
function oxy_shortcode_div($atts, $content = null) {
    extract(shortcode_atts(array(
        'class' => '',
        'id' => '',
        'style' => '',
                    ), $atts));

    $output = '<div id="' . $id . '" class="' . $class . '" style="' . $style . '">';
    $output .= do_shortcode($content);
    $output .= '</div>';
    return $output;
}

add_shortcode('div', 'oxy_shortcode_div');

/**
 * Icon Item Shortcode - for use inside an iconlist shortcode
 * @return Icon Item HTML
 * */
function oxy_shortcode_iconitem_enhanced($atts, $content = null) {
    extract(shortcode_atts(array(
        'title' => '',
        'icon' => '',
        'href' => '',
                    ), $atts));

    $output = '<li>';
    $output .= '<h4>';
    $output .= '<a href="' . $href . '"/i>';
    $output .= '<i class="' . $icon . '"></i>';
    $output .= $title;
    $output .= $content;
    $output .= '</h4>';
    $output .= '</li>';
    return $output;
}

add_shortcode('iconitem_enh', 'oxy_shortcode_iconitem_enhanced');

function cmp($a, $b) {
    if ($a == $b) {
        return 0;
    }
    return ($a < $b) ? -1 : 1;
}

/**
 * Custom shortcode functions go here
 * @author Andriy Sobol
 */
function oxy_content_latest_topics($atts, $content = '') {
    // setup options
    extract(shortcode_atts(array(
        'style' => '',
        'excerpt_length' => '',
        'except' => ''
                    ), $atts));
    //get all taxonomy items and sort them by dates
    $taxonomy_name = 'teaching_topics';
    $terms = get_terms($taxonomy_name);
    $count = count($terms);
    $dates = array();
    foreach ($terms as $term) {
        $term_id = $term->term_id;
        $date = new DateTime(get_field('taxonomy_publiched_date', 'teaching_topics_' . $term_id));
        $date_formatted = date_format($date, 'Ymd');
        if ($date_formatted != null)
            $dates[$term_id] = get_field('taxonomy_publiched_date', 'teaching_topics_' . $term_id);
    }

    // Sort and print the resulting array
    arsort($dates);
    $output .= '<ul class="unstyled row-fluid">';
    $i = 0;
    while ($i < 3):
        $term_id = key($dates);
        $term = get_term($term_id, $taxonomy_name);
        $term_name = $term->name;
        //don't add taxonomy name which is alreay on main page as main taxonomy topic
        if ($term->slug == $except) {
            next($dates);
            continue;
        }
        $description = term_description($term_id, $taxonomy_name);
        $picture = get_field('taxonomy_image', 'teaching_topics_' . $term_id);
        $picture_url = $picture != null ? $picture[url] : null;

        $img = wp_get_attachment_image_src($picture, 'full');
        $content .='<li class="span4"><div class="round-box box-big"><span class="box-inner"><img alt="' . $title . '" class="img-circle" src="' . $picture[url] . '">';
        $content .='</span></div><h3 class="text-center">' . $term_name . '<small class="block">' . $icon . '</small></h3>';
        $content_more = apply_filters('summary_more', ' ' . '[...]');
        $content_more = '<a href="' . get_term_link($term_id, $taxonomy = $taxonomy_name) . '">' . $content_more . '</a>';
        $excerpt_length = empty($excerpt_length) ? 50 : $excerpt_length;
        $text = wp_trim_words($description, $excerpt_length);
        $text = $text . $content_more;
        $content .='<p class="no_li">' . $text . '</p>';
        $content .='<ul class="inline text-center big social-icons">';
        $content .= '</p>';

        $content .='</ul>';
        $content .='</li>';
        next($dates);
        $i++;
    endwhile;
    $output .= '</ul>';

    $output = oxy_shortcode_section($atts, $content);
    return $output;
}

//add_shortcode('latest_taxonomy_topics', 'oxy_content_latest_topics');

/* ------------ BLOCKQUOTE SHORTCODE ------------ */

function oxy_shortcode_topic_description($attrs, $content) {
    extract(shortcode_atts(array(
        'style' => '',
        'class' => ''), $attrs));
    return "<p class='" . $class . "' style='" . $style . "'>" . $content . "</p>";
}

function oxy_shortcode_blockquote_drops($atts, $content) {
    extract(shortcode_atts(array(
        'who' => '',
        'cite' => '',
        'align' => '',
        'width' => '',
                    ), $atts));

    $class = 'pullquote';
    if ($align == "left") {
        $class = 'pullquote_left';
    } elseif ($align == "right") {
        $class = 'pullquote_right';
    }
    return '<blockquote_drops class="' . $class . '">' . $content . '<small>' . $who . '</small></blockquote_drops>';
}

add_shortcode('blockquote_drops', 'oxy_shortcode_blockquote_drops');

/* Show content items of category */

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
        'orderby' => 'title',
        'order' => 'ASC'
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
            //$output .= '<p'.$text_class.'>' .  apply_filters( 'the_content', get_the_content('') ) . '</p>';
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
    $output .= '<div id="" class="span10"></div>';
    $output .= '<div id="" class="span2" style="height: 60px;border: orange 1px solid;margin-top: 0px;width: 170px;padding: 10px;margin-left:41px;">';
    $output .= '<span style="font-size: 15px; color: orange;"><i class="icon-signin icon-large"></i>';
    $output .= '<a href="' . $term_link . '"> &nbsp Далее к рубрике </a>';
    $output .= '</span><p></p></div>';
    //$output .= '</ul>';
    $post = $tmp_post;

    return oxy_shortcode_section($atts, $output);
}

add_shortcode('content_items', 'oxy_shortcode_content_items');

function oxy_shortcode_rtmp_player($atts) {
    extract(shortcode_atts(array(
        'ip' => '84.200.83.137',
        'stream' => 'myStream'
                    ), $atts));
    $output = '<div class = "span4">';
    $output .= '<strong>Начало:</strong> 12.00 Ландау (14.00 Московское время, 13.00 Киевское, 06.00 восточное сша)';
    $output .= '</div>';
    $output .= '<div class = "span8">';
    $output .= '<div id = "playerygRpQJGcOwEP">';
    $output .= '<script src = "' . GetHostForJWScript() . '"></script>';
    $output .= '<script type = \'text/javascript\'>';
    $output .= 'jwplayer(\'playerygRpQJGcOwEP\').setup({';
    $output .= 'playlist: [{';
    $output .= 'image: "' . get_theme_root_uri() . '/smartbox-theme-custom/images/broadcast.jpg",';
    $output .= 'sources: [';
    $output .= '{ file: "rtmp://84.200.83.137/live/myStream.sdp", },';
    $output .= '],';
    $output .= '}],';
    $output .= 'sources: [{';
    $output .= 'file: "http://84.200.83.137:1935/vod/mp4:sample.mp4/manifest.f4m"';
    $output .= '}],';
    $output .= 'sources: [{';
    $output .= 'file: "http://84.200.83.137:1935/vod/mp4:sample.mp4/playlist.m3u8"';
    $output .= '}],';
    $output .= 'height: 360,';
    $output .= 'rtmp: {';
    $output .= 'subscribe: true';
    $output .= '},';
    $output .= 'width: 640';
    $output .= '});';
    $output .= '</script></div></div>';
    $atts = array(
        'title' => 'Прямая трансляция'
    );
    return oxy_shortcode_section($atts, $output);
}

add_shortcode('rtmp_player', 'oxy_shortcode_rtmp_player');

function oxy_shortcode_js_player($atts) {
    extract(shortcode_atts(array(
        'sources' => '',
        'title' => '',
        'image' => '',
        'height' => '',
        'width' => ''
                    ), $atts));

    if (empty($image))
        $image = "\"" . get_theme_root_uri() . "/smartbox-theme-custom/images/broadcast.jpg\"";

    if (empty($height))
        $height = "200";

    if (empty($width))
        $width = "360";
    $uniqid = "player" . uniqid();
    $output = '<div id = "' . $uniqid . '">';
    $output .= '<script src = "' . GetHostForJWScript() . '"></script>';
    //$output .= '<script src = "http://84.200.83.37/wp-content/themes/smartbox-theme-custom/inc/js_player/JS_Player.js' . '"></script>';
    $output .= '<script type = \'text/javascript\'>';
    $output .= 'jwplayer(\'' . $uniqid . '\').setup({';
    $output .= ' height: ' . $height . ',';
    $output .= ' width: ' . $width . ',';
    $output .= 'playlist: [{';
    $output .= 'image: ' . $image . ',';
    $output .= 'sources: [';
    $sourcesArray = explode(",", $sources);
    $addComma = false;
    $counter = 0;
    foreach ($sourcesArray as $source) {
        if (isset($source)) {
            $counter = $counter + 1;
            if ($addComma)
                $output .= ',';
            $output .= '{ file: "' . $source . '", label: "' . $counter . '" }';
            $addComma = true;
        }
    }
    $output .= ']';
    $output .= '}]';
    $output .= '});';
    $output .= '</script></div>';
    $atts = array(
        'title' => $title
    );

    return $output;
}

add_shortcode('js_player', 'oxy_shortcode_js_player');

function audio_temp() {
    $content .= '[row]';
    $content .= '[span12]';
    $content .= '[div style="text-align: center; margin-top: 0px; color: orange; margin-bottom: 30px;"] ';
    $link_novyj_zavet = "http://bible-core.com/wp-content/uploads/audio/NovyjZavet.rar";
    $link_vetxij_zavet = "http://bible-core.com/wp-content/uploads/audio/VethijZavet.rar";
    $content .= '<a href=' . $link_novyj_zavet . '><span style="margin-right: 50px;">[[Новый Завет (1.3 ГБ)]]</span></a>';
    $content .= '<a href=' . $link_vetxij_zavet . '><span style="margin-right: 50px;">[[Ветхий завет (7 ГБ)]]</span>';
    $content .= '[/div]';
    $content .= '[/span12]';
    $content .= '[/row]';

    $atts = array('title' => 'Аудио Библия от Бондаренко');
    $output = oxy_shortcode_section($atts, $content);
    return $output;
}

add_shortcode('audio_bibel', 'audio_temp');

function get_latest_taxonomy_topics($atts) {
    $args = array(
        'hide_empty' => 1,
        'taxonomy' => 'teaching_topics',
        'pad_counts' => 1,
        'hierarchical' => 0,
	'number'       => '3',
    );
    $categories = get_categories($args);
    $count = count($categories);
    $columns = $count > 4 ? 4 : $count;
    $span = $columns > 0 ? 'span' . floor(12 / $columns) : 'span3';

    $output = '';
    $output .='<ul class="unstyled row-fluid">';

    $item_num = 1;
    $items_per_row = $columns;
    //loop over all related posts
    foreach ($categories as $category) {
        $output .= add_taxonomy_term_summary($category);
    }
    $output .= '</ul>';
    extract(shortcode_atts(array(
        'style' => '',
        'title' => ''
                    ), $atts));
    return oxy_shortcode_section($atts, $output);
}

add_shortcode('latest_taxonomy_topics', 'get_latest_taxonomy_topics');

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
    
    $post_link = get_post_permalink($post_video->ID, false, false);
    $more_text = '<Strong>Перейти</Strong> к видео';
    $read_more = '<a href="' . $post_link . '" class="more-link">' . $more_text . '</a>';
    $title = $title;
    $output = '<section class="section section-padded section-dark" data-background="url(' . $image . ') no-repeat top" style="background: url(' . $image . ') 50% 0% no-repeat;">
                <div class="container-fluid">
                    <div class="super-hero-unit">
                        <h1 class="animated fadeinup delayed text-center">' .
            $title . '</h1>
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

function hb_get_recent_posts($atts) {
    // setup options
    extract(shortcode_atts(array(
        'title' => 'Последние',
        'cat' => null,
        'count' => 4,
        'style' => '',
        'columns' => 4
                    ), $atts));

    $args = array(
        'post_type' => array('oxy_content', 'oxy_video', 'oxy_audio'),
        'showposts' => $count, // Number of related posts that will be shown.  
        'orderby' => 'date',
        'tax_query' => array(
            array(
                'taxonomy' => 'teaching_topics',
                'field' => 'slug',
                'terms' => 'god'
            )
        )
    );

    $my_query = new wp_query($args);
    $span = $columns == 3 ? 'span4' : 'span3';
    $output = '';
    if ($my_query->have_posts()) :
        $output .='<ul class="unstyled row-fluid">';
        global $post;
        $item_num = 1;
        $items_per_row = $columns;
        while ($my_query->have_posts()) {
            $my_query->the_post();
            setup_postdata($post);

            $summary = '';
            $image_folder = home_url() . '/wp-content/themes/smartbox-theme-custom/images/' ;
            switch ($post->post_type) {
                case 'oxy_video':
                    $summary = get_field('video_summary', $post->ID);
                    break;
                case 'oxy_audio':
                    $summary = get_field('audio_summary', $post->ID);
                    $IMAGE_URI = $image_folder . "audio_icon_recent.png";
                    break;
                case 'oxy_content':
                    $summary = get_field('summary', $post->ID);
                    $IMAGE_URI =  $image_folder . "text_icon_recent.png";
                    break;
                default :
                    break;
            }

                    if($item_num == 1)
                        $IMAGE_URI = $image_folder . 'video_icon_white_recent.png';
                    else if($item_num == 2)
                        $IMAGE_URI = $image_folder . 'video_icon_recent.png';
                    else if($item_num == 3)
                        $IMAGE_URI = $image_folder . 'text_transparent_icon_recent.png';
                    else if($item_num == 4)
                        $IMAGE_URI = $image_folder . 'video_icon_transparent_recent.png';
                    
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
            /*if (has_post_thumbnail($post->ID)) {
                $output .= get_the_post_thumbnail($post->ID, 'thumbnail', array('title' => $post->post_title, 'alt' => $post->post_title, 'class' => 'img-circle'));
                $output .= oxy_post_icon($post->ID, false);
            } else {              
             */
            $output .= '<img class="img-circle" src="' . $IMAGE_URI . '">';
            //$output .= '<img src="' . $ICON_URI . '">';
            
            $output.='</a></div>';
            $output.='</div><div class="span8"><h3><a href="' . $post_link . '">' . get_the_title() . '</a>';
            $output.='</h3></div></div></li>'; 
            /*if (empty($summary))
                $output.='</h3><p>' . oxy_limit_excerpt(get_the_excerpt(), 15) . '</p></div></div></li>';
            else
                $output.='</h3><p>' . oxy_limit_excerpt($summary, 15) . '</p></div></div></li>';             
             */
            $item_num++;
        }
        $output .= '</ul>';
    endif;
    // reset post data
    wp_reset_postdata();
    return oxy_shortcode_section($atts, $output);
}
add_shortcode('hb_recent_posts', 'hb_get_recent_posts');
function hb_get_recent_posts_new($atts) {
    $image_folder = home_url() . '/wp-content/themes/smartbox-theme-custom/images/' ;
            
    // setup options
    extract(shortcode_atts(array(
        'title' => 'Последние публикации',
        'cat' => null,
        'count' => 4,
        'style' => ''), $atts));

    $args = array(
        'post_type' => array('oxy_content', 'oxy_video', 'oxy_audio'),
        'showposts' => $count, // Number of related posts that will be shown.  
        'orderby' => 'date'
    );
    $my_query = new wp_query($args);
    $columns=2;
    $span =  'span6';
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
                 if($item_num == 1){
                        $IMAGE_URI = $image_folder . 'text_recent.jpg';
                 }else if($item_num == 2){
                        $IMAGE_URI = $image_folder . 'video_recent.jpg';
                 }
                $output .='<li class="' . $span . '">';
                $output .='<div class="round-box box-medium box-colored"><a href="' . $post_link . '" class="box-inner">';
               //get post icon
                if (has_post_thumbnail($post->ID)) {
                   $output .= get_the_post_thumbnail($post->ID, 'portfolio-thumb', array('title' => $post->post_title, 'alt' => $post->post_title, 'class' => 'img-circle'));
                   $output .= oxy_post_icon($post->ID, false);
                                              
                } else {
                    $output .= '<img class="img-circle" src="' . $IMAGE_URI.'">';
                   // $output .= '<img class="img-circle" src="' . IMAGES_URI . 'box-empty.gif">';
                    $output .= oxy_post_icon($post->ID, false);
                }

                $output .='</div>';
                $output.='<a href="' . $post_link . '"> <h3 class="text-center">' . get_the_title() . '</h3></a>';

                $content =  oxy_limit_excerpt(get_the_excerpt(), 33) ;
                $more_text=  get_more_text($post->post_type);
                $link = get_permalink();
                $content .= '<a href="' . $link . '" class="more-link">' . $more_text . '</a>';
                $output.='<p>' . apply_filters('the_content', $content) . '</p></li>';
                $item_num++;
            }
            $output .= '</ul>';
    endif;
    // reset post data
    wp_reset_postdata();
    return oxy_shortcode_section($atts, $output);
}
add_shortcode('hb_recent_posts_new', 'hb_get_recent_posts_new');
function hb_get_contact_form($atts,  $content = null) {
    // setup options
    extract(shortcode_atts(array(
        'title' => 'Contact us',
		'id' => ''), $atts));
    if($content==null){
        $content="<p>Если Вы желаете общаться с нами, узнать больше о нашей вере или же у Вас есть вопросы о нашей церкви, пишите нам.</p>

<p>Мы всегда рады общению с ищущими познать Правду на основании Писания.</p>";
    }
	$output ='<div class="span5">';
    $output.= '<div class="contact-details">' . do_shortcode( $content ) . '</div>';
    $output.='</div>'; 
    $output .='<div class="span7">';
    $output .= do_shortcode( '[contact-form-7 id="'.$id.'" title="ContactForm"]' );
    $output.='</div>';
          
    return oxy_shortcode_section($atts, $output);
}
add_shortcode('hb_contact_form', 'hb_get_contact_form');

function hb_add_element_into_wrapper($atts){
// setup options
    extract(shortcode_atts(array(
        'title' => '',
        'style' => '',
        'src_url' => ''
                    ), $atts));
	$output = create_videowrapper_div($src_url, "span12");
	return oxy_shortcode_section($atts, $output);
}
add_shortcode('hb_add_into_wrapper', 'hb_add_element_into_wrapper');

require_once get_template_directory() . '/inc/options/shortcodes/shortcodes.php';
require_once CUSTOM_INCLUDES_DIR . 'hb_utility.php';

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
				$content .= '<a href="' . $post_link . '" class="more-link">' . $more_text . '</a>';
             
				$output.='<p>' . apply_filters('the_content', $content) . '</p></div></li>';
			}
    	}
	$output .= '</ul>';
              
    return oxy_shortcode_section($atts, $output);
}	
add_shortcode('hb_blog_posts', 'hb_get_recent_blog_posts');

function hb_get_recent_oxy_content($atts) {
          
    // setup options
    extract(shortcode_atts(array(
        'title' => 'Новые проповеди',
        'cat' => null,
        'count' => 3,
        'style' => ''), $atts));

    $args = array(
        'post_type' => array('oxy_content'),
        'showposts' => $count, // Number of related posts that will be shown.  
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

                $content =  get_field('summary', $post->ID);//oxy_limit_excerpt(get_the_content(), 30) ;
                $more_text=  get_more_text($post->post_type);
                $link = get_permalink();
                $content .= '<a href="' . $link . '" class="more-link">' . $more_text . '</a>';
                $output.='<p>' . apply_filters('the_content', $content) . '</p></li>';
               
            }
            $output .= '</ul>';
    endif;
    // reset post data
    wp_reset_postdata();
    return oxy_shortcode_section($atts, $output);
}
add_shortcode('hb_recent_content', 'hb_get_recent_oxy_content');

function hb_get_recent_oxy_video($atts) {
    // setup options
    extract(shortcode_atts(array(
        'title' => 'Новые видео',
        'cat' => null,
        'count' => 3,
        'style' => ''), $atts));

    $args = array(
        'post_type' => array('oxy_video'),
        'showposts' => $count, // Number of related posts that will be shown.  
        'orderby' => 'date'
    );
    $my_query = new wp_query($args);
    $output = '';
	$IMAGE_URI = home_url() . '/wp-content/themes/smartbox-theme-custom/images/video1.jpg';
        if ($my_query->have_posts()) :
            $output .='<ul class="unstyled row-fluid">';
            global $post;
            //loop over all related posts
            while ($my_query->have_posts()) {
                $my_query->the_post();
                setup_postdata($post);
				$date = get_the_time(get_option("date_format"));
				
				$output .= '<li class="span4">';
				if ('link' == get_post_format()) {
                    $post_link = oxy_get_external_link();
                } else {
                    $post_link = get_permalink();
                }

				$output .= '<div class="row-fluid"><div class="span3">';
				$output .='<div class="round-box box-medium box-colored"><a href="' . $post_link . '" class="box-inner">';
                $output .= '<img class="img-circle" src="' . $IMAGE_URI.'">';
                $output .= oxy_post_icon($post->ID, false);
                $output .='</div>';
				//$output .= '<img class="img-circle" src="' . $IMAGE_URI.'">';
				//$output .= oxy_post_icon($post->ID, false);
                  
				$output .= '<h5 class="text-center light">'.$date.'</h5></div>';
				$output .= '<div class="span9">';
				$output.='<a href="' . $post_link . '"> <h3 class="text-center">' . get_the_title() . '</h3></a>';

                $content =  oxy_limit_excerpt(get_the_content(), 15) ;
                $more_text=  get_more_text($post->post_type);
                $link = get_permalink();
                $content .= '<a href="' . $link . '" class="more-link">' . $more_text . '</a>';
                $output.='<p>' . apply_filters('the_content', $content) . '</p></li>';
               
            }
            $output .= '</ul>';
    endif;
    // reset post data
    wp_reset_postdata();
    return oxy_shortcode_section($atts, $output);
}
add_shortcode('hb_recent_videos', 'hb_get_recent_oxy_video');
function get_latest_taxonomy_topics_as_list($atts) {
    $args = array(
        'hide_empty' => 1,
        'taxonomy' => 'teaching_topics',
        'pad_counts' => 1,
        'hierarchical' => 0,
	'number'       => '2',
    );
    $categories = get_categories($args);
    $count = count($categories);
    $span='span12';
    $output = '<div class="unstyled row-fluid">';

    //loop over all related posts
    foreach ($categories as $taxonomy) {
        $summary = get_taxonomy_term_summary_mini($taxonomy);
		$more_text = __('Go to topic', THEME_FRONT_TD);
        $slug = $taxonomy->slug;
        $link = get_term_link( $taxonomy );//home_url() . "/blog/teaching_topics/" . $slug;
        $taxonomy_image_link = get_taxonomy_image('teaching_topics', $taxonomy->slug);

        $more_text = '<a href="' . $link . '" class="more-link">' . $more_text . '</a>';
        $output .= '<div>
                    <div class="well blockquote-well">
                      <h3><a href="' . $link . '">' . $taxonomy->name . '</a></h3>
                        <blockquote class="margin_bottom_0px_mb"><p>' . $summary . $more_text . '</p></blockquote>';
        $output .='<a href="' . $link . '">' .get_image_as_round_box($taxonomy_image_link) .'</a>';
        $output.= '</div> </div>';
    }
    $output .= '</div>';
    extract(shortcode_atts(array(
        'style' => '',
        'title' => ''
                    ), $atts));
    return oxy_shortcode_section($atts, $output);
}
add_shortcode('latest_taxonomy_topics', 'get_latest_taxonomy_topics_as_list');