<?php
/**
 * Main Blog loop
 */
?>

<?php if( oxy_get_option('blog_layout') == 'sidebar-left' ): ?>
<aside class="span3 sidebar">
    <?php get_sidebar(); ?>
</aside>
<?php endif; ?>

<div class="<?php echo oxy_get_option('blog_layout') == 'full-width' ? 'span12':'span9' ; ?>">
    <?php 
       if (is_archive()) {
            $my_query = $wp_query;
        } else {
            $args = array(
                'post_type' => 'post',
                'post_status' => 'publish',
                'posts_per_page' => $my_query->max_num_pages,
                'paged' => $paged
            );
        $my_query = new wp_query($args);
        }?>
    <?php if( $my_query->have_posts() ): ?>
    <?php while ( $my_query->have_posts() ) : $my_query->the_post(); ?>

    <?php get_template_part(  'partials/archive-blog-section'  ); ?>

    <?php endwhile; ?>

    <?php oxy_pagination($my_query->max_num_pages); ?>
    <?php else: ?>
        <article id="post-0" class="post no-results not-found">
            <header class="entry-header">
                <h1 class="entry-title"><?php _e( 'Nothing Found', THEME_FRONT_TD ); ?></h1>
            </header>

            <div class="entry-content">
            <?php   if( is_category() ) {
                        $message = 'Sorry, no posts were found for this category.';
                    }
                    else if( is_date()  ){
                        $message = 'Sorry, no posts found in that timeframe';
                    }
                    else if ( is_author() ){
                        $message = 'Sorry, no posts from that author were found';
                    }
                    else if ( is_tag() ){
                        $message = 'Sorry, no posts were tagged with "'. single_tag_title( '', false ).'"' ;
                    }
                    else{
                        $message = 'Sorry, nothing found';
                    }
            ?>
                <p><?php _e( $message, THEME_FRONT_TD ); ?></p>
                <?php get_search_form(); ?>
            </div>
        </article>
    <?php endif; ?>
</div>

<?php if( oxy_get_option('blog_layout') == 'sidebar-right' ): ?>
<aside class="span3 sidebar">
    <?php get_sidebar(); ?>
</aside>
<?php endif;