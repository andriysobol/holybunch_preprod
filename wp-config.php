<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'wppreprod');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'rAS!+mU1h6282z6oSZ>sMy N|o2H>GGVvrfUf!~Qs#C2kD,x$GJ8F*mHxV[dDR)O');
define('SECURE_AUTH_KEY',  '<p{TD:|Otx{p-&^Y2xfHfW)O#BMb0PF5?^!SD6G5tydN[Fd(2 PV*^{cxJXgq2RG');
define('LOGGED_IN_KEY',    'SyXI-D4|=4R0NVhDG|q)(,$kOEd]5y=4.2hZPiNzDP|kk.j|(fOnt%|1pr-:vCG+');
define('NONCE_KEY',        '5,*Arl tiM@~W&<n/>By(hgp*K7jQ0(XH|bQWuD<gCf2_CBeu&C]eGZn;Ni~yxZX');
define('AUTH_SALT',        'BV/?7!)G=xH9|XD*q_n1iz||-($tzk9eV_x@sxST]`.Mc1U-Fx@*qf_`sDczj+MR');
define('SECURE_AUTH_SALT', 'aX(+p7CSx}s6-7AiI:`z7gu#+%ta0_~~0=QpH`?w[]V|:Kgcx/)7*#Z+;#s*CTrI');
define('LOGGED_IN_SALT',   'j/v-V{u0yB1*0Iy~|1qff+j5rr?#|e_zk+wzw5EI~6P)i`,doID0{|bW+r5QAtRW');
define('NONCE_SALT',       '6+#!Ijq^jj`Gni~EETGF<L{U3Pj 4H:k6][6 IvL8h^j;|GHYCSR8CXY_a+D3}C?');
define('FORCE_SSL_ADMIN', true);
/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'preprod_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
/*
// Enable WP_DEBUG mode
define('WP_DEBUG', true);

// Enable Debug logging to the /wp-content/debug.log file
define('WP_DEBUG_LOG', true);

// Disable display of errors and warnings
define('WP_DEBUG_DISPLAY', false);
@ini_set('display_errors',0);

// Use dev versions of core JS and CSS files (only needed if you are modifying these core files)
define('SCRIPT_DEBUG', true);
*/
/* Multisite */
define('WP_ALLOW_MULTISITE', true);
define('MULTISITE', true);
define('SUBDOMAIN_INSTALL', false);
define('DOMAIN_CURRENT_SITE', 'localhost'); //holybunch ip: 84.200.83.137
define('PATH_CURRENT_SITE', '/holybunch_prep/');
define('SITE_ID_CURRENT_SITE', 1);
define('BLOG_ID_CURRENT_SITE', 1);
/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');



