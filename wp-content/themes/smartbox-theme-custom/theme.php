<?php
/**
 * Main child theme class which extends smartbox theme
 * @Author: Andriy Sobol
 */

require_once get_template_directory() . '/inc/core/theme.php';

class OxyCustomTheme extends OxyTheme
{
    public $theme;
    /**
     * Constructor
     * @param array $theme array of all theme options to use in construction this theme
     */
    function __construct( $theme ) {
        parent::__construct($theme);
    }
    
    function init() {
        parent::init();
        $this->load_shortcodes();
    }
    
    function load_shortcodes() {
        if( isset( $this->theme['shortcodes'] ) ) {
            foreach( $this->theme['shortcodes']  as $file ) {
                require_once $this->get_custom_template_directory() . 'shortcodes/hb_shortcodes.php';
            }
        }
    }    
    
    function get_custom_template_directory() {
        $template = 'smartbox-theme-custom/';
        $theme_root = get_theme_root($template);
        $template_dir = "$theme_root/$template";
        return apply_filters('template_directory', $template_dir, $template, $theme_root);
    }
    
    /**
     * Creates all #defines for the theme
    */
    function defines() {
        parent::defines();
        // directories
        define('CUSTOM_THEME_DIR', $this->get_custom_template_directory());
        define('CUSTOM_INCLUDES_DIR', CUSTOM_THEME_DIR . '/inc/');
        define('CUSTOM_IMAGES_DIR', home_url() . '/wp-content/themes/smartbox-theme-custom/images/');
    }
}