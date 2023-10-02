<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Public Collection Pages Class
 * 
 * Handles all the different features and functions
 * for the front end pages.
 * 
 * @package Woocommerce Order Signature
 * @since 1.0.0
 */

use Dompdf\Dompdf;

if( !class_exists( 'Woo_Os_Public' ) ) { // If class not exist
	
	class Woo_Os_Public {
		
		public function __construct() {
			
		}
		
		/**
		 * Validate Signature Field
		 * 
		 * Handle to validate signature field on checkout page
		 * 
		 * @package Woocommerce Order Signature
		 * @since 1.0.0
		 */
		public function woo_os_checkout_field_process() {
			
			// enable signature functionality
			$enable_signature	= woo_os_enable_signature();
			
			// signature is required or not
			$signature_required	= get_option( 'wooos_enable_required' );

			global $woocommerce;

			$enable_product_wise = get_option( 'wooos_enable_product_wise_agreement' );

			if( $enable_product_wise == 'yes' ) {

				$items = $woocommerce->cart->get_cart();
				$show_signature = false;

				foreach( $items as $item => $values ) { 

					$woo_os_enable = get_post_meta( $values['product_id'] , 'woo_os_enable_signature', true );

					if ( $woo_os_enable == 'on' ) {
						$show_signature = true; break;
					}
				}

				if( !$show_signature ) return;
			}

			if( !empty( $enable_signature ) && $signature_required == 'yes' ) {
				
				// get signature error message
				$signature_error_msg	= woo_os_display_message( 'signature_error_msg' );
				
				if ( empty( $_POST['_wooos_hidden_signature'] ) ) { // empty signature field
					wc_add_notice( $signature_error_msg, 'error' );
				}
			}
		}
		
		/**
		 * Add Signature Field
		 * 
		 * Handle to add signature field on checkout page
		 * 
		 * @package Woocommerce Order Signature
		 * @since 1.0.0
		 */
		public function woo_os_checkout_fields() { 

			global $woocommerce;

			$items = $woocommerce->cart->get_cart();
			$show_signature = false;

			foreach( $items as $item => $values ) { 

				$woo_os_enable = get_post_meta( $values['product_id'] , 'woo_os_enable_signature', true );

				if ( $woo_os_enable == 'on' ) {
					$show_signature = true; break;
				}
			}

			// enable signature functionality
			$enable_signature						= woo_os_enable_signature();
			$signature_remember_msg					= woo_os_display_message( 'signature_remember_msg' );
			$clear_btn_text							= woo_os_display_message( 'clear_btn_text' );
			$save_btn_text							= woo_os_display_message( 'save_btn_text' );
			$wooos_above_text						= woo_os_display_message( 'wooos_above_text' );
			$wooos_enable_product_wise_agreement	= ( get_option( 'wooos_enable_product_wise_agreement' ) ) ? get_option( 'wooos_enable_product_wise_agreement' ) : 'no';

			if( $wooos_enable_product_wise_agreement == 'yes' && !$show_signature ) {
				return;
			}

			if( $enable_signature ) { // if signature functionality is enable ?>
<!--				<div class="overlay-mobile"></div>-->
				<div id="signature-pad" class="m-signature-pad">
					<p class="m-signature-title"><?php echo woo_os_signature_title();?></p>
					<?php if( !empty( $wooos_above_text ) ) {
						?><p><?php echo $wooos_above_text;?></p><?php
					}?>
					<div class="m-signature-pad--body">
						<canvas></canvas>
					</div>
					<div class="m-signature-pad--footer">
						<div id="woo-os-signature-saved"><?php echo __( 'Signature Saved!', 'wooos' );?></div>
						<input type="hidden" value="" name="_wooos_hidden_signature" id="wooos_hidden_signature" />
						<button type="button" class="button  wooos-button" data-action="clear"><?php echo $clear_btn_text;?></button>
						<button type="button" class="button wooos-save wooos-button" data-action="save"><?php echo $save_btn_text;?></button>
					</div>
					<i class="description"><?php echo $signature_remember_msg;?></i>
				</div><?php
			}
		}
		
		/**
		 * Save Signature Field
		 * 
		 * Handle to save signature field on checkout page
		 * 
		 * @package Woocommerce Order Signature
		 * @since 1.0.0
		 */
		public function woo_os_checkout_field_update_order_meta( $order_id ) {
			
			// enable signature functionality
			$enable_signature	= woo_os_enable_signature();
			
			if( $enable_signature ) { // if signature functionality is enable
				
				if ( ! empty( $_POST['_wooos_hidden_signature'] ) ) {
					update_post_meta( $order_id, '_wooos_hidden_signature', sanitize_text_field( $_POST['_wooos_hidden_signature'] ) );
				}
			}
		}
		
		/**
		 * Signature Image Display On Order Detail page
		 * 
		 * Handle to display signature on  order detail page.
		 * 
		 * @package Woocommerce Order Signature
		 * @since 1.0.0
		 */
		public function woo_os_woocommerce_order_details_after_order_table( $order ) {

			// enable signature functionality
			$enable_signature	= woo_os_enable_signature();
			
			// signature is enable for this template or not
			$display_enable	= get_option( 'wooos_display_order_detail_page' );
			
			if( $enable_signature && $display_enable == 'yes' ) { // if signature functionality is enable
				
				if( WOOCOMMERCE_VERSION < '3.0.0' ) {
					$order_id	= isset( $order->id ) ? $order->id : '';
				} else {
					$order_id	= $order->get_id();
				}

				/****/
				$wooos_enable_product_wise_agreement	= ( get_option( 'wooos_enable_product_wise_agreement' ) ) ? get_option( 'wooos_enable_product_wise_agreement' ) : 'no';

				if( $wooos_enable_product_wise_agreement == 'yes' ) {

					$order = wc_get_order( $order_id ); //getting order Object
					$show_signature = false;

					foreach ( $order->get_items() as $item_id => $item ) {

						$product = $item->get_product();

			            // Check if the product exists.
			            if (is_object($product)) {

			            	$product_id			= $product->get_id();
							$woo_os_enabled		= get_post_meta( $product_id, 'woo_os_enable_signature', true );

							if( $woo_os_enabled == 'on' ) {
								$show_signature = true;
								break;
							}
						}
					}

					if( $show_signature ) {
						// display download links product wise
						woo_os_download_links( $order_id, true );
					}
				}
				/****/

				// display signature
				woo_os_display_signature( $order_id, true, false );
			}
		}
		
		/**
		 * Signature Image Display In Email
		 * 
		 * Handle to display signature in email
		 * 
		 * @package Woocommerce Order Signature
		 * @since 1.0.0
		 */
		public function woo_os_woocommerce_email_after_order_table( $order, $sent_to_admin, $plain_text, $email ) {
			
			// enable signature functionality
			$enable_signature	= woo_os_enable_signature();
			
			if( $enable_signature && $plain_text != 1 ) { // if signature functionality is enable
				
				if( WOOCOMMERCE_VERSION < '3.0.0' ) {
					
					// get order id
					$order_id	= isset( $order->id ) ? $order->id : '';
				} else {
					
					// get order id
					$order_id	= $order->get_id();
				}
				
				// get email type
				$email_type		= isset( $email->id ) ? $email->id : '';
				
				// signature is enable for this template or not
				$display_enable	= get_option( 'wooos_display_' . $email_type . '_email' );
				
				if( !empty( $order_id ) && $display_enable == 'yes' ) { // if order id there and enable for this email template
					
					// get image string
					$imgStr		= get_post_meta( $order_id, '_wooos_hidden_signature', true );
					
					if( !empty( $imgStr ) ) { // IF image string is empty
						
						// display signature image
						woo_os_display_signature( $order_id, true, true );
						
					}
				}
			}
		}
		
		/**
		 * Display Agreement PDF
		 * 
		 * Handle to display agreement PDF
		 * 
		 * @package Woocommerce Order Signature
		 * @since 1.0.4
		 */
		public function woo_os_agreement_pdf_view() {
			
			if( !empty( $_GET['wooos-agreement'] ) && $_GET['wooos-agreement'] == 'download' && !empty( $_GET['order_id'] ) ) {
				
				// Include autoloader
				require_once( WOO_OS_INC_DIR . '/dompdf/autoload.inc.php' );
				
				// Instantiate and use the dompdf class
				$dompdf 		= new Dompdf();
				
				// Get order id
				$order_id		= $_GET['order_id'];

				$wooos_enable_product_wise_agreement	= ( get_option( 'wooos_enable_product_wise_agreement' ) ) ? get_option( 'wooos_enable_product_wise_agreement' ) : 'no';
				/***/
				$product_id			= isset( $_GET['product_id'] ) ? $_GET['product_id'] : '';
				$woo_os_enabled		= 'off';
				$product_agreement	= '';

				if( $wooos_enable_product_wise_agreement == 'yes' && !empty( $product_id ) ) {

					$woo_os_enabled		= get_post_meta( $product_id, 'woo_os_enable_signature', true );
					$product_agreement  = get_post_meta( $product_id, 'woo_os_agreement_content', true );

					$order = wc_get_order( $order_id ); //getting order Object
	
					foreach ( $order->get_items() as $item_id => $item ) {
	
						$product = $item->get_product();
	
			            // Check if the product exists.
			            if (is_object($product)) {
	
			            	if( $product->get_id() == $product_id ) {
	
			            		$product_name	= $item['name']; //$product->get_title();
								$product_price	= $product->get_price();
								$product_sku	= $product->get_sku();
								$product_qty	= $item['quantity'];
			            	}
			            }
					}
				}
				/***/

				// Get buyer info
				$buyer_info		= woo_os_get_buyer_information( $order_id );
				
				// Signature image
				$signature_image	= woo_os_display_signature( $order_id, false, true, '', 'no' );
				
				// Replace signature path
				$signature_image	= str_replace( WOO_OS_SIGNATURE_URL, WOO_OS_SIGNATURE_DIR, $signature_image );
				
				// Agreement html
				$agreement_html	= ( $woo_os_enabled == 'on' && !empty( $product_agreement ) ) ? $product_agreement : get_option( 'wooos_agreement_text' );
				
				// Agreement HTML shortcode replace
				$agreement_html	= str_replace( '{customer_name}', $buyer_info['first_name'] . ' ' . $buyer_info['last_name'], $agreement_html );
				$agreement_html	= str_replace( '{billing_address}', $buyer_info['address_1'], $agreement_html );
				$agreement_html	= str_replace( '{phone}', $buyer_info['phone'], $agreement_html );
				$agreement_html	= str_replace( '{city}', $buyer_info['city'], $agreement_html );
				$agreement_html	= str_replace( '{state}', $buyer_info['state'], $agreement_html );
				$agreement_html	= str_replace( '{zip}', $buyer_info['postcode'], $agreement_html );
				$agreement_html	= str_replace( '{customer_email}', $buyer_info['email'], $agreement_html );
				$agreement_html	= str_replace( '{purchase_date}', $buyer_info['order_date'], $agreement_html );
				$agreement_html	= str_replace( '{customer_signature}', $signature_image, $agreement_html );

				if( $wooos_enable_product_wise_agreement == 'yes' && !empty( $product_id ) ) { // if enabled option for product wise download

					$agreement_html	= str_replace( '{product_name}', $product_name, $agreement_html );
					$agreement_html	= str_replace( '{product_price}', $product_price, $agreement_html );
					$agreement_html	= str_replace( '{product_sku}', $product_sku, $agreement_html );
					$agreement_html	= str_replace( '{product_qty}', $product_qty, $agreement_html );
				}

				// Apply Filter
				$agreement_html	= apply_filters( 'woo_os_agreement_html_content', $agreement_html, $order_id );

				// get site url
				$site_url			= trailingslashit( site_url() );
				
				//get absolute path
				$abspath			= ABSPATH;
				
				$dom_doc	= new DOMDocument();
				$dom_doc->loadHTML( $agreement_html );
				
				$tags	= $dom_doc->getElementsByTagName( 'img' );
				
				foreach( $tags as $tag ) {
					$image_src		= $tag->getAttribute( 'src' );
					$image_src_new	= str_replace( $site_url, $abspath, $image_src );
					
					$agreement_html	= str_replace( $image_src, $image_src_new, $agreement_html );
				}
				
				// load html
				$dompdf->loadHtml( $agreement_html );
				
				// (Optional) Setup the paper size and orientation
				$dompdf->setPaper( 'A4', 'portrait' ); // landscape, portrait
				
				// Render the HTML as PDF
				$dompdf->render();
				
				// Output the generated PDF (1 = download and 0 = preview)
				$dompdf->stream( 'codex', array( 'Attachment' => 0 ) );
				
				exit;
			}
		}
		
		/**
		 * Add Public Hook
		 * 
		 * Handle to add public hooks
		 * 
		 * @package Woocommerce Order Signature
		 * @since 1.0.0
		 */
		public function add_hooks() {
			
			// checkout process
			add_action( 'woocommerce_checkout_process', array( $this, 'woo_os_checkout_field_process' ) );
			
			// add custom fields to checkout page
			add_action( 'woocommerce_after_order_notes', array( $this, 'woo_os_checkout_fields' ) );
			
			// save checkout custom meta
			add_action( 'woocommerce_checkout_update_order_meta', array( $this, 'woo_os_checkout_field_update_order_meta' ), 10, 1 );
			
			// add action to add an extra detail on order confirmation page
			add_action( 'woocommerce_order_details_after_order_table', array( $this, 'woo_os_woocommerce_order_details_after_order_table' ),10, 1 );
			
			//add action to add custom detail in woocommerce email for customer and admin.
			add_action( 'woocommerce_email_after_order_table',array( $this,  'woo_os_woocommerce_email_after_order_table' ), 10, 4 );
			
			// display Agreement PDF ( V 1.0.4 )
			add_action( 'init', array( $this, 'woo_os_agreement_pdf_view' ), 100 );
		}
	}
}