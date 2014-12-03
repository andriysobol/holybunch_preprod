<?php
/**
 * Shows video item for: 
 * 1 taxonomy request (e.g. url='.../blog/teaching_topics/golgota/?post_type=oxy_video')
 * 2 search requesr (e.g. url='.../?s=Ибо+слово+о+кресте&post_type=oxy_video'); in this case relevanssi_the_excerpt will be used
 * @package Smartbox
 * @subpackage Frontend
 * @since 1.0
 *
 * @copyright (c) 2013 Oxygenna.com
 * @license http://wiki.envato.com/support/legal-terms/licensing-terms/
 * @version 1.5
 */
global $post;
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('row-fluid'); ?>>    
    <div class="span12 archive-body">
        <div class="entry-content">
            <?php
            $video_shortcode = get_field('video_shortcode', $post->ID);
            echo create_videowrapper_div($video_shortcode, $span = "span12", "1250", "703");
            ?>
        </div>
    </div>
</article>
