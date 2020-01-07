<?php
global $wpdb;

/**
 * Change User Password
 */
if ( isset( $_POST['wp_afraex_change_pass_form'] ) and wp_verify_nonce( $_POST['wp_afraex_change_pass_form'], 'wp_afraex_reset_forget_form' ) and isset( $_POST['before_pass'] ) and isset( $_POST['new_pass'] ) and isset( $_POST['new_pass_2'] ) ) {

	$user  = get_userdata( get_current_user_id() );
	$error = false;

	// First Check Before Password
	$check_password = wp_check_password( trim( $_POST['before_pass'] ), $user->data->user_pass, $user->ID );
	if ( $check_password === false ) {
		$error      = true;
		$error_text = __( "The previous password is incorrect", "wp-afraex" );
	}

	// Check New Password is Same
	if ( $error === false and ( trim( $_POST['new_pass'] ) != trim( $_POST['new_pass_2'] ) ) ) {
		$error      = true;
		$error_text = __( "The new password you entered is not the same", "wp-afraex" );
	}

	// Set New Pass
	if ( $error === false ) {

		//wp_set_password( trim( $_POST['new_pass'] ), $user->ID );
		$update_user = wp_update_user( array( 'ID' => $user->ID, 'user_pass' => trim( $_POST['new_pass'] ) ) );
		if ( is_wp_error( $update_user ) ) {
			$error_text = $update_user->get_error_message();
		} else {
			$success_text = __( "New password changed successfully", "wp-afraex" );
		}
	}
}

/**
 * Change Profile Edit
 */
if ( isset( $_POST['wp_afraex_change_profile_form'] ) and wp_verify_nonce( $_POST['wp_afraex_change_profile_form'], 'wp_afraex_change_profile_form' ) and isset( $_POST['first_name'] ) and isset( $_POST['last_name'] ) and isset( $_POST['mobile'] ) ) {

	$user = get_userdata( get_current_user_id() );

	//wp_set_password( trim( $_POST['new_pass'] ), $user->ID );
	$update_user = wp_update_user( array(
		'ID'         => $user->ID,
		'first_name' => trim( $_POST['first_name'] ),
		'last_name'  => trim( $_POST['last_name'] ),
	) );
	if ( is_wp_error( $update_user ) ) {
		$error_text = $update_user->get_error_message();
	} else {
		update_user_meta( $user->ID, 'mobile', trim( $_POST['mobile'] ) );
		$success_text = __( "User info successfully edited", "wp-afraex" );
		\WP_AFRAEX\Helper::add_notification( array( 'icon' => 'ft-user', 'content' => $success_text ) );
	}

}

/**
 * Add Bank Card
 */
if ( isset( $_POST['wp_afraex_add_bank_card_form'] ) and wp_verify_nonce( $_POST['wp_afraex_add_bank_card_form'], 'wp_afraex_add_bank_card_form' ) and isset( $_POST['bank'] ) and isset( $_POST['card'] ) ) {
	$wpdb->insert(
		$wpdb->prefix . 'bank_card',
		array(
			'user_id' => get_current_user_id(),
			'bank'    => sanitize_text_field( $_POST['bank'] ),
			'card'    => sanitize_text_field( $_POST['card'] ),
			'sheba'   => sanitize_text_field( $_POST['sheba'] ),
		)
	);
	$success_text = __( "Bank card added successfully", "wp-afraex" );
}

/**
 * Edit Card Number
 */
if ( isset( $_POST['wp_afraex_edit_bank_card_form'] ) and wp_verify_nonce( $_POST['wp_afraex_edit_bank_card_form'], 'wp_afraex_edit_bank_card_form' ) and isset( $_POST['ID'] ) and isset( $_POST['bank'] ) and isset( $_POST['card'] ) ) {

	// Check For this User
	global $wpdb;
	$row = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}bank_card WHERE `ID` = " . trim( $_POST['ID'] ), ARRAY_A );
	if ( $row['user_id'] == get_current_user_id() ) {

		$wpdb->update(
			$wpdb->prefix . 'bank_card',
			array(
				'bank'  => sanitize_text_field( $_POST['bank'] ),
				'card'  => sanitize_text_field( $_POST['card'] ),
				'sheba' => sanitize_text_field( $_POST['sheba'] ),
			),
			array( 'ID' => $_POST['ID'] )
		);
		$success_text = __( "Bank card information successfully edited", "wp-afraex" );
	} else {
		$error_text = __( "Invalid request", "wp-afraex" );;
	}

}

/**
 * Remove Card ID
 */
if ( isset( $_GET['method'] ) and $_GET['method'] == "new_bank_card" and isset( $_GET['remove'] ) and is_numeric( $_GET['remove'] ) ) {

	// Check For this User
	global $wpdb;
	$row = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}bank_card WHERE `ID` = " . trim( $_GET['remove'] ), ARRAY_A );
	if ( $row['user_id'] == get_current_user_id() ) {
		$wpdb->delete( $wpdb->prefix . 'bank_card', array( 'ID' => $_GET['remove'] ) );
		$success_text = __( "Bank card successfully removed", "wp-afraex" );
	} else {
		$error_text = __( "Invalid request", "wp-afraex" );
	}

}

