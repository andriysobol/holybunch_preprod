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
 * @version 1.01
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
        <meta content="Bootsrap based theme" name="description" />
        <meta content="width=device-width" name="viewport" />
        <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
        <link href="favicon.ico" rel="shortcut icon" />
        <link href="images/apple-touch-icon-144-precomposed.png" rel="apple-touch-icon-precomposed" sizes="144x144" />
        <link href="images/apple-touch-icon-114-precomposed.png" rel="apple-touch-icon-precomposed" sizes="114x114" />
        <link href="images/apple-touch-icon-72-precomposed.png" rel="apple-touch-icon-precomposed" sizes="72x72" />
        <link href="images/apple-touch-icon-57-precomposed.png" rel="apple-touch-icon-precomposed" />
        <!--[if lt IE 9]>
          <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
          <script src="javascripts/PIE.js"></script>
        <![endif]-->
        <?php wp_head(); ?>
    </head>
    <body <?php body_class( oxy_get_option('style') ); ?>>
        <?php if ( is_active_sidebar( 'above-nav-right' ) || is_active_sidebar( 'above-nav-left' ) ) : ?>
        <div id="top-bar">
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
                <nav class="navbar navbar-static-top">
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
                                    wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'nav pull-right', 'depth' => 3, 'walker' => new OxyNavWalker() ) );
                                }
                                ?>
                            </nav>
                        </div>
                    </div>
                </nav>
            </header>
            <div id="content" role="main">