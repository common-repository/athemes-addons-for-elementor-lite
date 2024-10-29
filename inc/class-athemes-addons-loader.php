<?php
/**
 * aThemes_Addons_Loader Class.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'aThemes_Addons_Loader' ) ) {
	class aThemes_Addons_Loader {

		/**
		 * The single class instance.
		 */
		private static $instance = null;

		/**
		 * All registered widgets.
		 */
		private $widgets = array();

		/**
		 * All registered extensions.
		 */
		private $extensions = array();

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

			// Includes.
			$this->includes();

			// Get widgets.
			$this->widgets = athemes_addons_get_widgets();

			// Get extensions.
			$this->extensions = athemes_addons_get_extensions();

			// Register widget category.
			add_action( 'elementor/elements/categories_registered', array( $this, 'register_widget_category' ) );

			// Include widgets.
			add_action( 'elementor/widgets/register', array( $this, 'include_widgets' ) );

			// Include skins.
			add_action( 'elementor/init', array( $this, 'include_skins' ) );

			// Include extensions.
			add_action( 'elementor/init', array( $this, 'include_extensions' ) );

			// Enqueue scripts.
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles_scripts' ) );

			// Enqueue editor styles.
			add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'enqueue_editor_styles' ) );

			// Enqueue editor scripts.
			add_action( 'elementor/editor/after_enqueue_scripts', array( $this, 'enqueue_editor_scripts' ) );

			// Add identifier to body class.
			add_filter( 'body_class', array( $this, 'add_body_class' ) );

			// Print custom JS.
			add_action( 'wp_footer', array( $this, 'print_custom_js' ) );

			// Delete custom JS transient.
			add_action( 'elementor/editor/after_save', array( $this, 'delete_custom_js_transient' ) );

			// Add Elementor custom query control.
			add_action( 'elementor/controls/controls_registered', function( $controls_manager ) {
				require_once ATHEMES_AFE_DIR . 'inc/controls/class-query-control.php';

				$controls_manager->register_control( 'aafe-query', new aThemes_Addons\Controls\Query_Control() );
			} );
		}

		/**
		 * Include required classes.
		 */
		public function includes() {

			// Helper functions.
			require_once ATHEMES_AFE_DIR . 'inc/functions.php';

			// Ajax callbacks.
			require_once ATHEMES_AFE_DIR . 'inc/classes/class-athemes-addons-ajax-callbacks.php';

			// Core classes.
			require_once ATHEMES_AFE_DIR . 'inc/classes/class-athemes-addons-modules.php';

			// The main class for adding modules.
			require_once ATHEMES_AFE_DIR . 'inc/modules/class-add-module.php';

			// Posts helper.
			require_once ATHEMES_AFE_DIR . 'inc/classes/class-athemes-addons-posts-helper.php';

			// Helper class.
			require_once ATHEMES_AFE_DIR . 'inc/classes/class-athemes-addons-helper.php';

			// SVG icons.
			require_once ATHEMES_AFE_DIR . 'inc/classes/class-athemes-addons-svg-icons.php';

			// Theme Builder.
			if ( class_exists( 'Elementor\Plugin' ) ) {
				require_once ATHEMES_AFE_DIR . 'inc/theme-builder/class-athemes-addons-theme-builder.php';
			}

			// Traits.
			require_once ATHEMES_AFE_DIR . 'inc/traits/button-trait.php';

			/**
			 * Hook 'athemes_addons_admin_after_include_modules_classes'.
			 *
			 * @since 1.0
			 */
			do_action( 'athemes_addons_admin_after_include_modules_classes' );
		}

		/**
		 * Include widgets.
		 */
		public function include_widgets() {

			// Load and register active widgets.
			$widgets = $this->widgets;
			if ( ! empty( $widgets ) ) {
				foreach ( $widgets as $widget_id => $widget ) {
					if ( aThemes_Addons_Modules::is_module_active( $widget_id ) ) {

						$athemes_dir = defined('ATHEMES_AFE_PRO_DIR') && true === $widget['pro'] ? ATHEMES_AFE_PRO_DIR : ATHEMES_AFE_DIR;

						$widget_path = $athemes_dir . 'inc/modules/widgets/' . $widget_id . '/class-' . $widget_id . '.php';

						if ( file_exists( $widget_path ) ) {
							require_once $widget_path;

							if ( class_exists( $widget['class'] ) ) {
								Elementor\Plugin::instance()->widgets_manager->register( new $widget['class']() );
							}
						}
					}
				}
			}
		}

		/**
		 * Include skins.
		 */
		public function include_skins() {
			$widgets = $this->widgets;
			if ( !empty( $widgets ) ) {
				foreach ( $widgets as $widget_id => $widget ) {
					if ( aThemes_Addons_Modules::is_module_active( $widget_id ) && isset( $widget['has_skins'] ) && true === $widget['has_skins'] ) {
		
						$athemes_dir = defined('ATHEMES_AFE_PRO_DIR') && true === $widget['pro'] ? ATHEMES_AFE_PRO_DIR : ATHEMES_AFE_DIR;

						$skins_path = $athemes_dir . 'inc/modules/widgets/' . $widget_id . '/skins/';
						
						$files = scandir( $skins_path );
						
						if ( false !== $files ) {
							$php_files = array_filter($files, function ( $file ) {
								return pathinfo($file, PATHINFO_EXTENSION) === 'php';
							});
			
							if ( !empty( $php_files ) ) {
								foreach ( $php_files as $file ) {
									require_once $skins_path . $file;
								}
							}
						}
					}
				}
			}
		}

		/**
		 * Include extensions
		 */
		public function include_extensions() {
			$extensions = $this->extensions;

			if ( ! empty( $extensions ) ) {
				foreach ( $extensions as $extension_id => $extension ) {
					if ( aThemes_Addons_Modules::is_module_active( $extension_id ) ) {
						
						$athemes_dir = defined('ATHEMES_AFE_PRO_DIR') && true === $extension['pro'] ? ATHEMES_AFE_PRO_DIR : ATHEMES_AFE_DIR;

						$extension_path = $athemes_dir . 'inc/modules/extensions/' . $extension_id . '/class-' . $extension_id . '.php';

						if ( file_exists( $extension_path ) ) {
							require_once $extension_path;
						}
					}
				}
			}
		}

		/**
		 * Register widget category
		 */
		public function register_widget_category( $elements_manager ) {
			$elements_manager->add_category(
				'athemes-addons-elements',
				array(
					'title' => __( 'aThemes Addons', 'athemes-addons-elementor' ),
					'icon'  => 'eicon-star-o',
				)
			);
		}

		/**
		 * Add to body class.
		 */
		public function add_body_class( $classes ) {
			$theme = wp_get_theme();
			$theme = ( get_template_directory() !== get_stylesheet_directory() && $theme->parent() ) ? $theme->parent() : $theme;

			$classes[] = 'athemes-addons-theme-' . strtolower( esc_attr( $theme->name ) );

			return $classes;
		}

		/**
		 * Enqueue styles and scripts.
		 */
		public function enqueue_styles_scripts() {

			/**
			 * Hook 'athemes_addons_enqueue_before_main_css_js'
			 *
			 * @since 1.0
			 */
			do_action( 'athemes_addons_enqueue_before_main_css_js' );

			// Register shared styles.
			wp_register_style( 'athemes-addons-animations', ATHEMES_AFE_URI . 'assets/css/shared-styles/animations.min.css', array(), ATHEMES_AFE_VERSION );
			wp_register_style( 'athemes-addons-social-icons', ATHEMES_AFE_URI . 'assets/css/shared-styles/social-icons.min.css', array(), ATHEMES_AFE_VERSION );

			// Register isotope.
			wp_register_script( 'athemes-addons-isotope', ATHEMES_AFE_URI . 'assets/js/vendor/isotope.min.js', array( 'jquery' ), ATHEMES_AFE_VERSION, true );

			// Register styles for the active widgets.
			$widgets = $this->widgets;

			if ( ! empty( $widgets ) ) {
				foreach ( $widgets as $widget_id => $widget ) {
					if ( aThemes_Addons_Modules::is_module_active( $widget_id ) ) {
						$athemes_uri = defined('ATHEMES_AFE_PRO_URI') && true === $widget['pro'] ? ATHEMES_AFE_PRO_URI : ATHEMES_AFE_URI;

						// Register styles.
						if ( isset( $widget['has_styles'] ) && true === $widget['has_styles'] ) {
							wp_register_style( 'athemes-addons-' . $widget_id . '-styles', $athemes_uri . 'assets/css/modules/' . $widget_id . '/styles.min.css', array(), ATHEMES_AFE_VERSION );
						}

						// Register scripts.
						if ( isset( $widget['has_scripts'] ) && true === $widget['has_scripts'] ) {
							wp_register_script( 'athemes-addons-' . $widget_id . '-scripts', $athemes_uri . 'assets/js/modules/' . $widget_id . '/scripts.min.js', array( 'jquery' ), ATHEMES_AFE_VERSION, true );

							wp_localize_script(
								'athemes-addons-' . $widget_id . '-scripts',
								'AAFESettings',
								array(
									'ajaxurl'               => esc_url( admin_url( 'admin-ajax.php' ) ),
									'nonce'                 => wp_create_nonce( 'aafe-posts-widget-nonce' ),
									'nonce_mailchimp'       => wp_create_nonce( 'aafe-mailchimp-nonce' ),
									'nonce_product_filter'  => wp_create_nonce( 'aafe-product-filter-nonce' ),
									'view_event'            => esc_html__( 'View Event', 'athemes-addons-elementor' ),
									'search_text'           => esc_html__( 'Search', 'athemes-addons-elementor' ),
								)
							);
						}
					}
				}
			}

			/**
			 * Hook 'athemes_addons_enqueue_after_main_css_js'
			 *
			 * @since 1.0
			 */
			do_action( 'athemes_addons_enqueue_after_main_css_js' );
		}

		/**
		 * Enqueue Elementor editor styles.
		 */
		public function enqueue_editor_styles() {
			wp_enqueue_style( 'athemes-addons-elementor-editor', ATHEMES_AFE_URI . 'assets/css/admin/elementor-editor.min.css', array(), ATHEMES_AFE_VERSION );
		}

		/**
		 * Enqueue editor scripts
		 */
		public function enqueue_editor_scripts() {
			wp_enqueue_script( 'athemes-addons-elementor-editor', ATHEMES_AFE_URI . 'assets/js/admin/elementor-editor.min.js', array( 'jquery' ), ATHEMES_AFE_VERSION, true );

			wp_localize_script(
				'athemes-addons-elementor-editor',
				'AAFESettings',
				array(
					'ajaxurl' => esc_url( admin_url( 'admin-ajax.php' ) ),
					'nonce'   => wp_create_nonce( 'aafe-posts-widget-nonce' ),
				)
			);
		}

		/**
		 * Print custom JS
		 */
		public function print_custom_js() {
			$post_id = get_the_ID();

			$custom_js = get_transient( 'aafe_custom_js_' . $post_id );
		
			if ( false === $custom_js ) {
				$document = Elementor\Plugin::$instance->documents->get_doc_for_frontend($post_id);
		
				if ( $document ) {
					$custom_js = $document->get_settings('aafe_custom_js');
				}
		
				set_transient( 'aafe_custom_js_' . $post_id, $custom_js );
			}
		
			if ( !empty( $custom_js ) ) {
				echo '<script id="aafe-custom-js" type="text/javascript">' . wp_kses($custom_js, array()) . '</script>';
			}
		}

		/**
		 * Delete custom JS transient
		 */
		public function delete_custom_js_transient( $post_id ) {
			delete_transient( 'aafe_custom_js_' . $post_id );
		}
	}

	aThemes_Addons_Loader::instance();
}
