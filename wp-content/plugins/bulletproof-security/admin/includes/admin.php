<?php
// Direct calls to this file are Forbidden when core files are not present
if (!function_exists ('add_action')) {
		header('Status: 403 Forbidden');
		header('HTTP/1.1 403 Forbidden');
		exit();
}

function bulletproof_security_admin_init() {
global $wpdb;
$Stable_name = $wpdb->prefix . "bpspro_seclog_ignore";
$Ltable_name = $wpdb->prefix . "bpspro_login_security";

	if ($wpdb->get_var("SHOW TABLES LIKE '$Stable_name'") != $Stable_name) {

	$sql = "CREATE TABLE $Stable_name (
  id mediumint(9) NOT NULL AUTO_INCREMENT,
  time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
  user_agent_bot text NOT NULL,
  UNIQUE KEY id (id)
    );";

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);
	}

	if ($wpdb->get_var("SHOW TABLES LIKE '$Ltable_name'") != $Ltable_name) {

	$sql = "CREATE TABLE $Ltable_name (
  id mediumint(9) NOT NULL AUTO_INCREMENT,
  status VARCHAR(60) DEFAULT '' NOT NULL,
  user_id VARCHAR(60) DEFAULT '' NOT NULL,
  username VARCHAR(60) DEFAULT '' NOT NULL,
  public_name VARCHAR(250) DEFAULT '' NOT NULL,
  email VARCHAR(100) DEFAULT '' NOT NULL,
  role VARCHAR(15) DEFAULT '' NOT NULL,
  human_time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
  login_time VARCHAR(10) DEFAULT '' NOT NULL,
  lockout_time VARCHAR(10) DEFAULT '' NOT NULL,
  failed_logins VARCHAR(2) DEFAULT '' NOT NULL,
  ip_address VARCHAR(45) DEFAULT '' NOT NULL,
  hostname VARCHAR(60) DEFAULT '' NOT NULL,
  request_uri VARCHAR(255) DEFAULT '' NOT NULL,
  UNIQUE KEY id (id)
    );";

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);
	}

	// whitelist BPS DB options 
	register_setting('bulletproof_security_options', 'bulletproof_security_options', 'bulletproof_security_options_validate');
	register_setting('bulletproof_security_options_autolock', 'bulletproof_security_options_autolock', 'bulletproof_security_options_validate_autolock');
	register_setting('bulletproof_security_options_customcode', 'bulletproof_security_options_customcode', 'bulletproof_security_options_validate_customcode');
	register_setting('bulletproof_security_options_customcode_WPA', 'bulletproof_security_options_customcode_WPA', 'bulletproof_security_options_validate_customcode_WPA');
	register_setting('bulletproof_security_options_login_security', 'bulletproof_security_options_login_security', 'bulletproof_security_options_validate_login_security');
	register_setting('bulletproof_security_options_mynotes', 'bulletproof_security_options_mynotes', 'bulletproof_security_options_validate_mynotes');
	register_setting('bulletproof_security_options_maint', 'bulletproof_security_options_maint', 'bulletproof_security_options_validate_maint');
	register_setting('bulletproof_security_options_email', 'bulletproof_security_options_email', 'bulletproof_security_options_validate_email');			

	// Register BPS js
	wp_register_script( 'bps-js', plugins_url('/bulletproof-security/admin/js/bulletproof-security-admin-4.js'));
				
	// Register BPS stylesheet
	wp_register_style('bps-css', plugins_url('/bulletproof-security/admin/css/bulletproof-security-admin-blue-4.css'));

	// Create BPS Backup Folder structure - suppressing errors on activation - errors displayed in HUD
	if( !is_dir (WP_CONTENT_DIR . '/bps-backup')) {
		@mkdir (WP_CONTENT_DIR . '/bps-backup/master-backups', 0755, true);
		@chmod (WP_CONTENT_DIR . '/bps-backup/', 0755);
		@chmod (WP_CONTENT_DIR . '/bps-backup/master-backups/', 0755);
	}
	
	// Create the BPS Backup folder Deny all .htaccess file - recursive will protect all /bps-backup subfolders
	$bps_denyall_htaccess = WP_PLUGIN_DIR . '/bulletproof-security/admin/htaccess/deny-all.htaccess';
	$bps_ARHtaccess = WP_CONTENT_DIR . '/bps-backup/.htaccess';
	
	if ( !file_exists($bps_ARHtaccess) ) {
		@copy($bps_denyall_htaccess, $bps_ARHtaccess);
	}

	// Create logs folder
	if( !is_dir (WP_CONTENT_DIR . '/bps-backup/logs')) {
		@mkdir (WP_CONTENT_DIR . '/bps-backup/logs', 0755, true);
		@chmod (WP_CONTENT_DIR . '/bps-backup/logs/', 0755);
	}

	// Create the Security / HTTP error log in /logs
	$bpsProLog = WP_PLUGIN_DIR . '/bulletproof-security/admin/htaccess/http_error_log.txt';
	$bpsProLogARQ = WP_CONTENT_DIR . '/bps-backup/logs/http_error_log.txt';
	
	if (!file_exists($bpsProLogARQ)) {
		@copy($bpsProLog, $bpsProLogARQ);
	}	

	// Load scripts and styles only on BPS specified pages
	add_action('load-bulletproof-security/admin/options.php', 'bulletproof_security_load_settings_page');
	add_action('load-bulletproof-security/admin/login/login.php', 'bulletproof_security_load_settings_page_login');
}

