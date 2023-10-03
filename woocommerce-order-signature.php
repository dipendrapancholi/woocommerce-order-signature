<?php
/**
 * Plugin Name: Woocommerce Order Signature
 * Plugin URI: https://dharmisoft.com/
 * Description: This plugin allows you to add signature field in the woocommerce checkout page where customer can signature on checkout page and also admin can see the signature image in backend order detail page. Also you can add signature image in various emails.
 * Version: 1.0.0
 * Author: Dipendra Pancholi
 * Author URI: https://profiles.wordpress.org/dipendrapancholi/
 * Text Domain: wooos
 * Domain Path: languages
 * 
 * @package Woocommerce Order Signature
 * @category Core
 * @author Dipendra Pancholi
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Basic plugin definitions
 * 
 * @package Woocommerce Order Signature
 * @since 1.0.0
 */
if( !defined( 'WOO_OS_VERSION' ) ) {
	define( 'WOO_OS_VERSION', '1.0.0' );// Plugin Version
}
if( !defined( 'WOO_OS_DIR' ) ) {
	define( 'WOO_OS_DIR', dirname( __FILE__ ) );// Plugin dir
}
if( !defined( 'WOO_OS_URL' ) ) {
	define( 'WOO_OS_URL', plugin_dir_url( __FILE__ ) );// Plugin url
}
if( !defined( 'WOO_OS_INC_DIR' ) ) {
	define( 'WOO_OS_INC_DIR', WOO_OS_DIR . '/includes' );// Plugin include dir
}
if( !defined( 'WOO_OS_INC_URL' ) ) {
	define( 'WOO_OS_INC_URL', WOO_OS_URL . 'includes' );// Plugin include url
}
if( !defined( 'WOO_OS_ADMIN_DIR' ) ) {
	define( 'WOO_OS_ADMIN_DIR', WOO_OS_INC_DIR . '/admin' );// Plugin admin dir
}
if( !defined( 'WOO_OS_BASENAME' ) ) {
	define( 'WOO_OS_BASENAME', basename( WOO_OS_DIR ) ); // base name
}
if( !defined( 'WOO_OS_META_PREFIX' ) ) {
	define( 'WOO_OS_META_PREFIX', '_woo_os_' );// Plugin Prefix
}
if ( ! defined( 'WOO_OS_SIGNATURE_DIR' ) ) {
	define( 'WOO_OS_SIGNATURE_DIR', ABSPATH . 'wooos-images/' );
}

if( is_multisite() ) {
	if ( ! defined( 'WOO_OS_SIGNATURE_URL' ) ) {
		define( 'WOO_OS_SIGNATURE_URL', trailingslashit( network_site_url() ) . 'wooos-images/' );
	}
} else {
	if ( ! defined( 'WOO_OS_SIGNATURE_URL' ) ) {
		define( 'WOO_OS_SIGNATURE_URL', trailingslashit( site_url() ) . 'wooos-images/' );
	}
}

/**
 * Load Text Domain
 * 
 * This gets the plugin ready for translation.
 * 
 * @package Woocommerce Order Signature
 * @since 1.0.0
 */
function woo_os_load_textdomain() {
	
	// Set filter for plugin's languages directory
	$woo_os_lang_dir	= dirname( plugin_basename( __FILE__ ) ) . '/languages/';
	$woo_os_lang_dir	= apply_filters( 'woo_os_languages_directory', $woo_os_lang_dir );
	
	// Traditional WordPress plugin locale filter
	$locale	= apply_filters( 'plugin_locale',  get_locale(), 'wooos' );
	$mofile	= sprintf( '%1$s-%2$s.mo', 'wooos', $locale );
	
	// Setup paths to current locale file
	$mofile_local	= $woo_os_lang_dir . $mofile;
	$mofile_global	= WP_LANG_DIR . '/' . WOO_OS_BASENAME . '/' . $mofile;
	
	if ( file_exists( $mofile_global ) ) { // Look in global /wp-content/languages/woocommerce-order-signature folder
		load_textdomain( 'wooos', $mofile_global );
	} elseif ( file_exists( $mofile_local ) ) { // Look in local /wp-content/plugins/woocommerce-order-signature/languages/ folder
		load_textdomain( 'wooos', $mofile_local );
	} else { // Load the default language files
		load_plugin_textdomain( 'wooos', false, $woo_os_lang_dir );
	}
}

/**
 * Add plugin action links
 *
 * Adds a Settings, Support and Docs link to the plugin list.
 *
 * @package Woocommerce Order Signature
 * @since 1.0.0
 */
