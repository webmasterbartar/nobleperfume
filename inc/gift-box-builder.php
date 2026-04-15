<?php
/**
 * Gift box builder route (/gift-box-builder/) without requiring a WP page.
 *
 * @package noble-theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register rewrite rule for /gift-box-builder/.
 */
function noble_register_gift_box_builder_route() {
	add_rewrite_rule( '^gift-box-builder/?$', 'index.php?noble_gift_box_builder=1', 'top' );
}
add_action( 'init', 'noble_register_gift_box_builder_route' );

/**
 * Add query var for gift box builder.
 *
 * @param array<int,string> $vars Query vars.
 * @return array<int,string>
 */
function noble_add_gift_box_builder_query_var( $vars ) {
	$vars[] = 'noble_gift_box_builder';
	return $vars;
}
add_filter( 'query_vars', 'noble_add_gift_box_builder_query_var' );

/**
 * Load gift box builder template when route matches.
 *
 * @param string $template Template path.
 * @return string
 */
function noble_gift_box_builder_template_include( $template ) {
	$request_path = '';
	if ( isset( $_SERVER['REQUEST_URI'] ) ) {
		$request_path = trim( (string) wp_parse_url( wp_unslash( $_SERVER['REQUEST_URI'] ), PHP_URL_PATH ), '/' );
	}

	if ( 1 === (int) get_query_var( 'noble_gift_box_builder' ) || 'gift-box-builder' === $request_path ) {
		$page_template = get_template_directory() . '/page-gift-box-builder.php';
		if ( file_exists( $page_template ) ) {
			return $page_template;
		}
	}
	return $template;
}
add_filter( 'template_include', 'noble_gift_box_builder_template_include', 99 );

/**
 * Hard fallback loader for gift box builder route.
 * This guarantees rendering even if rewrite rules are stale.
 *
 * @return void
 */
function noble_gift_box_builder_template_redirect_fallback() {
	$request_path = '';
	if ( isset( $_SERVER['REQUEST_URI'] ) ) {
		$request_path = trim( (string) wp_parse_url( wp_unslash( $_SERVER['REQUEST_URI'] ), PHP_URL_PATH ), '/' );
	}
	if ( 'gift-box-builder' !== $request_path ) {
		return;
	}

	$page_template = get_template_directory() . '/page-gift-box-builder.php';
	if ( file_exists( $page_template ) ) {
		status_header( 200 );
		nocache_headers();
		include $page_template;
		exit;
	}
}
add_action( 'template_redirect', 'noble_gift_box_builder_template_redirect_fallback', 1 );

/**
 * Flush rewrite rules once after introducing route.
 */
function noble_gift_box_builder_maybe_flush_rewrite() {
	if ( get_option( 'noble_gift_box_builder_route_flushed' ) ) {
		return;
	}
	flush_rewrite_rules( false );
	update_option( 'noble_gift_box_builder_route_flushed', 1 );
}
add_action( 'admin_init', 'noble_gift_box_builder_maybe_flush_rewrite' );

