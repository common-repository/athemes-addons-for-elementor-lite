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
class Posts_List_Skin3 extends Skin_Base {

	/**
	 * Constructor.
	 */
	public function __construct( Widget_Base $parent ) {
		parent::__construct( $parent );

		add_action( 'elementor/element/athemes-addons-posts-list/section_style_titles/after_section_end', [ $this, 'counter_controls' ] );
	}

	/**
	 * Get the id.
	 */
	public function get_id() {
		return 'athemes-addons-posts-list-title-list';
	}

	/**
	 * Get the title.
	 */
	public function get_title() {
		return esc_html__( 'Title List', 'athemes-addons-elementor' );
	}

	/**
	 * Update counter controls.
	 * 
	 * @since 1.0.0
	 */
	public function counter_controls( $controls ) {

		$controls->start_controls_section(
			'section_counter_style',
			[
				'label' => __( 'Counter', 'athemes-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'_skin' => $this->get_id(),
				],
			]
		);
		
		$controls->add_control(
			'counter_color',
			[
				'label' => __( 'Color', 'athemes-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .list-counter' => 'color: {{VALUE}};',
				],
				'condition' => [
					'_skin' => $this->get_id(),
				],
			]
		);

		$controls->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'counter_typography',
				'selector' => '{{WRAPPER}} .list-counter',
				'condition' => [
					'_skin' => $this->get_id(),
				],
			]
		);

		$controls->end_controls_section();
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
			$this->parent->add_render_attribute( 'wrapper', 'class', 'athemes-addons-posts-list' );

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
				<?php $c = 1; ?>
				<?php while ( $query->have_posts() ) : $query->the_post(); ?>
					<?php $this->post_template( $c ); ?>
					<?php $c++; ?>
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
	public function post_template( $c ) {
		$settings = $this->parent->get_settings_for_display();

		$archive_meta_delimiter = $settings['delimiter'];
		?>
		<div class="athemes-post-item">
			<div class="post-content">
				<div class="post-content-inner">
					<div class="list-counter">
						<?php echo esc_attr( ($c < 10) ? '0' . $c : $c ) . '.'; ?>
					</div>

					<?php if ( $settings['show_title'] ) {
							the_title( '<' . esc_attr( $settings['title_tag'] ) . ' class="item-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></' . esc_attr( $settings['title_tag'] ) . '>' );
						}
					?>
				</div>

				<div class="post-info">
					<?php if ( $settings['show_date'] ) : ?>
						<?php athemes_addons_get_post_date(); ?>
					<?php endif; ?>
				</div>				

			</div>	

		</div>
		<?php
	}
}

/**
 * Register skin.
 */
add_action( 'elementor/widget/athemes-addons-posts-list/skins_init', function( $widget ) {
    $widget->add_skin( new Posts_List_Skin3( $widget ) );
} );