<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Misc Functions
 * 
 * All misc functions handles to 
 * different functions 
 * 
 * @package Woocommerce Order Signature
 * @since 1.0.0
 */

/**
 * Create Signature Directory
 * 
 * @package Woocommerce Order Signature
 * @since 1.0.0
 */
function woo_os_create_signature_directory() {
	
	$files = array(
		array(
			'base' 		=> WOO_OS_SIGNATURE_DIR,
			'file' 		=> 'index.html',
			'content' 	=> ''
		)
	);
	
	foreach ( $files as $file ) {
		if ( wp_mkdir_p( $file['base'] ) && ! file_exists( trailingslashit( $file['base'] ) . $file['file'] ) ) {
			if ( $file_handle = @fopen( trailingslashit( $file['base'] ) . $file['file'], 'w' ) ) {
				fwrite( $file_handle, $file['content'] );
				fclose( $file_handle );
			}
		}
	}
}

/**
 * Check Signature is Enable Or Not
 * 
 * @package Woocommerce Order Signature
 * @since 1.0.0
 */
function woo_os_enable_signature() {
	
	$enable_signature	= ( get_option( 'wooos_enable_signature' ) == 'yes' ) ? true : false;
	return apply_filters( 'woo_os_enable_signature', $enable_signature );
}

/**
 * Display Signature
 * 
 * @package Woocommerce Order Signature
 * @since 1.0.0
 */
function woo_os_display_signature( $order_id = '', $echo = true, $convert_image = false, $title_tag = 'h2', $agreement = 'default' ) {
	
	// initilize signature field
	$signature_html	= '';
	
	if( !empty( $order_id ) ) { // If order id is not empty
		
		// get image string
		$image_str			= get_post_meta( $order_id, '_wooos_hidden_signature', true );
		$enable_agreement	= ( $agreement == 'default' ) ? get_option( 'wooos_enable_agreement' ) : $agreement;
		$wooos_enable_product_wise_agreement	= ( get_option( 'wooos_enable_product_wise_agreement' ) ) ? get_option( 'wooos_enable_product_wise_agreement' ) : 'no';
		$agreement_text		= get_option( 'wooos_agreement_text' );

		if( !empty( $image_str ) ) { // if image string is not empty
			
			if( $convert_image ) { // If need to create signature image
				
				if( !is_dir( WOO_OS_SIGNATURE_DIR ) ) { // if directory not exit
					woo_os_create_signature_directory();
				}
				
				$image_file_path	= WOO_OS_SIGNATURE_DIR . 'woo-os-' . $order_id . '.png';
				$image_file_source	= WOO_OS_SIGNATURE_URL . 'woo-os-' . $order_id . '.png';
				
				if( !file_exists( $image_file_path ) ) { // if file not exist
					
					$data	= base64_decode( preg_replace( '#^data:image/\w+;base64,#i', '', $image_str ) );
					file_put_contents( $image_file_path, $data );
				}
				
			} else {
				
				$image_file_source	= $image_str;
			}
			
			if( !empty( $title_tag ) ) {
				$signature_html .= '<'. $title_tag . '>' . woo_os_signature_title() . '</' . $title_tag . '>';
			}
			$signature_html .=  '<img src="' . $image_file_source . '" id="sign-img">';
		}
	}
	
	if( $enable_agreement == 'yes' && !empty( $agreement_text ) && $wooos_enable_product_wise_agreement == 'no' ) {
		
		$agreement_download_html	= get_option( 'wooos_download_link_html' );
		$agreement_link				= trailingslashit( site_url() ) . '?wooos-agreement=download&order_id=' . $order_id;
		$agreement_link_text		= woo_os_display_message( 'agreement_link_text' );
		
		$signature_link				= '<a target="_blank" href="'.$agreement_link.'">' . $agreement_link_text . '</a>';
		$signature_html				.=	str_replace( '{download_link}', $signature_link, $agreement_download_html );
	}
	
	// Modify signature html
	$signature_html	= apply_filters( 'woo_os_display_signature', $signature_html, $order_id, $title_tag );
	
	if( $echo ) {
		echo $signature_html;
	} else {
		return $signature_html;
	}
}

/**
 * Display Signature
 * 
 * @package Woocommerce Order Signature
 * @since 1.0.0
 */
function woo_os_signature_title() {
	
	$signature_title	= woo_os_display_message( 'signature_title_text' );
	return apply_filters( 'woo_os_signature_title', $signature_title );
}

/**
 * Product Wise download agreement title
 * 
 * @package Woocommerce Order Signature
 * @since 1.0.0
 */
function woo_os_download_title() {
	
	$download_title	= woo_os_display_message( 'product_wise_agreement_title_text' );
	return apply_filters( 'woo_os_download_title', $download_title );
}

/**
 * Display Message
 * 
 * @package Woocommerce Order Signature
 * @since 1.0.0
 */
