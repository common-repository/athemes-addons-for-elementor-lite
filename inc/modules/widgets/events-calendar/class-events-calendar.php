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
class Events_Calendar extends Widget_Base {

	public function __construct($data = [], $args = null) {
		parent::__construct($data, $args);

		wp_register_script( 'athemes-addons-fullcalendar-js', ATHEMES_AFE_URI . 'assets/js/vendor/fullcalendar.min.js', array( 'elementor-frontend' ), ATHEMES_AFE_VERSION, true );
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
		return 'athemes-addons-events-calendar';
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
		return __( 'Google Calendar', 'athemes-addons-elementor' );
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
		return 'eicon-calendar	aafe-elementor-icon';
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
		return [ 'athemes-addons-fullcalendar-js', $this->get_name() . '-scripts' ];
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
		return [ 'calendar', 'google', 'events', 'events calendar', 'athemes', 'addons', 'athemes addons' ];
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
			'section_data',
			[
				'label' => __( 'Calendar data', 'athemes-addons-elementor' ),
			]
		);

		$this->add_control(
			'google_api_key',
			[
				'label'         => __( 'Google API Key', 'athemes-addons-elementor' ),
				'type'          => Controls_Manager::TEXT,
				'description' => sprintf(
					/* translators: %s: link to the documentation */
					__( 'Learn how to obtain your API key %s.', 'athemes-addons-elementor' ),
					sprintf('<a href="%s" target="_blank">%s</a>', 'https://docs.athemes.com/calendar-id-api-key/#key', __('here', 'athemes-addons-elementor'))
				),
			]
		);

