<?php

$opinion_req = ( nedwp_fm_opt( 'opinion_req' ) ) ? 'nedwp-required' : '';
$opinion_def = nedwp_fm_opt( 'opinion_def' );
$comment_req = ( nedwp_fm_opt( 'comment_req' ) ) ? ' required' : '';
$opinion_values = array(
	'1' => nedwp_fm_opt( 'op_value_1' ),
	'2' => nedwp_fm_opt( 'op_value_2' ),
	'3' => nedwp_fm_opt( 'op_value_3' ),
	'4' => nedwp_fm_opt( 'op_value_4' ),
	'5' => nedwp_fm_opt( 'op_value_5' )
);

?>
<div class="nedwp-fm-modal-screen" id="nedwp-fm-modal-screen-fb">

	<div class="nedwp-fm-modal-screen-wrap">

		<?php if ( nedwp_fm_opt( 'show_opinion' ) ) : ?>

			<div class="nedwp-fm-modal-field" id="nedwp-fm-modal-field-opinion">

				<div class="nedwp-fm-modal-field-head">
					<span class="nedwp-fm-modal-title"><?php echo esc_html( nedwp_fm_opt( 'mo_opinion' ) ); ?></span>
				</div>

				<div class="nedwp-fm-modal-field-content">
					<div id="nedwp-fm-modal-opinion-choice" class="<?php echo esc_attr( $opinion_req ); ?>">
						<?php foreach ( $opinion_values as $op_key => $op_value ) :
							$opinion_class = ( $op_key == $opinion_def ) ? ' nedwp-active' : '';
							?>
							<a href="#" class="nedwp-fm-modal-opinion-value<?php echo esc_attr( $opinion_class ); ?>" data-opinion="<?php echo esc_attr( $op_key ); ?>">
								<div class="nedwp-fm-modal-opinion-text"><?php echo esc_html( $op_key ); ?></div>
								<?php if ( '' !== $op_value ) : ?>
									<div class="nedwp-fm-modal-opinion-name"><?php echo esc_html( $op_value ); ?></div>
								<?php endif; ?>
							</a>
						<?php endforeach; ?>
					</div>
				</div>

				<div class="nedwp-fm-modal-field-notice" id="nedwp-fm-modal-opinion-notice"></div>

			</div>

		<?php endif;

		if ( nedwp_fm_opt( 'show_comment' ) ) : ?>

			<div class="nedwp-fm-modal-field" id="nedwp-fm-modal-field-comment">

				<div class="nedwp-fm-modal-field-head">
					<span class="nedwp-fm-modal-title"><?php echo esc_html( nedwp_fm_opt( 'mo_comment' ) ); ?></span>
				</div>

				<div class="nedwp-fm-modal-field-content">
					<textarea id="nedwp-fm-modal-textarea-comment" placeholder="<?php echo esc_attr( nedwp_fm_opt( 'mo_comment_i' ) ); ?>" class="nedwp-input"<?php echo esc_attr( $comment_req ); ?>></textarea>
				</div>

				<div class="nedwp-fm-modal-field-notice" id="nedwp-fm-modal-comment-notice"></div>

			</div>

		<?php endif; ?>

	</div>

	<div class="nedwp-fm-modal-screen-actions">

		<?php if ( nedwp_fm_opt( 'show_email' ) ) :
			include( NEDWP_FM_DIR . 'inc/public/views/action-next.php' );
		else :
			include( NEDWP_FM_DIR . 'inc/public/views/action-submit.php' );
		endif; ?>

	</div>

</div>