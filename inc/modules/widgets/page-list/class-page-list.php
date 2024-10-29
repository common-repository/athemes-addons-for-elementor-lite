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

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Page list widget.
 *
 * @since 1.0.0
 */
class Page_List extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'athemes-addons-page-list';
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
		return __( 'Page List', 'athemes-addons-elementor' );
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
		return 'eicon-bullet-list aafe-elementor-icon';
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
		return [ 'page', 'pages', 'page list', 'list', 'athemes', 'addons', 'athemes addons' ];
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
			'section_list',
			[
				'label' => __( 'List', 'athemes-addons-elementor' ),
			]
		);  

		$repeater = new Repeater();

		$repeater->add_control(
			'item_type',
			[
				'label' => __( 'Item type', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'custom',
				'options' => [
					'page'      => __( 'Page', 'athemes-addons-elementor' ),
					'custom'    => __( 'Custom link', 'athemes-addons-elementor' ),
				],
			]
		);

		$repeater->add_control(
			'page',
			[
				'label' => __( 'Page', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SELECT2,
				'options' => $this->get_pages(),
				'condition' => [
					'item_type' => 'page',
				],
				'separator' => 'before',
			]
		);

		$repeater->add_control(
			'item_title',
			[
				'label' => __( 'Title', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Item name', 'athemes-addons-elementor' ),
				'separator' => 'before',
				'condition' => [
					'item_type' => 'custom',
				],
			]
		);  
		
		$repeater->add_control(
			'item_subtitle',
			[
				'label' => __( 'Subtitle', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Item subtitle', 'athemes-addons-elementor' ),
			]
		);      
		
		$repeater->add_control(
			'item_link',
			[
				'label' => __( 'Link', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'athemes-addons-elementor' ),
				'default' => [
					'url' => '#',
				],
				'show_external' => true,
				'condition' => [
					'item_type' => 'custom',
				],
			]
		);

		$repeater->add_control(
			'item_icon',
			[
				'label' => __( 'Icon', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'default' => [
					'value' => 'fas fa-check-circle',
					'library' => 'fa-solid',
				],
				'separator' => 'before',
			]
		);

		$repeater->add_control(
			'show_badge',
			[
				'label' => __( 'Show Badge', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'return_value' => 'yes',
				'separator' => 'before',
			]
		);

		$repeater->add_control(
			'badge_text',
			[
				'label' => __( 'Badge Text', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Hot', 'athemes-addons-elementor' ),
				'condition' => [
					'show_badge' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'badge_bg_color',
			[
				'label' => __( 'Background Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .page-list-badge' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'show_badge' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'badge_text_color',
			[
				'label' => __( 'Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .page-list-badge' => 'color: {{VALUE}}',
				],
				'condition' => [
					'show_badge' => 'yes',
				],
			]
		);

		$this->add_control(
			'items',
			[
				'label' => __( 'List', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'item_title' => __( 'Item #1', 'athemes-addons-elementor' ),
						'item_subtitle' => __( 'Item subtitle', 'athemes-addons-elementor' ),
						'item_link' => [
							'url' => '#',
						],
						'item_icon' => [
							'value' => 'fas fa-check-circle',
							'library' => 'fa-solid',
						],
					],
					[
						'item_title' => __( 'Item #2', 'athemes-addons-elementor' ),
						'item_subtitle' => __( 'Item subtitle', 'athemes-addons-elementor' ),
						'item_link' => [
							'url' => '#',
						],
						'item_icon' => [
							'value' => 'fas fa-check-circle',
							'library' => 'fa-solid',
						],
						'show_badge' => 'yes',
					],
					[
						'item_title' => __( 'Item #3', 'athemes-addons-elementor' ),
						'item_subtitle' => __( 'Item subtitle', 'athemes-addons-elementor' ),
						'item_link' => [
							'url' => '#',
						],
						'item_icon' => [
							'value' => 'fas fa-check-circle',
							'library' => 'fa-solid',
						],
					],
				],
				'title_field' => '{{{ item_title }}}',
			]
		);

		$this->add_control(
			'view',
			[
				'label' => esc_html__( 'Layout', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'traditional',
				'options' => [
					'traditional' => [
						'title' => esc_html__( 'Default', 'athemes-addons-elementor' ),
						'icon' => 'eicon-editor-list-ul',
					],
					'inline' => [
						'title' => esc_html__( 'Inline', 'athemes-addons-elementor' ),
						'icon' => 'eicon-ellipsis-h',
					],
				],
				'prefix_class' => 'page-list-layout-',
			]
		);      

		$this->add_responsive_control(
			'alignment',
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
				'default' => 'left',
				'prefix_class' => 'elementor%s-align-',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			[
				'label' => __( 'General', 'athemes-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'item_padding',
			[
				'label' => __( 'Padding', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],

				'selectors' => [
					'{{WRAPPER}} .page-list-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'item_background',
				'label' => __( 'Background', 'athemes-addons-elementor' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .page-list-item',
			]
		);
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'item_border',
				'label' => __( 'Border', 'athemes-addons-elementor' ),
				'selector' => '{{WRAPPER}} .page-list-item',
			]
		);

		$this->add_responsive_control(
			'item_gap',
			[
				'label' => __( 'Item Gap', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],

				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],

				'selectors' => [
					'{{WRAPPER}}.page-list-layout-traditional .page-list-item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.page-list-layout-inline .page-list-item' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.page-list-layout-inline .page-list-item:last-child' => 'margin-right: 0;',
				],
			]
		);

		$this->add_control(
			'item_border_radius',
			[
				'label' => __( 'Border Radius', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .page-list-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'item_box_shadow',
				'label' => __( 'Box Shadow', 'athemes-addons-elementor' ),
				'selector' => '{{WRAPPER}} .page-list-item',
			]
		);
		
		$this->end_controls_section();

		$this->start_controls_section(
			'section_icon_style',
			[
				'label' => __( 'Icon', 'athemes-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label' => __( 'Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .page-list-icon' => 'color: {{VALUE}};fill: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'icon_spacing',
			[
				'label' => __( 'Spacing', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],

				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .page-list-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
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

		$this->add_control(
			'title_color',
			[
				'label' => __( 'Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .page-list-title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'title_hover_color',
			[
				'label' => __( 'Hover Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .page-list-title:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'selector' => '{{WRAPPER}} .page-list-title',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_subtitle_style',
			[
				'label' => __( 'Subtitle', 'athemes-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'subtitle_color',
			[
				'label' => __( 'Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .page-list-subtitle' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'subtitle_typography',
				'selector' => '{{WRAPPER}} .page-list-subtitle',
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
		
		$this->add_render_attribute( 'wrapper', 'class', 'athemes-addons-page-list' );

		?>

		<div <?php $this->print_render_attribute_string( 'wrapper' ); ?> >
			<div class="page-list-inner">		
				<?php foreach ( $settings['items'] as $index => $item ) : ?>
					<div class="page-list-item elementor-repeater-item-<?php echo esc_attr($item['_id']); ?>">
						<div class="page-list-title-wrapper">
						<?php if ( 'page' === $item['item_type'] ) : ?>
							<a href="<?php echo esc_url( get_permalink( $item['page'] ) ); ?>" class="page-list-link">
								<?php if ( ! empty( $item['item_icon']['value'] ) ) : ?>
									<span class="page-list-icon">
										<?php Icons_Manager::render_icon( $item['item_icon'], [ 'aria-hidden' => 'true' ] ); ?>
									</span>
								<?php endif; ?>
								<span class="page-list-title"><?php echo esc_html( get_the_title( $item['page'] ) ); ?></span>
							</a>
						<?php else : ?>
							<?php
							if ( ! empty( $item['item_link']['url'] ) ) {
								$link_key = 'link_' . $index;

								$this->add_link_attributes( $link_key, $item['item_link'] );
								$this->add_render_attribute( $link_key, 'class', 'page-list-link' );
								?>
								<a <?php $this->print_render_attribute_string( $link_key ); ?>>

								<?php
							}
							?>
							<?php if ( ! empty( $item['item_icon']['value'] ) ) : ?>
								<span class="page-list-icon">
									<?php Icons_Manager::render_icon( $item['item_icon'], [ 'aria-hidden' => 'true' ] ); ?>
								</span>
							<?php endif; ?>
							<span class="page-list-title"><?php echo esc_html( $item['item_title'] ); ?></span>
							<?php if ( ! empty( $item['item_link']['url'] ) ) : ?>
							</a>
							<?php endif; ?>
						<?php endif; ?>
						<?php if ( 'yes' === $item['show_badge'] ) : ?>
							<span class="page-list-badge"><?php echo esc_html( $item['badge_text'] ); ?></span>
						<?php endif; ?>						
						</div>
						<?php if ( ! empty( $item['item_subtitle'] ) ) : ?>
							<span class="page-list-subtitle"><?php echo esc_html( $item['item_subtitle'] ); ?></span>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>		
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

	/**
	 * Get page list.
	 */
	public function get_pages() {
		$pages = get_pages();
		$choices = [];

		foreach ( $pages as $page ) {
			$choices[ $page->ID ] = $page->post_title;
		}

		return $choices;
	}
}
Plugin::instance()->widgets_manager->register( new Page_List() );