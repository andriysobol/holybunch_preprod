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
global $post;
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
            <h2 class="small-screen-center">
                <?php
                $link_shortcode = oxy_get_content_shortcode( $post, 'link' );
                if( $link_shortcode !== null ) {
                    if( isset( $link_shortcode[5] ) ) {
                        $link_shortcode = $link_shortcode[5];
                        if( isset( $link_shortcode[0] ) ) {
                            $title = '<a href="' . $link_shortcode[0] . '">' . get_the_title( $post->ID ) . ' <i class="icon-double-angle-right"></i></a>';
                        }
                    }
                }
                echo empty( $title ) ? the_title() : $title;
                ?>
            </h2>
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
            <?php
            if ( has_post_thumbnail() ){
                $img = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full' );
                echo '<figure>' . '<img alt="featured image" src="'.$img[0].'">' . '</figure>';
            }
            the_content();
            ?>
        </div>
    </div>
</article>

