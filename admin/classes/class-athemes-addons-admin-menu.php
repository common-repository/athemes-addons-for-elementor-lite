<?php
/**
 * Admin_Menu Class.
 */
namespace aThemesAddons;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Admin_Menu' ) ) {

	class Admin_Menu {

		/**
		 * Page title.
		 */
		public $page_title = 'aThemes Addons';

		/**
		 * Plugin slug.
		 */
		public $plugin_slug = 'athemes-addons';

		/**
		 * Plugin capability.
		 */
		public $capability = 'manage_options';

		/**
		 * Plugin priority.
		 */
		public $priority = 58;

		/**
		 * Plugin notifications.
		 */
		public $notifications = array();

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
			add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
			add_action( 'wp_ajax_athemes_addons_notifications_read', array( $this, 'ajax_notifications_read' ) );
		}

		/**
		 * Include required classes.
		 */
		public function add_admin_menu() {

			global $submenu;

			// Dashboard
			add_menu_page(
				$this->page_title,
				$this->page_title,
				$this->capability,
				$this->plugin_slug,
				array( $this, 'page_dashboard' ),
				ATHEMES_AFE_URI . 'assets/images/athemes-addons-logo.svg',
				$this->priority
			);
		}

		/**
		 * Get Notifications
		 */
		public function get_notifications() {
			$notifications = get_transient( 'athemes_addons_notifications' );

			if ( ! empty( $notifications ) ) {
				$this->notifications = $notifications;
			} else {

				/**
				 * Hook: athemes_addons_changelog_api_url
				 * 
				 * @since 1.0
				 */

				$response = wp_remote_get( apply_filters( 'athemes_addons_changelog_api_url', 'https://athemes.com/wp-json/wp/v2/notifications?theme=7105&per_page=3' ) );

				if ( ! is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) === 200 ) {
					$this->notifications = json_decode( wp_remote_retrieve_body( $response ) );
					set_transient( 'athemes_addons_notifications', $this->notifications, 24 * HOUR_IN_SECONDS );
				}
			}

			return $this->notifications;
		}

		/**
		 * Check if the latest notification is read
		 */
		public function is_latest_notification_read() {

			if ( ! isset( $this->notifications ) || empty( $this->notifications ) ) {
				return false;
			}
			
			$user_id        = get_current_user_id();
			$user_read_meta = get_user_meta( $user_id, 'athemes_addons_dashboard_notifications_latest_read', true );

			$last_notification_date      = strtotime( is_string( $this->notifications[0]->post_date ) ? $this->notifications[0]->post_date : '' );
			$last_notification_date_ondb = $user_read_meta ? strtotime( $user_read_meta ) : false;

			if ( ! $last_notification_date_ondb ) {
				return false;
			}

			if ( $last_notification_date > $last_notification_date_ondb ) {
				return false;
			}

			return true;
		}

		/**
		 * Ajax notifications.
		 */
		public function ajax_notifications_read() {
			check_ajax_referer( 'athemes-addons-elementor', 'nonce' );

			// Check current user capabilities
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( 'You are not allowed to do this.' );
			}

			$latest_notification_date = ( isset( $_POST[ 'latest_notification_date' ] ) ) ? sanitize_text_field( wp_unslash( $_POST[ 'latest_notification_date' ] ) ) : false;

			update_user_meta( get_current_user_id(), 'athemes_addons_dashboard_notifications_latest_read', $latest_notification_date );

			wp_send_json_success();
		}

		public function page_dashboard() {
			require_once ATHEMES_AFE_DIR . 'admin/pages/page-dashboard.php';
		}

		/**
		 * Dashboard tabs
		 */
		public function dashboard_tabs() {
			$tabs = array(
				'widgets' => array(
					'title' => __( 'Widgets', 'athemes-addons-elementor' ),
					'link'  => '#',
				),
				/*
				'theme-builder' => array(
					'title' => __( 'Theme Builder', 'athemes-addons-elementor' ),
					'link'  => '#',
				),
				*/
				'extensions' => array(
					'title' => __( 'Extensions', 'athemes-addons-elementor' ),
					'link'  => '#',
				),
				
				'settings' => array(
					'title' => __( 'Settings', 'athemes-addons-elementor' ),
					'link'  => '#',
				),
				
			);
			/*
			if ( ! defined( 'ATHEMES_AFE_PRO_VERSION' ) ) {
				$tabs['upgrade'] = array(
					'title' => __( 'Free vs Pro', 'athemes-addons-elementor' ),
					'link'  => '#'
				);
			}
			*/

			/**
			 * Hook: athemes_addons_dashboard_tabs
			 * 
			 * @since 1.0
			 */
			return apply_filters( 'athemes_addons_dashboard_tabs', $tabs );
		}
	}

	Admin_Menu::instance();

}
