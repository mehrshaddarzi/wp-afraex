<?php
if ( ! isset( $_GET['ID'] ) ) {
	?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
						<?php _e( "Add Bank Card", "wp-afraex" ); ?>
                    </h4>
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
						if ( ( isset( $_POST['bank'] ) and isset( $_POST['card'] ) ) || isset( $_GET['remove'] ) ) {

						if ( ! empty( $error_text ) ) {
							?>
                        <div class="alert alert-danger mb-2" role="alert">
							<?php echo $error_text; ?>
                        </div><?php
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

                        <form class="form form-horizontal" data-show-submit-spinner method="post" action="<?php echo add_query_arg( array( 'method' => 'new_bank_card' ), get_page_link( $post->ID ) ) ?>">
							<?php wp_nonce_field( 'wp_afraex_add_bank_card_form', 'wp_afraex_add_bank_card_form' ); ?>
							<?php $user = get_userdata( get_current_user_id() ); ?>
                            <div class="form-body">

                                <div class="form-group row">
                                    <label class="col-md-3 label-control pad-label" for="timesheetinput2"><?php _e( "Bank Name", "wp-afraex" ); ?></label>
                                    <div class="col-md-9">
                                        <div class="position-relative has-icon-left">
                                            <select class="form-control select2 hide-search" name="bank">
												<?php
												$options   = get_option( 'wp_afraex_options' );
												$list_bank = explode( PHP_EOL, $options['iranian_bank_list'] );
												foreach ( $list_bank as $name ) {
													?>
                                                    <option><?php echo $name; ?></option>
													<?php
												}
												?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 label-control pad-label" for="timesheetinput2"><?php _e( "Sheba Number", "wp-afraex" ); ?></label>
                                    <div class="col-md-9">
                                        <div class="position-relative has-icon-left">

                                            <div class="input-group">
                                                <input type="tel" class="form-control text-left ltr" name="sheba" placeholder="" data-only-numeric required>
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">IR</div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 label-control pad-label" for="timesheetinput2"><?php _e( "Card Number", "wp-afraex" ); ?></label>
                                    <div class="col-md-9">
                                        <div class="position-relative has-icon-left">
                                            <input type="tel" class="form-control text-left ltr" name="card" maxlength="19" placeholder="xxxx-xxxx-xxxx-xxxx" data-only-numeric data-bank-number required>
                                            <div class="form-control-position">
                                                <i class="la la-credit-card"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 label-control pad-label" for="timesheetinput2"><?php _e( "Full Name", "wp-afraex" ); ?></label>
                                    <div class="col-md-9">
                                        <div class="position-relative has-icon-left">
                                            <input type="text" class="form-control text-right rtl" name="user_full_name" value="<?php echo $user->first_name.' '.$user->last_name ?>" readonly>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="form-actions right">
                                <button type="submit" class="btn btn-primary col-12">
                                    <i class="la la-check-square-o submit-icon-vertical"></i> <?php _e( "Add Bank Card", "wp-afraex" ); ?>
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
	<?php
}
?>

<?php
if ( isset( $_GET['ID'] ) and is_numeric( $_GET['ID'] ) ) {
	// Check is For this User
	global $wpdb;
	$row = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}bank_card WHERE `ID` = " . trim( $_GET['ID'] ), ARRAY_A );
	if ( $row['user_id'] == get_current_user_id() ) {
		?>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">
							<?php _e( "Edit Bank Card", "wp-afraex" ); ?>
                        </h4>
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
							if ( isset( $_POST['wp_afraex_edit_bank_card_form'] ) || wp_verify_nonce( $_POST['wp_afraex_edit_bank_card_form'], 'wp_afraex_edit_bank_card_form' ) and isset( $_POST['ID'] ) and isset( $_POST['bank'] ) and isset( $_POST['card'] ) ) {

								if ( ! empty( $error_text ) ) {
									?>
                                    <div class="alert alert-danger mb-2" role="alert">
										<?php echo $error_text; ?>
                                    </div>
									<?php
								}

								if ( ! empty( $success_text ) ) {
									?>
                                    <div class="alert alert-success mb-2" role="alert">
										<?php echo $success_text; ?>
                                    </div>
									<?php
								}
							}
							?>

                            <form class="form form-horizontal" data-show-submit-spinner method="post" action="<?php echo add_query_arg( array( 'method' => 'new_bank_card' ), get_page_link( $post->ID ) ) ?>">
								<?php wp_nonce_field( 'wp_afraex_edit_bank_card_form', 'wp_afraex_edit_bank_card_form' ); ?>
								<?php $user = get_userdata( get_current_user_id() ); ?>
                                <input type="hidden" name="ID" value="<?php echo $_GET['ID']; ?>">
                                <div class="form-body">

                                    <div class="form-group row">
                                        <label class="col-md-3 label-control pad-label" for="timesheetinput2"><?php _e( "Bank Name", "wp-afraex" ); ?></label>
                                        <div class="col-md-9">
                                            <div class="position-relative has-icon-left">
                                                <select class="form-control select2 hide-search" name="bank">
													<?php
													$options   = get_option( 'wp_afraex_options' );
													$list_bank = explode( PHP_EOL, $options['iranian_bank_list'] );
													foreach ( $list_bank as $name ) {
														?>
                                                        <option <?php selected( $row['bank'], $name ); ?>><?php echo $name; ?></option>
														<?php
													}
													?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label class="col-md-3 label-control pad-label" for="timesheetinput2"><?php _e( "Sheba Number", "wp-afraex" ); ?></label>
                                        <div class="col-md-9">
                                            <div class="position-relative has-icon-left">

                                                <div class="input-group">
                                                    <input type="tel" class="form-control text-left ltr" name="sheba" placeholder="" value="<?php echo $row['sheba']; ?>" data-only-numeric required>
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">IR</div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 label-control pad-label" for="timesheetinput2"><?php _e( "Card Number", "wp-afraex" ); ?></label>
                                        <div class="col-md-9">
                                            <div class="position-relative has-icon-left">
                                                <input type="tel" class="form-control text-left ltr" name="card" maxlength="19" placeholder="xxxx-xxxx-xxxx-xxxx" data-only-numeric data-bank-number value="<?php echo $row['card']; ?>" required>
                                                <div class="form-control-position">
                                                    <i class="la la-credit-card"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 label-control pad-label" for="timesheetinput2"><?php _e( "Full Name", "wp-afraex" ); ?></label>
                                        <div class="col-md-9">
                                            <div class="position-relative has-icon-left">
                                                <input type="text" class="form-control text-right rtl" name="user_full_name" value="<?php echo $user->first_name.' '.$user->last_name ?>" readonly>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="form-actions right">
                                    <button type="submit" class="btn btn-primary col-12">
                                        <i class="la la-check-square-o submit-icon-vertical"></i> <?php _e( "Edit Bank Card", "wp-afraex" ); ?>
                                    </button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
		<?php
	}
}
?>

<?php
global $wpdb;
$list = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}bank_card WHERE `user_id` = " . get_current_user_id() . " ORDER BY ID DESC", ARRAY_A );
if ( count( $list ) > 0 ) {
	?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
						<?php _e( "Bank Card List", "wp-afraex" ); ?>
                    </h4>
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

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered" style="table-layout: fixed;">
                                <thead>
                                <tr>
                                    <th style="width: 200px; !important;"><?php _e( "Bank Name", "wp-afraex" ); ?></th>
                                    <th style="width: 300px; !important;"><?php _e( "Card Number", "wp-afraex" ); ?></th>
                                    <th style="width: 300px; !important;"><?php _e( "Sheba Number", "wp-afraex" ); ?></th>
                                    <th style="width: 300px; !important;"></th>
                                </tr>
                                </thead>
                                <tbody>
								<?php
								foreach ( $list as $r ) {
									?>
                                    <tr>
                                        <td><?php echo $r['bank']; ?></td>
                                        <td><?php echo $r['card']; ?></td>
                                        <td>IR<?php echo $r['sheba']; ?></td>
                                        <td>
                                            <a href="<?php echo add_query_arg( array( 'method' => 'new_bank_card', 'ID' => $r['ID'] ), get_page_link( $post->ID ) ); ?>" class="btn btn-icon btn-success mr-1 text-white"><?php _e( "Edit", "wp-afraex" ); ?></a>
                                            <a href="<?php echo add_query_arg( array( 'method' => 'new_bank_card', 'remove' => $r['ID'] ), get_page_link( $post->ID ) ); ?>" class="btn btn-icon btn-danger mr-1 text-white"><?php _e( "Remove", "wp-afraex" ); ?></a>
                                        </td>
                                    </tr>
									<?php
								}
								?>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

	<?php
}

