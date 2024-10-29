<?php

namespace aThemes_Addons\Extensions;

// Elementor classes
use Elementor\Controls_Manager;
use Elementor\Core\DynamicTags\Dynamic_CSS;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Custom_CSS {

	/**
	 * Instance
	 */     
	private static $instance;

	/**
	 * Initiator
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}   

    public final function __construct() {
		
		add_action( 'elementor/element/after_section_end', [ $this, 'register_controls' ], 10, 3 );
		
		add_action( 'elementor/element/parse_css', [ $this, 'add_custom_css' ], 10, 2 );

		add_action( 'elementor/element/after_section_end', [ $this, 'enqueue_scripts' ] );
	}

	public function enqueue_scripts() {
		wp_enqueue_script( 'athemes-addons-custom-css', ATHEMES_AFE_URI . 'assets/js/modules/custom-css/scripts.min.js', [ 'jquery' ], ATHEMES_AFE_VERSION, true );
	}
    
    public function register_controls( $element, $section_id ) {

		if ( '_section_responsive' !== $section_id ) {
			return;
        }
        
		$element->start_controls_section(
			'aafe_custom_css_section',
			[
                'tab'   => Controls_Manager::TAB_ADVANCED,
				'label' => '<i style="top:1px;" class="aafe-ele-svg-logo"></i>' . __( 'Custom CSS', 'athemes-addons-elementor' ),
			]
        );
        
			$element->add_control(
				'aafe_custom_css',
				[
					'label' => __( 'Custom CSS', 'athemes-addons-elementor' ),
					'type' => Controls_Manager::CODE,
					'language' => 'css',
					'rows' => 30,
					'render_type' => 'ui',
				]
			);

			$element->add_control(
				'aafe_custom_css_help',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw' => __( '<p>Example:</p><br>
					<kbd> selector {<br> &nbsp;&nbsp;&nbsp;&nbsp;background-color: red; <br>} </kbd>', 'athemes-addons-elementor' ),
				]
			);

        $element->end_controls_section();
	}

	public function add_custom_css( $post_css, $element ) {

		if ( $post_css instanceof Dynamic_CSS ) {
			return;
		}

		$settings = $element->get_settings_for_display();

		if ( empty( $settings['aafe_custom_css'] ) ) {
			return;
		}
		
		$customCss = trim( $settings['aafe_custom_css'] );

		$customCss = str_replace( 'selector', $post_css->get_element_unique_selector( $element ), $customCss );

		// Queueing CSS
		$post_css->get_stylesheet()->add_raw_css( $customCss );
	}
}

Custom_CSS::get_instance();