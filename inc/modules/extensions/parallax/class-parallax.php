<?php

namespace aThemes_Addons\Extensions;

// Elementor classes
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Parallax {

	/**
	 * Instance
	 */     
	private static $instance;

	/**
	 * Assets
	 */
	private $load_bg_assets = null;
	private $load_ml_assets = null;

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
		//enqueue scripts
		add_action( 'elementor/preview/enqueue_scripts', [ $this, 'enqueue_bg_parallax_scripts' ] );
		add_action( 'elementor/preview/enqueue_scripts', [ $this, 'enqueue_ml_parallax_scripts' ] );
		
		//register controls
		add_action( 'elementor/element/section/section_advanced/after_section_end', [ $this, 'register_controls' ], 10, 2 );
		add_action( 'elementor/element/container/section_layout/after_section_end', [ $this, 'register_controls' ], 10, 2 );
		
		//background parallax
		add_action( 'elementor/frontend/section/before_render', [ $this, 'check_bg_parallax_script_enqueue' ] );
		add_action( 'elementor/frontend/container/before_render', [ $this, 'check_bg_parallax_script_enqueue' ] );

		//multilayer parallax
		add_action( 'elementor/frontend/section/before_render', [ $this, 'check_ml_parallax_script_enqueue' ] );
		add_action( 'elementor/frontend/container/before_render', [ $this, 'check_ml_parallax_script_enqueue' ] );

		//add parallax speed as data attribute
		add_action( 'elementor/frontend/section/before_render', [ $this, 'add_data_attributes' ], 10, 1 );
		add_action( 'elementor/frontend/container/before_render', [ $this, 'add_data_attributes' ], 10, 1 );

		//after render
		add_action( 'elementor/frontend/section/after_render', [ $this, 'render_parallax_layers' ] );
		add_action( 'elementor/frontend/container/after_render', [ $this, 'render_parallax_layers' ] );
		
		add_action( 'elementor/container/print_template', array( $this, '_print_template' ), 10, 2 );
		add_action( 'elementor/section/print_template', array( $this, '_print_template' ), 10, 2 );
	}

	public function enqueue_bg_parallax_scripts() {
		wp_enqueue_script( 'jarallax', ATHEMES_AFE_URI . 'assets/js/vendor/jarallax.min.js', [ 'jquery' ], ATHEMES_AFE_VERSION, true );
		wp_enqueue_script( 'athemes-addons-parallax', ATHEMES_AFE_URI . 'assets/js/modules/parallax/scripts.min.js', [ 'jquery', 'jarallax' ], ATHEMES_AFE_VERSION, true );
	}

	public function enqueue_ml_parallax_scripts() {
		wp_enqueue_script( 'athemes-addons-parallax-lib', ATHEMES_AFE_URI . 'assets/js/vendor/parallax.min.js', [ 'jquery' ], ATHEMES_AFE_VERSION, true );
		wp_enqueue_script( 'athemes-addons-parallax', ATHEMES_AFE_URI . 'assets/js/modules/parallax/scripts.min.js', [ 'jquery' ], ATHEMES_AFE_VERSION, true );
		wp_enqueue_style( 'athemes-addons-parallax', ATHEMES_AFE_URI . 'assets/css/modules/parallax/styles.min.css', [], ATHEMES_AFE_VERSION );
	}
    
    public function register_controls( $element ) {

		$element->start_controls_section(
			'aafe_parallax_section',
			[
                'tab'   => Controls_Manager::TAB_ADVANCED,
				'label' => '<i style="top:1px;" class="aafe-ele-svg-logo"></i>' . __( 'Parallax', 'athemes-addons-elementor' ),
			]
        );
	
		$element->add_control(
			'bg_parallax_refresh_preview',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => '<div class="aafe-update-preview" style="display: flex;align-items:center;">
					<div class="elementor-update-preview-title">' . __( 'Update changes', 'athemes-addons-elementor' ) . '</div>
					<div class="elementor-update-preview-button-wrapper">
						<button class="elementor-update-preview-button elementor-button">' . __( 'Apply', 'athemes-addons-elementor' ) . '</button>
					</div>
				</div>',
				'separator' => 'after',
			]
		);
        
		$element->add_control(
			'enable_background_parallax',
			[
				'label'        => __( 'Enable Background Parallax', 'athemes-addons-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'athemes-addons-elementor' ),
				'label_off'    => __( 'No', 'athemes-addons-elementor' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'prefix_class' => 'aafe-bg-parallax-',
				'render_type'  => 'template',
			]
		);

		$element->add_control(
			'background_parallax_speed',
			[
				'label'     => __( 'Parallax Speed', 'athemes-addons-elementor' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 0.5,
				'min'       => -1,
				'max'       => 2,
				'step'      => 0.1,
				'condition' => [
					'enable_background_parallax' => 'yes',
				],
				'render_type'  => 'template',
			]
		);

		$element->add_control(
			'background_parallax_disable_mobile',
			[
				'label'        => __( 'Disable on Mobile', 'athemes-addons-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'athemes-addons-elementor' ),
				'label_off'    => __( 'No', 'athemes-addons-elementor' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'condition'    => [
					'enable_background_parallax' => 'yes',
				],
				'render_type'  => 'template',
			]
		);

		$element->add_control(
			'enable_multilayer_parallax',
			[
				'label'        => __( 'Enable Multilayer Parallax', 'athemes-addons-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'athemes-addons-elementor' ),
				'label_off'    => __( 'No', 'athemes-addons-elementor' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'prefix_class' => 'aafe-ml-parallax-',
				'render_type'  => 'template',
				'separator'    => 'before',
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'effect',
			[
				'label'   => __( 'Effect', 'athemes-addons-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'move',
				'options' => [
					'move'      => __( 'Mouse Move', 'athemes-addons-elementor' ),
					'scroll'    => __( 'Scroll', 'athemes-addons-elementor' ),
					'tilt'      => __( 'Tilt', 'athemes-addons-elementor' ),
				],
			]
		);

		$repeater->add_control(
            'ml_image',
			[
                'label' => esc_html__('Choose Image', 'athemes-addons-elementor'),
                'type' => Controls_Manager::MEDIA,
            ]
        );


		$repeater->add_control(
			'speed',
			[
				'label'   => __( 'Speed', 'athemes-addons-elementor' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 30,
				'min'     => 0,
				'max'     => 100,
				'step'    => 1,
			]
		);

		$repeater->add_control(
			'ml_rotate',
			[
				'label'   => __( 'Rotate', 'athemes-addons-elementor' ),
				'type'    => Controls_Manager::SLIDER,
				'range'   => [
					'px' => [
						'min'  => -360,
						'max'  => 360,
						'step' => 1,
					],
				],
				'default' => [
					'size' => 0,
				],
			]
		);
		
		$repeater->add_control(
			'ml_position_x',
			[
				'label'   => __( 'Position X', 'athemes-addons-elementor' ),
				'type'    => Controls_Manager::SLIDER,
				'range'   => [
					'px' => [
						'min'  => -100,
						'max'  => 100,
						'step' => 1,
					],
				],
			]
		);
		
		$repeater->add_control(
			'ml_position_y',
			[
				'label'   => __( 'Position Y', 'athemes-addons-elementor' ),
				'type'    => Controls_Manager::SLIDER,
				'range'   => [
					'px' => [
						'min'  => -100,
						'max'  => 100,
						'step' => 1,
					],
				],
			]
		);
		
		$repeater->add_control(
			'ml_opacity',
			[
				'label'   => __( 'Opacity', 'athemes-addons-elementor' ),
				'type'    => Controls_Manager::SLIDER,
				'range'   => [
					'px' => [
						'min'  => 0,
						'max'  => 1,
						'step' => 0.01,
					],
				],
				'default' => [
					'size' => 1,
				],
			]
		);
		
		$repeater->add_control(
			'ml_size',
			[
				'label'   => __( 'Size', 'athemes-addons-elementor' ),
				'type'    => Controls_Manager::SLIDER,
				'range'   => [
					'px' => [
						'min'  => 10,
						'max'  => 1000,
						'step' => 10,
					],
				],
				'default' => [
					'size' => 100,
				],
			]
		);
		
		$repeater->add_control(
			'depth',
			[
				'label'   => __( 'Depth', 'athemes-addons-elementor' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 0,
				'min'     => -100,
				'max'     => 100,
				'step'    => 1,
			]
		);

		$element->add_control(
			'ml_layers',
			[
				'label'       => __( 'Layers', 'athemes-addons-elementor' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ effect }}}',
				'condition'   => [
					'enable_multilayer_parallax' => 'yes',
				],
				'render_type'  => 'template',
			]
		);

        $element->end_controls_section();
	}

	/**
	 * Add data attributes
	 */
	public function add_data_attributes( $element ) {

		$parallax_speed = $element->get_settings( 'background_parallax_speed' );
		$disable_mobile = $element->get_settings( 'background_parallax_disable_mobile' );

		if ( 'yes' === $element->get_settings( 'enable_background_parallax' ) ) {
			$element->add_render_attribute( '_wrapper', 'data-parallax-speed', esc_attr( $parallax_speed ) );
			$element->add_render_attribute( '_wrapper', 'data-parallax-disable-mobile', esc_attr( $disable_mobile ) );      
		}

		if ( 'yes' === $element->get_settings( 'enable_multilayer_parallax' ) ) {
			$element->add_render_attribute( '_wrapper', 'data-multilayer-parallax', 'true' );
		}
	}

	/**
	 * Check if scripts needs to be enqueued
	 */
	public function check_bg_parallax_script_enqueue( $element ) {

		if ( $this->load_bg_assets ) {
			return;
		}

		if ( 'yes' === $element->get_settings_for_display( 'enable_background_parallax' ) ) {

			$this->enqueue_bg_parallax_scripts();

			$this->load_bg_assets = true;

			remove_action( 'elementor/frontend/section/before_render', [ $this, 'check_bg_parallax_script_enqueue' ] );
			remove_action( 'elementor/frontend/container/before_render', [ $this, 'check_bg_parallax_script_enqueue' ] );

		}
	}

	/**
	 * Check if scripts needs to be enqueued
	 */
	public function check_ml_parallax_script_enqueue( $element ) {
		
		if ( $this->load_ml_assets ) {
			return;
		}

		if ( 'yes' === $element->get_settings_for_display( 'enable_multilayer_parallax' ) ) {

			$this->enqueue_ml_parallax_scripts();

			$this->load_ml_assets = true;

			remove_action( 'elementor/frontend/section/before_render', [ $this, 'check_ml_parallax_script_enqueue' ] );
			remove_action( 'elementor/frontend/container/before_render', [ $this, 'check_ml_parallax_script_enqueue' ] );

		}
	}

	public function _print_template( $template, $widget ) {
		if ( $widget->get_name() === 'widget' ) {
			return $template;
		}

		$old_template = $template;
		
		ob_start();
		?>
		<# if( 'yes' === settings.enable_multilayer_parallax ) {
			#>
			<# _.each( settings.ml_layers, function( layer, index ) { #>
				<div class="aafe-ml-parallax-layer elementor-repeater-item-{{ index }}" data-parallax-settings="{{ JSON.stringify( layer ) }}" 
				style="background-image: url({{ layer.ml_image.url }});
				opacity: {{layer.ml_opacity.size}};
				transform: rotate({{layer.ml_rotate.size}}deg);
				z-index: {{layer.depth}};
				size: {{layer.ml_size.size}}px;
				top: {{layer.ml_position_y.size}}%;
				left:{{layer.ml_position_x.size}}%;">
				</div>
			<# }); #>
			<# } #>
		<?php
		$content = ob_get_contents();
		ob_end_clean();
		$template = $content . $old_template;
		return $template;
	}

	/**
	 * After render
	 */
	public function render_parallax_layers( $element ) {
		$settings = $element->get_settings_for_display();

		if ( 'yes' !== $settings['enable_multilayer_parallax'] || empty( $settings['ml_layers'] ) ) {
			return;
		}

		$unique_id = 'aafe-multilayer-parallax-' . $element->get_id();

		foreach ( $settings['ml_layers'] as $index => $layer ) {
			$layer_unique_id = $unique_id . '-layer-' . $index;
			$image_url = $layer['ml_image']['url'];
	
			if ( empty( $image_url ) ) {
				continue;
			}
	
			$layer_settings = [
				'effect'                => esc_attr( $layer['effect'] ),
				'speed'                 => floatval( $layer['speed'] ),
				'depth'                 => intval( $layer['depth'] ),
			];
	
			$element->add_render_attribute( $layer_unique_id, [
				'id'                        => $layer_unique_id,
				'class'                     => 'aafe-ml-parallax-layer elementor-repeater-item-' . $index,
				'data-parallax-settings'    => wp_json_encode( $layer_settings ),
				'style'                     => '--size:' . esc_attr( $layer['ml_size']['size'] ) . 'px;background-image: url(' . esc_url( $image_url ) . '); top: ' . esc_attr( $layer['ml_position_y']['size'] ) . '%;left: ' . esc_attr( $layer['ml_position_x']['size'] ) . '%;opacity: ' . esc_attr( $layer['ml_opacity']['size'] ) . ';transform: rotate(' . esc_attr( $layer['ml_rotate']['size'] ) . 'deg);z-index: ' . esc_attr( $layer['depth'] ) . ';',
			] );
	
			?>

			<script>
				(function( $ ) {
					$( document ).ready( function() {
						var section = $( '.elementor-element-<?php echo esc_attr( $element->get_id() ); ?>' );

						if ( ! section.length ) {
							return;
						}					

						$( '<div <?php $element->print_render_attribute_string( $layer_unique_id ); ?>></div>' ).appendTo( section );
					});
				})( jQuery );
			</script>
			<?php
		}
	}
}

Parallax::get_instance();