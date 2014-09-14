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
 * @version 1.4
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