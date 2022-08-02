<?php

add_action('wp_ajax_srpwp_ajax_datas', 'srpwp_add_to_cart_datas_callabck');
add_action('wp_ajax_nopriv_srpwp_ajax_datas', 'srpwp_add_to_cart_datas_callabck');

// Request form data handling
if ( ! function_exists( 'srpwp_add_to_cart_datas_callabck' ) ) {
	function srpwp_add_to_cart_datas_callabck() {
		$product_id = apply_filters('srpwp_add_to_cart_product_id', absint($_POST['product_id']));
		$quantity = empty($_POST['quantity']) ? 1 : wc_stock_amount(absint($_POST['quantity']));
		$variation_id = absint($_POST['variation_id']);
		$passed_validation = apply_filters('srpwp_add_to_cart_validation', true, $product_id, $quantity);
		$product_status = get_post_status($product_id); 
		if ($passed_validation && WC()->cart->add_to_cart($product_id, $quantity, $variation_id) && 'publish' === $product_status) { 
			do_action('srpwp_ajax_added_to_cart', $product_id);
			$data = array( 
			'error' => false,
			'message' => __('Product added to cart successfully', 'woo-product-table-cd' )
			);
			WC_AJAX :: get_refreshed_fragments(); 

			echo wp_send_json($data);
		}
		wp_die();

	}
}


// Donations save to db
function srpwp_donations_save_to_db( $donor_name, $donor_email, $donation_type, $donation_amount, $customer_id, $subscription_id, $donation_time, $output ) {
	global $wpdb;
	$table_name = $wpdb->base_prefix.'srpwp_donations';
	$submission_insert = $wpdb->insert( 
		$table_name, 
		array( 
			'donor_name' 		=> $donor_name, 
			'donor_email' 		=> $donor_email, 
			'donation_type' 	=> $donation_type, 
			'donation_amount'	=> $donation_amount, 
			'customer_id' 		=> $customer_id, 
			'subscription_id' 	=> $subscription_id, 
			'donation_time' 	=> $donation_time,
		), 
		array( 
			'%s', 
			'%s', 
			'%s', 
			'%s', 
			'%s', 
			'%s', 
			'%s', 
			'%s', 
		) 
	);

	if ( $submission_insert ) {
		echo wp_send_json(['success' => 'Donation has been received.', 'datas' => $output]); 
	}
}

