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

/**
 * Animated heading widget.
 *
 * @since 1.0.0
 */
class Image_Accordion extends Widget_Base {

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
		return 'athemes-addons-image-accordion';
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
		return __( 'Image accordion', 'athemes-addons-elementor' );
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
		return 'eicon-accordion aafe-elementor-icon';
	}

	/**
	 * Enqueue styles.
	 */
	public function get_style_depends() {
		return [ $this->get_name() . '-styles' ];
	}

	/**
	 * Enqueue scripts
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
		return [ 'accordion', 'image accordion', 'image', 'athemes', 'addons', 'athemes addons' ];
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
			'section_image_accordion',
			[
				'label' => __( 'Image accordion', 'athemes-addons-elementor' ),
			]
		);  

		$repeater = new Repeater();

		$repeater->add_control(
			'image',
			[
				'label' => __( 'Image', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$repeater->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'thumb',
				'default' => 'large',
			]
		);

		//image_position
		$repeater->add_control(
			'image_position',
			[
				'label' => __( 'Image position', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'center',
				'options' => [
					'top'       => __( 'Top', 'athemes-addons-elementor' ),
					'bottom'    => __( 'Bottom', 'athemes-addons-elementor' ),
					'left'      => __( 'Left', 'athemes-addons-elementor' ),
					'right'     => __( 'Right', 'athemes-addons-elementor' ),
					'center'    => __( 'Center', 'athemes-addons-elementor' ),
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} img' => 'object-position: {{VALUE}};',
				],
				'separator' => 'after',
			]
		);

		$repeater->add_control(
			'title',
			[
				'label' => __( 'Title', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
			]
		);  

		$repeater->add_control(
			'text',
			[
				'label' => __( 'Description', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::WYSIWYG,
			]
		);

		$repeater->add_control(
			'show_button',
			[
				'label' => __( 'Show button', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$repeater->add_control(
			'button_text',
			[
				'label' => __( 'Button text', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Read more', 'athemes-addons-elementor' ),
				'condition' => [
					'show_button' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'button_link',
			[
				'label' => __( 'Button link', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'athemes-addons-elementor' ),
				'default' => [
					'url' => '#',
				],
				'condition' => [
					'show_button' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'active_image',
			[
				'label' => __( 'Active image', 'athemes-addons-elementor' ),
				'description' => __( 'This accordion item will be opened by default.', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'return_value' => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'image_accordion',
			[
				'type' => Controls_Manager::REPEATER,
				'label' => __( 'Items', 'athemes-addons-elementor' ),
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'image' => [
							'url'       => Utils::get_placeholder_image_src(),
						],
						'title'         => __( 'Accordion title 1', 'athemes-addons-elementor' ),
						'text'          => __( 'Lorem ipsum dolor sit amet', 'athemes-addons-elementor' ),
						'show_button'   => 'yes',
					],
					[
						'image' => [
							'url'       => Utils::get_placeholder_image_src(),
						],
						'title'         => __( 'Accordion title 2', 'athemes-addons-elementor' ),
						'text'          => __( 'Lorem ipsum dolor sit amet', 'athemes-addons-elementor' ),
						'show_button'   => 'yes',
					],
					[
						'image' => [
							'url'       => Utils::get_placeholder_image_src(),
						],
						'title'         => __( 'Accordion title 3', 'athemes-addons-elementor' ),
						'text'          => __( 'Lorem ipsum dolor sit amet', 'athemes-addons-elementor' ),
						'show_button'   => 'yes',
					],
					[
						'image' => [
							'url'       => Utils::get_placeholder_image_src(),
						],
						'title'         => __( 'Accordion title 4', 'athemes-addons-elementor' ),
						'text'          => __( 'Lorem ipsum dolor sit amet', 'athemes-addons-elementor' ),
						'show_button'   => 'yes',
					],                  
				],              
				'title_field' => '{{{ title }}}',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_settings',
			[
				'label' => __( 'Settings', 'athemes-addons-elementor' ),
			]
		);

		$this->add_control(
			'direction',
			[
				'label' => __( 'Direction', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'horizontal',
				'options' => [
					'horizontal'    => __( 'Horizontal', 'athemes-addons-elementor' ),
					'vertical'      => __( 'Vertical', 'athemes-addons-elementor' ),
				],
			]
		);

		$this->add_control(
			'open_mode',
			[
				'label' => __( 'Open mode', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'hover',
				'options' => [
					'hover'     => __( 'Hover', 'athemes-addons-elementor' ),
					'click'     => __( 'Click', 'athemes-addons-elementor' ),
				],
				'separator' => 'after',
			]
		);

		$this->add_responsive_control(
			'height',
			[
				'label' => __( 'Height', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'vh' ],
				'range' => [
					'px' => [
						'min' => 100,
						'max' => 1000,
					],
					'em' => [
						'min' => 1,
						'max' => 100,
					],
					'vh' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 500,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .image-accordion-inner' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'spacing',
			[
				'label' => __( 'Spacing', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
					'em' => [
						'min' => 0,
						'max' => 10,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .image-accordion-inner' => 'gap: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'growth_rate',
			[
				'label' => __( 'Growth rate', 'athemes-addons-elementor' ),
				'description' => __( 'The rate at which the active item will grow.', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 4,
				'min' => 2,
				'max' => 10,
				'step' => 1,
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-image-accordion[data-open-mode=hover] .image-accordion-item:hover' => 'flex: {{SIZE}};',
					'{{WRAPPER}} .athemes-addons-image-accordion[data-open-mode=click] .image-accordion-item.accordion-item-active' => 'flex: {{SIZE}};',
				],
			]
		);

		$this->add_control(
			'transition_time',
			[
				'label' => __( 'Transition time', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.6,
				'min' => 0.1,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-image-accordion' => '--transition-time: {{SIZE}}s;',
				],
				'separator' => 'after',
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
					'{{WRAPPER}} .athemes-addons-image-accordion .image-accordion-item' => 'justify-content: {{VALUE}};',
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
					'left'      => 'align-items:flex-start;text-align:left',
					'center'    => 'align-items:center;text-align:center',
					'right'     => 'align-items:flex-end;text-align:right',
				],
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-image-accordion .image-accordion-item' => '{{VALUE}};',

				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'title_html_tag',
			[
				'label' => __( 'Title HTML Tag', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1'    => 'H1',
					'h2'    => 'H2',
					'h3'    => 'H3',
					'h4'    => 'H4',
					'h5'    => 'H5',
					'h6'    => 'H6',
					'div'   => 'div',
					'span'  => 'span',
					'p'     => 'p',
				],
				'default' => 'h3',
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

		$this->add_control(
			'wrapper_background',
			[
				'label' => __( 'Background', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-image-accordion' => 'background-color: {{VALUE}};',
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
					'{{WRAPPER}} .athemes-addons-image-accordion' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .athemes-addons-image-accordion' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'wrapper_border',
				'label' => __( 'Border', 'athemes-addons-elementor' ),
				'selector' => '{{WRAPPER}} .athemes-addons-image-accordion',
			]
		);

		$this->add_responsive_control(
			'wrapper_border_radius',
			[
				'label' => __( 'Border Radius', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-image-accordion' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'wrapper_box_shadow',
				'label' => __( 'Box Shadow', 'athemes-addons-elementor' ),
				'selector' => '{{WRAPPER}} .athemes-addons-image-accordion',
			]
		);

		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_item_style',
			[
				'label' => __( 'Item', 'athemes-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'item_padding',
			[
				'label' => __( 'Padding', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-image-accordion .image-accordion-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'item_border',
				'label' => __( 'Border', 'athemes-addons-elementor' ),
				'selector' => '{{WRAPPER}} .athemes-addons-image-accordion .image-accordion-item',
			]
		);

		$this->add_responsive_control(
			'item_border_radius',
			[
				'label' => __( 'Border Radius', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-image-accordion .image-accordion-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'item_box_shadow',
				'label' => __( 'Box Shadow', 'athemes-addons-elementor' ),
				'selector' => '{{WRAPPER}} .athemes-addons-image-accordion .image-accordion-item',
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'overlay_color',
				'label' => __( 'Background', 'athemes-addons-elementor' ),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'default' => 'rgba(0,0,0,0.5)',
				'selector' => '{{WRAPPER}} .athemes-addons-image-accordion .image-accordion-item:after',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content_style',
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
					'{{WRAPPER}} .athemes-addons-image-accordion .image-accordion-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => __( 'Title Typography', 'athemes-addons-elementor' ),
				'selector' => '{{WRAPPER}} .athemes-addons-image-accordion .image-accordion-title',
			]
		);

		$this->add_responsive_control(
			'title_margin',
			[
				'label' => __( 'Title Margin', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-image-accordion .image-accordion-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'text_color',
			[
				'label' => __( 'Text Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-image-accordion .image-accordion-text' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'text_typography',
				'label' => __( 'Text Typography', 'athemes-addons-elementor' ),
				'selector' => '{{WRAPPER}} .athemes-addons-image-accordion .image-accordion-text',
			]
		);

		$this->add_responsive_control(
			'text_margin',
			[
				'label' => __( 'Text Margin', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-image-accordion .image-accordion-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
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

		$this->register_button_style_controls( $args = array( 'class' => 'image-accordion-button' ) );

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
		
		$this->add_render_attribute( 'image-accordion', 'class', 'athemes-addons-image-accordion' );
		$this->add_render_attribute( 'image-accordion', 'class', 'image-accordion-' . esc_attr( $settings['direction'] ) );
		$this->add_render_attribute( 'image-accordion', 'data-open-mode', esc_attr( $settings['open_mode' ] ) );
		?>

		<div <?php $this->print_render_attribute_string( 'image-accordion' ); ?> >
			<div class="image-accordion-inner">
				<?php foreach ( $settings['image_accordion'] as $item ) : ?>
					<?php $active_item = ''; ?>
					<?php if ( 'yes' === $item['active_image'] ) : ?>
						<?php $active_item = 'accordion-item-active'; ?>
					<?php endif; ?>
					<div class="image-accordion-item <?php echo esc_attr( $active_item ); ?> elementor-repeater-item-<?php echo esc_attr($item['_id']); ?>">
						<?php Group_Control_Image_Size::print_attachment_image_html( $item, 'thumb', 'image' ); ?>
						<div class="image-accordion-content">
							<<?php echo esc_attr( $settings['title_html_tag'] ); ?> class="image-accordion-title"><?php echo esc_html( $item['title'] ); ?></<?php echo esc_attr( $settings['title_html_tag'] ); ?>>
							<div class="image-accordion-text"><?php echo wp_kses_post( $item['text'] ); ?></div>
							<?php if ( 'yes' === $item['show_button'] ) : ?>
								<a href="<?php echo esc_url( $item['button_link']['url'] ); ?>" class="button image-accordion-button"><?php echo esc_html( $item['button_text'] ); ?></a>
							<?php endif; ?>
						</div>
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
}
Plugin::instance()->widgets_manager->register( new Image_Accordion() );