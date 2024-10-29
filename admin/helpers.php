<?php
/**
 * Helper functions
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Output the switcher button for module activation.
 */
if ( ! function_exists( 'athemes_addons_module_activation_switcher' ) ) {
	function athemes_addons_module_activation_switcher( $module_id, $value, $is_upsell = false ) {
		?>
			<div class="athemes-addons-toggle-switch athemes-addons-module-page-button-action-<?php echo ( $value ) ? 'deactivate' : 'activate'; ?> " data-module="<?php echo esc_attr( $module_id ); ?>">
				<span class="saved-label"><?php echo esc_html__( 'Saved!', 'athemes-addons-elementor' ); ?></span>
				<input type="checkbox" id="<?php echo esc_attr( $module_id ); ?>" name="aafe[<?php echo esc_attr( $module_id ); ?>]" value="1" <?php checked( $value, 1, true ); ?>
						class="toggle-switch-checkbox"/>
				<label class="toggle-switch-label" for="<?php echo esc_attr( $module_id ); ?>">
					<span class="toggle-switch-inner"></span>
					<span class="toggle-switch-switch"></span>
				</label>
				<?php if ( ! empty( $settings['label'] ) ) : ?>
					<span><?php echo esc_html( $settings['label'] ); ?></span>
				<?php endif; ?>
			</div>
		<?php
	}
}

/**
 * Output the help icon on the modules list.
 */
if ( ! function_exists( 'athemes_addons_module_help_icon' ) ) {
	function athemes_addons_module_help_icon( $tutorial_url = false ) {
		?>
		<div class="athemes-addons-module-help-link">
			<a href="<?php echo esc_url( $tutorial_url ); ?>" target="_blank">
				<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path fill-rule="evenodd" clip-rule="evenodd" d="M14.1663 4.16667H5.83301C5.61199 4.16667 5.40003 4.25446 5.24375 4.41074C5.08747 4.56702 4.99967 4.77899 4.99967 5V15C4.99967 15.221 5.08747 15.433 5.24375 15.5893C5.40003 15.7455 5.61199 15.8333 5.83301 15.8333H14.1663C14.3874 15.8333 14.5993 15.7455 14.7556 15.5893C14.9119 15.433 14.9997 15.221 14.9997 15V5C14.9997 4.77899 14.9119 4.56702 14.7556 4.41074C14.5993 4.25446 14.3874 4.16667 14.1663 4.16667ZM5.83301 2.5C5.16997 2.5 4.53408 2.76339 4.06524 3.23223C3.5964 3.70107 3.33301 4.33696 3.33301 5V15C3.33301 15.663 3.5964 16.2989 4.06524 16.7678C4.53408 17.2366 5.16997 17.5 5.83301 17.5H14.1663C14.8294 17.5 15.4653 17.2366 15.9341 16.7678C16.4029 16.2989 16.6663 15.663 16.6663 15V5C16.6663 4.33696 16.4029 3.70107 15.9341 3.23223C15.4653 2.76339 14.8294 2.5 14.1663 2.5H5.83301Z" fill="#5C5F62"/>
					<path d="M6.66602 5.83325H13.3327V7.49992H6.66602V5.83325ZM6.66602 9.16659H13.3327V10.8333H6.66602V9.16659ZM6.66602 12.4999H10.8327V14.1666H6.66602V12.4999Z" fill="#5C5F62"/>
				</svg>
			</a>
			<span class="athemes-addons-module-tooltip"><?php esc_html_e( 'Help', 'athemes-addons-elementor' ); ?></span>
		</div>
		<?php
	}
}

/**
 * Output the preview icon on the modules list.
 */
if ( ! function_exists( 'athemes_addons_module_preview_icon' ) ) {
	function athemes_addons_module_preview_icon( $preview_url = false ) {
		?>
		<div class="athemes-addons-module-help-link">
			<a href="<?php echo esc_url( $preview_url ); ?>" target="_blank">
				<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M1.66699 1.66675H18.3337V3.33341H17.5003V15.0001H12.012L15.3453 18.3334L14.167 19.5117L10.0003 15.3451L5.83366 19.5117L4.65533 18.3334L7.98866 15.0001H2.50033V3.33341H1.66699V1.66675ZM4.16699 3.33341V13.3334H15.8337V3.33341H4.16699ZM8.33366 5.41675L12.2228 8.33341L8.33366 11.2501V5.41675Z" fill="#5C5F62"/>
				</svg>
			</a>
			<span class="athemes-addons-module-tooltip"><?php esc_html_e( 'Preview', 'athemes-addons-elementor' ); ?></span>
		</div>
		<?php
	}
}

/**
 * Get all widget categories from the modules list.
 */
if ( ! function_exists( 'athemes_addons_get_widget_categories' ) ) {
	function athemes_addons_get_widget_categories( $aafe_modules ) {
		$categories = array();
		
		foreach ( $aafe_modules as $aafe_module_id => $aafe_module ) : 
			if ( isset( $aafe_module['category'] ) ) {
				if ( !in_array( $aafe_module['category'], $categories, true ) ) {
					$categories[] = $aafe_module['category'];
				}
			}
		endforeach;

		return $categories;
	}
}

/**
 * Get theme builder elements.
 */
if ( ! function_exists( 'athemes_addons_get_theme_builder_elements' ) ) {
	function athemes_addons_get_theme_builder_elements() {
		$tb_elements = array(
			'header' => array(
				'title' => esc_html__( 'Header', 'athemes-addons-elementor' ),
			),
			'footer' => array(
				'title' => esc_html__( 'Footer', 'athemes-addons-elementor' ),
			),
			'singular' => array(
				'title' => esc_html__( 'Singular', 'athemes-addons-elementor' ),
			),
			'archive' => array(
				'title' => esc_html__( 'Archive', 'athemes-addons-elementor' ),
			),
			'archive-item' => array(
				'title' => esc_html__( 'Archive Item', 'athemes-addons-elementor' ),
			),
			'error404' => array(
				'title' => esc_html__( '404', 'athemes-addons-elementor' ),
			),
		);
		
		$woo_elements = array(
			'shop' => array(
				'title' => esc_html__( 'Product Archive', 'athemes-addons-elementor' ),
			),
			'product' => array(
				'title' => esc_html__( 'Product', 'athemes-addons-elementor' ),
			),
		);
		
		if ( !defined( 'ATHEMES_AFE_PRO_VERSION' ) || defined( 'ATHEMES_AFE_PRO_VERSION') && class_exists( 'Woocommerce' ) ) {
			$tb_elements = array_merge( $tb_elements, $woo_elements );
		}

		return apply_filters( 'athemes_addons_theme_builder_elements', $tb_elements );
	}
}

/**
 * Get all post types.
 */
if ( ! function_exists( 'athemes_addons_get_post_types' ) ) {
	function athemes_addons_get_post_types() {
		$post_types = get_post_types( array( 'public' => true ), 'objects' );
		$exclude_post_types = array( 'elementor_library', 'attachment' );

		$filtered_post_types = array();

		$filtered_post_types['all'] = esc_html__( 'All', 'athemes-addons-elementor' );

		foreach ( $post_types as $post_type ) {
			if ( ! in_array( $post_type->name, $exclude_post_types, true ) ) {
				$filtered_post_types[$post_type->name] = $post_type->labels->name;
			}
		}

		return $filtered_post_types;
	}
}