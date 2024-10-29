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
 * Posts list widget.
 *
 *
 * @since 1.0.0
 */
class Posts_List_Skin1 extends Skin_Base {

	/**
	 * Constructor.
	 */
	public function __construct( Widget_Base $parent ) {
		parent::__construct( $parent );

		add_action( 'elementor/element/athemes-addons-posts-list/section_style_image/after_section_end', [ $this, 'update_image_controls' ] );
		add_action( 'elementor/element/athemes-addons-posts-list/section_style_meta/after_section_end', [ $this, 'update_category_controls' ] );
	}

	/**
	 * Update image controls.
	 * 
	 * @since 1.0.0
	 */
	public function update_image_controls( $controls ) {

		$controls->start_injection( [
			'at' => 'after',
			'of' => 'image_border_radius',
		] );

		$controls->add_control(
			'image_border_radius_' . $this->get_id(),
			[
				'label' => __( 'Border radius', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => '15',
					'right' => '15',
					'bottom' => '15',
					'left' => '15',
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .athemes-post-item .post-item-thumb img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],      
				'condition' => [
					'_skin' => $this->get_id(),
				],
			]
		);

		$controls->end_injection();
	}

	/**
	 * Update category controls.
	 */
	public function update_category_controls( $controls ) {

		$controls->start_injection( [
			'at' => 'after',
			'of' => 'show_cats',
		] );

		$controls->add_control(
			'cat_display_' . $this->get_id(),
			[
				'label' => __( 'Category display', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'label',
				'options' => [
					'link'      => __( 'Link', 'athemes-addons-elementor' ),
					'label'     => __( 'Label', 'athemes-addons-elementor' ),
				],
				'condition' => [
					'show_cats' => 'yes',
					'_skin' => $this->get_id(),
				],
			]
		);

		$controls->end_injection();
	}

	/**
	 * Get the id.
	 */
	public function get_id() {
		return 'athemes-addons-posts-list-modern';
	}

	/**
	 * Get the title.
	 */
	public function get_title() {
		return esc_html__( 'Modern', 'athemes-addons-elementor' );
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

		$edit_mode = \Elementor\Plugin::$instance->editor->is_edit_mode();

		if ( 'main' === $settings['query_type'] ) {
			if ( !$edit_mode ) {
				global $wp_query;
				$query = $wp_query;
			} else {
				//for the editor preview
				$type = $settings['post_type'];

				$args = array(
					'post_type'         => $type,
					'posts_per_page'    => 9,
					'post_status'       => 'publish',
					'no_found_rows'     => true,
				);
	
				$query = new \WP_Query( $args );
			}
		}
		
		if ( $query->have_posts() ) :

			if ( 'yes' === $settings['paginated_posts'] ) {

				$total_pages = $query->max_num_pages;

				Posts_Helper::$page_limit = $total_pages;

				if ( ! empty( $settings['max_pages'] ) ) {
					$total_pages = min( $settings['max_pages'], $total_pages );
				}
			}

			//add render attribute to the wrapper
			$this->parent->add_render_attribute( 'wrapper', 'class', 'athemes-addons-posts-list elementor-grid' );

			$this->parent->add_render_attribute( 'wrapper', 'class', 'style-' . $settings['post_style'] );

			$this->parent->add_render_attribute( 'wrapper', 'data-skin-id', $this->get_id() );
			
			$page_id = '';
			if ( null !== Plugin::$instance->documents->get_current() ) {
				$page_id = Plugin::$instance->documents->get_current()->get_main_id();
			}

			$this->parent->add_render_attribute( 'wrapper', 'data-page', $page_id );

			if ( 'yes' === $settings['paginated_posts'] && $total_pages > 1 ) {
				$this->parent->add_render_attribute( 'wrapper', 'data-pagination', 'true' );
			}

			?>
			<div <?php $this->parent->print_render_attribute_string( 'wrapper' ); ?>>
				<?php while ( $query->have_posts() ) : $query->the_post(); ?>
					<?php $this->post_template(); ?>
				<?php endwhile; ?>
			</div>
			<?php 

			if ( 'yes' === $settings['paginated_posts'] && $total_pages > 1 ) { ?>
				<div class="athemes-posts-pagination">
				<?php $helper->get_pagination( $settings ); ?>
				</div>
			<?php }

		endif; //end have_posts() check
		wp_reset_postdata();
	}   

		/**
	 * Loop item
	 */
	public function post_template() {
		$settings = $this->parent->get_settings_for_display();

		$archive_meta_delimiter = $settings['delimiter'];

		$this->parent->add_render_attribute( 'post-item', 'class', 'athemes-post-item' );

		if ( has_post_thumbnail() && $settings['show_thumbnail'] ) {
			$this->parent->add_render_attribute( 'post-item', 'class', 'has-thumbnail' );
		}
		?>
		<div <?php $this->parent->print_render_attribute_string( 'post-item' ); ?>>
			<?php if ( has_post_thumbnail() && $settings['show_thumbnail'] ) : ?>
			<div class="post-item-thumb position-<?php echo esc_attr( $settings['image_position'] ); ?>">
				<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_post_thumbnail( $settings['image_size'] ); ?></a>
			</div>
			<?php endif; ?>		

			<div class="post-content">
				<?php if ( $settings['show_cats'] ) : ?>
					<div class="post-info item-cats cats-<?php echo esc_attr( $settings['cat_display_' . $this->get_id()] ); ?>">
					<?php athemes_addons_get_first_cat(); ?>
					</div>
				<?php endif; ?>	

				<?php if ( $settings['show_title'] ) {
						the_title( '<' . esc_attr( $settings['title_tag'] ) . ' class="item-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></' . esc_attr( $settings['title_tag'] ) . '>' );
					}
				?>

				<?php if ( 'yes' === $settings['show_excerpt'] ) {
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
add_action( 'elementor/widget/athemes-addons-posts-list/skins_init', function( $widget ) {
    $widget->add_skin( new Posts_List_Skin1( $widget ) );
} );