		$this->add_control(
			'calendar_id',
			[
				'label'         => __( 'Calendar ID', 'athemes-addons-elementor' ),
				'type'          => Controls_Manager::TEXT,
				'description' => sprintf(
					/* translators: %s: link to the documentation */
					__( 'Learn how to obtain your Calendar ID %s.', 'athemes-addons-elementor' ),
					sprintf('<a href="%s" target="_blank">%s</a>', 'https://docs.athemes.com/calendar-id-api-key/#id', __('here', 'athemes-addons-elementor'))
				),
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
            'locale',
            [
                'label' => __( 'Language', 'athemes-addons-elementor' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'af' => 'Afrikaans',
                    'sq' => 'Albanian',
                    'hy-am' => 'Armenian',
                    'ar' => 'Arabic',
                    'az' => 'Azerbaijani',
                    'eu' => 'Basque',
                    'bn' => 'Bengali',
                    'bs' => 'Bosnian',
                    'bg' => 'Bulgarian',
                    'ca' => 'Catalan',
                    'zh-cn' => 'Chinese',
                    'zh-tw' => 'Chinese-tw',
                    'hr' => 'Croatian',
                    'cs' => 'Czech',
                    'da' => 'Danish',
                    'nl' => 'Dutch',
                    'en' => 'English',
                    'et' => 'Estonian',
                    'fi' => 'Finnish',
                    'fr' => 'French',
                    'gl' => 'Galician',
                    'ka' => 'Georgian',
                    'de' => 'German',
                    'el' => 'Greek',
                    'he' => 'Hebrew',
                    'hi' => 'Hindi',
                    'hu' => 'Hungarian',
                    'is' => 'Icelandic',
                    'io' => 'Ido',
                    'id' => 'Indonesian',
                    'it' => 'Italian',
                    'ja' => 'Japanese',
                    'kk' => 'Kazakh',
                    'ko' => 'Korean',
                    'lv' => 'Latvian',
                    'lb' => 'Letzeburgesch',
                    'lt' => 'Lithuanian',
                    'lu' => 'Luba-Katanga',
                    'mk' => 'Macedonian',
                    'mg' => 'Malagasy',
                    'ms' => 'Malay',
                    'ro' => 'Moldovan, Moldavian, Romanian',
                    'nb' => 'Norwegian Bokmål',
                    'nn' => 'Norwegian Nynorsk',
                    'fa' => 'Persian',
                    'pl' => 'Polish',
                    'pt' => 'Portuguese',
                    'ru' => 'Russian',
                    'sr' => 'Serbian',
                    'sk' => 'Slovak',
                    'sl' => 'Slovenian',
                    'es' => 'Spanish',
                    'sv' => 'Swedish',
                    'tr' => 'Turkish',
                    'uk' => 'Ukrainian',
                    'vi' => 'Vietnamese',
                ],
                'default' => 'en',
            ]
        );      

		$this->add_control(
			'initial_view',
			[
				'label'     => __( 'Initial View', 'athemes-addons-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'dayGridMonth',
				'options'   => [
                    'timeGridDay'   => __('Day', 'athemes-addons-elementor'),
                    'timeGridWeek'  => __('Week', 'athemes-addons-elementor'),
                    'dayGridMonth'  => __('Month', 'athemes-addons-elementor'),
                    'listMonth'     => __('List', 'athemes-addons-elementor'),
				],
			]
		);

		$this->add_control(
			'format_24',
			[
				'label'         => __( '24-hour Time Format', 'athemes-addons-elementor' ),
				'type'          => Controls_Manager::SWITCHER,
				'label_on'      => __( 'Yes', 'athemes-addons-elementor' ),
				'label_off'     => __( 'No', 'athemes-addons-elementor' ),
				'return_value'  => 'yes',
			]
		);

		$this->add_control(
			'first_day',
			[
				'label'     => __( 'First Day of the Week', 'athemes-addons-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '1',
				'options'   => [
					'0' => __( 'Sunday', 'athemes-addons-elementor' ),
					'1' => __( 'Monday', 'athemes-addons-elementor' ),
					'2' => __( 'Tuesday', 'athemes-addons-elementor' ),
					'3' => __( 'Wednesday', 'athemes-addons-elementor' ),
					'4' => __( 'Thursday', 'athemes-addons-elementor' ),
					'5' => __( 'Friday', 'athemes-addons-elementor' ),
					'6' => __( 'Saturday', 'athemes-addons-elementor' ),
				],
			]
		);

		$this->add_control(
			'hide_past_events',
			[
				'label'         => __( 'Hide Past Events', 'athemes-addons-elementor' ),
				'type'          => Controls_Manager::SWITCHER,
				'label_on'      => __( 'Yes', 'athemes-addons-elementor' ),
				'label_off'     => __( 'No', 'athemes-addons-elementor' ),
				'return_value'  => 'yes',
				'selectors_dictionary' => [
					'yes' => 'display: none;',
				],
				'selectors' => [
					'{{WRAPPER}} .aafe-events-calendar .fc-event-past' => '{{VALUE}}',
				],
			]
		);

		$this->add_control(
			'hide_link',
			[
				'label'         => __( 'Hide Event Link', 'athemes-addons-elementor' ),
				'type'          => Controls_Manager::SWITCHER,
				'label_on'      => __( 'Yes', 'athemes-addons-elementor' ),
				'label_off'     => __( 'No', 'athemes-addons-elementor' ),
				'return_value'  => 'yes',
				'selectors_dictionary' => [
					'yes' => 'display: none;',
				],
				'selectors' => [
					'{{WRAPPER}} .aafe-events-calendar .modal-footer' => '{{VALUE}}',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			[
				'label' => __( 'Calendar', 'athemes-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'calendar_padding',
			[
				'label' => __( 'Padding', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .aafe-events-calendar' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'calendar_background_color',
			[
				'label'     => __( 'Background Color', 'athemes-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .aafe-events-calendar' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'calendar_text_color',
			[
				'label'     => __( 'Text Color', 'athemes-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .aafe-events-calendar' => '--fc-text-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'calendar_border',
				'selector'  => '{{WRAPPER}} .aafe-events-calendar',
			]
		);
		
		$this->add_control(
			'calendar_border_radius',
			[
				'label'     => __( 'Border Radius', 'athemes-addons-elementor' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .aafe-events-calendar' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'      => 'calendar_box_shadow',
				'selector'  => '{{WRAPPER}} .aafe-events-calendar',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_toolbar_style',
			[
				'label' => __( 'Toolbar', 'athemes-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'toolbar_heading',
			[
				'label' => __( 'Title', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => __( 'Color', 'athemes-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .fc-toolbar-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'title_typography',
				'selector'  => '{{WRAPPER}} .fc-toolbar-title',
			]
		);

		$this->add_control(
			'button_heading',
			[
				'label' => __( 'Buttons', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label'     => __( 'Border Radius', 'athemes-addons-elementor' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .fc-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'button_padding',
			[
				'label' => __( 'Padding', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .fc-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'button_typography',
				'selector'  => '{{WRAPPER}} .fc-button',
			]
		);      

		$this->start_controls_tabs( 'tabs_toolbar_buttons' );

		$this->start_controls_tab(
			'tab_toolbar_buttons_normal',
			[
				'label' => __( 'Normal', 'athemes-addons-elementor' ),
			]
		);

		$this->add_control(
			'button_color',
			[
				'label'     => __( 'Color', 'athemes-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .fc-button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_background_color',
			[
				'label'     => __( 'Background Color', 'athemes-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .fc-button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_toolbar_buttons_hover',
			[
				'label' => __( 'Hover', 'athemes-addons-elementor' ),
			]
		);

		$this->add_control(
			'button_hover_color',
			[
				'label'     => __( 'Color', 'athemes-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .fc-button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_background_color',
			[
				'label'     => __( 'Background Color', 'athemes-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .fc-button:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_toolbar_buttons_active',
			[
				'label' => __( 'Active', 'athemes-addons-elementor' ),
			]
		);

		$this->add_control(
			'button_active_color',
			[
				'label'     => __( 'Color', 'athemes-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .fc-button.fc-button-active' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_active_background_color',
			[
				'label'     => __( 'Background Color', 'athemes-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .fc-button.fc-button-active' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_table_header_style',
			[
				'label' => __( 'Table Header', 'athemes-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'table_header_padding',
			[
				'label' => __( 'Padding', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .fc-col-header-cell' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'table_header_typography',
				'selector'  => '{{WRAPPER}} .fc-col-header-cell',
			]
		);

		$this->add_control(
			'table_header_color',
			[
				'label'     => __( 'Color', 'athemes-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .fc-col-header-cell,{{WRAPPER}} .fc-col-header-cell a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'table_header_background_color',
			[
				'label'     => __( 'Background Color', 'athemes-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .fc-col-header-cell' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'table_header_text_align',
			[
				'label' => __( 'Text Align', 'athemes-addons-elementor' ),
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
				'selectors' => [
					'{{WRAPPER}} .fc-col-header-cell' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_table_cell_style',
			[
				'label' => __( 'Table Cell', 'athemes-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'table_cell_padding',
			[
				'label' => __( 'Padding', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .fc-daygrid-day' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'table_cell_typography',
				'selector'  => '{{WRAPPER}} .fc-daygrid-day',
			]
		);

		$this->add_control(
			'table_cell_color',
			[
				'label'     => __( 'Color', 'athemes-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .fc-daygrid-day-top:not(.fc-day-today) a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'table_cell_today_color',
			[
				'label'     => __( 'Today Color', 'athemes-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .fc-day-today .fc-daygrid-day-top a' => 'color: {{VALUE}};',
				],
			]
		);  

		$this->add_control(
			'table_cell_background_color',
			[
				'label'     => __( 'Background Color', 'athemes-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .fc-daygrid-day:not(.fc-day-today)' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'table_cell_today_bg_color',
			[
				'label'     => __( 'Today Background', 'athemes-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .fc-day-today' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_event_style',
			[
				'label' => __( 'Event', 'athemes-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'event_title_typography',
				'selector'  => '{{WRAPPER}} .fc-event-title',
			]
		);

		$this->add_responsive_control(
			'event_padding',
			[
				'label' => __( 'Padding', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .fc-event' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'event_border_radius',
			[
				'label'     => __( 'Border Radius', 'athemes-addons-elementor' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .fc-event' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_event' );

		$this->start_controls_tab(
			'tab_event_normal',
			[
				'label' => __( 'Normal', 'athemes-addons-elementor' ),
			]
		);

		$this->add_control(
			'event_color',
			[
				'label'     => __( 'Color', 'athemes-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .fc-event,{{WRAPPER}} .fc-h-event .fc-event-main' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'event_background_color',
			[
				'label'     => __( 'Background Color', 'athemes-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .fc-event' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_event_hover',
			[
				'label' => __( 'Hover', 'athemes-addons-elementor' ),
			]
		);

		$this->add_control(
			'event_hover_color',
			[
				'label'     => __( 'Color', 'athemes-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .fc-event:hover,{{WRAPPER}} .fc-event:hover .fc-event-main' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'event_hover_background_color',
			[
				'label'     => __( 'Background Color', 'athemes-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .fc-event:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();
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

		$this->add_render_attribute( 'wrapper', 'class', 'athemes-addons-events-calendar' );

		$this->add_render_attribute( 'calendar', 'id', 'aafe-events-calendar' );
		$this->add_render_attribute( 'calendar', 'class', 'aafe-events-calendar' );
		$this->add_render_attribute( 'calendar', 'data-initial-view', esc_attr( $settings['initial_view'] ) );
		$this->add_render_attribute( 'calendar', 'data-api-key', esc_attr( $settings['google_api_key'] ) );
		$this->add_render_attribute( 'calendar', 'data-calendar-id', esc_attr( $settings['calendar_id'] ) );
		$this->add_render_attribute( 'calendar', 'data-format-24', esc_attr( $settings['format_24'] ) );
		$this->add_render_attribute( 'calendar', 'data-locale', esc_attr( $settings['locale'] ) );
		$this->add_render_attribute( 'calendar', 'data-first-day', esc_attr( $settings['first_day'] ) );
		?>

		<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
			<div <?php $this->print_render_attribute_string( 'calendar' ); ?>></div>
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
Plugin::instance()->widgets_manager->register( new Events_Calendar() );