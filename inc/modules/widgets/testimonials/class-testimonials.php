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
use aThemes_Addons_SVG_Icons;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Advanced carousel widget.
 *
 * @since 1.0.0
 */
class Testimonials extends Widget_Base {

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
		return 'athemes-addons-testimonials';
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
		return __( 'Testimonials', 'athemes-addons-elementor' );
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
		return 'eicon-testimonial-carousel aafe-elementor-icon';
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
		return [ 'testimonial', 'testimonials', 'athemes addons', 'athemes', 'addons' ];
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
		return [ $this->get_name() . '-scripts' ];
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
				'label' => __( 'Testimonials', 'athemes-addons-elementor' ),
			]
		);

		$repeater = new Repeater();

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
				'label'         => __( 'Review title', 'athemes-addons-elementor' ),
				'type'          => Controls_Manager::TEXT,
				'placeholder'   => __( 'Enter the review title', 'athemes-addons-elementor' ),
				'separator'     => 'after',
			]
		);      

		$repeater->add_control(
			'name',
			[
				'label' => __( 'Name', 'athemes-addons-elementor' ),
				'type'  => Controls_Manager::TEXT,
				'placeholder' => 'John Doe',
			]
		);

		$repeater->add_control(
			'position',
			[
				'label' => __( 'Position', 'athemes-addons-elementor' ),
				'type'  => Controls_Manager::TEXT,
				'placeholder' => 'CEO, Company Name',
				'separator' => 'after',
			]
		);

		$repeater->add_control(
			'content',
			[
				'label'         => __( 'Content', 'athemes-addons-elementor' ),
				'type'          => Controls_Manager::WYSIWYG,
				'placeholder'   => __( 'Add the review content here', 'athemes-addons-elementor' ),
			]
		);

		$repeater->add_control(
			'rating',
			[
				'label' => __( 'Rating', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%' ],        
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 5,
						'step' => 0.1,
					],                  
				],
				'default' => [
					'unit' => '%',
					'size' => 5,
				],      
			]
		);

		$repeater->add_control(
			'review_date',
			[
				'label' => __( 'Review date', 'athemes-addons-elementor' ),
				'type'  => Controls_Manager::TEXT,
				'separator' => 'after',
			]
		);      

		$repeater->add_control(
			'link',
			[
				'label' => __( 'Link', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'athemes-addons-elementor' ),
			]
		);

		$this->add_control(
			'slides',
			[
				'label' => __( 'Slides', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::REPEATER,
				'default' => [
					[
						'image'     => [ 'url' => Utils::get_placeholder_image_src() ],
						'title'     => __( 'Awesome plugin!', 'athemes-addons-elementor' ),
						'name'      => 'John Doe',
						'position'  => 'CEO @ Cool Company',
						'content'   => __( 'aThemes Addons for Elementor enhances my workflow seamlessly. Impressive support, swift loading, and impeccable code quality. A must-have!', 'athemes-addons-elementor' ),
					],
					[
						'image'     => [ 'url' => Utils::get_placeholder_image_src() ],
						'title'     => __( 'Great support!', 'athemes-addons-elementor' ),
						'name'      => 'Jane Doe',
						'position'  => 'CTO @ Cool Company',
						'content'   => __( 'Incredible support from aThemes Addons team. Noticed a significant speed boost on my site. Clean code makes customization a breeze.', 'athemes-addons-elementor' ),
					],
					[
						'image'     => [ 'url' => Utils::get_placeholder_image_src() ],
						'title'     => __( 'Excellent work!', 'athemes-addons-elementor' ),
						'name'      => 'John Smith',
						'position'  => 'CEO @ Great Company',
						'content'   => __( 'aThemes Addons for Elementor exceeded my expectations. Top-notch support, lightning-fast speed, and well-structured codebase. Highly recommended for all Elementor users!', 'athemes-addons-elementor' ),
					],
				],
				'fields' => $repeater->get_controls(),
				'title_field' => '{{{ title }}}',
			]
		);
		
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
				'desktop_default' => 2,
				'tablet_default' => 2,
				'mobile_default' => 1,
				'separator' => 'before',
				'condition' => [
					'_skin!' => 'athemes-addons-testimonials-side-by-side',
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

		//image size
		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'image_size',
				'default' => 'large',
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

		//transition speed
		$this->add_control(
			'transition_speed',
			[
				'label' => __( 'Transition Speed', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 500,
				'separator' => 'before',
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
				'default' => 'true',
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

		$this->add_control(
			'show_thumbs',
			[
				'label' => __( 'Show thumbs', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',  
				'return_value' => 'yes',
			]
		);      

		//end section
		$this->end_controls_section();

		$this->start_controls_section(
			'section_wrapper_style',
			[
				'label' => __( 'Wrapper', 'athemes-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'wrapper_padding',
			[
				'label' => __( 'Padding', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-testimonials' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],              
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'wrapper_border',
				'label' => __( 'Border', 'athemes-addons-elementor' ),
				'selector' => '{{WRAPPER}} .athemes-addons-testimonials',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'wrapper_border_radius',
			[
				'label' => __( 'Border Radius', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-testimonials' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],              
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'      => 'wrapper_box_shadow',
				'selector'  => '{{WRAPPER}} .athemes-addons-testimonials',
			]
		);
		
		$this->add_responsive_control(
			'carousel_width',
			[
				'label' => __( 'Max. carousel width', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 85,
					'unit' => '%',
				],
				'range' => [
					'%' => [
						'min' => 50,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-testimonials' => 'max-width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
				'render_type' => 'template',
			]
		);

		$this->end_controls_section();      

		$this->start_controls_section(
			'section_style_items',
			[
				'label' => __( 'Items', 'athemes-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'items_padding',
			[
				'label' => __( 'Padding', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-testimonials .swiper-slide' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .athemes-addons-testimonials[data-id="athemes-addons-testimonials-side-by-side"] .author-image' => 'margin: -{{TOP}}{{UNIT}} 0 -{{BOTTOM}}{{UNIT}} -{{LEFT}}{{UNIT}};',
				],              
			]
		);

		$this->add_control(
			'items_bg_color',
			[
				'label' => __( 'Background Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-testimonials .swiper-slide' => 'background-color: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'items_border',
				'label' => __( 'Border', 'athemes-addons-elementor' ),
				'selector' => '{{WRAPPER}} .athemes-addons-testimonials .swiper-slide',
			]
		);

		$this->add_control(
			'items_border_color_hover',
			[
				'label' => __( 'Border hover color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-testimonials .swiper-slide:hover' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .athemes-addons-testimonials .swiper-slide:hover .quote-icon' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'items_border_radius',
			[
				'label' => __( 'Border Radius', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-testimonials .swiper-slide' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],              
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'      => 'items_box_shadow',
				'selector'  => '{{WRAPPER}} .athemes-addons-testimonials .swiper-slide',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_quote_icon',
			[
				'label' => __( 'Quote Icon', 'athemes-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'quote_icon_size',
			[
				'label' => __( 'Size', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 80,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-testimonials .quote-icon' => '--icon-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'quote_icon_border',
				'label' => __( 'Border', 'athemes-addons-elementor' ),
				'selector' => '{{WRAPPER}} .athemes-addons-testimonials .quote-icon',
				'separator' => 'after',
			]
		);

		$this->add_control(
			'quote_icon_color',
			[
				'label' => __( 'Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-testimonials .quote-icon svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'quote_icon_bg_color',
			[
				'label' => __( 'Background Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-testimonials .quote-icon' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'      => 'quote_icon_box_shadow',
				'selector'  => '{{WRAPPER}} .athemes-addons-testimonials .quote-icon',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_content',
			[
				'label' => __( 'Content', 'athemes-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => __( 'Title Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-testimonials .review-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'selector' => '{{WRAPPER}} .athemes-addons-testimonials .review-title',
			]
		);

		$this->add_control(
			'name_color',
			[
				'label' => __( 'Name Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-testimonials .author-name' => 'color: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'name_typography',
				'selector' => '{{WRAPPER}} .athemes-addons-testimonials .author-name',
			]
		);

		$this->add_control(
			'position_color',
			[
				'label' => __( 'Position Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-testimonials .author-position' => 'color: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'position_typography',
				'selector' => '{{WRAPPER}} .athemes-addons-testimonials .author-position',
			]
		);

		$this->add_control(
			'content_color',
			[
				'label' => __( 'Content Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-testimonials .review-content' => 'color: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography',
				'selector' => '{{WRAPPER}} .athemes-addons-testimonials .review-content',
			]
		);

		$this->add_control(
			'rating_color',
			[
				'label' => __( 'Rating Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-testimonials .review-icon-marked svg' => 'fill: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

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
					'{{WRAPPER}} .athemes-addons-testimonials .swiper-button-prev svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .athemes-addons-testimonials .swiper-button-next svg' => 'fill: {{VALUE}};',
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
					'{{WRAPPER}} .athemes-addons-testimonials .swiper-button-prev' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .athemes-addons-testimonials .swiper-button-next' => 'background-color: {{VALUE}};',
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
					'{{WRAPPER}} .athemes-addons-testimonials .swiper-button-prev:hover svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .athemes-addons-testimonials .swiper-button-next:hover svg' => 'fill: {{VALUE}};',
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
					'{{WRAPPER}} .athemes-addons-testimonials .swiper-button-prev:hover' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .athemes-addons-testimonials .swiper-button-next:hover' => 'background-color: {{VALUE}};',
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

		$this->start_controls_section(
			'section_style_thumbs',
			[
				'label' => __( 'Thumbs', 'athemes-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_thumbs' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'thumbs_spacing',
			[
				'label' => __( 'Spacing', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'default' => [
					'size' => 30,
				],
				'range' => [
					'px' => [
						'min' => -60,
						'max' => 60,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-testimonials-thumbs' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'thumbs_max_width',
			[
				'label' => __( 'Max width', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'units' => [ '%' ],
				'default' => [
					'size' => 30,
					'unit' => '%',
				],
				'range' => [
					'%' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-testimonials-thumbs' => 'max-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'show_thumbs' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'thumbs_height',
			[
				'label' => __( 'Height', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 80,
				],
				'range' => [
					'px' => [
						'min' => 40,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-testimonials-thumbs img' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'show_thumbs' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'thumbs_width',
			[
				'label' => __( 'Width', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 80,
				],
				'range' => [
					'px' => [
						'min' => 40,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-testimonials-thumbs img' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'show_thumbs' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'thumbs_border_radius',
			[
				'label' => __( 'Border radius', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-testimonials-thumbs img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],              
				'condition' => [
					'show_thumbs' => 'yes',
				],
			]
		);

		$this->add_control(
			'active_thumb_border_color',
			[
				'label' => __( 'Active thumb border color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#dddddd',
				'selectors' => [
					'{{WRAPPER}} .swiper-thumb-wrapper .swiper-slide-thumb-active img' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'show_thumbs' => 'yes',
				],
			]
		);

		$this->add_control(
			'grayscale_effect',
			[
				'label' => __( 'Grayscale effect', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',  
				'return_value' => 'yes',
				'condition' => [
					'show_thumbs' => 'yes',
				],
				'prefix_class' => 'aafe-grayscale-effect-',
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

		$this->lightbox_slide_index = 0;

		$this->add_render_attribute( 'testimonials-container', 'class', 'athemes-addons-testimonials-container' );
		$this->add_render_attribute( 'testimonials', 'class', 'swiper-container athemes-addons-testimonials' );
		$this->add_render_attribute( 'testimonials', 'data-id', esc_attr( $this->get_id() ) );
		$this->add_render_attribute( 'testimonials', 'data-autoplay', esc_attr( $settings['autoplay'] ) );
		$this->add_render_attribute( 'testimonials', 'data-autoplay-speed', esc_attr( $settings['autoplay_speed'] ) );
		$this->add_render_attribute( 'testimonials', 'data-infinite', esc_attr( $settings['infinite'] ) );
		$this->add_render_attribute( 'testimonials', 'data-pause-on-hover', esc_attr( $settings['pause_on_hover'] ) );
		$this->add_render_attribute( 'testimonials', 'data-transition-speed', esc_attr( $settings['transition_speed'] ) );
		$this->add_render_attribute( 'testimonials', 'data-arrows', esc_attr( $settings['show_arrows'] ) );
		$this->add_render_attribute( 'testimonials', 'data-dots', esc_attr( $settings['show_dots'] ) );
		$this->add_render_attribute( 'testimonials', 'data-items', isset( $settings['slides_to_show'] ) && '' !== $settings['slides_to_show'] ? esc_attr( $settings['slides_to_show'] ) : 2 );
		$this->add_render_attribute( 'testimonials', 'data-items-tablet', isset( $settings['slides_to_show_tablet'] ) ? esc_attr( $settings['slides_to_show_tablet'] ) : 2 );
		$this->add_render_attribute( 'testimonials', 'data-items-mobile', isset( $settings['slides_to_show_mobile'] ) ? esc_attr( $settings['slides_to_show_mobile'] ) : 1 );       
		?>
		<div <?php $this->print_render_attribute_string( 'testimonials-container' ); ?>>
			<div <?php $this->print_render_attribute_string( 'testimonials' ); ?>>
				<div class="swiper-wrapper">
					<?php $c = 0; ?>
					<?php foreach ( $settings['slides'] as $index => $slide ) : ?>
						<div class="swiper-slide">
							<?php if ( ! empty( $slide['link']['url'] ) ) : ?>
								<?php $this->add_render_attribute( 'link-' . $c, 'class', 'overlay-link' ); ?>
								<?php $this->add_render_attribute( 'link-' . $c, 'href', esc_url( $slide['link']['url'] ) ); ?>
								<?php $this->add_render_attribute( 'link-' . $c, 'target', $slide['link']['is_external'] ? '_blank' : '_self' ); ?>
								<a <?php $this->print_render_attribute_string( 'link-' . $c ); ?>><span class="screen-reader-text"><?php echo esc_html( $slide['name'] ); ?></span></a>
							<?php endif; ?>

							<div class="quote-icon">
								<?php aThemes_Addons_SVG_Icons::get_svg_icon( 'icon-quote' ); ?>
							</div>

							<?php if ( !empty( $slide['title'] ) ) : ?>
								<h3 class="review-title"><?php echo esc_html( $slide['title'] ); ?></h3>
							<?php endif; ?>

							<?php if ( !empty( $slide['rating']['size'] ) ) : ?>
								<?php $this->render_rating( $slide, $settings ); ?>		
							<?php endif; ?>

							<?php if ( !empty( $slide['content'] ) ) : ?>
								<div class="review-content"><?php echo wp_kses_post( $slide['content'] ); ?></div>
							<?php endif; ?>

							<?php if ( !empty( $slide['review_date'] ) ) : ?>
								<div class="review-date"><?php echo esc_html( $slide['review_date'] ); ?></div>
							<?php endif; ?>

							<div class="author-info">
								<div class="author-image">
									<?php $this->render_image(  $slide, $settings ); ?>
								</div>

								<div class="author-data">
								<?php if ( !empty( $slide['name'] ) ) : ?>
									<div class="author-name"><?php echo esc_html( $slide['name'] ); ?></div>
								<?php endif; ?>
								<?php if ( !empty( $slide['position'] ) ) : ?>
									<div class="author-position"><?php echo esc_html( $slide['position'] ); ?></div>
								<?php endif; ?>
								</div>
							</div>
						</div>
						<?php $c++; ?>
					<?php endforeach; ?>
				</div>

				<?php if ( 'yes' === $settings['show_arrows'] ) : ?>
					<div class="swiper-button-prev"><?php aThemes_Addons_SVG_Icons::get_svg_icon( 'icon-arrow-left' ); ?></div>							
					<div class="swiper-button-next"><?php aThemes_Addons_SVG_Icons::get_svg_icon( 'icon-arrow-right' ); ?></div>
				<?php endif; ?>				
			</div>

			<?php if ( 'yes' === $settings['show_dots'] ) : ?>
				<div class="testimonials-pagination"></div>
			<?php endif; ?>
		</div>
		<?php if ( 'yes' === $settings['show_thumbs'] ) : ?>
		<div class="athemes-addons-testimonials-thumbs">
			<div class="swiper-wrapper swiper-thumb-wrapper">
				<?php foreach ( $settings['slides'] as $index => $item ) : ?>
					<div class="swiper-slide">
						<?php if ( $item['image']['url'] ) : ?>
							<?php $this->render_image(  $item, $settings ); ?>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<?php endif; ?>	
		<?php
	}

	/**
	 * Image
	 */
	public function render_image( $slide, $settings ) {

		if ( !empty( $slide['image']['url'] ) ) :
			$image_url = Group_Control_Image_Size::get_attachment_image_src( $slide['image']['id'], 'image_size', $settings ); 
			
			if ( !empty( $slide['image']['alt'] ) ) {
				$this->add_render_attribute( 'image', 'alt', $slide['image']['alt'] );
			}

			if ( empty( $image_url ) ) {
				$image_url = $slide['image']['url'];
			}
			?>

			<img <?php $this->print_render_attribute_string( 'image' ); ?> src="<?php echo esc_url( $image_url ); ?>">
		<?php endif;
	}

	/**
	 * Render rating
	 */
	public function render_rating( $slide, $settings ) {
		if ( !empty( $slide['rating']['size'] ) ) :
			?>
			<div class="review-rating">
				<?php for ( $i = 1; $i <= 5; $i++ ) : ?>
					<?php 
						$width = ($slide['rating']['size'] - ($i - 1)) * 100 <= 100 ? ($slide['rating']['size'] - ($i - 1)) * 100 . '%' : '100%'; 
						if ( $slide['rating']['size'] - ($i - 1) < 0 ) {
							$width = 0;
						}
					?>
					<div class="review-icon">
						<div class="review-icon-empty">
							<?php aThemes_Addons_SVG_Icons::get_svg_icon( 'icon-star' ); ?>
						</div>
						<div class="review-icon-marked" style="--icon-marked-width: <?php echo esc_attr( $width ); ?>">
							<?php aThemes_Addons_SVG_Icons::get_svg_icon( 'icon-star' ); ?>
						</div>
					</div>
				<?php endfor; ?>
			</div>
		<?php endif;
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
Plugin::instance()->widgets_manager->register( new Testimonials() );