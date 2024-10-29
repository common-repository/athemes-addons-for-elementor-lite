<?php
namespace aThemes_Addons\Widgets;

use aThemes_Addons_Posts_Helper as Posts_Helper;
use Elementor\Plugin;
use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Group_Control_Image_Size;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Posts list widget.
 *
 *
 * @since 1.0.0
 */
class Posts_Carousel extends Widget_Base {

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
		return 'athemes-addons-posts-carousel';
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
		return __( 'Post carousel', 'athemes-addons-elementor' );
	}

	public function get_keywords() {
		return [ 'posts', 'blog', 'post', 'carousel', 'slider', 'news', 'articles', 'athemes', 'addons', 'athemes addons' ];
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
		return 'eicon-posts-carousel aafe-elementor-icon';
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
		$this->start_controls_section(
			'section_blog',
			[
				'label' => __( 'Layout', 'athemes-addons-elementor' ),
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
				'desktop_default' => 3,
				'tablet_default' => 2,
				'mobile_default' => 1,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_query',
			[
				'label' => __( 'Query', 'athemes-addons-elementor' ),
			]
		);

		$post_types = Posts_Helper::get_post_types();

		$this->add_control(
			'query_type',
			array(
				'label'       => __( 'Query Type', 'athemes-addons-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'custom',
				'label_block' => true,
				'options'     => array(
					'main'   => __( 'Main Query', 'athemes-addons-elementor' ),
					'custom' => __( 'Custom Query', 'athemes-addons-elementor' ),
				),
			)
		);

		$this->add_control(
			'post_type_filter',
			array(
				'label'     => __( 'Post Type', 'athemes-addons-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => $post_types,
				'default'   => 'post',
				'condition' => array(
					'query_type' => 'custom',
				),
			)
		);

		foreach ( $post_types as $key => $type ) {

			$taxonomy = Posts_Helper::get_taxonomies( $key );

			if ( ! empty( $taxonomy ) ) {

				foreach ( $taxonomy as $index => $tax ) {

					$terms = get_terms( $index );

					$related_tax = array();

					if ( ! empty( $terms ) ) {

						foreach ( $terms as $i => $object ) {

							$related_tax[ $object->slug ] = $object->name;
						}

						$this->add_control(
							$index . '_' . $key . '_filter_rule',
							array(
								'label'       => $tax->label,
								'type'        => Controls_Manager::SELECT,
								'default'     => 'IN',
								'label_block' => true,
								'options'     => array(
									/* translators: %s: Taxonomy label */
									'IN'     => sprintf( __( 'Include %s', 'athemes-addons-elementor' ), $tax->label ),
 									/* translators: %s: Taxonomy label */
									'NOT IN' => sprintf( __( 'Exclude %s', 'athemes-addons-elementor' ), $tax->label ),
								),
								'condition'   => array(
									'post_type_filter' => $key,
								),
							)
						);

						$this->add_control(
							'tax_' . $index . '_' . $key . '_filter',
							array(
								/* translators: %s: Taxonomy label */
								'label'       => sprintf( __( 'Choose %s', 'athemes-addons-elementor' ), $tax->label ),
								'type'        => Controls_Manager::SELECT2,
								'default'     => '',
								'multiple'    => true,
								'label_block' => true,
								'options'     => $related_tax,
								'condition'   => array(
									'post_type_filter' => $key,
								),
								'separator'   => 'after',
							)
						);

					}
				}
			}
		}

		$this->add_control(
			'posts_filter_rule',
			array(
				'label'       => __( 'Posts', 'athemes-addons-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'post__not_in',
				'label_block' => true,
				'options'     => array(
					'post__in'     => __( 'Include posts', 'athemes-addons-elementor' ),
					'post__not_in' => __( 'Exclude posts', 'athemes-addons-elementor' ),
				),
				'condition'   => array(
					'post_type_filter' => 'post',
				),
			)
		);

		$this->add_control(
			'blog_posts_filter',
			array(
				'label'       => __( 'Posts', 'athemes-addons-elementor' ),
				'type'        => Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple'    => true,
				'options'     => Posts_Helper::get_default_posts_list( 'post' ),
				'condition'   => array(
					'post_type_filter' => 'post',
				),
			)
		);

		$this->add_control(
			'number',
			[
				'label' => __( 'Number of posts', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 5,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'orderby',
			[
				'label' => __( 'Order by', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'date',
				'options' => [
					'date'      => __( 'Date', 'athemes-addons-elementor' ),
					'title'     => __( 'Title', 'athemes-addons-elementor' ),
					'rand'      => __( 'Random', 'athemes-addons-elementor' ),
				],
			]
		);

		$this->add_control(
			'order',
			[
				'label' => __( 'Order', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'desc',
				'options' => [
					'asc'   => __( 'ASC', 'athemes-addons-elementor' ),
					'desc'  => __( 'DESC', 'athemes-addons-elementor' ),
				],
			]
		);

		$this->add_control(
			'ignore_sticky_posts',
			array(
				'label'     => __( 'Ignore Sticky Posts', 'athemes-addons-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => __( 'Yes', 'athemes-addons-elementor' ),
				'label_off' => __( 'No', 'athemes-addons-elementor' ),
				'default'   => 'yes',
				'condition'   => array(
					'post_type_filter' => 'post',
				),
			)
		);

		$this->add_control(
			'offset',
			[
				'label' => __( 'Offset', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0,
			]
		);  

		$this->end_controls_section();

		//Item
		$this->start_controls_section(
			'section_item_settings',
			[
				'label' => __( 'Elements', 'athemes-addons-elementor' ),
			]
		);

		$this->add_control(
			'post_title_heading',
			[
				'label' => esc_html__( 'Post title', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'show_title',
			[
				'label' => __( 'Show title', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on'  => __( 'Yes', 'athemes-addons-elementor' ),
				'label_off' => __( 'No', 'athemes-addons-elementor' ),
				'return_value' => 'yes',
				'default' => 'yes',                         
			]
		);  

		$this->add_control(
			'title_tag',
			[
				'label' => __('Title tag', 'athemes-addons-elementor'),
				'type' => Controls_Manager::SELECT,
				'default' => 'h2',
				'options' => [
					'h1'    => __('H1', 'athemes-addons-elementor'),
					'h2'    => __('H2', 'athemes-addons-elementor'),
					'h3'    => __('H3', 'athemes-addons-elementor'),
					'h4'    => __('H4', 'athemes-addons-elementor'),
					'h5'    => __('H5', 'athemes-addons-elementor'),
					'h6'    => __('H6', 'athemes-addons-elementor'),
					'span'  => __('Span', 'athemes-addons-elementor'),
					'p'     => __('P', 'athemes-addons-elementor'),
					'div'   => __('Div', 'athemes-addons-elementor'),
				],
			]
		);

		$this->add_responsive_control(
			'title_align',
			[
				'label' => __( 'Alignment', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left'      => [
						'title' => __( 'Left', 'athemes-addons-elementor' ),
						'icon' => 'eicon-text-align-left',
					],
					'center'    => [
						'title' => __( 'Center', 'athemes-addons-elementor' ),
						'icon' => 'eicon-text-align-center',
					],
					'right'     => [
						'title' => __( 'Right', 'athemes-addons-elementor' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .item-title' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'post_img_heading',
			[
				'label' => esc_html__( 'Image', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'show_thumbnail',
			[
				'label' => __( 'Show featured image', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on'  => __( 'Yes', 'athemes-addons-elementor' ),
				'label_off' => __( 'No', 'athemes-addons-elementor' ),
				'return_value' => 'yes',
				'default' => 'yes',     
			]
		);
		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'      => 'image',
				'default'   => 'large',
				'separator' => 'none',
			]
		);

		$this->add_responsive_control(
			'image_height',
			[
				'label' => __( 'Image height', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 300,
				],
				'range' => [
					'px' => [
						'min' => 50,
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .athemes-post-item .post-item-thumb img' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'show_thumbnail' => 'yes',
				],
			]
		);

		$this->add_control(
			'image_fit',
			[
				'label' => __( 'Image fit', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'cover',
				'options' => [
					'cover'     => __( 'Cover', 'athemes-addons-elementor' ),
					'contain'   => __( 'Contain', 'athemes-addons-elementor' ),
					'fill'      => __( 'Fill', 'athemes-addons-elementor' ),
					'none'      => __( 'None', 'athemes-addons-elementor' ),
				],
				'selectors' => [
					'{{WRAPPER}} .athemes-post-item .post-item-thumb img' => 'object-fit: {{VALUE}};',
				],
				'condition' => [
					'show_thumbnail' => 'yes',
				],
			]
		);

		$this->add_control(
			'post_info_heading',
			[
				'label' => esc_html__( 'Post info', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'delimiter',
			[
				'label' => __( 'Delimiter', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'dot',
				'options' => [
					'none'      => esc_html__( 'None', 'athemes-addons-elementor' ),
					'dot'       => '&middot;',
					'vertical'  => '&#124;',
					'horizontal'=> '&#x23AF;',
				],
			]
		);

		$this->add_control(
			'show_date',    
			[
				'label' => __( 'Show date', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on'  => __( 'Yes', 'athemes-addons-elementor' ),
				'label_off' => __( 'No', 'athemes-addons-elementor' ),
				'return_value' => 'yes',
				'default' => 'yes',                 
			]
		);
		
		$this->add_control(
			'show_author',  
			[
				'label' => __( 'Show author', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on'  => __( 'Yes', 'athemes-addons-elementor' ),
				'label_off' => __( 'No', 'athemes-addons-elementor' ),
				'return_value' => 'yes',
				'default' => 'yes',                         
			]
		);      

		$this->add_control(
			'show_cats',
			[
				'label' => __( 'Show categories', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on'  => __( 'Yes', 'athemes-addons-elementor' ),
				'label_off' => __( 'No', 'athemes-addons-elementor' ),
				'return_value' => 'yes',
				'default' => 'yes',     
			]
		);

		$this->add_control(
			'cat_display',
			[
				'label' => __( 'Category display', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'link',
				'options' => [
					'link'      => __( 'Link', 'athemes-addons-elementor' ),
					'label'     => __( 'Label', 'athemes-addons-elementor' ),
				],
				'condition' => [
					'show_cats' => 'yes',
					'_skin!' => 'athemes-addons-posts-carousel-modern',
				],
			]
		);


		$this->add_responsive_control(
			'info_align',
			[
				'label' => __( 'Alignment', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left'      => [
						'title' => __( 'Left', 'athemes-addons-elementor' ),
						'icon' => 'eicon-text-align-left',
					],
					'center'    => [
						'title' => __( 'Center', 'athemes-addons-elementor' ),
						'icon' => 'eicon-text-align-center',
					],
					'right'     => [
						'title' => __( 'Right', 'athemes-addons-elementor' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .post-info' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'post_excerpt_heading',
			[
				'label' => esc_html__( 'Excerpt', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'show_excerpt',
			[
				'label' => __( 'Show excerpt', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on'  => __( 'Yes', 'athemes-addons-elementor' ),
				'label_off' => __( 'No', 'athemes-addons-elementor' ),
				'return_value' => 'yes',
				'default' => 'yes',     
				'condition' => [
					'_skin!' => 'athemes-addons-posts-carousel-banner',
				],
			]
		);

		$this->add_control(
			'excerpt_length',
			[
				'label' => __( 'Number of words', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 12,
				'condition' => [
					'show_excerpt' => 'yes',
				],
			]
		);  

		$this->add_responsive_control(
			'excerpt_align',
			[
				'label' => __( 'Alignment', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left'      => [
						'title' => __( 'Left', 'athemes-addons-elementor' ),
						'icon' => 'eicon-text-align-left',
					],
					'center'    => [
						'title' => __( 'Center', 'athemes-addons-elementor' ),
						'icon' => 'eicon-text-align-center',
					],
					'right'     => [
						'title' => __( 'Right', 'athemes-addons-elementor' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .item-excerpt' => 'text-align: {{VALUE}};',
				],
				'condition' => [
					'show_excerpt' => 'yes',
				],
			]
		);
		
		$this->add_control(
			'readmore_heading',
			[
				'label' => esc_html__( 'Read more', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'read_more_text',
			[
				'label'         => __( 'Read more text', 'athemes-addons-elementor' ),
				'type'          => Controls_Manager::TEXT,
			]
		);

		$this->add_control(
			'readmore_align',
			[
				'label' => __( 'Alignment', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left'      => [
						'title' => __( 'Left', 'athemes-addons-elementor' ),
						'icon' => 'eicon-text-align-left',
					],
					'center'    => [
						'title' => __( 'Center', 'athemes-addons-elementor' ),
						'icon' => 'eicon-text-align-center',
					],
					'right'     => [
						'title' => __( 'Right', 'athemes-addons-elementor' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .read-more-wrapper' => 'text-align: {{VALUE}};',
				],
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
				'condition' => [
					'autoplay' => 'true',
				],
			]
		);

		//end section
		$this->end_controls_section();      

		$this->start_controls_section(
			'section_style_card',
			[
				'label' => __( 'Card', 'athemes-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'card_padding',
			[
				'label' => __( 'Padding', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .athemes-post-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],              
				'condition' => [
					'_skin!' => 'athemes-addons-posts-carousel-banner',
				],
			]
		);

		$this->add_control(
			'card_border_radius',
			[
				'label' => __( 'Border radius', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .athemes-post-item,{{WRAPPER}} .athemes-post-item::after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],  
				'condition' => [
					'_skin!' => 'athemes-addons-posts-carousel-banner',
				],  
			]
		);
		
		$this->start_controls_tabs( 'tabs_card_style' );

		$this->start_controls_tab(
			'tab_card_normal',
			[
				'label' => __( 'Normal', 'athemes-addons-elementor' ),
			]
		);

		$this->add_control(
			'card_bg_color',
			[
				'label' => __( 'Background color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .athemes-post-item' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'card_border',
				'selector' => '{{WRAPPER}} .athemes-post-item',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'card_shadow',
				'selector' => '{{WRAPPER}} .athemes-post-item',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_card_hover',
			[
				'label' => __( 'Hover', 'athemes-addons-elementor' ),
			]
		);

		$this->add_control(
			'card_bg_color_hover',
			[
				'label' => __( 'Background hover color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .athemes-post-item:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'card_border_color_hover',
			[
				'label' => __( 'Border hover color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .athemes-post-item:hover' => 'border-color: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'card_shadow_hover',
				'selector' => '{{WRAPPER}} .athemes-post-item:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_image',
			[
				'label' => __( 'Image', 'athemes-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_thumbnail' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'image_spacing',
			[
				'label' => __( 'Spacing', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 12,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .athemes-post-item .post-item-thumb' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'image_border_radius',
			[
				'label' => __( 'Border radius', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .athemes-post-item .post-item-thumb img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],      
				'condition' => [
					'_skin!' => 'athemes-addons-posts-carousel-modern',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_titles',
			[
				'label' => __( 'Post titles', 'athemes-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => __( 'Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .item-title a' => 'color: {{VALUE}};',
				],
				'condition' => [
					'_skin!' => 'athemes-addons-posts-carousel-banner',
				],
			]
		);

		$this->add_control(
			'title_color_hover',
			[
				'label' => __( 'Hover color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .item-title a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'selector' => '{{WRAPPER}} .item-title',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_meta',
			[
				'label' => __( 'Post meta', 'athemes-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'meta_color',
			[
				'label' => __( 'Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .post-info:not(.cats-label), {{WRAPPER}} .post-info:not(.cats-label) a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'meta_color_hover',
			[
				'label' => __( 'Hover color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .post-info a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'meta_typography',
				'selector' => '{{WRAPPER}} .post-info',
			]
		);

		$this->add_control(
			'cat_label_heading',
			[
				'label' => esc_html__( 'Category label', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'cat_display' => 'label',
				],  
			]
		);

		$this->add_control(
			'cat_label_color',
			[
				'label' => __( 'Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .post-info.cats-label a' => 'color: {{VALUE}};',
				],
				'condition' => [
					'cat_display' => 'label',
				],
			]
		);

		$this->add_control(
			'cat_label_bg_color',
			[
				'label' => __( 'Background color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .post-info.cats-label a' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'cat_display' => 'label',
				],
			]
		);      

		$this->add_control(
			'cat_label_color_hover',
			[
				'label' => __( 'Hover color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .post-info.cats-label a:hover' => 'color: {{VALUE}};',
				],
				'condition' => [
					'cat_display' => 'label',
				],
			]
		);

		$this->add_control(
			'cat_label_bg_color_hover',
			[
				'label' => __( 'Background hover color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .post-info.cats-label a:hover' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'cat_display' => 'label',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_excerpt',
			[
				'label' => __( 'Content', 'athemes-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'excerpt_color',
			[
				'label' => __( 'Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .item-excerpt' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'excerpt_typography',
				'selector' => '{{WRAPPER}} .item-excerpt',
			]
		);

		$this->end_controls_section();

		//style section
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
					'{{WRAPPER}} .athemes-addons-posts-carousel .swiper-button-prev svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .athemes-addons-posts-carousel .swiper-button-next svg' => 'fill: {{VALUE}};',
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
					'{{WRAPPER}} .athemes-addons-posts-carousel .swiper-button-prev' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .athemes-addons-posts-carousel .swiper-button-next' => 'background-color: {{VALUE}};',
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
					'{{WRAPPER}} .athemes-addons-posts-carousel .swiper-button-prev:hover svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .athemes-addons-posts-carousel .swiper-button-next:hover svg' => 'fill: {{VALUE}};',
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
					'{{WRAPPER}} .athemes-addons-posts-carousel .swiper-button-prev:hover' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .athemes-addons-posts-carousel .swiper-button-next:hover' => 'background-color: {{VALUE}};',
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
	 * @access public
	 */
	protected function render() {
		$settings = $this->get_settings();
		$settings['widget_id'] = $this->get_id();

		$helper = Posts_Helper::instance();

		$query_args = $helper->get_query_args( $settings );

		$query = new \WP_Query( $query_args );  

		if ( $query->have_posts() ) :

			//add render attribute to the wrapper
			$this->add_render_attribute( 'container', 'class', 'athemes-addons-posts-carousel-container' );
			$this->add_render_attribute( 'wrapper', 'class', 'athemes-addons-posts-carousel swiper-container' );
			$this->add_render_attribute( 'wrapper', 'data-autoplay', $settings['autoplay'] );
			$this->add_render_attribute( 'wrapper', 'data-autoplay-speed', $settings['autoplay_speed'] );
			$this->add_render_attribute( 'wrapper', 'data-infinite', $settings['infinite'] );
			$this->add_render_attribute( 'wrapper', 'data-pause-on-hover', $settings['pause_on_hover'] );
			$this->add_render_attribute( 'wrapper', 'data-transition-speed', $settings['transition_speed'] );
			$this->add_render_attribute( 'wrapper', 'data-arrows', $settings['show_arrows'] );
			$this->add_render_attribute( 'wrapper', 'data-dots', $settings['show_dots'] );
			$this->add_render_attribute( 'wrapper', 'data-items', isset( $settings['slides_to_show'] ) && '' !== $settings['slides_to_show'] ? $settings['slides_to_show'] : 3 );
			$this->add_render_attribute( 'wrapper', 'data-items-tablet', isset( $settings['slides_to_show_tablet'] ) ? $settings['slides_to_show_tablet'] : 2 );
			$this->add_render_attribute( 'wrapper', 'data-items-mobile', isset( $settings['slides_to_show_mobile'] ) ? $settings['slides_to_show_mobile'] : 1 );
			
			$page_id = '';
			if ( null !== Plugin::$instance->documents->get_current() ) {
				$page_id = Plugin::$instance->documents->get_current()->get_main_id();
			}

			$this->add_render_attribute( 'wrapper', 'data-page', $page_id );
			?>
			<div <?php $this->print_render_attribute_string( 'container' ); ?>>
				<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
					<div class="swiper-wrapper">
					<?php while ( $query->have_posts() ) : $query->the_post(); ?>
						<?php $this->post_template(); ?>
					<?php endwhile; ?>
					</div>
				</div>
				<?php if ( 'yes' === $settings['show_arrows'] ) : ?>
				<div class="swiper-button-prev"><svg xmlns="http://www.w3.org/2000/svg" width="21" height="40" fill="none"><path d="M3.589 20 20.564 2.556a1.498 1.498 0 1 0-2.149-2.09L.425 18.954a1.5 1.5 0 0 0 0 2.09l17.99 18.49a1.5 1.5 0 1 0 2.149-2.091L3.587 20h.002Z"/></svg></div>							
				<div class="swiper-button-next"><svg xmlns="http://www.w3.org/2000/svg" width="21" height="40" fill="none"><path d="M17.411 20 .436 2.556A1.5 1.5 0 1 1 2.585.466l17.99 18.489a1.5 1.5 0 0 1 0 2.09l-17.99 18.49a1.498 1.498 0 1 1-2.149-2.091L17.413 20h-.002Z"/></svg></div>
				<?php endif; ?>					
				<?php if ( 'yes' === $settings['show_dots'] ) : ?>
				<div class="posts-carousel-pagination"></div>
				<?php endif; ?>
			</div>
			<?php

		endif; //end have_posts() check
		wp_reset_postdata();
	}

	/**
	 * Loop item
	 */
	public function post_template() {
		$settings = $this->get_settings_for_display();

		$archive_meta_delimiter = $settings['delimiter'];

		?>
		<div class="athemes-post-item swiper-slide">
			<?php if ( has_post_thumbnail() && $settings['show_thumbnail'] ) : ?>
			<div class="post-item-thumb">
				<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_post_thumbnail( $settings['image_size'] ); ?></a>
			</div>
			<?php endif; ?>		

			<div class="post-content">
				<?php if ( $settings['show_cats'] ) : ?>
					<div class="post-info item-cats cats-<?php echo esc_attr( $settings['cat_display']); ?>">
					<?php athemes_addons_get_first_cat(); ?>
					</div>
				<?php endif; ?>	

				<?php if ( $settings['show_title'] ) {
						the_title( '<' . esc_attr( $settings['title_tag'] ) . ' class="item-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></' . esc_attr( $settings['title_tag'] ) . '>' );
					}
				?>

				<?php if ( 'yes' === $settings['show_excerpt'] ) {
					$excerpt = wp_trim_words( get_the_content(), $settings['excerpt_length'], '&hellip;' );
					echo '<div class="item-excerpt">' . esc_html( $excerpt ) . '</div>';
				}   
				?>

				<div class="post-info delimiter-<?php echo esc_attr( $archive_meta_delimiter ); ?>">
					<?php if ( $settings['show_author'] ) : ?>
						<?php athemes_addons_get_post_author(); ?>
					<?php endif; ?>
					<?php if ( $settings['show_date'] ) : ?>
						<?php athemes_addons_get_post_date(); ?>
					<?php endif; ?>
				</div>

				<?php if ( $settings['read_more_text'] ) : ?>
					<div class="read-more-wrapper">
						<a class="post-item-read-more button" title="<?php the_title_attribute(); ?>" href="<?php the_permalink(); ?>">
							<?php echo esc_html( $settings['read_more_text'] ); ?>
						</a>
					</div>
				<?php endif; ?>		
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