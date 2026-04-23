<?php
/**
 * Lightweight theme-level caching helpers (safe by default).
 *
 * @package noble-theme
 */
defined( 'ABSPATH' ) || exit;

/**
 * Is caching enabled?
 *
 * Safe default is OFF unless explicitly enabled.
 *
 * @return bool
 */
function noble_cache_is_enabled() {
	$enabled = defined( 'NOBLE_CACHE_ENABLED' ) ? (bool) NOBLE_CACHE_ENABLED : false;
	/**
	 * Filter: enable/disable caching.
	 *
	 * @param bool $enabled Whether caching is enabled.
	 */
	return (bool) apply_filters( 'noble_cache_enabled', $enabled );
}

/**
 * Should bypass caching for this request?
 *
 * @return bool
 */
function noble_cache_should_bypass() {
	if ( ! noble_cache_is_enabled() ) {
		return true;
	}

	if ( is_admin() ) {
		return true;
	}

	if ( is_user_logged_in() ) {
		return true;
	}

	if ( wp_doing_ajax() ) {
		return true;
	}

	if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
		return true;
	}

	if ( function_exists( 'wp_is_json_request' ) && wp_is_json_request() ) {
		return true;
	}

	if ( is_preview() ) {
		return true;
	}

	// WooCommerce sensitive areas: never cache.
	if ( function_exists( 'is_cart' ) && is_cart() ) {
		return true;
	}
	if ( function_exists( 'is_checkout' ) && is_checkout() ) {
		return true;
	}
	if ( function_exists( 'is_account_page' ) && is_account_page() ) {
		return true;
	}

	// If WooCommerce session/cart cookies exist, be conservative.
	if ( ! empty( $_COOKIE ) && is_array( $_COOKIE ) ) {
		if ( isset( $_COOKIE['woocommerce_items_in_cart'] ) || isset( $_COOKIE['woocommerce_cart_hash'] ) ) {
			return true;
		}
		foreach ( array_keys( $_COOKIE ) as $cookie_name ) {
			$cookie_name = (string) $cookie_name;
			if ( 0 === strpos( $cookie_name, 'wp_woocommerce_session_' ) ) {
				return true;
			}
		}
	}

	return (bool) apply_filters( 'noble_cache_should_bypass', false );
}

/**
 * Get cache group version (for invalidation).
 *
 * @param string $group Group name.
 * @return int
 */
function noble_cache_get_version( $group ) {
	$group  = sanitize_key( (string) $group );
	$option = 'noble_cache_v_' . $group;
	$ver    = (int) get_option( $option, 1 );
	return $ver > 0 ? $ver : 1;
}

/**
 * Bump cache group version (invalidates all keys in group).
 *
 * @param string $group Group name.
 * @return int New version.
 */
function noble_cache_bump_version( $group ) {
	$group  = sanitize_key( (string) $group );
	$option = 'noble_cache_v_' . $group;
	$ver    = (int) get_option( $option, 1 );
	$ver    = $ver > 0 ? $ver + 1 : 2;
	update_option( $option, $ver, false );
	return $ver;
}

/**
 * Build a safe transient key (hashed, short).
 *
 * @param string $group  Cache group (home/shop/giftbuilder/..).
 * @param string $suffix Specific key suffix.
 * @param array  $vary   Key/value pairs that affect output.
 * @return string
 */
function noble_cache_key( $group, $suffix, $vary = array() ) {
	$group  = sanitize_key( (string) $group );
	$suffix = sanitize_key( (string) $suffix );
	$vary   = is_array( $vary ) ? $vary : array();

	$locale   = function_exists( 'determine_locale' ) ? determine_locale() : get_locale();
	$currency = function_exists( 'get_woocommerce_currency' ) ? get_woocommerce_currency() : '';
	$version  = noble_cache_get_version( $group );

	ksort( $vary );

	$raw = wp_json_encode(
		array(
			'g' => $group,
			's' => $suffix,
			'v' => $version,
			'l' => (string) $locale,
			'c' => (string) $currency,
			'x' => $vary,
		)
	);
	$hash = md5( (string) $raw );

	// Keep under transient key length limits. Prefix helps with debugging.
	return 'nbl_' . $group . '_' . substr( $hash, 0, 20 );
}

