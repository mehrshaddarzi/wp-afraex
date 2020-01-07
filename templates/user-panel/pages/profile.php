<!-- Basic Tables start -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title"><?php _e( "Edit Profile", "wp-afraex" ); ?></h4>
                <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                <div class="heading-elements">
                    <ul class="list-inline mb-0">
                        <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                        <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                    </ul>
                </div>
            </div>
            <div class="card-content collapse show">
                <div class="card-body">

					<?php
					if ( isset( $_POST['wp_afraex_change_profile_form'] ) || wp_verify_nonce( $_POST['wp_afraex_change_profile_form'], 'wp_afraex_change_profile_form' ) and isset( $_POST['first_name'] ) and isset( $_POST['last_name'] ) ) {

					if ( ! empty( $error_text ) ) {
						?>
                        <div class="alert alert-danger mb-2" role="alert">
							<?php echo $error_text; ?>
                        </div>							<?php
					}

					if ( ! empty( $success_text ) ) {
					?>
                        <script>
                            swal({
                                title: "",
                                text: "<?php echo $success_text; ?>",
                                icon: "success",
                                button: "<?php _e( "OK", "wp-afraex" ); ?>",
                            });
                        </script>

                        <div class="alert alert-success mb-2" role="alert">
							<?php echo $success_text; ?>
                        </div>
						<?php
					}
					}
					?>

                    <form class="form form-horizontal" data-show-submit-spinner method="post" action="<?php echo add_query_arg( array( 'method' => 'profile' ), get_page_link( $post->ID ) ) ?>">
						<?php wp_nonce_field( 'wp_afraex_change_profile_form', 'wp_afraex_change_profile_form' ); ?>
						<?php $user = get_userdata( get_current_user_id() ); ?>
                        <div class="form-body">

                            <div class="form-group row">
                                <label class="col-md-3 label-control pad-label" for="timesheetinput2"><?php _e( "Email", "wp-afraex" ); ?></label>
                                <div class="col-md-9">
                                    <div class="position-relative has-icon-left">
                                        <input type="email" class="form-control" name="user_email" value="<?php echo $user->user_email; ?>" readonly required>
                                        <div class="form-control-position">
                                            <i class="la la-send"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 label-control pad-label" for="timesheetinput2"><?php _e( "First Name", "wp-afraex" ); ?></label>
                                <div class="col-md-9">
                                    <div class="position-relative has-icon-left">
                                        <input type="text" class="form-control" placeholder="<?php _e( "First Name", "wp-afraex" ); ?>" name="first_name" value="<?php echo $user->first_name; ?>" readonly required>
                                        <div class="form-control-position">
                                            <i class="la la-user"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 label-control pad-label" for="timesheetinput2"><?php _e( "Last Name", "wp-afraex" ); ?></label>
                                <div class="col-md-9">
                                    <div class="position-relative has-icon-left">
                                        <input type="text" class="form-control" placeholder="<?php _e( "Last Name", "wp-afraex" ); ?>" name="last_name" value="<?php echo $user->last_name; ?>" readonly required>
                                        <div class="form-control-position">
                                            <i class="la la-user"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 label-control pad-label" for="timesheetinput2"><?php _e( "Mobile", "wp-afraex" ); ?></label>
                                <div class="col-md-9">
                                    <div class="position-relative has-icon-left">
                                        <input type="tel" class="form-control" style="direction: ltr; text-align: left;" placeholder="<?php _e( "Mobile", "wp-afraex" ); ?>" name="mobile" value="<?php echo get_user_meta( $user->ID, 'mobile' , true ); ?>" required>
                                        <div class="form-control-position">
                                            <i class="la la-mobile"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

