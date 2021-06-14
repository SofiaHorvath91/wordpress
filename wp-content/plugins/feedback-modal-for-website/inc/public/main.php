<?php

if ( !class_exists( 'Nedwp_Feedback_Modal_Public' ) ) {

	class Nedwp_Feedback_Modal_Public {

		protected static $instance;
		private $options;

		public static function initiate() {

			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

	    public function __construct() {
			add_action( 'init', array( $this, 'load' ) );
	    }

	    public function load() {

	    	// Status
	    	if ( !$this->is_enabled() || !$this->visibility() ) {
	    		return;
	    	}

	    	// Scripts
	    	add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

	    	// Render
	        add_action( 'wp_footer', array( $this, 'render_content' ) );

	    }

	    public function is_enabled() {

	    	$is_enabled = (
	    		( nedwp_fm_opt( 'desktop_display' ) || nedwp_fm_opt( 'mobile_display' ) ) &&
	    		( nedwp_fm_opt( 'show_opinion' ) || nedwp_fm_opt( 'show_comment' ) )
	    	);

	    	return apply_filters( 'nedwp_fm_is_enabled', $is_enabled );
	    }

	    public function visibility() {

			if ( 'front' == nedwp_fm_opt( 'visibility' ) && !is_front_page() && !is_home() ) {
				$visibility = false;
			} else {
				$visibility = true;
			}

			return apply_filters( 'nedwp_fm_visibility', $visibility );
	    }

	    public function enqueue_scripts() {

			// CSS
			$this->link_fonts();
			if ( nedwp_fm_opt( 'enable_icons' ) ) :
				wp_enqueue_style( 'line-awesome', NEDWP_FM_ICO . '/line-awesome/css/la.min.css', array(), '1.3.0' );
			endif;
			wp_enqueue_style( 'nedwp-fm-public', NEDWP_FM_CSS . '/fm-public.css', array(), NEDWP_FM_VER );
			$this->custom_styles();

    		// JS
    		wp_enqueue_script( 'nedwp-fm-public', NEDWP_FM_JS . '/fm-public.js', array( 'jquery' ), NEDWP_FM_VER, true );
			wp_localize_script( 'nedwp-fm-public', 'nedwp_fm_public_var', array(
			    'ajax_url' => admin_url( 'admin-ajax.php' ),
			    'ajax_nonce' => wp_create_nonce( 'ajaxnonce' ),
			    'fm_i18n' => $this->input_i18n()
			));

			do_action( 'nedwp_fm_public_enqueue_scripts' );

	    }

	    public function link_fonts() {

	        // Selected fonts
	    	$content_font_f = nedwp_fm_opt( 'content_font' );
	    	$content_font_w = array( '300', '300i', '400', '400i', '700', '700i' );
	    	$content_font_w = (array) apply_filters( 'nedwp_fm_content_font_weight', $content_font_w );

	    	if ( 'theme-inherit' !== $content_font_f ) {

		    	$font_family_weight = array( $content_font_f => $content_font_w );

		        // Format fonts
		        $formated_fonts = array();
		        
		        if ( $font_family_weight ) {

		            foreach( $font_family_weight as $font_name => $font_weight ) {

		                $font_name = str_replace( ' ', '+', $font_name );

		                if ( !empty( $font_weight ) ) {
		                    $font_weight = implode( ',', $font_weight );
		                    $formated_fonts[] = trim( $font_name . ':' . urlencode( trim( $font_weight ) ) );
		                } else {
		                    $formated_fonts[] = trim( $font_name );
		                }
		            }

		        }

		        // Fonts args
		        $subsets = (array) apply_filters( 'nedwp_fm_content_font_subset', array( 'latin', 'latin-ext' ) );
		        $fonts_args = array();
		        
		        if ( !empty( $formated_fonts ) ) {

		            $fonts_args['family'] = implode( '|', $formated_fonts );

		            if ( !empty( $subsets ) ) {
		                $subsets = implode( ',', $subsets );
		                $fonts_args['subset'] = urlencode( trim( $subsets ) );
		            }

		        }

		        // Enqueue fonts
		        $protocol = ( is_ssl() ) ? 'https' : 'http';
		        $font_link = add_query_arg( $fonts_args, $protocol . '://fonts.googleapis.com/css' );
		        wp_enqueue_style( 'nedwp-fm-fonts', esc_url_raw( $font_link ), array(), NEDWP_FM_VER );

	    	}

	    }

	    public function input_i18n() {
	    	return array(
	    		'required' => esc_html( nedwp_fm_opt( 'no_required' ) ),
	    		'invalid' => esc_html( nedwp_fm_opt( 'no_invalid' ) )
		    );
	    }

	    public function custom_styles() {

	    	$content_font = nedwp_fm_opt( 'content_font' );
			$content_font = ( 'theme-inherit' !== $content_font ) ? $content_font : 'inherit';
			$input_border_color = $this->hex_to_rgba( nedwp_fm_opt( 'inputs_color' ), 0.6 );

			// Load
			include( NEDWP_FM_DIR . 'inc/public/styles.php' );
			$custom_styles = preg_replace( '/^\h*\v+/m', '', $custom_styles );

			// Add
			if ( !empty( $custom_styles ) ) {
				wp_add_inline_style( 'nedwp-fm-public', $custom_styles );
			}

	    }

	    public function hex_to_rgba( $color, $opacity = false ) {
     
	        if ( $color[0] == '#' ) {
	            $color = substr( $color, 1 );
	        }

	        if ( strlen( $color ) == 6 ) {
	            $hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
	        } else if ( strlen( $color ) == 3 ) {
	            $hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
	        }

	        $rgb = array_map( 'hexdec', $hex );

	        if ( $opacity ) {
	            $opacity = ( abs( $opacity ) > 1 ) ? 1.0 : $opacity;
	            return sprintf( 'rgba(%1$s,%2$s)', implode( ',', $rgb ), $opacity );
	        }

	        return sprintf( 'rgba(%1$s)', implode( ',', $rgb ) );
	    }

	    public function render_content() {
	    	include( NEDWP_FM_DIR . 'inc/public/views/main-view.php' );
		}

	}

}