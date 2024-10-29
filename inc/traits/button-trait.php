<?php
namespace aThemes_Addons\Traits;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

trait Button_Trait {

	/**
	 * Register button controls.
	 */
	protected function register_button_content_controls( $args = [] ) {
		$default_args = [
			'class' => '',
			'section_condition' => [],
			'button_default_text' => esc_html__( 'Click here', 'athemes-addons-elementor' ),
			'text_control_label' => esc_html__( 'Text', 'athemes-addons-elementor' ),
			'alignment_control_prefix_class' => 'elementor%s-align-',
			'alignment_default' => '',
			'icon_exclude_inline_options' => [],
		];
	
		$args = wp_parse_args( $args, $default_args );

		$prefix = '';

		if ( !empty( $args['class'] ) ) {
			$prefix = $args['class'] . '_';
			$args['class'] = '.' . $args['class'];
		}
	
		$this->add_control(
			$prefix . 'text',
			[
				'label' => $args['text_control_label'],
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => $args['button_default_text'],
				'placeholder' => $args['button_default_text'],
				'condition' => $args['section_condition'],
			]
		);
	
		$this->add_control(
			$prefix . 'link',
			[
				'label' => esc_html__( 'Link', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'default' => [
					'url' => '#',
				],
				'condition' => $args['section_condition'],
			]
		);
	
		$this->add_responsive_control(
			$prefix . 'align',
			[
				'label' => esc_html__( 'Alignment', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left'    => [
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
					'justify' => [
						'title' => esc_html__( 'Justified', 'athemes-addons-elementor' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'prefix_class' => $args['alignment_control_prefix_class'],
				'default' => $args['alignment_default'],
				'condition' => $args['section_condition'],
			]
		);
	
		$this->add_control(
			$prefix . 'selected_icon',
			[
				'label' => esc_html__( 'Icon', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'skin' => 'inline',
				'label_block' => false,
				'condition' => $args['section_condition'],
				'icon_exclude_inline_options' => $args['icon_exclude_inline_options'],
			]
		);
	
		$this->add_control(
			$prefix . 'icon_align',
			[
				'label' => esc_html__( 'Icon Position', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => [
					'left' => esc_html__( 'Before', 'athemes-addons-elementor' ),
					'right' => esc_html__( 'After', 'athemes-addons-elementor' ),
				],
				'condition' => array_merge( $args['section_condition'], [ $prefix . 'selected_icon[value]!' => '' ] ),
			]
		);
	
		$this->add_control(
			$prefix . 'icon_indent',
			[
				'label' => esc_html__( 'Icon Spacing', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} ' . $args['class'] . '.button .elementor-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} ' . $args['class'] . '.button .elementor-align-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
				'condition' => $args['section_condition'],
			]
		);
	
		$this->add_control(
			$prefix . 'view',
			[
				'label' => esc_html__( 'View', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::HIDDEN,
				'default' => 'traditional',
				'condition' => $args['section_condition'],
			]
		);
	
		$this->add_control(
			$prefix . 'button_css_id',
			[
				'label' => esc_html__( 'Button ID', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'ai' => [
					'active' => false,
				],
				'default' => '',
				'title' => esc_html__( 'Add your custom id WITHOUT the Pound key. e.g: my-id', 'athemes-addons-elementor' ),
				'description' => sprintf(
					/* translators: %1$s: opening code tag, %2$s: closing code tag */
					esc_html__( 'Please make sure the ID is unique and not used elsewhere on the page this form is displayed. This field allows %1$sA-z 0-9%2$s & underscore chars without spaces.', 'athemes-addons-elementor' ),
					'<code>',
					'</code>'
				),
				'separator' => 'before',
				'condition' => $args['section_condition'],
			]
		);
	}   

	protected function register_button_style_controls( $args = [] ) {
		$default_args = [
			'class' => '',
			'default_class' => '.button',
			'background_color' => '',
			'section_condition' => [],
		];

		$prefix = '';
	
		$args = wp_parse_args( $args, $default_args );

		if ( !empty( $args['class'] ) ) {
			$prefix = str_replace( ' ', '_', $args['class'] ) . '_';
			$args['class'] = '.' . $args['class'];
		}   
	
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => $prefix . 'typography',
				'selector' => '{{WRAPPER}} ' . $args['class'] . $args['default_class'],
				'condition' => $args['section_condition'],
			]
		);
		
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => $prefix . 'text_shadow',
				'selector' => '{{WRAPPER}} ' .$args['class'] . $args['default_class'],
				'condition' => $args['section_condition'],
			]
		);
		
		$this->start_controls_tabs( $prefix . 'tabs_button_style', [
			'condition' => $args['section_condition'],
		] );
		
		$this->start_controls_tab(
			$prefix . 'tab_button_normal',
			[
				'label' => esc_html__( 'Normal', 'athemes-addons-elementor' ),
				'condition' => $args['section_condition'],
			]
		);
		
		$this->add_control(
			$prefix . 'button_text_color',
			[
				'label' => esc_html__( 'Text Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} ' .$args['class'] . $args['default_class'] => 'fill: {{VALUE}}; color: {{VALUE}};',
				],
				'condition' => $args['section_condition'],
			]
		);
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => $prefix . 'background',
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} ' .$args['class'] . $args['default_class'],
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
					'color' => [
						'default' => $args['background_color'],
					],
				],
				'condition' => $args['section_condition'],
			]
		);
		
