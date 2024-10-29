<?php
/**
 * Admin Loader.
 */
namespace aThemesAddons;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Admin_Loader' ) ) {

	class Admin_Loader {

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

			add_action( 'init', array( $this, 'includes' ) );

			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles_scripts' ) );
			add_action( 'plugin_action_links_' . ATHEMES_AFE_BASE, array( $this, 'action_links' ) );
			add_filter( 'admin_footer_text', array( $this, 'add_admin_footer_text' ), 999 );
			add_filter( 'admin_body_class', array( $this, 'add_admin_body_class' ), 999 );
		}

		/**
		 * Include admin classes.
		 */
		public function includes() {

			require_once ATHEMES_AFE_DIR . 'admin/classes/class-athemes-addons-admin-menu.php';
			require_once ATHEMES_AFE_DIR . 'admin/classes/class-athemes-addons-admin-settings.php';
			require_once ATHEMES_AFE_DIR . 'admin/classes/class-athemes-addons-admin-notices.php';
			require_once ATHEMES_AFE_DIR . 'admin/classes/class-athemes-addons-review-notice.php';
			require_once ATHEMES_AFE_DIR . 'admin/helpers.php';

			// Theme Builder.
			require_once ATHEMES_AFE_DIR . 'inc/theme-builder/class-athemes-addons-theme-builder-admin.php';
			$themeBuilderAdmin = Theme_Builder_Admin::instance();
			$themeBuilderAdmin->includes();
		}

		/**
		 * Enqueue admin styles and scripts.
		 */
		public function enqueue_styles_scripts() {
			
			$page = ( ! empty( $_GET['page'] ) ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

			if ( ! empty( $page ) && false !== strpos( $page, 'athemes-addons' ) ) {


				wp_enqueue_style( 'athemes-addons-admin', ATHEMES_AFE_URI . 'assets/css/admin/admin.min.css', array(), ATHEMES_AFE_VERSION );

				wp_enqueue_script( 'athemes-addons-admin', ATHEMES_AFE_URI . 'assets/js/admin/admin.min.js', array( 'jquery', 'jquery-ui-sortable', 'jquery-ui-core', 'wp-util' ), ATHEMES_AFE_VERSION, true );

				wp_localize_script( 'athemes-addons-admin', 'athemes_addons_elementor', array(
					'nonce'    => wp_create_nonce( 'athemes-addons-elementor' ),
					'ajax_url' => admin_url( 'admin-ajax.php' ),
					'admin_url'=> admin_url(),
					'save'     => esc_html__( 'Save', 'athemes-addons-elementor' ),
					'saving'   => esc_html__( 'Saving...', 'athemes-addons-elementor' ),
					'saved'    => esc_html__( 'Saved!', 'athemes-addons-elementor' ),
				) );

				wp_enqueue_script( 'athemes-addons-admin-select2', ATHEMES_AFE_URI . 'assets/js/vendor/select2.min.js', array( 'jquery' ), ATHEMES_AFE_VERSION, true );

				wp_enqueue_style( 'athemes-addons-admin-select2', ATHEMES_AFE_URI . 'assets/css/admin/select2.min.css', array(), ATHEMES_AFE_VERSION );
			}
		}

		/**
		 * Add plugin settings link on the plugin page.
		 */
		public function action_links( $links ) {

			$page_url = add_query_arg( array( 'page' => 'athemes-addons-elementor' ), admin_url( 'themes.php' ) );

			$action_links = array(
				'settings' => '<a href="' . esc_url( $page_url ) . '">' . esc_html__( 'Settings', 'athemes-addons-elementor' ) . '</a>',
			);

			return array_merge( $action_links, $links );
		}

		/**
		 * Add plugin settings link on the plugin page.
		 */
		public function add_admin_footer_text( $text ) {
			$page   = ( ! empty( $_GET['page'] ) ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$module = ( ! empty( $_GET['module'] ) ) ? sanitize_text_field( wp_unslash( $_GET['module'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			
			if ( ! empty( $page ) && false !== strpos( $page, 'athemes-addons-elementor' ) ) {

				$text = '';
				
				if ( empty( $module ) ) {
					
					$text .= sprintf( '<a href="https://www.facebook.com/groups/245922400035997" target="_blank" class="athemes-addons-admin-footer-text-link">%s</a>', esc_html__( 'Join our community', 'athemes-addons-elementor' ) );
					$text .= esc_html__( 'to discuss about the product and ask for support or help the community.', 'athemes-addons-elementor' );
					
				}
				
			}
			
			return $text;
		}
		
		/**
		 * Add admin body class.
		 */
		public function add_admin_body_class( $classes ) {          
			$page   = ( ! empty( $_GET['page'] ) ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$module = ( ! empty( $_GET['module'] ) ) ? sanitize_text_field( wp_unslash( $_GET['module'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

			if ( ! empty( $page ) && false !== strpos( $page, 'athemes-addons-elementor' ) ) {

				if ( ! empty( $module ) ) {

					$classes .= ' athemes-addons-admin-page-module';

				} else {

					$classes .= ' athemes-addons-admin-page';

				}
			}

			return $classes;
		}
	}

	Admin_Loader::instance();

}
