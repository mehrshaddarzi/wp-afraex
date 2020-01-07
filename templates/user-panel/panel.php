<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="rtl">
<!-- BEGIN: Head-->
<?php global $post; ?>
<?php $options = get_option( 'wp_afraex_options' ); ?>
<?php $user_data = get_userdata( get_current_user_id() ); ?>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title><?php
		$title = __( "Dashboard", "wp-afraex" );
		if ( isset( $_GET['method'] ) and array_key_exists( $_GET['method'], $list_page ) ) {
			$title = $list_page[ $_GET['method'] ]['title'];
		}
		echo $title;
		?></title>
    <link rel="apple-touch-icon" href="<?php echo $options['logo']; ?>">
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo $options['logo']; ?>">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="<?php echo WP_AFRAEX::$plugin_url; ?>/templates/user-panel/app-assets/vendors/css/vendors-rtl.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo WP_AFRAEX::$plugin_url; ?>/templates/user-panel/app-assets/vendors/css/forms/toggle/switchery.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo WP_AFRAEX::$plugin_url; ?>/templates/user-panel/app-assets/css-rtl/plugins/forms/switch.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo WP_AFRAEX::$plugin_url; ?>/templates/user-panel/app-assets/css-rtl/core/colors/palette-switch.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo WP_AFRAEX::$plugin_url; ?>/templates/user-panel/app-assets/vendors/css/forms/selects/select2.min.css">
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

	<?php
	if ( isset( $_GET['method'] ) and $_GET['method'] == "ticket" ) {
		?>
        <link rel="stylesheet" type="text/css" href="<?php echo WP_AFRAEX::$plugin_url; ?>/templates/user-panel/app-assets/css-rtl/pages/gap-application.css">
		<?php
	}

	if ( isset( $_GET['method'] ) and $_GET['method'] == "new_order" ) {
	    ?>
        <link rel="stylesheet" type="text/css" href="<?php echo WP_AFRAEX::$plugin_url; ?>/templates/user-panel/assets/js/chartjs/chart.min.css">
    <?php
	}
	?>
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
	<?php
	if ( is_rtl() ) {
		?>
        <link rel="stylesheet" type="text/css" href="<?php echo WP_AFRAEX::$plugin_url; ?>/templates/user-panel/assets/css/style-rtl.css?ver=<?php echo WP_AFRAEX::$plugin_version; ?>">
		<?php
	}
	?>
    <!-- END: Custom CSS-->

    <!-- Js -->
    <!-- https://sweetalert.js.org/guides/ -->
    <script src="<?php echo WP_AFRAEX::$plugin_url; ?>/templates/user-panel/assets/js/sweetalert.min.js"></script>

</head>
<!-- END: Head-->

<!-- BEGIN: Body-->
<?php
if ( isset( $_GET['method'] ) and $_GET['method'] == "ticket" ) {
?>
<body class="vertical-layout vertical-menu content-left-sidebar chat-application  fixed-navbar" data-open="click" data-menu="vertical-menu" data-color="bg-gradient-x-purple-blue" data-col="content-left-sidebar">
<?php
} else {
?>
<body class="vertical-layout vertical-menu 2-columns fixed-navbar" data-open="click" data-menu="vertical-menu" data-color="bg-gradient-x-purple-blue" data-col="2-columns">
<?php
}
?>

<!-- BEGIN: Header-->
<nav class="header-navbar navbar-expand-md navbar navbar-with-menu navbar-without-dd-arrow fixed-top navbar-semi-light">
    <div class="navbar-wrapper">
        <div class="navbar-container content">
            <div class="collapse navbar-collapse show" id="navbar-mobile">
                <ul class="nav navbar-nav mr-auto float-left">
                    <li class="nav-item mobile-menu d-md-none mr-auto">
                        <a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="ft-menu font-large-1"></i></a>
                    </li>
                    <li class="nav-item d-none d-md-block">
                        <a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="ft-menu"></i></a>
                    </li>
                    <li class="nav-item d-none d-md-block">
                        <a class="nav-link nav-link-expand" href="#"><i class="ficon ft-maximize"></i></a></li>
                </ul>
                <ul class="nav navbar-nav float-right">
                    <!-- Flag -->

                    <!-- Notification -->
					<?php include WP_AFRAEX::$plugin_path . '/templates/user-panel/panel-notification.php'; ?>

                    <!-- User Profile -->
                    <li class="dropdown dropdown-user nav-item">
                        <a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown">
                            <span class="avatar avatar-online">
                                <img src="<?php
                                echo get_avatar_url( get_current_user_id(), [ 'size' => '150' ] );
                                ?>" alt="avatar">
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <div class="arrow_box_right">
                                <a class="dropdown-item" href="#">
                                    <span class="avatar avatar-online">
                                        <img src="<?php
                                        echo get_avatar_url( get_current_user_id(), [ 'size' => '150' ] );
                                        ?>" alt="avatar">
                                        <span class="user-name text-bold-700 ml-1"><?php echo $user_data->first_name . ' ' . $user_data->last_name; ?></span>
                                    </span>
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="<?php echo add_query_arg( array( 'method' => 'profile' ), get_page_link( $post->ID ) ); ?>">
                                    <i class="ft-user"></i>
									<?php _e( 'My Profile', 'wp-afraex' ); ?>
                                </a>
                                <a class="dropdown-item" href="<?php echo add_query_arg( array( 'method' => 'ticket' ), get_page_link( $post->ID ) ); ?>"><i class="ft-mail"></i> <?php _e( "Support Ticket", "wp-afraex" ); ?>
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="<?php echo add_query_arg( array( 'method' => 'exit' ), get_page_link( $post->ID ) ); ?>"><i class="ft-power"></i> <?php _e( "Logout", "wp-afraex" ); ?>
                                </a>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
