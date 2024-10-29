<?php
/**
 * Functionality used throughout the plugin.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * List all available widgets.
 * 
 * @return array
 */
function athemes_addons_get_widgets() {
	$widgets = array(
		'posts-list' => array(
			'pro'           => false,
			'category'      => 'posts',
			'title'         => esc_html__( 'Posts list', 'athemes-addons-elementor' ),
			'desc'           => esc_html__( 'Display a list of posts with multiple skins', 'athemes-addons-elementor' ),
			'tutorial_url'  => 'https://docs.athemes.com/article/posts-list/',
			'preview_url'   => 'https://addons.athemes.com/widget/posts-list/',
			'class'         => 'aThemes_Addons\Widgets\Posts_List',
			'has_styles'    => true,
			'has_scripts'   => false,
			'has_skins'     => true,
			'default'       => true,
		),
		'posts-carousel' => array(
			'pro'           => false,
			'category'      => 'posts',
			'title'         => esc_html__( 'Post Carousel', 'athemes-addons-elementor' ),
			'desc'           => esc_html__( 'Display a carousel of posts with multiple skins', 'athemes-addons-elementor' ),
			'tutorial_url'  => 'https://docs.athemes.com/article/post-carousel/',
			'preview_url'   => 'https://addons.athemes.com/widget/posts-carousel/',
			'class'         => 'aThemes_Addons\Widgets\Posts_Carousel',
			'has_styles'    => true,
			'has_scripts'   => true,
			'has_skins'     => true,
			'default'       => false,
		),
		'advanced-tabs' => array(
			'pro'           => true,
			'category'      => 'content',
			'title'         => esc_html__( 'Advanced Tabs', 'athemes-addons-elementor' ),
			'desc'           => esc_html__( 'Tabs with custom content and templates support', 'athemes-addons-elementor' ),
			'tutorial_url'  => '',
			'preview_url'   => '',
			'class'         => 'aThemes_Addons\Widgets\Advanced_Tabs',
			'has_styles'    => true,
			'has_scripts'   => true,
		),
		'advanced-carousel' => array(
			'pro'           => true,
			'category'      => 'content',
			'title'         => esc_html__( 'Advanced Carousel', 'athemes-addons-elementor' ),
			'desc'           => esc_html__( 'Carousel with support for images, custom content, videos and templates', 'athemes-addons-elementor' ),
			'tutorial_url'  => '',
			'preview_url'   => '',
			'class'         => 'aThemes_Addons\Widgets\Advanced_Carousel',
			'has_styles'    => true,
			'has_scripts'   => true,
		),
		'testimonials'  => array(
			'pro'           => false,
			'category'      => 'content',
			'title'         => esc_html__( 'Testimonials', 'athemes-addons-elementor' ),
			'desc'           => esc_html__( 'Display testimonials in a carousel', 'athemes-addons-elementor' ),
			'tutorial_url'  => 'https://docs.athemes.com/article/testimonials/',
			'preview_url'   => 'https://addons.athemes.com/widget/testimonials/',
			'class'         => 'aThemes_Addons\Widgets\Testimonials',
			'has_styles'    => true,
			'has_scripts'   => true,
			'has_skins'     => true,
			'default'       => true,
		),
		'animated-heading'  => array(
			'pro'           => true,
			'category'      => 'content',
			'title'         => esc_html__( 'Animated Heading', 'athemes-addons-elementor' ),
			'desc'           => esc_html__( 'Typing effect for any heading or text', 'athemes-addons-elementor' ),
			'tutorial_url'  => '',
			'preview_url'   => '',
			'class'         => 'aThemes_Addons\Widgets\Animated_Heading',
			'has_styles'    => true,
			'has_scripts'   => true,
		),
		'dual-heading'  => array(
			'pro'           => false,
			'category'      => 'content',
			'title'         => esc_html__( 'Dual Heading', 'athemes-addons-elementor' ),
			'desc'           => esc_html__( 'Style two parts of a heading individually for amazing effects', 'athemes-addons-elementor' ),
			'tutorial_url'  => 'https://docs.athemes.com/article/dual-heading/',
			'preview_url'   => 'https://addons.athemes.com/widget/dual-heading/',
			'class'         => 'aThemes_Addons\Widgets\Dual_Heading',
			'has_styles'    => false,
			'has_scripts'   => false,
		),
		'image-hotspots' => array(
			'pro'           => true,
			'category'      => 'content',
			'title'         => esc_html__( 'Image Hotspots', 'athemes-addons-elementor' ),
			'desc'           => esc_html__( 'Add animated hotposts over any image', 'athemes-addons-elementor' ),
			'tutorial_url'  => '',
			'preview_url'   => '',
			'class'         => 'aThemes_Addons\Widgets\Image_Hotspots',
			'has_styles'    => true,
			'has_scripts'   => true,
		),
		'image-card' => array(
			'pro'           => true,
			'category'      => 'content',
			'title'         => esc_html__( 'Image Card', 'athemes-addons-elementor' ),
			'desc'          => esc_html__( 'Display an image with content effects', 'athemes-addons-elementor' ),
			'tutorial_url'  => 'https://docs.athemes.com/article/image-card/',
			'preview_url'   => 'https://addons.athemes.com/widget/image-card/',
			'class'         => 'aThemes_Addons\Widgets\Image_Card',
			'has_styles'    => true,
			'has_scripts'   => false,
		),
		'social-proof' => array(
			'pro'           => true,
			'category'      => 'content',
			'title'         => esc_html__( 'Social Proof', 'athemes-addons-elementor' ),
			'desc'          => esc_html__( 'Display social proof in a stylish manner', 'athemes-addons-elementor' ),
			'tutorial_url'  => 'https://docs.athemes.com/article/social-proof/',
			'preview_url'   => 'https://addons.athemes.com/widget/social-proof/',
			'class'         => 'aThemes_Addons\Widgets\Social_Proof',
			'has_styles'    => true,
			'has_scripts'   => false,
		),
		'service-box' => array(
			'pro'           => true,
			'category'      => 'content',
			'title'         => esc_html__( 'Service Box', 'athemes-addons-elementor' ),
			'desc'          => esc_html__( 'Display your services with multiple skins', 'athemes-addons-elementor' ),
			'tutorial_url'  => 'https://docs.athemes.com/article/service-box/',
			'preview_url'   => 'https://addons.athemes.com/widget/service-box/',
			'class'         => 'aThemes_Addons\Widgets\Service_Box',
			'has_styles'    => true,
			'has_scripts'   => false,
			'has_skins'     => true,
		),
		'contact-form7' => array(
			'pro'           => false,
			'category'      => 'forms',
			'title'         => esc_html__( 'Contact Form 7', 'athemes-addons-elementor' ),
			'desc'           => esc_html__( 'Full styling control for any form built with Contact Form 7', 'athemes-addons-elementor' ),
			'tutorial_url'  => 'https://docs.athemes.com/article/contact-form-7/',
			'preview_url'   => 'https://addons.athemes.com/widget/contact-form-7/',
			'class'         => 'aThemes_Addons\Widgets\Contact_Form_7',
			'has_styles'    => true,
			'has_scripts'   => false,
		),
		'ninja-forms' => array(
			'pro'           => false,
			'category'      => 'forms',
			'title'         => esc_html__( 'Ninja Forms', 'athemes-addons-elementor' ),
			'desc'           => esc_html__( 'Full styling control for any form built with Ninja Forms', 'athemes-addons-elementor' ),
			'tutorial_url'  => 'https://docs.athemes.com/article/ninja-forms/',
			'preview_url'   => 'https://addons.athemes.com/widget/ninja-forms/',
			'class'         => 'aThemes_Addons\Widgets\Ninja_Forms',
			'has_styles'    => true,
			'has_scripts'   => false,
		),
		'wpforms' => array(
			'pro'           => false,
			'category'      => 'forms',
			'title'         => esc_html__( 'WPForms', 'athemes-addons-elementor' ),
			'desc'           => esc_html__( 'Full styling control for any form built with WPForms', 'athemes-addons-elementor' ),
			'tutorial_url'  => 'https://docs.athemes.com/article/wpforms/',
			'preview_url'   => 'https://addons.athemes.com/widget/wpforms/',
			'class'         => 'aThemes_Addons\Widgets\WPForms',
			'has_styles'    => true,
			'has_scripts'   => false,
		),
		'gravity-forms' => array(
			'pro'           => false,
			'category'      => 'forms',
			'title'         => esc_html__( 'Gravity Forms', 'athemes-addons-elementor' ),
			'desc'           => esc_html__( 'Full styling control for any form built with Gravity Forms', 'athemes-addons-elementor' ),
			'tutorial_url'  => 'https://docs.athemes.com/article/gravity-forms/',
			'preview_url'   => 'https://addons.athemes.com/widget/gravity-forms/',
			'class'         => 'aThemes_Addons\Widgets\Gravity_Forms',
			'has_styles'    => true,
			'has_scripts'   => false,
		),
		'weforms'       => array(
			'pro'           => false,
			'category'      => 'forms',
			'title'         => esc_html__( 'weForms', 'athemes-addons-elementor' ),
			'desc'           => esc_html__( 'Full styling control for any form built with weForms', 'athemes-addons-elementor' ),
			'tutorial_url'  => 'https://docs.athemes.com/article/weforms/',
			'preview_url'   => 'https://addons.athemes.com/widget/weforms/',
			'class'         => 'aThemes_Addons\Widgets\weForms',
			'has_styles'    => true,
			'has_scripts'   => false,
		),
		'mailchimp' => array(
			'pro'           => true,
			'category'      => 'forms',
			'title'         => esc_html__( 'Mailchimp', 'athemes-addons-elementor' ),
			'desc'           => esc_html__( 'Connect a form to any Mailchimp list', 'athemes-addons-elementor' ),
			'tutorial_url'  => 'https://docs.athemes.com/article/mailchimp/',
			'preview_url'   => '',
			'class'         => 'aThemes_Addons\Widgets\Mailchimp',
			'has_styles'    => true,
			'has_scripts'   => true,
		),
		'content-switcher' => array(
			'pro'           => true,
			'category'      => 'content',
			'title'         => esc_html__( 'Content Switcher', 'athemes-addons-elementor' ),
			'desc'           => esc_html__( 'Switch between multiple content sections', 'athemes-addons-elementor' ),
			'tutorial_url'  => 'https://docs.athemes.com/article/content-switcher/',
			'preview_url'   => 'https://addons.athemes.com/widget/content-switcher/',
			'class'         => 'aThemes_Addons\Widgets\Content_Switcher',
			'has_styles'    => true,
			'has_scripts'   => true,
		),
		'business-hours' => array(
			'pro'           => false,
			'category'      => 'content',
			'title'         => esc_html__( 'Business Hours', 'athemes-addons-elementor' ),
			'desc'           => esc_html__( 'Display your business hours with full styling', 'athemes-addons-elementor' ),
			'tutorial_url'  => 'https://docs.athemes.com/article/business-hours/',
			'preview_url'   => 'https://addons.athemes.com/widget/business-hours/',
			'class'         => 'aThemes_Addons\Widgets\Business_Hours',
			'has_styles'    => true,
			'has_scripts'   => false,
			'default'       => true,
		),
		'before-after-image' => array(
			'pro'           => false,
			'category'      => 'content',
			'title'         => esc_html__( 'Before/After Image', 'athemes-addons-elementor' ),
			'desc'           => esc_html__( 'Compare two images by dragging a slider', 'athemes-addons-elementor' ),
			'tutorial_url'  => 'https://docs.athemes.com/article/before-after-image/',
			'preview_url'   => 'https://addons.athemes.com/widget/before-after-image-comparison/',
			'class'         => 'aThemes_Addons\Widgets\Before_After_Image',
			'has_styles'    => true,
			'has_scripts'   => true,
		),
		'team-member' => array(
			'pro'           => false,
			'category'      => 'content',
			'title'         => esc_html__( 'Team Member', 'athemes-addons-elementor' ),
			'desc'           => esc_html__( 'Display a team member with bio and socials', 'athemes-addons-elementor' ),
			'tutorial_url'  => 'https://docs.athemes.com/article/team-member/',
			'preview_url'   => 'https://addons.athemes.com/widget/team-member/',
			'class'         => 'aThemes_Addons\Widgets\Team_Member',
			'has_styles'    => true,
			'has_scripts'   => false,
			'default'       => true,
		), 
		'countdown' => array(
			'pro'           => false,
			'category'      => 'content',
			'title'         => esc_html__( 'Countdown', 'athemes-addons-elementor' ),
			'desc'           => esc_html__( 'Countdown timer with multiple skins', 'athemes-addons-elementor' ),
			'tutorial_url'  => 'https://docs.athemes.com/article/countdown/',
			'preview_url'   => 'https://addons.athemes.com/widget/countdown/',
			'class'         => 'aThemes_Addons\Widgets\Countdown',
			'has_styles'    => true,
			'has_scripts'   => true,
		),
		'flip-box' => array(
			'pro'           => false,
			'category'      => 'content',
			'title'         => esc_html__( 'Flip Box', 'athemes-addons-elementor' ),
			'desc'          => esc_html__( 'A fancy and interactive way to display content', 'athemes-addons-elementor' ),
			'tutorial_url'  => 'https://docs.athemes.com/article/flip-box/',
			'preview_url'   => 'https://addons.athemes.com/widget/flip-box/',
			'class'         => 'aThemes_Addons\Widgets\Flip_Box',
			'has_styles'    => true,
			'has_scripts'   => false,
		),
		'offcanvas' => array(
			'pro'           => true,
			'category'      => 'content',
			'title'         => esc_html__( 'Offcanvas Content', 'athemes-addons-elementor' ),
			'desc'          => esc_html__( 'Create offcanvas content with ease', 'athemes-addons-elementor' ),
			'tutorial_url'  => 'https://docs.athemes.com/article/offcanvas/',
			'preview_url'   => 'https://addons.athemes.com/widget/offcanvas/',
			'class'         => 'aThemes_Addons\Widgets\Offcanvas',
			'has_styles'    => true,
			'has_scripts'   => true,
		),
		'dual-buttons' => array(
			'pro'           => false,
			'category'      => 'content',
			'title'         => esc_html__( 'Dual Buttons', 'athemes-addons-elementor' ),
			'desc'          => esc_html__( 'Two side-by-side buttons with individual styling', 'athemes-addons-elementor' ),
			'tutorial_url'  => 'https://docs.athemes.com/article/dual-buttons/',
			'preview_url'   => 'https://addons.athemes.com/widget/dual-buttons/',
			'class'         => 'aThemes_Addons\Widgets\Dual_Buttons',
			'has_styles'    => true,
			'has_scripts'   => false,
		),
		'image-scroll' => array(
			'pro'           => false,
			'category'      => 'content',
			'title'         => esc_html__( 'Image Scroll', 'athemes-addons-elementor' ),
			'desc'          => esc_html__( 'Scroll an image by hovering', 'athemes-addons-elementor' ),
			'tutorial_url'  => 'https://docs.athemes.com/article/image-scroll/',
			'preview_url'   => 'https://addons.athemes.com/widget/image-scroll/',
			'class'         => 'aThemes_Addons\Widgets\Image_Scroll',
			'has_styles'    => true,
			'has_scripts'   => true,
		),
		'timeline' => array(
			'pro'           => true,
			'category'      => 'content',
			'title'         => esc_html__( 'Timeline', 'athemes-addons-elementor' ),
			'desc'          => esc_html__( 'Display events in a timeline layout', 'athemes-addons-elementor' ),
			'tutorial_url'  => 'https://docs.athemes.com/article/timeline/',
			'preview_url'   => 'https://addons.athemes.com/widget/timeline/',
			'class'         => 'aThemes_Addons\Widgets\Timeline',
			'has_styles'    => true,
			'has_scripts'   => true,
		),
		'video-gallery' => array(
			'pro'           => true,
			'category'      => 'content',
			'title'         => esc_html__( 'Video Gallery', 'athemes-addons-elementor' ),
			'desc'          => esc_html__( 'Display a gallery of videos', 'athemes-addons-elementor' ),
			'tutorial_url'  => 'https://docs.athemes.com/article/video-gallery/',
			'preview_url'   => 'https://addons.athemes.com/widget/video-gallery/',
			'class'         => 'aThemes_Addons\Widgets\Video_Gallery',
			'has_styles'    => true,
			'has_scripts'   => true,
		),
		'video-playlist' => array(
			'pro'           => true,
			'category'      => 'content',
			'title'         => esc_html__( 'Video Playlist', 'athemes-addons-elementor' ),
			'desc'          => esc_html__( 'Create and display a video playlist', 'athemes-addons-elementor' ),
			'tutorial_url'  => 'https://docs.athemes.com/article/video-playlist/',
			'preview_url'   => 'https://addons.athemes.com/widget/video-playlist/',
			'class'         => 'aThemes_Addons\Widgets\Video_Playlist',
			'has_styles'    => true,
			'has_scripts'   => true,
		),
		'video-carousel' => array(
			'pro'           => true,
			'category'      => 'content',
			'title'         => esc_html__( 'Video Carousel', 'athemes-addons-elementor' ),
			'desc'          => esc_html__( 'Display a carousel of videos', 'athemes-addons-elementor' ),
			'tutorial_url'  => 'https://docs.athemes.com/article/video-carousel/',
			'preview_url'   => 'https://addons.athemes.com/widget/video-carousel/',
			'class'         => 'aThemes_Addons\Widgets\Video_Carousel',
			'has_styles'    => true,
			'has_scripts'   => true,
		),
		'lottie' => array(
			'pro'           => false,
			'category'      => 'content',
			'title'         => esc_html__( 'Lottie', 'athemes-addons-elementor' ),
			'desc'          => esc_html__( 'Add Lottie animations to your pages', 'athemes-addons-elementor' ),
			'tutorial_url'  => 'https://docs.athemes.com/article/lottie/',
			'preview_url'   => 'https://addons.athemes.com/widget/lottie/',
			'class'         => 'aThemes_Addons\Widgets\Lottie',
			'has_styles'    => false,
			'has_scripts'   => true,
		),
		'pricing-table' => array(
			'pro'           => false,
			'category'      => 'content',
			'title'         => esc_html__( 'Pricing Table', 'athemes-addons-elementor' ),
			'desc'          => esc_html__( 'Create advanced pricing tables with ease', 'athemes-addons-elementor' ),
			'tutorial_url'  => 'https://docs.athemes.com/article/pricing-table/',
			'preview_url'   => 'https://addons.athemes.com/widget/pricing-table/',
			'class'         => 'aThemes_Addons\Widgets\Pricing_Table',
			'has_styles'    => true,
			'has_scripts'   => false,
		),
		'call-to-action' => array(
			'pro'           => false,
			'category'      => 'content',
			'title'         => esc_html__( 'Call to Action', 'athemes-addons-elementor' ),
			'desc'          => esc_html__( 'Quickly draw attention and increase conversions', 'athemes-addons-elementor' ),
			'tutorial_url'  => 'https://docs.athemes.com/article/call-to-action/',
			'preview_url'   => 'https://addons.athemes.com/widget/call-to-action/',
			'class'         => 'aThemes_Addons\Widgets\Call_To_Action',
			'has_styles'    => true,
			'has_scripts'   => false,
			'has_skins'     => true,
			'default'       => true,
		),
		'slider' => array(
			'pro'           => false,
			'category'      => 'content',
			'title'         => esc_html__( 'Slider', 'athemes-addons-elementor' ),
			'desc'          => esc_html__( 'Engaging and responsive content slider', 'athemes-addons-elementor' ),
			'tutorial_url'  => 'https://docs.athemes.com/article/slider/',
			'preview_url'   => 'https://addons.athemes.com/widget/slider/',
			'class'         => 'aThemes_Addons\Widgets\Slider',
			'has_styles'    => true,
			'has_scripts'   => true,
		),
		'food-menu' => array(
			'pro'           => true,
			'category'      => 'content',
			'title'         => esc_html__( 'Food Menu', 'athemes-addons-elementor' ),
			'desc'          => esc_html__( 'Easily display your restaurant menu', 'athemes-addons-elementor' ),
			'tutorial_url'  => 'https://docs.athemes.com/article/food-menu/',
			'preview_url'   => 'https://addons.athemes.com/widget/food-menu/',
			'class'         => 'aThemes_Addons\Widgets\Food_Menu',
			'has_styles'    => true,
			'has_scripts'   => false,
		),
		'page-list' => array(
			'pro'           => false,
			'category'      => 'posts',
			'title'         => esc_html__( 'Page List', 'athemes-addons-elementor' ),
			'desc'          => esc_html__( 'Display a list of pages or custom links', 'athemes-addons-elementor' ),
			'tutorial_url'  => 'https://docs.athemes.com/article/page-list/',
			'preview_url'   => 'https://addons.athemes.com/widget/page-list/',
			'class'         => 'aThemes_Addons\Widgets\Page_List',
			'has_styles'    => true,
			'has_scripts'   => false,
		),
		'gallery' => array(
			'pro'           => false,
			'category'      => 'content',
			'title'         => esc_html__( 'Gallery', 'athemes-addons-elementor' ),
			'desc'          => esc_html__( 'Create beautiful galleries with ease', 'athemes-addons-elementor' ),
			'tutorial_url'  => 'https://docs.athemes.com/article/gallery/',
			'preview_url'   => 'https://addons.athemes.com/widget/gallery/',
			'class'         => 'aThemes_Addons\Widgets\Gallery',
			'has_styles'    => true,
			'has_scripts'   => true,
			'has_skins'     => true,
			'default'       => true,
		),
		'image-accordion' => array(
			'pro'           => false,
			'category'      => 'content',
			'title'         => esc_html__( 'Image accordion', 'athemes-addons-elementor' ),
			'desc'          => esc_html__( 'Showcase images inside an accordion', 'athemes-addons-elementor' ),
			'tutorial_url'  => 'https://docs.athemes.com/article/image-accordion/',
			'preview_url'   => 'https://addons.athemes.com/widget/image-accordion/',
			'class'         => 'aThemes_Addons\Widgets\Image_Accordion',
			'has_styles'    => true,
			'has_scripts'   => true,
			'default'       => false,
		),
		'advanced-social' => array(
			'pro'           => false,
			'category'      => 'social',
			'title'         => esc_html__( 'Advanced Social Icons', 'athemes-addons-elementor' ),
			'desc'          => esc_html__( 'Display social icons with advanced styling options', 'athemes-addons-elementor' ),
			'tutorial_url'  => 'https://docs.athemes.com/article/advanced-social-icons/',
			'preview_url'   => 'https://addons.athemes.com/widget/advanced-social/',
			'class'         => 'aThemes_Addons\Widgets\Advanced_Social',
			'has_styles'    => true,
			'has_scripts'   => false,
			'default'       => false,
		),
		'woo-product-grid'  => array(
			'pro'           => false,
			'category'      => 'woocommerce',
			'title'         => esc_html__( 'Woo Product Grid', 'athemes-addons-elementor' ),
			'desc'          => esc_html__( 'Highly-customizable WooCommerce product grid', 'athemes-addons-elementor' ),
			'tutorial_url'  => 'https://docs.athemes.com/article/woo-product-grid/',
			'preview_url'   => 'https://addons.athemes.com/widget/woo-products-grid/',
			'class'         => 'aThemes_Addons\Widgets\Woo_Product_Grid',
			'has_styles'    => true,
			'has_scripts'   => true,
			'default'       => false,
		),
		'logo-carousel' => array(
			'pro'           => false,
			'category'      => 'content',
			'title'         => esc_html__( 'Logo Carousel', 'athemes-addons-elementor' ),
			'desc'          => esc_html__( 'Display your clients logos in a carousel', 'athemes-addons-elementor' ),
			'tutorial_url'  => 'https://docs.athemes.com/article/logo-carousel/',
			'preview_url'   => 'https://addons.athemes.com/widget/logo-carousel/',
			'class'         => 'aThemes_Addons\Widgets\Logo_Carousel',
			'has_styles'    => true,
			'has_scripts'   => true,
			'default'       => false,
		),
		'table' => array(
			'pro'           => false,
			'category'      => 'content',
			'title'         => esc_html__( 'Table', 'athemes-addons-elementor' ),
			'desc'          => esc_html__( 'Create responsive tables with ease', 'athemes-addons-elementor' ),
			'tutorial_url'  => 'https://docs.athemes.com/article/table/',
			'preview_url'   => 'https://addons.athemes.com/widget/table/',
			'class'         => 'aThemes_Addons\Widgets\Table',
			'has_styles'    => true,
			'has_scripts'   => true,
			'default'       => false,
		),
		'progress-bar' => array(
			'pro'           => false,
			'category'      => 'content',
			'title'         => esc_html__( 'Progress Bar', 'athemes-addons-elementor' ),
			'desc'          => esc_html__( 'Display progress bars with custom styles', 'athemes-addons-elementor' ),
			'tutorial_url'  => 'https://docs.athemes.com/article/progress-bar/',
			'preview_url'   => 'https://addons.athemes.com/widget/progress-bar/',
			'class'         => 'aThemes_Addons\Widgets\Progress_Bar',
			'has_styles'    => true,
			'has_scripts'   => true,
			'default'       => false,
		),
		'events-calendar' => array(
			'pro'           => false,
			'category'      => 'content',
			'title'         => esc_html__( 'Google Calendar', 'athemes-addons-elementor' ),
			'desc'          => esc_html__( 'Display events from your Google Calendar', 'athemes-addons-elementor' ),
			'tutorial_url'  => 'https://docs.athemes.com/article/google-calendar/',
			'preview_url'   => 'https://addons.athemes.com/widget/google-calendar/',
			'class'         => 'aThemes_Addons\Widgets\Events_Calendar',
			'has_styles'    => true,
			'has_scripts'   => true,
			'default'       => false,
		),
		'posts-timeline' => array(
			'pro'           => false,
			'category'      => 'posts',
			'title'         => esc_html__( 'Post Timeline', 'athemes-addons-elementor' ),
			'desc'          => esc_html__( 'Display posts in a timeline layout', 'athemes-addons-elementor' ),
			'tutorial_url'  => 'https://docs.athemes.com/article/post-timeline/',
			'preview_url'   => 'https://addons.athemes.com/widget/posts-timeline/',
			'class'         => 'aThemes_Addons\Widgets\Posts_Timeline',
			'has_styles'    => true,
			'has_scripts'   => false,
			'default'       => false,
		),
		'video-popup' => array(
			'pro'           => false,
			'category'      => 'content',
			'title'         => esc_html__( 'Video Popup', 'athemes-addons-elementor' ),
			'desc'          => esc_html__( 'Open videos in a lightbox popup', 'athemes-addons-elementor' ),
			'tutorial_url'  => 'https://docs.athemes.com/article/video-popup/',
			'preview_url'   => 'https://addons.athemes.com/widget/video-popup/',
			'class'         => 'aThemes_Addons\Widgets\Video_Popup',
			'has_styles'    => true,
			'has_scripts'   => false,
			'default'       => false,
		),
	);

	return apply_filters( 'athemes_addons_widgets', $widgets );
}

