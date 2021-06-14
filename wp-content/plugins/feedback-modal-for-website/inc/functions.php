<?php

if ( !function_exists( 'nedwp_fm_opt_default' ) ) {
	function nedwp_fm_opt_default() {
		return array(
    		'nedwp_fm_general' => array(
	    		'desktop_display' => 'on',
	    		'mobile_display' => '',
	    		'exporting' => 'on',
	    		'capability' => 'nedwp_feedback_access',
	    		'tracking' => '',
	    		'notices' => 'on'
    		),
    		'nedwp_fm_modal' => array(
    			'visibility' => 'all',
				'modal_position' => 'left',
				'modal_zindex' => '999999999',
				'screen_shadow' => 'on',
				'toggle_size' => 'normal',
				'toggle_icon' => 'la-comments',
				'enable_icons' => 'on',
				'show_opinion' => 'on',
				'opinion_req' => 'on',
				'opinion_def' => '5',
				'show_comment' => 'on',
				'comment_req' => '',
				'show_email' => 'on',
				'email_req' => ''
    		),
    		'nedwp_fm_notifs' => array(
    			'no_enable' => '',
    			'no_to' => get_option( 'admin_email' ),
    			'no_subject' => sprintf( __( 'New feedback submitted at %1$s', 'nedwp-feedback-modal' ),
    				get_bloginfo( 'name' )
    			),
    			'no_body' => __( "A new feedback has been submitted. View it: %link%. This email was sent from your website by the %plugin% plugin at %time%.", 'nedwp-feedback-modal' )
    		),
    		'nedwp_fm_styles' => array(
    			'content_font' => 'Lato',
    			'primary_color' => '#598ad5',
    			'second_color' => '#787878',
    			'ovls_color' => '#ffffff',
    			'texts_color' => '#454545',
    			'inputs_color' => '#8e8e92',
    			'notices_color' => '#ed1b24'
    		),
    		'nedwp_fm_messages' => array(
				'to_fb' => __( 'Feedback', 'nedwp-feedback-modal' ),
				'mo_fb' => __( 'Feedback', 'nedwp-feedback-modal' ),
				'mo_cl' => __( 'Close', 'nedwp-feedback-modal' ),
				'ac_back' => __( 'Back', 'nedwp-feedback-modal' ),
				'ac_next' => __( 'Next', 'nedwp-feedback-modal' ),
				'ac_submit' => __( 'Submit', 'nedwp-feedback-modal' ),
				'mo_opinion' => __( 'How would you rate your experience?', 'nedwp-feedback-modal' ),
				'mo_comment' => __( 'Do you have any additional comment?', 'nedwp-feedback-modal' ),
				'mo_comment_i' => __( 'Please enter your comment here', 'nedwp-feedback-modal' ),
				'mo_email' => __( "Enter your email if you'd like us to contact you regarding with your feedback.", 'nedwp-feedback-modal' ),
				'mo_email_i' => __( 'Please enter your email here', 'nedwp-feedback-modal' ),
				'mo_thank' => __( 'Thank you for submitting your feedback!', 'nedwp-feedback-modal' ),
				'no_required' => __( 'This field is required.', 'nedwp-feedback-modal' ),
				'no_invalid' => __( 'This field is invalid.', 'nedwp-feedback-modal' ),
				'op_value_1' => __( 'Bad!', 'nedwp-feedback-modal' ),
				'op_value_2' => __( 'Poor!', 'nedwp-feedback-modal' ),
				'op_value_3' => __( 'Fair!', 'nedwp-feedback-modal' ),
				'op_value_4' => __( 'Good!', 'nedwp-feedback-modal' ),
				'op_value_5' => __( 'Great!', 'nedwp-feedback-modal' )
    		)
    	);
	}
}

if ( !function_exists( 'nedwp_fm_opt' ) ) {
	function nedwp_fm_opt( $option = '' ) {

		$fm_default = (array) nedwp_fm_opt_default();
		$fm_general = (array) get_option( 'nedwp_fm_general', $fm_default['nedwp_fm_general'] );
		$fm_modal = (array) get_option( 'nedwp_fm_modal', $fm_default['nedwp_fm_modal'] );
		$fm_notifs = (array) get_option( 'nedwp_fm_notifs', $fm_default['nedwp_fm_notifs'] );
		$fm_styles = (array) get_option( 'nedwp_fm_styles', $fm_default['nedwp_fm_styles'] );
		$fm_messages = (array) get_option( 'nedwp_fm_messages', $fm_default['nedwp_fm_messages'] );

		$fm_options = array_merge(
			$fm_general,
			$fm_modal,
			$fm_notifs,
			$fm_styles,
			$fm_messages
		);

		$fm_default = array_merge(
			$fm_default['nedwp_fm_general'],
			$fm_default['nedwp_fm_modal'],
			$fm_default['nedwp_fm_notifs'],
			$fm_default['nedwp_fm_styles'],
			$fm_default['nedwp_fm_messages']
		);

		$checkbox_keys = array(
			'desktop_display',
			'mobile_display',
			'exporting',
			'tracking',
			'notices',
			'screen_shadow',
			'enable_icons',
			'show_opinion',
			'opinion_req',
			'show_comment',
			'comment_req',
			'show_email',
			'email_req',
			'no_enable'
		);

		foreach ( $checkbox_keys as $checkbox_key ) {
			if ( isset( $fm_default[$checkbox_key] ) ) {
				$fm_default[$checkbox_key] = '';
			}
		}

        $fm_options = wp_parse_args( $fm_options, $fm_default );

        return isset( $fm_options[$option] ) ? $fm_options[$option] : '';
	}
}