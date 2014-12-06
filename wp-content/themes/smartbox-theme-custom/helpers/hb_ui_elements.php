<?php
/**
 * @description get all taxonomies which contain posts and return as term cloud, used for (video-)archive 
 * @param string $post_type <i>type of post</i>
 * @param title $title <i>title</i>
 * @return string
 */
function get_taxonomy_terms_cloud($post_type, $title) {
    if (empty($post_type))
        $post_type = array('oxy_content', 'oxy_video', 'oxy_audio');
    $args = array(
        'hide_empty' => 1,
        'taxonomy' => 'teaching_topics',
        'pad_counts' => 1,
        'hierarchical' => 0
    );
    $categories = get_categories($args);
    $output = '<div id="tag_cloud-3" class="sidebar-widget  widget_tag_cloud">';
    $output .= '<div class="tagcloud">';
    $output .= '    <h3 class="sidebar-header">'.$title.'</h3>';
    $output .= '<ul>';
    $output .= get_taxonomy_terms_as_list($categories, $post_type);
    $output .= '</ul>';
    $output .= '</div>';
    $output .= '</div>';
    echo $output;
}
?>
