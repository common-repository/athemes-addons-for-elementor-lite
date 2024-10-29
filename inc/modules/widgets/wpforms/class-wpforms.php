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
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Text_Shadow;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor icon list widget.
 *
 * Elementor widget that displays a bullet list with any chosen icons and texts.
 *
 * @since 1.0.0
 */
class WPForms extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve icon list widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'athemes-addons-wpforms';
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
		return __( 'WPForms', 'athemes-addons-elementor' );
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
		return 'eicon-form-horizontal aafe-elementor-icon';
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
		return [ 'contact', 'form', 'contact form', 'wpforms', 'athemes', 'addons', 'athemes addons' ];
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

        if ( !function_exists( 'wpforms' ) ) {
            $this->start_controls_section(
                'missing_plugin_notice_section',
                [
                    'label' => __( 'Missing plugin: WPForms', 'athemes-addons-elementor' ),
                ]
            );

            $this->add_control(
                'missing_plugin_notice',
                [
                    'type'            => Controls_Manager::RAW_HTML,
                    'raw'             => __('Please install <a href="plugin-install.php?s=wpforms&tab=search&type=term" target="_blank">WPForms</a> to use this widget.',
                        'athemes-addons-elementor'),
                ]
            );

            $this->end_controls_section();
            return;
        }

		$this->start_controls_section(
			'section_forms',
			[
				'label' => __( 'WPForms', 'athemes-addons-elementor' ),
			]
		);

		$this->add_control(
			'contact_title_heading',
			[
				'label' => __( 'Title', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'form_title',
			[
				'label' => __( 'Form title', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => '',
			]
		);  
		
		$this->add_control(
			'form_title_tag',
			[
				'label' => __('Form title tag', 'athemes-addons-elementor'),
				'type' => Controls_Manager::SELECT,
				'default' => 'h2',
				'options' => [
					'h1'    => __('H1', 'athemes-addons-elementor'),
					'h2'    => __('H2', 'athemes-addons-elementor'),
					'h3'    => __('H3', 'athemes-addons-elementor'),
					'h4'    => __('H4', 'athemes-addons-elementor'),
					'h5'    => __('H5', 'athemes-addons-elementor'),
					'h6'    => __('H6', 'athemes-addons-elementor'),
					'span'  => __('Span', 'athemes-addons-elementor'),
					'p'     => __('P', 'athemes-addons-elementor'),
					'div'   => __('Div', 'athemes-addons-elementor'),
				],
			]
		);  

		$this->add_control(
			'contact_forms_heading',
			[
				'label' => __( 'Form', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'contact_forms',
			[
				'label' => __('Select your form', 'athemes-addons-elementor'),
				'type' => Controls_Manager::SELECT,
				'default' => 0,
				'options' => $this->forms(),
			]
		);  
	
		$this->add_responsive_control(
			'align',
			[
				'label' => esc_html__( 'Form alignment', 'athemes-addons-elementor' ),
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
				'default' => '',
				'selectors_dictionary' => [
					'left'      => '-webkit-box-pack:start;-ms-flex-pack:start;justify-content:flex-start',
					'center'    => '-webkit-box-pack:center;-ms-flex-pack:center;justify-content:center',
					'right'     => '-webkit-box-pack:end;-ms-flex-pack:end;justify-content:flex-end',
				],
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-contact-form' => '{{VALUE}}',
				],
			]
		);  
		
	
		$this->add_responsive_control(
			'content_align',
			[
				'label' => esc_html__( 'Content alignment', 'athemes-addons-elementor' ),
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
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-contact-form' => 'text-align: {{VALUE}}',
				],
			]
		);          

		$this->add_responsive_control(
			'size',
			[
				'label' => __( 'Form width', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 2000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],                  
				],
				'devices' => [ 'desktop', 'tablet', 'mobile' ],
				'desktop_default' => [
					'size' => 370,
					'unit' => 'px',
				],
				'tablet_default' => [
					'size' => 370,
					'unit' => 'px',
				],
				'mobile_default' => [
					'size' => 370,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-form-inner' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);      

		$this->add_control(
			'contact_button_heading',
			[
				'label' => __( 'Button', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'stretch_button',
			[
				'label' => __( 'Stretch button?', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on'  => __( 'Yes', 'athemes-addons-elementor' ),
				'label_off' => __( 'No', 'athemes-addons-elementor' ),
				'return_value' => 'yes',
				'default' => 'no',
				'selectors_dictionary' => [
					'yes'   => 'width:100%',
					'no'    => 'width:auto',
				],
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-contact-form button[type="submit"]' => '{{VALUE}}',
				],                               
			]
		);  
		
		$this->add_responsive_control(
			'button_size',
			[
				'label' => __( 'Button max. width', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 2000,
						'step' => 5,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],                  
				],
				'devices' => [ 'desktop', 'tablet', 'mobile' ],
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-contact-form button[type="submit"]' => 'max-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => array(
					'stretch_button' => 'yes',
				),
			]
		);          

		$this->add_responsive_control(
			'align_button',
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
				'default' => '',
				'selectors_dictionary' => [
					'left'      => 'margin-left:0;margin-right:auto;display:table;',
					'center'    => 'margin-left:auto;margin-right:auto;display:table;',
					'right'     => 'margin-left:auto;margin-right:0;display:table;',
				],
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-contact-form button[type="submit"]' => '{{VALUE}}',
				],
			]
		);  
		

		$this->end_controls_section();

		//First part styles
		$this->start_controls_section(
			'section_form_container',
			[
				'label' => __( 'Container', 'athemes-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'container_background',
				'label' => __( 'Background', 'athemes-addons-elementor' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .athemes-addons-form-inner',
			]
		);

		$this->add_responsive_control(
			'container_padding',
			[
				'label' => __( 'Padding', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'devices' => [ 'desktop', 'tablet', 'mobile' ],
				'desktop_default' => [
					'top' => 15,
					'right' => 15,
					'bottom' => 15,
					'left' => 15,
					'unit' => 'px',
				],
				'tablet_default' => [
					'top' => 15,
					'right' => 15,
					'bottom' => 15,
					'left' => 15,
					'unit' => 'px',
				],
				'mobile_default' => [
					'top' => 15,
					'right' => 15,
					'bottom' => 15,
					'left' => 15,
					'unit' => 'px',
				],              
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-form-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);  
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'container_border',
				'label' => __( 'Border', 'athemes-addons-elementor' ),
				'selector' => '{{WRAPPER}} .athemes-addons-form-inner',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'container_shadow',
				'label' => __( 'Box Shadow', 'athemes-addons-elementor' ),
				'selector' => '{{WRAPPER}} .athemes-addons-form-inner',
			]
		);

		$this->end_controls_section();

		//Title styles
		$this->start_controls_section(
			'section_form_title',
			[
				'label' => __( 'Title', 'athemes-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'form_title_color',
			[
				'label'     => __( 'Color', 'athemes-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-contact-form-title' => 'color: {{VALUE}};',
				],
			]
		);          

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'form_title_typography',
				'selector'  => '{{WRAPPER}} .athemes-addons-contact-form-title',
			]
		);

		$this->end_controls_section();

		//Label styles
		$this->start_controls_section(
			'section_form_labels',
			[
				'label' => __( 'Labels', 'athemes-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'form_labels_color',
			[
				'label'     => __( 'Color', 'athemes-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-form-inner label' => 'color: {{VALUE}};',
					'{{WRAPPER}} .athemes-addons-form-inner legend' => 'color: {{VALUE}};',
				],
			]
		);          

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'form_labels_typography',
				'selector'  => '{{WRAPPER}} .athemes-addons-form-inner label, {{WRAPPER}} .athemes-addons-form-inner legend',
			]
		);

		$this->end_controls_section();      
		
		//Fields styles
		$this->start_controls_section(
			'section_form_fields',
			[
				'label' => __( 'Fields', 'athemes-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'field_background_color',
			[
				'label'     => __( 'Background color', 'athemes-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-form-inner input:not([type="submit"]), {{WRAPPER}} .athemes-addons-form-inner textarea, {{WRAPPER}} .athemes-addons-form-inner select' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'field_text_color',
			[
				'label'     => __( 'Color', 'athemes-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-form-inner input:not([type="submit"]), {{WRAPPER}} .athemes-addons-form-inner textarea, {{WRAPPER}} .athemes-addons-form-inner select' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'field_placeholder_color',
			[
				'label'     => __( 'Placeholder color', 'athemes-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} input:not([type="submit"])::placeholder,
					{{WRAPPER}} textarea::placeholder' => 'color: {{VALUE}};',
                ],
			]
		);      

		$this->add_control(
			'field_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} input:not([type="submit"])' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} textarea' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'field_border',
				'label' => __( 'Border', 'athemes-addons-elementor' ),
				'selector' => '{{WRAPPER}} .athemes-addons-form-inner input:not([type="submit"]), {{WRAPPER}} .athemes-addons-form-inner textarea, {{WRAPPER}} .athemes-addons-form-inner select',
			]
		);

		$this->add_responsive_control(
			'field_padding',
			[
				'label' => __( 'Padding', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'devices' => [ 'desktop', 'tablet', 'mobile' ],         
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-form-inner input:not([type="submit"]), {{WRAPPER}} .athemes-addons-form-inner textarea, {{WRAPPER}} .athemes-addons-form-inner select' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);  
		
		$this->add_responsive_control(
			'field_spacing',
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
				'devices' => [ 'desktop', 'tablet', 'mobile' ],
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-form-inner input:not([type="submit"]), {{WRAPPER}} .athemes-addons-form-inner textarea' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);  

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			[
				'label' => esc_html__( 'Button', 'athemes-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'selector' => '{{WRAPPER}} button[type="submit"]',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'text_shadow',
				'selector' => '{{WRAPPER}} button[type="submit"]',
			]
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => esc_html__( 'Normal', 'athemes-addons-elementor' ),
			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label' => esc_html__( 'Text Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} button[type="submit"]' => 'fill: {{VALUE}}; color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'background',
				'label' => esc_html__( 'Background', 'athemes-addons-elementor' ),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} button[type="submit"],{{WRAPPER}} div.wpforms-container-full button[type=submit]:not(:hover):not(:active)',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
					'color' => [],
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => esc_html__( 'Hover', 'athemes-addons-elementor' ),
			]
		);

		$this->add_control(
			'hover_color',
			[
				'label' => esc_html__( 'Text Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} button[type="submit"]:hover, {{WRAPPER}} button[type="submit"]:focus' => 'color: {{VALUE}};',
					'{{WRAPPER}} button[type="submit"]:hover svg, {{WRAPPER}} button[type="submit"]:focus svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'button_background_hover',
				'label' => esc_html__( 'Background', 'athemes-addons-elementor' ),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} button[type="submit"]:hover, {{WRAPPER}} button[type="submit"]:focus',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
				],
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} button[type="submit"]:hover, {{WRAPPER}} button[type="submit"]:focus' => 'border-color: {{VALUE}};',
				],
			]
		);


		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'border',
				'selector' => '{{WRAPPER}} button[type="submit"]',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} button[type="submit"]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow',
				'selector' => '{{WRAPPER}} button[type="submit"]',
			]
		);

		$this->add_responsive_control(
			'text_padding',
			[
				'label' => esc_html__( 'Padding', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} button[type="submit"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();      
	}

	public function forms() {

		if ( !function_exists( 'wpforms' ) ) {
			return;
		}

		static $forms_list = [];

		if ( empty( $forms_list ) ) {
			$forms = wpforms()->form->get();

			if ( ! empty( $forms ) ) {
				$forms_list[0] = esc_html__( 'Select a form', 'athemes-addons-elementor' );
				foreach ( $forms as $form ) {
					$forms_list[ $form->ID ] = mb_strlen( $form->post_title ) > 100 ? mb_substr( $form->post_title, 0, 97 ) . '...' : $form->post_title;
				}
			}
		}

		return $forms_list;
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
		?>

		<div class="athemes-addons-contact-form athemes-ele-wpforms">
			<div class="athemes-addons-form-inner">
				<?php if ( $settings['form_title'] ) : ?>
					<<?php echo esc_attr( $settings['form_title_tag'] ); ?> class="athemes-addons-contact-form-title">
						<?php echo esc_html( $settings['form_title'] ); ?>
					</<?php echo esc_attr( $settings['form_title_tag'] ); ?>>
				<?php endif; ?>

				<?php 
				if ( !empty( $settings['contact_forms'] ) ) {
					echo do_shortcode( '[wpforms id="' . $settings['contact_forms'] . '"]' );
				}
				?>
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
Plugin::instance()->widgets_manager->register( new WPForms() );