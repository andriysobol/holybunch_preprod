<?php
/**
 * Main Blog loop
 *
 * @package Smartbox
 * @subpackage Frontend
 * @since 1.4
 *
 * @copyright (c) 2013 Oxygenna.com
 * @license **LICENSE**
 * @version 1.4
 */
?>
<?php oxy_pagination($wp_query->max_num_pages); ?>
    <?php 
    $taxonomy_term = $wp_query->queried_object;
    $slug = $taxonomy_term->slug;
    if(empty($slug))
	$slug = 'god';
    $wp_query->tax_query = array(
                array(
                    'taxonomy' => 'teaching_topics',
                    'field' => 'slug',
                    'terms' => $slug
                ));
    $my_query = $wp_query;?>
    <?php if( $my_query->have_posts() ): ?>
    <?php while ( $my_query->have_posts() ) : $my_query->the_post(); ?>
    <?php get_template_part(  'partials/video-single-archive'  ); ?>
    <?php endwhile; ?>

    <?php oxy_pagination($my_query->max_num_pages); ?>
    <?php else: ?>
        <article id="post-0" class="post no-results not-found">
            <header class="entry-header">
                <h1 class="entry-title"><?php _e( 'Nothing Found', THEME_FRONT_TD ); ?></h1>
            </header>
        </article>
    <?php endif; ?>
