<?php
/**
 * Displays content items which belongs to taxonomy topic
 * @Author: tomik_b
 */
get_header();
global $post;
$teaching_topic = get_teaching_topic_from_query();
$category = get_query_var("oxy_content_category");
if(empty($category)){
    $output = get_content_text_posts($teaching_topic);
    $output .= get_content_video_posts($teaching_topic);
}  else {
    $output = get_content_for_category($category, $teaching_topic);
}
echo $output;
wp_reset_postdata();
get_footer(); 

?>