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
use Elementor\Control_Media;
use aThemes_Addons_SVG_Icons;

use aThemes_Addons\Traits\Button_Trait;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Before After Image Widget.
 *
 * @since 1.0.0
 */
class Woo_Product_Grid extends Widget_Base {

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
		return 'athemes-addons-woo-product-grid';
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
		return __( 'Woo Product Grid', 'athemes-addons-elementor' );
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
		return 'eicon-woocommerce aafe-elementor-icon';
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
	 * Get widget keywords.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'woo', 'woocommerce', 'product', 'products', 'athemes', 'addons', 'athemes addons' ];
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
			'section_layout',
			[
				'label' => __( 'Layout', 'athemes-addons-elementor' ),
			]
		);

		$this->add_responsive_control(
			'columns',
			[
				'label' => esc_html__( 'Columns', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => '3',
				'options' => [
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				],
				'prefix_class' => 'elementor-grid%s-',
				'selectors' => [
					'{{WRAPPER}}' => '--grid-template-columns: repeat({{VALUE}}, auto);',
				],
			]
		);

		$this->add_responsive_control(
			'item_gap',
			[
				'label' => __( 'Item Gap', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
					'em' => [
						'min' => 0,
						'max' => 10,
					],
					'rem' => [
						'min' => 0,
						'max' => 10,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-grid' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'product_template',
			[
				'label' => __( 'Product Template', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'style1',
				'options' => [
					'style1'    => __( 'Style 1', 'athemes-addons-elementor' ),
					'style2'    => __( 'Style 2', 'athemes-addons-elementor' ),
					'style3'    => __( 'Style 3', 'athemes-addons-elementor' ),
					'style4'    => __( 'Style 4', 'athemes-addons-elementor' ),
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_query',
			[
				'label' => __( 'Query', 'athemes-addons-elementor' ),
			]
		);

		$this->add_control(
			'display_mode',
			[
				'label' => __( 'Query type', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'recent',
				'options' => [
					'recent'        => __( 'Recent products', 'athemes-addons-elementor' ),
					'featured'      => __( 'Featured products', 'athemes-addons-elementor' ),
					'sale'          => __( 'Products on sale', 'athemes-addons-elementor' ),
					'best_selling'  => __( 'Best Selling', 'athemes-addons-elementor' ),
					'top_rated'     => __( 'Top Rated', 'athemes-addons-elementor' ),
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'products_per_page',
			[
				'label' => __( 'Products Per Page', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 3,
			]
		);

		$this->add_control(
			'offset',
			[
				'label' => __( 'Offset', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0,
				'separator' => 'after',
			]
		);

		$this->add_control(
			'orderby',
			[
				'label' => __( 'Order By', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'date',
				'options' => [
					'date'       => __( 'Date', 'athemes-addons-elementor' ),
					'title'      => __( 'Title', 'athemes-addons-elementor' ),
					'price'      => __( 'Price', 'athemes-addons-elementor' ),
					'popularity' => __( 'Popularity', 'athemes-addons-elementor' ),
					'rating'     => __( 'Rating', 'athemes-addons-elementor' ),
					'random'     => __( 'Random', 'athemes-addons-elementor' ),
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
				'separator' => 'after',
			]
		);

		$this->add_control(
			'categories',
			[
				'label'     => __( 'Categories', 'athemes-addons-elementor' ),
				'type'      => Controls_Manager::SELECT2,
				'multiple'  => true,
				'options'   => Posts_Helper::get_terms_list( 'product_cat', 'slug' ),
			]
		);

		$this->add_control(
			'tags',
			[
				'label'     => __( 'Tags', 'athemes-addons-elementor' ),
				'type'      => Controls_Manager::SELECT2,
				'multiple'  => true,
				'options'   => Posts_Helper::get_terms_list( 'product_tag', 'slug' ),
			]
		);

		
		$this->add_control(
			'show_filter',
			[
				'label' => __( 'Show Filter', 'athemes-addons-elementor' ),
				'description' => __( 'Requires categories to be selected', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'show_filter_all_text',
			[
				'label' => __( 'Show All Text', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Show all', 'athemes-addons-elementor' ),
				'condition' => [
					'show_filter' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_category_thumbs',
			[
				'label' => __( 'Show Category Thumbs', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
			]
		);

		$this->add_control(
			'all_products_thumb',
			[
				'label' => __( 'Show All Thumb', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'show_category_thumbs' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_load_more',
			[
				'label' => __( 'Show Load More', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'load_more_text',
			[
				'label' => __( 'Load More Text', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'Load More',
				'condition' => [
					'show_load_more' => 'yes',
				],
			]
		);

		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_elements',
			[
				'label' => __( 'Product elements', 'athemes-addons-elementor' ),
			]
		);

		$this->add_control(
			'show_image',
			[
				'label' => __( 'Show Image', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_category',
			[
				'label' => __( 'Show Category', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
			]
		);

		$this->add_control(
			'title_html_tag',
			[
				'label' => __( 'Title HTML Tag', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'h2',
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
			]
		);

		$this->add_control(
			'show_rating',
			[
				'label' => __( 'Show Rating', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_short_description',
			[
				'label' => __( 'Show Short Description', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
			]
		);

		$this->add_control(
			'show_price',
			[
				'label' => __( 'Show Price', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_add_to_cart',
			[
				'label' => __( 'Show Add to Cart', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_responsive_control(
			'sale_badge_alignment',
			[
				'label' => __( 'Sale badge alignment', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left'      => [
						'title' => __( 'Left', 'athemes-addons-elementor' ),
						'icon' => 'eicon-order-start',
					],
					'right'     => [
						'title' => __( 'Right', 'athemes-addons-elementor' ),
						'icon' => 'eicon-order-end',
					],
				],
				'default' => 'right',
				'selectors_dictionary' => [
					'left' => 'left: 10px !important;right:auto',
					'right' => 'right: 10px;left:auto!important',
				],
				'selectors' => [
					'{{WRAPPER}} .products li.product .onsale' => '{{VALUE}};',
				],
			]
		);  
		
		$this->add_control(
			'sale_badge_text',
			[
				'label' => __( 'Sale badge text', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Sale!', 'athemes-addons-elementor' ),
			]
		);

		if ( !class_exists( 'Merchant' ) ) {
			$this->add_control(
				'notice_merchant',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw' => sprintf(
						/* translators: %s: Merchant plugin install URL */
						__( 'Get extra elements for your product cards like: Quick View, Buy Now, Wishlist, Product Swatches & more. Click to <a target="_blank" href="%s">install Merchant</a>.', 'athemes-addons-elementor' ),
						esc_url( admin_url( 'plugin-install.php?s=merchant+toolkit+athemes&tab=search&type=term' ) )
					),
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				]
			);
		} else {
			$this->add_control(
				'more_options',
				[
					'label' => esc_html__( 'Merchant', 'athemes-addons-elementor' ),
					'type' => Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_control(
				'notice_merchant',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw' => __( 'Make sure you first enable the modules you want to use in Merchant\'s settings.', 'athemes-addons-elementor' ),
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				]
			);

			$this->add_control(
				'enable_merchant_buy_now',
				[
					'label' => __( 'Enable Buy Now', 'athemes-addons-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'default' => 'no',
				]
			);

			$this->add_control(
				'enable_merchant_wishlist',
				[
					'label' => __( 'Enable Wishlist', 'athemes-addons-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'default' => 'no',
				]
			);

			$this->add_control(
				'enable_merchant_quick_view',
				[
					'label' => __( 'Enable Quick View', 'athemes-addons-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'default' => 'no',
				]
			);

			$this->add_control(
				'enable_merchant_product_swatches',
				[
					'label' => __( 'Enable Product Swatches', 'athemes-addons-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'default' => 'no',
				]
			);
		}

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
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
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-products-grid' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .athemes-addons-products-grid' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'wrapper_background_color',
			[
				'label' => __( 'Background Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-products-grid' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'wrapper_border_radius',
			[
				'label' => __( 'Border Radius', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-products-grid' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'wrapper_border',
				'selector' => '{{WRAPPER}} .athemes-addons-products-grid',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'wrapper_box_shadow',
				'selector' => '{{WRAPPER}} .athemes-addons-products-grid',
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

		$this->add_responsive_control(
			'filter_alignment',
			[
				'label' => __( 'Alignment', 'athemes-addons-elementor' ),
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
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .product-filter' => 'justify-content: {{VALUE}};',
				],
			]
		);      

		$this->add_control(
			'filter_background',
			[
				'label' => __( 'Background', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .product-filter' => 'background-color: {{VALUE}};',
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
					'{{WRAPPER}} .product-filter' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .product-filter' => 'margin-bottom: {{SIZE}}{{UNIT}};',
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
				'selector' => '{{WRAPPER}} .product-filter span',
			]
		);

		$this->add_responsive_control(
			'filter_items_padding',
			[
				'label' => __( 'Padding', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .product-filter span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .product-filter span' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .product-filter span:last-child' => 'margin-right: 0;',
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
					'{{WRAPPER}} .product-filter span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .product-filter span:not(.active)' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'filter_items_background',
			[
				'label' => __( 'Background', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .product-filter span:not(.active)' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'filter_items_border_color',
			[
				'label' => __( 'Border Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .product-filter span:not(.active)' => 'border-color: {{VALUE}};',
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
					'{{WRAPPER}} .product-filter span:not(.active):hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'filter_items_hover_background',
			[
				'label' => __( 'Background', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .product-filter span:not(.active):hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'filter_items_hover_border_color',
			[
				'label' => __( 'Border Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .product-filter span:not(.active):hover' => 'border-color: {{VALUE}};',
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
					'{{WRAPPER}} .product-filter span.active' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'filter_items_active_background',
			[
				'label' => __( 'Background', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .product-filter span.active' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'filter_items_active_border_color',
			[
				'label' => __( 'Border Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .product-filter span.active' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_product_card_style',
			[
				'label' => __( 'Product Card', 'athemes-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'product_card_padding',
			[
				'label' => __( 'Padding', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .products li.product' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'product_card_background_color',
			[
				'label' => __( 'Background Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .products li.product' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'product_card_border_radius',
			[
				'label' => __( 'Border Radius', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .products li.product' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'product_card_border',
				'selector' => '{{WRAPPER}} .products li.product',
			]
		);

		$this->start_controls_tabs( 'product_card_style_tabs' );

		$this->start_controls_tab( 'product_card_style_normal_tab', [ 'label' => esc_html__( 'Normal', 'athemes-addons-elementor' ) ] );

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'product_card_box_shadow',
				'selector' => '{{WRAPPER}} .products li.product',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'product_card_style_hover_tab', [ 'label' => esc_html__( 'Hover', 'athemes-addons-elementor' ) ] );

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'product_card_hover_box_shadow',
				'selector' => '{{WRAPPER}} .products li.product:hover',
			]
		);

		$this->add_control(
			'product_card_hover_border_color',
			[
				'label' => __( 'Border Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .products li.product:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_product_image_style',
			[
				'label' => __( 'Image', 'athemes-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'product_image_height',
			[
				'label' => __( 'Height', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 50,
						'max' => 600,
					],
				],
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .products li.product img' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'product_image_border_radius',
			[
				'label' => __( 'Border Radius', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .products li.product img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_product_content_style',
			[
				'label' => __( 'Content', 'athemes-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'content_style_title_heading',
			[
				'label' => __( 'Title', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'product_title_typography',
				'selector' => '{{WRAPPER}} .products li.product .woocommerce-loop-product__title',
			]
		);

		$this->add_control(
			'product_title_color',
			[
				'label' => __( 'Title Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .products li.product .woocommerce-loop-product__title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'product_title_margin',
			[
				'label' => __( 'Margin', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .products li.product .woocommerce-loop-product__title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'content_style_short_desc_heading',
			[
				'label' => __( 'Short Description', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'product_short_desc_typography',
				'selector' => '{{WRAPPER}} .products li.product .product-short-description',
			]
		);

		$this->add_control(
			'product_short_desc_color',
			[
				'label' => __( 'Short Description Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .products li.product .product-short-description' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'content_style_price_heading',
			[
				'label' => __( 'Price', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'product_price_typography',
				'selector' => '{{WRAPPER}} .products li.product .price',
			]
		);

		$this->add_control(
			'product_price_color',
			[
				'label' => __( 'Price Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .products li.product .price' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'product_discounted_price_color',
			[
				'label' => __( 'Full Price Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .products li.product .price del' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'content_style_desc_heading',
			[
				'label' => __( 'Short description', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'condition' => [
					'show_short_description' => 'yes',
				],
				'separator' => 'before',
			]
		);      

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'product_short_description_typography',
				'selector' => '{{WRAPPER}} .products li.product .product-short-description',
				'condition' => [
					'show_short_description' => 'yes',
				],
			]
		);

		$this->add_control(
			'product_short_description_color',
			[
				'label' => __( 'Short Description Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .products li.product .product-short-description' => 'color: {{VALUE}};',
				],
				'condition' => [
					'show_short_description' => 'yes',
				],
			]
		);

		$this->add_control(
			'content_style_rating_heading',
			[
				'label' => __( 'Rating', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'condition' => [
					'show_rating' => 'yes',
				],
				'separator' => 'before',
			]
		);              

		$this->add_control(
			'product_rating_color',
			[
				'label' => __( 'Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .products li.product .star-rating,{{WRAPPER}} .products li.product .star-rating::before' => 'color: {{VALUE}};',
				],
				'condition' => [
					'show_rating' => 'yes',
				],
			]
		);

		$this->add_control(
			'content_style_category_heading',
			[
				'label' => __( 'Category', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'condition' => [
					'show_category' => 'yes',
				],
				'separator' => 'before',
			]
		);      

		$this->add_control(
			'product_category_color',
			[
				'label' => __( 'Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .products li.product .aafe-product-category' => 'color: {{VALUE}};',
				],
				'condition' => [
					'show_category' => 'yes',
				],
			]
		);

		$this->add_control(
			'product_category_color_hover',
			[
				'label' => __( 'Hover Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .products li.product .aafe-product-category:hover' => 'color: {{VALUE}};',
				],
				'condition' => [
					'show_category' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_sale_badge_style',
			[
				'label' => __( 'Sale Badge', 'athemes-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'sale_badge_padding',
			[
				'label' => __( 'Padding', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .products li.product .onsale' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'sale_badge_margin',
			[
				'label' => __( 'Margin', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .products li.product .onsale' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'sale_badge_background_color',
			[
				'label' => __( 'Background Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .products li.product .onsale' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'sale_badge_color',
			[
				'label' => __( 'Text Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .products li.product .onsale' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'sale_badge_border_radius',
			[
				'label' => __( 'Border Radius', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .products li.product .onsale' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_button_style',
			[
				'label' => __( 'Add to cart button', 'athemes-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->register_button_style_controls( $args = array( 'class' => 'product-buttons a' ) );

		$this->end_controls_section();

		$this->start_controls_section(
			'section_load_more_button_style',
			[
				'label' => __( 'Load More Button', 'athemes-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_load_more' => 'yes',
				],
			]
		);

		$this->register_button_style_controls( $args = array( 'class' => 'load-more-button', 'section_condition' => array( 'show_load_more' => 'yes' ) ) );

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
		
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}

		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'wrapper', 'class', 'athemes-addons-products-grid woocommerce' );
		
		$this->add_render_attribute( 'wrapper', 'data-product-template', esc_attr( $settings['product_template'] ) );

		if ( !empty( $settings['categories'] ) ) {

			if ( !is_array( $settings['categories'] ) ) {
				$settings['categories'] = array( $settings['categories'] );
			}
			
			$this->add_render_attribute( 'wrapper', 'data-categories', esc_attr( implode( ',', $settings['categories'] ) ) );
		}
		$this->add_render_attribute( 'wrapper', 'data-display-mode', esc_attr( $settings['display_mode'] ) );
		$this->add_render_attribute( 'wrapper', 'data-widget-id', $this->get_id() );
		$this->add_render_attribute( 'wrapper', 'data-page-id', get_the_ID() );
		$this->add_render_attribute( 'wrapper', 'data-posts-per-page', esc_attr( $settings['products_per_page'] ) );
		$this->add_render_attribute( 'wrapper', 'data-offset', esc_attr( $settings['offset'] ) );
		$this->add_render_attribute( 'wrapper', 'data-orderby', esc_attr( $settings['orderby'] ) );
		$this->add_render_attribute( 'wrapper', 'data-order', esc_attr( $settings['order'] ) );

		$query_args = array(
			'post_type'         => 'product',
			'posts_per_page'    => $settings['products_per_page'],
			'offset'            => $settings['offset'],
			'orderby'           => $settings['orderby'],
			'order'             => $settings['order'],
		);

		if ( ! empty( $settings['categories'] ) ) {
			$query_args['tax_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
				array(
					'taxonomy' => 'product_cat',
					'field'    => 'slug',
					'terms'    => $settings['categories'],
				),
			);
		}

		if ( ! empty( $settings['tags'] ) ) {
			$query_args['tax_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
				array(
					'taxonomy' => 'product_tag',
					'field'    => 'slug',
					'terms'    => $settings['tags'],
				),
			);
		}

		switch ( $settings['display_mode'] ) {
			case 'featured':
				$query_args['meta_query'] = WC()->query->get_meta_query(); // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
				$query_args['post__in'] = wc_get_featured_product_ids();
				break;
			case 'sale':
				$query_args['meta_query'] = WC()->query->get_meta_query(); // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
				$query_args['post__in'] = array_merge( array( 0 ), wc_get_product_ids_on_sale() );
				break;
			case 'best_selling':
				$query_args['meta_key'] = 'total_sales'; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
				$query_args['orderby'] = 'meta_value_num';
				break;
			case 'top_rated':
				$query_args['meta_key'] = '_wc_average_rating'; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
				$query_args['orderby'] = 'meta_value_num';
				break;
		}

		$products = new \WP_Query( $query_args );

		$max_pages = $products->max_num_pages;
		$current_page = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

		$this->add_render_attribute( 'wrapper', 'data-max-pages', esc_attr( $max_pages ) );
		$this->add_render_attribute( 'wrapper', 'data-current-page', esc_attr( $current_page ) );
		?>

		<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
			<?php
			if ( 'yes' === $settings['show_filter'] ) {
				$terms = $settings['categories'];

				if ( !empty( $terms ) ) {
					?>
					<div class="product-filter">
						<span class="active" data-filter="all">
							<?php
								if ( $settings['show_category_thumbs'] ) {
									if ( isset( $settings['all_products_thumb']['id'] ) ) {
										$thumbnail_id   = $settings['all_products_thumb']['id'];
										$thumbnail      = wp_get_attachment_image_url( $thumbnail_id, 'thumbnail' );

										if ( $thumbnail ) {
											?>
											<img src="<?php echo esc_url( $thumbnail ); ?>" alt="<?php echo esc_attr( $settings['show_filter_all_text'] ); ?>">
											<?php
										}
									}
								}
							?>
							<?php echo esc_html( $settings['show_filter_all_text'] ); ?>
						</span>
						<?php
						foreach ( $terms as $term ) {
							$term = get_term_by( 'slug', $term, 'product_cat' );
							?>
							<span data-filter="<?php echo esc_attr( $term->slug ); ?>">
								<?php
									if ( $settings['show_category_thumbs'] ) {
										$thumbnail_id   = get_term_meta( $term->term_id, 'thumbnail_id', true );
										$thumbnail      = wp_get_attachment_image_url( $thumbnail_id, 'thumbnail' );

										if ( $thumbnail ) {
											?>
											<img src="<?php echo esc_url( $thumbnail ); ?>" alt="<?php echo esc_attr( $term->name ); ?>">
											<?php
										}
									}
								?>
								<?php echo esc_html( $term->name ); ?>
							</span>
							<?php
						}
						?>
					</div>
					<?php
				}
			} 
			?>
			<div class="product-grid-inner">
				<ul data-products class="products elementor-grid">
					<?php
					if ( $products->have_posts() ) {
						while ( $products->have_posts() ) {
							$products->the_post();

							$this->load_product_template( $settings );
						}
					} else {
						echo esc_html__( 'No products found', 'athemes-addons-elementor' );
					}
					wp_reset_postdata();
					?>

				</ul>

				<?php if ( 'yes' === $settings['show_load_more'] ) : ?>
				<?php $this->add_render_attribute( 'load_more_button', 'class', 'load-more-button button' ); ?>
				<?php if ( ! empty( $settings['load-more-button_hover_animation'] ) ) {
					$this->add_render_attribute( 'load_more_button', 'class', 'elementor-animation-' . $settings['load-more-button_hover_animation'] );
				} ?>
				<div class="product-grid-load-more">
					<div <?php $this->print_render_attribute_string( 'load_more_button' ); ?>>
						<span><?php echo aThemes_Addons_SVG_Icons::get_svg_icon( 'icon-loading' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						<?php echo esc_html( $settings['load_more_text'] ); ?>
					</div>
				</div>
				<?php endif; ?>

				<?php if ( 'yes' === $settings['show_filter'] ) : ?>
				<div class="product-grid-loader">
					<?php echo aThemes_Addons_SVG_Icons::get_svg_icon( 'icon-loading' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
				<?php endif; ?>			
			</div>
		</div>
	
		<?php
	}

	/**
	 * Load product template.
	 *
	 * @param string $template
	 */
	protected function load_product_template( $settings ) {	

		$file = ATHEMES_AFE_DIR . 'inc/modules/widgets/woo-product-grid/templates/product-template-' . esc_attr( $settings['product_template'] ) . '.php';

		if ( !file_exists( $file ) ) {
			return;
		}

		include $file;
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
Plugin::instance()->widgets_manager->register( new Woo_Product_Grid() );