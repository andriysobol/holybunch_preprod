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

<div class="span12">
    <?php 
        $taxonomy_term = $wp_query->queried_object;
        hb_create_flexslider( $taxonomy_term, 'oxy_content', 'Проповеди', 'white') ;
        hb_create_flexslider( $taxonomy_term, 'oxy_video', 'Видео', 'white') ;
        hb_create_flexslider( $taxonomy_term, 'oxy_audio', 'Аудио', 'white') ;
    ?>
</div>
