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
 * @version 1.01
 */

get_header();
global $post;
$custom_fields = get_post_custom($post->ID);
if ( isset ($custom_fields[THEME_SHORT.'_thickbox']) ){
    $img = wp_get_attachment_image_src( $custom_fields[THEME_SHORT.'_thickbox'][0], 'full' );
    oxy_create_hero_section( $img[0] );
} else {
    oxy_create_hero_section();
}

$allow_comments = oxy_get_option( 'site_comments' );
?>

<section class="section section-padded">
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span9">
                <?php while ( have_posts() ) : the_post(); ?>

                <?php get_template_part( 'partials/content', get_post_format() ); ?>

                <nav id="nav-below" class="post-navigation">
                    <ul class="pager">
                        <li class="previous"><?php previous_post_link( '%link', '<i class="icon-angle-left"></i>' . ' %title' ); ?></li>
                        <li class="next"><?php next_post_link( '%link', '%title ' . '<i class="icon-angle-right"></i>' ); ?></li>
                    </ul>
                </nav><!-- nav-below -->

                <?php if( $allow_comments == 'posts' || $allow_comments == 'all' ) comments_template( '', true ); ?>

                <?php endwhile; ?>
            </div>
            <aside class="span3 sidebar">
                <?php get_sidebar(); ?>
            </aside>
        </div>
    </div>
</section>
<?php get_footer();