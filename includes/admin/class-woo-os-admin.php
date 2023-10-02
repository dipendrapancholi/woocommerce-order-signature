<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Admin Pages Class
 * 
 * Handles all the different features and functions
 * for the admin pages.
 * 
 * @package Woocommerce Order Signature
 * @since 1.0.0
 */

if( !class_exists( 'Woo_Os_Admin' ) ) { // If class not exist
	
	class Woo_Os_Admin {
		
		public function __construct() {
			
		}
		
		/**
		 * Adding Signature setting tab
		 * 
		 * @package Woocommerce Order Signature
		 * @since 1.0.0
		 */
		public function woo_os_add_settings_tab( $tabs ) {
			$tabs['wooos'] = __( 'Signature', 'wooos' );
			return $tabs;
		}
		
		/**
		 * Settings Tab Content
		 * 
		 * Adds the settings content to the ban signature tab.
		 *
		 * @package Woocommerce Order Signature
		 * @since 1.0.0
		 */
		public function woo_os_settings_tab() {
			
			woocommerce_admin_fields( $this->woo_os_get_settings() );		
		}
		
		/**
		 * Update Settings
		 * 
		 * Updates the signature options when being saved.
		 *
		 *  @package Woocommerce Order Signature
		 * @since 1.0.0
		 */
		public function woo_os_update_settings() {
			
			woocommerce_update_options( $this->woo_os_get_settings() );		
		}
		
		/**
	 	 * Add plugin settings
	 	 * 
	 	 * Handles to add plugin settings
	 	 * 
	 	 * @package Woocommerce Order Signature
	 	 * @since 1.0.0
	 	 */
		public function woo_os_get_settings() {
			
			$woo_os_shortcode	= '';
			$woo_os_shortcode	= apply_filters( 'woo_os_agreement_shortcodes', $woo_os_shortcode );
			
			$woo_os_settings	= array(	
										array( 
											'name'	=>	__( 'Signature Options', 'wooos' ),
											'type'	=>	'title',
											'desc'	=>	'',
											'id'	=>	'wooos_general_settings'
										),
										array(
											'id'		=> 'wooos_enable_signature',
											'name'		=> __( 'Enable Signature:', 'wooos' ),
											'desc'		=> '',
											'type'		=> 'checkbox',
											'desc_tip'	=> '<p class="description">'.__( 'If you want to use the signature option on your site, then you have to enable this setting.', 'wooos' ).'</p>'
										),
										array(
											'id'		=> 'wooos_enable_required',
											'name'		=> __( 'Enable Signature Required:', 'wooos' ),
											'desc'		=> '',
											'type'		=> 'checkbox',
											'desc_tip'	=> '<p class="description">'.__( 'If you want to use the signature field is required when place the order then please enable this option.', 'wooos' ).'</p>'
										),
										array(
											'id'		=> 'wooos_display_order_detail_page',
											'name'		=> __( 'Display On Order Detail Page:', 'wooos' ),
											'desc'		=> '',
											'type'		=> 'checkbox',
											'desc_tip'	=> '<p class="description">'.__( 'If you want to display the signature on view order page then please enable this option.', 'wooos' ).'</p>'
										),
										array(
											'id'		=> 'wooos_display_new_order_email',
											'name'		=> __( 'Display On New Order Email:', 'wooos' ),
											'desc'		=> '',
											'type'		=> 'checkbox',
											'desc_tip'	=> '<p class="description">'.__( 'If you want to display the signature in the new order email template then please enable this option.', 'wooos' ).'</p>'
										),
										array(
											'id'		=> 'wooos_display_customer_on_hold_order_email',
											'name'		=> __( 'Display On On-Hold Order Email:', 'wooos' ),
											'desc'		=> '',
											'type'		=> 'checkbox',
											'desc_tip'	=> '<p class="description">'.__( 'If you want to display the signature in the on-hold order email template then please enable this option.', 'wooos' ).'</p>'
										),
										array(
											'id'		=> 'wooos_display_customer_processing_order_email',
											'name'		=> __( 'Display On Processing Order Email:', 'wooos' ),
											'desc'		=> '',
											'type'		=> 'checkbox',
											'desc_tip'	=> '<p class="description">'.__( 'If you want to display the signature in the processing order email template then please enable this option.', 'wooos' ).'</p>'
										),
										array(
											'id'		=> 'wooos_display_customer_completed_order_email',
											'name'		=> __( 'Display On Completed Order Email:', 'wooos' ),
											'desc'		=> '',
											'type'		=> 'checkbox',
											'desc_tip'	=> '<p class="description">'.__( 'If you want to display the signature in the completed order email template then please enable this option.', 'wooos' ).'</p>'
										),
										array(
											'id'		=> 'wooos_no_signaturepad_load_onscreen_resize',
											'name'		=> __( 'No Signature Pad Load On Screen Resize:', 'wooos' ),
											'desc'		=> '',
											'type'		=> 'checkbox',
											'desc_tip'	=> '<p class="description">'.__( 'If you don\'t want to resize the signature pad when your screen resize then please enable this option.', 'wooos' ).'</p>'
										),
										array(
											'id'		=> 'wooos_sig_width',
											'name'		=> __( 'Signature Field Width:', 'wooos' ),
											'desc'		=> '',
											'type'		=> 'text',
											'css'		=> 'width:6em;',
											'default'	=> '100%',
											'desc_tip'	=> '<p class="description">'.__( 'Here you can namage the signature field width. You can use the width parameter eg: 100% Or 500px Or 500em.', 'wooos' ).'</p>'
										),
										array(
											'id'		=> 'wooos_sig_title_color',
											'name'		=> __( 'Signature Title Color:', 'wooos' ),
											'desc'		=> '',
											'type'		=> 'color',
											'css'		=> 'width:6em;',
											'default'	=> '#0a018b',
											'desc_tip'	=> '<p class="description">'.__( 'The title color for signature field. Dafault is <code>#0a018b</code>.', 'wooos' ).'</p>'
										),
										array(
											'id'		=> 'wooos_pen_colour',
											'name'		=> __( 'Signature Pan Color:', 'wooos' ),
											'desc'		=> '',
											'type'		=> 'color',
											'css'		=> 'width:6em;',
											'default'	=> '#000000',
											'desc_tip'	=> '<p class="description">'.__( 'The pen color for signature field. Dafault is <code>#000000</code>.', 'wooos' ).'</p>'
										),
										array(
											'id'		=> 'wooos_container_background',
											'name'		=> __( 'Signature container Background:', 'wooos' ),
											'desc'		=> '',
											'type'		=> 'color',
											'css'		=> 'width:6em;',
											'default'	=> '#a9a9a9',
											'desc_tip'	=> '<p class="description">'.__( 'The container background color for signature field. Dafault is <code>#a9a9a9</code>.', 'wooos' ).'</p>'
										),
										array(
											'id'		=> 'wooos_signaturepad_background',
											'name'		=> __( 'Signature Pad Background:', 'wooos' ),
											'desc'		=> '',
											'type'		=> 'color',
											'css'		=> 'width:6em;',
											'default'	=> '#cccccc',
											'desc_tip'	=> '<p class="description">'.__( 'The pad background color for signature field. Dafault is <code>#cccccc</code>.', 'wooos' ).'</p>'
										),
										array(
											'id'		=> 'wooos_signaturepad_border',
											'name'		=> __( 'Signature Pad Border Color:', 'wooos' ),
											'desc'		=> '',
											'type'		=> 'color',
											'css'		=> 'width:6em;',
											'default'	=> '#000000',
											'desc_tip'	=> '<p class="description">'.__( 'The pad border color for signature field. Dafault is <code>#000000</code>.', 'wooos' ).'</p>'
										),
										array(
											'id'		=> 'wooos_signature_btn_color',
											'name'		=> __( 'Signature Field Button Color:', 'wooos' ),
											'desc'		=> '',
											'type'		=> 'color',
											'css'		=> 'width:6em;',
											'default'	=> '#1a1a1a',
											'desc_tip'	=> '<p class="description">'.__( 'The button color for signature field. Dafault is <code>#1a1a1a</code>.', 'wooos' ).'</p>'
										),
										array(
											'id'		=> 'wooos_sig_btn_text_color',
											'name'		=> __( 'Signature Button Text Color:', 'wooos' ),
											'desc'		=> '',
											'type'		=> 'color',
											'css'		=> 'width:6em;',
											'default'	=> '#ffffff',
											'desc_tip'	=> '<p class="description">'.__( 'The button text color for signature field. Dafault is <code>#ffffff</code>.', 'wooos' ).'</p>'
										),
										array(
											'id'		=> 'wooos_sig_remark_text_color',
											'name'		=> __( 'Signature Remark Text Color:', 'wooos' ),
											'desc'		=> '',
											'type'		=> 'color',
											'css'		=> 'width:6em;',
											'default'	=> '#000000',
											'desc_tip'	=> '<p class="description">'.__( 'The remark text for signature field. Dafault is <code>#000000</code>.', 'wooos' ).'</p>'
										),
										array(
											'id'		=> 'wooos_above_text',
											'name'		=> __( 'Above Signature Text:', 'wooos' ),
											'desc'		=> '',
											'class' 	=> '',
						                	'css' 		=> 'width:100%;min-height:100px',
											'type'		=> 'textarea',
											'desc_tip'	=> '<p class="description">'.__( 'Here you can enter your custom text which will display above the signature field. Leave it empty if you don\'t wabt to display.', 'wooos' ).'</p>'
										),
										array(
											'id'		=> 'wooos_enable_agreement',
											'name'		=> __( 'Enable Download Agreement:', 'wooos' ),
											'desc'		=> '',
											'type'		=> 'checkbox',
											'desc_tip'	=> '<p class="description">'.__( 'If you want to display the link to donwload agreement on order page then please enable this option.', 'wooos' ).'</p>'
										),
										array(
											'id'		=> 'wooos_enable_product_wise_agreement',
											'name'		=> __( 'Enable Download Agreement Product Wise:', 'wooos' ),
											'desc'		=> '',
											'type'		=> 'checkbox',
											'desc_tip'	=> '<p class="description">'.__( 'If you want to display the link to donwload agreement product wise on order page then please enable this option.', 'wooos' ).'</p>'
										),
										array(
											'id'		=> 'wooos_download_link_html',
											'name'		=> __( 'Download Agreement HTML:', 'wooos' ),
											'desc'		=> '',
											'class' 	=> '',
						                	'css' 		=> 'width:100%;min-height:100px',
											'type'		=> 'textarea',
											'desc_tip'	=> '<p class="description">'.__( 'Here you can enter your text which will display on front side for download text.', 'wooos' ).'</p>',
											'desc'		=> '<p class="description"><br /><code>{download_link}</code> - displays the agreement PDF link.</p>'
										),
										array(
											'id'		=> 'wooos_agreement_text',
											'name'		=> __( 'Agreement Content:', 'woocl' ),
											'desc'		=> '<p class="description">' . __( 'This is the main content of the agreement that will be sent to the customer when he buy product. The available tags are:','woocl' ) .
															'<br /><code>{customer_name}</code> - '. __( 'displays the customer name', 'woocl' ) .
															'<br /><code>{billing_address}</code> - ' . __( 'displays the customer billing address.', 'woocl' ) .
															'<br /><code>{phone}</code> - ' . __( 'displays the customer phone.', 'woocl' ) .
															'<br /><code>{city}</code> - ' . __( 'displays the customer city.', 'woocl' ) .
															'<br /><code>{state}</code> - ' . __( 'displays the customer state.', 'woocl' ) .
															'<br /><code>{zip}</code> - ' . __( 'displays the customer zip.', 'woocl' ) .
															'<br /><code>{customer_email}</code> - ' . __( 'displays the customer email', 'woocl' ) .
															'<br /><code>{purchase_date}</code> - ' . __( 'displays the order purchase date', 'woocl' ) .
															'<br /><code>{customer_signature}</code> - '. __( 'displays the customer signature', 'woocl' ) .
															'<br /><code>{product_name}</code> - '. __( 'displays product name', 'woocl' ) .
															'<br /><code>{product_price}</code> - '. __( 'displays product price', 'woocl' ) .
															'<br /><code>{product_sku}</code> - '. __( 'displays product SKU', 'woocl' ) .
															'<br /><code>{product_qty}</code> - '. __( 'displays product quantity', 'woocl' ) .
															$woo_os_shortcode .
															'</p>',
											'type'		=> 'woo_os_textarea',
											'class'		=> 'large-text',
											'css' 		=> 'height: 200px;',
											'editor'	=> true
										),
										array(
											'id'		=> 'wooos_custom_css',
											'name'		=> __( 'Custom CSS:', 'wooos' ),
											'desc'		=> '',
											'class' 	=> '',
						                	'css' 		=> 'width:100%;min-height:100px',
											'type'		=> 'textarea',
											'desc_tip'	=> '<p class="description">'.__( 'Here you can enter your custom css for the signature. The css will be automatically added to the header, when you save it.', 'wooos' ).'</p>'
										),
										array( 
											'type' 		=> 'sectionend',
											'id' 		=> 'wooos_general_settings'
										),
									);
			
			return apply_filters( 'woo_be_get_settings', $woo_os_settings );
		}
		
		/**
		 * Display Signature Image on Order Page
		 * 
		 * @package Woocommerce Order Signature
		 * @since 1.0.0
		 */
		public function woo_os_checkout_field_display_admin_order_meta( $order ) {
			
			// enable signature functionality
			$enable_signature	= woo_os_enable_signature();
			
			if( WOOCOMMERCE_VERSION < '3.0.0' ) {
				// get order id
				$order_id	= isset( $order->id ) ? $order->id : '';
				
			} else {
				// get order id
				$order_id	= $order->get_id();
			}
			
			if( $enable_signature && !empty( $order_id ) ) { // if signature functionality is enable
				
				// display signature image
				woo_os_display_signature( $order_id, true, false, 'h3' );
			}
		}
		
		/**
		 * Add woo_os_textarea type Setting
		 * 
		 * Handles to add woo_os_textarea field
		 * type for admin side
		 * 
		 * @package Woocommerce Order Signature
	 	 * @since 1.0.4
		 */
		public function woo_os_admin_field_woo_os_textarea( $field ) {
			
			global $woocommerce;
			
			if ( isset( $field['title'] ) && isset( $field['id'] ) ) :
				
				$file_val		= get_option( $field['id'] );
				$file_val		= !empty($file_val) ? $file_val : '';
				$editor			= ( isset( $field['editor'] ) && $field['editor'] == true ) ? true : false;
				$editor_cofig	= array(
										'media_buttons'	=> true,
										'textarea_rows'	=> 5,
										'editor_class'	=> 'woo-os-wpeditor'
									); ?>
					
					<tr valign="top">
						<th scope="row" class="titledesc">
							<label for="<?php echo esc_attr( $field['id'] ); ?>"><?php echo wp_kses_post( $field['title'] ); ?></label>
						</th>
						<td class="forminp forminp-text">
							<fieldset><?php 
								if( $editor ) {
									wp_editor( $file_val, esc_attr( $field['id'] ), $editor_cofig );
									
								} else { ?>
									
									<textarea name="<?php echo esc_attr( $field['id']  ); ?>" id="<?php echo esc_attr( $field['id'] ); ?>" style="width: 99%;height:200px;"/><?php echo esc_attr( $file_val ); ?></textarea><?php 
								} ?>
							</fieldset>
							<span class="description"><?php echo $field['desc'];?></span>
						</td>
					</tr><?php
			endif;
		}
		
		/**
		 * Add Admin Hook
		 * 
		 * Handle to add admin hooks
		 * 
		 * @package Woocommerce Order Signature
		 * @since 1.0.4
		 */
		public function woo_os_save_wooostextarea_field( $value, $option, $raw_value ) {
			
			if( $option['type'] == 'woo_os_textarea' ) {
				
				$value	= $raw_value;
			}
			
			return $value;
		}

		/**
		 * 
		 * Custom metabox for product wise signature
		 * 
		 * @package Woocommerce Order Signature
		 * @since 1.0.0
		 */
		public function woo_os_product_signature_option() {

		    add_meta_box( 'woo_os_signature_options', 'Signature Options', array( $this, 'woo_os_signature_options_callback' ), 'product', 'normal', 'high' );
		}

		/**
		 * 
		 * Callable function for singature options
		 * 
		 * @package Woocommerce Order Signature
		 * @since 1.0.0
		 */
		public function woo_os_signature_options_callback() {

			// $post is already set, and contains an object: the WordPress post
			global $post;

			$enabled			= get_post_meta( $post->ID, 'woo_os_enable_signature', true );
			$agreement_content	= get_post_meta( $post->ID, 'woo_os_agreement_content', true );

			$woo_os_enabled				= isset( $enabled ) ? esc_attr( $enabled ) : '';
			$woo_os_agreement_content	= isset( $agreement_content ) ? ( $agreement_content ) : '';

			// We'll use this nonce field later on when saving.
			wp_nonce_field( 'woo_os_signature_nonce', 'woo_os_signature_nonce' ); ?>

			<p>
				<input type="checkbox" id="woo_os_enable_signature" name="woo_os_enable_signature" <?php checked( $woo_os_enabled, 'on' ); ?> />
				<label for="woo_os_enable_signature">Enable Signature</label>
			</p>

			<p>
				<label for="woo_os_agreement_content"><strong>Agreement Content:</strong></label>
				<?php wp_editor( $woo_os_agreement_content, 'woo_os_agreement_content', $settings = array( 'textarea_rows' => 4 ) ); ?>
				<p class="description"><?php 

					$woo_os_shortcode	= '';
					$woo_os_shortcode	= apply_filters( 'woo_os_agreement_shortcodes', $woo_os_shortcode );

					echo __( 'This is the main content of the agreement that will be sent to the customer when he buy product. The available tags are:','woocl' ) .
															'<br /><code>{customer_name}</code> - '. __( 'displays the customer name', 'woocl' ) .
															'<br /><code>{billing_address}</code> - ' . __( 'displays the customer billing address.', 'woocl' ) .
															'<br /><code>{phone}</code> - ' . __( 'displays the customer phone.', 'woocl' ) .
															'<br /><code>{city}</code> - ' . __( 'displays the customer city.', 'woocl' ) .
															'<br /><code>{state}</code> - ' . __( 'displays the customer state.', 'woocl' ) .
															'<br /><code>{zip}</code> - ' . __( 'displays the customer zip.', 'woocl' ) .
															'<br /><code>{customer_email}</code> - ' . __( 'displays the customer email', 'woocl' ) .
															'<br /><code>{purchase_date}</code> - ' . __( 'displays the order purchase date', 'woocl' ) .
															'<br /><code>{customer_signature}</code> - '. __( 'displays the customer signature', 'woocl' ) .
															'<br /><code>{product_name}</code> - '. __( 'displays product name', 'woocl' ) .
															'<br /><code>{product_price}</code> - '. __( 'displays product price', 'woocl' ) .
															'<br /><code>{product_sku}</code> - '. __( 'displays product SKU', 'woocl' ) .
															'<br /><code>{product_qty}</code> - '. __( 'displays product quantity', 'woocl' ) .
															$woo_os_shortcode; ?>
				</p>
			</p>

		<?php    
		}

		/**
		 * 
		 * Save product signature options
		 * 
		 * @package Woocommerce Order Signature
		 * @since 1.0.0
		 */
		public function woo_os_product_signature_option_save( $post_id ) {

			// Bail if we're doing an auto save
			if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

			// if our nonce isn't there, or we can't verify it, bail
			if( !isset( $_POST['woo_os_signature_nonce'] ) || !wp_verify_nonce( $_POST['woo_os_signature_nonce'], 'woo_os_signature_nonce' ) ) return;

			// if our current user can't edit this post, bail
			if( !current_user_can( 'edit_post' ) ) return;

			// This is purely my personal preference for saving check-boxes
			$checked = isset( $_POST['woo_os_enable_signature'] ) && $_POST['woo_os_enable_signature'] ? 'on' : 'off';
			update_post_meta( $post_id, 'woo_os_enable_signature', $checked );

			if( isset( $_POST['woo_os_agreement_content'] ) )
			update_post_meta( $post_id, 'woo_os_agreement_content', ( $_POST['woo_os_agreement_content'] ) );
		}

		/**
		 * Add Admin Hook
		 * 
		 * Handle to add admin hooks
		 * 
		 * @package Woocommerce Order Signature
		 * @since 1.0.0
		 */
		public function add_hooks() {
			
			
			//add Signature tab to woocommerce setting page
			add_filter( 'woocommerce_settings_tabs_array', array( $this, 'woo_os_add_settings_tab' ), 99 );			
			
			// add signature tab content
			add_action( 'woocommerce_settings_tabs_wooos', array( $this, 'woo_os_settings_tab' ) );
			
			// save custom update content
			add_action( 'woocommerce_update_options_wooos', array( $this, 'woo_os_update_settings'), 100 );
			
			// display signature image
			add_action( 'woocommerce_admin_order_data_after_billing_address', array($this, 'woo_os_checkout_field_display_admin_order_meta' ), 10 , 1 );
			
			// Add a custom field types
			add_action( 'woocommerce_admin_field_woo_os_textarea', array( $this, 'woo_os_admin_field_woo_os_textarea' ) );
			
			// Save custom field types ( V 1.0.4 )
			add_filter( 'woocommerce_admin_settings_sanitize_option', array( $this, 'woo_os_save_wooostextarea_field' ), 10, 3 ); 

			// add metabox for product signature option
			add_action( 'add_meta_boxes', array( $this, 'woo_os_product_signature_option' ) );

			// add action for save product type post
			add_action( 'save_post', array( $this, 'woo_os_product_signature_option_save' ) );
		}
	}
}