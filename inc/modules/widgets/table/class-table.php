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
 * Table widget.
 *
 * @since 1.0.0
 */
class Table extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'athemes-addons-table';
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
		return __( 'Table', 'athemes-addons-elementor' );
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
		return 'eicon-table aafe-elementor-icon';
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
		return [ 'table', 'tables', 'athemes', 'addons', 'athemes addons' ];
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
			'section_table',
			[
				'label' => __( 'Source', 'athemes-addons-elementor' ),
			]
		);

		$this->add_control(
			'source',
			array(
				'label'   => __( 'Source', 'athemes-addons-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'manual',
				'options' => array(
					'manual'    => __( 'Manual', 'athemes-addons-elementor' ),
					'csv'       => __( 'CSV File', 'athemes-addons-elementor' ),
				),
			)
		);

		$this->add_control(
			'csv_file',
			[
				'label' => __( 'CSV File', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::MEDIA,
				'media_types' => [
					'csv',
				],
				'description' => __( 'Upload a CSV file.', 'athemes-addons-elementor' ),
				'condition' => [
					'source' => 'csv',
				],
			]
		);

		$this->add_control(
			'csv_delimiter',
			[
				'label' => __( 'Delimiter', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => ',',
				'condition' => [
					'source' => 'csv',
				],
			]
		);

		$this->add_control(
			'csv_header',
			[
				'label' => __( 'Import first row as header', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'athemes-addons-elementor' ),
				'label_off' => __( 'No', 'athemes-addons-elementor' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'condition' => [
					'source' => 'csv',
				],
			]
		);

		$this->end_controls_section();


		$this->start_controls_section(
			'section_table_header',
			[
				'label' => __( 'Header', 'athemes-addons-elementor' ),
			]
		);  

		$repeater = new Repeater();

		$repeater->add_control(
			'header_name',
			[
				'label' => __( 'Name', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Name', 'athemes-addons-elementor' ),
				'show_label' => true,
			]
		);

		$repeater->add_control(
			'header_span',
			[
				'label' => __( 'Span', 'athemes-addons-elementor' ),
				'description' => __( 'How many columns this header should span.', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => 1,
				'show_label' => true,
			]
		);

		$repeater->add_control(
			'header_icon_switcher',
			[
				'label' => __( 'Icon', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'athemes-addons-elementor' ),
				'label_off' => __( 'No', 'athemes-addons-elementor' ),
				'return_value' => 'yes',
				'default' => 'no',
				'show_label' => true,
			]
		);

		$repeater->add_control(
			'header_icon_type',
			[
				'label' => __( 'Icon Type', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'icon' => [
						'title' => __( 'Icon', 'athemes-addons-elementor' ),
						'icon' => 'eicon-star',
					],
					'image' => [
						'title' => __( 'Image', 'athemes-addons-elementor' ),
						'icon' => 'eicon-image',
					],
				],
				'default' => 'icon',
				'condition' => [
					'header_icon_switcher' => 'yes',
				],
				'show_label' => true,
			]
		);
		
		$repeater->add_control(
			'header_icon',
			[
				'label' => esc_html__( 'Icon', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'default' => [
					'value'     => 'fas fa-star',
					'library'   => 'fa-solid',
				],
				'condition' => [
					'header_icon_switcher'  => 'yes',
					'header_icon_type'      => 'icon',
				],
			]
		);  
		
		$repeater->add_control(
			'header_image',
			[
				'label' => __( 'Image', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::MEDIA,
				'condition' => [
					'header_icon_switcher'  => 'yes',
					'header_icon_type'      => 'image',
				],
			]
		);

		$this->add_control(
			'table_header',
			[
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'header_name' => __( 'Column #1', 'athemes-addons-elementor' ),
						'header_span' => 1,
						'header_icon_switcher' => 'no',
					],
					[
						'header_name' => __( 'Column #2', 'athemes-addons-elementor' ),
						'header_span' => 1,
						'header_icon_switcher' => 'no',
					],
					[
						'header_name' => __( 'Column #3', 'athemes-addons-elementor' ),
						'header_span' => 1,
						'header_icon_switcher' => 'no',
					],
					[
						'header_name' => __( 'Column #4', 'athemes-addons-elementor' ),
						'header_span' => 1,
						'header_icon_switcher' => 'no',
					],
				],              
				'title_field' => '{{{ header_name }}}',
			]
		);

		$this->add_control(
			'disable_header',
			[
				'label' => __( 'Disable Header', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'athemes-addons-elementor' ),
				'label_off' => __( 'No', 'athemes-addons-elementor' ),
				'return_value' => 'yes',
				'default' => 'no',
				'show_label' => true,
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_table_content',
			[
				'label' => __( 'Content', 'athemes-addons-elementor' ),
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'element_type',
			[
				'label' => __( 'Element Type', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'row'       => __( 'Row', 'athemes-addons-elementor' ),
					'column'    => __( 'Column', 'athemes-addons-elementor' ),
				],
				'default' => 'row',
				'show_label' => true,
			]
		);

		$repeater->start_controls_tabs( 'cell_options' );

		$repeater->start_controls_tab(
			'cell_content',
			[
				'label' => __( 'Content', 'athemes-addons-elementor' ),
			]
		);

		$repeater->add_control(
			'cell_content_type',
			[
				'label' => __( 'Content Type', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'text' => [
						'title' => __( 'Text', 'athemes-addons-elementor' ),
						'icon' => 'eicon-text-field',
					],
					'icon' => [
						'title' => __( 'Icon', 'athemes-addons-elementor' ),
						'icon' => 'eicon-star',
					],
					'image' => [
						'title' => __( 'Image', 'athemes-addons-elementor' ),
						'icon' => 'eicon-image',
					],
					'wywiwyg' => [
						'title' => __( 'WYSIWYG', 'athemes-addons-elementor' ),
						'icon' => 'eicon-text-area',
					],
					'template' => [
						'title' => __( 'Template', 'athemes-addons-elementor' ),
						'icon' => 'eicon-document-file',
					],
				],
				'default' => 'text',
				'show_label' => true,
			]
		);

		$repeater->add_control(
			'cell_text',
			[
				'label' => __( 'Text', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Text', 'athemes-addons-elementor' ),
				'condition' => [
					'cell_content_type' => 'text',
				],
				'show_label' => true,
			]
		);

		$repeater->add_control(
			'cell_icon',
			[
				'label' => esc_html__( 'Icon', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'default' => [
					'value'     => 'fas fa-star',
					'library'   => 'fa-solid',
				],
				'condition' => [
					'cell_content_type' => 'icon',
				],
			]
		);

		$repeater->add_control(
			'cell_image',
			[
				'label' => __( 'Image', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::MEDIA,
				'condition' => [
					'cell_content_type' => 'image',
				],
			]
		);

		$repeater->add_control(
			'cell_wysiwyg',
			[
				'label' => __( 'WYSIWYG', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::WYSIWYG,
				'default' => __( 'WYSIWYG', 'athemes-addons-elementor' ),
				'condition' => [
					'cell_content_type' => 'wywiwyg',
				],
			]
		);

		$repeater->add_control(
			'cell_template',
			[
				'label' => __( 'Template', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => $this->get_available_templates(),
				'condition' => [
					'cell_content_type' => 'template',
				],
			]
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'cell_settings',
			[
				'label' => __( 'Settings', 'athemes-addons-elementor' ),
			]
		);

		$repeater->add_control(
			'cell_span',
			[
				'label' => __( 'Span', 'athemes-addons-elementor' ),
				'description' => __( 'How many columns this cell should span.', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => 1,
				'show_label' => true,
			]
		);

		$repeater->add_control(
			'cell_row_span',
			[
				'label' => __( 'Row Span', 'athemes-addons-elementor' ),
				'description' => __( 'How many rows this cell should span.', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => 1,
				'show_label' => true,
			]
		);

		$repeater->add_control(
			'cell_header',
			[
				'label' => __( 'Use as Header', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'athemes-addons-elementor' ),
				'label_off' => __( 'No', 'athemes-addons-elementor' ),
				'return_value' => 'yes',
				'default' => 'no',
				'show_label' => true,
				'separator' => 'before',
			]
		);

		$repeater->add_control(
			'cell_color',
			[
				'label' => __( 'Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'color: {{VALUE}}',
				],
				'separator' => 'before',
			]
		);

		$repeater->add_control(
			'cell_background_color',
			[
				'label' => __( 'Background Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'background-color: {{VALUE}}',
				],
			]
		);

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		$this->add_control(
			'table_content',
			[
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'element_type' => 'row',
						'cell_content_type' => 'text',
						'cell_text' => __( 'Text', 'athemes-addons-elementor' ),
						'cell_span' => 1,
						'cell_row_span' => 1,
						'cell_header' => 'no',
					],
					[
						'element_type' => 'column',
						'cell_content_type' => 'text',
						'cell_text' => __( 'Text #1', 'athemes-addons-elementor' ),
						'cell_span' => 1,
						'cell_row_span' => 1,
						'cell_header' => 'no',
					],
					[
						'element_type' => 'column',
						'cell_content_type' => 'text',
						'cell_text' => __( 'Text #2', 'athemes-addons-elementor' ),
						'cell_span' => 1,
						'cell_row_span' => 1,
						'cell_header' => 'no',
					],
					[
						'element_type' => 'column',
						'cell_content_type' => 'text',
						'cell_text' => __( 'Text #3', 'athemes-addons-elementor' ),
						'cell_span' => 1,
						'cell_row_span' => 1,
						'cell_header' => 'no',
					],
					[
						'element_type' => 'column',
						'cell_content_type' => 'text',
						'cell_text' => __( 'Text #4', 'athemes-addons-elementor' ),
						'cell_span' => 1,
						'cell_row_span' => 1,
						'cell_header' => 'no',
					],  
					[
						'element_type' => 'row',
						'cell_content_type' => 'text',
						'cell_text' => __( 'Text', 'athemes-addons-elementor' ),
						'cell_span' => 1,
						'cell_row_span' => 1,
						'cell_header' => 'no',
					],
					[
						'element_type' => 'column',
						'cell_content_type' => 'text',
						'cell_text' => __( 'Text #5', 'athemes-addons-elementor' ),
						'cell_span' => 1,
						'cell_row_span' => 1,
						'cell_header' => 'no',
					],
					[
						'element_type' => 'column',
						'cell_content_type' => 'text',
						'cell_text' => __( 'Text #6', 'athemes-addons-elementor' ),
						'cell_span' => 1,
						'cell_row_span' => 1,
						'cell_header' => 'no',
					],
					[
						'element_type' => 'column',
						'cell_content_type' => 'text',
						'cell_text' => __( 'Text #7', 'athemes-addons-elementor' ),
						'cell_span' => 1,
						'cell_row_span' => 1,
						'cell_header' => 'no',
					],
					[
						'element_type' => 'column',
						'cell_content_type' => 'text',
						'cell_text' => __( 'Text #8', 'athemes-addons-elementor' ),
						'cell_span' => 1,
						'cell_row_span' => 1,
						'cell_header' => 'no',
					],              
				],              
				'title_field' => '{{{ element_type === "column" ? "Column: " + cell_text : "Row" }}}',
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
			'highlight_mode',
			[
				'label' => __( 'Highlight Mode', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none'          => __( 'None', 'athemes-addons-elementor' ),
					'row'           => __( 'Row', 'athemes-addons-elementor' ),
					'rowcolumn'     => __( 'Row &amp; column', 'athemes-addons-elementor' ),
					'cell'          => __( 'Cell', 'athemes-addons-elementor' ),
				],
				'default' => 'row',
				'prefix_class' => 'aafe-table-highlight-',
			]
		);

		$this->add_control(
			'enable_sorting',
			[
				'label' => __( 'Enable Sorting', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'athemes-addons-elementor' ),
				'label_off' => __( 'No', 'athemes-addons-elementor' ),
				'return_value' => 'yes',
				'default' => 'no',
				'show_label' => true,
			]
		);

		$this->add_control(
			'enable_search',
			[
				'label' => __( 'Enable Search', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'athemes-addons-elementor' ),
				'label_off' => __( 'No', 'athemes-addons-elementor' ),
				'return_value' => 'yes',
				'default' => 'no',
				'show_label' => true,
			]
		);

		$this->add_control(
			'enable_pagination',
			[
				'label' => __( 'Enable Pagination', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'athemes-addons-elementor' ),
				'label_off' => __( 'No', 'athemes-addons-elementor' ),
				'return_value' => 'yes',
				'default' => 'no',
				'show_label' => true,
			]
		);

		$this->add_control(
			'rows_per_page',
			[
				'label' => __( 'Rows per Page', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 10,
				'condition' => [
					'enable_pagination' => 'yes',
				],
			]
		);


		$this->add_responsive_control(
			'pagination_alignment',
			[
				'label' => __( 'Pagination Alignment', 'athemes-addons-elementor' ),
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
				'condition' => [
					'enable_pagination' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .aafe-table-pagination' => 'text-align: {{VALUE}};',
				],
			]
		);


		$this->end_controls_section();

		$this->start_controls_section(
			'section_responsive',
			[
				'label' => __( 'Responsive', 'athemes-addons-elementor' ),
			]
		);

		$this->add_control(
			'enable_responsive',
			[
				'label' => __( 'Enable horizontal table scroll', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'athemes-addons-elementor' ),
				'label_off' => __( 'No', 'athemes-addons-elementor' ),
				'return_value' => 'yes',
				'default' => 'no',
				'show_label' => true,
				'prefix_class' => 'aafe-table-responsive-',
			]
		);

		$this->add_responsive_control(
			'width',
			[
				'label' => __( 'Width', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'%' => [
						'min' => 10,
						'max' => 100,
					],
					'px' => [
						'min' => 100,
						'max' => 1500,
					],
				],
				'condition' => [
					'enable_responsive' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-table-wrapper table' => 'width: {{SIZE}}{{UNIT}};',
				],
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
					'{{WRAPPER}} .athemes-addons-table' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .athemes-addons-table' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],              
			]
		);

		$this->add_control(
			'wrapper_background_color',
			[
				'label' => __( 'Background Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-table' => 'background-color: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'wrapper_border',
				'label'     => esc_html__( 'Border', 'athemes-addons-elementor' ),
				'selector'  => '{{WRAPPER}} .athemes-addons-table',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'wrapper_border_radius',
			[
				'label' => __( 'Border Radius', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,             
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-table' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],              
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'wrapper_box_shadow',
				'label' => __( 'Box Shadow', 'athemes-addons-elementor' ),
				'selector' => '{{WRAPPER}} .athemes-addons-table',
			]
		);

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
					'{{WRAPPER}} .athemes-addons-table' => 'max-width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_table_style',
			[
				'label' => __( 'Table', 'athemes-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);      

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'table_border',
				'label'     => esc_html__( 'Border', 'athemes-addons-elementor' ),
				'selector'  => '{{WRAPPER}} .athemes-addons-table table',
			]
		);

		$this->add_responsive_control(
			'table_border_radius',
			[
				'label' => __( 'Border Radius', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,             
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-table table' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .athemes-addons-table table tr:last-of-type td:first-child' => 'border-radius: 0 0 0 {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .athemes-addons-table table tr:last-of-type td:last-child' => 'border-radius: 0 0 {{BOTTOM}}{{UNIT}} 0;',
				],
			]
		);

		$this->add_control(
			'table_border_collapse',
			[
				'label' => __( 'Border Collapse', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'collapse'  => __( 'Collapse', 'athemes-addons-elementor' ),
					'separate'  => __( 'Separate', 'athemes-addons-elementor' ),
				],
				'default' => 'collapse',
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-table table' => 'border-collapse: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_table_header_style',
			[
				'label' => __( 'Header', 'athemes-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'header_padding',
			[
				'label' => __( 'Padding', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-table th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],              
			]
		);

		$this->add_responsive_control(
			'header_border_radius',
			[
				'label' => __( 'Border Radius', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,             
				'selectors' => [
					'{{WRAPPER}} .aafe-table-header' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} 0 0;',
					'{{WRAPPER}} .aafe-table-header tr' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} 0 0;',
					'{{WRAPPER}} .aafe-table-header th:first-child' => 'border-radius: {{TOP}}{{UNIT}} 0 0 0;',
					'{{WRAPPER}} .aafe-table-header th:last-child' => 'border-radius: 0 {{RIGHT}}{{UNIT}} 0 0;',
				],
				'separator' => 'after',            
			]
		);

		$this->add_responsive_control(
			'header_alignment',
			[
				'label' => __( 'Alignment', 'athemes-addons-elementor' ),
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
					'left' => 'flex-start',
					'center' => 'center',
					'right' => 'flex-end',
				],
				'condition' => [
					'source' => 'manual',
				],
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-table th span:not(.aafe-th-icon)' => 'justify-content: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'header_alignment_csv',
			[
				'label' => __( 'Alignment', 'athemes-addons-elementor' ),
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
				'condition' => [
					'source' => 'csv',
				],
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-table th' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'header_typography',
				'label' => __( 'Typography', 'athemes-addons-elementor' ),
				'selector' => '{{WRAPPER}} .aafe-table-header',
			]
		);

		$this->add_responsive_control(
			'header_icon_size',
			[
				'label' => __( 'Icon Size', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem' ],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .aafe-table-header .elementor-icon' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .athemes-addons-table th svg' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .athemes-addons-table th img' => 'max-width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'header_icon_position',
			[
				'label' => __( 'Icon Position', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'before'    => __( 'Before', 'athemes-addons-elementor' ),
					'after'     => __( 'After', 'athemes-addons-elementor' ),
					'top'       => __( 'Top', 'athemes-addons-elementor' ),
				],
				'default' => 'before',
			]
		);
		

		$this->start_controls_tabs( 'header_style' );

		$this->start_controls_tab(
			'header_normal',
			[
				'label' => __( 'Normal', 'athemes-addons-elementor' ),
			]
		);

		$this->add_control(
			'header_color',
			[
				'label' => __( 'Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .aafe-table-header' => 'color: {{VALUE}};',
					'{{WRAPPER}} .aafe-table-header svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'header_background_color',
			[
				'label' => __( 'Background Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .aafe-table-header' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'header_border',
				'label'     => esc_html__( 'Border', 'athemes-addons-elementor' ),
				'selector'  => '{{WRAPPER}} .athemes-addons-table th',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'header_hover',
			[
				'label' => __( 'Hover', 'athemes-addons-elementor' ),
			]
		);

		$this->add_control(
			'header_hover_color',
			[
				'label' => __( 'Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .aafe-table-header:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .aafe-table-header:hover .aafe-table-sorting th:before' => 'border-bottom-color: {{VALUE}};',
					'{{WRAPPER}} .aafe-table-header:hover .aafe-table-sorting th:after' => 'border-top-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'header_hover_background_color',
			[
				'label' => __( 'Background Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .aafe-table-header:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'header_hover_border',
			[
				'label' => __( 'Border Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-table th' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_table_content_style',
			[
				'label' => __( 'Content', 'athemes-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'content_padding',
			[
				'label' => __( 'Padding', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-table td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],              
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'content_border',
				'label'     => esc_html__( 'Border', 'athemes-addons-elementor' ),
				'selector'  => '{{WRAPPER}} .athemes-addons-table td',
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography',
				'label' => __( 'Typography', 'athemes-addons-elementor' ),
				'selector' => '{{WRAPPER}} .athemes-addons-table td',
			]
		);

		$this->add_control(
			'content_icon_color',
			[
				'label' => __( 'Icon Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-table td svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->start_controls_tabs( 'odd_even' );

		$this->start_controls_tab(
			'odd',
			[
				'label' => __( 'Odd', 'athemes-addons-elementor' ),
			]
		);

		$this->add_control(
			'odd_color',
			[
				'label' => __( 'Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-table tr:nth-child(odd) td' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'odd_background_color',
			[
				'label' => __( 'Background Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-table tr:nth-child(odd) td' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'even',
			[
				'label' => __( 'Even', 'athemes-addons-elementor' ),
			]
		);

		$this->add_control(
			'even_color',
			[
				'label' => __( 'Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-table tr:nth-child(even) td' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'even_background_color',
			[
				'label' => __( 'Background Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-table tr:nth-child(even) td' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'hover',
			[
				'label' => __( 'Hover', 'athemes-addons-elementor' ),
			]
		);

		$this->add_control(
			'hover_color',
			[
				'label' => __( 'Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-table td:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'hover_background_color',
			[
				'label' => __( 'Background Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-table td:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'content_alignment',
			[
				'label' => __( 'Alignment', 'athemes-addons-elementor' ),
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
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-table td' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_pagination_style',
			[
				'label' => __( 'Pagination', 'athemes-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [
					'enable_pagination' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'pagination_padding',
			[
				'label' => __( 'Padding', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .aafe-table-pagination span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],              
			]
		);

		$this->add_responsive_control(
			'pagination_spacing',
			[
				'label' => __( 'Spacing', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 20,
					],
				],              
				'selectors' => [
					'{{WRAPPER}} .aafe-table-pagination span' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'pagination_style' );

		$this->start_controls_tab(
			'pagination_normal',
			[
				'label' => __( 'Normal', 'athemes-addons-elementor' ),
			]
		);

		$this->add_control(
			'pagination_color',
			[
				'label' => __( 'Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .aafe-table-pagination span' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'pagination_background_color',
			[
				'label' => __( 'Background Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .aafe-table-pagination span' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'pagination_border_color',
			[
				'label' => __( 'Border Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .aafe-table-pagination span' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'pagination_hover',
			[
				'label' => __( 'Hover', 'athemes-addons-elementor' ),
			]
		);

		$this->add_control(
			'pagination_hover_color',
			[
				'label' => __( 'Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .aafe-table-pagination span:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'pagination_hover_background_color',
			[
				'label' => __( 'Background Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .aafe-table-pagination span:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'pagination_hover_border_color',
			[
				'label' => __( 'Border Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .aafe-table-pagination span:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'pagination_active',
			[
				'label' => __( 'Active', 'athemes-addons-elementor' ),
			]
		);

		$this->add_control(
			'pagination_active_color',
			[
				'label' => __( 'Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .aafe-table-pagination span.active' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'pagination_active_background_color',
			[
				'label' => __( 'Background Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .aafe-table-pagination span.active' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'pagination_active_border_color',
			[
				'label' => __( 'Border Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .aafe-table-pagination span.active' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'pagination_border_radius',
			[
				'label' => __( 'Border Radius', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,             
				'selectors' => [
					'{{WRAPPER}} .aafe-table-pagination span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
		
		$this->add_render_attribute( 'table', 'class', 'athemes-addons-table' );

		if ( 'yes' === $settings['enable_pagination'] ) {
			$perPage     = $settings['rows_per_page'];
			$currentPage = 1;
			$totalItems  = count( array_filter( $settings['table_content'], function( $content ) {
				return 'row' === $content['element_type'];
			} ) );
			$totalPages  = ceil( $totalItems / $perPage );

			$this->add_render_attribute( 'table', 'data-per-page', $perPage );
			$this->add_render_attribute( 'table', 'data-current-page', $currentPage );
			$this->add_render_attribute( 'table', 'data-total-pages', $totalPages );
		}
		?>

		<div <?php $this->print_render_attribute_string( 'table' ); ?> >
			<?php if ( 'yes' === $settings['enable_search'] ) : ?>
			<div class="aafe-table-search">
				<input type="search" class="aafe-table-search-input" placeholder="<?php esc_attr_e( 'Search...', 'athemes-addons-elementor' ); ?>">
			</div>
			<?php endif; ?>
			<?php if ( 'manual' === $settings['source'] ) : ?>
				<div class="athemes-addons-table-wrapper">
				<table>
					<?php
						$this->render_table_header();
						$this->render_table_content();
					?>
				</table>
				</div>
				<?php if ( 'yes' === $settings['enable_pagination'] ) : ?>
					<?php $this->render_pagination( $totalPages ); ?>
				<?php endif; ?>
			<?php else : ?>
				<?php $this->render_csv_table(); ?>
			<?php endif; ?>	
		</div>
		<?php
	}

	protected function render_table_header() {
		$settings = $this->get_settings_for_display();

		if ( 'yes' === $settings['disable_header'] ) {
			return;
		}

		$columns = [];
		$colspan = 0;

		foreach ( $settings['table_header'] as $header ) {
			$columns[] = $header['header_name'];
			$colspan += $header['header_span'];
		}

		$this->add_render_attribute( 'table-header', 'class', 'aafe-table-header' );
		
		if ( isset( $settings['enable_sorting'] ) && 'yes' === $settings['enable_sorting'] ) {
			$this->add_render_attribute( 'table-header', 'class', 'aafe-table-sorting' );       
		}

		$this->add_render_attribute( 'table-header', 'class', 'aafe-table-header-' . esc_attr( $settings['header_icon_position'] ) );
		?>
		<thead <?php $this->print_render_attribute_string( 'table-header' ); ?>>
			<tr class="aafe-table-row aafe-table-header-row">
				<?php
				foreach ( $settings['table_header'] as $header ) {
					?>
					<th class="aafe-table-column" colspan="<?php echo esc_attr( $header['header_span'] ); ?>">
						<span>
						<?php
						if ( 'yes' === $header['header_icon_switcher'] ) {
							echo '<span class="aafe-th-icon">';
							if ( 'icon' === $header['header_icon_type'] ) {
								Icons_Manager::render_icon( $header['header_icon'], [ 'aria-hidden' => 'true' ] );
							} elseif ( 'image' === $header['header_icon_type'] ) {
								echo wp_get_attachment_image( $header['header_image']['id'], 'thumbnail' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							}
							echo '</span>';
						}
						?>
						<?php echo esc_html( $header['header_name'] ); ?>
						</span>
					</th>
					<?php
				}
				?>
			</tr>
		</thead>
		<?php
	}

	protected function render_table_content() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'table-content', 'class', 'aafe-table-content' );
	
		?>
		<tbody <?php $this->print_render_attribute_string( 'table-content' ); ?>>
			<?php
			$row = 0;
			$col = 0;
			$columns = [];
			$colspan = 0;

			foreach ( $settings['table_header'] as $header ) {
				$columns[] = $header['header_name'];
				$colspan += $header['header_span'];
			}

			foreach ( $settings['table_content'] as $index => $content ) {
				if ( 'row' === $content['element_type'] ) {
					if ( $row > 0 ) {
						?>
						</tr>
						<?php
					}
					$this->add_render_attribute( 'table-row-' . $index, 'class', 'aafe-table-row' );

					if ( 'yes' === $settings['enable_pagination'] ) {
						
						if ( $row >= $settings['rows_per_page'] ) {
							$this->add_render_attribute( 'table-row-' . $index, 'class', 'aafe-hidden-row' );
						}
						
						$this->add_render_attribute( 'table-row-' . $index, 'data-page', ceil( ( $row + 1 ) / $settings['rows_per_page'] ) );
					}

					?>
					<tr <?php $this->print_render_attribute_string( 'table-row-' . $index ); ?>>
					<?php
					$row++;
					$col = 0;
				}

				$colspan = 0;
				foreach ( $settings['table_header'] as $header ) {
					$colspan += $header['header_span'];
				}

				$this->add_render_attribute( 'cell_' . $index, 'class', 'aafe-table-cell elementor-repeater-item-' . $content['_id'] );

				if ( 'column' === $content['element_type'] ) {

					$cell_tag = 'td';
					if ( 'yes' === $content['cell_header'] ) {
						$cell_tag = 'th';
					}
					?>
					<<?php echo esc_attr( $cell_tag ); ?> <?php $this->print_render_attribute_string( 'cell_' . $index ); ?> colspan="<?php echo esc_attr( $content['cell_span'] ); ?>" rowspan="<?php echo esc_attr( $content['cell_row_span'] ); ?>">
						<?php
						if ( 'text' === $content['cell_content_type'] ) {
							echo esc_html( $content['cell_text'] );
						} elseif ( 'icon' === $content['cell_content_type'] ) {
							Icons_Manager::render_icon( $content['cell_icon'], [ 'aria-hidden' => 'true' ] );
						} elseif ( 'image' === $content['cell_content_type'] ) {
							echo wp_get_attachment_image( $content['cell_image']['id'], 'thumbnail' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						} elseif ( 'wywiwyg' === $content['cell_content_type'] ) {
							echo wp_kses_post( $content['cell_wysiwyg'] );
						} elseif ( 'template' === $content['cell_content_type'] ) {
							echo Plugin::instance()->frontend->get_builder_content_for_display( $content['cell_template'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						}
						?>
					</<?php echo esc_attr( $cell_tag ); ?>>
					<?php
					$col++;
				}
			}
			?>
			</tr>
		</tbody>
		<?php
	}

	/**
	 * Render pagination
	 */
	protected function render_pagination( $totalPages ) {
		?>
			<div class="aafe-table-pagination">
				<?php for ( $i = 1; $i <= $totalPages; $i++ ) : ?>
					<span class="aafe-table-pagination-button <?php echo ( $i === 1 ) ? 'active' : ''; ?>" data-page="<?php echo esc_attr( $i ); ?>"><?php echo esc_html( $i ); ?></span>
				<?php endfor; ?>
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

	/**
	 * Render CSV table
	 */
	protected function render_csv_table() {
		$settings = $this->get_settings_for_display();
	
		$csv = $settings['csv_file']['url'];
		if ( empty( $csv ) ) {
			echo '<p>' . esc_html__( 'Please select a CSV file.', 'athemes-addons-elementor' ) . '</p>';
			return;
		}
	
		$delimiter = $settings['csv_delimiter'];
		if ( empty( $delimiter ) ) {
			$delimiter = ',';
		}
	
		$header = 'yes' === $settings['csv_header'];
	
		ob_start();
		
		echo '<div class="athemes-addons-table-wrapper">';
		echo '<table>';

		if ( ( $handle = fopen($csv, 'r') ) !== false) { // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fopen
			$rowIndex = 0;
			while ( ( $row = fgetcsv($handle, 1000, $delimiter ) ) !== false) {
				if ( $header && $rowIndex === 0) {

					$this->add_render_attribute( 'table-header', 'class', 'aafe-table-header' );

					if ( isset( $settings['enable_sorting'] ) && 'yes' === $settings['enable_sorting'] ) {
						$this->add_render_attribute( 'table-header', 'class', 'aafe-table-sorting' );       
					}
					?>

					<thead <?php $this->print_render_attribute_string( 'table-header' ); ?>><tr class="aafe-table-row aafe-table-header-row">
					<?php
					foreach ( $row as $headerCell ) {
						echo '<th class="aafe-table-column">' . esc_html( $headerCell ) . '</th>';
					}
					?>
					</tr></thead>
					<?php
				} else {
					$this->add_render_attribute( 'table-row-' . $rowIndex, 'class', 'aafe-table-row' );

					if ( 'yes' === $settings['enable_pagination'] ) {
						
						if ( $rowIndex - 1 >= $settings['rows_per_page'] ) {
							$this->add_render_attribute( 'table-row-' . $rowIndex, 'class', 'aafe-hidden-row' );
						}
						
						$this->add_render_attribute( 'table-row-' . $rowIndex, 'data-page', ceil( ( ( $header ) ? $rowIndex : $rowIndex + 1 ) / $settings['rows_per_page'] ) );
					}

					?>
					<tr <?php $this->print_render_attribute_string( 'table-row-' . esc_attr( $rowIndex ) ); ?>>
					<?php
					foreach ( $row as $cell ) {
						echo '<td class="aafe-table-cell">' . esc_html( $cell ) . '</td>';
					}
					?>
					</tr>
					<?php
				}
				$rowIndex++;
			}
			fclose( $handle ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fclose
		} else {
			echo '<p>' . esc_html__( 'Failed to open CSV file.', 'athemes-addons-elementor' ) . '</p>';
		}

		echo '</table>';
		echo '</div>';

		if ( 'yes' === $settings['enable_pagination'] ) {
			$totalItems  = $rowIndex;
			if ( $header ) {
				$totalItems = $rowIndex - 1;
			}
			$perPage     = $settings['rows_per_page'];
			$currentPage = 1;
			$totalPages  = ceil( $totalItems / $perPage );
			
			$this->render_pagination( $totalPages );
		}
	
		$tableContent = ob_get_clean();
	
		echo wp_kses_post( $tableContent );
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
Plugin::instance()->widgets_manager->register( new Table() );