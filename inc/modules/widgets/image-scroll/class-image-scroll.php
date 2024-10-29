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
use aThemes_Addons_SVG_Icons;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Before After Image Widget.
 *
 * @since 1.0.0
 */
class Image_Scroll extends Widget_Base {

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
		return 'athemes-addons-image-scroll';
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
		return __( 'Image Scroll', 'athemes-addons-elementor' );
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
		return 'eicon-image-rollover aafe-elementor-icon';
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
		return [ 'image', 'scroll', 'image scroll', 'athemes', 'addons', 'athemes addons' ];
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
			'section_images',
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

		$this->add_responsive_control(
			'height',
			[
				'label' => __( 'Height', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 1000,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 350,
				],
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-image-scroll .image-scroll-inner' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .athemes-addons-image-scroll[data-direction=ltr] .image-scroll-inner img, {{WRAPPER}} .athemes-addons-image-scroll[data-direction=rtl] .image-scroll-inner img' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'link',
			[
				'label' => __( 'Link', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'athemes-addons-elementor' ),
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_overlay',
			[
				'label' => __( 'Overlay', 'athemes-addons-elementor' ),
			]
		);

		$this->add_control(
			'show_overlay',
			[
				'label' => __( 'Show Overlay', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'athemes-addons-elementor' ),
				'label_off' => __( 'Hide', 'athemes-addons-elementor' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);

		$this->add_control(
			'overlay_element',
			[
				'label' => __( 'Overlay Element', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'none' => __( 'None', 'athemes-addons-elementor' ),
					'text' => __( 'Text', 'athemes-addons-elementor' ),
					'icon' => __( 'Icon', 'athemes-addons-elementor' ),
				],
				'condition' => [
					'show_overlay' => 'yes',
				],
			]
		);

		$this->add_control(
			'overlay_text',
			[
				'label' => __( 'Overlay Text', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'View image', 'athemes-addons-elementor' ),
				'condition' => [
					'show_overlay' => 'yes',
					'overlay_element' => 'text',
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

		$this->add_control(
			'direction',
			[
				'label' => __( 'Direction', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'ttb',
				'options' => [
					'ltr'   => __( 'Left to Right', 'athemes-addons-elementor' ),
					'rtl'   => __( 'Right to Left', 'athemes-addons-elementor' ),
					'ttb'   => __( 'Top to Bottom', 'athemes-addons-elementor' ),
					'btt'   => __( 'Bottom to Top', 'athemes-addons-elementor' ),
				],
			]
		);

		$this->add_control(
			'trigger_type',
			[
				'label' => __( 'Trigger Type', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'hover',
				'options' => [
					'hover'     => __( 'Hover', 'athemes-addons-elementor' ),
					'mouse'     => __( 'Mouse scroll', 'athemes-addons-elementor' ),
				],
			]
		);

		$this->add_control(
			'speed',
			[
				'label' => __( 'Speed', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 's' ],
				'range' => [
					's' => [
						'min' => 0.1,
						'max' => 10,
						'step' => 0.1,
					],
				],
				'default' => [
					'unit' => 's',
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-image-scroll .image-scroll-inner img' => 'transition-duration: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'trigger_type' => 'hover',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_wrapper',
			[
				'label' => __( 'Wrapper', 'athemes-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'wrapper_border_radius',
			[
				'label' => __( 'Border Radius', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-image-scroll' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'wrapper_style_tabs' );

		$this->start_controls_tab(
			'wrapper_style_normal_tab',
			[
				'label' => __( 'Normal', 'athemes-addons-elementor' ),
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'wrapper_border',
				'label' => __( 'Border', 'athemes-addons-elementor' ),
				'selector' => '{{WRAPPER}} .athemes-addons-image-scroll',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'wrapper_box_shadow',
				'label' => __( 'Box Shadow', 'athemes-addons-elementor' ),
				'selector' => '{{WRAPPER}} .athemes-addons-image-scroll',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'wrapper_style_hover_tab',
			[
				'label' => __( 'Hover', 'athemes-addons-elementor' ),
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'wrapper_hover_border',
				'label' => __( 'Border', 'athemes-addons-elementor' ),
				'selector' => '{{WRAPPER}} .athemes-addons-image-scroll:hover',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'wrapper_hover_box_shadow',
				'label' => __( 'Box Shadow', 'athemes-addons-elementor' ),
				'selector' => '{{WRAPPER}} .athemes-addons-image-scroll:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_overlay',
			[
				'label' => __( 'Overlay', 'athemes-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_overlay' => 'yes',
				],
			]
		);

		$this->add_control(
			'overlay_color',
			[
				'label' => __( 'Overlay Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => 'rgba(0,0,0,0.5)',
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-image-scroll .image-scroll-overlay' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'overlay_text_color',
			[
				'label' => __( 'Text Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-image-scroll .overlay-text' => 'color: {{VALUE}};',
				],
				'condition' => [
					'overlay_element' => 'text',
				],
			]
		);

		$this->add_control(
			'overlay_icon_color',
			[
				'label' => __( 'Icon Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-image-scroll .overlay-icon svg' => 'fill: {{VALUE}};',
				],
				'condition' => [
					'overlay_element' => 'icon',
				],
			]
		);

		$this->add_responsive_control(
			'overlay_text_size',
			[
				'label' => __( 'Text Size', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 500,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 40,
				],
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-image-scroll .overlay-text' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'overlay_element' => 'text',
				],
			]
		);

		$this->add_responsive_control(
			'overlay_size',
			[
				'label' => __( 'Icon Size', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 500,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 40,
				],
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-image-scroll .overlay-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'overlay_element' => 'icon',
				],
			]
		);

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

		$this->add_render_attribute( 'wrapper', 'class', 'athemes-addons-image-scroll' );
		$this->add_render_attribute( 'wrapper', 'data-direction', $settings['direction'] );
		$this->add_render_attribute( 'wrapper', 'data-trigger-type', $settings['trigger_type'] );

		if ( ! empty( $settings['link']['url'] ) ) {
			$this->add_render_attribute( 'link', 'href', esc_url( $settings['link']['url'] ) );
			$this->add_render_attribute( 'link', 'target', $settings['link']['is_external'] ? '_blank' : '_self' );
		}

		?>

		<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
			<?php if ( ! empty( $settings['link']['url'] ) ) : ?>
				<a <?php $this->print_render_attribute_string( 'link' ); ?>>
			<?php endif; ?>
				<div class="image-scroll-inner">
					<?php Group_Control_Image_Size::print_attachment_image_html( $settings, 'thumb', 'image' ); ?>
					<?php if ( 'yes' === $settings['show_overlay'] ) : ?>
						<div class="image-scroll-overlay">
							<?php if ( 'text' === $settings['overlay_element'] ) : ?>
								<div class="overlay-text"><?php echo esc_html( $settings['overlay_text'] ); ?></div>
							<?php elseif ( 'icon' === $settings['overlay_element'] ) : ?>
								<div class="overlay-icon">
								<?php aThemes_Addons_SVG_Icons::get_svg_icon( 'icon-vertical-scroll' ); ?>
								</div>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</div>
			<?php if ( ! empty( $settings['link']['url'] ) ) : ?>
				</a>
			<?php endif; ?>
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
Plugin::instance()->widgets_manager->register( new Image_Scroll() );