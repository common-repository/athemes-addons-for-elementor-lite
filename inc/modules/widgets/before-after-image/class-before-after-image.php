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

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Before After Image Widget.
 *
 * @since 1.0.0
 */
class Before_After_Image extends Widget_Base {

	public function __construct($data = [], $args = null) {
		parent::__construct($data, $args);

		wp_register_script( 'athemes-addons-eventmove-js', ATHEMES_AFE_URI . 'assets/js/vendor/event-move.min.js', array( 'elementor-frontend' ), ATHEMES_AFE_VERSION, true );
		wp_register_script( 'athemes-addons-twentytwenty-js', ATHEMES_AFE_URI . 'assets/js/vendor/twentytwenty.min.js', array( 'elementor-frontend' ), ATHEMES_AFE_VERSION, true );
	}

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
		return 'athemes-addons-before-after-image';
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
		return __( 'Before/After Image', 'athemes-addons-elementor' );
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
		return 'eicon-image-before-after aafe-elementor-icon';
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
		return [ 'athemes-addons-eventmove-js', 'athemes-addons-twentytwenty-js', $this->get_name() . '-scripts' ];
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
		return [ 'before', 'after', 'image', 'compare', 'comparison', 'athemes', 'addons', 'athemes addons' ];
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
			'section_images',
			[
				'label' => __( 'Image', 'athemes-addons-elementor' ),
			]
		);

		$this->add_control(
			'image_before',
			[
				'label' => esc_html__( 'Image Before', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$this->add_control(
			'image_before_label',
			[
				'label' => __( 'Label', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'show_label' => true,
				'default' => __( 'Before', 'athemes-addons-elementor' ),
			]
		);

		$this->add_control(
			'image_after',
			[
				'label' => esc_html__( 'Image After', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'image_after_label',
			[
				'label' => __( 'Label', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'show_label' => true,
				'default' => __( 'After', 'athemes-addons-elementor' ),
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
			'orientation',
			[
				'label' => __( 'Orientation', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'horizontal',
				'options' => [
					'horizontal'    => __( 'Horizontal', 'athemes-addons-elementor' ),
					'vertical'      => __( 'Vertical', 'athemes-addons-elementor' ),
				],
			]
		);

		$this->add_control(
			'offset',
			[
				'label' => __( 'Offset', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%' ],        
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],                  
				],
				'default' => [
					'unit' => '%',
					'size' => 50,
				],      
			]
		);

		$this->add_control(
			'no_overlay',
			[
				'label' => __( 'Hide overlay', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'athemes-addons-elementor' ),
				'label_off' => __( 'No', 'athemes-addons-elementor' ),
				'return_value' => true,
				'default' => 'no',
			]
		);

		$this->add_control(
			'move_on_hover',
			[
				'label' => __( 'Move on hover', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'athemes-addons-elementor' ),
				'label_off' => __( 'No', 'athemes-addons-elementor' ),
				'return_value' => true,
				'default' => 'no',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'click_to_move',
			[
				'label' => __( 'Click to move', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'athemes-addons-elementor' ),
				'label_off' => __( 'No', 'athemes-addons-elementor' ),
				'return_value' => true,
				'default' => 'no',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_image',
			[
				'label' => __( 'Image', 'athemes-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);      

		$this->add_responsive_control(
			'image_padding',
			[
				'label' => __( 'Padding', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],      
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-before-after-image' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],              
			]
		);

		$this->add_control(
			'image_border_radius',
			[
				'label' => __( 'Border radius', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],      
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-before-after-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],              
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'card_border',
				'selector' => '{{WRAPPER}} .athemes-addons-before-after-image',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'image_box_shadow',
				'selector' => '{{WRAPPER}} .athemes-addons-before-after-image',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_overlay',
			[
				'label' => __( 'Overlay', 'athemes-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'overlay_color',
			[
				'label' => __( 'Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-before-after-image .twentytwenty-overlay:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_label',
			[
				'label' => __( 'Labels', 'athemes-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'label_color',
			[
				'label' => __( 'Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-before-after-image .twentytwenty-before-label::before, {{WRAPPER}} .athemes-addons-before-after-image .twentytwenty-after-label::before' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'label_background_color',
			[
				'label' => __( 'Background color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-before-after-image .twentytwenty-before-label::before, {{WRAPPER}} .athemes-addons-before-after-image .twentytwenty-after-label::before' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'label_border_radius',
			[
				'label' => __( 'Border radius', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],      
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-before-after-image .twentytwenty-before-label::before, {{WRAPPER}} .athemes-addons-before-after-image .twentytwenty-after-label::before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],              
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'label_typography',
				'selector'  => '{{WRAPPER}} .athemes-addons-before-after-image .twentytwenty-before-label::before, {{WRAPPER}} .athemes-addons-before-after-image .twentytwenty-after-label::before',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_handle',
			[
				'label' => __( 'Handle', 'athemes-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'handle_bg_color',
			[
				'label' => __( 'Background color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-before-after-image .twentytwenty-handle' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'handle_color',
			[
				'label' => __( 'Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-before-after-image .twentytwenty-left-arrow' => 'border-right-color: {{VALUE}};',
					'{{WRAPPER}} .athemes-addons-before-after-image .twentytwenty-right-arrow' => 'border-left-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'handle_border_radius',
			[
				'label' => __( 'Border radius', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],      
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-before-after-image .twentytwenty-handle' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],              
			]
		);

		$this->add_responsive_control(
			'handle_width',
			[
				'label' => __( 'Width', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],      
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],      
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],          
				],      
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-before-after-image .twentytwenty-handle' => 'width: {{SIZE}}{{UNIT}};',
					
				],              
			]
		);

		$this->add_responsive_control(
			'handle_height',
			[
				'label' => __( 'Height', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],      
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],  
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],              
				],  
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-before-after-image .twentytwenty-handle' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .twentytwenty-horizontal .twentytwenty-handle:before' => 'margin-bottom: calc({{SIZE}}{{UNIT}} / 2);',
					'{{WRAPPER}} .twentytwenty-horizontal .twentytwenty-handle:after' => 'margin-top: calc( {{SIZE}}{{UNIT}} / 2);',
				],              
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'handle_border',
				'selector' => '{{WRAPPER}} .athemes-addons-before-after-image .twentytwenty-handle',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'handle_box_shadow',
				'selector' => '{{WRAPPER}} .athemes-addons-before-after-image .twentytwenty-handle',
			]
		);

		//divider color
		$this->add_control(
			'divider_color',
			[
				'label' => __( 'Divider color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-before-after-image .twentytwenty-handle::after, {{WRAPPER}} .athemes-addons-before-after-image .twentytwenty-handle::before' => 'background-color: {{VALUE}};',
				],
				'separator' => 'before',
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

		$this->add_render_attribute( 'wrapper', 'class', 'athemes-addons-before-after-image orientation-' . esc_attr( $settings['orientation'] ) );

		$this->add_render_attribute( 'wrapper', 'data-orientation', esc_attr( $settings['orientation'] ) );
		$this->add_render_attribute( 'wrapper', 'data-offset', esc_attr( $settings['offset']['size'] / 100 ) );
		$this->add_render_attribute( 'wrapper', 'data-overlay', esc_attr( $settings['no_overlay'] ) );
		$this->add_render_attribute( 'wrapper', 'data-move-on-hover', esc_attr( $settings['move_on_hover'] ) );
		$this->add_render_attribute( 'wrapper', 'data-click-to-move', esc_attr( $settings['click_to_move'] ) );
		$this->add_render_attribute( 'wrapper', 'data-before-label', esc_attr( $settings['image_before_label'] ) );
		$this->add_render_attribute( 'wrapper', 'data-after-label', esc_attr( $settings['image_after_label'] ) );
		?>

		<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
			<div class="before-image">
				<?php Group_Control_Image_Size::print_attachment_image_html( $settings,  'image_before' ); ?>
			</div>
			<div class="after-image">
				<?php Group_Control_Image_Size::print_attachment_image_html( $settings, 'image_after' ); ?>>
			</div>
		</div>

		<?php

		if ( Plugin::$instance->editor->is_edit_mode() ) {
			?>
			<script type="text/javascript">
				jQuery(document).ready(function($) {
					$('.athemes-addons-before-after-image').twentytwenty({
						default_offset_pct: <?php echo esc_attr( $settings['offset']['size'] / 100 ); ?>,
						orientation: '<?php echo esc_attr( $settings['orientation'] ); ?>',
						move_on_hover: <?php echo isset( $settings['move_on_hover'] ) ? esc_attr( $settings['move_on_hover'] ) : false; ?>,
						click_to_move: <?php echo isset( $settings['click_to_move'] ) ? esc_attr( $settings['click_to_move'] ) : true; ?>,
						no_overlay: <?php echo isset( $settings['no_overlay'] ) ? esc_attr( $settings['no_overlay'] ) : false; ?>,
						before_label: '<?php echo esc_attr( $settings['image_before_label'] ); ?>',
						after_label: '<?php echo esc_attr( $settings['image_after_label'] ); ?>',
					});
				});
			</script>
			<?php
		}
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
Plugin::instance()->widgets_manager->register( new Before_After_Image() );