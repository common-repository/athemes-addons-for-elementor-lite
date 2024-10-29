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
use Elementor\Icons_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Posts list widget.
 *
 *
 * @since 1.0.0
 */
class Posts_Timeline extends Widget_Base {

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
		return 'athemes-addons-posts-timeline';
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
		return __( 'Post timeline', 'athemes-addons-elementor' );
	}

	public function get_keywords() {
		return [ 'posts', 'timeline', 'blog', 'post', 'news', 'articles', 'athemes', 'addons', 'athemes addons' ];
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
		return 'eicon-time-line aafe-elementor-icon';
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
	 * Register widget controls.
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

		$this->add_control(
			'marker_position',
			[
				'label' => __( 'Marker position', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'center',
				'options' => [
					'left'      => __( 'Left', 'athemes-addons-elementor' ),
					'center'    => __( 'Center', 'athemes-addons-elementor' ),
					'right'     => __( 'Right', 'athemes-addons-elementor' ),
				],
			]
		);

		$this->add_control(
			'marker_style',
			[
				'label' => __( 'Marker style', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'circle',
				'options' => [
					'circle'            => __( 'Circle', 'athemes-addons-elementor' ),
					'date'              => __( 'Date', 'athemes-addons-elementor' ),
					'icon'              => __( 'Icon', 'athemes-addons-elementor' ),
					'featured-image'    => __( 'Featured image', 'athemes-addons-elementor' ),
				],
			]
		);

		$this->add_control(
			'marker_icon',
			[
				'label' => __( 'Marker icon', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-calendar-alt',
					'library' => 'fa-solid',
				],
				'condition' => [
					'marker_style' => 'icon',
				],
			]
		);

		$this->add_control(
			'marker_date_format',
			[
				'label' => __( 'Date format', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'd_M_Y',
				'options' => [
					'd_M_Y' => gmdate( 'd M Y' ),
					'M_d_Y' => gmdate( 'M d Y' ),
					'd_m_Y' => gmdate( 'd.m.Y' ),
					'm_d_Y' => gmdate( 'm.d.Y' ),
				],
				'condition' => [
					'marker_style' => 'date',
				],
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
				'default' => 3,
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

		$this->start_controls_section(
			'section_pagination',
			[
				'label' => __( 'Pagination', 'athemes-addons-elementor' ),
			]
		);

		$this->add_control(
			'paginated_posts',
			array(
				'label'       => __( 'Enable Pagination', 'athemes-addons-elementor' ),
				'type'        => Controls_Manager::SWITCHER,
				'separator'   => 'before',
			)
		);

		$this->add_control(
			'max_pages',
			array(
				'label'     => __( 'Page Limit', 'athemes-addons-elementor' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 5,
				'condition' => array(
					'paginated_posts' => 'yes',
				),
			)
		);

		$this->add_control(
			'pagination_strings',
			array(
				'label'     => __( 'Next/Prev Strings', 'athemes-addons-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'condition' => array(
					'paginated_posts' => 'yes',
				),
			)
		);

		$this->add_control(
			'pagination_prev_text',
			array(
				'label'     => __( 'Previous Page String', 'athemes-addons-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'Previous', 'athemes-addons-elementor' ),
				'condition' => array(
					'paginated_posts' => 'yes',
					'pagination_strings'  => 'yes',
				),
			)
		);

		$this->add_control(
			'pagination_next_text',
			array(
				'label'     => __( 'Next Page String', 'athemes-addons-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'Next', 'athemes-addons-elementor' ),
				'condition' => array(
					'paginated_posts' => 'yes',
					'pagination_strings'  => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'pagination_align',
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
				'default' => 'center',
				'selectors_dictionary' => [
					'left'      => 'margin-right: auto;',
					'center'    => 'margin-left: auto; margin-right: auto;',
					'right'     => 'margin-left: auto;',
				],
				'selectors' => [
					'{{WRAPPER}} .pagination-container' => '{{VALUE}};',
				],
				'condition' => [
					'paginated_posts' => 'yes',
				],
			]
		);

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
					'{{WRAPPER}} .athemes-addons-posts-timeline .post-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],              
			]
		);

		$this->add_control(
			'card_border_radius',
			[
				'label' => __( 'Border radius', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-posts-timeline .post-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				], 
			]
		);

		$this->add_control(
			'card_bg_color',
			[
				'label' => __( 'Background color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-posts-timeline' => '--background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'card_shadow',
				'selector' => '{{WRAPPER}} .athemes-addons-posts-timeline .post-content',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_marker',
			[
				'label' => __( 'Marker', 'athemes-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'marker_bg_color',
			[
				'label' => __( 'Background color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-posts-timeline .timeline-marker' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'marker_text_color',
			[
				'label' => __( 'Text color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-posts-timeline .timeline-marker' => 'color: {{VALUE}};fill: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'marker_typography',
				'selector' => '{{WRAPPER}} .athemes-addons-posts-timeline .timeline-marker',
			]
		);

		$this->add_control(
			'marker_border_radius',
			[
				'label' => __( 'Border radius', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-posts-timeline .timeline-marker' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'line_color',
			[
				'label' => __( 'Line color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-posts-timeline .athemes-post-item::after' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .athemes-addons-posts-timeline .timeline-marker' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'line_width',
			[
				'label' => __( 'Line width', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 1,
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 10,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-posts-timeline:not(.marker-position-left):not(.marker-position-right) .athemes-post-item::after' => 'width: {{SIZE}}{{UNIT}};left: calc(50% - {{SIZE}}{{UNIT}} / 2);',
					'{{WRAPPER}} .athemes-addons-posts-timeline .timeline-marker' => 'border-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

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

		$this->add_control(
			'date_heading',
			[
				'label' => esc_html__( 'Outer Date', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'date_color',
			[
				'label' => __( 'Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .post-date .posted-on a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'date_typography',
				'selector' => '{{WRAPPER}} .post-date .posted-on',
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

		// Pagination.
		$this->start_controls_section(
			'section_style_pagination',
			array(
				'label'     => __( 'Pagination', 'athemes-addons-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'paginated_posts' => 'yes',
				),
			)
		);

		$this->add_control(
			'pagination_color',
			array(
				'label'     => __( 'Color', 'athemes-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .athemes-posts-pagination a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'pagination_color_hover',
			array(
				'label'     => __( 'Hover Color', 'athemes-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .athemes-posts-pagination a:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render widget output on the frontend.
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

			if ( 'yes' === $settings['paginated_posts'] ) {

				$total_pages = $query->max_num_pages;

				Posts_Helper::$page_limit = $total_pages;

				if ( ! empty( $settings['max_pages'] ) ) {
					$total_pages = min( $settings['max_pages'], $total_pages );
				}
			}

			$this->add_render_attribute( 'wrapper', 'class', 'athemes-addons-posts-timeline' );

			$this->add_render_attribute( 'wrapper', 'class', 'timeline-marker-' . esc_attr( $settings['marker_style'] ) );

			$this->add_render_attribute( 'wrapper', 'class', 'marker-position-' . esc_attr( $settings['marker_position'] ) );
			
			$page_id = '';
			if ( null !== Plugin::$instance->documents->get_current() ) {
				$page_id = Plugin::$instance->documents->get_current()->get_main_id();
			}

			$this->add_render_attribute( 'wrapper', 'data-page', $page_id );

			if ( 'yes' === $settings['paginated_posts'] && $total_pages > 1 ) {
				$this->add_render_attribute( 'wrapper', 'data-pagination', 'true' );
			}

			?>
			<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
				<?php while ( $query->have_posts() ) : $query->the_post(); ?>
					<?php $this->post_template(); ?>
				<?php endwhile; ?>
			</div>
			<?php 

			if ( 'yes' === $settings['paginated_posts'] && $total_pages > 1 ) { ?>
				<div class="athemes-posts-pagination">
				<?php $helper->get_pagination( $settings ); ?>
				</div>
			<?php }

		endif;
		wp_reset_postdata();
	}

	/**
	 * Loop item
	 */
	public function post_template() {
		$settings = $this->get_settings_for_display();

		$archive_meta_delimiter = $settings['delimiter'];

		?>
		<div class="athemes-post-item">

			<div class="post-content">
				<?php if ( has_post_thumbnail() && $settings['show_thumbnail'] && 'featured-image' !== $settings['marker_style'] ) : ?>
				<div class="post-item-thumb">
					<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_post_thumbnail( $settings['image_size'] ); ?></a>
				</div>
				<?php endif; ?>		

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
					<?php if ( $settings['show_date'] && 'date' !== $settings['marker_style'] ) : ?>
					<div class="mobile-post-date">
						<?php athemes_addons_get_post_date(); ?>
					</div>
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

			<div class="timeline-marker">
			<?php if ( 'date' === $settings['marker_style'] ) : ?>
				<div class="marker-date">
					<?php $date_format = str_replace( '_', ' ', $settings['marker_date_format'] ); ?>
					<?php if ( 'd_m_Y' === $settings['marker_date_format'] || 'm_d_Y' === $settings['marker_date_format'] ) : ?>
						<?php $date_format = str_replace( '_', '.', $settings['marker_date_format'] ); ?>
					<?php endif; ?>
					<?php echo esc_html( get_the_date( $date_format ) ); ?>
				</div>
			<?php elseif ( 'icon' === $settings['marker_style'] ) : ?>
				<div class="marker-icon">
					<?php Icons_Manager::render_icon( $settings['marker_icon'], [ 'aria-hidden' => 'true' ] ); ?>
				</div>
			<?php elseif ( 'featured-image' === $settings['marker_style'] ) : ?>
				<div class="marker-image">
					<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_post_thumbnail( $settings['image_size'] ); ?></a>
				</div>
			<?php endif; ?>
			</div>

			<div class="post-date">
			<?php if ( $settings['show_date'] && 'date' !== $settings['marker_style'] ) : ?>
				<?php athemes_addons_get_post_date(); ?>
			<?php endif; ?>
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