<?php
/**
 * Displays archive for videos. This page is used for requests:
 * 1 Search reguest of videos(e.g. url =".../?s=searched_keaword&post_type=oxy_video"
 * 2 Taxonomy request of videos (e.g. url = ".../blog/teaching_topics/golgota/?post_type=oxy_video")
 * 3 Archive request of videos (e.g. url = ".../blog/2014/10/?post_type=oxy_video")
 * 4 Display all videos (e.g url = "...?post_type=oxy_video")
 * @package Smartbox
 * @subpackage Frontend
 * @since 0.1
 *
 * @copyright (c) 2013 Oxygenna.com
 * @license http://wiki.envato.com/support/legal-terms/licensing-terms/
 * @version 1.5
 */
if (is_day()) {
    //$title = __('Day', THEME_FRONT_TD);
    $title = get_the_date('j M Y');
} elseif (is_month()) {
    //$title = __('Month', THEME_FRONT_TD);
    $title = get_the_date('F Y');
} elseif (is_year()) {
    //$title = __('Year', THEME_FRONT_TD);
    $title = get_the_date('Y');
} elseif (is_search()) {
    $title = __('Search', THEME_FRONT_TD) . ': ' . '<span class="lighter">' . get_search_query() . '</span>'; 
}else {
    $term = $wp_query->queried_object;
    $title = $term->name;
    if ($term->slug == "god") 
        $title = "";
}

if(!empty($term->slug))
    $image = hb_get_taxonomy_image('teaching_topics', $term->slug, hb_enum_taxonomy_image_type::banner_image);
else 
    $image = hb_get_taxonomy_image('teaching_topics', 'god', hb_enum_taxonomy_image_type::banner_image);

if(is_page()){
    get_header();
    oxy_page_header();
}else{
    get_header();
    oxy_create_hero_section($image, $title);
}

?>
<section class="section section-padded">
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span9">
                <?php get_template_part('partials/hb_loop_video'); ?>
            </div>
            <aside class="span3 sidebar">
                <?php dynamic_sidebar('sidebar-videos'); ?>
            </aside>
        </div>        
    </div>
</section>
<?php get_footer();


            