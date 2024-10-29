<?php
/**
 * Display conditions script template
 *
 * @package aThemes Addons
 */

function athemes_addons_templates_display_conditions_script_template() {

	$settings = array();

	$settings['types'][] = array(
		'id'   => 'include',
		'text' => esc_html__( 'Include', 'athemes-addons-elementor' ),
	);

	$settings['types'][] = array(
		'id'   => 'exclude',
		'text' => esc_html__( 'Exclude', 'athemes-addons-elementor' ),
	);

	$settings['display'][] = array(
		'id'   => 'all',
		'text' => esc_html__( 'Entire Site', 'athemes-addons-elementor' ),
	);

	$settings['display'][] = array(
		'id'      => 'basic',
		'text'    => esc_html__( 'Basic', 'athemes-addons-elementor' ),
		'options' => array(
			array(
				'id'   => 'singular',
				'text' => esc_html__( 'Singulars', 'athemes-addons-elementor' ),
			),
			array(
				'id'   => 'archive',
				'text' => esc_html__( 'Archives', 'athemes-addons-elementor' ),
			),
		),
	);

	$settings['display'][] = array(
		'id'      => 'posts',
		'text'    => esc_html__( 'Posts', 'athemes-addons-elementor' ),
		'options' => array(
			array(
				'id'   => 'single-post',
				'text' => esc_html__( 'Single Post', 'athemes-addons-elementor' ),
			),
			array(
				'id'   => 'post-archives',
				'text' => esc_html__( 'Post Archives', 'athemes-addons-elementor' ),
			),
			array(
				'id'   => 'post-categories',
				'text' => esc_html__( 'Post Categories', 'athemes-addons-elementor' ),
			),
			array(
				'id'   => 'post-tags',
				'text' => esc_html__( 'Post Tags', 'athemes-addons-elementor' ),
			),
		),
	);

	$settings['display'][] = array(
		'id'      => 'pages',
		'text'    => esc_html__( 'Pages', 'athemes-addons-elementor' ),
		'options' => array(
			array(
				'id'   => 'single-page',
				'text' => esc_html__( 'Single Page', 'athemes-addons-elementor' ),
			),
		),
	);

	if ( class_exists( 'WooCommerce' ) ) {

		$settings['display'][] = array(
			'id'      => 'woocommerce',
			'text'    => esc_html__( 'WooCommerce', 'athemes-addons-elementor' ),
			'options' => array(
				array(
					'id'   => 'shop',
					'text' => esc_html__( 'Shop', 'athemes-addons-elementor' ),
				),
				array(
					'id'   => 'single-product',
					'text' => esc_html__( 'Single Product', 'athemes-addons-elementor' ),
				),
				array(
					'id'   => 'product-archives',
					'text' => esc_html__( 'Product Archives', 'athemes-addons-elementor' ),
				),
				array(
					'id'   => 'product-categories',
					'text' => esc_html__( 'Product Categories', 'athemes-addons-elementor' ),
				),
				array(
					'id'   => 'product-tags',
					'text' => esc_html__( 'Product Tags', 'athemes-addons-elementor' ),
				),
				array(
					'id'   => 'product-id',
					'text' => esc_html__( 'Product name', 'athemes-addons-elementor' ),
					'ajax' => true,
				),
				array(
					'id'   => 'cart',
					'text' => esc_html__( 'Cart', 'athemes-addons-elementor' ),
				),
				array(
					'id'   => 'checkout',
					'text' => esc_html__( 'Checkout', 'athemes-addons-elementor' ),
				),
			),
		);

	}

	$settings['display'][] = array(
		'id'      => 'specifics',
		'text'    => esc_html__( 'Specific', 'athemes-addons-elementor' ),
		'options' => array(
			array(
				'id'   => 'post-id',
				'text' => esc_html__( 'Post name', 'athemes-addons-elementor' ),
				'ajax' => true,
			),
			array(
				'id'   => 'page-id',
				'text' => esc_html__( 'Page name', 'athemes-addons-elementor' ),
				'ajax' => true,
			),
			array(
				'id'   => 'category-id',
				'text' => esc_html__( 'Category name', 'athemes-addons-elementor' ),
				'ajax' => true,
			),
			array(
				'id'   => 'tag-id',
				'text' => esc_html__( 'Tag name', 'athemes-addons-elementor' ),
				'ajax' => true,
			),
			array(
				'id'   => 'author-id',
				'text' => esc_html__( 'Author name', 'athemes-addons-elementor' ),
				'ajax' => true,
			),
		),
	);

	$available_post_types = get_post_types( array( 'show_in_nav_menus' => true ), 'objects' );
	$available_post_types = array_diff( array_keys( $available_post_types ), array( 'post', 'page', 'product', 'e-landing-page' ) );

	if ( ! empty( $available_post_types ) ) {

		foreach ( $available_post_types as $post_type ) {
			//get post type label
			$post_type_object = get_post_type_object( $post_type );
			$post_type_label = $post_type_object->label;

			$settings['display'][] = array(
				'id'      => 'cpt-'. $post_type,
				'text'    => $post_type_label,
				'options' => array(
					array(
						'id'   => 'cpt-'. $post_type .'-single',
						'text' => esc_html__( $post_type_label .' Single', 'athemes-addons-elementor' ),
					),
					array(
						'id'   => 'cpt-'. $post_type .'-archives',
						'text' => esc_html__( $post_type_label .' Archives', 'athemes-addons-elementor' ),
					),
				),
			);
		}

		$settings['display'][] = array(
			'id'      => 'cpt',
			'text'    => esc_html__( 'Custom Post Types', 'athemes-addons-elementor' ),
			'options' => array(
				array(
					'id'   => 'cpt-post-id',
					'text' => esc_html__( 'CPT: Post name', 'athemes-addons-elementor' ),
					'ajax' => true,
				),
				array(
					'id'   => 'cpt-term-id',
					'text' => esc_html__( 'CPT: Term name', 'athemes-addons-elementor' ),
					'ajax' => true,
				),
				array(
					'id'   => 'cpt-taxonomy-id',
					'text' => esc_html__( 'CPT: Taxonomy name', 'athemes-addons-elementor' ),
					'ajax' => true,
				),
			),
		);

	}

	$settings['display'][] = array(
		'id'      => 'other',
		'text'    => esc_html__( 'Other', 'athemes-addons-elementor' ),
		'options' => array(
			array(
				'id'   => 'front-page',
				'text' => esc_html__( 'Front Page', 'athemes-addons-elementor' ),
			),
			array(
				'id'   => 'blog',
				'text' => esc_html__( 'Blog', 'athemes-addons-elementor' ),
			),
			array(
				'id'   => 'search',
				'text' => esc_html__( 'Search', 'athemes-addons-elementor' ),
			),
			array(
				'id'   => '404',
				'text' => esc_html__( '404', 'athemes-addons-elementor' ),
			),
			array(
				'id'   => 'author',
				'text' => esc_html__( 'Author', 'athemes-addons-elementor' ),
			),
			array(
				'id'   => 'privacy-policy-page',
				'text' => esc_html__( 'Privacy Policy Page', 'athemes-addons-elementor' ),
			),
		),
	);

	$user_roles = array();
	$user_rules = get_editable_roles();

	if ( ! empty( $user_rules ) ) {
		foreach ( $user_rules as $role_id => $role_data ) {
			$user_roles[] = array(
				'id'   => 'user_role_'. $role_id,
				'text' => $role_data['name'],
			);
		}
	}

	$settings['user'][] = array(
		'id'      => 'user-auth',
		'text'    => esc_html__( 'User Auth', 'athemes-addons-elementor' ),
		'options' => array(
			array(
				'id'   => 'logged-in',
				'text' => esc_html__( 'User Logged In', 'athemes-addons-elementor' ),
			),
			array(
				'id'   => 'logged-out',
				'text' => esc_html__( 'User Logged Out', 'athemes-addons-elementor' ),
			),
		),
	);

	$settings['user'][] = array(
		'id'      => 'user-roles',
		'text'    => esc_html__( 'User Roles', 'athemes-addons-elementor' ),
		'options' => $user_roles,
	);

	$settings['user'][] = array(
		'id'      => 'other',
		'text'    => esc_html__( 'Other', 'athemes-addons-elementor' ),
		'options' => array(
			array(
				'id'   => 'author',
				'text' => esc_html__( 'Author', 'athemes-addons-elementor' ),
				'ajax' => true,
			),
		),
	);

	$settings = apply_filters( 'athemes_addons_display_conditions_script_settings', $settings );

	?>
		<script type="text/javascript">
			var aafeDCSettings = <?php echo json_encode( $settings ); // phpcs:ignore WordPress.WP.AlternativeFunctions.json_encode_json_encode ?>;
		</script>
		<script type="text/template" id="tmpl-athemes-addons-display-conditions-template">
			<?php
			?>
			<div class="athemes-addons-display-conditions-modal">
				<div class="athemes-addons-display-conditions-modal-outer">
					<div class="athemes-addons-display-conditions-modal-header">
						<i class="athemes-addons-button-close athemes-addons-display-conditions-modal-toggle dashicons dashicons-no-alt"></i>
					</div>
					<div class="athemes-addons-display-conditions-modal-content">
						<ul class="athemes-addons-display-conditions-modal-content-list">
							<li class="athemes-addons-display-conditions-modal-content-list-item hidden">
								<div class="athemes-addons-display-conditions-select2-type" data-type="include">
									<select name="type">
										<# _.each( aafeDCSettings.types, function( type ) { #>
											<option value="{{ type.id }}">{{ type.text }}</option>
										<# }); #>
									</select>
								</div>
								<div class="athemes-addons-display-conditions-select2-groupped">
									<# _.each( ['display', 'user'], function( conditionGroup ) { #>
										<div class="athemes-addons-display-conditions-select2-condition" data-condition-group="{{ conditionGroup }}">
											<select name="condition">
												<# _.each( aafeDCSettings[ conditionGroup ], function( condition ) { #>
													<# if ( _.isEmpty( condition.options ) ) { #>
														<option value="{{ condition.id }}">{{ condition.text }}</option>
													<# } else { #>
														<optgroup label="{{ condition.text }}">
															<# _.each( condition.options, function( option ) { #>
																<# var ajax = ( option.ajax ) ? ' data-ajax="true"' : ''; #>
																<option value="{{ option.id }}"{{{ ajax }}}>{{ option.text }}</option>
															<# }); #>
														</optgroup>
													<# } #>
												<# }); #>
											</select>
										</div>
									<# }); #>
									<div class="athemes-addons-display-conditions-select2-id hidden">
										<select name="id"></select>
									</div>
								</div>
								<div class="athemes-addons-display-conditions-modal-remove">
									<i class="dashicons dashicons-trash"></i>
								</div>
							</li>
							<# _.each( data.values, function( value ) { #>
								<li class="athemes-addons-display-conditions-modal-content-list-item">
									<div class="athemes-addons-display-conditions-select2-type" data-type="{{ value.type }}">
										<select name="type">
											<# _.each( aafeDCSettings.types, function( type ) { #>
												<# var selected = ( value.type == type.id ) ? ' selected="selected"' : ''; #>
												<option value="{{ type.id }}"{{{ selected }}}>{{ type.text }}</option>
											<# }); #>
										</select>
									</div>
									<div class="athemes-addons-display-conditions-select2-groupped">
										<# 
											var currentCondition;
											_.each( aafeDCSettings, function( conditionValues, conditionKey ) {
												_.each( conditionValues, function( condition ) {
													if ( _.isEmpty( condition.options ) ) {
														if ( value.condition == condition.id ) {
															currentCondition = conditionKey;
														}
													} else {
														_.each( condition.options, function( option ) {
															if ( value.condition == option.id ) {
																currentCondition = conditionKey;
															}
														});
													}
												});
											});
										#>
										<# if ( ! _.isEmpty( currentCondition ) ) { #>
											<div class="athemes-addons-display-conditions-select2-condition" data-condition-group="{{ currentCondition }}">
												<select name="condition">
													<# _.each( aafeDCSettings[ currentCondition ], function( condition ) { #>
														<# if ( _.isEmpty( condition.options ) ) { #>
															<option value="{{ condition.id }}">{{ condition.text }}</option>
														<# } else { #>
															<optgroup label="{{ condition.text }}">
																<# _.each( condition.options, function( option ) { #>
																	<# var ajax = ( option.ajax ) ? ' data-ajax="true"' : ''; #>
																	<# var selected = ( value.condition == option.id ) ? ' selected="selected"' : ''; #>
																	<option value="{{ option.id }}"{{{ ajax }}}{{{ selected }}}>{{ option.text }}</option>
																<# }); #>
															</optgroup>
														<# } #>
													<# }); #>
												</select>
											</div>
										<# } #>
										<div class="athemes-addons-display-conditions-select2-id hidden">
											<select name="id">
												<# if ( ! _.isEmpty( value.id ) ) { #>
													<option value="{{ value.id }}" selected="selected">{{ data.labels[ value.id ] }}</option>
												<# } #>
											</select>
										</div>
									</div>
									<div class="athemes-addons-display-conditions-modal-remove">
										<i class="dashicons dashicons-trash"></i>
									</div>
								</li>
							<# }); #>
						</ul>
						<div class="athemes-addons-display-conditions-modal-content-footer">
							<a href="#" class="button athemes-addons-display-conditions-modal-add" data-condition-group="display"><?php esc_html_e( 'Add Display Condition', 'athemes-addons-elementor' ); ?></a>
							<a href="#" class="button athemes-addons-display-conditions-modal-add" data-condition-group="user"><?php esc_html_e( 'Add User Condition', 'athemes-addons-elementor' ); ?></a>
						</div>
					</div>
					<div class="athemes-addons-display-conditions-modal-footer">
						<a href="#" class="button button-primary athemes-addons-display-conditions-modal-save athemes-addons-display-conditions-modal-toggle"><?php esc_html_e( 'Save Conditions', 'athemes-addons-elementor' ); ?></a>
					</div>
				</div>
			</div>
		</script>
	<?php
}