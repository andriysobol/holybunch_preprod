<?php
/* Generates first of all a single text item html section, 
 * used for showing all items by hb_loop, but also used for:
 * 1 taxonomy request (e.g. url='.../blog/teaching_topics/golgota/?post_type=oxy_text')
 * 2 search request (e.g. url='.../?s=Ибо+слово+о+кресте&post_type=oxy_text'); in this case relevanssi_the_excerpt will be used - $output = relevanssi_the_excerpt()
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('row-fluid'); ?>>    
    <div class="span12 archive-body">
        <div class="post-head">
            <h2 class="small-screen-center">
                <?php if (is_single()) : ?>
                    <?php the_title(); ?>
                <?php else : ?>
                    <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr(sprintf(__('Permalink to %s', THEME_FRONT_TD), the_title_attribute('echo=0'))); ?>" rel="bookmark">
                        <?php the_title(); ?>
                    </a>
                <?php endif; ?>
            </h2>
        </div>
        <div class="entry-content">
            <?php
            $content_right .= hb_ui_link(array(
                'content' => hb_get_more_text($post->post_type),
                'link' => get_permalink(),
                'class' => 'more-link'));

            if (is_search()):
                $output = relevanssi_the_excerpt() . $content_right;
            else:
                $content = oxy_limit_excerpt(strip_tags(get_the_content()), 55) . $content_right;
                $output = apply_filters('the_content', $content);
            endif;
            echo $output;
            ?>
        </div>
    </div>
</article>
