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
?>
<?php oxy_create_hero_section( null, __('Tag', THEME_FRONT_TD) . ' ' . '<span class="lighter">' .  single_tag_title( '', false ) . '</span>'  ); ?>
<section class="section section-padded">
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span9">
                <?php while ( have_posts() ) : the_post(); ?>

                <?php get_template_part( 'partials/content', get_post_format() ); ?>

                <?php endwhile; ?>

                <?php oxy_pagination($wp_query->max_num_pages); ?>

            </div>
            <aside class="span3 sidebar">
                <?php get_sidebar(); ?>
            </aside>
        </div>
    </div>
</section>
<?php get_footer();