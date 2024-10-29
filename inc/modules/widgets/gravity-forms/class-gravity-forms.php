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
class Gravity_Forms extends Widget_Base {

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
		return 'athemes-addons-gravity-forms';
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
		return __( 'Gravity Forms', 'athemes-addons-elementor' );
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
		return [ 'contact', 'form', 'contact form', 'gravity', 'gravity forms', 'athemes', 'addons', 'athemes addons' ];
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

        if ( !class_exists('\GFForms') ) {
            $this->start_controls_section(
                'missing_plugin_notice_section',
                [
                    'label' => __( 'Missing plugin: Gravity Forms', 'athemes-addons-elementor' ),
                ]
            );

            $this->add_control(
                'missing_plugin_notice',
                [
                    'type'            => Controls_Manager::RAW_HTML,
					'raw'             => __('Please install <a href="https://www.gravityforms.com/" target="_blank">Gravity Forms</a> to use this widget.',
					'athemes-addons-elementor'),
                ]
            );

            $this->end_controls_section();
            return;
        }

		$this->start_controls_section(
			'section_forms',
			[
				'label' => __( 'Gravity Forms', 'athemes-addons-elementor' ),
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
		
		$this->add_control(
			'form_title',
			[
				'label' => __( 'Form title', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on'  => __( 'Yes', 'athemes-addons-elementor' ),
				'label_off' => __( 'No', 'athemes-addons-elementor' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'form_description',
			[
				'label' => __( 'Form description', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on'  => __( 'Yes', 'athemes-addons-elementor' ),
				'label_off' => __( 'No', 'athemes-addons-elementor' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);      

		$this->add_control(
			'ajax',
			[
				'label' => __( 'AJAX', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on'  => __( 'Yes', 'athemes-addons-elementor' ),
				'label_off' => __( 'No', 'athemes-addons-elementor' ),
				'return_value' => 'yes',
				'default' => 'no',
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
					'yes'   => 'width:100% !important;',
					'no'    => 'width:auto !important;',
				],
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-contact-form .gform-theme.gform-theme--framework.gform_wrapper .button:where(:not(.gform-theme-no-framework):not(.gform-theme__disable):not(.gform-theme__disable *):not(.gform-theme__disable-framework):not(.gform-theme__disable-framework *))' => '{{VALUE}}',
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
					'{{WRAPPER}} .athemes-addons-contact-form .gform-theme.gform-theme--framework.gform_wrapper .button:where(:not(.gform-theme-no-framework):not(.gform-theme__disable):not(.gform-theme__disable *):not(.gform-theme__disable-framework):not(.gform-theme__disable-framework *))' => 'max-width: {{SIZE}}{{UNIT}};',
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
					'center'    => 'margin-left:auto;margin-right:auto;display:table!important;margin-inline:auto!important;',
					'right'     => 'margin-left:auto;margin-right:0;display:table!important;margin-inline-start:auto!important;',
				],
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-contact-form .gform-theme.gform-theme--framework.gform_wrapper .button:where(:not(.gform-theme-no-framework):not(.gform-theme__disable):not(.gform-theme__disable *):not(.gform-theme__disable-framework):not(.gform-theme__disable-framework *))' => '{{VALUE}}',
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
				'label' => __( 'Title & description', 'athemes-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'form_title_color',
			[
				'label'     => __( 'Color', 'athemes-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gform_title' => 'color: {{VALUE}};',
				],
			]
		);          

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'form_title_typography',
				'selector'  => '{{WRAPPER}} .gform_title',
			]
		);

		$this->add_control(
			'form_description_color',
			[
				'label'     => __( 'Description color', 'athemes-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gform_description' => 'color: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'form_description_typography',
				'selector'  => '{{WRAPPER}} .gform_description',
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
					'{{WRAPPER}} .athemes-addons-form-inner label, {{WRAPPER}} .athemes-addons-form-inner legend, {{WRAPPER}} .athemes-addons-form-inner .gfield_description' => 'color: {{VALUE}};',
				],
			]
		);          

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'form_labels_typography',
				'selector'  => '{{WRAPPER}} .athemes-addons-form-inner label, {{WRAPPER}} .athemes-addons-form-inner legend, {{WRAPPER}} .athemes-addons-form-inner .gfield_description',
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
					'{{WRAPPER}} .athemes-addons-form-inner input:not([type="submit"]), {{WRAPPER}} .athemes-addons-form-inner textarea, {{WRAPPER}} .athemes-addons-form-inner fieldset' => 'margin-bottom: {{SIZE}}{{UNIT}};',
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
				'selector' => '{{WRAPPER}} .gform-theme.gform-theme--framework.gform_wrapper .button:where(:not(.gform-theme-no-framework):not(.gform-theme__disable):not(.gform-theme__disable *):not(.gform-theme__disable-framework):not(.gform-theme__disable-framework *))',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'text_shadow',
				'selector' => '{{WRAPPER}} .gform-theme.gform-theme--framework.gform_wrapper .button:where(:not(.gform-theme-no-framework):not(.gform-theme__disable):not(.gform-theme__disable *):not(.gform-theme__disable-framework):not(.gform-theme__disable-framework *))',
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
					'{{WRAPPER}} .gform-theme.gform-theme--framework.gform_wrapper .button:where(:not(.gform-theme-no-framework):not(.gform-theme__disable):not(.gform-theme__disable *):not(.gform-theme__disable-framework):not(.gform-theme__disable-framework *))' => 'fill: {{VALUE}}; color: {{VALUE}};',
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
				'selector' => '{{WRAPPER}} .gform-theme.gform-theme--framework.gform_wrapper .button:where(:not(.gform-theme-no-framework):not(.gform-theme__disable):not(.gform-theme__disable *):not(.gform-theme__disable-framework):not(.gform-theme__disable-framework *)), {{WRAPPER}} --gf-color-primary',
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
					'{{WRAPPER}} .gform-theme.gform-theme--framework.gform_wrapper .button:where(:not(.gform-theme-no-framework):not(.gform-theme__disable):not(.gform-theme__disable *):not(.gform-theme__disable-framework):not(.gform-theme__disable-framework *)):hover, {{WRAPPER}} .gform-theme.gform-theme--framework.gform_wrapper .button:where(:not(.gform-theme-no-framework):not(.gform-theme__disable):not(.gform-theme__disable *):not(.gform-theme__disable-framework):not(.gform-theme__disable-framework *)):focus' => 'color: {{VALUE}};',
					'{{WRAPPER}} .gform-theme.gform-theme--framework.gform_wrapper .button:where(:not(.gform-theme-no-framework):not(.gform-theme__disable):not(.gform-theme__disable *):not(.gform-theme__disable-framework):not(.gform-theme__disable-framework *)):hover svg, {{WRAPPER}} .gform-theme.gform-theme--framework.gform_wrapper .button:where(:not(.gform-theme-no-framework):not(.gform-theme__disable):not(.gform-theme__disable *):not(.gform-theme__disable-framework):not(.gform-theme__disable-framework *)):focus svg' => 'fill: {{VALUE}};',
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
				'selector' => '{{WRAPPER}} .gform-theme.gform-theme--framework.gform_wrapper .button:where(:not(.gform-theme-no-framework):not(.gform-theme__disable):not(.gform-theme__disable *):not(.gform-theme__disable-framework):not(.gform-theme__disable-framework *)):hover, {{WRAPPER}} .gform-theme.gform-theme--framework.gform_wrapper .button:where(:not(.gform-theme-no-framework):not(.gform-theme__disable):not(.gform-theme__disable *):not(.gform-theme__disable-framework):not(.gform-theme__disable-framework *)):focus, {{WRAPPER}} --gf-ctrl-btn-bg-color-hover-primary',
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
					'{{WRAPPER}} .gform-theme.gform-theme--framework.gform_wrapper .button:where(:not(.gform-theme-no-framework):not(.gform-theme__disable):not(.gform-theme__disable *):not(.gform-theme__disable-framework):not(.gform-theme__disable-framework *)):hover, {{WRAPPER}} .gform-theme.gform-theme--framework.gform_wrapper .button:where(:not(.gform-theme-no-framework):not(.gform-theme__disable):not(.gform-theme__disable *):not(.gform-theme__disable-framework):not(.gform-theme__disable-framework *)):focus' => 'border-color: {{VALUE}};',
				],
			]
		);


		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'border',
				'selector' => '{{WRAPPER}} .gform-theme.gform-theme--framework.gform_wrapper .button:where(:not(.gform-theme-no-framework):not(.gform-theme__disable):not(.gform-theme__disable *):not(.gform-theme__disable-framework):not(.gform-theme__disable-framework *))',
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
					'{{WRAPPER}} .gform-theme.gform-theme--framework.gform_wrapper .button:where(:not(.gform-theme-no-framework):not(.gform-theme__disable):not(.gform-theme__disable *):not(.gform-theme__disable-framework):not(.gform-theme__disable-framework *))' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .gform-theme.gform-theme--framework.gform_wrapper .button:where(:not(.gform-theme-no-framework):not(.gform-theme__disable):not(.gform-theme__disable *):not(.gform-theme__disable-framework):not(.gform-theme__disable-framework *))',
			]
		);

		$this->add_responsive_control(
			'text_padding',
			[
				'label' => esc_html__( 'Padding', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .gform-theme.gform-theme--framework.gform_wrapper .button:where(:not(.gform-theme-no-framework):not(.gform-theme__disable):not(.gform-theme__disable *):not(.gform-theme__disable-framework):not(.gform-theme__disable-framework *))' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();      
	}

	public function forms() {

		if ( !class_exists( '\GFForms' ) ) {
			return;
		}

		static $forms_list = [];

		if ( empty( $forms_list ) ) {
			$forms = \GFAPI::get_forms();

			if ( ! empty( $forms ) ) {
				foreach ( $forms as $form ) {
					$forms_list[ $form['id'] ] = $form['title'];
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

		<div class="athemes-addons-contact-form athemes-ele-gravity-forms">
			<div class="athemes-addons-form-inner">
			<?php 
			if ( $settings['contact_forms'] ) {
				$shortcode_atts = array(
					'id'            => $settings['contact_forms'],
					'title'         => 'yes' === $settings['form_title'] ? 'true' : 'false',
					'description'   => 'yes' === $settings['form_description'] ? 'true' : 'false',          
					'ajax'          => !empty($settings['ajax']) ? 'true' : 'false',            
				);

				$this->add_render_attribute( 'shortcode_atts', $shortcode_atts );

				echo do_shortcode( sprintf( '[gravityform %s]', $this->get_render_attribute_string( 'shortcode_atts' ) ) );
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
Plugin::instance()->widgets_manager->register( new Gravity_Forms() );