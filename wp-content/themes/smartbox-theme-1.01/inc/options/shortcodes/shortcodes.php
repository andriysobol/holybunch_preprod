<?php

/**
 * Themes shortcode functions go here
 *
 * @package Smartbox
 * @subpackage Core
 * @since 1.0
 *
 * @copyright (c) 2013 Oxygenna.com
 * @license http://wiki.envato.com/support/legal-terms/licensing-terms/
 * @version 1.01
 */
/* ---------- TESTIMONIALS SHORTCODE ---------- */

function oxy_shortcode_testimonials($atts, $content = '') {
    // setup options
    extract(shortcode_atts(array(
        'title' => '',
        'count' => 3,
        'layout' => 'big',
        'columns' => 3,
        'style' => '',
                    ), $atts));

    $query_options = array(
        'post_type' => 'oxy_testimonial',
        'numberposts' => $count
    );

    // fetch posts
    $span = $columns == 3 ? 'span4' : 'span3';
    $items = get_posts($query_options);
    $items_count = count($items);
    $output = '';
    if ($items_count > 0):
        $item_num = 1;
        if ($layout == 'big') {
?>
            <?php

            foreach ($items as $item) :
                global $post;
                $post = $item;
                setup_postdata($post);
                $custom_fields = get_post_custom($post->ID);

                $img = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
                $class = ($item_num % 2 == 0) ? ' class="pull-right"' : '';
                $cite = (isset($custom_fields[THEME_SHORT . '_citation'])) ? $custom_fields[THEME_SHORT . '_citation'][0] : '';
                $hr = ($item_num !== $items_count) ? '<hr>' : '';
                $output .='<div class="row-fluid">';
                if ($item_num % 2 == 1) {
                    $output.='<div class="span3"><div class="round-box box-big"><span class="box-inner">';
                    $output.='<img alt="' . get_the_title() . '" class="img-circle" src="' . $img[0] . '" ></span></div></div>';
                }
                $output.='<div class="span9"><blockquote' . $class . '>' . '<p class="lead">' . get_the_content();
                $output.='</p><small>' . get_the_title() . '<cite title="Source Title"> ' . $cite . '</cite></small></blockquote></div>';
                if ($item_num % 2 == 0) {
                    $output.='<div class="span3"><div class="round-box box-big"><span class="box-inner">';
                    $output.='<img alt="' . get_the_title() . '" class="img-circle" src="' . $img[0] . '"></span></div></div>';
                }
                $output .='</div>' . $hr;
                $item_num++;
            endforeach;
        } else {  // Calculate how many items we will render before we need another row
            $items_per_row = ($span == 'span4') ? 3 : 4;
            $item_num = 1;
            $output .='<ul class="inline row-fluid">';
            foreach ($items as $item) :
                global $post;
                $post = $item;
                setup_postdata($post);
                $custom_fields = get_post_custom($post->ID);
                $cite = (isset($custom_fields[THEME_SHORT . '_citation'])) ? $custom_fields[THEME_SHORT . '_citation'][0] : '';
                $img = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');

                if ($item_num > $items_per_row) {
                    $output.= '</ul><ul class="inline row-fluid">';
                    $item_num = 1;
                }

                $output.='<li class="' . $span . '"><div class="well blockquote-well"><blockquote><p>' . get_the_content() . '</p>';
                $output.='<small>' . get_the_title() . '<cite title="Source Title"> ' . $cite . '</cite></small></blockquote>';
                $output.='<div class="round-box box-medium"><span class="box-inner"><img alt="' . get_the_title() . '" class="img-circle" src="' . $img[0] . '"></span></div></div></li>';
                $item_num++;

            endforeach;
            $output.='</ul>';
        }

        wp_reset_postdata();
    endif;

    return oxy_shortcode_section($atts, $output);
}

add_shortcode('testimonials', 'oxy_shortcode_testimonials');


/* ---- BOOTSTRAP BUTTON SHORTCODE ----- */

