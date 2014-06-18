<?php
/* 
Template Name: Archives
*/
get_header();
oxy_page_header();
$allow_comments = oxy_get_option( 'site_comments' );
?>
<div id="primary" class="site-content">
<div id="content" role="main">

<?php while ( have_posts() ) : the_post(); ?>
				
<h1 class="entry-title"><?php the_title(); ?></h1>

<div class="entry-content">

<?php the_content(); ?>
<!-- Custom Archives Functions Go Below this line -->
<p><strong>By Date</strong></p>
<ul>
<?php compact_archive($style='block'); ?>
</ul>

<p><strong>Categories:</strong></p>
<ul class="bycategories">
<?php wp_list_categories('title_li='); ?>
</ul>
<div class="clear"></div>

<p><strong>Tags Cloud:</strong></p>
<?php wp_tag_cloud(); ?>

<p><strong>Archives:</strong></p>
<?php wp_get_archives('type=postbypost&limit=10'); ?>

<p><strong>Video Category:</strong></p>
    <?php
    $term_id = 4;
    $taxonomy_name = 'oxy_content_category';
    $termchildren = get_term_children($term_id, $taxonomy_name);

    echo '<ul>';
    foreach ($termchildren as $child) {
        $term = get_term_by('id', $child, $taxonomy_name);
        echo '<li><a href="' . get_term_link($child, $taxonomy_name) . '">' . $term->name . '</a></li>';
    }
    echo '</ul>';
    ?>

</div><!-- .entry-content -->

<?php endwhile; // end of the loop. ?>

</div><!-- #content -->
</div><!-- #primary -->
<?php get_footer(); ?>
