<?php
namespace aThemes_Addons\Widgets;

use Elementor\Plugin;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Group_Control_Image_Size;
use Elementor\Repeater;
use Elementor\Icons_Manager;
use Elementor\Control_Media;

/**
 * Animated heading widget.
 *
 * @since 1.0.0
 */
class Business_Hours extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'athemes-addons-business-hours';
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
		return __( 'Business hours', 'athemes-addons-elementor' );
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
		return 'eicon-clock-o aafe-elementor-icon';
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
		return [ 'business', 'hours', 'open', 'athemes', 'addons', 'athemes addons' ];
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
			'section_business_hours',
			[
				'label' => __( 'Business hours', 'athemes-addons-elementor' ),
			]
		);  

		$repeater = new Repeater();

		$repeater->add_control(
			'day',
			[
				'label' => __( 'Day', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Monday', 'athemes-addons-elementor' ),
				'show_label' => true,
			]
		);  

		$repeater->add_control(
			'hours',
			[
				'label' => __( 'Hours', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( '9:00 - 17:00', 'athemes-addons-elementor' ),
				'show_label' => true,
			]
		);

		$repeater->add_control(
			'highlight_day',
			[
				'label' => __( 'Highlight this day', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'show_label' => true,
			]
		);

		$this->add_control(
			'business_hours',
			[
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'day' => __( 'Monday', 'athemes-addons-elementor' ),
						'hours' => __( '9:00 - 17:00', 'athemes-addons-elementor' ),
					],
					[
						'day' => __( 'Tuesday', 'athemes-addons-elementor' ),
						'hours' => __( '9:00 - 17:00', 'athemes-addons-elementor' ),
					],
					[
						'day' => __( 'Wednesday', 'athemes-addons-elementor' ),
						'hours' => __( '9:00 - 17:00', 'athemes-addons-elementor' ),
					],
					[
						'day' => __( 'Thursday', 'athemes-addons-elementor' ),
						'hours' => __( '9:00 - 17:00', 'athemes-addons-elementor' ),
					],
					[
						'day' => __( 'Friday', 'athemes-addons-elementor' ),
						'hours' => __( '9:00 - 17:00', 'athemes-addons-elementor' ),
					],
					[
						'day' => __( 'Saturday', 'athemes-addons-elementor' ),
						'hours' => __( 'Closed', 'athemes-addons-elementor' ),
					],
					[
						'day' => __( 'Sunday', 'athemes-addons-elementor' ),
						'hours' => __( 'Closed', 'athemes-addons-elementor' ),
					],
				],              
				'title_field' => '{{{ day }}}',
			]
		);

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
					'{{WRAPPER}} .business-hours-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .business-hours-inner' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],              
			]
		);

		$this->add_control(
			'wrapper_background_color',
			[
				'label' => __( 'Background Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .business-hours-inner' => 'background-color: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'wrapper_border',
				'label'     => esc_html__( 'Border', 'athemes-addons-elementor' ),
				'selector'  => '{{WRAPPER}} .business-hours-inner',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'wrapper_border_radius',
			[
				'label' => __( 'Border Radius', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,             
				'selectors' => [
					'{{WRAPPER}} .business-hours-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],              
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'wrapper_box_shadow',
				'label' => __( 'Box Shadow', 'athemes-addons-elementor' ),
				'selector' => '{{WRAPPER}} .business-hours-inner',
			]
		);

		//max width
		$this->add_responsive_control(
			'wrapper_max_width',
			[
				'label' => __( 'Max Width', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'max' => 1200,
					],
				],              
				'selectors' => [
					'{{WRAPPER}} .business-hours-inner' => 'max-width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'wrapper_text_align',
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
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-business-hours' => 'justify-content: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_items_style',
			[
				'label' => __( 'Rows', 'athemes-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'items_padding',
			[
				'label' => __( 'Padding', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,             
				'selectors' => [
					'{{WRAPPER}} .aafe-single-day' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],              
			]
		);

		$this->add_responsive_control(
			'items_spacing',
			[
				'label' => __( 'Spacing', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,             
				'selectors' => [
					'{{WRAPPER}} .aafe-single-day:not(:last-of-type)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'items_background_color',
			[
				'label' => __( 'Background Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,              
				'selectors' => [
					'{{WRAPPER}} .aafe-single-day' => 'background-color: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'item_border',
				'label'     => esc_html__( 'Border', 'athemes-addons-elementor' ),
				'selector'  => '{{WRAPPER}} .aafe-single-day',
				'fields_options' => [
					'border' => [
						'default' => 'solid',
					],
					'width' => [
						'default' => [
							'top' => '0',
							'right' => '0',
							'bottom' => '1',
							'left' => '0',
							'isLinked' => false,
						],
					],
					'color' => [
						'default' => '#eee',
					],
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'items_border_radius',
			[
				'label' => __( 'Border Radius', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,             
				'selectors' => [
					'{{WRAPPER}} .aafe-single-day' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],              
			]
		);

		$this->add_control(
			'highlighted_heading',
			[
				'label' => __( 'Highlighted rows', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'highlighted_background_color',
			[
				'label' => __( 'Background Color', 'athemes-addons-elementor' ),
				'description' => __( 'This applies if you have set any day as highlighted.', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,              
				'selectors' => [
					'{{WRAPPER}} .aafe-single-day.highlighted-day' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_day_style',
			[
				'label' => __( 'Day', 'athemes-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'day_color',
			[
				'label' => __( 'Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,              
				'selectors' => [
					'{{WRAPPER}} .aafe-single-day .day' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'day_typography',
				'label'     => esc_html__( 'Typography', 'athemes-addons-elementor' ),
				'selector'  => '{{WRAPPER}} .aafe-single-day .day',
			]
		);

		$this->add_control(
			'highlighted_day_color',
			[
				'label' => __( 'Highlighted Color', 'athemes-addons-elementor' ),
				'description' => __( 'This applies if you have set any day as highlighted.', 'athemes-addons-elementor' ),
				'default' => '#D13E3E',
				'type' => Controls_Manager::COLOR,              
				'selectors' => [
					'{{WRAPPER}} .aafe-single-day.highlighted-day .day' => 'color: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_hours_style',
			[
				'label' => __( 'Hours', 'athemes-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'hours_color',
			[
				'label' => __( 'Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,              
				'selectors' => [
					'{{WRAPPER}} .aafe-single-day .hours' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'hours_typography',
				'label'     => esc_html__( 'Typography', 'athemes-addons-elementor' ),
				'selector'  => '{{WRAPPER}} .aafe-single-day .hours',
			]
		);

		$this->add_control(
			'highlighted_hours_color',
			[
				'label' => __( 'Highlighted Color', 'athemes-addons-elementor' ),
				'description' => __( 'This applies if you have set any day as highlighted.', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,              
				'default' => '#D13E3E',
				'selectors' => [
					'{{WRAPPER}} .aafe-single-day.highlighted-day .hours' => 'color: {{VALUE}};',
				],
				'separator' => 'before',
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
		
		$this->add_render_attribute( 'business-hours', 'class', 'athemes-addons-business-hours' );
		?>

		<div <?php $this->print_render_attribute_string( 'business-hours' ); ?> >
			<div class="business-hours-inner">
				<?php foreach ( $settings['business_hours'] as $item ) : ?>
					<div class="aafe-single-day <?php echo esc_html( ( 'yes' === $item['highlight_day'] ) ? 'highlighted-day' : '' ); ?>">
						<span class="day"><?php echo esc_html( $item['day'] ); ?></span>
						<span class="hours"><?php echo esc_html( $item['hours'] ); ?></span>
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
Plugin::instance()->widgets_manager->register( new Business_Hours() );