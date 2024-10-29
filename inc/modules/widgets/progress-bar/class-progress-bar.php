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
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Text_Shadow;

/**
 * Progress bar widget.
 *
 * @since 1.0.0
 */
class Progress_Bar extends Widget_Base {

	public function __construct($data = [], $args = null) {
		parent::__construct($data, $args);

		wp_register_script( 'athemes-addons-lottie-js', ATHEMES_AFE_URI . 'assets/js/vendor/lottie.min.js', array( 'elementor-frontend' ), ATHEMES_AFE_VERSION, true );
	}

	/**
	 * Get widget name.
	 *
	 * Retrieve progress widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'athemes-addons-progress-bar';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve progress widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Progress Bar', 'athemes-addons-elementor' );
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
	 * Retrieve progress widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-skill-bar aafe-elementor-icon';
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
		return [ 'athemes-addons-lottie-js', $this->get_name() . '-scripts' ];
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @since 2.1.0
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'progress', 'bar', 'athemes', 'addons', 'athemes addons' ];
	}

	/**
	 * Register progress widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 3.1.0
	 * @access protected
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_progress',
			[
				'label' => esc_html__( 'Progress Bar', 'athemes-addons-elementor' ),
			]
		);

		$this->add_control(
			'progress_layout',
			[
				'label' => esc_html__( 'Layout', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'line'          => esc_html__( 'Line', 'athemes-addons-elementor' ),
					'circle'        => esc_html__( 'Circle', 'athemes-addons-elementor' ),
				],
				'default' => 'line',
				'separator' => 'after',
			]
		);

		$this->add_control(
			'title',
			[
				'label' => esc_html__( 'Title', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'Enter your title', 'athemes-addons-elementor' ),
				'default' => esc_html__( 'My Skill', 'athemes-addons-elementor' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'title_tag',
			[
				'label' => esc_html__( 'Title HTML Tag', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
					'span' => 'span',
					'p' => 'p',
				],
				'default' => 'span',
				'condition' => [
					'title!' => '',
				],
			]
		);

		$this->add_control(
			'icon_type',
			[
				'label' => __( 'Icon Type', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'none' => [
						'title' => __( 'None', 'athemes-addons-elementor' ),
						'icon' => 'eicon-ban',
					],
					'icon' => [
						'title' => __( 'Icon', 'athemes-addons-elementor' ),
						'icon' => 'eicon-star',
					],
					'lottie' => [
						'title' => __( 'Lottie', 'athemes-addons-elementor' ),
						'icon' => 'eicon-lottie',
					],
				],
				'default' => 'none',
				'show_label' => true,
				'separator' => 'before',
				'condition' => [
					'progress_layout' => 'circle',
				],
			]
		);

		$this->add_control(
			'icon',
			[
				'label' => esc_html__( 'Icon', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'default' => [
					'value'     => 'fas fa-star',
					'library'   => 'fa-solid',
				],
				'condition' => [
					'icon_type'         => 'icon',
					'progress_layout'   => 'circle',
				],
			]
		);  

		$this->add_control(
			'source_type',
			[
				'label' => __( 'Source Type', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'url',
				'options' => [
					'library'   => __( 'Media Library', 'athemes-addons-elementor' ),
					'url'       => __( 'URL', 'athemes-addons-elementor' ),
				],
				'condition' => [
					'icon_type'         => 'lottie',
					'progress_layout'   => 'circle',
				],
			]
		);

		$this->add_control(
			'json_url',
			[
				'label'       => __( 'JSON URL', 'athemes-addons-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'https://lottie.host/b1c5822f-b67f-4161-a3b4-1add55a317c1/sVz6yzXAwE.json',
				/* translators: %1$s: <a> tag open, %2$s: <a> tag close */
				'description' => sprintf( __( 'Enter the URL of your Lottie JSON file. Find Lottie animations %1$shere%2$s.', 'athemes-addons-elementor' ),'<a href="https://lottiefiles.com/" target="_blank">','</a>' ),
				'label_block' => true,
				'condition'   => [
					'icon_type'     => 'lottie',
					'source_type'   => 'url',
					'progress_layout' => 'circle',
				],
			]
		);

		$this->add_control(
			'json_id',
			[
				'label'       => __( 'JSON File', 'athemes-addons-elementor' ),
				'type'        => Controls_Manager::MEDIA,
				'media_type'  => 'application/json',
				/* translators: %1$s: <a> tag open, %2$s: <a> tag close */
				'description' => sprintf( __( 'Upload your Lottie JSON file. Find Lottie animations %1$shere%2$s.', 'athemes-addons-elementor' ),'<a href="https://lottiefiles.com/" target="_blank">','</a>'
				),
				'label_block' => true,
				'condition'   => [
					'icon_type'     => 'lottie',
					'source_type'   => 'library',
					'progress_layout' => 'circle',
				],
			]
		);      

		$this->add_control(
			'percent',
			[
				'label' => esc_html__( 'Percentage', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 60,
					'unit' => '%',
				],
				'dynamic' => [
					'active' => true,
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'display_percentage',
			[
				'label' => esc_html__( 'Display Percentage', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'athemes-addons-elementor' ),
				'label_off' => esc_html__( 'Hide', 'athemes-addons-elementor' ),
				'return_value' => 'show',
				'default' => 'show',
			]
		);

		$this->add_control(
			'inner_text',
			[
				'label' => esc_html__( 'Inner Text', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'e.g. Web Designer', 'athemes-addons-elementor' ),
				'default' => esc_html__( 'Web Designer', 'athemes-addons-elementor' ),
				'label_block' => true,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'content_position',
			[
				'label' => esc_html__( 'Text Position', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'inside'    => esc_html__( 'Inside', 'athemes-addons-elementor' ),
					'outside'   => esc_html__( 'Outside', 'athemes-addons-elementor' ),
				],
				'default' => 'inside',
				'separator' => 'before',
				'condition' => [
					'progress_layout' => 'line',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_progress_style',
			[
				'label' => esc_html__( 'Progress', 'athemes-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'bar_color',
			[
				'label' => esc_html__( 'Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .aafe-progress-wrapper' => '--accent-color: {{VALUE}};',
				],
				'condition' => [
					'progress_layout' => 'circle',
				],              
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'bar_line_color',
				'label' => esc_html__( 'Color', 'athemes-addons-elementor' ),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [
					'image',
				],
				'selector' => '{{WRAPPER}} .aafe-progress-bar',
				'condition' => [
					'progress_layout' => 'line',
				],
			]
		);      

		$this->add_control(
			'bar_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .aafe-progress-wrapper' => '--bg-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'bar_height',
			[
				'label' => esc_html__( 'Height', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .aafe-progress-wrapper[data-layout="line"]' => '--height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'progress_layout' => 'line',
				],
			]
		);

		$this->add_control(
			'bar_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .aafe-progress-wrapper' => '--border-radius: {{SIZE}}{{UNIT}}; overflow: hidden;',
				],
				'condition' => [
					'progress_layout' => 'line',
				],
			]
		);

		$this->add_responsive_control(
			'circle_size',
			[
				'label' => esc_html__( 'Circle Size', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 1,
					],
				],              
				'default' => [
					'size' => 250,
					'unit' => 'px',
				],
				'condition' => [
					'progress_layout' => 'circle',
				],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'stroke_width',
			[
				'label' => esc_html__( 'Stroke Width', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'default' => [
					'size' => 20,
					'unit' => 'px',
				],
				'condition' => [
					'progress_layout' => 'circle',
				],
				'selectors' => [
					'{{WRAPPER}} .aafe-progress-circle' => '--stroke-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'bar_stripes',
			[
				'label' => esc_html__( 'Stripes', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'athemes-addons-elementor' ),
				'label_off' => esc_html__( 'Hide', 'athemes-addons-elementor' ),
				'return_value' => 'striped',
				'condition' => [
					'progress_layout' => 'line',
				],
			]
		);

		$this->add_control(
			'bar_animated',
			[
				'label' => esc_html__( 'Animated stripes?', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'athemes-addons-elementor' ),
				'label_off' => esc_html__( 'No', 'athemes-addons-elementor' ),
				'return_value' => 'animated',
				'condition' => [
					'bar_stripes'       => 'striped',
					'progress_layout'   => 'line',
				],
			]
		);

		$this->add_control(
			'inner_padding',
			[
				'label' => esc_html__( 'Inner Padding', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 20,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .aafe-progress-wrapper[data-layout="line"] .aafe-progress-bar' => 'height: calc(100% - 2 * {{SIZE}}{{UNIT}});top: {{SIZE}}{{UNIT}};left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'progress_layout' => 'line',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_title',
			[
				'label' => esc_html__( 'Content', 'athemes-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_heading',
			[
				'label' => esc_html__( 'Title', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Text Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .progress-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'selector' => '{{WRAPPER}} .progress-title',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'title_shadow',
				'selector' => '{{WRAPPER}} .progress-title',
			]
		);

		$this->add_control(
			'inner_text_heading',
			[
				'label' => esc_html__( 'Text', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'bar_inline_color',
			[
				'label' => esc_html__( 'Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .progress-text' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'bar_inner_typography',
				'selector' => '{{WRAPPER}} .progress-text',
				'exclude' => [
					'line_height',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'bar_inner_shadow',
				'selector' => '{{WRAPPER}} .progress-text',
			]
		);

		$this->add_control(
			'percentage_heading',
			[
				'label' => esc_html__( 'Percentage', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'percentage_color',
			[
				'label' => esc_html__( 'Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .progress-percentage' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'percentage_typography',
				'selector' => '{{WRAPPER}} .progress-percentage',
				'exclude' => [
					'line_height',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'percentage_shadow',
				'selector' => '{{WRAPPER}} .progress-percentage',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render progress widget output on the frontend.
	 * Make sure value does no exceed 100%.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'athemes-addons-progress-bar', 'class', 'athemes-addons-progress-bar' );
		?>

		<div <?php $this->print_render_attribute_string( 'athemes-addons-progress-bar' ); ?> >

		<?php
		$progressbar_id = 'aafe-progress-bar-' . $this->get_id();

		$progress_percentage = is_numeric( $settings['percent']['size'] ) ? $settings['percent']['size'] : '0';
		if ( 100 < $progress_percentage ) {
			$progress_percentage = 100;
		}

		if ( ! Utils::is_empty( $settings['title'] ) ) {
			$this->add_render_attribute(
				'title',
				[
					'class' => 'progress-title',
					'id'    => $progressbar_id,
				]
			);

			$this->add_inline_editing_attributes( 'title' );

			$this->add_render_attribute( 'wrapper', 'aria-labelledby', $progressbar_id );
		}

		$this->add_render_attribute(
			'wrapper',
			[
				'class'         => 'aafe-progress-wrapper',
				'role'          => 'progressbar',
				'aria-valuemin' => '0',
				'aria-valuemax' => '100',
				'aria-valuenow' => esc_attr( $progress_percentage ),
				'data-layout'   => esc_attr( $settings['progress_layout'] ),
				'data-max'      => esc_attr( $progress_percentage ),
				'data-icon'     => esc_attr( $settings['icon_type'] ),
				'data-position' => esc_attr( $settings['content_position'] ),
				'data-stripes'  => 'striped' === $settings['bar_stripes'] ? 'yes' : 'no',
				'data-animated' => 'animated' === $settings['bar_animated'] ? 'yes' : 'no',
			]
		);

		if ( ! empty( $settings['inner_text'] ) ) {
			$this->add_render_attribute( 'wrapper', 'aria-valuetext', "{$progress_percentage}% ({$settings['inner_text']})" );
		}

		if ( ! empty( $settings['progress_type'] ) ) {
			$this->add_render_attribute( 'wrapper', 'class', 'progress-' . $settings['progress_type'] );
		}

		$this->add_render_attribute(
			'progress-bar',
			[
				'class' => 'aafe-progress-bar',
			]
		);

		$this->add_render_attribute( 'inner_text', 'class', 'progress-text' );

		$this->add_inline_editing_attributes( 'inner_text' );

		if ( ! Utils::is_empty( $settings['title'] ) ) { ?>
			<<?php Utils::print_validated_html_tag( $settings['title_tag'] ); ?> <?php $this->print_render_attribute_string( 'title' ); ?>>
				<?php $this->print_unescaped_setting( 'title' ); ?>
			</<?php Utils::print_validated_html_tag( $settings['title_tag'] ); ?>>
		<?php } ?>

		<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
			<?php if ( 'line' === $settings['progress_layout'] ) { ?>
				<?php if ( 'inside' === $settings['content_position'] ) : ?>
				<div class="aafe-progress-container">
					<div <?php $this->print_render_attribute_string( 'progress-bar' ); ?>>
						<span <?php $this->print_render_attribute_string( 'inner_text' ); ?>><?php $this->print_unescaped_setting( 'inner_text' ); ?></span>
						<?php if ( 'show' === $settings['display_percentage'] ) { ?>
							<span class="progress-percentage">0%</span>
						<?php } ?>
					</div>
				</div>
				<?php else : ?>
				<div class="aafe-text-container">
					<span <?php $this->print_render_attribute_string( 'inner_text' ); ?>><?php $this->print_unescaped_setting( 'inner_text' ); ?></span>
					<?php if ( 'show' === $settings['display_percentage'] ) { ?>
						<span class="progress-percentage">0%</span>
					<?php } ?>
				</div>
				<div class="aafe-progress-container"><div <?php $this->print_render_attribute_string( 'progress-bar' ); ?>></div></div>					
				<?php endif; ?>
			<?php } elseif ( 'circle' === $settings['progress_layout'] ) { ?>
				<?php 
					$circle_size = $settings['circle_size']['size'];
					$stroke_width = $settings['stroke_width']['size'];
				?>
				<div class="aafe-progress-circle" style="--size: <?php echo esc_attr( $circle_size ); ?>px">
					<svg width="<?php echo esc_attr( $circle_size ); ?>" height="<?php echo esc_attr( $circle_size ); ?>" viewBox="0 0 <?php echo esc_attr( $circle_size ); ?> <?php echo esc_attr( $circle_size ); ?>">
						<circle cx="<?php echo esc_attr( $circle_size / 2 ); ?>" cy="<?php echo esc_attr( $circle_size / 2 ); ?>" r="<?php echo esc_attr( $circle_size / 2 - $stroke_width ); ?>" class="circle-bg"></circle>
						<circle cx="<?php echo esc_attr( $circle_size / 2 ); ?>" cy="<?php echo esc_attr( $circle_size / 2 ); ?>" r="<?php echo esc_attr( $circle_size / 2 - $stroke_width ); ?>" class="circle-fg"></circle>

						<foreignObject x="0" y="0" width="<?php echo esc_attr( $circle_size ); ?>" height="<?php echo esc_attr( $circle_size ); ?>">
							<div class="aafe-circle-inner">
								<?php if ( 'icon' === $settings['icon_type'] ) {
									echo '<span class="aafe-icon">';
										Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] );
									echo '</span>';
								} elseif ( 'lottie' === $settings['icon_type'] ) {
									wp_enqueue_script( 'athemes-addons-lottie-js' );
									$this->add_render_attribute( 'lottie-container', 'class', 'aafe-lottie' );
									$this->add_render_attribute( 'lottie-container', 'data-json-url', 'url' === $settings['source_type'] ? esc_url($settings['json_url']) : esc_attr( $settings['json_id']['url'] ) );
									?>
									<div <?php $this->print_render_attribute_string( 'lottie-container' ); ?>></div>
								<?php
								} ?>
								<span <?php $this->print_render_attribute_string( 'inner_text' ); ?>><?php $this->print_unescaped_setting( 'inner_text' ); ?></span>
								<?php if ( 'show' === $settings['display_percentage'] ) { ?>
									<span class="progress-percentage">0%</span>
								<?php } ?>
							</div>
						</foreignObject>
					</svg>
				</div> 

			<?php } ?>
		</div>

		</div>
		<?php
	}

	/**
	 * Render progress widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 2.9.0
	 * @access protected
	 */
	protected function content_template() {}
}
Plugin::instance()->widgets_manager->register( new Progress_Bar() );