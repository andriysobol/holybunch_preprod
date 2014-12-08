<?php
/**
 * Main Blog loop
 *
 * @package Smartbox
 * @subpackage Frontend
 * @since 1.4
 *
 * @copyright (c) 2013 Oxygenna.com
 * @license **LICENSE**
 * @version 1.5
 */
?>
<?php
    $taxonomy_term = $wp_query->queried_object;
    echo hb_ui_taxonomy_topic_page($taxonomy_term);
?>

