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

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor countdown widget.
 *
 * @since 1.0.0
 */
class Countdown extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve tabs widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'athemes-addons-countdown';
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
		return __( 'Countdown', 'athemes-addons-elementor' );
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
	 * Get widget icon.
	 *
	 * Retrieve tabs widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-countdown aafe-elementor-icon';
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
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'count', 'countdown', 'timer', 'athemes', 'addons', 'athemes addons' ];
	}

	/**
	 * Register widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 3.1.0
	 * @access protected
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_timer',
			[
				'label' => esc_html__( 'Timer', 'athemes-addons-elementor' ),
			]
		);

		$this->add_control(
			'type',
			[
				'label' => esc_html__( 'Type', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'fixed',
				'options' => [
					'fixed'     => esc_html__( 'Fixed', 'athemes-addons-elementor' ),
					'evergreen' => esc_html__( 'Evergreen', 'athemes-addons-elementor' ),
				],
			]
		);

		$this->add_control(
			'end_date',
			[
				'label' => esc_html__( 'End Date', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DATE_TIME,
				'default' => gmdate( 'Y-m-d H:i:s', strtotime( '+1 week' ) ),
				'condition' => [
					'type' => 'fixed',
				],
			]
		);

		$this->add_control(
			'hours',
			[
				'label' => esc_html__( 'Hours', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 3,
				'condition' => [
					'type' => 'evergreen',
				],
			]
		);

		$this->add_control(
			'minutes',
			[
				'label' => esc_html__( 'Minutes', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 15,
				'condition' => [
					'type' => 'evergreen',
				],
			]
		);

		$this->add_control(
			'reset',
			[
				'label' => esc_html__( 'Reset', 'athemes-addons-elementor' ),
				'description' => esc_html__( 'Allow the countdown to reset when it expires.', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'athemes-addons-elementor' ),
				'label_off' => esc_html__( 'No', 'athemes-addons-elementor' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'condition' => [
					'type' => 'evergreen',
				],
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
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'athemes-addons-elementor' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'athemes-addons-elementor' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'center',
				'prefix_class' => 'aafe%s-flex-align-',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'separator',
			[
				'label' => __( 'Number separator', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'athemes-addons-elementor' ),
				'label_off' => __( 'Hide', 'athemes-addons-elementor' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Content', 'athemes-addons-elementor' ),
			]
		);

		$this->start_controls_tabs( 'labels' );

		$this->start_controls_tab(
			'labels_singular',
			[
				'label' => __( 'Singular', 'athemes-addons-elementor' ),
			]
		);

		$this->add_control(
			'label_days_singular',
			[
				'label' => __( 'Days', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Day', 'athemes-addons-elementor' ),
			]
		);

		$this->add_control(
			'label_hours_singular',
			[
				'label' => __( 'Hours', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Hour', 'athemes-addons-elementor' ),
			]
		);

		$this->add_control(
			'label_minutes_singular',
			[
				'label' => __( 'Minutes', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Minute', 'athemes-addons-elementor' ),
			]
		);

		$this->add_control(
			'label_seconds_singular',
			[
				'label' => __( 'Seconds', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Second', 'athemes-addons-elementor' ),
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'labels_plural',
			[
				'label' => __( 'Plural', 'athemes-addons-elementor' ),
			]
		);

		$this->add_control(
			'label_days_plural',
			[
				'label' => __( 'Days', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Days', 'athemes-addons-elementor' ),
			]
		);

		$this->add_control(
			'label_hours_plural',
			[
				'label' => __( 'Hours', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Hours', 'athemes-addons-elementor' ),
			]
		);

		$this->add_control(
			'label_minutes_plural',
			[
				'label' => __( 'Minutes', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Minutes', 'athemes-addons-elementor' ),
			]
		);

		$this->add_control(
			'label_seconds_plural',
			[
				'label' => __( 'Seconds', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Seconds', 'athemes-addons-elementor' ),
			]
		);
	
		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_expire_action',
			[
				'label' => esc_html__( 'Expire Action', 'athemes-addons-elementor' ),
			]
		);

		$this->add_control(
			'expire_action',
			[
				'label' => esc_html__( 'Expire Action', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'nothing',
				'options' => [
					'nothing'   => esc_html__( 'Nothing', 'athemes-addons-elementor' ),
					'text'      => esc_html__( 'Show text', 'athemes-addons-elementor' ),
					'template'  => esc_html__( 'Show Template', 'athemes-addons-elementor' ),
					'url'       => esc_html__( 'Redirect to URL', 'athemes-addons-elementor' ),
				],
			]
		);

		$this->add_control(
			'expire_text',
			[
				'label' => esc_html__( 'Expire Text', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::WYSIWYG,
				'default' => esc_html__( 'The countdown is finished!', 'athemes-addons-elementor' ),
				'condition' => [
					'expire_action' => 'text',
				],
			]
		);

		$this->add_control(
			'expire_template',
			[
				'label' => esc_html__( 'Expire Template', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SELECT2,
				'options' => $this->get_available_templates(),
				'condition' => [
					'expire_action' => 'template',
				],
			]
		);

		$this->add_control(
			'expire_url',
			[
				'label' => esc_html__( 'Expire URL', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-link.com', 'athemes-addons-elementor' ),
				'default' => [
					'url' => '',
				],
				'show_external' => true,
				'condition' => [
					'expire_action' => 'url',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_wrapper_style',
			[
				'label' => esc_html__( 'Wrapper', 'athemes-addons-elementor' ),
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
					'{{WRAPPER}} .countdown-timer' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],              
			]
		);

		$this->add_control(
			'wrapper_background_color',
			[
				'label' => esc_html__( 'Background Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .countdown-timer' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'wrapper_border',
				'selector' => '{{WRAPPER}} .countdown-timer',
			]
		);

		$this->add_responsive_control(
			'wrapper_border_radius',
			[
				'label' => __( 'Border Radius', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .countdown-timer' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],              
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'wrapper_box_shadow',
				'selector' => '{{WRAPPER}} .countdown-timer',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_element_style',
			[
				'label' => esc_html__( 'Elements', 'athemes-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'element_size',
			[
				'label' => __( 'Size', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'%' => [
						'min' => 10,
						'max' => 150,
					],
					'px' => [
						'min' => 10,
						'max' => 500,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 70,
				],
				'selectors' => [
					'{{WRAPPER}} .countdown-element' => '--countdown-element-size: {{SIZE}}{{UNIT}};',
				],              
			]
		);

		$this->add_responsive_control(
			'element_gap',
			[
				'label' => __( 'Gap', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 30,
				],
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-countdown .countdown-timer' => '--countdown-element-gap: {{SIZE}}{{UNIT}};',
				],              
			]
		);

		$this->add_control(
			'element_background_color',
			[
				'label' => esc_html__( 'Background Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .countdown-element' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'element_border',
				'selector' => '{{WRAPPER}} .countdown-element',
			]
		);

		$this->add_responsive_control(
			'element_border_radius',
			[
				'label' => __( 'Border Radius', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .countdown-element' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],              
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'element_box_shadow',
				'selector' => '{{WRAPPER}} .countdown-element',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_number_style',
			[
				'label' => esc_html__( 'Numbers', 'athemes-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'number_padding',
			[
				'label' => __( 'Padding', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .timer-element' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],              
			]
		);

		$this->add_responsive_control(
			'number_margin',
			[
				'label' => __( 'Margin', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'em', 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .timer-element' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],              
			]
		);

		$this->add_responsive_control(
			'number_border_radius',
			[
				'label' => __( 'Border Radius', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .timer-element' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],              
			]
		);

		$this->add_control(
			'number_color',
			[
				'label' => esc_html__( 'Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .timer-element' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'number_background_color',
			[
				'label' => esc_html__( 'Background Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .timer-element' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'number_typography',
				'selector' => '{{WRAPPER}} .timer-element',
			]
		);

		$this->end_controls_section();      

		$this->start_controls_section(
			'section_label_style',
			[
				'label' => esc_html__( 'Labels', 'athemes-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'label_padding',
			[
				'label' => __( 'Padding', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .label-element' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],              
			]
		);

		$this->add_responsive_control(
			'label_border_radius',
			[
				'label' => __( 'Border Radius', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .label-element' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],              
			]
		);

		$this->add_control(
			'label_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .label-element' => 'background-color: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'label_color',
			[
				'label' => esc_html__( 'Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .label-element' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'label_typography',
				'selector' => '{{WRAPPER}} .label-element',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_expired_style',
			[
				'label' => esc_html__( 'Expired Content', 'athemes-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'expire_action' => [ 'text' ],
				],
			]
		);

		$this->add_responsive_control(
			'expired_content_padding',
			[
				'label' => __( 'Padding', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [ 'top' => 20, 'right' => 20, 'bottom' => 20, 'left' => 20 ],
				'selectors' => [
					'{{WRAPPER}} .countdown-expired-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],              
			]
		);

		$this->add_control(
			'expired_text_color',
			[
				'label' => esc_html__( 'Text Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .countdown-expired-content' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'expired_text_background_color',
			[
				'label' => esc_html__( 'Background Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .countdown-expired-content' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_separator_style',
			[
				'label' => esc_html__( 'Separator', 'athemes-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'separator' => 'yes',
				],
			]
		);

		$this->add_control(
			'separator_color',
			[
				'label' => esc_html__( 'Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} .countdown-separator' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'separator_size',
			[
				'label' => __( 'Size', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'%' => [
						'min' => 10,
						'max' => 150,
					],
					'px' => [
						'min' => 10,
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .countdown-separator' => 'font-size: {{SIZE}}{{UNIT}};',
				],              
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render tabs widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'athemes-addons-countdown', 'class', 'athemes-addons-countdown' );

		$this->add_render_attribute( 'countdown-timer', 'class', 'countdown-timer' );

		$this->add_render_attribute( 'countdown-timer', 'id', 'countdown-timer-' . $this->get_id() );

		$this->add_render_attribute( 'countdown-timer', 'data-type', esc_attr( $settings['type'] ) );

		if ( 'fixed' === $settings['type'] ) {
			$this->add_render_attribute( 'countdown-timer', 'data-end-date', esc_attr( $settings['end_date'] ) );
		} else {
			$this->add_render_attribute( 'countdown-timer', 'data-hours', esc_attr( $settings['hours'] ) );
			$this->add_render_attribute( 'countdown-timer', 'data-minutes', esc_attr( $settings['minutes'] ) );
			$this->add_render_attribute( 'countdown-timer', 'data-reset', esc_attr( $settings['reset'] ) );
		}

		$this->add_render_attribute( 'countdown-timer', 'data-label-days-singular', esc_attr( $settings['label_days_singular'] ) );
		$this->add_render_attribute( 'countdown-timer', 'data-label-hours-singular', esc_attr( $settings['label_hours_singular'] ) );
		$this->add_render_attribute( 'countdown-timer', 'data-label-minutes-singular', esc_attr( $settings['label_minutes_singular'] ) );
		$this->add_render_attribute( 'countdown-timer', 'data-label-seconds-singular', esc_attr( $settings['label_seconds_singular'] ) );

		$this->add_render_attribute( 'countdown-timer', 'data-label-days-plural', esc_attr( $settings['label_days_plural'] ) );
		$this->add_render_attribute( 'countdown-timer', 'data-label-hours-plural', esc_attr( $settings['label_hours_plural'] ) );
		$this->add_render_attribute( 'countdown-timer', 'data-label-minutes-plural', esc_attr( $settings['label_minutes_plural'] ) );
		$this->add_render_attribute( 'countdown-timer', 'data-label-seconds-plural', esc_attr( $settings['label_seconds_plural'] ) );
		
		$this->add_render_attribute( 'countdown-timer', 'data-separator', esc_attr( $settings['separator'] ) );

		$this->add_render_attribute( 'countdown-timer', 'data-expire-action', esc_attr( $settings['expire_action'] ) );
		if ( 'url' === $settings['expire_action'] ) {
			$this->add_render_attribute( 'countdown-timer', 'data-redirect-url', esc_url( $settings['expire_url']['url'] ) );
		}

		?>
		<div <?php $this->print_render_attribute_string( 'athemes-addons-countdown' ); ?>>

			<div <?php $this->print_render_attribute_string( 'countdown-timer' ); ?>></div>

			<?php if ( 'text' === $settings['expire_action'] ) : ?>
				<div class="countdown-expired-content"><?php echo wp_kses_post( $settings['expire_text'] ); ?></div>
			<?php elseif ( 'template' === $settings['expire_action'] ) : ?>
				<div class="countdown-expired-content"><?php echo Plugin::instance()->frontend->get_builder_content_for_display( $settings['expire_template'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
			<?php endif; ?>

		</div>
		<?php
	}

	/**
	 * Render tabs widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 2.9.0
	 * @access protected
	 */
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
Plugin::instance()->widgets_manager->register( new Countdown() );