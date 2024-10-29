<?php
/**
 * OceanWP Theme Builder Compatibility Class.
 */
namespace aThemesAddons;

use aThemesAddons\Theme_Builder;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'OceanWP_Theme_Builder_Compatibility' ) ) {
	class OceanWP_Theme_Builder_Compatibility {

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
		 * Header setup
		 */
		public function header_setup( $template_id ) {
			$header_type    = get_post_meta( $template_id, '_ahf_header_type', true );
			
			if ( 'sticky' !== $header_type ) { //already removed by the regular header
				//remove default header
				remove_action( 'ocean_top_bar', 'oceanwp_top_bar_template' );
				remove_action( 'ocean_header', 'oceanwp_header_template' );
				remove_action( 'ocean_page_header', 'oceanwp_page_header_template' );
			}

			//add our header
			add_action( 'ocean_header', function() use ( $template_id ) {
				Theme_Builder::instance()->header_content( $template_id );
			} );
		}

		/**
		 * Footer setup
		 */
		public function footer_setup( $template_id ) {
			//remove default footer
			remove_action( 'ocean_footer', 'oceanwp_footer_template' );

			//add our footer
			add_action( 'ocean_footer', function() use ( $template_id ) {
				Theme_Builder::instance()->footer_content( $template_id );
			} );
		}
	}
}