function bps_network_remove_menu_pages() {
	remove_menu_page('bulletproof-security/admin/options.php');
	remove_submenu_page('bulletproof-security/admin/options.php', 'bulletproof-security/admin/options.php' );
	remove_submenu_page('bulletproof-security/admin/options.php', 'bulletproof-security/admin/login/login.php' );
}

// BPS Menu
function bulletproof_security_admin_menu() {
	if ( is_multisite() && !is_super_admin() ) {

	add_action( 'admin_menu', 'bps_network_remove_menu_pages' );

	} else {

	add_menu_page(__('BulletProof Security ~ htaccess Core', 'bulletproof-security'), __('BPS Security', 'bulletproof-security'), 'manage_options', 'bulletproof-security/admin/options.php', '', plugins_url('bulletproof-security/admin/images/bps-icon-small.png'));
	add_submenu_page('bulletproof-security/admin/options.php', __('BulletProof Security ~ htaccess Core', 'bulletproof-security'), __('htaccess Core', 'bulletproof-security'), 'manage_options', 'bulletproof-security/admin/options.php' );
	add_submenu_page('bulletproof-security/admin/options.php', __('Login Security ~ BPS Pro Login Security', 'bulletproof-security'), __('Login Security', 'bulletproof-security'), 'manage_options', 'bulletproof-security/admin/login/login.php' );
}}

// Add Plugins here that load their js and css scripts throughout other plugins pages and the WP Dashboard
// to block these scripts from loading in BPS Pro plugin pages and breaking BPS Pro scripts.
// Remember to add the global
//$plugin_var1 = 'smart-slideshow-widget/smart-slideshow-widget.php';
$plugin_var1 = 'all-in-one-webmaster/all-in-one-webmaster.php';
$plugin_var2 = 'facebook-members/facebook-members.php';
$plugin_var3 = 'easyrotator-for-wordpress/easyrotator.php';
$return_var = in_array( $plugin_var1 || $plugin_var2 || $plugin_var3, apply_filters('active_plugins', get_option('active_plugins')));

// Loads Settings for H-Core
function bulletproof_security_load_settings_page() {
global $bulletproof_security, $plugin_var1, $plugin_var2, $plugin_var3, $return_var;
	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-ui-tabs');
	wp_enqueue_script('jquery-ui-dialog');
	wp_enqueue_script('jquery-form');
	wp_enqueue_script('jquery-ui-accordion');
	wp_enqueue_script('jquery-effects-core');
	wp_enqueue_script('jquery-effects-blind');
	wp_enqueue_script('jquery-effects-explode');
	wp_enqueue_script('bps-js');
	// Engueue BPS stylesheet
	wp_enqueue_style('bps-css', plugins_url('/bulletproof-security/admin/css/bulletproof-security-admin-blue-4.css'));
	
	if ( $return_var == 1) { // 1 equals active	
	// Block SSW from loading its scripts in BPS Pro pages and breaking BPS Pro scripts/menus/etc
	//wp_dequeue_script( 'jQuery-UI-Effects', plugins_url('/smart-slideshow-widget/js/jquery-ui.min.js') );
	//wp_dequeue_script( 'SSW', plugins_url('/smart-slideshow-widget/js/smart-slideshow-widget.js') );

	// Block All in One Webmaster plugin from loading its scripts in BPS Pro pages and breaking BPS Pro scripts/menus/etc
	wp_dequeue_script( 'aiow-plugin-script3', plugins_url('/all-in-one-webmaster/js/myscript.js') );
	wp_dequeue_script( 'aiow-plugin-script4', plugins_url('/all-in-one-webmaster/js/jquery.powertip.js') );  	
	wp_dequeue_style( 'aiow-plugin-css', plugins_url('/all-in-one-webmaster/css/jquery-ui.css') );
	wp_dequeue_style( 'aiow-tip-plugin-css', plugins_url('/all-in-one-webmaster/css/jquery.powertip.css') );		
	wp_dequeue_style( 'aiow-member-plugin-css', plugins_url('/all-in-one-webmaster/css/all-in-one-webmaster.css') );

	// Block Facebook Members plugin from loading its scripts in BPS Pro pages and breaking BPS Pro scripts/menus/etc
	wp_dequeue_script( 'facebook-plugin-script4', plugins_url('/facebook-members/js/jquery.powertip.js') );
	wp_dequeue_script( 'facebook-plugin-script3', plugins_url('/facebook-members/js/myscript.js') );  	
	wp_dequeue_style( 'facebook-plugin-css', plugins_url('/facebook-members/css/jquery-ui.css') );
	wp_dequeue_style( 'facebook-tip-plugin-css', plugins_url('/facebook-members/css/jquery.powertip.css') );	
	wp_dequeue_style( 'facebook-member-plugin-css', plugins_url('/facebook-members/css/facebook-members.css') );

	// Block EasyRotator for WordPress plugin from loading its scripts in BPS Pro pages and breaking BPS Pro scripts/menus/etc
	wp_dequeue_style( 'easyrotator-plugin-admin-css', plugins_url('/easyrotator-for-wordpress/css/easyrotator_admin.css') );	
	wp_dequeue_style( 'jquery-ui-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css' );
	}
}