<!--                            <div class="form-group row">-->
<!--                                <label class="col-md-3 label-control" for="timesheetinput2">--><?php //_e( "Avatar", "wp-afraex" ); ?><!--</label>-->
<!--                                <div class="col-md-9">-->
<!--                                    <div class="position-relative has-icon-left">-->
<!--                                        <a href="http://en.gravatar.com/" target="_blank">--><?php //_e( "Use the Gravatar service to change your profile picture", "wp-afraex" ); ?><!--</a>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </div>-->

                        </div>

                        <div class="form-actions right">
                            <button type="submit" class="btn btn-primary col-12">
                                <i class="la la-check-square-o submit-icon-vertical"></i> <?php _e( "Save", "wp-afraex" ); ?>
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div><!-- Basic Tables end -->


<!-- Basic Tables start -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title"><?php _e( "Change Password", "wp-afraex" ); ?></h4>
                <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                <div class="heading-elements">
                    <ul class="list-inline mb-0">
                        <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                        <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                    </ul>
                </div>
            </div>
            <div class="card-content collapse show">
                <div class="card-body">

					<?php
					if ( isset( $_POST['wp_afraex_change_pass_form'] ) || wp_verify_nonce( $_POST['wp_afraex_change_pass_form'], 'wp_afraex_reset_forget_form' ) and isset( $_POST['before_pass'] ) and isset( $_POST['new_pass'] ) and isset( $_POST['new_pass_2'] ) ) {

					if ( ! empty( $error_text ) ) {
						?>
                        <div class="alert alert-danger mb-2" role="alert">
							<?php echo $error_text; ?>
                        </div>							<?php
					}

					if ( ! empty( $success_text ) ) {
					?>
                        <script>
                            swal({
                                title: "",
                                text: "<?php echo $success_text; ?>",
                                icon: "success",
                                button: "<?php _e( "OK", "wp-afraex" ); ?>",
                            });
                        </script>

                        <div class="alert alert-success mb-2" role="alert">
							<?php echo $success_text; ?>
                        </div>
						<?php
					}
					}
					?>

                    <form class="form form-horizontal" data-show-submit-spinner method="post" action="<?php echo add_query_arg( array( 'method' => 'profile' ), get_page_link( $post->ID ) ) ?>">
						<?php wp_nonce_field( 'wp_afraex_change_pass_form', 'wp_afraex_change_pass_form' ); ?>
                        <div class="form-body">

                            <div class="form-group row">
                                <label class="col-md-3 label-control pad-label" for="timesheetinput2"><?php _e( "Current Password", "wp-afraex" ); ?></label>
                                <div class="col-md-9">
                                    <div class="position-relative has-icon-left">
                                        <input type="password" class="form-control" placeholder="<?php _e( "Current Password", "wp-afraex" ); ?>" name="before_pass" required>
                                        <div class="form-control-position">
                                            <i class="la la-lock"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 label-control pad-label" for="timesheetinput2"><?php _e( "New Password", "wp-afraex" ); ?></label>
                                <div class="col-md-9">
                                    <div class="position-relative has-icon-left">
                                        <input type="password" class="form-control" placeholder="<?php _e( "New Password", "wp-afraex" ); ?>" name="new_pass" pattern=".{8,}" title="<?php _e( "8 characters minimum", "wp-afraex" ); ?>" required>
                                        <div class="form-control-position">
                                            <i class="la la-unlock-alt"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 label-control pad-label" for="timesheetinput2"><?php _e( "Repeat New Password", "wp-afraex" ); ?></label>
                                <div class="col-md-9">
                                    <div class="position-relative has-icon-left">
                                        <input type="password" class="form-control" placeholder="<?php _e( "Repeat New Password", "wp-afraex" ); ?>" name="new_pass_2" pattern=".{8,}" title="<?php _e( "8 characters minimum", "wp-afraex" ); ?>" required>
                                        <div class="form-control-position">
                                            <i class="la la-unlock-alt"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="form-actions right">
                            <button type="submit" class="btn btn-primary col-12">
                                <i class="la la-check-square-o submit-icon-vertical"></i> <?php _e( "Save", "wp-afraex" ); ?>
                            </button>
                        </div>
                    </form>


                </div>
            </div>
        </div>
    </div>
</div><!-- Basic Tables end -->
