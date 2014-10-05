<?php
// Direct calls to this file are Forbidden when core files are not present 
if ( !current_user_can('manage_options') ) { 
		header('Status: 403 Forbidden');
		header('HTTP/1.1 403 Forbidden');
		exit();
}
	
/*****************************/
// BEGIN HTACCESS FILE WRITING
/*****************************/
function bpsPro_network_domain_check() {
	global $wpdb;
	if ( $wpdb->get_var( "SHOW TABLES LIKE '$wpdb->site'" ) )
		return $wpdb->get_var( "SELECT domain FROM $wpdb->site ORDER BY id ASC LIMIT 1" );
	return false;
}

function bpsPro_get_clean_basedomain() {
	if ( $existing_domain = bpsPro_network_domain_check() )
		return $existing_domain;
	$domain = preg_replace( '|https?://|', '', get_option( 'siteurl' ) );
	if ( $slash = strpos( $domain, '/' ) )
		$domain = substr( $domain, 0, $slash );
	return $domain;
}

	if ( is_multisite() ) {
	
	$hostname				= bpsPro_get_clean_basedomain();
	$slashed_home			= trailingslashit( get_option( 'home' ) );
	$base 					= parse_url( $slashed_home, PHP_URL_PATH );
	$document_root_fix		= str_replace( '\\', '/', realpath( $_SERVER['DOCUMENT_ROOT'] ) );
	$abspath_fix			= str_replace( '\\', '/', ABSPATH );
	$home_path				= 0 === strpos( $abspath_fix, $document_root_fix ) ? $document_root_fix . $base : get_home_path();
	$wp_siteurl_subdir		= preg_replace( '#^' . preg_quote( $home_path, '#' ) . '#', '', $abspath_fix );
	$rewrite_base			= ! empty( $wp_siteurl_subdir ) ? ltrim( trailingslashit( $wp_siteurl_subdir ), '/' ) : '';
	$subdomain_install		= is_subdomain_install();
	$subdir_match			= $subdomain_install ? '' : '([_0-9a-zA-Z-]+/)?';
	$subdir_replacement_01	= $subdomain_install ? '' : '$1';
	$subdir_replacement_12	= $subdomain_install ? '$1' : '$2';
		
		$ms_files_rewriting = '';
		
		if ( is_multisite() && get_site_option( 'ms_files_rewriting' ) ) {
			$ms_files_rewriting = "\n# uploaded files\nRewriteRule ^";
			$ms_files_rewriting .= $subdir_match . "files/(.+) {$rewrite_base}wp-includes/ms-files.php?file={$subdir_replacement_12} [L]" . "\n";
		}
	}

$BPSCustomCodeOptions = get_option('bulletproof_security_options_customcode');
$bps_auto_write_default_file = WP_PLUGIN_DIR . '/bulletproof-security/admin/htaccess/default.htaccess';

$bpsSuccessMessageDef = '<font color="green"><strong>'.__('Success! Your Default Mode Master htaccess file was created successfully!', 'bulletproof-security').'</strong></font><br><font color="red"><strong>'.__('CAUTION: Default Mode should only be activated for testing or troubleshooting purposes. Default Mode does not protect your website with any security protection.', 'bulletproof-security').'</strong></font><br><font color="black"><strong>'.__('To activate Default Mode for troubleshooting, select the Default Mode radio button and click the Activate button to put your website in Default Mode.', 'bulletproof-security').'</strong></font>';

$bpsFailMessageDef = '<font color="red"><strong>'.__('The file ', 'bulletproof-security').$bps_auto_write_default_file.__(' is not writable or does not exist.', 'bulletproof-security').'</strong></font><br><strong>'.__('Check that the file is named default.htaccess and that the file exists in the /bulletproof-security/admin/htaccess master folder. If this is not the problem click ', 'bulletproof-security').'<a href="http://forum.ait-pro.com/" target="_blank">'.__('HERE', 'bulletproof-security').'</a>'.__(' to go the the BulletProof Security Forum.', 'bulletproof-security').'</strong><br>';

if ( ! is_multisite() && $BPSCustomCodeOptions['bps_customcode_wp_rewrite_start'] != '' ) {        
$bpsBeginWP = "# CUSTOM CODE WP REWRITE LOOP START - Your Custom htaccess code will be created here with AutoMagic\n".htmlspecialchars_decode($BPSCustomCodeOptions['bps_customcode_wp_rewrite_start'], ENT_QUOTES)."\n\n";
} else {
$bpsBeginWP = "# WP REWRITE LOOP START
RewriteEngine On
RewriteBase $bps_get_wp_root_default
RewriteRule ^index\.php$ - [L]\n";
}

