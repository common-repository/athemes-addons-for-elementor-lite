<?php
/**
 * Blocksy Theme Builder Compatibility Class.
 */
namespace aThemesAddons;

use aThemesAddons\Theme_Builder;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Blocksy_Theme_Builder_Compatibility' ) ) {
	class Blocksy_Theme_Builder_Compatibility {

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
			//remove default header
			add_filter( 'blocksy:builder:header:enabled', '__return_false' );

			//add our header
			add_action( 'blocksy:header:before', function() use ( $template_id ) {
				Theme_Builder::instance()->header_content( $template_id );
			} );
		}

		/**
		 * Footer setup
		 */
		public function footer_setup( $template_id ) {
			//remove default footer
			add_filter( 'blocksy:builder:footer:enabled', '__return_false' );

			//add our footer
			add_action( 'blocksy:builder:footer:enabled', function() use ( $template_id ) {
				Theme_Builder::instance()->footer_content( $template_id );
			} );
		}
	}
}