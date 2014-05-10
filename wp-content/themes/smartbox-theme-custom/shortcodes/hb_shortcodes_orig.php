<?php

/**
 * Custom shortcode functions go here
 * @author Andriy Sobol
 */

function oxy_content_taxonomy_topic_old($atts, $content = '') {
    // setup options
    extract(shortcode_atts(array(
        'title' => '',
        'topic' => '', 
        'style' => '',
        'title' => '', 
        'excerpt_length' => '', 
        'addrelatedposts' => ''
        ), $atts));

    //get taxonomy term description
    $taxonomy_name = 'teaching_topics';
    $termDiscription = get_taxonomy_description($taxonomy_name, $topic);
    if(empty($termDiscription))
        return 'Темы('. $topic . '), которую ты указал в shortcode не существует, используй существующую тему';
    
    //get taxonomy main video content
    $video_content = get_taxonomy_video($taxonomy_name, $topic);
    if(empty($video_content))
        return 'Ты не указал видео для это темы. Укажи видео в таксономии: ' . $taxonomy_name;
  
    //add video to taxonomy topic and related links
    $content .= '[row]';
    $content .= '[span1]';
    $content .= '[/span1]';
    $content .= '[span7]';
    if($add_super_hero == true){
        $content .= '<div class="super-hero-unit"><figure><img alt="some image" src="http://localhost/test/wp-content/uploads/sites/14/2013/03/landscape-5-1250x300.jpg" />';
        $content .= '<figcaption class="flex-caption">';
    }
    $content .= $video_content;
    if($add_super_hero == true){
        $content .= '</figcaption>';
        $content .= '</div>';}
    $content .= '[/span7]';
    $content .= '[span1]';
    $content .= '[/span1]';
    
    $content .= '[span3]';
    $content .= '[iconlist id="blockRigthBlack"]';
    $content .= '<h3>а также по теме...</h3>';
    
    // Get the ID of a given category
    $content .= '[iconitem_enh icon="icon-facetime-video" href=' . get_category_term_link_for_taxonomy_topic('video', $topic) . ']Видео[/iconitem_enh]';
    $content .= '[iconitem_enh icon="icon-book" href=' . get_category_term_link_for_taxonomy_topic('text', $topic) . ']Текстовые проповеди[/iconitem_enh]';
    $content .= '[iconitem_enh icon="icon-headphones" href=' . get_category_term_link_for_taxonomy_topic('music', $topic) . ']Аудиопроповеди[/iconitem_enh]';
    $content .= '[iconitem_enh icon="icon-music" href=' . get_category_term_link_for_taxonomy_topic('psalm', $topic) . ']Псалмы[/iconitem_enh]';
    $content .= '[/iconlist]';
    $content .= '[/span3]';
    $content .= '[/row]';
    
    //add description of taxonomy
    $content .= '[row]';
    $content .= '[span12]';
    if($add_super_hero == true){
        $content .= '<div class="super-hero-unit"><figure><img alt="some image" src="http://84.200.83.137/test/wp-content/uploads/sites/14/2013/03/landscape-5-1250x300.jpg" />';
        $content .= '<figcaption class="flex-caption">';}
    $content .= '<blockquote>';    
    $content .= $termDiscription;
    $content .= '</blockquote>';

    if($add_super_hero == true){
        $content .= '</figcaption>';
        $content .= '</div>';
    }    
    $content .= '[/span12]';
    $content .= '[/row]';

    $atts[title] = $title;
    if(empty($style)){
        $atts[style] = 'dark';
    }else{
        $atts[style] = $style;
    }
    
    //add related posts; query for all related posts except main video which is already shown
    if ($addrelatedposts == true) {
        $wp_query = new WP_Query;
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
                    'terms' => $topic, // should work without a slug, try it both ways...and use a variable, don't hardcode
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
                $content_more = '<a href="' . get_term_link($topic, $taxonomy = $taxonomy_name) . '">' . $content_more . '</a>';
                $excerpt_length = empty($excerpt_length) ? 50 : $excerpt_length;
                $text = wp_trim_words($post_content, $excerpt_length);
                $text = $text . $content_more;
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
    }
   $content .= '[row][span10][/span10]';
   $content .= '[span2][button icon="icon-share-alt" type="warning" size="btn-default" label="далее к теме" link="' . get_term_link($topic, $taxonomy = $taxonomy_name) . '" place="right"]';
   $content .= '[/span2][/row]';
   $output = oxy_shortcode_section($atts, $content);
    return $output;
}

