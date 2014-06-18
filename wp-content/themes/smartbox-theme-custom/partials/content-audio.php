<?php
/**
 * Shows a simple single post
 * @Author: andriy sobol
 * @Description: custom post template which called for all posts with format "Standart"
 * It has to be here in custom theme folder because it doesn't show author icon and author name for posts
 * it is called from theme pages using get_template_part( 'partials/content', get_post_format() );
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('row-fluid'); ?>>
    <div class="post-body">
        <div class="entry-content">
            <?php get_audio_content();?>
            <?php the_content(); ?>
        </div>
    </div>
</article>

