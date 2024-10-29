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
use Elementor\Repeater;
use Elementor\Icons_Manager;
use Elementor\Control_Media;
use Elementor\Group_Control_Css_Filter;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Advanced carousel widget.
 *
 * @since 1.0.0
 */
class Logo_Carousel extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve icon list widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'athemes-addons-logo-carousel';
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
		return __( 'Logo Carousel', 'athemes-addons-elementor' );
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
		return 'eicon-carousel aafe-elementor-icon';
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
	 * Get widget keywords.
	 */
	public function get_keywords() {
		return [ 'carousel', 'slider', 'image', 'gallery', 'image carousel', 'image slider', 'logo carousel', 'logo', 'logos', 'athemes', 'addons' ];
	}   

	/**
	 * Enqueue styles.
	 */
	public function get_style_depends() {
		return [ $this->get_name() . '-styles' ];
	}   

	/**
	 * Enqueue scripts.
	 */
	public function get_script_depends() {
		return [ 'swiper', $this->get_name() . '-scripts' ];
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

		//slides section
		$this->start_controls_section(
			'section_slides',
			[
				'label' => __( 'Logos', 'athemes-addons-elementor' ),
			]
		);

		//repeater control with tab to select image or video slide
		$repeater = new Repeater();

		//image slide
		$repeater->add_control(
			'image',
			[
				'label' => __( 'Choose image', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$repeater->add_control(
			'title',
			[
				'label' => __( 'Title', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'hover_image',
			[
				'label' => __( 'Different image on hover', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'athemes-addons-elementor' ),
				'label_off' => __( 'No', 'athemes-addons-elementor' ),
				'return_value' => 'yes',
				'separator' => 'before',
			]
		);

		$repeater->add_control(
			'hover_image_image',
			[
				'label' => __( 'Choose image', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'hover_image' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'image_link_to',
			[
				'label' => __( 'Link to', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => __( 'None', 'athemes-addons-elementor' ),
					'file' => __( 'Media File', 'athemes-addons-elementor' ),
					'custom' => __( 'Custom URL', 'athemes-addons-elementor' ),
				],
				'default' => '',
				'separator' => 'before',
			]
		);

		$repeater->add_control(
			'image_link',
			[
				'label' => __( 'Link', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'athemes-addons-elementor' ),
				'default' => [
					'url' => '',
				],
				'condition' => [
					'image_link_to' => 'custom',
				],
			]
		);

		//the repeater
		$this->add_control(
			'slides',
			[
				'label' => __( 'Logos', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::REPEATER,
				'default' => [
					[
						'image' => [ 'url' => Utils::get_placeholder_image_src() ],
					],
					[
						'image' => [ 'url' => Utils::get_placeholder_image_src() ],
					],
					[
						'image' => [ 'url' => Utils::get_placeholder_image_src() ],
					],
					[
						'image' => [ 'url' => Utils::get_placeholder_image_src() ],
					],
					[
						'image' => [ 'url' => Utils::get_placeholder_image_src() ],
					],
				],
				'fields' => $repeater->get_controls(),
				'title_field' => '{{{ title }}}',
			]
		);
		
		//responsive slides to show
		$this->add_responsive_control(
			'slides_to_show',
			[
				'label' => __( 'Columns', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					1 => __( '1', 'athemes-addons-elementor' ),
					2 => __( '2', 'athemes-addons-elementor' ),
					3 => __( '3', 'athemes-addons-elementor' ),
					4 => __( '4', 'athemes-addons-elementor' ),
					5 => __( '5', 'athemes-addons-elementor' ),
					6 => __( '6', 'athemes-addons-elementor' ),
				],
				'desktop_default' => 4,
				'tablet_default' => 2,
				'mobile_default' => 1,
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		//settings
		$this->start_controls_section(
			'section_settings',
			[
				'label' => __( 'Settings', 'athemes-addons-elementor' ),
			]
		);

		//image size
		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'image_size',
				'default' => 'large',
			]
		);

		//object fit
		$this->add_control(
			'object_fit',
			[
				'label' => __( 'Image Fit', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'cover'     => __( 'Cover', 'athemes-addons-elementor' ),
					'contain'   => __( 'Contain', 'athemes-addons-elementor' ),
				],
				'default' => 'contain',
				'selectors_dictionary' => [
					'cover' => 'object-fit:cover;height:100%',
				],
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-logo-carousel .slide-image img' => '{{VALUE}};',
				],
			]
		);

		//responsive image height
		$this->add_responsive_control(
			'image_height',
			[
				'label' => __( 'Image Height', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'vh' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
					'vh' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 350,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-logo-carousel .slide-image' => 'height: {{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'object_fit' => 'cover',
				],
			]
		);      

		//show arrows
		$this->add_control(
			'show_arrows',
			[
				'label' => __( 'Arrows', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'athemes-addons-elementor' ),
				'label_off' => __( 'Hide', 'athemes-addons-elementor' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'separator' => 'before',
			]
		);

		//show dots
		$this->add_control(
			'show_dots',
			[
				'label' => __( 'Dots', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'athemes-addons-elementor' ),
				'label_off' => __( 'Hide', 'athemes-addons-elementor' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'image_gap',
			[
				'label' => __( 'Image Gap', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 30,
					'unit' => 'px',
				],
				'separator' => 'before',
				'render_type' => 'template',
			]
		);

		//transition speed
		$this->add_control(
			'transition_speed',
			[
				'label' => __( 'Transition Speed', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 500,
			]
		);

		//autoplay
		$this->add_control(
			'autoplay',
			[
				'label' => __( 'Autoplay', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'On', 'athemes-addons-elementor' ),
				'label_off' => __( 'Off', 'athemes-addons-elementor' ),
				'return_value' => 'true',
				'default' => 'true',
			]
		);

		//autoplay speed
		$this->add_control(
			'autoplay_speed',
			[
				'label' => __( 'Autoplay Speed', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 5000,
				'condition' => [
					'autoplay' => 'true',
				],
			]
		);

		//infinite loop
		$this->add_control(
			'infinite',
			[
				'label' => __( 'Infinite Loop', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'On', 'athemes-addons-elementor' ),
				'label_off' => __( 'Off', 'athemes-addons-elementor' ),
				'return_value' => 'true',
				'frontend_available' => true,
			]
		);

		//pause on hover
		$this->add_control(
			'pause_on_hover',
			[
				'label' => __( 'Pause on Hover', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'On', 'athemes-addons-elementor' ),
				'label_off' => __( 'Off', 'athemes-addons-elementor' ),
				'return_value' => 'true',
				'default' => 'true',
			]
		);

		//end section
		$this->end_controls_section();

		$this->start_controls_section(
			'section_wrapper_style',
			[
				'label' => __( 'Wrapper', 'athemes-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'wrapper_padding',
			[
				'label' => __( 'Padding', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-logo-carousel-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .athemes-addons-logo-carousel-container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],              
			]
		);

		$this->add_control(
			'wrapper_background_color',
			[
				'label' => __( 'Background Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-logo-carousel-container' => 'background-color: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'wrapper_border',
				'label'     => esc_html__( 'Border', 'athemes-addons-elementor' ),
				'selector'  => '{{WRAPPER}} .athemes-addons-logo-carousel-container',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'wrapper_border_radius',
			[
				'label' => __( 'Border Radius', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,             
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-logo-carousel-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],              
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'wrapper_box_shadow',
				'label' => __( 'Box Shadow', 'athemes-addons-elementor' ),
				'selector' => '{{WRAPPER}} .athemes-addons-logo-carousel-container',
			]
		);

		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_style_image',
			[
				'label' => __( 'Image', 'athemes-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_image' );

		$this->start_controls_tab(
			'tab_image_normal',
			[
				'label' => __( 'Normal', 'athemes-addons-elementor' ),
			]
		);

		$this->add_control(
			'image_bg_color',
			[
				'label' => __( 'Background Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-logo-carousel .slide-image' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'image_border_radius',
			[
				'label' => __( 'Image Border Radius', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-logo-carousel .slide-image,{{WRAPPER}} .athemes-addons-logo-carousel .slide-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'image_border',
				'label' => __( 'Border', 'athemes-addons-elementor' ),
				'selector' => '{{WRAPPER}} .athemes-addons-logo-carousel .slide-image',
			]
		);

		$this->add_responsive_control(
			'image_padding',
			[
				'label' => __( 'Padding', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-logo-carousel .slide-image' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],              
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'image_box_shadow',
				'label' => __( 'Box Shadow', 'athemes-addons-elementor' ),
				'selector' => '{{WRAPPER}} .athemes-addons-logo-carousel .slide-image',
			]
		);

		$this->add_control(
			'image_opacity',
			[
				'label' => __( 'Opacity', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-logo-carousel .slide-image img' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'image_css_filters',
				'selector' => '{{WRAPPER}} .athemes-addons-logo-carousel .slide-image img',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_image_hover',
			[
				'label' => __( 'Hover', 'athemes-addons-elementor' ),
			]
		);

		$this->add_control(
			'image_bg_color_hover',
			[
				'label' => __( 'Background Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-logo-carousel .slide-image:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'image_border_radius_hover',
			[
				'label' => __( 'Image Border Radius', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-logo-carousel .slide-image:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'image_border_hover',
				'label' => __( 'Border', 'athemes-addons-elementor' ),
				'selector' => '{{WRAPPER}} .athemes-addons-logo-carousel .slide-image:hover',
			]
		);

		$this->add_responsive_control(
			'image_padding_hover',
			[
				'label' => __( 'Padding', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-logo-carousel .slide-image:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],              
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'image_box_shadow_hover',
				'label' => __( 'Box Shadow', 'athemes-addons-elementor' ),
				'selector' => '{{WRAPPER}} .athemes-addons-logo-carousel .slide-image:hover',
			]
		);

		$this->add_control(
			'image_opacity_hover',
			[
				'label' => __( 'Opacity', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-logo-carousel .slide-image:hover img' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'image_css_filters_hover',
				'selector' => '{{WRAPPER}} .athemes-addons-logo-carousel .slide-image:hover img',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_title_text',
			[
				'label' => __( 'Titles', 'athemes-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		//title color
		$this->add_control(
			'title_color',
			[
				'label' => __( 'Title Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-logo-carousel .slide-title' => 'color: {{VALUE}};',
				],
			]
		);

		//title typography
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'selector' => '{{WRAPPER}} .athemes-addons-logo-carousel .slide-title',
			]
		);

		//end section
		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_arrows',
			[
				'label' => __( 'Arrows', 'athemes-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		//arrows color
		$this->add_control(
			'arrows_color',
			[
				'label' => __( 'Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-logo-carousel .swiper-button-prev svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .athemes-addons-logo-carousel .swiper-button-next svg' => 'fill: {{VALUE}};',
				],
			]
		);

		//arrows background color
		$this->add_control(
			'arrows_bg_color',
			[
				'label' => __( 'Background Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-logo-carousel .swiper-button-prev' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .athemes-addons-logo-carousel .swiper-button-next' => 'background-color: {{VALUE}};',
				],
			]
		);

		//arrows color hover
		$this->add_control(
			'arrows_color_hover',
			[
				'label' => __( 'Color Hover', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-logo-carousel .swiper-button-prev:hover svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .athemes-addons-logo-carousel .swiper-button-next:hover svg' => 'fill: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		//arrows background color hover
		$this->add_control(
			'arrows_bg_color_hover',
			[
				'label' => __( 'Background Color Hover', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-logo-carousel .swiper-button-prev:hover' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .athemes-addons-logo-carousel .swiper-button-next:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		//end section
		$this->end_controls_section();

		//dots style section
		$this->start_controls_section(
			'section_style_dots',
			[
				'label' => __( 'Dots', 'athemes-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		//dots color
		$this->add_control(
			'dots_color',
			[
				'label' => __( 'Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-bullet' => 'background-color: {{VALUE}};',
				],
			]
		);

		//dots color active
		$this->add_control(
			'dots_color_active',
			[
				'label' => __( 'Color Active', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-bullet-active' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'dots_height',
			[
				'label' => __( 'Height', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 20,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-bullet' => 'height: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'dots_width',
			[
				'label' => __( 'Width', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 20,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'dots_radius',
			[
				'label' => __( 'Border Radius', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-bullet' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'dots_spacing',
			[
				'label' => __( 'Spacing', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 20,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-bullet' => 'margin: 0 {{SIZE}}{{UNIT}};',
				],
			]
		);


		//end section
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

		$this->lightbox_slide_index = 0;

		$this->add_render_attribute( 'logo-carousel-container', 'class', 'athemes-addons-logo-carousel-container' );
		$this->add_render_attribute( 'logo-carousel', 'class', 'swiper-container athemes-addons-logo-carousel' );
		$this->add_render_attribute( 'logo-carousel', 'data-gap', esc_attr( $settings['image_gap']['size'] ) );
		$this->add_render_attribute( 'logo-carousel', 'data-autoplay', esc_attr( $settings['autoplay'] ) );
		$this->add_render_attribute( 'logo-carousel', 'data-autoplay-speed', esc_attr( $settings['autoplay_speed'] ) );
		$this->add_render_attribute( 'logo-carousel', 'data-infinite', esc_attr( $settings['infinite'] ) );
		$this->add_render_attribute( 'logo-carousel', 'data-pause-on-hover', esc_attr( $settings['pause_on_hover'] ) );
		$this->add_render_attribute( 'logo-carousel', 'data-transition-speed', esc_attr( $settings['transition_speed'] ) );
		$this->add_render_attribute( 'logo-carousel', 'data-arrows', esc_attr( $settings['show_arrows'] ) );
		$this->add_render_attribute( 'logo-carousel', 'data-dots', esc_attr( $settings['show_dots'] ) );
		$this->add_render_attribute( 'logo-carousel', 'data-items', isset( $settings['slides_to_show'] ) && '' !== $settings['slides_to_show'] ? esc_attr( $settings['slides_to_show'] ) : 4 );
		$this->add_render_attribute( 'logo-carousel', 'data-items-tablet', isset( $settings['slides_to_show_tablet'] ) ? esc_attr( $settings['slides_to_show_tablet'] ) : 2 );
		$this->add_render_attribute( 'logo-carousel', 'data-items-mobile', isset( $settings['slides_to_show_mobile'] ) ? esc_attr( $settings['slides_to_show_mobile'] ) : 1 );
		?>
		<div <?php $this->print_render_attribute_string( 'logo-carousel-container' ); ?>>
			<div <?php $this->print_render_attribute_string( 'logo-carousel' ); ?>>
				<div class="swiper-wrapper">
					<?php foreach ( $settings['slides'] as $index => $slide ) : ?>
						<div class="swiper-slide">
							<div class="slide-image hover-image-<?php echo esc_attr( $slide['hover_image'] ); ?>">
								<?php $this->render_image_slide(  $slide, $index, $settings ); ?>
							</div>

							<?php if ( !empty( $slide['title'] ) ) : ?>
							<h3 class="slide-title"><?php echo esc_html( $slide['title'] ); ?></h3>
							<?php endif; ?>
						</div>
					<?php endforeach; ?>
				</div>	
				<?php if ( 'yes' === $settings['show_arrows'] ) : ?>
				<div class="swiper-button-prev"><svg xmlns="http://www.w3.org/2000/svg" width="21" height="40" fill="none"><path d="M3.589 20 20.564 2.556a1.498 1.498 0 1 0-2.149-2.09L.425 18.954a1.5 1.5 0 0 0 0 2.09l17.99 18.49a1.5 1.5 0 1 0 2.149-2.091L3.587 20h.002Z"/></svg></div>							
				<div class="swiper-button-next"><svg xmlns="http://www.w3.org/2000/svg" width="21" height="40" fill="none"><path d="M17.411 20 .436 2.556A1.5 1.5 0 1 1 2.585.466l17.99 18.489a1.5 1.5 0 0 1 0 2.09l-17.99 18.49a1.498 1.498 0 1 1-2.149-2.091L17.413 20h-.002Z"/></svg></div>
				<?php endif; ?>					
			</div>

			<?php if ( 'yes' === $settings['show_dots'] ) : ?>
				<div class="logo-carousel-pagination"></div>
			<?php endif; ?>
		</div>

		<?php
	}

	/**
	 * Image
	 */
	protected function render_image( $slide, $settings ) {

		if ( !empty( $slide['image']['url'] ) ) :
			$image_url = Group_Control_Image_Size::get_attachment_image_src( $slide['image']['id'], 'image_size', $settings ); 
			
			if ( !empty( $slide['image']['alt'] ) ) {
				$this->add_render_attribute( 'image', 'alt', esc_attr( $slide['image']['alt'] ) );
			}

			if ( empty( $image_url ) ) {
				$image_url = $slide['image']['url'];
			}
			?>

			<img class="carousel-image" <?php $this->print_render_attribute_string( 'image' ); ?> src="<?php echo esc_url( $image_url ); ?>">
		<?php endif;

		if ( 'yes' === $slide['hover_image'] && !empty( $slide['hover_image_image']['url'] ) ) :
			$hover_image_url = Group_Control_Image_Size::get_attachment_image_src( $slide['hover_image_image']['id'], 'image_size', $settings ); 
			
			if ( !empty( $slide['hover_image_image']['alt'] ) ) {
				$this->add_render_attribute( 'hover_image', 'alt', esc_attr( $slide['hover_image_image']['alt'] ) );
			}

			if ( empty( $hover_image_url ) ) {
				$hover_image_url = $slide['hover_image_image']['url'];
			}
			?>

			<img class="hover-image" <?php $this->print_render_attribute_string( 'hover_image' ); ?> src="<?php echo esc_url( $hover_image_url ); ?>">
		<?php endif;
	}

	/**
	 * Render image slide
	 */
	protected function render_image_slide( $slide, $index, $settings ) {

		if ( 'file' === $slide['image_link_to'] ) {
			$this->add_render_attribute( 'image_slide_' . $index, 'class', 'elementor-clickable' );
			$this->add_render_attribute( 'image_slide_' . $index, 'href', $slide['image']['url'] );
			$this->add_render_attribute( 'image_slide_' . $index, 'data-elementor-lightbox-slideshow', $this->get_id() );

			$this->add_lightbox_data_attributes( 'image_slide_' . $index, $slide['image']['id'], 'yes', $this->get_id() );
	
			echo '<a ' . $this->get_render_attribute_string( 'image_slide_' . $index ) . '>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
											
			$this->render_image( $slide, $settings );

			echo '</a>';            
		} elseif ( 'custom' === $slide['image_link_to'] ) {
			$this->add_render_attribute( 'image_slide_' . $index, 'href', $slide['image_link'] );
	
			echo '<a ' . $this->get_render_attribute_string( 'image_slide_' . $index ) . '>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
											
			$this->render_image( $slide, $settings );

			echo '</a>';            
		} else {
			$this->render_image( $slide, $settings );
		}
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
Plugin::instance()->widgets_manager->register( new Logo_Carousel() );