add_shortcode('content_taxonomy_topic_old', 'oxy_content_taxonomy_topic_old');

function oxy_content_taxonomy_topic($atts, $content = '') {
    // setup options
    extract(shortcode_atts(array(
        'title' => '',
        'topic' => '', 
        'style' => '',
        'title' => '', 
        'excerpt_length' => '', 
        'addrelatedposts' => ''
        ), $atts));

    //get taxonomy term description
    $taxonomy_name = 'teaching_topics';
    $termDiscription = get_taxonomy_description($taxonomy_name, $topic);
    if(empty($termDiscription))
        return 'Темы('. $topic . '), которую ты указал в shortcode не существует, используй существующую тему';
    
    //get taxonomy image
    $taxonomy_image = get_taxonomy_image($taxonomy_name, $topic);
    
    //get taxonomy main video content
    $video_content = get_taxonomy_video($taxonomy_name, $topic);
    if(empty($video_content))
        $video_content = 'Ты не указал видео для это темы. Укажи видео в таксономии: ' . $taxonomy_name;
  
    //add taxonomy image
    $content .= '[row]';
    $content .= '[span12]';
    $content .= '<img src="'. $taxonomy_image . '" width="348" height="256" align="left" style="margin-right: 10px; margin-top: 5px; margin-bottom: 5px;">';
    $termDiscription = str_replace( "<p>", "<p style=\"text-align: justify\">", $termDiscription);
    $content .= $termDiscription;
    $content .= '[/span12]';
    $content .= '[/row]';
    $content .= '[row]';
    //add related video    
    $content .= '[span4]';
    $content .= $video_content;
    $content .= '[/span4]';
    
    //add main predigt
    $content .= '[span4]';
    $content .= get_taxonomy_content_item($taxonomy_name, $topic);
    $content .= '[/span4]';
    $content .= '[span4]';
    $content .= '[button icon="icon-book" type="warning" size="btn-large" label="Далее к теме" link="' . get_term_link($topic, $taxonomy = $taxonomy_name) . '" place="right"]';
    $content .= '[/span4][/row]';
    
    $atts[title] = $title;
    if(empty($style)){
        $atts[style] = 'dark';
    }else{
        $atts[style] = $style;
    }
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
        $counter = 1;
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
                $output .= '<h4 class="infokasten">';
                $output .= '<i class="' . $icon . '"></i>';
                if(!$addtitle) $output .= '</h4>';
            } 
            
            if($addtitle) {
                if (!$addicon) $output .= '<h4 class="infokasten">';
                $output .= '<a class="infokasten">';
                $output .= get_the_title() . " : ";
                $output .= '</a></h4>';
            }
            if ($contenttype == 'excerpt') {
                $text = get_the_excerpt();
                $excerpt_more = apply_filters('excerpt_more', ' ' . '...');
                $excerpt_more = '<i href="' . get_permalink() . '">' . '...' . '</i>';
                $excerpt_length = $excerpt_length == 0 ? 999 : $excerpt_length;
                $text = wp_trim_words($text, $excerpt_length, $excerpt_more);
                $output .= $text;
            } else if($contenttype == 'summary') {
                $output .= '<i>';
                //get value of summary
                $summary = get_field('summary', $post->ID);    
                $summary_more = apply_filters('summary_more', ' ' . '...');
                $summary_more = '<a href="' . get_permalink() . '">' . '...' . '</a>';
                $excerpt_length = empty($excerpt_length) ? 999 : $excerpt_length;
                $text = wp_trim_words($summary, $excerpt_length);
                $output .= $text . $summary_more;
                $output .= '</i>';
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

/* Content List */
function oxy_shortcode_content_list($atts, $content = '') {
    // setup options
    extract(shortcode_atts(array(
        'title' => '',
        'count' => 3,
        'columns' => 3,
        'style' => '',
        'category' => '',
        'orderby' => ''
                    ), $atts));

    $query_options = array(
        'post_type' => 'oxy_content',
        'numberposts' => $count,
        'orderby' => $orderby
    );

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
        $items_per_row = $columns;
        $member_num = 1;

        $output .= '<ul class="unstyled row-fluid">';

        foreach ($items as $member) :
            global $post;
            $post = $member;
            setup_postdata($post);
            $custom_fields = get_post_custom($post->ID);
            $icon = (isset($custom_fields[THEME_SHORT . '_icon'])) ? $custom_fields[THEME_SHORT . '_icon'][0] : '';
            $facebook = (isset($custom_fields[THEME_SHORT . '_facebook'])) ? $custom_fields[THEME_SHORT . '_facebook'][0] : '';
            $twitter = (isset($custom_fields[THEME_SHORT . '_twitter'])) ? $custom_fields[THEME_SHORT . '_twitter'][0] : '';
            $linkedin = (isset($custom_fields[THEME_SHORT . '_linkedin'])) ? $custom_fields[THEME_SHORT . '_linkedin'][0] : '';
            $pinterest = (isset($custom_fields[THEME_SHORT . '_pinterest'])) ? $custom_fields[THEME_SHORT . '_pinterest'][0] : '';
            $googleplus = (isset($custom_fields[THEME_SHORT . '_googleplus'])) ? $custom_fields[THEME_SHORT . '_googleplus'][0] : '';
            $img = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');

            if ($member_num > $items_per_row) {
                $output.='</ul><ul class="unstyled row-fluid">';
                $member_num = 1;
            }

            $output.='<li class="' . $span . '"><div class="round-box box-big"><span class="box-inner"><img alt="' . get_the_title() . '" class="img-circle" src="' . $img[0] . '">';
            $output.='</span></div><h3 class="text-center">' . get_the_title() . '<small class="block">' . $icon . '</small></h3>';
            $output.='<p class="no_li">' . get_the_content() . '</p>';
            $output.='<ul class="inline text-center big social-icons">';
            // must render
            $output.=($facebook !== '') ? '<li><a data-iconcolor="#3b5998" href="' . $facebook . '" style="color: rgb(66, 87, 106);"><i class="icon-facebook"></i></a></li>' : '';
            $output.=($twitter !== '') ? '<li><a data-iconcolor="#00a0d1" href="' . $twitter . '" style="color: rgb(66, 87, 106);"><i class="icon-twitter"></i></a></li>' : '';
            $output.=($pinterest !== '') ? '<li><a data-iconcolor="#910101" href="' . $pinterest . '" style="color: rgb(66, 87, 106);"><i class="icon-pinterest"></i></a></li>' : '';
            $output.=($googleplus !== '') ? '<li><a data-iconcolor="#E45135" href="' . $googleplus . '" style="color: rgb(66, 87, 106);"><i class="icon-google-plus"></i></a></li>' : '';
            $output.=($linkedin !== '') ? '<li><a data-iconcolor="#5FB0D5" href="' . $linkedin . '" style="color: rgb(66, 87, 106);"><i class="icon-linkedin"></i></a></li>' : '';

            $output.='</ul>';
            $output.='</li>';
            $member_num++;
        endforeach;
        $output .= '</ul>';
    endif;
    wp_reset_postdata();
    return oxy_shortcode_section($atts, $output);
}

add_shortcode('content_list', 'oxy_shortcode_content_list');

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
 **/
function oxy_shortcode_iconitem_enhanced( $atts, $content = null) {
    extract( shortcode_atts( array(
        'title'       => '',
        'icon'        => '',
        'href'        => '',  
    ), $atts ) );

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
add_shortcode( 'iconitem_enh', 'oxy_shortcode_iconitem_enhanced' );

function cmp($a, $b)
{ 
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
        if($date_formatted != null)
            $dates[$term_id] = get_field('taxonomy_publiched_date', 'teaching_topics_' . $term_id);
    }
    
    // Sort and print the resulting array
    arsort($dates);
    $output .= '<ul class="unstyled row-fluid">';
    $i = 0;
    while ($i < 3):
        $term_id = key($dates);
        $term = get_term( $term_id, $taxonomy_name );
        $term_name = $term->name;
        //don't add taxonomy name which is alreay on main page as main taxonomy topic
        if($term->slug == $except){
            next($dates);
            continue;            
        }
        $description = term_description( $term_id, $taxonomy_name );
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
add_shortcode('latest_taxonomy_topics', 'oxy_content_latest_topics');

function oxy_shortcode_stickit(){
    $output = '<ul_stickit> <li_stickit> <a_stickit href="www.bible-core.com"> <h2_stickit>ДЕРЕВО ЖИЗНИ И ДЕРЕВО ПОЗНАНИЯ ДОБРА И ЗЛА</h2_stickit> <p_stickit>И заповедал Господь Бог человеку, говоря… </p_stickit> </a_stickit> </li_stickit>';
    $output .= '<li_stickit><a_stickit href="www.bible-core.com"><h2_stickit>Я БУДУ ЛИКОВАТЬ! </h2_stickit><p_stickit>Слуашайте новый псалом ...</p_stickit></a_stickit></li_stickit>';
    $output .= '<li_stickit><a_stickit href="www.bible-core.com"><h2_stickit>МЛАДЕНЕЦ ВО ХРИСТЕ0 </h2_stickit><p_stickit>Потому отныне мы никого не знаем по плоти; если же…</p_stickit></a_stickit></li_stickit>';
    $output .= '<li_stickit><a_stickit href="www.bible-core.com"><h2_stickit>КАК РОДИТЬСЯ ОТ БОГА? </h2_stickit><p_stickit>Как же родиться от Бога, неужели снова войти в утробу…</p_stickit></a_stickit></li_stickit></ul_stickit>';
    return $output;
}

add_shortcode('stickit', 'oxy_shortcode_stickit');

/* ------------ BLOCKQUOTE SHORTCODE ------------*/

function oxy_shortcode_blockquote_drops( $atts, $content ) {
    extract( shortcode_atts( array(
        'who'   => '',
        'cite'  => '',
        'align' => '',
    ), $atts ) );
    
    $class = 'pullquote';
    if($align == "left"){
        $class = 'pullquote_left';
    }
    return '<blockquote_drops class="' . $class . '">' . $content . '<small>' . $who . '</small></blockquote_drops>';
}
add_shortcode( 'blockquote_drops', 'oxy_shortcode_blockquote_drops' );

/* Show content items of category */
function oxy_shortcode_content_items( $atts ) {
    extract( shortcode_atts( array(
        'category'    => '',
        'count'       => 3,
        'columns'     => 3,
        'links'       => 'show',
        'lead'        => 'hide',
        'title'       => '',
        'style'       => '',
        'title_size'  => 'medium',
        'image_style' => ''
    ), $atts ) );

    $query = array(
        'post_type'   => 'oxy_content',
        'numberposts' =>  $count,
        'orderby'     => 'menu_order',
        'order'       => 'ASC'
    );

    if( !empty( $category ) ) {
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

    $content_items = get_posts( $query );
    $output = '';
    if( count( $content_items > 0 ) ) {
        $output .= '<ul class="unstyled row-fluid">';
        if ($title_size == 'big')
            $header = 'h2';
        else if ( $title_size == 'medium')
            $header = 'h3';
        else
            $header = 'h4';
        $size = ($columns == 4)? 'round-medium': 'box-big';
        $text_class = ($lead == 'show')?' class="lead text-center"':'';
        $items_per_row = ($columns == 3)? 3:4;
        $span = ($columns== 4)?'span3':'span4';
        $service_num = 1;
        foreach( $content_items as $post ) {
            setup_postdata($post);
            global $more;
            $more = 0;
            if( $links == 'show' ){
                $link = oxy_get_slide_link( $post );
                if( null == $link ) {
                    $link = get_permalink();
                }
            }
            if( $service_num > $items_per_row){
                $output .='</ul><ul class="unstyled row-fluid">';
                $service_num = 1;
            }
            $icon = get_post_meta( $post->ID, THEME_SHORT. '_icon', true );
            $output .= '<li class="'.$span.'">';
            $output .= '<div class="round-box '.$size.' '.$image_style.'">';
            if( $links == 'show' ) {
                $output .= '<a href="' . $link . '" class="box-inner">';
            }
            else {
                $output .= '<span class="box-inner">';
            }
            $output .= get_the_post_thumbnail( $post->ID, 'portfolio-thumb', array( 'class' => 'img-circle', 'alt' => get_the_title() ) );
            if( $links == 'show' ) {
                $output .= '</a>';
            }
            else {
                $output .= '</span>';
            }
            if( $icon != '' ) {
                $output .= '<i class="' . $icon . '"></i>';
            }
            $output .= '</span>';
            $output .= '</div>';
            if( $links == 'show' ) {
                $output .= '<a href="' . $link . '">';
            }
            $output .= '<'.$header.' class="text-center">' . get_the_title() . '</'.$header.'>';
            if( $links == 'show' ) {
                $output .= '</a>';
            }
            //$output .= '<p'.$text_class.'>' .  apply_filters( 'the_content', get_the_content('') ) . '</p>';
            $shortcode_value = get_field('video_shortcode', $post->ID);
            $output .= '<p'.$text_class.'>' .  apply_filters( 'the_content', $shortcode_value ) . '</p>';
             if( $links == 'show' ) {
                $more_text = oxy_get_option('blog_readmore')? oxy_get_option('blog_readmore'): 'Read more';
                $output .= '<a href="'.$link.'" class="more-link">'. $more_text.'</a>';
            }
            $output .= '</li>';
            $service_num++;
        }
        $output .= '</ul>';
    }
    
    //Always check if it's an error before continuing. get_term_link() can be finicky sometimes
    $term = get_term_by('slug', $category, 'oxy_content_category');
    $term_link = get_term_link( $term, 'oxy_content_category' );
    if( is_wp_error( $term_link ) )
    {
    //We successfully got a link. Print it out.
    $output .= '<div id="" class="span2" style="height: 60px;border: orange 1px solid;margin-top: 40px;width: 170px;padding: 10px;margin-left:41px;">';
    $output .= '<span style="font-size: 15px; color: orange;"><i class="icon-signin icon-large"></i>';
    $output .= '<a href="' . $term_link . '"> &nbsp Далее к рубрике </a>';
    $output .= '</span><p></p></div>';
    $post = $tmp_post;
    }
    return oxy_shortcode_section( $atts, $output );
}
add_shortcode( 'content_items', 'oxy_shortcode_content_items' );

function oxy_shortcode_rtmp_player($atts) {
    extract(shortcode_atts(array(
        'ip' => '84.200.83.137',
        'stream' => 'myStream'
                    ), $atts));
    $output = '<div class = "span4">';
    $output .= '<div class = "content clearfix">';
    $output .= '<div class = "field field-name-body field-type-text-with-summary field-label-hidden"><div class = "field-items"><div class = "field-item even" property = "content:encoded"><script src = "http://jwpsrv.com/library/Qoe2IHBBEeONLyIACi0I_Q.js"></script>';
    $output .= '<div class = "content clearfix">';
    $output .= '<div class = "field field-name-body field-type-text-with-summary field-label-hidden">';
    $output .= '<div class = "field-items">';
    $output .= '<div class = "field-item even" property = "content:encoded">';
    $output .= '<div>';
    $output .= '<strong>Ссылка на конференцию</strong> (Zoom) -&nbsp;';
    $output .= '<a href = "https://zoom.us/j/247854777">https://zoom.us/j/247854777</a></div>';
    $output .= '<div>';
    $output .= '<strong>Начало:</strong> 12.00 Ландау (14.00 Московское время, 13.00 Киевское, 06.00 восточное сша)</div>';
    $output .= '<div>';
    $output .= '&nbsp;';
    $output .= '</div>';
    $output .= '</div>';
    $output .= '</div>';
    $output .= '</div>';
    $output .= '</div>';
    $output .= '<div id = "playerygRpQJGcOwEP">';
    $output .= '&nbsp;';
    $output .= '</div>';
    $output .= '<div class = "span8">';
    $output .= '<script type = \'text/javascript\'>';
    $output .= 'jwplayer(\'playerygRpQJGcOwEP\').setup({';
    $output .= 'playlist: [{';
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
    $output .= 'image: "/assets/myLivestream.jpg",';
    $output .= 'rtmp: {';
    $output .= 'subscribe: true';
    $output .= '},';
    $output .= 'width: 640';
    $output .= '});';
    $output .= '</script></div></div></div></div></div></div>';
    return $output;
}
add_shortcode( 'rtmp_player', 'oxy_shortcode_rtmp_player' );


require_once get_template_directory() . '/inc/options/shortcodes/shortcodes.php';
require_once CUSTOM_INCLUDES_DIR . 'hb_utility.php';