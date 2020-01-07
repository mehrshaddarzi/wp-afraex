<ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
	<?php
	foreach ( $list_page as $pages_slug => $pages_val ) {
		?>
        <li class="nav-item <?php if ( isset( $_GET['method'] ) and $_GET['method'] == $pages_slug ) {
			echo 'open';
		} ?>">
            <a href="<?php echo add_query_arg( array( 'method' => $pages_slug ), get_page_link( $post->ID ) ); ?>">
                <i class="<?php echo $pages_val['icon']; ?>"></i>
                <span class="menu-title" data-i18n=""><?php echo $pages_val['title']; ?></span>
            </a>
        </li>
		<?php
	}
	?>
</ul>