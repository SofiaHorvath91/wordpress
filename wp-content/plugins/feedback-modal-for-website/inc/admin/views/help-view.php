<?php

$settings = array(
	'nedwp_fm_general' => __( 'General settings', 'nedwp-feedback-modal' ),
	'nedwp_fm_modal' => __( 'Modal settings', 'nedwp-feedback-modal' ),
	'nedwp_fm_notifs' => __( 'Notifications settings', 'nedwp-feedback-modal' ),
	'nedwp_fm_styles' => __( 'Styles settings', 'nedwp-feedback-modal' ),
	'nedwp_fm_messages' => __( 'Messages settings', 'nedwp-feedback-modal' )
);

?>
<div id="poststuff">
	<div id="post-body">

		<div class="postbox">
			<h3 class="hndle">
				<label><?php esc_html_e( 'Reset Settings', 'nedwp-feedback-modal' ); ?></label>
			</h3>
			<div class="inside">
				<form method="post" action="">
					<?php
						foreach ( $settings as $sett_key => $sett_name ) :
							printf( '<p><input type="checkbox" name="nedwp_fm_reset[%1$s]" /><label>%2$s</label></p>',
								esc_attr( $sett_key ),
								esc_html( $sett_name )
							);
						endforeach;
					?>
					<input type="submit" class="button button-secondary"  value="<?php esc_attr_e( 'Reset defaults', 'nedwp-feedback-modal' ); ?>" />
				</form>
			</div>
		</div>

		<div class="postbox">
			<h3 class="hndle">
				<label><?php esc_html_e( 'Capability Levels', 'nedwp-feedback-modal' ); ?></label>
			</h3>
			<div class="inside">
				<p>
					<?php
						$wp_roles_link = sprintf( '<a href="%1$s" target="_blank">%2$s</a>',
							'https://wordpress.org/support/article/roles-and-capabilities/',
							esc_html__( 'WordPress Roles and Capabilities page', 'nedwp-feedback-modal' )
						);
						printf( esc_html__( 'See the %1$s for details on capability levels.', 'nedwp-feedback-modal' ), $wp_roles_link );
					?>
				</p>
			</div>
		</div>

		<div class="postbox">
			<h3 class="hndle">
				<label><?php esc_html_e( 'Usage Tracking', 'nedwp-feedback-modal' ); ?></label>
			</h3>
			<div class="inside">
				<p>
					<?php
						$usage_tracking_link = sprintf( '<a href="%1$s" target="_blank">%2$s</a>',
							'http://bit.ly/2TiP83C',
							esc_html__( 'This is what we track.', 'nedwp-feedback-modal' )
						);
						printf( esc_html__( 'Help us make plugin better fit and understand your needs. %1$s', 'nedwp-feedback-modal' ), $usage_tracking_link );
					?>
				</p>
			</div>
		</div>

		<div class="postbox">
			<h3 class="hndle">
				<label><?php esc_html_e( 'Custom Request', 'nedwp-feedback-modal' ); ?></label>
			</h3>
			<div class="inside">
				<p>
					<?php esc_html_e( 'For custom plugin modification contact in this email address:', 'nedwp-feedback-modal' ); ?>
					<a href="mailto:info@nedwp.com" target="_blank">info@nedwp.com</a>
				</p>
			</div>
		</div>

		<div class="postbox">
			<h3 class="hndle">
				<label><?php esc_html_e( 'Plugin Support', 'nedwp-feedback-modal' ); ?></label>
			</h3>
			<div class="inside">
				<p><?php esc_html_e( "We're sorry you're having problem with the plugin and we're happy to help out.", 'nedwp-feedback-modal' ); ?></p>
				<p>
					<?php esc_html_e( 'Email:', 'nedwp-feedback-modal' ); ?>
					<a href="mailto:support@nedwp.com" target="_blank">support@nedwp.com</a>
				</p>
				<p>
					<?php esc_html_e( 'Website:', 'nedwp-feedback-modal' ); ?>
					<a href="http://bit.ly/35nJNKE" target="_blank">https://nedwp.com</a>
				</p>
			</div>
		</div>

		<div class="postbox">
			<h3 class="hndle">
				<label><?php esc_html_e( 'Plugin Donation', 'nedwp-feedback-modal' ); ?></label>
			</h3>
			<div class="inside">
				<p><?php esc_html_e( 'Feel like showing us how much you enjoy the plugin? Contribute some donation.', 'nedwp-feedback-modal' ); ?></p>
				<p>
					<?php esc_html_e( 'Buy Me a Coffee:', 'nedwp-feedback-modal' ); ?>
					<a href="https://bit.ly/3iLfEN3" target="_blank">https://bit.ly/3iLfEN3</a>
				</p>
			</div>
		</div>

		<div class="postbox">
			<h3 class="hndle">
				<label><?php esc_html_e( 'Plugin Credits', 'nedwp-feedback-modal' ); ?></label>
			</h3>
			<div class="inside">
				<p>
					<?php
						$nedwp_link = sprintf( '<a href="%1$s" target="_blank">%2$s</a>',
							'http://bit.ly/35nJNKE',
							'https://nedwp.com'
						);
						printf( esc_html__( '%1$s, a full-service digital agency approach to grow your business.', 'nedwp-feedback-modal' ), $nedwp_link );
					?>
				</p>
			</div>
		</div>

	</div>
</div>