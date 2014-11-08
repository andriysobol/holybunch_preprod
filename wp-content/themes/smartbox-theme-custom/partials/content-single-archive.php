<?php
/** Shows a single text
 *
 * @package Smartbox
 * @subpackage Frontend
 * @since 1.0
 *
 * @copyright (c) 2013 Oxygenna.com
 * @license http://wiki.envato.com/support/legal-terms/licensing-terms/
 * @version 1.5
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('row-fluid'); ?>>    
    <div class="span12">
        <div class="span12 post-body">
            <div class="entry-content">
                <?php
                if (is_search()):
                    $output = create_one_text_items($post, relevanssi_the_excerpt());  
                else:
                    $output = create_one_text_items($post);  
                endif;
                echo $output;
                ?>
            </div>
</article>
