<?php
/**
 * Shows a simple single post of type oxy_text used for example in single-oxy_content
 * @Author: andriy sobol
 * @Description: custom post template which called for all posts with format "Standart"
 * It has to be here in custom theme folder because it doesn't show author icon and author name for posts
 * it is called from theme pages using get_template_part( 'partials/content', get_post_format() );
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('row-fluid'); ?>>
    <div class="<?php echo 'span12'; ?> post-body">
        <div class="entry-content content-text print-only">
            <?php
            $current_url = $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
            //if user opens print view, means url contains "?pfstyle=wp", then content should not shown in pages but completely
            if (strpos($current_url, '?pfstyle=wp') !== false) {
                $content = apply_filters('the_content', $post->post_content);
                echo $content;
            } else {
                echo the_content();
            }
            ?>
        </div>
    </div>
</article>
<script type="text/javascript">
    // PrintFriendly
    var e = document.createElement('script');
    e.type = "text/javascript";
    e.async = true;
    e.src = '//cdn.printfriendly.com/printfriendly.js';
    document.getElementsByTagName('head')[0].appendChild(e);
</script>



