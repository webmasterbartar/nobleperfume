<?php
/**
 * WooCommerce support.
 *
 * @package noble-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function noble_add_woocommerce_support() {
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'noble_add_woocommerce_support' );

function noble_woocommerce_wrapper_start() {
	echo '<section class="container mx-auto px-8 py-12">';
}

function noble_woocommerce_wrapper_end() {
	echo '</section>';
}

remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
add_action( 'woocommerce_before_main_content', 'noble_woocommerce_wrapper_start', 10 );
add_action( 'woocommerce_after_main_content', 'noble_woocommerce_wrapper_end', 10 );

/**
 * Move Out of Stock products to the bottom of the archive queries.
 */
add_filter( 'posts_clauses', 'noble_order_by_stock_status', 2000, 2 );
function noble_order_by_stock_status( $posts_clauses, $query ) {
	global $wpdb;
	if ( is_admin() || ! $query->is_main_query() ) {
		return $posts_clauses;
	}
	if ( is_woocommerce() && ( is_shop() || is_product_category() || is_product_tag() ) ) {
		if ( strpos( $posts_clauses['join'], 'wc_product_meta_lookup' ) === false ) {
			$posts_clauses['join'] .= " LEFT JOIN {$wpdb->wc_product_meta_lookup} wc_product_meta_lookup ON $wpdb->posts.ID = wc_product_meta_lookup.product_id ";
		}
		// 'instock' < 'outofstock' alphabetically
		$posts_clauses['orderby'] = " wc_product_meta_lookup.stock_status ASC, " . $posts_clauses['orderby'];
	}
	return $posts_clauses;
}

/**
 * Replace English variation labels with Persian wording.
 *
 * @param string              $label   Attribute label.
 * @param string              $name    Attribute name.
 * @param WC_Product|false    $product Product object.
 * @return string
 */
function noble_localize_attribute_labels( $label, $name, $product ) {
	$normalized = strtolower( (string) $name );
	if ( in_array( $normalized, array( 'volume', 'pa_volume' ), true ) ) {
		return 'مقدار';
	}
	return $label;
}
add_filter( 'woocommerce_attribute_label', 'noble_localize_attribute_labels', 10, 3 );

/**
 * Apply quiz/archive attribute filters (filter_pa_*) to main WooCommerce query.
 *
 * @param WP_Query $query Query object.
 * @return void
 */
function noble_apply_attribute_filters_to_shop_query( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( ! ( is_shop() || is_product_taxonomy() ) ) {
		return;
	}

	$tax_query = (array) $query->get( 'tax_query' );
	if ( empty( $tax_query ) ) {
		$tax_query = array();
	}
	$meta_query = (array) $query->get( 'meta_query' );
	if ( empty( $meta_query ) ) {
		$meta_query = array();
	}

	foreach ( $_GET as $key => $value ) {
		$key = (string) $key;
		if ( 0 !== strpos( $key, 'filter_pa_' ) ) {
			continue;
		}

		$taxonomy = substr( $key, 7 ); // remove "filter_".
		if ( ! taxonomy_exists( $taxonomy ) ) {
			continue;
		}

		$values = array();
		if ( is_array( $value ) ) {
			foreach ( $value as $item ) {
				$values[] = rawurldecode( wp_unslash( (string) $item ) );
			}
		} else {
			$values[] = rawurldecode( wp_unslash( (string) $value ) );
		}

		$terms = array();
		foreach ( $values as $raw_value ) {
			if ( '' === trim( $raw_value ) ) {
				continue;
			}
			foreach ( explode( ',', $raw_value ) as $chunk ) {
				$chunk = trim( (string) $chunk );
				if ( '' !== $chunk ) {
					$terms[] = $chunk;
				}
			}
		}
		$terms = array_values( array_unique( $terms ) );
		if ( empty( $terms ) ) {
			continue;
		}

		$tax_query[] = array(
			'taxonomy' => $taxonomy,
			'field'    => 'slug',
			'terms'    => $terms,
			'operator' => 'IN',
		);
	}

	if ( count( $tax_query ) > 1 ) {
		$tax_query['relation'] = 'AND';
	}

	$min_price = isset( $_GET['min_price'] ) ? floatval( wp_unslash( (string) $_GET['min_price'] ) ) : null;
	$max_price = isset( $_GET['max_price'] ) ? floatval( wp_unslash( (string) $_GET['max_price'] ) ) : null;
	if ( null !== $min_price || null !== $max_price ) {
		if ( null !== $min_price && null !== $max_price && $max_price > 0 ) {
			$meta_query[] = array(
				'key'     => '_price',
				'value'   => array( $min_price, $max_price ),
				'compare' => 'BETWEEN',
				'type'    => 'NUMERIC',
			);
		} elseif ( null !== $min_price ) {
			$meta_query[] = array(
				'key'     => '_price',
				'value'   => $min_price,
				'compare' => '>=',
				'type'    => 'NUMERIC',
			);
		} elseif ( null !== $max_price && $max_price > 0 ) {
			$meta_query[] = array(
				'key'     => '_price',
				'value'   => $max_price,
				'compare' => '<=',
				'type'    => 'NUMERIC',
			);
		}
	}

	$query->set( 'tax_query', $tax_query );
	$query->set( 'meta_query', $meta_query );
}
add_action( 'pre_get_posts', 'noble_apply_attribute_filters_to_shop_query', 20 );

