<?php
/**
 * Displays content items which belongs to taxonomy topic
 * @Author:
 */
get_header();
global $post;
$teaching_topic = get_teaching_topic_from_query();
$category = get_query_var('oxy_content_category');
$my_query = get_query($category, $teaching_topic);
//$my_query = get_query_only_video($teaching_topic);
//$my_query = get_query_only_music($teaching_topic);
?>
<?php if ($my_query->have_posts()): ?>
    <section class="section" <?php echo $data_image ?> >
        <div class="container-fluid">
        <?php else: ?>
            <h1 class="animated fadeinup delayed text-center">
                <?php $my_query->the_title(); ?>
            </h1>
        <?php endif ?>
        <div class="row-fluid margin-top">
            <div class="span11"> 
                <?php global $wp_embed; ?>
                <?php
                $video_content;
                while ($my_query->have_posts()):
                    $my_query->the_post();
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
                                    <div class="span12"><hr noshade size="4" align="center">  </div>
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
                                <div class="span12" style = "color:#FFA500;">
                                    <?php echo get_field('quote'); ?>
                                </div>
                                <div class="span12">
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
<!--                <div class="section">
                    <div class="row-fluid margin-top">
                        <div style="text-align: center">
                            <h1>
                                <?php //echo "Читайте далее"; ?>
                            </h1>
                        </div>
                    </div>
                </div>
-->
            </div>                
        </div>
    </div>
</section>
<?php wp_reset_postdata(); get_footer();?>