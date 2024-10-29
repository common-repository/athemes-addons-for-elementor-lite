<?php
/**
 * Notices Class.
 */
namespace aThemesAddons;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Admin_Notices' ) ) {
	class Admin_Notices {

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
			add_action( 'admin_notices', array( $this, 'install_elementor' ) );
		}

		/**
		 * Install Elementor.
		 */
		public function install_elementor() {
			if ( ! current_user_can( 'install_plugins' ) ) {
				return;
			}

			if ( !file_exists( WP_PLUGIN_DIR . '/elementor/elementor.php' ) ) { // Check if Elementor is installed.
				$elementor_install_url = wp_nonce_url(
					add_query_arg(
						array(
							'action' => 'install-plugin',
							'plugin' => 'elementor',
						),
						admin_url( 'update.php' )
					),
					'install-plugin_elementor'
				);

				$message = sprintf(
					/* translators: 1: aThemes Addons, 2: Elementor */
					esc_html__( 'To use %1$s, please install %2$s.', 'athemes-addons-elementor' ),
					'<strong>' . esc_html__( 'aThemes Addons', 'athemes-addons-elementor' ) . '</strong>',
					'<strong>' . esc_html__( 'Elementor', 'athemes-addons-elementor' ) . '</strong>'
				);

				$button_text = esc_html__( 'Install Elementor', 'athemes-addons-elementor' );

				$this->notice( $message, $button_text, $elementor_install_url );
			} elseif ( !is_plugin_active( 'elementor/elementor.php' ) ) { // Check if Elementor is activated.
				$elementor_activate_url = wp_nonce_url(
					add_query_arg(
						array(
							'action' => 'activate',
							'plugin' => 'elementor/elementor.php',
						),
						admin_url( 'plugins.php' )
					),
					'activate-plugin_elementor/elementor.php'
				);

				$message = sprintf(
					/* translators: 1: aThemes Addons, 2: Elementor */
					esc_html__( 'To use %1$s, please activate %2$s.', 'athemes-addons-elementor' ),
					'<strong>' . esc_html__( 'aThemes Addons', 'athemes-addons-elementor' ) . '</strong>',
					'<strong>' . esc_html__( 'Elementor', 'athemes-addons-elementor' ) . '</strong>'
				);

				$button_text = esc_html__( 'Activate Elementor', 'athemes-addons-elementor' );

				$this->notice( $message, $button_text, $elementor_activate_url );
			}
		}

		/**
		 * Display a notice.
		 */
		public function notice( $message, $button_text, $button_url ) {
			?>
			<div class="notice notice-error is-dismissible">
				<p><?php echo wp_kses_post( $message ); ?></p>
				<p>
					<a href="<?php echo esc_url( $button_url ); ?>" class="button button-primary"><?php echo esc_html( $button_text ); ?></a>
				</p>
			</div>
			<?php
		}
	}

	Admin_Notices::instance();
}