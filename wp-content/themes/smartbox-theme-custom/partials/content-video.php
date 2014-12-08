<?php
/**
 * Shows a simple single post
 * @Author: andriy sobol 
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('row-fluid'); ?>>
    <div class="<?php echo  'span12'; ?> post-body">
        <div class="entry-content">
		<?php echo hb_ui_video_content($post);?>
        </div>
    </div>
</article>


