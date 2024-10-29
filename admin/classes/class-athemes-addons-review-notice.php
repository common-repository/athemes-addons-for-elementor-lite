<?php
/**
 * aThemes Addons review notice
 *
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Class to display the plugin review notice after certain period.
 *
*/
class aThemes_Addons_Review_Notice {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'admin_init', array( $this, 'review_notice' ) );
		add_action( 'admin_notices', array( $this, 'review_notice_markup' ) );
		add_action( 'admin_init', array( $this, 'ignore_theme_review_notice' ) );
		add_action( 'admin_init', array( $this, 'ignore_theme_review_notice_partially' ) );
	}

	/**
	 * Set the required option value as needed for theme review notice.
	 */
	public function review_notice() {
		if ( !get_option( 'athemes_addons_installed_time' ) ) {
			update_option( 'athemes_addons_installed_time', time() );
		}
	}

	/**
	 * Show HTML markup if conditions meet.
	 */
	public function review_notice_markup() {
		$user_id                  = get_current_user_id();
		$current_user             = wp_get_current_user();
		$ignored_notice           = get_user_meta( $user_id, 'athemes_addons_disable_review_notice', true );
		$ignored_notice_partially = get_user_meta( $user_id, 'delay_athemes_addons_disable_review_notice_partially', true );

		if ( ( get_option( 'athemes_addons_installed_time' ) > strtotime( '-14 day' ) ) || ( $ignored_notice_partially > strtotime( '-14 day' ) ) || ( $ignored_notice ) ) {
			return;
		}
		?>
		<div class="notice notice-success" style="position:relative;">
			<p>
				<?php
				printf(
				    /* Translators: %1$s current user display name. */
					esc_html__(
						'Hey, %1$s! You\'ve been using aThemes Addons for more than two weeks now and we hope you\'re happy with it. If you have a few minutes, we would love to get a 5 star review from you.', 'athemes-addons-elementor'
					),
					'<strong>' . esc_html( $current_user->display_name ) . '</strong>'
				);
				?>
			</p>

			<p>
				<a href="https://wordpress.org/support/plugin/athemes-addons-for-elementor-lite/reviews/?filter=5#new-post" class="btn button-primary" target="_blank"><?php esc_html_e( 'Sure', 'athemes-addons-elementor' ); ?></a>
				
				<a href="?delay_athemes_addons_disable_review_notice_partially=0" class="btn button-secondary"><?php esc_html_e( 'Maybe later', 'athemes-addons-elementor' ); ?></a>

				<a href="?nag_athemes_addons_disable_review_notice=0" class="btn button-secondary"><?php esc_html_e( 'I already did', 'athemes-addons-elementor' ); ?></a>
			</p>

			<a class="notice-dismiss" href="?nag_athemes_addons_disable_review_notice=0" style="text-decoration:none;"></a>
		</div>
		<?php
	}

	/**
	 * Disable review notice permanently
	 */
	public function ignore_theme_review_notice() {
		if ( isset( $_GET['nag_athemes_addons_disable_review_notice'] ) && '0' === $_GET['nag_athemes_addons_disable_review_notice'] ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			add_user_meta( get_current_user_id(), 'athemes_addons_disable_review_notice', 'true', true );
		}
	}

	/**
	 * Delay review notice
	 */
	public function ignore_theme_review_notice_partially() {
		if ( isset( $_GET['delay_athemes_addons_disable_review_notice_partially'] ) && '0' === $_GET['delay_athemes_addons_disable_review_notice_partially'] ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			update_user_meta( get_current_user_id(), 'delay_athemes_addons_disable_review_notice_partially', time() );
		}
	}

	/**
	 * Delete data if plugin is removed
	 */
	public function review_notice_data_remove() {
		$get_all_users        = get_users();
		$theme_installed_time = get_option( 'athemes_addons_installed_time' );

		// Delete options data.
		if ( $theme_installed_time ) {
			delete_option( 'athemes_addons_installed_time' );
		}

		foreach ( $get_all_users as $user ) {
			$ignored_notice           = get_user_meta( $user->ID, 'athemes_addons_disable_review_notice', true );
			$ignored_notice_partially = get_user_meta( $user->ID, 'delay_athemes_addons_disable_review_notice_partially', true );

			if ( $ignored_notice ) {
				delete_user_meta( $user->ID, 'athemes_addons_disable_review_notice' );
			}

			if ( $ignored_notice_partially ) {
				delete_user_meta( $user->ID, 'delay_athemes_addons_disable_review_notice_partially' );
			}
		}
	}
}

new aThemes_Addons_Review_Notice();