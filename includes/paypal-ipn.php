<?php
// Paypal payment confirmation content shortcode
add_action( 'init', 'srpwp_paypal_ipn_shortcode_callback' );

function srpwp_paypal_ipn_shortcode_callback() {
    add_shortcode( 'srpwp_ipn', 'srpwp_paypal_ipn_shortcode' );
}
function srpwp_paypal_ipn_shortcode() {
    // ob_start();
 
    /* 
    * Read POST data 
    * reading posted data directly from $_POST causes serialization 
    * issues with array data in POST. 
    * Reading raw POST data from input stream instead. 
    */         
    $raw_post_data = file_get_contents('php://input'); 
    echo $raw_post_data;
    // wp_die();
    $raw_post_array = explode('&', $raw_post_data); 
    $myPost = array(); 
    foreach ($raw_post_array as $keyval) { 
        $keyval = explode ('=', $keyval); 
        if (count($keyval) == 2) 
            $myPost[$keyval[0]] = urldecode($keyval[1]); 
    } 
    
    // Read the post from PayPal system and add 'cmd' 
    $req = 'cmd=_notify-validate'; 
    foreach ($myPost as $key => $value) { 
        $value = urlencode($value); 
        $req .= "&$key=$value"; 
    } 
    
    /* 
    * Post IPN data back to PayPal to validate the IPN data is genuine 
    * Without this step anyone can fake IPN data 
    */ 
    $paypalURL = PAYPAL_URL; 
    $ch = curl_init($paypalURL); 
    if ($ch == FALSE) { 
        return FALSE; 
    } 
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1); 
    curl_setopt($ch, CURLOPT_POST, 1); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); 
    curl_setopt($ch, CURLOPT_POSTFIELDS, $req); 
    curl_setopt($ch, CURLOPT_SSLVERSION, 6); 
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1); 
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); 
    curl_setopt($ch, CURLOPT_FORBID_REUSE, 1); 
    
    // Set TCP timeout to 30 seconds 
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30); 
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close', 'User-Agent: company-name')); 
    $res = curl_exec($ch); 
    
    /* 
    * Inspect IPN validation result and act accordingly 
    * Split response headers and payload, a better way for strcmp 
    */  
    $tokens = explode("\r\n\r\n", trim($res)); 
    $res = trim(end($tokens)); 

    print_r($res);
    if ( isset($_POST['payment_status']) && $_POST['payment_status'] == 'Completed' ) {
        $srpwp_payer_id = sanitize_text_field($_POST['payer_id']);
        $srpwp_first_name = sanitize_text_field($_POST['first_name']);
        $srpwp_donor_email = sanitize_text_field($_POST['payer_email']);
        $srpwp_txn_id = sanitize_text_field($_POST['txn_id']);
        $srpwp_mc_gross = sanitize_text_field($_POST['mc_gross']);
        $srpwp_payment_type = sanitize_text_field($_POST['custom']);
        // $custom_values = explode(',', $srpwp_custom);
        // $srpwp_donation_type = $custom_values[0];
        // $srpwp_donor_name = $custom_values[1];
        // $srpwp_donor_email = $custom_values[2];

        // Inserting to db
        global $wpdb;
        $table_name = $wpdb->base_prefix.'srpwp_donations';
        $submission_insert = $wpdb->insert( 
            $table_name,
            array( 
                'donor_name' 		=> $srpwp_first_name, 
                'donor_email' 		=> $srpwp_donor_email, 
                'donation_type' 	=> $srpwp_payment_type, 
                'donation_amount'	=> $srpwp_mc_gross, 
                'customer_id' 		=> $srpwp_payer_id, 
                'subscription_id' 	=> $srpwp_txn_id, 
                'donation_time' 	=> current_time('mysql', 1),
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
            echo '<h1>Thank you so much for your donation, we just received it.</h1>';
        } else {
            echo '<h1>Sorry! There is a problem please contact with the admin.</h1>';
        }
    }
    // $form_html = ob_get_clean();
	// return $form_html;
}