/**
 * List all available extensions.
 * 
 * @return array
 */
function athemes_addons_get_extensions() {
	$extensions = array(
		'custom-css' => array(
			'pro'           => false,
			'title'         => esc_html__( 'Custom CSS', 'athemes-addons-elementor' ),
			'desc'          => esc_html__( 'Add custom CSS to any element', 'athemes-addons-elementor' ),
			'tutorial_url'  => '',
			'preview_url'   => '',
			'class'         => 'aThemes_Addons\Extensions\Custom_CSS',
			'has_styles'    => false,
			'has_scripts'   => false,
		),
		'page-duplicator' => array(
			'pro'           => false,
			'title'         => esc_html__( 'Page Duplicator', 'athemes-addons-elementor' ),
			'desc'          => esc_html__( 'Duplicate any kind of page with a single click', 'athemes-addons-elementor' ),
			'tutorial_url'  => '',
			'preview_url'   => '',
			'class'         => 'aThemes_Addons\Extensions\Page_Duplicator',
			'has_styles'    => false,
			'has_scripts'   => false,
		),
		'custom-js' => array(
			'pro'           => false,
			'title'         => esc_html__( 'Custom Javascript', 'athemes-addons-elementor' ),
			'desc'          => esc_html__( 'Add custom JS to specific pages', 'athemes-addons-elementor' ),
			'tutorial_url'  => '',
			'preview_url'   => '',
			'class'         => 'aThemes_Addons\Extensions\Custom_JS',
			'has_styles'    => false,
			'has_scripts'   => false,
		),
		'parallax' => array(
			'pro'           => false,
			'title'         => esc_html__( 'Parallax', 'athemes-addons-elementor' ),
			'desc'          => esc_html__( 'Easy-to-use parallax effects', 'athemes-addons-elementor' ),
			'tutorial_url'  => '',
			'preview_url'   => '',
			'class'         => 'aThemes_Addons\Extensions\Parallax',
			'has_styles'    => false,
			'has_scripts'   => false,
		),
		'animation-effects' => array(
			'pro'           => true,
			'title'         => esc_html__( 'Animation Effects', 'athemes-addons-elementor' ),
			'desc'          => esc_html__( 'desc', 'athemes-addons-elementor' ),
			'tutorial_url'  => '',
			'preview_url'   => '',
			'class'         => 'aThemes_Addons\Extensions\Animation_Effects',
			'has_styles'    => false,
			'has_scripts'   => false,
		),		
		'content-protection' => array(
			'pro'           => true,
			'title'         => esc_html__( 'Content Protection', 'athemes-addons-elementor' ),
			'desc'          => esc_html__( 'desc', 'athemes-addons-elementor' ),
			'tutorial_url'  => '',
			'preview_url'   => '',
			'class'         => 'aThemes_Addons\Extensions\Content_Protection',
			'has_styles'    => false,
			'has_scripts'   => false,
		),
		'cursor-effects' => array(
			'pro'           => true,
			'title'         => esc_html__( 'Cursor Effects', 'athemes-addons-elementor' ),
			'desc'          => esc_html__( 'desc', 'athemes-addons-elementor' ),
			'tutorial_url'  => '',
			'preview_url'   => '',
			'class'         => 'aThemes_Addons\Extensions\Cursor_Effects',
			'has_styles'    => false,
			'has_scripts'   => false,
		),
		'dynamic-tags' => array(
			'pro'           => true,
			'title'         => esc_html__( 'Dynamic Tags', 'athemes-addons-elementor' ),
			'desc'          => esc_html__( 'desc', 'athemes-addons-elementor' ),
			'tutorial_url'  => '',
			'preview_url'   => '',
			'class'         => 'aThemes_Addons\Extensions\Dynamic_Tags',
			'has_styles'    => false,
			'has_scripts'   => false,
		),
		'display-conditions' => array(
			'pro'           => true,
			'title'         => esc_html__( 'Display Conditions', 'athemes-addons-elementor' ),
			'desc'          => esc_html__( 'desc', 'athemes-addons-elementor' ),
			'tutorial_url'  => '',
			'preview_url'   => '',
			'class'         => 'aThemes_Addons\Extensions\Display_Conditions',
			'has_styles'    => false,
			'has_scripts'   => false,
		),
		'animation-effects' => array(
			'pro'           => true,
			'title'         => esc_html__( 'Animation Effects', 'athemes-addons-elementor' ),
			'desc'           => esc_html__( 'desc', 'athemes-addons-elementor' ),
			'tutorial_url'  => '',
			'preview_url'   => '',
			'class'         => 'aThemes_Addons\Extensions\Animation_Effects',
			'has_styles'    => true,
			'has_scripts'   => true,
		),
		'glassmorphism' => array(
			'pro'           => true,
			'title'         => esc_html__( 'Glassmorphism', 'athemes-addons-elementor' ),
			'desc'           => esc_html__( 'desc', 'athemes-addons-elementor' ),
			'tutorial_url'  => '',
			'preview_url'   => '',
			'class'         => 'aThemes_Addons\Extensions\Glassmorphism',
			'has_styles'    => true,
			'has_scripts'   => true,
		),
		'particles' => array(
			'pro'           => true,
			'title'         => esc_html__( 'Particles', 'athemes-addons-elementor' ),
			'desc'           => esc_html__( 'desc', 'athemes-addons-elementor' ),
			'tutorial_url'  => '',
			'preview_url'   => '',
			'class'         => 'aThemes_Addons\Extensions\Particles',
			'has_styles'    => true,
			'has_scripts'   => true,
		),
		'sticky' => array(
			'pro'           => true,
			'title'         => esc_html__( 'Sticky', 'athemes-addons-elementor' ),
			'desc'           => esc_html__( 'desc', 'athemes-addons-elementor' ),
			'tutorial_url'  => '',
			'preview_url'   => '',
			'class'         => 'aThemes_Addons\Extensions\Sticky',
			'has_styles'    => true,
			'has_scripts'   => true,
		),
		'tooltips' => array(
			'pro'           => true,
			'title'         => esc_html__( 'Tooltips', 'athemes-addons-elementor' ),
			'desc'           => esc_html__( 'desc', 'athemes-addons-elementor' ),
			'tutorial_url'  => '',
			'preview_url'   => '',
			'class'         => 'aThemes_Addons\Extensions\Tooltips',
			'has_styles'    => true,
			'has_scripts'   => true,
		),
	);

	return apply_filters( 'athemes_addons_extensions', $extensions );
}

