<?php
/**
 * Displays a single post of type oxy_video
 */
get_header();
global $post;
oxy_create_hero_section(hb_get_post_banner_image($post), $post->post_title);
$allow_comments = oxy_get_option('site_comments');
?>
<section class="section section-padded">
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span12">
                <?php while (have_posts()) : the_post(); ?>
                    <div class="span12" style = "color:#FFA500;">
                        <?php echo get_field('quote'); ?>
                    </div>
                    <?php echo hb_ui_video_content($post); ?>
                    <?php oxy_wp_link_pages(array('before' => '<div class="pagination pagination-centered">', 'after' => '</div>')); ?>
                    <?php echo hb_ui_related_posts(get_the_ID()); ?>
                    <?php if ($allow_comments == 'posts' || $allow_comments == 'all') comments_template('', true); ?>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
</section>
<?php
get_footer();
