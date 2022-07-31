<?php

/**
 * Plugin Name:       Digital Payment Gateway WP Plugin
 * Plugin URI:        https://me.hasanfardous.com/stripe-recurring-payment-wpplugin
 * Description:       The digital payment gateway WP plugin makes it easier for donors to make the donation by one time or subscription basis.
 * Version:           1.0.0
 * Requires at least: 5.5
 * Requires PHP:      7.2
 * Author:            Hasanfardous
 * Author URI:        https://me.hasanfardous.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       stripe-recurring-payment-wpplugin
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'STRIPE_RECURRING_PAYMENT_WP_PLUGIN_VERSION', '1.0.0' );
define( 'STRIPE_PLUGIN_DIR_URL', plugin_dir_url(__FILE__) );
define( 'STRIPE_PLUGIN_DIR_PATH', plugin_dir_path(__FILE__) );
define('STRIPE_CURRENCY', 'USD'); 
//2ZUG@zSvd&*d)BS
function srpwp_load_textdomain() {
	load_plugin_textdomain( 'stripe-recurring-payment-wpplugin', false, dirname( __FILE__ ) . "/languages" );
}

add_action( "plugins_loaded", "srpwp_load_textdomain" );

// Enqueue Front-end scripts
add_action( 'wp_enqueue_scripts', 'srpwp_enqueue_scripts', 99 );
function srpwp_enqueue_scripts() {
	$srpwp_settings_array = get_option('srpwp_settings_datas');
	$srpwp_publishable_key = ($srpwp_settings_array['srpwp_publishable_key'] != '') ? $srpwp_settings_array['srpwp_publishable_key'] : 'XXXXXXXXXX';
	// Load CSS
	wp_enqueue_style( 'srpwp-bootstrap-css', plugins_url( 'assets/css/bootstrap.min.css', __FILE__ ) );
	wp_enqueue_style( 'srpwp-font-awesome-css', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css' );
	wp_enqueue_style( 'srpwp-styles', plugins_url( 'assets/css/styles.css', __FILE__ ), '', time() );
	
	// Load JS
	// wp_enqueue_script( 'srpwp-bootstrap-js', plugins_url( 'assets/js/bootstrap.min.js', __FILE__ ), array( 'jquery' ), '1.0', false );
	wp_enqueue_script( 'srpwp-bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js', array('jquery'), '5.0.2' );
	wp_enqueue_script( 'srpwp-stripe-js', 'https://js.stripe.com/v3/', array(), '1.0' );
	wp_enqueue_script( 'srpwp-custom-script-frontend', plugins_url( 'assets/js/scripts.js', __FILE__ ), array('jquery', 'srpwp-stripe-js'), '1.0', true);
	wp_localize_script(
		'srpwp-custom-script-frontend', 
		'srpwp_admin_datas', 
		array(
			// 'nonce' => wp_create_nonce( 'srpwp-add-to-cart-nonce' ),
			'srpwp_plugin_dir_url' => plugin_dir_url(__FILE__),
			'srpwp_publishable_key' => $srpwp_publishable_key,
			'ajax_url' 				=> admin_url( 'admin-ajax.php' )
		) 
	);
}

// Enqueue back-end scripts
add_action( 'admin_enqueue_scripts', 'srpwp_admin_enqueue_scripts', 99 );
function srpwp_admin_enqueue_scripts() {
	wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_style( 'srpwp-jquery-ui-styles', plugins_url( 'includes/admin/assets/css/jquery-ui.css', __FILE__ ), array(), '1.13.1' );
	wp_enqueue_style( 'srpwp-admin-styles', plugins_url( 'includes/admin/assets/css/styles.css', __FILE__ ) );
	wp_enqueue_script("jquery-ui-tabs");
	wp_enqueue_script( 'srpwp-custom-script', plugins_url( 'includes/admin/assets/js/script.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
	wp_localize_script(
		'srpwp-custom-script', 
		'srpwp_admin_panel_datas', 
		array(
			// 'nonce' => wp_create_nonce( 'srpwp-add-to-cart-nonce' ),
			'ajax_url' => admin_url( 'admin-ajax.php' )
		) 
	);
}

/**
 * Including plugin files
 */
require plugin_dir_path( __FILE__ ) . 'paypal-configurations.php';
require plugin_dir_path( __FILE__ ) . 'includes/shortcode.php';
require plugin_dir_path( __FILE__ ) . 'includes/paypal-ipn.php';
require plugin_dir_path( __FILE__ ) . 'includes/form-handling.php';
require plugin_dir_path( __FILE__ ) . 'includes/admin/admin-menu-page.php';
require plugin_dir_path( __FILE__ ) . 'includes/admin/create-db-table.php';

// Default plugin options
function srpwp_set_default_options(){
	$srpwp_settings_array 									= [];
	$srpwp_settings_array['srpwp_publishable_key'] 			= 'xxxxxxxxxx';
	$srpwp_settings_array['srpwp_secret_key'] 				= 'xxxxxxxxxx';
	$srpwp_settings_array['srpwp_paypal_business_email'] 	= 'xxxxxxxxxx';
	// Updating default settings
	update_option( 'srpwp_settings_datas', $srpwp_settings_array );
}

function srpwp_auto_page_creation() {
	$page_title = wp_strip_all_tags( 'Thank you for your effort' );
	// Create post object
    $my_post = array(
		'post_title'    => $page_title,
		'post_content'  => '[srpwp_ipn]',
		'post_status'   => 'publish',
		'post_author'   => 1,
		'post_type'     => 'page',
	  );
  
	  // Insert the post into the database
	  if ( ! post_exists( $page_title ) ) {
		wp_insert_post( $my_post );
	  }
}

/**
 * The code that runs during plugin activation.
 */
register_activation_hook( __FILE__, 'srpwp_create_db_table' );
if ( ! function_exists( 'srpwp_create_db_table' ) ) {
	function srpwp_create_db_table() {
		// Saving our plugin current version
		add_option( "stripe_recurring_payment_wp_plugin_version", STRIPE_RECURRING_PAYMENT_WP_PLUGIN_VERSION );

		// Auto page creation
		srpwp_auto_page_creation();

		// Set default table options
		srpwp_set_default_options();

		// Create the db table to store donations data
		srpwp_donations_create_db_table();
	}
}

?>