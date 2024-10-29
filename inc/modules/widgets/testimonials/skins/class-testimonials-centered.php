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
use Elementor\Skin_Base;
use Elementor\Group_Control_Image_Size;
use Elementor\Repeater;
use Elementor\Icons_Manager;
use Elementor\Control_Media;
use aThemes_Addons_SVG_Icons;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Posts list widget.
 *
 *
 * @since 1.0.0
 */
class Testimonials_Skin4 extends Skin_Base {

	/**
	 * Constructor.
	 */
	public function __construct( Widget_Base $parent ) {
		parent::__construct( $parent );
	}

	/**
	 * Get the id.
	 */
	public function get_id() {
		return 'athemes-addons-testimonials-centered';
	}

	/**
	 * Get the title.
	 */
	public function get_title() {
		return esc_html__( 'Centered', 'athemes-addons-elementor' );
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
		$settings = $this->parent->get_settings_for_display();
	
		$this->lightbox_slide_index = 0;
	
		$this->parent->add_render_attribute( 'testimonials-container', 'class', 'athemes-addons-testimonials-container' );
		$this->parent->add_render_attribute( 'testimonials', 'class', 'swiper-container athemes-addons-testimonials' );
		$this->parent->add_render_attribute( 'testimonials', 'data-id', esc_attr( $this->get_id() ) );
		$this->parent->add_render_attribute( 'testimonials', 'data-autoplay', esc_attr( $settings['autoplay'] ) );
		$this->parent->add_render_attribute( 'testimonials', 'data-autoplay-speed', esc_attr( $settings['autoplay_speed'] ) );
		$this->parent->add_render_attribute( 'testimonials', 'data-infinite', esc_attr( $settings['infinite'] ) );
		$this->parent->add_render_attribute( 'testimonials', 'data-pause-on-hover', esc_attr( $settings['pause_on_hover'] ) );
		$this->parent->add_render_attribute( 'testimonials', 'data-transition-speed', esc_attr( $settings['transition_speed'] ) );
		$this->parent->add_render_attribute( 'testimonials', 'data-arrows', esc_attr( $settings['show_arrows'] ) );
		$this->parent->add_render_attribute( 'testimonials', 'data-dots', esc_attr( $settings['show_dots'] ) );
		$this->parent->add_render_attribute( 'testimonials', 'data-items', isset( $settings['slides_to_show'] ) && '' !== $settings['slides_to_show'] ? esc_attr( $settings['slides_to_show'] ) : 2 );
		$this->parent->add_render_attribute( 'testimonials', 'data-items-tablet', isset( $settings['slides_to_show_tablet'] ) ? esc_attr( $settings['slides_to_show_tablet'] ) : 2 );
		$this->parent->add_render_attribute( 'testimonials', 'data-items-mobile', isset( $settings['slides_to_show_mobile'] ) ? esc_attr( $settings['slides_to_show_mobile'] ) : 1 );       
		?>
		<div <?php $this->parent->print_render_attribute_string( 'testimonials-container' ); ?>>
			<div <?php $this->parent->print_render_attribute_string( 'testimonials' ); ?>>
				<div class="swiper-wrapper">
					<?php $c = 0; ?>
					<?php foreach ( $settings['slides'] as $index => $slide ) : ?>
						<div class="swiper-slide">
							<?php if ( ! empty( $slide['link']['url'] ) ) : ?>
								<?php $this->parent->add_render_attribute( 'link-' . $c, 'class', 'overlay-link' ); ?>
								<?php $this->parent->add_render_attribute( 'link-' . $c, 'href', esc_url( $slide['link']['url'] ) ); ?>
								<?php $this->parent->add_render_attribute( 'link-' . $c, 'target', $slide['link']['is_external'] ? '_blank' : '_self' ); ?>
								<a <?php $this->parent->print_render_attribute_string( 'link-' . $c ); ?>><span class="screen-reader-text"><?php echo esc_html( $slide['name'] ); ?></span></a>
							<?php endif; ?>
	
							<div class="quote-icon">
								<?php aThemes_Addons_SVG_Icons::get_svg_icon( 'icon-quote' ); ?>
							</div>

							<div class="author-image">
								<?php $this->parent->render_image(  $slide, $settings ); ?>
							</div>
	
							<?php if ( !empty( $slide['title'] ) ) : ?>
								<h3 class="review-title"><?php echo esc_html( $slide['title'] ); ?></h3>
							<?php endif; ?>
	
							<?php if ( !empty( $slide['rating']['size'] ) ) : ?>
								<?php $this->parent->render_rating( $slide, $settings ); ?>        
							<?php endif; ?>
	
							<?php if ( !empty( $slide['content'] ) ) : ?>
								<div class="review-content"><?php echo wp_kses_post( $slide['content'] ); ?></div>
							<?php endif; ?>
	
							<?php if ( !empty( $slide['review_date'] ) ) : ?>
								<div class="review-date"><?php echo esc_html( $slide['review_date'] ); ?></div>
							<?php endif; ?>

							<div class="author-data">
							<?php if ( !empty( $slide['name'] ) ) : ?>
								<div class="author-name"><?php echo esc_html( $slide['name'] ); ?></div>
							<?php endif; ?>
							<?php if ( !empty( $slide['position'] ) ) : ?>
								<div class="author-position"><?php echo esc_html( $slide['position'] ); ?></div>
							<?php endif; ?>
							</div>
						</div>
						<?php $c++; ?>
					<?php endforeach; ?>
				</div>
	
				<?php if ( 'yes' === $settings['show_arrows'] ) : ?>
					<div class="swiper-button-prev"><?php aThemes_Addons_SVG_Icons::get_svg_icon( 'icon-arrow-left' ); ?></div>                            
					<div class="swiper-button-next"><?php aThemes_Addons_SVG_Icons::get_svg_icon( 'icon-arrow-right' ); ?></div>
				<?php endif; ?>              
			</div>
	
			<?php if ( 'yes' === $settings['show_dots'] ) : ?>
				<div class="testimonials-pagination"></div>
			<?php endif; ?>
		</div>
		<?php if ( 'yes' === $settings['show_thumbs'] ) : ?>
		<div class="athemes-addons-testimonials-thumbs">
			<div class="swiper-wrapper swiper-thumb-wrapper">
				<?php foreach ( $settings['slides'] as $index => $item ) : ?>
					<div class="swiper-slide">
						<?php if ( $item['image']['url'] ) : ?>
							<?php $this->parent->render_image(  $item, $settings ); ?>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<?php endif; ?>  
		<?php   
	}
}   

/**
 * Register skin.
 */
add_action( 'elementor/widget/athemes-addons-testimonials/skins_init', function( $widget ) {
    $widget->add_skin( new Testimonials_Skin4( $widget ) );
} );