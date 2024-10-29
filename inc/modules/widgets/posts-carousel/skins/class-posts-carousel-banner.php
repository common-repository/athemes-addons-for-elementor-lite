<?php
namespace aThemes_Addons\Widgets;

use aThemes_Addons_Posts_Helper as Posts_Helper;
use Elementor\Plugin;
use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Skin_Base;
use Elementor\Group_Control_Image_Size;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Posts carousel widget.
 *
 *
 * @since 1.0.0
 */
class Posts_Carousel_Skin2 extends Skin_Base {

	/**
	 * Constructor.
	 */
	public function __construct( Widget_Base $parent ) {
		parent::__construct( $parent );

		add_action( 'elementor/element/athemes-addons-posts-carousel/section_style_card/after_section_end', [ $this, 'update_card_controls' ] );
		add_action( 'elementor/element/athemes-addons-posts-carousel/section_item_settings/after_section_end', [ $this, 'update_element_controls' ] );
	}

	/**
	 * Get the id.
	 */
	public function get_id() {
		return 'athemes-addons-posts-carousel-banner';
	}

	/**
	 * Get the title.
	 */
	public function get_title() {
		return esc_html__( 'Banner', 'athemes-addons-elementor' );
	}

	/**
	 * Update card controls.
	 * 
	 * @since 1.0.0
	 */
	public function update_card_controls( $controls ) {

		$controls->start_injection( [
			'at' => 'before',
			'of' => 'tabs_card_style',
		] );

		$controls->add_responsive_control(
			'card_padding_' . $this->get_id(),
			[
				'label' => __( 'Padding', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'unit' => 'px',
					'top' => '90',
					'right' => '30',
					'bottom' => '30',
					'left' => '30',
				],
				'selectors' => [
					'{{WRAPPER}} .athemes-post-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],      
				'condition' => [
					'_skin' => $this->get_id(),
				],
			]
		);

		$controls->add_control(
			'card_border_radius_' . $this->get_id(),
			[
				'label' => __( 'Border radius', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'unit' => 'px',
					'top' => '15',
					'right' => '15',
					'bottom' => '15',
					'left' => '15',
				],
				'selectors' => [
					'{{WRAPPER}} .athemes-post-item,{{WRAPPER}} .athemes-post-item::after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],  
				'condition' => [
					'_skin' => $this->get_id(),
				],  
			]
		);

		$controls->end_injection();
	}

	/**
	 * Update elements.
	 */
	public function update_element_controls( $controls ) {
		$controls->start_injection( [
			'at' => 'after',
			'of' => 'post_excerpt_heading',
		] );

		$controls->add_control(
			'show_excerpt_' . $this->get_id(),
			[
				'label' => __( 'Show excerpt', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on'  => __( 'Yes', 'athemes-addons-elementor' ),
				'label_off' => __( 'No', 'athemes-addons-elementor' ),
				'return_value' => 'yes',
				'default' => 'no',      
				'condition' => [
					'_skin' => $this->get_id(),
				],
			]
		);

		$controls->end_injection();
	}


	/**
	 * Render widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function render() {
		$settings = $this->parent->get_settings();
		$settings['widget_id'] = $this->get_id();

		$helper = Posts_Helper::instance();

		$query_args = $helper->get_query_args( $settings );

		$query = new \WP_Query( $query_args );  


		if ( $query->have_posts() ) :

			//add render attribute to the wrapper
			$this->parent->add_render_attribute( 'container', 'class', 'athemes-addons-posts-carousel-container' );
			$this->parent->add_render_attribute( 'wrapper', 'class', 'athemes-addons-posts-carousel swiper-container' );
			$this->parent->add_render_attribute( 'wrapper', 'data-autoplay', $settings['autoplay'] );
			$this->parent->add_render_attribute( 'wrapper', 'data-autoplay-speed', $settings['autoplay_speed'] );
			$this->parent->add_render_attribute( 'wrapper', 'data-infinite', $settings['infinite'] );
			$this->parent->add_render_attribute( 'wrapper', 'data-pause-on-hover', $settings['pause_on_hover'] );
			$this->parent->add_render_attribute( 'wrapper', 'data-transition-speed', $settings['transition_speed'] );
			$this->parent->add_render_attribute( 'wrapper', 'data-arrows', $settings['show_arrows'] );
			$this->parent->add_render_attribute( 'wrapper', 'data-dots', $settings['show_dots'] );
			$this->parent->add_render_attribute( 'wrapper', 'data-items', isset( $settings['slides_to_show'] ) && '' !== $settings['slides_to_show'] ? $settings['slides_to_show'] : 3 );
			$this->parent->add_render_attribute( 'wrapper', 'data-items-tablet', isset( $settings['slides_to_show_tablet'] ) ? $settings['slides_to_show_tablet'] : 2 );
			$this->parent->add_render_attribute( 'wrapper', 'data-items-mobile', isset( $settings['slides_to_show_mobile'] ) ? $settings['slides_to_show_mobile'] : 1 );
			$this->parent->add_render_attribute( 'wrapper', 'data-skin-id', $this->get_id() );
			
			$page_id = '';
			if ( null !== Plugin::$instance->documents->get_current() ) {
				$page_id = Plugin::$instance->documents->get_current()->get_main_id();
			}

			$this->parent->add_render_attribute( 'wrapper', 'data-page', $page_id );
			?>
			<div <?php $this->parent->print_render_attribute_string( 'container' ); ?>>
				<div <?php $this->parent->print_render_attribute_string( 'wrapper' ); ?>>
					<div class="swiper-wrapper">
					<?php while ( $query->have_posts() ) : $query->the_post(); ?>
						<?php $this->post_template(); ?>
					<?php endwhile; ?>
					</div>
					<?php if ( 'yes' === $settings['show_arrows'] ) : ?>
					<div class="swiper-button-prev"><svg xmlns="http://www.w3.org/2000/svg" width="21" height="40" fill="none"><path d="M3.589 20 20.564 2.556a1.498 1.498 0 1 0-2.149-2.09L.425 18.954a1.5 1.5 0 0 0 0 2.09l17.99 18.49a1.5 1.5 0 1 0 2.149-2.091L3.587 20h.002Z"/></svg></div>							
					<div class="swiper-button-next"><svg xmlns="http://www.w3.org/2000/svg" width="21" height="40" fill="none"><path d="M17.411 20 .436 2.556A1.5 1.5 0 1 1 2.585.466l17.99 18.489a1.5 1.5 0 0 1 0 2.09l-17.99 18.49a1.498 1.498 0 1 1-2.149-2.091L17.413 20h-.002Z"/></svg></div>
					<?php endif; ?>	
				</div>
				<?php if ( 'yes' === $settings['show_dots'] ) : ?>
				<div class="posts-carousel-pagination"></div>
				<?php endif; ?>
			</div>
			<?php

		endif; //end have_posts() check
		wp_reset_postdata();
	}

	/**
	 * Post template.
	 *
	 * @since 1.0.0
	 */
	public function post_template() {
		$settings = $this->parent->get_settings_for_display();

		$archive_meta_delimiter = $settings['delimiter'];

		global $post;
		$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post -> ID ), $settings['image_size'] );
		?>
		<div class="athemes-post-item swiper-slide" style="background-image:url(<?php echo ( !is_bool( $image ) ? esc_url( $image[0] ) : '' ); ?>);">
			<div class="post-content">
				<?php if ( $settings['show_cats'] ) : ?>
					<div class="post-info item-cats cats-<?php echo esc_attr( $settings['cat_display']); ?>">
					<?php athemes_addons_get_first_cat(); ?>
					</div>
				<?php endif; ?>	

				<?php if ( $settings['show_title'] ) {
						the_title( '<' . esc_attr( $settings['title_tag'] ) . ' class="item-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></' . esc_attr( $settings['title_tag'] ) . '>' );
					}
				?>

				<?php if ( 'yes' === $settings['show_excerpt_' . $this->get_id()] ) {
					$excerpt = wp_trim_words( get_the_content(), $settings['excerpt_length'], '&hellip;' );
					echo '<div class="item-excerpt">' . esc_html( $excerpt ) . '</div>';
				}   
				?>

				<div class="post-info delimiter-<?php echo esc_attr( $archive_meta_delimiter ); ?>">
					<?php if ( $settings['show_author'] ) : ?>
						<?php athemes_addons_get_post_author(); ?>
					<?php endif; ?>
					<?php if ( $settings['show_date'] ) : ?>
						<?php athemes_addons_get_post_date(); ?>
					<?php endif; ?>
				</div>

				<?php if ( $settings['read_more_text'] ) : ?>
					<div class="read-more-wrapper">
						<a class="post-item-read-more button" title="<?php the_title_attribute(); ?>" href="<?php the_permalink(); ?>">
							<?php echo esc_html( $settings['read_more_text'] ); ?>
						</a>
					</div>
				<?php endif; ?>		
			</div>	

		</div>
		<?php
	}
}

/**
 * Register skin.
 */
add_action( 'elementor/widget/athemes-addons-posts-carousel/skins_init', function( $widget ) {
    $widget->add_skin( new Posts_Carousel_Skin2( $widget ) );
} );