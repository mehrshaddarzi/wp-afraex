<li class="dropdown dropdown-notification nav-item">
	<?php
	global $wpdb, $post;
	$user_id               = get_current_user_id();
	$get_user_notification = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}user_notification WHERE `user_id` = " . $user_id . " AND `read` = 0 ORDER BY ID DESC", ARRAY_A );

	// Check Notification Chat
	$chat_unread_msg = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}chat_box WHERE `user_receiver` = {$user_id} AND `read` =0" );
	if ( $chat_unread_msg > 0 and isset( $_GET['method'] ) and $_GET['method'] != 'ticket' ) {
		$get_user_notification[] = array(
			'content' => __( "You have an unread message", "wp-afraex" ),
			'icon'    => 'ft-sunrise',
			'page'    => 'ticket',
		);
	}

	?>
    <a class="nav-link nav-link-label" href="#" data-toggle="dropdown">
        <i class="ficon ft-bell <?php
		if ( count( $get_user_notification ) > 0 ) {
			?>bell-shake<?php } ?>" id="notification-navbar-link"></i>
		<?php
		if ( count( $get_user_notification ) > 0 ) {
			?>
            <span class="badge badge-pill badge-sm badge-danger badge-up badge-glow"><?php echo count( $get_user_notification ); ?></span>
			<?php
		}
		?>
    </a>
	<?php
	if ( count( $get_user_notification ) > 0 ) {
		?>
        <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right">
            <div class="arrow_box_right">
                <li class="dropdown-menu-header">
                    <h6 class="dropdown-header m-0">
                        <span class="grey darken-2"><?php _e( 'Notifications', 'wp-afraex' ); ?></span>
                    </h6>
                </li>
                <li class="scrollable-container media-list w-100">
					<?php
					foreach ( $get_user_notification as $notify ) {
						$link = '#';
						if ( ! empty( $notify['page'] ) ) {
							$link = add_query_arg( array( 'method' => $notify['page'] ), get_page_link( $post->ID ) );

							if ( ! empty( $notify['arg'] ) ) {
								$argument = json_decode( $notify['arg'], true );
								$link     = add_query_arg( $notify['arg'], $link );
							}
						}
						?>
                        <a href="<?php echo $link; ?>" <?php if ( $link == "#" ) {
							echo 'style="cursor: default;"';
						} ?>>
                            <div class="media">
                                <div class="media-left align-self-center">
                                    <i class="<?php echo $notify['icon']; ?> info font-medium-4 mt-2"></i></div>
                                <div class="media-body">
                                    <!--<h6 class="media-heading info">New Order Received</h6>-->
                                    <p class="notification-text font-small-3 text-muted text-bold-600"><?php echo $notify['content']; ?></p>
                                    <small>
                                        <time class="media-meta text-muted"><?php echo date_i18n( "l j F y ساعت H:i", strtotime( $notify['created_at'] ) ); ?></time>
                                    </small>
                                </div>
                            </div>
                        </a>
						<?php
					}
					?>
                </li>
                <li class="dropdown-menu-footer">
                    <a class="dropdown-item info text-right" style="padding: 1rem;" href="<?php echo add_query_arg( array( 'method' => $_GET['method'], 'action' => 'set_all_notification_read', '_dashboard_notify_nonce' => wp_create_nonce( 'set_all_notification_read' ) ), get_page_link( $post->ID ) ); ?>"><?php _e( "Read all", "wp-afraex" ); ?></a>
                </li>
            </div>
        </ul>
		<?php
	}
	?>
</li>