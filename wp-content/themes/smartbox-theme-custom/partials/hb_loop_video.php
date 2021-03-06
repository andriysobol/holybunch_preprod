<?php
/**
 * Main video loop
 */
?>
    <?php oxy_pagination($wp_query->max_num_pages); ?>
    <?php 
   if(is_archive()){
        $my_query = $wp_query;
    }else{
        $args = array(
                'post_type' => 'oxy_video',
                'post_status' => 'publish',
                'posts_per_page' => $my_query->max_num_pages, 
                'paged' => $paged 
        );
        $my_query = new wp_query($args);
    }?>
    <?php if( $my_query->have_posts() ): ?>
    <?php while ( $my_query->have_posts() ) : $my_query->the_post(); ?>
    <?php get_template_part(  'partials/archive-video-section'  ); ?>
    <?php endwhile; ?>

    <?php oxy_pagination($my_query->max_num_pages); ?>
    <?php else: ?>
        <article id="post-0" class="post no-results not-found">
            <header class="entry-header">
                <h1 class="entry-title"><?php _e( 'Nothing Found', THEME_FRONT_TD ); ?></h1>
            </header>
        </article>
    <?php endif; ?>
