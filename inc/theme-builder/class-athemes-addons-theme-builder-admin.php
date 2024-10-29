<?php
/**
 * Admin_Options Class.
 */
namespace aThemesAddons;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Theme_Builder_Admin' ) ) {
	class Theme_Builder_Admin {

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
			// Load display conditions script   
			add_action( 'admin_footer', 'athemes_addons_templates_display_conditions_script_template' );
			
			//Flush rewrite rules
			add_action( 'init', array( $this, 'flush_rules' ), 999 );

			// Ajax actions.
			add_action( 'wp_ajax_athemes_addons_update_template_conditions', array( $this, 'update_template_conditions' ) );
			add_action( 'wp_ajax_athemes_addons_delete_template', array( $this, 'delete_template' ) );
			add_action( 'wp_ajax_athemes_addons_header_type', array( $this, 'update_header_type' ) );
			add_action( 'wp_ajax_athemes_addons_create_template', array( $this, 'create_template' ) );
			add_action( 'wp_ajax_athemes_addons_get_templates', array( $this, 'get_templates' ) );
		}

		/**
		 * Include admin classes.
		 */
		public function includes() {
			require_once ATHEMES_AFE_DIR . 'inc/theme-builder/class-athemes-addons-theme-builder-metabox.php';
			require_once ATHEMES_AFE_DIR . 'inc/theme-builder/display-conditions/ajax-callback.php';
			require_once ATHEMES_AFE_DIR . 'inc/theme-builder/display-conditions/display-conditions-script-template.php';
		}

		/**
		 * Update template display conditions.
		 */
		public function update_template_conditions() {
			check_ajax_referer( 'athemes-addons-elementor', 'nonce' );

			if( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error();
			}

			$template_id = isset( $_POST['template_id'] ) ? absint( $_POST['template_id'] ) : 0;
			$conditions  = isset( $_POST['conditions'] ) ? stripslashes_deep( $_POST['conditions'] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

			if ( $template_id ) {
				update_post_meta( $template_id, '_ahf_template_conditions', $conditions );
			}

			wp_send_json_success();
		}

		/**
		 * Delete template.
		 */
		public function delete_template() {
			check_ajax_referer( 'athemes-addons-elementor', 'nonce' );

			if( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error();
			}

			$template_id = isset( $_POST['template_id'] ) ? absint( $_POST['template_id'] ) : 0;

			if ( $template_id ) {
				wp_delete_post( $template_id, true );
			}

			wp_send_json_success();
		}

		/**
		 * Update header type.
		 */
		public function update_header_type() {
			check_ajax_referer( 'athemes-addons-elementor', 'nonce' );

			if( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error();
			}

			$template_id    = isset( $_POST['template_id'] ) ? absint( $_POST['template_id'] ) : 0;
			$header_type    = isset( $_POST['header_type'] ) ? sanitize_text_field( $_POST['header_type'] ) : '';

			if ( $template_id ) {
				update_post_meta( $template_id, '_ahf_header_type', $header_type );
			}

			wp_send_json_success();
		}

		/**
		 * Create template.
		 */
		public function create_template() {
			check_ajax_referer( 'athemes-addons-elementor', 'nonce' );

			if( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error();
			}

			$template_type  = isset( $_POST['template_type'] ) ? sanitize_text_field( $_POST['template_type'] ) : '';
			$template_label = isset( $_POST['template_label'] ) ? sanitize_text_field( $_POST['template_label'] ) : '';

			if ( empty( $template_type ) ) {
				wp_send_json_error();
			}

			// translators: %1$s represents the template label, %2$s represents the current date and time.
			$template_title = sprintf( esc_html__( '%1$s Template - %2$s', 'athemes-addons-elementor' ), $template_label, gmdate( 'Y-m-d H:i:s' ) );

			$template_id = wp_insert_post( array(
				'post_title'    => $template_title,
				'post_type'     => 'aafe_templates',
				'post_status'   => 'publish',
			) );

			if ( $template_id ) {
				update_post_meta( $template_id, '_ahf_template_type', $template_type );
			}

			wp_send_json_success( array( 'template_id' => $template_id ) );
		}

		/**
		 * Get templates.
		 */
		public function get_templates() {
			check_ajax_referer('athemes-addons-elementor', 'nonce');

			if (!current_user_can('manage_options')) {
				wp_send_json_error();
			}

			$tb_elements = athemes_addons_get_theme_builder_elements();
			$html_output = '';

			foreach ($tb_elements as $tb_element_id => $tb_element) {
				$templates = get_posts(array(
					'post_type' => 'aafe_templates',
					'numberposts' => -1,
					'meta_query' => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
						array(
							'key' => '_ahf_template_type',
							'value' => $tb_element_id,
						),
					),
				));

				if (!empty($templates)) {
					foreach ($templates as $template) {
						$template_id = $template->ID;
						$template_title = $template->post_title;
						$template_type = get_post_meta($template_id, '_ahf_template_type', true);
						$template_conditions = get_post_meta($template_id, '_ahf_template_conditions', true);

						// Get header specific settings
						if ('header' === $template_type) {
							$header_type    = get_post_meta( $template_id, '_ahf_header_type', true );
						}

						// Display conditions
						$conditions = (isset($template_conditions)) ? json_decode($template_conditions, true) : array();
						$settings = array(
							'values' => $conditions,
						);

						// Build HTML content as a string
						ob_start();
						?>
						<div class="athemes-addons-tb-element" data-template-id="<?php echo esc_attr($template_id); ?>">
							<div class="tb-element-name">
								<span class="template-label"><?php echo esc_html($tb_elements[$template_type]['title']); ?></span>
								<h4><?php echo esc_html($template_title); ?></h4>
							</div>
							<div class="tb-element-actions">
								<?php if ('header' === $template_type) : ?>
									<label class="athemes-addons-header-type-select">
										<select name="ahf_header_type" class="aafe-header-type-select">
											<option value="regular" <?php selected( $header_type, 'regular' ); ?>><?php esc_html_e( 'Regular', 'athemes-addons-elementor' ); ?></option>
											<option value="transparent" <?php selected( $header_type, 'transparent' ); ?>><?php esc_html_e( 'Transparent', 'athemes-addons-elementor' ); ?></option>
											<option value="sticky" <?php selected( $header_type, 'sticky' ); ?>><?php esc_html_e( 'Sticky', 'athemes-addons-elementor' ); ?></option>
										</select>
										<span class="tooltip"><?php esc_html_e( 'Note: if you create a sticky header, you also need a regular or transparent header.', 'athemes-addons-elementor' ); ?></span>
									</label>									
								<?php endif; ?>

								<?php if (!in_array($template_type, array( 'error404', 'shop', 'cart', 'checkout' ), true)) : // no need for display conditions for: error404, shop, cart, checkout ?>
									<div class="athemes-addons-display-conditions-control" data-condition-settings="<?php echo esc_attr(json_encode($settings)); // phpcs:ignore WordPress.WP.AlternativeFunctions.json_encode_json_encode ?>">
										<span class="athemes-addons-display-conditions-modal-toggle">
											<span class="dashicons dashicons-admin-generic"></span>
											<span style="min-width:130px;margin-left:-65px;" class="tooltip"><?php esc_html_e('Display conditions', 'athemes-addons-elementor'); ?></span>
										</span>
										<div class="athemes-addons-display-conditions-modal">
											<!-- Modal content goes here -->
										</div>
										<input class="athemes-addons-display-conditions-textarea" type="hidden" name="conditions" value="<?php echo isset($template_conditions) ? esc_attr($template_conditions) : ''; ?>">
									</div>
								<?php endif; ?>

								<div class="aafe-edit-template">
									<span class="dashicons dashicons-edit"></span>
									<span class="tooltip"><?php esc_html_e('Edit', 'athemes-addons-elementor'); ?></span>
								</div>

								<div class="aafe-delete-template">
									<span class="dashicons dashicons-trash"></span>
									<span class="tooltip"><?php esc_html_e('Delete', 'athemes-addons-elementor'); ?></span>
								</div>
							</div>
						</div>
						<?php
						$html_output .= ob_get_clean();
					}
				}
			}

			wp_send_json_success( $html_output );
		}

		/**
		 * Flush rewrite rules
		 */
		public function flush_rules() {
			if ( !get_transient( 'athemes_addons_theme_builder_flushed_rules') ) {
				flush_rewrite_rules();
				set_transient( 'athemes_addons_theme_builder_flushed_rules', true, 0 );
			}
		}
	}
}