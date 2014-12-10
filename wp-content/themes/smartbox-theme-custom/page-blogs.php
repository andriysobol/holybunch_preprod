<?php
/**
 * Displays page with all blogs in it
 */
get_header();
oxy_page_header();
$allow_comments = oxy_get_option( 'site_comments' );
?>
<section class="section section-padded">
    <div class="container-fluid">
        <div class="row-fluid">
            <?php get_template_part( 'partials/hb_loop_blog' ); ?>
        </div>
    </div>
</section>
<?php get_footer();