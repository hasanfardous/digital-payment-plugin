<?php

// Admin menu page
add_action( 'admin_menu', 'srpwp_adding_admin_menu_page' );
if ( ! function_exists( 'srpwp_adding_admin_menu_page' ) ) {
	function srpwp_adding_admin_menu_page() {
		add_menu_page(
			__( 'Stripe Payment Settings', 'woo-product-table-cd' ),
			__( 'Stripe Payment Settings', 'woo-product-table-cd' ),
			'manage_options',
			'product-table-settings',
			'srpwp_product_table_settings_callback',
			'dashicons-editor-table',
			6
		);
	}
}

// Admin notice function
if ( ! function_exists( 'srpwp_show_admin_notice' ) ) {
	function srpwp_show_admin_notice( $message, $type )  {
		echo "
			<div class='srpwp_show_admin_notice notice notice-{$type} is-dismissible'>
				<p>" . __("{$message}", 'woo-product-table-cd') . "</p>
			</div>
		";
	}
}

// Settings page callback function
if ( ! function_exists( 'srpwp_product_table_settings_callback' ) ) {
	function srpwp_product_table_settings_callback() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$srpwp_settings_datas = get_option('srpwp_settings_datas');
		// echo '<pre>';
		// print_r($srpwp_settings_datas);

		// Table design
		$srpwp_publishable_key = isset($srpwp_settings_datas['srpwp_publishable_key']) ? $srpwp_settings_datas['srpwp_publishable_key'] : '';
		$srpwp_secret_key = isset($srpwp_settings_datas['srpwp_secret_key']) ? $srpwp_settings_datas['srpwp_secret_key'] : '';
		?>
		<div class="wrap srpwp-admin-wrapper">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

			<div class="srpwp-add-product-feature-requests-wrapper">
				<div class="srpwp-section-content">
					<form method="post" class="srpwp-feature-request-form">
						<div class="tab-content">
							<div id="tabs">
								<ul>
									<li><a href="#tabs-1">Configurations</a></li>
									<li><a href="#tabs-2">Donations</a></li>
								</ul>
								<div id="tabs-1">
									<h2 class="title">Configurations</h2>
									
									<p>Use the shortcode <kbd>[srpwp]</kbd> for displaying the donation form anywhere. <a href="https://dashboard.stripe.com/test/apikeys" target="_blank">Get your API Keys</a> from the Stripe for setting up the plugin.</p>
									<br>
									<h2 class="title">Stripe Settings</h2>	
									<table class="form-table table-design-form">
										<tbody>
											<tr>
												<th scope="row">
													<label for="srpwp_publishable_key">Publishable key</label>
												</th>
												<td>
													<input name="srpwp_publishable_key" type="text" id="srpwp_publishable_key" class="regular-text" value="<?php echo esc_attr($srpwp_publishable_key)?>">
												</td>
											</tr>
											<tr>
												<th scope="row">
													<label for="srpwp_secret_key">Secret key</label>
												</th>
												<td>
													<input name="srpwp_secret_key" type="text" id="srpwp_secret_key" class="regular-text" value="<?php echo esc_attr($srpwp_secret_key)?>">
												</td>
											</tr>

											<tr>
												<th>
													<h2 class="title">Paypal Settings</h2>
												</th>
											</tr>

											<tr>
												<th scope="row">
													<label for="srpwp_publishable_key">Publishable key</label>
												</th>
												<td>
													<input name="srpwp_publishable_key" type="text" id="srpwp_publishable_key" class="regular-text" value="<?php echo esc_attr($srpwp_publishable_key)?>">
												</td>
											</tr>
											<tr>
												<th scope="row">
													<label for="srpwp_secret_key">Secret key</label>
												</th>
												<td>
													<input name="srpwp_secret_key" type="text" id="srpwp_secret_key" class="regular-text" value="<?php echo esc_attr($srpwp_secret_key)?>">
												</td>
											</tr>
										</tbody>
									</table>
									<p class="submit"><input type="submit" name="settingsSubmitBtn" id="submit" class="button button-primary" value="Save Changes"></p>
								</div>
								<div id="tabs-2">
									<h2 class="title">Donations</h2>
									<table class="form-table">
										<tbody>
											<?php
												// Display latest applicants
												global $wpdb;
												$table_name  	  = "{$wpdb->base_prefix}srpwp_donations";
												$count_query 	  = $wpdb->get_results( "SELECT * FROM {$table_name}" );
												$total_submissons = count($count_query);
												$all_submissions  = $wpdb->get_results( "SELECT * FROM {$table_name} ORDER BY id DESC LIMIT 5", ARRAY_A );

												if ( $total_submissons == 0 ) {
													echo "<tr>".__( "Sorry! No Entry Found.", "job-app-manager" )."</tr>";
												} else {
													foreach ( $all_submissions as $item ) {
														echo "
														<tr>
															<th scope='row'>
																{$item['donor_name']}
															</th>
															<td>
																<p>\${$item['donation_amount']} - {$item['donation_type']} payment</p>
																<p class='description'>{$item['donation_time']}</p>
															</td>
														</tr>";
													}
												}
											?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<?php
	}
}