<?php
/**
 * Variable product add to cart — Noble theme layout.
 *
 * @package noble-theme
 * @version 9.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

$variations_json = wp_json_encode( $available_variations );
$variations_attr = function_exists( 'wc_esc_json' ) ? wc_esc_json( $variations_json ) : _wp_specialchars( $variations_json, ENT_QUOTES, 'UTF-8', true );

$variation_attribute_keys = array();
foreach ( (array) $available_variations as $variation_data ) {
	if ( empty( $variation_data['attributes'] ) || ! is_array( $variation_data['attributes'] ) ) {
		continue;
	}
	foreach ( $variation_data['attributes'] as $variation_attr_key => $variation_attr_value ) {
		if ( '' === (string) $variation_attr_value ) {
			continue;
		}
		$variation_attr_key = str_replace( 'attribute_', '', (string) $variation_attr_key );
		$variation_attribute_keys[] = sanitize_title( $variation_attr_key );
	}
}
$variation_attribute_keys = array_values( array_unique( array_filter( $variation_attribute_keys ) ) );

do_action( 'woocommerce_before_add_to_cart_form' );
?>

<form class="variations_form cart noble-variations-form" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype="multipart/form-data" data-product_id="<?php echo absint( $product->get_id() ); ?>" data-product_variations="<?php echo $variations_attr; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>">
	<?php do_action( 'woocommerce_before_variations_form' ); ?>

	<?php if ( empty( $available_variations ) && false !== $available_variations ) : ?>
		<p class="stock out-of-stock"><?php echo esc_html( apply_filters( 'woocommerce_out_of_stock_message', __( 'This product is currently out of stock and unavailable.', 'woocommerce' ) ) ); ?></p>
	<?php else : ?>
		<div class="noble-variations-fields">
			<table class="variations noble-variations-table" cellspacing="0" role="presentation">
				<tbody>
					<?php foreach ( $attributes as $attribute_name => $options ) : ?>
						<?php
						$normalized_attribute_name = sanitize_title( $attribute_name );
						if ( ! empty( $variation_attribute_keys ) && ! in_array( $normalized_attribute_name, $variation_attribute_keys, true ) ) {
							continue;
						}
						?>
						<tr class="noble-variation-row noble-variation-card">
							<th class="label" scope="row">
								<label for="<?php echo esc_attr( sanitize_title( $attribute_name ) ); ?>">
									<?php echo wc_attribute_label( $attribute_name ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								</label>
							</th>
							<td class="value">
								<div class="noble-variation-select-shell">
									<?php
									wc_dropdown_variation_attribute_options(
										array(
											'options'          => $options,
											'attribute'        => $attribute_name,
											'product'          => $product,
											'class'            => 'noble-variation-select',
											'show_option_none' => __( 'انتخاب کنید…', 'noble-theme' ),
										)
									);
									?>
								</div>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			<?php if ( ! empty( $variation_attribute_keys ) ) : ?>
				<div class="noble-variations-toolbar">
					<a class="reset_variations noble-reset-variations" href="#" aria-label="<?php echo esc_attr__( 'پاک کردن انتخاب‌ها', 'noble-theme' ); ?>">
						<span class="material-symbols-outlined noble-reset-variations__icon" aria-hidden="true">restart_alt</span>
						<?php esc_html_e( 'پاک کردن انتخاب‌ها', 'noble-theme' ); ?>
					</a>
				</div>
			<?php endif; ?>
		</div>
		<div class="reset_variations_alert screen-reader-text" role="alert" aria-live="polite" aria-relevant="all"></div>
		<?php do_action( 'woocommerce_after_variations_table' ); ?>

		<div class="single_variation_wrap noble-variation-cta-wrap">
			<?php
			do_action( 'woocommerce_before_single_variation' );
			do_action( 'woocommerce_single_variation' );
			do_action( 'woocommerce_after_single_variation' );
			?>
		</div>
	<?php endif; ?>

	<?php do_action( 'woocommerce_after_variations_form' ); ?>
</form>

<?php
do_action( 'woocommerce_after_add_to_cart_form' );
