<?php
defined( 'ABSPATH' ) || exit;

global $product;

// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}
?>
<li <?php wc_product_class( '', $product ); ?>>

	<?php if ( $settings['show_image'] ) : ?>
	<div class="product-image">
		<?php
			woocommerce_template_loop_product_link_open();

			if ( $product->is_on_sale() )  {
				echo '<span class="onsale">' . esc_html( $settings['sale_badge_text'] ) . '</span>';
			}

			woocommerce_template_loop_product_thumbnail();

			woocommerce_template_loop_product_link_close();
		?>
		<div class="product-actions">
		<?php
			if ( isset( $settings['enable_merchant_quick_view'] ) && 'yes' === $settings['enable_merchant_quick_view'] ) {
				athemes_addons_render_element( 'merchant_quick_view' );
			}

			if ( isset( $settings['enable_merchant_wishlist'] ) && 'yes' === $settings['enable_merchant_wishlist'] ) {
				athemes_addons_render_element( 'merchant_wishlist' ); 
			}
		?>
		</div>	
	</div>
	<?php endif; ?>

	<div class="product-content">
	<?php
		if ( $settings['show_rating'] ) {
			woocommerce_template_loop_rating();
		}

		if ( $settings['show_category'] ) {
			athemes_addons_woo_categories();
		}

		echo '<' . tag_escape( $settings['title_html_tag'] ) . ' class="' . esc_attr( apply_filters( 'woocommerce_product_loop_title_classes', 'woocommerce-loop-product__title' ) ) . '">' . esc_html( get_the_title() ) . '</' . tag_escape( $settings['title_html_tag'] ) . '>';
		
		if ( $settings['show_short_description'] ) {
			echo '<div class="product-short-description">' . esc_html( $product->get_short_description() ) . '</div>';
		}

		if ( $settings['show_price'] ) {
			woocommerce_template_loop_price();
		}
	?>
	</div>

	<div class="product-buttons">
	<?php
		if ( $product->is_type( 'variable' ) && isset( $settings['enable_merchant_product_swatches'] ) && 'yes' === $settings['enable_merchant_product_swatches']) {
			athemes_addons_render_element( 'merchant_product_swatches' );
		}

		if ( $settings['show_add_to_cart'] ) {
			woocommerce_template_loop_add_to_cart();
		}
		
		if ( isset( $settings['enable_merchant_buy_now'] ) && 'yes' === $settings['enable_merchant_buy_now'] ) {
			athemes_addons_render_element( 'merchant_buy_now' );
		}
	?>
	</div>
</li>