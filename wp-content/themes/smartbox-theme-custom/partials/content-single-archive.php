<?php
/**
 * Shows a simple single post
 *
 * @package Smartbox
 * @subpackage Frontend
 * @since 1.0
 *
 * @copyright (c) 2013 Oxygenna.com
 * @license http://wiki.envato.com/support/legal-terms/licensing-terms/
 * @version 1.5
 */
$author_id = get_the_author_meta('ID');
$img = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'small' );
if (empty($img[0]))
    $img[0] = "http://www.one-faith.ru/images/suend.jpg\"";

$atts[title] = get_the_title();
$atts[style] = 'grey';
$output .= "[row]";
$output .= "[span4]<img class=\"aligncenter\" style=\"margin-top: 20px; margin-bottom: 10px;\" alt=\"featured image\" src=\"" . $img[0] . "\" width=\"444\" height=\"325\" />";
$output .= "[/span4]";
$output .= "[span8] ";
//get summary
$output .= '<i>';
//get value of summary
$summary = get_field('summary', $post->ID);
$summary_more = apply_filters('summary_more', ' ' . '...');
$summary_more = '<a href="' . get_permalink() . '">' . '...' . '</a>';
$text = oxy_limit_excerpt($summary, 100);
$output .= $text . $summary_more;
$output .= '</i>';

$output .= "[/span8]";
$output .= "[/row]";
$output .= "[row]";
$output .= "[/row]";
$output = oxy_shortcode_section($atts, $output);
echo $output;
?>

<div class="text-right">
    <a href="<?php echo get_permalink(); ?>" class="btn btn-primary btn-large pull-center">
        <i class="icon-arrow-right"></i>
        <?php _e('Читать Далее', THEME_FRONT_TD); ?>
    </a>
</div>