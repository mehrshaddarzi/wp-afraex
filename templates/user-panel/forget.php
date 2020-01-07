<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="rtl">
<!-- BEGIN: Head-->
<?php global $post; ?>
<?php $options = get_option( 'wp_afraex_options' ); ?>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title><?php _e( "Recover Password", "wp-afraex" ); ?></title>
    <link rel="apple-touch-icon" href="<?php echo $options['logo']; ?>">
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo $options['logo']; ?>">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="<?php echo WP_AFRAEX::$plugin_url; ?>/templates/user-panel/app-assets/vendors/css/vendors-rtl.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo WP_AFRAEX::$plugin_url; ?>/templates/user-panel/app-assets/vendors/css/forms/toggle/switchery.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo WP_AFRAEX::$plugin_url; ?>/templates/user-panel/app-assets/css-rtl/plugins/forms/switch.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo WP_AFRAEX::$plugin_url; ?>/templates/user-panel/app-assets/css-rtl/core/colors/palette-switch.min.css">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="<?php echo WP_AFRAEX::$plugin_url; ?>/templates/user-panel/app-assets/css-rtl/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo WP_AFRAEX::$plugin_url; ?>/templates/user-panel/app-assets/css-rtl/bootstrap-extended.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo WP_AFRAEX::$plugin_url; ?>/templates/user-panel/app-assets/css-rtl/colors.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo WP_AFRAEX::$plugin_url; ?>/templates/user-panel/app-assets/css-rtl/components.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo WP_AFRAEX::$plugin_url; ?>/templates/user-panel/app-assets/css-rtl/custom-rtl.min.css">
    <!-- END: Theme CSS-->

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="<?php echo WP_AFRAEX::$plugin_url; ?>/templates/user-panel/app-assets/css-rtl/core/menu/menu-types/vertical-menu.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo WP_AFRAEX::$plugin_url; ?>/templates/user-panel/app-assets/css-rtl/core/colors/palette-gradient.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo WP_AFRAEX::$plugin_url; ?>/templates/user-panel/app-assets/css-rtl/pages/login-register.min.css">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
	<?php
	if ( is_rtl() ) {
		?>
        <link rel="stylesheet" type="text/css" href="<?php echo WP_AFRAEX::$plugin_url; ?>/templates/user-panel/assets/css/style-rtl.css">
		<?php
	}
	?>
    <!-- END: Custom CSS-->

</head>
<!-- END: Head-->