/**
 * Get the post date
 */
function athemes_addons_get_post_date() {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
	}
	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);
	$posted_on = '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>';
	
	echo '<span class="posted-on">' . $posted_on . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Get the first post category
 */
function athemes_addons_get_first_cat() {
	if ( 'post' === get_post_type() ) {
		$cats = get_the_category();
		if( isset($cats[0]) ) {
			echo '<a href="' . esc_url( get_category_link( $cats[0]->term_id ) ) . '" title="' . esc_attr( $cats[0]->name ) . '" class="post-cat">' . esc_html( $cats[0]->name ) . '</a>';
		}
	} elseif ( 'product' === get_post_type() ) {
		$terms = get_the_terms( get_the_ID(), 'product_cat' );
		if ( $terms && ! is_wp_error( $terms ) ) {
			$term = current( $terms );
			echo '<a href="' . esc_url( get_term_link( $term ) ) . '" title="' . esc_attr( $term->name ) . '" class="post-cat">' . esc_html( $term->name ) . '</a>';
		}
	}
}

/**
 * Get all post categories
 */
function athemes_addons_get_all_cats() {
	$categories = get_the_category();
	if ( $categories ) {
		foreach ($categories as $cat) {
			echo '<a href="' . esc_url( get_category_link( $cat->term_id ) ) . '" title="' . esc_attr( $cat->name ) . '" class="post-cat">' . esc_html( $cat->name ) . '</a>';
		}
	}
}