// Saving admin datas
add_action('wp_ajax_srpwp_admin_datas', 'srpwp_save_admin_datas_callabck');
add_action('wp_ajax_nopriv_srpwp_admin_datas', 'srpwp_save_admin_datas_callabck');
// Request form data handling
if ( ! function_exists( 'srpwp_save_admin_datas_callabck' ) ) {
	function srpwp_save_admin_datas_callabck() {
		$response = [];
		$srpwp_payment_method = isset($_POST['srpwp_payment_method']) ? sanitize_text_field($_POST['srpwp_payment_method']) : '';
		$srpwp_payment_type = isset($_POST['srpwp_payment_type']) ? sanitize_text_field($_POST['srpwp_payment_type']) : '';
		$srpwp_amount_val = isset($_POST['srpwp_amount_val']) ? sanitize_text_field($_POST['srpwp_amount_val']) : '';
		$customer_name = isset($_POST['customer_name']) ? sanitize_text_field($_POST['customer_name']) : '';
		$customer_email = isset($_POST['customer_email']) ? sanitize_email($_POST['customer_email']) : '';
		$srpwp_phone_number = isset($_POST['srpwp_phone_number']) ? sanitize_text_field($_POST['srpwp_phone_number']) : '';
		$srpwp_address = isset($_POST['srpwp_address']) ? sanitize_text_field($_POST['srpwp_address']) : '';
		$srpwp_address2 = isset($_POST['srpwp_address2']) ? sanitize_text_field($_POST['srpwp_address2']) : '';
		$srpwp_city = isset($_POST['srpwp_city']) ? sanitize_text_field($_POST['srpwp_city']) : '';
		$srpwp_state = isset($_POST['srpwp_state']) ? sanitize_text_field($_POST['srpwp_state']) : '';
		// $response['srpwp_payment_method'] = $srpwp_payment_method;
		// $response['srpwp_payment_type'] = $srpwp_payment_type;
		// $response['srpwp_amount_val'] = $srpwp_amount_val;
		// $response['customer_name'] = $customer_name;
		// $response['customer_email'] = $customer_email;
		// $response['srpwp_phone_number'] = $srpwp_phone_number;
		// $response['srpwp_address'] = $srpwp_address;
		// $response['srpwp_address2'] = $srpwp_address2;
		// $response['srpwp_city'] = $srpwp_city;
		// $response['srpwp_state'] = $srpwp_state;
		
		if ( $srpwp_payment_method == 'Paypal' ) {
			ob_start();
			require_once STRIPE_PLUGIN_DIR_PATH . 'paypal-payment-button-content.php'; 
			$form_html = ob_get_clean();
			echo wp_send_json( $form_html );
			wp_die();
		} else {

		// Saving the settings
		// if (update_option( 'srpwp_settings_datas', $srpwp_settings_array )) {
		// 	$response['status'] = 'success';
		// 	$response['message'] = __('Settings has been updated!', 'woo-product-table-cd' );
		// } else {
		// 	$response['status'] = 'warning';
		// 	$response['message'] = __('Nothing to save!', 'woo-product-table-cd');
		// }

		// $response['srpwp_payment_type'] = $srpwp_payment_type;
		// $response['srpwp_amount_val'] = $srpwp_amount_val;
		// $response['customer_name'] = $customer_name;
		// $response['customer_email'] = $customer_email;
		// $response['srpwp_phone_number'] = $srpwp_phone_number;
		// $response['srpwp_address'] = $srpwp_address;
		// $response['srpwp_address2'] = $srpwp_address2;
		// $response['srpwp_city'] = $srpwp_city;
		// $response['srpwp_state'] = $srpwp_state;

		$srpwp_settings_array = get_option('srpwp_settings_datas');
		$srpwp_secret_key = ($srpwp_settings_array['srpwp_secret_key'] != '') ? $srpwp_settings_array['srpwp_secret_key'] : 'XXXXXXXXXX';
		// Include the Stripe PHP library 
		require_once STRIPE_PLUGIN_DIR_PATH . 'stripe-php/init.php'; 
		 
		// Set API key 
		\Stripe\Stripe::setApiKey($srpwp_secret_key); 
		
		// Retrieve JSON from POST body 
		$jsonStr = file_get_contents('php://input'); 
		$jsonObj = json_decode($jsonStr); 
		
		// Get user ID from current SESSION 
		$userID = isset($_SESSION['loggedInUserID'])?$_SESSION['loggedInUserID']:1; 


		// Convert price to cents 
		$planPriceCents = round($srpwp_amount_val*100); 
		
		if ( $srpwp_payment_type == 'Monthly' ) {
			// Add customer to stripe 
			try {   
				$customer = \Stripe\Customer::create([ 
					'name' => $customer_name,  
					'email' => $customer_email 
				]);  
			}catch(Exception $e) {   
				$api_error = $e->getMessage(); 
			} 
			
			if(empty($api_error) && $customer){ 
				try { 
					// Create price with subscription info and interval 
					$srpwp_price_obj = [ 
						'unit_amount' => $planPriceCents, 
						'currency' => STRIPE_CURRENCY, 
						'product_data' => ['name' => $customer_name], 
					];
					if ( $srpwp_payment_type == 'Monthly' ) {
						$srpwp_price_obj['recurring'] = ['interval' => 'month'];
					}
					$price = \Stripe\Price::create( $srpwp_price_obj ); 

					if(empty($api_error) && $price){ 
						// Create a new subscription 
						try { 
							$subscription = \Stripe\Subscription::create([ 
								'customer' => $customer->id, 
								'items' => [[ 
									'price' => $price->id, 
								]], 
								'payment_behavior' => 'default_incomplete', 
								'expand' => ['latest_invoice.payment_intent'], 
							]); 
						}catch(Exception $e) { 
							$api_error = $e->getMessage(); 
						} 
						
						if(empty($api_error) && $subscription){ 
							$output = [ 
								'subscriptionId' => $subscription->id, 
								'clientSecret' => $subscription->latest_invoice->payment_intent->client_secret, 
								'customerId' => $customer->id 
							]; 

							// Insert to db
							srpwp_donations_save_to_db( 
								$customer_name, 
								$customer_email, 
								$srpwp_payment_type, 
								$srpwp_amount_val, 
								$customer->id, 
								$subscription->id, 
								current_time('mysql', 1),
								$output
							);
						}else{ 
							echo wp_send_json(['error' => $api_error]); 
						} 
					}else{ 
						echo wp_send_json(['error' => $api_error]); 
					} 
				} catch (Exception $e) {  
					$api_error = $e->getMessage(); 
				} 

			} else { 			
				echo wp_send_json(['error' => $api_error]); 
			}

			require_once STRIPE_PLUGIN_DIR_PATH . 'includes/email-template.php'; 
			srpwp_mail_function ( $customer_email, $customer_name, $srpwp_payment_type, $srpwp_amount_val );
		} else {
			try {   
				$paymentOneTime = \Stripe\PaymentIntent::create([ 
					'amount' => $planPriceCents, 
					'currency' => STRIPE_CURRENCY, 
					// 'product_data' => ['name' => $customer_name], 
				]);  
			}catch(Exception $e) {   
				$api_error = $e->getMessage(); 

				echo wp_send_json(['error' => $api_error]); 
			} 

			if(empty($api_error) && $paymentOneTime){ 
				// Insert to db
				srpwp_donations_save_to_db( 
					$customer_name, 
					$customer_email, 
					$srpwp_payment_type, 
					$srpwp_amount_val, 
					'n/a', 
					'n/a', 
					current_time('mysql', 1),
					['clientSecret' => $paymentOneTime->client_secret]
				);
			}

			require_once STRIPE_PLUGIN_DIR_PATH . 'includes/email-template.php'; 
			srpwp_mail_function ( $customer_email, $customer_name, $srpwp_payment_type, $srpwp_amount_val );
		}
		
		// echo wp_send_json( $response );
		wp_die();
	}
	}
}

