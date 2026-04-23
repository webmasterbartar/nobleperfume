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
								<?php
								$attribute_key   = sanitize_title( $attribute_name );
								$request_key     = 'attribute_' . $attribute_key;
								$current_value   = isset( $_REQUEST[ $request_key ] ) ? wc_clean( wp_unslash( (string) $_REQUEST[ $request_key ] ) ) : $product->get_variation_default_attribute( $attribute_key );
								$is_taxonomy     = taxonomy_exists( $attribute_name );
								$option_buttons  = array();

								foreach ( (array) $options as $option ) {
									$option_value = (string) $option;
									$option_label = $option_value;

									if ( $is_taxonomy ) {
										$term = get_term_by( 'slug', $option_value, $attribute_name );
										if ( $term && ! is_wp_error( $term ) && isset( $term->name ) ) {
											$option_label = (string) $term->name;
										}
									}

									$option_buttons[] = array(
										'value' => $option_value,
										'label' => $option_label,
									);
								}
								?>
								<div class="noble-variation-select-shell noble-variation-select-shell--sr">
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
								<div class="noble-variation-options" role="radiogroup" aria-label="<?php echo esc_attr( wc_attribute_label( $attribute_name ) ); ?>" data-attribute-name="<?php echo esc_attr( $attribute_key ); ?>">
									<?php foreach ( $option_buttons as $option_button ) : ?>
										<?php
										$is_selected = ( '' !== (string) $current_value && (string) $current_value === (string) $option_button['value'] );
										?>
										<button
											type="button"
											class="noble-variation-option<?php echo $is_selected ? ' is-selected' : ''; ?>"
											data-value="<?php echo esc_attr( (string) $option_button['value'] ); ?>"
											role="radio"
											aria-checked="<?php echo $is_selected ? 'true' : 'false'; ?>"
										>
											<span class="noble-variation-option__text"><?php echo esc_html( (string) $option_button['label'] ); ?></span>
										</button>
									<?php endforeach; ?>
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
