<?php
namespace aThemes_Addons\Widgets;

use Elementor\Plugin;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Group_Control_Image_Size;
use Elementor\Repeater;
use Elementor\Icons_Manager;
use Elementor\Control_Media;

/**
 * Gallery widget.
 *
 * @since 1.0.0
 */
class Gallery extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'athemes-addons-gallery';
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
		return __( 'Filterable gallery', 'athemes-addons-elementor' );
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
		return 'eicon-gallery-grid aafe-elementor-icon';
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
		return [ 'athemes-addons-isotope', 'imagesloaded', $this->get_name() . '-scripts' ];
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
		return [ 'images', 'image', 'gallery', 'athemes', 'addons', 'athemes addons' ];
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
			'section_gallery_layout',
			[
				'label' => __( 'Layout', 'athemes-addons-elementor' ),
			]
		);  

		$this->add_responsive_control(
			'columns',
			[
				'label' => __( 'Columns', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 3,
				'tablet_default' => 2,
				'mobile_default' => 1,
				'options' => [
					1 => 1,
					2 => 2,
					3 => 3,
					4 => 4,
				],
				'prefix_class' => 'aafe-grid%s-',
				'render_type' => 'template',
			]
		);      

		$this->add_responsive_control(
			'gutter',
			[
				'label' => __( 'Gutter', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 30,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-gallery' => '--gallery-gap: {{SIZE}}{{UNIT}}; ',
				],
				'render_type' => 'template',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_gallery_items',
			[
				'label' => __( 'Gallery', 'athemes-addons-elementor' ),
			]
		);  



		$repeater = new Repeater();

		$repeater->add_control(
			'image',
			[
				'label' => __( 'Image', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::MEDIA,
				'label_block' => true,
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
				'placeholder' => __( 'Title', 'athemes-addons-elementor' ),
				'default' => __( 'Item title', 'athemes-addons-elementor' ),
			]
		);

		$repeater->add_control(
			'content',
			[
				'label' => __( 'Content', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::WYSIWYG,
			]
		);
		
		$repeater->add_control(
			'link',
			[
				'label' => __( 'Link', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::URL,
			]
		);  

		$repeater->add_control(
			'lightbox_content',
			[
				'label' => __( 'Lightbox Content', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'image' => __( 'Image', 'athemes-addons-elementor' ),
					'video' => __( 'Video', 'athemes-addons-elementor' ),
				],
				'default' => 'image',
			]
		);

		$repeater->add_control(
			'video_link',
			[
				'label' => __( 'Video Link', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'condition' => [
					'lightbox_content' => 'video',
				],
			]
		);

		$repeater->add_control(
			'term',
			[
				'label'         => __( 'Filter term', 'athemes-addons-elementor' ),
				'description'   => sprintf(
					/* translators: %s: example */
					__( 'Categories that this item belongs to. One per line, in this format: %s', 'athemes-addons-elementor' ),
					'<br><strong>logo-design:Logo design</strong>'
				),
				'type'          => Controls_Manager::TEXTAREA,
				'default'       => 'logo-design:Logo Design',
				'placeholder'   => __( 'logo-design:Logo Design', 'athemes-addons-elementor' ),
			]
		);
		
		$this->add_control(
			'portfolio_list',
			[
				'label' => __( 'Items list', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'title' => __( 'Site branding', 'athemes-addons-elementor' ),
						'image' => [
							'url' => Utils::get_placeholder_image_src(),
						],
					],
					[
						'title' => __( 'Site development', 'athemes-addons-elementor' ),
						'term' => 'development:Development',
						'image' => [
							'url' => Utils::get_placeholder_image_src(),
						],
					],  
					[
						'title' => __( 'Logo design', 'athemes-addons-elementor' ),
						'term' => 'logo-design:Logo Design',
						'image' => [
							'url' => Utils::get_placeholder_image_src(),
						],
					],                                      
	
				],              
				'title_field' => '{{{ title }}}',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_gallery_settings',
			[
				'label' => __( 'Settings', 'athemes-addons-elementor' ),
			]
		);  

		$this->add_control(
			'filter_settings_heading',
			[
				'label' => __( 'Filter', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'show_filter',
			[
				'label' => __( 'Show filter', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'athemes-addons-elementor' ),
				'label_off' => __( 'Hide', 'athemes-addons-elementor' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_all_text',
			[
				'label' => __( 'Show all text', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => __( 'Show all', 'athemes-addons-elementor' ),
				'condition' => [
					'show_filter' => 'yes',
				],
			]
		);

		$this->add_control(
			'filter_alignment',
			[
				'label' => esc_html__( 'Filter Alignment', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Left', 'athemes-addons-elementor' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'athemes-addons-elementor' ),
						'icon' => 'eicon-text-align-center',
					],
					'flex-end' => [
						'title' => esc_html__( 'Right', 'athemes-addons-elementor' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .gallery-filter' => 'justify-content: {{VALUE}};',
				],
				'condition' => [
					'show_filter' => 'yes',
				],
				'separator' => 'after',
			]
		);  

		$this->add_control(
			'image_settings_heading',
			[
				'label' => __( 'Images', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
			]
		);      

		$this->add_control(
			'open_lightbox',
			[
				'label' => __( 'Open lightbox', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'athemes-addons-elementor' ),
				'label_off' => __( 'No', 'athemes-addons-elementor' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'image_hover_effect',
			array(
				'label'   => __( 'Image Hover Effect', 'athemes-addons-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'none'      => __( 'None', 'athemes-addons-elementor' ),
					'zoomin'    => __( 'Zoom in', 'athemes-addons-elementor' ),
					'opacity'   => __( 'Opacity', 'athemes-addons-elementor' ),
					'rotate'    => __( 'Zoom & Rotate', 'athemes-addons-elementor' ),
				),
				'default' => 'none',
			)
		);  

		$this->add_control(
			'transition_duration',
			[
				'label' => __( 'Transition Duration', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.4,
				'min' => 0,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .gallery-item-image img' => 'transition-duration: {{VALUE}}s;',
				],
				'condition' => [
					'image_hover_effect!' => 'none',
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'content_settings_heading',
			[
				'label' => __( 'Content', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
			]
		);
		
		$this->add_control(
			'content_vertical_alignment',
			[
				'label' => esc_html__( 'Vertical Alignment', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Top', 'athemes-addons-elementor' ),
						'icon' => 'eicon-v-align-top',
					],
					'center' => [
						'title' => esc_html__( 'Middle', 'athemes-addons-elementor' ),
						'icon' => 'eicon-v-align-middle',
					],
					'flex-end' => [
						'title' => esc_html__( 'Bottom', 'athemes-addons-elementor' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .gallery-item-content' => 'justify-content: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'content_alignment',
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
				'selectors_dictionary' => [
					'left'      => 'align-items:flex-start',
					'center'    => 'align-items:center;text-align:center',
					'right'     => 'align-items:flex-end;text-align:right',
				],
				'selectors' => [
					'{{WRAPPER}} .gallery-item-content' => '{{VALUE}};',

				],
			]
		);  

		$this->add_control(
			'title_html_tag',
			[
				'label' => __( 'Item title HTML tag', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
					'p' => 'p',
					'span' => 'span',
				],
				'default' => 'h3',
			]
		);          

		$this->end_controls_section();

		$this->start_controls_section(
			'section_gallery_icons',
			[
				'label' => __( 'Icons', 'athemes-addons-elementor' ),
			]
		);

		$this->add_control(
			'lightbox_icon',
			[
				'label' => esc_html__( 'Lightbox icon', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'default' => [
					'value' => 'fas fa-search',
					'library' => 'fa-solid',
				],
			]
		);
		
		$this->add_control(
			'link_icon',
			[
				'label' => esc_html__( 'Link icon', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'default' => [
					'value' => 'fas fa-link',
					'library' => 'fa-solid',
				],
			]
		);

		$this->add_control(
			'video_icon',
			[
				'label' => esc_html__( 'Video icon', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'default' => [
					'value' => 'fas fa-play',
					'library' => 'fa-solid',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_gallery_style',
			[
				'label' => __( 'Wrapper', 'athemes-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'gallery_background',
			[
				'label' => __( 'Background', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-gallery' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'gallery_padding',
			[
				'label' => __( 'Padding', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-gallery' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'gallery_margin',
			[
				'label' => __( 'Margin', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-gallery' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'gallery_border',
				'label' => __( 'Border', 'athemes-addons-elementor' ),
				'selector' => '{{WRAPPER}} .athemes-addons-gallery',
			]
		);

		$this->add_responsive_control(
			'gallery_border_radius',
			[
				'label' => __( 'Border Radius', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-gallery' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'gallery_box_shadow',
				'label' => __( 'Box Shadow', 'athemes-addons-elementor' ),
				'selector' => '{{WRAPPER}} .athemes-addons-gallery',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_gallery_filter_style',
			[
				'label' => __( 'Filter', 'athemes-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_filter' => 'yes',
				],
			]
		);

		$this->add_control(
			'filter_style_heading',
			[
				'label' => __( 'General', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'filter_background',
			[
				'label' => __( 'Background', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gallery-filter' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'filter_padding',
			[
				'label' => __( 'Padding', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .gallery-filter' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'filter_spacing',
			[
				'label' => __( 'Spacing', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 70,
					],
				],
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .gallery-filter' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'filter_items_style_heading',
			[
				'label' => __( 'Filter items', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'filter_items_typography',
				'selector' => '{{WRAPPER}} .gallery-filter a',
			]
		);

		$this->add_responsive_control(
			'filter_items_padding',
			[
				'label' => __( 'Padding', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .gallery-filter a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'filter_items_spacing',
			[
				'label' => __( 'Spacing', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 30,
					],
				],
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .gallery-filter a' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .gallery-filter a:last-child' => 'margin-right: 0;',
				],
			]
		);
		
		$this->add_responsive_control(
			'filter_items_border_radius',
			[
				'label' => __( 'Border Radius', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .gallery-filter a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'filter_items_style_tabs' );

		$this->start_controls_tab( 'filter_items_style_normal_tab', [ 'label' => esc_html__( 'Normal', 'athemes-addons-elementor' ) ] );

		$this->add_control(
			'filter_items_color',
			[
				'label' => __( 'Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gallery-filter a:not(.active)' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'filter_items_background',
			[
				'label' => __( 'Background', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gallery-filter a:not(.active)' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'filter_items_border_color',
			[
				'label' => __( 'Border Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gallery-filter a:not(.active)' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'filter_items_style_hover_tab', [ 'label' => esc_html__( 'Hover', 'athemes-addons-elementor' ) ] );

		$this->add_control(
			'filter_items_hover_color',
			[
				'label' => __( 'Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gallery-filter a:not(.active):hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'filter_items_hover_background',
			[
				'label' => __( 'Background', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gallery-filter a:not(.active):hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'filter_items_hover_border_color',
			[
				'label' => __( 'Border Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gallery-filter a:not(.active):hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'filter_items_style_active_tab', [ 'label' => esc_html__( 'Active', 'athemes-addons-elementor' ) ] );

		$this->add_control(
			'filter_items_active_color',
			[
				'label' => __( 'Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gallery-filter a.active' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'filter_items_active_background',
			[
				'label' => __( 'Background', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gallery-filter a.active' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'filter_items_active_border_color',
			[
				'label' => __( 'Border Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gallery-filter a.active' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_gallery_item_style',
			[
				'label' => __( 'Items', 'athemes-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'item_style_heading',
			[
				'label' => __( 'General', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_responsive_control(
			'item_padding',
			[
				'label' => __( 'Padding', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .aafe-gallery-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'item_border',
				'label' => __( 'Border', 'athemes-addons-elementor' ),
				'selector' => '{{WRAPPER}} .aafe-gallery-item',
			]
		);

		$this->add_responsive_control(
			'item_border_radius',
			[
				'label' => __( 'Border Radius', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .aafe-gallery-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'item_box_shadow',
				'label' => __( 'Box Shadow', 'athemes-addons-elementor' ),
				'selector' => '{{WRAPPER}} .aafe-gallery-item',
			]
		);

		$this->add_control(
			'item_content_style_heading',
			[
				'label' => __( 'Content', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'item_overlay_padding',
			[
				'label' => __( 'Overlay padding', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .gallery-item-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'item_overlay_background',
				'label' => esc_html__( 'Overlay background', 'athemes-addons-elementor' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .gallery-item-content',
				'separator' => 'after',
			]
		);

		$this->add_responsive_control(
			'item_content_padding',
			[
				'label' => __( 'Content padding', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .gallery-item-ext-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'_skin' => 'athemes-addons-gallery-card',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'item_content_background',
				'label' => esc_html__( 'Content background', 'athemes-addons-elementor' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .gallery-item-ext-content',
				'separator' => 'after',
				'condition' => [
					'_skin' => 'athemes-addons-gallery-card',
				],
			]
		);  


		$this->add_control(
			'item_title_color',
			[
				'label' => __( 'Title Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .item-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'item_title_typography',
				'selector' => '{{WRAPPER}} .item-title',
				'separator' => 'after',
			]
		);

		$this->add_control(
			'item_content_color',
			[
				'label' => __( 'Content Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .item-content' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'item_content_typography',
				'selector' => '{{WRAPPER}} .item-content',
				'separator' => 'after',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_gallery_icons_style',
			[
				'label' => __( 'Icons', 'athemes-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'icons_size',
			[
				'label' => __( 'Size', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem' ],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .gallery-item-icons a' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .gallery-item-video-icon a' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'icons_style_tabs' );

		$this->start_controls_tab( 'icons_style_normal_tab', [ 'label' => esc_html__( 'Normal', 'athemes-addons-elementor' ) ] );

		$this->add_control(
			'icons_background_color',
			[
				'label' => __( 'Background Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gallery-item-icons a' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .gallery-item-video-icon a' => 'background-color: {{VALUE}};',
				],
			]
		);      

		$this->add_control(
			'icons_color',
			[
				'label' => __( 'Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gallery-item-icons a' => 'color: {{VALUE}};fill: {{VALUE}};',
					'{{WRAPPER}} .gallery-item-video-icon a' => 'color: {{VALUE}};fill: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'icons_style_hover_tab', [ 'label' => esc_html__( 'Hover', 'athemes-addons-elementor' ) ] );

		$this->add_control(
			'icons_hover_background_color',
			[
				'label' => __( 'Background Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gallery-item-icons a:hover' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .gallery-item-video-icon a:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'icons_hover_color',
			[
				'label' => __( 'Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gallery-item-icons a:hover' => 'color: {{VALUE}};fill: {{VALUE}};',
					'{{WRAPPER}} .gallery-item-video-icon a:hover' => 'color: {{VALUE}};fill: {{VALUE}};',
				],
			]
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

		$open_lightbox = isset( $settings['open_lightbox'] ) ? $settings['open_lightbox'] : null;
		
		$this->add_render_attribute( 'gallery', 'class', 'athemes-addons-gallery' );

		$this->add_render_attribute( 'gallery', 'class', $this->get_id() );
		?>

		<div <?php $this->print_render_attribute_string( 'gallery' ); ?> >
			<div class="gallery-inner">

				<?php if ( 'yes' === $settings['show_filter'] ) : ?>
				<div class="gallery-filter">
					<a href="#" class="active" data-filter="*"><?php echo esc_html( $settings['show_all_text'] ); ?></a>

					<?php $filter_items = array(); ?>

					<?php foreach ( $settings['portfolio_list'] as $index => $item ) : ?>
						<?php
						$term = explode( "\n", $item['term'] );

						foreach ( $term as $t ) {
							$t = explode( ':', $t );

							if ( ! isset( $t[0] ) || ! isset( $t[1] ) ) {
								continue;
							}

							if ( ! in_array( $t[0], $filter_items ) ) { // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
								$filter_items[$t[0]] = $t[1];
							}
						}
						?>
					<?php endforeach; ?>

					<?php foreach ( $filter_items as $key => $value ) : ?>
						<a href="#" data-filter=".<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $value ); ?></a>
					<?php endforeach; ?>

				</div>
				<?php endif; ?>

				<div class="gallery-items">
					<?php $c = 0; ?>
					<?php foreach ( $settings['portfolio_list'] as $index => $item ) : ?>
						
					<div class="aafe-gallery-item <?php echo esc_attr( $this->prepare_term( $item['term'] ) ); ?>">
						<div class="item-inner">
							<?php
							$this->add_render_attribute( 'link-' . $c, 'class', 'elementor-clickable', true );


							$this->add_lightbox_data_attributes( 'link-' . $c, $item['image']['id'], $open_lightbox, $this->get_id(), true );

							$this->add_render_attribute( 'link-' . $c, 'href', esc_url( $item['image']['url'] ) );

							if ( 'video' === $item['lightbox_content'] && $item['video_link'] ) {
								$this->add_render_attribute( 'link-' . $c, 'data-elementor-lightbox-video', $item['video_link'] );
							}
							?>

							<?php if ( $item['image']['url'] ) :
								$this->add_render_attribute( 'image-' . $index, 'src', $item['image']['url'] );
								$this->add_render_attribute( 'image-' . $index, 'alt', Control_Media::get_image_alt( $item['image'] ) );                            
							?>
							<div class="gallery-item-image" data-effect="<?php echo esc_attr( $settings['image_hover_effect'] ); ?>">
								<img <?php $this->print_render_attribute_string( 'image-' . $index ); ?>/>
							</div>
							<?php endif; ?>

							<?php
								if ( ! empty( $item['link']['url'] ) ) {
									$this->add_render_attribute( 'button-' . $c, 'href', esc_url( $item['link']['url'] ) );

									if ( $item['link']['is_external'] ) {
										$this->add_render_attribute( 'button-' . $c, 'target', '_blank' );
									}

									if ( $item['link']['nofollow'] ) {
										$this->add_render_attribute( 'button-' . $c, 'rel', 'nofollow' );
									}
								}
							?>

							<?php if ( 'image' === $item['lightbox_content'] ) : ?>
							<div class="gallery-item-content">
								<<?php echo tag_escape( $settings['title_html_tag'] ); ?> class="item-title"><?php echo esc_html( $item['title'] ); ?></<?php echo tag_escape( $settings['title_html_tag'] ); ?>>

								<?php if ( ! empty( $item['content'] ) ) : ?>
									<div class="item-content"><?php echo wp_kses_post( $item['content'] ); ?></div>
								<?php endif; ?>

								<div class="gallery-item-icons">
									<?php if ( ! empty( $item['link']['url'] ) ) : ?>
										<a <?php $this->print_render_attribute_string( 'button-' . $c ); ?>>
											<?php Icons_Manager::render_icon( $settings['link_icon'], [ 'aria-hidden' => 'true' ] ); ?>
										</a>
									<?php endif; ?>

									<?php if ( $item['image']['url'] && 'yes' === $open_lightbox ) : ?>
										<a <?php $this->print_render_attribute_string( 'link-' . $c ); ?>>
											<?php Icons_Manager::render_icon( $settings['lightbox_icon'], [ 'aria-hidden' => 'true' ] ); ?>
										</a>
									<?php endif; ?>
								</div>
							</div>
							<?php else : ?>
							<div class="gallery-item-video-icon">
								<<?php echo esc_attr( $settings['title_html_tag'] ); ?> class="item-title"><?php echo esc_html( $item['title'] ); ?></<?php echo esc_attr( $settings['title_html_tag'] ); ?>>

								<?php if ( ! empty( $item['content'] ) ) : ?>
									<div class="item-content"><?php echo wp_kses_post( $item['content'] ); ?></div>
								<?php endif; ?>
								<a <?php $this->print_render_attribute_string( 'link-' . $c ); ?>>
									<?php Icons_Manager::render_icon( $settings['video_icon'], [ 'aria-hidden' => 'true' ] ); ?>
								</a>
							</div>
							<?php endif; ?>
						</div>
					</div>

					<?php $c++; ?>
					<?php endforeach; ?>
				</div>
			</div>
		</div>

		<?php if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) : ?>
			<style>
				.athemes-addons-gallery {
					--gallery-gap: <?php echo esc_attr( $settings['gutter']['size'] ); ?>px;
				}
			</style>
		<?php endif; ?>

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
	 * Prepare filter terms to be inserted as classes
	 *
	 */
	public function prepare_term( $term ) {

		$term = str_replace( ' ', '-', strtolower( $term ) );

		$prepared = '';
		$term = explode( "\n", $term );
		foreach ( $term as $t ) {
			$t = explode( ':', $t );
			$prepared .= esc_html( $t[0] ) . ' ';
		}

		return $prepared;
	}
}
Plugin::instance()->widgets_manager->register( new Gallery() );