function woo_os_display_message( $message_key = '' ) {

	$messages	= apply_filters( 'woo_os_get_all_message', array(
										'signature_error_msg'		=> __( 'Please enter your signature and save this.', 'wooos' ),
										'signature_remember_msg'	=> '<strong>'. __( 'Remember: ', 'wooos' ) .'</strong>' . __( 'Please click on save button to save signature.', 'wooos' ),
										'clear_btn_text'			=> __( 'Clear', 'wooos' ),
										'save_btn_text'				=> __( 'Save', 'wooos' ),
										'signature_title_text'		=> __( 'Signature', 'wooos' ),
										'agreement_link_text'		=> __( 'Download', 'wooos' ),
										'wooos_above_text'			=> get_option( 'wooos_above_text' ),
										'product_wise_agreement_title_text'		=> __( 'Download Agreement', 'wooos' ),
									));
									
	if( !empty( $message_key ) ) {
		return isset( $messages[$message_key] ) ? $messages[$message_key] : '';
	} else {
		return $messages;
	}
}

/**
 * Get Buyer Information
 * 
 * @package Woocommerce Order Signature
 * @since 1.0.4
 */
function woo_os_get_buyer_information( $order_id = '' ) {

	$buyer_details	= array();
	$order			= array();

	if( $order_id ) {

		// get order detail
		$order = new WC_Order( $order_id );		
		
		// if version is lower then 3.0.0
		if ( version_compare( WOOCOMMERCE_VERSION, "3.0.0" ) == -1 ) {
			// buyer's details array
			$buyer_details = array(
				'first_name'	=> $order->billing_first_name,
				'last_name'		=> $order->billing_last_name,					
				'address_1'		=> $order->billing_address_1,
				'address_2'		=> $order->billing_address_2,
				'city'			=> $order->billing_city,
				'state'			=> $order->billing_state,
				'postcode'		=> $order->billing_postcode,
				'country'		=> $order->billing_country,
				'email'			=> $order->billing_email,
				'phone'			=> $order->billing_phone,
				'order_date'	=> $order->order_date
			);	
		} else {
			// buyer's details array
			$buyer_details = array(
				'first_name'	=> $order->get_billing_first_name(),
				'last_name'		=> $order->get_billing_last_name(),					
				'address_1'		=> $order->get_billing_address_1(),
				'address_2'		=> $order->get_billing_address_2(),
				'city'			=> $order->get_billing_city(),
				'state'			=> $order->get_billing_state(),
				'postcode'		=> $order->get_billing_postcode(),
				'country'		=> $order->get_billing_country(),
				'email'			=> $order->get_billing_email(),
				'phone'			=> $order->get_billing_phone(),
				'order_date'	=> $order->get_date_created()->date('c')
			);
		}
		
	}

	return apply_filters( 'woo_os_get_buyer_information', $buyer_details, $order );
}

/**
 * Function for show download agreement product wise on order page
 * 
 * @package Woocommerce Order Signature
 * @since 1.0.0
 */
function woo_os_download_links( $order_id = '', $echo = true, $title_tag = 'h2', $agreement = 'default' ) {
	
	// initilize signature field
	$download_html	= '';
	
	if( !empty( $order_id ) ) { // If order id is not empty

		$enable_agreement	= ( $agreement == 'default' ) ? get_option( 'wooos_enable_agreement' ) : $agreement;
		$wooos_enable_product_wise_agreement	= ( get_option( 'wooos_enable_product_wise_agreement' ) ) ? get_option( 'wooos_enable_product_wise_agreement' ) : 'no';
		$agreement_text		= get_option( 'wooos_agreement_text' );

		if( !empty( $title_tag ) ) {

			$download_html .= '<'. $title_tag . '>' . woo_os_download_title() . '</' . $title_tag . '>';
		}

		/** Download Links **/
		if( $enable_agreement == 'yes' && !empty( $agreement_text ) && $wooos_enable_product_wise_agreement == 'yes' ) {

			$agreement_link			= trailingslashit( site_url() ) . '?wooos-agreement=download&order_id=' . $order_id;
			$agreement_link_text	= woo_os_display_message( 'agreement_link_text' );

			$download_html .= '<table class="woocommerce-table woocommerce-table--order-details shop_table order_details">
								<thead>
									<tr>
										<th class="woocommerce-table__product-name product-name">' . __( 'Product', 'wooos' ) . '</th>
										<th class="woocommerce-table__product-table product-agreement-link">' . __( 'PDF Download Link', 'wooos' ) . '</th>
									</tr>
								</thead>

								<tbody>';
	
			$order = wc_get_order( $order_id ); //getting order Object

			foreach ( $order->get_items() as $item_id => $item ) {

				$product = $item->get_product();

	            // Check if the product exists.
	            if (is_object($product)) {

	            	$product_id			= $product->get_id();
					$product_name		= $item['name']; //$product->get_title();
					$enabled_signature	= get_post_meta( $product_id, 'woo_os_enable_signature', true ); //$product->get_title();

					if( $enabled_signature == 'on' ) {
						$download_link	= '<a target="_blank" href="'.$agreement_link.'&product_id=' . $product_id . '">' . $agreement_link_text . '</a>';

						$download_html .= '<tr class="woocommerce-table__line-item order_item">
												<td class="woocommerce-table__product-name product-name">' . $product_name . '</td>
												<td class="woocommerce-table__product-agreement-link product-agreement-link">' . $download_link . '</td>
											</tr>';
	           		}
	           }
	        }
			$download_html .= '</tbody></table>';

			/** Download Links **/

			// Modify download html
			$download_html	= apply_filters( 'woo_os_display_download', $download_html, $order_id, $title_tag );

			if( $echo ) {
				echo $download_html;
			} else {
				return $download_html;
			}
		}
	}
}
