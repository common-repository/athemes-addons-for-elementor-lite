<?php

namespace aThemes_Addons\Extensions;

// Elementor classes
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Custom_JS {

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
				
		add_action('elementor/documents/register_controls',[ $this, 'register_controls' ], 10 );
	}
    
    public function register_controls( $element ) {
        
		$element->start_controls_section(
			'aafe_custom_js_section',
			[
                'tab'   => Controls_Manager::TAB_ADVANCED,
				'label' => '<i style="top:1px;" class="aafe-ele-svg-logo"></i>' . __( 'Custom JS', 'athemes-addons-elementor' ),
			]
        );
        
		$element->add_control(
			'aafe_custom_js',
			[
				'label' => __( 'Custom JS', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::CODE,
				'language' => 'javascript',
				'rows' => 30,
				'render_type' => 'ui',
			]
		);

        $element->end_controls_section();
	}
}

Custom_JS::get_instance();