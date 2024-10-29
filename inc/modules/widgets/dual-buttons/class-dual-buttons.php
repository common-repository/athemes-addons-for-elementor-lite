<?php
namespace aThemes_Addons\Widgets;

use Elementor\Plugin;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Text_Shadow;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor dual buttons widget.
 *
 *
 * @since 1.0.0
 */
class Dual_Buttons extends Widget_Base {

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
		return 'athemes-addons-dual-buttons';
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
		return __( 'Dual buttons', 'athemes-addons-elementor' );
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
		return 'eicon-dual-button aafe-elementor-icon';
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
		return [ 'dual', 'buttons', 'button', 'athemes', 'addons', 'athemes addons' ];
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
			'section_settings',
			[
				'label' => __( 'Settings', 'athemes-addons-elementor' ),
			]
		);

		$this->add_responsive_control(
			'width',
			[
				'label' => __( 'Width', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'size' => 38,
					'unit' => '%',
				],
				'range' => [
					'%' => [
						'max' => 100,
					],
					'px' => [
						'max' => 1000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-dual-buttons .dual-buttons-inner' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label' => __( 'Alignment', 'athemes-addons-elementor' ),
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
				'prefix_class' => 'aafe-align%s-',
			]
		);

		$this->add_control(
			'text_separator',
			[
				'label' => esc_html__( 'Separator', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( 'or', 'athemes-addons-elementor' ),
				'separator' => 'before',
			]
		);      

		$this->end_controls_section();

		$this->start_controls_section(
			'section_first_button',
			[
				'label' => __( 'First button', 'athemes-addons-elementor' ),
			]
		);
		$this->add_control(
			'text_first',
			[
				'label' => esc_html__( 'Text', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( 'Start here', 'athemes-addons-elementor' ),
				'placeholder' => esc_html__( 'Click here', 'athemes-addons-elementor' ),
			]
		);

		$this->add_control(
			'link',
			[
				'label' => esc_html__( 'Link', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'https://your-link.com', 'athemes-addons-elementor' ),
				'default' => [
					'url' => '#',
				],
			]
		);

		$this->add_control(
			'selected_icon_first',
			[
				'label' => esc_html__( 'Icon', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
			]
		);

		$this->add_control(
			'icon_align_first',
			[
				'label' => esc_html__( 'Icon Position', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => [
					'left' => esc_html__( 'Before', 'athemes-addons-elementor' ),
					'right' => esc_html__( 'After', 'athemes-addons-elementor' ),
				],
				'condition' => [
					'selected_icon_first[value]!' => '',
				],
			]
		);

		$this->add_control(
			'icon_indent',
			[
				'label' => esc_html__( 'Icon Spacing', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .dual-buttons-first .elementor-button .elementor-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .dual-buttons-first .elementor-button .elementor-align-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		//Second button

		$this->start_controls_section(
			'section_second_button',
			[
				'label' => __( 'Second button', 'athemes-addons-elementor' ),
			]
		);
        $this->add_control(
			'text_second',
			[
				'label' => esc_html__( 'Text', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( 'Get in touch', 'athemes-addons-elementor' ),
				'placeholder' => esc_html__( 'Click here', 'athemes-addons-elementor' ),
			]
		);

		$this->add_control(
			'link_second',
			[
				'label' => esc_html__( 'Link', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'https://your-link.com', 'athemes-addons-elementor' ),
				'default' => [
					'url' => '#',
				],
			]
		);

		$this->add_control(
			'selected_icon_second',
			[
				'label' => esc_html__( 'Icon', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
			]
		);

		$this->add_control(
			'icon_align_second',
			[
				'label' => esc_html__( 'Icon Position', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => [
					'left' => esc_html__( 'Before', 'athemes-addons-elementor' ),
					'right' => esc_html__( 'After', 'athemes-addons-elementor' ),
				],
				'condition' => [
					'selected_icon_second[value]!' => '',
				],
			]
		);

		$this->add_control(
			'icon_indent_second',
			[
				'label' => esc_html__( 'Icon Spacing', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .dual-buttons-last .elementor-button .elementor-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .dual-buttons-last .elementor-button .elementor-align-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'view_second',
			[
				'label' => esc_html__( 'View', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::HIDDEN,
				'default' => 'traditional',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			[
				'label' => esc_html__( 'First button', 'athemes-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'selector' => '{{WRAPPER}} .dual-buttons-first .elementor-button',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'text_shadow',
				'selector' => '{{WRAPPER}} .dual-buttons-first .elementor-button',
			]
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => esc_html__( 'Normal', 'athemes-addons-elementor' ),
			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label' => esc_html__( 'Text Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .dual-buttons-first .elementor-button' => 'fill: {{VALUE}}; color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'background',
				'label' => esc_html__( 'Background', 'athemes-addons-elementor' ),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .dual-buttons-first .elementor-button',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
					'color' => [
						'default' => 'var(--athemes-addons-accent-color, #0E00AC)',
					],
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => esc_html__( 'Hover', 'athemes-addons-elementor' ),
			]
		);

		$this->add_control(
			'hover_color',
			[
				'label' => esc_html__( 'Text Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .dual-buttons-first .elementor-button:hover, {{WRAPPER}} .dual-buttons-first .elementor-button:focus' => 'color: {{VALUE}};',
					'{{WRAPPER}} .dual-buttons-first .elementor-button:hover svg, {{WRAPPER}} .dual-buttons-first .elementor-button:focus svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'button_background_hover',
				'label' => esc_html__( 'Background', 'athemes-addons-elementor' ),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .dual-buttons-first .elementor-button:hover, {{WRAPPER}} .dual-buttons-first .elementor-button:focus',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
					'color' => [
						'default' => 'rgba(14, 0, 172, 0.75)',
					],
				],
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .dual-buttons-first .elementor-button:hover, {{WRAPPER}} .dual-buttons-first .elementor-button:focus' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'hover_animation',
			[
				'label' => esc_html__( 'Hover Animation', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'border',
				'selector' => '{{WRAPPER}} .dual-buttons-first .elementor-button',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .dual-buttons-first .elementor-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .dual-buttons-first .elementor-button',
			]
		);

		$this->add_responsive_control(
			'first_button_height',
			[
				'label' => __( 'Height', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'size' => 50,
					'unit' => 'px',
				],
				'range' => [
					'%' => [
						'max' => 100,
					],
					'px' => [
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-dual-buttons .dual-buttons-first a.elementor-button-link' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_sep_style',
			[
				'label' => esc_html__( 'Separator', 'athemes-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'sep_color',
			[
				'label' => esc_html__( 'Text Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .dual-buttons-sep' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'sep_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .dual-buttons-sep' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'sep_size',
			[
				'label' => __( 'Size', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'default' => [
					'size' => 30,
					'unit' => 'px',
				],
				'range' => [
					'em' => [
						'min' => 0.1,
						'max' => 10,
						'step' => 0.1,
					],
					'px' => [
						'min' => 0.1,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .dual-buttons-sep' => '--separator-size: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'sep_border',
				'selector' => '{{WRAPPER}} .dual-buttons-sep',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'sep_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,             
				'selectors' => [
					'{{WRAPPER}} .dual-buttons-sep' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'sep_typography',
				'selector' => '{{WRAPPER}} .dual-buttons-sep',
				'separator' => 'before',
			]
		);


		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_style_second',
			[
				'label' => esc_html__( 'Second button', 'athemes-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography_second',
				'selector' => '{{WRAPPER}} .dual-buttons-last .elementor-button',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'text_shadow_second',
				'selector' => '{{WRAPPER}} .dual-buttons-last .elementor-button',
			]
		);

		$this->start_controls_tabs( 'tabs_button_style_second' );

		$this->start_controls_tab(
			'tab_button_normal_second',
			[
				'label' => esc_html__( 'Normal', 'athemes-addons-elementor' ),
			]
		);

		$this->add_control(
			'button_text_color_second',
			[
				'label' => esc_html__( 'Text Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .dual-buttons-last .elementor-button' => 'fill: {{VALUE}}; color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'background_second',
				'label' => esc_html__( 'Background', 'athemes-addons-elementor' ),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .dual-buttons-last .elementor-button',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
					'color' => [
						'default' => '#1c1c1c',
					],
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover_second',
			[
				'label' => esc_html__( 'Hover', 'athemes-addons-elementor' ),
			]
		);

		$this->add_control(
			'hover_color_second',
			[
				'label' => esc_html__( 'Text Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .dual-buttons-last .elementor-button:hover, {{WRAPPER}} .dual-buttons-last .elementor-button:focus' => 'color: {{VALUE}};',
					'{{WRAPPER}} .dual-buttons-last .elementor-button:hover svg, {{WRAPPER}} .dual-buttons-last .elementor-button:focus svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'button_background_hover_second',
				'label' => esc_html__( 'Background', 'athemes-addons-elementor' ),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .dual-buttons-last .elementor-button:hover, {{WRAPPER}} .dual-buttons-last .elementor-button:focus',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
					'color' => [
						'default' => 'rgba(28, 28, 28, 0.8)',
					],
				],
			]
		);

		$this->add_control(
			'button_hover_border_color_second',
			[
				'label' => esc_html__( 'Border Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .dual-buttons-last .elementor-button:hover, {{WRAPPER}} .dual-buttons-last .elementor-button:focus' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'hover_animation_second',
			[
				'label' => esc_html__( 'Hover Animation', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'border_second',
				'selector' => '{{WRAPPER}} .dual-buttons-last .elementor-button',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'border_radius_second',
			[
				'label' => esc_html__( 'Border Radius', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .dual-buttons-last .elementor-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow_second',
				'selector' => '{{WRAPPER}} .dual-buttons-last .elementor-button',
			]
		);

		$this->add_responsive_control(
			'second_button_height',
			[
				'label' => __( 'Height', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'size' => 50,
					'unit' => 'px',
				],
				'range' => [
					'%' => [
						'max' => 100,
					],
					'px' => [
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-dual-buttons .dual-buttons-last a.elementor-button-link' => 'height: {{SIZE}}{{UNIT}};',
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

		$this->add_render_attribute( 'athemes-addons-dual-buttons', 'class', 'athemes-addons-dual-buttons' );

		//First button
		if ( ! empty( $settings['link']['url'] ) ) {
			$this->add_link_attributes( 'first-button', $settings['link'] );
			$this->add_render_attribute( 'first-button', 'class', 'elementor-button-link' );
		}
		$this->add_render_attribute( 'first-button', 'class', 'elementor-button' );
		$this->add_render_attribute( 'first-button', 'role', 'button' );

		if ( $settings['hover_animation'] ) {
			$this->add_render_attribute( 'first-button', 'class', 'elementor-animation-' . $settings['hover_animation'] );
		}

		//second button
		if ( ! empty( $settings['link_second']['url'] ) ) {
			$this->add_link_attributes( 'second-button', $settings['link_second'] );
			$this->add_render_attribute( 'second-button', 'class', 'elementor-button-link' );
		}
		$this->add_render_attribute( 'second-button', 'class', 'elementor-button' );
		$this->add_render_attribute( 'second-button', 'role', 'button' );

		if ( $settings['hover_animation_second'] ) {
			$this->add_render_attribute( 'second-button', 'class', 'elementor-animation-' . $settings['hover_animation_second'] );
		}       

		?>

		<div <?php $this->print_render_attribute_string( 'athemes-addons-dual-buttons' ); ?>>
			<div class="dual-buttons-inner">
				<div class="dual-buttons-first">
					<a <?php $this->print_render_attribute_string( 'first-button' ); ?>>
						<?php $this->render_text( 'first' ); ?>
					</a>
				</div>

				<?php if ( $settings['text_separator'] ) : ?>
				<div class="dual-buttons-sep">
					<?php echo esc_html( $settings['text_separator'] ); ?>
				</div>
				<?php endif; ?>

				<div class="dual-buttons-last">
					<a <?php $this->print_render_attribute_string( 'second-button' ); ?>>
						<?php $this->render_text( 'second' ); ?>
					</a>
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
	 * @since 1.0.0
	 * @access protected
	 */
	protected function content_template() { 
	}

	/**
	 * Render button text.
	 *
	 * Render button widget text.
	 */
	protected function render_text( $position ) {
		$settings = $this->get_settings_for_display();

		$migrated = isset( $settings['__fa4_migrated']['selected_icon_' . $position ] );
		$is_new = empty( $settings['icon'] ) && Icons_Manager::is_migration_allowed();

		if ( ! $is_new && empty( $settings['icon_align_' . $position] ) ) {
			$settings['icon_align_' . $position] = $this->get_settings( 'icon_align_' . $position );
		}

		$this->add_render_attribute( [
			'content-wrapper' => [
				'class' => 'elementor-button-content-wrapper',
			],
			'icon-align-' . $position => [
				'class' => [
					'elementor-button-icon',
					'elementor-align-icon-' . $settings['icon_align_' . $position],
				],
			],
			'text_' . $position => [
				'class' => 'elementor-button-text',
			],
		] );

		$this->add_inline_editing_attributes( 'text_' . $position, 'none' );
		?>
		<span <?php $this->print_render_attribute_string( 'content-wrapper' ); ?>>
			<?php if ( ! empty( $settings['icon_' . $position] ) || ! empty( $settings['selected_icon_' . $position]['value'] ) ) : ?>
			<span <?php $this->print_render_attribute_string( 'icon-align-' . $position ); ?>>
				<?php if ( $is_new || $migrated ) :
					Icons_Manager::render_icon( $settings['selected_icon_' . $position], [ 'aria-hidden' => 'true' ] );
				else : ?>
					<i class="<?php echo esc_attr( $settings['icon_' . $position] ); ?>" aria-hidden="true"></i>
				<?php endif; ?>
			</span>
			<?php endif; ?>
			<span <?php $this->print_render_attribute_string( 'text_' . $position ); ?>><?php $this->print_unescaped_setting( 'text_' . $position ); ?></span>
		</span>
		<?php
	}

	public function on_import( $element ) {
		return Icons_Manager::on_import_migration( $element, 'icon_' . $position, 'selected_icon_' . $position );
	}
}
Plugin::instance()->widgets_manager->register( new Dual_Buttons() );