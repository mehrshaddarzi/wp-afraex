<?php
global $post, $wpdb;
$options            = get_option( 'wp_afraex_options' );
$user_data          = get_userdata( get_current_user_id() );
$user_id            = get_current_user_id();
$admin_chat_user_id = $options['chat_operator'];
$admin_user_data    = get_userdata( $options['chat_operator'] );
$is_admin_user      = $admin_chat_user_id == $user_id;
?>

<div class="app-content content">
    <div class="sidebar-left sidebar-fixed ps ps--active-y">
        <div class="sidebar">
            <div class="sidebar-content card d-none d-lg-block">
                <!-- Search Box -->
                <div id="users-list" class="list-group position-relative">
                    <div class="users-list-padding media-list" style="padding-top: 20px;padding-bottom: 0;">
						<?php
						if ( $is_admin_user ) {

							$get_list_admin_chats = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}chat_box WHERE `user_receiver` = {$admin_chat_user_id} GROUP BY user_sender", ARRAY_A );
							foreach ( $get_list_admin_chats as $row ) {
								?>
                                <a href="<?php echo add_query_arg( array( 'method' => 'ticket', 'chat_with' => $row['user_sender'] ), get_page_link( $post->ID ) ); ?>" class="media border-bottom-blue-grey border-bottom-lighten-5 <?php if ( isset( $_GET['chat_with'] ) and $_GET['chat_with'] == $row['user_sender'] ) {
									echo 'border-right-primary border-right-2';
								} ?>">
                                    <div class="media-left pr-1">
									<span class="avatar avatar-md">
										<?php
										$this_user_data = get_userdata( $row['user_sender'] );
										?>
										<img class="media-object rounded-circle" src="<?php print get_avatar_url( $row['user_sender'], [ 'size' => '150' ] ); ?>" alt="<?php echo $this_user_data->first_name . ' ' . $this_user_data->last_name; ?>">
                                </span>
                                    </div>
                                    <div class="media-body w-100">
                                        <h6 class="list-group-item-heading font-medium-1 text-bold-700"><?php echo $this_user_data->first_name . ' ' . $this_user_data->last_name; ?> &nbsp;
                                            <span class="float-right primary">
		                                    <?php
		                                    $chat_unread_msg = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}chat_box WHERE `user_receiver` = {$admin_chat_user_id} AND `user_sender` = {$row['user_sender']} AND `read` =0" );
		                                    if ( $chat_unread_msg > 0 ) {
			                                    ?>
                                                <span class="badge badge-pill badge-danger lighten-3"><?php echo $chat_unread_msg; ?></span>
			                                    <?php
		                                    }
		                                    ?>
	                                    </span>
                                        </h6>
                                        <p class="font-small-3 text-muted text-bold-500">
											<?php
											// Get Last Notification
											$last_notify = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}chat_box WHERE `user_receiver` = {$admin_chat_user_id} AND `user_sender` = {$row['user_sender']} ORDER BY ID DESC LIMIT 1", ARRAY_A );
											if ( null !== $last_notify ) {
												echo date_i18n( "H:i A", strtotime( $last_notify['created_at'] ) );
											} else {
												//echo date_i18n( "H:i A", current_time( 'timestamp' ) );
											}
											?>
                                        </p>
                                    </div>
                                </a>
								<?php
							}
						} else {
							?>
                            <a href="<?php echo add_query_arg( array( 'method' => 'ticket' ), get_page_link( $post->ID ) ); ?>" class="media border-bottom-blue-grey border-bottom-lighten-5 border-right-primary border-right-2">
                                <div class="media-left pr-1">
									<span class="avatar avatar-md">
										<img class="media-object rounded-circle" src="<?php print get_avatar_url( $admin_chat_user_id, [ 'size' => '150'  ] ); ?>" alt="<?php echo $admin_user_data->first_name . ' ' . $admin_user_data->last_name; ?>">
                                </span>
                                </div>
                                <div class="media-body w-100">
                                    <h6 class="list-group-item-heading font-medium-1 text-bold-700"><?php echo $admin_user_data->first_name . ' ' . $admin_user_data->last_name; ?> &nbsp;
                                        <span class="float-right primary">
		                                    <?php
		                                    $chat_unread_msg = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}chat_box WHERE `user_receiver` = {$user_id} AND `read` =0" );
		                                    if ( $chat_unread_msg > 0 ) {
			                                    ?>
                                                <span class="badge badge-pill badge-danger lighten-3"><?php echo $chat_unread_msg; ?></span>
			                                    <?php
		                                    }
		                                    ?>
	                                    </span>
                                    </h6>
                                    <p class="font-small-3 text-muted text-bold-500">
										<?php

										// Get Last Notification
										$last_notify = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}chat_box WHERE `user_receiver` = {$user_id} AND `read` =0 ORDER BY ID DESC LIMIT 1", ARRAY_A );
										if ( null !== $last_notify ) {
											echo date_i18n( "H:i A", strtotime( $last_notify['created_at'] ) );
										} else {
											echo date_i18n( "H:i A", current_time( 'timestamp' ) );
										}
										?>
                                    </p>
                                </div>
                            </a>
							<?php
						}
						?>
                    </div>
                </div>
            </div>

        </div>
        <div class="ps__rail-x" style="left: 0px; bottom: 0px;">
            <div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div>
        </div>
        <div class="ps__rail-y" style="top: 0px; height: 727px; right: 300px;">
            <div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 372px;"></div>
        </div>
    </div>
    <div class="content-right">
        <div class="content-wrapper">
            <div class="content-wrapper-before"></div>
            <div class="content-header row"></div>
            <div class="content-body">
                <section class="chat-app-window">
                    <div class="mb-1 secondary text-bold-700 text-white"><?php _e( "Chat history", "wp-afraex" ); ?></div>
                    <div class="chats">
                        <div class="chats">
							<?php
							$i_user_id = get_current_user_id();
							$chat_with = isset( $_GET['chat_with'] ) ? $_GET['chat_with'] : $admin_chat_user_id;
							$last_id   = 0;

							$get_list_chat = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}chat_box WHERE ((`user_receiver` = {$i_user_id} AND `user_sender` = {$chat_with}) OR (`user_receiver` = {$chat_with} AND `user_sender` = {$i_user_id})) GROUP BY ID ORDER BY ID ASC", ARRAY_A );
							if ( count( $get_list_chat ) > 0 ) {
								$_copy_list = $get_list_chat;
								$end_of     = end( $_copy_list );
								$last_id    = $end_of['ID'];
							}
							foreach ( $get_list_chat as $row ) {

								$sender_userdata  = get_userdata( $row['user_sender'] );
								$receive_userdata = get_userdata( $row['user_receiver'] );
								?>
                                <div class="chat <?php if ( $row['user_sender'] == get_current_user_id() ) {
									echo 'chat-left';
								} ?>">
                                    <div class="chat-avatar">
                                        <a class="avatar" data-toggle="tooltip" href="#" data-placement="right" title="" data-original-title="">
                                            <img src="<?php print get_avatar_url( $row['user_sender'], [ 'size' => '150' ] ); ?>" class="box-shadow-4" alt="avatar">
                                        </a>
                                    </div>
                                    <div class="chat-body">
                                        <div class="chat-content">
											<?php
											if ( $row['content_type'] == "text" ) {
												?>
                                                <p><?php echo $row['content']; ?></p>
												<?php
											} else {
												$download_url = wp_get_attachment_url( $row['content'] );
												$file_name    = basename( get_attached_file( $row['content'] ) );
												?>
                                                <p>
                                                    <span dir="ltr"><a target="_blank" href="<?php echo $download_url; ?>"><?php echo $file_name; ?></a></span>
                                                </p>
												<?php
											}

											// Set to Read MSG
											if ( $row['user_receiver'] == get_current_user_id() ) {
												$wpdb->update(
													$wpdb->prefix . 'chat_box',
													array(
														'read' => 1
													),
													array( 'ID' => $row['ID'] )
												);
											}
											?>
                                        </div>
                                    </div>
                                </div>
								<?php
							}
							?>
                        </div>
                    </div>
                </section>
                <section class="chat-app-form">
                    <div class="chat-app-input d-flex">
                        <fieldset class="col-10 m-0">

							<?php
							$arg = array( 'method' => 'ticket' );
							if ( isset( $_GET['chat_with'] ) ) {
								$arg['chat_with'] = trim( $_GET['chat_with'] );
							}
							$form_submit_url = add_query_arg( $arg, get_page_link( $post->ID ) );
							?>
                            <form action="<?php echo $form_submit_url; ?>" method="post" id="send_chat_attachment" enctype="multipart/form-data">
								<?php wp_nonce_field( 'chat_box_send_attachment', 'chat_box_send_attachment' ); ?>
                                <input type="file" name="attachment_file" accept=".png, .jpg, .jpeg, .pdf, .zip" id="attachment_file" class="d-none">
                                <input type="hidden" name="redirect" value="<?php echo $form_submit_url; ?>">
                                <input type="hidden" name="send_to" value="<?php echo $chat_with; ?>">
                            </form>

                            <div class="input-group position-relative has-icon-left">
                                <div class="form-control-position">
                                    <span id="basic-addon3">
	                                    <i class="ft-image" style="margin-top: 4px;" id="add_new_attachment"></i>
                                    </span>
                                </div>
                                <form action="<?php echo $form_submit_url; ?>" method="post" style="width: 100%;" id="send_chat_pm">
									<?php wp_nonce_field( 'chat_box_send_pm', 'chat_box_send_pm' ); ?>
                                    <input type="hidden" name="send_to" value="<?php echo $chat_with; ?>">
                                    <input type="hidden" name="redirect" value="<?php echo $form_submit_url; ?>">
                                    <input type="text" class="form-control" name="msg" placeholder="<?php _e( "Message text", "wp-afraex" ); ?>" aria-describedby="button-addon2" autocomplete="off" required>
                                    <input type="submit" class="d-none" id="submit_btn_pm">
                                </form>
                            </div>
                        </fieldset>
                        <fieldset class="form-group position-relative has-icon-left col-2 m-0 p-0">
                            <button type="button" class="btn btn-primary" id="send_pm">
                                <i class="la la-paper-plane-o d-xl-none"></i>
                                <span class="d-none d-lg-none d-xl-block"><?php _e( "Send", "wp-afraex" ); ?> </span>
                            </button>
                        </fieldset>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>
<script>
    var last_ID = <?php echo $last_id; ?>;
    var admin_ajax_refresh_chat_box = '<?php echo admin_url( 'admin-ajax.php?action=get_new_chat_item&user=' . get_current_user_id() . '&with=' . $chat_with ); ?>';
    var redirect_link = '<?php echo $form_submit_url; ?>';
</script>