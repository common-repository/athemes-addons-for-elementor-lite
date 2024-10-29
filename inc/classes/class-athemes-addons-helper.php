<?php
/**
 * aThemes_Addons_Helper Class.
 */

use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'aThemes_Addons_Helper' ) ) {

	class aThemes_Addons_Helper {

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
		 * Get settings from an Elementor widget.
		 */
		public static function get_widget_settings( $page_id, $widget_id ) {
			$document = Plugin::$instance->documents->get( $page_id );
			$settings = [];
			if ( $document ) {
				$elements    = Plugin::instance()->documents->get( $page_id )->get_elements_data();
				$widget_data = self::find_element_recursive( $elements, $widget_id );
				
				if ( !empty( $widget_data ) && is_array( $widget_data ) ) {
					$widget      = Plugin::instance()->elements_manager->create_element_instance( $widget_data );
				}
				if ( !empty( $widget ) ) {
					$settings    = $widget->get_settings_for_display();
				}
			}

			return $settings;
		}

		/**
		 * Find an element in the elements data.
		 */
		public static function find_element_recursive( $elements, $element_id ) {
			foreach ( $elements as $element ) {
				if ( $element['id'] === $element_id ) {
					return $element;
				}

				if ( ! empty( $element['elements'] ) ) {
					$found = self::find_element_recursive( $element['elements'], $element_id );
					if ( $found ) {
						return $found;
					}
				}
			}

			return null;
		}
	}
}