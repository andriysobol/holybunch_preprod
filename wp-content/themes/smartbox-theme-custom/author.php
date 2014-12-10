<?php
/**
 * Displays a excerpts of blogs by author in timeline layout
*/

get_header();
oxy_page_header();
// get the author name
if( get_query_var('author') ){
    $author = get_userdata( get_query_var( 'author' ) );
    $author_name = $author->display_name;
}
?>
<?php oxy_create_hero_section( null, '<span class="lighter">' . $author_name  . '</span>'  ); ?>
<section class="section section-padded section-alt">
    <div class="container-fluid">
        <div class="row-fluid">
            <ol id="timeline">
                <?php while ( have_posts() ) : the_post(); ?>
                    <?php get_template_part( 'partials/timeline/content-timeline-excerpt' ); ?>
                <?php endwhile; ?>
                <?php wp_reset_postdata(); ?>
            </ol>
        </div>
    </div>
</section>
<?php get_footer();