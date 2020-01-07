<?php

namespace WP_AFRAEX\admin;

use WP_AFRAEX;

class Admin {

	/**
	 * Admin Page slug
	 */
	public static $admin_page_slug;

	/**
	 * Admin_Page constructor.
	 */
	public function __construct() {
		/*
		 * Set Page slug Admin
		 */
		self::$admin_page_slug = 'wp-admin';
		/*
		 * Setup Admin Menu
		 */
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		/*
		 * Register Script in Admin Area
		 */
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_assets' ) );
		/**
		 * Disable Admin Bar
		 */
		add_action( 'init', array( $this, 'hide_admin_bar' ) );
		/**
		 * add Column in Users.php
		 */
		add_filter( 'manage_users_columns', array( $this, 'pippin_add_user_id_column' ) );
		add_action( 'manage_users_custom_column', array( $this, 'pippin_show_user_id_column_content' ), 10, 3 );
		/**
		 * Register Post Type
		 */
		add_action( 'init', array( $this, 'register_post_type' ) );
		add_filter( 'enter_title_here', array( $this, 'custom_enter_title' ) );
		/**
		 * Add Meta Box
		 */
		add_action( 'add_meta_boxes', array( $this, 'create_meta_box' ) );
		add_action( 'save_post_currency', array( $this, 'save_metabox' ), 10, 2 );
		/**
		 * Add Post Type Custom Column
		 */
		add_action( 'manage_posts_custom_column', array( $this, 'column_post_table' ), 10, 2 );
		add_filter( 'manage_currency_order_posts_columns', array( $this, 'column_currency_order' ) );
		/**
		 * Custom Status
		 */
		add_filter( 'wp_statuses_get_registered_post_types', array( $this, 'example_restrict_statuses_for_tickets' ), 10, 2 );
		add_action( 'transition_post_status', array( $this, 'wpdocs_run_on_publish_only' ), 10, 3 );
		/**
		 * Define new Gravatar
		 */
		add_filter( 'avatar_defaults', array( $this, 'wp_my_new_gravatar' ) );
	}

	function wp_my_new_gravatar( $avatar_defaults ) {
		$myavatar                     = WP_AFRAEX::$plugin_url . '/asset/user.png';
		$avatar_defaults[ $myavatar ] = "my_new_gravatar_currency";
		return $avatar_defaults;
	}

	function wpdocs_run_on_publish_only( $new_status, $old_status, $post ) {

		if ( 'currency_order' != $post->post_type ) {
			return;
		}

		if ( $new_status == $old_status ) {
			return;
		}

		if ( $new_status == "draft" || $old_status == "draft" ) {
			return;
		}

		if ( $new_status == "doing" ) {
			return;
		}

		$user_id = get_post_meta( $post->ID, 'order_user_id', true );
		$old_s   = get_post_status_object( $old_status );
		$new_s   = get_post_status_object( $new_status );

		$success_text = sprintf(
			__( 'Your order status with %1$s ID Changed from %2$s to %3$s', "wp-afraex" ),
			$post->ID,
			$old_s->label,
			$new_s->label
		);
		\WP_AFRAEX\Helper::add_notification( array(
			'icon'    => 'ft-activity',
			'content' => $success_text,
			'user_id' => $user_id,
		) );
	}

	function example_restrict_statuses_for_tickets( $post_types = array(), $status_name = '' ) {
		if ( 'draft' === $status_name ) {
			return $post_types;
		}
		return array_diff( $post_types, array( 'currency_order' ) );
	}

	/**
	 * @see https://wordpress.stackexchange.com/questions/50043/how-to-determine-whether-we-are-in-add-new-page-post-cpt-or-in-edit-page-post-cp
	 * @param null $new_edit
	 * @return bool
	 */
	public static function is_edit_page( $new_edit = null ) {
		global $pagenow;
		//make sure we are on the backend
		if ( ! is_admin() ) {
			return false;
		}
		if ( $new_edit == "edit" ) {
			return in_array( $pagenow, array( 'post.php', ) );
		} elseif ( $new_edit == "new" ) //check for new post page
		{
			return in_array( $pagenow, array( 'post-new.php' ) );
		} else //check for either new or edit
		{
			return in_array( $pagenow, array( 'post.php', 'post-new.php' ) );
		}
	}

