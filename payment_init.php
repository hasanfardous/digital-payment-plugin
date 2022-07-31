<?php
$srpwp_settings_array = get_option('srpwp_settings_datas');
$srpwp_publishable_key = ($srpwp_settings_array['srpwp_publishable_key'] != '') ? $srpwp_settings_array['srpwp_publishable_key'] : 'XXXXXXXXXX';
// Include the Stripe PHP library 
require_once STRIPE_PLUGIN_DIR_PATH . 'stripe-php/init.php'; 
 
// Set API key 
\Stripe\Stripe::setApiKey($srpwp_publishable_key); 
 
// Retrieve JSON from POST body 
$jsonStr = file_get_contents('php://input'); 
$jsonObj = json_decode($jsonStr); 

echo json_encode(['error' => 'Transaction has been failed!']); 