/**
 * Build gift box cart via AJAX.
 *
 * @return void
 */
function noble_ajax_build_gift_box_cart() {
	check_ajax_referer( 'noble_gift_box_builder', 'nonce' );

	if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
		wp_send_json_error(
			array(
				'message' => 'WooCommerce cart unavailable.',
			),
			400
		);
	}

	$raw_ids = isset( $_POST['product_ids'] ) ? (array) wp_unslash( $_POST['product_ids'] ) : array();
	$product_ids = array_values(
		array_filter(
			array_map( 'absint', $raw_ids )
		)
	);

	if ( empty( $product_ids ) ) {
		wp_send_json_error(
			array(
				'message' => 'No products selected.',
			),
			400
		);
	}

	$package_type  = isset( $_POST['package_type'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['package_type'] ) ) : 'standard';
	$gift_message  = isset( $_POST['gift_message'] ) ? sanitize_textarea_field( wp_unslash( (string) $_POST['gift_message'] ) ) : '';
	$allowed_pack  = array( 'standard', 'premium' );
	$package_type  = in_array( $package_type, $allowed_pack, true ) ? $package_type : 'standard';

	WC()->cart->empty_cart();

	foreach ( $product_ids as $product_id ) {
		$product = wc_get_product( $product_id );
		if ( ! $product || ! $product->is_purchasable() ) {
			continue;
		}
		WC()->cart->add_to_cart( $product_id, 1 );
	}

	if ( WC()->session ) {
		WC()->session->set( 'noble_gift_box_package', $package_type );
		WC()->session->set( 'noble_gift_box_message', $gift_message );
	}

	wp_send_json_success(
		array(
			'redirect' => wc_get_checkout_url(),
		)
	);
}
add_action( 'wp_ajax_noble_build_gift_box_cart', 'noble_ajax_build_gift_box_cart' );
add_action( 'wp_ajax_nopriv_noble_build_gift_box_cart', 'noble_ajax_build_gift_box_cart' );

/**
 * Add gift box premium package fee in cart/checkout.
 *
 * @param WC_Cart $cart Cart object.
 * @return void
 */
function noble_gift_box_package_fee( $cart ) {
	if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
		return;
	}
	if ( ! WC()->session ) {
		return;
	}

	$package = WC()->session->get( 'noble_gift_box_package', 'standard' );
	if ( 'premium' === $package ) {
		$cart->add_fee( 'بسته‌بندی پریمیوم', 50000 );
	}
}
add_action( 'woocommerce_cart_calculate_fees', 'noble_gift_box_package_fee', 20 );

/**
 * Force classic WooCommerce checkout shortcode rendering.
 *
 * If the Checkout page is built with Woo blocks, theme template overrides like
 * `woocommerce/checkout/form-checkout.php` are ignored. This ensures our custom
 * checkout template is always used.
 *
 * @param string $content Original page content.
 * @return string
 */
function noble_force_classic_checkout_content( $content ) {
	if ( is_admin() ) {
		return $content;
	}

	if ( ! function_exists( 'is_checkout' ) || ! is_checkout() ) {
		return $content;
	}

	if ( is_wc_endpoint_url( 'order-received' ) || is_wc_endpoint_url( 'order-pay' ) ) {
		return $content;
	}

	return do_shortcode( '[woocommerce_checkout]' );
}
add_filter( 'the_content', 'noble_force_classic_checkout_content', 20 );

/**
 * Whether the cart page request carries query args that must run on the real cart endpoint.
 *
 * @return bool
 */
