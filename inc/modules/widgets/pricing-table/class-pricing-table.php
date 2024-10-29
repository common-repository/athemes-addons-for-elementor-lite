<?php
namespace aThemes_Addons\Widgets;

use Elementor\Plugin;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Group_Control_Image_Size;
use Elementor\Repeater;
use Elementor\Icons_Manager;
use Elementor\Control_Media;

use aThemes_Addons\Traits\Button_Trait;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Animated heading widget.
 *
 * @since 1.0.0
 */
class Pricing_Table extends Widget_Base {

	use Button_Trait;

	/**
	 * Get widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'athemes-addons-pricing-table';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve icon list widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Pricing table', 'athemes-addons-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve icon list widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-price-table aafe-elementor-icon';
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
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'pricing', 'table', 'price', 'athemes', 'addons', 'athemes addons' ];
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the icon list widget belongs to.
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
	 * Register icon list widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {


		$this->start_controls_section(
			'section_header',
			[
				'label' => __( 'Header', 'athemes-addons-elementor' ),
			]
		);

		$this->add_control(
			'header_icon_type',
			[
				'label' => __( 'Icon type', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'icon',
				'options' => [
					'icon' => __( 'Icon', 'athemes-addons-elementor' ),
					'image' => __( 'Image', 'athemes-addons-elementor' ),
				],
			]
		);

		$this->add_control(
			'header_icon',
			[
				'label' => __( 'Icon', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'default' => [
					'value' => 'fas fa-trophy',
					'library' => 'fa-solid',
				],
				'condition' => [
					'header_icon_type' => 'icon',
				],
			]
		);

		$this->add_control(
			'header_image',
			[
				'label' => __( 'Image', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'header_icon_type' => 'image',
				],
			]
		);      

		$this->add_control(
			'header_title',
			[
				'label' => __( 'Title', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Basic', 'athemes-addons-elementor' ),
				'separator' => 'before',
			]
		);

		$this->add_control(
			'header_title_tag',
			[
				'label' => __( 'Title HTML Tag', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => __( 'H1', 'athemes-addons-elementor' ),
					'h2' => __( 'H2', 'athemes-addons-elementor' ),
					'h3' => __( 'H3', 'athemes-addons-elementor' ),
					'h4' => __( 'H4', 'athemes-addons-elementor' ),
					'h5' => __( 'H5', 'athemes-addons-elementor' ),
					'h6' => __( 'H6', 'athemes-addons-elementor' ),
					'div' => __( 'div', 'athemes-addons-elementor' ),
					'span' => __( 'span', 'athemes-addons-elementor' ),
					'p' => __( 'p', 'athemes-addons-elementor' ),
				],
				'default' => 'h3',
			]
		);

		$this->add_control(
			'header_subtitle',
			[
				'label' => __( 'Subtitle', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'For individuals', 'athemes-addons-elementor' ),
			]
		);

		$this->add_responsive_control(
			'header_alignment',
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
				'prefix_class' => 'header-align-',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_price',
			[
				'label' => __( 'Price', 'athemes-addons-elementor' ),
			]
		);

		$this->add_control(
			'price_currency',
			[
				'label' => __( 'Currency', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( '$', 'athemes-addons-elementor' ),
			]
		);

		$this->add_control(
			'currency_position',
			[
				'label' => __( 'Currency Position', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'0' => [
						'title' => esc_html__( 'Before price', 'athemes-addons-elementor' ),
						'icon' => 'eicon-h-align-left',
					],
					'1' => [
						'title' => esc_html__( 'After price', 'athemes-addons-elementor' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'default' => '0',
				'selectors' => [
					'{{WRAPPER}} .currency' => 'order: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'full_price_amount',
			[
				'label' => __( 'Full price', 'athemes-addons-elementor' ),
				'description' => __( 'The full price will show up slashed', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'separator' => 'before',
			]
		);      

		$this->add_control(
			'price_amount',
			[
				'label' => __( 'Amount', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( '15', 'athemes-addons-elementor' ),
				'separator' => 'after',
			]
		);

		$this->add_control(
			'price_period',
			[
				'label' => __( 'Period', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( '/ per month', 'athemes-addons-elementor' ),
			]
		);

		$this->add_control(
			'price_description',
			[
				'label' => __( 'Description', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Renews automatically', 'athemes-addons-elementor' ),
			]
		);

		$this->add_responsive_control(
			'price_alignment',
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
					'{{WRAPPER}} .plan-price' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_features',
			[
				'label' => __( 'Features', 'athemes-addons-elementor' ),
			]
		);  

		$repeater = new Repeater();

		$repeater->add_control(
			'feature_icon',
			[
				'label' => __( 'Icon', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'default' => [
					'value' => 'fas fa-check-circle',
					'library' => 'fa-solid',
				],
			]
		);

		$repeater->add_control(
			'feature_text',
			[
				'label' => __( 'Feature text', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Feature', 'athemes-addons-elementor' ),
				'label_block' => true,
				'separator' => 'before',
			]
		);

		$repeater->add_control(
			'feature_slashed_text',
			[
				'label' => __( 'Slashed text', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'separator' => 'before',
				'return_value' => 'yes',
			]
		);

		$repeater->add_control(
			'feature_icon_color',
			[
				'label' => __( 'Icon Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .feature-icon' => 'color: {{VALUE}} !important',
					'{{WRAPPER}} {{CURRENT_ITEM}} .feature-icon svg' => 'fill: {{VALUE}} !important',
				],
				'separator' => 'before',
			]
		);

		$repeater->add_control(
			'feature_text_color',
			[
				'label' => __( 'Text Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .feature-text' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'features',
			[
				'label' => __( 'Features', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
[
					'feature_text' => __( 'Feature #1', 'athemes-addons-elementor' ),
				], [
					'feature_text' => __( 'Feature #2', 'athemes-addons-elementor' ),
				], [
					'feature_text' => __( 'Feature #3', 'athemes-addons-elementor' ),
				],
],
				'title_field' => '{{{ feature_text }}}',
			]
		);

		$this->add_responsive_control(
			'features_alignment',
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
					'{{WRAPPER}} .plan-features' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_footer',
			[
				'label' => __( 'Footer', 'athemes-addons-elementor' ),
			]
		);

		$this->add_control(
			'footer_text',
			[
				'label' => __( 'Footer text', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
			]
		);

		$this->add_control(
			'button_heading',
			[
				'label' => __( 'Button', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'display_button',
			[
				'label' => __( 'Display Button', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
			]
		);

		$this->register_button_content_controls( $args = array( 'alignment_default' => 'center', 'button_default_text' => __( 'Buy Now', 'athemes-addons-elementor' ), 'section_condition' => array( 'display_button' => 'yes' ), 'alignment_control_prefix_class' => 'button-align-' ) );

		$this->remove_control( 'size' );

		$this->remove_control( 'button_type' );
		

		$this->end_controls_section();

		$this->start_controls_section(
			'section_ribbon',
			[
				'label' => __( 'Ribbon', 'athemes-addons-elementor' ),
			]
		);

		$this->add_control(
			'ribbon_style',
			[
				'label' => __( 'Style', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'ribbon',
				'options' => [
					'none'          => __( 'None', 'athemes-addons-elementor' ),
					'circle'        => __( 'Circle', 'athemes-addons-elementor' ),
					'before_title'  => __( 'Before title', 'athemes-addons-elementor' ),
					'triangle'      => __( 'Triangle', 'athemes-addons-elementor' ),
					'flag'          => __( 'Flag', 'athemes-addons-elementor' ),
					'ribbon'        => __( 'Ribbon', 'athemes-addons-elementor' ),
				],
			]
		);

		$this->add_control(
			'ribbon_text',
			[
				'label' => __( 'Text', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Hot', 'athemes-addons-elementor' ),
			]
		);

		$this->add_responsive_control(
			'ribbon_size',
			[
				'label' => __( 'Size', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'%' => [
						'min' => 10,
						'max' => 100,
					],
					'px' => [
						'min' => 10,
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-pricing-table' => '--ribbon-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'ribbon_style' => [ 'circle' ],
				],
			]
		);

		$this->add_responsive_control(
			'ribbon_offset_horizontal',
			[
				'label' => __( 'Offset Horizontal', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'%' => [
						'min' => -50,
						'max' => 50,
					],
					'px' => [
						'min' => -500,
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-pricing-table' => '--ribbon-offset-horizontal: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'ribbon_style' => [ 'circle' ],
				],
			]
		);

		$this->add_responsive_control(
			'ribbon_offset_vertical',
			[
				'label' => __( 'Offset Vertical', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'%' => [
						'min' => -50,
						'max' => 50,
					],
					'px' => [
						'min' => -500,
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-pricing-table' => '--ribbon-offset-vertical: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'ribbon_style!' => [ 'before_title', 'triangle', 'ribbon' ],
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_order',
			[
				'label' => __( 'Order', 'athemes-addons-elementor' ),
			]
		);

		$this->add_control(
			'order_description',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => __( 'Set the order in which the elements are displayed (e.g. 1 will be shown first)', 'athemes-addons-elementor' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			]
		);

		$this->add_control(
			'order_icon',
			[
				'label' => __( 'Icon', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 1,
				'selectors' => [
					'{{WRAPPER}} .plan-icon' => 'order: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'order_header',
			[
				'label' => __( 'Title & description', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 2,
				'selectors' => [
					'{{WRAPPER}} .plan-header' => 'order: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'order_price',
			[
				'label' => __( 'Price', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 3,
				'selectors' => [
					'{{WRAPPER}} .plan-price' => 'order: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'order_features',
			[
				'label' => __( 'Features', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 4,
				'selectors' => [
					'{{WRAPPER}} .plan-features' => 'order: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'order_footer',
			[
				'label' => __( 'Footer', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 5,
				'selectors' => [
					'{{WRAPPER}} .plan-footer' => 'order: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_wrapper_style',
			[
				'label' => __( 'Wrapper', 'athemes-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'wrapper_max_width',
			[
				'label' => __( 'Max Width', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'%' => [
						'min' => 10,
						'max' => 100,
					],
					'px' => [
						'min' => 100,
						'max' => 2000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .pricing-table-inner' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'wrapper_margin',
			[
				'label' => __( 'Margin', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-pricing-table' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'wrapper_padding',
			[
				'label' => __( 'Padding', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .pricing-table-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'wrapper_border_radius',
			[
				'label' => __( 'Border Radius', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .pricing-table-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .pricing-table-inner::after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .ribbon-style-triangle' => 'border-radius: 0 {{RIGHT}}{{UNIT}} 0 0;',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_wrapper_style' );

		$this->start_controls_tab(
			'tab_wrapper_normal',
			[
				'label' => __( 'Normal', 'athemes-addons-elementor' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'wrapper_background',
				'label' => __( 'Background', 'athemes-addons-elementor' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .pricing-table-inner',
			]
		);

		$this->add_control(
			'wrapper_overlay_color',
			[
				'label' => __( 'Overlay', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .pricing-table-inner:after' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'wrapper_background_background' => 'classic',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'wrapper_border',
				'label' => __( 'Border', 'athemes-addons-elementor' ),
				'selector' => '{{WRAPPER}} .pricing-table-inner',
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'wrapper_box_shadow',
				'label' => __( 'Box Shadow', 'athemes-addons-elementor' ),
				'selector' => '{{WRAPPER}} .pricing-table-inner',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_wrapper_hover',
			[
				'label' => __( 'Hover', 'athemes-addons-elementor' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'wrapper_hover_background',
				'label' => __( 'Background', 'athemes-addons-elementor' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .pricing-table-inner:hover',
			]
		);

		$this->add_control(
			'wrapper_hover_overlay_color',
			[
				'label' => __( 'Overlay', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .pricing-table-inner:hover:after' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'wrapper_hover_background_background' => 'classic',
				],
			]
		);

		$this->add_control(
			'wrapper_hover_border_color',
			[
				'label' => __( 'Border Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .pricing-table-inner:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'wrapper_hover_box_shadow',
			[
				'label' => __( 'Box Shadow', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::BOX_SHADOW,
				'selector' => '{{WRAPPER}} .pricing-table-inner:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section(); 

		$this->start_controls_section(
			'section_icon_style',
			[
				'label' => __( 'Icon', 'athemes-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'header_icon_type' => 'icon',
				],
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label' => __( 'Icon Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .plan-icon' => 'color: {{VALUE}}',
					'{{WRAPPER}} .plan-icon svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'icon_size',
			[
				'label' => __( 'Icon Size', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'em', 'px', '%' ],
				'range' => [
					'em' => [
						'min' => 0.1,
						'max' => 10,
					],
					'px' => [
						'min' => 10,
						'max' => 500,
					],
					'%' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .plan-icon' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .plan-icon svg' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_wrapper_size',
			[
				'label' => __( 'Wrapper Size', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'em', 'px', '%' ],
				'range' => [
					'em' => [
						'min' => 0.1,
						'max' => 10,
					],
					'px' => [
						'min' => 10,
						'max' => 100,
					],
					'%' => [
						'min' => 10,
						'max' => 500,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .plan-icon div' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};line-height: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'icon_background_color',
			[
				'label' => __( 'Background Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .plan-icon div' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'icon_border_radius',
			[
				'label' => __( 'Border Radius', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .plan-icon div' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_margin',
			[
				'label' => __( 'Margin', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .plan-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'icon_border',
				'label' => __( 'Border', 'athemes-addons-elementor' ),
				'selector' => '{{WRAPPER}} .plan-icon div',
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'icon_box_shadow',
				'label' => __( 'Box Shadow', 'athemes-addons-elementor' ),
				'selector' => '{{WRAPPER}} .plan-icon div',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_image_style',
			[
				'label' => __( 'Image', 'athemes-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'header_icon_type' => 'image',
				],
			]
		);

		$this->add_responsive_control(
			'image_size',
			[
				'label' => __( 'Image Size', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'em', 'px', '%' ],
				'range' => [
					'em' => [
						'min' => 0.1,
						'max' => 10,
					],
					'px' => [
						'min' => 10,
						'max' => 500,
					],
					'%' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} .plan-icon img' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'header_icon_type' => 'image',
				],
			]
		);

		$this->add_responsive_control(
			'image_border_radius',
			[
				'label' => __( 'Border Radius', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .plan-icon img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
				'condition' => [
					'header_icon_type' => 'image',
				],
			]
		);

		$this->add_responsive_control(
			'image_margin',
			[
				'label' => __( 'Margin', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .plan-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'header_icon_type' => 'image',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'image_border',
				'label' => __( 'Border', 'athemes-addons-elementor' ),
				'selector' => '{{WRAPPER}} .plan-icon img',
				'separator' => 'before',
				'condition' => [
					'header_icon_type' => 'image',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_header_style',
			[
				'label' => __( 'Header', 'athemes-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'header_margin',
			[
				'label' => __( 'Margin', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .plan-header' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'header_padding',
			[
				'label' => __( 'Padding', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => [
				'{{WRAPPER}} .plan-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'header_background',
				'label' => __( 'Background', 'athemes-addons-elementor' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .plan-header',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'header_border',
				'label' => __( 'Border', 'athemes-addons-elementor' ),
				'selector' => '{{WRAPPER}} .plan-header',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'header_border_radius',
			[
				'label' => __( 'Border Radius', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => [
				'{{WRAPPER}} .plan-header' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Title
		$this->add_control(
			'header_title_heading',
			[
				'label' => __( 'Title', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'header_title_color',
			[
				'label' => __( 'Title Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
				'{{WRAPPER}} .plan-title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'header_title_typography',
				'selector'  => '{{WRAPPER}} .plan-title',
			]
		);

		$this->add_control(
			'header_subtitle_heading',
			[
				'label' => __( 'Subtitle', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'header_subtitle_color',
			[
				'label' => __( 'Subtitle Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
				'{{WRAPPER}} .plan-subtitle' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'header_subtitle_typography',
				'selector'  => '{{WRAPPER}} .plan-subtitle',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_price_style',
			[
				'label' => __( 'Price', 'athemes-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'price_padding',
			[
				'label' => __( 'Padding', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => [
				'{{WRAPPER}} .plan-price' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'price_border',
				'label' => __( 'Border', 'athemes-addons-elementor' ),
				'selector' => '{{WRAPPER}} .plan-price',
				'separator' => 'before',
			]
		);

		// Full Price
		$this->add_control(
			'full_price_heading',
			[
				'label' => __( 'Full Price', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'full_price_amount_color',
			[
				'label' => __( 'Amount Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
				'{{WRAPPER}} .full-price' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'full_price_amount_typography',
				'selector'  => '{{WRAPPER}} .full-price',
			]
		);

		$this->add_control(
			'price_heading',
			[
				'label' => __( 'Price', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'price_amount_color',
			[
				'label' => __( 'Amount Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
				'{{WRAPPER}} .price' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'price_amount_typography',
				'selector'  => '{{WRAPPER}} .price',
			]
		);

		$this->add_control(
			'price_currency_heading',
			[
				'label' => __( 'Currency', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'price_currency_color',
			[
				'label' => __( 'Currency Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
				'{{WRAPPER}} .currency' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'price_currency_typography',
				'selector'  => '{{WRAPPER}} .currency',
			]
		);

		$this->add_control(
			'price_period_heading',
			[
				'label' => __( 'Period', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'price_period_color',
			[
				'label' => __( 'Period Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
				'{{WRAPPER}} .period' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'price_period_typography',
				'selector'  => '{{WRAPPER}} .period',
			]
		);

		$this->add_control(
			'price_description_heading',
			[
				'label' => __( 'Description', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'price_description_color',
			[
				'label' => __( 'Description Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
				'{{WRAPPER}} .price-description' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'price_description_typography',
				'selector'  => '{{WRAPPER}} .price-description',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_features_style',
			[
				'label' => __( 'Features', 'athemes-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'features_margin',
			[
				'label' => __( 'Margin', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .plan-features' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'features_spacing',
			[
				'label' => __( 'Spacing', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'em', 'px', '%' ],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .plan-feature:not(:last-of-type)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'features_color',
			[
				'label' => __( 'Text Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
				'{{WRAPPER}} .plan-feature' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'features_typography',
				'selector'  => '{{WRAPPER}} .plan-feature',
			]
		);

		$this->add_control(
			'features_slashed_text_color',
			[
				'label' => __( 'Slashed Text Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .slashed-text-yes .feature-text' => 'color: {{VALUE}};text-decoration: line-through;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'features_slashed_text_typography',
				'selector'  => '{{WRAPPER}} .slashed-text-yes .feature-text',
			]
		);

		$this->add_control(
			'features_icon_color',
			[
				'label' => __( 'Icon Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
				'{{WRAPPER}} .plan-feature .feature-icon' => 'color: {{VALUE}}',
				'{{WRAPPER}} .plan-feature .feature-icon svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'features_icon_size',
			[
				'label' => __( 'Icon Size', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'em', 'px', '%' ],
				'range' => [
					'em' => [
						'min' => 0.1,
						'max' => 10,
					],
					'px' => [
						'min' => 10,
						'max' => 500,
					],
					'%' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 14,
				],
				'selectors' => [
					'{{WRAPPER}} .plan-feature .feature-icon' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .plan-feature .feature-icon svg' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'features_icon_margin_right',
			[
				'label' => __( 'Icon spacing', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'em', 'px', '%' ],
				'default' => [
					'unit' => 'px',
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .plan-feature .feature-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_footer_style',
			[
				'label' => __( 'Footer', 'athemes-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'footer_text_heading',
			[
				'label' => __( 'Text', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'footer_text_color',
			[
				'label' => __( 'Text Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
				'{{WRAPPER}} .footer-text' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'footer_text_typography',
				'selector'  => '{{WRAPPER}} .footer-text',
			]
		);

		$this->add_control(
			'footer_button_heading',
			[
				'label' => __( 'Button', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->register_button_style_controls();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_ribbon_style',
			[
				'label' => __( 'Ribbon', 'athemes-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'ribbon_style!' => 'none',
				],
			]
		);

		$this->add_control(
			'ribbon_text_color',
			[
				'label' => __( 'Text Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
				'{{WRAPPER}} .plan-ribbon .ribbon-text' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'ribbon_text_typography',
				'selector'  => '{{WRAPPER}} .plan-ribbon .ribbon-text',
			]
		);

		$this->add_control(
			'ribbon_background_color',
			[
				'label' => __( 'Background Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .plan-ribbon .ribbon-text' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .ribbon-style-flag .ribbon-text::before' => 'border-left-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render icon list widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		
		$this->add_render_attribute( 'pricing-table', 'class', 'athemes-addons-pricing-table' );

		if ( ! empty( $settings['button_url']['url'] ) ) {
			$this->add_render_attribute( 'link', 'class', 'button' );
			$this->add_render_attribute( 'link', 'href', esc_url( $settings['button_url']['url'] ) );
			$this->add_render_attribute( 'link', 'target', $settings['button_url']['is_external'] ? '_blank' : '_self' );
		}
		?>

		<div <?php $this->print_render_attribute_string( 'pricing-table' ); ?> >
			<div class="pricing-table-inner">
				<?php if ( 'none' !== $settings['ribbon_style'] ) : ?>
				<div class="plan-ribbon ribbon-style-<?php echo esc_attr( $settings['ribbon_style'] ); ?>">
					<span class="ribbon-text"><?php echo esc_html( $settings['ribbon_text'] ); ?></span>
				</div>
				<?php endif; ?>		
				<div class="plan-icon">
					<?php if ( 'icon' === $settings['header_icon_type'] && ! empty( $settings['header_icon']['value'] ) ) : ?>
						<div><?php Icons_Manager::render_icon( $settings['header_icon'], [ 'aria-hidden' => 'true' ] ); ?></div>
					<?php elseif ( 'image' === $settings['header_icon_type'] && ! empty( $settings['header_image']['url'] ) ) : ?>
						<img src="<?php echo esc_url( $settings['header_image']['url'] ); ?>" alt="<?php echo esc_attr( $settings['header_title'] ); ?>">
					<?php endif; ?>
				</div>
				<div class="plan-header">
					<?php if ( ! empty( $settings['header_title'] ) ) : ?>
						<<?php echo esc_attr( $settings['header_title_tag'] ); ?> class="plan-title"><?php echo esc_html( $settings['header_title'] ); ?></<?php echo esc_attr( $settings['header_title_tag'] ); ?>>
					<?php endif; ?>
					<?php if ( ! empty( $settings['header_subtitle'] ) ) : ?>
						<div class="plan-subtitle"><?php echo esc_html( $settings['header_subtitle'] ); ?></div>
					<?php endif; ?>
				</div>
				<div class="plan-price">
					<?php if ( ! empty( $settings['full_price_amount'] ) ) : ?>
						<span class="full-price"><?php echo esc_html( $settings['full_price_amount'] ); ?></span>
					<?php endif; ?>
					
					<div class="price-amount">
					<?php if ( ! empty( $settings['price_currency'] ) ) : ?>
						<span class="currency"><?php echo esc_html( $settings['price_currency'] ); ?></span>
					<?php endif; ?>
					<?php if ( ! empty( $settings['price_amount'] ) ) : ?>
						<span class="price"><?php echo esc_html( $settings['price_amount'] ); ?></span>
					<?php endif; ?>
					</div>

					<?php if ( ! empty( $settings['price_period'] ) ) : ?>
						<span class="period"><?php echo esc_html( $settings['price_period'] ); ?></span>
					<?php endif; ?>
					<?php if ( ! empty( $settings['price_description'] ) ) : ?>
						<div class="price-description"><?php echo esc_html( $settings['price_description'] ); ?></div>
					<?php endif; ?>
				</div>
				<div class="plan-features">
					<?php if ( ! empty( $settings['features'] ) ) : ?>
						<div class="features-list">
							<?php foreach ( $settings['features'] as $index => $item ) : ?>
								<div class="plan-feature slashed-text-<?php echo esc_attr( $item['feature_slashed_text'] ); ?> elementor-repeater-item-<?php echo esc_attr($item['_id']); ?>">
									<?php if ( ! empty( $item['feature_icon']['value'] ) ) : ?>
										<span class="feature-icon">
											<?php Icons_Manager::render_icon( $item['feature_icon'], [ 'aria-hidden' => 'true' ] ); ?>
										</span>
									<?php endif; ?>
									<span class="feature-text"><?php echo esc_html( $item['feature_text'] ); ?></span>
									</div>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>
				<div class="plan-footer">
				<?php if ( 'yes' === $settings['display_button'] ) : ?>
					<div class="footer-button">
						<?php $this->render_button(); ?>
					</div>
				<?php endif; ?>
				<?php if ( ! empty( $settings['footer_text'] ) ) : ?>
					<div class="footer-text"><?php echo esc_html( $settings['footer_text'] ); ?></div>
				<?php endif; ?>					
				</div>
			</div>	
		</div>

		<?php
	}

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
}
Plugin::instance()->widgets_manager->register( new Pricing_Table() );