<?php
/**
 * Shows a simple gallery post
 *
 * @package Smartbox
 * @subpackage Frontend
 * @since 1.0
 *
 * @copyright (c) 2013 Oxygenna.com
 * @license http://wiki.envato.com/support/legal-terms/licensing-terms/
 * @version 1.5
 */
global $post;
$author_id = get_the_author_meta('ID');
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('row-fluid'); ?>>    
    <div class="span12">
        <div class="span12 post-body">
        <div class="post-head">
            <h2 class="small-screen-center">
                <?php if ( is_single() ) : ?>
                    <?php the_title(); ?>
                <?php else : ?>
                    <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', THEME_FRONT_TD ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark">
                        <?php the_title(); ?>
                    </a>
                <?php endif; // is_single() ?>
            </h2>
        </div>
        <div class="entry-content">
            <?php
            $content = hb_limit_excerpt(get_the_content(), 40);
            $more_text = __('Read more', THEME_FRONT_TD);
            $link = get_permalink();
            $content .= '<a href="' . $link . '" class="more-link">' . $more_text . '</a>';
            $video_shortcode = get_field('video_shortcode', $post->ID);
            $output = create_videowrapper_div($video_shortcode, $span="span8", "600", "400").
            '<div class="span4" style="margin-top: 25px;">'.
            $content. 
            '</div>';
            echo $output;
            ?>
        </div>
      </div>
</article>