		$this->end_controls_tab();
		
		$this->start_controls_tab(
			$prefix . 'tab_button_hover',
			[
				'label' => esc_html__( 'Hover', 'athemes-addons-elementor' ),
				'condition' => $args['section_condition'],
			]
		);
		
		$this->add_control(
			$prefix . 'hover_color',
			[
				'label' => esc_html__( 'Text Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} ' . $args['class'] . '.button:hover, {{WRAPPER}} ' . $args['class'] . '.button:focus' => 'color: {{VALUE}};',
					'{{WRAPPER}} ' . $args['class'] . '.button:hover svg, {{WRAPPER}} ' . $args['class'] . '.button:focus svg' => 'fill: {{VALUE}};',
				],
				'condition' => $args['section_condition'],
			]
		);
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => $prefix . 'button_background_hover',
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} ' . $args['class'] . '.button:hover, {{WRAPPER}} ' . $args['class'] . '.button:focus',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
				],
				'condition' => $args['section_condition'],
			]
		);
		
		$this->add_control(
			$prefix . 'button_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} ' . $args['class'] . '.button:hover, {{WRAPPER}} ' . $args['class'] . '.button:focus' => 'border-color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			$prefix . 'hover_animation',
			[
				'label' => esc_html__( 'Hover Animation', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
				'condition' => $args['section_condition'],
			]
		);
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => $prefix . 'border',
				'selector' => '{{WRAPPER}} ' .$args['class'] . $args['default_class'],
				'separator' => 'before',
				'condition' => $args['section_condition'],
			]
		);
		
		$this->add_responsive_control(
			$prefix . 'border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} ' .$args['class'] . $args['default_class'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => $args['section_condition'],
			]
		);
		
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => $prefix . 'button_box_shadow',
				'selector' => '{{WRAPPER}} ' .$args['class'] . $args['default_class'],
				'condition' => $args['section_condition'],
			]
		);
		
		$this->add_responsive_control(
			$prefix . 'text_padding',
			[
				'label' => esc_html__( 'Padding', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} ' .$args['class'] . $args['default_class'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
				'condition' => $args['section_condition'],
			]
		);
	}   

	/**
	 * Render button widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @param \Elementor\Widget_Base|null $instance
	 *
	 * @access protected
	 */
	protected function render_button( Widget_Base $instance = null, $selector = '' ) {
		if ( empty( $instance ) ) {
			$instance = $this;
		}

		if ( !empty( $selector ) ) {
			$prefix = $selector . '_';
		} else {
			$prefix = '';
		}
	
		$settings = $instance->get_settings_for_display();
	
		$instance->add_render_attribute( $prefix . 'wrapper', 'class', 'elementor-button-wrapper' );
	
		$instance->add_render_attribute( $prefix . 'button', 'class', 'button aafe-button' );

		$instance->add_render_attribute( $prefix . 'button', 'class', $selector );
	
		if ( ! empty( $settings[$prefix . 'link']['url'] ) ) {
			$instance->add_link_attributes( $prefix . 'button', $settings[$prefix . 'link'] );
			$instance->add_render_attribute( $prefix . 'button', 'class', 'elementor-button-link' );
		} else {
			$instance->add_render_attribute( $prefix . 'button', 'role', 'button' );
		}
	
		if ( ! empty( $settings[$prefix . 'button_css_id'] ) ) {
			$instance->add_render_attribute( $prefix . 'button', 'id', $settings[$prefix . 'button_css_id'] );
		}
	
		if ( ! empty( $settings[$prefix . 'hover_animation'] ) ) {
			$instance->add_render_attribute( $prefix . 'button', 'class', 'elementor-animation-' . $settings[$prefix . 'hover_animation'] );
		}
		?>
		<div <?php $instance->print_render_attribute_string( $prefix . 'wrapper' ); ?>>
			<a <?php $instance->print_render_attribute_string( $prefix . 'button' ); ?>>
				<?php $this->render_text( $instance, $selector ); ?>
			</a>
		</div>
		<?php
	}   


	/**
	 * Render button text.
	 *
	 * Render button widget text.
	 *
	 * @param \Elementor\Widget_Base|null $instance
	 *
	 * @since  3.4.0
	 * @access protected
	 */
	protected function render_text( Widget_Base $instance = null, $selector = '' ) {
		// The default instance should be `$this` (a Button widget), unless the Trait is being used from outside of a widget (e.g. `Skin_Base`) which should pass an `$instance`.
		if ( empty( $instance ) ) {
			$instance = $this;
		}

		if ( !empty( $selector ) ) {
			$selector = $selector . '_';
		}
	
		$settings = $instance->get_settings_for_display();
	
		$migrated = isset( $settings['__fa4_migrated'][$selector . 'selected_icon'] );
		$is_new = empty( $settings[$selector . 'icon'] ) && Icons_Manager::is_migration_allowed();
	
		if ( ! $is_new && empty( $settings[$selector . 'icon_align'] ) ) {
			// @todo: remove when deprecated
			// added as bc in 2.6
			//old default
			$settings[$selector . 'icon_align'] = $instance->get_settings( $selector . 'icon_align' );
		}
	
		$instance->add_render_attribute( [
			$selector . 'content-wrapper' => [
				'class' => 'button-content-wrapper',
			],
			$selector . 'icon-align' => [
				'class' => [
					'elementor-button-icon',
					'elementor-align-icon-' . $settings[$selector . 'icon_align'],
				],
			],
			$selector . 'text' => [
				'class' => 'elementor-button-text',
			],
		] );
	
		// TODO: replace the protected with public
		//$instance->add_inline_editing_attributes( 'text', 'none' );
		?>
		<?php if ( ! empty( $settings[$selector . 'icon'] ) || ! empty( $settings[$selector . 'selected_icon']['value'] ) ) : ?>
		<span <?php $instance->print_render_attribute_string( $selector . 'icon-align' ); ?>>
			<?php if ( $is_new || $migrated ) :
				Icons_Manager::render_icon( $settings[$selector . 'selected_icon'], [ 'aria-hidden' => 'true' ] );
			else : ?>
				<i class="<?php echo esc_attr( $settings[$selector . 'icon'] ); ?>" aria-hidden="true"></i>
			<?php endif; ?>
		</span>
		<?php endif; ?>
		<span <?php $instance->print_render_attribute_string( $selector . 'text' ); ?>><?php $instance->print_unescaped_setting( $selector . 'text' ); ?></span>
		<?php
	}   

	public function on_import( $element ) {
		return Icons_Manager::on_import_migration( $element, 'icon', $selector . 'selected_icon' );
	}
}