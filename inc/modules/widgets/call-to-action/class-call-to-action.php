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

use aThemes_Addons\Traits\Button_Trait;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Before After Image Widget.
 *
 * @since 1.0.0
 */
class Call_To_Action extends Widget_Base {

	use Button_Trait;

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
		return 'athemes-addons-call-to-action';
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
		return __( 'Call to action', 'athemes-addons-elementor' );
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
		return 'eicon-call-to-action aafe-elementor-icon';
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
		return [ 'button', 'call to action', 'athemes', 'addons', 'athemes addons' ];
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
			'section_image',
			[
				'label' => __( 'Image', 'athemes-addons-elementor' ),
			]
		);

		$this->add_control(
			'image',
			[
				'label' => esc_html__( 'Image', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'thumb',
				'label' => __( 'Image Size', 'athemes-addons-elementor' ),
				'exclude' => [ 'custom' ],
				'include' => [],
				'default' => 'large',
			]
		);

		$this->add_control(
			'image_position',
			[
				'label' => __( 'Image Position', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'inline',
				'options' => [
					'inline' => __( 'Inline', 'athemes-addons-elementor' ),
					'before' => __( 'Before', 'athemes-addons-elementor' ),
				],
				'prefix_class' => 'cta-image-position-',
				'condition' => [
					'_skin!' => 'athemes-addons-cta-banner',
				],
			]
		);

		$this->add_responsive_control(
			'image_alignment',
			[
				'label' => __( 'Image Alignment', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'0' => [
						'title' => __( 'Left', 'athemes-addons-elementor' ),
						'icon' => 'eicon-h-align-left',
					],
					'10' => [
						'title' => __( 'Right', 'athemes-addons-elementor' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'default' => '0',
				'selectors' => [
					'{{WRAPPER}} .call-to-action-image' => 'order: {{VALUE}};',
				],
				'condition' => [
					'image_position' => 'inline',
					'_skin!' => 'athemes-addons-cta-banner',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'athemes-addons-elementor' ),
			]
		);

		$this->add_control(
			'icon_type',
			[
				'label' => esc_html__( 'Icon Type', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'none' => esc_html__( 'None', 'athemes-addons-elementor' ),
					'icon' => esc_html__( 'Icon', 'athemes-addons-elementor' ),
					'image' => esc_html__( 'Image', 'athemes-addons-elementor' ),
				],
			]
		);      

		$this->add_control(
			'content_icon',
			[
				'label' => esc_html__( 'Icon', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-star',
					'library' => 'fa-solid',
				],              
				'skin' => 'inline',
				'condition' => [
					'icon_type' => 'icon',
				],
			]
		);

		$this->add_control(
			'icon_image',
			[
				'label'     => esc_html__( 'Image', 'athemes-addons-elementor' ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'icon_type' => 'image',
				],
			]
		);

		$this->add_control(
			'content_layout',
			[
				'label'     => esc_html__( 'Content layout', 'athemes-addons-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'block',
				'options'   => [
					'inline'   => esc_html__( 'Inline', 'athemes-addons-elementor' ),
					'block'    => esc_html__( 'Block', 'athemes-addons-elementor' ),
				],
				'separator' => 'before',
				'condition' => [
					'image_position' => 'before',
				],
			]
		);      

		$this->add_control(
			'before_title',
			[
				'label'         => esc_html__( 'Before title', 'athemes-addons-elementor' ),
				'type'          => Controls_Manager::TEXT,
			]
		);      

		$this->add_control(
			'title',
			[
				'label'         => esc_html__( 'Title', 'athemes-addons-elementor' ),
				'type'          => Controls_Manager::TEXT,
				'default'       => esc_html__( 'This is the heading', 'athemes-addons-elementor' ),
				'placeholder'   => esc_html__( 'Enter your title', 'athemes-addons-elementor' ),
			]
		);

		$this->add_control(
			'content',
			[
				'label'         => esc_html__( 'Content', 'athemes-addons-elementor' ),
				'type'          => Controls_Manager::WYSIWYG,
				'default'       => esc_html__( 'This is the content', 'athemes-addons-elementor' ),
				'placeholder'   => esc_html__( 'Enter your content', 'athemes-addons-elementor' ),
			]
		);

		$this->add_control(
			'title_html_tag',
			[
				'label'     => esc_html__( 'Title HTML Tag', 'athemes-addons-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'h2',
				'options'   => [
					'h1'    => esc_html__( 'H1', 'athemes-addons-elementor' ),
					'h2'    => esc_html__( 'H2', 'athemes-addons-elementor' ),
					'h3'    => esc_html__( 'H3', 'athemes-addons-elementor' ),
					'h4'    => esc_html__( 'H4', 'athemes-addons-elementor' ),
					'h5'    => esc_html__( 'H5', 'athemes-addons-elementor' ),
					'h6'    => esc_html__( 'H6', 'athemes-addons-elementor' ),
					'div'   => esc_html__( 'div', 'athemes-addons-elementor' ),
					'span'  => esc_html__( 'span', 'athemes-addons-elementor' ),
					'p'     => esc_html__( 'p', 'athemes-addons-elementor' ),
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'content_alignment',
			[
				'label' => __( 'Alignment', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'athemes-addons-elementor' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'athemes-addons-elementor' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'athemes-addons-elementor' ),
						'icon' => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => __( 'Justified', 'athemes-addons-elementor' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'default' => 'left',
				'selectors' => [
					'{{WRAPPER}} .call-to-action-content' => 'text-align: {{VALUE}};',
				],
				'prefix_class' => 'cta-content-align-',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'content_vertical_alignment',
			[
				'label' => __( 'Vertical Alignment', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'top' => [
						'title' => __( 'Top', 'athemes-addons-elementor' ),
						'icon' => 'eicon-v-align-top',
					],
					'middle' => [
						'title' => __( 'Middle', 'athemes-addons-elementor' ),
						'icon' => 'eicon-v-align-middle',
					],
					'bottom' => [
						'title' => __( 'Bottom', 'athemes-addons-elementor' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'default' => 'middle',
				'selectors_dictionary' => [
					'top' => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				],
				'selectors' => [
					'{{WRAPPER}} .call-to-action-content' => 'justify-content: {{VALUE}};',
				],
				'condition' => [
					'image_position' => 'inline',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_button',
			[
				'label' => __( 'Buttons', 'athemes-addons-elementor' ),
			]
		);

		$this->add_control(
			'number_of_buttons',
			[
				'label' => esc_html__( 'Number of Buttons', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => '2',
				'options' => [
					'1' => esc_html__( 'One', 'athemes-addons-elementor' ),
					'2' => esc_html__( 'Two', 'athemes-addons-elementor' ),
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'first_button_heading',
			[
				'label' => __( 'First Button', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'number_of_buttons' => '2',
				],
			]
		);  

		$this->register_button_content_controls( $args = array( 'class' => 'first_button', 'alignment_control_prefix_class' => 'button-align-' ) );

		$this->remove_control( 'first_button_align' );

		$this->add_control(
			'second_button_heading',
			[
				'label' => __( 'Second Button', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'number_of_buttons' => '2',
				],
			]
		);      

		$this->register_button_content_controls( $args = array( 'button_default_text' => __( 'Read more', 'athemes-addons-elementor' ), 'class' => 'second_button', 'alignment_control_prefix_class' => 'button-align-', 'section_condition' => array( 'number_of_buttons' => '2' ) ) );

		$this->remove_control( 'second_button_align' );

		$this->end_controls_section();

		$this->start_controls_section(
			'section_wrapper_style',
			[
				'label' => esc_html__( 'Wrapper', 'athemes-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'wrapper_height',
			[
				'label' => __( 'Height', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'vh' ],
				'range' => [
					'px' => [
						'min' => 50,
						'max' => 1000,
					],
					'vh' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-call-to-action .call-to-action-inner' => 'min-height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'wrapper_padding',
			[
				'label' => __( 'Padding', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .call-to-action-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],              
			]
		);

		$this->add_responsive_control(
			'wrapper_margin',
			[
				'label' => __( 'Margin', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-call-to-action' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],              
			]
		);

		$this->add_control(
			'wrapper_background_color',
			[
				'label' => __( 'Background Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-call-to-action' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'wrapper_border',
				'selector' => '{{WRAPPER}} .athemes-addons-call-to-action',
			]
		);

		$this->add_control(
			'wrapper_border_radius',
			[
				'label' => __( 'Border Radius', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-call-to-action' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],              
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'wrapper_box_shadow',
				'selector' => '{{WRAPPER}} .athemes-addons-call-to-action',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_image_style',
			[
				'label' => esc_html__( 'Image', 'athemes-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'image_overlay_color',
			[
				'label' => __( 'Overlay Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => 'rgba(0,0,0,0.5)',
				'selectors' => [
					'{{WRAPPER}} .cta-overlay' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'_skin' => 'athemes-addons-cta-banner',
				],
			]
		);

		$this->add_responsive_control(
			'image_width',
			[
				'label' => __( 'Width', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px','%' ],
				'range' => [
					'px' => [
						'min' => 50,
						'max' => 1000,
					],
					'%' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .call-to-action-image' => 'flex: 0 0 {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .call-to-action-content' => 'flex: 0 0 calc(100% - {{SIZE}}{{UNIT}});',
				],
				'condition' => [
					'image_position' => 'inline',
					'_skin!' => 'athemes-addons-cta-banner',
				],
			]
		);

		$this->add_responsive_control(
			'image_border_radius',
			[
				'label' => __( 'Border Radius', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px','%' ],
				'selectors' => [
					'{{WRAPPER}} .call-to-action-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],      
				'condition' => [
					'_skin!' => 'athemes-addons-cta-banner',
				],      
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'image_box_shadow',
				'selector' => '{{WRAPPER}} .call-to-action-image img',
				'condition' => [
					'_skin!' => 'athemes-addons-cta-banner',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content_style',
			[
				'label' => esc_html__( 'Content', 'athemes-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'content_padding',
			[
				'label' => __( 'Padding', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px','em','rem' ],
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-call-to-action .call-to-action-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],              
			]
		);

		$this->add_responsive_control(
			'content_max_width',
			[
				'label' => __( 'Max Width', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px','%' ],
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'range' => [
					'px' => [
						'min' => 50,
						'max' => 2000,
					],
					'%' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .call-to-action-content-inner' => 'max-width: {{SIZE}}{{UNIT}};',
				],              
			]
		);

		$this->add_control(
			'icon_style_heading',
			[
				'label' => __( 'Icon', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'condition' => [
					'icon_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => __( 'Icon Size', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px','em','rem' ],
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 100,
					],
					'em' => [
						'min' => 0.1,
						'max' => 50,
					],
					'rem' => [
						'min' => 0.1,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .call-to-action-content .content-icon div' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .call-to-action-content .content-icon svg' => 'width: {{SIZE}}{{UNIT}};max-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .call-to-action-content .content-icon img' => 'width: {{SIZE}}{{UNIT}};max-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'icon_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label' => __( 'Icon Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .call-to-action-content .content-icon div' => 'color: {{VALUE}};',
					'{{WRAPPER}} .call-to-action-content .content-icon svg' => 'fill: {{VALUE}};',
				],
				'condition' => [
					'icon_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'before_title_style_heading',
			[
				'label' => __( 'Before Title', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'before_title!' => '',
				],
			]
		);

		$this->add_control(
			'before_title_color',
			[
				'label' => __( 'Before Title Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .call-to-action-content .call-to-action-before-title' => 'color: {{VALUE}};',
				],
				'condition' => [
					'before_title!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'before_title_typography',
				'selector' => '{{WRAPPER}} .call-to-action-content .call-to-action-before-title',
				'condition' => [
					'before_title!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'before_title_margin',
			[
				'label' => __( 'Title Margin', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px','em','rem' ],
				'selectors' => [
					'{{WRAPPER}} .call-to-action-content .call-to-action-before-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],              
				'condition' => [
					'before_title!' => '',
				],
			]
		);

		$this->add_control(
			'title_style_heading',
			[
				'label' => __( 'Title', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => __( 'Title Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .call-to-action-content .call-to-action-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'selector' => '{{WRAPPER}} .call-to-action-content .call-to-action-title',
			]
		);

		$this->add_responsive_control(
			'title_margin',
			[
				'label' => __( 'Margin', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px','em','rem' ],
				'selectors' => [
					'{{WRAPPER}} .call-to-action-content .call-to-action-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],              
			]
		);

		$this->add_control(
			'content_style_heading',
			[
				'label' => __( 'Content', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'content_color',
			[
				'label' => __( 'Content Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .call-to-action-content .call-to-action-text' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography',
				'selector' => '{{WRAPPER}} .call-to-action-content .call-to-action-text',
			]
		);

		$this->add_responsive_control(
			'content_margin',
			[
				'label' => __( 'Margin', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px','em','rem' ],
				'selectors' => [
					'{{WRAPPER}} .call-to-action-content .call-to-action-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],              
			]
		);

		$this->end_controls_section();
		

		$this->start_controls_section(
			'section_button_style',
			[
				'label' => esc_html__( 'Buttons', 'athemes-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'first_button_heading_style',
			[
				'label' => __( 'First Button', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'number_of_buttons' => '2',
				],
			]
		);  

		$this->register_button_style_controls( $args = array( 'class' => 'first_button' ) );

		$this->add_control(
			'second_button_heading_style',
			[
				'label' => __( 'Second Button', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'number_of_buttons' => '2',
				],
			]
		);  

		$this->register_button_style_controls( $args = array( 'class' => 'second_button', 'background_color' => '#333333', 'section_condition' => array( 'number_of_buttons' => '2' ) ) );

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

		$this->add_render_attribute( 'wrapper', 'class', 'athemes-addons-call-to-action' );

		$this->add_render_attribute( 'wrapper', 'class', 'content-layout-' . $settings['content_layout'] );

		?>

		<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
			<div class="call-to-action-inner">
				<?php if ( ! empty( $settings['image']['url'] ) ) : ?>
				<div class="call-to-action-image">
					<?php Group_Control_Image_Size::print_attachment_image_html( $settings, 'thumb', 'image' ); ?>
				</div>
				<?php endif; ?>
				<div class="call-to-action-content">
					<div class="call-to-action-content-inner">
						<div class="content-icon">
							<?php if ( 'icon' === $settings['icon_type'] && ! empty( $settings['content_icon']['value'] ) ) : ?>
								<div><?php Icons_Manager::render_icon( $settings['content_icon'], [ 'aria-hidden' => 'true' ] ); ?></div>
							<?php elseif ( 'image' === $settings['icon_type'] && ! empty( $settings['icon_image']['url'] ) ) : ?>
								<img src="<?php echo esc_url( $settings['icon_image']['url'] ); ?>" alt="<?php echo esc_attr( $settings['title'] ); ?>">
							<?php endif; ?>
						</div>	
						<?php if ( ! empty( $settings['before_title'] ) ) : ?>
							<div class="call-to-action-before-title"><?php echo esc_html( $settings['before_title'] ); ?></div>
						<?php endif; ?>
						<?php if ( ! empty( $settings['title'] ) ) : ?>
							<<?php echo esc_html( $settings['title_html_tag'] ); ?> class="call-to-action-title"><?php echo wp_kses_post( $settings['title'] ); ?></<?php echo esc_html( $settings['title_html_tag'] ); ?>>
						<?php endif; ?>
						<?php if ( ! empty( $settings['content'] ) ) : ?>
							<div class="call-to-action-text"><?php echo wp_kses_post( $settings['content'] ); ?></div>
						<?php endif; ?>
					</div>
					<div class="call-to-action-buttons">
					<?php $this->render_button( $this, $class = 'first_button' ); ?>
					<?php
					if ( '2' === $settings['number_of_buttons'] ) :
						$this->render_button( $this, $class = 'second_button' );
					endif;
					?>
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
	 * @since 1.0.0
	 * @access protected
	 */
	protected function content_template() {
	}
}
Plugin::instance()->widgets_manager->register( new Call_To_Action() );