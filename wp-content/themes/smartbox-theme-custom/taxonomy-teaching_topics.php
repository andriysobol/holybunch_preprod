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
<?php get_template_part('partials/hb_loop_all'); ?>
<?php echo  do_shortcode( '[hb_contact_form title="Напишите нам"]
<p>Если Вы желаете общаться с нами, узнать больше о нашей вере или же у Вас есть вопросы о нашей церкви, пишите нам!</p>

<p>Мы всегда рады общению с ищущими познать Правду на основании Писания.</p>
[/hb_contact_form]');?>
<?php get_footer();

