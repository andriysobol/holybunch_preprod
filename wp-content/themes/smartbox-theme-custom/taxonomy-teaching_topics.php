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
$title = "Тема: " . $term->name;
oxy_create_hero_section(null, $title); ?>
<section class="section section-padded">
    <div class="container-fluid">
        <div class="row-fluid">
            <?php get_taxonomy_terms_cloud('') ?>
        </div>
    </div>
</section>
<section class="section section-padded">
    <div class="container-fluid">
        <div class="row-fluid">
<?php get_template_part('partials/hb_loop_all'); ?>
        </div>
    </div>
</section>
<?php get_footer();

            