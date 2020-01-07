<?php

namespace WP_AFRAEX;

use WP_AFRAEX;

/*
 * Ajax Method wordpress
 */

class Ajax {

	/**
	 * Ajax constructor.
	 */
	public function __construct() {

		$list_function = array(
			'get_new_chat_item',
			'convert_currency_price',
		);

		foreach ( $list_function as $method ) {
			add_action( 'wp_ajax_' . $method, array( $this, $method ) );
			add_action( 'wp_ajax_nopriv_' . $method, array( $this, $method ) );
		}

	}

	public function get_new_chat_item() {
		global $wpdb;
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			if ( isset( $_REQUEST['user'] ) and isset( $_REQUEST['with'] ) ) {
				$last_id = (int) $wpdb->get_var( "SELECT `ID` FROM {$wpdb->prefix}chat_box WHERE ((`user_receiver` = {$_REQUEST['user']} AND `user_sender` = {$_REQUEST['with']}) OR (`user_receiver` = {$_REQUEST['with']} AND `user_sender` = {$_REQUEST['user']})) ORDER BY ID DESC LIMIT 1" );
				WP_AFRAEX\core\utility::json_exit( array( 'last_id' => $last_id ) );
			}
		}
		die();
	}


	public function convert_currency_price() {
		global $wpdb;
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {

			if ( isset( $_REQUEST['from_currency'] ) and isset( $_REQUEST['to_currency'] ) and isset( $_REQUEST['from_currency_price'] ) ) {

				// Check Empty Validate
				$from_currency_price = sanitize_text_field( $_REQUEST['from_currency_price'] );
				if ( empty( $from_currency_price ) ) {
					WP_AFRAEX\core\utility::json_exit( array( 'error' => 'yes', 'content' => __( "Please enter the amount of currency", "wp-afraex" ) ) );
				}

				// Check Currency ID
				if ( WP_AFRAEX\core\utility::post_exist( $_REQUEST['from_currency'], 'currency' ) === false OR WP_AFRAEX\core\utility::post_exist( $_REQUEST['to_currency'], 'currency' ) === false ) {
					WP_AFRAEX\core\utility::json_exit( array( 'error' => 'yes', 'content' => __( "Invalid request", "wp-afraex" ) ) );
				}

				// Check Number
				//if ( self::is_numeric( $from_currency_price ) === false ) {
				//	WP_AFRAEX\core\utility::json_exit( array( 'error' => 'yes', 'content' => __( "The currency value should only be a number", "wp-afraex" ) ) );
				//}

				// Convert Data
				if($_REQUEST['to_currency'] ==$_REQUEST['from_currency']) {
					$calculate = $from_currency_price;
				} else {

					$convert_price = get_post_meta( $_REQUEST['from_currency'], "currency_" . $_REQUEST['to_currency'], true );
					if ( empty( $convert_price ) ) {
						WP_AFRAEX\core\utility::json_exit( array( 'error' => 'yes', 'content' => __( "Invalid request", "wp-afraex" ) ) );
					}

					// Calculate Price
					$calculate = $from_currency_price * $convert_price;
				}

				// Return
				WP_AFRAEX\core\utility::json_exit( array( 'error' => 'no', 'price' => number_format( $calculate ) ) );
			}
		}
		die();
	}

	public static function is_numeric( $value ) {
		if ( preg_match( '/^.[0-9]+$/', $value ) ) {
			return true;
		}

		return false;
	}

}