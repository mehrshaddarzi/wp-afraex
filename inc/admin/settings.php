<?php

namespace WP_AFRAEX\admin;

use WP_AFRAEX\core\SettingAPI;

/**
 * Class Settings
 * @see https://github.com/tareq1988/wordpress-settings-api-class
 */
class Settings {

	/**
	 * Plugin Option name
	 */
	public $setting;

	/**
	 * The single instance of the class.
	 */
	protected static $_instance = null;

	/**
	 * Main Instance.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Admin_Setting_Api constructor.
	 */
	public function __construct() {
		/**
		 * Set Admin Setting
		 */
		add_action( 'admin_init', array( $this, 'init_option' ) );
	}

	/**
	 * Display the plugin settings options page
	 */
	public function setting_page() {

		echo '<div class="wrap">';
		settings_errors();

		$this->setting->show_navigation();
		$this->setting->show_forms();

		echo '</div>';
	}

	/**
	 * Registers settings section and fields
	 */
	public function init_option() {
		$sections = array(
			array(
				'id'    => 'wp_afraex_options',
				'title' => __( 'General', 'wp-afraex' )
			),
			array(
				'id'    => 'wp_afraex_email_opt',
				'desc'  => __( 'Basic email settings', 'wp-afraex' ),
				'title' => __( 'Email', 'wp-afraex' )
			),
			array(
				'id'    => 'wp_afraex_page_help',
				'title' => __( 'Help', 'wp-afraex' )
			),
		);

		// Get Users
		$user_list = array();
		$blogusers = get_users( [ 'role__in' => [ 'author', 'administrator' ] ] );
		foreach ( $blogusers as $user ) {
			$user_list[ $user->ID ] = $user->first_name . ' ' . $user->last_name;
		}

		// Get Currency List
		$currency_list_post = array();
		$args = array(
			'post_type'      => 'currency',
			'post_status'    => 'publish',
			'posts_per_page' => '-1',
			'order'          => 'ASC',
			'fields'         => 'ids'
		);
		$query = new \WP_Query( $args );
		foreach ( $query->posts as $ID ) {
			$currency_list_post[ $ID ] = get_the_title( $ID );
		}
		wp_reset_postdata();

		$fields = array(
			'wp_afraex_options'   => array(
				array(
					'name'    => 'logo',
					'label'   => __( 'Logo', 'wp-afraex' ),
					'desc'    => __( 'Dashboard Logo', 'wp-afraex' ),
					'type'    => 'file',
					'default' => '',
					'options' => array(
						'button_label' => __( 'Choose Image', 'wp-afraex' )
					)
				),
				array(
					'name'  => 'iranian_bank_list',
					'label' => __( 'Bank List', 'wp-afraex' ),
					'desc'  => __( 'Please enter a bank name on each line', 'wp-afraex' ),
					'type'  => 'textarea'
				),
				array(
					'name'    => 'chat_operator',
					'label'   => __( 'Chat operator user', 'wp-afraex' ),
					//'desc' => __( 'Dropdown description', 'wedevs' ),
					'type'    => 'select',
					'default' => 1,
					'options' => $user_list
				),
				array(
					'name'    => 'rial_currency',
					'label'   => __( 'Rial currency', 'wp-afraex' ),
					//'desc' => __( 'Dropdown description', 'wedevs' ),
					'type'    => 'select',
					'options' => $currency_list_post
				),




			),
			'wp_afraex_email_opt' => array(
				array(
					'name'    => 'from_email',
					'label'   => __( 'From Email', 'wp-reviews-insurance' ),
					'type'    => 'text',
					'default' => get_option( 'admin_email' )
				),
				array(
					'name'    => 'from_name',
					'label'   => __( 'From Name', 'wp-reviews-insurance' ),
					'type'    => 'text',
					'default' => get_option( 'blogname' )
				),
				array(
					'name'         => 'email_logo',
					'label'        => __( 'Email Logo', 'wp-reviews-insurance' ),
					'type'         => 'file',
					'button_label' => 'choose logo image'
				),
				array(
					'name'    => 'email_footer',
					'label'   => __( 'Email Footer Text', 'wp-reviews-insurance' ),
					'type'    => 'wysiwyg',
					'default' => 'All rights reserved',
				)
			),
			'wp_afraex_page_help' => array(
				array(
					'name'  => 'html_help_shortcode',
					'label' => __( 'Create Page', 'wp-afraex' ),
					'desc'  => __( 'Please Create a WordPress page and Set Page Template User Panel', 'wp-afraex' ),
					'type'  => 'html'
				)
			)
		);

		$this->setting = new SettingAPI();

		//set sections and fields
		$this->setting->set_sections( $sections );
		$this->setting->set_fields( $fields );

		//initialize them
		$this->setting->admin_init();
	}

}