/**
 * Set All Notification as Read
 */
if ( isset( $_GET['_dashboard_notify_nonce'] ) and wp_verify_nonce( $_GET['_dashboard_notify_nonce'], 'set_all_notification_read' ) and isset( $_GET['action'] ) and $_GET['action'] == "set_all_notification_read" ) {
	\WP_AFRAEX\Helper::remove_all_notification( get_current_user_id() );
}

/**
 * Add Chat Attachment
 */
if ( ! defined( 'DOING_AJAX' ) and isset( $_POST['chat_box_send_attachment'] ) and wp_verify_nonce( $_POST['chat_box_send_attachment'], 'chat_box_send_attachment' ) and isset( $_POST['send_to'] ) and isset( $_FILES['attachment_file'] ) ) {

	// First Upload File
	require_once( ABSPATH . 'wp-admin/includes/image.php' );
	require_once( ABSPATH . 'wp-admin/includes/file.php' );
	require_once( ABSPATH . 'wp-admin/includes/media.php' );

	// Let WordPress handle the upload.
	// Remember, 'my_image_upload' is the name of our file input in our form above.
	$attachment_id = media_handle_upload( 'attachment_file', 0 );
	if ( ! is_wp_error( $attachment_id ) ) {
		$wpdb->insert(
			$wpdb->prefix . 'chat_box',
			array(
				'created_at'    => current_time( 'mysql' ),
				'content_type'  => 'attachment',
				'content'       => $attachment_id,
				'user_sender'   => get_current_user_id(),
				'user_receiver' => $_POST['send_to'],
				'read'          => 0,
			)
		);
		header( "Location: " . $_POST['redirect'] . "&_=" . time() );
	}
}

/**
 * Add Chat PM
 */
if ( ! defined( 'DOING_AJAX' ) and isset( $_POST['chat_box_send_pm'] ) and wp_verify_nonce( $_POST['chat_box_send_pm'], 'chat_box_send_pm' ) and isset( $_POST['send_to'] ) and isset( $_POST['msg'] ) ) {
	$wpdb->insert(
		$wpdb->prefix . 'chat_box',
		array(
			'created_at'    => current_time( 'mysql' ),
			'content_type'  => 'text',
			'content'       => trim( $_POST['msg'] ),
			'user_sender'   => get_current_user_id(),
			'user_receiver' => $_POST['send_to'],
			'read'          => 0,
		)
	);
	header( "Location: " . $_POST['redirect'] . "&_=" . time() );
}


/**
 * Add New Order
 */
if ( ! defined( 'DOING_AJAX' ) and isset( $_POST['create_new_order_currency'] ) and wp_verify_nonce( $_POST['create_new_order_currency'], 'create_new_order_currency' ) and isset( $_POST['from_currency'] ) and isset( $_POST['to_currency'] ) and isset( $_POST['from_currency_price'] ) and isset( $_POST['wallet'] ) ) {
	global $post;

	// Calculate
	$convert_price = get_post_meta( $_REQUEST['from_currency'], "currency_" . $_REQUEST['to_currency'], true );
	$calculate     = sanitize_text_field( $_POST['from_currency_price'] ) * $convert_price;

	// Wallet
	$wallet           = sanitize_text_field( $_POST['wallet'] );
	$opt              = get_option( 'wp_afraex_options' );
	$rial_currency_id = $opt['rial_currency'];
	if ( $_POST['to_currency'] == $rial_currency_id ) {
		$wallet = $_POST['wallet_select'];
	}

	// Insert Post
	$my_post  = array(
		'post_title'   => current_time( 'timestamp' ),
		'post_content' => '',
		'post_author'  => 1,
		'post_status'  => 'doing',
		'post_type'    => 'currency_order',
		'meta_input'   => array(
			'order_user_id'             => get_current_user_id(),
			'order_currency_from'       => sanitize_text_field( $_POST['from_currency'] ),
			'order_currency_from_price' => sanitize_text_field( $_POST['from_currency_price'] ),
			'order_currency_to'         => sanitize_text_field( $_POST['to_currency'] ),
			'order_currency_calculate'  => number_format( $calculate ),
			'order_user_wallet'         => $wallet,
		),
	);
	$order_id = wp_insert_post( $my_post );

	// Update Post Title
	$my_post = array(
		'ID'         => $order_id,
		'post_title' => __( "Order", "wp-afraex" ) . ' #' . $order_id,
	);
	wp_update_post( $my_post );

	// Redirect
	$url = add_query_arg( array( 'method' => 'my_order', 'alert' => 'new', "meta" => $order_id ), get_page_link( $post->ID ) );
	wp_redirect( $url );
	exit;
}


