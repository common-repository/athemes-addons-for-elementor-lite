<?php
/**
 * aThemes_Addons_Modules Class.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'aThemes_Addons_Modules' ) ) {

	class aThemes_Addons_Modules {

		/**
		 * The modules container.
		 */
		private $container = array();

		/**
		 * Option name
		 */
		public static $option = 'athemes-addons-modules';

		/**
		 * The single class instance.
		 */
		private static $instance = null;

		/**
		 * Instance.
		 */
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Constructor.
		 */
		public function __construct() {
			add_action( 'wp_ajax_athemes_addons_module_activate', array( $this, 'activate_module' ) );
			add_action( 'wp_ajax_athemes_addons_module_deactivate', array( $this, 'deactivate_module' ) );
			add_action( 'wp_ajax_athemes_addons_module_feedback', array( $this, 'feedback_module' ) );
		}

		/**
		 * Activate module with Ajax.
		 */
		public function activate_module() {
			$nonce  = ( isset( $_POST['nonce'] ) ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
			$module = ( isset( $_POST['module'] ) ) ? sanitize_text_field( wp_unslash( $_POST['module'] ) ) : '';

			// Check current user capabilities
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( 'You are not allowed to do this.' );
			}

			if ( wp_verify_nonce( $nonce, 'athemes-addons-elementor' ) && ! empty( $module ) ) {

				$modules = get_option( self::$option, array() );

				$modules[ $module ] = true;

				update_option( self::$option, $modules );

				wp_send_json_success();

			}
			
			wp_send_json_error();
		}

		/**
		 * Deactivate module with Ajax.
		 */
		public function deactivate_module() {
			$nonce  = ( isset( $_POST['nonce'] ) ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
			$module = ( isset( $_POST['module'] ) ) ? sanitize_text_field( wp_unslash( $_POST['module'] ) ) : '';

			// Check current user capabilities
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( 'You are not allowed to do this.' );
			}

			if ( wp_verify_nonce( $nonce, 'athemes-addons-elementor' ) && ! empty( $module ) ) {

				$modules = get_option( self::$option, array() );

				$modules[ $module ] = false;

				update_option( self::$option, $modules );

				wp_send_json_success();

			}
			
			wp_send_json_error();
		}

		/**
		 * Feedback module with Ajax.
		 */
		public function feedback_module() {
			$nonce   = ( isset( $_POST['nonce'] ) ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
			$subject = ( isset( $_POST['subject'] ) ) ? sanitize_text_field( wp_unslash( $_POST['subject'] ) ) : '';
			$message = ( isset( $_POST['message'] ) ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';
			$module  = ( isset( $_POST['module'] ) ) ? sanitize_text_field( wp_unslash( $_POST['module'] ) ) : '';
			$from    = get_bloginfo( 'admin_email' );

			// Check current user capabilities
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( 'You are not allowed to do this.' );
			}

			if ( wp_verify_nonce( $nonce, 'athemes-addons-elementor' ) ) {
				$response = wp_remote_post( 'https://athemes.com/athemes-addons-elementor/', array(
					'body' => array(
						'mailsender' => true,
						'from'    => $from,
						'subject' => $subject,
						'message' => $message,
						'module'  => $module,
					),
				) );

				if ( is_wp_error( $response ) ) {
					wp_send_json_error();
				}

				wp_send_json_success();
			}
			
			wp_send_json_error();
		}

		/**
		 * Creates and adds the module instance to the container.
		 *
		 * @param aThemes_Addons_Add_Module $module The module instance.
		 *
		 * @return void
		 */
		public static function create_module( aThemes_Addons_Add_Module $module ) {
			static::instance()->container[ $module->module_id ] = $module;
		}

		/**
		 * Get the module instance.
		 *
		 * @param string $module_id The module ID.
		 *
		 * @return aThemes_Addons_Add_Module|mixed The module instance.
		 */
		public static function get_module( $module_id ) {
			return static::instance()->container[ $module_id ];
		}

		/**
		 * Determines if a module has already been added to the container.
		 *
		 * @param string $module_id The module ID.
		 *
		 * @return bool
		 */
		public static function is_module_created( $module_id ) {
			return in_array( $module_id, static::instance()->container, true );
		}

		/**
		 * Check if a specific module is activated
		 */
		public static function is_module_active( $module ) {
			/**
			 * Hook 'athemes_addons_module_{$module}_deactivate'
			 * 
			 * @since 1.0
			 */
			if ( apply_filters( "athemes_addons_module_{$module}_deactivate", false ) ) {
				add_filter( "athemes_addons_admin_module_{$module}_list_item_class", function( $class ) { // phpcs:ignore
					return $class . ' athemes-addons-module-deactivated-by-bp';
				} );

				return false;
			}

			$modules = get_option( self::$option, array() );

			if ( is_array( $modules ) && array_key_exists( $module, $modules ) && true === $modules[ $module ] ) {
				return true;
			}

			return false;
		}
	}

	aThemes_Addons_Modules::instance();

}