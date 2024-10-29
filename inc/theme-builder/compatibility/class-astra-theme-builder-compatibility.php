<?php
/**
 * Astra Theme Builder Compatibility Class.
 */
namespace aThemesAddons;

use aThemesAddons\Theme_Builder;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Astra_Theme_Builder_Compatibility' ) ) {
	class Astra_Theme_Builder_Compatibility {

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
				remove_action( 'astra_header', 'astra_header_markup' );
			}

			if ( class_exists( 'Astra_Builder_Helper' ) && \Astra_Builder_Helper::$is_header_footer_builder_active ) {
				remove_action( 'astra_header', [ \Astra_Builder_Header::get_instance(), 'prepare_header_builder_markup' ] );
			}            

			//add our header
			add_action( 'astra_header', function() use ( $template_id ) {
				Theme_Builder::instance()->header_content( $template_id );
			} );
		}

		/**
		 * Footer setup
		 */
		public function footer_setup( $template_id ) {
			//remove default footer
			remove_action( 'astra_footer', 'astra_footer_markup' );

			if ( class_exists( 'Astra_Builder_Helper' ) && \Astra_Builder_Helper::$is_header_footer_builder_active ) {
				remove_action( 'astra_footer', [ \Astra_Builder_Footer::get_instance(), 'footer_markup' ] );
			}

			//add our footer
			add_action( 'astra_footer', function() use ( $template_id ) {
				Theme_Builder::instance()->footer_content( $template_id );
			} );
		}
	}
}