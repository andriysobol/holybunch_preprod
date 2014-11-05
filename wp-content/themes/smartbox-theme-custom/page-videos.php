<?php
/**
 * Displays a single post
 *
 * @package Smartbox
 * @subpackage Frontend
 * @since 0.1
 *
 * @copyright (c) 2013 Oxygenna.com
 * @license http://wiki.envato.com/support/legal-terms/licensing-terms/
 * @version 1.4
 */
get_header();
oxy_page_header();
$allow_comments = oxy_get_option( 'site_comments' );
?>
<?php
$term = $wp_query->queried_object;
$title = $term->name;
if ($term->slug == "god") 
    $title = "";
oxy_create_hero_section(get_taxonomy_banner_image('teaching_topics', $term->slug), $title);
?>
<section class="section section-padded">
    <div class="container-fluid">
        <div class="row-fluid">
            <?php get_template_part( 'partials/hb_loop_video' ); ?>
        </div>
    </div>
</section>
<?php get_footer();