function oxy_shortcode_button($atts, $content = '') {
    // setup options
    extract(shortcode_atts(array(
        'type' => 'default',
        'size' => 'default',
        'xclass' => '',
        'link' => '#',
        'label' => 'button',
        'icon' => '',
        'place' => 'left',
                    ), $atts));
    if($place == 'left'){
        return '<a href="' . $link . '" class="btn btn-' . $type . ' ' . $size . ' ' . $xclass . '"><i class="' . $icon . '"></i> ' . $label . '</a>';
    }
    else {
        return '<a href="' . $link . '" class="btn btn-' . $type . ' ' . $size . ' ' . $xclass . '"> ' . $label . ' <i class="' . $icon . ' icon_right"></i></a>';
    }
}

add_shortcode('button', 'oxy_shortcode_button');


/* ---- BOOTSTRAP ALERT SHORTCODE ----- */

function oxy_shortcode_alert($atts, $content = '') {
    // setup options
    extract(shortcode_atts(array(
        'type' => 'default',
        'label' => 'warning!',
        'description' => 'something is wrong!',
                    ), $atts));

    return '<div class="alert ' . $type . '"><button type="button" class="close" data-dismiss="alert">&times;</button><h4>' . $label . '</h4>' . $description . '</div>';
}

add_shortcode('alert', 'oxy_shortcode_alert');

/* ----------------- BOOTSTRAP ACCORDION SHORTCODES --------------- */

function oxy_shortcode_accordions($atts, $content = '') {
    // setup options
    /* extract( shortcode_atts( array(
      'type'        => 'primary',
      'size'        => 'default',
      'xclass'      => '',
      'link'        => '#',
      'label'       => 'button'
      ), $atts ) ); */

    $id = 'accordion_' . rand(100, 999);
    $pattern = get_shortcode_regex();
    $count = preg_match_all('/' . $pattern . '/s', $content, $matches);
    //var_dump($matches);
    if (is_array($matches) && array_key_exists(2, $matches) && in_array('accordion', $matches[2])) {
        $lis = array();
        for ($i = 0; $i < $count; $i++) {
            $group_id = 'group_' . rand(100, 999);
            // is it a tab?
            if ('accordion' == $matches[2][$i]) {
                $accordion_atts = wp_parse_args($matches[3][$i]);
                $lis[] = '<div class="accordion-group"><div class="accordion-heading">';
                $lis[].='<a class="accordion-toggle collapsed" data-parent="#' . $id . '" data-toggle="collapse" href="#' . $group_id . '">' .
                        substr($accordion_atts['title'], 1, -1) . '</a></div>';
                $lis[].='<div class="accordion-body collapse" id="' . $group_id . '"><div class="accordion-inner">' . do_shortcode($matches[5][$i]) . '</div></div></div>';
            }
        }
    }

    return '<div class="accordion" id="' . $id . '">' . implode($lis) . '</div>';
}

add_shortcode('accordions', 'oxy_shortcode_accordions');

function oxy_shortcode_accordion($atts, $content = '') {

    return do_shortcode($content);
}

add_shortcode('accordion', 'oxy_shortcode_accordion');


/* ----------- BOOTSTRAP TABS AND TAB PANES SHORTCODES --------- */

function oxy_shortcode_tab($atts, $content = '') {

    $pattern = get_shortcode_regex();
    $count = preg_match_all('/' . $pattern . '/s', $content, $matches);
    if (is_array($matches) && array_key_exists(2, $matches) && in_array('tab', $matches[2])) {
        $lis = array();
        $divs = array();
        $extraclass = ' active';
        for ($i = 0; $i < $count; $i++) {
            $pane_id = 'group_' . rand(100, 999);
            // is it a tab?
            if ('tab' == $matches[2][$i]) {
                $tab_atts = wp_parse_args($matches[3][$i]);
                $lis[] = '<li class="' . $extraclass . '"><a data-toggle="tab" href="#' . $pane_id . '">' . substr($tab_atts['title'], 1, -1) . '</a></li>';
                $divs[] = '<div class="tab-pane' . $extraclass . '" id="' . $pane_id . '">' . do_shortcode($matches[5][$i]) . '</div>';
                $extraclass = '';
            }
        }
    }

    return '<ul class="nav nav-tabs" data-tabs="tabs">' . implode($lis) . '</ul><div class="tab-content">' . implode($divs) . '</div>';
}

