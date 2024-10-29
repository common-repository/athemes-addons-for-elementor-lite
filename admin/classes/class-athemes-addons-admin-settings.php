<?php
/**
 * Admin_Options Class.
 */
namespace aThemesAddons;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Admin_Settings' ) ) {
	class Admin_Settings {

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
			add_action( 'wp_ajax_aafe_save_settings', array( $this, 'save_settings_callback' ) );
		}

		/**
		 * Save settings.
		 */
		public function save_settings_callback() {
			$nonce = ( isset( $_POST['nonce'] ) ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
		
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( 'You are not allowed to do this.' );
			}
		
			if ( wp_verify_nonce( $nonce, 'athemes-addons-elementor' ) ) {

				$options = get_option( 'athemes-addons-settings', array() );
		
				$fields = ( isset( $_POST['fields'] ) ) ? $_POST['fields'] : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Sanitized in foreach.
		
				foreach ( $fields as $field_id => $field_value ) {
					$sanitized_value  = isset( $field_value ) ? sanitize_text_field( wp_unslash( $field_value ) ) : '';

					$options[ $field_id ] = $sanitized_value;
				}
		
				update_option( 'athemes-addons-settings', $options );
		
				wp_send_json_success();
			}
		
			wp_send_json_error();
		}

		/**
		 * Get option.
		 */
		public static function get( $setting, $default ) {
			$options = get_option( 'athemes-addons-settings', array() );

			$value = $default;

			if ( isset( $options[ $setting ] ) && isset( $options[ $setting ] ) ) {
				$value = $options[ $setting ];
			}

			return $value;
		}

		/**
		 * Create options.
		 */
		public static function create( $settings ) {
			/**
			 * Hook: aafe_module_settings
			 *
			 * @since 1.0
			 */
			$settings = apply_filters( 'aafe_module_settings', $settings );

			$options = get_option( 'athemes-addons-settings', array() );

			?>
            <div class="athemes-addons-module-page-settings">
                <div class="athemes-addons-module-page-setting-box">
					<?php
					if ( ! empty( $settings['title'] ) ) : ?>
                        <div class="athemes-addons-module-page-setting-title">
							<?php
							echo '<h4>' . esc_html( $settings['title'] ) . '</h4>'; ?>
							<?php
							if ( ! empty( $settings['subtitle'] ) ) : ?>
                                <div class="athemes-addons-module-page-setting-subtitle"><?php
									echo esc_html( $settings['subtitle'] ); ?></div>
							<?php
							endif; ?>
                        </div>
					<?php
					endif; ?>
                    <div class="athemes-addons-module-page-setting-fields">
						<?php

						if ( ! empty( $settings['fields'] ) ) {
							foreach ( $settings['fields'] as $field ) {
								$value = null;

								if ( isset( $field['default'] ) ) {
									$value = $field['default'];
								}

								if ( isset( $field['id'] ) && isset( $options[ $field['id'] ] ) ) {
									$value = $options[ $field['id'] ];
								}
	
								if ( 'text' === $field['type'] ) {
									self::text( $field, $value );
								} elseif ( 'multicheckbox' === $field['type'] ) {
									self::multicheckbox( $field, $value );
								}
							}
						}
						?>
                    </div>
                </div>
            </div>
			<?php
		}

		/**
		 * Field: Text
		 */
		public static function text( $settings, $value ) {
			?>
			<div class="athemes-addons-module-page-setting-field athemes-addons-module-page-setting-field-text">
            <input type="text" name="<?php
			echo esc_attr( $settings['id'] ); ?>" value="<?php
			echo esc_attr( $value ); ?>"/>
			</div>
			<?php
		}

		/**
		 * Field: Multiple checkbox that allows multiple selection
		 */
		public static function multicheckbox( $settings, $value ) {
			?>
			<div class="athemes-addons-module-page-setting-field athemes-addons-module-page-setting-field-multicheckbox">
				<div>
				<?php
				if ( ! is_array( $value ) ) {
					$value = explode( ',', $value );
				}
				if ( ! empty( $settings['options'] ) ) : ?>
					<?php
					foreach ( $settings['options'] as $key => $option ) : ?>
						<label>
							<input 
								type="checkbox" name="<?php echo esc_attr( $settings['id'] ); ?>[]" 
								value="<?php echo esc_attr( $key ); ?>" 
								<?php checked( in_array( $key, $value, true ), true ); ?>
							/>
							<span><?php echo esc_html( $option ); ?></span>
						</label>
					<?php
					endforeach; ?>
				<?php
				endif; 
				?>
				</div>
				<input type="hidden" name="<?php echo esc_attr( $settings['id'] ); ?>" value="" />
			</div>
			<?php
		}

		/**
		 * Save button.
		 */
		public static function save_button() {
			?>
			<div class="athemes-addons-module-page-setting-save">
				<button class="button button-primary button-hero aafe-save-settings"><?php esc_html_e( 'Save', 'athemes-addons-elementor' ); ?></button>
			</div>
			<?php
		}
	}

	Admin_Settings::instance();
}