	/**
	 * Create Meta Box
	 */
	public function create_meta_box() {
		if ( ! self::is_edit_page( "edit" ) ) {
			return;
		}
		add_meta_box( 'wp_afraex_change_currency', __( 'The ratio of units to each other', "wp-afraex" ), array( $this, 'curreny_metabox' ), 'currency', 'normal', 'low' );
	}

	public function curreny_metabox() {
		global $post, $wpdb;

		//Nonce Security
		wp_nonce_field( 'set_currency_change', 'set_currency_change' );
		$current_title   = get_the_title( $post->ID );
		$current_post_id = $post->ID;

		// List
		echo '
        <table class="form-table" dir="ltr">
	    <tbody>
	    ';

		$query = new \WP_Query( array(
			'post__not_in'   => array( $post->ID ),
			'post_type'      => 'currency',
			'post_status'    => 'publish',
			'posts_per_page' => '-1',
			'order'          => 'DESC',
			'orderby'        => 'ID'
		) );
		$count = $query->post_count;
		$i     = 1;
		while ( $query->have_posts() ):
			$query->the_post();

			echo '
		<tr>
		<th scope="row" style="text-align: left;"> 1 ' . $current_title . ' = </th>
		<td style="text-align: left;">
		<input type="text" name="currency_' . get_the_ID() . '" value="' . get_post_meta( $current_post_id, 'currency_' . get_the_ID(), true ) . '" class="regular-text">
		' . get_the_title() . '
        </td>
        </tr>
        ';

		endwhile;
		wp_reset_postdata();

		echo '
		</tbody>
        </table>
        ';
	}