/**
 * Get the post author
 */
function athemes_addons_get_post_author() {
	global $post;
	$author = $post->post_author;

	$byline = '<span class="author vcard">';
	$avatar = '';
	
	$byline .= '<a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID', $author ) ) ) . '">' . esc_html( get_the_author_meta( 'display_name', $author ) ) . '</a>';

	$byline .= '</span>';

	echo '<span class="post-author">' . $byline . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Get Mailchimp lists
 */
function athemes_addons_get_mailchimp_lists() {
	$api_key = get_option( 'athemes-addons-settings' )['aafe_mailchimp_api_key'];
	
	$lists = array();

	if ( empty( $api_key ) ) {
		return $lists;
	}

	$response = wp_remote_get('https://' . substr($api_key,
		strpos($api_key, '-') + 1) . '.api.mailchimp.com/3.0/lists/?fields=lists.id,lists.name&count=1000', [
		'headers' => [
			'Content-Type' => 'application/json',
			// phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode
			'Authorization' => 'Basic ' . base64_encode('user:' . $api_key),
		],
	]);

	if (!is_wp_error($response)) {
		$response = json_decode(wp_remote_retrieve_body($response));

		if (!empty($response) && !empty($response->lists)) {
			$lists[''] = __( 'Select list', 'athemes-addons-elementor' );

			for ($i = 0; $i < count($response->lists); $i++) {
				$lists[$response->lists[$i]->id] = $response->lists[$i]->name;
			}
		}
	}

	return $lists;
}