add_shortcode('tabs', 'oxy_shortcode_tab');

function oxy_shortcode_tab_pane($atts, $content = '') {

    return do_shortcode($content);
}

add_shortcode('tab', 'oxy_shortcode_tab_pane');


/* ------------------ PROGRESS BAR SHORTCODE -------------------- */

function oxy_shortcode_progress_bar($atts, $content = '') {
    // setup options
    extract(shortcode_atts(array(
        'percentage' => 50,
        'type' => 'progress',
        'style' => 'progress-info',
                    ), $atts));

    return '<div class="' . $type . ' ' . $style . '"><div class="bar" style="width: ' . $percentage . '%"></div></div>';
}

add_shortcode('progress', 'oxy_shortcode_progress_bar');



/* --------------------- PRICING SHORTCODE ---------------------- */

function oxy_shortcode_pricing($atts, $content = '') {
    extract(shortcode_atts(array(
        'heading' => 'standard',
        'price' => 10,
        'per' => 'month',
        'featured' => 'no',
                    ), $atts));
    $featured_class = ($featured == 'yes') ? '<span class="tag"><i class="icon-star"></i></span>' : '';
    $output = '<div class="span4"><div class="well well-package"><h3 class="well-package-heading">';
    $output.= $heading . $featured_class . '</h3>';
    $output.= '<div class="well-package-price"><small>$</small>' . $price . '<small>/' . $per . '</small></div>';

    return $output . do_shortcode($content) . '</div></div>';
}

add_shortcode('pricing', 'oxy_shortcode_pricing');


/* ------------------------ IMAGE SHORTCODE ------------------------ */

function oxy_shortcode_image($atts, $content = '') {
    // setup options
    extract(shortcode_atts(array(
        'size' => 'box-medium',
        'rounded' => 'yes',
        'polaroid' => 'no',
        'source' => '',
        'icon' => '',
        'link' => ''
                    ), $atts));

    $iconclass = ($icon != '') ? '<i class="' . $icon . '"></i>' : '';
    $polaroidclss = ( $polaroid == 'yes') ? 'img-polaroid' : '';
    $extraclass = ($rounded == 'no') ? ' no-rounded' : '';
    $tag = ($link != '') ? 'a' : 'span';
    $ref = ($tag == 'a') ? ' href="' . $link . '"' : '';

    $output = '<div class="round-box' . $extraclass . ' ' . $size . '"> <' . $tag . ' class="box-inner"' . $ref . '>';
    $output.= '<img class="img-circle ' . $polaroidclss . '"  src="' . $source . '">' . $iconclass . '</' . $tag . '></div>';

    return $output;
}

add_shortcode('image', 'oxy_shortcode_image');



/* --------------------- PORTFOLIO SHORTCODES --------------------- */

