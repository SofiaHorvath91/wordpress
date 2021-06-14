<?php

if ( !nedwp_fm_opt( 'show_opinion' ) && !nedwp_fm_opt( 'show_comment' ) ) :
	return;
endif;

$modal_classes = array(
	sprintf( 'nedwp-fm-%1$s', nedwp_fm_opt( 'modal_position' ) ),
	sprintf( 'nedwp-fm-%1$s', nedwp_fm_opt( 'toggle_size' ) )
);

if ( nedwp_fm_opt( 'desktop_display' ) ) {
	$modal_classes[] = 'nedwp-fm-desktop';
}

if ( nedwp_fm_opt( 'mobile_display' ) ) {
	$modal_classes[] = 'nedwp-fm-mobile';
}

$modal_classes = (array) apply_filters( 'nedwp_fm_modal_classes', $modal_classes );
$toggle_icon = apply_filters( 'nedwp_fm_toggle_icon', nedwp_fm_opt( 'toggle_icon' ) );

?>
<div id="nedwp-fm" class="<?php echo esc_attr( join( ' ', $modal_classes ) ); ?>">

	<div id="nedwp-fm-toggle">

		<?php if ( nedwp_fm_opt( 'enable_icons' ) ) : ?>
			<i class="las <?php echo esc_attr( $toggle_icon ); ?>"></i>
		<?php endif; ?>

		<span><?php echo esc_html( nedwp_fm_opt( 'to_fb' ) ); ?></span>

	</div>

	<div id="nedwp-fm-modal">

		<div id="nedwp-fm-modal-head">

			<span id="nedwp-fm-modal-title"><?php echo esc_html( nedwp_fm_opt( 'mo_fb' ) ); ?></span>

			<?php if ( nedwp_fm_opt( 'enable_icons' ) ) : ?>
				<i id="nedwp-fm-modal-close" class="las la-times"></i>
			<?php else : ?>
				<span id="nedwp-fm-modal-close"><?php echo esc_html( nedwp_fm_opt( 'mo_cl' ) ); ?></span>
			<?php endif; ?>
			
		</div>

		<div id="nedwp-fm-modal-body">

			<?php do_action( 'nedwp_fm_views_modal_body_start' ); ?>

			<div id="nedwp-fm-modal-screens">
				<?php
					// Screen : feedback
					include( NEDWP_FM_DIR . 'inc/public/views/screen-fb.php' );

					// Screen : email
					if ( nedwp_fm_opt( 'show_email' ) ) :
						include( NEDWP_FM_DIR . 'inc/public/views/screen-email.php' );
					endif;

					// Screen : done
					include( NEDWP_FM_DIR . 'inc/public/views/screen-done.php' );

					// Screen : loader
					include( NEDWP_FM_DIR . 'inc/public/views/screen-loader.php' );

					do_action( 'nedwp_fm_views_modal_body_screens' );
				?>
			</div>

			<div id="nedwp-fm-modal-adjust"></div>

			<?php do_action( 'nedwp_fm_views_modal_body_end' ); ?>

		</div>
		
	</div>

	<?php if ( nedwp_fm_opt( 'screen_shadow' ) ) : ?>
		<div id="nedwp-fm-shadow"></div>
	<?php endif; ?>

</div>