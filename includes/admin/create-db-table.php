<?php

// Create the db table to store donations data
if ( ! function_exists( 'srpwp_donations_create_db_table' ) ) {
	function srpwp_donations_create_db_table() {
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();

		// SQL Query
		$sql = "CREATE TABLE `{$wpdb->base_prefix}srpwp_donations` (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		donor_name tinytext NOT NULL,
		donor_email tinytext NOT NULL,
		donation_type tinytext NOT NULL,
		donation_amount tinytext NOT NULL,
		customer_id tinytext NOT NULL,
		subscription_id tinytext NOT NULL,
		donation_time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		PRIMARY KEY (id)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
		
	}
}