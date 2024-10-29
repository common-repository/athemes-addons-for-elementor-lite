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
use Elementor\Icons_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Background;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor flip box widget.
 *
 * @since 1.0.0
 */
class FlipBox extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve tabs widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'athemes-addons-flip-box';
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
		return __( 'Flip box', 'athemes-addons-elementor' );
	}

	/**
	 * Get widget categories.
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
	 * Get widget icon.
	 *
	 * Retrieve tabs widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-flip-box aafe-elementor-icon';
	}

	/**
	 * Enqueue styles.
	 */
	public function get_style_depends() {
		return [ $this->get_name() . '-styles' ];
	}   

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'flip', 'box', 'flip box', 'athemes', 'addons', 'athemes addons' ];
	}

	/**
	 * Register widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 3.1.0
	 * @access protected
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_settings',
			[
				'label' => esc_html__( 'Settings', 'athemes-addons-elementor' ),
			]
		);

		$this->add_control(
			'height',
			[
				'label' => esc_html__( 'Height', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'vh' ],
				'range' => [
					'px' => [
						'min' => 100,
						'max' => 1000,
					],
					'vh' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 400,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .flip-box' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'direction',
			[
				'label' => esc_html__( 'Direction', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => [
					'left' => esc_html__( 'Left', 'athemes-addons-elementor' ),
					'top' => esc_html__( 'Top', 'athemes-addons-elementor' ),
					'right' => esc_html__( 'Right', 'athemes-addons-elementor' ),
					'bottom' => esc_html__( 'Bottom', 'athemes-addons-elementor' ),
				],
				'prefix_class' => 'flip-box-direction-',
			]
		);

		$this->add_control(
			'effect',
			[
				'label'         => esc_html__( '3D Effect', 'athemes-addons-elementor' ),
				'description'   => esc_html__( 'When flipping, the content has a 3D effect', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'athemes-addons-elementor' ),
				'label_off' => esc_html__( 'No', 'athemes-addons-elementor' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'prefix_class' => 'flip-box-3d-',
			]
		);

		$this->add_control(
			'easing',
			[
				'label' => esc_html__( 'Transition', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'ease',
				'options' => [
					'ease' => esc_html__( 'Ease', 'athemes-addons-elementor' ),
					'ease-in' => esc_html__( 'Ease In', 'athemes-addons-elementor' ),
					'ease-out' => esc_html__( 'Ease Out', 'athemes-addons-elementor' ),
					'ease-in-out' => esc_html__( 'Ease In Out', 'athemes-addons-elementor' ),
					'linear' => esc_html__( 'Linear', 'athemes-addons-elementor' ),
				],
				'selectors' => [
					'{{WRAPPER}} .flip-box-inner' => 'transition-timing-function: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'transition_time',
			[
				'label' => esc_html__( 'Transition Time', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'ms' ],
				'range' => [
					'ms' => [
						'min' => 100,
						'max' => 3000,
					],
				],
				'default' => [
					'size' => 800,
					'unit' => 'ms',
				],
				'selectors' => [
					'{{WRAPPER}} .flip-box-inner' => 'transition-duration: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_front',
			[
				'label' => esc_html__( 'Front Side', 'athemes-addons-elementor' ),
			]
		);

		$this->add_control(
			'front_content_type',
			[
				'label' => esc_html__( 'Content Type', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'custom',
				'options' => [
					'custom' => esc_html__( 'Custom', 'athemes-addons-elementor' ),
					'template' => esc_html__( 'Template', 'athemes-addons-elementor' ),
				],
			]
		);

		$this->add_control(
			'front_template',
			[
				'label' => esc_html__( 'Choose Template', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => $this->get_available_templates(),
				'condition' => [
					'front_content_type' => 'template',
				],
			]
		);

		$this->add_control(
			'front_icon_type',
			[
				'label' => esc_html__( 'Icon Type', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'icon',
				'options' => [
					'none' => esc_html__( 'None', 'athemes-addons-elementor' ),
					'icon' => esc_html__( 'Icon', 'athemes-addons-elementor' ),
					'image' => esc_html__( 'Image', 'athemes-addons-elementor' ),
				],
				'condition' => [
					'front_content_type' => 'custom',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'front_icon',
			[
				'label' => esc_html__( 'Icon', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'default' => [
					'value' => 'fas fa-star',
					'library' => 'solid',
				],
				'condition' => [
					'front_icon_type' => 'icon',
					'front_content_type' => 'custom',
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'front_image',
			[
				'label' => esc_html__( 'Image', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'front_icon_type' => 'image',
					'front_content_type' => 'custom',
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'front_title',
			[
				'label' => esc_html__( 'Title', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Front side title', 'athemes-addons-elementor' ),
				'placeholder' => esc_html__( 'Enter your title', 'athemes-addons-elementor' ),
				'label_block' => true,
				'condition' => [
					'front_content_type' => 'custom',
				],
			]
		);

		$this->add_control(
			'front_description',
			[
				'label' => esc_html__( 'Description', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::WYSIWYG,
				'default' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec et porta nunc.', 'athemes-addons-elementor' ),
				'placeholder' => esc_html__( 'Enter your description', 'athemes-addons-elementor' ),
				'show_label' => false,
				'condition' => [
					'front_content_type' => 'custom',
				],
			]
		);

		$this->add_control(
			'front_vertical_alignment',
			[
				'label' => esc_html__( 'Vertical Alignment', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'top' => [
						'title' => esc_html__( 'Top', 'athemes-addons-elementor' ),
						'icon' => 'eicon-v-align-top',
					],
					'middle' => [
						'title' => esc_html__( 'Middle', 'athemes-addons-elementor' ),
						'icon' => 'eicon-v-align-middle',
					],
					'bottom' => [
						'title' => esc_html__( 'Bottom', 'athemes-addons-elementor' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'default' => 'middle',
				'prefix_class' => 'flip-box-front-v-align-',
				'condition' => [
					'front_content_type' => 'custom',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'front_align',
			[
				'label' => esc_html__( 'Alignment', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'athemes-addons-elementor' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'athemes-addons-elementor' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'athemes-addons-elementor' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'center',
				'prefix_class' => 'flip-box-front-h-align-',
				'condition' => [
					'front_content_type' => 'custom',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_back',
			[
				'label' => esc_html__( 'Back Side', 'athemes-addons-elementor' ),
			]
		);

		$this->add_control(
			'back_content_type',
			[
				'label' => esc_html__( 'Content Type', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'custom',
				'options' => [
					'custom' => esc_html__( 'Custom', 'athemes-addons-elementor' ),
					'template' => esc_html__( 'Template', 'athemes-addons-elementor' ),
				],
			]
		);

		$this->add_control(
			'back_template',
			[
				'label' => esc_html__( 'Choose Template', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => $this->get_available_templates(),
				'condition' => [
					'back_content_type' => 'template',
				],
			]
		);

		$this->add_control(
			'back_icon_type',
			[
				'label' => esc_html__( 'Icon Type', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'icon',
				'options' => [
					'none' => esc_html__( 'None', 'athemes-addons-elementor' ),
					'icon' => esc_html__( 'Icon', 'athemes-addons-elementor' ),
					'image' => esc_html__( 'Image', 'athemes-addons-elementor' ),
				],
				'condition' => [
					'back_content_type' => 'custom',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'back_icon',
			[
				'label' => esc_html__( 'Icon', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'default' => [
					'value' => 'fas fa-star',
					'library' => 'solid',
				],
				'condition' => [
					'back_icon_type' => 'icon',
					'back_content_type' => 'custom',
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'back_image',
			[
				'label' => esc_html__( 'Image', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'back_icon_type' => 'image',
					'back_content_type' => 'custom',
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'back_title',
			[
				'label' => esc_html__( 'Title', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Back side title', 'athemes-addons-elementor' ),
				'placeholder' => esc_html__( 'Enter your title', 'athemes-addons-elementor' ),
				'label_block' => true,
				'condition' => [
					'back_content_type' => 'custom',
				],
			]
		);

		$this->add_control(
			'back_description',
			[
				'label' => esc_html__( 'Description', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::WYSIWYG,
				'default' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec et porta nunc.', 'athemes-addons-elementor' ),
				'placeholder' => esc_html__( 'Enter your description', 'athemes-addons-elementor' ),
				'show_label' => false,
				'condition' => [
					'back_content_type' => 'custom',
				],
			]
		);

		$this->add_control(
			'back_vertical_alignment',
			[
				'label' => esc_html__( 'Vertical Alignment', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'top' => [
						'title' => esc_html__( 'Top', 'athemes-addons-elementor' ),
						'icon' => 'eicon-v-align-top',
					],
					'middle' => [
						'title' => esc_html__( 'Middle', 'athemes-addons-elementor' ),
						'icon' => 'eicon-v-align-middle',
					],
					'bottom' => [
						'title' => esc_html__( 'Bottom', 'athemes-addons-elementor' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'default' => 'middle',
				'prefix_class' => 'flip-box-back-v-align-',
				'condition' => [
					'back_content_type' => 'custom',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'back_align',
			[
				'label' => esc_html__( 'Alignment', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'athemes-addons-elementor' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'athemes-addons-elementor' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'athemes-addons-elementor' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'center',
				'prefix_class' => 'flip-box-back-h-align-',
				'condition' => [
					'back_content_type' => 'custom',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_general',
			[
				'label' => esc_html__( 'General', 'athemes-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],              
				'selectors' => [
					'{{WRAPPER}} .flip-box-front, {{WRAPPER}} .flip-box-back, {{WRAPPER}} .flip-box-overlay' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],              
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow',
				'label' => __( 'Box Shadow', 'athemes-addons-elementor' ),
				'selector' => '{{WRAPPER}} .flip-box-front, {{WRAPPER}} .flip-box-back',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_front',
			[
				'label' => esc_html__( 'Front Side', 'athemes-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'front_padding',
			[
				'label' => esc_html__( 'Padding', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .flip-box-front' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],      
				'separator' => 'after',      
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'front_bg',
				'label' => esc_html__( 'Background', 'athemes-addons-elementor' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .flip-box-front',
			]
		);

		$this->add_control(
			'front_overlay_color',
			[
				'label' => esc_html__( 'Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .flip-box-front .flip-box-overlay' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'front_bg_background' => 'classic',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'front_border',
				'label' => __( 'Border', 'athemes-addons-elementor' ),
				'selector' => '{{WRAPPER}} .flip-box-front',
			]
		);

		$this->add_control(
			'front_icon_heading',
			[
				'label' => esc_html__( 'Icon', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'front_icon_type!' => 'none',
					'front_content_type' => 'custom',
				],
			]
		);

		$this->add_control(
			'front_icon_color',
			[
				'label' => esc_html__( 'Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .flip-box-front .flip-box-icon i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .flip-box-front .flip-box-icon svg' => 'fill: {{VALUE}};',
				],
				'condition' => [
					'front_icon_type!' => 'none',
					'front_content_type' => 'custom',
				],
			]
		);

		$this->add_control(
			'front_icon_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .flip-box-front .flip-box-icon' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'front_icon_type!' => 'none',
					'front_content_type' => 'custom',
				],
			]
		);

		$this->add_responsive_control(
			'front_icon_size',
			[
				'label' => esc_html__( 'Size', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'size' => 40,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .flip-box-front .flip-box-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .flip-box-front .flip-box-icon svg' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'front_icon_type!' => 'none',
					'front_content_type' => 'custom',
				],
			]
		);

		$this->add_responsive_control(
			'front_icon_padding',
			[
				'label' => esc_html__( 'Padding', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],              
				'selectors' => [
					'{{WRAPPER}} .flip-box-front .flip-box-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],              
				'condition' => [
					'front_icon_type!' => 'none',
					'front_content_type' => 'custom',
				],
			]
		);

		$this->add_responsive_control(
			'front_icon_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],              
				'selectors' => [
					'{{WRAPPER}} .flip-box-front .flip-box-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],              
				'condition' => [
					'front_icon_type!' => 'none',
					'front_content_type' => 'custom',
				],
			]
		);

		$this->add_control(
			'front_title_heading',
			[
				'label' => esc_html__( 'Title', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'front_title!' => '',
					'front_content_type' => 'custom',
				],
			]
		);

		$this->add_responsive_control(
			'front_title_margin',
			[
				'label' => esc_html__( 'Margin', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],              
				'selectors' => [
					'{{WRAPPER}} .flip-box-front .flip-box-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],              
				'condition' => [
					'front_title!' => '',
					'front_content_type' => 'custom',
				],
			]
		);      

		$this->add_control(
			'front_title_color',
			[
				'label' => esc_html__( 'Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,              
				'selectors' => [
					'{{WRAPPER}} .flip-box-front .flip-box-title' => 'color: {{VALUE}};',
				],              
				'condition' => [
					'front_title!' => '',
					'front_content_type' => 'custom',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'front_title_typography',
				'label' => esc_html__( 'Typography', 'athemes-addons-elementor' ),
				'selector' => '{{WRAPPER}} .flip-box-front .flip-box-title',
				'condition' => [
					'front_title!' => '',
					'front_content_type' => 'custom',
				],
			]
		);

		$this->add_control(
			'front_description_heading',
			[
				'label' => esc_html__( 'Description', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'front_description!' => '',
					'front_content_type' => 'custom',
				],
			]
		);

		$this->add_control(
			'front_description_color',
			[
				'label' => esc_html__( 'Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,              
				'selectors' => [
					'{{WRAPPER}} .flip-box-front .flip-box-description' => 'color: {{VALUE}};',
				],              
				'condition' => [
					'front_description!' => '',
					'front_content_type' => 'custom',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'front_description_typography',
				'label' => esc_html__( 'Typography', 'athemes-addons-elementor' ),
				'selector' => '{{WRAPPER}} .flip-box-front .flip-box-description',
				'condition' => [
					'front_description!' => '',
					'front_content_type' => 'custom',
				],
			]
		);

		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_style_back',
			[
				'label' => esc_html__( 'Back Side', 'athemes-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'back_padding',
			[
				'label' => esc_html__( 'Padding', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .flip-box-back' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],      
				'separator' => 'after',      
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'back_bg',
				'label' => esc_html__( 'Background', 'athemes-addons-elementor' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .flip-box-back',
			]
		);

		$this->add_control(
			'back_overlay_color',
			[
				'label' => esc_html__( 'Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .flip-box-back .flip-box-overlay' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'back_bg_background' => 'classic',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'back_border',
				'label' => __( 'Border', 'athemes-addons-elementor' ),
				'selector' => '{{WRAPPER}} .flip-box-back',
			]
		);

		$this->add_control(
			'back_icon_heading',
			[
				'label' => esc_html__( 'Icon', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'back_icon_type!' => 'none',
					'back_content_type' => 'custom',
				],
			]
		);

		$this->add_control(
			'back_icon_color',
			[
				'label' => esc_html__( 'Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .flip-box-back .flip-box-icon i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .flip-box-back .flip-box-icon svg' => 'fill: {{VALUE}};',
				],
				'condition' => [
					'back_icon_type!' => 'none',
					'back_content_type' => 'custom',
				],
			]
		);

		$this->add_control(
			'back_icon_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .flip-box-back .flip-box-icon' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'back_icon_type!' => 'none',
					'back_content_type' => 'custom',
				],
			]
		);

		$this->add_responsive_control(
			'back_icon_size',
			[
				'label' => esc_html__( 'Size', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'size' => 40,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .flip-box-back .flip-box-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .flip-box-back .flip-box-icon svg' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'back_icon_type!' => 'none',
					'back_content_type' => 'custom',
				],
			]
		);

		$this->add_responsive_control(
			'back_icon_padding',
			[
				'label' => esc_html__( 'Padding', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],              
				'selectors' => [
					'{{WRAPPER}} .flip-box-back .flip-box-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],              
				'condition' => [
					'back_icon_type!' => 'none',
					'back_content_type' => 'custom',
				],
			]
		);

		$this->add_responsive_control(
			'back_icon_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],              
				'selectors' => [
					'{{WRAPPER}} .flip-box-back .flip-box-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],              
				'condition' => [
					'back_icon_type!' => 'none',
					'back_content_type' => 'custom',
				],
			]
		);

		$this->add_control(
			'back_title_heading',
			[
				'label' => esc_html__( 'Title', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'back_title!' => '',
					'back_content_type' => 'custom',
				],
			]
		);

		$this->add_responsive_control(
			'back_title_margin',
			[
				'label' => esc_html__( 'Margin', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],              
				'selectors' => [
					'{{WRAPPER}} .flip-box-back .flip-box-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],              
				'condition' => [
					'back_title!' => '',
					'back_content_type' => 'custom',
				],
			]
		);      

		$this->add_control(
			'back_title_color',
			[
				'label' => esc_html__( 'Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,              
				'selectors' => [
					'{{WRAPPER}} .flip-box-back .flip-box-title' => 'color: {{VALUE}};',
				],              
				'condition' => [
					'back_title!' => '',
					'back_content_type' => 'custom',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'back_title_typography',
				'label' => esc_html__( 'Typography', 'athemes-addons-elementor' ),
				'selector' => '{{WRAPPER}} .flip-box-back .flip-box-title',
				'condition' => [
					'back_title!' => '',
					'back_content_type' => 'custom',
				],
			]
		);

		$this->add_control(
			'back_description_heading',
			[
				'label' => esc_html__( 'Description', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'back_description!' => '',
					'back_content_type' => 'custom',
				],
			]
		);

		$this->add_control(
			'back_description_color',
			[
				'label' => esc_html__( 'Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,              
				'selectors' => [
					'{{WRAPPER}} .flip-box-back .flip-box-description' => 'color: {{VALUE}};',
				],              
				'condition' => [
					'back_description!' => '',
					'back_content_type' => 'custom',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'back_description_typography',
				'label' => esc_html__( 'Typography', 'athemes-addons-elementor' ),
				'selector' => '{{WRAPPER}} .flip-box-back .flip-box-description',
				'condition' => [
					'back_description!' => '',
					'back_content_type' => 'custom',
				],
			]
		);

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

		$this->add_render_attribute( 'athemes-addons-flip-box', 'class', 'athemes-addons-flip-box' );

		?>
		<div <?php $this->print_render_attribute_string( 'athemes-addons-flip-box' ); ?>>
			<div class="flip-box">
				<div class="flip-box-inner">
					<div class="flip-box-front">
						<div class="flip-box-front-inner">
						<?php if ( 'template' === $settings['front_content_type'] && ! empty( $settings['front_template'] ) ) : ?>
							<?php echo Plugin::instance()->frontend->get_builder_content_for_display( $settings['front_template'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<?php else : ?>
							<?php if ( 'icon' === $settings['front_icon_type'] && ! empty( $settings['front_icon']['value'] ) ) : ?>
								<div class="flip-box-icon">
									<?php Icons_Manager::render_icon( $settings['front_icon'], [ 'aria-hidden' => 'true' ] ); ?>
								</div>
							<?php elseif ( 'image' === $settings['front_icon_type'] && ! empty( $settings['front_image']['url'] ) ) : ?>
								<div class="flip-box-image">
									<?php Group_Control_Image_Size::print_attachment_image_html( $settings, 'front_image' ); ?>
								</div>
							<?php endif; ?>

							<?php if ( ! empty( $settings['front_title'] ) ) : ?>
								<h3 class="flip-box-title"><?php echo esc_html( $settings['front_title'] ); ?></h3>
							<?php endif; ?>

							<?php if ( ! empty( $settings['front_description'] ) ) : ?>
								<div class="flip-box-description"><?php echo wp_kses_post( $settings['front_description'] ); ?></div>
							<?php endif; ?>
						<?php endif; ?>
						</div>
						<div class="flip-box-overlay"></div>
					</div>
					<div class="flip-box-back">
						<div class="flip-box-back-inner">
						<?php if ( 'template' === $settings['back_content_type'] && ! empty( $settings['back_template'] ) ) : ?>
							<?php echo Plugin::instance()->frontend->get_builder_content_for_display( $settings['back_template'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<?php else : ?>
							<?php if ( 'icon' === $settings['back_icon_type'] && ! empty( $settings['back_icon']['value'] ) ) : ?>
								<div class="flip-box-icon">
									<?php Icons_Manager::render_icon( $settings['back_icon'], [ 'aria-hidden' => 'true' ] ); ?>
								</div>
							<?php elseif ( 'image' === $settings ['back_icon_type'] && ! empty( $settings['back_image']['url'] ) ) : ?>
								<div class="flip-box-image">
									<?php Group_Control_Image_Size::print_attachment_image_html( $settings, 'back_image' ); ?>
								</div>
							<?php endif; ?>

							<?php if ( ! empty( $settings['back_title'] ) ) : ?>
								<h3 class="flip-box-title"><?php echo esc_html( $settings['back_title'] ); ?></h3>
							<?php endif; ?>

							<?php if ( ! empty( $settings['back_description'] ) ) : ?>
								<div class="flip-box-description"><?php echo wp_kses_post( $settings['back_description'] ); ?></div>
							<?php endif; ?>
						<?php endif; ?>
						</div>
						<div class="flip-box-overlay"></div>
					</div>	
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Render widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 2.9.0
	 * @access protected
	 */
	/**
	 * Render icon list widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function content_template() { 
	}

	/**
	 * Get item templates
	 */
	protected function get_available_templates() {

		$args = array(
			'numberposts'      => -1,
			'post_type'        => 'elementor_library',
			'post_status'      => 'publish',
		);  

		$templates = get_posts( $args );

		$options = [ '' => '' ];

		foreach ( $templates as $template ) {
			$options[ $template->ID ] = $template->post_title;
		}

		return $options;
	}
}
Plugin::instance()->widgets_manager->register( new FlipBox() );