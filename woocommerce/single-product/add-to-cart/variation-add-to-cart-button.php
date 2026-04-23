<?php
/**
 * Variation add to cart — Noble theme (qty steppers + primary CTA).
 *
 * @package noble-theme
 * @version 10.5.2
 */

defined( 'ABSPATH' ) || exit;

global $product;

$min_value   = $product->get_min_purchase_quantity();
$max_value   = $product->get_max_purchase_quantity();
$input_value = isset( $_POST['quantity'] ) ? wc_stock_amount( wp_unslash( $_POST['quantity'] ) ) : $product->get_min_purchase_quantity();
$in_cart     = false;

if ( function_exists( 'WC' ) && WC() && WC()->cart ) {
	$in_cart = WC()->cart->get_cart_contents_count() > 0;
}

$button_class = 'single_add_to_cart_button button alt noble-simple-submit noble-variable-submit flex-1 w-full bg-primary text-white h-14 rounded-2xl font-bold text-md hover:bg-primary/90 transition-all flex items-center justify-center gap-3';
if ( function_exists( 'wc_wp_theme_get_element_class_name' ) ) {
	$theme_btn = wc_wp_theme_get_element_class_name( 'button' );
	if ( $theme_btn ) {
		$button_class .= ' ' . $theme_btn;
	}
}
?>
<div class="woocommerce-variation-add-to-cart variations_button noble-variable-atc flex flex-col sm:flex-row items-stretch sm:items-center gap-4 pt-1">
	<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>
	<?php do_action( 'woocommerce_before_add_to_cart_quantity' ); ?>

	<div class="noble-simple-qty noble-variable-qty flex items-center bg-white border border-primary/10 rounded-2xl p-1 w-full sm:w-auto">
		<button class="w-12 h-12 flex items-center justify-center text-primary hover:bg-primary/5 rounded-xl transition-colors noble-qty-btn" type="button" data-action="minus" aria-label="<?php esc_attr_e( 'کاهش تعداد', 'noble-theme' ); ?>">
			<span class="material-symbols-outlined" aria-hidden="true">remove</span>
		</button>
		<?php
		woocommerce_quantity_input(
			array(
				'min_value'   => $min_value,
				'max_value'   => $max_value,
				'input_value' => $input_value,
				'classes'     => array( 'input-text', 'qty', 'text', 'noble-qty-input' ),
			)
		);
		?>
		<button class="w-12 h-12 flex items-center justify-center text-primary hover:bg-primary/5 rounded-xl transition-colors noble-qty-btn" type="button" data-action="plus" aria-label="<?php esc_attr_e( 'افزایش تعداد', 'noble-theme' ); ?>">
			<span class="material-symbols-outlined" aria-hidden="true">add</span>
		</button>
	</div>

	<?php do_action( 'woocommerce_after_add_to_cart_quantity' ); ?>

	<button
		type="submit"
		class="<?php echo esc_attr( $button_class . ( $in_cart ? ' noble-atc-in-cart' : '' ) ); ?>"
		data-noble-in-cart="<?php echo $in_cart ? '1' : '0'; ?>"
	>
		<span class="material-symbols-outlined text-xl" aria-hidden="true">shopping_bag</span>
		<?php echo esc_html( $product->single_add_to_cart_text() ); ?>
	</button>

	<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>

	<input type="hidden" name="add-to-cart" value="<?php echo absint( $product->get_id() ); ?>" />
	<input type="hidden" name="product_id" value="<?php echo absint( $product->get_id() ); ?>" />
	<input type="hidden" name="variation_id" class="variation_id" value="0" />
</div>
