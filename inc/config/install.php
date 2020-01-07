<?php

namespace WP_AFRAEX\config;

class install {
	/*
	 * install Plugin Method
	 */
	public static function run_install() {
		global $wpdb;

		// Load DB delta
		if ( ! function_exists( 'dbDelta' ) ) {
			require( ABSPATH . 'wp-admin/includes/upgrade.php' );
		}

		// Charset Collate
		$collate = $wpdb->get_charset_collate();

		// Create Bank Card
		$create_tbl = ( "
					CREATE TABLE `{$wpdb->prefix}bank_card` (
						`ID` bigint(200) UNSIGNED NOT NULL AUTO_INCREMENT,
						`user_id` BIGINT(200) NOT NULL, 
						`bank` TEXT NOT NULL, 
						`card` TEXT NOT NULL,
						`sheba` TEXT NOT NULL,
						PRIMARY KEY  (ID)
					) {$collate}" );
		dbDelta( $create_tbl );

		// Create User Notification
		$create_tbl = ( "
					CREATE TABLE `{$wpdb->prefix}user_notification` (
					`ID` bigint(200) UNSIGNED NOT NULL AUTO_INCREMENT,
					`user_id` BIGINT(200) NOT NULL,
					`title` TEXT NOT NULL,
					`content` TEXT NOT NULL,
					`icon` VARCHAR(100) NOT NULL,
					`created_at` DATETIME NOT NULL,
					`page` VARCHAR(100) NOT NULL,
					`arg` TEXT NOT NULL,
					`read` BIGINT(1) NOT NULL,
					PRIMARY KEY  (ID)
					) {$collate}" );
		dbDelta( $create_tbl );

		// Wp Chat Box
		$create_tbl = ( "
					CREATE TABLE `{$wpdb->prefix}chat_box` (
					`ID` bigint(200) UNSIGNED NOT NULL AUTO_INCREMENT,
					`created_at` DATETIME NOT NULL,
					`content_type` VARCHAR(30) NOT NULL COMMENT 'attachment | text',
					`content` TEXT NOT NULL, 
					`user_sender` BIGINT(200) NOT NULL, 
					`user_receiver` BIGINT(200) NOT NULL, 
					`read` INT(2) NOT NULL, 
					PRIMARY KEY  (ID)
					) {$collate}" );
		dbDelta( $create_tbl );


	}

}