<?php
/**
 * Admin_Options Class.
 */
namespace aThemesAddons;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Theme_Builder_Metabox' ) ) {

	class Theme_Builder_Metabox {

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
			add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
			add_action( 'save_post', array( $this, 'save' ) );
			add_action( 'save_post', array( $this, 'get_template_type' ) );
		}

		/**
		 * Add the metabox.
		 */
		public function add_meta_box( $post_type ) {
			
			$types = get_post_types(
				array(
					'public' => true,
				)
			);

			if ( in_array( $post_type, $types, true ) && ( 'attachment' !== $post_type ) ) {
				add_meta_box(
					'aafe_templates_metabox'
					,__( 'Templates', 'athemes-addons-elementor' )
					,array( $this, 'render_meta_box_content' )
					,'aafe_templates'
					,'normal'
					,'low'
				);
			}
		}

		/**
		 * Get the template type from the url.
		 */
		public function get_template_type( $post_id ) {

			if ( get_post_type( $post_id ) == 'aafe_templates' ) { //phpcs:ignore Universal.Operators.StrictComparisons.LooseEqual

				if (get_post_status( $post_id ) == 'auto-draft') { //phpcs:ignore Universal.Operators.StrictComparisons.LooseEqual

					if ( isset( $_GET['ahf_template_type'] ) ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended
						$ahf_template_type = sanitize_text_field( $_GET['ahf_template_type'] ); //phpcs:ignore WordPress.Security.NonceVerification.Recommended
						update_post_meta($post_id, '_ahf_template_type', $ahf_template_type);
					}
				}
			}
		}

		/**
		 * Save the metabox.
		 */
		public function save( $post_id ) {
			if ( ! isset( $_POST['athemes_display_rules_nonce'] ) || !wp_verify_nonce( $_POST['athemes_display_rules_nonce'], 'athemes_display_rules_nonce' ) ) { //phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
				return;
			}
			
			if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
				return;
			}
			
			if ( !current_user_can( 'edit_post', $post_id ) ) {
				return;
			}
			
			$old = get_post_meta($post_id, 'ahf_display_rules', true);
			$new = array();
			
			$selects = isset( $_POST['select'] ) ? array_map( 'sanitize_text_field', $_POST['select'] ) : array();
			
			if ( isset( $selects ) ) {
				$count = count( $selects );
	
				for ( $i = 0; $i < $count; $i++ ) {     
					$new[$i]['select'] = $selects[$i];
				}           
			}
	
			if ( !empty( $new ) && $new !== $old && '' !== $new ) {
				update_post_meta( $post_id, 'ahf_display_rules', $new );
			} elseif ( empty($new) && $old ) {
				delete_post_meta( $post_id, 'ahf_display_rules', $old );
			}
	
			//Save template type
			$templates = array( 'header', 'footer', 'content', 'archive', 'archive-item', 'single', 'content', 'error404', 'product', 'shop', 'cart', 'checkout' );
			$template_type = $this->sanitize_selects( sanitize_text_field( $_POST['ahf_template_type'] ), $templates ); //phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
			update_post_meta( $post_id, '_ahf_template_type', $template_type );
			
			//Save hooks type
			$hooks = $this->hooks();
			$all_hooks = array();
			foreach ( $hooks as $hook_group ) {
				$all_hooks = array_merge( $all_hooks, array_keys( $hook_group['group'] ) );
			}
			$hooks_type = $this->sanitize_selects( sanitize_text_field( $_POST['ahf_hook_type'] ), $all_hooks ); //phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
			update_post_meta( $post_id, '_ahf_hook_type', $hooks_type );
	
			//Save hooks priority
			$priority = intval( $_POST['ahf_hook_priority'] ); //phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
			update_post_meta( $post_id, '_ahf_hook_priority', $priority );
	
			//Transparent header
			$merge_header = ( isset( $_POST['ahf_merge_header'] ) && '1' === $_POST['ahf_merge_header'] ) ? 1 : 0;
			update_post_meta( $post_id, '_ahf_merge_header', $merge_header );
		}   
		
		public function render_meta_box_content( $post ) {
			global $post;
	
			$repeatable_fields  = get_post_meta($post->ID, 'ahf_display_rules', true);
			$priority           = get_post_meta($post->ID, '_ahf_hook_priority', true);
	
			if ( !$priority ) {
				$priority = 10;
			}
	
			wp_nonce_field( 'athemes_display_rules_nonce', 'athemes_display_rules_nonce' );
			?>
			<script type="text/javascript">
			jQuery(document).ready(function( $ ){
				$( '#add-row' ).on('click', function() {
					var row = $( '.regular-target.empty-row.screen-reader-text' ).clone(true);
					row.removeClass( 'empty-row screen-reader-text' );
					row.insertBefore( '#repeatable-fieldset-one tbody>tr:last' ).css('display', 'flex');
					row.find( 'select' ).attr( 'name', 'select[]' );
	
					$( '.is-visible .athemes-rule-select' ).select2({
						minimumResultsForSearch: Infinity
					});
	
					return false;
				});
	
				$( '#add-specific-page' ).on('click', function() {
					var row = $( '.specific-page-select-row.empty-row.screen-reader-text' ).clone(true);
					row.removeClass( 'empty-row screen-reader-text' ).addClass( 'is-visible' );
					row.insertBefore( '#repeatable-fieldset-one tbody>tr:last' ).css('display', 'flex');
					row.find( 'select' ).attr( 'name', 'select[]' );
					
					$( '.is-visible .athemes-specific-page-select' ).select2();
	
					return false;
				});			
			  
				$( '.remove-row' ).on('click', function() {
					$(this).parents('tr').remove();
					return false;
				});
	
				//Init select2
				$( '.is-visible .athemes-specific-page-select' ).select2();
	
				$( '.is-visible .athemes-rule-select' ).select2({
					minimumResultsForSearch: Infinity,
				});
	
				$( '.ahf-hook-select' ).select2({
					minimumResultsForSearch: Infinity
				});	
	
	
				if ( 'content' == $('input[name="ahf_template_type"]:checked').val() ) {
					$( '.ahf-hook-group,.hooks-hr' ).fadeIn( 'fast' );	
				}			
	
				$( '.ahf-template-select' ).change(function () {                            
					if ( 'content' == $(this).val() ) {
						$( '.ahf-hook-group,.hooks-hr' ).fadeIn( 'fast' );	
					} else {
						$( '.ahf-hook-group,.hooks-hr' ).fadeOut( 'fast' );	
					}
	
					if ( 'archive-item' == $(this).val() || 'error404' == $(this).val() ) {
						$( '.ahf-rules-group,.rules-hr' ).fadeOut( 'fast' );	
					} else {
						$( '.ahf-rules-group,.rules-hr' ).fadeIn( 'fast' );	
					}
	
					if ( 'header' == $(this).val() ) {
						$( '.ahf-header-group,.header-hr' ).fadeIn( 'fast' );	
					} else {
						$( '.ahf-header-group,.header-hr' ).fadeOut( 'fast' );	
					}				
				});
	
				if ( 'archive-item' != $('input[name="ahf_template_type"]:checked').val() && 'error404' != $('input[name="ahf_template_type"]:checked').val() ) {
					$( '.ahf-rules-group,.rules-hr' ).show();	
				}	
	
				if ( 'header' == $('input[name="ahf_template_type"]:checked').val() ) {
					$( '.ahf-header-group,.header-hr' ).show();
				}
			});
			</script>
			<style type="text/css">
				.target-rules-wrapper { display: -webkit-box; display: -ms-flexbox; display: flex; -ms-flex-wrap: wrap; flex-wrap: wrap; gap:30px;}
				.target-rules-column:first-of-type {-webkit-box-flex: 0;-ms-flex: 0 0 calc(65% - 15px);flex: 0 0 calc(65% - 15px);max-width:calc(65% - 15px);}
				.target-rules-column:last-of-type {-webkit-box-flex: 0;-ms-flex: 0 0 calc(35% - 15px);flex: 0 0 calc(35% - 15px);max-width:calc(35% - 15px);}
				@media screen and (max-width: 991px) {
					.target-rules-wrapper { display: block; }
					.target-rules-column:first-of-type {-webkit-box-flex: 0;-ms-flex: 0 0 100%;flex: 0 0 100%;max-width:100%;}
					.target-rules-column:last-of-type {-webkit-box-flex: 0;-ms-flex: 0 0 100%;flex: 0 0 100%;max-width:100%;}
				}				
				.is-visible .select2-container {width:100% !important;}
				.ahf-template-select + .select2-container, .ahf-hook-select + .select2-container { max-width: 500px; }
				.ahf-template-group label,.ahf-hook-group label { display: inline-block;min-width:80px;color: #939393;padding-right:15px; }
				.ahf-template-group label {text-align: center;padding:0;margin-bottom:15px;margin-right:25px;width: 115px;}
				.ahf-template-group, .ahf-rules-group { margin-bottom: 20px; }
				.ahf-template-group > div { display: flex; flex-wrap: wrap; }
				.ahf-hook-group,.hooks-hr, .ahf-rules-group, .rules-hr, .ahf-header-group, .header-hr {display:none;}
				.target-rule-td {min-width:80px;color: #939393;padding-right:15px;padding-top:5px;}
				.target-rule-td-large {max-width:500px;width:100%;display:block;}
				.tips-wrapper {padding: 30px;background-color: #f7f7f7;height:100%;box-sizing:border-box;}
				.tips-list {list-style:disc;padding-left:20px;}
				.ahf-template-select {position: absolute;opacity: 0;}
				.ahf-template-select+img {max-width:100%;cursor: pointer;display:block;margin-bottom:10px;outline:3px solid rgba(0,0,0,0.05);transition:outline 0.2s;}
				.ahf-template-select:checked + img, .ahf-template-select:hover + img {outline: 2px solid #2271b1;}
				.option-info {display:block; color: #939393; font-style: italic; margin-left: 26px;}
				.target-rules-wrapper input[type=checkbox] {margin-right:10px;}
			</style>
	
			<div class="target-rules-wrapper">
				<div class="target-rules-column">
					<?php $template_type = get_post_meta( $post->ID, '_ahf_template_type', true ); ?>
					<div class="ahf-template-group">
						<h4><?php echo esc_html__( 'What would you like to build?', 'athemes-addons-elementor' ); ?></h4>
						<div>
							<label>
								<input class="ahf-template-select" type="radio" name="ahf_template_type" value="header" <?php checked( $template_type, 'header' ); ?>>
								<img src="<?php echo esc_url( ATHEMES_AFE_URI . 'assets/images/theme-builder/header.svg' ); ?>"/>
								<?php esc_attr_e( 'Header', 'athemes-addons-elementor' ); ?>
							</label>
							<label>
								<input class="ahf-template-select" type="radio" name="ahf_template_type" value="footer" <?php checked( $template_type, 'footer' ); ?>>
								<img src="<?php echo esc_url( ATHEMES_AFE_URI . 'assets/images/theme-builder/footer.svg' ); ?>"/>
	
								<?php esc_attr_e( 'Footer', 'athemes-addons-elementor' ); ?>
							</label>
							<label>
								<input class="ahf-template-select" type="radio" name="ahf_template_type" value="archive" <?php checked( $template_type, 'archive' ); ?>>
								<img src="<?php echo esc_url( ATHEMES_AFE_URI . 'assets/images/theme-builder/archive.svg' ); ?>"/>
	
								<?php esc_attr_e( 'Archive', 'athemes-addons-elementor' ); ?>
							</label>
							<label>
								<input class="ahf-template-select" type="radio" name="ahf_template_type" value="archive-item" <?php checked( $template_type, 'archive-item' ); ?>>
								<img src="<?php echo esc_url( ATHEMES_AFE_URI . 'assets/images/theme-builder/archive-item.svg' ); ?>"/>
	
								<?php esc_attr_e( 'Archive item', 'athemes-addons-elementor' ); ?>
							</label>
							<label>
								<input class="ahf-template-select" type="radio" name="ahf_template_type" value="single" <?php checked( $template_type, 'single' ); ?>>
								<img src="<?php echo esc_url( ATHEMES_AFE_URI . 'assets/images/theme-builder/singular.svg' ); ?>"/>
	
								<?php esc_attr_e( 'Single', 'athemes-addons-elementor' ); ?>
							</label>
							<label>
								<input class="ahf-template-select" type="radio" name="ahf_template_type" value="content" <?php checked( $template_type, 'content' ); ?>>
								<img src="<?php echo esc_url( ATHEMES_AFE_URI . 'assets/images/theme-builder/content.svg' ); ?>"/>
								<?php esc_attr_e( 'Content block', 'athemes-addons-elementor' ); ?>
							</label>
							<label>
								<input class="ahf-template-select" type="radio" name="ahf_template_type" value="error404" <?php checked( $template_type, 'error404' ); ?>>
								<img src="<?php echo esc_url( ATHEMES_AFE_URI . 'assets/images/theme-builder/error404.svg' ); ?>"/>
								<?php esc_attr_e( '404 page', 'athemes-addons-elementor' ); ?>
							</label>
							<?php if ( class_exists( 'WooCommerce' ) ) : ?>
								<label>
									<input class="ahf-template-select" type="radio" name="ahf_template_type" value="shop" <?php checked( $template_type, 'shop' ); ?>>
									<img src="<?php echo esc_url( ATHEMES_AFE_URI . 'assets/images/theme-builder/shop.svg' ); ?>"/>
									<?php esc_attr_e( 'Shop', 'athemes-addons-elementor' ); ?>
								</label>
								<label>
									<input class="ahf-template-select" type="radio" name="ahf_template_type" value="product" <?php checked( $template_type, 'product' ); ?>>
									<img src="<?php echo esc_url( ATHEMES_AFE_URI . 'assets/images/theme-builder/product.svg' ); ?>"/>
									<?php esc_attr_e( 'Product', 'athemes-addons-elementor' ); ?>
								</label>
								<label>
									<input class="ahf-template-select" type="radio" name="ahf_template_type" value="cart" <?php checked( $template_type, 'cart' ); ?>>
									<img src="<?php echo esc_url( ATHEMES_AFE_URI . 'assets/images/theme-builder/cart.svg' ); ?>"/>
									<?php esc_attr_e( 'Cart', 'athemes-addons-elementor' ); ?>
								</label>	
								<label>
									<input class="ahf-template-select" type="radio" name="ahf_template_type" value="checkout" <?php checked( $template_type, 'checkout' ); ?>>
									<img src="<?php echo esc_url( ATHEMES_AFE_URI . 'assets/images/theme-builder/checkout.svg' ); ?>"/>
									<?php esc_attr_e( 'Checkout', 'athemes-addons-elementor' ); ?>
								</label>															
							<?php endif; ?>
						</div>
					</div>

					<hr class="hooks-hr">
					
					<?php $hook_type = get_post_meta( $post->ID, '_ahf_hook_type', true ); ?>
					<?php $hooks = $this->hooks(); ?>
					<div class="ahf-hook-group">
						<h4><?php echo esc_html__( 'Hook type', 'athemes-addons-elementor' ); ?></h4>
						<p>
						<label for="ahf_hook_type"><?php esc_html_e( 'Select hook', 'athemes-addons-elementor' ); ?></label>
						<select class="ahf-hook-select" style="max-width:500px;width:100%;" name="ahf_hook_type">
							<?php foreach ( $hooks as $group => $data ) : ?>
	
								<optgroup label="<?php echo esc_html( $data['label'] ); ?>">
	
								<?php foreach ( $data['group'] as $hook => $label ) : ?>
									<option value="<?php echo esc_attr( $hook ); ?>" <?php selected( $hook_type, $hook ); ?>><?php echo esc_html( $label ); ?></option>
								<?php endforeach; ?>
	
								</optgroup>
							<?php endforeach; ?>
						</select>
						</p>		
						<p>
						<label for="ahf_hook_priority"><?php esc_html_e( 'Priority', 'athemes-addons-elementor' ); ?></label>
						<input style="max-width:500px;width:100%;" type="number" name="ahf_hook_priority" value="<?php echo esc_attr( $priority ); ?>"/>
						</p>
					</div>
				</div>
			</div>
	
			<?php
		}

		/**
		 * Hooks locations
		 */
		public function hooks() {

			//Theme hooks
			$locations = array(
				'theme'         => array(
					'label' => esc_html__( 'Sydney', 'athemes-addons-elementor' ), 
					'group' => array(
						'sydney_before_site'                    => esc_html__( 'Before site (sydney_before_site)', 'athemes-addons-elementor' ),
						'sydney_after_site'                     => esc_html__( 'After site (sydney_after_site)', 'athemes-addons-elementor' ),
						'sydney_before_header'                  => esc_html__( 'Before header (sydney_before_header)', 'athemes-addons-elementor' ),
						'sydney_header'                         => esc_html__( 'Header (sydney_header)', 'athemes-addons-elementor' ),
						'sydney_after_header'                   => esc_html__( 'After header (sydney_after_header)', 'athemes-addons-elementor' ),
						'sydney_main_container_start'           => esc_html__( 'Main container start (sydney_main_container_start)', 'athemes-addons-elementor' ),
						'sydney_main_container_end'             => esc_html__( 'Main container end (sydney_main_container_end)', 'athemes-addons-elementor' ),
						'sydney_before_content'                 => esc_html__( 'Before content (sydney_before_content)', 'athemes-addons-elementor' ),
						'sydney_after_content'                  => esc_html__( 'After content (sydney_after_content)', 'athemes-addons-elementor' ),
						'sydney_before_loop_entry'              => esc_html__( 'Before loop post (sydney_before_loop_entry)', 'athemes-addons-elementor' ),
						'sydney_after_loop_entry'               => esc_html__( 'After loop post (sydney_after_loop_entry)', 'athemes-addons-elementor' ),
						'sydney_before_single_entry'            => esc_html__( 'Before single post (sydney_before_single_entry)', 'athemes-addons-elementor' ),
						'sydney_after_single_entry'             => esc_html__( 'After single post (sydney_after_single_entry)', 'athemes-addons-elementor' ),
						'sydney_404_content'                    => esc_html__( '404 content (sydney_404_content)', 'athemes-addons-elementor' ),
						'sydney_before_footer'                  => esc_html__( 'Before footer (sydney_before_footer)', 'athemes-addons-elementor' ),
						'sydney_footer'                         => esc_html__( 'Footer (sydney_before_footer)', 'athemes-addons-elementor' ),
						'sydney_after_footer'                   => esc_html__( 'After footer (sydney_after_footer)', 'athemes-addons-elementor' ),
						'sydney_after_hero'                     => esc_html__( 'After hero (sydney_after_hero)', 'athemes-addons-elementor' ),
					),
				),
			);

			//Woocommerce hooks
			if ( class_exists( 'Woocommerce' ) ) {
				$locations['woocommerce'] = array(
					'label' => esc_html__( 'WooCommerce', 'athemes-addons-elementor' ), 
					'group' => array(
						'woocommerce_before_main_content'           => esc_html__( 'Before content (woocommerce_before_main_content)', 'athemes-addons-elementor' ),
						'woocommerce_after_main_content'            => esc_html__( 'After content (woocommerce_after_main_content)', 'athemes-addons-elementor' ),
						'woocommerce_before_shop_loop'              => esc_html__( 'Before shop loop (woocommerce_before_shop_loop)', 'athemes-addons-elementor' ),
						'woocommerce_after_shop_loop'               => esc_html__( 'After shop loop (woocommerce_after_shop_loop)', 'athemes-addons-elementor' ),
						'woocommerce_before_shop_loop_item'         => esc_html__( 'Before shop loop item (woocommerce_before_shop_loop_item)', 'athemes-addons-elementor' ),
						'woocommerce_after_shop_loop_item'          => esc_html__( 'After shop loop item (woocommerce_after_shop_loop_item)', 'athemes-addons-elementor' ),
						'woocommerce_before_single_product'         => esc_html__( 'Before single product (woocommerce_before_single_product)', 'athemes-addons-elementor' ),
						'woocommerce_before_single_product_summary' => esc_html__( 'Before single product summary (woocommerce_before_single_product_summary)', 'athemes-addons-elementor' ),
						'woocommerce_single_product_summary'        => esc_html__( 'Single product summary (woocommerce_single_product_summary)', 'athemes-addons-elementor' ),
						'woocommerce_after_single_product_summary'  => esc_html__( 'After single product summary (woocommerce_after_single_product_summary)', 'athemes-addons-elementor' ),
						'woocommerce_after_single_product'          => esc_html__( 'After single product (woocommerce_after_single_product)', 'athemes-addons-elementor' ),
					),
				);
			}

			return $locations;
		}       
	
		/**
		 * Sanitize selects
		 */
		public function sanitize_selects( $input, $choices ) {
	
			$input = sanitize_key( $input );
	
			return ( in_array( $input, $choices, true ) ? $input : '' );
		}       
	}

	Theme_Builder_Metabox::instance();

}