<!-- BEGIN: Body-->
<body class="vertical-layout vertical-menu 1-column bg-full-screen-image blank-page blank-page" data-open="click" data-menu="vertical-menu" data-color="bg-gradient-x-purple-blue" data-col="1-column">
<!-- BEGIN: Content-->
<div class="app-content content">
    <div class="content-wrapper">
        <div class="content-wrapper-before"></div>
        <div class="content-header row"></div>
        <div class="content-body">
            <section class="flexbox-container">
                <div class="col-12 d-flex align-items-center justify-content-center">
                    <div class="col-lg-4 col-md-6 col-10 box-shadow-2 p-0">
                        <div class="card border-grey border-lighten-3 px-1 py-1 m-0">
                            <div class="card-header border-0">
                                <div class="text-center mb-1">
                                    <img src="<?php echo $options['logo']; ?>" alt="<?php get_bloginfo( 'name' ); ?>" style="width: 110px; height: auto;">
                                </div>
                                <div class="font-large-1  text-center">
									<?php _e( "Recover Password", "wp-afraex" ); ?>
                                </div>
                            </div>
                            <div class="card-content">

								<?php
								if ( ! empty( $error_forget ) ) {
									?>
                                    <div class="alert alert-danger mb-2" role="alert">
										<?php echo $error_forget; ?>
                                    </div>
									<?php
								}
								?>

								<?php
								if ( ! empty( $success_alert ) ) {
									?>
                                    <div class="alert alert-success mb-2" role="alert">
										<?php echo $success_alert; ?>
                                    </div>
									<?php
								}
								?>


                                <div class="card-body">
                                    <form class="form-horizontal" action="<?php echo add_query_arg( array( 'method' => 'forget' ), get_page_link( $post->ID ) ); ?>" method="post">

										<?php
										$show_reset_password = false;
										if ( isset( $_GET['key'] ) and ! empty( $_GET['key'] ) and isset( $_GET['login'] ) and ! empty( $_GET['login'] ) ) {
											$check = check_password_reset_key( trim( $_GET['key'] ), trim( $_GET['login'] ) );
											if ( ! is_wp_error( $check ) ) {
												$show_reset_password = true;
											}
										}

										if ( $show_reset_password === false ) {
											?>

											<?php wp_nonce_field( 'wp_afraex_forget_form', 'wp_afraex_forget_form' ); ?>
                                            <fieldset class="form-group position-relative has-icon-left">
                                                <input type="email" class="form-control round" id="user-name" name="user_email" placeholder="<?php _e( "Email", "wp-afraex" ); ?>" required>
                                                <div class="form-control-position">
                                                    <i class="ft-mail"></i>
                                                </div>
                                            </fieldset>

                                            <div class="form-group text-center">
                                                <button type="submit" class="btn round btn-block btn-glow btn-bg-gradient-x-purple-blue col-12 mr-1 mb-1"><?php _e( 'Reset Password', 'wp-afraex' ); ?></button>
                                            </div>

											<?php
										} else {
											?>

											<?php wp_nonce_field( 'wp_afraex_reset_forget_form', 'wp_afraex_reset_forget_form' ); ?>
                                            <input type="hidden" name="key" value="<?php echo $_GET['key']; ?>">
                                            <input type="hidden" name="login" value="<?php echo $_GET['login']; ?>">
                                            <fieldset class="form-group position-relative has-icon-left">
                                                <input type="password" class="form-control round" id="user-name" name="new_password" placeholder="<?php _e( "New Password", "wp-afraex" ); ?>" pattern=".{8,}" title="<?php _e( "8 characters minimum", "wp-afraex" ); ?>" required>
                                                <div class="form-control-position">
                                                    <i class="ft-lock"></i>
                                                </div>
                                            </fieldset>

                                            <div class="form-group text-center">
                                                <button type="submit" class="btn round btn-block btn-glow btn-bg-gradient-x-purple-blue col-12 mr-1 mb-1"><?php _e( 'Change Password', 'wp-afraex' ); ?></button>
                                            </div>
											<?php
										}
										?>


                                    </form>
                                </div>
                                <p class="card-subtitle text-muted text-center font-small-3 mx-2 my-1" style="margin-top: -0.5rem !important;">
                                    <span><a href="<?php echo add_query_arg( array(), get_page_link( $post->ID ) ); ?>" class="card-link"><?php _e( 'Login to Site', 'wp-afraex' ); ?></a></span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </div>
</div>
<!-- END: Content-->

<!-- BEGIN: Vendor JS-->
<script src="<?php echo WP_AFRAEX::$plugin_url; ?>/templates/user-panel/app-assets/vendors/js/vendors.min.js" type="text/javascript"></script>
<script src="<?php echo WP_AFRAEX::$plugin_url; ?>/templates/user-panel/app-assets/vendors/js/forms/toggle/switchery.min.js" type="text/javascript"></script>
<script src="<?php echo WP_AFRAEX::$plugin_url; ?>/templates/user-panel/app-assets/js/scripts/forms/switch.min.js" type="text/javascript"></script>
<!-- BEGIN Vendor JS-->

<!-- BEGIN: Page Vendor JS-->
<script src="<?php echo WP_AFRAEX::$plugin_url; ?>/templates/user-panel/app-assets/vendors/js/forms/validation/jqBootstrapValidation.js" type="text/javascript"></script>
<!-- END: Page Vendor JS-->

<!-- BEGIN: Theme JS-->
<script src="<?php echo WP_AFRAEX::$plugin_url; ?>/templates/user-panel/app-assets/js/core/app-menu.min.js" type="text/javascript"></script>
<script src="<?php echo WP_AFRAEX::$plugin_url; ?>/templates/user-panel/app-assets/js/core/app.min.js" type="text/javascript"></script>
<!-- END: Theme JS-->

<!-- BEGIN: Page JS-->
<script src="<?php echo WP_AFRAEX::$plugin_url; ?>/templates/user-panel/app-assets/js/scripts/forms/form-login-register.min.js" type="text/javascript"></script>
<!-- END: Page JS-->

</body>
<!-- END: Body-->

</html>