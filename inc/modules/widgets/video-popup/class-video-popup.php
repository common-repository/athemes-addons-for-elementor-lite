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
use Elementor\Modules\DynamicTags\Module as TagsModule;
use Elementor\Group_Control_Css_Filter;
use Elementor\Embed;
use Elementor\Icons_Manager;

use aThemes_Addons\Traits\Button_Trait;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor widget.
 *
 * Elementor widget that displays a bullet list with any chosen icons and texts.
 *
 * @since 1.0.0
 */
class Video_Popup extends Widget_Base {

	use Button_Trait;

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
		return 'athemes-addons-video-popup';
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
		return __( 'Video Popup', 'athemes-addons-elementor' );
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
		return 'eicon-youtube aafe-elementor-icon';
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
		return [ 'video', 'popup', 'youtube', 'vimeo', 'embed', 'video popup', 'athemes', 'addons', 'athemes addons' ];
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the widget belongs to.
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
	 * Register widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'section_trigger',
			[
				'label' => esc_html__( 'Trigger', 'athemes-addons-elementor' ),
			]
		);

		$this->add_control(
			'trigger',
			[
				'label' => esc_html__( 'Trigger', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'text'      => esc_html__( 'Text', 'athemes-addons-elementor' ),
					'icon'      => esc_html__( 'Icon', 'athemes-addons-elementor' ),
					'text_icon' => esc_html__( 'Text & Icon', 'athemes-addons-elementor' ),
				],
				'default' => 'icon',
			]
		);

		$this->add_control(
			'text',
			[
				'label' => esc_html__( 'Text', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Play Video', 'athemes-addons-elementor' ),
				'condition' => [
					'trigger' => [ 'text', 'text_icon' ],
				],
			]
		);
		
		$this->add_control(
			'icon',
			[
				'label' => esc_html__( 'Select Icon', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'default' => [
					'value' => 'fas fa-play',
					'library' => 'fa-solid',
				],
				'recommended' => [
					'fa-regular' => [
						'play-circle',
					],
					'fa-solid' => [
						'play',
						'play-circle',
					],
				],
				'condition' => [
					'trigger' => [ 'icon', 'text_icon' ],
				],
			]
		);

		$this->add_control(
			'separate_lines',
			[
				'label' => esc_html__( 'Separate lines?', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'return_value' => 'yes',
				'condition' => [
					'trigger' => 'text_icon',
				],
				'prefix_class' => 'aafe-custom-embed-separate-lines-',
			]
		);

		$this->add_control(
			'trigger_style',
			[
				'label' => esc_html__( 'Trigger Style', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'button'    => esc_html__( 'Button', 'athemes-addons-elementor' ),
					'link'      => esc_html__( 'Link', 'athemes-addons-elementor' ),
				],
				'default' => 'button',
				'separator' => 'after',
			]
		);

		$this->add_control(
			'glow',
			[
				'label' => esc_html__( 'Glow', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'prefix_class' => 'aafe-custom-embed-glow-',
				'condition' => [
					'trigger_style' => 'button',
				],
			]
		);

		$this->add_control(
			'align',
			[
				'label' => esc_html__( 'Alignment', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'athemes-addons-elementor' ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'athemes-addons-elementor' ),
						'icon' => 'eicon-h-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'athemes-addons-elementor' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'default' => 'center',
				'toggle' => false,
				'condition' => [
					'trigger' => [ 'text', 'icon', 'text_icon' ],
				],
				'selectors' => [
					'{{WRAPPER}} .athemes-addons-video-popup' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_video',
			[
				'label' => esc_html__( 'Video', 'athemes-addons-elementor' ),
			]
		);

		$this->add_control(
			'video_type',
			[
				'label' => esc_html__( 'Source', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'youtube',
				'options' => [
					'youtube' => esc_html__( 'YouTube', 'athemes-addons-elementor' ),
					'vimeo' => esc_html__( 'Vimeo', 'athemes-addons-elementor' ),
					'dailymotion' => esc_html__( 'Dailymotion', 'athemes-addons-elementor' ),
					'videopress' => esc_html__( 'VideoPress', 'athemes-addons-elementor' ),
					'hosted' => esc_html__( 'Self Hosted', 'athemes-addons-elementor' ),
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'youtube_url',
			[
				'label' => esc_html__( 'Link', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
					'categories' => [
						TagsModule::POST_META_CATEGORY,
						TagsModule::URL_CATEGORY,
					],
				],
				'placeholder' => esc_html__( 'Enter your URL', 'athemes-addons-elementor' ) . ' (YouTube)',
				'default' => 'https://www.youtube.com/watch?v=XHOmBV4js_E',
				'label_block' => true,
				'condition' => [
					'video_type' => 'youtube',
				],
				'ai' => [
					'active' => false,
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'vimeo_url',
			[
				'label' => esc_html__( 'Link', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
					'categories' => [
						TagsModule::POST_META_CATEGORY,
						TagsModule::URL_CATEGORY,
					],
				],
				'placeholder' => esc_html__( 'Enter your URL', 'athemes-addons-elementor' ) . ' (Vimeo)',
				'default' => 'https://vimeo.com/235215203',
				'label_block' => true,
				'condition' => [
					'video_type' => 'vimeo',
				],
				'ai' => [
					'active' => false,
				],
			]
		);

		$this->add_control(
			'dailymotion_url',
			[
				'label' => esc_html__( 'Link', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
					'categories' => [
						TagsModule::POST_META_CATEGORY,
						TagsModule::URL_CATEGORY,
					],
				],
				'placeholder' => esc_html__( 'Enter your URL', 'athemes-addons-elementor' ) . ' (Dailymotion)',
				'default' => 'https://www.dailymotion.com/video/x6tqhqb',
				'label_block' => true,
				'condition' => [
					'video_type' => 'dailymotion',
				],
				'ai' => [
					'active' => false,
				],
			]
		);

		$this->add_control(
			'insert_url',
			[
				'label' => esc_html__( 'External URL', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'video_type' => [ 'hosted', 'videopress' ],
				],
			]
		);

		$this->add_control(
			'hosted_url',
			[
				'label' => esc_html__( 'Choose Video File', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
					'categories' => [
						TagsModule::MEDIA_CATEGORY,
					],
				],
				'media_types' => [
					'video',
				],
				'condition' => [
					'video_type' => [ 'hosted', 'videopress' ],
					'insert_url' => '',
				],
			]
		);

		$this->add_control(
			'external_url',
			[
				'label' => esc_html__( 'URL', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::URL,
				'autocomplete' => false,
				'options' => false,
				'label_block' => true,
				'show_label' => false,
				'dynamic' => [
					'active' => true,
					'categories' => [
						TagsModule::POST_META_CATEGORY,
						TagsModule::URL_CATEGORY,
					],
				],
				'placeholder' => esc_html__( 'Enter your URL', 'athemes-addons-elementor' ),
				'condition' => [
					'video_type' => 'hosted',
					'insert_url' => 'yes',
				],
			]
		);

		$this->add_control(
			'videopress_url',
			[
				'label' => esc_html__( 'URL', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'show_label' => false,
				'default' => 'https://videopress.com/v/ZCAOzTNk',
				'dynamic' => [
					'active' => true,
					'categories' => [
						TagsModule::POST_META_CATEGORY,
						TagsModule::URL_CATEGORY,
					],
				],
				'placeholder' => esc_html__( 'VideoPress URL', 'athemes-addons-elementor' ),
				'ai' => [
					'active' => false,
				],
				'condition' => [
					'video_type' => 'videopress',
					'insert_url' => 'yes',
				],

			]
		);

		$this->add_control(
			'start',
			[
				'label' => esc_html__( 'Start Time', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'description' => esc_html__( 'Specify a start time (in seconds)', 'athemes-addons-elementor' ),
				'frontend_available' => true,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'end',
			[
				'label' => esc_html__( 'End Time', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'description' => esc_html__( 'Specify an end time (in seconds)', 'athemes-addons-elementor' ),
				'condition' => [
					'video_type' => [ 'youtube', 'hosted' ],
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'video_options',
			[
				'label' => esc_html__( 'Video Options', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'autoplay',
			[
				'label' => esc_html__( 'Autoplay', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'play_on_mobile',
			[
				'label' => esc_html__( 'Play On Mobile', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'autoplay' => 'yes',
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'mute',
			[
				'label' => esc_html__( 'Mute', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'loop',
			[
				'label' => esc_html__( 'Loop', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'video_type!' => 'dailymotion',
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'controls',
			[
				'label' => esc_html__( 'Player Controls', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'Hide', 'athemes-addons-elementor' ),
				'label_on' => esc_html__( 'Show', 'athemes-addons-elementor' ),
				'default' => 'yes',
				'condition' => [
					'video_type!' => 'vimeo',
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'showinfo',
			[
				'label' => esc_html__( 'Video Info', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'Hide', 'athemes-addons-elementor' ),
				'label_on' => esc_html__( 'Show', 'athemes-addons-elementor' ),
				'default' => 'yes',
				'condition' => [
					'video_type' => [ 'dailymotion' ],
				],
			]
		);

		$this->add_control(
			'modestbranding',
			[
				'label' => esc_html__( 'Modest Branding', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'video_type' => [ 'youtube' ],
					'controls' => 'yes',
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'logo',
			[
				'label' => esc_html__( 'Logo', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'Hide', 'athemes-addons-elementor' ),
				'label_on' => esc_html__( 'Show', 'athemes-addons-elementor' ),
				'default' => 'yes',
				'condition' => [
					'video_type' => [ 'dailymotion' ],
				],
			]
		);

		// YouTube.
		$this->add_control(
			'yt_privacy',
			[
				'label' => esc_html__( 'Privacy Mode', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'description' => esc_html__( 'When you turn on privacy mode, YouTube/Vimeo won\'t store information about visitors on your website unless they play the video.', 'athemes-addons-elementor' ),
				'condition' => [
					'video_type' => [ 'youtube', 'vimeo' ],
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'lazy_load',
			[
				'label' => esc_html__( 'Lazy Load', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'video_type',
							'operator' => '===',
							'value' => 'youtube',
						],
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'video_type',
									'operator' => '!==',
									'value' => 'hosted',
								],
							],
						],
					],
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'rel',
			[
				'label' => esc_html__( 'Suggested Videos', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => esc_html__( 'Current Video Channel', 'athemes-addons-elementor' ),
					'yes' => esc_html__( 'Any Video', 'athemes-addons-elementor' ),
				],
				'condition' => [
					'video_type' => 'youtube',
				],
			]
		);

		// Vimeo.
		$this->add_control(
			'vimeo_title',
			[
				'label' => esc_html__( 'Intro Title', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'Hide', 'athemes-addons-elementor' ),
				'label_on' => esc_html__( 'Show', 'athemes-addons-elementor' ),
				'default' => 'yes',
				'condition' => [
					'video_type' => 'vimeo',
				],
			]
		);

		$this->add_control(
			'vimeo_portrait',
			[
				'label' => esc_html__( 'Intro Portrait', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'Hide', 'athemes-addons-elementor' ),
				'label_on' => esc_html__( 'Show', 'athemes-addons-elementor' ),
				'default' => 'yes',
				'condition' => [
					'video_type' => 'vimeo',
				],
			]
		);

		$this->add_control(
			'vimeo_byline',
			[
				'label' => esc_html__( 'Intro Byline', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'Hide', 'athemes-addons-elementor' ),
				'label_on' => esc_html__( 'Show', 'athemes-addons-elementor' ),
				'default' => 'yes',
				'condition' => [
					'video_type' => 'vimeo',
				],
			]
		);

		$this->add_control(
			'color',
			[
				'label' => esc_html__( 'Controls Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'condition' => [
					'video_type' => [ 'vimeo', 'dailymotion' ],
				],
			]
		);

		$this->add_control(
			'download_button',
			[
				'label' => esc_html__( 'Download Button', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'Hide', 'athemes-addons-elementor' ),
				'label_on' => esc_html__( 'Show', 'athemes-addons-elementor' ),
				'condition' => [
					'video_type' => 'hosted',
				],
			]
		);

		$this->add_control(
			'preload',
			[
				'label' => esc_html__( 'Preload', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'metadata' => esc_html__( 'Metadata', 'athemes-addons-elementor' ),
					'auto' => esc_html__( 'Auto', 'athemes-addons-elementor' ),
					'none' => esc_html__( 'None', 'athemes-addons-elementor' ),
				],
				'description' => sprintf(
					'%1$s <a target="_blank" href="https://go.elementor.com/preload-video/">%2$s</a>',
					esc_html__( 'Preload attribute lets you specify how the video should be loaded when the page loads.', 'athemes-addons-elementor' ),
					esc_html__( 'Learn more', 'athemes-addons-elementor' ),
				),
				'default' => 'metadata',
				'condition' => [
					'video_type' => 'hosted',
					'autoplay' => '',
				],
			]
		);

		$this->add_control(
			'poster',
			[
				'label' => esc_html__( 'Poster', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'video_type' => 'hosted',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_trigger_style',
			[
				'label' => esc_html__( 'Trigger', 'athemes-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);      

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem' ],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .aafe-custom-embed-button-wrapper' => '--icon-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'trigger' => [ 'icon', 'text_icon' ],
				],
			]
		);

		$this->add_control(
			'glow_color',
			[
				'label' => esc_html__( 'Glow Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .aafe-custom-embed-button-wrapper' => '--glow-color: {{VALUE}};',
				],
				'condition' => [
					'glow' => 'yes',
				],
			]
		);

		$this->register_button_style_controls( $args = array( 'class' => 'aafe-custom-embed-button-wrapper', 'background_color' => '#1c1c1c' ) );

		$this->end_controls_section();      

		$this->start_controls_section(
			'section_video_style',
			[
				'label' => esc_html__( 'Video', 'athemes-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'aspect_ratio',
			[
				'label' => esc_html__( 'Aspect Ratio', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'169' => '16:9',
					'219' => '21:9',
					'43' => '4:3',
					'32' => '3:2',
					'11' => '1:1',
					'916' => '9:16',
				],
				'selectors_dictionary' => [
					'169' => '1.77777', // 16 / 9
					'219' => '2.33333', // 21 / 9
					'43' => '1.33333', // 4 / 3
					'32' => '1.5', // 3 / 2
					'11' => '1', // 1 / 1
					'916' => '0.5625', // 9 / 16
				],
				'default' => '169',
				'selectors' => [
					'{{WRAPPER}} .elementor-wrapper' => '--video-aspect-ratio: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'css_filters',
				'selector' => '{{WRAPPER}} .elementor-wrapper',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_lightbox_style',
			[
				'label' => esc_html__( 'Lightbox', 'athemes-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'lightbox_color',
			[
				'label' => esc_html__( 'Background Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'#elementor-lightbox-{{ID}}' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'lightbox_ui_color',
			[
				'label' => esc_html__( 'UI Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'#elementor-lightbox-{{ID}} .dialog-lightbox-close-button' => 'color: {{VALUE}}',
					'#elementor-lightbox-{{ID}} .dialog-lightbox-close-button svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'lightbox_ui_color_hover',
			[
				'label' => esc_html__( 'UI Hover Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'#elementor-lightbox-{{ID}} .dialog-lightbox-close-button:hover' => 'color: {{VALUE}}',
					'#elementor-lightbox-{{ID}} .dialog-lightbox-close-button:hover svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'lightbox_content_animation',
			[
				'label' => esc_html__( 'Entrance Animation', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::ANIMATION,
				'frontend_available' => true,
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

		$video_url = $settings[ $settings['video_type'] . '_url' ];

		if ( 'hosted' === $settings['video_type'] ) {
			$video_url = $this->get_hosted_video_url();
		} else {
			if ( 'videopress' === $settings['video_type'] ) {
				$video_url = $this->get_videopress_video_url();
			}

			$embed_params = $this->get_embed_params();
			$embed_options = $this->get_embed_options();
		}

		if ( empty( $video_url ) ) {
			return;
		}

		if ( 'youtube' === $settings['video_type'] ) {
			$video_html = '<div class="elementor-video"></div>';
		}

		if ( 'hosted' === $settings['video_type'] ) {
			$this->add_render_attribute( 'video-wrapper', 'class', 'e-hosted-video' );

			ob_start();

			$this->render_hosted_video();

			$video_html = ob_get_clean();
		} else {
			$is_static_render_mode = Plugin::$instance->frontend->is_static_render_mode();
			$post_id = get_queried_object_id();

			if ( $is_static_render_mode ) {
				$video_html = Embed::get_embed_thumbnail_html( $video_url, $post_id );
				// YouTube API requires a different markup which was set above.
			} elseif ( 'youtube' !== $settings['video_type'] ) {
				$video_html = Embed::get_embed_html( $video_url, $embed_params, $embed_options );
			}
		}

		if ( empty( $video_html ) ) {
			echo esc_url( $video_url );

			return;
		}

		$this->add_render_attribute( 'video-wrapper', 'class', 'athemes-addons-video-popup' );

		$this->add_render_attribute( 'video-wrapper', 'class', 'elementor-open-lightbox' );
		?>
		<div <?php $this->print_render_attribute_string( 'video-wrapper' ); ?>>
			<?php
			$this->add_render_attribute( 'button-wrapper', 'class', 'aafe-custom-embed-button-wrapper' );

			if ( 'button' === $settings['trigger_style'] ) {
				$this->add_render_attribute( 'button-wrapper', 'class', 'is-button-style button' );
			}

			if ( 'icon' === $settings['trigger'] ) {
				$this->add_render_attribute( 'button-wrapper', 'class', 'has-icon-only' );
			}
			

			if ( 'hosted' === $settings['video_type'] ) {
				$lightbox_url = $video_url;
			} else {
				$lightbox_url = Embed::get_embed_url( $video_url, $embed_params, $embed_options );
			}

			$lightbox_options = [
				'type' => 'video',
				'videoType' => $settings['video_type'],
				'url' => $lightbox_url,
				'modalOptions' => [
					'id' => 'elementor-lightbox-' . $this->get_id(),
					'entranceAnimation' => $settings['lightbox_content_animation'],
					'entranceAnimation_tablet' => $settings['lightbox_content_animation_tablet'],
					'entranceAnimation_mobile' => $settings['lightbox_content_animation_mobile'],
					'videoAspectRatio' => isset( $settings['aspect_ratio'] ) ? $settings['aspect_ratio'] : '169',
				],
			];

			if ( 'hosted' === $settings['video_type'] ) {
				$lightbox_options['videoParams'] = $this->get_hosted_params();
			}

			$this->add_render_attribute( 'button-wrapper', [
				'data-elementor-open-lightbox' => 'yes',
				'data-elementor-lightbox' => wp_json_encode( $lightbox_options ),
				'data-e-action-hash' => Plugin::instance()->frontend->create_action_hash( 'lightbox', $lightbox_options ),
			] );

			if ( Plugin::$instance->editor->is_edit_mode() ) {
				$this->add_render_attribute( 'button-wrapper', [
					'class' => 'elementor-clickable',
				] );
			}
			?>
			<div <?php $this->print_render_attribute_string( 'button-wrapper' ); ?>>
				<?php if ( 'icon' === $settings['trigger'] || 'text_icon' === $settings['trigger'] ) : ?>
					<div class="aafe-custom-embed-play" role="button" aria-label="<?php echo esc_html__( 'Play Video', 'athemes-addons-elementor' ); ?>" tabindex="0">
						<?php Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] ); ?>
						<span class="elementor-screen-only"><?php echo esc_html__( 'Play Video', 'athemes-addons-elementor' ); ?></span>
					</div>
				<?php endif; ?>
				<?php if ( 'text' === $settings['trigger'] || 'text_icon' === $settings['trigger'] ) : ?>
					<div class="aafe-custom-embed-text"><?php echo esc_html( $settings['text'] ); ?></div>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Get embed params.
	 *
	 * Retrieve video widget embed parameters.
	 *
	 * @access public
	 *
	 * @return array Video embed parameters.
	 */
	public function get_embed_params() {
		$settings = $this->get_settings_for_display();

		$params = [];

		if ( $settings['autoplay'] ) {
			$params['autoplay'] = '1';

			if ( $settings['play_on_mobile'] ) {
				$params['playsinline'] = '1';
			}
		}

		$params_dictionary = [];

		if ( 'youtube' === $settings['video_type'] ) {
			$params_dictionary = [
				'loop',
				'controls',
				'mute',
				'rel',
				'modestbranding',
			];

			if ( $settings['loop'] ) {
				$video_properties = Embed::get_video_properties( $settings['youtube_url'] );

				$params['playlist'] = $video_properties['video_id'];
			}

			$params['start'] = $settings['start'];

			$params['end'] = $settings['end'];

			$params['wmode'] = 'opaque';
		} elseif ( 'vimeo' === $settings['video_type'] ) {
			$params_dictionary = [
				'loop',
				'mute' => 'muted',
				'vimeo_title' => 'title',
				'vimeo_portrait' => 'portrait',
				'vimeo_byline' => 'byline',
			];

			$params['color'] = str_replace( '#', '', $settings['color'] );

			$params['autopause'] = '0';

			if ( ! empty( $settings['yt_privacy'] ) ) {
				$params['dnt'] = 'true';
			}
		} elseif ( 'dailymotion' === $settings['video_type'] ) {
			$params_dictionary = [
				'controls',
				'mute',
				'showinfo' => 'ui-start-screen-info',
				'logo' => 'ui-logo',
			];

			$params['ui-highlight'] = str_replace( '#', '', $settings['color'] );

			$params['start'] = $settings['start'];

			$params['endscreen-enable'] = '0';
		} elseif ( 'videopress' === $settings['video_type'] ) {
			$params_dictionary = $this->get_params_dictionary_for_videopress();

			$params['at'] = $settings['start'];
		}

		foreach ( $params_dictionary as $key => $param_name ) {
			$setting_name = $param_name;

			if ( is_string( $key ) ) {
				$setting_name = $key;
			}

			$setting_value = $settings[ $setting_name ] ? '1' : '0';

			$params[ $param_name ] = $setting_value;
		}

		return $params;
	}   

	private function get_embed_options() {
		$settings = $this->get_settings_for_display();

		$embed_options = [];

		if ( 'youtube' === $settings['video_type'] ) {
			$embed_options['privacy'] = $settings['yt_privacy'];
		} elseif ( 'vimeo' === $settings['video_type'] ) {
			$embed_options['start'] = $settings['start'];
		}

		$embed_options['lazy_load'] = ! empty( $settings['lazy_load'] );

		return $embed_options;
	}

	private function get_hosted_params() {
		$settings = $this->get_settings_for_display();

		$video_params = [];

		foreach ( [ 'autoplay', 'loop', 'controls' ] as $option_name ) {
			if ( $settings[ $option_name ] ) {
				$video_params[ $option_name ] = '';
			}
		}

		if ( $settings['preload'] ) {
			$video_params['preload'] = $settings['preload'];
		}

		if ( $settings['mute'] ) {
			$video_params['muted'] = 'muted';
		}

		if ( $settings['play_on_mobile'] ) {
			$video_params['playsinline'] = '';
		}

		if ( ! $settings['download_button'] ) {
			$video_params['controlsList'] = 'nodownload';
		}

		if ( $settings['poster']['url'] ) {
			$video_params['poster'] = $settings['poster']['url'];
		}

		return $video_params;
	}

	/**
	 * @param bool $from_media
	 *
	 * @return string
	 * 
	 * @access private
	 */
	private function get_hosted_video_url() {
		$settings = $this->get_settings_for_display();

		if ( ! empty( $settings['insert_url'] ) ) {
			$video_url = $settings['external_url']['url'];
		} else {
			$video_url = $settings['hosted_url']['url'];
		}

		if ( empty( $video_url ) ) {
			return '';
		}

		if ( $settings['start'] || $settings['end'] ) {
			$video_url .= '#t=';
		}

		if ( $settings['start'] ) {
			$video_url .= $settings['start'];
		}

		if ( $settings['end'] ) {
			$video_url .= ',' . $settings['end'];
		}

		return $video_url;
	}

	/**
	 * Get the VideoPress video URL from the current selected settings.
	 *
	 * @return string
	 */
	private function get_videopress_video_url() {
		$settings = $this->get_settings_for_display();

		if ( ! empty( $settings['insert_url'] ) ) {
			return $settings['videopress_url'];
		}

		return $settings['hosted_url']['url'];
	}

	/**
	 * Get the params dictionary for VideoPress videos.
	 *
	 * @return array
	 */
	private function get_params_dictionary_for_videopress() {
		return [
			'controls',
			'autoplay' => 'autoPlay',
			'mute' => 'muted',
			'loop',
			'play_on_mobile' => 'playsinline',
		];
	}

	/**
	 *
	 * @access private
	 */
	private function render_hosted_video() {
		$video_url = $this->get_hosted_video_url();
		if ( empty( $video_url ) ) {
			return;
		}

		$video_params = $this->get_hosted_params();
		/* Sometimes the video url is base64, therefore we use `esc_attr` in `src`. */
		?>
		<video class="elementor-video" src="<?php echo esc_attr( $video_url ); ?>" <?php Utils::print_html_attributes( $video_params ); ?>></video>
		<?php
	}

	/**
	 * Render widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function content_template() { 
	}
}
Plugin::instance()->widgets_manager->register( new Video_Popup() );