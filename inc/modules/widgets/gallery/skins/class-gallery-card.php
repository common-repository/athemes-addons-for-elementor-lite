<?php
namespace aThemes_Addons\Widgets;

use Elementor\Plugin;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Skin_Base;
use Elementor\Group_Control_Image_Size;
use Elementor\Control_Media;
use Elementor\Icons_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Posts list widget.
 *
 *
 * @since 1.0.0
 */
class Gallery_Skin2 extends Skin_Base {

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
		return 'athemes-addons-gallery-card';
	}

	/**
	 * Get the title.
	 */
	public function get_title() {
		return esc_html__( 'Card', 'athemes-addons-elementor' );
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
		
		$open_lightbox = isset( $settings['open_lightbox'] ) ? $settings['open_lightbox'] : null;
		
		$this->parent->add_render_attribute( 'gallery', 'class', 'athemes-addons-gallery' );
	
		$this->parent->add_render_attribute( 'gallery', 'class', $this->get_id() );
		?>

		<div <?php $this->parent->print_render_attribute_string( 'gallery' ); ?> >
			<div class="gallery-inner">

				<?php if ( 'yes' === $settings['show_filter'] ) : ?>
				<div class="gallery-filter">
					<a href="#" class="active" data-filter="*"><?php echo esc_html( $settings['show_all_text'] ); ?></a>

					<?php $filter_items = array(); ?>

					<?php foreach ( $settings['portfolio_list'] as $index => $item ) : ?>
						<?php
						$term = explode( "\n", $item['term'] );

						foreach ( $term as $t ) {
							$t = explode( ':', $t );

							if ( ! isset( $t[0] ) || ! isset( $t[1] ) ) {
								continue;
							}

							if ( ! in_array( $t[0], $filter_items ) ) { // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
								$filter_items[$t[0]] = $t[1];
							}
						}
						?>
					<?php endforeach; ?>

					<?php foreach ( $filter_items as $key => $value ) : ?>
						<a href="#" data-filter=".<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $value ); ?></a>
					<?php endforeach; ?>

				</div>
				<?php endif; ?>

				<div class="gallery-items">
					<?php $c = 0; ?>
					<?php foreach ( $settings['portfolio_list'] as $index => $item ) : ?>
						
					<div class="aafe-gallery-item <?php echo esc_attr( $this->parent->prepare_term( $item['term'] ) ); ?>">
						<div class="item-inner">
							<?php
							$this->parent->add_render_attribute( 'link-' . $c, 'class', 'elementor-clickable', true );


							$this->parent->add_lightbox_data_attributes( 'link-' . $c, $item['image']['id'], $open_lightbox, $this->parent->get_id(), true );

							$this->parent->add_render_attribute( 'link-' . $c, 'href', esc_url( $item['image']['url'] ) );

							if ( 'video' === $item['lightbox_content'] && $item['video_link'] ) {
								$this->parent->add_render_attribute( 'link-' . $c, 'data-elementor-lightbox-video', $item['video_link'] );
							}
							?>

							<?php if ( $item['image']['url'] ) :
								$this->parent->add_render_attribute( 'image-' . $index, 'src', $item['image']['url'] );
								$this->parent->add_render_attribute( 'image-' . $index, 'alt', Control_Media::get_image_alt( $item['image'] ) );                            
							?>
							<div class="gallery-item-image" data-effect="<?php echo esc_attr( $settings['image_hover_effect'] ); ?>">
								<img <?php $this->parent->print_render_attribute_string( 'image-' . $index ); ?>/>
							</div>
							<?php endif; ?>

							<?php
								if ( ! empty( $item['link']['url'] ) ) {
									$this->parent->add_render_attribute( 'button-' . $c, 'href', esc_url( $item['link']['url'] ) );

									if ( $item['link']['is_external'] ) {
										$this->parent->add_render_attribute( 'button-' . $c, 'target', '_blank' );
									}

									if ( $item['link']['nofollow'] ) {
										$this->parent->add_render_attribute( 'button-' . $c, 'rel', 'nofollow' );
									}
								}
							?>

							<?php if ( 'image' === $item['lightbox_content'] ) : ?>
							<div class="gallery-item-content">
								<div class="gallery-item-icons">
									<?php if ( ! empty( $item['link']['url'] ) ) : ?>
										<a <?php $this->parent->print_render_attribute_string( 'button-' . $c ); ?>>
											<?php Icons_Manager::render_icon( $settings['link_icon'], [ 'aria-hidden' => 'true' ] ); ?>
										</a>
									<?php endif; ?>

									<?php if ( $item['image']['url'] && 'yes' === $open_lightbox ) : ?>
										<a <?php $this->parent->print_render_attribute_string( 'link-' . $c ); ?>>
											<?php Icons_Manager::render_icon( $settings['lightbox_icon'], [ 'aria-hidden' => 'true' ] ); ?>
										</a>
									<?php endif; ?>
								</div>
							</div>
							<?php else : ?>
							<div class="gallery-item-video-icon">
								<a <?php $this->parent->print_render_attribute_string( 'link-' . $c ); ?>>
									<?php Icons_Manager::render_icon( $settings['video_icon'], [ 'aria-hidden' => 'true' ] ); ?>
								</a>
							</div>
							<?php endif; ?>
						</div>
						
						<div class="gallery-item-ext-content">
						<<?php echo esc_attr( $settings['title_html_tag'] ); ?> class="item-title">
							<?php if ( ! empty( $item['link']['url'] ) ) : ?>
								<a <?php $this->parent->print_render_attribute_string( 'button-' . $c ); ?>>
							<?php endif; ?>	
							<?php echo esc_html( $item['title'] ); ?>
							<?php if ( ! empty( $item['link']['url'] ) ) : ?>
								</a>
							<?php endif; ?>
						</<?php echo esc_attr( $settings['title_html_tag'] ); ?>>

						<?php if ( ! empty( $item['content'] ) ) : ?>
							<div class="item-content"><?php echo wp_kses_post( $item['content'] ); ?></div>
						<?php endif; ?>	
						</div>
					
					</div>

					<?php $c++; ?>
					<?php endforeach; ?>
				</div>
			</div>
		</div>

		<?php if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) : ?>
			<style>
				.athemes-addons-gallery {
					--gallery-gap: <?php echo esc_attr( $settings['gutter']['size'] ); ?>px;
				}
			</style>
		<?php endif; ?>

		<?php
	}   
}

/**
 * Register skin.
 */
add_action( 'elementor/widget/athemes-addons-gallery/skins_init', function( $widget ) {
    $widget->add_skin( new Gallery_Skin2( $widget ) );
} );