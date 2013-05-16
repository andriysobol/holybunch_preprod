<?php
/**
 * Oxygenna.com
 *
 * $Template:: *(TEMPLATE_NAME)*
 * $Copyright:: *(COPYRIGHT)*
 * $Licence:: *(LICENCE)*
 */
require_once CORE_DIR . 'widget.php';

/**
 * Adds Caelus_title widget.
 */
class Smartbox_social extends OxyWidget {

    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        $widget_options = array( 'description' => __( 'Social Icons Widget', THEME_ADMIN_TD) );
        parent::__construct( 'smartbox_social-options.php', false, $name = THEME_NAME . ' - ' . __('Social Icons Widget', THEME_ADMIN_TD), $widget_options );
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
        extract( $args );

        $facebook   = $this->get_option( 'facebook', $instance, '');
        $twitter    = $this->get_option( 'twitter', $instance, '');
        $pinterest  = $this->get_option( 'pinterest', $instance, '');
        $googleplus = $this->get_option( 'googleplus', $instance, '');
        $linkedin   = $this->get_option( 'linkedin', $instance, '');
       

        $output = $before_widget;
        $output.= '<ul class="unstyled inline small-screen-center big social-icons">';
          $output.=($facebook !== '')?'<li><a data-iconcolor="#3b5998" href="'. $facebook.'"><i class="icon-facebook"></i></a></li>':'';
            $output.=($twitter !== '')?'<li><a data-iconcolor="#00a0d1" href="'. $twitter.'"><i class="icon-twitter"></i></a></li>':'';
            $output.=($pinterest !== '')? '<li><a data-iconcolor="#910101" href="'.$pinterest.'"><i class="icon-pinterest"></i></a></li>':'';
            $output.=($googleplus !== '')? '<li><a data-iconcolor="#E45135" href="'.$googleplus.'"><i class="icon-google-plus"></i></a></li>':'';
            $output.=($linkedin !== '')? '<li><a data-iconcolor="#5FB0D5" href="'.$linkedin.'"><i class="icon-linkedin"></i></a></li>':'';
        $output.= '</ul>';
        $output.= $after_widget;

        echo $output;
    }
}