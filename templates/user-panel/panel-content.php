<?php
if ( isset( $_GET['method'] ) and $_GET['method'] == "ticket" ) {
	include WP_AFRAEX::$plugin_path . '/templates/user-panel/pages/' . trim( $_GET['method'] ) . '.php';
} else {
	?>
    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-wrapper-before"></div>
            <div class="content-header row">
                <div class="content-header-left col-md-4 col-12 mb-2">
                    <h3 class="content-header-title"><?php echo $list_page[ $_GET['method'] ]['title']; ?></h3>
                </div>
                <div class="content-header-right col-md-8 col-12">
                    <!-- breadcrumbs -->
                </div>
            </div>
            <div class="content-body">
				<?php include WP_AFRAEX::$plugin_path . '/templates/user-panel/pages/' . trim( $_GET['method'] ) . '.php'; ?>
            </div>
        </div>
    </div><!-- END: Content-->
	<?php
}