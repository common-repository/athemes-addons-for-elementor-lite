<?php
namespace aThemes_Addons\Widgets;

use Elementor\Plugin;
use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Group_Control_Image_Size;
use Elementor\Icons_Manager;
use Elementor\Control_Media;
use Elementor\Group_Control_Css_Filter;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Lottie animation Widget.
 *
 * @since 1.0.0
 */
class Lottie extends Widget_Base {

	public function __construct($data = [], $args = null) {
		parent::__construct($data, $args);

		wp_register_script( 'athemes-addons-lottie-js', ATHEMES_AFE_URI . 'assets/js/vendor/lottie.min.js', array( 'elementor-frontend' ), ATHEMES_AFE_VERSION, true );
	}

	/**
	 * Get widget name.
	 *
	 * Retrieve widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'athemes-addons-lottie';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Lottie', 'athemes-addons-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-lottie aafe-elementor-icon';
	}

	/**
	 * Enqueue scripts.
	 */
	public function get_script_depends() {
		return [ 'athemes-addons-lottie-js', $this->get_name() . '-scripts' ];
	}   

	/**
	 * Get widget keywords.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'lottie', 'animation', 'athemes', 'addons', 'athemes addons' ];
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'athemes-addons-elements' ];
	}

	/**
	 * Register widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_source',
			[
				'label' => __( 'Lottie file', 'athemes-addons-elementor' ),
			]
		);

		$this->add_control(
			'source_type',
			[
				'label' => __( 'Source Type', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'url',
				'options' => [
					'library'   => __( 'Media Library', 'athemes-addons-elementor' ),
					'url'       => __( 'URL', 'athemes-addons-elementor' ),
				],
			]
		);

		$this->add_control(
			'json_url',
			[
				'label'       => __( 'JSON URL', 'athemes-addons-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'https://lottie.host/b1c5822f-b67f-4161-a3b4-1add55a317c1/sVz6yzXAwE.json',
				/* translators: %1$s: <a> tag open, %2$s: <a> tag close */
				'description' => sprintf( __( 'Enter the URL of your Lottie JSON file. Find Lottie animations %1$shere%2$s.', 'athemes-addons-elementor' ),'<a href="https://lottiefiles.com/" target="_blank">','</a>' ),
				'label_block' => true,
				'condition'   => [
					'source_type' => 'url',
				],
			]
		);

		$this->add_control(
			'json_id',
			[
				'label'       => __( 'JSON File', 'athemes-addons-elementor' ),
				'type'        => Controls_Manager::MEDIA,
				'media_type'  => 'application/json',
				/* translators: %1$s: <a> tag open, %2$s: <a> tag close */
				'description' => sprintf( __( 'Upload your Lottie JSON file. Find Lottie animations %1$shere%2$s.', 'athemes-addons-elementor' ),'<a href="https://lottiefiles.com/" target="_blank">','</a>'
				),
				'label_block' => true,
				'condition'   => [
					'source_type' => 'library',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_settings',
			[
				'label' => __( 'Settings', 'athemes-addons-elementor' ),
			]
		);

		$this->add_control(
			'autoplay',
			[
				'label' => __( 'Autoplay', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'athemes-addons-elementor' ),
				'label_off' => __( 'No', 'athemes-addons-elementor' ),
				'return_value' => 'true',
				'default' => 'true',
			]
		);

		$this->add_control(
			'loop',
			[
				'label' => __( 'Loop', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'athemes-addons-elementor' ),
				'label_off' => __( 'No', 'athemes-addons-elementor' ),
				'return_value' => 'true',
				'default' => 'true',
			]
		);

		$this->add_control(
			'reverse',
			[
				'label' => __( 'Reverse', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'athemes-addons-elementor' ),
				'label_off' => __( 'No', 'athemes-addons-elementor' ),
				'return_value' => 'true',
				'default' => 'false',
			]
		);

		$this->add_control(
			'speed',
			[
				'label' => __( 'Speed', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 1,
				'min' => 0.1,
				'max' => 10,
				'step' => 0.1,
			]
		);

		$this->add_control(
			'trigger_type',
			[
				'label' => __( 'Trigger Type', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'none'      => __( 'None', 'athemes-addons-elementor' ),
					'viewport'  => __( 'Viewport', 'athemes-addons-elementor' ),
					'hover'     => __( 'Hover', 'athemes-addons-elementor' ),
					'scroll'    => __( 'Scroll', 'athemes-addons-elementor' ),
					'click'     => __( 'Click', 'athemes-addons-elementor' ),
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'viewport_position',
			array(
				'label'     => __( 'Viewport', 'athemes-addons-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'sizes' => array(
						'start' => 0,
						'end'   => 100,
					),
					'unit'  => '%',
				),
				'labels'    => array(
					__( 'Bottom', 'athemes-addons-elementor' ),
					__( 'Top', 'athemes-addons-elementor' ),
				),
				'scales'    => 1,
				'handles'   => 'range',
				'condition' => array(
					'trigger_type'         => array( 'scroll', 'viewport' ),
				),
			)
		);

		$this->add_control(
			'size',
			[
				'label' => __( 'Size', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'vw' ],
				'range' => [
					'px' => [
						'min' => 50,
						'max' => 1000,
					],
					'%' => [
						'min' => 10,
						'max' => 100,
					],
					'vw' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-lottie' => 'width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'alignment',
			[
				'label' => __( 'Alignment', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'athemes-addons-elementor' ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'athemes-addons-elementor' ),
						'icon' => 'eicon-h-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'athemes-addons-elementor' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-lottie' => 'margin: 0 auto; text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'rotate',
			[
				'label' => __( 'Rotate', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'deg' ],
				'range' => [
					'deg' => [
						'min' => -360,
						'max' => 360,
					],
				],
				'default' => [
					'unit' => 'deg',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-lottie' => 'transform: rotate({{SIZE}}{{UNIT}});',
				],
			]
		); 

		$this->add_control(
			'render_type',
			[
				'label' => __( 'Render Type', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'svg',
				'options' => [
					'canvas'    => __( 'Canvas', 'athemes-addons-elementor' ),
					'svg'       => __( 'SVG', 'athemes-addons-elementor' ),
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'link',
			[
				'label' => __( 'Link', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'athemes-addons-elementor' ),
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_lottie',
			[
				'label' => __( 'Styles', 'athemes-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_lottie' );

		$this->start_controls_tab(
			'tab_lottie_normal',
			[
				'label' => __( 'Normal', 'athemes-addons-elementor' ),
			]
		);

		$this->add_control(
			'opacity',
			[
				'label' => __( 'Opacity', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '' ],
				'range' => [
					'' => [
						'min' => 0,
						'max' => 1,
						'step' => 0.05,
					],
				],
				'default' => [
					'unit' => '',
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-lottie-container' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->add_control(
			'transition_duration',
			[
				'label' => __( 'Transition Duration', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 1000,
				'min' => 0,
				'max' => 10000,
				'step' => 100,
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-lottie-container' => 'transition-duration: {{SIZE}}ms;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			array(
				'name'     => 'css_filters',
				'selector' => '{{WRAPPER}} .athemes-addons-lottie-container',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_lottie_hover',
			[
				'label' => __( 'Hover', 'athemes-addons-elementor' ),
			]
		);

		$this->add_control(
			'opacity_hover',
			[
				'label' => __( 'Opacity', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '' ],
				'range' => [
					'' => [
						'min' => 0,
						'max' => 1,
						'step' => 0.05,
					],
				],
				'default' => [
					'unit' => '',
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-lottie-container:hover' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			array(
				'name'     => 'css_filters_hover',
				'selector' => '{{WRAPPER}} .athemes-addons-lottie-container:hover',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Render widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'wrapper', 'class', 'athemes-addons-lottie' );

		if ( ! empty( $settings['link']['url'] ) ) {
			$this->add_render_attribute( 'link', 'href', esc_url( $settings['link']['url'] ) );
			$this->add_render_attribute( 'link', 'target', $settings['link']['is_external'] ? '_blank' : '_self' );
		}

		$this->add_render_attribute( 'lottie-container', 'class', 'athemes-addons-lottie-container' );
		$this->add_render_attribute( 'lottie-container', 'data-json-url', 'url' === $settings['source_type'] ? esc_url($settings['json_url']) : esc_attr( $settings['json_id']['url'] ) );
		$this->add_render_attribute( 'lottie-container', 'data-autoplay', esc_attr( $settings['autoplay'] ) );
		$this->add_render_attribute( 'lottie-container', 'data-loop', esc_attr( $settings['loop'] ) );
		$this->add_render_attribute( 'lottie-container', 'data-reverse', esc_attr( $settings['reverse'] ) );
		$this->add_render_attribute( 'lottie-container', 'data-render-type', esc_attr( $settings['render_type'] ) );
		$this->add_render_attribute( 'lottie-container', 'data-trigger-type', esc_attr( $settings['trigger_type'] ) );
		$this->add_render_attribute( 'lottie-container', 'data-scroll-start', isset( $settings['viewport_position']['sizes']['start'] ) ? esc_attr( $settings['viewport_position']['sizes']['start'] ) : '' );
		$this->add_render_attribute( 'lottie-container', 'data-scroll-end', isset( $settings['viewport_position']['sizes']['end'] ) ? esc_attr( $settings['viewport_position']['sizes']['end'] ) : '' );
		$this->add_render_attribute( 'lottie-container', 'data-speed', esc_attr( $settings['speed'] ) );
		?>

		<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
			<?php if ( ! empty( $settings['link']['url'] ) ) : ?>
				<a <?php $this->print_render_attribute_string( 'link' ); ?>>
			<?php endif; ?>

			<div <?php $this->print_render_attribute_string( 'lottie-container' ); ?>></div>

			<?php if ( ! empty( $settings['link']['url'] ) ) : ?>
				</a>
			<?php endif; ?>
		</div>
	
		<?php
	}

	/**
	 * Render widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function content_template() {
	}
}
Plugin::instance()->widgets_manager->register( new Lottie() );