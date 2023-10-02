<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Script Class
 * 
 * Handles all the JS and CSS include
 * on fron and backend
 * 
 * @package Woocommerce Order Signature
 * @since 1.0.0
 */

if( !class_exists( 'Woo_Os_Scripts' ) ) { // If class not exist
	
	class Woo_Os_Scripts {
		
		public function __construct() {
			
		}
		
		/**
		 * Enqueue Public Script
		 * 
		 * @package Woocommerce Order Signature
		 * @since 1.0.0
		 */
		public function woo_os_public_scripts() {
			
			// enable signature functionality
			$enable_signature	= woo_os_enable_signature();
			
			$wooos_pen_colour				= get_option( 'wooos_pen_colour' );
			$wooos_signaturepad_background	= get_option( 'wooos_signaturepad_background' );
			$no_screen_resize				= get_option( 'wooos_no_signaturepad_load_onscreen_resize' );
			
			if( $enable_signature && is_checkout() ) { // if signature functionality is enable
				
				// Register & Enqueue jSignature
				wp_register_script( 'woo-os-app', WOO_OS_URL . 'assets/js/woo-os-app.js', array( 'jquery' ), WOO_OS_VERSION, true );
				wp_register_script( 'woo-os-signature-pad', WOO_OS_URL . 'assets/js/woo-os-signature-pad.js', array( 'jquery' ), WOO_OS_VERSION, true );
				wp_register_style( 'woo-os-public-custom-style', WOO_OS_URL . 'assets/css/woo-os-public.css', array(), WOO_OS_VERSION );
				
				wp_enqueue_script( 'woo-os-app' );
				wp_enqueue_script( 'woo-os-signature-pad' );
				wp_enqueue_style( 'woo-os-public-custom-style' );
				
				wp_localize_script( 'woo-os-app','Woo_os_APP',array(
																'ajaxurl'						=> admin_url( 'admin-ajax.php', ( is_ssl() ? 'https' : 'http' ) ),
																'wooos_pen_colour'				=> !empty( $wooos_pen_colour ) ? $wooos_pen_colour : '#000000',
																'wooos_signaturepad_background' => !empty( $wooos_signaturepad_background ) ? $wooos_signaturepad_background : '#cccccc',
																'no_screen_resize' 				=> ( $no_screen_resize == 'yes' ) ? true : false,
															));
			}
		}
		
		/**
		 * Style on head of page
		 * 
		 * Handles style code display when wp head initialize
		 * 
		 * @package Woocommerce Order Signature
	 	 * @since 1.0.1
		 */
		public function woo_os_custom_style() {
			
			$wooos_sig_width	= get_option( 'wooos_sig_width' );
			$custom_css			= '';
			
			if( !empty( $wooos_sig_width ) ) {
				$custom_css .= '#signature-pad{width: ' . $wooos_sig_width . ' !important;}';
			}
			
			$wooos_sig_title_color	= get_option( 'wooos_sig_title_color' );
			if( !empty( $wooos_sig_title_color ) ) {
				$custom_css .= '.m-signature-title{color: ' . $wooos_sig_title_color . ' !important;}';
			}
			
			$wooos_container_background	= get_option( 'wooos_container_background' );
			if( !empty( $wooos_container_background ) ) {
				$custom_css .= '#signature-pad{background-color: ' . $wooos_container_background . ' !important;}';
			}
			
			$wooos_signaturepad_background	= get_option( 'wooos_signaturepad_background' );
			if( !empty( $wooos_signaturepad_background ) ) {
				$custom_css .= '.m-signature-pad--body{background-color: ' . $wooos_signaturepad_background . ' !important;}';
			}
			
			$wooos_signaturepad_border	= get_option( 'wooos_signaturepad_border' );
			if( !empty( $wooos_signaturepad_border ) ) {
				$custom_css .= '.m-signature-pad--body{border-color: ' . $wooos_signaturepad_border . ' !important;}';
			}
			
			$wooos_signature_btn_color	= get_option( 'wooos_signature_btn_color' );
			if( !empty( $wooos_signaturepad_background ) ) {
				$custom_css .= '.wooos-button{background-color: ' . $wooos_signature_btn_color . ' !important;}';
			}
			
			$wooos_sig_btn_text_color	= get_option( 'wooos_sig_btn_text_color' );
			if( !empty( $wooos_sig_btn_text_color ) ) {
				$custom_css .= '.wooos-button{color: ' . $wooos_sig_btn_text_color . ' !important;}';
			}
			
			$wooos_sig_remark_text_color	= get_option( 'wooos_sig_remark_text_color' );
			if( !empty( $wooos_sig_remark_text_color ) ) {
				$custom_css .= '#signature-pad .description{color: ' . $wooos_sig_remark_text_color . ' !important;}';
			}
			
			//Get custom css code
			$custom_css	.= get_option( 'wooos_custom_css' );
			
			if( !empty( $custom_css ) )	{// If custom css code available
				echo '<style type="text/css">' . $custom_css . '</style>';
			}
		}
		
		/**
		 * Add Script Hook
		 * 
		 * Handle to add script hooks
		 * 
		 * @package Woocommerce Order Signature
		 * @since 1.0.0
		 */
		public function add_hooks() {
			
			add_action( 'wp_enqueue_scripts', array( $this, 'woo_os_public_scripts' ) );
			
			//style code on wp head
			add_action( 'wp_head', array( $this, 'woo_os_custom_style' ) );
		}
	}
}