$bps_default_content_top = "#   BULLETPROOF DEFAULT .HTACCESS      \n
# If you edit the line of code above you will see error messages on the BPS Security Status page
# WARNING!!! THE default.htaccess FILE DOES NOT PROTECT YOUR WEBSITE AGAINST HACKERS
# This is a standard generic htaccess file that does NOT provide any website security
# The DEFAULT .HTACCESS file should be used for testing and troubleshooting purposes only\n
# BEGIN WordPress\n";

$bps_default_content_bottom = "\n<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase $bps_get_wp_root_default
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . $bps_get_wp_root_default"."index.php [L]
</IfModule>\n
# END WordPress";

$bpsMUEndWP = "# END WordPress";

// Network/Multisite all site types and versions
if ( is_multisite() ) {
if ( $BPSCustomCodeOptions['bps_customcode_wp_rewrite_start'] != '' ) {    
$bpsMUSDirTop = "# CUSTOM CODE WP REWRITE LOOP START - Your Custom htaccess code will be created here with AutoMagic\n".htmlspecialchars_decode($BPSCustomCodeOptions['bps_customcode_wp_rewrite_start'], ENT_QUOTES)."\n\n";
} else {
$bpsMUSDirTop = "# WP REWRITE LOOP START
RewriteEngine On
RewriteBase $bps_get_wp_root_default
RewriteRule ^index\.php$ - [L]\n
{$ms_files_rewriting}
# add a trailing slash to /wp-admin
RewriteRule ^{$subdir_match}wp-admin$ {$subdir_replacement_01}wp-admin/ [R=301,L]\n\n";
}

// Network/Multisite all site types and versions
if ( $BPSCustomCodeOptions['bps_customcode_wp_rewrite_end'] != '' ) {    
$bpsMUSDirBottom = "# CUSTOM CODE WP REWRITE LOOP END - Your Custom htaccess code will be created here with AutoMagic\n".htmlspecialchars_decode($BPSCustomCodeOptions['bps_customcode_wp_rewrite_end'], ENT_QUOTES)."\n\n";
} else {
$bpsMUSDirBottom = "RewriteCond %{REQUEST_FILENAME} -f [OR]
RewriteCond %{REQUEST_FILENAME} -d
RewriteRule ^ - [L]
RewriteRule ^{$subdir_match}(wp-(content|admin|includes).*) {$rewrite_base}{$subdir_replacement_12} [L]
RewriteRule ^{$subdir_match}(.*\.php)$ {$rewrite_base}$subdir_replacement_12 [L]
RewriteRule . index.php [L]
# WP REWRITE LOOP END\n";
}
}

// secure.htaccess fwrite content for all WP site types
$bps_get_wp_root_secure = bps_wp_get_root_folder();
$bps_auto_write_secure_file = WP_PLUGIN_DIR . '/bulletproof-security/admin/htaccess/secure.htaccess';

$bpsSuccessMessageSec = '<font color="green"><strong>'.__('Success! Your BulletProof Security Root Master htaccess file was created successfully!', 'bulletproof-security').'</strong></font><br><font color="black"><strong>'.__('You can now Activate BulletProof Mode for your Root folder. Select the Root Folder BulletProof Mode radio button and click the Activate button to activate Root Folder BulletProof Mode.', 'bulletproof-security').'</strong></font>';

$bpsFailMessageSec = '<font color="red"><strong>'.__('The file ', 'bulletproof-security').$bps_auto_write_secure_file.__(' is not writable or does not exist.', 'bulletproof-security').'</strong></font><br><strong>'.__('Check that the file is named secure.htaccess and that the file exists in the /bulletproof-security/admin/htaccess master folder. If this is not the problem click', 'bulletproof-security').' <a href="http://forum.ait-pro.com/" target="_blank">'.__('HERE', 'bulletproof-security').'</a>'.__(' to go the the BulletProof Security Forum.', 'bulletproof-security').'</strong><br>';

$bps_secure_content_top = "#   BULLETPROOF $bps_version >>>>>>> SECURE .HTACCESS     \n
# If you edit the BULLETPROOF $bps_version >>>>>>> SECURE .HTACCESS text above
# you will see error messages on the BPS Security Status page
# BPS is reading the version number in the htaccess file to validate checks
# If you would like to change what is displayed above you
# will need to edit the BPS /includes/functions.php file to match your changes
# If you update your WordPress Permalinks the code between BEGIN WordPress and
# END WordPress is replaced by WP htaccess code.
# This removes all of the BPS security code and replaces it with just the default WP htaccess code
# To restore this file use BPS Restore or activate BulletProof Mode for your Root folder again.\n
# BEGIN WordPress
# IMPORTANT!!! DO NOT DELETE!!! - B E G I N Wordpress above or E N D WordPress - text in this file
# They are reference points for WP, BPS and other plugins to write to this htaccess file.
# IMPORTANT!!! DO NOT DELETE!!! - BPSQSE BPS QUERY STRING EXPLOITS - text
# BPS needs to find the - BPSQSE - text string in this file to validate that your security filters exist\n
# TURN OFF YOUR SERVER SIGNATURE
ServerSignature Off\n
# ADD A PHP HANDLER
# If you are using a PHP Handler add your web hosts PHP Handler below\n\n";

