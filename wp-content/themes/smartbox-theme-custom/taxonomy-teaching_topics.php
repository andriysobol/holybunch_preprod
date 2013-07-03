<?php
/**
 * Displays a single portfolio post
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
 
// if override image not empty , display default image as portfolio background.
$img = '';
$data_image = '';
$override_image = get_post_meta( $post->ID, THEME_SHORT . '_background', true );
if( !empty( $override_image ) ) {
    $img = wp_get_attachment_image_src( $override_image, 'full' );
    if( $img[0] !== null ) {
        $img = $img[0];
        $data_image = 'data-background="url(' . $img . ') no-repeat top"';
    }
}
$args = array(
            // post basics
            'post_type' => 'oxy_content', // check capitalization, make sure this matches your post type slug
            'post_status' => 'publish', // you may not need this line.
            'posts_per_page' => 3, // set this yourself, 10 is a placeholder
            'post__not_in' => array($video[0]->ID),
            // taxonomy
            'tax_query' => array(
                array( 
				'taxonomy' => 'teaching_topics', // slug for desired tag goes here
                'field' => 'slug',
                'terms' => get_query_var('teaching_topics'), // should work without a slug, try it both ways...and use a variable, don't hardcode
	            'include_children' => false,
				)
            )
        );
        $my_query = new wp_query($args);?>
<?php if ( $my_query->have_posts() ):
?>
<section class="section" <?php echo $data_image ?> >
    <div class="container-fluid">
            <?php if ( $img == ''): ?>
                
            <?php else: ?>
                <h1 class="animated fadeinup delayed text-center">
                   <?php the_title(); ?>
                </h1>
            <?php endif ?>
            <div class="row-fluid margin-top">
				<div class="span9"> 
				<?php global $wp_embed;?>
				<?php $video_content; 
				while ( $my_query->have_posts() ):
					$my_query->the_post();
					$content = get_the_content();
					$title = get_the_title();
				?>
					<section class="section">
					
					<?php  
					if( get_post_format($post) == 'video' ) {
						if($video_content==null){ ?>
							<div class="row-fluid margin-top">
								<div style="text-align: center">
								<h1>
								<?php the_title(); ?>
								</h1>
								</div>
								<?php $video_content = $wp_embed->run_shortcode( $content ); ?>
								<div class="span1"></div>
								<div class="span9" style="align:center">
								<?php echo $video_content;?>
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
						<?php echo get_field( 'quote' ); ?>
						</div>
						<div class="span11">
						<?php 
						$content_more = '<a href="' . get_permalink() . '">' . '... <i>Читать далее</i>' . '</a>';
						echo wp_trim_words($content,150, $content_more); 
						?>
						<hr noshade size="4" align="center"> 
						</div>
					</div>
					</section>
					
					<?php
					} 
					endwhile ?>
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
<?php endif;


    wp_reset_postdata();


get_footer();