// Loads Settings for BPS Login Security
function bulletproof_security_load_settings_page_login() {
global $bulletproof_security, $plugin_var1, $plugin_var2, $plugin_var3, $return_var;
	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-ui-tabs');
	wp_enqueue_script('jquery-ui-dialog');
	wp_enqueue_script('jquery-form');
	wp_enqueue_script('jquery-ui-accordion');
	wp_enqueue_script('jquery-effects-core');
	wp_enqueue_script('jquery-effects-blind');
	wp_enqueue_script('jquery-effects-explode');
	wp_enqueue_script('bps-js');
	// Enqueue BPS stylesheet
	wp_enqueue_style('bps-css', plugins_url('/bulletproof-security/admin/css/bulletproof-security-admin-blue-4.css'));	
	
	if ( $return_var == 1) { // 1 equals active	
	// Block SSW from loading its scripts in BPS Pro pages and breaking BPS Pro scripts/menus/etc
	//wp_dequeue_script( 'jQuery-UI-Effects', plugins_url('/smart-slideshow-widget/js/jquery-ui.min.js') );
	//wp_dequeue_script( 'SSW', plugins_url('/smart-slideshow-widget/js/smart-slideshow-widget.js') );

	// Block All in One Webmaster plugin from loading its scripts in BPS Pro pages and breaking BPS Pro scripts/menus/etc
	wp_dequeue_script( 'aiow-plugin-script3', plugins_url('/all-in-one-webmaster/js/myscript.js') );
	wp_dequeue_script( 'aiow-plugin-script4', plugins_url('/all-in-one-webmaster/js/jquery.powertip.js') );  	
	wp_dequeue_style( 'aiow-plugin-css', plugins_url('/all-in-one-webmaster/css/jquery-ui.css') );
	wp_dequeue_style( 'aiow-tip-plugin-css', plugins_url('/all-in-one-webmaster/css/jquery.powertip.css') );		
	wp_dequeue_style( 'aiow-member-plugin-css', plugins_url('/all-in-one-webmaster/css/all-in-one-webmaster.css') );

	// Block Facebook Members plugin from loading its scripts in BPS Pro pages and breaking BPS Pro scripts/menus/etc
	wp_dequeue_script( 'facebook-plugin-script4', plugins_url('/facebook-members/js/jquery.powertip.js') );
	wp_dequeue_script( 'facebook-plugin-script3', plugins_url('/facebook-members/js/myscript.js') );  	
	wp_dequeue_style( 'facebook-plugin-css', plugins_url('/facebook-members/css/jquery-ui.css') );
	wp_dequeue_style( 'facebook-tip-plugin-css', plugins_url('/facebook-members/css/jquery.powertip.css') );	
	wp_dequeue_style( 'facebook-member-plugin-css', plugins_url('/facebook-members/css/facebook-members.css') );

	// Block EasyRotator for WordPress plugin from load scripts in BPS Pro pages and breaking BPS Pro scripts/menus/etc
	wp_dequeue_style( 'easyrotator-plugin-admin-css', plugins_url('/easyrotator-for-wordpress/css/easyrotator_admin.css') );	
	wp_dequeue_style( 'jquery-ui-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css' );
	}
}

