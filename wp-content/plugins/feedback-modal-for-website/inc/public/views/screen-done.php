<?php

$done_icon = apply_filters( 'nedwp_fm_screen_done_icon', 'la-check-circle' );

?>
<div class="nedwp-fm-modal-screen" id="nedwp-fm-modal-screen-done">
	<div class="nedwp-fm-modal-screen-wrap">
		<div class="nedwp-fm-modal-field" id="nedwp-fm-modal-field-done">
			<div class="nedwp-fm-modal-field-content">

				<div id="nedwp-fm-modal-done-screen-icon">
					<?php if ( nedwp_fm_opt( 'enable_icons' ) ) : ?>
						<i class="las <?php echo esc_attr( $done_icon ); ?>"></i>
					<?php endif; ?>
				</div>

				<div id="nedwp-fm-modal-done-screen-text">
					<span class="nedwp-fm-modal-title"><?php echo esc_html( nedwp_fm_opt( 'mo_thank' ) ); ?></span>
				</div>
				
			</div>
		</div>
	</div>
</div>