function noble_cart_page_has_operational_query() {
	$keys = array(
		'remove_item',
		'undo_item',
		'add-to-cart',
		'removed_item',
		'remove_coupon',
		'order_again',
		'empty-cart',
		'download_file',
		'wc-ajax',
	);
	foreach ( $keys as $key ) {
		if ( isset( $_GET[ $key ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return true;
		}
	}
	return false;
}

/**
 * Send shoppers from the cart page to checkout step 1 (no standalone cart UX).
 *
 * @return void
 */
function noble_redirect_cart_page_to_checkout_step_one() {
	if ( is_admin() || wp_doing_ajax() ) {
		return;
	}
	if ( ! function_exists( 'is_cart' ) || ! is_cart() ) {
		return;
	}
	if ( noble_cart_page_has_operational_query() ) {
		return;
	}
	if ( ! function_exists( 'wc_get_checkout_url' ) ) {
		return;
	}

	wp_safe_redirect( add_query_arg( 'noble_step', 1, wc_get_checkout_url() ) );
	exit;
}
add_action( 'template_redirect', 'noble_redirect_cart_page_to_checkout_step_one', 18 );

/**
 * Drop legacy checkout "step 0" URLs: bare `/checkout/` or `noble_step=0` → step 1.
 *
 * Does not run on POST (e.g. final place order posts to `/checkout/` without query args).
 *
 * @return void
 */
function noble_redirect_checkout_to_step_one_entry() {
	if ( is_admin() || wp_doing_ajax() ) {
		return;
	}
	if ( ! function_exists( 'is_checkout' ) || ! is_checkout() ) {
		return;
	}
	if ( is_wc_endpoint_url( 'order-received' ) || is_wc_endpoint_url( 'order-pay' ) ) {
		return;
	}
	if ( isset( $_GET['wc-ajax'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		return;
	}
	// Let legacy qty URLs hit `noble_handle_checkout_qty_actions` first.
	if ( isset( $_GET['noble_qty_action'], $_GET['cart_item'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		return;
	}

	$method = isset( $_SERVER['REQUEST_METHOD'] ) ? strtoupper( (string) wp_unslash( $_SERVER['REQUEST_METHOD'] ) ) : 'GET';
	if ( 'GET' !== $method && 'HEAD' !== $method ) {
		return;
	}

	$step = isset( $_GET['noble_step'] ) ? absint( wp_unslash( $_GET['noble_step'] ) ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if ( $step >= 1 && $step <= 3 ) {
		return;
	}

	if ( ! function_exists( 'wc_get_checkout_url' ) ) {
		return;
	}

	wp_safe_redirect( add_query_arg( 'noble_step', 1, wc_get_checkout_url() ) );
	exit;
}
add_action( 'template_redirect', 'noble_redirect_checkout_to_step_one_entry', 19 );

/**
 * Point "cart" navigation to checkout step 1 (no dedicated cart page in the funnel).
 *
 * @param string $url Cart URL.
 * @return string
 */
function noble_filter_cart_url_to_checkout_step_one( $url ) {
	if ( ! function_exists( 'wc_get_checkout_url' ) ) {
		return $url;
	}
	return add_query_arg( 'noble_step', 1, wc_get_checkout_url() );
}
add_filter( 'woocommerce_get_cart_url', 'noble_filter_cart_url_to_checkout_step_one', 99 );

/**
 * Remove/undo URLs must hit the real cart page so WooCommerce can process them.
 *
 * @param string $url Remove URL built by WooCommerce (may use filtered cart URL).
 * @return string
 */
function noble_filter_remove_cart_item_url_real_cart( $url ) {
	$cart_page = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'cart' ) : '';
	if ( '' === $cart_page || '' === (string) $url ) {
		return $url;
	}
	$parts = wp_parse_url( $url );
	if ( empty( $parts['query'] ) ) {
		return $url;
	}
	parse_str( (string) $parts['query'], $query );
	if ( empty( $query['remove_item'] ) ) {
		return $url;
	}
	$key = wc_clean( (string) $query['remove_item'] );
	return wp_nonce_url( add_query_arg( 'remove_item', $key, $cart_page ), 'woocommerce-cart' );
}
add_filter( 'woocommerce_get_remove_url', 'noble_filter_remove_cart_item_url_real_cart', 99 );

/**
 * Undo remove URLs must hit the real cart page.
 *
 * @param string $url           Undo URL.
 * @param string $cart_item_key Cart item key.
 * @return string
 */
function noble_filter_undo_cart_item_url_real_cart( $url, $cart_item_key ) {
	$cart_page = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'cart' ) : '';
	if ( '' === $cart_page || '' === (string) $cart_item_key ) {
		return $url;
	}
	return wp_nonce_url( add_query_arg( 'undo_item', wc_clean( (string) $cart_item_key ), $cart_page ), 'woocommerce-cart' );
}
add_filter( 'woocommerce_get_undo_url', 'noble_filter_undo_cart_item_url_real_cart', 99, 2 );

/**
 * Handle quantity +/- actions from legacy checkout URLs (`noble_qty_action`).
 *
 * @return void
 */
function noble_handle_checkout_qty_actions() {
	if ( is_admin() || ! function_exists( 'is_checkout' ) || ! is_checkout() ) {
		return;
	}
	if ( ! isset( $_GET['noble_qty_action'], $_GET['cart_item'] ) || ! WC()->cart ) {
		return;
	}

	$action   = sanitize_key( wp_unslash( (string) $_GET['noble_qty_action'] ) );
	$cart_key = wc_clean( wp_unslash( (string) $_GET['cart_item'] ) );
	$item     = WC()->cart->get_cart_item( $cart_key );
	if ( ! $item ) {
		return;
	}

	$current_qty = (int) $item['quantity'];
	$new_qty     = $current_qty;
	if ( 'plus' === $action ) {
		$new_qty = $current_qty + 1;
	} elseif ( 'minus' === $action ) {
		$new_qty = max( 0, $current_qty - 1 );
	}

	WC()->cart->set_quantity( $cart_key, $new_qty, true );

	wp_safe_redirect( add_query_arg( 'noble_step', 1, wc_get_checkout_url() ) );
	exit;
}
add_action( 'template_redirect', 'noble_handle_checkout_qty_actions', 20 );

/**
 * Keep checkout clean from non-critical carried notices.
 *
 * WooCommerce stores notices in session and prints them on checkout by default.
 * We keep only error notices so the user sees actionable validation messages.
 *
 * @return void
 */
function noble_cleanup_checkout_notices() {
	if ( is_admin() || wp_doing_ajax() || ! function_exists( 'is_checkout' ) || ! is_checkout() ) {
		return;
	}

	if ( is_wc_endpoint_url( 'order-received' ) || is_wc_endpoint_url( 'order-pay' ) ) {
		return;
	}

	// Do not alter notices during checkout submit requests.
	if ( isset( $_SERVER['REQUEST_METHOD'] ) && 'POST' === strtoupper( (string) $_SERVER['REQUEST_METHOD'] ) ) {
		return;
	}

	if ( ! function_exists( 'wc_get_notices' ) || ! function_exists( 'wc_clear_notices' ) ) {
		return;
	}

	$error_notices = wc_get_notices( 'error' );
	wc_clear_notices();

	if ( ! empty( $error_notices ) ) {
		foreach ( $error_notices as $notice ) {
			wc_add_notice(
				isset( $notice['notice'] ) ? (string) $notice['notice'] : '',
				'error',
				isset( $notice['data'] ) && is_array( $notice['data'] ) ? $notice['data'] : array()
			);
		}
	}
}
add_action( 'template_redirect', 'noble_cleanup_checkout_notices', 30 );

/**
 * Remove default WooCommerce checkout coupon toggle notice.
 *
 * We render coupon input in our custom checkout layout.
 *
 * @return void
 */
function noble_remove_default_checkout_coupon_toggle() {
	remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );
}
add_action( 'wp', 'noble_remove_default_checkout_coupon_toggle', 20 );

/**
 * Make checkout email optional.
 *
 * @param array $fields Checkout fields.
 * @return array
 */
function noble_make_checkout_email_optional( $fields ) {
	if ( isset( $fields['billing']['billing_email'] ) ) {
		$fields['billing']['billing_email']['required'] = false;
	}
	return $fields;
}
add_filter( 'woocommerce_checkout_fields', 'noble_make_checkout_email_optional', 20 );

/**
 * Ensure checkout always has a safe email value.
 *
 * If user leaves email empty, generate a deterministic local placeholder
 * so order creation and notifications don't fail.
 *
 * @param array $data Posted checkout data.
 * @return array
 */
function noble_fill_empty_checkout_email( $data ) {
	$phone = isset( $data['billing_phone'] ) ? preg_replace( '/\D+/', '', (string) $data['billing_phone'] ) : '';
	if ( '' === $phone ) {
		$phone = (string) time();
	}

	$data['billing_email'] = 'guest-' . $phone . '@noble.local';
	return $data;
}
add_filter( 'woocommerce_checkout_posted_data', 'noble_fill_empty_checkout_email', 20 );

/**
 * Normalize checkout phone to numeric-only format.
 *
 * @param array $data Posted checkout data.
 * @return array
 */
function noble_normalize_checkout_phone( $data ) {
	if ( isset( $data['billing_phone'] ) ) {
		$data['billing_phone'] = substr( preg_replace( '/\D+/', '', (string) $data['billing_phone'] ), 0, 11 );
	}
	return $data;
}
add_filter( 'woocommerce_checkout_posted_data', 'noble_normalize_checkout_phone', 30 );

/**
 * Ensure WooCommerce add-to-cart AJAX nonce exists on the front page.
 *
 * Needed for custom homepage buttons that call `wc-ajax=add_to_cart`.
 *
 * @return void
 */
function noble_enqueue_home_add_to_cart_nonce() {
	if ( is_admin() || ! function_exists( 'is_front_page' ) || ! is_front_page() ) {
		return;
	}
	if ( ! function_exists( 'WC' ) || ! WC()->session ) {
		return;
	}

	wp_register_script( 'noble-home-atc', false, array( 'jquery' ), null, true );
	wp_enqueue_script( 'noble-home-atc' );

	wp_add_inline_script(
		'noble-home-atc',
		'window.nobleHomeAtcNonce = ' . wp_json_encode( wp_create_nonce( 'add-to-cart' ) ) . ';',
		'before'
	);
}
add_action( 'wp_enqueue_scripts', 'noble_enqueue_home_add_to_cart_nonce', 30 );

/**
 * Persist Step 1 checkout fields into session when moving to Step 2.
 *
 * @return void
 */
function noble_capture_step1_fields_to_session() {
	if ( is_admin() || ! function_exists( 'is_checkout' ) || ! is_checkout() ) {
		return;
	}
	if ( ! WC()->session ) {
		return;
	}
	if ( isset( $_SERVER['REQUEST_METHOD'] ) && 'POST' !== strtoupper( (string) $_SERVER['REQUEST_METHOD'] ) ) {
		return;
	}
	$is_step_two_post = isset( $_GET['noble_step'] ) && 2 === absint( $_GET['noble_step'] );
	$is_marked_submit = isset( $_POST['noble_step1_submit'] ) && '1' === (string) wp_unslash( $_POST['noble_step1_submit'] );
	if ( ! $is_step_two_post && ! $is_marked_submit ) {
		return;
	}

	$keys = array(
		'billing_first_name',
		'billing_last_name',
		'billing_phone',
		'billing_state',
		'billing_address_1',
		'billing_address_2',
		'billing_postcode',
		'billing_city',
		'noble_plaque',
	);
	$payload = array();
	foreach ( $keys as $k ) {
		if ( isset( $_POST[ $k ] ) ) {
			$payload[ $k ] = sanitize_text_field( wp_unslash( (string) $_POST[ $k ] ) );
		}
	}

	// Step 1 server-side validation (authoritative gate before Step 2).
	$first_name = isset( $payload['billing_first_name'] ) ? trim( (string) $payload['billing_first_name'] ) : '';
	$last_name  = isset( $payload['billing_last_name'] ) ? trim( (string) $payload['billing_last_name'] ) : '';
	$phone_raw  = isset( $payload['billing_phone'] ) ? (string) $payload['billing_phone'] : '';
	$state      = isset( $payload['billing_state'] ) ? trim( (string) $payload['billing_state'] ) : '';
	$address_1  = isset( $payload['billing_address_1'] ) ? trim( (string) $payload['billing_address_1'] ) : '';
	$city       = isset( $payload['billing_city'] ) ? trim( (string) $payload['billing_city'] ) : '';
	$postcode   = isset( $payload['billing_postcode'] ) ? (string) $payload['billing_postcode'] : '';

	$phone_digits = preg_replace( '/\D+/', '', $phone_raw );
	$postcode_digits = preg_replace( '/\D+/', '', $postcode );

	$has_errors = false;
	if ( '' === $first_name ) {
		wc_add_notice( 'لطفا نام را وارد کنید.', 'error' );
		$has_errors = true;
	}
	if ( '' === $last_name ) {
		wc_add_notice( 'لطفا نام خانوادگی را وارد کنید.', 'error' );
		$has_errors = true;
	}
	if ( '' === $phone_digits ) {
		wc_add_notice( 'لطفا شماره موبایل را وارد کنید.', 'error' );
		$has_errors = true;
	} elseif ( ! preg_match( '/^09\d{9}$/', $phone_digits ) ) {
		wc_add_notice( 'فرمت شماره موبایل صحیح نیست. مثال: 09123456789', 'error' );
		$has_errors = true;
	}
	if ( '' === $state ) {
		wc_add_notice( 'لطفا استان را انتخاب کنید.', 'error' );
		$has_errors = true;
	}
	if ( '' === $city ) {
		wc_add_notice( 'لطفا شهر را انتخاب کنید.', 'error' );
		$has_errors = true;
	}
	if ( '' === $address_1 ) {
		wc_add_notice( 'لطفا آدرس ارسال را وارد کنید.', 'error' );
		$has_errors = true;
	}
	if ( '' === $postcode_digits ) {
		wc_add_notice( 'لطفا کد پستی را وارد کنید.', 'error' );
		$has_errors = true;
	} elseif ( 10 !== strlen( $postcode_digits ) ) {
		wc_add_notice( 'کد پستی باید 10 رقم باشد.', 'error' );
		$has_errors = true;
	}

	// Keep normalized values for compatibility with WooCommerce defaults.
	$payload['billing_phone']    = $phone_digits;
	$payload['billing_postcode'] = $postcode_digits;
	WC()->session->set( 'noble_step1_checkout', $payload );

	if ( $has_errors ) {
		wp_safe_redirect( add_query_arg( 'noble_step', 1, wc_get_checkout_url() ) );
		exit;
	}

	// Avoid form resubmission; Step 2 will read from session.
	wp_safe_redirect( add_query_arg( 'noble_step', 2, wc_get_checkout_url() ) );
	exit;
}
add_action( 'template_redirect', 'noble_capture_step1_fields_to_session', 40 );

/**
 * Persist Step 2 shipping choice into session when moving to Step 3.
 *
 * @return void
 */
function noble_capture_step2_shipping_to_session() {
	if ( is_admin() || ! function_exists( 'is_checkout' ) || ! is_checkout() ) {
		return;
	}
	if ( ! isset( $_GET['noble_step'] ) || 3 !== absint( $_GET['noble_step'] ) ) {
		return;
	}
	if ( ! WC()->session ) {
		return;
	}
	if ( isset( $_SERVER['REQUEST_METHOD'] ) && 'POST' !== strtoupper( (string) $_SERVER['REQUEST_METHOD'] ) ) {
		return;
	}
	if ( ! isset( $_POST['noble_chosen_shipping'] ) || ! is_array( $_POST['noble_chosen_shipping'] ) ) {
		return;
	}

	$raw  = (array) wp_unslash( $_POST['noble_chosen_shipping'] );
	$next = array();
	foreach ( $raw as $pkg_idx => $rate_id ) {
		$idx = absint( $pkg_idx );
		$rid = wc_clean( (string) $rate_id );
		if ( '' === $rid ) {
			continue;
		}
		$next[ $idx ] = $rid;
	}
	if ( empty( $next ) ) {
		return;
	}

	ksort( $next );
	WC()->session->set( 'chosen_shipping_methods', array_values( $next ) );

	if ( WC()->cart ) {
		WC()->cart->calculate_totals();
	}

	// Avoid form resubmission; Step 3 will read chosen_shipping_methods from session.
	wp_safe_redirect( add_query_arg( 'noble_step', 3, wc_get_checkout_url() ) );
	exit;
}
add_action( 'template_redirect', 'noble_capture_step2_shipping_to_session', 45 );

/**
 * Provide values from our Step 1 session payload.
 *
 * @param mixed  $value Current value.
 * @param string $input Input key.
 * @return mixed
 */
function noble_step1_checkout_get_value( $value, $input ) {
	if ( ! WC()->session ) {
		return $value;
	}
	$payload = WC()->session->get( 'noble_step1_checkout', array() );
	if ( is_array( $payload ) && array_key_exists( $input, $payload ) && '' !== (string) $payload[ $input ] ) {
		return $payload[ $input ];
	}
	return $value;
}
add_filter( 'woocommerce_checkout_get_value', 'noble_step1_checkout_get_value', 20, 2 );
