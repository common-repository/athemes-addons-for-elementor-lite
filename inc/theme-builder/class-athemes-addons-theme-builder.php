<?php
/**
 * Admin_Options Class.
 */
namespace aThemesAddons;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Theme_Builder' ) ) {
	class Theme_Builder {

		/**
		 * The single class instance.
		 */
		private static $instance = null;

		/**
		 * Theme.
		 */
		private $theme;

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

			// Theme
			$this->theme = get_template();

			// Enqueue scripts
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

			// Include files
			add_action( 'init', array( $this, 'includes' ) );

			//Render header & footer
			if ( !is_admin() ) {
				if ( $this->theme_is_supported() ) {
					add_action( 'wp', array( $this, 'render_header' ) );
					add_action( 'wp', array( $this, 'render_sticky_header' ) );
					add_action( 'wp', array( $this, 'render_footer' ), 999 );
				} elseif ( 'css' === $this->render_mode() ) {
						add_action( 'wp', array( $this, 'render_header' ) );
						add_action( 'wp', array( $this, 'render_sticky_header' ) );
						add_action( 'wp_footer', array( $this, 'render_footer' ) );
					} else {
						add_action( 'get_header', array( $this, 'render_header' ) );
						add_action( 'get_footer', array( $this, 'render_footer' ) );
						add_action( 'athemes_addons_do_header', array( $this, 'header_content' ), 10, 1 );
						add_action( 'athemes_addons_do_footer', array( $this, 'footer_content' ), 10, 1 );
				}
			}

			//Render singular
			add_action( 'wp', array( $this, 'render_singular' ) );

			//Render archive
			add_action( 'wp', array( $this, 'render_archive' ) );

			//Render product archives
			add_action( 'wp', array( $this, 'render_product_archive' ) );

			//Render single product
			add_action( 'wp', array( $this, 'render_single_product' ) );
			
			// Include theme builder CPT.
			add_action( 'plugins_loaded', array( $this, 'include_theme_builder_cpt' ) );
			
			// Load the canvas template instead of the default one
			add_action( 'single_template', array( $this, 'load_canvas_template' ) );

			// Redirect single template view to home
			add_action( 'template_redirect', array( $this, 'redirect_single_template' ) );      

			// Print styles
			add_action( 'wp_head', array( $this, 'add_inline_style' ) );
		}

		/**
		 * Enqueue scripts
		 */
		public function enqueue_scripts() {
			if ( !defined( 'ATHEMES_AFE_PRO_VERSION' ) ) {
				return;
			}
			
			// Register theme builder scripts
			wp_enqueue_script( 'athemes-addons-theme-builder', ATHEMES_AFE_PRO_URI . 'assets/js/theme-builder/theme-builder.min.js', array( 'jquery' ), ATHEMES_AFE_PRO_VERSION, true );
			
			// Register theme builder styles
			wp_register_style( 'athemes-addons-theme-builder', ATHEMES_AFE_PRO_URI . 'assets/css/theme-builder/theme-builder.min.css', array(), ATHEMES_AFE_PRO_VERSION );

			// Register site navigation styles
			wp_register_style( 'athemes-addons-theme-builder-navigation', ATHEMES_AFE_PRO_URI . 'assets/css/theme-builder/theme-builder-nav.min.css', array(), ATHEMES_AFE_PRO_VERSION );

			// Register woo styles
			wp_register_style( 'athemes-addons-theme-builder-woo', ATHEMES_AFE_PRO_URI . 'assets/css/theme-builder/theme-builder-woo.min.css', array(), ATHEMES_AFE_PRO_VERSION );
		}

		/**
		 * Include files
		 */
		public function includes() {
			require_once ATHEMES_AFE_DIR . 'inc/theme-builder/display-conditions/display-conditions.php';

			// Include theme builder compatibility
			if ( $this->theme_is_supported() ) {
				$active_theme = $this->theme;

				if ( file_exists( ATHEMES_AFE_DIR . 'inc/theme-builder/compatibility/class-' . $active_theme . '-theme-builder-compatibility.php' ) ) {
					require ATHEMES_AFE_DIR . 'inc/theme-builder/compatibility/class-' . $active_theme . '-theme-builder-compatibility.php';
				}
			}
		}
		
		/**
		 * Include theme builder CPT.
		 */
		public function include_theme_builder_cpt() {
			if ( defined( 'ATHEMES_AFE_PRO_VERSION' ) ) {
				require_once ATHEMES_AFE_DIR . 'inc/theme-builder/class-athemes-addons-theme-builder-cpt.php';
			}
		}

		/**
		 * Determine the render mode: CSS or file replacement
		 */
		public function render_mode() {

			$render_mode = apply_filters( 'athemes_addons_theme_builder_render_mode', 'css' );

			return $render_mode;
		}

		/**
		 * Render header
		 */
		public function render_header() {
			//Return if is elementor preview
			if ( \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
				return;
			}

			$template_id = athemes_addons_templates_display_conditions( 'header' );

			if ( $template_id && $this->is_built_with_elementor( $template_id ) ) {

				// Check if is supported theme
				if ( $this->theme_is_supported() ) {
					$active_theme = $this->theme;


					if ( 'sydney-pro-ii' === $active_theme ) {
						$active_theme = 'sydney'; //load sydney compatibility for sydney pro as well
					}

					$class = 'aThemesAddons\\' . ucfirst( $active_theme ) . '_Theme_Builder_Compatibility';

					//If class exists, call the header_setup method
					if ( class_exists( $class ) ) {
						$class::instance()->header_setup( $template_id );
					}
					
				} elseif ( 'css' === $this->render_mode() ) {
						$this->add_inline_style( 'header#masthead,.site > header:not(.athemes-addons-custom-header),header.header, body > div > header,body > header:not(.athemes-addons-custom-header),.site > .header { display: none; }' ); // Hide default header
						$this->header_content( $template_id );
					} else {
						require ATHEMES_AFE_DIR . 'inc/theme-builder/templates/header.php';
						$templates   = array();
						$templates[] = 'header.php';
						remove_all_actions( 'wp_head' );
						ob_start();
						locate_template( $templates, true );
						ob_get_clean();
				}
			}
		}

		/**
		 * Render sticky header
		 */
		public function render_sticky_header() {
			//Return if is elementor preview
			if ( \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
				return;
			}

			$template_id = athemes_addons_templates_display_conditions( 'sticky-header' );

			if ( $template_id && $this->is_built_with_elementor( $template_id ) ) {
				if ( $this->theme_is_supported() ) {
					$active_theme = $this->theme;

					if ( 'sydney-pro-ii' === $active_theme ) {
						$active_theme = 'sydney'; //load sydney compatibility for sydney pro as well
					}

					$class = 'aThemesAddons\\' . ucfirst( $active_theme ) . '_Theme_Builder_Compatibility';

					//If class exists, call the header_setup method
					if ( class_exists( $class ) ) {
						$class::instance()->header_setup( $template_id );
					}
					
				} elseif ( 'css' === $this->render_mode() ) {
					$this->header_content( $template_id );
				}
			}
		}

		/**
		 * Render footer
		 */
		public function render_footer() {
			//Return if is elementor preview
			if ( \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
				return;
			}

			$template_id = athemes_addons_templates_display_conditions( 'footer' );

			if ( $template_id && $this->is_built_with_elementor( $template_id ) ) {
				if ( $this->theme_is_supported() ) {
					$active_theme = $this->theme;

					if ( 'sydney-pro-ii' === $active_theme ) {
						$active_theme = 'sydney'; //load sydney compatibility for sydney pro as well
					}

					$class = 'aThemesAddons\\' . ucfirst( $active_theme ) . '_Theme_Builder_Compatibility';

					//If class exists, call the footer_setup method
					if ( class_exists( $class ) ) {
						$class::instance()->footer_setup( $template_id );
					}
				} elseif ( 'css' === $this->render_mode() ) {
						$this->add_inline_style( 'body > footer:not(.athemes-addons-custom-footer),footer#colophon, .site > footer:not(.athemes-addons-custom-footer), footer.footer, body > div > footer { display: none; }' ); // Hide default footer
						$this->footer_content( $template_id );
					} else {
						require ATHEMES_AFE_DIR . 'inc/theme-builder/templates/footer.php';
						$templates   = array();
						$templates[] = 'footer.php';
						remove_all_actions( 'wp_footer' );
						ob_start();
						locate_template( $templates, true );
						ob_get_clean();
				}
			}
		}

		/**
		 * Render singular
		 */
		public function render_singular() {

			//Return if is elementor preview
			if ( \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
				return;
			}

			$template_id = athemes_addons_templates_display_conditions( 'singular' );

			if ( $template_id && $this->is_built_with_elementor( $template_id ) ) {

				$types = array( 'single', 'page', 'singular', '404' );
				foreach ( $types as $type ) {
					add_filter( "{$type}_template", array( $this, 'add_canvas_template' ) );
				}

				//Add the custom content
                add_action( 'athemes_addons_do_content', function() use ( $template_id ) {
                    $this->do_content( $template_id );
                }, 10, 1 );
			}
		}

		/**
		 * Render archive
		 */
		public function render_archive() {
			$template_id = athemes_addons_templates_display_conditions( 'archive' );

			if ( $template_id && $this->is_built_with_elementor( $template_id ) ) {
				
				// Filter all possible archive templates
				$types = array( 'home', 'archive', 'search', 'category', 'tag', 'author', 'date', 'taxonomy' );
				foreach ( $types as $type ) {
					add_filter( "{$type}_template", array( $this, 'add_canvas_template' ) ); 
				}

				//Add the custom content
				add_action( 'athemes_addons_do_content', function() use ( $template_id ) {
					$this->do_content( $template_id, 'archive' );
				}, 10, 1 );
			}
		}

		/**
		 * Render product archives
		 */
		public function render_product_archive() {
			
			$template_id = athemes_addons_templates_display_conditions( 'shop' );

			if ( $template_id && $this->is_built_with_elementor( $template_id ) ) {
				add_action( 'template_include', array( $this, 'add_woo_canvas_template' ) );

				//Add the custom content
				add_action( 'athemes_addons_do_content', function() use ( $template_id ) {
					$this->do_content( $template_id, 'product-archive' );
				}, 10, 1 );
			}
		}

		/**
		 * Render single product
		 */
		public function render_single_product() {
			$template_id = athemes_addons_templates_display_conditions( 'product' );

			if ( $template_id && $this->is_built_with_elementor( $template_id ) ) {
				add_action( 'template_include', array( $this, 'add_canvas_template' ) );
				
				//Add the custom content
				add_action( 'athemes_addons_do_content', function() use ( $template_id ) {
					$this->do_content( $template_id, 'single-product' );
				}, 10, 1 );
			}
		}

		/**
		 * Add blank canvas template
		 */
		public function add_canvas_template( $template ) {
			if ( file_exists( ATHEMES_AFE_DIR . 'inc/theme-builder/templates/canvas.php' ) ) {
				$template = ATHEMES_AFE_DIR . 'inc/theme-builder/templates/canvas.php';

				locate_template( $template, true );
			}

			return $template;
		}

		/**
		 * Add woo canvas template
		 */
		public function add_woo_canvas_template( $template ) {
			if ( file_exists( ATHEMES_AFE_DIR . 'inc/theme-builder/templates/woo.php' ) ) {
				$template = ATHEMES_AFE_DIR . 'inc/theme-builder/templates/woo.php';

				locate_template( $template, true );
			}

			return $template;
		}

		/**
		 * Header content
		 */
		public function header_content( $template_id ) {
			$header_type    = get_post_meta( $template_id, '_ahf_header_type', true );

			ob_start();
			echo '<header class="athemes-addons-custom-header" data-header-type="' . esc_attr( $header_type ) . '" itemscope="itemscope" itemtype="https://schema.org/WPHeader">' . \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $template_id ) . '</header>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			$content = ob_get_clean();

			echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		/**
		 * Footer content
		 */
		public function footer_content( $template_id ) {
			ob_start();
			echo '<footer class="athemes-addons-custom-footer" itemscope="itemscope" itemtype="https://schema.org/WPFooter">' . \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $template_id ) . '</footer>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			$content = ob_get_clean();

			echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		/**
		 * Singular content
		 */
		public function do_content( $template_id, $type = 'singular' ) {
			ob_start();
			echo '<div class="athemes-addons-custom-' . esc_attr( $type ) . '" itemscope="itemscope" itemtype="https://schema.org/WebPageElement">' . \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $template_id ) . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			$content = ob_get_clean();

			echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		/**
		 * Get styles
		 */
		public function add_inline_style( $styles ) {
			if ( empty( $styles ) ) {
				return;
			}

			echo '<style>' . $styles . '</style>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		/**
		 * Load Elementor canvas template
		 */
		function load_canvas_template( $single_template ) {

			if ( !class_exists( 'Elementor\Plugin' ) ) {
				return $single_template;
			}

			global $post;

			if ( 'aafe_templates' == $post->post_type ) { // phpcs:ignore Universal.Operators.StrictComparisons.LooseEqual
				$elementor_2_0_canvas = ELEMENTOR_PATH . '/modules/page-templates/templates/canvas.php';

				if ( file_exists( $elementor_2_0_canvas ) ) {
					return $elementor_2_0_canvas;
				} else {
					return ELEMENTOR_PATH . '/includes/page-templates/canvas.php';
				}
			}

			return $single_template;
		}

		/**
		 * Redirect single template view to home
		 */
		public function redirect_single_template() {
			if ( is_singular( 'aafe_templates' ) && ! current_user_can( 'edit_posts' ) ) {
				wp_safe_redirect( site_url(), 301 );
				die;
			}
		}

		/**
		 * Check if template is built with Elementor
		 */
		public function is_built_with_elementor( $id ) {
			if ( class_exists( 'Elementor\Plugin' ) && \Elementor\Plugin::$instance->documents->get( $id )->is_built_with_elementor() ) {
				return true;
			} 

			return false;
		}

		/**
		 * Check if theme is supported
		 */
		public function theme_is_supported() {
			$active_theme = $this->theme;

			if ( in_array( $active_theme, array( 'sydney', 'botiga', 'astra', 'generatepress', 'oceanwp', 'neve', 'blocksy', 'kadence' ), true ) ) {
				return true;
			}

			return false;
		}
	}

	Theme_Builder::instance();
}