	public function save_metabox( $post_id, $post ) {
		global $wpdb;
		/*
		 * Check User Not Permission
		 */
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}
		/*
		   * verify if this is an auto save routine.
		   */
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}
		/*
		 * check Isset Post Requet
		 */
		if ( ! isset( $_POST['set_currency_change'] ) || ! wp_verify_nonce( $_POST['set_currency_change'], 'set_currency_change' ) ) {
			return $post_id;
		}

		// Save
		$query = new \WP_Query( array(
			'post__not_in'   => array( $post_id ),
			'post_type'      => 'currency',
			'post_status'    => 'publish',
			'posts_per_page' => '-1',
			'order'          => 'DESC',
			'orderby'        => 'id'
		) );
		$count = $query->post_count;
		$i     = 1;
		while ( $query->have_posts() ):
			$query->the_post();
			$v = 0;
			if ( isset( $_POST[ 'currency_' . get_the_ID() ] ) and ! empty( $_POST[ 'currency_' . get_the_ID() ] ) ) {
				$v = trim( $_POST[ 'currency_' . get_the_ID() ] );
			}
			update_post_meta( $post_id, 'currency_' . get_the_ID(), $v );
		endwhile;
		wp_reset_postdata();
	}

	public function custom_enter_title( $input ) {
		if ( 'currency' === get_post_type() ) {
			return __( 'Please enter the currency symbol name', 'wp-afraex' );
		}
		return $input;
	}

	/**
	 * Register Post Type
	 */
	public function register_post_type() {

		$labels = array(
			'name'               => __( 'Currency', "wp-afraex" ),
			'singular_name'      => __( 'Currency', "wp-afraex" ),
			'add_new'            => __( 'New Currency', "wp-afraex" ),
			'add_new_item'       => __( 'Add New Currency', "wp-afraex" ),
			'edit_item'          => __( 'Edit Currency', "wp-afraex" ),
			'new_item'           => __( 'New Currency', "wp-afraex" ),
			'all_items'          => __( 'All Currency', "wp-afraex" ),
			'view_item'          => __( 'Show Currency', "wp-afraex" ),
			'search_items'       => __( 'Search in Currency', "wp-afraex" ),
			'not_found'          => __( 'Not found Any Currency', "wp-afraex" ),
			'not_found_in_trash' => __( 'Not found any Currency in Trash', "wp-afraex" ),
			'parent_item_colon'  => __( 'Parent Currency', "wp-afraex" ),
			'menu_name'          => __( 'Currencies', "wp-afraex" ),
		);
		$args   = array(
			'labels'              => $labels,
			'description'         => __( 'Currency', "wp-afraex" ),
			'public'              => false,
			'menu_position'       => 5,
			'has_archive'         => true,
			'show_in_admin_bar'   => false,
			'hierarchical'        => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'menu_icon'           => 'dashicons-feedback',
			'can_export'          => true,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'capability_type'     => 'page',
			/* support */
			'supports'            => array( 'title' ),
			/* Rewrite */
			'rewrite'             => array( 'slug' => 'currency' ),
			/* Rest Api */
			//'show_in_rest'          => true,
			//'rest_base'             => 'shortlink_api',
			//'rest_controller_class' => 'WP_REST_Posts_Controller',
		);
		register_post_type( 'currency', $args );

		$labels = array(
			'name'               => __( 'Order', "wp-afraex" ),
			'singular_name'      => __( 'Order', "wp-afraex" ),
			'add_new'            => __( 'New Order', "wp-afraex" ),
			'add_new_item'       => __( 'Add New Order', "wp-afraex" ),
			'edit_item'          => __( 'Edit Order', "wp-afraex" ),
			'new_item'           => __( 'New Order', "wp-afraex" ),
			'all_items'          => __( 'All Order', "wp-afraex" ),
			'view_item'          => __( 'Show Order', "wp-afraex" ),
			'search_items'       => __( 'Search in Order', "wp-afraex" ),
			'not_found'          => __( 'Not found Any Order', "wp-afraex" ),
			'not_found_in_trash' => __( 'Not found any Order in Trash', "wp-afraex" ),
			'parent_item_colon'  => __( 'Parent Order', "wp-afraex" ),
			'menu_name'          => __( 'Orders', "wp-afraex" ),
		);
		$args   = array(
			'labels'              => $labels,
			'description'         => __( 'Order', "wp-afraex" ),
			'public'              => false,
			'menu_position'       => 5,
			'has_archive'         => true,
			'show_in_admin_bar'   => false,
			'hierarchical'        => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'menu_icon'           => 'dashicons-cart',
			'can_export'          => true,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'capability_type'     => 'page',

			// https://stackoverflow.com/questions/3235257/wordpress-disable-add-new-on-custom-post-type
			'capabilities'        => array(
				'create_posts' => 'do_not_allow', // Removes support for the "Add New" function ( use 'do_not_allow' instead of false for multisite set ups )
			),
			'map_meta_cap'        => true,

			/* support */
			'supports'            => array( 'title', 'editor' ),
			/* Rewrite */
			'rewrite'             => array( 'slug' => 'order' ),
			/* Rest Api */
			//'show_in_rest'          => true,
			//'rest_base'             => 'shortlink_api',
			//'rest_controller_class' => 'WP_REST_Posts_Controller',
		);
		register_post_type( 'currency_order', $args );

		// Add Custom Post Status
		// https://github.com/imath/wp-statuses
		// https://gist.github.com/mehrshaddarzi/1ca3e0d7804d1bf1aa0bdec7779e1a20
		register_post_status( 'canceled', array(
			'label'                     => __( 'Canceled', 'wp-afraex' ),
			'public'                    => true,
			'label_count'               => _n_noop( 'Canceled <span class="count">(%s)</span>', 'Canceled <span class="count">(%s)</span>', 'plugin-domain' ),
			'post_type'                 => array( 'currency_order' ), // Define one or more post types the status can be applied to.
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'show_in_metabox_dropdown'  => true,
			'show_in_inline_dropdown'   => true,
			'dashicon'                  => '',
		) );
		register_post_status( 'completed', array(
			'label'                     => __( 'Completed', 'wp-afraex' ),
			'public'                    => true,
			'label_count'               => _n_noop( 'Completed <span class="count">(%s)</span>', 'Completed <span class="count">(%s)</span>', 'wp-afraex' ),
			'post_type'                 => array( 'currency_order' ), // Define one or more post types the status can be applied to.
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'show_in_metabox_dropdown'  => true,
			'show_in_inline_dropdown'   => true,
			'dashicon'                  => '',
		) );
		register_post_status( 'doing', array(
			'label'                     => __( 'Doing', 'wp-afraex' ),
			'public'                    => true,
			'label_count'               => _n_noop( 'Doing <span class="count">(%s)</span>', 'Doing <span class="count">(%s)</span>', 'wp-afraex' ),
			'post_type'                 => array( 'currency_order' ), // Define one or more post types the status can be applied to.
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'show_in_metabox_dropdown'  => true,
			'show_in_inline_dropdown'   => true,
			'dashicon'                  => '',
		) );

	}

	/*
     * Column Book Table Add
     */
	public function column_currency_order( $columns ) {

		$columns['order_user_id']             = __( "User", "wp-afraex" );
		$columns['order_currency_from']       = __( "From", "wp-afraex" );
		$columns['order_currency_from_price'] = __( "Price", "wp-afraex" );
		$columns['order_currency_to']         = __( "To", "wp-afraex" );
		$columns['order_currency_calculate']  = __( "Calculate", "wp-afraex" );
		$columns['order_status']              = __( "Status", "wp-afraex" );
		$columns['order_user_wallet']         = __( "Wallet", "wp-afraex" );

		return $columns;
	}

	public function column_post_table( $column, $post_id ) {
		global $wpdb;

		switch ( $column ) {
			case "order_user_id":
				$user_meta = get_userdata( get_post_meta( $post_id, 'order_user_id', true ) );
				echo '<a href="' . get_edit_user_link( $user_meta->ID ) . '" target="_blank">' . $user_meta->user_email . '<br />' . $user_meta->first_name . ' ' . $user_meta->last_name . '</a>';
				break;
			case "order_currency_from":
				echo get_the_title( get_post_meta( $post_id, 'order_currency_from', true ) ) . '<br />' . get_post_meta( get_post_meta( $post_id, 'order_currency_from', true ), 'currency_persian', true );
				break;
			case "order_currency_from_price":
				$price = get_post_meta( $post_id, 'order_currency_from_price', true );
				echo( self::is_numeric( $price ) ? number_format( $price ) : $price );
				break;
			case "order_currency_to":
				echo get_the_title( get_post_meta( $post_id, 'order_currency_to', true ) ) . '<br />' . get_post_meta( get_post_meta( $post_id, 'order_currency_to', true ), 'currency_persian', true );
				break;
			case "order_currency_calculate":
				$price = get_post_meta( $post_id, 'order_currency_calculate', true );
				echo( self::is_numeric( $price ) ? number_format( $price ) : $price );
				break;
			case "order_status":
				$post_status_detail = get_post_status_object( get_post_status( $post_id ) );
				echo $post_status_detail->label;
				break;
			case "order_user_wallet":
				echo get_post_meta( $post_id, 'order_user_wallet', true );
				break;
		}
	}

	public static function is_numeric( $value ) {
		if ( ctype_digit( $value ) && (int) $value > 0 ) {
			return true;
		}

		return false;
	}

	/**
	 * Add Column in User.php
	 * @param $columns
	 * @return mixed
	 */
	function pippin_add_user_id_column( $columns ) {
		$columns['user_card_list'] = __( "Card List", "wp-afraex" );
		$columns['user_mobile']    = __( "Mobile", "wp-afraex" );
		return $columns;
	}

	/**
	 * Add Column in User.php
	 *
	 * @param $value
	 * @param $column_name
	 * @param $user_id
	 * @return string
	 */
	function pippin_show_user_id_column_content( $value, $column_name, $user_id ) {
		global $wpdb;
		$list = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}bank_card WHERE `user_id` = " . $user_id . " ORDER BY ID DESC", ARRAY_A );

		$user = get_userdata( $user_id );
		add_thickbox();
		if ( 'user_mobile' == $column_name ) {
			return get_user_meta( $user_id, 'mobile' , true );
		}
		if ( 'user_card_list' == $column_name ) {
			if ( count( $list ) > 0 ) {
				$t = '';
				$t .= '
		<div id="bank-list-id-' . $user_id . '" style="display:none;">
		<table class="widefat" style="margin-top: 15px;text-align: right;">
                                <thead>
                                <tr>
                                    <th>' . __( "Bank Name", "wp-afraex" ) . '</th>
                                    <th>' . __( "Card Number", "wp-afraex" ) . '</th>
                                    <th>' . __( "Sheba Number", "wp-afraex" ) . '</th>
                                </tr>
                                </thead>
                                <tbody>';
				foreach ( $list as $r ) {
					$t .= '
                                    <tr>
                                        <td>' . $r['bank'] . '</td>
                                        <td>' . $r['card'] . '</td>
                                        <td>IR' . $r['sheba'] . '</td>
                                    </tr>
									';
				}
				$t .= '
                                </tbody>
                            </table>
		
		</div>
		<a href="#TB_inline?width=600&height=550&inlineId=bank-list-id-' . $user_id . '" class="thickbox">' . __( "Show", "wp-afraex" ) . '</a>
		';
				return $t;
			} else {
				return '-';
			}

		}
		return $value;
	}

	/**
	 * Hide Admin Bar
	 */
	public function hide_admin_bar() {

		// Hide Admin Bar
		if ( ! current_user_can( 'manage_options' ) ) {
			add_filter( 'show_admin_bar', '__return_false' );
		}

		// Disable Access Dashboard
		if ( is_admin() && ! defined( 'DOING_AJAX' ) && current_user_can( 'subscriber' ) ) {
			wp_redirect( home_url() );
			exit;
		}
	}

	/**
	 * Admin Link
	 *
	 * @param $page
	 * @param array $args
	 * @return string
	 */
	public static function admin_link( $page, $args = array() ) {
		return add_query_arg( $args, admin_url( 'admin.php?page=' . $page ) );
	}

	/**
	 * If in Page in Admin
	 *
	 * @param $page_slug
	 * @return bool
	 */
	public static function in_page( $page_slug ) {
		global $pagenow;
		if ( $pagenow == "admin.php" and isset( $_GET['page'] ) and $_GET['page'] == $page_slug ) {
			return true;
		}

		return false;
	}

	/**
	 * Load assets file in admin
	 */
	public function admin_assets() {
		global $pagenow;

		//List Allow This Script
		if ( $pagenow == "admin.php" ) {

			//wp_enqueue_style( 'wp-afraex', WP_AFRAEX::$plugin_url . '/asset/admin/css/style.css', array(), WP_AFRAEX::$plugin_version, 'all' );
			//wp_enqueue_script( 'wp-afraex', WP_AFRAEX::$plugin_url . '/asset/admin/js/script.js', array( 'jquery' ), WP_AFRAEX::$plugin_version, false );

		}

	}

	/**
	 * Set Admin Menu
	 */
	public function admin_menu() {
		add_menu_page( __( 'User Panel', 'wp-afraex' ), __( 'User Panel', 'wp-afraex' ), 'manage_options', self::$admin_page_slug, array( Settings::instance(), 'setting_page' ), 'dashicons-editor-contract', 99 );
		//add_submenu_page( self::$admin_page_slug, __( 'order', 'wp-afraex' ), __( 'order', 'wp-afraex' ), 'manage_options', self::$admin_page_slug, array( $this, 'admin_page' ) );
		//add_submenu_page( self::$admin_page_slug, __( 'setting', 'wp-afraex' ), __( 'setting', 'wp-afraex' ), 'manage_options', 'wp_plugin_option', array( Settings::instance(), 'setting_page' ) );
	}

	/*
	 * Admin Page
	 */
	public function admin_page() {
		//$simple_text = 'Hi';
		//require_once WP_AFRAEX::$plugin_path . '/inc/admin/views/default.php';
	}

}