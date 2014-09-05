<?php
/**
 * Displays a timeline custom post
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
oxy_page_header();
// get the author name
if( get_query_var('author') ){
    $author = get_userdata( get_query_var( 'author' ) );
}

$query = new WP_Query(  array( 'post_type' => 'post', 'author' => $author->ID, 'post_status' => 'publish' ) );
?>
<?php oxy_create_hero_section( null, '<span class="lighter">' .  $author->nickname . '</span>'  ); ?>
<section class="section section-padded section-alt">
    <div class="container-fluid">
        <div class="row-fluid">
            <ol id="timeline">
                <?php while ( $query->have_posts() ) : $query->the_post(); ?>

                <?php get_template_part( 'partials/timeline/content-timeline-excerpt' ); ?>

                <?php endwhile; ?>

                <?php wp_reset_postdata(); ?>
            </ol>
        </div>
    </div>
</section>
<?php get_footer();