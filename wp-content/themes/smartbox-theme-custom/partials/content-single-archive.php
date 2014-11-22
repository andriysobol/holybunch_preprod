<?php
/** Shows a single text
 *
 * @package Smartbox
 * @subpackage Frontend
 * @since 1.0
 *
 * @copyright (c) 2013 Oxygenna.com
 * @license http://wiki.envato.com/support/legal-terms/licensing-terms/
 * @version 1.5
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('row-fluid'); ?>>    
    <div class="span12">
        <div class="span12 post-body">
            <div class="post-head">
                <h2 class="small-screen-center">
                    <?php if (is_single()) : ?>
                        <?php the_title(); ?>
                    <?php else : ?>
                        <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr(sprintf(__('Permalink to %s', THEME_FRONT_TD), the_title_attribute('echo=0'))); ?>" rel="bookmark">
                            <?php the_title(); ?>
                        </a>
                    <?php endif; // is_single() ?>
                </h2>
                <?php get_template_part('partials/post-extras'); ?>
            </div>
            <div class="entry-content">
                <?php
                $more_text = get_more_text($post->post_type);
                $link = get_permalink();
                if (is_search()):
                    $output = relevanssi_the_excerpt();
                    $output .= oxy_shortcode_accordions('','[accordion title="Краткое описание статьи"]'.get_post_summary_mini($post).'[/accordion]');
                    $output .= '<a href="' . $link . '" class="more-link">' . $more_text . '</a>';
                else:
                    $content = get_post_summary_mini($post); 
                    $content .= '<a href="' . $link . '" class="more-link">' . $more_text . '</a>';
                    $output = apply_filters('the_content', $content);
                endif;
                echo $output;
                ?>
            </div>
            </article>
