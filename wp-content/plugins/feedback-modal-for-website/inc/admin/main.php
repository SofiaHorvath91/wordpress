<?php

require_once( NEDWP_FM_DIR . 'inc/admin/list.php' );
require_once( NEDWP_FM_DIR . 'inc/admin/fonts.php' );
require_once( NEDWP_FM_DIR . 'inc/admin/export.php' );
require_once( NEDWP_FM_DIR . 'inc/admin/tracking.php' );

if ( !class_exists( 'Nedwp_Feedback_Modal_Admin' ) ) {

	class Nedwp_Feedback_Modal_Admin {

		protected static $instance;

		public static function initiate() {

			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

	    public function __construct() {

	    	// Init
	    	register_activation_hook( NEDWP_FM_FIL, array( $this, 'on_activation' ) );
	    	register_deactivation_hook( NEDWP_FM_FIL, array( $this, 'on_deactivation' ) );

			// Scripts
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
			add_action( 'admin_footer', array( $this, 'enqueue_scripts_inline' ) );

	    	// Contents
		    add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		    add_action( 'admin_init', array( $this, 'register_settings' ) );

	        // Submit
		    add_action( 'wp_ajax_nopriv_nedwp_fm_handle_submit', array( $this, 'handle_submit' ) );
		    add_action( 'wp_ajax_nedwp_fm_handle_submit', array( $this, 'handle_submit' ) );

		    // Export
		    add_action( 'init', array( $this, 'handle_export' ) );

		    // Notices
		    add_action( 'admin_notices', array( $this, 'add_notice_donation' ) );
		    add_action( 'wp_ajax_nopriv_nedwp_fm_dismiss_notice_donation', array( $this, 'dismiss_notice_donation' ) );
		    add_action( 'wp_ajax_nedwp_fm_dismiss_notice_donation', array( $this, 'dismiss_notice_donation' ) );

		    // Tracking
		    add_action( 'init', array( $this, 'usage_tracking' ) );

	    }

	    public function on_activation( $network_wide ) {

		    if ( function_exists( 'is_multisite' ) && is_multisite() && $network_wide ) {

				if ( function_exists( 'get_sites' ) ) {
					$blog_ids = get_sites( array( 'fields' => 'ids' ) );
				} else {
					global $wpdb;
					$blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
				}

		        foreach ( $blog_ids as $blog_id ) {
		            switch_to_blog( $blog_id );
		            $this->create_db_table();
		            restore_current_blog();
		        }

		    } else {
		        $this->create_db_table();
		    }

		    // Capability level
			$role = get_role( 'administrator' );
			$role->add_cap( 'nedwp_feedback_access' );

	    }

	    public function on_deactivation( $network_wide ) {

			global $wp_roles;

			if ( !empty( $wp_roles ) ) {
				foreach( array_keys( $wp_roles->roles ) as $role ) {
					$wp_roles->remove_cap( $role, 'nedwp_feedback_access' );
				}
			}

	    }

	    public function create_db_table() {

			global $wpdb;
			$table_name = $wpdb->prefix . 'nedwp_fm';

			if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) != $table_name ) {

			    $charset_collate = $wpdb->get_charset_collate();

			    $sql = "CREATE TABLE $table_name (
			        id int(10) NOT NULL AUTO_INCREMENT,
					opinion int(2),
					comment text,
					email varchar(255),
					date_in datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			        PRIMARY KEY (id)
			    ) $charset_collate;";

			    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

			    dbDelta( $sql );

			}

			// Install date
			add_option( 'nedwp_fm_install_date', date( 'Y-m-d H:i:s' ) );

	    }

		public function enqueue_scripts() {

			if ( !empty( $_GET['page'] ) ) {

				$page = sanitize_text_field( $_GET['page'] );

				if ( false !== strpos( $_GET['page'], NEDWP_FM_KEY ) ) {

					// CSS
					wp_enqueue_style( 'wp-color-picker' );
					wp_enqueue_style( 'nedwp-fm-admin', NEDWP_FM_CSS . '/fm-admin.css', array(), NEDWP_FM_VER );

					// JS
					wp_enqueue_script( 'theia-sticky-sidebar', NEDWP_FM_JS . '/theia-sticky-sidebar.js', array( 'jquery' ), '1.7.0', true );
					wp_enqueue_script( 'nedwp-fm-admin', NEDWP_FM_JS . '/fm-admin.js', array( 'jquery', 'wp-color-picker' ), NEDWP_FM_VER );
					wp_localize_script( 'nedwp-fm-admin', 'nedwp_fm_admin_var', array(
					    'ajax_url' => admin_url( 'admin-ajax.php' ),
					    'ajax_nonce' => wp_create_nonce( 'ajaxnonce' )
					));

					do_action( 'nedwp_fm_admin_enqueue_scripts' );

				}

			}

		}

		public function enqueue_scripts_inline() {
			echo '
				<script>
					jQuery(document).ready(function() {
						jQuery("#nedwp-fm-notice-donation-dismiss").on("click", function(e) {
							e.preventDefault();
							jQuery.ajax({
								type: "POST",
								url: "'.esc_js( admin_url( 'admin-ajax.php' ) ).'",
								data: {
									"action": "nedwp_fm_dismiss_notice_donation",
									"security": "'.esc_js( wp_create_nonce( 'ajaxnonce' ) ).'"
								}
							});
							jQuery("#nedwp-fm-notice-donation").hide(150);
						});
					});
				</script>
			';
		}

	    public function add_admin_menu() {

	    	add_menu_page(
	    		__( 'Feedback Modal Settings', 'nedwp-feedback-modal' ),
	    		__( 'Feedback Modal', 'nedwp-feedback-modal' ),
	    		'manage_options',
	    		NEDWP_FM_KEY,
	    		array( $this, 'main_page_html' ),
	    		'dashicons-testimonial'
	    	);

	    	add_submenu_page(
	    		NEDWP_FM_KEY,
	    		__( 'Feedback Modal: Settings', 'nedwp-feedback-modal' ),
	    		__( 'Settings', 'nedwp-feedback-modal' ),
	    		'manage_options',
	    		NEDWP_FM_KEY,
	    		array( $this, 'main_page_html' )
	    	);

	    	add_submenu_page(
	    		NEDWP_FM_KEY,
	    		__( 'Feedback Modal: Feedback', 'nedwp-feedback-modal' ),
	    		__( 'Feedback', 'nedwp-feedback-modal' ),
	    		$this->data_capability(),
	    		sprintf( '%1$s-list', NEDWP_FM_KEY ),
	    		array( $this, 'list_page_html' )
	    	);

	    }

	    public function plugin_name() {
	    	return __( 'Feedback Modal for Website', 'nedwp-feedback-modal' );
	    }

	    public function main_page_html() {
	    	$this->handle_reset_settings();
	    	include( NEDWP_FM_DIR . 'inc/admin/views/main-view.php' );
	    }

		public function list_page_html() {

			$data_capability = $this->data_capability();
			$list = new Nedwp_Feedback_Modal_Admin_List();
			$list->prepare_items();

			include( NEDWP_FM_DIR . 'inc/admin/views/list-view.php' );
		}

	    public function default_settings( $setting = '' ) {
	    	$default = nedwp_fm_opt_default();
			return ( '' !== $setting ) ? $default[$setting] : $default;
	    }

	    public function category_settings() {
	    	return array(
	    		'nedwp_fm_general',
	    		'nedwp_fm_modal',
	    		'nedwp_fm_notifs',
	    		'nedwp_fm_styles',
	    		'nedwp_fm_messages'
	    	);
	    }

	    public function register_settings() {

	    	$settings = $this->category_settings();

	    	// Settings initiate
			foreach ( $settings as $setting ) {
				register_setting( $setting, $setting, array( $this, 'fields_sanitize' ) );
			}

			// Settings sections
			$sections = array(
				'nedwp_fm_settings_general' => array(
					__( 'General settings', 'nedwp-feedback-modal' ),
					'register_settings_se_info'
				),
				'nedwp_fm_settings_modal' => array(
					__( 'Modal settings', 'nedwp-feedback-modal' ),
					'register_settings_se_info'
				),
				'nedwp_fm_settings_notifs' => array(
					__( 'Notifications settings', 'nedwp-feedback-modal' ),
					'register_settings_se_info'
				),
				'nedwp_fm_settings_styles' => array(
					__( 'Styles settings', 'nedwp-feedback-modal' ),
					'register_settings_se_info'
				),
				'nedwp_fm_settings_messages' => array(
					__( 'Messages settings', 'nedwp-feedback-modal' ),
					'register_settings_se_info'
				)
			);

			foreach ( $sections as $section_id => $section_data ) {
				add_settings_section(
				    $section_id,
				    $section_data[0],
				    array( $this, $section_data[1] ),
				    $section_id
				);
			}

			// Settings fields
	    	$fields = array(
		    	// General
	    		array(
	    			'id' => 'desktop_display',
					'title' => __( 'Enable in desktop:', 'nedwp-feedback-modal' ),
					'description' => __( 'Enable or disable feedback modal in desktop devices.', 'nedwp-feedback-modal' ),
					'section' => 'general',
					'type' => 'checkbox'
	    		),
	    		array(
	    			'id' => 'mobile_display',
					'title' => __( 'Enable in mobile:', 'nedwp-feedback-modal' ),
					'description' => __( 'Enable or disable feedback modal in mobile devices.', 'nedwp-feedback-modal' ),
					'section' => 'general',
					'type' => 'checkbox'
	    		),
	    		array(
	    			'id' => 'capability',
					'title' => __( 'Capability level:', 'nedwp-feedback-modal' ),
					'description' => __( 'Required user level to view and manage feedback data.', 'nedwp-feedback-modal' ),
					'section' => 'general',
					'type' => 'select',
					'choices' => $this->capability_levels()
	    		),
	    		array(
	    			'id' => 'exporting',
					'title' => __( 'Exporting data:', 'nedwp-feedback-modal' ),
					'description' => __( 'Enable or disable exporting feature for feedback data.', 'nedwp-feedback-modal' ),
					'section' => 'general',
					'type' => 'checkbox'
	    		),
	    		array(
	    			'id' => 'tracking',
					'title' => __( 'Usage tracking:', 'nedwp-feedback-modal' ),
					'description' => __( 'Allow us to anonymously track how this plugin is used.', 'nedwp-feedback-modal' ),
					'section' => 'general',
					'type' => 'checkbox'
	    		),
	    		array(
	    			'id' => 'notices',
					'title' => __( 'Plugin notices:', 'nedwp-feedback-modal' ),
					'description' => __( 'Enable or disable to show notices and suggestion.', 'nedwp-feedback-modal' ),
					'section' => 'general',
					'type' => 'checkbox'
	    		),
	    		// Modal
	    		array(
	    			'id' => 'visibility',
					'title' => __( 'Modal visibility:', 'nedwp-feedback-modal' ),
					'description' => __( 'Select where the feedback modal will be displayed.', 'nedwp-feedback-modal' ),
					'section' => 'modal',
					'type' => 'select',
					'choices' => array(
						'all' => __( 'All pages', 'nedwp-feedback-modal' ),
						'front' => __( 'Front page', 'nedwp-feedback-modal' )
					)
	    		),
	    		array(
	    			'id' => 'modal_position',
					'title' => __( 'Modal position:', 'nedwp-feedback-modal' ),
					'description' => __( 'Select the position for feedback modal and toggle.', 'nedwp-feedback-modal' ),
					'section' => 'modal',
					'type' => 'select',
					'choices' => array(
						'right' => __( 'Right', 'nedwp-feedback-modal' ),
						'left' => __( 'Left', 'nedwp-feedback-modal' )
					)
	    		),
	    		array(
	    			'id' => 'modal_zindex',
					'title' => __( 'Modal z-index:', 'nedwp-feedback-modal' ),
					'description' => __( 'Select z-index stack order of the modal element.', 'nedwp-feedback-modal' ),
					'section' => 'modal',
					'type' => 'select',
					'choices' => array(
						'999999' => __( '999999', 'nedwp-feedback-modal' ),
						'9999999' => __( '9999999', 'nedwp-feedback-modal' ),
						'99999999' => __( '99999999', 'nedwp-feedback-modal' ),
						'999999999' => __( '999999999', 'nedwp-feedback-modal' ),
						'9999999999' => __( '9999999999', 'nedwp-feedback-modal' ),
						'99999999999' => __( '99999999999', 'nedwp-feedback-modal' ),
						'999999999999' => __( '999999999999', 'nedwp-feedback-modal' )
					)
	    		),
	    		array(
	    			'id' => 'toggle_size',
					'title' => __( 'Size of toggle:', 'nedwp-feedback-modal' ),
					'description' => __( 'Select your preferred size for modal toggle button.', 'nedwp-feedback-modal' ),
					'section' => 'modal',
					'type' => 'select',
					'choices' => array(
						'mini' => __( 'Mini', 'nedwp-feedback-modal' ),
						'normal' => __( 'Normal', 'nedwp-feedback-modal' )
					)
	    		),
	    		array(
	    			'id' => 'toggle_icon',
					'title' => __( 'Icon of toggle:', 'nedwp-feedback-modal' ),
					'description' => __( 'Select Line Awesome icon for modal toggle button.', 'nedwp-feedback-modal' ),
					'section' => 'modal',
					'type' => 'select',
					'choices' => array(
						'la-comment' => __( 'la-comment', 'nedwp-feedback-modal' ),
						'la-comments' => __( 'la-comments', 'nedwp-feedback-modal' ),
						'la-comment-alt' => __( 'la-comment-alt', 'nedwp-feedback-modal' ),
						'la-comment-dots' => __( 'la-comment-dots', 'nedwp-feedback-modal' ),
						'la-comment-medical' => __( 'la-comment-medical', 'nedwp-feedback-modal' ),
						'la-envelope-open' => __( 'la-envelope-open', 'nedwp-feedback-modal' ),
						'la-bell' => __( 'la-bell', 'nedwp-feedback-modal' ),
						'la-bullhorn' => __( 'la-bullhorn', 'nedwp-feedback-modal' ),
						'la-envelope' => __( 'la-envelope', 'nedwp-feedback-modal' ),
						'la-inbox' => __( 'la-inbox', 'nedwp-feedback-modal' ),
						'la-edit' => __( 'la-edit', 'nedwp-feedback-modal' ),
						'la-copy' => __( 'la-copy', 'nedwp-feedback-modal' ),
						'la-stop' => __( 'la-stop', 'nedwp-feedback-modal' )
					)
	    		),
	    		array(
	    			'id' => 'screen_shadow',
					'title' => __( 'Screen shadow:', 'nedwp-feedback-modal' ),
					'description' => __( 'Enable or disable screen shadow behind the modal.', 'nedwp-feedback-modal' ),
					'section' => 'modal',
					'type' => 'checkbox'
	    		),
	    		array(
	    			'id' => 'enable_icons',
					'title' => __( 'Enable icons:', 'nedwp-feedback-modal' ),
					'description' => __( 'Enable or disable the use of icons in modal buttons.', 'nedwp-feedback-modal' ),
					'section' => 'modal',
					'type' => 'checkbox'
	    		),
	    		array(
	    			'id' => 'modal_field_opinion_sep',
					'title' => '',
					'description' => '',
					'section' => 'modal',
					'type' => 'seperator'
	    		),
	    		array(
	    			'id' => 'show_opinion',
					'title' => __( 'Show opinion:', 'nedwp-feedback-modal' ),
					'description' => __( 'Show or hide opinion field in feedback modal form.', 'nedwp-feedback-modal' ),
					'section' => 'modal',
					'type' => 'checkbox'
	    		),
	    		array(
	    			'id' => 'opinion_req',
					'title' => __( 'Opinion required:', 'nedwp-feedback-modal' ),
					'description' => __( 'Make the opinion field required in feedback modal.', 'nedwp-feedback-modal' ),
					'section' => 'modal',
					'type' => 'checkbox'
	    		),
	    		array(
	    			'id' => 'opinion_def',
					'title' => __( 'Opinion default:', 'nedwp-feedback-modal' ),
					'description' => __( 'Select default opinion field value in feedback modal.', 'nedwp-feedback-modal' ),
					'section' => 'modal',
					'type' => 'select',
					'choices' => array(
						'1' => __( '1', 'nedwp-feedback-modal' ),
						'2' => __( '2', 'nedwp-feedback-modal' ),
						'3' => __( '3', 'nedwp-feedback-modal' ),
						'4' => __( '4', 'nedwp-feedback-modal' ),
						'5' => __( '5', 'nedwp-feedback-modal' ),
						'-' => __( 'Disable', 'nedwp-feedback-modal' )
					)
	    		),
	    		array(
	    			'id' => 'modal_field_comment_sep',
					'title' => '',
					'description' => '',
					'section' => 'modal',
					'type' => 'seperator'
	    		),
	    		array(
	    			'id' => 'show_comment',
					'title' => __( 'Show comment:', 'nedwp-feedback-modal' ),
					'description' => __( 'Show or hide comment field in feedback modal form.', 'nedwp-feedback-modal' ),
					'section' => 'modal',
					'type' => 'checkbox'
	    		),
	    		array(
	    			'id' => 'comment_req',
					'title' => __( 'Comment required:', 'nedwp-feedback-modal' ),
					'description' => __( 'Make the comment field required in feedback modal.', 'nedwp-feedback-modal' ),
					'section' => 'modal',
					'type' => 'checkbox'
	    		),
	    		array(
	    			'id' => 'modal_field_email_sep',
					'title' => '',
					'description' => '',
					'section' => 'modal',
					'type' => 'seperator'
	    		),
	    		array(
	    			'id' => 'show_email',
					'title' => __( 'Show email:', 'nedwp-feedback-modal' ),
					'description' => __( 'Show or hide email field in feedback modal form.', 'nedwp-feedback-modal' ),
					'section' => 'modal',
					'type' => 'checkbox'
	    		),
	    		array(
	    			'id' => 'email_req',
					'title' => __( 'Email required:', 'nedwp-feedback-modal' ),
					'description' => __( 'Make the email field required in feedback modal.', 'nedwp-feedback-modal' ),
					'section' => 'modal',
					'type' => 'checkbox'
	    		),
	    		// Notifications
	    		array(
	    			'id' => 'no_enable',
					'title' => __( 'Email notifications:', 'nedwp-feedback-modal' ),
					'description' => __( 'Enable or disable email notifications for new feedback.', 'nedwp-feedback-modal' ),
					'section' => 'notifs',
					'type' => 'checkbox'
	    		),
	    		array(
	    			'id' => 'no_to',
					'title' => __( 'Email recipient/s:', 'nedwp-feedback-modal' ),
					'description' => __( 'Enter email recipient/s separated by commas [,].', 'nedwp-feedback-modal' ),
					'section' => 'notifs',
					'type' => 'textarea'
	    		),
	    		array(
	    			'id' => 'no_subject',
					'title' => __( 'Email subject:', 'nedwp-feedback-modal' ),
					'description' => __( 'Input email subject for the feedback notifications.', 'nedwp-feedback-modal' ),
					'section' => 'notifs',
					'type' => 'text'
	    		),
	    		array(
	    			'id' => 'no_body',
					'title' => __( 'Email message:', 'nedwp-feedback-modal' ),
					'description' => __( 'Input email body for the feedback notifications. Use %link% for feedback link, %time% for feedback time, %plugin% for plugin name. For submitted feedback data use %opinion%, %comment%, %email%.', 'nedwp-feedback-modal' ),
					'section' => 'notifs',
					'type' => 'wysiwyg'
	    		),
	    		// styles
	    		array(
	    			'id' => 'content_font',
					'title' => __( 'Content font:', 'nedwp-feedback-modal' ),
					'description' => __( 'Select the main font for feedback modal.', 'nedwp-feedback-modal' ),
					'section' => 'styles',
					'type' => 'select',
					'choices' => $this->fonts_list()
	    		),
	    		array(
	    			'id' => 'styles_field_color_sep',
					'title' => '',
					'description' => '',
					'section' => 'styles',
					'type' => 'seperator'
	    		),
	    		array(
	    			'id' => 'primary_color',
					'title' => __( 'Primary color:', 'nedwp-feedback-modal' ),
					'description' => __( 'Select primary color for feedback modal.', 'nedwp-feedback-modal' ),
					'section' => 'styles',
					'type' => 'color'
	    		),
	    		array(
	    			'id' => 'second_color',
					'title' => __( 'Secondary color:', 'nedwp-feedback-modal' ),
					'description' => __( 'Select secondary color for feedback modal.', 'nedwp-feedback-modal' ),
					'section' => 'styles',
					'type' => 'color'
	    		),
	    		array(
	    			'id' => 'ovls_color',
					'title' => __( 'Overlays color:', 'nedwp-feedback-modal' ),
					'description' => __( 'Select overlays color for feedback modal.', 'nedwp-feedback-modal' ),
					'section' => 'styles',
					'type' => 'color'
	    		),
	    		array(
	    			'id' => 'texts_color',
					'title' => __( 'Texts color:', 'nedwp-feedback-modal' ),
					'description' => __( 'Select content color for feedback modal.', 'nedwp-feedback-modal' ),
					'section' => 'styles',
					'type' => 'color'
	    		),
	    		array(
	    			'id' => 'inputs_color',
					'title' => __( 'Inputs color:', 'nedwp-feedback-modal' ),
					'description' => __( 'Select inputs color for feedback modal.', 'nedwp-feedback-modal' ),
					'section' => 'styles',
					'type' => 'color'
	    		),
	    		array(
	    			'id' => 'notices_color',
					'title' => __( 'Notices color:', 'nedwp-feedback-modal' ),
					'description' => __( 'Select notices color for feedback modal.', 'nedwp-feedback-modal' ),
					'section' => 'styles',
					'type' => 'color'
	    		),
	    		// String
	    		array(
	    			'id' => 'to_fb',
					'title' => __( 'Feedback ...', 'nedwp-feedback-modal' ),
					'description' => __( 'Default (toggle): Feedback', 'nedwp-feedback-modal' ),
					'section' => 'messages',
					'type' => 'text'
	    		),
	    		array(
	    			'id' => 'mo_fb',
					'title' => __( 'Feedback ...', 'nedwp-feedback-modal' ),
					'description' => __( 'Default (modal): Feedback', 'nedwp-feedback-modal' ),
					'section' => 'messages',
					'type' => 'text'
	    		),
	    		array(
	    			'id' => 'mo_cl',
					'title' => __( 'Close ...', 'nedwp-feedback-modal' ),
					'description' => __( 'Default: Close', 'nedwp-feedback-modal' ),
					'section' => 'messages',
					'type' => 'text'
	    		),
	    		array(
	    			'id' => 'mo_opinion',
					'title' => __( 'How would ...', 'nedwp-feedback-modal' ),
					'description' => __( 'Default: How would you rate your experience?', 'nedwp-feedback-modal' ),
					'section' => 'messages',
					'type' => 'text'
	    		),
	    		array(
	    			'id' => 'mo_comment',
					'title' => __( 'Do you ...', 'nedwp-feedback-modal' ),
					'description' => __( 'Default: Do you have any additional comment?', 'nedwp-feedback-modal' ),
					'section' => 'messages',
					'type' => 'text'
	    		),
	    		array(
	    			'id' => 'mo_comment_i',
					'title' => __( 'Please enter ...', 'nedwp-feedback-modal' ),
					'description' => __( 'Default: Please enter your comment here', 'nedwp-feedback-modal' ),
					'section' => 'messages',
					'type' => 'text'
	    		),
	    		array(
	    			'id' => 'mo_email',
					'title' => __( 'Enter your ...', 'nedwp-feedback-modal' ),
					'description' => __( "Default: Enter your email if you'd like us to contact you regarding with your feedback.", 'nedwp-feedback-modal' ),
					'section' => 'messages',
					'type' => 'text'
	    		),
	    		array(
	    			'id' => 'mo_email_i',
					'title' => __( 'Please enter ...', 'nedwp-feedback-modal' ),
					'description' => __( 'Default: Please enter your email here', 'nedwp-feedback-modal' ),
					'section' => 'messages',
					'type' => 'text'
	    		),
	    		array(
	    			'id' => 'mo_thank',
					'title' => __( 'Thank you ...', 'nedwp-feedback-modal' ),
					'description' => __( 'Default: Thank you for submitting your feedback!', 'nedwp-feedback-modal' ),
					'section' => 'messages',
					'type' => 'text'
	    		),
	    		array(
	    			'id' => 'ac_back',
					'title' => __( 'Back ...', 'nedwp-feedback-modal' ),
					'description' => __( 'Default: Back', 'nedwp-feedback-modal' ),
					'section' => 'messages',
					'type' => 'text'
	    		),
	    		array(
	    			'id' => 'no_required',
					'title' => __( 'This field ...', 'nedwp-feedback-modal' ),
					'description' => __( 'Default: This field is required.', 'nedwp-feedback-modal' ),
					'section' => 'messages',
					'type' => 'text'
	    		),
	    		array(
	    			'id' => 'no_invalid',
					'title' => __( 'This field ...', 'nedwp-feedback-modal' ),
					'description' => __( 'Default: This field is invalid.', 'nedwp-feedback-modal' ),
					'section' => 'messages',
					'type' => 'text'
	    		),
	    		array(
	    			'id' => 'ac_next',
					'title' => __( 'Next ...', 'nedwp-feedback-modal' ),
					'description' => __( 'Default: Next', 'nedwp-feedback-modal' ),
					'section' => 'messages',
					'type' => 'text'
	    		),
	    		array(
	    			'id' => 'ac_submit',
					'title' => __( 'Submit ...', 'nedwp-feedback-modal' ),
					'description' => __( 'Default: Submit', 'nedwp-feedback-modal' ),
					'section' => 'messages',
					'type' => 'text'
	    		),
	    		array(
	    			'id' => 'op_value_1',
					'title' => __( 'Bad ...', 'nedwp-feedback-modal' ),
					'description' => __( 'Default: Bad!', 'nedwp-feedback-modal' ),
					'section' => 'messages',
					'type' => 'text'
	    		),
	    		array(
	    			'id' => 'op_value_2',
					'title' => __( 'Poor ...', 'nedwp-feedback-modal' ),
					'description' => __( 'Default: Poor!', 'nedwp-feedback-modal' ),
					'section' => 'messages',
					'type' => 'text'
	    		),
	    		array(
	    			'id' => 'op_value_3',
					'title' => __( 'Fair ...', 'nedwp-feedback-modal' ),
					'description' => __( 'Default: Fair!', 'nedwp-feedback-modal' ),
					'section' => 'messages',
					'type' => 'text'
	    		),
	    		array(
	    			'id' => 'op_value_4',
					'title' => __( 'Good ...', 'nedwp-feedback-modal' ),
					'description' => __( 'Default: Good!', 'nedwp-feedback-modal' ),
					'section' => 'messages',
					'type' => 'text'
	    		),
	    		array(
	    			'id' => 'op_value_5',
					'title' => __( 'Great ...', 'nedwp-feedback-modal' ),
					'description' => __( 'Default: Great!', 'nedwp-feedback-modal' ),
					'section' => 'messages',
					'type' => 'text'
	    		)
	    	);

	    	// Add settings field
	    	foreach ( $fields as $field ) {

	    		$args = array(
					'label_for' => $field['id'],
					'id' => $field['id'],
					'section' => $field['section'],
					'type' => $field['type']
	    		);

	    		if ( !empty( $field['choices'] ) ) {
	    			$args['choices'] = $field['choices'];
	    		}

	    		if ( !empty( $field['description'] ) ) {
	    			$args['description'] = $field['description'];
	    		}

				add_settings_field(
				    $field['id'],
				    $field['title'],
				    array( $this, 'fields_render' ),
				    sprintf( 'nedwp_fm_settings_%1$s', $field['section'] ),
				    sprintf( 'nedwp_fm_settings_%1$s', $field['section'] ),
				    $args
				);

	    	}

	    	// Settings default
			foreach ( $settings as $setting ) {
				if ( false === get_option( $setting ) ) {
					update_option( $setting, $this->default_settings( $setting ) );
				}
			}

	    }

		public function handle_reset_settings() {

			if ( !empty( $_POST['nedwp_fm_reset'] ) && is_array( $_POST['nedwp_fm_reset'] ) ) {

				$fm_reset = $_POST['nedwp_fm_reset'];
		    	$fm_settings = $this->category_settings();

				foreach ( array_keys( $fm_reset ) as $key ) {

					$key = sanitize_text_field( $key );

					if ( in_array( $key, $fm_settings ) ) {
						update_option( $key, $this->default_settings( $key ) );
					}

				}

				add_settings_error(
					'nedwp_fm_reset_notice',
					'nedwp_fm_reset_notice',
					__( 'Default settings restored.', 'nedwp-feedback-modal' ),
					'success'
				);

			}

		}

		public function register_settings_se_info() {
			printf( '<p>%1$s</p>',
				esc_html__( 'These options are generaly safe to edit as needed.', 'nedwp-feedback-modal' )
			);
		}

		public function fields_sanitize( $input ) {
			
			$output = array();

	    	if ( $input && is_array( $input ) ) {

				foreach ( $input as $key => $value ) {

					if ( in_array( $key, array( 'no_to' ) ) ) {
						$output[$key] = $this->sanitize_email_field( $value );
					} else if ( in_array( $key, array( 'primary_color' ) ) ) {
						$output[$key] = $this->sanitize_color_field( $key, $value );
					} else if ( in_array( $key, array( 'no_body' ) ) ) {
						$output[$key] = wp_filter_post_kses( $value );
					} else {
						$output[$key] = sanitize_text_field( $value );
					}

				}

	    	}

	    	return $output;
		}

		public function sanitize_email_field( $value ) {

			if ( !empty( $value ) ) {
				
				$to = array_map( 'sanitize_email', explode( ',', $value ) );

				if ( $to ) {
					return implode( ',', $to );
				}

			}

			return '';
		}

		public function sanitize_color_field( $key, $value ) {
			$value = sanitize_hex_color( $value );
			return ( $value ) ? $value : nedwp_fm_opt( $key );
		}

		public function fields_render( $args ) {

			$section = ( !empty( $args['section'] ) ) ? $args['section'] : 'general';
			$section = 'nedwp_fm_' . $section;
			$id = ( isset( $args['id'] ) ) ? $args['id'] : '';
			$type = ( !empty( $args['type'] ) ) ? $args['type'] : 'text';
			$value = nedwp_fm_opt( $id );

			if ( $type == 'select' && !empty( $args['choices'] ) ) :
				printf( '<select id="%1$s" name="%2$s[%1$s]">', esc_attr( $id ), esc_attr( $section ) );
					foreach ( $args['choices'] as $ch_key => $ch_name ) :
						printf( '<option value="%1$s" %2$s>%3$s</option>',
							esc_attr( $ch_key ),
							selected( $ch_key, $value, false ),
							esc_html( $ch_name )
						);
					endforeach;
				print( '</select>' );
			elseif ( $type === 'checkbox' ):
				printf( '<input type="checkbox" id="%1$s" name="%2$s[%1$s]" %3$s />',
					esc_attr( $id ),
					esc_attr( $section ),
					checked( $value, 'on', false )
				);
			elseif ( $type === 'color' ) :
				printf( '<input class="wp-color-picker" type="text" id="%1$s" name="%2$s[%1$s]" value="%3$s" />',
					esc_attr( $id ),
					esc_attr( $section ),
					esc_attr( $value )
				);
			elseif ( $type === 'textarea' ) :
				printf( '<textarea rows="3" cols="50" id="%1$s" name="%2$s[%1$s]">%3$s</textarea>',
					esc_attr( $id ),
					esc_attr( $section ),
					esc_textarea( $value )
				);
			elseif ( $type === 'wysiwyg' ) :
				wp_editor( $value, esc_attr( $id ),
					array(
						'textarea_rows' => '6',
						'textarea_name' => sprintf( '%2$s[%1$s]', esc_attr( $id ), esc_attr( $section ) ),
						'media_buttons' => false,
						'teeny' => true
					)
				);
			elseif ( $type === 'text' ) :
				printf( '<input size="49" type="text" id="%1$s" name="%2$s[%1$s]" value="%3$s" />',
					esc_attr( $id ),
					esc_attr( $section ),
					esc_attr( $value )
				);
			elseif ( $type === 'seperator' ) :
				print( '<hr>' );
			endif;

			if ( !empty( $args['description'] ) ) {
				printf( '<p class="description">%1$s</p>', esc_html( $args['description'] ) );
			}

		}

		public function fonts_list() {

			$fm_fonts = new Nedwp_Feedback_Modal_Fonts();
			$fonts_list = $fm_fonts->get_list();

			if ( is_array( $fonts_list ) ) {
				$fonts_list['theme-inherit'] = __( '-Theme Inherit-', 'nedwp-feedback-modal' );
				return $fonts_list;
			}

			return array();
		}

		public function capability_levels() {

			global $wp_roles;
			$roles = ( !empty( $wp_roles ) ) ? $wp_roles->roles : array();
			$capabilities = array();

			if ( !empty( $roles ) ) {
				foreach ( $roles as $role ) {
					foreach ( array_keys( $role['capabilities'] ) as $cap ) {
						$capabilities[$cap] = $cap;
					}
				}
			}

			return $capabilities;
		}

		public function data_capability() {
	    	return ( !empty( nedwp_fm_opt( 'capability' ) ) ) ? nedwp_fm_opt( 'capability' ) : 'nedwp_feedback_access';
		}

		public function handle_submit() {

			$status = 403;

	        if ( check_ajax_referer( 'ajaxnonce', 'security' ) ) {

	        	$opinion = 0;

		        if ( !empty( $_POST['opinion'] ) ) {

		        	$opinion_input = absint( $_POST['opinion'] );

		        	if ( in_array( $opinion_input, array( 1, 2, 3, 4, 5 ) ) ) {
		        		$opinion = $opinion_input;
		        	}

		        }

		        $comment = ( !empty( $_POST['comment'] ) ) ? sanitize_text_field( $_POST['comment'] ) : '-';
		        $email = ( !empty( $_POST['email'] ) ) ? sanitize_email( $_POST['email'] ) : '-';
		        
		        if ( $this->insert_data( $opinion, $comment, $email ) ) {
		        	$this->admin_notification( $opinion, $comment, $email );
		        	$status = 200;
		        }

			}

	        die( $status );

		}

		public function insert_data( $opinion, $comment, $email ) {

			global $wpdb;
			$table_name = $wpdb->prefix . 'nedwp_fm';

			$data = apply_filters( 'nedwp_fm_insert_data', array(
			    'opinion' => esc_sql( $opinion ),
			    'comment' => esc_sql( $comment ),
			    'email' => esc_sql( $email )
			) );

			$data = is_array( $data ) ? $data : array();
			$data['date_in'] = esc_sql( current_time( 'Y-m-d H:i:s' ) );

			return $wpdb->insert( $table_name, $data );
		}

		public function current_time() {
	    	return current_time( sprintf( '%1$s %2$s', get_option( 'date_format' ), get_option( 'time_format' ) ) );
		}

		public function admin_notification( $opinion = '', $comment = '', $email = '' ) {

	    	if ( !nedwp_fm_opt( 'no_enable' ) || empty( nedwp_fm_opt( 'no_to' ) ) ) {
	    		return;
	    	}

	    	$headers = array( 'Content-Type: text/html; charset=UTF-8' );
			$link = admin_url( sprintf( 'admin.php?page=%1$s-list', NEDWP_FM_KEY ) );
			$anchor = sprintf( '<a href="%1$s" target="_blank">%1$s</a>', $link );

	    	$params = apply_filters( 'nedwp_fm_notification_params', array(
	    		'%link%' => $anchor,
	    		'%plugin%' => $this->plugin_name(),
	    		'%time%' => $this->current_time(),
	    		'%opinion%' => $opinion,
	    		'%comment%' => $comment,
	    		'%email%' => $email
	    	) );

	    	$body = nedwp_fm_opt( 'no_body' );

	    	foreach ( $params as $param => $value ) {
	    		$body = str_replace( $param, $value, $body );
	    	}

	    	wp_mail(
	    		nedwp_fm_opt( 'no_to' ),
	    		nedwp_fm_opt( 'no_subject' ),
	    		nl2br( $body, false ),
	    		$headers
	    	);

		}

		public function handle_export() {

			if ( !empty( $_GET['export_data'] ) ) {

				if ( !empty( $_GET['export_type'] ) ) {

					$export_file = new Nedwp_Feedback_Modal_Export();
					$export_type = sanitize_key( $_GET['export_type'] );

					if ( 'csv' === $export_type ) {
						$export_file->download( 'csv' );
					} else if ( 'json' === $export_type ) {
						$export_file->download( 'json' );
					}

				}

			}

		}

		public function add_notice_donation() {

			$install_date = date_create( get_option( 'nedwp_fm_install_date', '' ) );
			$date_now = date_create( date( 'Y-m-d H:i:s' ) );
			$date_diff = date_diff( $install_date, $date_now );

			if ( false === get_option( 'nedwp_fm_donation_notice' ) ) {
				add_option( 'nedwp_fm_donation_notice', 1 );
			}

			if ( nedwp_fm_opt( 'notices' ) && $date_diff->format( '%d' ) > 7 ) {

				if ( get_option( 'nedwp_fm_donation_notice', 1 ) ) :

				    echo '<div id="nedwp-fm-notice-donation" class="notice notice-info">';

					    echo '<p>';

						    printf( __( 'Thank you for choosing <b>%1$s</b>. Contribute some donation, to make plugin more stable. You can pay amount of your choice.', 'nedwp-feedback-modal' ),
						    	$this->plugin_name()
						    );

						    printf( '&nbsp;<a href="https://bit.ly/3iLfEN3" target="_blank">%1$s</a> | <a href="#" id="nedwp-fm-notice-donation-dismiss">%2$s</a>',
						    	esc_html__( 'Donate', 'nedwp-feedback-modal' ),
						    	esc_html__( 'Not now', 'nedwp-feedback-modal' )
							);

					   echo '</p>';

				    echo '</div>';

				endif;

			}

		}

		public function dismiss_notice_donation() {

			if ( check_ajax_referer( 'ajaxnonce', 'security' ) ) {
				update_option( 'nedwp_fm_donation_notice', 0 );
			}

			die();

		}

		public function usage_tracking() {
			if ( isset( $_GET['doing_wp_cron'] ) || ( defined( 'DOING_CRON' ) && DOING_CRON ) || ( defined( 'WP_CLI' ) && WP_CLI ) ) {
		        Nedwp_Feedback_Modal_Tracking::initiate();
		    }
		}

	}

}