if ( $BPSCustomCodeOptions['bps_customcode_one'] != '' ) {
$phpiniHCode = "# CUSTOM CODE TOP PHP/PHP.INI HANDLER/CACHE CODE - Your Custom htaccess code will be created here with AutoMagic\n".htmlspecialchars_decode($BPSCustomCodeOptions['bps_customcode_one'], ENT_QUOTES)."\n\n";
} else {
$phpiniHCode = "# ADD PHP/php.ini HANDLER CODE
# If you are using PHP/php.ini Handler htaccess code then add your web hosts PHP/php.ini Handler 
# htaccess code to BPS Pro Custom Code. Most Hosts do not have/use/require PHP/php.ini Handler htaccess code\n\n";
}

if ( $BPSCustomCodeOptions['bps_customcode_directory_index'] != '' ) {        
$bps_secure_content_top_two = "# CUSTOM CODE DIRECTORY LISTING/DIRECTORY INDEX - Your Custom htaccess code will be created here with AutoMagic\n".htmlspecialchars_decode($BPSCustomCodeOptions['bps_customcode_directory_index'], ENT_QUOTES)."\n\n";
} else {
$bps_secure_content_top_two = "# DO NOT SHOW DIRECTORY LISTING
# If you are getting 500 Errors when activating BPS then comment out Options -Indexes 
# by adding a # sign in front of it. If there is a typo anywhere in this file you will also see 500 errors.
Options -Indexes\n
# DIRECTORY INDEX FORCE INDEX.PHP
# Use index.php as default directory index file
# index.html will be ignored will not load.
DirectoryIndex index.php index.html /index.php\n\n";
}

if ( $BPSCustomCodeOptions['bps_customcode_server_protocol'] != '' ) {        
$bps_secure_server_protocol = "# CUSTOM CODE BRUTE FORCE LOGIN PAGE PROTECTION - Your Custom htaccess code will be created here with AutoMagic\n".htmlspecialchars_decode($BPSCustomCodeOptions['bps_customcode_server_protocol'], ENT_QUOTES)."\n\n";
} else {
$bps_secure_server_protocol = "# BRUTE FORCE LOGIN PAGE PROTECTION
# PLACEHOLDER ONLY
# See this link: http://forum.ait-pro.com/forums/topic/protect-login-page-from-brute-force-login-attacks/
# for more information before choosing to add this code to BPS Custom Code
# Protects the Login page from SpamBots & Proxies
# that use Server Protocol HTTP/1.0 or a blank User Agent\n\n";
}

if ( $BPSCustomCodeOptions['bps_customcode_error_logging'] != '' ) {        
$bps_secure_error_logging = "# CUSTOM CODE ERROR LOGGING AND TRACKING - Your Custom htaccess code will be created here with AutoMagic\n".htmlspecialchars_decode($BPSCustomCodeOptions['bps_customcode_error_logging'], ENT_QUOTES)."\n\n";
} else {
$bps_secure_error_logging = "# BPS ERROR LOGGING AND TRACKING
# BPS has premade 403 Forbidden, 400 Bad Request and 404 Not Found files that are used 
# to track and log 403, 400 and 404 errors that occur on your website. When a hacker attempts to
# hack your website the hackers IP address, Host name, Request Method, Referering link, the file name or
# requested resource, the user agent of the hacker and the query string used in the hack attempt are logged.
# All BPS log files are htaccess protected so that only you can view them. 
# The 400.php, 403.php and 404.php files are located in /$bps_plugin_dir/bulletproof-security/
# The 400 and 403 Error logging files are already set up and will automatically start logging errors
# after you install BPS and have activated BulletProof Mode for your Root folder.
# If you would like to log 404 errors you will need to copy the logging code in the BPS 404.php file
# to your Theme's 404.php template file. Simple instructions are included in the BPS 404.php file.
# You can open the BPS 404.php file using the WP Plugins Editor.
# NOTE: By default WordPress automatically looks in your Theme's folder for a 404.php template file.\n
ErrorDocument 400 $bps_get_wp_root_secure"."$bps_plugin_dir/bulletproof-security/400.php
ErrorDocument 401 default
ErrorDocument 403 $bps_get_wp_root_secure"."$bps_plugin_dir/bulletproof-security/403.php
ErrorDocument 404 $bps_get_wp_root_secure"."404.php\n\n";
}

