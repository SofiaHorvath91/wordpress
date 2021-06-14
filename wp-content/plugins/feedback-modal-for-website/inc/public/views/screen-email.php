<?php

$email_req = ( nedwp_fm_opt( 'email_req' ) ) ? ' required' : '';

?>
<div class="nedwp-fm-modal-screen" id="nedwp-fm-modal-screen-email">

	<div class="nedwp-fm-modal-screen-wrap">
		<div class="nedwp-fm-modal-field" id="nedwp-fm-modal-field-email">

			<div class="nedwp-fm-modal-field-head">
				<span class="nedwp-fm-modal-title"><?php echo esc_html( nedwp_fm_opt( 'mo_email' ) ); ?></span>
			</div>

			<div class="nedwp-fm-modal-field-content">
				<input type="email" id="nedwp-fm-modal-input-email" placeholder="<?php echo esc_attr( nedwp_fm_opt( 'mo_email_i' ) ); ?>" class="nedwp-input"<?php echo esc_attr( $email_req ); ?> />
			</div>

			<div class="nedwp-fm-modal-field-notice" id="nedwp-fm-modal-email-notice"></div>
			
		</div>
	</div>

	<div class="nedwp-fm-modal-screen-actions">
		<?php
			include( NEDWP_FM_DIR . 'inc/public/views/action-back.php' );
			include( NEDWP_FM_DIR . 'inc/public/views/action-submit.php' );
		?>
	</div>

</div>