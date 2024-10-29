<?php
namespace aThemes_Addons\Widgets;

use Elementor\Plugin;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Background;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor flip box widget.
 *
 * @since 1.0.0
 */
class Advanced_Social extends Widget_Base {

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
		return 'athemes-addons-advanced-social';
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
		return __( 'Advanced Social icons', 'athemes-addons-elementor' );
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
		return 'eicon-social-icons aafe-elementor-icon';
	}

	/**
	 * Enqueue styles.
	 */
	public function get_style_depends() {
		return [ $this->get_name() . '-styles', 'athemes-addons-social-icons' ];
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
		return [ 'social', 'facebook', 'social icons', 'social icon', 'athemes', 'addons', 'athemes addons' ];
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
			'section_social_icon',
			[
				'label' => esc_html__( 'Social Icons', 'athemes-addons-elementor' ),
			]
		);

		$this->add_control(
			'display_mode',
			[
				'label' => esc_html__( 'Display Mode', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'icons_text',
				'options' => [
					'icons'         => esc_html__( 'Icons', 'athemes-addons-elementor' ),
					'text'          => esc_html__( 'Text', 'athemes-addons-elementor' ),
					'icons_text'    => esc_html__( 'Icons & Text', 'athemes-addons-elementor' ),
				],
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'social_icon',
			[
				'label' => esc_html__( 'Icon', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'social',
				'default' => [
					'value' => 'fab fa-wordpress',
					'library' => 'fa-brands',
				],
				'recommended' => [
					'fa-brands' => [
						'android',
						'apple',
						'behance',
						'bitbucket',
						'codepen',
						'delicious',
						'deviantart',
						'digg',
						'dribbble',
						'athemes-addons-elementor',
						'facebook',
						'flickr',
						'foursquare',
						'free-code-camp',
						'github',
						'gitlab',
						'globe',
						'houzz',
						'instagram',
						'jsfiddle',
						'linkedin',
						'medium',
						'meetup',
						'mix',
						'mixcloud',
						'odnoklassniki',
						'pinterest',
						'product-hunt',
						'reddit',
						'shopping-cart',
						'skype',
						'slideshare',
						'snapchat',
						'soundcloud',
						'spotify',
						'stack-overflow',
						'steam',
						'telegram',
						'thumb-tack',
						'tripadvisor',
						'tumblr',
						'twitch',
						'twitter',
						'viber',
						'vimeo',
						'vk',
						'weibo',
						'weixin',
						'whatsapp',
						'wordpress',
						'xing',
						'yelp',
						'youtube',
						'500px',
					],
					'fa-solid' => [
						'envelope',
						'link',
						'rss',
					],
				],
			]
		);

		$repeater->add_control(
			'social_label',
			[
				'label' => esc_html__( 'Label', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Facebook', 'athemes-addons-elementor' ),
			]
		);

		$repeater->add_control(
			'link',
			[
				'label' => esc_html__( 'Link', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::URL,
				'default' => [
					'is_external' => 'true',
				],
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'social_tooltip',
			[
				'label' => esc_html__( 'Tooltip', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'label_on' => esc_html__( 'Yes', 'athemes-addons-elementor' ),
				'label_off' => esc_html__( 'No', 'athemes-addons-elementor' ),
				'return_value' => 'yes',
			]
		);

		$repeater->add_control(
			'social_tooltip_text',
			[
				'label' => esc_html__( 'Tooltip Text', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Follow us', 'athemes-addons-elementor' ),
				'condition' => [
					'social_tooltip' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'item_icon_color',
			[
				'label' => esc_html__( 'Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => esc_html__( 'Official Color', 'athemes-addons-elementor' ),
					'custom' => esc_html__( 'Custom', 'athemes-addons-elementor' ),
				],
			]
		);

		$repeater->add_control(
			'item_icon_primary_color',
			[
				'label' => esc_html__( 'Primary Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'item_icon_color' => 'custom',
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}.aafe-social-icon' => 'background-color: {{VALUE}};',
				],
			]
		);

		$repeater->add_control(
			'item_icon_secondary_color',
			[
				'label' => esc_html__( 'Secondary Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'item_icon_color' => 'custom',
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}.aafe-social-icon i' => 'color: {{VALUE}};',
					'{{WRAPPER}} {{CURRENT_ITEM}}.aafe-social-icon svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'social_icon_list',
			[
				'label' => esc_html__( 'Social Icons', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'social_icon' => [
							'value' => 'fab fa-facebook',
							'library' => 'fa-brands',
						],
						'social_label' => 'Facebook',
					],
					[
						'social_icon' => [
							'value' => 'fab fa-instagram',
							'library' => 'fa-brands',
						],
						'social_label' => 'Instagram',
					],
					[
						'social_icon' => [
							'value' => 'fab fa-youtube',
							'library' => 'fa-brands',
						],
						'social_label' => 'Youtube',
					],
				],
				'title_field' => '<# var migrated = "undefined" !== typeof __fa4_migrated, social = ( "undefined" === typeof social ) ? false : social; #>{{{ elementor.helpers.getSocialNetworkNameFromIcon( social_icon, social, true, migrated, true ) }}}',
			]
		);

		$this->add_responsive_control(
			'columns',
			[
				'label' => esc_html__( 'Columns', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => '0',
				'options' => [
					'0' => esc_html__( 'Auto', 'athemes-addons-elementor' ),
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

		$this->add_control(
			'full_width',
			[
				'label' => esc_html__( 'Full Width', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'columns' => '1',
				],
				'return_value' => 'yes',
				'default' => 'no',
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-social-icons-wrapper.elementor-grid' => 'grid-template-columns:1fr;width:100%;',
					'{{WRAPPER}} .athemes-addons-social-icons-wrapper.elementor-grid .elementor-grid-item,{{WRAPPER}} .athemes-addons-social-icons-wrapper.elementor-grid a' => 'width:100%;',
				],
			]
		);

		$this->add_responsive_control(
			'align',
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
				],
				'prefix_class' => 'e-grid-align%s-',
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .elementor-widget-container' => 'text-align: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'icon_align',
			[
				'label' => esc_html__( 'Icon Position', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => '0',
				'options' => [
					'0'     => esc_html__( 'Before', 'athemes-addons-elementor' ),
					'10'    => esc_html__( 'After', 'athemes-addons-elementor' ),
				],
				'selectors' => [
					'{{WRAPPER}} .social-icon' => 'order: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'view',
			[
				'label' => esc_html__( 'View', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::HIDDEN,
				'default' => 'traditional',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_social_style',
			[
				'label' => esc_html__( 'Element', 'athemes-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label' => esc_html__( 'Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => esc_html__( 'Official Color', 'athemes-addons-elementor' ),
					'custom' => esc_html__( 'Custom', 'athemes-addons-elementor' ),
				],
			]
		);

		$this->add_control(
			'icon_primary_color',
			[
				'label' => esc_html__( 'Primary Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'icon_color' => 'custom',
				],
				'selectors' => [
					'{{WRAPPER}} .aafe-social-icon' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'icon_secondary_color',
			[
				'label' => esc_html__( 'Secondary Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'icon_color' => 'custom',
				],
				'selectors' => [
					'{{WRAPPER}} .aafe-social-icon i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .aafe-social-icon svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => esc_html__( 'Size', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				// The `%' and `em` units are not supported as the widget implements icons differently then other icons.
				'size_units' => [ 'px', 'rem', 'vw', 'custom' ],
				'range' => [
					'px' => [
						'min' => 6,
						'max' => 300,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--icon-size: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'icon_padding',
			[
				'label' => esc_html__( 'Horizontal padding', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .aafe-social-icon' => '--icon-padding-horizontal: {{SIZE}}{{UNIT}}',
				],
				'default' => [
					'unit' => 'em',
				],
				'tablet_default' => [
					'unit' => 'em',
				],
				'mobile_default' => [
					'unit' => 'em',
				],
				'range' => [
					'em' => [
						'min' => 0,
						'max' => 5,
					],
				],
			]
		);

		$this->add_responsive_control(
			'icon_padding_vertical',
			[
				'label' => esc_html__( 'Vertical padding', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .aafe-social-icon' => '--icon-padding-vertical: {{SIZE}}{{UNIT}}',
				],
				'default' => [
					'unit' => 'em',
				],
				'tablet_default' => [
					'unit' => 'em',
				],
				'mobile_default' => [
					'unit' => 'em',
				],
				'range' => [
					'em' => [
						'min' => 0,
						'max' => 5,
					],
				],
			]
		);

		$this->add_responsive_control(
			'icon_spacing',
			[
				'label' => esc_html__( 'Spacing', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}}' => '--grid-column-gap: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'row_gap',
			[
				'label' => esc_html__( 'Rows Gap', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}}' => '--grid-row-gap: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'icon_border',
				'selector' => '{{WRAPPER}} .aafe-social-icon',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .aafe-social-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'icon_shadow',
				'selector' => '{{WRAPPER}} .aafe-social-icon',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_social_hover',
			[
				'label' => esc_html__( 'Element Hover', 'athemes-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'hover_primary_color',
			[
				'label' => esc_html__( 'Primary Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'condition' => [
					'icon_color' => 'custom',
				],
				'selectors' => [
					'{{WRAPPER}} .aafe-social-icon:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'hover_secondary_color',
			[
				'label' => esc_html__( 'Secondary Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'condition' => [
					'icon_color' => 'custom',
				],
				'selectors' => [
					'{{WRAPPER}} .aafe-social-icon:hover i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .aafe-social-icon:hover svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'condition' => [
					'icon_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .aafe-social-icon:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'hover_icon_shadow',
				'selector' => '{{WRAPPER}} .aafe-social-icon:hover',
			]
		);

		$this->add_control(
			'hover_animation',
			[
				'label' => esc_html__( 'Hover Animation', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->add_control(
			'show_label_on_hover',
			[
				'label' => esc_html__( 'Show Label on Hover', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'label_on' => esc_html__( 'Yes', 'athemes-addons-elementor' ),
				'label_off' => esc_html__( 'No', 'athemes-addons-elementor' ),
				'return_value' => 'yes',
				'condition' => [
					'display_mode' => 'icons_text',
				],
			]
		);

		$this->add_responsive_control(
			'hover_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .aafe-social-icon:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
		$fallback_defaults = [
			'fa fa-facebook',
			'fa fa-instagram',
			'fa fa-google-plus',
		];

		$class_animation = '';

		if ( ! empty( $settings['hover_animation'] ) ) {
			$class_animation = ' elementor-animation-' . $settings['hover_animation'];
		}

		$migration_allowed = Icons_Manager::is_migration_allowed();

		$this->add_render_attribute( 'wrapper', 'class', 'athemes-addons-social-icons-wrapper elementor-grid' );

		if ( 'yes' === $settings['show_label_on_hover'] ) {
			$this->add_render_attribute( 'wrapper', 'class', 'show-label-hover' );
		}
		?>
		<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
			<?php
			foreach ( $settings['social_icon_list'] as $index => $item ) {
				$migrated = isset( $item['__fa4_migrated']['social_icon'] );
				$is_new = empty( $item['social'] ) && $migration_allowed;
				$social = '';

				// add old default
				if ( empty( $item['social'] ) && ! $migration_allowed ) {
					$item['social'] = isset( $fallback_defaults[ $index ] ) ? $fallback_defaults[ $index ] : 'fa fa-wordpress';
				}

				if ( ! empty( $item['social'] ) ) {
					$social = str_replace( 'fa fa-', '', $item['social'] );
				}

				if ( ( $is_new || $migrated ) && 'svg' !== $item['social_icon']['library'] ) {
					$social = explode( ' ', $item['social_icon']['value'], 2 );
					if ( empty( $social[1] ) ) {
						$social = '';
					} else {
						$social = str_replace( 'fa-', '', $social[1] );
					}
				}
				if ( 'svg' === $item['social_icon']['library'] ) {
					$social = get_post_meta( $item['social_icon']['value']['id'], '_wp_attachment_image_alt', true );
				}

				$link_key = 'link_' . $index;

				$this->add_render_attribute( $link_key, 'class', [
					'elementor-icon',
					'aafe-social-icon',
					'aafe-social-icon-type-' . $settings['display_mode'],
					'aafe-social-icon-' . $social . $class_animation,
					'elementor-repeater-item-' . $item['_id'],
				] );

				$this->add_link_attributes( $link_key, $item['link'] );

				?>
				<span class="elementor-grid-item">
					<?php if ( $item['social_tooltip'] === 'yes' ) : ?>
					<span class="aafe-social-tooltip">
						<?php echo esc_html( $item['social_tooltip_text'] ); ?>
					</span>
					<?php endif; ?>
					<a <?php $this->print_render_attribute_string( $link_key ); ?>>
						<span class="social-icon">
						<?php
						if ( 'icons' === $settings['display_mode'] || 'icons_text' === $settings['display_mode'] ) {
							if ( $is_new || $migrated ) {
								Icons_Manager::render_icon( $item['social_icon'] );
							} else { ?>
								<i class="<?php echo esc_attr( $item['social'] ); ?>"></i>
							<?php }
						} ?>
						</span>
						<?php if ( 'text' === $settings['display_mode'] || 'icons_text' === $settings['display_mode'] ) : ?>
							<span class="aafe-social-label"><?php echo esc_html( $item['social_label'] ); ?></span>
						<?php else : ?>
							<span class="elementor-screen-only"><?php echo esc_html( ucwords( $social ) ); ?></span>
						<?php endif; ?>						
					</a>
				</span>
			<?php } ?>
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
	protected function content_template() {}
}
Plugin::instance()->widgets_manager->register( new Advanced_Social() );