/**
 * Render third-party elements
 */
function athemes_addons_render_element( $element ) {
	
	switch ( $element ) {
		case 'merchant_buy_now':
			if ( class_exists( 'Merchant_Buy_Now' ) ) {
				$buy_now = new Merchant_Buy_Now();
				$buy_now->shop_archive_product_buy_now_button();
			}

			break;

		case 'merchant_quick_view':
			if ( class_exists( 'Merchant_Quick_View' ) ) {
				$quick_view = new Merchant_Quick_View();

				$quick_view->quick_view_button();
				
				remove_action( 'wp_footer', array( $quick_view, 'modal_output' ) );
			}
			break;

		case 'merchant_wishlist':
			if ( class_exists( 'Merchant_Pro_Wishlist' ) ) {

				$wishlist = new Merchant_Pro_Wishlist( Merchant_Modules::get_module( Merchant_Wishlist::MODULE_ID ) );

				wp_enqueue_style( 'merchant-wishlist-button' );

				wp_enqueue_script( 'merchant-wishlist-button', MERCHANT_PRO_URI . 'assets/js/modules/wishlist/wishlist-button.min.js', array(), MERCHANT_PRO_VERSION, true );

				$product = wc_get_product( get_the_ID() );

				$wishlist->wishlist_button( $product );
			}
			break;

		case 'merchant_product_swatches':
			if ( class_exists( 'Merchant_Pro_Product_Swatches' ) ) {
				add_filter( 'merchant_product_swatch_shop_catalog_add_to_cart_button_html', '__return_empty_string' );
				Merchant_Pro_Product_Swatches::product_swatch_on_shop_catalog();
			}
			break;
	}
}