function oxy_shortcode_portfolio($atts, $content = '') {
    // setup options
    extract(shortcode_atts(array(
        'title' => '',
        'count' => 3,
        'columns' => 3,
        'cat' => '',
        'style' => '',
        'img_style' => '',
                    ), $atts));

    $query_options = array(
        'post_type' => 'oxy_portfolio_image',
        'numberposts' => $count,
        'orderby' => 'menu_order',
        'order' => 'ASC'
    );
    $span = $columns == 3 ? 'span4' : 'span3';
    $box_size = $columns == 3 ? 'box-huge' : 'box-big';
    // fetch posts
    $projects = get_posts($query_options);
    $projects_count = count($projects);
    $output = '';
    if ($projects_count > 0) {
        $projects_per_row = ($span == 'span4') ? 3 : 4;
        $project_num = 1;
        $filters = get_terms('oxy_portfolio_categories', array('hide_empty' => 1));

        $output.='<div class="portfolio-filters"><h5 class="text-center"><a class="active" data-filter="all" href="#">all</a>';
        foreach ($filters as $filter) {
            $output.=' / <a href="#" data-filter="' . $filter->slug . '">' . $filter->name . '</a>';
        }
        $output.='</h5></div><div class="row-fluid"><ul class="thumbnails portfolio">';

        foreach ($projects as $project) {
            global $post;
            $post = $project;
            setup_postdata($post);

            $item_link = '<a class="box-inner" href="' . get_permalink() . '">';
            $extra_gallery_images = array();

            $format = get_post_format($post->ID);
            if (false === $format) {
                $format = 'standard';
            }
            $use_fancybox = get_post_meta($post->ID, THEME_SHORT . '_open_fancybox', true);
            if ($use_fancybox) {
                switch ($format) {
                    case 'standard':
                        $image_link = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
                        $item_link = '<a class="box-inner fancybox" href="' . $image_link[0] . '">';
                        break;
                    case 'gallery':
                        $gallery_ids = oxy_get_content_gallery($post);
                        if ($gallery_ids !== null) {
                            if (count($gallery_ids) > 0) {
                                // ok lets create a gallery
                                $gallery_rel = 'rel="gallery' . $post->ID . '"';
                                $gallery_image = wp_get_attachment_image_src($gallery_ids[0], 'full');
                                $item_link = '<a class="box-inner fancybox" href="' . $gallery_image[0] . '" ' . $gallery_rel . '>';

                                // remove first gallery image from array
                                array_shift($gallery_ids);
                                foreach ($gallery_ids as $gallery_image_id) {
                                    $gallery_image = wp_get_attachment_image_src($gallery_image_id, 'full');
                                    $extra_gallery_images[] = '<a class="fancybox" href="' . $gallery_image[0] . '" ' . $gallery_rel . '></a>';
                                }
                            }
                        }
                        break;
                    case 'video':
                        $video_shortcode = oxy_get_content_shortcode($post, 'embed');
                        if ($video_shortcode !== null) {
                            if (isset($video_shortcode[5])) {
                                $video_shortcode = $video_shortcode[5];
                                if (isset($video_shortcode[0])) {
                                    $item_link = '<a href="' . $video_shortcode[0] . '" class="box-inner fancybox-media">';
                                }
                            }
                        }
                        break;
                }
            }

            $filter_tags = get_the_terms($post->ID, 'oxy_portfolio_categories');
            $author_id = get_the_author_meta('ID');
            $post_filters = array();
            if ($filter_tags && !is_wp_error($filter_tags)) {
                foreach ($filter_tags as $tag) {
                    $post_filters[] = 'filter-' . $tag->slug;
                }
            }
            $output .= '<li class=" ' . $span . ' ' . implode(',', $post_filters) . '" >';
            $output .= '<figure class="round-box ' . $box_size . ' ' . $img_style . '">';
            $output .= $item_link;
            $output .= get_the_post_thumbnail($post->ID, 'portfolio-thumb', array('class' => 'img-circle', 'alt' => get_the_title())) . '<i class="plus-icon"></i>';
            $output .= '</a>';
            $output .= '<figcaption><h4><a href="' . get_permalink() . '">' . get_the_title() . '</a></h4>';
            $output .= '<p>' . oxy_limit_excerpt(get_the_excerpt(), oxy_get_option('portfolio_excerpt_words')) . '</p></figcaption></figure>';
            foreach ($extra_gallery_images as $extra_image) {
                $output .= $extra_image;
            }
            $output .= '</li>';
        }
        $output .= '</ul></div>';
    }
    wp_reset_postdata();
    return oxy_shortcode_section($atts, $output);
}

add_shortcode('portfolio', 'oxy_shortcode_portfolio');


/* ------------------ LAYOUT SHORTCODES ------------------- */

/* ------------------ COLUMNS SHORTCODES ------------------- */

function oxy_shortcode_row($atts, $content = null, $code) {
    return '<div class="row-fluid">' . do_shortcode($content) . '</div>';
}

add_shortcode('row', 'oxy_shortcode_row');

