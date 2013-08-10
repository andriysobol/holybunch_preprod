<?php
/**
 * Display taxonomy topic page
 * @Author: andriy sobol 
 * This page is a prototype. I've tried to query content for taxonomy using while (have_posts()): the_post();
 * But it doesn't work properly. It shows also content items from taxonomy children which is not needed for us
 * We need explicit query with include_children = false;
 */
get_header();
global $post;

// if override image not empty , display default image as portfolio background.
$img = '';
$data_image = '';
$override_image = get_post_meta($post->ID, THEME_SHORT . '_background', true);
if (!empty($override_image)) {
    $img = wp_get_attachment_image_src($override_image, 'full');
    if ($img[0] !== null) {
        $img = $img[0];
        $data_image = 'data-background="url(' . $img . ') no-repeat top"';
    }
}
?>
<section class="section" <?php echo $data_image ?> >
    <div class="container-fluid">
        <?php if ($img == ''): ?>

        <?php else: ?>
            <h1 class="animated fadeinup delayed text-center">
                <?php the_title(); ?>
            </h1>
        <?php endif ?>
        <div class="row-fluid margin-top">
            <div class="span9"> 
                <?php global $wp_embed; ?>
                <?php

                //global $wp_query;
                //$wp_query->queries[0][include_children] = 0;
                $video_content;
                while (have_posts()):
                    the_post();
                    $content = get_the_content();
                    $title = get_the_title();
                    ?>
                    <section class="section">

                        <?php
                        if (get_post_format($post) == 'video') {
                            if ($video_content == null) {
                                ?>
                                <div class="row-fluid margin-top">
                                    <div style="text-align: center">
                                        <h1>
                                            <?php the_title(); ?>
                                        </h1>
                                    </div>
                                    <?php $video_content = $wp_embed->run_shortcode($content); ?>
                                    <div class="span1"></div>
                                    <div class="span9" style="align:center">
                                        <?php echo $video_content; ?>
                                    </div>
                                    <div class="span11"><hr noshade size="4" align="center">  </div>
                                </div>
                                <?php
                            }
                        } else {
                            ?>
                            <div class="row-fluid margin-top">
                                <div style="text-align: center">
                                    <h1>
                                        <?php the_title(); ?>
                                    </h1>
                                </div>
                                <div class="span11" style = "color:#FFA500;">
                                    <?php echo get_field('quote'); ?>
                                </div>
                                <div class="span11">
                                    <?php
                                    $content_more = '<a href="' . get_permalink() . '">' . '... <i>Читать далее</i>' . '</a>';
                                    echo wp_trim_words($content, 150, $content_more);
                                    ?>
                                    <hr noshade size="4" align="center"> 
                                </div>
                            </div>
                        </section>

                        <?php
                    }
                endwhile
                ?>
                <div class="section">
                    <div class="row-fluid margin-top">
                        <div style="text-align: center">
                            <h1>
                                <?php echo "Читайте далее"; ?>
                            </h1>
                        </div>
                    </div>
                </div>
            </div> 
            <aside class="span3 sidebar">
                <?php get_sidebar(); ?>
            </aside>
        </div>

    </div>
</section>