<!-- END: Header-->

<!-- BEGIN: Main Menu-->
<div class="main-menu menu-fixed menu-light menu-accordion    menu-shadow " data-scroll-to-active="true" data-img="<?php echo WP_AFRAEX::$plugin_url; ?>/templates/user-panel/app-assets/images/backgrounds/02.jpg">
    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item mr-auto">
                <a class="navbar-brand" href="#">
                    <img class="brand-logo" src="<?php echo $options['logo']; ?>" alt="<?php get_bloginfo( 'name' ); ?>"/>
                    <h3 class="brand-text">اَفرا اِکسچِنج</h3>
                </a>
            </li>
            <li class="nav-item d-md-none"><a class="nav-link close-navbar"><i class="ft-x"></i></a></li>
        </ul>
    </div>
    <div class="navigation-background"></div>
    <div class="main-menu-content">
		<?php include WP_AFRAEX::$plugin_path . '/templates/user-panel/panel-menu.php'; ?>
    </div>
</div>
<!-- END: Main Menu-->

<!-- Content -->
<?php include WP_AFRAEX::$plugin_path . '/templates/user-panel/panel-content.php'; ?>

<!-- BEGIN: Footer-->
<footer class="footer footer-static footer-light navbar-border navbar-shadow">
    <div class="clearfix blue-grey lighten-2 text-sm-center mb-0 px-2">
        <span class="float-md-left d-block d-md-inline-block"><?php _e( "All rights reserved", "wp-afraex" ); ?></span>
        <ul class="list-inline float-md-right d-block d-md-inline-blockd-none d-lg-block mb-0">
            <!--            <li class="list-inline-item"><a class="my-1" href="https://themeselection.com/" target="_blank"> More themes</a></li>-->
            <!--            <li class="list-inline-item"><a class="my-1" href="https://themeselection.com/support" target="_blank"> Support</a></li>-->
        </ul>
    </div>
</footer>
<!-- END: Footer-->

<!-- BEGIN: Vendor JS-->
<script src="<?php echo WP_AFRAEX::$plugin_url; ?>/templates/user-panel/app-assets/vendors/js/vendors.min.js" type="text/javascript"></script>
<script src="<?php echo WP_AFRAEX::$plugin_url; ?>/templates/user-panel/app-assets/vendors/js/forms/toggle/switchery.min.js" type="text/javascript"></script>
<script src="<?php echo WP_AFRAEX::$plugin_url; ?>/templates/user-panel/app-assets/js/scripts/forms/switch.min.js" type="text/javascript"></script>
<script src="<?php echo WP_AFRAEX::$plugin_url; ?>/templates/user-panel/app-assets/vendors/js/forms/select/select2.full.min.js" type="text/javascript"></script>

<!-- BEGIN Vendor JS-->

<!-- BEGIN: Page Vendor JS-->
<!-- END: Page Vendor JS-->

<!-- BEGIN: Theme JS-->
<script src="<?php echo WP_AFRAEX::$plugin_url; ?>/templates/user-panel/app-assets/js/core/app-menu.min.js" type="text/javascript"></script>
<script src="<?php echo WP_AFRAEX::$plugin_url; ?>/templates/user-panel/app-assets/js/core/app.min.js" type="text/javascript"></script>
<script src="<?php echo WP_AFRAEX::$plugin_url; ?>/templates/user-panel/app-assets/js/scripts/customizer.min.js" type="text/javascript"></script>
<script src="<?php echo WP_AFRAEX::$plugin_url; ?>/templates/user-panel/app-assets/vendors/js/jquery.sharrre.js" type="text/javascript"></script>
<!-- END: Theme JS-->

<!-- BEGIN: Page JS-->
<script src="<?php echo WP_AFRAEX::$plugin_url; ?>/templates/user-panel/assets/js/scripts.js?ver=<?php echo WP_AFRAEX::$plugin_version; ?>"></script>
<script src="<?php echo WP_AFRAEX::$plugin_url; ?>/templates/user-panel/app-assets/js/scripts/forms/select/form-select2.min.js"></script>

<?php
if ( isset( $_GET['method'] ) and $_GET['method'] == "ticket" ) {
	?>
    <script src="<?php echo WP_AFRAEX::$plugin_url; ?>/templates/user-panel/app-assets/js/scripts/pages/gap-application.js" type="text/javascript"></script>
	<?php
}
?>

<?php
if ( isset( $_GET['method'] ) and $_GET['method'] == "new_order" ) {
	    ?>
    <script src="<?php echo WP_AFRAEX::$plugin_url; ?>/templates/user-panel/assets/js/chartjs/chart.min.js" type="text/javascript"></script>
    <?php
	}
?>
<!-- END: Page JS-->

</body>
<!-- END: Body-->

</html>