function oxy_shortcode_layout($atts, $content = null, $code) {
    return '<div class="' . $code . '">' . do_shortcode($content) . '</div>';
}

add_shortcode('span1', 'oxy_shortcode_layout');
add_shortcode('span2', 'oxy_shortcode_layout');
add_shortcode('span3', 'oxy_shortcode_layout');
add_shortcode('span4', 'oxy_shortcode_layout');
add_shortcode('span5', 'oxy_shortcode_layout');
add_shortcode('span6', 'oxy_shortcode_layout');
add_shortcode('span7', 'oxy_shortcode_layout');
add_shortcode('span8', 'oxy_shortcode_layout');
add_shortcode('span9', 'oxy_shortcode_layout');
add_shortcode('span10', 'oxy_shortcode_layout');
add_shortcode('span11', 'oxy_shortcode_layout');
add_shortcode('span12', 'oxy_shortcode_layout');


/* ---------- LEAD SHORTCODE ---------- */

function oxy_shortcode_lead($atts, $content) {
    extract(shortcode_atts(array(
        'centered' => 'yes'
                    ), $atts));
    $extraclass = ( $centered == 'yes') ? ' text-center' : '';
    return '<p class="lead' . $extraclass . '">' . do_shortcode($content) . '</p>';
}

add_shortcode('lead', 'oxy_shortcode_lead');

function oxy_shortcode_donothing() {
    return '';
}

add_shortcode('gallery', 'oxy_shortcode_donothing');
add_shortcode('link', 'oxy_shortcode_donothing');


/* ------------ BLOCKQUOTE SHORTCODE ------------ */

/**
 * @return type
 * @version 1.1 add id and class to Blockquote + ifelse-Anweisung
 */
function oxy_shortcode_blockquote($atts, $content) {
    extract(shortcode_atts(array(
        'who' => '',
        'cite' => '',
        'class' => '',
                    ), $atts));
        return '<blockquote id="' . $id . '" class="' . $class . '">"' . do_shortcode($content) . '"<small>' . $who . ' <cite title="source title">' . $cite . '</cite></small></blockquote>';

}

add_shortcode('blockquote', 'oxy_shortcode_blockquote');


/* * **********************************      SECTIONS       ******************************* */

/* Basic Section */

function oxy_shortcode_section($atts, $content = '') {
    extract(shortcode_atts(array(
        'style' => '',
        'title' => '',
                    ), $atts));

    switch ($style) {
        case 'gray':
            $style = 'section-alt';
            break;
        case 'dark':
            $style = 'section-alt section-dark';
            break;
    }

    $section_title = ( $title != '' ) ? '<div class="section-header"><h1>' . oxy_filter_title($title) . '</h1></div>' : '';
    return '<section class="section section-padded ' . $style . '"><div class="container-fluid">' . $section_title . '<div class="row-fluid">' . do_shortcode($content) . '</div></div></section>';
}

add_shortcode('section', 'oxy_shortcode_section');

/* Services Section */

function oxy_shortcode_services($atts) {
    extract(shortcode_atts(array(
        'count' => 3,
        'columns' => 3,
        'links' => 'hide',
        'lead' => 'hide',
        'title' => '',
        'style' => '',
                    ), $atts));

    $query = array(
        'post_type' => 'oxy_service',
        'numberposts' => $count,
        'orderby' => 'menu_order',
        'order' => 'ASC'
    );

    global $post;
    $tmp_post = $post;

    $services = get_posts($query);
    $output = '';
    if (count($services > 0)) {
        $output .= '<ul class="unstyled row-fluid">';
        $header = ($columns == 4) ? 'h3' : 'h2';
        $size = ($columns == 4) ? 'round-medium' : 'box-big';
        $text_class = ($lead == 'show') ? ' class="lead text-center"' : '';
        $services_per_row = ($columns == 3) ? 3 : 4;
        $span = ($columns == 4) ? 'span3' : 'span4';
        $service_num = 1;
        foreach ($services as $post) {
            setup_postdata($post);
            if ($service_num > $services_per_row) {
                $output .='</ul><ul class="unstyled row-fluid">';
                $service_num = 1;
            }
            $icon = get_post_meta($post->ID, THEME_SHORT . '_icon', true);
            $output .= '<li class="' . $span . '">';
            $output .= '<div class="round-box ' . $size . '">';
            $output .= '<span class="box-inner">';
            $output .= get_the_post_thumbnail($post->ID, 'portfolio-thumb', array('class' => 'img-circle', 'alt' => get_the_title()));
            if ($icon != '') {
                $output .= '<i class="' . $icon . '"></i>';
            }
            $output .= '</span>';
            $output .= '</div>';
            $output .= '<' . $header . ' class="text-center">' . get_the_title() . '</' . $header . '>';
            $output .= '<p' . $text_class . '>' . get_the_content() . '</p>';
            // $output .= ( $links == 'show' )?'<a class="more-link" href="'.get_permalink().'"><strong>Read</strong>More</a>':'';
            $output .= '</li>';
            $service_num++;
        }
        $output .= '</ul>';
    }

    $post = $tmp_post;

    return oxy_shortcode_section($atts, $output);
}

