<?php
/**
 * WooCommerce fallback template.
 *
 * @package noble-theme
 */

defined( 'ABSPATH' ) || exit;

// Ensure shop/product taxonomy archives use the dedicated archive template.
if ( is_shop() || is_product_taxonomy() ) {
	wc_get_template( 'archive-product.php' );
	return;
}

// Ensure single product pages use the dedicated single template.
if ( is_product() ) {
	wc_get_template( 'single-product.php' );
	return;
}

get_header();
?>
<div class="noble-woocommerce">
	<?php woocommerce_content(); ?>
</div>
<?php
get_footer();
