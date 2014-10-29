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

<div class="<?php echo oxy_get_option('blog_layout') == 'full-width' ? 'span12':'span9' ; ?>">
    <?php $args = array(
                'post_type' => 'oxy_video',
                'post_status' => 'publish'
            );
    $my_query = new wp_query($args);?>
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
</div>

<aside class="span3 sidebar">
    <?php dynamic_sidebar('sidebar-videos'); ?>
</aside>