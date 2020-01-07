<?php
global $post;

use WP_AFRAEX\core\Utility;
use WP_AFRAEX\core\WP_MAIL;

if ( is_user_logged_in() ) {

	if ( isset( $_GET['method'] ) and $_GET['method'] == "exit" ) {
		wp_logout();
		wp_redirect( add_query_arg( array( 'logout' => 'yes' ), get_page_link( $post->ID ) ) );
	} else {

		// List Pages
		$list_page = array(
			'new_order'     => array(
				'title' => __( "My Dashboard", "wp-afraex" ),
				'icon'  => 'ft-sliders'
			),
			'my_order'      => array(
				'title' => __( "My Exchange List", "wp-afraex" ),
				'icon' => 'ft-activity'
			),
			'new_bank_card' => array(
				'title' => __( "Add a bank card", "wp-afraex" ),
				'icon' => 'ft-credit-card'
			),
			'ticket'        => array(
				'title' => __( "Support Tickets", "wp-afraex" ),
				'icon' => 'ft-sunrise'
			),
			'profile'       => array(
				'title' => __( "My Account Information", "wp-afraex" ),
				'icon' => 'ft-user'
			),
			'exit'          => array(
				'title' => __( "logout", "wp-afraex" ),
				'icon' => 'ft-power'
			),
		);

		// Set Default Value
		if ( ! isset( $_GET['method'] ) || ( isset( $_GET['method'] ) and ! array_key_exists( $_GET['method'], $list_page ) ) ) {
			$_GET['method'] = 'new_order';
		}

		// Process Init in WordPress
		$error_text   = '';
		$success_text = '';
		include WP_AFRAEX::$plugin_path . '/templates/user-panel/process.php';

		// Load Template
		include WP_AFRAEX::$plugin_path . '/templates/user-panel/panel.php';
	}

} else {

	if ( isset( $_GET['method'] ) and $_GET['method'] == "register" ) {

		// Register Process
		$error_register = '';
		if ( isset( $_POST['wp_afraex_register_form'] ) || wp_verify_nonce( $_POST['wp_afraex_register_form'], 'wp_afraex_register_form' ) and isset( $_POST['first_name'] ) and ! empty( $_POST['first_name'] ) and isset( $_POST['last_name'] ) and ! empty( $_POST['last_name'] ) and isset( $_POST['user_email'] ) and ! empty( $_POST['user_email'] ) and isset( $_POST['user_pass'] ) and ! empty( $_POST['user_pass'] ) ) {

			//Check Exist User email
			if ( email_exists( trim( $_POST['user_email'] ) ) ) {
				$error_register = __( "This email has already been registered", "wp-afraex" );
			} else {

				$userdata = array(
					'user_login'           => trim( $_POST['user_email'] ),
					'user_email'           => trim( $_POST['user_email'] ),
					'first_name'           => sanitize_text_field( $_POST['first_name'] ),
					'last_name'            => sanitize_text_field( $_POST['last_name'] ),
					'show_admin_bar_front' => false,
					'display_name'         => sanitize_text_field( $_POST['first_name'] ) . ' ' . sanitize_text_field( $_POST['last_name'] ),
					'user_pass'            => $_POST['user_pass']
				);
				$user_id  = wp_insert_user( $userdata );
				if ( is_wp_error( $user_id ) ) {
					$error_register = $user_id->get_error_message();
				} else {

					// Send Email Notification
					$body = __( "Hi,", "wp-afraex" ) . ' ' . sanitize_text_field( $_POST['first_name'] ) . ' ' . sanitize_text_field( $_POST['last_name'] );
					$body .= '<br />';
					$body .= __( "Your registration has been successful", "wp-afraex" );
					$body .= '<br /><br />';
					$body .= __( "Email", "wp-afraex" ) . ': ' . trim( $_POST['user_email'] );
					$body .= '<br />';
					$body .= __( "Password", "wp-afraex" ) . ': ' . trim( $_POST['user_pass'] );
					Utility::send_mail( trim( $_POST['user_email'] ), __( "Register in Site", "wp-afraex" ), $body );

					// set the WP login cookie
					$secure_cookie = is_ssl() ? true : false;
					wp_set_auth_cookie( $user_id, true, $secure_cookie );

					// Redirect
					wp_redirect( get_page_link( $post->ID ) );
				}

			}
		}

		// Load Template
		include WP_AFRAEX::$plugin_path . '/templates/user-panel/register.php';

	} elseif ( isset( $_GET['method'] ) and $_GET['method'] == "forget" ) {

		// Process Forget
		$error_forget  = '';
		$success_alert = '';
		if ( isset( $_POST['wp_afraex_forget_form'] ) || wp_verify_nonce( $_POST['wp_afraex_forget_form'], 'wp_afraex_forget_form' ) and isset( $_POST['user_email'] ) ) {

			$user_id = email_exists( trim( $_POST['user_email'] ) );
			if ( ! $user_id ) {
				$error_forget = __( "No user found with this email", "wp-afraex" );
			} else {

				$user = get_userdata( $user_id );
				$key  = get_password_reset_key( $user );
				if ( is_wp_error( $user ) ) {
					$error_forget = $user->get_error_message();
				} else {

					// Send Email Notification
					$body = __( "Hi,", "wp-afraex" ) . ' ' . $user->first_name . ' ' . $user->last_name;
					$body .= '<br />';
					$body .= __( "Click the link below to recover your password", "wp-afraex" );
					$body .= '<br /><br />';
					$body .= '<a href="' . add_query_arg( array( 'method' => 'forget', 'key' => $key, 'login' => $user->user_login ), get_page_link( $post->ID ) ) . '">' . __( "View Link", "wp-afraex" ) . '</a>';
					Utility::send_mail( $user->user_email, __( "Recover Password", "wp - afraex" ), $body );

					// Set Alert
					$success_alert = __( "The password change link was sent to your email", "wp-afraex" );
				}
			}
		}

		// Process Reset Password
		if ( isset( $_POST['wp_afraex_reset_forget_form'] ) || wp_verify_nonce( $_POST['wp_afraex_reset_forget_form'], 'wp_afraex_reset_forget_form' ) and isset( $_POST['key'] ) and isset( $_POST['login'] ) and isset( $_POST['new_password'] ) ) {

			$check = check_password_reset_key( trim( $_POST['key'] ), trim( $_POST['login'] ) );
			if ( ! is_wp_error( $check ) ) {

				$user = get_user_by( 'login', $_POST['login'] );
				if ( ! empty( $user ) ) {
					wp_set_password( $_POST['new_password'], $user->ID );
					wp_redirect( add_query_arg( array( 'success_alert' => __( "Your password has been successfully changed", "wp-afraex" ) ), get_page_link( $post->ID ) ) );
				}
			}

		}


		// Load Template
		include WP_AFRAEX::$plugin_path . '/templates/user-panel/forget.php';

	} else {

		// Login Process
		$error_login = '';
		if ( isset( $_POST['wp_afraex_login_form'] ) || wp_verify_nonce( $_POST['wp_afraex_login_form'], 'wp_afraex_login_form' ) and isset( $_POST['user_login'] ) and isset( $_POST['user_pass'] ) and ! empty( $_POST['user_login'] ) and ! empty( $_POST['user_pass'] ) ) {
			$user = wp_signon( $credentials = array(
				'user_login'    => sanitize_text_field( $_POST['user_login'] ),
				'user_password' => sanitize_text_field( $_POST['user_pass'] ),
				'remember'      => true
			) );
			if ( is_wp_error( $user ) ) {
				$error_login = $user->get_error_message();
			} else {
				wp_redirect( get_page_link( $post->ID ) );
			}
		}

		// Load Template
		include WP_AFRAEX::$plugin_path . '/templates/user-panel/login.php';
	}


}
