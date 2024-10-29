<?php
/**
 * Plugin Name: aThemes Addons for Elementor Lite
 * Plugin URI:  https://athemes.com/addons-for-elementor
 * Description: Widgets and extensions for the Elementor page builder
 * Version:     1.0.8
 * Author:      aThemes
 * Author URI:  https://athemes.com
 * License:     GPLv3 or later License
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * Elementor tested up to: 3.23.4
 * Elementor Pro tested up to: 3.23.3
 * Text Domain: athemes-addons-elementor
 * Domain Path: /languages
 *
 * @package aThemes Addons for Elementor
 * @since 1.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// AAFE constants.
define( 'ATHEMES_AFE_VERSION', '1.0.8' );
define( 'ATHEMES_AFE_FILE', __FILE__ );
define( 'ATHEMES_AFE_BASE', trailingslashit( plugin_basename( ATHEMES_AFE_FILE ) ) );
define( 'ATHEMES_AFE_DIR', trailingslashit( plugin_dir_path( ATHEMES_AFE_FILE ) ) );
define( 'ATHEMES_AFE_URI', trailingslashit( plugins_url( '/', ATHEMES_AFE_FILE ) ) );

/**
 * AAFE class.
 *
 */
class aThemes_Addons_Elementor {

	/**
	 * The single class instance.
	 *
	 */
	private static $instance = null;

	/**
	 * Instance.
	 *
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 *
	 */
	public function __construct() {
		// Load the plugin functionality.
		$this->includes();
	}

	/**
	 * Includes.
	 *
	 */
	public function includes() {
		if ( is_admin() ) {
			require_once ATHEMES_AFE_DIR . 'admin/class-athemes-addons-admin-loader.php';
		}
		require_once ATHEMES_AFE_DIR . 'inc/class-athemes-addons-loader.php';
	}

	/**
	 * Plugin activation.
	 */
	public function activation() {
		$all_widgets = athemes_addons_get_widgets();

		$modules = get_option( aThemes_Addons_Modules::$option, array() );

		//return if modules are already activated
		if ( !empty( $modules ) ) {
			return;
		}

		//activate default widgets
		if ( !empty( $all_widgets ) ) {
			foreach ( $all_widgets as $widget_id => $widget ) {
				if ( isset( $widget['default'] ) && true === $widget['default'] ) {

					$modules[ $widget_id ] = true;

					update_option( aThemes_Addons_Modules::$option, $modules );
				} else {
					$modules[ $widget_id ] = false;

					update_option( aThemes_Addons_Modules::$option, $modules );
				}
			}
		}
	}
}

/**
 * Function works with the aThemes_Addons_Elementor class instance
 *
 */
function athemes_addons_elementor() {
	return aThemes_Addons_Elementor::instance();
}
if ( class_exists( 'Elementor\Plugin' ) ) {
	athemes_addons_elementor();
}

/**
 * Register activation hook.
 */
register_activation_hook( __FILE__, array( athemes_addons_elementor(), 'activation' ) );