<?php
/**
 * Displays the head section of the theme
 *
 * @package Smartbox
 * @subpackage Frontend
 * @since 0.1
 *
 * @copyright (c) 2013 Oxygenna.com
 * @license http://wiki.envato.com/support/legal-terms/licensing-terms/
 * @version 1.4
 */
?><!DOCTYPE html>
<!--[if IE 8 ]> <html <?php language_attributes(); ?> class="ie8"> <![endif]-->
<!--[if IE 9 ]> <html <?php language_attributes(); ?> class="ie9"> <![endif]-->
<!--[if gt IE 9]> <html <?php language_attributes(); ?>> <![endif]-->
<!--[if !IE]> <!--> <html <?php language_attributes(); ?>> <!--<![endif]-->
    <head>
        <meta charset="<?php bloginfo( 'charset' ); ?>" />
        <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible" />
        <title><?php wp_title( '|', true, 'right' );  bloginfo('name'); ?></title>
        <meta content="Christian side" name="description" />
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
        <link href="<?php echo oxy_get_option( 'favicon' ); ?>" rel="shortcut icon" />
        <meta name="google-site-verification" content="<?php echo oxy_get_option('google_webmaster'); ?>" />

        <?php oxy_add_apple_icons( 'iphone_icon' ); ?>
        <?php oxy_add_apple_icons( 'iphone_retina_icon', 'sizes="114x114"' ); ?>
        <?php oxy_add_apple_icons( 'ipad_icon', 'sizes="72x72"' ); ?>
        <?php oxy_add_apple_icons( 'ipad_retina_icon', 'sizes="144x144"' ); ?>

        <!--[if lt IE 9]>
          <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
          <script src="javascripts/PIE.js"></script>
        <![endif]-->
<!-- jquery -->
<script src="http://holybunch.com/wp-content/themes/smartbox-theme-custom/inc/js/jquery-1.7.2.min.js" type="text/javascript"></script>
<!-- easing plugin ( optional ) -->
<script src="http://holybunch.com/wp-content/themes/smartbox-theme-custom/inc/js/easing.js" type="text/javascript"></script>
<!-- UItoTop plugin -->
<script src="http://holybunch.com/wp-content/themes/smartbox-theme-custom/inc/js/jquery.ui.totop.js" type="text/javascript"></script>
<!-- Starting the plugin -->
<script type="text/javascript">
$(document).ready(function() {
/*
var defaults = {
containerID: 'toTop', // fading element id
containerHoverID: 'toTopHover', // fading element hover id
scrollSpeed: 1200,
easingType: 'linear'
};
*/
 
$().UItoTop({ easingType: 'easeOutQuart' });
 
});
</script>
        <?php wp_head(); ?>
<script>
var _prum = [['id', '53c3db78abe53d4068bb7da6'],
             ['mark', 'firstbyte', (new Date()).getTime()]];
(function() {
    var s = document.getElementsByTagName('script')[0]
      , p = document.createElement('script');
    p.async = 'async';
    p.src = '//rum-static.pingdom.net/prum.min.js';
    s.parentNode.insertBefore(p, s);
})();
</script>
    </head>
    <body <?php body_class( oxy_get_option('style') . ' ' .  oxy_get_option('width')); ?>>
        <?php if ( is_active_sidebar( 'above-nav-right' ) || is_active_sidebar( 'above-nav-left' ) ) : ?>
        <div id="top-bar" class="<?php echo oxy_get_option('skin'); ?>">
            <div class="wrapper wrapper-transparent top-wrapper">
                <div class="container-fluid">
                    <div class="row-fluid">
                        <div class="span6 small-screen-center text-left">
                            <?php dynamic_sidebar( 'above-nav-left' ); ?>
                        </div>
                        <div class="span6 small-screen-center text-right">
                            <?php dynamic_sidebar( 'above-nav-right' ); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <div class="wrapper">
            <!-- Page Header -->
            <header id="masthead">
                <nav class="navbar navbar-static-top <?php echo oxy_get_option('header_style'); ?>">
                    <div class="navbar-inner">
                        <div class="container-fluid">
                            <a class="btn btn-navbar" data-target=".nav-collapse" data-toggle="collapse">
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </a>
                            <?php oxy_create_logo(); ?>
                            <nav class="nav-collapse collapse" role="navigation">
                                <?php
                                if( has_nav_menu( 'primary' ) ) {
                                    wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'nav pull-right ' . oxy_get_option('menu_compact'), 'depth' => 3, 'walker' => new OxyNavWalker() ) );
                                }
                                ?>
                            </nav>
                        </div>
                    </div>
                </nav>
            </header>
            <div id="content" role="main">