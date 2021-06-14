<?php

if ( !class_exists( 'Nedwp_Feedback_Modal_Tracking' ) ) {

	class Nedwp_Feedback_Modal_Tracking {

		protected static $instance;

		public static function initiate() {

			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

	    public function __construct() {

			if ( nedwp_fm_opt( 'tracking' ) ) {
				$this->enable_schedule();
			} else {
				$this->disable_schedule();
			}

			add_action( 'nedwp_fm_usage_tracking', array( $this, 'usage_send' ) );
			add_filter( 'cron_schedules', array( $this, 'cron_schedules' ) );

	    }

	    public function enable_schedule() {

			if ( !wp_next_scheduled( 'nedwp_fm_usage_tracking' ) ) {
				return wp_schedule_event( time(), 'monthly', 'nedwp_fm_usage_tracking' );
			}

			return true;
	    }

	    public function disable_schedule() {
	    	return wp_clear_scheduled_hook( 'nedwp_fm_usage_tracking' );
	    }

	    public function cron_schedules( $schedules ) {

			$schedules['monthly'] = array(
				'interval' => 30 * DAY_IN_SECONDS,
				'display' => __( 'Once a month', 'nedwp-feedback-modal' )
			);

			return $schedules;
	    }

	    public function usage_send() {

	    	wp_remote_post( $this->tracking_uri(), array(
				'body' => $this->tracking_data(),
				'blocking' => false
			) );

			if ( false !== get_option( 'nedwp_fm_usage_tracking_last' ) ) {
				update_option( 'nedwp_fm_usage_tracking_last', time() );
			} else {
				add_option( 'nedwp_fm_usage_tracking_last', time() );
			}

			return true;
	    }

	    public function tracking_uri() {
	    	return 'http://api.nedwp.com/wp-plugins/fm/usage-tracking';
	    }

	    public function tracking_data() {

	    	// Current theme info
	    	$theme_data = wp_get_theme();

	    	// Non sensitive opts
			$fm_default = (array) nedwp_fm_opt_default();
			$fm_general = (array) get_option( 'nedwp_fm_general', $fm_default['nedwp_fm_general'] );
			$fm_modal = (array) get_option( 'nedwp_fm_modal', $fm_default['nedwp_fm_modal'] );
			$fm_options = array_merge( $fm_general, $fm_modal );

			// Submissions count
	    	global $wpdb;
	        $table_name = $wpdb->prefix . 'nedwp_fm';
	        $sub_count = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name" );

			$data = array(
				'fm_version' => NEDWP_FM_VER,
				'fm_in_date' => get_option( 'nedwp_fm_install_date', '' ),
				'fm_options' => $fm_options,
				'fm_s_count' => ( !empty( $sub_count ) ) ? $sub_count : 0,
				'wp_version' => get_bloginfo( 'version' ),
				'wp_language' => get_locale(),
				'wp_multisite' => ( function_exists( 'is_multisite' ) && is_multisite() ) ? 1 : 0,
				'theme_name' => $theme_data->get( 'Name' ),
				'theme_uri' => $theme_data->get( 'ThemeURI' ),
				'theme_ver' => $theme_data->get( 'Version' ),
				'plugins' => (array) get_option( 'active_plugins', array() ),
				'server' => ( !empty( $_SERNEDWP_FM_VER['SERNEDWP_FM_VER_SOFTWARE'] ) ) ? $_SERNEDWP_FM_VER['SERNEDWP_FM_VER_SOFTWARE'] : '',
				'php_version' => phpversion()
			);

			return (array) apply_filters( 'nedwp_fm_tracking_data', $data );
	    }

	}

}