<?php
/**
 * Shows a simple single post
 *
 * @package Smartbox
 * @subpackage Frontend
 * @since 1.0
 *
 * @copyright (c) 2013 Oxygenna.com
 * @license http://wiki.envato.com/support/legal-terms/licensing-terms/
 * @version 1.4
 */
$author_id = get_the_author_meta('ID');
?>
<li class="timeline-item">
    <div class="well post">
        <div class="post-info">
            <h5 class="text-center light">
                  <?php the_time(get_option('date_format')); ?>
            </h5>
            <div class="round-box box-small">
                <?php echo get_avatar( $author_id, 300 ); ?>
            </div>
            <h5 class="text-center">
                 <?php the_author(); ?>
            </h5>
        </div>
        <div class="post-body clearfix">
            <h4 class="post-title small-screen-center">
                <a href="<?php the_permalink(); ?>">
                    <?php the_title(); ?>
                </a>
            </h4>
            <?php  // if post has featured image , display it also.
                if( has_post_thumbnail() ) :
                    $img = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full' );
                    echo '<figure>'
                        .   '<img alt="featured image" src="'.$img[0].'">'
                        .'</figure>';
                endif;
                global $more;    // Declare global $more (before the loop).
                $more = 0;
                $content= oxy_limit_excerpt($post->post_content, "60") ;
                $more_text=  get_more_text($post->post_type);
                $link = get_permalink();
                $content .= '<a href="' . $link . '" class="more-link">' . $more_text . '</a>'; 
                echo $content;
            ?>
        </div>
        <div class="post-arrow"></div>
    </div>
</li>
