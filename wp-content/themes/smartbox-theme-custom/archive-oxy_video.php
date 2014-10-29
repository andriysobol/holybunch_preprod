<?php if(get_page_by_title('Videos')) : ?>
<?php include('page-videos.php'); ?>
<?php else:?>
<?php
/**
 * Displays a tag archive
 * @package Smartbox
 * @subpackage Frontend
 * @since 0.1
 *
 * @copyright (c) 2013 Oxygenna.com
 * @license http://wiki.envato.com/support/legal-terms/licensing-terms/
 * @version 1.5
 */
get_header();
if (is_day()) {
    $title = __('Day', THEME_FRONT_TD);
    $sub = get_the_date('j M Y');
} elseif (is_month()) {
    $title = __('Month', THEME_FRONT_TD);
    $sub = get_the_date('F Y');
} elseif (is_year()) {
    $title = __('Year', THEME_FRONT_TD);
    $sub = get_the_date('Y');
} else {
    $title = __('Blog', THEME_FRONT_TD);
    $sub = 'Archives';
}
?>
<?php 
$term =	$wp_query->queried_object;
$title = $term->name;
if ($term->slug == "god") 
    $title = "";
oxy_create_hero_section(get_taxonomy_banner_image('teaching_topics', $term->slug), $title); ?>


<section class="section section-padded">
    <div class="container-fluid">
        <div class="row-fluid">
                <div class="span9">
                    
            <?php get_template_part('partials/hb_loop_video'); ?>
        </div>
            <aside class="span3 text-left">
            <?php dynamic_sidebar('sidebar-videos'); ?>
        </aside>
    </div>
        
    </div>
</section>
<?php get_footer();
endif;?>

            