add_shortcode('services', 'oxy_shortcode_services');

/* Recent Posts */

function oxy_get_recent_posts($count, $category = null, $authors = null, $post_formats = null) {
    $query = array();
    // set post count
    $query['numberposts'] = $count;
    // set category if selected
    if (!empty($category)) {
        $query['category_name'] = $category;
    }
    // set author if selected
    if (!empty($authors)) {
        $query['author'] = implode(',', $authors);
    }
    // set post format if selected
    if (!empty($post_formats)) {
        foreach ($post_formats as $key => $value) {
            $post_formats[$key] = 'post-format-' . $value;
        }
        $query['tax_query'] = array();
        $query['tax_query'][] = array(
            'taxonomy' => 'post_format',
            'field' => 'slug',
            'terms' => $post_formats
        );
    }
    // fetch posts
    return get_posts($query);
}

function oxy_shortcode_recent_posts($atts, $content = '') {
    // setup options
    extract(shortcode_atts(array(
        'title' => '',
        'cat' => null,
        'count' => 3,
        'style' => '',
        'columns' => 3
                    ), $atts));

    $span = $columns == 3 ? 'span4' : 'span3';

    $posts = oxy_get_recent_posts($count, $cat);
    $output = '';
    if (!empty($posts)) :
        $output .='<ul class="unstyled row-fluid">';
        global $post;
        $item_num = 1;
        $items_per_row = $columns;
        foreach ($posts as $post) :
            setup_postdata($post);
            if ($item_num > $items_per_row) {
                $output.= '</ul><ul class="unstyled row-fluid">';
                $item_num = 1;
            }

            $output .='<li class="' . $span . '"><div class="row-fluid"><div class="span4">';
            $output .='<div class="round-box box-small box-colored"><a href="' . get_permalink() . '" class="box-inner">';
            if (has_post_thumbnail($post->ID)) {
                $output .=get_the_post_thumbnail($post->ID, array(128, 128), array('title' => $post->post_title, 'alt' => $post->post_title, 'class' => 'img-circle'));
                $output .= oxy_post_icon($post->ID, false);
            } else {
                $output .= '<img class="img-circle" src="' . IMAGES_URI . 'box-empty.gif">';
                $output .= oxy_post_icon($post->ID, false);
            }
            $output.='</a></div><h5 class="text-center light">' . get_the_date() . '</h5>';
            $output.='</div><div class="span8"><h3><a href="' . get_permalink() . '">' . get_the_title() . '</a>';
            $output.='</h3><p>' . oxy_limit_excerpt(get_the_excerpt(), 15) . '</p></div></div></li>';
            $item_num++;
        endforeach;
        $output .= '</ul>';
    endif;
    // reset post data
    wp_reset_postdata();
    return oxy_shortcode_section($atts, $output);
}

add_shortcode('recent_posts', 'oxy_shortcode_recent_posts');

/* Content Featured */

