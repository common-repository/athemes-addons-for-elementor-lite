<?php
namespace aThemes_Addons\Widgets;

use Elementor\Repeater;
use Elementor\Plugin;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Text_Shadow;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Slider block
 *
 * @since 1.0.0
 */
class Slider extends Widget_Base {

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
		return 'athemes-addons-slider';
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
		return __( 'Slider', 'athemes-addons-elementor' );
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
		return 'eicon-slider-push aafe-elementor-icon';
	}

	/**
	 * Enqueue styles.
	 */
	public function get_style_depends() {
		return [ 'athemes-addons-animations', $this->get_name() . '-styles' ];
	}

	/**
	 * Enqueue scripts.
	 */
	public function get_script_depends() {
		return [ 'swiper', $this->get_name() . '-scripts' ];
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
		return [ 'slide', 'slider', 'slides', 'carousel', 'athemes', 'addons', 'athemes addons' ];
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
			'section_slider_items',
			[
				'label' => __( 'Slides', 'athemes-addons-elementor' ),
			]
		);

		$repeater = new Repeater();

		$repeater->start_controls_tabs( 'slides_options' );

		$repeater->start_controls_tab( 'slider_content', [ 'label' => __( 'Content', 'athemes-addons-elementor' ) ] );

		$repeater->add_control(
			'content_type',
			[
				'label' => __( 'Content type', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'custom',
				'options' => [
					'custom'    => __( 'Custom', 'athemes-addons-elementor' ),
					'template'  => __( 'Template', 'athemes-addons-elementor' ),
				],
			]
		);

		$repeater->add_control(
			'slide_heading',
			[
				'label' => __( 'Title & Description', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Slide Heading', 'athemes-addons-elementor' ),
				'condition' => [
					'content_type' => 'custom',
				],
			]
		);

		$repeater->add_control(
			'slide_description',
			[
				'label' => __( 'Description', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::WYSIWYG,
				'default' => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'athemes-addons-elementor' ),
				'show_label' => false,
				'condition' => [
					'content_type' => 'custom',
				],
			]
		);

		$repeater->add_control(
			'button_text',
			[
				'label' => __( 'Button text', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Click me', 'athemes-addons-elementor' ),
				'show_label' => true,
				'condition' => [
					'content_type' => 'custom',
				],
			]
		);

		$repeater->add_control(
			'button_url',
			[
				'label' => __( 'Button URL', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::URL,
				'default' => [
					'url' => '#',
				],
				'show_label' => true,
				'condition' => [
					'content_type' => 'custom',
				],
			]
		);  

		$repeater->add_control(
			'template_id',
			[
				'label' => __( 'Template', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => $this->get_available_templates(),
				'condition' => [
					'content_type' => 'template',
				],
			]
		);
			
		$repeater->add_control(
			'content_animation',
			[
				'label'     => __( 'Content Animation', 'athemes-addons-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'none',
				'options'   => [
					'none'              => __( 'None', 'athemes-addons-elementor' ),
					'aafe-fadeIn'       => __( 'Fade in', 'athemes-addons-elementor' ),
					'aafe-fadeInDown'   => __( 'Fade in down', 'athemes-addons-elementor' ),
					'aafe-fadeInUp'     => __( 'Fade in up', 'athemes-addons-elementor' ),
					'aafe-fadeInRight'  => __( 'Fade in right', 'athemes-addons-elementor' ),
					'aafe-fadeInLeft'   => __( 'Fade in left', 'athemes-addons-elementor' ),
					'aafe-zoomIn'       => __( 'Zoom in', 'athemes-addons-elementor' ),
				],
			]
		);

		$repeater->add_control(
			'kenburns',
			[
				'label' => __( 'Kenburns effect', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',  
				'return_value' => 'yes',
			]
		);
				
		$repeater->end_controls_tab();

		$repeater->start_controls_tab( 'slider_style', [ 'label' => __( 'Style', 'athemes-addons-elementor' ) ] );

		$repeater->add_control(
			'content_alignment',
			[
				'label' => __( 'Content alignment', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default'   => 'center',
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
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => '{{VALUE}}',
				],
				'selectors_dictionary' => [
					'left'      => 'justify-content: flex-start',
					'center'    => 'justify-content: center; text-align: center',
					'right'     => 'justify-content: flex-end; text-align: right',
				],
			]
		);  

		$repeater->add_control(
			'content_vertical_alignment',
			[
				'label' => __( 'Vertical alignment', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default'   => 'center',
				'options' => [
					'top' => [
						'title' => __( 'Top', 'athemes-addons-elementor' ),
						'icon' => 'eicon-v-align-top',
					],
					'center' => [
						'title' => __( 'Center', 'athemes-addons-elementor' ),
						'icon' => 'eicon-v-align-middle',
					],
					'bottom' => [
						'title' => __( 'Bottom', 'athemes-addons-elementor' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => '{{VALUE}}',
				],
				'selectors_dictionary' => [
					'top'      => 'align-items: flex-start',
					'center'    => 'align-items: center',
					'bottom'     => 'align-items: flex-end',
				],
				'separator' => 'after',
			]
		);

		$repeater->add_control(
			'background_color',
			[
				'label' => __( 'Background color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#787c80',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'background-color: {{VALUE}}',
				],
			]
		);  

		$repeater->add_control(
			'background_image',
			[
				'label' => __( 'Background Image', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => '',
				],              
			]
		);

		$repeater->add_control(
			'background_size',
			[
				'label' => __( 'Background size', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'cover',
				'options' => [
					'cover'     => __( 'Cover', 'athemes-addons-elementor' ),
					'contain'   => __( 'Contain', 'athemes-addons-elementor' ),
					'auto'      => __( 'Auto', 'athemes-addons-elementor' ),
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'background-size: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'      => 'background_image[url]',
							'operator'  => '!=',
							'value'     => '',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'enable_overlay',
			[
				'label' => __( 'Enable overlay', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'conditions' => [
					'terms' => [
						[
							'name'      => 'background_image[url]',
							'operator'  => '!=',
							'value'     => '',
						],
					],
				],              
			]
		);
		
		$repeater->add_control(
			'overlay_color',
			[
				'label' => __( 'Overlay color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => 'rgba(0,0,0,0.5)',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .slide-overlay' => 'background-color: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name'      => 'background_image[url]',
							'operator'  => '!=',
							'value'     => '',
						],
					],
				],                  
			]
		);      

		$repeater->add_control(
			'heading_color',
			[
				'label' => __( 'Heading color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .swiper-wrapper {{CURRENT_ITEM}} .slide-title' => 'color: {{VALUE}}',
				],
				'condition' => [
					'content_type' => 'custom',
				],
			]
		);          

		$repeater->add_control(
			'text_color',
			[
				'label' => __( 'Text color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .swiper-wrapper {{CURRENT_ITEM}} .slide-text' => 'color: {{VALUE}}',
				],
				'condition' => [
					'content_type' => 'custom',
				],
			]
		);          

		$repeater->end_controls_tab();
		
		$repeater->end_controls_tabs();

		$this->add_control(
			'slides_controls',
			[
				'label' => __( 'Slides', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'slide_heading' => __( 'Welcome to our website', 'athemes-addons-elementor' ),
						'slide_description' => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum id nunc rutrum libero posuere rutrum vel a nibh.', 'athemes-addons-elementor' ),
						'background_color'  => '#071555',
					],
					[
						'slide_heading' => __( 'Feel free to explore', 'athemes-addons-elementor' ),
						'slide_description' => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum id nunc rutrum libero posuere rutrum vel a nibh.', 'athemes-addons-elementor' ),
						'background_color'  => '#0124c5',                   
					],
				],              
				'title_field' => '{{{ slide_heading }}}',
			]
		);

		$this->end_controls_section();


		$this->start_controls_section(
			'section_slider_settings',
			[
				'label' => __( 'Settings', 'athemes-addons-elementor' ),
			]
		);

		$this->add_control(
			'slide_effect',
			[
				'label' => __( 'Effect', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'fade',
				'options' => [
					'slide'     => __( 'Slide', 'athemes-addons-elementor' ),
					'fade'      => __( 'Fade', 'athemes-addons-elementor' ),
					'cube'      => __( 'Cube', 'athemes-addons-elementor' ),
					'coverflow' => __( 'Coverflow', 'athemes-addons-elementor' ),
					'flip'      => __( 'Flip', 'athemes-addons-elementor' ),
				],
				'render_type' => 'template',
			]
		);

		$this->add_responsive_control(
			'slider_height',
			[
				'label' => __( 'Slider height', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 500,
				],
				'range' => [
					'px' => [
						'min' => 400,
						'max' => 1000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-wrapper:not(.swiper-thumb-wrapper) .swiper-slide, {{WRAPPER}} .athemes-addons-slider' => 'height: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'autoplay_speed',
			[
				'label' => __( 'Autoplay speed [ms]', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 3000,
				'range' => [
						'min' => 500,
						'max' => 10000,
				],
			]
		);

		$this->add_control(
			'show_pagination',
			[
				'label' => __( 'Show pagination', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',   
				'return_value' => 'yes',   
				'prefix_class' => 'aafe-swiper-pagination-',
			]
		);

		$this->add_control(
			'show_navigation',
			[
				'label' => __( 'Show navigation', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',  
				'return_value' => 'yes',
				'prefix_class' => 'aafe-swiper-navigation-',            
			]
		);      

		$this->add_control(
			'pause_on_hover',
			[
				'label' => __( 'Pause on hover', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',  
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'pause_on_interaction',
			[
				'label' => __( 'Pause on interaction', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',  
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'loop',
			[
				'label' => __( 'Loop', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',  
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'show_thumbs',
			[
				'label' => __( 'Show thumbs', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',  
				'return_value' => 'yes',
				'render_type' => 'template',
				'prefix_class' => 'aafe-swiper-thumbs-',            
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
			'wrapper_margin',
			[
				'label' => __( 'Margin', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-slider' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],              
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'wrapper_border',
				'label' => __( 'Border', 'athemes-addons-elementor' ),
				'selector' => '{{WRAPPER}} .athemes-addons-slider',
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
					'{{WRAPPER}} .athemes-addons-slider' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],              
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'      => 'wrapper_box_shadow',
				'selector'  => '{{WRAPPER}} .athemes-addons-slider',
			]
		);


		$this->add_responsive_control(
			'slide_padding',
			[
				'label' => __( 'Slide inner padding', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-slider .swiper-slide' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],              
			]
		);  
		
		$this->add_responsive_control(
			'slide_content_width',
			[
				'label' => __( 'Slide content width', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 600,
				],
				'range' => [
					'px' => [
						'min' => 400,
						'max' => 1200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-slider .slide-inner.content-type-custom' => 'max-width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_title_style',
			[
				'label' => __( 'Title', 'athemes-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'title_margin',
			[
				'label' => __( 'Margin', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .swiper-wrapper .slide-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],              
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => __( 'Text Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .swiper-wrapper .slide-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'selector' => '{{WRAPPER}} .swiper-wrapper .slide-title',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_text_style',
			[
				'label' => __( 'Text', 'athemes-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'text_margin',
			[
				'label' => __( 'Margin', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .swiper-wrapper .slide-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],              
			]
		);

		$this->add_control(
			'text_color',
			[
				'label' => __( 'Text Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .swiper-wrapper .slide-text' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'text_typography',
				'selector' => '{{WRAPPER}} .swiper-wrapper .slide-text',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_button_style',
			[
				'label' => __( 'Button', 'athemes-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'selector' => '{{WRAPPER}} a.button',
			]
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => __( 'Normal', 'athemes-addons-elementor' ),
			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label' => __( 'Text Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} a.button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'background_color',
			[
				'label' => __( 'Background Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} a.button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => __( 'Hover', 'athemes-addons-elementor' ),
			]
		);

		$this->add_control(
			'hover_color',
			[
				'label' => __( 'Text Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} a.button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_background_hover_color',
			[
				'label' => __( 'Background Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} a.button:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[
				'label' => __( 'Border Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} a.button:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'hover_animation',
			[
				'label' => __( 'Hover Animation', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'border',
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} a.button',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'border_radius',
			[
				'label' => __( 'Border Radius', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} a.button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow',
				'selector' => '{{WRAPPER}} a.button',
			]
		);

		$this->add_responsive_control(
			'text_padding',
			[
				'label' => __( 'Padding', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} a.button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_navigation_style',
			[
				'label' => __( 'Navigation', 'athemes-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'arrows_heading',
			[
				'label' => __( 'Arrows', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_responsive_control(
			'arrows_size',
			[
				'label' => __( 'Size', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 40,
				],
				'range' => [
					'px' => [
						'min' => 20,
						'max' => 80,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-button-next, {{WRAPPER}} .swiper-button-prev' => '--swiper-navigation-size: {{SIZE}}{{UNIT}};',
				],
			]
		);


		$this->start_controls_tabs( 'tabs_arrows_style' );

		$this->start_controls_tab(
			'tab_arrows_normal',
			[
				'label' => __( 'Normal', 'athemes-addons-elementor' ),
			]
		);

		$this->add_control(
			'arrows_bg_color',
			[
				'label' => __( 'Background Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .swiper-button-prev, {{WRAPPER}} .swiper-button-next' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'arrows_color',
			[
				'label' => __( 'Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .swiper-button-prev, {{WRAPPER}} .swiper-button-next' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_arrows_hover',
			[
				'label' => __( 'Hover', 'athemes-addons-elementor' ),
			]
		);

		$this->add_control(
			'arrows_hover_bg_color',
			[
				'label' => __( 'Background Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .swiper-button-prev:hover, {{WRAPPER}} .swiper-button-next:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'arrows_hover_color',
			[
				'label' => __( 'Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .swiper-button-prev:hover, {{WRAPPER}} .swiper-button-next:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'arrows_border_radius',
			[
				'label' => __( 'Border Radius', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'unit' => 'px',
					'left' => 50,
					'right' => 50,
					'top' => 50,
					'bottom' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-button-prev, {{WRAPPER}} .swiper-button-next' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],              
			]
		);

		$this->add_control(
			'dots_heading',
			[
				'label' => __( 'Dots', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

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

		$this->add_responsive_control(
			'dots_width',
			[
				'label' => __( 'Width', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 20,
				],
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 40,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'dots_height',
			[
				'label' => __( 'Height', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 6,
				],
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 40,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-bullet' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'dots_border_radius',
			[
				'label' => __( 'Border Radius', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'unit' => 'px',
					'left' => 50,
					'right' => 50,
					'top' => 50,
					'bottom' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-bullet' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],              
			]
		);

		$this->add_control(
			'thumbs_heading',
			[
				'label' => __( 'Thumbs', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'show_thumbs' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'thumbs_margin',
			[
				'label' => __( 'Margin', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-slider-thumbs' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],              
				'condition' => [
					'show_thumbs' => 'yes',
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
					'{{WRAPPER}} .athemes-addons-slider-thumbs' => 'max-width: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .athemes-addons-slider-thumbs img' => 'height: {{SIZE}}{{UNIT}};',
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
				'default' => [
					'unit' => 'px',
					'left' => 0,
					'right' => 0,
					'top' => 0,
					'bottom' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-slider-thumbs img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'default' => '#1c1c1c',
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
		$settings   = $this->get_settings();
		$total      = count( $settings['slides_controls'] );
		
		$this->add_render_attribute( 'wrapper', 'class', 'athemes-addons-slider swiper-container' );
		$this->add_render_attribute( 'wrapper', 'data-autoplay-delay', $settings['autoplay_speed'] );
		$this->add_render_attribute( 'wrapper', 'data-loop', $settings['loop'] );
		$this->add_render_attribute( 'wrapper', 'data-pause-on-hover', $settings['pause_on_hover'] );
		$this->add_render_attribute( 'wrapper', 'data-pause-on-interaction', $settings['pause_on_interaction'] );
		$this->add_render_attribute( 'wrapper', 'data-effect', $settings['slide_effect'] );
		?>

		<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
			<div class="swiper-wrapper">
				<?php foreach ( $settings['slides_controls'] as $index => $item ) :

					$this->add_render_attribute( 'slide' . $index, 'class', 'swiper-slide elementor-repeater-item-' . $item['_id'] );

					if ( 0 === $index ) {
						$this->add_render_attribute( 'slide'  . $index, 'class', 'run-animation' );
					}

					$this->add_render_attribute( 'content-wrapper' . $index, 'class', 'slide-content-wrapper' );

					if ( 'none' !== $item['content_animation'] ) {
						$this->add_render_attribute( 'content-wrapper'  . $index, 'class', 'aafe-animated ' . $item['content_animation'] );
					}
					
					?>
					<div <?php $this->print_render_attribute_string( 'slide' . $index ); ?>>
						<?php if ( $item['background_image']['url'] ) : ?>
						<div class="slide-image <?php echo ( 'yes' === $item['kenburns'] ? 'aafe-kenburns' : '' ); ?>">
							<?php Group_Control_Image_Size::print_attachment_image_html( $item, 'background_image' ); ?>
						</div>
						<?php endif; ?>
						<?php if ( $item['enable_overlay'] ) : ?>
						<div class="slide-overlay"></div>
						<?php endif; ?>						
						<div class="slide-inner content-type-<?php echo esc_attr( $item['content_type'] ); ?>">
							<div <?php $this->print_render_attribute_string( 'content-wrapper' . $index ); ?>>
								<?php if ( 'custom' === $item['content_type'] ) : ?>
									<h2 class="slide-title"><?php echo wp_kses_post( $item['slide_heading'] ); ?></h2>
									<div class="slide-text"><?php echo wp_kses_post( $item['slide_description'] ); ?></div>

									<?php
									if ( ! empty( $item['button_url']['url'] ) ) {
										echo '<div class="slide-button">';
										$link_key = 'button_url_' . $index;

										$this->add_render_attribute( $link_key, 'class', 'button' );

										$this->add_render_attribute( $link_key, 'href', esc_url( $item['button_url']['url'] ) );

										if ( $item['button_url']['is_external'] ) {
											$this->add_render_attribute( $link_key, 'target', '_blank' );
										}

										if ( $item['button_url']['nofollow'] ) {
											$this->add_render_attribute( $link_key, 'rel', 'nofollow' );
										}

										// Button animation.
										if ( 'none' !== $settings['hover_animation'] ) {
											$this->add_render_attribute( $link_key, 'class', 'elementor-animation-' . esc_attr( $settings['hover_animation'] ) );
										}

										echo '<a ' . $this->get_render_attribute_string( $link_key ) . '>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									}
									echo esc_html( $item['button_text'] );
									if ( ! empty( $item['button_url']['url'] ) ) {
										echo '</a>';
										echo '</div>';
									}
									?>
								<?php else : ?>
									<?php echo Plugin::instance()->frontend->get_builder_content_for_display( $item['template_id'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								<?php endif; ?>
							</div>
						</div>
					</div>
					<?php
				endforeach;
				?>
			</div>	
			<div class="swiper-pagination"></div>
			<div class="swiper-button-next"></div>
			<div class="swiper-button-prev"></div>		
		</div>
		<?php if ( 'yes' === $settings['show_thumbs'] ) : ?>
		<div class="athemes-addons-slider-thumbs">
			<div class="swiper-wrapper swiper-thumb-wrapper">
				<?php foreach ( $settings['slides_controls'] as $index => $item ) : ?>
					<div class="swiper-slide">
						<?php if ( $item['background_image']['url'] ) : ?>
							<?php Group_Control_Image_Size::print_attachment_image_html( $item, 'background_image' ); ?>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<?php endif; ?>		
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
Plugin::instance()->widgets_manager->register( new Slider() );