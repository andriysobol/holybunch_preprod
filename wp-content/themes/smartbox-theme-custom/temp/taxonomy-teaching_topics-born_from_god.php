<?php
/**
 * Displays taxonomy topic "born_from_god"
 * @Author: andriy sobol
 * It is just prototype 
 */
get_header();
global $post;
//get taxonomy term description
$taxonomy_name = 'teaching_topics';
$title = get_the_title();
$topic = $teaching_topics;
$termDiscription = get_taxonomy_description($taxonomy_name, $topic);
if (empty($termDiscription))
    return 'Темы(' . $topic . '), которую ты указал в shortcode не существует, используй существующую тему';

//get taxonomy main video content
$video_content = get_taxonomy_video($taxonomy_name, $topic);
if (empty($video_content))
    return 'Ты не указал видео для это темы. Укажи видео в таксономии: ' . $taxonomy_name;

//$content .= '[section title="' . $title . '"]';
$content .= $termDiscription;
//$content .= '[/section]';
$atts[title] = $title;
$output = oxy_shortcode_section($atts, $content);
echo $output;
?>
