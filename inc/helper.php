<?php

namespace WP_AFRAEX;

/**
 * Class Helper Used in Custom Helper Method For This Plugin
 */
class Helper {

	public static function add_notification( $arg = array() ) {
		global $wpdb;

		$defaults = array(
			'user_id'    => get_current_user_id(),
			'title'      => '',
			'content'    => '',
			'icon'       => '',
			'created_at' => current_time( 'mysql' ),
			'page'       => '',
			'arg'        => '',
			'read'       => 0
		);
		$args     = wp_parse_args( $arg, $defaults );

		$wpdb->insert( $wpdb->prefix . 'user_notification', $args );
		return $wpdb->insert_id;
	}

	public static function remove_notification( $ID ) {
		global $wpdb;
		$wpdb->delete( $wpdb->prefix . 'user_notification', array( 'ID' => $ID ) );
	}

	public static function remove_all_notification( $user_id ) {
		global $wpdb;
		$wpdb->query( "DELETE FROM `{$wpdb->prefix}user_notification` WHERE `user_id` = {$user_id}" );
	}

}