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
class Posts_List_Skin2 extends Skin_Base {

	/**
	 * Constructor.
	 */
	public function __construct( Widget_Base $parent ) {
		parent::__construct( $parent );

		add_action( 'elementor/element/athemes-addons-posts-list/section_style_card/after_section_end', [ $this, 'update_card_controls' ] );
		add_action( 'elementor/element/athemes-addons-posts-list/section_item_settings/after_section_end', [ $this, 'update_element_controls' ] );
	}

	/**
	 * Get the id.
	 */
	public function get_id() {
		return 'athemes-addons-posts-list-banner';
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
		<div class="athemes-post-item" style="background-image:url(<?php echo ( !is_bool( $image ) ? esc_url( $image[0] ) : '' ); ?>);">
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
add_action( 'elementor/widget/athemes-addons-posts-list/skins_init', function( $widget ) {
    $widget->add_skin( new Posts_List_Skin2( $widget ) );
} );