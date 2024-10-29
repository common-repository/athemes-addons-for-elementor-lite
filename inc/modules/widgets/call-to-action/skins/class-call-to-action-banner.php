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
use Elementor\Icons_Manager;

use aThemes_Addons\Traits\Button_Trait;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Posts list widget.
 *
 *
 * @since 1.0.0
 */
class Call_To_Action_Skin2 extends Skin_Base {

	use Button_Trait;

	/**
	 * Constructor.
	 */
	public function __construct( Widget_Base $parent ) {
		parent::__construct( $parent );

		//add_action( 'elementor/element/athemes-addons-posts-list/section_style_card/after_section_end', [ $this, 'update_card_controls' ] );
		//add_action( 'elementor/element/athemes-addons-posts-list/section_item_settings/after_section_end', [ $this, 'update_element_controls' ] );
	}

	/**
	 * Get the id.
	 */
	public function get_id() {
		return 'athemes-addons-cta-banner';
	}

	/**
	 * Get the title.
	 */
	public function get_title() {
		return esc_html__( 'Banner', 'athemes-addons-elementor' );
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

		$this->parent->add_render_attribute( 'wrapper', 'class', 'athemes-addons-call-to-action' );

		$this->parent->add_render_attribute( 'wrapper', 'class', 'content-layout-' . $settings['content_layout'] );
		
		$image = Group_Control_Image_Size::get_attachment_image_src( $settings['image']['id'], 'thumb', $settings );
		?>

		<div <?php $this->parent->print_render_attribute_string( 'wrapper' ); ?>>
			<div class="call-to-action-inner" style="background-image:url(<?php echo esc_url( $image ); ?>);">
				<div class="call-to-action-content">
					<div class="call-to-action-content-inner">
						<div class="content-icon">
							<?php if ( 'icon' === $settings['icon_type'] && ! empty( $settings['content_icon']['value'] ) ) : ?>
								<div><?php Icons_Manager::render_icon( $settings['content_icon'], [ 'aria-hidden' => 'true' ] ); ?></div>
							<?php elseif ( 'image' === $settings['icon_type'] && ! empty( $settings['icon_image']['url'] ) ) : ?>
								<img src="<?php echo esc_url( $settings['icon_image']['url'] ); ?>" alt="<?php echo esc_attr( $settings['title'] ); ?>">
							<?php endif; ?>
						</div>	
						<?php if ( ! empty( $settings['before_title'] ) ) : ?>
							<div class="call-to-action-before-title"><?php echo esc_html( $settings['before_title'] ); ?></div>
						<?php endif; ?>
						<?php if ( ! empty( $settings['title'] ) ) : ?>
							<<?php echo esc_html( $settings['title_html_tag'] ); ?> class="call-to-action-title"><?php echo wp_kses_post( $settings['title'] ); ?></<?php echo esc_html( $settings['title_html_tag'] ); ?>>
						<?php endif; ?>
						<?php if ( ! empty( $settings['content'] ) ) : ?>
							<div class="call-to-action-text"><?php echo wp_kses_post( $settings['content'] ); ?></div>
						<?php endif; ?>
					</div>
					<div class="call-to-action-buttons">
					<?php $this->render_button( $this->parent, $class = 'first_button' ); ?>
					<?php
					if ( '2' === $settings['number_of_buttons'] ) :
						$this->render_button( $this->parent, $class = 'second_button' );
					endif;
					?>
					</div>
				</div>
				<div class="cta-overlay"></div>
			</div>
		</div>
		<?php
	}   
}

/**
 * Register skin.
 */
add_action( 'elementor/widget/athemes-addons-call-to-action/skins_init', function( $widget ) {
    $widget->add_skin( new Call_To_Action_Skin2( $widget ) );
} );