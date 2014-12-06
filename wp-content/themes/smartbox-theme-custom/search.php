<?php
/**
 * Displays a tag archive
 * @package Smartbox
 * @subpackage Frontend
 * @since 0.1
 *
 * @copyright (c) 2013 Oxygenna.com
 * @license http://wiki.envato.com/support/legal-terms/licensing-terms/
 * @version 1.4
 */
get_header();
?>
<?php oxy_create_hero_section(null, __('Search', THEME_FRONT_TD) . ' ' . '<span class="lighter">' . get_search_query() . '</span>'); ?>
<section class="section section-padded">    
<div class="container-fluid">
        <div class="row-fluid">
            <div class="span12">
                <form role="search" method="get" id="searchform" action="<?php echo home_url('/'); ?>">
                    <input type="text" name="s" id="s" <?php if (is_search()) { ?>value="<?php the_search_query(); ?>" <?php } else { ?>value="Enter keywords &hellip;" onfocus="if (this.value == this.defaultValue)
                this.value = '';" onblur="if(this.value == '')this.value = this.defaultValue;"<?php } ?> />
                    <input type="submit" id="searchsubmit" value="Search" ><br />
                    <?php $query_types = get_query_var('post_type'); ?>
                                       
                    <input type="checkbox" name="post_type[]" value="oxy_content" <?php if (is_array($query_types) and in_array('oxy_content', $query_types)) {
                        echo 'checked="checked"';
                    } ?> > Проповедь
                    <input type="checkbox" name="post_type[]" value="post" <?php if (is_array($query_types) and in_array('post', $query_types)) {
                        echo 'checked="checked"';
                    } ?> > Блог
                    <input type="checkbox" name="post_type[]" value="oxy_audio" <?php if (is_array($query_types) and in_array('oxy_audio', $query_types)) {
                        echo 'checked="checked"';
                    } ?> > Аудио
                    <input type="checkbox" name="post_type[]" value="oxy_video" <?php if (is_array($query_types) and in_array('oxy_video', $query_types)) {
                        echo 'checked="checked"';
                        } ?> /> Видео<br>                       
                </form>
            </div>
        </div>
    </div>
</section>
<section class="section section-padded">    
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span12">
                            <?php if (have_posts()) : ?>

                            <?php while (have_posts()) : the_post(); ?>
                        <div class="post-head">
                            <h2 class="small-screen-center">
                            <?php if (is_single()) : ?>
            <?php the_title(); ?>
        <?php else : ?>
                                    <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr(sprintf(__('Permalink to %s', THEME_FRONT_TD), the_title_attribute('echo=0'))); ?>" rel="bookmark">
                                <?php the_title(); ?>
                                    </a>
                            <?php endif; // is_single()  ?>
                            </h2>
                            <?php get_template_part('partials/post-extras'); ?>
                        </div>
                        <div class="entry-content">

                        <?php
                        $more_text = hb_get_more_text($post->post_type);
                        $link = get_permalink();
                        $content = relevanssi_the_excerpt();
                        $content .= '<a href="' . $link . '" class="more-link">' . $more_text . '</a>';
                        echo $content;
                        ?>
                        </div>
    <?php endwhile; ?>
                            <?php oxy_pagination($wp_query->max_num_pages); ?>
                        <?php else: ?>
                    <article id="post-0" class="post no-results not-found">
                        <header class="entry-header">
                            <h1 class="entry-title"><?php _e('Nothing Found', THEME_FRONT_TD); ?></h1>
                        </header>

                        <div class="entry-content">
                            <p><?php _e('Sorry, but nothing matched your search criteria. Please try again with some different keywords.', THEME_FRONT_TD); ?></p>
    <?php get_search_form(); ?>
                        </div><!-- .entry-content -->
                    </article><!-- #post-0 -->
<?php endif; ?>

            </div>
        </div>
    </div>
</section>
<?php
get_footer();