<?php
/**
 * Displays a single post
 *
 * @package Smartbox
 * @subpackage Frontend
 * @since 0.1
 *
 * @copyright (c) 2013 Oxygenna.com
 * @license http://wiki.envato.com/support/legal-terms/licensing-terms/
 * @version 1.4
 */

get_header();
global $post;
$custom_fields = get_post_custom($post->ID);
if ( isset ($custom_fields[THEME_SHORT.'_thickbox']) ){
    $img = wp_get_attachment_image_src( $custom_fields[THEME_SHORT.'_thickbox'][0], 'full' );
    oxy_create_hero_section( $img[0], $post->post_title );
} else {
    oxy_create_hero_section( null, $post->post_title);
}

$allow_comments = oxy_get_option( 'site_comments' );
?>

<section class="section section-padded">
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span12">
                <?php while ( have_posts() ) : the_post(); ?>
                               <div class="span12" style = "color:#FFA500;">
                    <?php echo get_field('quote'); ?>
                </div>
                <?php get_template_part( 'partials/content', get_post_format() ); ?>
                <?php oxy_wp_link_pages(array('before' => '<div class="pagination pagination-centered">', 'after' => '</div>')); ?>
                <?php echo get_related_posts(get_the_ID()); ?>
                <?php if( $allow_comments == 'posts' || $allow_comments == 'all' ) comments_template( '', true ); ?>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
</section>
<?php get_footer();