$bps_secure_dot_server_files = "# DENY ACCESS TO PROTECTED SERVER FILES AND FOLDERS
# Files and folders starting with a dot: .htaccess, .htpasswd, .errordocs, .logs
RedirectMatch 403 \.(htaccess|htpasswd|errordocs|logs)$\n\n";

if ( $BPSCustomCodeOptions['bps_customcode_admin_includes'] != '' ) {        
$bps_secure_content_wpadmin = "# CUSTOM CODE WP-ADMIN/INCLUDES - Your Custom htaccess code will be created here with AutoMagic\n".htmlspecialchars_decode($BPSCustomCodeOptions['bps_customcode_admin_includes'], ENT_QUOTES)."\n\n";
} else {
$bps_secure_content_wpadmin = "# WP-ADMIN/INCLUDES
RewriteEngine On
RewriteBase $bps_get_wp_root_secure
RewriteRule ^wp-admin/includes/ - [F,L]
RewriteRule !^wp-includes/ - [S=3]
RewriteRule ^wp-includes/[^/]+\.php$ - [F,L]
RewriteRule ^wp-includes/js/tinymce/langs/.+\.php - [F,L]
RewriteRule ^wp-includes/theme-compat/ - [F,L]\n\n";
}

if ( $BPSCustomCodeOptions['bps_customcode_request_methods'] != '' ) {        
$bps_secure_content_mid_top = "# CUSTOM CODE REQUEST METHODS FILTERED - Your Custom htaccess code will be created here with AutoMagic\n".htmlspecialchars_decode($BPSCustomCodeOptions['bps_customcode_request_methods'], ENT_QUOTES)."\n\n";
} else {
$bps_secure_content_mid_top = "\n# REQUEST METHODS FILTERED
# This filter is for blocking junk bots and spam bots from making a HEAD request, but may also block some
# HEAD request from bots that you want to allow in certains cases. This is not a security filter and is just
# a nuisance filter. This filter will not block any important bots like the google bot. If you want to allow
# all bots to make a HEAD request then remove HEAD from the Request Method filter.
# The TRACE, DELETE, TRACK and DEBUG request methods should never be allowed against your website.
RewriteEngine On
RewriteCond %{REQUEST_METHOD} ^(HEAD|TRACE|DELETE|TRACK|DEBUG) [NC]
RewriteRule ^(.*)$ - [F,L]\n\n";
}

$bps_secure_begin_plugins_skip_rules_text = "# PLUGINS/THEMES AND VARIOUS EXPLOIT FILTER SKIP RULES
# IMPORTANT!!! If you add or remove a skip rule you must change S= to the new skip number
# Example: If RewriteRule S=5 is deleted than change S=6 to S=5, S=7 to S=6, etc.\n\n";

// AutoMagic - CUSTOM CODE PLUGIN FIXES
$CustomCodeTwo = '';
if ( $BPSCustomCodeOptions['bps_customcode_two'] != '' ) {
$CustomCodeTwo = "# CUSTOM CODE PLUGIN/THEME SKIP/BYPASS RULES - Your plugins/themes skip/bypass rules .htaccess code will be created here with AutoMagic\n".htmlspecialchars_decode($BPSCustomCodeOptions['bps_customcode_two'], ENT_QUOTES)."\n\n";
}

$bps_secure_content_mid_top2 = "# Adminer MySQL management tool data populate
RewriteCond %{REQUEST_URI} ^$bps_get_wp_root_secure"."$bps_plugin_dir/adminer/ [NC]
RewriteRule . - [S=12]
# Comment Spam Pack MU Plugin - CAPTCHA images not displaying 
RewriteCond %{REQUEST_URI} ^$bps_get_wp_root_secure"."$bps_wpcontent_dir/mu-plugins/custom-anti-spam/ [NC]
RewriteRule . - [S=11]
# Peters Custom Anti-Spam display CAPTCHA Image
RewriteCond %{REQUEST_URI} ^$bps_get_wp_root_secure"."$bps_plugin_dir/peters-custom-anti-spam-image/ [NC] 
RewriteRule . - [S=10]
# Status Updater plugin fb connect
RewriteCond %{REQUEST_URI} ^$bps_get_wp_root_secure"."$bps_plugin_dir/fb-status-updater/ [NC] 
RewriteRule . - [S=9]
# Stream Video Player - Adding FLV Videos Blocked
RewriteCond %{REQUEST_URI} ^$bps_get_wp_root_secure"."$bps_plugin_dir/stream-video-player/ [NC]
RewriteRule . - [S=8]
# XCloner 404 or 403 error when updating settings
RewriteCond %{REQUEST_URI} ^$bps_get_wp_root_secure"."$bps_plugin_dir/xcloner-backup-and-restore/ [NC]
RewriteRule . - [S=7]
# BuddyPress Logout Redirect
RewriteCond %{QUERY_STRING} action=logout&redirect_to=http%3A%2F%2F(.*) [NC]
RewriteRule . - [S=6]
# redirect_to=
RewriteCond %{QUERY_STRING} redirect_to=(.*) [NC]
RewriteRule . - [S=5]
# Login Plugins Password Reset And Redirect 1
RewriteCond %{QUERY_STRING} action=resetpass&key=(.*) [NC]
RewriteRule . - [S=4]
# Login Plugins Password Reset And Redirect 2
RewriteCond %{QUERY_STRING} action=rp&key=(.*) [NC]
RewriteRule . - [S=3]\n\n";