function woo_os_add_plugin_links( $links ) {
	
	$plugin_links = array(
		'<a href="admin.php?page=wc-settings&tab=wooos">' . __( 'Settings', 'wooos' ) . '</a>',
		'<a target="_blank" href="http://support.serveonetech.com/">' . __( 'Support', 'wooos' ) . '</a>',
		'<a target="_blank" href="http://serveonetech.com/documents/woocommerce-order-signature/">' . __( 'Docs', 'wooos' ) . '</a>'
	);
	
	return array_merge( $plugin_links, $links );
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'woo_os_add_plugin_links' );

/**
 * Activation Hook
 *
 * Register plugin activation hook.
 *
 * @package Woocommerce Order Signature
 * @since 1.0.0
 */
register_activation_hook( __FILE__, 'woo_os_install' );

/**
 * Plugin Setup (On Activation)
 * 
 * Does the initial setup,
 * stest default values for the plugin options.
 * 
 * @package Woocommerce Order Signature
 * @since 1.0.0
 */
function woo_os_install() {
	
	//get option for when plugin is activating first time
	$woo_os_set_option = get_option( 'woo_os_set_option' );
	
	if( empty( $woo_os_set_option ) ) { //check plugin version option
		
		//update plugin version to option
		update_option( 'woo_os_set_option', '1.0' );
		
		update_option( 'wooos_enable_signature', 'yes' );
		update_option( 'wooos_enable_required', 'yes' );
		update_option( 'wooos_display_order_detail_page', 'yes' );
		update_option( 'wooos_display_new_order_email', 'yes' );
		update_option( 'wooos_display_customer_on_hold_order_email', 'yes' );
		update_option( 'wooos_display_customer_processing_order_email', 'yes' );
		update_option( 'wooos_display_customer_completed_order_email', 'yes' );
		update_option( 'wooos_sig_width', '100%' );
		update_option( 'wooos_sig_title_color', '#0a018b' );
		update_option( 'wooos_pen_colour', '#000000' );
		update_option( 'wooos_container_background', '#a9a9a9' );
		update_option( 'wooos_signaturepad_background', '#cccccc' );
		update_option( 'wooos_signaturepad_border', '#000000' );
		update_option( 'wooos_signature_btn_color', '#1a1a1a' );
		update_option( 'wooos_sig_btn_text_color', '#ffffff' );
		update_option( 'wooos_sig_remark_text_color', '#000000' );
		update_option( 'wooos_above_text', '' );
		update_option( 'wooos_download_link_html', '<p>Please download agreement from this URL: {download_link}</p>' );
		update_option( 'wooos_custom_css', '' );
	}
}

//add action to load plugin
add_action( 'plugins_loaded', 'woo_os_plugin_loaded' );

/**
 * Load Plugin
 * 
 * Handles to load plugin after
 * dependent plugin is loaded
 * successfully
 * 
 * @package Woocommerce Order Signature
 * @since 1.0.0
 */
function woo_os_plugin_loaded() {
	
	if( class_exists( 'Woocommerce' ) ) { //check Woocommerce is activated or not
		
		//Gets the plugin ready for translation
		woo_os_load_textdomain();
		
		/**
		 * Deactivation Hook
		 *
		 * Register plugin deactivation hook.
		 *
		 * @package Woocommerce Order Signature
		 * @since 1.0.0
		 */
		register_deactivation_hook( __FILE__, 'woo_os_uninstall');
		
		/**
		 * Plugin Setup (On Deactivation)
		 * 
		 * Delete  plugin options.
		 * 
		 * @package Woocommerce Order Signature
		 * @since 1.0.0
		 */
		function woo_os_uninstall() {
		  	global $wpdb;
		}
		
		// Global variables
		global $woo_os_scripts, $woo_os_public, $woo_os_admin;
		
		// Include Misc Functions File
		include_once( WOO_OS_INC_DIR.'/woo-os-misc-functions.php' );
		
		// Script class handles most of script functionalities of plugin
		include_once( WOO_OS_INC_DIR.'/class-woo-os-scripts.php' );
		$woo_os_scripts = new Woo_Os_Scripts();
		$woo_os_scripts->add_hooks();
		
		// Admin class handles most of admin panel functionalities of plugin
		include_once( WOO_OS_INC_DIR.'/class-woo-os-public.php' );
		$woo_os_public = new Woo_Os_Public();
		$woo_os_public->add_hooks();
		
		// Public class handles most of public functionalities of plugin
		include_once( WOO_OS_ADMIN_DIR.'/class-woo-os-admin.php' );
		$woo_os_admin = new Woo_Os_Admin();
		$woo_os_admin->add_hooks();
	}
}