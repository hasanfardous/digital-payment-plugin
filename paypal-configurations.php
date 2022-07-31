<?php

/* PayPal configuration 
 * Remember to switch to your live PayPal email in production! 
 */ 
$srpwp_current_page_url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
define('PAYPAL_ID', 'sb-zu4m012782203@business.example.com'); 
define('PAYPAL_SANDBOX', TRUE); //TRUE or FALSE  
  
define('PAYPAL_RETURN_URL', $srpwp_current_page_url.'?action=success');  
define('PAYPAL_CANCEL_URL', $srpwp_current_page_url.'?action=cancel');  
define('PAYPAL_NOTIFY_URL', get_site_url().'/thank-you-for-your-effort');  
define('PAYPAL_CURRENCY', 'USD');  
  
// Change not required  
define('PAYPAL_URL', (PAYPAL_SANDBOX == true)?"https://www.sandbox.paypal.com/cgi-bin/webscr":"https://www.paypal.com/cgi-bin/webscr"); 