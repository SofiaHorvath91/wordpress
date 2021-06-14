<?php
/**
 * Plugin Name: Feedback Modal for Website
 * Description: Allow your visitor/customer submit instant visual feedback on your website.
 * Plugin URI: https://nedwp.com/fm
 * Author: Nedwp
 * Author URI: https://nedwp.com
 * Version: 1.0.1
 * License: GNU General Public License version 3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: nedwp-feedback-modal
 * Domain Path: /languages
 */

/*
    Copyright (C) 2020, Nedwp, info@nedwp.com

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// Define constantes
defined( 'ABSPATH' ) || die();
defined( 'NEDWP_FM_VER' ) or define( 'NEDWP_FM_VER', '1.0.1' );
defined( 'NEDWP_FM_FIL' ) or define( 'NEDWP_FM_FIL', __FILE__ );
defined( 'NEDWP_FM_URL' ) or define( 'NEDWP_FM_URL', plugin_dir_url( NEDWP_FM_FIL ) );
defined( 'NEDWP_FM_DIR' ) or define( 'NEDWP_FM_DIR', plugin_dir_path( NEDWP_FM_FIL ) );
defined( 'NEDWP_FM_CSS' ) or define( 'NEDWP_FM_CSS', NEDWP_FM_URL . 'assets/css' );
defined( 'NEDWP_FM_JS' )  or define( 'NEDWP_FM_JS',  NEDWP_FM_URL . 'assets/js' );
defined( 'NEDWP_FM_IMG' ) or define( 'NEDWP_FM_IMG', NEDWP_FM_URL . 'assets/imgs' );
defined( 'NEDWP_FM_ICO' ) or define( 'NEDWP_FM_ICO', NEDWP_FM_URL . 'assets/icons' );
defined( 'NEDWP_FM_KEY' ) or define( 'NEDWP_FM_KEY', 'nedwp-feedback-modal' );

// Load plugin
require_once( NEDWP_FM_DIR . 'inc/functions.php' );

if ( is_admin() ) {
	require_once( NEDWP_FM_DIR . 'inc/admin/main.php' );
	Nedwp_Feedback_Modal_Admin::initiate();
} else {
	require_once( NEDWP_FM_DIR . 'inc/public/main.php' );
	Nedwp_Feedback_Modal_Public::initiate();
}

// Translate plugin
load_plugin_textdomain( 'nedwp-feedback-modal', false, dirname( plugin_basename( NEDWP_FM_FIL ) ) . '/languages/' );