/**
 * Insert add to cart icon when Merchant buy now is enabled
 */
function athemes_addons_add_cart_icon( $button, $product, $args ) {

	if ( !$product->is_type( 'simple' ) ) {
		return $button;
	}

	$text = aThemes_Addons_SVG_Icons::get_svg_icon( 'icon-cart', false );

	$args['class'] .= ' has-merchant-buy-now';

	$button = sprintf(
		'<a href="%s" data-quantity="%s" class="%s" %s>%s</a>',
		esc_url( $product->add_to_cart_url() ),
		esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
		esc_attr( isset( $args['class'] ) ? $args['class'] : 'button' ),
		isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
		$text
	);

	return $button;
}

/**
 * Show product categories inside the product loop
 */
function athemes_addons_woo_categories() {
	?>
		<span class="aafe-product-category">
			<?php
			global $product;
			$product_categories = function_exists( 'wc_get_product_category_list' ) ? wc_get_product_category_list( get_the_ID(), ';', '', '' ) : $product->get_categories( ';', '', '' );

			$product_categories = htmlspecialchars_decode( wp_strip_all_tags( $product_categories ) );
			if ( $product_categories ) {
				list( $parent_cat ) = explode( ';', $product_categories );
				echo esc_html( $parent_cat );
			}
			?>
		</span>
	<?php
}