function bulletproof_security_install() {
global $bulletproof_security, $bps_version;
$previous_install = get_option('bulletproof_security_options');
	
	if ( $previous_install ) {
	if ( version_compare($previous_install['version'], $bps_version, '<') )
	remove_role('denied');
	}
}

// Deactivation - remove/delete nothing at this point
function bulletproof_security_deactivation() {
// nothing needs to removed on deactivation for now
}

// Partial Uninstall - BPS later version will have the option of complete removal in addition to a BPS Pro upgrade uninstall
// Currently options and files are not deleted on uninstall as a courtesy to BPS Pro customers
function bulletproof_security_uninstall() {
	require_once( ABSPATH . 'wp-admin/includes/plugin.php');
	$options = get_option('bulletproof_security_options');
	delete_option('bulletproof_security_options');
}

/* 
// This uninstall function will completely remove BPS files, DB Options & Tables
function bulletproof_security_uninstall() {
global $wpdb, $current_user;
require_once( ABSPATH . 'wp-admin/includes/plugin.php');

$user_id = $current_user->ID;
$Stable_name = $wpdb->prefix . "bpspro_seclog_ignore";
$Ltable_name = $wpdb->prefix . "bpspro_login_security";
$RootHtaccess = ABSPATH . '.htaccess';
$RootHtaccessBackup = WP_CONTENT_DIR . '/bps-backup/master-backups/root.htaccess';
$wpadminHtaccess = ABSPATH . 'wp-admin/.htaccess';
$wpadminHtaccessBackup = WP_CONTENT_DIR . '/bps-backup/master-backups/wpadmin.htaccess';
$options = get_option('bulletproof_security_options');

	if ( file_exists($RootHtaccess) ) {
		copy($RootHtaccess, $RootHtaccessBackup);
	}
	if ( file_exists($wpadminHtaccess) ) {
		copy($wpadminHtaccess, $wpadminHtaccessBackup);
	}

	delete_option('bulletproof_security_options');
	delete_option('bulletproof_security_options_customcode');
	delete_option('bulletproof_security_options_customcode_WPA');
	delete_option('bulletproof_security_options_maint');
	delete_option('bulletproof_security_options_mynotes');
	delete_option('bulletproof_security_options_email');
	delete_option('bulletproof_security_options_autolock');
	delete_option('bulletproof_security_options_login_security');
	// will be adding this new upgrade notice option later
	// delete_option('bulletproof_security_options_upgrade_notice');	
	$wpdb->query("DROP TABLE IF EXISTS $Stable_name");
	$wpdb->query("DROP TABLE IF EXISTS $Ltable_name");
	delete_user_meta($user_id, 'bps_ignore_iis_notice');
	delete_user_meta($user_id, 'bps_ignore_sucuri_notice');
	delete_user_meta($user_id, 'bps_ignore_BLC_notice');
	delete_user_meta($user_id, 'bps_ignore_PhpiniHandler_notice');
	delete_user_meta($user_id, 'bps_ignore_Permalinks_notice');
	delete_user_meta($user_id, 'bps_brute_force_login_protection_notice');
	delete_user_meta($user_id, 'bps_speed_boost_cache_notice');
	@unlink($RootHtaccess);
	@unlink($wpadminHtaccess);
}
*/

// Validate BPS options 
function bulletproof_security_options_validate($input) {  
	$options = get_option('bulletproof_security_options');  
	$options['bps_blank'] = wp_filter_nohtml_kses($input['bps_blank']);
			
	return $options;  
}

// Validate BPS options - Maintenance Mode Form 
function bulletproof_security_options_validate_maint($input) {  
	$options = get_option('bulletproof_security_options_maint');  
	$options['bps-site-title'] = wp_filter_nohtml_kses($input['bps-site-title']);
	$options['bps-message-1'] = wp_filter_nohtml_kses($input['bps-message-1']);
	$options['bps-message-2'] = wp_filter_nohtml_kses($input['bps-message-2']);
	$options['bps-retry-after'] = wp_filter_nohtml_kses($input['bps-retry-after']);
	$options['bps-background-image'] = wp_filter_nohtml_kses($input['bps-background-image']);
		
	return $options;  
}

// Validate BPS options - Options.php - Edit/Uploads/Downloads page - Root .htaccess file AutoLock 
function bulletproof_security_options_validate_autolock($input) {  
	$options = get_option('bulletproof_security_options_autolock');  
	$options['bps_root_htaccess_autolock'] = wp_filter_nohtml_kses($input['bps_root_htaccess_autolock']);
		
	return $options;  
}