function oxy_shortcode_content_featured($atts, $content = '') {
    // setup options
    extract(shortcode_atts(array(
        'title' => '',
        'member' => '',
        'style' => ''
                    ), $atts));

    $output = '';
    if ($member !== ''):
        $item = get_post($member);
        $custom_fields = get_post_custom($item->ID);
        $img = wp_get_attachment_image_src(get_post_thumbnail_id($item->ID), 'full');
        $icon = (isset($custom_fields[THEME_SHORT . '_icon'])) ? $custom_fields[THEME_SHORT . '_icon'][0] : '';
        $skills = wp_get_post_terms($item->ID, 'oxy_content_skills');
        $output.='<div class="row-fluid"><div class="span6"><img alt="' . $item->post_title . '" class="push-bottom" src="' . $img[0] . '"></div>';
        $output.='<div class="span6"><p class="lead">' . $item->post_content . '</p>';
        if (!empty($skills)) {
            $output.='<ul class="lead icons icons-small">';
            foreach ($skills as $skill) {
                $output.='<li><i class="icon-ok"></i>' . $skill->name . '</li>';
            }
            $output.='</ul>';
        }
        $output.='</div></div>';
        wp_reset_postdata();
    endif;
    return oxy_shortcode_section($atts, $output);
}

add_shortcode('content_featured', 'oxy_shortcode_content_featured');

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
    $content .= '[row][span10][/span10][span2][button icon="icon-share-alt" type="warning" size="btn-default" label="далее к теме" link="/oxy_content_taxonomy?topic='.$topic.'" place="right"]';
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
                $custom_fields = get_post_custom($post->ID);
                $summary = (isset($custom_fields[THEME_SHORT . '_summary'])) ? $custom_fields[THEME_SHORT . '_summary'][0] : '';
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

/* * ****************************************      COMPONENTS        ************************************ */

/* Slideshow Shortcode */

function oxy_shortcode_flexslider($atts, $content = null) {
    $params = shortcode_atts(array(
        'slideshow' => '',
        'animation' => 'slide',
        'speed' => 7000,
        'duration' => 600,
        'directionnav' => 'hide',
        'directionnavpos' => 'outside',
        'controlsposition' => 'inside',
        'itemwidth' => '',
        'showcontrols' => 'show',
        'captions' => 'show',
        'captionsize' => 'super',
        'captionanimation' => 'animated'
            ), $atts);
    return oxy_create_flexslider($params['slideshow'], $params, false);
}

add_shortcode('flexslider', 'oxy_shortcode_flexslider');

/**
 * Icon List Shortcode
 *
 * @return Icon List
 * @version 1.1 add id and class to iconlist
 * */
function oxy_shortcode_iconlist($atts, $content = null) {
    extract(shortcode_atts(array(
        'id' => '',
        'class' => '',
                    ), $atts));
    $output = '<ul class="icons ' . $class . '" id="' . $id . '">';
    $output .= do_shortcode($content);
    $output .= '</ul>';
    return $output;
}

add_shortcode('iconlist', 'oxy_shortcode_iconlist');

/**
 * Icon Item Shortcode - for use inside an iconlist shortcode
 *
 * @return Icon Item HTML
 * */
function oxy_shortcode_iconitem($atts, $content = null) {
    extract(shortcode_atts(array(
        'title' => '',
        'icon' => '',
                    ), $atts));
    if ($title != 'null') {
        $output = '<li>';
        $output .= '<h4>';
        $output .= '<i class="' . $icon . '"></i>';
        $output .= $title;
        $output .= '</h4>';
        $output .= '<p>';
        $output .= $content;
        $output .= '</p>';
        $output .= '</li>';
    } else {
        $output = '<li>';
        $output .= '<i class="' . $icon . '"></i>';
        $output .= $content;
        $output .= '</li>';
    }
    return $output;
}

add_shortcode('iconitem', 'oxy_shortcode_iconitem');

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
 * Code shortcode - for showing code!
 *
 * @return Code html
 * */
function oxy_shortcode_code($atts, $content = null) {
    return '<pre>' . htmlentities($content) . '</pre>';
}

add_shortcode('code', 'oxy_shortcode_code');