if ( $BPSCustomCodeOptions['bps_customcode_timthumb_misc'] != '' ) {        
$bps_secure_timthumb_misc = "# CUSTOM CODE TIMTHUMB FORBID RFI and MISC FILE SKIP/BYPASS RULE - Your Custom htaccess code will be created here with AutoMagic\n".htmlspecialchars_decode($BPSCustomCodeOptions['bps_customcode_timthumb_misc'], ENT_QUOTES)."\n\n";
} else {
$bps_secure_timthumb_misc = "# TIMTHUMB FORBID RFI and MISC FILE SKIP/BYPASS RULE
# Only Allow Internal File Requests From Your Website
# To Allow Additional Websites Access to a File Use [OR] as shown below.
# RewriteCond %{HTTP_REFERER} ^.*YourWebsite.com.* [OR]
# RewriteCond %{HTTP_REFERER} ^.*AnotherWebsite.com.*
RewriteCond %{QUERY_STRING} ^.*(http|https|ftp)(%3A|:)(%2F|/)(%2F|/)(w){0,3}.?(blogger|picasa|blogspot|tsunami|petapolitik|photobucket|imgur|imageshack|wordpress\.com|img\.youtube|tinypic\.com|upload\.wikimedia|kkc|start-thegame).*$ [NC,OR]
RewriteCond %{THE_REQUEST} ^.*(http|https|ftp)(%3A|:)(%2F|/)(%2F|/)(w){0,3}.?(blogger|picasa|blogspot|tsunami|petapolitik|photobucket|imgur|imageshack|wordpress\.com|img\.youtube|tinypic\.com|upload\.wikimedia|kkc|start-thegame).*$ [NC]
RewriteRule .* index.php [F,L]
RewriteCond %{REQUEST_URI} (timthumb\.php|phpthumb\.php|thumb\.php|thumbs\.php) [NC]
RewriteCond %{HTTP_REFERER} ^.*$bps_get_domain_root.*
RewriteRule . - [S=1]\n\n";
}

