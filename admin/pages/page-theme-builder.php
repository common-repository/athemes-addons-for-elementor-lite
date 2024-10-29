<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$tb_elements = athemes_addons_get_theme_builder_elements();

// Icon and text for the action button.
$action_text        = esc_html__( 'Upgrade', 'athemes-addons-elementor' );
$action_icon        = 'dashicons-lock';
$create_template    = 'aafe-upgrade';

if ( defined( 'ATHEMES_AFE_PRO_VERSION' ) ) {
	$action_text        = esc_html__( 'Create', 'athemes-addons-elementor' );
	$action_icon        = 'dashicons-plus-alt2';
	$create_template    = 'aafe-create-template';
}
?>

<div class="athemes-addons-modules-box">
	<h3><?php esc_html_e( 'What are you building today?', 'athemes-addons-elementor' ); ?></h3>

	<?php if ( !defined( 'ATHEMES_AFE_PRO_VERSION' ) ) : ?>
	<div class="athemes-addons-tb-upgrade-notice">
		<p><?php esc_html_e( 'This feature requires aThemes Addons Pro. Upgrade and unlock the theme builder and much more.', 'athemes-addons-elementor' ); ?></p>
		<a href="https://athemes.com/addons/" target="_blank"><?php esc_html_e( 'Upgrade now', 'athemes-addons-elementor' ); ?></a>
	</div>
	<?php endif; ?>

	<div class="athemes-addons-theme-builder-elements" data-type="parts">
		<?php foreach ( $tb_elements as $tb_element_id => $tb_element ) : ?>
			<div class="athemes-addons-tb-element <?php echo esc_attr( $create_template ); ?>" data-template-type="<?php echo esc_attr( $tb_element_id ); ?>" data-template-label="<?php echo esc_attr( $tb_element['title'] ); ?>">
				<div class="athemes-addons-tb-element-img">
					<img src="<?php echo esc_url( ATHEMES_AFE_URI . 'assets/images/theme-builder/' . $tb_element_id . '.svg' ); ?>" alt="<?php echo esc_attr( $tb_element['title'] ); ?>">
					<div class="overlay-action">
						<span><span class="dashicons <?php echo esc_attr( $action_icon ); ?>"></span></span>
						<h6><?php echo esc_html( $action_text ); ?></h6>
					</div>
				</div>
				<h4><?php echo esc_html( $tb_element['title'] ); ?></h4>
			</div>
		<?php endforeach; ?>
	</div>

	<hr>

	<?php if ( defined( 'ATHEMES_AFE_PRO_VERSION' ) ) : ?>
	<h3><?php esc_html_e( 'Your templates', 'athemes-addons-elementor' ); ?></h3>

	<div class="athemes-addons-theme-builder-elements" data-type="templates">
		<?php
		$no_templates = true;
		foreach ( $tb_elements as $tb_element_id => $tb_element ) {

			$templates = get_posts( array(
				'post_type'     => 'aafe_templates',
				'numberposts'   => -1,
				'meta_query'    => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
					array(
						'key'       => '_ahf_template_type',
						'value'     => $tb_element_id,
					),
				),
			) );
			
			if ( !empty( $templates ) ) {
				foreach ( $templates as $template ) {
					$template_id            = $template->ID;
					$template_title         = $template->post_title;
					$template_type          = get_post_meta( $template_id, '_ahf_template_type', true );
					$template_conditions    = get_post_meta( $template_id, '_ahf_template_conditions', true );

					// Get header specific settings
					if ( 'header' === $template_type ) {
						$header_type    = get_post_meta( $template_id, '_ahf_header_type', true );
					}

					// Display conditions
					$conditions = ( isset( $template_conditions ) ) ? json_decode( $template_conditions, true ) : array();

					$settings = array(
						'values' => $conditions,
					);
	
					?>
					<div class="athemes-addons-tb-element" data-template-id="<?php echo esc_attr( $template_id ); ?>">				
						<div class="tb-element-name">
							<span class="template-label"><?php echo esc_html( $tb_elements[$template_type]['title'] ); ?></span>	
							<h4><?php echo esc_html( $template_title ); ?></h4>
						</div>
						<div class="tb-element-actions">
							<?php if ( 'header' === $template_type ) : ?>							
							<label class="athemes-addons-header-type-select">
								<span class="saved-label"><?php echo esc_html__( 'Saved!', 'athemes-addons-elementor' ); ?></span>
								<select name="ahf_header_type" class="aafe-header-type-select">
									<option value="regular" <?php selected( $header_type, 'regular' ); ?>><?php esc_html_e( 'Regular', 'athemes-addons-elementor' ); ?></option>
									<option value="transparent" <?php selected( $header_type, 'transparent' ); ?>><?php esc_html_e( 'Transparent', 'athemes-addons-elementor' ); ?></option>
									<option value="sticky" <?php selected( $header_type, 'sticky' ); ?>><?php esc_html_e( 'Sticky', 'athemes-addons-elementor' ); ?></option>
								</select>
								<span class="tooltip"><?php esc_html_e( 'Note: if you create a sticky header, you also need a regular or transparent header.', 'athemes-addons-elementor' ); ?></span>
							</label>
							
							<?php endif; ?>

							<?php if ( !in_array( $template_type, array( 'error404', 'cart', 'checkout' ), true ) ) : // no need for display conditions for: error404, shop, cart, checkout ?>
							<div class="athemes-addons-display-conditions-control" data-condition-settings="<?php echo esc_attr( json_encode( $settings ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions.json_encode_json_encode ?>">
								<span class="athemes-addons-display-conditions-modal-toggle" style="width:100%;">
									<span class="dashicons dashicons-admin-generic"></span>
									<span style="min-width:130px;margin-left:-65px;" class="tooltip"><?php esc_html_e( 'Display conditions', 'athemes-addons-elementor' ); ?></span>
								</span>
								<div class="athemes-addons-display-conditions-modal">
								<!-- Modal content goes here -->
								</div>
								<input class="athemes-addons-display-conditions-textarea" type="hidden" name="conditions" value="<?php echo isset( $template_conditions ) ? esc_attr( $template_conditions ) : ''; ?>">
							</div>
							<?php endif; ?>

							<div class="aafe-edit-template">
								<span class="dashicons dashicons-edit"></span>
								<span class="tooltip"><?php esc_html_e( 'Edit', 'athemes-addons-elementor' ); ?></span>
							</div>	

							<div class="aafe-delete-template">
								<span class="dashicons dashicons-trash"></span>
								<span class="tooltip"><?php esc_html_e( 'Delete', 'athemes-addons-elementor' ); ?></span>
							</div>
						</div>
					</div>
					<?php
				}
				$no_templates = false;
			}
		}

		if ( $no_templates ) {
			?>
			<p><?php esc_html_e( 'No templates found.', 'athemes-addons-elementor' ); ?></p>
			<?php
		}
		?>		
	</div>

	<div class="athemes-addons-elementor-iframe-wrapper">
		<iframe id="athemes-addons-elementor-iframe"></iframe>
		<div class="athemes-addons-close-modal"><span class="dashicons dashicons-no-alt"></span></div>
		<div class="athemes-addons-iframe-loader"><span class="dashicons dashicons-update-alt"></span></div>
	</div>	
	<?php endif; //end pro check ?>	
</div>