/**
 * Remember a computed value in transient cache.
 *
 * @param string   $key Cache key.
 * @param int      $ttl TTL seconds.
 * @param callable $callback Computes value on cache miss.
 * @param string   $group Optional group name for debug headers.
 * @return mixed
 */
function noble_cache_remember( $key, $ttl, $callback, $group = '' ) {
	if ( noble_cache_should_bypass() ) {
		return call_user_func( $callback );
	}

	$key = (string) $key;
	$ttl = (int) $ttl;
	$ttl = $ttl > 0 ? $ttl : MINUTE_IN_SECONDS * 5;

	$cached = get_transient( $key );
	if ( false !== $cached ) {
		noble_cache_maybe_send_debug_header( $group, 'hit' );
		return $cached;
	}

	$value = call_user_func( $callback );
	if ( null === $value ) {
		noble_cache_maybe_send_debug_header( $group, 'miss' );
		return $value;
	}

	set_transient( $key, $value, $ttl );
	noble_cache_maybe_send_debug_header( $group, 'miss' );
	return $value;
}

/**
 * Optional lightweight debug header for admins on local/dev.
 *
 * @param string $group Cache group.
 * @param string $status hit|miss
 * @return void
 */
function noble_cache_maybe_send_debug_header( $group, $status ) {
	if ( headers_sent() ) {
		return;
	}
	$group  = sanitize_key( (string) $group );
	$status = sanitize_key( (string) $status );

	$should = false;
	if ( function_exists( 'wp_get_environment_type' ) ) {
		$env = wp_get_environment_type();
		$should = in_array( $env, array( 'local', 'development', 'staging' ), true );
	}
	$should = (bool) apply_filters( 'noble_cache_send_debug_header', $should );
	if ( ! $should ) {
		return;
	}

	header( 'X-Noble-Cache: ' . ( $group ? $group . ';' : '' ) . $status );
}

/**
 * Register cache invalidation hooks.
 *
 * Versions are bumped even if caching is currently disabled.
 *
 * @return void
 */
function noble_cache_register_invalidation_hooks() {
	// Product changes should invalidate home/shop/giftbuilder fragments.
	add_action(
		'save_post_product',
		function () {
			noble_cache_bump_version( 'home' );
			noble_cache_bump_version( 'shop' );
			noble_cache_bump_version( 'giftbuilder' );
		},
		20
	);

	add_action(
		'woocommerce_update_product',
		function () {
			noble_cache_bump_version( 'home' );
			noble_cache_bump_version( 'shop' );
			noble_cache_bump_version( 'giftbuilder' );
		},
		20
	);

	add_action(
		'woocommerce_product_set_stock',
		function () {
			noble_cache_bump_version( 'home' );
			noble_cache_bump_version( 'shop' );
			noble_cache_bump_version( 'giftbuilder' );
		},
		20
	);

	add_action(
		'woocommerce_product_set_stock_status',
		function () {
			noble_cache_bump_version( 'home' );
			noble_cache_bump_version( 'shop' );
			noble_cache_bump_version( 'giftbuilder' );
		},
		20
	);

	// Blog listing fragment.
	add_action(
		'save_post_post',
		function () {
			noble_cache_bump_version( 'home' );
		},
		20
	);

	// Term updates (product categories, attributes, etc).
	add_action(
		'created_term',
		function () {
			noble_cache_bump_version( 'home' );
			noble_cache_bump_version( 'shop' );
			noble_cache_bump_version( 'giftbuilder' );
		},
		20
	);
	add_action(
		'edited_term',
		function () {
			noble_cache_bump_version( 'home' );
			noble_cache_bump_version( 'shop' );
			noble_cache_bump_version( 'giftbuilder' );
		},
		20
	);
	add_action(
		'delete_term',
		function () {
			noble_cache_bump_version( 'home' );
			noble_cache_bump_version( 'shop' );
			noble_cache_bump_version( 'giftbuilder' );
		},
		20
	);

	// Relationship changes between objects and terms.
	add_action(
		'set_object_terms',
		function () {
			noble_cache_bump_version( 'home' );
			noble_cache_bump_version( 'shop' );
			noble_cache_bump_version( 'giftbuilder' );
		},
		20
	);
}

add_action( 'init', 'noble_cache_register_invalidation_hooks', 20 );

