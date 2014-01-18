<?php

require_once WPCF_ABSPATH . '/wp-load.php';
// Initialize the page ID to -1. This indicates no action has been taken.
$post_id = -1;
// Setup the author, slug, and title for the post
$author_id = 1;
$slug = 'example-post';
$title = 'My Example Post';
// If the page doesn't already exist, then create it
if( null == get_page_by_title( $title ) ) {
// Set the post ID so that we know the post was created successfully
$post_id = wp_insert_post(
array(
'comment_status'  => 'closed',
'ping_status'   => 'closed',
'post_author'   => $author_id,
'post_name'   => $slug,
'post_title'    => $title,
'post_status'   => 'publish',
'post_content'  => '[video width="600" height="480" mp4="http://bible-core.com/wp-content/uploads/2014/01/myStream.mp4"]',
'post_type'   => 'service'
)
);
// Otherwise, we'll stop
} else {
// Arbitrarily use -2 to indicate that the page with the title already exists
$post_id = -2;
} // end if

?>
