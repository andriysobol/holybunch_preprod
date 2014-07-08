<?php
/**
 * Displays a tag archive
 * @package Smartbox
 * @subpackage Frontend
 * @since 0.1
 *
 * @copyright (c) 2013 Oxygenna.com
 * @license http://wiki.envato.com/support/legal-terms/licensing-terms/
 * @version 1.5
 */
get_header();
?>
<?php
$term = $wp_query->queried_object;
$title = $term->name;
if ($term->slug == "god") 
    $title = "";

oxy_create_hero_section(get_taxonomy_banner_image('teaching_topics', $term->slug), $title);
?>
<?php
if (isset($term->description)) {
    echo oxy_shortcode_section('', oxy_shortcode_topic_description(array("class" => 'lead lead_custom_mb'), $term->description));
}
?>

<?php get_template_part('partials/hb_loop_all'); ?>
<?php get_footer();