// Saving admin datas
add_action('wp_ajax_srpwp_admin_panel_datas', 'srpwp_admin_panel_datas_callabck');
// Request form data handling
if ( ! function_exists( 'srpwp_admin_panel_datas_callabck' ) ) {
	function srpwp_admin_panel_datas_callabck() {
		$response = [];
		// Table design
		$srpwp_publishable_key = isset($_POST['srpwp_publishable_key']) ? sanitize_text_field($_POST['srpwp_publishable_key']) : '';
		$srpwp_secret_key = isset($_POST['srpwp_secret_key']) ? sanitize_text_field($_POST['srpwp_secret_key']) : '';

		// Settings array
		$srpwp_settings_array = [];
		$srpwp_settings_array['srpwp_publishable_key'] = $srpwp_publishable_key;
		$srpwp_settings_array['srpwp_secret_key'] = $srpwp_secret_key;

		// Saving the settings
		if (update_option( 'srpwp_settings_datas', $srpwp_settings_array )) {
			$response['status'] = 'success';
			$response['message'] = __('Settings has been updated!', 'stripe-recurring-payment-wpplugin' );
		} else {
			$response['status'] = 'warning';
			$response['message'] = __('Nothing to save!', 'stripe-recurring-payment-wpplugin');
		}
		echo wp_send_json( $response );
		wp_die();
	}
}