// Validate BPS options - BPS Custom Code - Root .htaccess
function bulletproof_security_options_validate_customcode($input) {  
	$options = get_option('bulletproof_security_options_customcode');  
	$options['bps_customcode_one'] = esc_html($input['bps_customcode_one']);
	$options['bps_customcode_directory_index'] = esc_html($input['bps_customcode_directory_index']);
	$options['bps_customcode_error_logging'] = esc_html($input['bps_customcode_error_logging']);
	$options['bps_customcode_admin_includes'] = esc_html($input['bps_customcode_admin_includes']);
	$options['bps_customcode_wp_rewrite_start'] = esc_html($input['bps_customcode_wp_rewrite_start']);
	$options['bps_customcode_request_methods'] = esc_html($input['bps_customcode_request_methods']);
	$options['bps_customcode_two'] = esc_html($input['bps_customcode_two']);
	$options['bps_customcode_timthumb_misc'] = esc_html($input['bps_customcode_timthumb_misc']);
	$options['bps_customcode_bpsqse'] = esc_html($input['bps_customcode_bpsqse']);
	$options['bps_customcode_deny_files'] = esc_html($input['bps_customcode_deny_files']);
	$options['bps_customcode_three'] = esc_html($input['bps_customcode_three']);

	return $options;  
}

// Validate BPS options - BPS Custom Code - WP-admin .htaccess
function bulletproof_security_options_validate_customcode_WPA($input) {  
	$options = get_option('bulletproof_security_options_customcode_WPA');  
	$options['bps_customcode_deny_files_wpa'] = esc_html($input['bps_customcode_deny_files_wpa']);
	$options['bps_customcode_one_wpa'] = esc_html($input['bps_customcode_one_wpa']);
	$options['bps_customcode_two_wpa'] = esc_html($input['bps_customcode_two_wpa']);
	$options['bps_customcode_bpsqse_wpa'] = esc_html($input['bps_customcode_bpsqse_wpa']);		
	
	return $options;  
}

// Validate BPS options - BPS "My Notes" settings 
function bulletproof_security_options_validate_mynotes($input) {  
	$options = get_option('bulletproof_security_options_mynotes');  
	$options['bps_my_notes'] = esc_html($input['bps_my_notes']);
		
	return $options;  
}

// Validate BPS options - Login Security & Monitoring
function bulletproof_security_options_validate_login_security($input) {  
	$BPSoptions = get_option('bulletproof_security_options_login_security');  
	$BPSoptions['bps_max_logins'] = trim(wp_filter_nohtml_kses($input['bps_max_logins']));
	$BPSoptions['bps_lockout_duration'] = trim(wp_filter_nohtml_kses($input['bps_lockout_duration']));
	$BPSoptions['bps_manual_lockout_duration'] = trim(wp_filter_nohtml_kses($input['bps_manual_lockout_duration']));
	$BPSoptions['bps_max_db_rows_display'] = trim(wp_filter_nohtml_kses($input['bps_max_db_rows_display']));
	$BPSoptions['bps_login_security_OnOff'] = wp_filter_nohtml_kses($input['bps_login_security_OnOff']);
	$BPSoptions['bps_login_security_logging'] = wp_filter_nohtml_kses($input['bps_login_security_logging']);
	$BPSoptions['bps_login_security_errors'] = wp_filter_nohtml_kses($input['bps_login_security_errors']);
	$BPSoptions['bps_login_security_pw_reset'] = wp_filter_nohtml_kses($input['bps_login_security_pw_reset']);		
	
	return $BPSoptions;  
}

// Validate BPS options - BPS Free Email Alerts
function bulletproof_security_options_validate_email($input) {  
	$options = get_option('bulletproof_security_options_email');  
	$options['bps_send_email_to'] = trim(wp_filter_nohtml_kses($input['bps_send_email_to']));
	$options['bps_send_email_from'] = trim(wp_filter_nohtml_kses($input['bps_send_email_from']));
	$options['bps_send_email_cc'] = trim(wp_filter_nohtml_kses($input['bps_send_email_cc']));
	$options['bps_send_email_bcc'] = trim(wp_filter_nohtml_kses($input['bps_send_email_bcc']));
	$options['bps_login_security_email'] = wp_filter_nohtml_kses($input['bps_login_security_email']);
	//$options['bps_upgrade_email'] = wp_filter_nohtml_kses($input['bps_upgrade_email']);		
	
	return $options;  
}
?>