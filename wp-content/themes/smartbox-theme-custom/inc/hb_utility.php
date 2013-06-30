<?php

function get_category_term_link_for_taxonomy_topic( $category_term, $teaching_topic) {
    $content_link = get_term_link($category_term, $taxonomy = 'oxy_content_category');
    if(!is_a($content_link, 'WP_Error' )){
        $term_link = $content_link . "?taxonomy=teaching_topics&term=" . $teaching_topic;
        return $term_link;
    }
    return '';
}

function get_post_type_link_for_taxonomy_topic( $post_type, $teaching_topic) {
    $post_link = get_post_type_archive_link( $post_type );
    if(!is_a($post_link, 'WP_Error' )){
        $term_link = $post_link . "?taxonomy=teaching_topics&term=" . $teaching_topic;
        return $term_link;
    }
    return '';
}

?>