if ( $BPSCustomCodeOptions['bps_customcode_bpsqse'] != '' ) {        
$bps_secure_BPSQSE = "# CUSTOM CODE BPSQSE BPS QUERY STRING EXPLOITS - Your Custom htaccess code will be created here with AutoMagic\n".htmlspecialchars_decode($BPSCustomCodeOptions['bps_customcode_bpsqse'], ENT_QUOTES)."\n\n";
} else {
$bps_secure_BPSQSE = "# BEGIN BPSQSE BPS QUERY STRING EXPLOITS
# The libwww-perl User Agent is forbidden - Many bad bots use libwww-perl modules, but some good bots use it too.
# Good sites such as W3C use it for their W3C-LinkChecker. 
# Add or remove user agents temporarily or permanently from the first User Agent filter below.
# If you want a list of bad bots / User Agents to block then scroll to the end of this file.
RewriteCond %{HTTP_USER_AGENT} (havij|libwww-perl|wget|python|nikto|curl|scan|java|winhttp|clshttp|loader) [NC,OR]
RewriteCond %{HTTP_USER_AGENT} (%0A|%0D|%27|%3C|%3E|%00) [NC,OR]
RewriteCond %{HTTP_USER_AGENT} (;|<|>|'|".'"'."|\)|\(|%0A|%0D|%22|%27|%28|%3C|%3E|%00).*(libwww-perl|wget|python|nikto|curl|scan|java|winhttp|HTTrack|clshttp|archiver|loader|email|harvest|extract|grab|miner) [NC,OR]
RewriteCond %{THE_REQUEST} (\?|\*|%2a)+(%20+|\\\\s+|%20+\\\\s+|\\\\s+%20+|\\\\s+%20+\\\\s+)HTTP(:/|/) [NC,OR]
RewriteCond %{THE_REQUEST} etc/passwd [NC,OR]
RewriteCond %{THE_REQUEST} cgi-bin [NC,OR]
RewriteCond %{THE_REQUEST} (%0A|%0D|\\"."\\"."r|\\"."\\"."n) [NC,OR]
RewriteCond %{REQUEST_URI} owssvr\.dll [NC,OR]
RewriteCond %{HTTP_REFERER} (%0A|%0D|%27|%3C|%3E|%00) [NC,OR]
RewriteCond %{HTTP_REFERER} \.opendirviewer\. [NC,OR]
RewriteCond %{HTTP_REFERER} users\.skynet\.be.* [NC,OR]
RewriteCond %{QUERY_STRING} [a-zA-Z0-9_]=http:// [NC,OR]
RewriteCond %{QUERY_STRING} [a-zA-Z0-9_]=(\.\.//?)+ [NC,OR]
RewriteCond %{QUERY_STRING} [a-zA-Z0-9_]=/([a-z0-9_.]//?)+ [NC,OR]
RewriteCond %{QUERY_STRING} \=PHP[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12} [NC,OR]
RewriteCond %{QUERY_STRING} (\.\./|%2e%2e%2f|%2e%2e/|\.\.%2f|%2e\.%2f|%2e\./|\.%2e%2f|\.%2e/) [NC,OR]
RewriteCond %{QUERY_STRING} ftp\: [NC,OR]
RewriteCond %{QUERY_STRING} http\: [NC,OR] 
RewriteCond %{QUERY_STRING} https\: [NC,OR]
RewriteCond %{QUERY_STRING} \=\|w\| [NC,OR]
RewriteCond %{QUERY_STRING} ^(.*)/self/(.*)$ [NC,OR]
RewriteCond %{QUERY_STRING} ^(.*)cPath=http://(.*)$ [NC,OR]
RewriteCond %{QUERY_STRING} (\<|%3C).*script.*(\>|%3E) [NC,OR]
RewriteCond %{QUERY_STRING} (<|%3C)([^s]*s)+cript.*(>|%3E) [NC,OR]
RewriteCond %{QUERY_STRING} (\<|%3C).*embed.*(\>|%3E) [NC,OR]
RewriteCond %{QUERY_STRING} (<|%3C)([^e]*e)+mbed.*(>|%3E) [NC,OR]
RewriteCond %{QUERY_STRING} (\<|%3C).*object.*(\>|%3E) [NC,OR]
RewriteCond %{QUERY_STRING} (<|%3C)([^o]*o)+bject.*(>|%3E) [NC,OR]
RewriteCond %{QUERY_STRING} (\<|%3C).*iframe.*(\>|%3E) [NC,OR]
RewriteCond %{QUERY_STRING} (<|%3C)([^i]*i)+frame.*(>|%3E) [NC,OR] 
RewriteCond %{QUERY_STRING} base64_encode.*\(.*\) [NC,OR]
RewriteCond %{QUERY_STRING} base64_(en|de)code[^(]*\([^)]*\) [NC,OR]
RewriteCond %{QUERY_STRING} GLOBALS(=|\[|\%[0-9A-Z]{0,2}) [OR]
RewriteCond %{QUERY_STRING} _REQUEST(=|\[|\%[0-9A-Z]{0,2}) [OR]
RewriteCond %{QUERY_STRING} ^.*(\(|\)|<|>|%3c|%3e).* [NC,OR]
RewriteCond %{QUERY_STRING} ^.*(\\x00|\\x04|\\x08|\\x0d|\\x1b|\\x20|\\x3c|\\x3e|\\x7f).* [NC,OR]
RewriteCond %{QUERY_STRING} (NULL|OUTFILE|LOAD_FILE) [OR]
RewriteCond %{QUERY_STRING} (\.{1,}/)+(motd|etc|bin) [NC,OR]
RewriteCond %{QUERY_STRING} (localhost|loopback|127\.0\.0\.1) [NC,OR]
RewriteCond %{QUERY_STRING} (<|>|'|%0A|%0D|%27|%3C|%3E|%00) [NC,OR]
RewriteCond %{QUERY_STRING} concat[^\(]*\( [NC,OR]
RewriteCond %{QUERY_STRING} union([^s]*s)+elect [NC,OR]
RewriteCond %{QUERY_STRING} union([^a]*a)+ll([^s]*s)+elect [NC,OR]
RewriteCond %{QUERY_STRING} \-[sdcr].*(allow_url_include|allow_url_fopen|safe_mode|disable_functions|auto_prepend_file) [NC,OR]
RewriteCond %{QUERY_STRING} (;|<|>|'|".'"'."|\)|%0A|%0D|%22|%27|%3C|%3E|%00).*(/\*|union|select|insert|drop|delete|update|cast|create|char|convert|alter|declare|order|script|set|md5|benchmark|encode) [NC,OR]
RewriteCond %{QUERY_STRING} (sp_executesql) [NC]
RewriteRule ^(.*)$ - [F,L]
# END BPSQSE BPS QUERY STRING EXPLOITS\n";
}

$bps_secure_content_mid_bottom = "RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . $bps_get_wp_root_secure"."index.php [L]
# WP REWRITE LOOP END\n";

if ( $BPSCustomCodeOptions['bps_customcode_deny_files'] != '' ) {        
$bps_secure_content_bottom = "# CUSTOM CODE DENY BROWSER ACCESS TO THESE FILES - Your Custom htaccess code will be created here with AutoMagic\n".htmlspecialchars_decode($BPSCustomCodeOptions['bps_customcode_deny_files'], ENT_QUOTES)."\n\n";
} else {
$bps_secure_content_bottom = "\n# DENY BROWSER ACCESS TO THESE FILES 
# wp-config.php, bb-config.php, php.ini, php5.ini, readme.html
# Replace Allow from 88.77.66.55 with your current IP address and remove the  
# pound sign # from in front of the Allow from line of code below to access these
# files directly from your browser.\n
<FilesMatch ".'"'."^(wp-config\.php|php\.ini|php5\.ini|readme\.html|bb-config\.php)".'"'.">
Order allow,deny
Deny from all
#Allow from 88.77.66.55
</FilesMatch>\n\n";
}

$bps_secure_end_wordpress_text = "# IMPORTANT!!! DO NOT DELETE!!! the END WordPress text below
# END WordPress\n\n";

// AutoMagic - CUSTOM CODE BOTTOM
$CustomCodeThree = '';
if ( $BPSCustomCodeOptions['bps_customcode_three'] != '' ) {
$CustomCodeThree = "# CUSTOM CODE BOTTOM HOTLINKING/FORBID COMMENT SPAMMERS/BLOCK BOTS/BLOCK IP/REDIRECT CODE - Your Custom htaccess code will be created here with AutoMagic\n".htmlspecialchars_decode($BPSCustomCodeOptions['bps_customcode_three'], ENT_QUOTES)."\n\n";
} else {
$CustomCodeThree = "# BLOCK HOTLINKING TO IMAGES
# To Test that your Hotlinking protection is working visit http://altlab.com/htaccess_tutorial.html
#RewriteEngine On
#RewriteCond %{HTTP_REFERER} !^https?://(www\.)?add-your-domain-here\.com [NC]
#RewriteCond %{HTTP_REFERER} !^$
#RewriteRule .*\.(jpeg|jpg|gif|bmp|png)$ - [F]\n
# FORBID COMMENT SPAMMERS ACCESS TO YOUR wp-comments-post.php FILE
# This is a better approach to blocking Comment Spammers so that you do not 
# accidentally block good traffic to your website. You can add additional
# Comment Spammer IP addresses on a case by case basis below.
# Searchable Database of known Comment Spammers http://www.stopforumspam.com/\n
<FilesMatch ".'"'."^(wp-comments-post\.php)".'"'.">
Order Allow,Deny
Deny from 46.119.35.
Deny from 46.119.45.
Deny from 91.236.74.
Deny from 93.182.147.
Deny from 93.182.187.
Deny from 94.27.72.
Deny from 94.27.75.
Deny from 94.27.76.
Deny from 193.105.210.
Deny from 195.43.128.
Deny from 198.144.105.
Deny from 199.15.234.
Allow from all
</FilesMatch>\n
# BLOCK MORE BAD BOTS RIPPERS AND OFFLINE BROWSERS
# If you would like to block more bad bots you can get a blacklist from
# http://perishablepress.com/press/2007/06/28/ultimate-htaccess-blacklist/
# You should monitor your site very closely for at least a week if you add a bad bots list
# to see if any website traffic problems or other problems occur.
# Copy and paste your bad bots user agent code list directly below.";
}

// Single/Standard WordPress site type: Create default.htaccess Master File
if ( isset( $_POST['bps-auto-write-default'] ) && current_user_can('manage_options')) {
	check_admin_referer( 'bulletproof_security_auto_write_default' );

		$stringReplace = file_get_contents($bps_auto_write_default_file);

	if ( file_exists($bps_auto_write_default_file) ) {
		$stringReplace = $bps_default_content_top.$bps_default_content_bottom;
		
		if ( file_put_contents( $bps_auto_write_default_file, $stringReplace ) ) {
    		
			echo $bps_topDiv;
			echo $bpsSuccessMessageDef;
			echo $bps_bottomDiv;
		
		} else {
		
			echo $bps_topDiv;
    		echo $bpsFailMessageDef;
			echo $bps_bottomDiv;
		}
	}
}

// Single/Standard WordPress site type: Create secure.htaccess Master File
if ( isset( $_POST['bps-auto-write-secure-root'] ) && current_user_can('manage_options')) {
	check_admin_referer( 'bulletproof_security_auto_write_secure_root' );

		$stringReplace = file_get_contents($bps_auto_write_secure_file);

	if ( file_exists($bps_auto_write_secure_file) ) {
		$stringReplace = $bps_secure_content_top.$phpiniHCode.$bps_secure_content_top_two.$bps_secure_server_protocol.$bps_secure_error_logging.$bps_secure_dot_server_files.$bps_secure_content_wpadmin.$bpsBeginWP.$bps_secure_content_mid_top.$bps_secure_begin_plugins_skip_rules_text.$CustomCodeTwo.$bps_secure_content_mid_top2.$bps_secure_timthumb_misc.$bps_secure_BPSQSE.$bps_secure_content_mid_bottom.$bps_secure_content_bottom.$bps_secure_end_wordpress_text.$CustomCodeThree;
		
		if ( file_put_contents( $bps_auto_write_secure_file, $stringReplace ) ) {
    		
			echo $bps_topDiv;
			echo $bpsSuccessMessageSec;
			echo $bps_bottomDiv;
		
		} else {
		
			echo $bps_topDiv;
    		echo $bpsFailMessageSec;
			echo $bps_bottomDiv;
		}
	}
}

// Network site type: Create default.htaccess Master File
if ( isset( $_POST['bps-auto-write-default-MUSDir'] ) && current_user_can('manage_options')) {
	check_admin_referer( 'bulletproof_security_auto_write_default_MUSDir' );

		$stringReplace = file_get_contents($bps_auto_write_default_file);

	if ( file_exists($bps_auto_write_default_file) ) {
		$stringReplace = $bps_default_content_top.$bpsMUSDirTop.$bpsMUSDirBottom.$bpsMUEndWP;
		
		if ( file_put_contents( $bps_auto_write_default_file, $stringReplace ) ) {
    		
			echo $bps_topDiv;
			echo $bpsSuccessMessageDef;
			echo $bps_bottomDiv;
		
		} else {
		
			echo $bps_topDiv;
    		echo $bpsFailMessageDef;
			echo $bps_bottomDiv;
		}
	}
}

// Network site type: Create secure.htaccess Master File
if ( isset( $_POST['bps-auto-write-secure-root-MUSDir'] ) && current_user_can('manage_options') ) {
	check_admin_referer( 'bulletproof_security_auto_write_secure_root_MUSDir' );

		$stringReplace = file_get_contents($bps_auto_write_secure_file);

	if ( file_exists($bps_auto_write_secure_file) ) {
		$stringReplace = $bps_secure_content_top.$phpiniHCode.$bps_secure_content_top_two.$bps_secure_server_protocol.$bps_secure_error_logging.$bps_secure_dot_server_files.$bpsMUSDirTop.$bps_secure_content_mid_top.$bps_secure_begin_plugins_skip_rules_text.$CustomCodeTwo.$bps_secure_content_mid_top2.$bps_secure_timthumb_misc.$bps_secure_BPSQSE.$bpsMUSDirBottom.$bps_secure_content_bottom.$bps_secure_end_wordpress_text.$CustomCodeThree;
		
		if ( file_put_contents( $bps_auto_write_secure_file, $stringReplace ) ) {
    		
			echo $bps_topDiv;
			echo $bpsSuccessMessageSec;
			echo $bps_bottomDiv;
		
			$Net_options = get_option('bulletproof_security_options_net_correction');  
			$bps_netcorrect_options = 'bulletproof_security_options_net_correction';
			
			$bps_net_automagic = ! $Net_options['bps_net_automagic'] ? 'automagic' : 'automagic';
			$bps_net_activated = ! $Net_options['bps_net_activated'] ? '' : 'activated';

			$NC_Options = array( 
			'bps_net_automagic' => $bps_net_automagic, 
			'bps_net_activated' => $bps_net_activated
			);
	
			foreach( $NC_Options as $key => $value ) {
				update_option('bulletproof_security_options_net_correction', $NC_Options);
			}

		} else {
		
			echo $bps_topDiv;
    		echo $bpsFailMessageSec;
			echo $bps_bottomDiv;
		}
	}
}

/*****************************/
// END HTACCESS FILE WRITING
/*****************************/

?>