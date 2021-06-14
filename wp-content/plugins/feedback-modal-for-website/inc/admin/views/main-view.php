<?php

if ( !current_user_can( 'manage_options' ) ) :
	wp_die( _e( 'You do not have sufficient permissions to access this page.', 'nedwp-feedback-modal' ) );
endif;

$active_tab = !empty( $_GET['tab'] ) ? $_GET['tab'] : 'general';

$plugin_tabs = array(
	'general' => __( 'General', 'nedwp-feedback-modal' ),
	'modal' => __( 'Modal', 'nedwp-feedback-modal' ),
	'notifs' => __( 'Notifications', 'nedwp-feedback-modal' ),
	'styles' => __( 'Styles', 'nedwp-feedback-modal' ),
	'messages' => __( 'Messages', 'nedwp-feedback-modal' ),
	'help' => __( 'Help', 'nedwp-feedback-modal' )
);

?>
<div class="wrap">

	<div id="icon-users" class="icon32"></div>
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

	<?php settings_errors(); ?>

	<div id="nedwp-fm-admin">

		<div id="nedwp-fm-admin-settings">
			
			<h2 class="nav-tab-wrapper">
				<?php
					foreach ( $plugin_tabs as $tab_key => $tab_name ) :
						$tab_class = ( $tab_key === $active_tab ) ? 'nav-tab nav-tab-active' : 'nav-tab';
						printf( '<a href="?page=%1$s&tab=%2$s" class="%3$s">%4$s</a>',
							esc_attr( NEDWP_FM_KEY ),
							esc_attr( $tab_key ),
							esc_attr( $tab_class ),
							esc_html( $tab_name )
						);
					endforeach;
				?>
			</h2>

			<?php if ( 'help' === $active_tab ) :
				include( NEDWP_FM_DIR . 'inc/admin/views/help-view.php' );
			else : ?>
				<form method="post" action="options.php">
					<?php
						settings_fields( sprintf( 'nedwp_fm_%1$s', $active_tab ) );
						do_settings_sections( sprintf( 'nedwp_fm_settings_%1$s', $active_tab ) );
						submit_button();
					?>
				</form>
			<?php endif; ?>

		</div>

		<div id="nedwp-fm-admin-sidebar">
			<?php include( NEDWP_FM_DIR . 'inc/admin/views/sbar-view.php' ); ?>
		</div>

	</div>

</div>