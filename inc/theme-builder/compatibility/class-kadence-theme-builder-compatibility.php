<?php
/**
 * Kadence Theme Builder Compatibility Class.
 */
namespace aThemesAddons;

use aThemesAddons\Theme_Builder;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Kadence_Theme_Builder_Compatibility' ) ) {
	class Kadence_Theme_Builder_Compatibility {

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
				remove_all_actions( 'kadence_header' );
			}

			//add our header
			add_action( 'kadence_header', function() use ( $template_id ) {
				Theme_Builder::instance()->header_content( $template_id );
			} );
		}

		/**
		 * Footer setup
		 */
		public function footer_setup( $template_id ) {
			//remove default footer
			remove_all_actions( 'kadence_footer' );
			
			//add our footer
			add_action( 'kadence_footer', function() use ( $template_id ) {
				Theme_Builder::instance()->footer_content( $template_id );
			} );
		}
	}
}