<?php
/**
 * Displays a tag archive
 * @package Smartbox
 * @subpackage Frontend
 * @since 0.1
 *
 * @copyright (c) 2013 Oxygenna.com
 * @license http://wiki.envato.com/support/legal-terms/licensing-terms/
 * @version 1.01
 */
get_header();
//get current category slug
$category = get_query_var("oxy_content_category");
//get term by slug in order to get name of Category
$category_term = get_term_by('slug', $category, 'oxy_content_category');
oxy_create_hero_section( null, $category_term->name);

?>
<section class="section section-padded">
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span12">
                <?php while ( have_posts() ) : the_post(); ?>

                <?php get_template_part( 'partials/content', get_post_format() ); ?>

                <?php endwhile; ?>

                <?php oxy_pagination($wp_query->max_num_pages); ?>

            </div>
        </div>
    </div>
</section>
<?php get_footer();