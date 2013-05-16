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
 * @version 1.01
 */
$author_id = get_the_author_meta('ID');
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('row-fluid'); ?>>
    <div class="span2 post-info">
        <div class="round-box box-small">
            <?php echo get_avatar( $author_id, 300 ); ?>
        </div>
        <h5 class="text-center">
            <?php the_author(); ?>
        </h5>
        <h5 class="text-center light">
            <?php the_time(get_option('date_format')); ?>
        </h5>
    </div>
    <div class="span10 post-body">
        <div class="post-head">
            <div class="post-extras">
                <?php if( has_tag() ) : ?>
                <i class="icon-tags"></i>
                <?php the_tags( $before = null, $sep = ', ', $after = '' ); ?>
                <?php endif; ?>
                <?php if( has_category() ) : ?>
                <i class="icon-bookmark"></i>
                <?php the_category( ', ' ); ?>
                <?php endif; ?>
                <?php if ( comments_open() && ! post_password_required() ) : ?>
                <i class="icon-comments"></i>
                <?php comments_popup_link( _x( 'No comments', 'comments number', THEME_FRONT_TD ), _x( '1 comment', 'comments number', THEME_FRONT_TD ), _x( '% comments', 'comments number', THEME_FRONT_TD ) ); ?>
            <?php endif; ?>
            </div>
        </div>
        <div class="entry-content">
                <?php if ( has_post_thumbnail() ){
                    $img = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full' );
                    echo '<figure>'
                        .   '<img alt="featured image" src="'.$img[0].'">'
                        .'</figure>'; ?>
                <?php  } ?>
                <?php echo do_shortcode('[blockquote who="'.get_the_title().'" cite=""]'.get_the_content().'[/blockquote]'); ?>
        </div>

    </div>
</article>

